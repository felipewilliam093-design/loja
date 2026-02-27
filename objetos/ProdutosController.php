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

    public function pesquisaProduto($id){
        return $this->produtos->pesquisaProduto($id);
    }

    public function cadastrarProduto($dados){

        $this->produtos->nome = $dados["nome"];
        $this->produtos->quantidade = $dados["quantidade"];
        $this->produtos->preco = $dados["preco"];
        $this->produtos->descricao = $dados["descricao"];

        if($this->produtos->Cadastrar()){
            header("location: index.php");
            exit();
        }
    }
}