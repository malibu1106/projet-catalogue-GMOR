<?php 
session_start();
require_once("../elements/connect.php");

    // Gestion de la suppression multiple
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_ids']) && is_array($_POST['delete_ids'])) {
        $ids = implode(',', array_map('intval', $_POST['delete_ids']));
        $sql = "DELETE FROM products WHERE id IN ($ids)";
        $query = $db->prepare($sql);
        $query->execute();
        header("Location: backoffice-produits.php");
        exit();
    }


    $sql ="SELECT * FROM products";

    $requete = $db->prepare($sql);
    $requete->execute();
    $resulta = $requete->fetchAll(PDO::FETCH_ASSOC);

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../CSS/backoffice-style.css">
    <link rel="stylesheet" href="../CSS/style.css">
    <title>Gestion des Produits</title>
</head>
<body>
    <?php require_once ('../elements/header.php');?>

    <main class="bg-produits">
        <article class="backgene-set container mt-4">
            <h1 class="backoff-prod-title mb-4">Gestion des Produits</h1>

            <div class="mb-3">
                <a href="backoffice-add.php" class="btn btn-success">
                    <i class="bi bi-plus-circle me-2"></i>Ajouter un nouveau produit
                </a>
            </div>

            <form method="POST" action="backoffice-produits.php" class="table-responsive">
                <table class="table table-striped table-hover mt-3 mb-5">
                <thead>
                    <tr>
                        <th>Action</th>
                        <th class="pointer" data-sort="ref">Référence</th>
                        <th class="pointer" data-sort="brand">Marque</th>
                        <th class="pointer" data-sort="size">Taille</th>
                        <th class="pointer" data-sort="color">Couleur</th>
                        <th class="pointer" data-sort="pattern">Motif</th>
                        <th class="pointer" data-sort="material">Matière</th>
                        <th class="pointer" data-sort="gender">Genre</th>
                        <th class="pointer" data-sort="stock">Stock</th>
                        <th class="pointer" data-sort="price">Prix</th>
                        <th class="pointer" data-sort="discount">Promotion</th>
                        <th class="pointer" data-sort="category">Catégorie</th>
                        <th class="pointer" data-sort="content">Description</th>
                        <th scope="col"><input type="checkbox" id="selectAllProducts"></th>
                    </tr>
                </thead>

                    <?php foreach($resulta as $produit): ?>
                    <tbody>
                        <!-- Exemple en brute a modifier avec du php pour rajouter nos produits de la BDD -->
                        <tr>
                            <td>
                                <a class="btn btn-sm btn-primary btn-space" title="Voir" href="article.php?id=<?= $produit["id"] ?>"><i class="bi bi-eye"></i>
                                <a class="btn btn-sm btn-warning btn-space" title="Modifier" href="backoffice-modif.php?id=<?= $produit["id"] ?>"><i class="bi bi-pencil"></i>
                                <a class="btn btn-sm btn-danger btn-space" title="Supprimer" href="../tools/delete.php?id=<?= $produit["id"] ?>"><i class="bi bi-trash"></i>
                            </td>
                            <td><?= $produit['ref'] ?></td>
                            <td><?= $produit['brand'] ?></td>
                            <td><?= $produit['size'] ?></td>
                            <td><?= $produit['color'] ?></td>
                            <td><?= $produit['pattern'] ?></td>
                            <td><?= $produit['material'] ?></td>
                            <td><?= $produit['gender'] ?></td>
                            <td><?= $produit['stock'] ?></td>
                            <td><?= $produit['price'] ?></td>
                            <td><?= $produit['discount'] ?></td>
                            <td><?= $produit['category'] ?></td>
                            <td class="backoff-short-desc"><?= $produit['content'] ?></td>
                            <td><input type="checkbox" name="delete_ids[]" value="<?= $produit['id'] ?>"></td>
                        </tr>
                    
                        <!-- le foreach plus haut repetera cette structure pour chaque produit -->
                    </tbody>
                    <?php endforeach; ?>
                </table>
                <button type="submit" class="btn btn-danger mb-5">Supprimer les articles sélectionnés</button>
            </form>
        </article>
    </main>
    <?php require_once ('../elements/footer.php');?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script type="text/javascript" src="../JS/script.js" defer></script>
</body>
</html>