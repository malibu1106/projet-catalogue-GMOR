<?php
session_start();
require_once("../elements/connect.php");
require_once ('../elements/debug.php');

$category = isset($_GET['category']) ? $_GET['category'] : '';

$sql = "SELECT * FROM products WHERE category = ?";
$stmt = $db->prepare($sql);
$stmt->bindParam(1, $category, PDO::PARAM_STR);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC); ?>

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
     <?php
     require_once ('../elements/header.php');
     ?>

        <div class="retour-accueil">
        <a href="../index.php">Accueil</a>
        </div>
        <div class="tri">Zone de tri ici
            foreach catégories
        </div>

        <section class="affichage_des_produits">
        <?php 
        foreach($result as $row) { 
        ?>
        <div class="container">
            <a href="article.php?id=<?php echo htmlspecialchars($row['id']); ?>">
                <div class="card">
                    <img class="" src="<?php echo htmlspecialchars($row['image_1']); ?>" alt="<?php echo htmlspecialchars($row['brand']); ?>">
                    <div class="intro">
                        <h1 class="text-h1"><?php echo htmlspecialchars($row['brand']); ?> - <?php echo htmlspecialchars($row['price']); ?>€</h1>
                        <p class="text-p">
                        <?php echo htmlspecialchars($row['content']); ?>
                        </p>
                    </div>
                </div>
            </a>
        </div>
        <?php
        }
        ?>
        </section>

<?php require_once ('../elements/footer.php');?>

    
</body>
</html>

