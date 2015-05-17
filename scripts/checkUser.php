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
                include("../scripts/loader.php");
                $req->closeCursor();
                exit;
            } // si bon combo

            else if ($resultat && $cat == 'v') {
                // Creation d'un cookie
                setcookie('user', $_POST['login'], 0, '/', $domain, false);
                header('Refresh:2;url=../contents/visiteur.php');
                include("../scripts/loader.php");
                $req->closeCursor();
                exit;
            }
        }
    }

?>
