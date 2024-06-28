<?php
session_start();?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/style.css">
    <link rel="stylesheet" href="../CSS/article.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script type="text/javascript" src="../JS/script.js" defer></script>
    
    <title>Fiche produit</title>
    
</head>
<body>
<?php require_once ('../elements/debug.php');?>

<?php require_once ('../elements/header.php');?>

<div class="retour-accueil">
     <a href="../index.php">Accueil</a>
</div>

<section id="container-pcp">
    <article class="photos-article">
        <figure class="photo-pcp">
        <img src="../img/temporaire/crescendo-aos-poucos.jpeg" alt="php name">
        </figure>
        <figure class="miniatures">
            <img src="../img/temporaire/crescendo-aos-poucos.jpeg" alt="php name">
            <img src="../img/temporaire/crescendo-aos-poucos.jpeg" alt="php name">
            <img src="../img/temporaire/crescendo-aos-poucos.jpeg" alt="php name">
        </figure>
    </article>

    <article class="description">
        <h4>NOM DE L'ARTICLE</h4>
        <h5>26.90€</h5>
        <p>Tailles :</p>
        <div class="btn-tailles">
        <button>XS</button><button>S</button><button>M</button><button>L</button>
        </div>
        <p>Description du produit, matière etc</p>
        <div class="add-to-cart">
        <button>Ajouter au panier</button>
        </div>
    </article>
</section>

<section id="suggestions">
    <h2>Vous aimerez aussi...</h2>
    <article class="photos-suggestions">
        <img src="../img/temporaire/crescendo-aos-poucos.jpeg" alt="php name">
        <img src="../img/temporaire/crescendo-aos-poucos.jpeg" alt="php name">
        <img src="../img/temporaire/crescendo-aos-poucos.jpeg" alt="php name">
    </article>
</section>
        

<?php require_once ('../elements/footer.php');?>

</body>
</html>