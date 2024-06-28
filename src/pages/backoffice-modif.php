<?php
session_start();

// Traitement du formulaire de mise à jour
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
        && isset($_POST['discount']) && !empty($_POST['discount'])
        && isset($_POST['category']) && !empty($_POST['category'])
        && isset($_POST['content']) && !empty($_POST['content'])
        ) {

        require_once("../elements/connect.php");

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

        // Vérification de la promotion
        $discount = isset($_POST['discount']) ? 1 : 0;

        // Gestion de l'upload de l'image
        $imagePath = ""; // Initialisation de la variable
        if (isset($_FILES["image"]) && $_FILES["image"]["error"] === 0) {
            $allowed = [
                "jpg" => "image/jpeg",
                "jpeg" => "image/jpeg",
                "png" => "image/png"
            ];

            $filename = $_FILES["image"]["name"];
            $filetype = $_FILES["image"]["type"];
            $filesize = $_FILES["image"]["size"];
            $extension = pathinfo($filename, PATHINFO_EXTENSION);

            if (!array_key_exists($extension, $allowed) || !in_array($filetype, $allowed)) {
                die("Erreur : le format du fichier est incorrect");
            }

            if ($filesize > 1024 * 1024) {
                die("Fichier trop volumineux");
            }

            $newname = md5(uniqid()) . ".$extension";
            $newfilename = __DIR__ . "/img/upload_animaux/$newname";

            if (!move_uploaded_file($_FILES["image"]["tmp_name"], $newfilename)) {
                die("L'upload a échoué");
            }

            chmod($newfilename, 0644);
            $imagePath = "img/upload_animaux/$newname"; // Chemin relatif à stocker dans la base de données
        }

        // Construction de la requête SQL
        $sql = 'UPDATE products SET `ref`=:ref, `brand`=:brand, `size`=:size, `color`=:color, 
        `pattern`=:pattern, `material`=:material, `gender`=:gender, `stock`=:stock, 
        `price`=:price, `discount`=:discount, `category`=:category, `content`=:content';
        
        if ($imagePath) {
            $sql .= ', `images`=:images';
        }
        $sql .= ' WHERE `id`=:id;';

        $query = $db->prepare($sql);

        $query->bindValue(":id", $id, PDO::PARAM_INT);
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

        if ($imagePath) {
            $query->bindValue(":images", $imagePath, PDO::PARAM_STR);
        }

        $query->execute();

        $_SESSION['message'] = "Fiche produit modifiée";
        // header("Location: pagedetail.php?id=$id");
        exit();

    } else {
        $_SESSION['erreur'] = "Le formulaire est incomplet";
        header('Location: backoffice-modif.php?id=' . $_POST['id']);
        exit();
    }
}

// Récupération des données actuelles du produit si l'id est présent dans l'URL
if (isset($_GET["id"]) && !empty($_GET["id"])) {
    require_once("../elements/connect.php");

    $id = strip_tags($_GET["id"]);

    $sql = "SELECT * FROM products WHERE id = :id;";

    $query = $db->prepare($sql);

    $query->bindValue(':id', $id, PDO::PARAM_INT);

    $query->execute();

    $produit = $query->fetch();

    if (!$produit) {
        $_SESSION['erreur'] = "Cet id n'existe pas...";
    }

} else {
    $_SESSION["erreur"] = "URL invalide";
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
<body class="backofficemodif-body">
    <?php require_once ('../elements/header.php');?>

    <main class="backkoff-admin-panel">
        <article class="backofficemodif-container">
            <section class="backofficemodif-card">
                <h1>PRODUIT ACTUEL</h1>


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
                        <label for="image1" class="image-upload-btn">Image 1</label>
                            <div class="backoff-modif-img"></div>
                        <label for="image2" class="image-upload-btn">Image 2</label>
                            <div class="backoff-modif-img"></div>
                        <label for="image3" class="image-upload-btn">Image 3</label>
                            <div class="backoff-modif-img"></div>
                        <label for="image4" class="image-upload-btn">Image 4</label>
                            <div class="backoff-modif-img"></div>
                    </div>
                    <div class="backoff-center-btn">
                        <button type="submit" class="submit-btn">Ajouter le Produit</button>
                    </div>
                </form>
                
            </section>
        </article>


        <article class="backofficemodif-container">
            <section class="backofficemodif-card">
                <h1>MODIFIER UN PRODUIT</h1>
                <form method="POST" enctype="multipart/form-data">
                    <div class="backofficemodif-line">
                        <p class="rem-bts">Référence du produit:</p>
                        <input type="text" name="ref" value="<?= htmlspecialchars($produit['ref']) ?>" required>
                    </div>
                    <div class="backofficemodif-line">
                        <p class="rem-bts">Nom du produit:</p>
                        <input type="text" name="brand" value="<?= htmlspecialchars($produit['brand']) ?>" required>
                    </div>
                    <div class="backofficemodif-line">
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
                    <div class="backofficemodif-line">
                        <p class="rem-bts">Couleur: </p>
                        <div id="custom-select" class="custom-select">
                            <select name="color" id="color" required>
                                <option value="">Sélectionnez une couleur</option>
                                <option value="bleu">bleu</option>
                                <option value="rouge">rouge</option>
                            </select>
                        </div>
                    </div>
                    <div class="backofficemodif-line">
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
                    <div class="backofficemodif-line">
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
                    <div class="backofficemodif-line">
                        <p class="rem-bts">Genre: </p>
                        <div id="custom-select" class="custom-select">
                            <select name="gender" id="gender" required>
                                <option value="">Sélectionnez un genre</option>
                                <option value="homme">Homme</option>
                                <option value="femme">Femme</option>
                            </select>
                        </div>
                    </div>
                    <div class="backofficemodif-line">
                        <p class="rem-bts">Stock:</p>
                        <input type="number" name="stock" min="0" required>
                    </div>
                    <div class="backofficemodif-line">
                        <p class="rem-bts">Prix:</p>
                        <input type="number" name="price" min="0" step="0.01" required>
                    </div>
                    <div class="backofficemodif-line">
                        <p class="rem-bts">Promotion:</p>
                        <input type="number" name="discount">
                    </div>
                    <div class="backofficemodif-line">
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
            </section>
        </article>
    </main>

    <?php require_once ('../elements/footer.php');?>
</body>
    <script type="text/javascript" src="../JS/script.js" defer></script>
</html>
