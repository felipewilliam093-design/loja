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

    public function pesquisaProduto($id){
        $sql = "SELECT * FROM produtos WHERE ID = :id";
        $resultado = $this->bd->prepare($sql);
        $resultado->bindParam(":id", $id);
        $resultado->execute();

        return $resultado->fetch(PDO::FETCH_OBJ);
    }

    public function cadastrar(){
        $sql = "INSERT INTO produtos(nome, quantidade, preco, descricao) VALUES(:nome, :quantidade, :preco, :descricao)";

        $stmt = $this->bd->prepare($sql);
        $stmt->bindParam(":nome", $this->nome, PDO::PARAM_STR);
        $stmt->bindParam(":quantidade", $this->quantidade, PDO::PARAM_STR);
        $stmt->bindParam(":preco", $this->preco, PDO::PARAM_STR);
        $stmt->bindParam(":descricao", $this->descricao, PDO::PARAM_STR);

        if($stmt->execute()){
            return true;
        } else {
            return false;
        }
    }
}