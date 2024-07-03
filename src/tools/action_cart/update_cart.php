<?php
session_start();
require_once("../../elements/connect.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user']['id'])) {
    $cart_id = filter_input(INPUT_POST, 'cart_id', FILTER_VALIDATE_INT);
    $action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING);
    $user_id = $_SESSION['user']['id'];

    // Verifica se o item do carrinho pertence ao usuário atual
    $check_sql = "SELECT id FROM carts WHERE id = :cart_id AND user_id = :user_id";
    $check_stmt = $db->prepare($check_sql);
    $check_stmt->bindParam(':cart_id', $cart_id, PDO::PARAM_INT);
    $check_stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $check_stmt->execute();
    if (!$check_stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'Item não encontrado no carrinho do usuário']);
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

        // Busca as informações atualizadas do item do carrinho
        if ($action !== 'delete') {
            $sql_select = "SELECT c.product_quantity, p.price FROM carts c JOIN products p ON c.product_id = p.id WHERE c.id = :cart_id";
            $stmt_select = $db->prepare($sql_select);
            $stmt_select->bindParam(':cart_id', $cart_id, PDO::PARAM_INT);
            $stmt_select->execute();
            $result = $stmt_select->fetch(PDO::FETCH_ASSOC);
        }

        // Busca os totais atualizados do carrinho
        $sql_total = "SELECT SUM(c.product_quantity) AS cart_total, SUM(c.product_quantity * p.price) AS total_price 
                      FROM carts c JOIN products p ON c.product_id = p.id 
                      WHERE c.user_id = :user_id";
        $stmt_total = $db->prepare($sql_total);
        $stmt_total->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt_total->execute();
        $result_total = $stmt_total->fetch(PDO::FETCH_ASSOC);

        $db->commit();

        echo json_encode([
            'success' => true,
            'new_quantity' => $result['product_quantity'] ?? 0,
            'item_price' => $result['price'] ?? 0,
            'cart_total' => $result_total['cart_total'] ?? 0,
            'total_price' => number_format($result_total['total_price'] ?? 0, 2)
        ]);

    } catch (Exception $e) {
        $db->rollBack();
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método inválido']);
}