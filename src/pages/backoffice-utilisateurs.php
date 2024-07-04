<?php
session_start();
require_once("../elements/connect.php");

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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" integrity="sha512-5A8nwdMOWrSz20fDsjczgUidUBR8liPYU+WymTZP1lmY9G6Oc7HlZv156XqnsgNUzTyMefFTcsFH/tnJE/+xBg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../CSS/backoffice-style.css">
    <link rel="stylesheet" href="../CSS/style.css">
</head>
<body>
    <?php require_once ('../elements/header.php');?>
    <main class="bg-backoff-users">
        <div class="container">
            <h1 class="text-center mb-4"><i class="fas fa-users icon-users-panel"></i> Gestion des Utilisateurs</h1>
            
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
                    <div class="profil-card">
                        <div class="profil-circle">
                            <div class="imgBx">
                                <img src="<?php echo htmlspecialchars($user['avatar']); ?>" alt="Photo de profil">
                            </div>
                        </div>
                        <div class="profil-content">
                            <a href="#"><i class="fa fa-linkedin" aria-hidden="true"></i></a>
                            <h3 class="profil-name">
                                <span class="profil-firstname"><?= htmlspecialchars($user['first_name']) ?></span>
                                <span class="profil-lastname"><?= htmlspecialchars($user['last_name']) ?></span>
                            </h3>
                            <div class="roles-and-email">
                                <div class="profil-roles-container">
                                    <div class="profil-roles">
                                        <?php
                                        $user_roles = explode(',', $user['group']);
                                        foreach ($user_roles as $role):
                                        ?>
                                            <span class="role-badge"><?= htmlspecialchars(trim($role)) ?></span>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <a href="mailto:<?= htmlspecialchars($user['email']) ?>" class="email-icon">
                                    <i class="fa fa-envelope-open" aria-hidden="true"></i>
                                </a>
                            </div>
                            <div>
                                <button class="btn-modif-role" data-bs-toggle="modal" data-bs-target="#editModal<?= $user['id'] ?>">Modifier role</button>
                            </div>
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
    </main>
    
    <?php require_once("../elements/footer.php") ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../JS/script.js"></script>
</body>
</html>