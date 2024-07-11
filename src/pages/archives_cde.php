<?php 
session_start();
require_once("../elements/connect.php");

// Gestion de la suppression multiple
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_ids']) && is_array($_POST['delete_ids'])) {
    $ids = implode(',', array_map('intval', $_POST['delete_ids']));
    $sql = "DELETE FROM archive WHERE order_id IN ($ids)";
    $query = $db->prepare($sql);
    $query->execute();
    header("Location: archives_cde.php");
    exit();
}

// Récupérer les commandes archivées avec le nom de l'utilisateur
$sql = "SELECT a.order_id, a.cart_id, a.user_id, u.first_name AS user_name, a.order_date, SUM(a.total_amount) as total_amount, a.status 
        FROM archive a
        INNER JOIN users u ON a.user_id = u.id
        GROUP BY a.order_id, a.cart_id, a.user_id, u.first_name, a.order_date, a.status
        ORDER BY a.order_date DESC";

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
    <title>Commandes Archivées</title>
    <style>
        .back-button-container {
            padding: 10px 15px;
            position: fixed;
        }
    </style>
</head>
<body>
    <?php require_once ('../elements/header.php');?>

    <div class="back-button-container">
        <div class="d-flex justify-content-end">
            <a href="../pages/commandes.php" class=" btn-primary">
                <i class="bi bi-arrow-left"></i> Retour
            </a>
        </div>
    </div>

    <main class="bg-commandes">
        <article class="backgene-set container mt-4">
            <h1 class="backoff-prod-title mb-4">Commandes Archivées</h1>

            <?php
            if (isset($_SESSION['success'])) {
                echo '<div class="alert alert-success">' . $_SESSION['success'] . '</div>';
                unset($_SESSION['success']);
            }
            if (isset($_SESSION['error'])) {
                echo '<div class="alert alert-danger">' . $_SESSION['error'] . '</div>';
                unset($_SESSION['error']);
            }
            if (isset($_SESSION['info'])) {
                echo '<div class="alert alert-info">' . $_SESSION['info'] . '</div>';
                unset($_SESSION['info']);
            }
            ?>

            <form method="POST" action="archives_cde.php" class="table-responsive">
                <table class="table table-striped table-hover mt-3 mb-5">
                <thead>
                    <tr>
                        <th>Action</th>
                        <th class="pointer" data-sort="order_id">ID Commande</th>
                        <th class="pointer" data-sort="cart_id">ID Panier</th>
                        <th class="pointer" data-sort="user_name">Nom Utilisateur</th>
                        <th class="pointer" data-sort="order_date">Date de Commande</th>
                        <th class="pointer" data-sort="total_amount">Montant Total</th>
                        <th class="pointer" data-sort="status">Statut</th>
                        <th scope="col"><input type="checkbox" id="selectAllOrders"></th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach($resulta as $commande): ?>
                    <tr>
                        <td>
                            <a class="btn btn-sm btn-primary btn-space" title="Voir" href="view_order.php?id=<?= $commande["order_id"] ?>"><i class="bi bi-eye"></i></a>
                            <a class="btn btn-sm btn-danger btn-space" title="Supprimer" href="../pages/delete_archive.php?id=<?= $commande["order_id"] ?>"><i class="bi bi-trash"></i></a>
                        </td>
                        <td><?= $commande['order_id'] ?></td>
                        <td><?= $commande['cart_id'] ?></td>
                        <td><?= $commande['user_name'] ?></td>
                        <td><?= $commande['order_date'] ?></td>
                        <td><?= number_format($commande['total_amount'], 2) ?> €</td>
                        <td><?= $commande['status'] ?></td>
                        <td><input type="checkbox" name="delete_ids[]" value="<?= $commande['order_id'] ?>"></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                </table>
                <button type="submit" class="btn btn-danger mb-5">Supprimer les commandes sélectionnées de l'archive</button>
            </form>
        </article>
    </main>
    <?php require_once ('../elements/footer.php');?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script type="text/javascript" src="../JS/script.js" defer></script>
</body>
</html>