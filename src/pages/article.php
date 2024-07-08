<?php
session_start();

require_once("../elements/connect.php");

$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($product_id > 0) {
    $sql = "SELECT * FROM products WHERE id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(1, $product_id, PDO::PARAM_INT);
    $stmt->execute();
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        echo "Produit non trouvé.";
        exit;
    }
} else {
    echo "ID de produit invalide.";
    exit;
}

?>

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
<?php require_once ('../elements/header.php');?>

    <div class="retour-accueil">
    <a href="../index.php">Accueil</a>
    </div>

<section id="container-pcp">
    <article class="photos-article">
        <figure class="photo-pcp">
            <img id="thumbnail" src="<?php echo htmlspecialchars($product['image_1']); ?>" alt="php name">
        </figure>
        
        <?php if (!empty($product['image_2']) || !empty($product['image_3']) || !empty($product['image_4'])): ?>
        <figure class="miniatures">
            <?php if (!empty($product['image_2'])): ?>
                <img class="miniature" src="<?php echo htmlspecialchars($product['image_2']); ?>" alt="php name">
            <?php endif; ?>
            <?php if (!empty($product['image_3'])): ?>
                <img class="miniature" src="<?php echo htmlspecialchars($product['image_3']); ?>" alt="php name">
            <?php endif; ?>
            <?php if (!empty($product['image_4'])): ?>
                <img class="miniature" src="<?php echo htmlspecialchars($product['image_4']); ?>" alt="php name">
            <?php endif; ?>
        </figure>
        <?php endif; ?>
    </article>

    <div id="fullscreen-container">
        <span id="close-btn">&times;</span>
        <img id="fullscreen-image" src="<?php echo htmlspecialchars($product['image_1']); ?>" alt="php name">
    </div>

    <article class="description">
        <h4><?php echo htmlspecialchars($product['brand']); ?></h4>
        <h5><?php echo htmlspecialchars($product['price']); ?>€</h5>
        <p>Tailles :</p>
        <div class="btn-tailles">
        <button>XS</button><button>S</button><button>M</button><button>L</button>
        </div>
        <div class="description-produit">
        <p id="descr-produit1"><?php echo htmlspecialchars($product['content']); ?></p>
        </div>
        <div class="add-to-cart">
            <form action="../tools/action_cart/insert_cart.php" method="POST">
                <input type="hidden" name="product_id" value="<?php $product['id'];?>">
                <input type="hidden" name="user_id" value="<?php (isset($_SESSION['id']) ? $_SESSION['id'] : '') ?>">
                <button class="btn btn-dark" type="submit">Ajouter au panier</button>
            </form>
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
<?php
echo '<pre>';
print_r($product);
echo '</pre>';

echo '<pre>';
print_r($_SESSION);
echo '</pre>';
?>
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