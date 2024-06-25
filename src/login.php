<?php
session_start();
require_once('user.php');

$errors = [];
$messages = [];

// On teste si le formulaire a bien été rempli
if (isset($_POST['loginUser'])) {
    if (isset($_POST["email"], $_POST["password"]) && !empty($_POST["email"]) && !empty($_POST["password"])) {
        if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
            // filter_var et FILTER_VALIDATE_EMAIL servent à sécuriser l'adresse mail en back-end
            die("L'adresse eMail est incorrecte");
        }

        require_once('connect.php');

        $user = verifyUserLoginPassword($db, $_POST['email'], $_POST['password']);

        if ($user) {
            // On utilise une seule session pour stocker l'utilisateur
            $_SESSION['user'] = [
                'email' => $user['email'],
                'id' => $user['id'],
                "nom" => $user['nom'],
                "prenom" => $user['prenom'],
                "group" => $user['group']
            ];
            header('Location: index.php');
            exit(); // On arrete le script aprés la redirection
        } else {
            $errors[] = 'Email ou mot de passe incorrect.';
        }
    } else {
        $errors[] = 'Veuillez remplir tous les champs du formulaire.';
    }
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Catalogue</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="/CSS/style.css">
    <link rel="stylesheet" href="/CSS/fonts.css">
</head>

<body class="d-flex align-items-center justify-content-center vh-100 login-body">
    <section class="full-box">

        <!-- Afficher les erreurs s'il y en a -->
        <?php if (!empty($errors)) : ?>
            <div class="alert alert-danger" role="alert">
                <?php foreach ($errors as $error) : ?>
                    <p><?= htmlspecialchars($error) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <article>
            <section class="container col-8">
            <img src="/img/illustration/logo-online-training.png" alt="logoOnlineTraining">
                <form method="POST" class="p-4 p-md-5 border rounded-3 bg-body-tertiary center-form">
                    <div class="form-floating mb-3">
                        <input type="email" class="form-control" name="email" id="email" placeholder="name@example.com">
                        <label for="email">Email address</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="password" class="form-control" name="password" id="password" placeholder="Password">
                        <label for="password">Password</label>
                    </div>
                    <div class="checkbox mb-3">
                        <label>
                            <input type="checkbox" value="remember-me"> Remember me
                        </label>
                    </div>
                    <button class="w-100 btn btn-lg btn-primary" name="loginUser" type="submit">Connexion</button>
                    <hr class="my-4">
                    <small class="text-body-secondary">Si vous ne possédez pas de compte cliquer sur <a href="inscription.php">S'enregistrer</a>.</small>
                </form>
            </section>
        </article>
    </section>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>