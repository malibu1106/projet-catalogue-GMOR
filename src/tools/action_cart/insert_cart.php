<?php
session_start();
require_once("../elements/connect.php");

if (!isset($_SESSION['user_id'])) {
    // Redirecionar para a página de login se o usuário não estiver conectado
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $product_id = $_POST['product_id'];

    // Verificar se o produto já está no carrinho do usuário
    $check_cart = $db->prepare("SELECT * FROM carts WHERE user_id = ? AND product_id = ?");
    $check_cart->execute([$user_id, $product_id]);
    $existing_cart_item = $check_cart->fetch();

    if ($existing_cart_item) {
        // Se o produto já estiver no carrinho, aumente a quantidade
        $update_quantity = $db->prepare("UPDATE carts SET product_quantity = product_quantity + 1 WHERE id = ?");
        $update_quantity->execute([$existing_cart_item['id']]);
    } else {
        // Se o produto não estiver no carrinho, adicione-o
        $insert_cart = $db->prepare("INSERT INTO carts (user_id, product_id, product_quantity) VALUES (?, ?, 1)");
        $insert_cart->execute([$user_id, $product_id]);
    }

    // Redirecionar de volta para a página do produto ou para o carrinho
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}