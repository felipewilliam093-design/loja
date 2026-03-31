<?php
session_start();
include_once("objetos/FuncionarioController.php");

$erro = "";

if($_SERVER["REQUEST_METHOD"] === 'POST'){
    $controller = new FuncionarioController();
    
    if (isset($_POST["entrar"])){
        $login = $_POST["login"];
        $senha = $_POST["senha"];
        
        $usuario = $controller->logar($login, $senha);
        
        if ($usuario) {
            $_SESSION["funcionario_id"] = $usuario->id;
            $_SESSION["funcionario_nome"] = $usuario->nome;
            $_SESSION["funcionario_tipo"] = $usuario->tipo;
            
            header("Location: index.php");
            exit();
        } else {
            $erro = "Login ou senha inválidos!";
        }
    }
}
?>

<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Loja Senac</title>
</head>
<body>

<h1>Login</h1>

<?php if($erro): ?>
    <p style="color:red;"><?= $erro ?></p>
<?php endif; ?>

<form action="login.php" method="post">
    <label>Login</label><br>
    <input type="text" name="login" required><br><br>
    
    <label>Senha</label><br>
    <input type="password" name="senha" required><br><br>
    
    <button name="entrar">Entrar</button>
</form>

<br>
<p>Não tem conta? <a href="cadastro-funcionario.php">Cadastre-se</a></p>

</body>
</html>
