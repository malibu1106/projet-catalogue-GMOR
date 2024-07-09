<?php
session_start();
require_once("../elements/connect.php");

// Active les erreurs PDO pour le débogage
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['user']['id'])) {
    echo json_encode(['success' => false, 'message' => 'Utilisateur non connecté']);
    exit;
}

$user_id = $_SESSION['user']['id'];

// Vérifie si le panier de l'utilisateur est vide
$sql_check_cart = "SELECT COUNT(*) FROM carts WHERE user_id = :user_id";
$stmt_check_cart = $db->prepare($sql_check_cart);
$stmt_check_cart->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt_check_cart->execute();
$cart_count = $stmt_check_cart->fetchColumn();

if ($cart_count == 0) {
    echo json_encode(['success' => false, 'message' => 'Le panier est vide']);
    exit;
}

// Récupère les données de commande
$data = json_decode(file_get_contents('php://input'), true);

$endereco = $data['shipping_address'];
$metodo_pagamento = $data['payment_method'];

// Calcule le total_amount basé sur les éléments du panier
$sql_total = "SELECT SUM(c.product_quantity * p.price) AS total_amount 
                FROM carts c 
                JOIN products p ON c.product_id = p.id 
                WHERE c.user_id = :user_id";
$stmt_total = $db->prepare($sql_total);
$stmt_total->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt_total->execute();
$result_total = $stmt_total->fetch(PDO::FETCH_ASSOC);
$total_amount = $result_total['total_amount'];

// Insère la commande dans la table orders
$sql_insert_order = "INSERT INTO orders (user_id, order_date, status, total_amount, shipping_address, payment_method) 
                    VALUES (:user_id, NOW(), 'en attente', :total_amount, :shipping_address, :payment_method)";

$stmt_insert_order = $db->prepare($sql_insert_order);
$stmt_insert_order->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt_insert_order->bindParam(':total_amount', $total_amount, PDO::PARAM_STR);
$stmt_insert_order->bindParam(':shipping_address', $endereco, PDO::PARAM_STR);
$stmt_insert_order->bindParam(':payment_method', $metodo_pagamento, PDO::PARAM_STR);

try {
    $db->beginTransaction();

    // Exécute l'insertion de la commande
    $stmt_insert_order->execute();
    $order_id = $db->lastInsertId();

    // Déplace les articles du panier vers la table order_items
    $sql_move_items = "INSERT INTO order_items (order_id, cart_id, product_id, quantity, price)
                        SELECT :order_id, NULL, c.product_id, c.product_quantity, p.price
                        FROM carts c
                        JOIN products p ON c.product_id = p.id
                        WHERE c.user_id = :user_id";
    $stmt_move_items = $db->prepare($sql_move_items);
    $stmt_move_items->bindParam(':order_id', $order_id, PDO::PARAM_INT);
    $stmt_move_items->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt_move_items->execute();


    // Nettoie le panier de l'utilisateur
    $sql_clear_cart = "DELETE FROM carts WHERE user_id = :user_id";
    $stmt_clear_cart = $db->prepare($sql_clear_cart);
    $stmt_clear_cart->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt_clear_cart->execute();

    $db->commit();
    echo json_encode(['success' => true, 'order_id' => $order_id]);
} catch (PDOException $e) {
    $db->rollBack();
    echo json_encode(['success' => false, 'message' => 'Erreur lors de la création de la commande: ' . $e->getMessage()]);
}
?>
