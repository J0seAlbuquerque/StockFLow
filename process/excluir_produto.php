<?php
session_start(); // Iniciar a sessão
require_once('../includes/config.php');

// Verificar se o ID do produto foi enviado via GET
if (isset($_GET['delete'])) {
    $product_id = intval($_GET['delete']);

    // Preparar e executar a exclusão do produto
    $stmt = $conn->prepare("DELETE FROM products WHERE product_id = ? AND user_id = ?");
    $stmt->bind_param('ii', $product_id, $_SESSION['user_id']);

    if ($stmt->execute()) {
        header('Location: ../pages/estoque.php'); // Redirecionar de volta para a página de estoque após exclusão
        exit;
    } else {
        echo "Erro ao excluir o produto: " . $stmt->error;
    }
} else {
    echo "Produto inválido.";
}
?>
