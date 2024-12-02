<?php
session_start();
require_once('../includes/config.php');

// Verificar se os dados foram enviados via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $produtos = json_decode($_POST['produtos'], true);
    $forma_pagamento = $_POST['forma_pagamento'];
    $valor_total = floatval($_POST['valor_total']);

    // Registrar a venda na tabela "sales"
    $stmt = $conn->prepare("INSERT INTO sales (user_id, total_value, payment_method, sale_date) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param('ids', $user_id, $valor_total, $forma_pagamento);

    if ($stmt->execute()) {
        $sale_id = $stmt->insert_id;

        // Inserir cada produto na tabela "sales_products" e atualizar o estoque
        foreach ($produtos as $produto) {
            $produto_id = intval($produto['id']);
            $quantidade = intval($produto['quantidade']);
            $valor_unitario = floatval($produto['preco']);
            $valor_total_produto = $quantidade * $valor_unitario;

            // Inserir os detalhes da venda
            $stmt_produto = $conn->prepare("INSERT INTO sales_products (sale_id, product_id, quantity, unit_price, total_price) VALUES (?, ?, ?, ?, ?)");
            $stmt_produto->bind_param('iiidd', $sale_id, $produto_id, $quantidade, $valor_unitario, $valor_total_produto);
            $stmt_produto->execute();

            // Atualizar a quantidade no estoque
            $stmt_estoque = $conn->prepare("UPDATE products SET quantity = quantity - ? WHERE product_id = ? AND user_id = ?");
            $stmt_estoque->bind_param('iii', $quantidade, $produto_id, $user_id);
            $stmt_estoque->execute();
        }

        echo json_encode(['status' => 'success', 'message' => 'Venda registrada com sucesso!', 'sale_id' => $sale_id]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Erro ao registrar a venda.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Método de requisição inválido.']);
}
?>
