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
    <title>Atualizar Funcionário - Admin</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="login-body" style="padding: 40px 0;">

<div class="login-container" style="max-width: 500px;">
    <h1><i class="fa-solid fa-user-pen" style="color: #3b82f6;"></i> Atualizar Funcionário</h1>
    <p style="margin-bottom: 24px; color: #64748b; font-weight: 500;">Alteração de dados de cadastro</p>

    <form action="atualizar-funcionario.php" method="post">
        <!-- ID Oculto para o update -->
        <input type="hidden" name="funcionario[id]" value="<?= $funcionarioAtual->id ?>">

        <div class="form-group">
            <label>Nome Completo</label>
            <input type="text" name="funcionario[nome]" value="<?= $funcionarioAtual->nome ?>" required>
        </div>
        
        <div class="form-group" style="display: flex; gap: 16px; flex-direction: row;">
            <div style="flex: 1;">
                <label>CPF</label>
                <input type="text" name="funcionario[cpf]" value="<?= $funcionarioAtual->cpf ?>" required>
            </div>
            <div style="flex: 1;">
                <label>Telefone</label>
                <input type="text" name="funcionario[telefone]" value="<?= $funcionarioAtual->telefone ?>">
            </div>
        </div>
        
        <div class="form-group">
            <label>Login de Acesso</label>
            <input type="text" name="funcionario[login]" value="<?= $funcionarioAtual->login ?>" required>
        </div>
        
        <div class="form-group">
            <label>Nova Senha (opcional)</label>
            <input type="password" name="funcionario[senha]" placeholder="Em branco p/ manter atual">
        </div>

        <div class="form-group">
            <label>Nível de Acesso (Tipo)</label>
            <select name="funcionario[tipo]">
                <option value="comum" <?= $funcionarioAtual->tipo == 'comum' ? 'selected' : '' ?>>Comum (Acesso Padrão)</option>
                <option value="admin" <?= $funcionarioAtual->tipo == 'admin' ? 'selected' : '' ?>>Administrador (Total)</option>
            </select>
        </div>

        <button type="submit" name="atualizar" class="btn-login"><i class="fa-solid fa-floppy-disk"></i> Atualizar Funcionário</button>
    </form>

    <div class="register-link">
        <a href="admin-funcionario.php" style="color: #64748b;"><i class="fa-solid fa-arrow-left"></i> Voltar ao Painel</a>
    </div>
</div>

</body>
</html>
