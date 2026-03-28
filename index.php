<?php

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
    <input typep="text" name="pesquisar">
    <select name="tipo">
        <option value="id">ID</option>
        <option value="nome">Nome</option>
    </select>

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
        <td>Descrição</td>
    </tr>

    <?php if($produtos) : ?>
    <?php foreach($produtos as $produto) : ?>

    <tr>
        <td><a href="ver-produto.php?id <?= $produto->id; ?>"><?= $produto->id; ?></a> </td>
        <td><?= $produto->nome; ?></td>
        <td><?= $produto->descricao; ?></td>

        <td><a href="atualizar.php?alterar=<?= $produto->id ?>">Alterar</a> </td>
        <td><a href="index.php?excluir=<?= $produto->id ?>">Excluir</a> </td>
        <td><a href="ver-produto.php?id=<?= $produto->id ?>">Visualizar</a> </td>

    </tr>

    <?php endforeach; ?>
    <?php endif; ?>

</table>
</body>

</html>