<?php
    require_once('../includes/config.php');

    // Consultar produtos no banco
    $query = "SELECT * FROM products WHERE user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
?>

<link rel="stylesheet" href="../assets/css/_prench_info.css">
<div class="container">
    <div class="box">
        <h1>Estoque</h1>

        <!-- Formulário de Cadastro de Produto (à esquerda) -->
        <section id="form-section" class="left-section">
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

        <!-- Lista de Produtos Cadastrados (à direita) -->
        <section id="produtos-lista" class="right-section">
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
                    <?php while ($produto = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $produto['name']; ?></td>
                            <td><?php echo $produto['code']; ?></td>
                            <td><?php echo $produto['supplier']; ?></td>
                            <td><?php echo $produto['category']; ?></td>
                            <td><?php echo $produto['cost_price']; ?></td>
                            <td><?php echo $produto['sale_price']; ?></td>
                            <td><?php echo $produto['quantity']; ?></td>
                            <td>
                                <a href="editar_produto.php?id=<?php echo $produto['product_id']; ?>">Editar</a>
                                <a href="../process/processa_estoque.php?delete=<?php echo $produto['product_id']; ?>">Excluir</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </section>
    </div>
</div>

<?php
    include('../includes/footer.php');
?>
