<?php

Class Funcionario{
    public $id;
    public $nome;
    public $cpf;
    public $telefone;
    public $login;
    public $senha;
    public $imagem;
    public $tipo;
    public $bd;

    public function __construct($bd){
        $this->bd = $bd;
    }

    public function LerTodos(){
        $sql = "SELECT * FROM funcionario";
        $resultado = $this->bd->query($sql);
        $resultado->execute();
        return $resultado->fetchAll(PDO::FETCH_OBJ);
    }

    public function pesquisaFuncionario($pesquisa, $tipoPesquisa){
        if($tipoPesquisa == "id"){
            $sql = "SELECT * FROM funcionario WHERE id = :pesquisa";
        } else {
            $sql = "SELECT * FROM funcionario WHERE nome like :pesquisa";
            $pesquisa = "%".$pesquisa."%";
        }

        $resultado = $this->bd->prepare($sql);
        $resultado->bindParam(":pesquisa", $pesquisa);
        $resultado->execute();

        return $resultado->fetch(PDO::FETCH_OBJ);
    }

    public function cadastrar(){
        $sql = "INSERT INTO funcionario(nome, cpf, telefone, login, senha, imagem, tipo) VALUES(:nome, :cpf, :telefone, :login, :senha, :imagem, :tipo)";

        $stmt = $this->bd->prepare($sql);
        $stmt->bindParam(":nome", $this->nome, PDO::PARAM_STR);
        $stmt->bindParam(":cpf", $this->cpf, PDO::PARAM_STR);
        $stmt->bindParam(":telefone", $this->telefone, PDO::PARAM_STR);
        $stmt->bindParam(":login", $this->login, PDO::PARAM_STR);
        // Hashing password
        $senha_hash = password_hash($this->senha, PASSWORD_DEFAULT);
        $stmt->bindParam(":senha", $senha_hash, PDO::PARAM_STR);
        $stmt->bindParam(":imagem", $this->imagem, PDO::PARAM_STR);
        $stmt->bindParam(":tipo", $this->tipo, PDO::PARAM_STR);

        if($stmt->execute()){
            return true;
        } else {
            return false;
        }
    }

    public function Excluir(){
        $sql = "DELETE FROM funcionario WHERE id = :id";
        $stmt = $this->bd->prepare($sql);
        $stmt->bindParam(":id", $this->id, PDO::PARAM_INT);

        if($stmt->execute()){
            return true;
        }else{
            return false;
        }
    }

    public function atualizar(){
        if (!empty($this->senha)) {
            $sql = "UPDATE funcionario SET nome = :nome, cpf = :cpf, telefone = :telefone, login = :login, tipo = :tipo, senha = :senha WHERE id = :id";
            $stmt = $this->bd->prepare($sql);
            $senha_hash = password_hash($this->senha, PASSWORD_DEFAULT);
            $stmt->bindParam(":senha", $senha_hash, PDO::PARAM_STR);
        } else {
            $sql = "UPDATE funcionario SET nome = :nome, cpf = :cpf, telefone = :telefone, login = :login, tipo = :tipo WHERE id = :id";
            $stmt = $this->bd->prepare($sql);
        }

        $stmt->bindParam(":nome", $this->nome, PDO::PARAM_STR);
        $stmt->bindParam(":cpf", $this->cpf, PDO::PARAM_STR);
        $stmt->bindParam(":telefone", $this->telefone, PDO::PARAM_STR);
        $stmt->bindParam(":login", $this->login, PDO::PARAM_STR);
        $stmt->bindParam(":tipo", $this->tipo, PDO::PARAM_STR);
        $stmt->bindParam(":id", $this->id, PDO::PARAM_INT);

        if($stmt->execute()){
            return true;
        } else {
            return false;
        }
    }

    public function buscaFuncionario($id){
        $sql = "SELECT * FROM funcionario WHERE id = :id";
        $resultado = $this->bd->prepare($sql);
        $resultado->bindParam(":id", $id);
        $resultado->execute();

        return $resultado->fetch(PDO::FETCH_OBJ);
    }
    
    public function realizarLogin($login, $senha) {
        $sql = "SELECT * FROM funcionario WHERE login = :login";
        $stmt = $this->bd->prepare($sql);
        $stmt->bindParam(":login", $login, PDO::PARAM_STR);
        $stmt->execute();
        
        $usuario = $stmt->fetch(PDO::FETCH_OBJ);
        
        if ($usuario && password_verify($senha, $usuario->senha)) {
            return $usuario;
        }
        return false;
    }
}
