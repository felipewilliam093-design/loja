<?php
session_start();

if(!isset($_SESSION["funcionario_id"])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

include_once("objetos/ProdutosController.php");

$controller = new ProdutosController();
$produtoAtual = $controller->localizarProduto($_GET['id']);

if (!$produtoAtual) {
    echo "Produto não encontrado.";
    echo '<br><a href="index.php">Voltar</a>';
    exit();
}
?>

<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Visualizar Produto - Loja Senac</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .details-table { width: 100%; border-collapse: collapse; text-align: left; margin-bottom: 24px; }
        .details-table td { padding: 12px 0; border-bottom: 1px solid #e2e8f0; color: #334155; }
        .details-table td:first-child { font-weight: 600; color: #475569; width: 40%; }
        .product-image-container { text-align: center; margin-bottom: 20px; }
        .product-image-container img { border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); object-fit: cover; }
    </style>
</head>
<body class="login-body" style="padding: 40px 0;">

<div class="login-container" style="max-width: 500px;">
    <h1><i class="fa-solid fa-eye" style="color: #3b82f6;"></i> Detalhes do Produto</h1>
    <p style="margin-bottom: 24px; color: #64748b; font-weight: 500;">Visualização completa do item</p>

    <div class="product-image-container">
        <?php if(empty($produtoAtual->imagem)) : ?>
            <!-- Placeholder caso não exista imagem -->
            <div style="width: 150px; height: 150px; margin: 0 auto; background-color: #f1f5f9; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #cbd5e1; font-size: 40px;">
                <i class="fa-solid fa-image"></i>
            </div>
        <?php else : ?>
            <img src="uploads/<?= $produtoAtual->imagem; ?>" alt="Imagem do Produto" width="200" height="200">
        <?php endif; ?>
    </div>

    <table class="details-table">
        <tr>
            <td>ID do Produto</td>
            <td><span class="id-link">#<?= $produtoAtual->id; ?></span></td>
        </tr>
        <tr>
            <td>Nome</td>
            <td><strong><?= $produtoAtual->nome; ?></strong></td>
        </tr>
        <tr>
            <td>Quantidade</td>
            <td><span class="qty-tag"><i class="fa-solid fa-boxes-stacked"></i> <?= $produtoAtual->quantidade; ?> un.</span></td>
        </tr>
        <tr>
            <td>Preço</td>
            <td><span class="price-tag"><i class="fa-solid fa-tag"></i> R$ <?= number_format((float)$produtoAtual->preco, 2, ',', '.'); ?></span></td>
        </tr>
        <tr>
            <td>Descrição</td>
            <td><?= $produtoAtual->descricao; ?></td>
        </tr>
    </table>

    <a href="index.php" class="btn-login" style="display: inline-block; text-decoration: none;"><i class="fa-solid fa-arrow-left"></i> Voltar para a lista</a>
</div>

</body>
</html>
