<?php
    // Configuração de banco de dados
    $host = 'localhost'; // Servidor do banco de dados
    $dbname = 'stockflow'; // Nome do banco de dados
    $username = 'root'; // Usuário do banco (padrão no XAMPP)
    $password = ''; // Senha do banco (padrão no XAMPP)

    $conn = new mysqli($host, $username, $password, $dbname);

    // Verificar conexão
    if ($conn->connect_error) {
        die("Falha na conexão com o banco de dados: " . $conn->connect_error);
    }
?>

