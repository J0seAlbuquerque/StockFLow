<?php
// Incluir arquivo de configuração para a conexão com o banco
require_once '../includes/config.php';

// Verificar se o formulário foi submetido
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Capturar e sanitizar os dados
    $name = htmlspecialchars(trim($_POST['name']));
    $company_name = htmlspecialchars(trim($_POST['company_name']));
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $password = trim($_POST['password']);
    $phone = htmlspecialchars(trim($_POST['phone']));
    $logo = $_FILES['logo'] ?? null;

    // Validar dados obrigatórios
    if (!$name || !$company_name || !$email || !$password || !$phone) {
        die('Por favor, preencha todos os campos obrigatórios.');
    }

    // Verificar duplicidade de e-mail
    $query = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $query->bind_param('s', $email);
    $query->execute();
    $result = $query->get_result();
    if ($result->num_rows > 0) {
        die('E-mail já cadastrado. Tente outro.');
    }

    // Criptografar a senha
    $passwordHash = password_hash($password, PASSWORD_BCRYPT);

    // Processar o upload da logo, opcional
    // $logo_path = null;
    // if ($logo && $logo['error'] === UPLOAD_ERR_OK) {
    //     $extensao = pathinfo($logo['name'], PATHINFO_EXTENSION);
    //     $logo_name = uniqid('logo_', true) . '.' . $extensao;
    //     $logo_path = '../assets/uploads/' . $logo_name;
    //     if (!move_uploaded_file($logo['tmp_name'], $logo_path)) {
    //         die('Erro ao salvar o arquivo da logo.');
    //     }
    // }

    // Inserir dados do usuário na tabela users
    $stmt = $conn->prepare("INSERT INTO users (name, email, password, phone, company_name, logo_path) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param('ssssss', $name, $email, $passwordHash, $phone, $company_name, $logo_path);

    if ($stmt->execute()) {
        echo "Cadastro realizado com sucesso!";
        header('Location: ../pages/login.php');
        exit;
    } else {
        echo "Erro ao cadastrar: " . $conn->error;
    }
}
?>