<!doctype html>

<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Loading</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<section class="loaders loaders-bg-3"><span class="loader loader-circles"> </span></section>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="js/main.js"></script>
</body>
</html>

<?php

include("../scripts/connect.php");
// header('Location: ../contents/visiteur.php');
// variable d'aide a la creation du cookie pour regler conflit du localhost
$domain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false;

// verifie que tous les champs soient remplis
if (!empty($_POST)) {
    if (empty($_POST['login']) || empty($_POST['password'])) {

    } else {

        // initialisation des variables de session
        $username = ($_POST['login']);
        $pass = ($_POST['password']);


        //Vérification des identifiants
        $req = $connexion->prepare('SELECT * FROM visiteur WHERE login = :username AND mdp = :password');
        $req->execute(array(':password' => $pass, ':username' => $username,));
        $resultat = $req->fetch();


        // Ecriture de la requête d'extraction de la categorie utilisateur
        $reqSQL = "SELECT categorie FROM visiteur WHERE login='$username'";
        // Envoi de la requête
        $resultat2 = $connexion->query($reqSQL) or die ("Erreur dans la requete SQL '$reqSQL'");
        // On stocke le résultat dans un tableau
        $ligne = $resultat2->fetch();
        // On affecte la valeur de chaque cellule du tableau à une variable
        $cat = $ligne['categorie'];


        if (!$resultat) {
            header('Refresh:1;url=../index.html');
        } // si mauvais combo

        else if ($resultat && $cat == 'c') {

            // Creation d'un cookie
            setcookie('user', $_POST['login'], 0, '/', $domain, false);
            header('Refresh:2;url=../contents/comptable.php');

            $req->closeCursor();
            exit;
        } // si bon combo

        else if ($resultat && $cat == 'v') {
            // Creation d'un cookie
            setcookie('user', $_POST['login'], 0, '/', $domain, false);
            header('Refresh:2;url=../contents/visiteur.php');

            $req->closeCursor();
            exit;
        }


    }
}


?>
