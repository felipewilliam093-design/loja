<?php
include_once("configs/database.php");

$banco = new Database();
$bd = $banco->conectar();

try {
    // Tornando campos extras opcionais para evitar erros de restrição
    $sql = "ALTER TABLE cliente 
            MODIFY COLUMN cidade VARCHAR(50) NULL,
            MODIFY COLUMN estado VARCHAR(50) NULL,
            MODIFY COLUMN cep VARCHAR(30) NULL,
            MODIFY COLUMN numero INT NULL,
            MODIFY COLUMN login VARCHAR(50) NULL";
    
    $bd->exec($sql);
    echo "Estrutura da tabela cliente ajustada com sucesso!";
} catch (PDOException $e) {
    echo "Erro ao ajustar tabela: " . $e->getMessage();
}
