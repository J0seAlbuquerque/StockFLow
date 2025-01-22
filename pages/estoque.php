<?php
session_start();
if (!isset($_SESSION['nomeEmpresa']) || !isset($_SESSION['user_id'])) {
    header("Location: ../pages/login.php");
    exit();
}
require_once('../includes/config.php');

// Verifica se o usuário está autenticado e obtém o user_id da sessão
$user_id = $_SESSION['user_id'];
$nomeEmpresa = $_SESSION['nomeEmpresa']; // Define the $nomeEmpresa variable

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
                <span>| </span>
                <a href="../pages/caixa.php">Caixa</a>
                <span>| </span>
                <a href="../pages/estoque.php">Estoque</a>
                <span>| </span>
                <a href="../pages/">Vendas</a>               
            </div>
            
            <!-- Nome da empresa logada com o dropdown -->
            <div class="user-info">
                <span class="empresa-name"><?php echo htmlspecialchars($nomeEmpresa); ?></span>
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
    <div class="box">
        <h1>Estoque</h1>

        <!-- Botão para abrir o pop-up -->
        <button id="open-popup-btn">Cadastrar Produto</button>

        <!-- Pop-up de Cadastro de Produto -->
        <div class="popup-overlay" id="popup-overlay"></div>
        <div class="popup" id="popup">
            <span class="close-btn" id="close-popup-btn">&times;</span>
            <h2>Cadastrar Produto</h2>
            <form id="produto-form" method="POST" action="../process/processa_estoque.php" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="nome">Nome:</label>
                    <input type="text" id="nome" name="nome" required>
                </div>
                <div class="form-group">
                    <label for="codigo">Código:</label>
                    <input type="text" id="codigo" name="codigo" required>
                </div>
                <div class="form-group">
                    <label for="fornecedor">Fornecedor:</label>
                    <input type="text" id="fornecedor" name="fornecedor">
                </div>
                <div class="form-group">
                    <label for="categoria">Categoria:</label>
                    <input type="text" id="categoria" name="categoria">
                </div>
                <div class="form-group">
                    <label for="preco_custo">Preço de Custo:</label>
                    <input type="number" step="0.01" id="preco_custo" name="preco_custo" required>
                </div>
                <div class="form-group">
                    <label for="preco_venda">Preço de Venda:</label>
                    <input type="number" step="0.01" id="preco_venda" name="preco_venda" required>
                </div>
                <div class="form-group">
                    <label for="quantidade">Quantidade em Estoque:</label>
                    <input type="number" id="quantidade" name="quantidade" required>
                </div>
                <div class="form-group">
                    <label for="tem_vencimento">Tem Data de Vencimento:</label>
                    <input type="checkbox" id="tem_vencimento" name="tem_vencimento" onchange="toggleVencimento()">
                </div>
                <div class="form-group" id="vencimento-group" style="display: none;">
                    <label for="vencimento">Data de Vencimento:</label>
                    <input type="date" id="vencimento" name="vencimento">
                </div>
                <button type="submit">Cadastrar Produto</button>
            </form>
        </div>


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
                                    <a href="#" onclick='openEditModal(<?php echo json_encode($produto, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>)'>Editar</a>
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

            <!-- Modal de Edição -->
            <div id="editModal" class="popup">
                <span class="close-btn" id="close-edit-popup-btn">&times;</span>
                <h2>Editar Produto</h2>
                <form id="editForm" action="../process/editar_produto.php" method="post">
                    <input type="hidden" name="id" id="editId">
                    <div class="form-group">
                        <label for="editName">Nome:</label>
                        <input type="text" name="name" id="editName" required>
                    </div>
                    <div class="form-group">
                        <label for="editCode">Código:</label>
                        <input type="text" name="code" id="editCode" required>
                    </div>
                    <div class="form-group">
                        <label for="editSupplier">Fornecedor:</label>
                        <input type="text" name="supplier" id="editSupplier" required>
                    </div>
                    <div class="form-group">
                        <label for="editCategory">Categoria:</label>
                        <input type="text" name="category" id="editCategory" required>
                    </div>
                    <div class="form-group">
                        <label for="editCostPrice">Preço de Custo:</label>
                        <input type="text" name="cost_price" id="editCostPrice" required>
                    </div>
                    <div class="form-group">
                        <label for="editSalePrice">Preço de Venda:</label>
                        <input type="text" name="sale_price" id="editSalePrice" required>
                    </div>
                    <div class="form-group">
                        <label for="editQuantity">Quantidade:</label>
                        <input type="text" name="quantity" id="editQuantity" required>
                    </div>
                    <button type="submit">Salvar</button>
                </form>
            </div>
        </section>
    </div>
</div>
<script src="../assets/js/_estoque.js"></script>

<?php
    include('../includes/footer.php');
?>
</body>
</html>
