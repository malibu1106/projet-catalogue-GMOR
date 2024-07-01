<?php
session_start();
require_once("../elements/connect.php");

// Vérification de l'existence de l'ID du produit à modifier
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = $_GET['id'];

    // Récupération des données du produit à modifier depuis la base de données
    $query = $db->prepare('SELECT * FROM products WHERE id = :id');
    $query->bindValue(':id', $id, PDO::PARAM_INT);
    $query->execute();
    $produit = $query->fetch(PDO::FETCH_ASSOC);

    if (!$produit) {
        $_SESSION['erreur'] = "Le produit n'existe pas";
        header('Location: backoffice-produits.php');
        exit();
    }
} else {
    $_SESSION['erreur'] = "ID du produit non spécifié";
    header('Location: backoffice-produits.php');
    exit();
}

$imagePath = $produit['image_1']; // Par défaut, garde l'image principale existante

// Traitement du formulaire de mise à jour du produit
if ($_POST) {
    if (isset($_POST['id']) && !empty($_POST['id'])
        && isset($_POST['ref']) && !empty($_POST['ref'])
        && isset($_POST['brand']) && !empty($_POST['brand'])
        && isset($_POST['size']) && !empty($_POST['size'])
        && isset($_POST['color']) && !empty($_POST['color'])
        && isset($_POST['pattern']) && !empty($_POST['pattern'])
        && isset($_POST['material']) && !empty($_POST['material'])
        && isset($_POST['gender']) && !empty($_POST['gender'])
        && isset($_POST['stock']) && !empty($_POST['stock'])
        && isset($_POST['price']) && !empty($_POST['price'])
        && isset($_POST['discount']) && isset($_POST['category']) && isset($_POST['content'])
    ) {
        $id = strip_tags($_POST["id"]);
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

        // Gestion de l'upload de l'image principale
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
            $newfilename = "../img/upload_model/$newname";

            if (!move_uploaded_file($_FILES["image_1"]["tmp_name"], $newfilename)) {
                die("L'upload a échoué");
            }

            chmod($newfilename, 0644);
            $imagePath = "img/upload_model/$newname"; // Chemin relatif à stocker dans la base de données
        }

        // Construction de la requête SQL
        $sql = 'UPDATE products SET ref=:ref, brand=:brand, size=:size, color=:color, 
                pattern=:pattern, material=:material, gender=:gender, stock=:stock, 
                price=:price, discount=:discount, category=:category, content=:content';

        // Vérification et gestion des images facultatives
        if (isset($_FILES["image_2"]) && $_FILES["image_2"]["error"] === 0) {
            $imagePath2 = handleImageUpload($_FILES["image_2"]);
            $sql .= ', image_2=:image_2';
        }

        if (isset($_FILES["image_3"]) && $_FILES["image_3"]["error"] === 0) {
            $imagePath3 = handleImageUpload($_FILES["image_3"]);
            $sql .= ', image_3=:image_3';
        }

        if (isset($_FILES["image_4"]) && $_FILES["image_4"]["error"] === 0) {
            $imagePath4 = handleImageUpload($_FILES["image_4"]);
            $sql .= ', image_4=:image_4';
        }

        $sql .= ' WHERE id=:id';

        // Préparation de la requête SQL
        $query = $db->prepare($sql);

        // Liaison des valeurs aux paramètres de la requête
        $query->bindValue(":ref", $ref, PDO::PARAM_STR);
        $query->bindValue(":brand", $brand, PDO::PARAM_STR);
        $query->bindValue(":size", $size, PDO::PARAM_STR);
        $query->bindValue(":color", $color, PDO::PARAM_STR);
        $query->bindValue(":pattern", $pattern, PDO::PARAM_STR);
        $query->bindValue(":material", $material, PDO::PARAM_STR);
        $query->bindValue(":gender", $gender, PDO::PARAM_STR);
        $query->bindValue(":stock", $stock, PDO::PARAM_INT);
        $query->bindValue(":price", $price, PDO::PARAM_INT);
        $query->bindValue(":discount", $discount, PDO::PARAM_INT);
        $query->bindValue(":category", $category, PDO::PARAM_STR);
        $query->bindValue(":content", $content, PDO::PARAM_STR);
        $query->bindValue(":id", $id, PDO::PARAM_INT);

        // Bind des valeurs pour les images facultatives si elles existent
        if (isset($imagePath2)) {
            $query->bindValue(":image_2", $imagePath2, PDO::PARAM_STR);
        }
        if (isset($imagePath3)) {
            $query->bindValue(":image_3", $imagePath3, PDO::PARAM_STR);
        }
        if (isset($imagePath4)) {
            $query->bindValue(":image_4", $imagePath4, PDO::PARAM_STR);
        }

        // Exécution de la requête
        if ($query->execute()) {
            $_SESSION['message'] = "Produit mis à jour avec succès";
            header("Location: backoffice-produits.php");
            exit();
        } else {
            echo "Erreur lors de la mise à jour du produit.";
            print_r($query->errorInfo());
        }

    } else {
        $_SESSION['erreur'] = "Le formulaire est incomplet";
        header("Location: backoffice-modif.php?id=$id");
        exit();
    }
}

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
        die("Erreur : le format du fichier est incorrect");
    }

    if ($filesize > 1024 * 1024) {
        die("Fichier trop volumineux");
    }

    $newname = md5(uniqid()) . ".$extension";
    $newfilename = "../img/upload_model/$newname";

    if (!move_uploaded_file($file["tmp_name"], $newfilename)) {
        die("L'upload a échoué");
    }

    chmod($newfilename, 0644);
    return "img/upload_model/$newname"; // Retourne le chemin relatif
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
    <title>Modifier le Produit</title>
</head>
<body class="backofficemodif-body">
    <?php require_once('../elements/header.php'); ?>
    <main class="backofficeadd-container">
        <article class="backofficeadd-card">
            <h1 class="title-mod">MODIFIER LE PRODUIT</h1>
            <?php
            // Vérification de l'existence de l'ID du produit à modifier
            if (isset($_GET['id']) && !empty($_GET['id'])) {
                $id = $_GET['id'];

                // Récupération des données du produit à modifier depuis la base de données
                $query = $db->prepare('SELECT * FROM products WHERE id = :id');
                $query->bindValue(':id', $id, PDO::PARAM_INT);
                $query->execute();
                $produit = $query->fetch(PDO::FETCH_ASSOC);

                if (!$produit) {
                    echo "<p>Le produit n'existe pas.</p>";
                } else {
            ?>
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?php echo $produit['id']; ?>">
                <div class="backofficeadd-line">
                    <p class="rem-bts">Référence du produit:</p>
                    <input type="text" name="ref" value="<?php echo htmlspecialchars($produit['ref']); ?>" required>
                </div>
                <div class="backofficeadd-line">
                    <p class="rem-bts">Nom du produit:</p>
                    <input type="text" name="brand" value="<?php echo htmlspecialchars($produit['brand']); ?>" required>
                </div>
                <div class="backofficeadd-line">
                    <p class="rem-bts">Taille: </p>
                    <select name="size" id="size" required>
                        <option value="">Sélectionnez une taille</option>
                        <option value="XS" <?php if ($produit['size'] === 'XS') echo 'selected'; ?>>XS</option>
                        <option value="S" <?php if ($produit['size'] === 'S') echo 'selected'; ?>>S</option>
                        <option value="M" <?php if ($produit['size'] === 'M') echo 'selected'; ?>>M</option>
                        <option value="L" <?php if ($produit['size'] === 'L') echo 'selected'; ?>>L</option>
                        <option value="XL" <?php if ($produit['size'] === 'XL') echo 'selected'; ?>>XL</option>
                    </select>
                </div>
                <div class="backofficeadd-line">
                    <p class="rem-bts">Couleur: </p>
                    <div id="custom-select" class="custom-select">
                        <select name="color" id="color" required>
                            <option value="">Sélectionnez une couleur</option>
                            <option value="bleu" <?php if ($produit['color'] === 'bleu') echo 'selected'; ?>>Bleu</option>
                            <option value="rouge" <?php if ($produit['color'] === 'rouge') echo 'selected'; ?>>Rouge</option>
                        </select>
                    </div>
                </div>
                <div class="backofficeadd-line">
                    <p class="rem-bts">Motif: </p>
                    <div id="custom-select" class="custom-select">
                        <select name="pattern" id="pattern" required>
                            <option value="">Sélectionnez un motif</option>
                            <option value="rayure" <?php if ($produit['pattern'] === 'rayure') echo 'selected'; ?>>Rayure</option>
                            <option value="losange" <?php if ($produit['pattern'] === 'losange') echo 'selected'; ?>>Losange</option>
                            <option value="carre" <?php if ($produit['pattern'] === 'carre') echo 'selected'; ?>>Carré</option>
                        </select>
                    </div>
                </div>
                <div class="backofficeadd-line">
                    <p class="rem-bts">Matière: </p>
                    <div id="custom-select" class="custom-select">
                        <select name="material" id="material" required>
                            <option value="">Sélectionnez un matériel</option>
                            <option value="coton" <?php if ($produit['material'] === 'coton') echo 'selected'; ?>>Coton</option>
                            <option value="polyestere" <?php if ($produit['material'] === 'polyestere') echo 'selected'; ?>>Polyestere</option>
                            <option value="cuir" <?php if ($produit['material'] === 'cuir') echo 'selected'; ?>>Cuir</option>
                        </select>
                    </div>
                </div>
                <div class="backofficeadd-line">
                    <p class="rem-bts">Genre: </p>
                    <div id="custom-select" class="custom-select">
                        <select name="gender" id="gender" required>
                            <option value="">Sélectionnez un genre</option>
                            <option value="homme" <?php if ($produit['gender'] === 'homme') echo 'selected'; ?>>Homme</option>
                            <option value="femme" <?php if ($produit['gender'] === 'femme') echo 'selected'; ?>>Femme</option>
                        </select>
                    </div>
                </div>
                <div class="backofficeadd-line">
                    <p class="rem-bts">Stock:</p>
                    <input type="number" name="stock" min="0" value="<?php echo $produit['stock']; ?>" required>
                </div>
                <div class="backofficeadd-line">
                    <p class="rem-bts">Prix:</p>
                    <input type="number" name="price" min="0" step="0.01" value="<?php echo $produit['price']; ?>" required>
                </div>
                <div class="backofficeadd-line">
                    <p class="rem-bts">Promotion:</p>
                    <input type="number" name="discount" value="<?php echo $produit['discount']; ?>">
                </div>
                <div class="backofficeadd-line">
                    <p class="rem-bts">Catégorie:</p>
                    <select name="category" id="category" required>
                        <option value="">Sélectionnez une catégorie</option>
                        <option value="T-shirt" <?php if ($produit['category'] === 'T-shirt') echo 'selected'; ?>>T-shirt</option>
                        <option value="Pull" <?php if ($produit['category'] === 'Pull') echo 'selected'; ?>>Pull</option>
                        <option value="Veste" <?php if ($produit['category'] === 'Veste') echo 'selected'; ?>>Veste</option>
                        <option value="Pantalon" <?php if ($produit['category'] === 'Pantalon') echo 'selected'; ?>>Pantalon</option>
                        <option value="Jupe" <?php if ($produit['category'] === 'Jupe') echo 'selected'; ?>>Jupe</option>
                        <option value="Bottes" <?php if ($produit['category'] === 'Bottes') echo 'selected'; ?>>Bottes</option>
                        <option value="Robe" <?php if ($produit['category'] === 'Robe') echo 'selected'; ?>>Robe</option>
                    </select>
                </div>
                <div>
                    <p class="rem-bts">Description:</p>
                    <textarea name="content" id="description" required><?php echo htmlspecialchars($produit['content']); ?></textarea>
                </div>
                <p class="img-text-center">Télécharger des images:</p>
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
                    <button type="submit" class="submit-btn">Mettre à jour le Produit</button>
                </div>
            </form>
            <?php
                } // fin du else (produit trouvé)
            } else {
                echo "<p>Identifiant du produit non spécifié.</p>";
            }
            ?>
        </article>
    </main>

    <?php require_once ('../elements/footer.php');?>
</body>
<script type="text/javascript" src="../JS/script.js" defer></script>
</html>
