<?php
session_start();

date_default_timezone_set('Europe/Paris');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once("../elements/connect.php");

$response = ['status' => 'error', 'message' => 'Unknown error occurred'];

try {
    if (isset($_POST['sender_id']) && isset($_POST['receiver_id'])) {
        $sender_id = $_POST['sender_id'];
        $receiver_id = $_POST['receiver_id'];

        // Vérifier si un message texte est envoyé
        if (isset($_POST['conversation_message']) && !empty($_POST['conversation_message']) && !isset($_FILES['fileInput'])) {
            $message = $_POST['conversation_message'];

            $sql = "INSERT INTO messages (sender_user_id, receiver_user_id, message_content, datetime) VALUES (:sender_id, :receiver_id, :message_content, NOW())";
            $query = $db->prepare($sql);
            $query->bindValue(':sender_id', $sender_id);
            $query->bindValue(':receiver_id', $receiver_id);
            $query->bindValue(':message_content', $message);
            $query->execute();

            $response = ['status' => 'success'];
        }

        $sql = "SELECT * FROM conversations 
        WHERE (user_id_1 = :sender_id AND user_id_2 = :receiver_id) 
           OR (user_id_1 = :receiver_id AND user_id_2 = :sender_id)";
        $query = $db->prepare($sql);
        $query->bindValue(':sender_id', $sender_id);
        $query->bindValue(':receiver_id', $receiver_id);
        $query->execute();
        $conversations = $query->fetchAll(PDO::FETCH_ASSOC);
        if(!$conversations){
            $sql = "INSERT INTO conversations (user_id_1, user_id_2) VALUES (:sender_id, :receiver_id)";
        $query = $db->prepare($sql);
        $query->bindValue(':sender_id', $sender_id);
        $query->bindValue(':receiver_id', $receiver_id);
        $query->execute();
        }






        
        // Vérifier si un fichier est envoyé
        elseif (isset($_FILES['fileInput']) && $_FILES['fileInput']['error'] == UPLOAD_ERR_OK && empty($_POST['conversation_message'])) {
            $fileTmpPath = $_FILES['fileInput']['tmp_name'];
            $fileName = $_FILES['fileInput']['name'];
            $fileSize = $_FILES['fileInput']['size'];
            $fileType = $_FILES['fileInput']['type'];
            $fileNameCmps = explode(".", $fileName);
            $fileExtension = strtolower(end($fileNameCmps));

            // Vérifier si l'extension est une image
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
            if (in_array($fileExtension, $allowedExtensions)) {
                $messageType = 'image';
            } else {
                $messageType = 'other';
            }

            // Générer un nom aléatoire pour le fichier
            $randomFileName = bin2hex(random_bytes(10)) . '.' . $fileExtension;

            $uploadFileDir = '../messages-files-storage/';
            $dest_path = $uploadFileDir . $randomFileName;

            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                $fileUrl = $uploadFileDir . $randomFileName;
                
                $sql = "INSERT INTO messages (sender_user_id, receiver_user_id, message_content, message_type, datetime) VALUES (:sender_id, :receiver_id, :message_content, :message_type, NOW())";
                $query = $db->prepare($sql);
                $query->bindValue(':sender_id', $sender_id);
                $query->bindValue(':receiver_id', $receiver_id);
                $query->bindValue(':message_content', $fileUrl);
                $query->bindValue(':message_type', $messageType);
                $query->execute();

                $response = ['status' => 'success'];
            } else {
                $response['message'] = 'There was an error moving the uploaded file.';
            }
        } else {
            $response['message'] = 'Invalid message or file provided, or both were submitted together.';
        }
    } else {
        $response['message'] = 'Invalid sender or receiver ID.';
    }
} catch (Exception $e) {
    $response['message'] = 'Exception: ' . $e->getMessage();
}

// Assurez-vous que la réponse est en JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
