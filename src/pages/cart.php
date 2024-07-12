<?php
// Démarre la session et inclut les fichiers nécessaires
session_start();
require_once("../elements/connect.php");

// Ajoutez ceci au début de votre script pour voir ce que vous recevez
error_log('Data received: ' . file_get_contents('php://input'));



// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['user']['id'])) {
    // Redirige vers la page de connexion ou affiche un message d'erreur
    echo "<h2>You must be logged in to view the cart.</h2>";
    echo "<h3>click <a href='../tools/login.php'>here</a> to connect.</h3>";
    exit();
}

$user_id = $_SESSION['user']['id'];

// Requête SQL pour obtenir les éléments du panier de l'utilisateur
$sql = "SELECT c.id AS cart_id, c.*,  p.*, u.*  
        FROM  carts c 
        LEFT JOIN products p ON p.id = c.product_id 
        LEFT JOIN users u ON c.user_id = u.id 
        WHERE c.user_id = :user_id";

$requete = $db->prepare($sql);
$requete->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$requete->execute();
$cartResults = $requete->fetchAll(PDO::FETCH_ASSOC);

// Requête pour obtenir le nombre total de produits dans le panier
$sql_count = "SELECT SUM(`product_quantity`) AS total_product FROM `carts` WHERE user_id = :user_id";
$requete_count = $db->prepare($sql_count);
$requete_count->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$requete_count->execute();
$result_count = $requete_count->fetch(PDO::FETCH_ASSOC);

// Requête pour calculer le prix total du panier
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
    <?php require_once("../elements/header.php"); ?>
    <?php
    echo '<div class="back-button-container">';
    echo '<div class="d-flex justify-content-end">';
    echo   '<a href="../index.php" class=" btn_retour">';
    echo     '<i class="bi bi-arrow-left"></i> Retour </a>';
    echo '</div>';
    echo '</div>';
    // Vérifie si le panier est vide
    if (empty($cartResults)) {
        echo '<h2>Not the product in this cart.</h2>';
        echo ' <p><a href="produits.php">Select your products here.</a></p> ';
        echo ' <h2><a href="../pages/my_orders.php">My order</a></h2> ';
    } else {
        echo '<h2><a href="../pages/my_orders.php">My order</a></h2>';
        // Affiche le nombre total de produits et le prix total
        echo '<h2>Your cart has <span id="cart-total"> ' . $result_count['total_product'] . ' </span> products</h2>';

        echo '<h2>Total: <span id="cart-price-total">' . $result_total['total_price'] . '</span>€</h2>';
        echo '<section class="affichage_des_produits">';

        // Boucle pour afficher chaque produit dans le panier
        foreach ($cartResults as $cartResult) {
            // Affiche les détails du produit et les boutons d'action
            echo '<article data-cart-id="' . $cartResult['cart_id'] . '">
    <div>
        <figure >
                    <!-- Detalhes do produto e botões -->
            <a href="article.php?id=' . $cartResult['product_id'] . '" class="card"><img src="' . $cartResult['image_1'] . '" alt="' . $cartResult['brand'] . '"></a>
            <div class="recap">
                <figcaption>product: ' . $cartResult['brand'] . '</figcaption>
                <figcaption>color: ' . $cartResult['color'] . '</figcaption>
                <figcaption>size: ' . $cartResult['size'] . '</figcaption>
                <figcaption class="product-price">price: ' . number_format($cartResult['price'], 2) . '€</figcaption>
                <figcaption class="product-quantity" data-id="' . $cartResult['cart_id'] . '">quantity: ' . $cartResult['product_quantity'] . ' unit.' . ($cartResult['product_quantity'] !== 1 ? 's' : '') . '</figcaption>
        
                <div class="btn_action">
                    <button class="cart-action" data-action="add" data-id="' . $cartResult['cart_id'] . '" aria-label="Adicionar uma unidade"><img src="../img/illustration/add_produce.png" alt="adicionar produto"></button>
                    <button class="cart-action" data-action="subtract" data-id="' . $cartResult['cart_id'] . '" aria-label="Subtrair uma unidade"><img src="../img/illustration/remove_produce.png" alt="remover produto"></button>
                    <button class="cart-action" data-action="delete" data-id="' . $cartResult['cart_id'] . '" aria-label="Remover o produto"><img src="../img/illustration/delete.png" alt="deletar produto"></button>
                </div>
            </div>
        </figure>
    </div>
    </article>';
        }
    }

    // Affiche le bouton "Finaliser la commande" si le panier n'est pas vide
    if (!empty($cartResults)) {
        echo '<div class="finalizar-compra">';
        echo '<button id="btn-finalizar-compra" class="btn btn-dark">Finaliser la commande</button>';
        echo '</div>';
    }

    // Affiche les informations de débogage (session, comptage et résultats du panier)
    // echo '<pre>';
    // print_r($_SESSION);
    // echo '</pre>';
    // print_r($result_count);
    // echo '</pre>';
    // echo '<pre>';
    // print_r($cartResults);
    // echo '</pre>';
    ?>

    <!-- Fermer la section qui a été ouverte dans php -->
    </section>

    <!-- Popup pour les informations de livraison et de paiement -->
    <div id="popup-endereco" class="popup" style="display: none;">
        <div class="popup-content">
            <h3>Informations de livraison et de paiement</h3>

            <form id="form-endereco">
                <label for="endereco">Adresse de livraison <br> ex: 01 rue label 58000 Nevers:</label>
                <textarea id="endereco" name="endereco" required></textarea>

                <label for="metodo-pagamento">Méthode de paiement:</label>
                <select id="metodo-pagamento" name="metodo_pagamento" required>
                    <option value="">Sélectionner...</option>
                    <option value="Carte_bancaire">Carte bancaire</option>
                    <option value="Virement_bancaire">Virement bancaire</option>
                    <option value="PayPal">PayPal</option>
                </select>

                <button type="submit" class="btn btn-success">Confirmer la commande</button>
                <button type="button" id="btn-annuler" class="btn btn-secondary">Annuler</button>
            </form>
        </div>
    </div>


    <?php require_once('../elements/footer.php'); ?>


    <!-- // Script JavaScript pour gérer les actions du panier-->
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
                            updateCartDisplay(data, action, cartId);
                        } else {
                            console.error('Erreur lors de la mise à jour du panier:', data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Erreur:', error);
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
                        quantityElement.textContent = `quantité: ${data.new_quantity} unité${data.new_quantity !== 1 ? 's' : ''}`;
                    }

                    if (priceElement) {
                        const totalItemPrice = (data.new_quantity * data.item_price).toFixed(2);
                        priceElement.textContent = `prix: ${totalItemPrice}€`;
                    }
                }

                const cartTotalElement = document.getElementById('cart-total');
                if (cartTotalElement) {
                    cartTotalElement.textContent = data.cart_total;
                }

                const cartPriceTotalElement = document.getElementById('cart-price-total');
                if (cartPriceTotalElement) {
                    cartPriceTotalElement.textContent = data.total_price;
                }

                const productSection = document.querySelector('.affichage_des_produits');
                if (data.cart_total == 0 && productSection) {
                    productSection.innerHTML = '<h2>Votre panier est vide.</h2>';
                }
            }

            const btnFinalizarCompra = document.getElementById('btn-finalizar-compra');
            const popup = document.getElementById('popup-endereco');
            const form = document.getElementById('form-endereco');

            if (btnFinalizarCompra) {
                btnFinalizarCompra.addEventListener('click', function() {
                    popup.style.display = 'block';
                    popup.setAttribute('aria-hidden', 'false');
                    popup.classList.add('show-popup');
                });
            }

            form.addEventListener('submit', function(e) {
                e.preventDefault();

                const endereco = document.getElementById('endereco').value;
                const metodoPagamento = document.getElementById('metodo-pagamento').value;

                fetch('../pages/creer_order.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            // endereco: endereco,
                            // metodo_pagamento: metodoPagamento,
                            shipping_address: endereco,
                            payment_method: metodoPagamento
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Commande effectuée avec succès!');
                            window.location.href = '../index.php?id=' + data.order_id;
                        } else {
                            alert('Erreur lors de la création de la commande ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Erreur:', error);
                        alert('Erreur lors du traitement de votre demande. Veuillez réessayer.');
                    });

                popup.style.display = 'none';
                popup.setAttribute('aria-hidden', 'true');
                popup.classList.remove('show-popup');
            });

            const btnAnnuler = document.getElementById('btn-annuler');
            if (btnAnnuler) {
                btnAnnuler.addEventListener('click', function() {
                    popup.style.display = 'none';
                    popup.setAttribute('aria-hidden', 'true');
                    popup.classList.remove('show-popup');
                });
            }
        });
    </script>
</body>

</html>