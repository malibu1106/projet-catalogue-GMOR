<?php
session_start();
require_once("../elements/connect.php");

$imagePath = ""; // Initialisation de la variable

// Vérification et traitement du fichier uploadé
if (isset($_FILES["image_1"]) && $_FILES["image_1"]["error"] === 0) {
    $allowed = [
        "jpg" => "image/jpeg",
        "jpeg" => "image/jpeg",
        "png" => "image/png"
    ];

    $filename = $_FILES["image_1"]["name"];
    $filetype = $_FILES["image_1"]["type"];
    $filesize = $_FILES["image_1"]["size"];
    $extension = pathinfo($filename, PATHINFO_EXTENSION);

    if (!array_key_exists($extension, $allowed) || !in_array($filetype, $allowed)) {
        die("Erreur : le format du fichier est incorrect");
    }

    if ($filesize > 1024 * 1024) {
        die("Fichier trop volumineux");
    }

    $newname = md5(uniqid()) . ".$extension";
    $newfilename = __DIR__ . "/img/upload_model/$newname";

    if (!move_uploaded_file($_FILES["image_1"]["tmp_name"], $newfilename)) {
        die("L'upload a échoué");
    }

    chmod($newfilename, 0644);
    $imagePath = "/img/upload_model/$newname"; // Chemin relatif à stocker dans la base de données
}

if ($_POST && isset($_POST["ref"]) && isset($_POST["brand"]) && isset($_POST["size"]) && isset($_POST["color"]) 
    && isset($_POST["pattern"]) && isset($_POST["material"]) && isset($_POST["gender"]) && isset($_POST["stock"]) 
    && isset($_POST["price"]) && isset($_POST["discount"]) && isset($_POST["category"]) && isset($_POST["content"])) {

    $ref = strip_tags($_POST["ref"]);
    $brand = strip_tags($_POST["brand"]);
    $size = strip_tags($_POST["size"]);
    $color = strip_tags($_POST["color"]);
    $pattern = strip_tags($_POST["pattern"]);
    $material = strip_tags($_POST["material"]);
    $gender = strip_tags($_POST["gender"]);
    $stock = strip_tags($_POST["stock"]);
    $price = strip_tags($_POST["price"]);
    $discount = strip_tags($_POST["discount"]); // Correction ici
    $category = strip_tags($_POST["category"]);
    $content = strip_tags($_POST["content"]);

    // Utilisation de $imagePath défini plus haut
    $images = $imagePath;

    $sql = "INSERT INTO products (ref, brand, size, color, pattern, material, gender, stock, price, discount, category, content, image_1) 
            VALUES (:ref, :brand, :size, :color, :pattern, :material, :gender, :stock, :price, :discount, :category, :content, :image_1)";
    
    try {
        $query = $db->prepare($sql);

        $query->bindValue(":ref", $ref);
        $query->bindValue(":brand", $brand);
        $query->bindValue(":size", $size);
        $query->bindValue(":color", $color);
        $query->bindValue(":pattern", $pattern);
        $query->bindValue(":material", $material);
        $query->bindValue(":gender", $gender);
        $query->bindValue(":stock", $stock);
        $query->bindValue(":price", $price);
        $query->bindValue(":discount", $discount);
        $query->bindValue(":category", $category);
        $query->bindValue(":content", $content);
        $query->bindValue(":image_1", $images);

        if ($query->execute()) {
            require_once("../elements/disconnect.php");
            header("Location: backoffice-produits.php");
            exit();
        } else {
            echo "Erreur lors de l'exécution de la requête.";
            print_r($query->errorInfo());
        }
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
} else if ($_POST) {
    var_dump($_POST);
    die("Erreur veuillez réessayer");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../CSS/backoffice-style.css">
    <title>Document</title>
</head>
<body class="backofficeadd-body">
    <?php require_once ('../elements/header.php');?>
    <main class="backofficeadd-container">
        <article class="backofficeadd-card">
            <h1>AJOUTER UN PRODUIT</h1>
            <form method="POST" enctype="multipart/form-data">
                <div class="backofficeadd-line">
                    <p class="rem-bts">Référence du produit:</p>
                    <input type="text" name="ref" required>
                </div>
                <div class="backofficeadd-line">
                    <p class="rem-bts">Nom du produit:</p>
                    <input type="text" name="brand" required>
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
                    <p class="rem-bts">Motif: </p>
                    <div id="custom-select" class="custom-select">
                        <select name="pattern" id="pattern" required>
                            <option value="">Sélectionnez un motif</option>
                            <option value="rayure">Rayure</option>
                            <option value="losange">Losange</option>
                            <option value="carre">Carré</option>
                        </select>
                    </div>
                </div>
                <div class="backofficeadd-line">
                    <p class="rem-bts">Matière: </p>
                    <div id="custom-select" class="custom-select">
                        <select name="material" id="material" required>
                            <option value="">Sélectionnez un materiel</option>
                            <option value="coton">Coton</option>
                            <option value="polyestere">Polyestere</option>
                            <option value="cuir">Cuir</option>
                        </select>
                    </div>
                </div>
                <div class="backofficeadd-line">
                    <p class="rem-bts">Genre: </p>
                    <div id="custom-select" class="custom-select">
                        <select name="gender" id="gender" required>
                            <option value="">Sélectionnez un genre</option>
                            <option value="homme">Homme</option>
                            <option value="femme">Femme</option>
                        </select>
                    </div>
                </div>
                <div class="backofficeadd-line">
                    <p class="rem-bts">Stock:</p>
                    <input type="number" name="stock" min="0" required>
                </div>
                <div class="backofficeadd-line">
                    <p class="rem-bts">Prix:</p>
                    <input type="number" name="price" min="0" step="0.01" required>
                </div>
                <div class="backofficeadd-line">
                    <p class="rem-bts">Promotion:</p>
                    <input type="number" name="discount">
                </div>
                <div class="backofficeadd-line">
                    <p class="rem-bts">Catégorie:</p>
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
                    <textarea name="content" id="description" required></textarea>
                </div>
                <p class="img-text-center">Télécharger images:</p>
                <div class="image-upload">
                    <input type="file" name="image_1" id="image1" accept="image/*" style="display: none;">
                    <label for="image1" class="image-upload-btn">Image 1</label>
                    <input type="file" name="image_2" id="image2" accept="image/*" style="display: none;">
                    <label for="image2" class="image-upload-btn">Image 2</label>
                    <input type="file" name="image_3" id="image3" accept="image/*" style="display: none;">
                    <label for="image3" class="image-upload-btn">Image 3</label>
                    <input type="file" name="image_4" id="image4" accept="image/*" style="display: none;">
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
