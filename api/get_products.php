<?php
require_once('../includes/config.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Usuário não autenticado.']);
    exit;
}

$user_id = $_SESSION['user_id'];

$query = "SELECT * FROM products WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();

$products = [];
while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}

// Log para verificar os dados retornados
error_log(print_r($products, true));

echo json_encode(['success' => true, 'products' => $products]);
?>