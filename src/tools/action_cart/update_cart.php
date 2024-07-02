<?php
require_once("../elements/connect.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cart_id = $_POST['cart_id'];
    $action = $_POST['action'];

    switch($action) {
        case 'add':
            $sql = "UPDATE carts SET product_quantity = product_quantity + 1 WHERE id = :cart_id";
            break;
        case 'subtract':
            $sql = "UPDATE carts SET product_quantity = GREATEST(product_quantity - 1, 0) WHERE id = :cart_id";
            break;
        case 'delete':
            $sql = "DELETE FROM carts WHERE id = :cart_id";
            break;
        default:
            echo json_encode(['success' => false, 'message' => 'Ação inválida']);
            exit;
    }

    $stmt = $db->prepare($sql);
    $stmt->bindParam(':cart_id', $cart_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        if ($action !== 'delete') {
            $sql_select = "SELECT product_quantity FROM carts WHERE id = :cart_id";
            $stmt_select = $db->prepare($sql_select);
            $stmt_select->bindParam(':cart_id', $cart_id, PDO::PARAM_INT);
            $stmt_select->execute();
            $result = $stmt_select->fetch(PDO::FETCH_ASSOC);

            echo json_encode(['success' => true, 'new_quantity' => $result['product_quantity']]);
        } else {
            echo json_encode(['success' => true]);
        }
    } else {
        echo json_encode(['success' => false]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método inválido']);
}