<?php
session_start();
include_once "objetos/FuncionarioController.php";

if(!isset($_SESSION["funcionario_tipo"]) || $_SESSION["funcionario_tipo"] !== 'admin') {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: admin-funcionario.php");
    exit();
}

$controller = new FuncionarioController();
$func = $controller->localizarFuncionario($_GET['id']);

if (!$func) {
    echo "Funcionário não encontrado.";
    echo '<br><a href="admin-funcionario.php">Voltar</a>';
    exit();
}
?>

<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Visualizar Funcionário - Admin</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .details-table { width: 100%; border-collapse: collapse; text-align: left; margin-bottom: 24px; }
        .details-table td { padding: 12px 0; border-bottom: 1px solid #e2e8f0; color: #334155; font-size: 15px; }
        .details-table td:first-child { font-weight: 600; color: #475569; width: 45%; }
        .profile-image-container { text-align: center; margin-bottom: 20px; }
        .profile-image-container img { border-radius: 50%; box-shadow: 0 4px 10px rgba(0,0,0,0.1); object-fit: cover; }
        .badge-admin { background-color: #fef08a; color: #854d0e; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 600; }
        .badge-comum { background-color: #e0f2fe; color: #0369a1; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 600; }
    </style>
</head>
<body class="login-body" style="padding: 40px 0;">

<div class="login-container" style="max-width: 500px;">
    <h1><i class="fa-solid fa-address-card" style="color: #3b82f6;"></i> Ficha Cadastral</h1>
    
    <div class="profile-image-container">
        <?php if($func->imagem): ?>
            <img src="uploads/<?= $func->imagem; ?>" alt="Foto de Perfil" width="120" height="120">
        <?php else: ?>
            <div style="width: 120px; height: 120px; margin: 0 auto; background-color: #f1f5f9; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #cbd5e1; font-size: 50px;">
                <i class="fa-solid fa-user"></i>
            </div>
        <?php endif; ?>
    </div>
    
    <h2 style="color: #1e293b; font-size: 22px; margin-bottom: 5px;"><?= $func->nome; ?></h2>
    <p style="color: #64748b; margin-bottom: 24px; font-size: 14px;">Usuário da Plataforma</p>

    <table class="details-table">
        <tr>
            <td>ID Registro</td>
            <td><span class="id-link">#<?= $func->id; ?></span></td>
        </tr>
        <tr>
            <td>CPF</td>
            <td><?= $func->cpf; ?></td>
        </tr>
        <tr>
            <td>Telefone</td>
            <td><?= $func->telefone ? $func->telefone : '<span style="color:#94a3b8;font-style:italic;">Não informado</span>'; ?></td>
        </tr>
        <tr>
            <td>Login de Acesso</td>
            <td><strong>@<?= $func->login; ?></strong></td>
        </tr>
        <tr>
            <td>Nível de Acesso</td>
            <td>
                <span class="<?= $func->tipo === 'admin' ? 'badge-admin' : 'badge-comum' ?>">
                    <i class="fa-solid <?= $func->tipo === 'admin' ? 'fa-crown' : 'fa-user' ?>"></i> 
                    <?= ucfirst($func->tipo); ?>
                </span>
            </td>
        </tr>
        <tr>
            <td>Status da Conta</td>
            <td>
                <?php $is_inativo = isset($func->status) && $func->status === 'inativo'; ?>
                <span style="font-weight: 600; color: <?= $is_inativo ? '#dc2626' : '#059669' ?>">
                    <i class="fa-solid <?= $is_inativo ? 'fa-lock' : 'fa-check-circle' ?>"></i>
                    <?= ucfirst($func->status ?? 'ativo'); ?>
                </span>
            </td>
        </tr>
    </table>

    <a href="admin-funcionario.php" class="btn-login" style="display: inline-block; text-decoration: none;"><i class="fa-solid fa-arrow-left"></i> Voltar ao Painel</a>
</div>

</body>
</html>
