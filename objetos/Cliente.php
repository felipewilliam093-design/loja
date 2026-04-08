<?php

Class Cliente {
    public $id;
    public $nome;
    public $email;
    public $senha;
    public $cpf;
    public $telefone;
    public $endereco;
    public $bd;

    public function __construct($bd) {
        $this->bd = $bd;
    }

    public function cadastrar() {
        $sql = "INSERT INTO cliente (nome, email, login, senha, cpf, telefone, endereco) VALUES (:nome, :email, :login, :senha, :cpf, :telefone, :endereco)";
        $stmt = $this->bd->prepare($sql);
        
        $senha_hash = password_hash($this->senha, PASSWORD_DEFAULT);
        
        $stmt->bindParam(":nome", $this->nome);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":login", $this->email); // Usando email como login por padrão
        $stmt->bindParam(":senha", $senha_hash);
        $stmt->bindParam(":cpf", $this->cpf);
        $stmt->bindParam(":telefone", $this->telefone);
        $stmt->bindParam(":endereco", $this->endereco);

        return $stmt->execute();
    }

    public function realizarLogin($email, $senha) {
        $sql = "SELECT * FROM cliente WHERE email = :email";
        $stmt = $this->bd->prepare($sql);
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        
        $cliente = $stmt->fetch(PDO::FETCH_OBJ);
        
        if ($cliente && password_verify($senha, $cliente->senha)) {
            return $cliente;
        }
        return false;
    }
}
