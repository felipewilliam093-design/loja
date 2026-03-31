<?php
session_start();
include_once("objetos/FuncionarioController.php");

$controller = new FuncionarioController();
$erro = "";
$sucesso = "";

if($_SERVER["REQUEST_METHOD"] === 'POST'){
    if (isset($_POST["cadastrar"])){
        $dados = $_POST["funcionario"];
        
        // Se houver algum session indicando que é admin, pode autorizar a criação de admin.
        // Ou, se a tabela estiver vazia, criar como admin. Vamos deixar a escolha livre no form,
        // mas em um sistema real isso seria restrito.
        if (isset($_SESSION["funcionario_tipo"]) && $_SESSION["funcionario_tipo"] === 'admin') {
            $dados["tipo"] = $_POST["funcionario"]["tipo"];
        } else {
            // Se for cadastro externo ou comum, força como 'comum'
            if (!isset($dados["tipo"])) {
                $dados["tipo"] = 'comum';
            }
        }
        
        if($controller->cadastrarFuncionario($dados, $_FILES["funcionario"])) {
            $sucesso = "Funcionário cadastrado com sucesso!";
        } else {
            $erro = "Erro ao cadastrar funcionário.";
        }
    }
}
?>

<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cadastro de Funcionário</title>
</head>
<body>

<h1>Cadastro de Funcionário</h1>

<a href="login.php">Voltar ao Login</a> | 
<?php if(isset($_SESSION["funcionario_tipo"]) && $_SESSION["funcionario_tipo"] === 'admin'): ?>
<a href="admin-funcionario.php">Painel Admin</a>
<?php else: ?>
<a href="index.php">Ir para Loja</a>
<?php endif; ?>

<br><br>

<?php if($sucesso): ?>
    <p style="color:green;"><?= $sucesso ?></p>
<?php endif; ?>

<?php if($erro): ?>
    <p style="color:red;"><?= $erro ?></p>
<?php endif; ?>

<form action="cadastro-funcionario.php" method="post" enctype="multipart/form-data">
    <label>Nome</label><br>
    <input type="text" name="funcionario[nome]" required><br><br>
    
    <label>CPF</label><br>
    <input type="text" name="funcionario[cpf]" required><br><br>
    
    <label>Telefone</label><br>
    <input type="text" name="funcionario[telefone]"><br><br>
    
    <label>Login</label><br>
    <input type="text" name="funcionario[login]" required><br><br>
    
    <label>Senha</label><br>
    <input type="password" name="funcionario[senha]" required><br><br>
    
    <label>Tipo (Acesso)</label><br>
    <select name="funcionario[tipo]">
        <option value="comum">Comum</option>
        <?php 
        // Apenas para facilitar a demonstração/criação inicial, permitimos escolher Admin.
        // Em um ambiente restrito, só o admin logado veria essa opção.
        ?>
        <option value="admin">Administrador</option>
    </select><br><br>

    <label for="fileToUpload">Selecionar Foto de Perfil</label><br>
    <input type="file" name="funcionario[fileToUpload]" id="fileToUpload"><br><br>

    <button name="cadastrar">Cadastrar</button>
</form>

</body>
</html>
