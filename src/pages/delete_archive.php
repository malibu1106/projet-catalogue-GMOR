<?php
session_start();
require_once("../elements/connect.php");

if (isset($_GET['id'])) {
    $order_id = $_GET['id'];

    try {
        $db->beginTransaction();

        // Verificar se a comanda existe no arquivo
        $sql_check = "SELECT COUNT(*) FROM archive WHERE order_id = :order_id";
        $stmt_check = $db->prepare($sql_check);
        $stmt_check->bindParam(':order_id', $order_id, PDO::PARAM_INT);
        $stmt_check->execute();
        $is_archived = $stmt_check->fetchColumn();

        if ($is_archived == 0) {
            $_SESSION['error'] = "Cette commande n'est pas dans l'archive.";
            $db->rollBack();
            header("Location: ../pages/archives_cde.php");
            exit();
        }

        // Deletar a comanda do arquivo
        $sql_delete = "DELETE FROM archive WHERE order_id = :order_id";
        $stmt_delete = $db->prepare($sql_delete);
        $stmt_delete->bindParam(':order_id', $order_id, PDO::PARAM_INT);
        $stmt_delete->execute();

        // Opcional: Deletar a comanda da tabela orders se você quiser removê-la completamente
        // Descomente as linhas abaixo se quiser esta funcionalidade
        /*
        $sql_delete_order = "DELETE FROM orders WHERE id = :order_id";
        $stmt_delete_order = $db->prepare($sql_delete_order);
        $stmt_delete_order->bindParam(':order_id', $order_id, PDO::PARAM_INT);
        $stmt_delete_order->execute();
        */

        $db->commit();
        $_SESSION['success'] = "Commande supprimée de l'archive avec succès.";
    } catch (Exception $e) {
        $db->rollBack();
        $_SESSION['error'] = "Erreur lors de la suppression de la commande: " . $e->getMessage();
    }

    header("Location: ../pages/archives_cde.php");
    exit();
} else {
    $_SESSION['error'] = "ID de commande non fourni.";
    header("Location: ../pages/archives_cde.php");
    exit();
}