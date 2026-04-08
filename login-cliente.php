<?php
session_start();
include_once("objetos/ClienteController.php");

$erro = "";

if($_SERVER["REQUEST_METHOD"] === 'POST'){
    $controller = new ClienteController();
    
    if (isset($_POST["entrar"])){
        $email = $_POST["email"];
        $senha = $_POST["senha"];
        
        $usuario = $controller->logar($email, $senha);
        
        if ($usuario) {
            $_SESSION["cliente_id"] = $usuario->id;
            $_SESSION["cliente_nome"] = $usuario->nome;
            $_SESSION["cliente_email"] = $usuario->email;
            
            // Se houver carrinho, vai para checkout, senão para index
            if (isset($_SESSION['carrinho']) && !empty($_SESSION['carrinho'])) {
                header("Location: carrinho.php");
            } else {
                header("Location: index.php");
            }
            exit();
        } else {
            $erro = "E-mail ou senha inválidos!";
        }
    }
}
?>

<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login do Cliente - Loja Senac</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body class="login-body">

<div class="login-container">
    <a href="index.php" style="text-decoration: none; color: inherit;">
        <h1 style="display: flex; align-items: center; justify-content: center; gap: 10px;">
            <i class="fa-solid fa-store" style="color: #2563eb;"></i> Loja Senac
        </h1>
    </a>
    <p style="margin-bottom: 24px; color: #64748b; font-weight: 500;">Acesse sua conta para comprar</p>

    <?php if($erro): ?>
        <div class="error-message" style="background: #fef2f2; color: #991b1b; padding: 12px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #fecaca;">
            <?= $erro ?>
        </div>
    <?php endif; ?>

    <form action="login-cliente.php" method="post">
        <div class="form-group">
            <label><i class="fa-solid fa-envelope"></i> E-mail</label>
            <input type="email" name="email" placeholder="Digite seu e-mail" required>
        </div>
        
        <div class="form-group">
            <label><i class="fa-solid fa-lock"></i> Senha</label>
            <input type="password" name="senha" placeholder="Digite sua senha" required>
        </div>
        
        <button type="submit" name="entrar" class="btn-login" style="background: #2563eb; color: white; border: none; padding: 14px; border-radius: 10px; width: 100%; font-weight: 600; cursor: pointer;">
            Entrar Agora
        </button>
    </form>

    <div class="register-link" style="margin-top: 25px; text-align: center; color: #64748b;">
        Ainda não é cliente? <a href="cadastro-cliente.php" style="color: #2563eb; font-weight: 600; text-decoration: none;">Crie sua conta aqui</a>
        <br><br>
        <div style="border-top: 1px solid #e2e8f0; padding-top: 20px; margin-top: 10px;">
            <a href="login.php" style="color: #64748b; font-size: 0.85rem; text-decoration: none;">
                <i class="fa-solid fa-user-tie"></i> Área do Colaborador
            </a>
        </div>
    </div>
</div>

</body>
</html>
