<?php
session_start();
require_once("../elements/connect.php");

// Verifica se o usuário está logado
if (!isset($_SESSION['user']['id'])) {
    echo json_encode(['success' => false, 'message' => 'Usuário não está logado']);
    exit;
}

$user_id = $_SESSION['user']['id'];

// Verifica se o carrinho está vazio
$sql_check_cart = "SELECT COUNT(*) FROM carts WHERE user_id = :user_id";
$stmt_check_cart = $db->prepare($sql_check_cart);
$stmt_check_cart->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt_check_cart->execute();
$cart_count = $stmt_check_cart->fetchColumn();

if ($cart_count == 0) {
    echo json_encode(['success' => false, 'message' => 'O carrinho está vazio']);
    exit;
}

// Recebe os dados do pedido
$data = json_decode(file_get_contents('php://input'), true);

$endereco = $data['endereco'];
$metodo_pagamento = $data['metodo_pagamento'];

// Calcula o total_amount baseado nos itens do carrinho
$sql_total = "SELECT SUM(c.product_quantity * p.price) AS total_amount 
              FROM carts c 
              JOIN products p ON c.product_id = p.id 
              WHERE c.user_id = :user_id";
$stmt_total = $db->prepare($sql_total);
$stmt_total->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt_total->execute();
$result_total = $stmt_total->fetch(PDO::FETCH_ASSOC);
$total_amount = $result_total['total_amount'];

// Insere o pedido na tabela orders
$sql = "INSERT INTO orders (user_id, order_date, status, total_amount, shipping_address, payment_method) 
        VALUES (:user_id, NOW(), 'en attente', :total_amount, :shipping_address, :payment_method)";

$stmt = $db->prepare($sql);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->bindParam(':total_amount', $total_amount, PDO::PARAM_STR);
$stmt->bindParam(':shipping_address', $endereco, PDO::PARAM_STR);
$stmt->bindParam(':payment_method', $metodo_pagamento, PDO::PARAM_STR);

try {
    $db->beginTransaction();
    
    $stmt->execute();
    $order_id = $db->lastInsertId();

    // Move os itens do carrinho para a tabela order_items
    $sql_move_items = "INSERT INTO order_items (order_id, product_id, quantity, price)
                       SELECT :order_id, c.product_id, c.product_quantity, p.price
                       FROM carts c
                       JOIN products p ON c.product_id = p.id
                       WHERE c.user_id = :user_id";
    $stmt_move_items = $db->prepare($sql_move_items);
    $stmt_move_items->bindParam(':order_id', $order_id, PDO::PARAM_INT);
    $stmt_move_items->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt_move_items->execute();

    // Limpa o carrinho do usuário
    $sql_clear_cart = "DELETE FROM carts WHERE user_id = :user_id";
    $stmt_clear_cart = $db->prepare($sql_clear_cart);
    $stmt_clear_cart->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt_clear_cart->execute();

    $db->commit();
    echo json_encode(['success' => true, 'order_id' => $order_id]);
} catch (PDOException $e) {
    $db->rollBack();
    echo json_encode(['success' => false, 'message' => 'Erro ao criar o pedido: ' . $e->getMessage()]);
}