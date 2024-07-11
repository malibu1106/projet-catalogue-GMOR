<?php
session_start();
require_once("../elements/connect.php");

if (isset($_GET['id'])) {
    $order_id = $_GET['id'];

    try {
        $db->beginTransaction();

        // Vérifier si la commande est déjà archivée
        $sql_check = "SELECT COUNT(*) FROM archive WHERE order_id = :order_id";
        $stmt_check = $db->prepare($sql_check);
        $stmt_check->bindParam(':order_id', $order_id, PDO::PARAM_INT);
        $stmt_check->execute();
        $is_archived = $stmt_check->fetchColumn();

        if ($is_archived > 0) {
            $_SESSION['info'] = "Cette commandante est déjà sur l'archive ";
            $db->commit();
            header("Location: ../pages/commandes.php");
            exit();
        }

        // Récupérer les données de la commande
        $sql_order = "SELECT * FROM orders WHERE id = :order_id";
        $stmt_order = $db->prepare($sql_order);
        $stmt_order->bindParam(':order_id', $order_id, PDO::PARAM_INT);
        $stmt_order->execute();
        $order = $stmt_order->fetch(PDO::FETCH_ASSOC);

        // Récupérer les items de la commande
        $sql_items = "SELECT * FROM order_items WHERE order_id = :order_id";
        $stmt_items = $db->prepare($sql_items);
        $stmt_items->bindParam(':order_id', $order_id, PDO::PARAM_INT);
        $stmt_items->execute();
        $items = $stmt_items->fetchAll(PDO::FETCH_ASSOC);

        // Insérer des données dans la table archive
        foreach ($items as $item) {
            $sql_archive = "INSERT INTO archive (cart_id, order_id, order_items_id, product_id, quantity_id, price_id) 
                            VALUES (:cart_id, :order_id, :order_items_id, :product_id, :quantity_id, :price_id)";
            $stmt_archive = $db->prepare($sql_archive);
            $stmt_archive->execute([
                ':cart_id' => $order['cart_id'],
                ':order_id' => $order_id,
                ':order_items_id' => $item['id'],
                ':product_id' => $item['product_id'],
                ':quantity_id' => $item['quantity'],
                ':price_id' => $item['price']
            ]);
        }


        // Message d’erreur ou succès lors de l’archivage des données 
        $db->commit();
        $_SESSION['success'] = "Commande archivée avec succès.";
    } catch (Exception $e) {
        $db->rollBack();
        $_SESSION['error'] = "Erreur de classement commande: " . $e->getMessage();
    }

    header("Location: ../pages/commandes.php");
    exit();
    // Message d’erreur manquant de l’id  
} else {
    $_SESSION['error'] = "ID de commande non fourni.";
    header("Location: ../pages/commandes.php");
    exit();
}
