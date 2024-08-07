<?php
    session_start();
    require_once("elements/connect.php");

    $sql = "SELECT * FROM products";
    $requete = $db->prepare($sql);
    $requete->execute();
    $produits = $requete->fetchAll(PDO::FETCH_ASSOC);

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
require_once ('elements/header.php');
require_once ('pages/slider.php');?>

<section id="container-global">
        <h1>Découvrez nos&nbsp;<span>meilleures ventes</span>&nbsp;du mois</h1>

        <article class="article-produits">
            <section class="random-produits">
                <?php 
                    //random
                    shuffle($produits);

                    $counter = 0;

                    foreach ($produits as $produit) {
                        if ($counter < 4) {
                            if (!empty($produit['image_1'])) {
                                $imagePath = $produit['image_1'];
                            } else {
                                $imagePath = '/img/illustration/img_not_found.png';
                            }
                ?>
                <div class="photo-grande">
                    <div class="image-container">
                        <img src="<?= htmlspecialchars($imagePath); ?>" alt="Image de <?= htmlspecialchars($produit['brand']); ?>">
                        <div class="overlay">
                            <a class="no-deco" href="/pages/article.php?id=<?= $produit["id"] ?>"><p>Consultez cet article</p></a>
                        </div>
                    </div>
                </div>
                <?php 
                    $counter++;
                        } else {
                            break;
                        }
                    }
                ?>
            </section>
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
<script>
        document.addEventListener('DOMContentLoaded', function () {
            var konamiCode = [38, 38, 40, 40, 37, 39, 37, 39, 66, 65];
            var konamiIndex = 0;

            document.addEventListener('keydown', function (e) {
                if (e.keyCode === konamiCode[konamiIndex]) {
                    konamiIndex++;
                    if (konamiIndex === konamiCode.length) {
                        window.location.href = '/pages/konami.php';
                    }
                } else {
                    konamiIndex = 0;
                }
            });
        });
    </script>
</html>

