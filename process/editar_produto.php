<?php
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
include '../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $code = $_POST['code'];
    $supplier = $_POST['supplier'];
    $category = $_POST['category'];
    $cost_price = $_POST['cost_price'];
    $sale_price = $_POST['sale_price'];
    $quantity = $_POST['quantity'];

    $query = "UPDATE products SET name='$name', code='$code', supplier='$supplier', category='$category', cost_price='$cost_price', sale_price='$sale_price', quantity='$quantity' WHERE product_id=$id";

    if ($conn->query($query) === TRUE) {
        header('Location: ../pages/estoque.php');
    } else {
        echo "Erro ao atualizar produto: " . $conn->error;
    }
}
?>