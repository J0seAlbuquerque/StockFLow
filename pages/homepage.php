<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
if (!isset($_SESSION['nomeEmpresa'])) {
    header("Location: ../pages/login.php");
    exit();
}
require_once('../includes/config.php'); // Include the database configuration file

$user_id = $_SESSION['user_id'];
$nomeEmpresa = $_SESSION['nomeEmpresa']; // Define the $nomeEmpresa variable
$customer_name = $_SESSION['name']; // Define the $customer_name variable

// Fetch the latest 3 sales
$query_sales = "SELECT sale_id, customer_name, sale_date, total_amount FROM sales WHERE user_id = ? ORDER BY sale_date DESC LIMIT 3";
$stmt_sales = $conn->prepare($query_sales);
$stmt_sales->bind_param("i", $user_id);
$stmt_sales->execute();
$result_sales = $stmt_sales->get_result();
$latest_sales = $result_sales->fetch_all(MYSQLI_ASSOC);

// Fetch the 5 products with the lowest quantity
$query_products = "SELECT name, quantity FROM products WHERE user_id = ? ORDER BY quantity ASC LIMIT 5";
$stmt_products = $conn->prepare($query_products);
$stmt_products->bind_param("i", $user_id);
$stmt_products->execute();
$result_products = $stmt_products->get_result();
$low_stock_products = $result_products->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciador de Estoque - StockFlow</title>
    <link rel="stylesheet" href="../assets/css/_homepage.css"> 
</head>
<body>
    <!-- Barra de navegação -->
    <header>
        <nav class="navbar">
            <!-- Nome do sistema no canto esquerdo -->
            <div class="logo">
                <a href="./homepage.php" class="navbar-brand">StockFlow</a>
            </div>
            
            <!-- Links de navegação -->
            <div class="nav-links">
                <a href="./homepage.php">Home</a>
                <span>|</span>
                <a href="../pages/caixa.php">Caixa</a>
                <span>|</span>
                <a href="../pages/estoque.php">Estoque</a>
                <span>|</span>
                <a href="../pages/vendas.php">Vendas</a>               
            </div>
            
            <!-- Nome da empresa logada com o dropdown -->
            <div class="user-info">
                <span class="empresa-name"><?php echo $nomeEmpresa; ?></span>
                <div class="dropdown">
                    <button class="dropbtn">Mais +</button>
                    <div class="dropdown-content">
                        <a href="../pages/ver_info_empresa.php">Minha empresa</a>
                        <a href="../process/processa_logout.php">Sair</a>
                    </div>
                </div>
            </div>
        </nav>
    </header>
    <div class="welcome">    
        <h1>Bem-Vindo, <?php echo $customer_name?></h1>
    </div>
    <div class="dashboard">
        <section class="latest-sales">
            <h2>Últimas 3 Vendas</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Cliente</th>
                        <th>Data</th>
                        <th>Valor</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($latest_sales as $sale): ?>
                        <tr>
                            <td><?php echo $sale['sale_id']; ?></td>
                            <td><?php echo $sale['customer_name']; ?></td>
                            <td><?php echo $sale['sale_date']; ?></td>
                            <td><?php echo $sale['total_amount']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <a href="./vendas.php">Ver Vendas</a>
        </section>
        <section class="low-stock-products">
            <h2>Produtos com Menor Quantidade em Estoque</h2>
            <table>
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Quantidade</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($low_stock_products as $product): ?>
                        <tr>
                            <td><?php echo $product['name']; ?></td>
                            <td><?php echo $product['quantity']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </div>
</body>
</html>
