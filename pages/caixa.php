<?php
session_start();
require_once('../includes/config.php');

if (!isset($_SESSION['user_id'])) {
    die('Erro: Usuário não autenticado.');
}

$user_id = $_SESSION['user_id'];

$query = "SELECT * FROM products WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Caixa</title>
    <link rel="stylesheet" href="../assets/css/caixa.css">
</head>
<body>

<div class="container">
    <h1>Caixa</h1>

    <!-- Produtos Disponíveis -->
    <h2>Produtos Disponíveis</h2>
    <table>
        <thead>
            <tr>
                <th>Código</th>
                <th>Nome</th>
                <th>Preço Unitário</th>
                <th>Quantidade em Estoque</th>
                <th>Ação</th>
            </tr>
        </thead>
        <tbody>
            <?php
            require_once('../includes/config.php');
            $result = $conn->query("SELECT * FROM products WHERE user_id = {$_SESSION['user_id']}");
            while ($produto = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $produto['code']; ?></td>
                    <td><?php echo $produto['name']; ?></td>
                    <td>R$ <?php echo number_format($produto['sale_price'], 2, ',', '.'); ?></td>
                    <td><?php echo $produto['quantity']; ?></td>
                    <td><button onclick="adicionarCarrinho('<?php echo $produto['code']; ?>', '<?php echo $produto['name']; ?>', <?php echo $produto['sale_price']; ?>, <?php echo $produto['quantity']; ?>)">Adicionar</button></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <!-- Carrinho -->
    <h2>Carrinho</h2>
    <table id="carrinho">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Quantidade</th>
                <th>Preço Unitário</th>
                <th>Total</th>
                <th>Ação</th>
            </tr>
        </thead>
        <tbody>
            <!-- Itens do carrinho serão adicionados aqui dinamicamente -->
        </tbody>
    </table>

    <!-- Resumo do Pagamento -->
    <div>
        <h3>Total a Pagar: R$ <span id="total-geral">0.00</span></h3>
        <label for="forma-pagamento">Forma de Pagamento:</label>
        <select id="forma-pagamento">
            <option value="dinheiro">Dinheiro</option>
            <option value="cartao_credito">Cartão de Crédito</option>
        </select>

        <div id="parcelas-container" style="display: none;">
            <label for="parcelas">Parcelas:</label>
            <select id="parcelas">
                <option value="1">1x</option>
                <option value="2">2x</option>
                <option value="3">3x</option>
                <option value="4">4x</option>
                <option value="5">5x</option>
                <option value="6">6x</option>
            </select>
            <p>Valor por parcela: R$ <span id="valor-parcela">0.00</span></p>
        </div>
    </div>
</div>

<script src="../assets/js/caixa.js"></script>
</body>
</html>
