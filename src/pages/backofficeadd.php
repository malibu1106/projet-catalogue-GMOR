<?php 
session_start();
require_once("../tools/user.php");

    if(!isset($_SESSION['user'])){
        header('location: login.php');
    }

    if($_POST){
        
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../CSS/style.css">
    <title>Document</title>
</head>
<body class="backofficeadd-body">
    <?php require_once ('../elements/header.php');?>

    <main class="backofficeadd-container">
        <article class="backofficeadd-card">
            <h1>AJOUTER UN PRODUIT</h1>
            <form method="POST">
                <div class="backofficeadd-line">
                    <p class="rem-bts">Référence du produit:</p>
                    <input type="text" required>
                </div>
                <div class="backofficeadd-line">
                    <p class="rem-bts">Nom du produit:</p>
                    <input type="text" required>
                </div>
                <div class="backofficeadd-line">
                    <p class="rem-bts">Taille: </p>
                    <select name="size" id="size" required>
                        <option value="">Sélectionnez une taille</option>
                        <option value="XS">XS</option>
                        <option value="S">S</option>
                        <option value="M">M</option>
                        <option value="L">L</option>
                        <option value="XL">XL</option>
                    </select>
                </div>
                <div class="backofficeadd-line">
                    <p class="rem-bts">Couleur: </p>
                    <div id="custom-select" class="custom-select">
                        <select name="color" id="color" required>
                            <option value="">Sélectionnez une couleur</option>
                            <option value="bleu">bleu</option>
                            <option value="rouge">rouge</option>
                        </select>
                    </div>
                </div>
                <div class="backofficeadd-line">
                    <p class="rem-bts">Stock:</p>
                    <input type="number" min="0" required>
                </div>
                <div class="backofficeadd-line">
                    <p class="rem-bts">Prix:</p>
                    <input type="number" min="0" step="0.01" required>
                </div>
                <div class="backofficeadd-line">
                    <p class="rem-bts">Promotion:</p>
                    <input type="checkbox">
                </div>
                <div class="backofficeadd-line">
                    <p class="rem-bts">Catégorie</p>
                    <select name="category" id="category" required>
                        <option value="">Sélectionnez une catégorie</option>
                        <option value="shirt">T-shirt</option>
                        <option value="pull">Pull</option>
                        <option value="Jacket">Veste</option>
                        <option value="pants">Pantalon</option>
                        <option value="skirt">Jupe</option>
                        <option value="boots">Bottes</option>
                        <option value="dress">Robe</option>
                    </select>
                </div>
                <div>
                    <p class="rem-bts">Description:</p>
                    <textarea name="description" id="description" required></textarea>
                </div>
                <p class="img-text-center">Télécharger images:</p>
                <div class="image-upload">
                    <input type="file" name="image1" id="image1" accept="image/*" style="display: none;">
                    <label for="image1" class="image-upload-btn">Image 1</label>
                    <input type="file" name="image2" id="image2" accept="image/*" style="display: none;">
                    <label for="image2" class="image-upload-btn">Image 2</label>
                    <input type="file" name="image3" id="image3" accept="image/*" style="display: none;">
                    <label for="image3" class="image-upload-btn">Image 3</label>
                    <input type="file" name="image4" id="image4" accept="image/*" style="display: none;">
                    <label for="image4" class="image-upload-btn">Image 4</label>
                </div>
                <div class="backoff-center-btn">
                    <button type="submit" class="submit-btn">Ajouter le Produit</button>
                </div>
            </form>
        </article>
    </main>

    <?php require_once ('../elements/footer.php');?>
</body>
    <script type="text/javascript" src="../JS/script.js" defer></script>
</html>