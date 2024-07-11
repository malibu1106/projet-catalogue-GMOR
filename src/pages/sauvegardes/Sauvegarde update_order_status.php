<?php
session_start();
require_once("../elements/connect.php");

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['order_id']) || !isset($data['action'])) {
    echo json_encode(['success' => false, 'message' => 'ID de commande ou action manquante']);
    exit;
}

$order_id = $data['order_id'];
$action = $data['action'];

// Récupérer le statut actuel de la commande
$sql_get_status = "SELECT status FROM orders WHERE id = :order_id";
$stmt_get_status = $db->prepare($sql_get_status);
$stmt_get_status->bindParam(':order_id', $order_id, PDO::PARAM_INT);
$stmt_get_status->execute();
$current_status = $stmt_get_status->fetchColumn();

if (!$current_status) {
    echo json_encode(['success' => false, 'message' => 'Commande non trouvée']);
    exit;
}

// Mettre à jour le statut de la commande
switch ($action) {
    case 'progress':
        // Déterminer le prochain statut pour la progression
        $next_status = '';

        switch ($current_status) {
            case 'en attente':
                $next_status = 'en cours de traitement';
                break;
            case 'en cours de traitement':
                $next_status = 'expédiée';
                break;
            case 'expédiée':
                $next_status = 'livrée';
                break;
            case 'livrée':
                $next_status = 'livrée'; // Pas de changement après "livrée"
                break;
            case 'annulée':
                $next_status = 'en attente'; // Permettre de réactiver une commande annulée
                break;
            default:
                echo json_encode(['success' => false, 'message' => 'Statut de commande invalide']);
                exit;
        }

        // Mettre à jour le statut de la commande
        $sql_update_status = "UPDATE orders SET status = :status WHERE id = :order_id";
        $stmt_update_status = $db->prepare($sql_update_status);
        $stmt_update_status->bindParam(':status', $next_status, PDO::PARAM_STR);
        $stmt_update_status->bindParam(':order_id', $order_id, PDO::PARAM_INT);

        if ($stmt_update_status->execute()) {
            echo json_encode(['success' => true, 'status' => $next_status, 'message' => 'Statut de commande mis à jour']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erreur lors de la mise à jour du statut de commande']);
        }
        break;

    case 'cancel':
        // Mettre à jour le statut de la commande à "annulée"
        $next_status = 'annulée';

        $sql_update_status = "UPDATE orders SET status = :status WHERE id = :order_id";
        $stmt_update_status = $db->prepare($sql_update_status);
        $stmt_update_status->bindParam(':status', $next_status, PDO::PARAM_STR);
        $stmt_update_status->bindParam(':order_id', $order_id, PDO::PARAM_INT);

        if ($stmt_update_status->execute()) {
            echo json_encode(['success' => true, 'status' => $next_status, 'message' => 'Commande annulée avec succès']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erreur lors de l\'annulation de la commande']);
        }
        break;

    default:
        echo json_encode(['success' => false, 'message' => 'Action non définie ou incorrecte']);
        break;
}
?>
