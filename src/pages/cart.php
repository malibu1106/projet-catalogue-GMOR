<?php



require_once("../elements/connect.php");
require_once("../elements/header.php");

// $sql = "SELECT * FROM products";
$sql = "SELECT * FROM  carts c LEFT JOIN products p  ON p.id = c.product_id LEFT JOIN users u ON c.user_id = u.id ";
$requete = $db->prepare($sql);
$requete->execute();
$cartResults = $requete->fetchAll(PDO::FETCH_ASSOC);

$sql_count ="SELECT COUNT(*) FROM`carts` WHERE ID <> 0";
$requete_count = $db->prepare($sql_count);
$requete_count->execute();
$result_count = $requete_count->fetchAll(PDO::FETCH_ASSOC);

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/style.css">
    <link rel="stylesheet" href="../CSS/produits.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Ícones do Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Script JavaScript -->
    <script type="text/javascript" src="../JS/script.js" defer></script>
    <title>cart</title>
</head>
<body>
    
<?php    
    if(empty($cartResults)){
        echo 'not the product in this cart.';
        
    }else{
        echo '<h2>your cart have <?= . $result_count  . ?> products</h2>';
        echo'<section class="affichage_des_produits">';
// Boucle pour afficher chaque résultat
 foreach($cartResults as $cartResult){

echo '<article class="">
<figure class="">
    <img class="" src="'. $cartResult['image_1'].'" alt="php name ici">
    <figcaption class="">'. $cartResult['brand'].'</figcaption>
    <figcaption class="">'. $cartResult['color'].'</figcaption>
    <figcaption class="">'. $cartResult['size'].'</figcaption>
    <figcaption class="">'. $cartResult['price'].'</figcaption>

    <//Boutons d’accouplement pour ajouter des produits >

   <div class="btn_action" style="display: flex; width: 50px;">
        <a href=" ?id=$_GET["id"]"><img src="../img/illustration/add_produce.png " alt="add produce"></a>
        <a href=" ?id=$_GET["id"]"><img src="../img/illustration/remove_produce.png" alt="remove produce"></a>
        <a href="../tools/cart_delete.php?id=$_GET["id"]"><img src="../img/illustration/delete.png" alt="delete produce"></a>
    </div>
</figure>
</article>';
 }
}
    echo '<pre>';
    print_r($result_count);
    echo '</pre>';
    echo '<pre>';
    print_r($cartResults);
    echo '</pre>';
?>

<!-- Fermer la section qui a été ouverte dans php -->
</section>

<?php require_once ('../elements/footer.php');?>
</body>
</html>