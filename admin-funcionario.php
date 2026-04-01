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
    if(isset($_GET["arquivar"])){
        $controller->arquivarFuncionario($_GET["arquivar"]);
    }
    if(isset($_GET["ativar"])){
        $controller->ativarFuncionario($_GET["ativar"]);
    }
}
?>

<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Painel Admin - Senac</title>
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
        .status-ativo { background-color: #d1fae5; color: #059669; }
        .status-inativo { background-color: #fee2e2; color: #dc2626; }
        
        /* Action icons for employees */
        .action-icon.archive { color: #f97316; }
        .action-icon.archive:hover { color: #ea580c; background-color: #ffedd5; }
        .action-icon.activate { color: #10b981; }
        .action-icon.activate:hover { color: #059669; background-color: #d1fae5; }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar">
    <div class="navbar-brand">
        <i class="fa-solid fa-users-gear"></i> Painel Admin
    </div>
    <div class="nav-links">
        <span class="user-greeting">
            <i class="fa-solid fa-crown"></i> Admin: <?= $_SESSION["funcionario_nome"] ?>
        </span>
        <a href="index.php" class="nav-link"><i class="fa-solid fa-store"></i> Ir para Loja</a>
        <a href="logout.php" class="nav-link danger"><i class="fa-solid fa-arrow-right-from-bracket"></i> Sair</a>
    </div>
</nav>

<div class="container">
    
    <div class="top-actions">
        <!-- Search Block -->
        <form method="POST" action="admin-funcionario.php" class="search-card">
            <h3><i class="fa-solid fa-magnifying-glass"></i> Pesquisar Funcionário</h3>
            <div class="form-group">
                <input type="text" name="pesquisar" placeholder="Busca...">
            </div>
            <div class="form-group">
                <select name="tipo">
                    <option value="id">Buscar por ID</option>
                    <option value="nome">Buscar por Nome</option>
                </select>
            </div>
            <button class="btn"><i class="fa-solid fa-filter"></i> Buscar</button>
        </form>

        <div style="display: flex; gap: 10px;">
            <a href="arquivados-funcionario.php" class="btn" style="background-color: #f59e0b;"><i class="fa-solid fa-box-archive"></i> Ver Arquivados</a>
            <a href="cadastro-funcionario.php" class="btn"><i class="fa-solid fa-user-plus"></i> Novo Funcionário</a>
        </div>
    </div>

    <?php if($a) : ?>
    <!-- Search Results -->
    <div class="card">
        <h2 class="card-title"><i class="fa-solid fa-list-check"></i> Resultado da Pesquisa</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>CPF</th>
                <th>Login</th>
                <th>Acesso</th>
                <th>Status</th>
            </tr>
            <tr>
                <td><span class="id-link">#<?= $a->id; ?></span></td>
                <td><strong><?= $a->nome; ?></strong></td>
                <td><?= $a->cpf; ?></td>
                <td><?= $a->login; ?></td>
                <td><?= ucfirst($a->tipo); ?></td>
                <td>
                    <span class="status-badge <?= (isset($a->status) && $a->status === 'inativo') ? 'status-inativo' : 'status-ativo' ?>">
                        <?= ucfirst($a->status ?? 'ativo'); ?>
                    </span>
                </td>
            </tr>
        </table>
    </div>
    <?php endif; ?>

    <!-- Main Table -->
    <div class="card">
        <h2 class="card-title"><i class="fa-solid fa-users"></i> Gestão de Funcionários Cadastrados</h2>
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
                    <span class="status-badge <?= (isset($func->status) && $func->status === 'inativo') ? 'status-inativo' : 'status-ativo' ?>">
                        <?= ucfirst($func->status ?? 'ativo'); ?>
                    </span>
                </td>
                <td>
                    <div class="actions" style="justify-content: center;">
                        <?php if (isset($func->status) && $func->status === 'inativo'): ?>
                            <a href="admin-funcionario.php?ativar=<?= $func->id ?>" class="action-icon activate" title="Ativar Conta"><i class="fa-solid fa-user-check"></i></a>
                        <?php else: ?>
                            <a href="admin-funcionario.php?arquivar=<?= $func->id ?>" class="action-icon archive" title="Arquivar Conta"><i class="fa-solid fa-box-archive"></i></a>
                        <?php endif; ?>
                        
                        <a href="visualizar-funcionario.php?id=<?= $func->id ?>" class="action-icon view" title="Visualizar"><i class="fa-solid fa-eye"></i></a>
                        <a href="atualizar-funcionario.php?alterar=<?= $func->id ?>" class="action-icon edit" title="Alterar"><i class="fa-solid fa-pen-to-square"></i></a>
                        <a href="admin-funcionario.php?excluir=<?= $func->id ?>" class="action-icon delete" title="Excluir Permanentemente" onclick="return confirm('ATENÇÃO: Tem certeza que deseja excluir permanentemente o funcionário <?= htmlspecialchars($func->nome, ENT_QUOTES) ?>? Esta ação não pode ser desfeita.');"><i class="fa-solid fa-trash-can"></i></a>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php else: ?>
            <tr>
                <td colspan="8" style="text-align: center; padding: 40px; color: #64748b;">Nenhum funcionário cadastrado.</td>
            </tr>
            <?php endif; ?>
        </table>
    </div>

</div>

</body>
</html>
