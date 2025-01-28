<?php
// Incluir arquivo de configuração para a conexão com o banco
require_once '../includes/config.php';

// Iniciar a sessão para armazenar o estado do login
session_start();

// Verificar se o formulário foi submetido
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Capturar e sanitizar os dados
    $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
    $password = trim($_POST['senha']);

    // Verificar se os campos foram preenchidos
    if (!$email || !$password) {
        die('Por favor, preencha todos os campos.');
    }

    // Consultar o banco de dados para verificar se o e-mail existe
    $query = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $query->bind_param('s', $email);
    $query->execute();
    $result = $query->get_result();

    // Verificar se o usuário existe
    if ($result->num_rows === 0) {
        die('E-mail não encontrado. Verifique suas credenciais.');
    }

    // Obter os dados do usuário
    $user = $result->fetch_assoc();

    // Verificar se a senha informada é correta
    if (!password_verify($password, $user['password'])) {
        die('Senha incorreta.');
    }

    // Se o login for bem-sucedido, armazenar as informações do usuário na sessão
    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['name'] = $user['name'];
    $_SESSION['company_name'] = $user['company_name'];
    $_SESSION['nomeEmpresa'] = $user['company_name']; // Defina a variável de sessão com o nome da empresa

    // Redirecionar para a página inicial ou página desejada
    header('Location: ../pages/homepage.php');
    exit;
} else {
    // Se o formulário não for submetido corretamente, redirecionar para o login
    header('Location: ../pages/login.php');
    exit;
}
?>