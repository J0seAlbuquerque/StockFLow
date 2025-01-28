<?php
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
    
    // Incluir arquivo de configuração para a conexão com o banco
    require_once '../includes/config.php';
    session_start();

    // Verificar se o usuário está logado
    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    }

    $user_id = $_SESSION['user_id'];

    // Verificar se o formulário foi enviado
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Capturar e sanitizar os dados
        $cnpj = htmlspecialchars(trim($_POST['cnpj']));
        $endereco = htmlspecialchars(trim($_POST['endereco']));
        $numero = htmlspecialchars(trim($_POST['numero']));
        $bairro = htmlspecialchars(trim($_POST['bairro']));
        $estado = htmlspecialchars(trim($_POST['estado']));
        $cidade = htmlspecialchars(trim($_POST['cidade']));

        // Validar campos obrigatórios
        if (!$cnpj || !$endereco || !$numero || !$bairro || !$estado || !$cidade) {
            die('Por favor, preencha todos os campos obrigatórios.');
        }

        // Verificar se a empresa já está cadastrada
        $query = $conn->prepare("SELECT * FROM company_info WHERE company_id = ?");
        $query->bind_param('i', $user_id);
        $query->execute();
        $result = $query->get_result();

        if ($result->num_rows > 0) {
            // Atualizar os dados da empresa
            $stmt = $conn->prepare("UPDATE company_info SET cnpj = ?, address = ?, number = ?, neighborhood = ?, state = ?, city = ? WHERE company_id = ?");
            $stmt->bind_param('ssssssi', $cnpj, $endereco, $numero, $bairro, $estado, $cidade, $user_id);
        } else {
            // Inserir os dados da empresa
            $stmt = $conn->prepare("INSERT INTO company_info (company_id, cnpj, address, number, neighborhood, state, city) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param('issssss', $user_id, $cnpj, $endereco, $numero, $bairro, $estado, $cidade);
        }

        if ($stmt->execute()) {
            echo "Informações da empresa salvas com sucesso!";
            header('Location: ../pages/ver_info_empresa.php'); // Redirecionar para a página de visualização
            exit;
        } else {
            echo "Erro ao salvar as informações: " . $conn->error;
        }
    }
?>
