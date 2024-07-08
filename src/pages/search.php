<?php
session_start();
require_once("../elements/connect.php");

if (!isset($_GET["search"])) {
    header("Location: index.php");
    exit;
}

$search = "%" . trim($_GET["search"]) . "%";

// Préparation de la consultation pour rechercher des produits qui correspondent à la recherche
$search_name = $db->prepare("SELECT * FROM products WHERE
    id LIKE :id OR ref LIKE :ref OR brand LIKE :brand OR size LIKE :size OR
    color LIKE :color OR pattern LIKE :pattern OR material LIKE :material OR
    gender LIKE :gender OR stock LIKE :stock OR price LIKE :price OR
    discount LIKE :discount OR category LIKE :category OR content LIKE :content
    ORDER BY ref, color, size");

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

$search_name->execute();
$results = $search_name->fetchAll(PDO::FETCH_ASSOC);

function displayProduct($products) {
    $main_product = $products[0];
    $colors = array_unique(array_column($products, 'color'));
    $sizes = array_unique(array_column($products, 'size'));

    echo "<div class='product ' data-ref='{$main_product['ref']}'>";
    echo "<h2>{$main_product['brand']} - {$main_product['ref']}</h2>";
    echo "<p>category: {$main_product['category']}</p>";
    echo "<p>price: " . number_format($main_product['price'], 2) . "€</p>";
    echo "<div class='card'><a href='article.php?id={$main_product['id']}'><img src='{$main_product['image_1']}' alt='{$main_product['ref']}' width='200'></a></div>";
    
    echo "<h3>Colours available :</h3>";
    echo "<div class='color-options'>";
    foreach ($colors as $color) {
        echo "<button class='color-option' data-color='{$color}'>{$color}</button>";
    }
    echo "</div>";

    echo "<h3>Sizes available :</h3>";
    echo "<div class='size-options'>";
    foreach ($sizes as $size) {
        echo "<button class='size-option' data-size='{$size}'>{$size}</button>";
    }
    echo "</div>";

    echo "<p class='selected-product-info'></p>";

    echo "<div class='add-to-cart'>";
    echo "<form action='../../tools/action_cart/insert_cart.php' method='POST'>";
    echo "<input type='hidden' name='product_id' value='{$main_product['id']}'>";
    echo "<input type='hidden' name='user_id' value='" . (isset($_SESSION['id']) ? $_SESSION['id'] : '') . "'>";
    echo "<button class='btn btn-dark' type='submit'>Ajouter au panier</button>";
    echo "</form>";
    echo "</div>";

    echo "</div>";
    echo "<hr>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/style.css">
    <link rel="stylesheet" href="../CSS/produits.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script type="text/javascript" src="../JS/script.js" defer></script>
    <title>Résultats de recherche</title>
    <style>
        .product {
            margin-bottom: 30px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .color-options, .size-options {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 10px;
        }
        .color-option, .size-option {
            padding: 5px 10px;
            border: 1px solid #ddd;
            background-color: #f8f8f8;
            cursor: pointer;
        }
        .color-option.selected, .size-option.selected {
            background-color: #e0e0e0;
            border-color: #999;
        }
        .selected-product-info {
            margin-top: 10px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <?php require_once ('../elements/header.php'); ?>

    <h2>Résultats de la recherche</h2>
    <section class="affichage_des_produits">
    <?php
    if(empty($results)){
        echo 'Aucun résultat pour votre recherche.';
    } else {
        $current_ref = '';
        $products = [];
        foreach ($results as $result) {
            if ($current_ref != $result['ref']) {
                if (!empty($current_ref)) {
                    displayProduct($products);
                }
                $current_ref = $result['ref'];
                $products = [];
            }
            $products[] = $result;
        }
        // Exibir o último grupo de produtos
        if (!empty($products)) {
            displayProduct($products);
        }
    }
    ?>
    </section>

    <?php require_once ('../elements/footer.php'); ?>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const products = document.querySelectorAll('.product');

        products.forEach(product => {
            const colorButtons = product.querySelectorAll('.color-option');
            const sizeButtons = product.querySelectorAll('.size-option');
            const infoElement = product.querySelector('.selected-product-info');

            let selectedColor = null;
            let selectedSize = null;

            function updateSelection() {
                if (selectedColor && selectedSize) {
                    infoElement.textContent = `Selected: Colour ${selectedColor}, Size ${selectedSize}`;
                } else {
                    infoElement.textContent = 'Please select a color and size.';
                }
            }

            colorButtons.forEach(button => {
                button.addEventListener('click', function() {
                    if (this.classList.contains('selected')) {
                        selectedColor = null;
                        this.classList.remove('selected');
                    } else {
                        selectedColor = this.getAttribute('data-color');
                        colorButtons.forEach(btn => btn.classList.remove('selected'));
                        this.classList.add('selected');
                    }
                    updateSelection();
                });
            });

            sizeButtons.forEach(button => {
                button.addEventListener('click', function() {
                    if (this.classList.contains('selected')) {
                        selectedSize = null;
                        this.classList.remove('selected');
                    } else {
                        selectedSize = this.getAttribute('data-size');
                        sizeButtons.forEach(btn => btn.classList.remove('selected'));
                        this.classList.add('selected');
                    }
                    updateSelection();
                });
            });
        });
    });
    </script>
</body>
</html>