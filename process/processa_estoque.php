<?php
require_once '../includes/config.php';

// Cadastro de produto
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitizar dados do formulário
    $nome = htmlspecialchars(trim($_POST['nome']));
    $codigo = htmlspecialchars(trim($_POST['codigo']));
    $fornecedor = htmlspecialchars(trim($_POST['fornecedor']));
    $categoria = htmlspecialchars(trim($_POST['categoria']));
    $preco_custo = $_POST['preco_custo'];
    $preco_venda = $_POST['preco_venda'];
    $quantidade = $_POST['quantidade'];
    $vencimento = $_POST['vencimento'] ?? null;

    // Inserir produto no banco de dados
    $stmt = $conn->prepare("INSERT INTO products (user_id, name, code, supplier, category, cost_price, sale_price, quantity, expiration_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param('issssdiis', $user_id, $nome, $codigo, $fornecedor, $categoria, $preco_custo, $preco_venda, $quantidade, $vencimento);
    
    if ($stmt->execute()) {
        echo "Produto cadastrado com sucesso!";
        header('Location: ../pages/estoque.php');
        exit;
    } else {
        echo "Erro ao cadastrar produto: " . $conn->error;
    }
}

// Exclusão de produto
if (isset($_GET['delete'])) {
    $product_id = (int)$_GET['delete'];

    // Excluir produto do banco de dados
    $stmt = $conn->prepare("DELETE FROM products WHERE product_id = ?");
    $stmt->bind_param('i', $product_id);

    if ($stmt->execute()) {
        echo "Produto excluído com sucesso!";
        header('Location: ../pages/estoque.php');
        exit;
    } else {
        echo "Erro ao excluir produto: " . $conn->error;
    }
}
?>
