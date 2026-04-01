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
    <title>Cadastro - Loja Senac</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="login-body" style="padding: 40px 0;">

<div class="login-container" style="max-width: 500px;">
    <h1>Loja Senac</h1>
    <p style="margin-bottom: 24px; color: #64748b; font-weight: 500;">Crie ou Registre uma Conta</p>

    <?php if($sucesso): ?>
        <div class="success-message"><?= $sucesso ?></div>
    <?php endif; ?>

    <?php if($erro): ?>
        <div class="error-message"><?= $erro ?></div>
    <?php endif; ?>

    <form action="cadastro-funcionario.php" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label>Nome Completo</label>
            <input type="text" name="funcionario[nome]" placeholder="Seu nome completo" required>
        </div>
        
        <div class="form-group" style="display: flex; gap: 16px; flex-direction: row;">
            <div style="flex: 1;">
                <label>CPF</label>
                <input type="text" name="funcionario[cpf]" placeholder="Apenas números" required>
            </div>
            <div style="flex: 1;">
                <label>Telefone</label>
                <input type="text" name="funcionario[telefone]" placeholder="Seu número">
            </div>
        </div>
        
        <div class="form-group">
            <label>Login de Usuário</label>
            <input type="text" name="funcionario[login]" placeholder="Defina seu login" required>
        </div>
        
        <div class="form-group">
            <label>Senha</label>
            <input type="password" name="funcionario[senha]" placeholder="Digite sua senha secreta" required>
        </div>
        
        <div class="form-group">
            <label>Nível de Acesso</label>
            <select name="funcionario[tipo]">
                <option value="comum">Comum (Acesso Padrão)</option>
                <option value="admin">Administrador (Total)</option>
            </select>
        </div>

        <div class="form-group">
            <label for="fileToUpload">Foto de Perfil (Opcional)</label>
            <input type="file" name="funcionario[fileToUpload]" id="fileToUpload" style="padding: 10px; border: 2px dashed #cbd5e1; background-color: transparent;">
        </div>

        <button type="submit" name="cadastrar" class="btn-login">Criar Conta</button>
    </form>

    <div class="register-link">
        Já possui uma conta? <a href="login.php">Fazer Login</a>
        <br><br>
        <?php if(isset($_SESSION["funcionario_tipo"]) && $_SESSION["funcionario_tipo"] === 'admin'): ?>
            <a href="admin-funcionario.php" style="color: #64748b;">Retornar ao Painel Admin</a>
        <?php else: ?>
            <a href="index.php" style="color: #64748b;">Retornar à Loja Inicial</a>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
