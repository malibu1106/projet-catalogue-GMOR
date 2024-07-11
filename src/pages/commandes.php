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
    <?php require_once('../elements/header.php'); ?>
    <?php if (isset($_SESSION['success'])) : ?>
        <div class="alert alert-success" role="alert">
            <?= $_SESSION['success'] ?>
            <?php unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])) : ?>
        <div class="alert alert-danger" role="alert">
            <?= $_SESSION['error'] ?>
            <?php unset($_SESSION['error']); ?>
        </div>
    
    <?php if (isset($_SESSION['info'])): ?>
        <div class="alert alert-info" role="alert">
            <?= $_SESSION['info'] ?>
            <?php unset($_SESSION['info']); ?>
        </div>
<?php endif; ?>

    <?php endif; ?>
    <main class="bg-commandes">
        <article class="container mt-4">
            <h1 class="backoff-comm-title mb-4">Gestion des Commandes</h1>
            <a class="nav-link" href="archives_cde.php">Commandes Archivées</a>
            <div class="row">
                <?php foreach ($resulta as $commande) : ?>
                    <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                            <div class="card2" data-order-id="<?= $commande['id'] ?>">
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
                                            <a class="btn btn-sm btn-danger btn-cross" title="Annuler" href="#" onclick="cancelOrder(<?= $commande['id'] ?>); return false;">
                                                <i class="bi bi-x-lg"></i>
                                        </a>
                                            <a class="btn btn-sm btn-danger" title="Supprimer" href="#" onclick="confirmDelete(<?= $commande['id'] ?>); return false;"><i class="bi bi-trash"></i></a>
                                        <a class="btn btn-sm btn-secondary" title="archiver" href="../pages/archive_order.php?id=<?= $commande["id"] ?>"><i class="bi bi-archive"></i></a>
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
                                            case 'en cours de traitement':
                                                $progress = 50;
                                                break;
                                            case 'expédiée':
                                                $progress = 75;
                                                break;
                                            case 'livrée':
                                                $progress = 100;
                                                break;
                                            case 'annulée':
                                            $progress = 0;
                                            break;
                                    }
                                        ?>
                                        <div class="progress-bar" role="progressbar" style="width: <?= $progress ?>%;" aria-valuenow="<?= $progress ?>" aria-valuemin="0" aria-valuemax="100"><?= ucfirst($status) ?></div>
                                    </div>
                                    <!-- Image du tampon "cancel" -->
                                <img src="../img/illustration/cancel-btn.png" class="stamp-cancel" alt="Cancelled Stamp" />

                                <div class="btn-container mt-3 btn-prog-show">
                                        <button type="button" class="btn btn-success btn-progress" data-order-id="<?= $commande['id'] ?>">
                                        Faire progresser la commande
                                    </button>
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCommande<?= $commande['id'] ?>">
                                            Voir les produits commandés
                                        </button>
                                    </div>

                                <!-- Modal -->
                                <div class="modal fade" id="modalCommande<?= $commande['id'] ?>" tabindex="-1" aria-labelledby="modalCommande<?= $commande['id'] ?>Label" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="modalCommande<?= $commande['id'] ?>Label">Produits commandés pour la commande #<?= $commande['id'] ?></h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <ul>
                                                    <?php
                                                    // Requête pour récupérer les produits associés à cette commande, avec taille et couleur
                                                    $sql_produits = "SELECT oi.quantity, p.brand, p.size, p.color
                                                                    FROM order_items oi
                                                                    JOIN products p ON oi.product_id = p.id
                                                                    WHERE oi.order_id = :order_id";
                                                    $requete_produits = $db->prepare($sql_produits);
                                                    $requete_produits->bindParam(':order_id', $commande['id'], PDO::PARAM_INT);
                                                    $requete_produits->execute();
                                                    $produits_commandes = $requete_produits->fetchAll(PDO::FETCH_ASSOC);

                                                    foreach ($produits_commandes as $produit) {
                                                        echo '<li>' . $produit['quantity'] . ' x ' . $produit['brand'] . ' (Taille: ' . $produit['size'] . ', Couleur: ' . $produit['color'] . ')</li>';
                                                    }
                                                    ?>
                                                </ul>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </article>
    </main>

    <?php require_once('../elements/footer.php'); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script type="text/javascript" src="../JS/script.js" defer></script>
    <script>
        function cancelOrder(orderId) {
            if (confirm('Êtes-vous sûr de vouloir annuler cette commande ?')) {
                fetch('update_order_status.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ order_id: orderId, action: 'cancel' })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const card = document.querySelector('.card2[data-order-id="' + orderId + '"]');
                        const progressBar = card.querySelector('.progress-bar');
                        progressBar.style.width = '0%';
                        progressBar.setAttribute('aria-valuenow', '0');
                        progressBar.innerText = 'Annulée';

                        // Afficher l'image du tampon "cancel"
                        const stampImage = card.querySelector('.stamp-cancel');
                        stampImage.style.display = 'block';
                    } else {
                        alert('Erreur: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            }
        }

        function confirmDelete(orderId) {
            if (confirm('Êtes-vous sûr de vouloir supprimer cette commande ?')) {
                window.location.href = '../tools/delete_orders.php?id=' + orderId;
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const progressButtons = document.querySelectorAll('.btn-progress');

            progressButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const orderId = this.getAttribute('data-order-id');
                    fetch('update_order_status.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ order_id: orderId, action: 'progress' })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Mettre à jour la barre de progression et le texte du statut
                            const card = document.querySelector('.card2[data-order-id="' + orderId + '"]');
                            const progressBar = card.querySelector('.progress-bar');
                            let currentProgress = parseInt(progressBar.getAttribute('aria-valuenow'));
                            let nextProgress = currentProgress + 25;

                            if (nextProgress > 100) {
                                nextProgress = 100;
                            }

                            progressBar.style.width = nextProgress + '%';
                            progressBar.setAttribute('aria-valuenow', nextProgress);

                            let newStatusText = '';
                            switch (nextProgress) {
                                case 25:
                                    newStatusText = 'En attente';
                                    break;
                                case 50:
                                    newStatusText = 'En cours de traitement';
                                    break;
                                case 75:
                                    newStatusText = 'Expédiée';
                                    break;
                                case 100:
                                    newStatusText = 'Livrée';
                                    break;
                                case 0:
                                    newStatusText = 'Annulée';
                                    break;
                                default:
                                    newStatusText = progressBar.innerText;
                            }

                            progressBar.innerText = newStatusText;

                            // Cacher l'image du tampon "cancel" si la commande n'est plus annulée
                            const stampImage = card.querySelector('.stamp-cancel');
                            if (nextProgress !== 0) {
                                stampImage.style.display = 'none';
                            }
                        } else {
                            alert('Erreur: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
                });
            });
        });

    </script>
</body>

</html>