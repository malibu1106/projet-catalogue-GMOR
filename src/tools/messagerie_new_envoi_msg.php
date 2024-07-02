<?php
// echo '<pre>';
// print_r($_POST);
// echo '</pre>';
require_once("../elements/connect.php");
if (isset($_POST['new_sender_id']) && isset($_POST['new_receiver_id'])) {
    $new_sender_id = $_POST['new_sender_id'];
    $new_receiver_id = $_POST['new_receiver_id'];

    // Vérifier si un message texte est envoyé
    if (isset($_POST['new_conversation_message']) && !empty($_POST['new_conversation_message'])) {
        $message = $_POST['new_conversation_message'];

        $sql = "INSERT INTO messages (sender_user_id, receiver_user_id, message_content, datetime) VALUES (:sender_id, :receiver_id, :message_content, NOW())";
        $query = $db->prepare($sql);
        $query->bindValue(':sender_id', $new_sender_id);
        $query->bindValue(':receiver_id', $new_receiver_id);
        $query->bindValue(':message_content', $message);
        $query->execute();}


        $sql = "INSERT INTO conversations (user_id_1, user_id_2) VALUES (:user_id_1, :user_id_2)";
        $query = $db->prepare($sql);
        $query->bindValue(':user_id_1', $new_sender_id);
        $query->bindValue(':user_id_2', $new_receiver_id);
        $query->execute();




        header("Location: ../pages/messagerie.php?with_user_id=" . urlencode($new_receiver_id) . "#ancre_dernier_message");

    }










?>