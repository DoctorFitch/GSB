<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Bidon</title>
        <link rel="stylesheet" href="../css/style.css">
    </head>
    <body>
        

        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
        <script src="js/main.js"></script>

<?php

    include ('../scripts/connect.php');
    include ("../scripts/checkUser.php");

    // obtention de l'id de l'utilisateur
    $sqlIdentifiant = "SELECT id 
                       FROM Visiteur 
                       WHERE login = 'dandre' AND mdp = 'oppg5';";

    $resultIdentifiant = $connexion->query($sqlIdentifiant) or die ("Erreur morray");
    $ligne = $resultIdentifiant->fetch();

    $idUtilisateur = $ligne["id"];
    $resultIdentifiant->closeCursor();
 
    // fin obtention de l'id utilisateur

    
    /////////////////////// PARTIE FRAIS NORMAUX ///////////////////////
    
    // Verification que le bouton submit appartient au formulaire des frais normaux
    if(isset($_POST['validation']))
    {
        // Recuperation des donnees du formulaire de visiteur.php (fiche frais)
        $dateFicheFrais = $_POST['date'];
        $repasFicheFrais = $_POST['repas'];
        $nuiteeFicheFrais = $_POST['nuitee'];
        $etapeFicheFrais = $_POST['etape'];
        $kilometreFicheFrais = $_POST['km'];

        $date = str_replace("-", "", substr($dateFicheFrais,-10, 7));

        // On prepare les requete d'insertion
        $sqlInsertRepas = 'INSERT INTO `gsb`.`lignefraisforfait` (`idVisiteur`, `mois`, `idFraisForfait`, `quantite`) VALUES (:idVisiteur, :mois, "REP", :quantite)';
        $sqlInsertNuitee = 'INSERT INTO `gsb`.`lignefraisforfait` (`idVisiteur`, `mois`, `idFraisForfait`, `quantite`) VALUES (:idVisiteur, :mois, "NUI", :quantite)';
        $sqlInsertEtape = 'INSERT INTO `gsb`.`lignefraisforfait` (`idVisiteur`, `mois`, `idFraisForfait`, `quantite`) VALUES (:idVisiteur, :mois, "ETP", :quantite)';
        $sqlInsertKilometres = 'INSERT INTO `gsb`.`lignefraisforfait` (`idVisiteur`, `mois`, `idFraisForfait`, `quantite`) VALUES (:idVisiteur, :mois, "KM", :quantite)';


        // Affichage verificatif
        echo "ID : " .$idUtilisateur;
        echo "<br>"."Date : " .$date;
        echo "<br>"."Nombre de repas : " .$repasFicheFrais;
        echo "<br>"."Nombre de nuitee  : " .$nuiteeFicheFrais;
        echo "<br>"."Nombre d'etape  : " .$etapeFicheFrais;
        echo "<br>"."Nombre de kilometre  : " .$kilometreFicheFrais;

        // Requete INSERT pour remplir les repas 
        $reqRepas = $connexion->prepare($sqlInsertRepas);
        $reqRepas->execute(array(
                "idVisiteur" => $idUtilisateur, 
                "mois" => $date,
                "quantite" => $repasFicheFrais
                ));

        // Requete INSERT pour remplir les nuitees
        $reqNuitee = $connexion->prepare($sqlInsertNuitee);
        $reqNuitee->execute(array(
                "idVisiteur" => $idUtilisateur, 
                "mois" => $date,
                "quantite" => $nuiteeFicheFrais
                ));

        // Requete INSERT pour remplir les etapes
        $reqEtapes = $connexion->prepare($sqlInsertEtape);
        $reqEtapes->execute(array(
                "idVisiteur" => $idUtilisateur, 
                "mois" => $date,
                "quantite" => $etapeFicheFrais
                ));

        // Requete INSERT pour remplir les kilometres
        $reqKilometres = $connexion->prepare($sqlInsertKilometres);
        $reqKilometres->execute(array(
                "idVisiteur" => $idUtilisateur, 
                "mois" => $date,
                "quantite" => $kilometreFicheFrais
                ));
        }


    /////////////////////// PARTIE FRAIS HORS FORFAIT ///////////////////////

    // Verification que le bouton submit correspond au formulaire des frais hors forfait
    if(isset($_POST['validationHF']))
    {
        // Recuperation des donnees du formulaire de visiteur.php (fiche frais hors forfait)
        $dateFicheHorsFrais = $_POST['dateHF'];
        $libelleHorsForfait = $_POST['libelle'];
        $montantHorsForfait = $_POST['montant'];

        $date = str_replace("-", "", substr($dateFicheHorsFrais,-10, 7));

        $sqlInsertFraisHF = 'INSERT INTO `gsb`.`lignefraishorsforfait` (`id`, `idVisiteur`, `mois`, `libelle`, `date`, `montant`) VALUES (NULL, :idVisiteur, :mois, :libelle, :date, :montant)'; 

        // Requete INSERT pour remplir les repas 
        $reqDateHF = $connexion->prepare($sqlInsertFraisHF);
        if($reqDateHF->execute(array("idVisiteur" => $idUtilisateur, "mois" => $date, "libelle" => $libelleHorsForfait, "date" => $dateFicheHorsFrais, "montant" => $montantHorsForfait)) == true)
        { 
            echo '<div id="infoEnvoie">Envoie du formulaire en cours, veuillez patientez...</div>';
            header('Refresh:1000;url=../contents/visiteur.php');
        }
        else 
        {
            echo '<div id="infoEnvoie2">Une erreur est survenue impossible de traiter votre demande</div>';
            header('Refresh:1000;url=../contents/visiteur.php');
        }
    }

    $connexion = null;
?>
            </body>
</html>