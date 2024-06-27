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
<body class="backofficegeneral-body">
    <?php require_once ('../elements/header.php');?>

    <article class="backgene-set">
        <div class="general-title">
            <h2 class="backgene-title">Administrateur</h2>
        </div>
    

    <div class="backoff-general-panel">
        <div class="card" id="users">
            <div class="card-icon">👥</div>
            <div class="img-card-general" >
                <img src="/img/temporaire/ninja-cosmico.jpeg" alt="">
            </div>
            <div class="card-title">Utilisateurs</div>
            <div class="card-content">Gérez les rôles et les permissions des utilisateurs de votre plateforme.</div>
            <button class="card-action">Gérer les utilisateurs</button>
        </div>
        <div class="card" id="products">
            <div class="card-icon">📦</div>
            <div class="card-title">Produits</div>
            <div class="card-content">Ajoutez, modifiez ou supprimez des produits de votre catalogue.</div>
            <button class="card-action">Gérer les produits</button>
        </div>
        <div class="card" id="messages">
            <div class="card-icon">💬</div>
            <div class="card-title">Messagerie</div>
            <div class="card-content">Consultez et répondez aux messages des utilisateurs et clients.</div>
            <button class="card-action">Accéder à la messagerie</button>
        </div>
        <div class="card" id="orders">
            <div class="card-icon">🛒</div>
            <div class="card-title">Commandes</div>
            <div class="card-content">Suivez et gérez les commandes en cours et l'historique des ventes.</div>
            <button class="card-action">Voir les commandes</button>
        </div>
    </div>
    </article>

    <?php require_once ('../elements/footer.php');?>
</body>
    <script type="text/javascript" src="../JS/script.js" defer></script>
</html>