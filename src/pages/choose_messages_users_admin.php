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
<?php
require_once ('../elements/debug.php');
require_once ('../elements/header.php');
require_once("../elements/connect.php");
$sql = "SELECT * FROM users WHERE id != :admin_id";
$query = $db->prepare($sql);
$query->bindValue(':admin_id', $_SESSION['user']['id']);
$query->execute();
$usersList = $query->fetchAll(PDO::FETCH_ASSOC);

?>
<h1 class="msgListeH1"> Accéder aux messages de </h1>
<div class="messagesListe">
    
    <?php
    foreach($usersList as $user){
        echo'<a href="../pages/messagerie_admin.php?id='.$user['id'].'">
        <div class="user_msg_card">';
        echo ucfirst($user['first_name']).' '.ucfirst($user['last_name']);
        echo'</div>';
        echo'</a>';
        
        echo'<br>';
    }
    ?>
</div>
</body>
</html>