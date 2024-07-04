<?php
// Inicia a sessão e inclui arquivos necessários
session_start();
require_once("../elements/connect.php");


// Verifique se o usuário está logado
if (!isset($_SESSION['user']['id'])) {
    // Redirecione para a página de login ou mostre uma mensagem de erro
    echo "Você precisa estar logado para ver o carrinho.";
    exit();
}

$user_id = $_SESSION['user']['id'];

// Consulta SQL para obter os itens do carrinho do usuário
$sql = "SELECT c.id AS cart_id, c.*,  p.*, u.*  
        FROM  carts c 
        LEFT JOIN products p ON p.id = c.product_id 
        LEFT JOIN users u ON c.user_id = u.id 
        WHERE c.user_id = :user_id";

$requete = $db->prepare($sql);
$requete->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$requete->execute();
$cartResults = $requete->fetchAll(PDO::FETCH_ASSOC);

// Consulta para obter o total de produtos no carrinho
$sql_count = "SELECT SUM(`product_quantity`) AS total_product FROM `carts` WHERE user_id = :user_id";
$requete_count = $db->prepare($sql_count);
$requete_count->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$requete_count->execute();
$result_count = $requete_count->fetch(PDO::FETCH_ASSOC);

// Consulta para calcular o preço total do carrinho
$sql_total = "SELECT SUM(c.product_quantity * p.price) AS total_price 
              FROM carts c 
              JOIN products p ON c.product_id = p.id 
              WHERE c.user_id = :user_id";
$stmt_total = $db->prepare($sql_total);
$stmt_total->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt_total->execute();
$result_total = $stmt_total->fetch(PDO::FETCH_ASSOC);

// echo json_encode([
//     'success' => true, 
//     'new_quantity' => $result['product_quantity'] ?? 0,
//     'cart_total' => $result_total['total_product'] ?? 0,
//     'total_price' => number_format($result_total['total_price'], 2)
// ]);

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
<?php require_once("../elements/header.php");?>
<?php    

// Verifica se o carrinho está vazio
    if(empty($cartResults)){
        echo '<h2>Not the product in this cart.</h2>';
        echo ' <h2><a href="produits.php">Select your products here.</a></h2> ';
        
    }else{
        // Exibe o total de produtos e o preço total
        echo '<h2>Your cart has <span id="cart-total"> ' . $result_count['total_product'] . ' </span> products</h2>';
        
        echo '<h2>Total: <span id="cart-price-total">' . $result_total['total_price'] . '</span>€</h2>';
        echo'<section class="affichage_des_produits">';

        // Boucle pour afficher chaque résultat
    foreach($cartResults as $cartResult){
        // Exibe os detalhes do produto e botões de ação
    echo '<article data-cart-id="'. $cartResult['cart_id'] .'">
    <figure>
                <!-- Detalhes do produto e botões -->
        <a href="article.php?id='. $cartResult['product_id'] .'"><img src="'. $cartResult['image_1'].'" alt="'. $cartResult['brand'] .'"></a>
        <div class="recap">
            <figcaption>product: '. $cartResult['brand'].'</figcaption>
            <figcaption>color: '. $cartResult['color'].'</figcaption>
            <figcaption>size: '. $cartResult['size'].'</figcaption>
            <figcaption class="product-price">price: '.number_format($cartResult['price'], 2). '€</figcaption>
            <figcaption class="product-quantity" data-id="'. $cartResult['cart_id'] .'">quantity: '. $cartResult['product_quantity'].' unit.'. ($cartResult['product_quantity'] !== 1 ? 's' : '') .'</figcaption> 
            
            <div class="btn_action">
                <button class="cart-action" data-action="add" data-id="'. $cartResult['cart_id'] .'" aria-label="Adicionar uma unidade"><img src="../img/illustration/add_produce.png" alt="adicionar produto"></button>
                <button class="cart-action" data-action="subtract" data-id="'. $cartResult['cart_id'] .'" aria-label="Subtrair uma unidade"><img src="../img/illustration/remove_produce.png" alt="remover produto"></button>
                <button class="cart-action" data-action="delete" data-id="'. $cartResult['cart_id'] .'" aria-label="Remover o produto"><img src="../img/illustration/delete.png" alt="deletar produto"></button>
            </div>
        </div>
    </figure>
    </article>';    
}
}
     // Exibe informações de depuração (sessão, contagem e resultados do carrinho)
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

<!-- // Script JavaScript para manipulação do carrinho -->
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Adiciona event listeners aos botões de ação do carrinho
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
                updateCartDisplay(data, action, cartId);
            } else {
                console.error('Erro ao atualizar o carrinho:', data.message);
            }
        })
        .catch(error => {
            console.error('Erro:', error);
        });
    }

    function updateCartDisplay(data, action, cartId) {
        const cartItem = document.querySelector(`article[data-cart-id="${cartId}"]`);
        if (!cartItem) return;

        if (action === 'delete') {
            cartItem.remove();
        } else {
            const quantityElement = cartItem.querySelector('.product-quantity');
            const priceElement = cartItem.querySelector('.product-price');
            
            if (quantityElement) {
                quantityElement.textContent = `quantity: ${data.new_quantity} unit ${data.new_quantity !== 1 ? 's' : ''}`;
            }
            
            if (priceElement) {
                const totalItemPrice = (data.new_quantity * data.item_price).toFixed(2);
                priceElement.textContent = `price: ${totalItemPrice}€`;
            }
        }

        // Atualiza o total de itens no carrinho
        const cartTotalElement = document.getElementById('cart-total');
        if (cartTotalElement) {
            cartTotalElement.textContent = data.cart_total;
        }

        // Atualiza o preço total
        const cartPriceTotalElement = document.getElementById('cart-price-total');
        if (cartPriceTotalElement) {
            cartPriceTotalElement.textContent = data.total_price;
        }

        // Se o carrinho estiver vazio, exibe uma mensagem
        const productSection = document.querySelector('.affichage_des_produits');
        if (data.cart_total == 0 && productSection) {
            productSection.innerHTML = '<h2>Seu carrinho está vazio.</h2>';
        }
    }
});
</script>
</body>
</html>