<?php
    session_start();
    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    }
    require_once '../includes/config.php';

    $user_id = $_SESSION['user_id'];

    $query = $conn->prepare("SELECT * FROM company_info WHERE company_id = ?");
    $query->bind_param('i', $user_id);
    $query->execute();
    $result = $query->get_result();

    $dados_registrados = false;
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $cnpj = $row['cnpj'];
        $endereco = $row['address'];
        $numero = $row['number'];
        $bairro = $row['neighborhood'];
        $estado = $row['state'];
        $cidade = $row['city'];
        $dados_registrados = true;
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informações da Empresa</title>
    <link rel="stylesheet" href="../assets/css/_prench_i.css">
</head>
<body>
    <div class="container">
        <div class="box">
            <h1 class="site-name">StockFlow</h1>
            <h2>Informações da Empresa</h2>
            <?php if ($dados_registrados): ?>
                <p><strong>CNPJ:</strong> <?php echo htmlspecialchars($cnpj); ?></p>
                <p><strong>Endereço:</strong> <?php echo htmlspecialchars($endereco); ?></p>
                <p><strong>Número:</strong> <?php echo htmlspecialchars($numero); ?></p>
                <p><strong>Bairro:</strong> <?php echo htmlspecialchars($bairro); ?></p>
                <p><strong>Estado:</strong> <?php echo htmlspecialchars($estado); ?></p>
                <p><strong>Cidade:</strong> <?php echo htmlspecialchars($cidade); ?></p>
                <a href="preencher_info_empresa.php" class="btn">Editar Informações</a>
            <?php else: ?>
                <p>Dados ainda não registrados.</p>
                <a href="preencher_info_empresa.php" class="btn">Adicionar Informações</a>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>