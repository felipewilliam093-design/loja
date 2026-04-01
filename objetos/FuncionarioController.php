<?php

include_once("configs/database.php");
include_once("Funcionario.php");

Class FuncionarioController
{
    private $bd;
    private $funcionario;
    private $img_name;

    public function __construct()
    {
        $banco = new Database();
        $this->bd = $banco->conectar();
        $this->funcionario = new Funcionario($this->bd);
    }

    public function index(){
        return $this->funcionario->LerTodos();
    }

    public function inativos(){
        return $this->funcionario->LerInativos();
    }

    public function pesquisaFuncionario($id, $tipo){
        return $this->funcionario->pesquisaFuncionario($id, $tipo);
    }

    public function cadastrarFuncionario($dados, $arquivo)
    {
        $temArquivo = isset($arquivo['name']['fileToUpload'])
            && $arquivo['name']['fileToUpload'] !== ""
            && isset($arquivo['error']['fileToUpload'])
            && $arquivo['error']['fileToUpload'] === UPLOAD_ERR_OK;

        if ($temArquivo && !$this->upload($arquivo)) {
            return false;
        }

        if (!$temArquivo) {
            $this->img_name = null;
        }

        $this->funcionario->nome = $dados["nome"];
        $this->funcionario->cpf = $dados["cpf"];
        $this->funcionario->telefone = $dados["telefone"];
        $this->funcionario->login = $dados["login"];
        $this->funcionario->senha = $dados["senha"];
        $this->funcionario->tipo = $dados["tipo"] ?? 'comum';
        $this->funcionario->imagem = $this->img_name;

        if ($this->funcionario->cadastrar()) {
            return true;
        }
        return false;
    }

    public function excluirFuncionario($id){
        $this->funcionario->id = $id;

        if($this->funcionario->Excluir()){
            header("location: admin-funcionario.php");
        }
    }

    public function arquivarFuncionario($id){
        $this->funcionario->id = $id;
        if($this->funcionario->mudarStatus('inativo')){
            header("location: admin-funcionario.php");
        }
    }

    public function ativarFuncionario($id){
        $this->funcionario->id = $id;
        if($this->funcionario->mudarStatus('ativo')){
            header("location: admin-funcionario.php");
        }
    }

    public function atualizarFuncionario($dados){
        $this->funcionario->id = $dados["id"];
        $this->funcionario->nome = $dados["nome"];
        $this->funcionario->cpf = $dados["cpf"];
        $this->funcionario->telefone = $dados["telefone"];
        $this->funcionario->login = $dados["login"];
        if (!empty($dados["senha"])) {
            $this->funcionario->senha = $dados["senha"];
        } else {
            $this->funcionario->senha = null;
        }
        $this->funcionario->tipo = $dados["tipo"];

        if($this->funcionario->atualizar()){
            header("location: admin-funcionario.php");
        }
    }

    public function localizarFuncionario($id){
        return $this->funcionario->buscaFuncionario($id);
    }

    public function logar($login, $senha) {
        return $this->funcionario->realizarLogin($login, $senha);
    }

    public function upload($arquivo)
    {
        $target_dir = "uploads/";
        $uploadOk = 1;
        $target_file = $target_dir . $arquivo["name"]['fileToUpload'];
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $random_name = uniqid('img_', true) . '.' . pathinfo($arquivo['name']['fileToUpload'], PATHINFO_EXTENSION);
        $this->img_name = $random_name;
        $upload_file = $target_dir . $random_name;

        $check = getimagesize($arquivo['tmp_name']['fileToUpload']);

        if ($check !== false) {
            $uploadOk = 1;
        } else {
            $uploadOk = 0;
            echo "Erro ao tentar enviar o arquivo";
            die();
        }

        if (file_exists($upload_file)) {
            $uploadOk = 0;
        }

        if ($arquivo['size']['fileToUpload'] > 5000000) { // 5MB
            $uploadOk = 0;
            echo "Arquivo muito grande!";
        }

        if (
            $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif"
        ) {
            $uploadOk = 0;
        }

        if ($uploadOk == 0) {
            return false;
        } else {
            if (move_uploaded_file($arquivo['tmp_name']['fileToUpload'], $upload_file)) {
                return true;
            } else {
                return false;
            }
        }
        return false;
    }

}
