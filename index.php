<?php

include_once "configs/database.php";

$banco = new Database();
$bd = $banco->conectar();

if ($bd) {
    $sql = "select * from produtos";
    $resultado = $bd->query($sql);
    $resultado->execute();
    $resultado = $resultado->fetchAll(PDO::FETCH_ASSOC);

    foreach ($resultado as $produto) {
        echo "ID: " . $produto['id'] . "<br>";
        echo "Nome: " . $produto['nome'] . "<br>";
        echo "Quantidade: " . $produto['quantidade'] . "<br>";
        echo "Descrição: " . $produto['descricao'] . "<br>";
        echo "Preço: " . $produto['preco'] . "<br>";
    }
} else {
    echo "Falha ao conectar com o banco de dados";
}