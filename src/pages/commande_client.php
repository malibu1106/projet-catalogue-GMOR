<?php
session_start();
require_once("../elements/connect.php");

// Verifica se o usuário está autenticado
if (!isset($_SESSION['user']) || !isset($_SESSION['user']['id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user']['id'];

// Verifica se um ID de ordem foi fornecido
if (!isset($_GET['order_id'])) {
    die("ID de ordem não fornecido.");
}

$order_id = $_GET['order_id'];

try {
    // Recuperação da ordem específica do usuário
    $stmt = $db->prepare("SELECT o.*, u.first_name, u.last_name 
                          FROM orders o 
                          JOIN users u ON o.user_id = u.id 
                          WHERE o.id = ? AND o.user_id = ?");
    $stmt->execute([$order_id, $user_id]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$order) {
        die("Ordem não encontrada ou não pertence a este usuário.");
    }

    // Recuperação dos itens da ordem
    $stmt = $db->prepare("SELECT oi.*, p.ref, p.brand 
                          FROM order_items oi 
                          JOIN products p ON oi.product_id = p.id 
                          WHERE oi.order_id = ?");
    $stmt->execute([$order_id]);
    $order_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Erro no banco de dados: " . $e->getMessage());
    die("Ocorreu um erro ao processar sua solicitação. Por favor, tente novamente mais tarde.");
}

// Resto do código HTML permanece o mesmo...
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commande <?php echo htmlspecialchars($order['id']); ?></title>
    <style>
        /* Estilos CSS aqui */
    </style>
</head>
<body>
    <div class="header">
        <h1>SARL Société de démonstration</h1>
        <p>25 rue de Montmartre<br>
        75002 PARIS 2EME ARRONDISSEMENT</p>
    </div>

    <div class="client-info">
        <h2>Client</h2>
        <p><?php echo htmlspecialchars($order['first_name'] . ' ' . $order['last_name']); ?><br>
        <?php echo htmlspecialchars($order['shipping_address']); ?></p>
    </div>

    <div class="order-details">
    <h2>Détails de la commande</h2>
    <p><strong>Numéro de commande:</strong> <?php echo htmlspecialchars($order['id']); ?></p>
    <p><strong>Date:</strong> <?php echo date('d/m/Y', strtotime($order['order_date'])); ?></p>
    <p><strong>Livraison le:</strong> <?php echo date('d/m/Y', strtotime($order['order_date'] . ' +3 days')); ?></p>
    <p><strong>Statut:</strong> <?php echo htmlspecialchars($order['status']); ?></p>
    <a href="my_orders.php" class="btn btn-primary">Retour à Mes Commandes</a>
    </div>

    <div class="order-items">
        <h2>Articles commandés</h2>
        <table>
            <tr>
                <th>Référence</th>
                <th>Description</th>
                <th>Qté</th>
                <th>P.U. TTC</th>
                <th>Montant TTC</th>
            </tr>
            <?php foreach ($order_items as $item): ?>
            <tr>
                <td><?php echo htmlspecialchars($item['ref']); ?></td>
                <td><?php echo htmlspecialchars($item['brand']); ?></td>
                <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                <td><?php echo number_format($item['price'], 2, ',', ' '); ?> €</td>
                <td><?php echo number_format($item['price'] * $item['quantity'], 2, ',', ' '); ?> €</td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <div class="order-summary">
        <h2>Résumé de la commande</h2>
        <p><strong>Total TTC:</strong> <?php echo number_format($order['total_amount'], 2, ',', ' '); ?> €</p>
        <p><strong>Mode de paiement:</strong> <?php echo htmlspecialchars($order['payment_method']); ?></p>
    </div>

    <div class="footer">
        <p>Siret : 11111111811115 - APE : 4785Z - N° TVA intracom : FR44111111118 - Capital : 20 000,00 €</p>
    </div>
</body>
</html>