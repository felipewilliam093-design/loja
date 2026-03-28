<?php

include_once("configs/database.php");
include_once("produtos.php");

Class ProdutosController
{
    private $bd;
    private $produtos;
    private $img_name;

    public function __construct()
    {
        $banco = new Database();
        $this->bd = $banco->conectar();
        $this->produtos = new Produtos($this->bd);
    }

    public function index(){
        return $this->produtos->LerTodos();
    }

    public function pesquisaProduto($id, $tipo){
        return $this->produtos->pesquisaProduto($id, $tipo);
    }

    public function cadastrarProduto($dados, $arquivo)
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

        $this->produtos->nome = $dados["nome"];
        $this->produtos->quantidade = $dados["quantidade"];

        $preco = str_replace(',', '.', $dados["preco"]);
        $this->produtos->preco = $preco;

        $this->produtos->descricao = $dados["descricao"];
        $this->produtos->img = $this->img_name;

        if ($this->produtos->Cadastrar()) {
            header("location: index.php");
            exit();
        }
    }

    public function excluirProduto($id){
        $this->produtos->id = $id;

        if($this->produtos->Excluir()){
            header("location: index.php");
        }
    }

    public function atualizarProduto($dados){
        $this->produtos->id = $dados["id"];
        $this->produtos->nome = $dados["nome"];
        $this->produtos->quantidade = $dados["quantidade"];
        $this->produtos->preco = $dados["preco"];
        $this->produtos->descricao = $dados["descricao"];

        if($this->produtos->Atualizar()){
            header("location: index.php");
        }
    }

    public function localizarProduto($id){
        return $this->produtos->buscaProduto($id);
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
            //echo "Imagem selecionada - " . $check["mime"] . ".<br>";
            $uploadOk = 1;
        } else {
            // echo "O arquivo selecionado não é uma imagem.<br>";
            $uploadOk = 0;
            echo "Erro ao tentar enviar o arquivo";
            die();
        }

        // Verifica se o arquivo já existe na pasta
        if (file_exists($upload_file)) {
            // echo "O arquivo já existe no servidor.<br>";
            $uploadOk = 0;
        }

        // Verifica o tamanho do arquivo - Limite de 500Kb
        if ($arquivo['size']['fileToUpload'] > 500000) {
            $uploadOk = 0;
            echo "Arquivo muito grande!";
        }
        // Permite apenas determinados tipos de arquivo - jpg, png, jpeg e gif
        if (
            $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif"
        ) {
            //  echo "São aceitas somente imagens JPG, JPEG, PNG e GIF.";
            $uploadOk = 0;
        }

        // Verificação de erros. Se $uploadOk=0 ocorreu algum erro
        if ($uploadOk == 0) {
            //  echo "Erro: não foi possível fazer upload.";
            return false;
            // Se não ocorreu problemas, tenta fazer upload
        } else {
            if (move_uploaded_file($arquivo['tmp_name']['fileToUpload'], $upload_file)) {
                //     echo "Arquivo ". basename( $arquivo['full_path']['fileToUpload']) . " enviado.";
                return true;
            } else {
                //     echo "Erro ao enviar a imagem.";
                return false;
            }
        }

        return false;
    }

}