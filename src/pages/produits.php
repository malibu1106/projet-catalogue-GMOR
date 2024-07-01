<?php
session_start();?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/style.css">
    <link rel="stylesheet" href="../CSS/produits.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script type="text/javascript" src="../JS/script.js" defer></script>
    
    <title>Catalogue de produits</title>
    
</head>
<body>
<?php require_once ('../elements/debug.php');?>

<?php require_once ('../elements/header.php');?>

        <div class="retour-accueil">
        <a href="../index.php">Accueil</a>
        </div>
        <div class="tri">Zone de tri ici
            foreach catégories
        </div>

        <section class="affichage_des_produits">

        <div class="container">
                <div class="card">
                    <img class="" src="../img/temporaire/crescendo-aos-poucos.jpeg" alt="php name ici">
                    <div class="intro">
                        <h1 class="text-h1">Robe d'Été - 26.90€</h1>
                        <p class="text-p">
                            Courte description du produit.
                        </p>
                    </div>
                </div>
        </div>

        <div class="container">
                <div class="card">
                    <img class="" src="../img/temporaire/crescendo-aos-poucos.jpeg" alt="php name ici">
                    <div class="intro">
                    <h1 class="text-h1">Robe d'Été - 26.90€</h1>
                        <p class="text-p">
                            Courte description du produit.
                        </p>
                    </div>
                </div>
        </div>

        <div class="container">
                <div class="card">
                    <img class="" src="../img/temporaire/crescendo-aos-poucos.jpeg" alt="php name ici">
                    <div class="intro">
                    <h1 class="text-h1">Robe d'Été - 26.90€</h1>
                        <p class="text-p">
                            Courte description du produit.
                        </p>
                    </div>
                </div>
        </div>

        <div class="container">
                <div class="card">
                    <img class="" src="../img/temporaire/crescendo-aos-poucos.jpeg" alt="php name ici">
                    <div class="intro">
                    <h1 class="text-h1">Robe d'Été - 26.90€</h1>
                        <p class="text-p">
                            Courte description du produit.
                        </p>
                    </div>
                </div>
        </div>

        <div class="container">
                <div class="card">
                    <img class="" src="../img/temporaire/crescendo-aos-poucos.jpeg" alt="php name ici">
                    <div class="intro">
                    <h1 class="text-h1">Robe d'Été - 26.90€</h1>
                        <p class="text-p">
                            Courte description du produit.
                        </p>
                    </div>
                </div>
        </div>

        <div class="container">
                <div class="card">
                    <img class="" src="../img/temporaire/crescendo-aos-poucos.jpeg" alt="php name ici">
                    <div class="intro">
                    <h1 class="text-h1">Robe d'Été - 26.90€</h1>
                        <p class="text-p">
                            Courte description du produit.
                        </p>
                    </div>
                </div>
        </div>

</section>

<?php require_once ('../elements/footer.php');?>

    
</body>
</html>

