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
        <a href="../pages/messagerie.php"><img src="../img/illustration/new_message.png">Nouveau message</a>

        <?php 
        $chat = [];

        // Parcourir toutes les conversations
        foreach ($conversations as $conversation) {
            $sender_id = $conversation['sender_user_id'];
            $receiver_id = $conversation['receiver_user_id'];

            // Ajoutez l'ID de l'expéditeur s'il est différent de l'utilisateur connecté et s'il n'est pas déjà dans $chat
            if ($sender_id != $user_id && !in_array($sender_id, $chat)) {
                $chat[] = $sender_id;
            }

            // Ajoutez l'ID du destinataire s'il est différent de l'utilisateur connecté et s'il n'est pas déjà dans $chat
            if ($receiver_id != $user_id && !in_array($receiver_id, $chat)) {
                $chat[] = $receiver_id;
            }
        }

        // Récupérer les informations des utilisateurs dans $chat et les afficher
        foreach ($chat as $conversation_user_id) {
            $sql = "SELECT id, first_name, last_name, avatar FROM users WHERE id = :conversation_user_id";
            $query = $db->prepare($sql);
            $query->bindValue(':conversation_user_id', $conversation_user_id);
            $query->execute();
            $conversation_user_infos = $query->fetch(PDO::FETCH_ASSOC);

            // Assurez-vous que l'avatar a une valeur par défaut si vide
            if (empty($conversation_user_infos['avatar'])) {
                $conversation_user_infos['avatar'] = "../img/upload_avatars/default_avatar.png";
            }

            // Afficher les informations de l'utilisateur dans votre interface
            echo '<a href="../pages/messagerie.php?with_user_id='.$conversation_user_id.'#ancre_dernier_message">';
            echo '<div class="messagerie_conversation_title';
            if (isset($_GET['with_user_id']) && $_GET['with_user_id'] == $conversation_user_id) {
                echo ' selected';
            }
            $sql = "SELECT * FROM messages WHERE sender_user_id = :conversation_user_id AND receiver_user_id = :user_id AND message_read = 0";
            $query = $db->prepare($sql);
            $query->bindValue(':conversation_user_id', $conversation_user_id);
            $query->bindValue(':user_id', $user_id);
            $query->execute();
            $unread_message = $query->fetch(PDO::FETCH_ASSOC);
            if($unread_message){echo ' unread';}
            





            echo '">';
            echo '<div class="messagerie_conversation_avatar">';
            echo '<img class="conversation_avatar" alt="Avatar de '.$conversation_user_infos['first_name'].'" src="'.$conversation_user_infos['avatar'].'">';
            echo '</div>';
            echo '<div class="messagerie_conversation_name">';
            echo ucfirst($conversation_user_infos['first_name']) . ' ' . ucfirst($conversation_user_infos['last_name']);
            echo '</div></div></a>';
        }
        ?>
    </div>

    <div class="messagerie_main" id="messagerie_main">
        <?php 
        if(!isset($_GET['with_user_id'])){
            echo 'Envoyer un message à :<br>';

            $sql = "SELECT id, first_name, last_name FROM users WHERE id != :id";
            $query = $db->prepare($sql);
            $query->bindValue(':id', $user_id);
            $query->execute();
            $usersList = $query->fetchAll(PDO::FETCH_ASSOC);
            // echo'<pre>';
            // print_r($usersList);
            // echo'</pre>';

            echo '<select id="user_list">';
            foreach ($usersList as $user) {
                echo '<option value="' . $user['id'] . '">' . ucfirst(strtolower($user['last_name'])) . ' ' . ucfirst(strtolower($user['first_name'])) . '</option>';

            }
            echo '</select>';
            











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

            foreach($conversation_user_with_messages as $message) {
                if ($message['sender_user_id'] === $user_id) {
                    echo '<div class="message message_sent">';
                } else {
                    echo '<div class="message message_received">';
                }
            
                if (!empty($message['message_type'])) {
                    echo '<img class="message_image" src="' . htmlspecialchars($message['message_type']) . '">';
                } else {
                    echo htmlspecialchars($message['message_content']);
                }
            
                echo '<div class="message_time">' . (new DateTime($message['datetime']))->format('d/m/Y H:i') . '</div>';
                echo '</div>';
            }
            }
            echo '';
        
        ?>
    </div>


    <?php
        // Marquer tous les messages de cette conversation comme lus
        if (isset($_GET['with_user_id'])) {
            $with_user_id = $_GET['with_user_id'];
            $sql = "UPDATE messages SET message_read = 1 WHERE receiver_user_id = :user_id AND sender_user_id = :with_user_id AND message_read = 0";
            $query = $db->prepare($sql);
            $query->bindValue(':user_id', $user_id);
            $query->bindValue(':with_user_id', $with_user_id);
            $query->execute();
        }
        ?>



    <?php if(isset($_GET['with_user_id'])): ?>
</section><form class="messagerie_input_text" method="POST" id="message_form" action="../tools/messagerie_envoi_msg.php" enctype="multipart/form-data">
    <input type="text" name="conversation_message" id="conversation_message" placeholder="Envoyer un message à <?= ucfirst($conversation_user_with['first_name']) ?>">
    <input type="hidden" name="sender_id" value="<?= $user_id ?>">
    <input type="hidden" name="receiver_id" value="<?= $_GET['with_user_id'] ?>">
    <input type ="file" name="fileInput" id="fileInput"style="display:none">
    <img id="addFile" src="../img/illustration/add_file.png">
    
    <input type="submit" value="Envoyer">
</form>
<?php else: ?>
    </section><form class="messagerie_input_text" method="POST" id="new_message_form" action="../tools/messagerie_new_envoi_msg.php">
    <input type="text" name="new_conversation_message" id="new_conversation_message" placeholder="">
    <input type="hidden" name="new_sender_id" value="<?= $user_id ?>">
    <input type="hidden" name="new_receiver_id" id="new_receiver_id" value="">
    <input type="submit" value="Envoyer">
</form>
<?php endif; ?>


    <script>$(document).ready(function() {
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
            // Parse JSON response
            let messages = JSON.parse(response);
            let messageHtml = '';
            let newLastMessageId = messages.length ? messages[messages.length - 1].message_id : null;

            messages.forEach(function(message) {
                if (message.sender_user_id == <?= $user_id; ?>) {
                    messageHtml += '<div class="message message_sent">';
                } else {
                    messageHtml += '<div class="message message_received">';
                }

                if (message.message_type === 'image') {
                    messageHtml += '<img class="message_image" src="' + message.message_content + '">';
                } 
                
                else if (message.message_type === 'other') {
    // Extraire le nom du fichier à partir de l'URL
    let fileName = message.message_content.substring(message.message_content.lastIndexOf('/') + 1);


    messageHtml += '<center>Un fichier a été envoyé<br>';
    messageHtml += '<b><a class="message_file_link" download href="' + message.message_content + '">' + fileName + '</a></b>';
    messageHtml += '</center>';
}
                else {
                    messageHtml += '<div>' + message.message_content + '</div>';
                }

                messageHtml += '<div class="message_time">' + (new Date(message.datetime)).toLocaleString() + '</div>';
                messageHtml += '</div>';
            });

            messageHtml += '<div id="ancre_dernier_message"></div>';

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
                        document.getElementById('notificationSound').play().catch(function(error) {
                            // Gestion des erreurs lors de la lecture du son
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

        // Désactiver le bouton d'envoi pour éviter la double soumission
        $('#submit_button').prop('disabled', true);

        $.ajax({
            url: '../tools/messagerie_envoi_msg.php',
            type: 'POST',
            data: $(this).serialize(),
            success: function() {
                $('#conversation_message').val('');
                loadMessages();
            },
            complete: function() {
                // Réactiver le bouton d'envoi après que la requête AJAX soit complète
                $('#submit_button').prop('disabled', false);
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

let textInput = document.getElementById('conversation_message');
let fileInput = document.getElementById('fileInput');
let fileButton = document.getElementById('addFile');
let messageForm = document.getElementById('message_form');

if (fileButton) {
    fileButton.addEventListener("click", displayFileInput);
}

function displayFileInput() {
    if (textInput.style.display !== "none") {
        textInput.style.display = "none";
        fileInput.style.display = "block";
        fileButton.src = "../img/illustration/add_file_cancel.png";
    } else {
        textInput.style.display = "block";
        fileInput.style.display = "none";
        fileButton.src = "../img/illustration/add_file.png";
        fileInput.value = "";
    }
}

messageForm.addEventListener('submit', function(event) {
    event.preventDefault(); // Empêche la soumission par défaut du formulaire

    var formData = new FormData(messageForm); // Crée un FormData à partir du formulaire

    fetch(messageForm.action, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            // alert('Message envoyé avec succès !');
            // Réinitialise le formulaire après envoi
            messageForm.reset();
            // Réinitialise l'affichage des inputs
            textInput.style.display = "block";
            fileInput.style.display = "none";
            fileButton.src = "../img/illustration/add_file.png";
        } else {
            // alert('Erreur lors de l\'envoi du message : ' + (data.message || 'Erreur inconnue.'));
        }
    })
    .catch(error => {
        // console.error('Erreur:', error);
        // alert('Erreur lors de l\'envoi du message. Vérifiez la console pour plus de détails.');
    });
});

</script>


</body>
</html>