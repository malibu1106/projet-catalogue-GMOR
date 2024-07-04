<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script type="text/javascript" src="JS/script.js" defer></script>
    
    <title>Accueil</title>
    
</head>
<body>

<?php
require_once ('elements/debug.php');
require_once ('elements/header.php');
require_once ('pages/slider.php');?>

<section id="container-global">
        <h1>Découvrez nos&nbsp;<span>meilleures ventes</span>&nbsp;du mois</h1>
        <article class="container-photos-haut">
            <div class="container-photos-1">
                <div class="photo-grande">
                    <div class="image-container">
                        <img src="img/temporaire/vente1.jpg">
                        <div class="overlay">
                            <p>Consultez cet article</p>
                        </div>
                    </div>
                </div>
                <div class="photo-petite">
                    <div class="image-container">
                        <img src="img/temporaire/vente2.jpg">
                        <div class="overlay">
                            <p>Consultez cet article</p>
                        </div>
                    </div>
                </div>
            </div>
        </article>
        <article class="container-photos-bas">
            <div class="container-photos-1">
                <div class="photo-grande">
                    <div class="image-container">
                        <img src="img/temporaire/vente4.jpg">
                        <div class="overlay">
                            <p>Consultez cet article</p>
                        </div>
                    </div>
                </div>
                <div class="photo-petite">
                    <div class="image-container">
                        <img src="img/temporaire/vente3.jpg">
                        <div class="overlay">
                            <p>Consultez cet article</p>
                        </div>
                    </div>
                </div>
            </div>
        </article>
    </section>
    <section id="categories">
        <h1>Trouvez votre <span>bonheur</span> parmi nos articles</h1>
            <a href="pages/produits.php?category=Robe" style="text-decoration: none; color: inherit;">
            <article class="rond-categorie">
               <img src="img/illustration/robe.png">
               <p>Robes</p>
            </article>
            </a>
            <a href="pages/produits.php?category=T-shirt" style="text-decoration: none; color: inherit;">
            <article class="rond-categorie">
               <img src="img/illustration/tshirt.png">
               <p>T-shirts</p>
            </article>
            </a>
            <a href="pages/produits.php?category=Pantalon" style="text-decoration: none; color: inherit;">
            <article class="rond-categorie">
               <img src="img/illustration/pantalon.png">
               <p>Pantalons</p>
            </article>
            </a>
            <a href="pages/produits.php?category=Veste" style="text-decoration: none; color: inherit;">
            <article class="rond-categorie">
               <img src="img/illustration/veste.png">
               <p>Vestes</p>
            </article>
            </a>
            <a href="pages/produits.php?category=pull" style="text-decoration: none; color: inherit;">
            <article class="rond-categorie">
               <img src="img/illustration/pull.jpg">
               <p>Pulls</p>
            </article>
            </a>
    </section>
<?php require_once ('elements/footer.php');?>

<style>
    #menuBurgerIcon {
        display: none;
    }
</style>
</body>
</html>

