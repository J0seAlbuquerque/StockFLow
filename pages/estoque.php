<?php
session_start(); // Inicia a sessão
require_once('../includes/config.php');

// Verifica se o usuário está autenticado e obtém o user_id da sessão
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    die('Erro: Usuário não autenticado.');
}

// Consultar produtos no banco para o usuário autenticado
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estoque - Sistema de Vendas</title>
    <link rel="stylesheet" href="../assets/css/_estoque.css">
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
                        <a href="../pages/preencher_info_empresa.php">Editar Dados</a>
                        <a href="../pages/login.php">Sair</a>
                    </div>
                </div>
            </div>
        </nav>
    </header>

<div class="container">
    <div class="box">
        <h1>Estoque</h1>

        <!-- Formulário de Cadastro de Produto -->
        <section id="form-section">
            <div class="form-container">
                <h2>Cadastrar Produto</h2>
                <form id="produto-form" method="POST" action="../process/processa_estoque.php" enctype="multipart/form-data">
                    <label for="nome">Nome do Produto:</label>
                    <input type="text" id="nome" name="nome" required>

                    <label for="codigo">Código:</label>
                    <input type="text" id="codigo" name="codigo" required>

                    <label for="fornecedor">Fornecedor:</label>
                    <input type="text" id="fornecedor" name="fornecedor">

                    <label for="categoria">Categoria:</label>
                    <input type="text" id="categoria" name="categoria">

                    <label for="preco_custo">Preço de Custo:</label>
                    <input type="number" step="0.01" id="preco_custo" name="preco_custo" required>

                    <label for="preco_venda">Preço de Venda:</label>
                    <input type="number" step="0.01" id="preco_venda" name="preco_venda" required>

                    <label for="quantidade">Quantidade em Estoque:</label>
                    <input type="number" id="quantidade" name="quantidade" required>

                    <label for="vencimento">Data de Vencimento:</label>
                    <input type="date" id="vencimento" name="vencimento">

                    <button type="submit">Cadastrar Produto</button>
                </form>
            </div>
        </section>

        <!-- Lista de Produtos Cadastrados -->
        <section id="produtos-lista">
            <h2>Produtos Cadastrados</h2>
            <table>
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Código</th>
                        <th>Fornecedor</th>
                        <th>Categoria</th>
                        <th>Preço de Custo</th>
                        <th>Preço de Venda</th>
                        <th>Quantidade</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($produto = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($produto['name']); ?></td>
                                <td><?php echo htmlspecialchars($produto['code']); ?></td>
                                <td><?php echo htmlspecialchars($produto['supplier']); ?></td>
                                <td><?php echo htmlspecialchars($produto['category']); ?></td>
                                <td>R$ <?php echo number_format($produto['cost_price'], 2, ',', '.'); ?></td>
                                <td>R$ <?php echo number_format($produto['sale_price'], 2, ',', '.'); ?></td>
                                <td><?php echo htmlspecialchars($produto['quantity']); ?></td>
                                <td>
                                    <a href="editar_produto.php?id=<?php echo $produto['id']; ?>">Editar</a>
                                    <a href="../process/excluir_produto.php?delete=<?php echo $produto['product_id']; ?>">Excluir</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8">Nenhum produto cadastrado.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
    </div>
</div>

<?php
    include('../includes/footer.php');
?>
</body>
</html>
