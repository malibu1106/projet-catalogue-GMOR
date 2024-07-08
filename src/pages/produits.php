<?php
session_start();
require_once("../elements/connect.php");

$category = isset($_GET['category']) ? $_GET['category'] : '';
$size = isset($_GET['size']) ? $_GET['size'] : '';
$color = isset($_GET['color']) ? $_GET['color'] : '';
$pattern = isset($_GET['pattern']) ? $_GET['pattern'] : '';
$material = isset($_GET['material']) ? $_GET['material'] : '';
$gender = isset($_GET['gender']) ? $_GET['gender'] : '';

$sql = "SELECT * FROM products WHERE category = :category";
$params = [':category' => $category];

if ($size) {
    $sql .= " AND size = :size";
    $params[':size'] = $size;
}
if ($color) {
    $sql .= " AND color = :color";
    $params[':color'] = $color;
}
if ($pattern) {
    $sql .= " AND pattern = :pattern";
    $params[':pattern'] = $pattern;
}
if ($material) {
    $sql .= " AND material = :material";
    $params[':material'] = $material;
}
if ($gender) {
    $sql .= " AND gender = :gender";
    $params[':gender'] = $gender;
}

$stmt = $db->prepare($sql);
$stmt->execute($params);
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/style.css">
    <link rel="stylesheet" href="../CSS/produits.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script type="text/javascript" src="../JS/script.js" defer></script>
    <title>Catalogue de produits</title>
    <style>
        .tri form {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .tri select, .tri input[type="submit"] {
            padding: 5px;
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <?php require_once ('../elements/header.php'); ?>
    
    <div class="retour-accueil">
        <a href="../index.php">Accueil</a>
    </div>
    
    <div class="tri">
        <form action="" method="GET">
            <input type="hidden" name="category" value="<?php echo htmlspecialchars($category); ?>">
            
            <select name="size">
                <option value="">Toutes les tailles</option>
                <option value="XS" <?php if($size == 'XS') echo 'selected'; ?>>XS</option>
                <option value="S" <?php if($size == 'S') echo 'selected'; ?>>S</option>
                <option value="M" <?php if($size == 'M') echo 'selected'; ?>>M</option>
                <option value="L" <?php if($size == 'L') echo 'selected'; ?>>L</option>
                <option value="XL" <?php if($size == 'XL') echo 'selected'; ?>>XL</option>
            </select>

            <select name="color">
                <option value="">Toutes les couleurs</option>
                <option value="bleu" <?php if($color == 'bleu') echo 'selected'; ?>>Bleu</option>
                <option value="rouge" <?php if($color == 'rouge') echo 'selected'; ?>>Rouge</option>
            </select>

            <select name="pattern">
                <option value="">Tous les motifs</option>
                <option value="rayure" <?php if($pattern == 'rayure') echo 'selected'; ?>>Rayures</option>
                <option value="losange" <?php if($pattern == 'losange') echo 'selected'; ?>>Losanges</option>
                <option value="carre" <?php if($pattern == 'carre') echo 'selected'; ?>>Carrés</option>
            </select>

            <select name="material">
                <option value="">Toutes les matières</option>
                <option value="Coton" <?php if($material == 'Coton') echo 'selected'; ?>>Coton</option>
                <option value="Polyestère" <?php if($material == 'Polyestère') echo 'selected'; ?>>Polyestère</option>
                <option value="Cuir" <?php if($material == 'Cuir') echo 'selected'; ?>>Cuir</option>
            </select>

            <?php if ($category !== 'Robe'): ?>
                <select name="gender">
                    <option value="">Tous les genres</option>
                    <option value="homme" <?php if($gender == 'homme') echo 'selected'; ?>>Homme</option>
                    <option value="femme" <?php if($gender == 'femme') echo 'selected'; ?>>Femme</option>
                </select>
            <?php endif; ?>

            <input type="submit" value="Filtrer">
        </form>
    </div>
    
    <section class="affichage_des_produits">
        <?php foreach($result as $row) { ?>
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
        <?php } ?>
    </section>

    <?php require_once ('../elements/footer.php'); ?>
</body>
</html>