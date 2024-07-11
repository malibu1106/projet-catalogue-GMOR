<?php
session_start();

if (isset($_GET["id"]) && !empty($_GET["id"])) {
    require_once("../elements/connect.php");

    $id = strip_tags($_GET['id']);

    try {
        // Commencer une transaction
        $db->beginTransaction();

        // Suppression des éléments associés à la commande dans order_items
        $sql_delete_items = "DELETE FROM order_items WHERE order_id=:order_id";
        $requete_delete_items = $db->prepare($sql_delete_items);
        $requete_delete_items->bindValue(':order_id', $id, PDO::PARAM_INT);
        $requete_delete_items->execute();

        // Suppression de la commande de la base de données
        $sql_delete_order = "DELETE FROM orders WHERE id=:id";
        $requete_delete_order = $db->prepare($sql_delete_order);
        $requete_delete_order->bindValue(':id', $id, PDO::PARAM_INT);
        $requete_delete_order->execute();

        // Valider la transaction
        $db->commit();

        // Confirmation de la suppression
        $_SESSION['delete_confirm'] = true;
        $_SESSION['article_delete_id'] = $id;

    } catch (Exception $e) {
        // Annuler la transaction en cas d'erreur
        $db->rollBack();
        $_SESSION['delete_confirm'] = false;
        error_log("Erreur lors de la suppression de la commande : " . $e->getMessage());
    }

    require_once("../elements/disconnect.php");
    header('Location: ../pages/commandes.php');
} else {
    header('Location: ../pages/commandes.php');
}
?>
