<?php

// Définitions de constantes pour la connexion à MySQL
$hote = "localhost";
$login = "root";
$mdp = "";
$nombd = "gsb";

// Connection au serveur
try {
    $connexion = new PDO("mysql:host=$hote;dbname=$nombd", $login, $mdp);

    //Modification du jeu de caractères de la connexion
    $req = "SET NAMES utf8";

    $connexion->query($req);

} catch (PDOException $e) {
    die("\nConnexion a '$hote' impossible : " . $e->getMessage());
}

?>