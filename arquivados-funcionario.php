<?php
session_start();
include_once "objetos/FuncionarioController.php";

if(!isset($_SESSION["funcionario_tipo"]) || $_SESSION["funcionario_tipo"] !== 'admin') {
    header("Location: login.php");
    exit();
}

$controller = new FuncionarioController();
$funcionarios = $controller->inativos();

if($_SERVER["REQUEST_METHOD"] === "GET"){
    if(isset($_GET["ativar"])){
        $controller->ativarFuncionario($_GET["ativar"]);
        header("Location: arquivados-funcionario.php");
        exit();
    }
}
?>

<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Funcionários Arquivados - Admin</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
        .status-badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        .status-inativo { background-color: #fee2e2; color: #dc2626; }
        
        .action-icon.activate { color: #10b981; }
        .action-icon.activate:hover { color: #059669; background-color: #d1fae5; }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar">
    <div class="navbar-brand">
        <i class="fa-solid fa-box-archive" style="color: #f59e0b;"></i> Contas Arquivadas
    </div>
    <div class="nav-links">
        <a href="admin-funcionario.php" class="nav-link"><i class="fa-solid fa-arrow-left"></i> Voltar ao Painel Admin</a>
    </div>
</nav>

<div class="container">

    <!-- Table -->
    <div class="card">
        <h2 class="card-title"><i class="fa-solid fa-users-slash"></i> Lista de Funcionários Inativos</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>CPF</th>
                <th>Telefone</th>
                <th>Login</th>
                <th>Acesso</th>
                <th>Status</th>
                <th style="text-align: center;">Ações</th>
            </tr>

            <?php if($funcionarios) : ?>
            <?php foreach($funcionarios as $func) : ?>
            <tr>
                <td><a href="visualizar-funcionario.php?id=<?= $func->id; ?>" class="id-link">#<?= $func->id; ?></a></td>
                <td><strong><?= $func->nome; ?></strong></td>
                <td><?= $func->cpf; ?></td>
                <td><?= $func->telefone; ?></td>
                <td><?= $func->login; ?></td>
                <td><?= ucfirst($func->tipo); ?></td>
                <td>
                    <span class="status-badge status-inativo">Inativo</span>
                </td>
                <td>
                    <div class="actions" style="justify-content: center;">
                        <a href="arquivados-funcionario.php?ativar=<?= $func->id ?>" class="action-icon activate" title="Restaurar/Ativar Conta"><i class="fa-solid fa-user-check"></i></a>
                        <a href="visualizar-funcionario.php?id=<?= $func->id ?>" class="action-icon view" title="Visualizar Ficha"><i class="fa-solid fa-eye"></i></a>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php else: ?>
            <tr>
                <td colspan="8" style="text-align: center; padding: 40px; color: #64748b;">
                    <i class="fa-solid fa-folder-open" style="font-size: 32px; color: #cbd5e1; margin-bottom: 12px; display: block;"></i>
                    Nenhum funcionário arquivado encontrado no momento.
                </td>
            </tr>
            <?php endif; ?>
        </table>
    </div>

</div>

</body>
</html>
