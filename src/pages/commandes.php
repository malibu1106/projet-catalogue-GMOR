<?php 
session_start();
require_once("../elements/connect.php");

// Déterminer la colonne de tri et l'ordre
$sort_column = isset($_GET['sort_column']) ? $_GET['sort_column'] : 'order_date';
$sort_order = isset($_GET['sort_order']) && $_GET['sort_order'] == 'asc' ? 'asc' : 'desc';

// Requête SQL pour récupérer les commandes avec tri
$sql = "SELECT o.id, u.first_name, u.last_name, o.order_date, o.status, o.total_amount, o.shipping_address, o.payment_method 
        FROM orders o
        JOIN users u ON o.user_id = u.id
        ORDER BY $sort_column $sort_order";

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
    <title>Gestion des Commandes</title>
</head>
<body>
    <?php require_once ('../elements/header.php'); ?>

    <main class="bg-commandes">
        <article class="backgene-set container mt-4">
            <h1 class="backoff-comm-title mb-4">Gestion des Commandes</h1>

            <div class="table-responsive">
                <table class="table table-striped table-hover mt-3 mb-5">
                    <thead>
                        <tr>
                            <th><a href="?sort_column=id&sort_order=<?= $sort_order == 'asc' ? 'desc' : 'asc' ?>">ID</a></th>
                            <th><a href="?sort_column=first_name&sort_order=<?= $sort_order == 'asc' ? 'desc' : 'asc' ?>">Prénom</a></th>
                            <th><a href="?sort_column=last_name&sort_order=<?= $sort_order == 'asc' ? 'desc' : 'asc' ?>">Nom</a></th>
                            <th><a href="?sort_column=order_date&sort_order=<?= $sort_order == 'asc' ? 'desc' : 'asc' ?>">Date de commande</a></th>
                            <th><a href="?sort_column=status&sort_order=<?= $sort_order == 'asc' ? 'desc' : 'asc' ?>">Statut</a></th>
                            <th><a href="?sort_column=total_amount&sort_order=<?= $sort_order == 'asc' ? 'desc' : 'asc' ?>">Montant total</a></th>
                            <th>Adresse de livraison</th>
                            <th>Méthode de paiement</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <?php foreach($resulta as $commande): ?>
                    <tbody>
                        <tr>
                            <td><?= $commande['id'] ?></td>
                            <td><?= $commande['first_name'] ?></td>
                            <td><?= $commande['last_name'] ?></td>
                            <td><?= $commande['order_date'] ?></td>
                            <td><?= $commande['status'] ?></td>
                            <td><?= $commande['total_amount'] ?></td>
                            <td><?= $commande['shipping_address'] ?></td>
                            <td><?= $commande['payment_method'] ?></td>
                            <td>
                                <a class="btn btn-sm btn-primary btn-space" title="Voir" href="backoffice-commande-details.php?id=<?= $commande["id"] ?>"><i class="bi bi-eye"></i></a>
                                <a class="btn btn-sm btn-warning btn-space" title="Modifier" href="backoffice-modif-commande.php?id=<?= $commande["id"] ?>"><i class="bi bi-pencil"></i></a>
                                <a class="btn btn-sm btn-danger btn-space" title="Supprimer" href="../tools/delete-commande.php?id=<?= $commande["id"] ?>"><i class="bi bi-trash"></i></a>
                            </td>
                        </tr>
                    </tbody>
                    <?php endforeach; ?>
                </table>
            </div>
        </article>
    </main>
    <?php require_once ('../elements/footer.php'); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script type="text/javascript" src="../JS/script.js" defer></script>
</body>
</html>
