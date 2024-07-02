<?php
// vérification que le formulaire est correctement rempli
if(!empty($_POST)){
  // debug
  // echo '<pre>';
  // print_r($_POST);
  // echo '</pre>';
  if(
    isset($_POST["nom"], $_POST["prenom"] , $_POST["email"], $_POST["password"], $_POST["password2"]) 
    && !empty($_POST["nom"])  && !empty($_POST["prenom"]) && !empty($_POST["email"]) && !empty($_POST["password"]) && !empty($_POST["password2"])
  ){
    // Stocke le nom et prénom dans les variables respectives 
    $nom = strip_tags($_POST["nom"]);
    $prenom = strip_tags($_POST["prenom"]);

    // Filtre et valide l’email dans la variable $_POST
      if(!filter_var($_POST["email"] ,FILTER_VALIDATE_EMAIL)){
        die("L'adresse email est incorrecte");
      }
      // Connecte avec la base de données 
      require_once("../elements/connect.php");
      // On ajoute le verification 
      // Etape 2 : Verification de L'existentace de l'email dans la base de données
      $sql_email=" SELECT COUNT(*) AS nd_emails FROM users WHERE email = :email";
      $requete_email = $db->prepare($sql_email);
      $requete_email->bindValue(":email", $_POST["email"],PDO::PARAM_STR);
      $requete_email->execute();

      $email_count = $requete_email->fetchColumn();  //prendre l’email dans la colonne 

    if ($email_count > 0){
      die("Cette edresse email est déjà ultilisée");
    }

    // etape 2 :confirmation des mots de passe
    if(isset($_POST["password"]) && isset($_POST["password2"])){
      $password = $_POST["password"];
      $password2 = $_POST["password2"];
        // Validation de la correspondance des mots de passes
        if($password === $password2){
          // ON hash le mot de passe
          $password = password_hash($_POST["password"], PASSWORD_ARGON2ID);
          // Mot de passe confirme, procede a la logique d'inscription
          header("Location: ../tools/login.php");
    }else{
      die("Le mon de passe ne correspondent pas.");
    }
    } //fin des verifications
    
    // on écri la roquete sql d'inscription
    $sql = "INSERT INTO users (first_name, last_name, email, password, `group`) VALUES (:first_name, :last_name, :email, :password, 'user')";

        $requete = $db->prepare($sql);

        $requete->bindValue(":first_name", $prenom, PDO::PARAM_STR);
        $requete->bindValue(":last_name", $nom, PDO::PARAM_STR);
        $requete->bindValue(":email", $_POST["email"], PDO::PARAM_STR);
        $requete->bindValue(":password", $password, PDO::PARAM_STR);

        $requete->execute();
    } else {
        die("Le formulaire est incomplet");
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="/CSS/style.css">
    <title>login</title>
</head>
<body class="d-flex align-items-center justify-content-center vh-100 register-body">
    
<main>

  <div class="logo-register">
    <img src="/img/illustration/logo-online-training.png" alt="logoOnlineTraining">
  </div>

  <div class="modal modal-sheet position-static d-block bg-body-secondary" tabindex="-1" role="dialog" id="modalSignin">
    <div class="modal-dialog" role="document">
      <div class="modal-content rounded-4 shadow">
        <div class="modal-header  pb-4 border-bottom-0">
          <h1 class="fw-bold mb-0 fs-2">Enregistrez-vous</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body p-5 pt-0">
          <form class="" method="POST" enctype="multipart/form-data">
            <div class="form-floating mb-3">
              <input type="nom" class="form-control rounded-3" id="nom" placeholder="nom" name="nom">
              <label for="nom">Nom</label>
            </div>
            <div class="form-floating mb-3">
              <input type="prenom" class="form-control rounded-3" id="prenom" placeholder="prenom" name="prenom">
              <label for="prenom">Prenom</label>
            </div>
            <div class="form-floating mb-3">
              <input type="email" class="form-control rounded-3" id="email" placeholder="name@example.com" name="email">
              <label for="email">Email address</label>
            </div>
            <div class="form-floating mb-3">
              <input type="password" class="form-control rounded-3" id="password" placeholder="Password" name="password">
              <label for="password">Password</label>
            </div>
            <div class="form-floating mb-3">
              <input type="password" class="form-control rounded-3" id="password2" placeholder="Password2" name="password2">
              <label for="password2">Confirmation Password</label>
            </div>
            <button class="w-100 mb-2 btn btn-lg rounded-3 btn-primary" type="submit">S'enregistrer</button>
            <small class="text-body-secondary">Si vous possédez déjà un compte, cliquer sur <a class="registerbtn" href="login.php">Connexion</a>.</small>
          </form>
        </div>
      </div>
    </div>
  </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>