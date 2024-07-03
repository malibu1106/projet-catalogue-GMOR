<?php
session_start();
require_once("../elements/connect.php");

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user']) || !isset($_SESSION['user']['id'])) {
    header("Location: ../tools/login.php");
    exit();
}

$user_id = $_SESSION['user']['id'];
$default_avatar = "../img/illustration/img_not_found.png";

// Récupérer les informations complètes de l'utilisateur
$sql = "SELECT * FROM users WHERE id = :user_id";
$query = $db->prepare($sql);
$query->execute(['user_id' => $user_id]);
$user = $query->fetch(PDO::FETCH_ASSOC);

// Déterminer quelle image afficher
$avatar_to_display = $user['avatar'] ? $user['avatar'] : $default_avatar;

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $linkedin = $_POST['linkedin'];
    
    // Traitement de l'upload de l'image
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['avatar']['name'];
        $filetype = pathinfo($filename, PATHINFO_EXTENSION);

        if (in_array(strtolower($filetype), $allowed)) {
            $newname = uniqid('profile_') . "." . $filetype;
            $upload_dir = "../img/upload_avatars/";
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            if (move_uploaded_file($_FILES['avatar']['tmp_name'], $upload_dir . $newname)) {
                $avatar = $upload_dir . $newname;
                
                // Mise à jour de l'avatar dans la base de données
                $update_avatar_sql = "UPDATE users SET avatar = :avatar WHERE id = :user_id";
                $db->prepare($update_avatar_sql)->execute(['avatar' => $avatar, 'user_id' => $user_id]);
            }
        }
    }

    // Mise à jour du LinkedIn
    $update_linkedin_sql = "UPDATE users SET linkedin = :linkedin WHERE id = :user_id";
    $db->prepare($update_linkedin_sql)->execute(['linkedin' => $linkedin, 'user_id' => $user_id]);

    // Rediriger pour rafraîchir les informations
    header("Location: profil.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
    <link rel="stylesheet" href="../CSS/backoffice-style.css">
    <link rel="stylesheet" href="../CSS/style.css">
    <title>Profil</title>
</head>
<body>
    <?php require_once('../elements/header.php'); ?>
    
    <main class="backoffice-profil">
        <div class="profil-card">
        <div class="profil-circle">
            <div class="imgBx">
                <img src="<?= htmlspecialchars($avatar_to_display) ?>" alt="Photo de profil">
            </div>
        </div>
            <div class="profil-content">
                <a href="<?= htmlspecialchars($user['linkedin'] ?? '#') ?>" target="_blank"><i class="fa fa-linkedin" aria-hidden="true"></i></a>
                <h3 class="profil-name"><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></h3>
                <div class="mailIcon">
                    <h4 class="profil-roles"><?= htmlspecialchars($user['group']) ?></h4>
                    <a href="mailto:<?= htmlspecialchars($user['email']) ?>">
                        <i class="fa fa-envelope-open" aria-hidden="true"></i>
                    </a>
                </div>
                <button id="editProfileBtn" class="btn btn-primary mt-3">Modifier le profil</button>
            </div>
        </div>

        <!-- Formulaire de modification (initialement caché) -->
        <div id="editProfileForm" style="display: none;">
            <form method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="linkedin" class="form-label">Lien LinkedIn</label>
                    <input type="url" class="form-control" id="linkedin" name="linkedin" value="<?= htmlspecialchars($user['Linkedin'] ?? '') ?>">
                </div>
                <div class="mb-3">
                    <label for="avatar" class="form-label">Photo de profil</label>
                    <input type="file" class="form-control" id="avatar" name="avatar">
                </div>
                <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
            </form>
        </div>
    </main>

    <?php require_once('../elements/footer.php'); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('editProfileBtn').addEventListener('click', function() {
            document.getElementById('editProfileForm').style.display = 'block';
            this.style.display = 'none';
        });
    </script>
</body>
</html>