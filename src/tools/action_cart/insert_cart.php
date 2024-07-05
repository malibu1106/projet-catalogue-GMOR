<?php
session_start();
require_once("../../elements/connect.php");

// Adicione logs para depuração
error_log("Session data: " . print_r($_SESSION, true));
error_log("POST data: " . print_r($_POST, true));

if (!isset($_SESSION['user']['id']) || empty($_SESSION['user']['id'])) {
    error_log("User not logged in. Redirecting to login page.");
    header("Location: ../login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user']['id'];
    $product_id = $_POST['product_id'];

    // Verificar se o produto já está no carrinho do usuário
    $check_cart = $db->prepare("SELECT * FROM carts WHERE user_id = ? AND product_id = ?");
    $check_cart->execute([$user_id, $product_id]);
    $existing_cart_item = $check_cart->fetch();

    if ($existing_cart_item) {
        // Se o produto já estiver no carrinho, aumente a quantidade
        $update_quantity = $db->prepare("UPDATE carts SET product_quantity = product_quantity + 1 WHERE id = ?");
        $update_quantity->execute([$existing_cart_item['id']]);
        error_log("Updated quantity for existing cart item.");
    } else {
        // Se o produto não estiver no carrinho, adicione-o
        $insert_cart = $db->prepare("INSERT INTO carts (user_id, product_id, product_quantity) VALUES (?, ?, 1)");
        $insert_cart->execute([$user_id, $product_id]);
        error_log("Inserted new cart item.");
    }

    // Redirecionar de volta para a página do produto ou para o carrinho
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
} else {
    error_log("Invalid request method.");
    header("Location: ../../../index.php");
    exit;
}