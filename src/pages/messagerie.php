<?php 
// Démarrer la session
session_start(); 
date_default_timezone_set('Europe/Paris'); 
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messagerie</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../CSS/style.css">
    <link rel="stylesheet" href="../CSS/messagerie.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../JS/script.js" defer></script>
    <script src="../JS/messagerie.js" defer></script>
</head>
<body>

<audio style="display:none" id="notificationSound" src="../sounds/notification.mp3" preload="auto"></audio>

<?php

$user_id = $_SESSION['user']['id'];
require_once("../elements/connect.php");

$sql = "SELECT * FROM messages WHERE sender_user_id = :user_id OR receiver_user_id = :user_id";
$query = $db->prepare($sql);
$query->bindValue(':user_id', $user_id);
$query->execute();
$conversations = $query->fetchAll(PDO::FETCH_ASSOC);

require_once ('../elements/debug.php');
require_once ('../elements/header.php');
?>

<section class="messagerie">
    <div class="messagerie_menu">
        <a href="#">Nouveau message</a>
        
        <?php 
        $chat = []; 
        foreach ($conversations as $conversation) {
            if ($conversation['sender_user_id'] !== $user_id && !in_array($conversation['sender_user_id'], $chat)) {
                $chat[] = $conversation['sender_user_id'];
                $conversation_user_id = $conversation['sender_user_id'];

                $sql = "SELECT id, first_name, last_name, avatar FROM users WHERE id = :conversation_user_id";
                $query = $db->prepare($sql);
                $query->bindValue(':conversation_user_id', $conversation_user_id);
                $query->execute();
                $conversation_user_infos = $query->fetch(PDO::FETCH_ASSOC);
                if(empty($conversation_user_infos['avatar'])){
                    $conversation_user_infos['avatar'] = "../img/upload_avatars/default_avatar.png";
                }

                echo '<a href="../pages/messagerie.php?with_user_id='.$conversation_user_id.'#ancre_dernier_message">';
                echo '<div class="messagerie_conversation_title';
                if ($conversation_user_infos['id'] == $_GET['with_user_id']) {
                    echo ' selected';
                }
                echo '">';
                echo '<div class="messagerie_conversation_avatar">';
                echo '<img class="conversation_avatar" alt="Avatar de '.$conversation_user_infos['first_name'].'" src="'.$conversation_user_infos['avatar'].'">';
                echo '</div>';
                echo '<div class="messagerie_conversation_name">';
                echo ucfirst($conversation_user_infos['first_name']) . ' ' . ucfirst($conversation_user_infos['last_name']);
                echo '</div></div></a>';
            }
        }
        ?>
    </div>

    <div class="messagerie_main" id="messagerie_main">
        <?php 
        if(!isset($_GET['with_user_id'])){
            echo 'Aucune conversation sélectionnée';
        } else {
            $sql = "SELECT id, first_name, last_name, avatar FROM users WHERE id = :conversation_user_id";
            $query = $db->prepare($sql);
            $query->bindValue(':conversation_user_id', $_GET['with_user_id']);
            $query->execute();
            $conversation_user_with = $query->fetch(PDO::FETCH_ASSOC);


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
                } else {
                    echo '<div class="message message_received">' . $message['message_content'] . '
                        <div class="message_time">' . (new DateTime($message['datetime']))->format('d/m/Y H:i') . '</div>
                    </div>';
                }
            }
            echo '<div id="ancre_dernier_message"></div>';
        }
        ?>
    </div>

    <?php if(isset($_GET['with_user_id'])): ?>
    
    <?php endif; ?>
</section><form class="messagerie_input_text" method="POST" id="message_form" action="../tools/messagerie_envoi_msg.php">
        <input type="text" name="conversation_message" id="conversation_message" placeholder="Envoyer un message à <?= ucfirst($conversation_user_with['first_name']) ?>">
        <input type="hidden" name="sender_id" value="<?= $user_id ?>">
        <input type="hidden" name="receiver_id" value="<?= $_GET['with_user_id'] ?>">
        <input type="submit" value="Envoyer">
    </form>

    <script>
$(document).ready(function() {
    let lastMessageId = null;
    let isUserScrolling = false;

    function loadMessages() {
        $.ajax({
            url: '../tools/get_messages.php',
            type: 'GET',
            data: {
                with_user_id: '<?= $_GET['with_user_id']; ?>'
            },
            success: function(response) {
                console.log('Messages loaded:', response);
                let messages = JSON.parse(response);
                let messageHtml = '';
                let newLastMessageId = messages.length ? messages[messages.length - 1].message_id : null;

                messages.forEach(function(message) {
                    if (message.sender_user_id == <?= $user_id; ?>) {
                        messageHtml += '<div class="message message_sent">' + message.message_content +
                            '<div class="message_time">' + (new Date(message.datetime)).toLocaleString() + '</div></div>';
                    } else {
                        messageHtml += '<div class="message message_received">' + message.message_content +
                            '<div class="message_time">' + (new Date(message.datetime)).toLocaleString() + '</div></div>';
                    }
                });

                $('#messagerie_main').html(messageHtml);

                if (!isUserScrolling) {
                    $('#messagerie_main').scrollTop($('#messagerie_main')[0].scrollHeight);
                }

                if (newLastMessageId && newLastMessageId !== lastMessageId) {
                    let lastMessage = messages[messages.length - 1];
                    if (lastMessage.sender_user_id !== <?= $user_id; ?>) {
                        let messageDateTime = new Date(lastMessage.datetime);
                        let currentTime = new Date();
                        // Vérifier si le message a été envoyé il y a moins de 9 secondes
                        if ((currentTime - messageDateTime) / 1000 < 9) {
                            console.log('Playing sound for new message');
                            document.getElementById('notificationSound').play().catch(function(error) {
                                console.error('Error playing sound:', error);
                            });
                        }
                    }
                    lastMessageId = newLastMessageId;
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX error:', status, error);
            }
        });
    }

    $('#message_form').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: '../tools/messagerie_envoi_msg.php',
            type: 'POST',
            data: $(this).serialize(),
            success: function() {
                $('#conversation_message').val('');
                loadMessages();
            }
        });
    });

    $('#messagerie_main').on('scroll', function() {
        let element = $(this);
        let scrollHeight = element[0].scrollHeight;
        let scrollTop = element.scrollTop();
        let clientHeight = element.innerHeight();
        isUserScrolling = scrollHeight - scrollTop > clientHeight + 50;
    });

    // Charger les messages initiaux lors du chargement de la page
    loadMessages();
    // Vérifier les nouveaux messages toutes les 5 secondes
    setInterval(loadMessages, 5000);
});
</script>

</body>
</html>

