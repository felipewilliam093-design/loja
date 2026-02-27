<?php

include_once "objetos\ProdutosController.php";

$controller = new ProdutosController();
$produtos = $controller->index();
global $produtos;

if($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["pesquisar"])) {
        $a = $controller->pesquisaProduto($_POST["pesquisar"]);
    }
}

?>

<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Loja</title>

<!--    Estilização da tabela-->

    <STYLE>
        table, tr, td{
            border: 1px solid black;
            border-collapse: collapse;
        }
    </STYLE>

</head>
<body>

<h1>Loja Senac</h1>

<!--Link da página Cadastro de Produos-->
<a href="cadastro.php">Cadastrar Produtos</a>

<h3>Pesquisar Produtos</h3>

<form method="POST" action="index.php">
    <label>ID</label>
    <input typep="number" name="pesquisar">
    <button>Pesquisar</button>
</form>

<table>
    <tr>
        <td>ID</td>
        <td>Nome</td>
    </tr>

    <?php if($a) : ?>
        <!--        <?php //foreach($a as $produtos) : ?> -->
        <tr>
            <td><?= $a->id; ?></td>
            <td><?= $a->nome; ?></td>
        </tr>
        <!--        --><?php //endforeach; ?>
    <?php endif; ?>

</table>

<h2>Produtos Cadastrados</h2>

<table>
    <tr>
        <td>ID</td>
        <td>Nome</td>
        <td>Quantidade</td>
        <td>Preço</td>
        <td>Descrição</td>
    </tr>

    <?php if($produtos) : ?>
    <?php foreach($produtos as $produto) : ?>

    <tr>
        <td><?php echo $produto->id; ?></td>
        <td><?php echo $produto->nome; ?></td>
        <td><?php echo $produto->quantidade; ?></td>
        <td><?php echo $produto->preco; ?></td>
        <td><?php echo $produto->descricao; ?></td>
    </tr>

    <?php endforeach; ?>
    <?php endif; ?>
</table>
</body>

</html>