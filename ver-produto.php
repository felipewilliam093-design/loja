<?php
session_start();

if(!isset($_SESSION["funcionario_id"])) {
    header("Location: login.php");
    exit();
}

include_once("objetos/ProdutosController.php");

$controller = new ProdutosController();

if($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])){
    $a = $controller->localizarProduto($_GET['id']);
}

?>

<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Produto: <?= $a->nome?></title>
</head>
<body>

<h1><?= $a->nome?></h1>

<a href="index.php">Voltar</a>

<p><strong>Nome: </strong><?= $a->nome?></p>
<p><strong>Quantidade: </strong><?= $a->quantidade?></p>
<p><strong>Preco: </strong><?= $a->preco?></p>
<p><strong>Descricao: </strong><?= $a->descricao?></p>

<?php if($a->imagem == "") : ?>
    <img style="..." src="imagens/image-fail.jpg">
<?php else : ?>
    <img style="..." src="uploads/<?= $a->imagem; ?>">
<?php endif; ?>

</body>
</html>
