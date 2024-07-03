<?php
require_once("../../elements/connect.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validação e sanitização de entrada
    $cart_id = filter_input(INPUT_POST, 'cart_id', FILTER_VALIDATE_INT);
    $action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING);

    if (!$cart_id || !in_array($action, ['add', 'subtract', 'delete'])) {
        echo json_encode(['success' => false, 'message' => 'Parâmetros inválidos']);
        exit;
    }

    try {
        $db->beginTransaction();

        switch($action) {
            case 'add':
                $sql = "UPDATE carts SET product_quantity = LEAST(product_quantity + 1, 99) WHERE id = :cart_id";
                break;
                
            case 'subtract':
                $sql = "UPDATE carts SET product_quantity = GREATEST(product_quantity - 1, 0) WHERE id = :cart_id";
                break;
            case 'delete':
                $sql = "DELETE FROM carts WHERE id = :cart_id";
                break;
        }

        $stmt = $db->prepare($sql);
        $stmt->bindParam(':cart_id', $cart_id, PDO::PARAM_INT);

        if (!$stmt->execute()) {
            throw new Exception('Erro ao executar a query: ' . $stmt->errorInfo()[2]);
        }

        // Busca a nova quantidade e o total do carrinho
        if ($action !== 'delete') {
            $sql_select = "SELECT product_quantity FROM carts WHERE id = :cart_id";
            $stmt_select = $db->prepare($sql_select);
            $stmt_select->bindParam(':cart_id', $cart_id, PDO::PARAM_INT);
            $stmt_select->execute();
            $result = $stmt_select->fetch(PDO::FETCH_ASSOC);
        }

        $sql_total = "SELECT SUM(c.product_quantity) AS cart_total, SUM(c.product_quantity * p.price) AS total_price 
                      FROM carts c JOIN products p ON c.product_id = p.id";
        $stmt_total = $db->prepare($sql_total);
        $stmt_total->execute();
        $result_total = $stmt_total->fetch(PDO::FETCH_ASSOC);

        $db->commit();

        echo json_encode([
            'success' => true,
            'new_quantity' => $result['product_quantity'] ?? 0,
            'cart_total' => $result_total['total_product'] ?? 0,
            'total_price' => number_format($result_total['total_price'] ?? 0, 2)
        ]);

    } catch (Exception $e) {
        $db->rollBack();
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método inválido']);
}
// Verificação de existência do item: Antes de realizar a ação, verifique se o item existe no carrinho:
    $check_sql = "SELECT id FROM carts WHERE id = :cart_id";
    $check_stmt = $db->prepare($check_sql);
    $check_stmt->bindParam(':cart_id', $cart_id, PDO::PARAM_INT);
    $check_stmt->execute();
    if (!$check_stmt->fetch()) {
        throw new Exception('Item não encontrado no carrinho');
    }

    