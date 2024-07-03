<?php
session_start();
require_once("../elements/connect.php");
require_once ('../elements/header.php');

// Récupération de tous les utilisateurs
$sql = "SELECT * FROM users";
$query = $db->prepare($sql);
$query->execute();
$users = $query->fetchAll(PDO::FETCH_ASSOC);

// Traitement du formulaire de mise à jour des rôles
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_roles'])) {
    $user_id = $_POST['user_id'];
    $new_roles = isset($_POST['roles']) ? implode(',', $_POST['roles']) : '';

    $update_sql = "UPDATE users SET `group` = :roles WHERE id = :user_id";
    $update_query = $db->prepare($update_sql);
    $update_query->execute(['roles' => $new_roles, 'user_id' => $user_id]);

    // Redirection pour rafraîchir la page
    header("Location: backoffice-utilisateurs.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Utilisateurs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../CSS/backoffice-style.css">
    <link rel="stylesheet" href="../CSS/style.css">
</head>
<body class="bg-backoff-users">
    <?php require_once ('../elements/header.php');?>
    <main>
        <article>
            <div class="container">
                <h1><i class="fas fa-users icon-users-panel"></i> Gestion des Utilisateurs</h1>
                
                <div id="filterContainer">
                    <h4>Filtrer les utilisateurs</h4>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <input type="text" id="nameFilter" class="form-control" placeholder="Filtrer par nom">
                        </div>
                        <div class="col-md-4">
                            <input type="text" id="firstNameFilter" class="form-control" placeholder="Filtrer par prénom">
                        </div>
                        <div class="col-md-4">
                            <select id="groupFilter" class="form-select">
                                <option value="">Tous les groupes</option>
                                <?php
                                $all_roles = ['admin', 'sub-admin', 'utilisateur', 'formateur', 'apprenant', 'gestion commercial', 'logistique', 'comptable', 'vendeur'];
                                foreach ($all_roles as $role) {
                                    echo "<option value=\"$role\">" . ucfirst($role) . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row" id="userCards">
                    <?php foreach ($users as $user): ?>
                    <div class="col-md-4 user-card-container">
                        <div class="user-card">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></h5>
                                <p class="card-text"><i class="fas fa-envelope"></i> <?= htmlspecialchars($user['email']) ?></p>
                                <div class="user-roles">
                                    <?php
                                    $user_roles = explode(',', $user['group']);
                                    foreach ($user_roles as $role):
                                    ?>
                                    <span class="role-badge"><?= ucfirst(htmlspecialchars($role)) ?></span>
                                    <?php endforeach; ?>
                                </div>
                                <button class="btn btn-edit mt-3" data-bs-toggle="modal" data-bs-target="#editModal<?= $user['id'] ?>">
                                    <i class="fas fa-edit"></i> Modifier les rôles
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- Modal pour modifier les rôles -->
                    <div class="modal fade" id="editModal<?= $user['id'] ?>" tabindex="-1" aria-labelledby="editModalLabel<?= $user['id'] ?>" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editModalLabel<?= $user['id'] ?>">Modifier les rôles de <?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form method="POST">
                                        <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                        <div class="role-checkboxes">
                                            <?php
                                            $roles = ['admin', 'sub-admin', 'utilisateur', 'formateur', 'apprenant', 'gestion commercial', 'logistique', 'comptable', 'vendeur'];
                                            $user_roles = explode(',', $user['group']);
                                            foreach ($roles as $role):
                                            ?>
                                            <label>
                                                <input type="checkbox" name="roles[]" value="<?= $role ?>" <?= in_array($role, $user_roles) ? 'checked' : '' ?>>
                                                <?= ucfirst($role) ?>
                                            </label>
                                            <?php endforeach; ?>
                                        </div>
                                        <button type="submit" name="update_roles" class="btn btn-primary mt-3">Mettre à jour les rôles</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </article>
    </main>
    
    <?php require_once("../elements/footer.php") ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../JS/script.js"></script>
</body>
</html>