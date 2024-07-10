<?php
session_start();
require_once("../elements/connect.php");

if (isset($_GET['id'])) {
    $orderId = $_GET['id'];
    
    // Adicionar um campo 'archived' à tabela 'orders' em vez de alterar o status
    $sql = "UPDATE orders SET archived = 1 WHERE id = :id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':id', $orderId, PDO::PARAM_INT);
    
    if ($stmt->execute()) {
        $_SESSION['message'] = "Commande archivée avec succès.";
    } else {
        $_SESSION['error'] = "Erreur d'archivage de la commande.";
    }
}

header("Location: ../backoffice/commandes.php");
exit();