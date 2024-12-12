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

    // Inicializa variáveis para os dados da empresa
    $cnpj = $endereco = $numero = $bairro = $estado = $cidade = '';

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $cnpj = $row['cnpj'];
        $endereco = $row['address'];
        $numero = $row['number'];
        $bairro = $row['neighborhood'];
        $estado = $row['state'];
        $cidade = $row['city'];
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
            <h2>Complete as informações da sua empresa</h2>
            
            <form action="../process/processa_info_empresa.php" method="POST">
                <label for="cnpj">CNPJ:</label>
                <input type="text" id="cnpj" name="cnpj" value="<?php echo htmlspecialchars($cnpj); ?>" required>

                <label for="endereco">Endereço:</label>
                <input type="text" id="endereco" name="endereco" value="<?php echo htmlspecialchars($endereco); ?>" required>

                <label for="numero">Número:</label>
                <input type="text" id="numero" name="numero" value="<?php echo htmlspecialchars($numero); ?>" required>

                <label for="bairro">Bairro:</label>
                <input type="text" id="bairro" name="bairro" value="<?php echo htmlspecialchars($bairro); ?>" required>

                <label for="estado">Estado:</label>
                <select id="estado" name="estado" required>
                    <option value="">Selecione o Estado</option>
                    <option value="AC" <?php if ($estado == 'AC') echo 'selected'; ?>>Acre</option>
                    <option value="AL" <?php if ($estado == 'AL') echo 'selected'; ?>>Alagoas</option>
                    <option value="AP" <?php if ($estado == 'AP') echo 'selected'; ?>>Amapá</option>
                    <option value="AM" <?php if ($estado == 'AM') echo 'selected'; ?>>Amazonas</option>
                    <option value="BA" <?php if ($estado == 'BA') echo 'selected'; ?>>Bahia</option>
                    <option value="CE" <?php if ($estado == 'CE') echo 'selected'; ?>>Ceará</option>
                    <option value="DF" <?php if ($estado == 'DF') echo 'selected'; ?>>Distrito Federal</option>
                    <option value="ES" <?php if ($estado == 'ES') echo 'selected'; ?>>Espírito Santo</option>
                    <option value="GO" <?php if ($estado == 'GO') echo 'selected'; ?>>Goiás</option>
                    <option value="MA" <?php if ($estado == 'MA') echo 'selected'; ?>>Maranhão</option>
                    <option value="MT" <?php if ($estado == 'MT') echo 'selected'; ?>>Mato Grosso</option>
                    <option value="MS" <?php if ($estado == 'MS') echo 'selected'; ?>>Mato Grosso do Sul</option>
                    <option value="MG" <?php if ($estado == 'MG') echo 'selected'; ?>>Minas Gerais</option>
                    <option value="PA" <?php if ($estado == 'PA') echo 'selected'; ?>>Pará</option>
                    <option value="PB" <?php if ($estado == 'PB') echo 'selected'; ?>>Paraíba</option>
                    <option value="PR" <?php if ($estado == 'PR') echo 'selected'; ?>>Paraná</option>
                    <option value="PE" <?php if ($estado == 'PE') echo 'selected'; ?>>Pernambuco</option>
                    <option value="PI" <?php if ($estado == 'PI') echo 'selected'; ?>>Piauí</option>
                    <option value="RJ" <?php if ($estado == 'RJ') echo 'selected'; ?>>Rio de Janeiro</option>
                    <option value="RN" <?php if ($estado == 'RN') echo 'selected'; ?>>Rio Grande do Norte</option>
                    <option value="RS" <?php if ($estado == 'RS') echo 'selected'; ?>>Rio Grande do Sul</option>
                    <option value="RO" <?php if ($estado == 'RO') echo 'selected'; ?>>Rondônia</option>
                    <option value="RR" <?php if ($estado == 'RR') echo 'selected'; ?>>Roraima</option>
                    <option value="SC" <?php if ($estado == 'SC') echo 'selected'; ?>>Santa Catarina</option>
                    <option value="SP" <?php if ($estado == 'SP') echo 'selected'; ?>>São Paulo</option>
                    <option value="SE" <?php if ($estado == 'SE') echo 'selected'; ?>>Sergipe</option>
                    <option value="TO" <?php if ($estado == 'TO') echo 'selected'; ?>>Tocantins</option>
                </select>

                <label for="cidade">Cidade:</label>
                <input type="text" id="cidade" name="cidade" value="<?php echo htmlspecialchars($cidade); ?>" required>

                <button type="submit">Salvar Informações</button>
            </form>
        </div>
    </div>
</body>
</html>