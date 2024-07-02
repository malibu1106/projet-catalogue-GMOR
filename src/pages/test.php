<?php 
session_start();
require_once("../elements/connect.php");

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

            <div class="table-responsive">
                <table class="table table-striped table-hover mt-3 mb-5">
                    <thead>
                        <tr>
                            <th>Action</th>
                            <th>Référence</th>
                            <th>Marque</th>
                            <th>Taille</th>
                            <th>Couleur</th>
                            <th>Motif</th>
                            <th>Matière</th>
                            <th>Genre</th>
                            <th>Stock</th>
                            <th>Prix</th>
                            <th>Promotion</th>
                            <th>Catégorie</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <?php foreach($resulta as $produit): ?>
                    <tbody>
                        <!-- Exemple en brute a modifier avec du php pour rajouter nos produits de la BDD -->
                        <tr>
                            <td>
                                <button class="btn btn-sm btn-primary" title="Voir"><i class="bi bi-eye"></i></button>
                                <button class="btn btn-sm btn-warning" title="Modifier"><a href="backoffice-modif.php?id=<?= $produit["id"] ?>"><i class="bi bi-pencil"></i></button>
                                <button class="btn btn-sm btn-danger" title="Supprimer"><a href="../tools/delete.php?id=<?= $produit["id"] ?>"><i class="bi bi-trash"></i></button>
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
                        </tr>
                    
                        <!-- Répétez cette structure pour chaque produit -->
                    </tbody>
                    <?php endforeach; ?>
                </table>
            </div>
        </article>
    </main>
    <?php require_once ('../elements/footer.php');?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script type="text/javascript" src="../JS/script.js" defer></script>
</body>
</html>