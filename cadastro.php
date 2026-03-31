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
    <title>Cadastro de Produtos</title>
</head>
<body>

<h1>Cadastro de Produtos</h1>

<a href="index.php">Voltar</a>

<form action="cadastro.php" method="post" enctype="multipart/form-data">
    <label>Nome</label>
    <input type="text" name="produtos[nome]"><br><br>
    <label>Quantidade</label>
    <input type="number" name="produtos[quantidade]"><br><br>
    <label>Preco</label>
    <input type="text" name="produtos[preco]"><br><br>
    <label>Descricao</label>
    <input type="text" name="produtos[descricao]"><br><br>

    <label for="fileToUpload">Selecionar Foto</label>
    <input type="file" name="produtos[fileToUpload]" id="fileToUpload"><br><br>

    <button name="cadastrar">Cadastrar</button>
</form>

</body>
</html>
