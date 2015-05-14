<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Espace modification fiche</title>
    <link rel="stylesheet" href="">
    <link rel="stylesheet" href="../css/normalize.css">
    <link rel="stylesheet" href="../css/login.css">


</head>
<body>
<h1>Consultation fiche de frais</h1>

<?php
    include("../scripts/connect.php");

    // Ecriture de la requête d'extraction en SQL
    $reqSQL = "SELECT annee, mois FROM `gsb`.`fichefrais` ORDER BY mois ASC";

    // Envoi de la requête : on récupère le résultat dans le jeu
    // d'enregistrement $result
    $resultat = $connexion->query($reqSQL) or die ("Erreur dans la requete SQL '$reqSQL'");

    // Affichage de la requête
    echo "Résultat de la requête : " . $reqSQL . "<hr>";

    // Lecture de la première ligne du jeu d'enregistrements et copie des données dans le tableau associatif à une dimension $ligne
    $ligne = $resultat->fetch();

    echo '<select size=1 name="cat">' . "\n";
    echo '<option>Choisissez l\'année</option>' . "\n";


    // On boucle tant que $ligne #faux
    while ($ligne != false) {
        // On stocke le contenu des cellules du tableau associatif dans des variables
        $annee = $ligne["annee"];
        $mois = $ligne["mois"];

        // lisibilité de la date
        switch ($mois) {
            case 01:
                $dateFinale = 'Janvier' . " " . $annee;
                break;

            case 02:
                $dateFinale = 'Fevrier' . " " . $annee;
                break;

            case 03:
                $dateFinale = 'Mars' . " " . $annee;
                break;

            case 04:
                $dateFinale = 'Avril' . " " . $annee;
                break;

            case 05:
                $dateFinale = 'Mai' . " " . $annee;
                break;

            case 06:
                $dateFinale = 'Juin' . " " . $annee;
                break;

            case 07:
                $dateFinale = 'Juillet' . " " . $annee;
                break;

            case 08:
                $dateFinale = 'Aout' . " " . $annee;
                break;

            case 09:
                $dateFinale = 'Septembre' . " " . $annee;
                break;

            case 10:
                $dateFinale = 'Octobre' . " " . $annee;
                break;

            case 11:
                $dateFinale = 'Novembre' . " " . $annee;
                break;

            case 12:
                $dateFinale = 'Decembre' . " " . $annee;
                break;
        }

        // on affiche les informations
        echo '<option value="$annee">' . $dateFinale;
        echo '</option>' . "\n";

        // Lecture de la ligne suivante du jeu d'enregistrementse
        $ligne = $resultat->fetch();
    }

    echo '</select>' . "\n";
?>
</body>
</html>