<?php
session_start();
include_once("objetos/ClienteController.php");

$controller = new ClienteController();
$erro = "";
$sucesso = "";

if($_SERVER["REQUEST_METHOD"] === 'POST'){
    if (isset($_POST["cadastrar"])){
        $dados = $_POST["cliente"];
        
        if($controller->cadastrarCliente($dados)) {
            // Login automático após cadastro
            $usuario = $controller->logar($dados["email"], $dados["senha"]);
            if ($usuario) {
                $_SESSION["cliente_id"] = $usuario->id;
                $_SESSION["cliente_nome"] = $usuario->nome;
                $_SESSION["cliente_email"] = $usuario->email;
                
                // Se tiver itens no carrinho, volta para o carrinho para finalizar
                if (isset($_SESSION['carrinho']) && !empty($_SESSION['carrinho'])) {
                    header("Location: carrinho.php");
                    exit();
                }
            }
            $sucesso = "Cadastro realizado com sucesso!";
        } else {
            $erro = "Erro ao realizar cadastro. Tente novamente.";
        }
    }
}
?>

<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cadastro de Cliente - Loja Senac</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body class="login-body" style="padding: 40px 0;">

<div class="login-container" style="max-width: 550px;">
    <a href="index.php" style="text-decoration: none; color: inherit;">
        <h1 style="display: flex; align-items: center; justify-content: center; gap: 10px;">
            <i class="fa-solid fa-store" style="color: #2563eb;"></i> Loja Senac
        </h1>
    </a>
    <p style="margin-bottom: 24px; color: #64748b; font-weight: 500;">Crie sua conta de cliente</p>

    <?php if($sucesso): ?>
        <div class="success-message" style="background: #ecfdf5; color: #065f46; padding: 12px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #a7f3d0;">
            <?= $sucesso ?>
        </div>
    <?php endif; ?>

    <?php if($erro): ?>
        <div class="error-message" style="background: #fef2f2; color: #991b1b; padding: 12px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #fecaca;">
            <?= $erro ?>
        </div>
    <?php endif; ?>

    <form action="cadastro-cliente.php" method="post">
        <div class="form-group">
            <label><i class="fa-solid fa-user"></i> Nome Completo</label>
            <input type="text" name="cliente[nome]" placeholder="Seu nome completo" required>
        </div>
        
        <div class="form-group">
            <label><i class="fa-solid fa-envelope"></i> E-mail</label>
            <input type="email" name="cliente[email]" placeholder="seu@email.com" required>
        </div>

        <div class="form-group" style="display: flex; gap: 16px; flex-direction: row;">
            <div style="flex: 1;">
                <label><i class="fa-solid fa-id-card"></i> CPF</label>
                <input type="text" name="cliente[cpf]" placeholder="Apenas números" required>
            </div>
            <div style="flex: 1;">
                <label><i class="fa-solid fa-phone"></i> Telefone</label>
                <input type="text" name="cliente[telefone]" placeholder="(00) 00000-0000">
            </div>
        </div>
        
        <div class="form-group">
            <label><i class="fa-solid fa-location-dot"></i> Endereço para Entrega</label>
            <input type="text" name="cliente[endereco]" placeholder="Rua, número, bairro, cidade..." required>
        </div>
        
        <div class="form-group">
            <label><i class="fa-solid fa-lock"></i> Senha</label>
            <input type="password" name="cliente[senha]" placeholder="Crie uma senha segura" required>
        </div>
        
        <button type="submit" name="cadastrar" class="btn-login" style="background: #2563eb; color: white; border: none; padding: 14px; border-radius: 10px; width: 100%; font-weight: 600; cursor: pointer; margin-top: 10px;">
            Criar Minha Conta
        </button>
    </form>

    <div class="register-link" style="margin-top: 25px; text-align: center; color: #64748b;">
        Já possui uma conta? <a href="login-cliente.php" style="color: #2563eb; font-weight: 600; text-decoration: none;">Fazer Login</a>
        <br><br>
        <a href="index.php" style="color: #94a3b8; font-size: 0.9rem; text-decoration: none;">
            <i class="fa-solid fa-arrow-left"></i> Voltar para a Loja
        </a>
    </div>
</div>

</body>
</html>
