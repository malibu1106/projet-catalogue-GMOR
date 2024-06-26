<div class="slider">
        <div class="slides">
            <!-- Exemple de slide de produit. Remplace ce bloc avec un foreach en PHP pour ajouter tes produits -->
            <?php
            $products = [
                ['name' => 'Produit 1', 'image' => 'path/to/image1.jpg', 'price' => '29.99'],
                ['name' => 'Produit 2', 'image' => 'path/to/image2.jpg', 'price' => '39.99'],
                // Ajoute d'autres produits ici
            ];

            foreach ($products as $product) {
                echo '<div class="slide">';
                echo '<img src="' . $product['image'] . '" alt="' . $product['name'] . '">';
                echo '<div class="product-info">';
                echo '<h2>' . $product['name'] . '</h2>';
                echo '<p>' . $product['price'] . ' €</p>';
                echo '</div>';
                echo '</div>';
            }
            ?>
        </div>
        <button class="prev" onclick="moveSlide(-1)">&#10094;</button>
        <button class="next" onclick="moveSlide(1)">&#10095;</button>
    </div>
    <script src="slider.js"></script>