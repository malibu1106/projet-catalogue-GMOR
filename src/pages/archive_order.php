<?php
session_start();
require_once("../elements/connect.php");

if (isset($_GET['id'])) {
    $order_id = $_GET['id'];

    try {
        $db->beginTransaction();

        // Verificar se a ordem já está arquivada
        $sql_check = "SELECT COUNT(*) FROM archive WHERE order_id = :order_id";
        $stmt_check = $db->prepare($sql_check);
        $stmt_check->bindParam(':order_id', $order_id, PDO::PARAM_INT);
        $stmt_check->execute();
        $is_archived = $stmt_check->fetchColumn();

        if ($is_archived > 0) {
            $_SESSION['info'] = "Cette commande est déjà archivée";
            $db->commit();
            header("Location: ../pages/commandes.php");
            exit();
        }

        // Recuperar os dados da ordem
        $sql_order = "SELECT * FROM orders WHERE id = :order_id";
        $stmt_order = $db->prepare($sql_order);
        $stmt_order->bindParam(':order_id', $order_id, PDO::PARAM_INT);
        $stmt_order->execute();
        $order = $stmt_order->fetch(PDO::FETCH_ASSOC);

        // Recuperar os itens da ordem
        $sql_items = "SELECT * FROM order_items WHERE order_id = :order_id";
        $stmt_items = $db->prepare($sql_items);
        $stmt_items->bindParam(':order_id', $order_id, PDO::PARAM_INT);
        $stmt_items->execute();
        $items = $stmt_items->fetchAll(PDO::FETCH_ASSOC);

        // Inserir dados na tabela archive
        foreach ($items as $item) {
            $sql_archive = "INSERT INTO archive (user_id, cart_id, order_id, order_items_id, product_id, quantity_id, total_amount, shipping_address, payment_method, status, order_date) 
                            VALUES (:user_id, :cart_id, :order_id, :order_items_id, :product_id, :quantity_id, :total_amount, :shipping_address, :payment_method, :status, :order_date)";
            $stmt_archive = $db->prepare($sql_archive);
            $stmt_archive->execute([
                ':user_id' => $order['user_id'] ?? 0,
                ':cart_id' => $order['cart_id'],
                ':order_id' => $order_id,
                ':order_items_id' => $item['id'],
                ':product_id' => $item['product_id'],
                ':quantity_id' => $item['quantity'],
                ':total_amount' => $item['price'] * $item['quantity'],
                ':shipping_address' => $order['shipping_address'] ?? null,
                ':payment_method' => $order['payment_method'] ?? null,
                ':status' => $order['status'] ?? 'en attente',
                ':order_date' => $order['order_date'] ?? date('Y-m-d H:i:s')
            ]);
        }

        $db->commit();
        $_SESSION['success'] = "Commande archivée avec succès.";
    } catch (Exception $e) {
        $db->rollBack();
        $_SESSION['error'] = "Erreur lors de l'archivage de la commande: " . $e->getMessage();
    }

    header("Location: ../pages/commandes.php");
    exit();
} else {
    $_SESSION['error'] = "ID de commande non fourni.";
    header("Location: ../pages/commandes.php");
    exit();
}