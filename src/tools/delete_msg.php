<?php
session_start(); 
require_once("../elements/connect.php");

// Récupérer le message en fonction de l'ID passé en paramètre
$sql = "SELECT * FROM messages WHERE message_id = :message_id";
$query = $db->prepare($sql);
$query->bindValue(':message_id', $_GET['id']);
$query->execute();
$message = $query->fetch(PDO::FETCH_ASSOC);


// Vérifier si l'utilisateur connecté est l'expéditeur du message
if ($message['sender_user_id'] === $_SESSION['user']['id']) {

    // Supprimer le message
    $sql = "DELETE FROM messages WHERE message_id = :message_id";
    $query = $db->prepare($sql);
    $query->bindValue(':message_id', $_GET['id']);
    $query->execute();

    // Redirection après suppression du message
    header('Location: ../pages/messagerie.php?with_user_id=' . $message['receiver_user_id'] . '#ancre_dernier_message');
    exit();
}
?>