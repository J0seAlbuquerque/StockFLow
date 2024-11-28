<?php
    // Verifica se o usuário está logado
    session_start();
    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    }
    require_once '../includes/config.php';

    // Pega o ID do usuário logado
    $user_id = $_SESSION['user_id'];

    // Verifica se o usuário já preencheu as informações da empresa
    $query = $conn->prepare("SELECT * FROM company_info WHERE company_id = ?");
    $query->bind_param('i', $user_id);
    $query->execute();
    $result = $query->get_result();

    // Se a empresa já tiver sido cadastrada, redireciona para a próxima página
    if ($result->num_rows > 0) {
        header('Location: homepage.php');  // Ou qualquer outra página do sistema
        exit;
    }
?>


<head>
<title>Informações da Empresa</title>
<link rel="stylesheet" href="../assets/css/_prench_i.css">
</head>
    <div class="container">
        <div class="box">
            <h1 class="site-name">StockFlow</h1>
            <h2>Complete as informações da sua empresa</h2>
            
            <form action="../process/processa_info_empresa.php" method="POST">
                <label for="cnpj">CNPJ:</label>
                <input type="text" id="cnpj" name="cnpj" required>

                <label for="endereco">Endereço:</label>
                <input type="text" id="endereco" name="endereco" required>

                <label for="numero">Número:</label>
                <input type="text" id="numero" name="numero" required>

                <label for="bairro">Bairro:</label>
                <input type="text" id="bairro" name="bairro" required>

                <label for="estado">Estado:</label>
                <select id="estado" name="estado" required>
                    <option value="">Selecione o Estado</option>
                    <option value="AC">Acre</option>
                    <option value="AL">Alagoas</option>
                    <option value="AP">Amapá</option>
                    <option value="AM">Amazonas</option>
                    <option value="BA">Bahia</option>
                    <option value="CE">Ceará</option>
                    <option value="DF">Distrito Federal</option>
                    <option value="ES">Espírito Santo</option>
                    <option value="GO">Goiás</option>
                    <option value="MA">Maranhão</option>
                    <option value="MT">Mato Grosso</option>
                    <option value="MS">Mato Grosso do Sul</option>
                    <option value="MG">Minas Gerais</option>
                    <option value="PA">Pará</option>
                    <option value="PB">Paraíba</option>
                    <option value="PR">Paraná</option>
                    <option value="PE">Pernambuco</option>
                    <option value="PI">Piauí</option>
                    <option value="RJ">Rio de Janeiro</option>
                    <option value="RN">Rio Grande do Norte</option>
                    <option value="RS">Rio Grande do Sul</option>
                    <option value="RO">Rondônia</option>
                    <option value="RR">Roraima</option>
                    <option value="SC">Santa Catarina</option>
                    <option value="SP">São Paulo</option>
                    <option value="SE">Sergipe</option>
                    <option value="TO">Tocantins</option>
                </select>

                <label for="cidade">Cidade:</label>
                <input type="text" id="cidade" name="cidade" required>

                <button type="submit">Salvar Informações</button>
            </form>
        </div>
    </div>