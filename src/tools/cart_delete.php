<?php

session_start();

if (isset($_GET["id"]) && !empty($_GET["id"])) {
    require_once("../elements/connect.php");

    $id = strip_tags($_GET['id']);

    $sql = "DELETE FROM cart WHERE id=:id";
    $requete = $db->prepare($sql);

    $requete->bindValue(':id', $id);
    $requete->execute();

    $resulta = $requete->fetch();

    if (!$resulta) {
        header('Location: ..pages/cart.php');
    }

    $sql = 'DELETE FROM products WHERE id=:id';
    $requete = $db->prepare($sql);

    $requete->bindValue(":id", $id);
    $requete->execute();

    require_once("../elements/disconnect.php");
    header('Location: ..pages/cart.php');

    session_start();

    $_SESSION['delete_confirm'] = true;
    $_SESSION['article_delete_id'] = $resulta[1];
} else {
    header('Location: ..pages/cart.php');
}
echo '<pre>';
print_r($cartResults);
echo '</pre>';