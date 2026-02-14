<?php

Class Produtos{
    public $id;
    public $nome;
    public $quantidade;
    public $preco;
    public $descricao;
    public $cadastro;
    public $img;
    public $bd;

    public function __construct($bd){
        $this->bd = $bd;
    }

    public function LerTodos(){
        $sql = "SELECT * FROM produtos";
        $resultado = $this->bd->query($sql);
        $resultado->execute();
        return $resultado->fetchAll(PDO::FETCH_OBJ);
    }
}