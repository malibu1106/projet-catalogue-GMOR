<?php
session_start();
require_once("../elements/connect.php");

if (!isset($_SESSION['user']['id']) || !isset($_GET['with_user_id'])) {
    echo json_encode([]);
    exit;
}

$user_id = $_SESSION['user']['id'];
$conversation_user_id = $_GET['with_user_id'];

$sql = "SELECT * FROM messages 
    WHERE (sender_user_id = :user_id OR receiver_user_id = :user_id) 
    AND (sender_user_id = :conversation_user_id OR receiver_user_id = :conversation_user_id) 
    ORDER BY datetime ASC";

$query = $db->prepare($sql);
$query->bindValue(':user_id', $user_id, PDO::PARAM_INT);
$query->bindValue(':conversation_user_id', $conversation_user_id, PDO::PARAM_INT);
$query->execute();
$messages = $query->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($messages);
?>
