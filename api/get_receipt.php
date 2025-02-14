<?php
require_once('../includes/config.php');

    if (isset($_GET['sale_id'])) {
        $sale_id = intval($_GET['sale_id']);

        $query = "SELECT receipt FROM sales WHERE sale_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $sale_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            echo $row['receipt'];
        } else {
            echo "Recibo não encontrado.";
        }
    } else {
        echo "ID da venda não fornecido.";
    }
?>