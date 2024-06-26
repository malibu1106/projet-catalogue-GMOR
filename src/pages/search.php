<?php
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
$result = $search_name->fetchAll(PDO::FETCH_ASSOC);

echo '<pre>';
print_r($result);
echo '</pre>';
?>
