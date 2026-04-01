<?php
session_start();

if(!isset($_SESSION["funcionario_id"])) {
    header("Location: login.php");
    exit();
}

include_once ("objetos/ProdutosController.php");

if($_SERVER["REQUEST_METHOD"] === 'POST'){
    $controller = new ProdutosController();

    if (isset($_POST["cadastrar"])){
        $a = $controller->cadastrarProduto($_POST["produtos"], $_FILES["produtos"]);
    }
}

?>

<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cadastro de Produto - Loja Senac</title>
    <link rel="stylesheet" href="style.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="login-body" style="padding: 40px 0;">

<div class="login-container" style="max-width: 500px;">
    <h1><i class="fa-solid fa-box-open" style="color: #3b82f6;"></i> Cadastrar Produto</h1>
    <p style="margin-bottom: 24px; color: #64748b; font-weight: 500;">Adicione um novo item ao catálogo</p>

    <?php if(isset($a) && $a): ?>
        <div class="success-message">Produto cadastrado com sucesso!</div>
    <?php elseif(isset($a) && !$a): ?>
        <div class="error-message">Erro ao cadastrar produto. Verifique os dados.</div>
    <?php endif; ?>

    <form action="cadastro.php" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label>Nome do Produto</label>
            <input type="text" name="produtos[nome]" placeholder="Ex: Camisa Social Branca" required>
        </div>
        
        <div class="form-group" style="display: flex; gap: 16px; flex-direction: row;">
            <div style="flex: 1;">
                <label>Quantidade (Estoque)</label>
                <input type="number" name="produtos[quantidade]" placeholder="Ex: 50" required>
            </div>
            <div style="flex: 1;">
                <label>Preço Unitário (R$)</label>
                <input type="text" name="produtos[preco]" placeholder="Ex: 99.90" required>
            </div>
        </div>
        
        <div class="form-group">
            <label>Descrição Detalhada</label>
            <input type="text" name="produtos[descricao]" placeholder="Detalhes de material, tamanho, marca..." required>
        </div>

        <div class="form-group">
            <label for="fileToUpload">Fotografia do Produto (Opcional)</label>
            <input type="file" name="produtos[fileToUpload]" id="fileToUpload" style="padding: 10px; border: 2px dashed #cbd5e1; background-color: transparent; border-radius: 8px;">
        </div>

        <button type="submit" name="cadastrar" class="btn-login"><i class="fa-solid fa-plus"></i> Inserir no Catálogo</button>
    </form>

    <div class="register-link">
        <a href="index.php" style="color: #64748b;"><i class="fa-solid fa-arrow-left"></i> Voltar à Loja Inicial</a>
    </div>
</div>

</body>
</html>
