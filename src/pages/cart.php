<?php

use LDAP\Result;

require_once("../elements/connect.php");
require_once("../elements/header.php");

$sql = "SELECT * FROM products";
$requete = $db->prepare($sql);
$requete->execute();
$cartResults = $requete->fetchAll(PDO::FETCH_ASSOC);


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/style.css">
    <link rel="stylesheet" href="../CSS/produits.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Ícones do Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Script JavaScript -->
    <script type="text/javascript" src="../JS/script.js" defer></script>
    <title>cart</title>
</head>
<body>
<?php foreach($cartResults as $cartResult){

echo '<article class="">
<figure class="">
    <img class="" src="'. $cartResult['image_1'].'" alt="php name ici">
    <figcaption class="">'. $cartResult['brand'].'</figcaption>
</figure>
</article>';

}

?>
</body>
</html>