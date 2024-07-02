<?php
session_start();
require_once("../elements/connect.php");
require_once("../elements/header.php");

// $sql = "SELECT * FROM products";
$sql = "SELECT * FROM  carts c LEFT JOIN products p  ON p.id = c.product_id LEFT JOIN users u ON c.user_id = u.id ";
$requete = $db->prepare($sql);
$requete->execute();
$cartResults = $requete->fetchAll(PDO::FETCH_ASSOC);

$sql_count = "SELECT SUM(`product_quantity`) AS total_product FROM `carts`  ";
$requete_count = $db->prepare($sql_count);
$requete_count->execute();
$result_count = $requete_count->fetch(PDO::FETCH_ASSOC);

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/style.css">
    <link rel="stylesheet" href="../CSS/cart.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Ícones do Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Script JavaScript -->
    <script type="text/javascript" src="../JS/script.js" defer></script>
    <title>cart</title>
</head>
<body>
    <h1>Récapitulatif de vos commandes</h1>
    
<?php    
    if(empty($cartResults)){
        echo 'not the product in this cart.';
        
    }else{
        echo '<h2>Your cart has <span id="cart-total">' . $result_count['total_product'] . '</span> products</h2>';
        echo'<section class="affichage_des_produits">';
// Boucle pour afficher chaque résultat
 foreach($cartResults as $cartResult){

echo '<article class="">
<figure class="">
    <img class="" src="'. $cartResult['image_1'].'" alt="php name ici">
    <div class="recap">
    <figcaption class="">'. $cartResult['brand'].'</figcaption>
    <figcaption class="">'. $cartResult['color'].'</figcaption>
    <figcaption class="">'. $cartResult['size'].'</figcaption>
    <figcaption class="">'. $cartResult['price'].'</figcaption>
    <figcaption class="">'. $cartResult['product_quantity'].'</figcaption>
    

    <//Boutons d’accouplement pour ajouter des produits >

   <div class="btn_action" style="display: flex; width: 50px;">
        <a href=" ?id=$_GET["id"]"><img src="../img/illustration/add_produce.png " alt="add produce"></a>
        <a href=" ?id=$_GET["id"]"><img src="../img/illustration/remove_produce.png" alt="remove produce"></a>
        <a href="../tools/cart_delete.php?id=$_GET["id"]"><img src="../img/illustration/delete.png" alt="delete produce"></a>
    </div>
    </div>
</figure>
</article>';
foreach($cartResults as $cartResult){
    echo '<article class="">
    <figure class="">
        <img class="" src="'. $cartResult['image_1'].'" alt="php name ici">
        <figcaption class="">product: '. $cartResult['brand'].'</figcaption>
        <figcaption class="">color: '. $cartResult['color'].'</figcaption>
        <figcaption class="">size: '. $cartResult['size'].'</figcaption>
        <figcaption class="">price: '.number_format($cartResult['price'] ?? 0, 2). '</figcaption>
        <figcaption class="product-quantity" data-id="'. $cartResult['id'] .'">quantity: '. $cartResult['product_quantity'].' unit.</figcaption> 
        
        <div class="btn_action" style="display: flex; width: 100px;">
            <button class="cart-action" data-action="add" data-id="'. $cartResult['id'] .'"><img src="../img/illustration/add_produce.png " alt="add produce"></button>
            <button class="cart-action" data-action="subtract" data-id="'. $cartResult['id'] .'"><img src="../img/illustration/remove_produce.png" alt="remove produce"></button>
            <button class="cart-action" data-action="delete" data-id="'. $cartResult['id'] .'"><img src="../img/illustration/delete.png" alt="delete produce"></button>
        </div>
    </figure>
    </article>';    
 }
}
    echo '<pre>';
    print_r($_SESSION);
    echo '</pre>';
    print_r($result_count);
    echo '</pre>';
    echo '<pre>';
    print_r($cartResults);
    echo '</pre>';
?>

<!-- Fermer la section qui a été ouverte dans php -->
</section>

<?php require_once ('../elements/footer.php');?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const cartActions = document.querySelectorAll('.cart-action');
    
    cartActions.forEach(button => {
        button.addEventListener('click', function() {
            const action = this.getAttribute('data-action');
            const cartId = this.getAttribute('data-id');
            updateCart(action, cartId);
        });
    });

    function updateCart(action, cartId) {
    fetch('../tools/action_cart/update_cart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'cart_id=' + cartId + '&action=' + action
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const quantityElement = document.querySelector(`.product-quantity[data-id="${cartId}"]`);
            if (quantityElement) {
                if (action === 'delete') {
                    quantityElement.closest('article').remove();
                } else {
                    quantityElement.textContent = `quantity: ${data.new_quantity} unit.`;
                }
            }
            // Atualiza o total do carrinho
            document.getElementById('cart-total').textContent = data.cart_total;
        } else {
            alert('Erro ao atualizar o carrinho');
        }
    })
    .catch(error => {
        console.error('Erro:', error);
    });
}
});
</script>
</body>
</html>