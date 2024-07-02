<?php
session_start();
require_once("../elements/connect.php");


$error_messages = [];

// Fonction pour gérer l'upload des images et retourner le chemin relatif
function handleImageUpload($file) {
    $allowed = [
        "jpg" => "image/jpeg",
        "jpeg" => "image/jpeg",
        "png" => "image/png"
    ];

    $filename = $file["name"];
    $filetype = $file["type"];
    $filesize = $file["size"];
    $extension = pathinfo($filename, PATHINFO_EXTENSION);

    if (!array_key_exists($extension, $allowed) || !in_array($filetype, $allowed)) {
        $error_messages[] = "Erreur : le format du fichier est incorrect";
    }

    if ($filesize > 1024 * 1024) {
        $error_messages[] = "Fichier trop volumineux";
    }

    $newname = md5(uniqid()) . ".$extension";
    $newfilename = "../img/upload_model/$newname";

    if (!move_uploaded_file($file["tmp_name"], $newfilename)) {
        $error_messages[] = "L'upload a échoué";
    }

    chmod($newfilename, 0644);
    return "img/upload_model/$newname"; // Retourne le chemin relatif
}

$imagePaths = ["", "", "", ""]; // Initialisation des variables pour les chemins des images

// Traitement des fichiers uploadés
for ($i = 1; $i <= 4; $i++) {
    if (isset($_FILES["image_$i"]) && $_FILES["image_$i"]["error"] === 0) {
        $imagePaths[$i-1] = handleImageUpload($_FILES["image_$i"]);
    }
}

// Vérifie si le formulaire a été soumis et si tous les champs requis sont présents
if ($_POST && isset($_POST["ref"]) && isset($_POST["brand"]) && isset($_POST["size"]) && isset($_POST["color"])
    && isset($_POST["pattern"]) && isset($_POST["material"]) && isset($_POST["gender"]) && isset($_POST["stock"])
    && isset($_POST["price"]) && isset($_POST["discount"]) && isset($_POST["category"]) && isset($_POST["content"])) {

    // Nettoyage des données entrées par l'utilisateur
    $ref = strip_tags($_POST["ref"]);
    $brand = strip_tags($_POST["brand"]);
    $size = strip_tags($_POST["size"]);
    $color = strip_tags($_POST["color"]);
    $pattern = strip_tags($_POST["pattern"]);
    $material = strip_tags($_POST["material"]);
    $gender = strip_tags($_POST["gender"]);
    $stock = strip_tags($_POST["stock"]);
    $price = strip_tags($_POST["price"]);
    $discount = strip_tags($_POST["discount"]);
    $category = strip_tags($_POST["category"]);
    $content = strip_tags($_POST["content"]);

    // Préparation de la requête SQL pour insérer les données dans la table products
    $sql = "INSERT INTO products (ref, brand, size, color, pattern, material, gender, stock, price, discount, category, content, image_1, image_2, image_3, image_4)
            VALUES (:ref, :brand, :size, :color, :pattern, :material, :gender, :stock, :price, :discount, :category, :content, :image_1, :image_2, :image_3, :image_4)";

    try {
        $query = $db->prepare($sql);
        // Liaison des valeurs aux paramètres de la requête
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
        $query->bindValue(":image_1", $imagePaths[0]);
        $query->bindValue(":image_2", $imagePaths[1]);
        $query->bindValue(":image_3", $imagePaths[2]);
        $query->bindValue(":image_4", $imagePaths[3]);

        // Exécution de la requête
    $redirect = false;

    if ($query->execute()) {
        require_once("../elements/disconnect.php");
        $redirect = true;
        $redirect_url = "backoffice-produits.php";
    } else {
        $error_messages[] = "Erreur lors de l'exécution de la requête.";
        // ... afficher les détails de l'erreur ...
            print_r($query->errorInfo()); // Affiche les détails de l'erreur
        }
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage(); // Affiche les erreurs PDO
    }
    } else if ($_POST) {
        var_dump($_POST); // Affiche le contenu de $_POST pour le débogage
        // die("Erreur veuillez réessayer");
    }

    if ($redirect) {
        header("Location: " . $redirect_url);
        exit();
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
    <?php require_once('../elements/header.php'); ?>
    <main class="backofficeadd-container">
        <article class="backofficeadd-card">
            <h1 class="title-mod">AJOUTER UN PRODUIT</h1>
            <?php if (!empty($error_messages)): ?>
                <div class="error-messages">
                    <?php foreach ($error_messages as $message): ?>
                        <p><?php echo htmlspecialchars($message); ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
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
                        <option value="T-shirt">T-shirt</option>
                        <option value="Pull">Pull</option>
                        <option value="Veste">Veste</option>
                        <option value="Pantalon">Pantalon</option>
                        <option value="Jupe">Jupe</option>
                        <option value="Bottes">Bottes</option>
                        <option value="Robe">Robe</option>
                    </select>
                </div>
                <div>
                    <p class="rem-bts">Description:</p>
                    <textarea name="content" id="description" required></textarea>
                </div>
                <p class="img-text-center">Télécharger images:</p>
                    <div class="image-upload">
                        <div class="image-upload-container">
                            <input type="file" name="image_1" id="image1" accept="image/*" style="display: none;">
                            <label for="image1" class="image-upload-btn">Image 1</label>
                            <div class="image-preview" id="preview1"></div>
                        </div>
                        <div class="image-upload-container">
                            <input type="file" name="image_2" id="image2" accept="image/*" style="display: none;">
                            <label for="image2" class="image-upload-btn">Image 2</label>
                            <div class="image-preview" id="preview2"></div>
                        </div>
                        <div class="image-upload-container">
                            <input type="file" name="image_3" id="image3" accept="image/*" style="display: none;">
                            <label for="image3" class="image-upload-btn">Image 3</label>
                            <div class="image-preview" id="preview3"></div>
                        </div>
                        <div class="image-upload-container">
                            <input type="file" name="image_4" id="image4" accept="image/*" style="display: none;">
                            <label for="image4" class="image-upload-btn">Image 4</label>
                            <div class="image-preview" id="preview4"></div>
                        </div>
                    </div>
                <div class="backoff-center-btn">
                    <button type="submit" class="submit-btn">Ajouter le Produit</button>
                </div>
            </form>
        </article>
    </main>

    <script type="text/javascript" src="../JS/script.js" defer></script>
    <?php require_once('../elements/footer.php'); ?>
</body>
</html>
