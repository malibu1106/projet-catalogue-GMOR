<?php

session_start();

if (isset($_GET["id"]) && !empty($_GET["id"])) {
    require_once("../elements/connect.php");

    $id = strip_tags($_GET['id']);

    // Récupération des chemins des images avant de supprimer le produit
    $sql = "SELECT image_1, image_2, image_3, image_4 FROM products WHERE id=:id";
    $requete = $db->prepare($sql);
    $requete->bindValue(':id', $id);
    $requete->execute();
    $images = $requete->fetch(PDO::FETCH_ASSOC);

    if ($images) {
        // Suppression des images du dossier si elles existent
        $imagePaths = ['image_1', 'image_2', 'image_3', 'image_4'];
        foreach ($imagePaths as $imagePath) {
            if (!empty($images[$imagePath]) && file_exists("../" . $images[$imagePath])) {
                unlink("../" . $images[$imagePath]);
            }
        }
    }

    // Suppression du produit de la base de données
    $sql = "DELETE FROM products WHERE id=:id";
    $requete = $db->prepare($sql);
    $requete->bindValue(':id', $id);
    $requete->execute();

    // Confirmation de la suppression
    $_SESSION['delete_confirm'] = true;
    $_SESSION['article_delete_id'] = $id;

    require_once("../elements/disconnect.php");
    header('Location: ../pages/backoffice-produits.php');
} else {
    header('Location: ../pages/backoffice-produits.php');
}
?>
