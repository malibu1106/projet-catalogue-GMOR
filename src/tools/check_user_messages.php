<?php
require_once("../elements/connect.php");
$sql = "SELECT * FROM messages WHERE receiver_user_id = :user_id AND message_read = 0";
            $query = $db->prepare($sql);            
            $query->bindValue(':user_id', $_SESSION['user']['id']);
            $query->execute();
            $unread = $query->fetch(PDO::FETCH_ASSOC);
?>