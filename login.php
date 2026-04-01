<?php
session_start();
include_once("objetos/FuncionarioController.php");

$erro = "";

if($_SERVER["REQUEST_METHOD"] === 'POST'){
    $controller = new FuncionarioController();
    
    if (isset($_POST["entrar"])){
        $login = $_POST["login"];
        $senha = $_POST["senha"];
        
        $usuario = $controller->logar($login, $senha);
        
        if ($usuario) {
            $_SESSION["funcionario_id"] = $usuario->id;
            $_SESSION["funcionario_nome"] = $usuario->nome;
            $_SESSION["funcionario_tipo"] = $usuario->tipo;
            
            header("Location: index.php");
            exit();
        } else {
            $erro = "Login ou senha inválidos!";
        }
    }
}
?>

<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Loja Senac</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="login-body">

<div class="login-container">
    <h1>Loja Senac</h1>

    <?php if($erro): ?>
        <div class="error-message"><?= $erro ?></div>
    <?php endif; ?>

    <form action="login.php" method="post">
        <div class="form-group">
            <label>Login</label>
            <input type="text" name="login" placeholder="Digite seu login" required>
        </div>
        
        <div class="form-group">
            <label>Senha</label>
            <input type="password" name="senha" placeholder="Digite sua senha" required>
        </div>
        
        <button type="submit" name="entrar" class="btn-login">Entrar</button>
    </form>

    <div class="register-link">
        Não tem uma conta? <a href="cadastro-funcionario.php">Cadastre-se</a>
    </div>
</div>

</body>
</html>
