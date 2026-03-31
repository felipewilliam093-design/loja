<?php
session_start();
include_once("objetos/FuncionarioController.php");

if(!isset($_SESSION["funcionario_tipo"]) || $_SESSION["funcionario_tipo"] !== 'admin') {
    header("Location: login.php");
    exit();
}

$controller = new FuncionarioController();

if($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["alterar"])){
    $id = $_GET["alterar"];
    $funcionarioAtual = $controller->localizarFuncionario($id);
}

if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["atualizar"])){
    $dados = $_POST["funcionario"];
    $controller->atualizarFuncionario($dados);
    header("Location: admin-funcionario.php");
    exit();
}

if (!isset($funcionarioAtual)) {
    header("Location: admin-funcionario.php");
    exit();
}
?>

<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Atualizar Funcionário</title>
</head>
<body>

<h1>Atualizar Funcionário</h1>

<a href="admin-funcionario.php">Voltar</a><br><br>

<form action="atualizar-funcionario.php" method="post">
    <!-- ID Oculto para o update -->
    <input type="hidden" name="funcionario[id]" value="<?= $funcionarioAtual->id ?>">

    <label>Nome</label><br>
    <input type="text" name="funcionario[nome]" value="<?= $funcionarioAtual->nome ?>" required><br><br>
    
    <label>CPF</label><br>
    <input type="text" name="funcionario[cpf]" value="<?= $funcionarioAtual->cpf ?>" required><br><br>
    
    <label>Telefone</label><br>
    <input type="text" name="funcionario[telefone]" value="<?= $funcionarioAtual->telefone ?>"><br><br>
    
    <label>Login</label><br>
    <input type="text" name="funcionario[login]" value="<?= $funcionarioAtual->login ?>" required><br><br>
    
    <label>Senha (Deixe em branco para não alterar)</label><br>
    <input type="password" name="funcionario[senha]"><br><br>

    <label>Tipo (Acesso)</label><br>
    <select name="funcionario[tipo]">
        <option value="comum" <?= $funcionarioAtual->tipo == 'comum' ? 'selected' : '' ?>>Comum</option>
        <option value="admin" <?= $funcionarioAtual->tipo == 'admin' ? 'selected' : '' ?>>Administrador</option>
    </select><br><br>

    <button name="atualizar">Atualizar</button>
</form>

</body>
</html>
