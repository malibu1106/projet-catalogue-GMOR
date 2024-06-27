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
<body>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <div class="card" id="users">
            <img src="user-icon.png" alt="Users">
            <h2>Gérer les Utilisateurs</h2>
            <p>Ajouter, modifier ou supprimer des utilisateurs.</p>
            <button onclick="manageUsers()">Gérer</button>
        </div>
        <div class="card" id="products">
            <img src="product-icon.png" alt="Products">
            <h2>Gérer les Produits</h2>
            <p>Ajouter, modifier ou supprimer des produits.</p>
            <button onclick="manageProducts()">Gérer</button>
        </div>
        <div class="card" id="messaging">
            <img src="message-icon.png" alt="Messaging">
            <h2>Messagerie</h2>
            <p>Consulter et envoyer des messages.</p>
            <button onclick="manageMessaging()">Gérer</button>
        </div>
        <div class="card" id="orders">
            <img src="order-icon.png" alt="Orders">
            <h2>Gérer les Commandes</h2>
            <p>Consulter et gérer les commandes.</p>
            <button onclick="manageOrders()">Gérer</button>
        </div>
    </div>
    <script src="scripts.js"></script>
</body>
</html>




</body>
<script type="text/javascript" src="../JS/script.js" defer></script>
</html>