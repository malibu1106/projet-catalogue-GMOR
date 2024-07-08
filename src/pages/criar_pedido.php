<?php
session_start();
require_once("../elements/connect.php");

// Verifica se o usuário está logado
if (!isset($_SESSION['user']['id'])) {
    echo json_encode(['success' => false, 'message' => 'Usuário não está logado']);
    exit;
}

$user_id = $_SESSION['user']['id'];

// Recebe os dados do pedido
$data = json_decode(file_get_contents('php://input'), true);

$endereco = $data['endereco'];
$metodo_pagamento = $data['metodo_pagamento'];
$total_amount = $data['total_amount'];

// Insere o pedido na tabela orders
$sql = "INSERT INTO orders (user_id, order_date, status, total_amount, shipping_address, payment_method) 
        VALUES (:user_id, NOW(), 'en attente', :total_amount, :shipping_address, :payment_method)";

$stmt = $db->prepare($sql);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->bindParam(':total_amount', $total_amount, PDO::PARAM_STR);
$stmt->bindParam(':shipping_address', $endereco, PDO::PARAM_STR);
$stmt->bindParam(':payment_method', $metodo_pagamento, PDO::PARAM_STR);

try {
    $stmt->execute();
    $order_id = $db->lastInsertId();

    // Aqui você pode adicionar lógica para mover os itens do carrinho para uma tabela de itens do pedido

    // Limpa o carrinho do usuário
    $sql_clear_cart = "DELETE FROM carts WHERE user_id = :user_id";
    $stmt_clear_cart = $db->prepare($sql_clear_cart);
    $stmt_clear_cart->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt_clear_cart->execute();

    echo json_encode(['success' => true, 'order_id' => $order_id]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Erro ao criar o pedido: ' . $e->getMessage()]);
}