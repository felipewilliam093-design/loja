<?php
session_start();

if(!isset($_SESSION["funcionario_id"])) {
    header("Location: login.php");
    exit();
}

include_once ("objetos/ProdutosController.php");

$controller = new ProdutosController();

if($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["alterar"])){
    $id = $_GET["alterar"];
    $produtoAtual = $controller->localizarProduto($id);
}

if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["atualizar"])){
    $dados = $_POST["produtos"];
    $controller->atualizarProduto($dados);
    header("Location: index.php");
    exit();
}

if (!isset($produtoAtual)) {
    header("Location: index.php");
    exit();
}
?>

<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Atualizar Produto - Loja Senac</title>
    <link rel="stylesheet" href="style.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="login-body" style="padding: 40px 0;">

<div class="login-container" style="max-width: 500px;">
    <h1><i class="fa-solid fa-pen-to-square" style="color: #3b82f6;"></i> Atualizar Produto</h1>
    <p style="margin-bottom: 24px; color: #64748b; font-weight: 500;">Modifique os dados do produto abaixo</p>

    <form action="atualizar.php" method="post">
        <!-- ID Oculto para o update -->
        <input type="hidden" name="produtos[id]" value="<?= $produtoAtual->id ?>">

        <div class="form-group">
            <label>Nome do Produto</label>
            <input type="text" name="produtos[nome]" value="<?= $produtoAtual->nome ?>" required>
        </div>
        
        <div class="form-group" style="display: flex; gap: 16px; flex-direction: row;">
            <div style="flex: 1;">
                <label>Quantidade Estoque</label>
                <input type="number" name="produtos[quantidade]" value="<?= $produtoAtual->quantidade ?>" required>
            </div>
            <div style="flex: 1;">
                <label>Preço (R$)</label>
                <input type="text" name="produtos[preco]" value="<?= $produtoAtual->preco ?>" required>
            </div>
        </div>
        
        <div class="form-group">
            <label>Descrição</label>
            <input type="text" name="produtos[descricao]" value="<?= $produtoAtual->descricao ?>" required>
        </div>

        <button type="submit" name="atualizar" class="btn-login"><i class="fa-solid fa-floppy-disk"></i> Salvar Alterações</button>
    </form>

    <div class="register-link">
        <a href="index.php" style="color: #64748b;"><i class="fa-solid fa-arrow-left"></i> Voltar à Loja</a>
    </div>
</div>

</body>
</html>
