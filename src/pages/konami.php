<?php
    session_start();
    require_once("../elements/connect.php");

    $sql = "SELECT * FROM products";
    $requete = $db->prepare($sql);
    $requete->execute();
    $produits = $requete->fetchAll(PDO::FETCH_ASSOC);

?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script type="text/javascript" src="JS/script.js" defer></script>
    <title>secrets-page</title>
</head>
<body>
    <?php require_once('../elements/header.php'); ?>
        <img src="/img/illustration/error404.jpg" height="400px" alt="">
    <?php require_once ('../elements/footer.php');?>
</body>
</html>