<?php 
const DBHOST = 'db';
const DBNAME = 'catalogue';
const DBUSER = 'gmor';
const DBPASS = 'gmor_password';

    //c'est ça qu'il nous manquait ! le ";" veut lui signifier "ça y est c'est fait"
$dsn = "mysql:host=" . DBHOST . ";dbname=" . DBNAME . ";charset=utf8";

    //Maintenant, on se connecte avec le PDO
try {
$db = new PDO($dsn, DBUSER, DBPASS);
// echo "connexion réussie";
} catch(PDOException $error) {
    echo "échec de la connection: " . $error->getMessage() . "</br>";
}
//ci-dessus la fonction nous sort le message d'erreur en cas d'échec