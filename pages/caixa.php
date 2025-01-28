<?php
session_start();
if (!isset($_SESSION['nomeEmpresa']) || !isset($_SESSION['user_id'])) {
    header("Location: ../pages/login.php");
    exit();
}
require_once('../includes/config.php');

$user_id = $_SESSION['user_id'];
$nomeEmpresa = $_SESSION['nomeEmpresa'];
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Caixa - Sistema de Vendas</title>
    <link rel="stylesheet" href="../assets/css/_caixa.css">
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

<div class="container">
    <h1>Caixa</h1>

    <div class="sections-wrapper">
        <!-- Lista de Produtos -->
        <section id="products-section">
            <h2>Produtos Disponíveis</h2>
            <input type="text" id="searchBar" placeholder="Pesquisar por nome ou código..." class="search-bar">
            <table id="productsList">
                <thead>
                    <tr> 
                        <th>Nome</th>
                        <th>Código</th>
                        <th>Categoria</th> <!-- Nova coluna Categoria -->
                        <th>Preço Unitário</th>
                        <th>Estoque</th>
                        <th>Ação</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Produtos serão carregados aqui -->
                </tbody>
            </table>
        </section>

        <!-- Carrinho -->
        <div class="cart-wrapper">
            <h2>Carrinho de Compras</h2>
            <section id="cart-section">
                <table id="cartSummary">
                    <thead>
                        <tr>
                            <th>Produto</th>
                            <th>Qtd</th>
                            <th>Uni</th>
                            <th>Total</th>
                            <th>Editar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Itens do carrinho serão exibidos aqui -->
                    </tbody>
                </table>
                <div id="totalAmount">Total: R$ 0,00</div>
                <button id="proceedToPayment">Prosseguir para Pagamento</button>
            </section>
        </div>
    </div>
</div>

    <!-- Modal -->
    <div id="paymentModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Resumo do Pedido</h2>
            <table>
                <thead>
                    <tr>
                        <th>Produto</th>
                        <th>Qtd</th>
                        <th>Preço</th>
                    </tr>
                </thead>
                <tbody id="cartSummaryModal">
                    <!-- Itens do carrinho serão exibidos aqui -->
                </tbody>
            </table>
            <label for="paymentMethod">Forma de Pagamento:</label>
            <select id="paymentMethod">
                <option value="dinheiro">Dinheiro</option>
                <option value="pix">Pix</option>
                <option value="debito">Cartão de Débito</option>
                <option value="credito">Cartão de Crédito</option>
            </select>
            <div id="creditCardOptions" style="display: none;">
                <label for="installments">Parcelas:</label>
                <select id="installments">
                    <option value="1">1x</option>
                    <option value="2">2x</option>
                    <option value="3">3x</option>
                    <option value="4">4x</option>
                    <option value="5">5x</option>
                    <option value="6">6x</option>
                </select>
                <div id="installmentValue">Valor da Parcela: R$ 0,00</div>
            </div>
            <label for="discountType">Tipo de Desconto:</label>
            <select id="discountType">
                <option value="reais">R$</option>
                <option value="percentual">%</option>
            </select>
            <label for="discountValue">Valor do Desconto:</label>
            <input type="number" id="discountValue" placeholder="Digite o valor">
            <button id="applyDiscount">Aplicar Desconto</button>
            <button id="removeDiscount">Remover Desconto</button>
            <div id="discountDisplay">Desconto Aplicado: R$ 0,00</div>
            <div id="totalAmountModal">Total: R$ 0,00</div>
            <button id="confirmPayment">Confirmar Pagamento</button>
        </div>
    </div>

<script src="../assets/js/_caixa.js"></script>

</body>
</html>