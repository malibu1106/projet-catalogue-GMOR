<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../CSS/style.css">
    <title>Document</title>
</head>
<body>
    <?php require_once ('../elements/header.php');?>

    <main class="backofficeadd-container">
        <article class="backofficeadd-card">
            <h1>AJOUTER UN PRODUIT</h1>
            <p>Référence du produit:</p> <input type="text">  
            <p>Nom du produit:</p> <input type="text">
            <p>Taille: </p> <select name="size" id="size">
                <option value="XS">XS</option>
                <option value="S">S</option>
                <option value="M">M</option>
                <option value="L">L</option>
                <option value="XL">XL</option>
            </select>

            
            <p>Couleur: </p>
                <div id="custom-select" class="custom-select">
                    <select name="color" id="color" style="display: none;">
                        <option value="bleu">bleu</option>
                        <option value="rouge">rouge</option>
                    </select>
                    <div class="select-styled">bleu</div>
                    <ul class="select-options" style="display: none;">
                        <li data-value="bleu"><span class="color-preview bleu"></span>bleu</li>
                        <li data-value="rouge"><span class="color-preview rouge"></span>rouge</li>
                    </ul>
                </div>

            
            <p>Stock:</p> <input type="text">
            <p>Prix:</p> <input type="text">
            <p>Promotion:</p>
            <p>Catégorie</p><select name="category" id="category">
                <option value="shirt">T-shirt</option>
                <option value="pull">Pull</option>
                <option value="Jacket">Veste</option>
                <option value="pants">Pantalon</option>
                <option value="skirt">Jupe</option>
                <option value="boots">Bottes</option>
                <option value="dress">Robe</option>
            </select>

            <p>Description :</p>
            <input type="text">

            <p>Commentaire:</p>

            <p>Télécharger images:</p>
            <button>1</button>
            <button>1</button>
            <button>1</button>
            <button>1</button>
        </article>
    </main>

    <?php require_once ('../elements/footer.php');?>
</body>
    <script type="text/javascript" src="../JS/script.js" defer></script>
</html>