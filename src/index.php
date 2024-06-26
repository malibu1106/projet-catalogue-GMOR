<?php
session_start();?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script type="text/javascript" src="JS/script.js" defer></script>
    
    <title>Accueil</title>
    
</head>
<body>
<?php require_once ('elements/debug.php');?>



<?php require_once ('elements/header.php');?>

<?php require_once ('pages/slider.php');?>

<section id="container-photos">
    <div class="container-photos-1">
    
        <div class="photo-grande">
        <img src="img/temporaire/photo1.jpg">
        </div>
        <div class="photo-petite">
        <img src="img/temporaire/ninja-cosmico-.jpeg">
        </div>
    </div>
</section>
<?php require_once ('elements/footer.php');?>

    
</body>
</html>

