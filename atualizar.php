<?php
include_once ("objetos/ProdutosController.php");

$controller = new ProdutosController();

if($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['alterar'])){
    $a = $controller->localizarProduto($_GET['alterar']);
}elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['produtos'])){
    $a = $controller->atualizarProduto($_POST['produtos']);
}else{
    header("location: index.php");
}

?>

<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Atualização de Produtos</title>
</head>
<body>

<h1>Atualização de Produtos</h1>

<a href="index.php">Voltar</a>

<form action="atualizar.php" method="post">
    <input type="text" name="produtos[id]" value="<?= $a->id ?>" hidden>
    <label>Nome</label>
    <input type="text" name="produtos[nome]" value="<?= $a->nome ?>"><br><br>
    <label>Quantidade</label>
    <input type="number" name="produtos[quantidade]" value="<?= $a->quantidade ?>"><br><br>
    <label>Preco</label>
    <input type="text" name="produtos[preco]" value="<?= $a->preco ?>"><br><br>
    <label>Descricao</label>
    <input type="text" name="produtos[descricao]" value="<?= $a->descricao ?>"><br><br>

    <button name="atualizar">Atualizar</button>
</form>

</body>
</html>
