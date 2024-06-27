<?php
session_start(); 


if (isset($_POST) && !empty($_POST['conversation_message']) && !empty($_POST['sender_id']) && !empty($_POST['receiver_id'])){

    $datetime = (new DateTime())->format('Y-m-d H:i:s');
    $sender_user_id = $_POST['sender_id'];
    $receiver_user_id = $_POST['receiver_id'];
    $message_content = $_POST['conversation_message'];


    // Inclusion du fichier de connexion à la base de données
require_once("../elements/connect.php");

// Requête SQL pour récupérer les messages de l'utilisateur connecté
$sql = "INSERT INTO messages (sender_user_id, receiver_user_id, datetime, message_content)
VALUES (:sender_user_id, :receiver_user_id, :datetime, :message_content) ";

// Préparation de la requête
$query = $db->prepare($sql);
$query->bindValue(':datetime', $datetime);
$query->bindValue(':sender_user_id', $sender_user_id);
$query->bindValue(':receiver_user_id', $receiver_user_id);
$query->bindValue(':message_content', $message_content);

// Exécution de la requête et stockage des données dans une variable
$query->execute();

header('Location: ../pages/messagerie.php?with_user_id='.$receiver_user_id.'#ancre_dernier_message');

}
?>