<?php 
// Démarrer la session
session_start(); 
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messagerie</title>
    <!-- Inclusion de Bootstrap pour le style -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../CSS/style.css">
    <link rel="stylesheet" href="../CSS/messagerie.css">
    <!-- Inclusion du script JavaScript -->
    <script type="text/javascript" src="../JS/script.js" defer></script>
</head>
<body>

<?php
// Récupérer l'identifiant de l'utilisateur connecté
$user_id = $_SESSION['user']['id'];

// Inclusion du fichier de connexion à la base de données
require_once("../elements/connect.php");

// Requête SQL pour récupérer les messages de l'utilisateur connecté
$sql = "SELECT * FROM messages WHERE sender_user_id = :user_id OR receiver_user_id = :user_id";

// Préparation de la requête
$query = $db->prepare($sql);
$query->bindValue(':user_id', $user_id);

// Exécution de la requête et stockage des données dans une variable
$query->execute();
$conversations = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Inclusion des fichiers de débogage et d'en-tête -->
<?php require_once ('../elements/debug.php');?>
<?php require_once ('../elements/header.php');?>

<?php 
// Code de débogage pour afficher les conversations (commenté)
// echo '<pre>';
// print_r($conversations);
// echo '</pre>';
?>

<!-- Section messagerie pour l'utilisateur -->
<section class="messagerie">

    <div class="messagerie_menu">
        <a href="#">Nouveau message</a>
        
        <?php 
        // Initialisation du tableau pour suivre les utilisateurs avec lesquels il y a des conversations
        $chat = []; 
        foreach ($conversations as $conversation) {
            // Vérification des utilisateurs expéditeur et récepteur
            if ($conversation['sender_user_id'] !== $user_id && !in_array($conversation['sender_user_id'], $chat)) {
                $chat[] = $conversation['sender_user_id'];
                $conversation_user_id = $conversation['sender_user_id'];

                // Requête SQL pour récupérer les informations de l'utilisateur avec lequel il y a une conversation
                $sql = "SELECT first_name, last_name, avatar FROM users WHERE id = :conversation_user_id";

                // Préparation de la requête
                $query = $db->prepare($sql);
                $query->bindValue(':conversation_user_id', $conversation_user_id);

                // Exécution de la requête et stockage des données dans une variable
                $query->execute();
                $conversation_user_infos = $query->fetch(PDO::FETCH_ASSOC);
                if(empty($conversation_user_infos['avatar'])){
                    $conversation_user_infos['avatar'] = "../img/upload_avatars/default_avatar.png";
                }

                // Affichage des informations de l'utilisateur avec lequel il y a une conversation
                echo '<a href="../pages/messagerie.php?with_user_id='.$conversation_user_id.'#ancre_dernier_message">';
                echo'
                <div class="messagerie_conversation_title">
                    <div class="messagerie_conversation_avatar">
                        <img class="conversation_avatar" alt="Avatar de '.$conversation_user_infos['first_name'].'" src="'.$conversation_user_infos['avatar'].'">
                    </div>
                    <div class="messagerie_conversation_name">';

                    echo ucfirst($conversation_user_infos['first_name']) . ' ' . ucfirst($conversation_user_infos['last_name']);


                echo '</div>
                </div></a>';
            }
        }
        ?>
    </div>

    <div class="messagerie_main">
        <?php 
        // Affichage de la conversation sélectionnée
        if(!isset($_GET['with_user_id'])){
            echo 'Aucune conversation selectionnée';
        }
        
        
        else{
            


            // Requête SQL pour récupérer les informations de l'utilisateur avec lequel il y a une conversation
            $sql = "SELECT id, first_name, last_name, avatar FROM users WHERE id = :conversation_user_id";

            // Préparation de la requête
            $query = $db->prepare($sql);
            $query->bindValue(':conversation_user_id', $_GET['with_user_id']);

            // Exécution de la requête et stockage des données dans une variable
            $query->execute();
            $conversation_user_with = $query->fetch(PDO::FETCH_ASSOC);

            echo '<h2>';
            echo ucfirst($conversation_user_with['first_name']) . ' ' . ucfirst($conversation_user_with['last_name']);
            echo '</h2>';

            $sql = "SELECT * FROM messages 
        WHERE (sender_user_id = :user_id OR receiver_user_id = :user_id) 
        AND (sender_user_id = :conversation_user_id OR receiver_user_id = :conversation_user_id) 
        ORDER BY datetime ASC";


            $query = $db->prepare($sql);
            $query->bindValue(':conversation_user_id', $_GET['with_user_id']);
            $query->bindValue(':user_id', $user_id);
            $query->execute();
            $conversation_user_with_messages = $query->fetchAll(PDO::FETCH_ASSOC);

            foreach($conversation_user_with_messages as $message){
                if($message['sender_user_id'] === $user_id){
                    echo '<div class="message message_sent">'.$message['message_content'].'
                    <div class="message_time">' . (new DateTime($message['datetime']))->format('d/m/Y H:i') . '</div>
                    </div>';
                }
                else {
                    echo '<div class="message message_received">' . $message['message_content'] . '
                        <div class="message_time">' . (new DateTime($message['datetime']))->format('d/m/Y H:i') . '</div>
                    </div>';
                }
                
                
            }
            echo '<div id="ancre_dernier_message"></div>';

            



        
        
    
    }
        ?>

 
        
    </div>
</section>
<?php if(isset($_GET['with_user_id'])){
    echo'

<form class="messagerie_input_text" method="POST" action="../tools/messagerie_envoi_msg.php">

        <input type="text" name="conversation_message" placeholder="Envoyer un message à '.ucfirst($conversation_user_with['first_name']).'">
        <input type="hidden" name="sender_id" value="'.$user_id.'">
        <input type="hidden" name="receiver_id" value="'.$_GET['with_user_id'].'">
        <input type="submit" value="Envoyer">

</form>';}?>

<!-- Fin de la section messagerie pour l'utilisateur -->

</body>
</html>
