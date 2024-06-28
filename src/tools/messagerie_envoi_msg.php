<?php
session_start();



date_default_timezone_set('Europe/Paris');




require_once("../elements/connect.php");


if (isset($_POST['conversation_message']) && isset($_POST['sender_id']) && isset($_POST['receiver_id'])) {
    $message = $_POST['conversation_message'];
    $sender_id = $_POST['sender_id'];
    $receiver_id = $_POST['receiver_id'];

    $sql = "INSERT INTO messages (sender_user_id, receiver_user_id, message_content, datetime) VALUES (:sender_id, :receiver_id, :message, NOW())";
    $query = $db->prepare($sql);
    $query->bindValue(':sender_id', $sender_id);
    $query->bindValue(':receiver_id', $receiver_id);
    $query->bindValue(':message', $message);
    $query->execute();

    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error']);
}
?>
