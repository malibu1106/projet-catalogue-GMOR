<?php

function verifyUserLoginPassword(PDO $db, string $email, string $pass) {
    $sql=("SELECT * FROM users WHERE email = :email");

    $query = $db->prepare($sql);
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->execute();
    $user = $query->fetch();

        //Ici on compare l'adresse mail (et le mdp crypté avec password_hash() ) de l'utilisateur situé dans la BDD, 
        //a l'adresse mail (et mdp) envoyer par l'utilisateur dans le formulaire et si c'est bon, on renvoie le message email trouvé
    if ($user && password_verify($pass, $user['pass'])) {
        return $user;
    }else{
        return false;
    }
}