<?php
session_start();
require_once("../elements/connect.php");

if (!isset($_GET["search"])) {
     // Redirige vers la page d’accueil si le paramètre de recherche n’est pas défini
    header("Location: index.php");
    exit;
}

$search = "%" . trim($_GET["search"]) . "%";

require_once("../elements/connect.php");

// Préparation de la consultation pour rechercher des produits qui correspondent à la recherche
// Especifique cada coluna individualmente
$search_name = $db->prepare("SELECT * FROM products WHERE
    id LIKE :id OR
    ref LIKE :ref OR
    brand LIKE :brand OR
    size LIKE :size OR
    color LIKE :color OR
    pattern LIKE :pattern OR
    material LIKE :material OR
    gender LIKE :gender OR
    stock LIKE :stock OR
    price LIKE :price OR
    discount LIKE :discount OR
    category LIKE :category OR
    content LIKE :content");

 // Lie les paramètres de recherche aux colonnes pertinentes
$search_name->bindValue(":id", $search, PDO::PARAM_STR);
$search_name->bindValue(":ref", $search, PDO::PARAM_STR);
$search_name->bindValue(":brand", $search, PDO::PARAM_STR);
$search_name->bindValue(":size", $search, PDO::PARAM_STR);
$search_name->bindValue(":color", $search, PDO::PARAM_STR);
$search_name->bindValue(":pattern", $search, PDO::PARAM_STR);
$search_name->bindValue(":material", $search, PDO::PARAM_STR);
$search_name->bindValue(":gender", $search, PDO::PARAM_STR);
$search_name->bindValue(":stock", $search, PDO::PARAM_STR);
$search_name->bindValue(":price", $search, PDO::PARAM_STR);
$search_name->bindValue(":discount", $search, PDO::PARAM_STR);
$search_name->bindValue(":category", $search, PDO::PARAM_STR);
$search_name->bindValue(":content", $search, PDO::PARAM_STR);

// Exécute la requête
$search_name->execute();

// Obtient les résultats
$results = $search_name->fetchAll(PDO::FETCH_ASSOC);


// debug
echo '<pre>';
print_r($results);
echo '</pre>';

echo '<pre>';
print_r($_SESSION);
echo '</pre>';
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
    
    <title>Accueil</title>
    
</head>
<body>


<?php require_once ('../elements/header.php');

 // Vérifie si $results est vide
    if(empty($results)){
    echo 'Aucun résultat pour votre recherche.';
}


else{
    echo '<h2>Résultats de la recherche</h2>';
    echo'<section class="affichage_des_produits">';
// Boucle pour afficher chaque résultat
    foreach ($results as $result){

        echo '<article class="">
                <figure class="card">
                    <a href="article.php?id='.$result['id'] .'"><img class="" src="'. $result['image_1'].'" alt="php name ici"></a>
                    <figcaption class="">product :'.$result['brand'].'</figcaption>
                    <figcaption>color: '. $result['color'].'</figcaption>
                    <figcaption>size: '. $result['size'].'</figcaption>
                    <figcaption class="product-price">price: '.number_format($result['price'], 2). '€</figcaption>
                    
                    <div class="add-to-cart">
                    <form action="../../tools/action_cart/insert_cart.php" method="POST">
                        <input type="hidden" name="product_id" value="'.$result['id'].'">
                        <input type="hidden" name="user_id" value="'.(isset($_SESSION['id']) ? $_SESSION['id'] : '').'">
                        <button class="btn btn-dark" type="submit">Ajouter au panier</button>
                    </form>
                    </div>

                </figure>
        </article>';
    }
}
?>

<!-- Fermer la section qui a été ouverte dans php -->
</section>

<?php require_once ('../elements/footer.php');?>

    
</body>
</html>

