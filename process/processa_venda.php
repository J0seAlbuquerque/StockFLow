<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
require_once('../includes/config.php');

// Verifica se o usuário está autenticado
if (!isset($_SESSION['user_id'])) {
    die('Erro: Usuário não autenticado.');
}

$user_id = $_SESSION['user_id'];
$customer_name = $_POST['customer_name'] ?? null;
$payment_method = $_POST['payment_method'];
$total_amount = $_POST['total_amount'];
$cart_items = json_decode($_POST['cart_items'], true);

// Verifica se o método de pagamento é válido
$valid_payment_methods = ['dinheiro', 'pix', 'debito', 'credito'];
$payment_method_map = [
    'dinheiro' => 'cash',
    'pix' => 'pix',
    'debito' => 'debit_card',
    'credito' => 'credit_card'
];

if (!in_array($payment_method, $valid_payment_methods)) {
    die('Erro: Método de pagamento inválido.');
}

$payment_method_db = $payment_method_map[$payment_method];

// Calcula o lucro total
$profit = 0;
foreach ($cart_items as $item) {
    $product_id = $item['id'];
    $quantity = $item['quantity'];
    $unit_price = $item['price'];

    // Obtém o preço de custo do produto
    $query = "SELECT cost_price FROM products WHERE product_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    $cost_price = $product['cost_price'];
    $profit += ($unit_price - $cost_price) * $quantity;
}

// Insere a venda no banco de dados
$query = "INSERT INTO sales (user_id, customer_name, payment_method, total_amount, profit) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param('issdd', $user_id, $customer_name, $payment_method_db, $total_amount, $profit);

if ($stmt->execute()) {
    $sale_id = $stmt->insert_id;

    // Insere os itens vendidos e atualiza o estoque
    foreach ($cart_items as $item) {
        $product_id = $item['id'];
        $quantity = $item['quantity'];
        $unit_price = $item['price'];
        $total_price = $unit_price * $quantity;

        // Insere o item vendido
        $query = "INSERT INTO sales_items (sale_id, product_id, quantity, unit_price, total_price) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('iiidd', $sale_id, $product_id, $quantity, $unit_price, $total_price);
        $stmt->execute();

        // Atualiza a quantidade no estoque
        $query = "UPDATE products SET quantity = quantity - ? WHERE product_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('ii', $quantity, $product_id);
        $stmt->execute();
    }

    echo 'Venda processada com sucesso!';
    } else {
        echo 'Erro ao processar a venda: ' . $stmt->error;
    }
?>
