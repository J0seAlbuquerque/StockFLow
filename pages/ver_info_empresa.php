<?php
    session_start();
    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    }
    require_once '../includes/config.php';

    $user_id = $_SESSION['user_id'];

    // Consulta para obter as informações da empresa
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

    // Consulta para obter o nome da empresa
    $query = $conn->prepare("SELECT company_name FROM users WHERE user_id = ?");
    $query->bind_param('i', $user_id);
    $query->execute();
    $result = $query->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $empresaNome = $row['company_name'];
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
                <div class="dropdown">
                    <button class="dropbtn">Mais +</button>
                    <div class="dropdown-content">
                        <a href="../pages/ver_info_empresa.php">Minha empresa</a>
                        <a href="../pages/login.php">Sair</a>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <!-- Conteúdo da página -->
    <div class="container" style="margin-top: 20px;">
        <div class="box">
            <h2>Informações da Empresa</h2>
            <h1 class="site-name"><?php echo htmlspecialchars($empresaNome); ?></h1>
            <?php if ($dados_registrados): ?>
                <p><strong>CNPJ:</strong> <?php echo htmlspecialchars($cnpj); ?></p>
                <p><strong>Endereço:</strong> <?php echo htmlspecialchars($endereco); ?></p>
                <p><strong>Número:</strong> <?php echo htmlspecialchars($numero); ?></p>
                <p><strong>Bairro:</strong> <?php echo htmlspecialchars($bairro); ?></p>
                <p><strong>Estado:</strong> <?php echo htmlspecialchars($estado); ?></p>
                <p><strong>Cidade:</strong> <?php echo htmlspecialchars($cidade); ?></p>
                <a href="preencher_info_empresa.php" class="btn edit-btn">Editar Informações</a>
            <?php else: ?>
                <p>Dados ainda não registrados.</p>
                <a href="preencher_info_empresa.php" class="btn">Adicionar Informações</a>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>