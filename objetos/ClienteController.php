<?php

include_once("configs/database.php");
include_once("Cliente.php");

Class ClienteController {
    private $bd;
    private $cliente;

    public function __construct() {
        $banco = new Database();
        $this->bd = $banco->conectar();
        $this->cliente = new Cliente($this->bd);
    }

    public function cadastrarCliente($dados) {
        $this->cliente->nome = $dados["nome"];
        $this->cliente->email = $dados["email"];
        $this->cliente->senha = $dados["senha"];
        $this->cliente->cpf = $dados["cpf"];
        $this->cliente->telefone = $dados["telefone"];
        $this->cliente->endereco = $dados["endereco"];

        return $this->cliente->cadastrar();
    }

    public function logar($email, $senha) {
        return $this->cliente->realizarLogin($email, $senha);
    }
}
