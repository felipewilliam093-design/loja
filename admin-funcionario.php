<?php
session_start();
include_once "objetos/FuncionarioController.php";

if(!isset($_SESSION["funcionario_tipo"]) || $_SESSION["funcionario_tipo"] !== 'admin') {
    header("Location: login.php");
    exit();
}

$controller = new FuncionarioController();
$funcionarios = $controller->index();
$a = null;

if($_SERVER["REQUEST_METHOD"] === "POST"){
    if(isset($_POST["pesquisar"])){
        $a = $controller->pesquisaFuncionario($_POST["pesquisar"], $_POST["tipo"]);
    }
}

if($_SERVER["REQUEST_METHOD"] === "GET"){
    if(isset($_GET["excluir"])){
        $controller->excluirFuncionario($_GET["excluir"]);
    }
}
?>

<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Administração de Funcionários</title>

<!--    Estilização da tabela-->
    <STYLE>
        table, tr, td{
            border: 1px solid black;
            border-collapse: collapse;
        }
    </STYLE>

</head>
<body>

<h1>Painel Admin - Funcionários</h1>
<p>Bem-vindo, <?= $_SESSION["funcionario_nome"] ?>!</p>

<a href="cadastro-funcionario.php">Cadastrar Novo Funcionário</a> | 
<a href="index.php">Sair para Loja</a> |
<a href="logout.php">Deslogar</a>

<h3>Pesquisar Funcionário</h3>

<form method="POST" action="admin-funcionario.php">
    <label>Pesquisar:</label>
    <input type="text" name="pesquisar">
    <select name="tipo">
        <option value="id">ID</option>
        <option value="nome">Nome</option>
    </select>

    <button>Pesquisar</button>
</form>

<?php if($a) : ?>
    <h4>Resultado da Pesquisa:</h4>
    <table>
        <tr>
            <td>ID</td>
            <td>Nome</td>
            <td>CPF</td>
            <td>Login</td>
            <td>Acesso</td>
        </tr>
        <tr>
            <td><?= $a->id; ?></td>
            <td><?= $a->nome; ?></td>
            <td><?= $a->cpf; ?></td>
            <td><?= $a->login; ?></td>
            <td><?= $a->tipo; ?></td>
        </tr>
    </table>
<?php endif; ?>

<h2>Funcionários Cadastrados</h2>

<table>
    <tr>
        <td>ID</td>
        <td>Nome</td>
        <td>CPF</td>
        <td>Telefone</td>
        <td>Login</td>
        <td>Acesso</td>
        <td>Ações</td>
    </tr>

    <?php if($funcionarios) : ?>
    <?php foreach($funcionarios as $func) : ?>

    <tr>
        <td><?= $func->id; ?></td>
        <td><?= $func->nome; ?></td>
        <td><?= $func->cpf; ?></td>
        <td><?= $func->telefone; ?></td>
        <td><?= $func->login; ?></td>
        <td><?= $func->tipo; ?></td>

        <td>
            <a href="atualizar-funcionario.php?alterar=<?= $func->id ?>">Alterar</a> |
            <a href="admin-funcionario.php?excluir=<?= $func->id ?>">Excluir</a>
        </td>
    </tr>

    <?php endforeach; ?>
    <?php endif; ?>

</table>
</body>
</html>
