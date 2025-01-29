<?php
session_start();
if (!isset($_SESSION['nomeEmpresa'])) {
    header("Location: ../pages/login.php");
    exit();
}
$user_id = $_SESSION['user_id'];
$nomeEmpresa = $_SESSION['nomeEmpresa']; // Define the $nomeEmpresa variable
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciador de Estoque - StockFlow</title>
    <link rel="stylesheet" href="../assets/css/_vendas.css"> 
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
                <a href="../pages/">Vendas</a>               
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
    <main>
        <section class="sales-list">
            <h2>Vendas Realizadas</h2>
            <div class="search-bar">
                <input type="text" id="searchInput" placeholder="Pesquisar por ID ou Nome do Cliente">
                <select id="paymentFilter">
                    <option value="">Todos os Métodos de Pagamento</option>
                    <option value="Dinheiro">Dinheiro</option>
                    <option value="Pix">Pix</option>
                    <option value="Debito">Debito</option>
                    <option value="Credito">Credito</option>
                </select>
            </div>
            <table class="styled-table" id="salesTable">
                <thead>
                    <tr>
                        <th>ID da Venda</th>
                        <th>Nome do Cliente</th>
                        <th>Método de Pagamento</th>
                        <th>Valor Total</th>
                        <th>Data da Venda</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    require_once('../includes/config.php');
                    $query = "SELECT sale_id, customer_name, payment_method, total_amount, sale_date FROM sales WHERE user_id = ?";
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param('i', $user_id);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    $payment_method_names = [
                        'cash' => 'Dinheiro',
                        'pix' => 'Pix',
                        'debit_card' => 'Debito',
                        'credit_card' => 'Credito'
                    ];

                    while ($row = $result->fetch_assoc()) {
                        $payment_method_display = $payment_method_names[$row['payment_method']];
                        echo "<tr>";
                        echo "<td>{$row['sale_id']}</td>";
                        echo "<td>{$row['customer_name']}</td>";
                        echo "<td>{$payment_method_display}</td>";
                        echo "<td>{$row['total_amount']}</td>";
                        echo "<td>{$row['sale_date']}</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </section>
    </main>
    <script src="../assets/js/_vendas.js"></script>
</body>
</html>
