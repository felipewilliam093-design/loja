<?php

include_once("configs/database.php");
include_once("produtos.php");

Class ProdutosController
{
    private $bd;
    private $produtos;

    public function __construct()
    {
        $banco = new Database();
        $this->bd = $banco->conectar();
        $this->produtos = new Produtos($this->bd);
    }

    public function index(){
        return $this->produtos->LerTodos();
    }
}