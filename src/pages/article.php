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
        <img id="thumbnail" src="../img/temporaire/crescendo-aos-poucos.jpeg" alt="php name">
        </figure>
        <figure class="miniatures">
            <img class="miniature" src="../img/temporaire/ninja-cosmico.jpeg" alt="php name">
            <img  class="miniature" src="../img/temporaire/photo1.jpg" alt="php name">
            <img class="miniature" src="../img/temporaire/crescendo-aos-poucos.jpeg" alt="php name">
        </figure>
    </article>

    <div id="fullscreen-container">
        <span id="close-btn">&times;</span>
        <img id="fullscreen-image" src="../img/temporaire/crescendo-aos-poucos.jpeg" alt="php name">
    </div>

    <article class="description">
        <h4>NOM DE L'ARTICLE</h4>
        <h5>26.90€</h5>
        <p>Tailles :</p>
        <div class="btn-tailles">
        <button>XS</button><button>S</button><button>M</button><button>L</button>
        </div>
        <div class="description-produit">
        <p id="descr-produit">Top bandeau court en matière tissée. Modèle avec petit bord volanté en haut, cordon de serrage à nouer à 
            l'encolure et ouverture en V devant. Partie smockée à la taille avec basque. Doublé. <br>

            NUMÉRO D'ARTICLE:1236558002<br>
            Longueur: Courte<br>
            Longueur des manches: Sans manches<br>
            Coupe: Slim fit<br>
            Style: Bandeau, Petite ouverture, Smocks<br>
            Description : Blanc/vert/jaune, Fleuri<br></p>
        </div>
        <div class="add-to-cart">
        <button class="btn btn-dark" type="button";>Ajouter au panier</button>
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
        
<script>

//pour rendre la photo-pcp clicable et qu'elle s'affiche en fullscreen
    document.getElementById('thumbnail').addEventListener('click', function() {
    document.getElementById('fullscreen-container').style.display = 'flex';
});

document.getElementById('close-btn').addEventListener('click', function() {
    document.getElementById('fullscreen-container').style.display = 'none';
});

//pour qu'une miniature s'affiche à la place de l'image pcp
document.addEventListener('DOMContentLoaded', function() {
    const thumbnails = document.getElementById('thumbnail');
    const miniatures = document.querySelectorAll('.miniature');

    let currentThumbnail = document.getElementById('thumbnail');

      // remplacer l'image principale par la miniature cliquée
      function replaceThumbnail(thumbnail, newThumbnail) {
        const tempSrc = thumbnail.src;
        thumbnail.src = newThumbnail.src;
        newThumbnail.src = tempSrc;
        currentThumbnail = thumbnail; // Mettre à jour currentThumbnail
    }

    // clic sur chaque miniature
    miniatures.forEach(function(miniature) {
        miniature.addEventListener('click', function() {
            replaceThumbnail(currentThumbnail, miniature);
        });
    });

    // Afficher l'image en plein écran
    thumbnail.addEventListener('click', function() {
        document.getElementById('fullscreen-image').src = currentThumbnail.src;
        document.getElementById('fullscreen-container').style.display = 'flex';
    });

    // Fermer l'image en plein écran
    document.getElementById('close-btn').addEventListener('click', function() {
        document.getElementById('fullscreen-container').style.display = 'none';
    });
});

//pour le outline au clic sur une taille
document.querySelectorAll('.btn-tailles button').forEach(button => {
    button.addEventListener('mousedown', function(event) {
        // Supprime la classe 'clicked' de tous les boutons
        document.querySelectorAll('.btn-tailles button').forEach(btn => btn.classList.remove('clicked'));
        
        // Ajoute la classe 'clicked' au bouton cliqué
        this.classList.add('clicked');
        
        // Prévenir la perte de focus pendant le clic
        event.preventDefault();

        // empêche la perte de focus après le clic
        this.blur();
    });
});

// enlève la classe 'clicked' quand clique ailleurs
document.addEventListener('click', function(event) {
    // Vérifie si le clic s'est produit à l'extérieur des boutons
    if (!event.target.closest('.btn-tailles button')) {
        // Supprime la classe 'clicked' de tous les boutons
        document.querySelectorAll('.btn-tailles button').forEach(btn => btn.classList.remove('clicked'));
    }
});
</script>
<?php require_once ('../elements/footer.php');?>

</body>
</html>