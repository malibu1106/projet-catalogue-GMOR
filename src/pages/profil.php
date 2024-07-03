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
    return "/img/upload_model/$newname"; // Retourne le chemin relatif
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" integrity="sha512-5A8nwdMOWrSz20fDsjczgUidUBR8liPYU+WymTZP1lmY9G6Oc7HlZv156XqnsgNUzTyMefFTcsFH/tnJE/+xBg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../CSS/backoffice-style.css">
    <title>Profile</title>
</head>
<?php require_once('../elements/header.php'); ?>
<body>

    <main class="backoffice-profil">
        <div class="profil-card">
            <div class="profil-circle">
                <div class="imgBx">
                    <img src="/img/temporaire/No-trep.png" alt="">
                </div>
            </div>
            <div class="profil-content">
                <a href="#"><i class="fa fa-linkedin" aria-hidden="true"></i></a>
                <h3 class="profil-name">Blue Marry</h3>
                <div class="mailIcon">
                    <h4 class="profil-roles">Admin Comptable</h4>
                    <a href="#">
                    <i class="fa fa-envelope-open" aria-hidden="true"></i>
                    </a>
                </div>
            </div>
        </div>
    </main>

</body>
    <script type="text/javascript" src="../JS/script.js" defer></script>
    <?php require_once('../elements/footer.php'); ?>
</html>
