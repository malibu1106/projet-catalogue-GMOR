<?php 
session_start();
require_once("../elements/connect.php");

// Requête SQL pour récupérer les commandes archivées
$sql = "SELECT o.id, u.first_name, u.last_name, o.order_date, o.total_amount, o.shipping_address, o.payment_method 
        FROM orders o
        JOIN users u ON o.user_id = u.id
        WHERE o.status = 'archived'
        ORDER BY o.order_date DESC";

$requete = $db->prepare($sql);
$requete->execute();
$commandes_archivees = $requete->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../CSS/backoffice-style.css">
    <link rel="stylesheet" href="../CSS/style.css">
    <title>Commandes Archivées</title>
</head>
<body>
    <?php require_once ('../elements/header.php'); ?>

    <main class="bg-commandes">
        <article class="container mt-4">
            <h1 class="backoff-comm-title mb-4">Commandes Archivées</h1>

            <div class="row">
                <?php foreach($commandes_archivees as $commande): ?>
                    <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                        <div class="card2">
                            <div class="card-body">
                                <h5 class="card-title">Commande #<?= $commande['id'] ?></h5>
                                <p class="card-text"><strong>Client:</strong> <?= $commande['first_name'] . ' ' . $commande['last_name'] ?></p>
                                <p class="card-text"><strong>Date:</strong> <?= $commande['order_date'] ?></p>
                                <p class="card-text"><strong>Total:</strong> €<?= $commande['total_amount'] ?></p>
                                <p class="card-text"><strong>Adresse:</strong> <?= $commande['shipping_address'] ?></p>
                                <p class="card-text"><strong>Méthode de paiement:</strong> <?= $commande['payment_method'] ?></p>
                                <a class="btn btn-sm btn-primary" title="Voir" href="backoffice-commande-details.php?id=<?= $commande["id"] ?>"><i class="bi bi-eye"></i></a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </article>
    </main>

    <?php require_once ('../elements/footer.php'); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="../JS/script.js" defer></script>
</body>
</html>