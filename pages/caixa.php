<?php
session_start();
require_once('../includes/config.php');

// Verifica se o usuário está autenticado
if (!isset($_SESSION['user_id'])) {
    die('Erro: Usuário não autenticado.');
}

$user_id = $_SESSION['user_id'];
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

<div class="container">
    <h1>Caixa - Realizar Venda</h1>

    <!-- Lista de Produtos -->
    <section id="products-section">
        <h2>Produtos Disponíveis</h2>
        <table id="productsList">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Código</th>
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
    <section id="cart-section">
        <h2>Carrinho de Compras</h2>
        <table id="cartSummary">
            <thead>
                <tr>
                    <th>Produto</th>
                    <th>Quantidade</th>
                    <th>Preço Unitário</th>
                    <th>Total</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
                <!-- Itens do carrinho serão exibidos aqui -->
            </tbody>
        </table>
        <div id="totalAmount">Total: R$ 0,00</div>
        <button id="proceedToPayment">Prosseguir para Pagamento</button>
    </section>

    <!-- Popup de Pagamento -->
    <div id="paymentPopup" style="display:none;">
        <h2>Resumo da Compra</h2>
        <table id="popupCartSummary">
            <!-- Resumo do carrinho será exibido aqui -->
        </table>
        <div id="popupTotalAmount">Total: R$ 0,00</div>

        <h3>Forma de Pagamento</h3>
        <select id="paymentMethod">
            <option value="cash">Dinheiro</option>
            <option value="pix">Pix</option>
            <option value="debit_card">Cartão de Débito</option>
            <option value="credit_card">Cartão de Crédito</option>
        </select>

        <div id="creditCardOptions" style="display:none;">
            <label for="installments">Parcelas:</label>
            <select id="installments">
                <option value="1">1x</option>
                <option value="2">2x</option>
                <option value="3">3x</option>
                <option value="4">4x</option>
            </select>
        </div>
        <button id="closePopup">Fechar</button>
    </div>

</div>

<script src="../assets/js/_caixa.js"></script>

</body>
</html>