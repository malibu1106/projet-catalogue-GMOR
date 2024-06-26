<div class="slider">
        <div class="slides">
            <!-- Exemple de slide de produit. Remplace ce bloc avec un foreach en PHP pour ajouter tes produits -->
            <!-- <?php
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
            ?> -->


                 <div class="slide">
                 <img src="../img/temporaire/model-image-login.jpg">
                 <div class="product-info">
                 <h2>Le slip sale de Gepeto</h2>
                 <p>22€</p>
                 </div>
                 </div>

                 <div class="slide">
                 <img src="../img/temporaire/model-image-login.jpg">
                 <div class="product-info">
                 <h2>Le slip sale de Pierre</h2>
                 <p>22€</p>
                 </div>
                 </div>


        </div>
        <button class="prev" onclick="moveSlide(-1)">&#10094;</button>
        <button class="next" onclick="moveSlide(1)">&#10095;</button>
    </div>
    <script src="../JS/slider.js"></script>

    <style>
        .slider {
    position: relative;
    max-width: 1000px;
    margin: 50px auto;
    overflow: hidden;
    border: 1px solid #ddd;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.slides {
    display: flex;
    transition: transform 0.5s ease-in-out;
}

.slide {
    min-width: 100%;
    box-sizing: border-box;
    display: none;
}

.slide img {
    width: 100%;
    display: block;
}

.product-info {
    text-align: center;
    padding: 10px;
    background: rgba(0, 0, 0, 0.6);
    color: white;
    position: absolute;
    bottom: 0;
    width: 100%;
}

.prev, .next {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background-color: rgba(0,0,0,0.5);
    color: white;
    border: none;
    padding: 10px;
    cursor: pointer;
    border-radius: 50%;
    z-index: 10;
}

.prev {
    left: 10px;
}

.next {
    right: 10px;
}
</style>