<?php
session_start();
require_once('../includes/config.php');

// Verifica se o usuário está autenticado
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Consultar produtos cadastrados no banco
$query = "SELECT product_id, name, sale_price, quantity FROM products WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Caixa</title>
    <link rel="stylesheet" href="../assets/css/_caixa.css">
    <script src="../assets/js/caixa.js" defer></script>
</head>
<body>
    <div class="container">
        <h1>Caixa</h1>

        <!-- Lista de Produtos -->
        <section id="lista-produtos">
            <h2>Produtos Disponíveis</h2>
            <table>
                <thead>
                    <tr>
                        <th>Produto</th>
                        <th>Preço Unitário</th>
                        <th>Estoque</th>
                        <th>Ação</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($produto = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($produto['name']); ?></td>
                            <td>R$ <?php echo number_format($produto['sale_price'], 2, ',', '.'); ?></td>
                            <td><?php echo htmlspecialchars($produto['quantity']); ?></td>
                            <td>
                                <button class="adicionar-carrinho" data-id="<?php echo $produto['product_id']; ?>"
                                        data-nome="<?php echo htmlspecialchars($produto['name']); ?>"
                                        data-preco="<?php echo $produto['sale_price']; ?>">+</button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </section>

        <!-- Carrinho -->
        <section id="carrinho">
            <h2>Carrinho</h2>
            <table>
                <thead>
                    <tr>
                        <th>Produto</th>
                        <th>Quantidade</th>
                        <th>Preço Unitário</th>
                        <th>Total</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody id="itens-carrinho"></tbody>
            </table>
            <div id="total">
                Total Geral: R$ <span id="total-geral">0,00</span>
            </div>

            <label for="forma-pagamento">Forma de Pagamento:</label>
            <select id="forma-pagamento">
                <option value="Dinheiro">Dinheiro</option>
                <option value="Pix">Pix</option>
                <option value="Cartão de Crédito">Cartão de Crédito</option>
                <option value="Cartão de Débito">Cartão de Débito</option>
            </select>

            <div id="parcelamento">
                <label for="num-parcelas">Parcelas (Cartão):</label>
                <select id="num-parcelas">
                    <option value="1">1x</option>
                    <option value="2">2x</option>
                    <option value="3">3x</option>
                    <option value="4">4x</option>
                    <option value="5">5x</option>
                    <option value="6">6x</option>
                </select>
            </div>

            <button id="finalizar-venda">Finalizar Venda</button>
        </section>
    </div>
</body>
</html>
