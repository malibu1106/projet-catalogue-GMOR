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
        <article class="container mt-4">
            <h1 class="backoff-comm-title mb-4">Gestion des Commandes</h1>

            <div class="row">
                <?php foreach($resulta as $commande): ?>
                <div class="col-12 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h5 class="card-title">Commande #<?= $commande['id'] ?></h5>
                                    <p class="card-text"><strong>Client:</strong> <?= $commande['first_name'] . ' ' . $commande['last_name'] ?></p>
                                    <p class="card-text"><strong>Date:</strong> <?= $commande['order_date'] ?></p>
                                    <p class="card-text"><strong>Total:</strong> €<?= $commande['total_amount'] ?></p>
                                    <p class="card-text"><strong>Adresse:</strong> <?= $commande['shipping_address'] ?></p>
                                    <p class="card-text"><strong>Méthode de paiement:</strong> <?= $commande['payment_method'] ?></p>
                                </div>
                                <div class="text-end">
                                    <a class="btn btn-sm btn-primary" title="Voir" href="backoffice-commande-details.php?id=<?= $commande["id"] ?>"><i class="bi bi-eye"></i></a>
                                    <a class="btn btn-sm btn-warning" title="Modifier" href="backoffice-modif-commande.php?id=<?= $commande["id"] ?>"><i class="bi bi-pencil"></i></a>
                                    <a class="btn btn-sm btn-danger" title="Supprimer" href="../tools/delete-commande.php?id=<?= $commande["id"] ?>"><i class="bi bi-trash"></i></a>
                                </div>
                            </div>
                            <div class="progress mt-3">
                                <?php 
                                    $status = strtolower($commande['status']);
                                    $progress = 0;
                                    switch ($status) {
                                        case 'en attente':
                                            $progress = 25;
                                            break;
                                        case 'en traitement':
                                            $progress = 50;
                                            break;
                                        case 'expédié':
                                            $progress = 75;
                                            break;
                                        case 'livré':
                                            $progress = 100;
                                            break;
                                    }
                                ?>
                                <div class="progress-bar" role="progressbar" style="width: <?= $progress ?>%;" aria-valuenow="<?= $progress ?>" aria-valuemin="0" aria-valuemax="100"><?= ucfirst($status) ?></div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </article>
    </main>

    <?php require_once ('../elements/footer.php'); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script type="text/javascript" src="../JS/script.js" defer></script>
</body>
</html>
