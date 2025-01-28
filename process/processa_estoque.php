<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
require_once('../includes/config.php');

// Verifica se o usuário está autenticado
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    die('Erro: Usuário não autenticado.');
}

// Recupera os dados do formulário
$nome = $_POST['nome'];
$codigo = $_POST['codigo'];
$fornecedor = $_POST['fornecedor'];
$categoria = $_POST['categoria'];
$preco_custo = $_POST['preco_custo'];
$preco_venda = $_POST['preco_venda'];
$quantidade = $_POST['quantidade'];
$tem_vencimento = isset($_POST['tem_vencimento']) ? 1 : 0;
$vencimento = $tem_vencimento ? $_POST['vencimento'] : null;

// Insere o produto no banco de dados
$query = "INSERT INTO products (user_id, name, code, supplier, category, cost_price, sale_price, quantity, expiration_date, has_expiration)
          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($query);
$stmt->bind_param(
    'issssddisi',
    $user_id,
    $nome,
    $codigo,
    $fornecedor,
    $categoria,
    $preco_custo,
    $preco_venda,
    $quantidade,
    $vencimento,
    $tem_vencimento
);

if ($stmt->execute()) {
    echo 'Produto cadastrado com sucesso!';
    header('Location: ../pages/estoque.php'); // Redireciona para a página de estoque após o cadastro
} else {
    echo 'Erro ao cadastrar o produto: ' . $stmt->error;
}
?>
