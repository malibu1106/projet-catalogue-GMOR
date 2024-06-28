<?php
//IMAGES MULTIPLE
session_start();
require_once("../elements/connect.php");

// Tableau pour stocker les chemins des images
$imagePaths = ['image_1' => '', 'image_2' => '', 'image_3' => '', 'image_4' => ''];

// Fonction pour traiter une image uploadée
function processImage($imageField) {
    global $imagePaths;
    if (isset($_FILES[$imageField]) && $_FILES[$imageField]["error"] === 0) {
        $allowed = ["jpg" => "image/jpeg", "jpeg" => "image/jpeg", "png" => "image/png"];
        $filename = $_FILES[$imageField]["name"];
        $filetype = $_FILES[$imageField]["type"];
        $filesize = $_FILES[$imageField]["size"];
        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if (!array_key_exists($extension, $allowed) || !in_array($filetype, $allowed)) {
            return "Erreur : le format du fichier $imageField est incorrect";
        }
        if ($filesize > 1024 * 1024) {
            return "Fichier $imageField trop volumineux";
        }

        $newname = md5(uniqid()) . ".$extension";
        $newfilename = "../img/upload_model/$newname";
        if (!move_uploaded_file($_FILES[$imageField]["tmp_name"], $newfilename)) {
            return "L'upload de $imageField a échoué";
        }

        chmod($newfilename, 0644);
        $imagePaths[$imageField] = "../img/upload_model/$newname";
        return "";
    }
    return "";
}

// Traiter l'image principale (obligatoire)
$errorMsg = processImage("image_1");
if ($errorMsg) {
    die($errorMsg);
}

// Traiter les images facultatives
for ($i = 2; $i <= 4; $i++) {
    $errorMsg = processImage("image_$i");
    if ($errorMsg) {
        // Afficher un avertissement mais continuer le traitement
        echo "$errorMsg<br>";
    }
}

// Vérifier si le formulaire a été soumis avec tous les champs requis
if ($_POST && isset($_POST["ref"]) && isset($_POST["brand"]) && isset($_POST["size"]) && isset($_POST["color"])
    && isset($_POST["pattern"]) && isset($_POST["material"]) && isset($_POST["gender"]) && isset($_POST["stock"])
    && isset($_POST["price"]) && isset($_POST["discount"]) && isset($_POST["category"]) && isset($_POST["content"])) {

    // Nettoyer les données entrées par l'utilisateur
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

    // Préparer la requête SQL pour insérer les données dans la table products
    $sql = "INSERT INTO products (ref, brand, size, color, pattern, material, gender, stock, price, discount, category, content, image_1, image_2, image_3, image_4)
            VALUES (:ref, :brand, :size, :color, :pattern, :material, :gender, :stock, :price, :discount, :category, :content, :image_1, :image_2, :image_3, :image_4)";

    try {
        $query = $db->prepare($sql);
        // Lier les valeurs aux paramètres de la requête
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
        $query->bindValue(":image_1", $imagePaths['image_1']);
        $query->bindValue(":image_2", $imagePaths['image_2']);
        $query->bindValue(":image_3", $imagePaths['image_3']);
        $query->bindValue(":image_4", $imagePaths['image_4']);

        // Exécuter la requête
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
    die("Erreur : veuillez réessayer");
}
?>




// Récupération des données actuelles du produit si l'id est présent dans l'URL
// if (isset($_GET["id"]) && !empty($_GET["id"])) {
//     require_once("../elements/connect.php");

//     $id = strip_tags($_GET["id"]);

//     $sql = "SELECT * FROM products WHERE id = :id;";

//     $query = $db->prepare($sql);

//     $query->bindValue(':id', $id, PDO::PARAM_INT);

//     $query->execute();

//     $produit = $query->fetch();

//     if (!$produit) {
//         $_SESSION['erreur'] = "Cet id n'existe pas...";
//     }

// } else {
//     $_SESSION["erreur"] = "URL invalide";
// }


<!-- 
                <form method="POST" enctype="multipart/form-data">
                    <div class="backofficemodif-line">
                        <p class="rem-bts">Référence du produit:</p>
                        <p><?= $produit['ref'] ?></p>
                    </div>
                    <div class="backofficemodif-line">
                        <p class="rem-bts">Nom du produit:</p>
                        <p><?= $produit['brand'] ?></p>
                    </div>
                    <div class="backofficemodif-line">
                        <p class="rem-bts">Taille: </p>
                        <p><?= $produit['size'] ?></p>
                    </div>
                    <div class="backofficemodif-line">
                        <p class="rem-bts">Couleur: </p>
                        <p><?= $produit['color'] ?></p>
                    </div>
                    <div class="backofficemodif-line">
                        <p class="rem-bts">Motif: </p>
                        <p><?= $produit['pattern'] ?></p>
                    </div>
                    <div class="backofficemodif-line">
                        <p class="rem-bts">Matière: </p>
                        <p><?= $produit['material'] ?></p>
                    </div>
                    <div class="backofficemodif-line">
                        <p class="rem-bts">Genre: </p>
                        <p><?= $produit['gender'] ?></p>
                    </div>
                    <div class="backofficemodif-line">
                        <p class="rem-bts">Stock:</p>
                        <?= $produit['stock'] ?>
                    </div>
                    <div class="backofficemodif-line">
                        <p class="rem-bts">Prix:</p>
                        <?= $produit['price'] ?>
                    </div>
                    <div class="backofficemodif-line">
                        <p class="rem-bts">Promotion:</p>
                        <?= $produit['discount'] ?>
                    </div>
                    <div class="backofficemodif-line">
                        <p class="rem-bts">Catégorie:</p>
                        <p><?= $produit['category'] ?></p>
                    </div>
                    <div>
                        <p class="rem-bts">Description:</p>
                        <p><?= $produit['content'] ?></p>
                    </div>
                    <p class="img-text-center">Télécharger images:</p>
                    <div class="image-upload">
                        <img src="<?= $imagePath; ?>" class="d-block mx-lg-auto" alt="Bootstrap Themes" width="auto" height="600px" loading="lazy">
                    </div>
                    <div class="backoff-center-btn">
                        <button type="submit" class="submit-btn">Ajouter le Produit</button>
                    </div>
                </form> -->