<?php 
session_start();?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messagerie</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../CSS/style.css">
    <link rel="stylesheet" href="../CSS/messagerie.css">
    <script type="text/javascript" src="../JS/script.js" defer></script>
</head>
<body>

<?php require_once ('../elements/debug.php');?>
<?php require_once ('../elements/header.php');?>
    <!-- VERSION UTILISATEUR -->
    <section class="messagerie">
        <div class="messagerie_top">
            <h2>Support</h2>
        </div>
        <div class="messagerie_menu">
            <div class="messagerie_conversation_title">
                <div class="messagerie_conversation_avatar">
                </div>
                <div class="messagerie_conversation_name">
                </div>
            </div>
        </div>
        <div class="messagerie_main">
            <div class="message_received">reçu
            </div>
            <div class="message_sent">envoyé
            </div>
        </div>
    </section>
    <!-- FIN VERSION UTILISATEUR -->


</body>
</html>
