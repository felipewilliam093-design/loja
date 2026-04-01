<?php
session_start();

if(!isset($_SESSION["funcionario_id"])) {
    header("Location: login.php");
    exit();
}

include_once "objetos\ProdutosController.php";

$controller = new ProdutosController();
$produtos = $controller->index();
global $produtos;
$a = null;

if($_SERVER["REQUEST_METHOD"] === "POST"){
    if(isset($_POST["pesquisar"])){
        $a = $controller->pesquisaProduto($_POST["pesquisar"], $_POST["tipo"]);
    }
}

if($_SERVER["REQUEST_METHOD"] === "GET"){
    if(isset($_GET["excluir"])){
        $a = $controller->excluirProduto($_GET["excluir"]);

    }
}

if($_SERVER["REQUEST_METHOD"] === "POST"){
    if(isset($_GET["alterar"])){
        $a = $controller->atualizarProduto($_GET["alterar"]);
    }
}

?>

<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Loja Senac - Produtos</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- Navbar -->
<nav class="navbar">
    <div class="navbar-brand">
        <i class="fa-solid fa-store"></i> Loja Senac
    </div>
    <div class="nav-links">
        <span class="user-greeting">
            <i class="fa-regular fa-circle-user"></i> Olá, <?= $_SESSION["funcionario_nome"] ?? 'Funcionario' ?>
        </span>
        <?php if(isset($_SESSION["funcionario_tipo"]) && $_SESSION["funcionario_tipo"] === 'admin'): ?>
            <a href="admin-funcionario.php" class="nav-link"><i class="fa-solid fa-users-gear"></i> Painel Admin</a>
        <?php endif; ?>
        <a href="logout.php" class="nav-link danger"><i class="fa-solid fa-arrow-right-from-bracket"></i> Sair</a>
    </div>
</nav>

<div class="container">
    
    <div class="top-actions">
        <!-- Search Block inside top area -->
        <form method="POST" action="index.php" class="search-card">
            <h3><i class="fa-solid fa-magnifying-glass"></i> Pesquisar Produtos</h3>
            <div class="form-group">
                <input type="text" name="pesquisar" placeholder="Termo de busca...">
            </div>
            <div class="form-group">
                <select name="tipo">
                    <option value="id">Buscar por ID</option>
                    <option value="nome">Buscar por Nome</option>
                </select>
            </div>
            <button class="btn"><i class="fa-solid fa-filter"></i> Buscar</button>
        </form>

        <a href="cadastro.php" class="btn"><i class="fa-solid fa-plus"></i> Novo Produto</a>
    </div>

    <?php if($a) : ?>
    <!-- Search Results -->
    <div class="card">
        <h2 class="card-title"><i class="fa-solid fa-list-check"></i> Resultado da Pesquisa</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Quantidade</th>
                <th>Preço</th>
            </tr>
            <tr>
                <td><span class="id-link">#<?= $a->id; ?></span></td>
                <td><strong><?= $a->nome; ?></strong></td>
                <td><span class="qty-tag"><i class="fa-solid fa-boxes-stacked"></i> <?= $a->quantidade; ?> un.</span></td>
                <td><span class="price-tag"><i class="fa-solid fa-tag"></i> R$ <?= number_format((float)$a->preco, 2, ',', '.'); ?></span></td>
            </tr>
        </table>
    </div>
    <?php endif; ?>

    <!-- Main Table -->
    <div class="card">
        <h2 class="card-title"><i class="fa-solid fa-tags"></i> Roupas & Produtos Cadastrados</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Descrição</th>
                <th>Quantidade</th>
                <th>Preço</th>
                <th style="text-align: center;">Ações</th>
            </tr>

            <?php if($produtos) : ?>
            <?php foreach($produtos as $produto) : ?>
            <tr>
                <td><a href="ver-produto.php?id=<?= $produto->id; ?>" class="id-link">#<?= $produto->id; ?></a></td>
                <td><strong><?= $produto->nome; ?></strong></td>
                <td><?= $produto->descricao; ?></td>
                <td><span class="qty-tag"><i class="fa-solid fa-boxes-stacked"></i> <?= $produto->quantidade; ?> un.</span></td>
                <td><span class="price-tag"><i class="fa-solid fa-tag"></i> R$ <?= number_format((float)$produto->preco, 2, ',', '.'); ?></span></td>
                <td>
                    <div class="actions" style="justify-content: center;">
                        <a href="ver-produto.php?id=<?= $produto->id ?>" class="action-icon view" title="Visualizar"><i class="fa-solid fa-eye"></i></a>
                        <a href="atualizar.php?alterar=<?= $produto->id ?>" class="action-icon edit" title="Alterar"><i class="fa-solid fa-pen-to-square"></i></a>
                        <a href="index.php?excluir=<?= $produto->id ?>" class="action-icon delete" title="Excluir" onclick="return confirm('Tem certeza que deseja excluir este produto?');"><i class="fa-solid fa-trash-can"></i></a>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php else: ?>
            <tr>
                <td colspan="6" style="text-align: center; padding: 40px; color: #64748b;">Nenhum produto cadastrado no momento.</td>
            </tr>
            <?php endif; ?>
        </table>
    </div>

</div>

</body>
</html>