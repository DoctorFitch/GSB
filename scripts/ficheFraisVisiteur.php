<?php

include('../scripts/connect.php');
include("../scripts/checkUser.php");

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
if (isset($_POST['validation'])) {
    $date = date('Y-m-d');
    // Recuperation des donnees du formulaire de visiteur.php (fiche frais)
    $dateFicheFraisA = str_replace("-", "", substr($_POST['date'], -7, 4));
    $dateFicheFraisM = str_replace("-", "", substr($_POST['date'], -2, 2));
    $repasFicheFrais = $_POST['repas'];
    $nuiteeFicheFrais = $_POST['nuitee'];
    $etapeFicheFrais = $_POST['etape'];
    $kilometreFicheFrais = $_POST['km'];


    // On prepare les requete d'insertion
    $creationFicheFrais = "INSERT INTO `gsb`.`fichefrais` (`idVisiteur`, `annee`, `mois`, `nbJustificatifs`, `montantValide`, `dateModif`, `idEtat`) VALUES (:idVisiteur, :annee, :mois, '1', '', '', 'CR')";
    $sqlInsertRepas = 'INSERT INTO `gsb`.`lignefraisforfait` (`idVisiteur`, `annee`, `mois`, `idFraisForfait`, `quantite`) VALUES (:idVisiteur, :annee, :mois, "REP", :quantite)';
    $sqlInsertNuitee = 'INSERT INTO `gsb`.`lignefraisforfait` (`idVisiteur`, `annee`, `mois`, `idFraisForfait`, `quantite`) VALUES (:idVisiteur, :annee, :mois, "NUI", :quantite)';
    $sqlInsertEtape = 'INSERT INTO `gsb`.`lignefraisforfait` (`idVisiteur`, `annee`, `mois`, `idFraisForfait`, `quantite`) VALUES (:idVisiteur, :annee, :mois, "ETP", :quantite)';
    $sqlInsertKilometres = 'INSERT INTO `gsb`.`lignefraisforfait` (`idVisiteur`, `annee`, `mois`, `idFraisForfait`, `quantite`) VALUES (:idVisiteur, :annee, :mois, "KM", :quantite)';


    // On verifie si le fiche de frais existe
    $verification = $connexion->prepare("SELECT COUNT(*) FROM `gsb`.`fichefrais` WHERE annee = :annee AND mois = :mois");
    $verification->execute(array(
        "annee" => $dateFicheFraisA,
        "mois" => $dateFicheFraisM,
    ));

    $nbLignes = $verification->fetchColumn();
    if ($nbLignes == 0) {
        // Creation de la fiche de frais initial
        $reqCreationF = $connexion->prepare($creationFicheFrais);
        $reqCreationF->execute(array(
            "idVisiteur" => $idUtilisateur,
            "annee" => $dateFicheFraisA,
            "mois" => $dateFicheFraisM,
        ));
    } else {
        echo 'Une fiche des frais à déjà été crée pour cette date';
    }


    // Verificaion d'une saisie de date
    if (($dateFicheFraisA || $dateFicheFraisM) == "") {
        echo "Vous devez saisir une date";
    } // Si une date à été saisie mais que tous les champs sont vides
    else if (($repasFicheFrais == "") && ($nuiteeFicheFrais == "") && ($etapeFicheFrais == "") && ($kilometreFicheFrais == "")) {
        echo "Vous devez au moins saisir un frais";
    } // Si tout vas bien on execute les inserts
    else {

        // Affichage verificatif
        echo "ID : " . $idUtilisateur;
        echo "<br>" . "Date : " . $dateFicheFraisA . " " . $dateFicheFraisM;
        echo "<br>" . "Nombre de repas : " . $repasFicheFrais;
        echo "<br>" . "Nombre de nuitee  : " . $nuiteeFicheFrais;
        echo "<br>" . "Nombre d'etape  : " . $etapeFicheFrais;
        echo "<br>" . "Nombre de kilometre  : " . $kilometreFicheFrais;


        // Requete INSERT pour remplir les repas 
        $reqRepas = $connexion->prepare($sqlInsertRepas);
        $reqRepas->execute(array(
            "idVisiteur" => $idUtilisateur,
            "annee" => $dateFicheFraisA,
            "mois" => $dateFicheFraisM,
            "quantite" => $repasFicheFrais
        ));

        // Requete INSERT pour remplir les nuitees
        $reqNuitee = $connexion->prepare($sqlInsertNuitee);
        $reqNuitee->execute(array(
            "idVisiteur" => $idUtilisateur,
            "annee" => $dateFicheFraisA,
            "mois" => $dateFicheFraisM,
            "quantite" => $nuiteeFicheFrais
        ));

        // Requete INSERT pour remplir les etapes
        $reqEtapes = $connexion->prepare($sqlInsertEtape);
        $reqEtapes->execute(array(
            "idVisiteur" => $idUtilisateur,
            "annee" => $dateFicheFraisA,
            "mois" => $dateFicheFraisM,
            "quantite" => $etapeFicheFrais
        ));

        // Requete INSERT pour remplir les kilometres
        $reqKilometres = $connexion->prepare($sqlInsertKilometres);
        $reqKilometres->execute(array(
            "idVisiteur" => $idUtilisateur,
            "annee" => $dateFicheFraisA,
            "mois" => $dateFicheFraisM,
            "quantite" => $kilometreFicheFrais
        ));
    }
}

/////////////////////// PARTIE FRAIS HORS FORFAIT ///////////////////////

// Verification que le bouton submit correspond au formulaire des frais hors forfait
if (isset($_POST['validationHF'])) {
    $date = date('Y-m-d');
    // Recuperation des donnees du formulaire de visiteur.php (fiche frais hors forfait)
    $dateFicheHFraisA = str_replace("-", "", substr($_POST['dateHF'], -7, 4));
    $dateFicheHFraisM = str_replace("-", "", substr($_POST['dateHF'], -2, 2));
    $libelleHorsForfait = $_POST['libelle'];
    $montantHorsForfait = $_POST['montant'];


    $sqlInsertFraisHF = 'INSERT INTO `gsb`.`lignefraishorsforfait` (`id`, `idVisiteur`, `annee`, `mois`, `libelle`, `date`, `montant`) VALUES (NULL, :idVisiteur, :annee, :mois, :libelle, :date, :montant)';

    // Requete INSERT pour remplir les repas
    $reqDateHF = $connexion->prepare($sqlInsertFraisHF);
    if ($reqDateHF->execute(array("idVisiteur" => $idUtilisateur,"annee" => $dateFicheHFraisA, "mois" => $dateFicheHFraisM, "libelle" => $libelleHorsForfait, "date" => $date, "montant" => $montantHorsForfait)) == true) {
        echo '<div id="infoEnvoie">Envoie du formulaire en cours, veuillez patientez...</div>';
        echo $date;
    } else {
        echo '<div id="infoEnvoie2">Une erreur est survenue impossible de traiter votre demande</div>';
    }
}


/////////////////////// MISE A JOUR DU TARIF POUR LA FICHE FRAIS ///////////////////////

// On recupere les montant en fonction des ID (ETP, KM, NUI, REP)
$reqETP = $connexion->query('SELECT montant FROM `fraisforfait` WHERE id="etp"');
$resultat = $reqETP->fetch();
$ETP = $resultat[0];

$reqKM = $connexion->query('SELECT montant FROM `fraisforfait` WHERE id="km"');
$resultat = $reqKM->fetch();
$KM = $resultat[0];

$reqNUI = $connexion->query('SELECT montant FROM `fraisforfait` WHERE id="nui"');
$resultat = $reqNUI->fetch();
$NUI = $resultat[0];

$reqREP = $connexion->query('SELECT montant FROM `fraisforfait` WHERE id="rep"');
$resultat = $reqREP->fetch();
$REP = $resultat[0];

echo "<br><br>Montant ETP " . $ETP;
echo "<br>Montant KM " . $KM;
echo "<br>Montant NUI " . $NUI;
echo "<br>Montant REP " . $REP;

if (isset($_POST['validation'])) {
    // On recupere tout les frais pour un combo mois+annee+id
    $reqFraisETP = $connexion->query("SELECT quantite FROM `lignefraisforfait` WHERE idVisiteur='$idUtilisateur' AND annee='$dateFicheFraisA' AND mois='$dateFicheFraisM' AND idFraisForfait='etp'");
    $ligne = $reqFraisETP->fetch();
    $fraisETP = $ligne[0];

    $reqFraisKM = $connexion->query("SELECT quantite FROM `lignefraisforfait` WHERE idVisiteur='$idUtilisateur' AND annee='$dateFicheFraisA' AND mois='$dateFicheFraisM' AND idFraisForfait='km'");
    $ligne = $reqFraisKM->fetch();
    $fraisKM = $ligne[0];

    $reqFraisNUI = $connexion->query("SELECT quantite FROM `lignefraisforfait` WHERE idVisiteur='$idUtilisateur' AND annee='$dateFicheFraisA' AND mois='$dateFicheFraisM' AND idFraisForfait='nui'");
    $ligne = $reqFraisNUI->fetch();
    $fraisNUI = $ligne[0];

    $reqFraisREP = $connexion->query("SELECT quantite FROM `lignefraisforfait` WHERE idVisiteur='$idUtilisateur' AND annee='$dateFicheFraisA' AND mois='$dateFicheFraisM' AND idFraisForfait='rep'");
    $ligne = $reqFraisREP->fetch();
    $fraisREP = $ligne[0];


    echo "<br>Nombre d'étape " . $fraisETP;
    echo "<br>Nombre de kilometres " . $fraisKM;
    echo "<br>Nombre de nuit " . $fraisNUI;
    echo "<br>Nombre de repas " . $fraisREP;

    // Variable pour calculer le cout total
    $coutTotal = ($fraisETP * $ETP) + ($fraisKM * $KM) + ($fraisNUI * $NUI) + ($fraisREP * $REP) ;
    echo "<br>Montant total " . $coutTotal;

    $connexion->exec("UPDATE `gsb`.`fichefrais` SET `montantValide` = '$coutTotal', `fichefrais`.`dateModif` = '$date'  WHERE `fichefrais`.`idVisiteur` = '$idUtilisateur' AND `fichefrais`.`mois` = '$dateFicheFraisM' AND `fichefrais`.`annee` = '$dateFicheFraisA';");
}

if (isset($_POST['validationHF'])) {

    // On recupere la date HF
    $dateFicheHFraisA = str_replace("-", "", substr($_POST['dateHF'], -7, 4));
    $dateFicheHFraisM = str_replace("-", "", substr($_POST['dateHF'], -2, 2));
    $montantFinalHF = 0;


    // Ecriture de la requête d'extraction en SQL
    $reqSQL= "SELECT montant FROM `lignefraishorsforfait` WHERE idVisiteur='$idUtilisateur' AND annee='$dateFicheHFraisA' AND mois='$dateFicheHFraisM';";

    // Envoi de la requête : on récupère le résultat dans le jeu
    // d'enregistrement $result
    $calculMontantTT = $connexion->query($reqSQL) or die ("Erreur dans la requete SQL '$reqSQL'");

    // Affichage de la requête
    echo "Résultat de la requête : ".$reqSQL."<hr>";

    // Lecture de la première ligne du jeu d'enregistrements et copie des données dans le tableau associatif à une dimension $montantTT
    $montantTT = $calculMontantTT->fetch();

    // On boucle tant que $ligne #faux
    while($montantTT)
    {
        $montant = $montantTT["montant"];
        $montantFinalHF += $montant;
        echo "<br>Montant " .$montant;
        // Lecture de la ligne suivante du jeu d'enregistrementse
        $montantTT = $calculMontantTT->fetch();
    }

    // On va chercher le montant de "montantValid" dans la fiche de frais
    $reqMontantValid = $connexion->query("SELECT montantValide FROM `fichefrais` WHERE idVisiteur='$idUtilisateur' AND annee='$dateFicheHFraisA' AND mois='$dateFicheHFraisM'");
    $ligne = $reqMontantValid->fetch();
    $montantValid = $ligne[0];

    $montantFinalHF = $montantValid +$montantFinalHF;

    $connexion->exec("UPDATE `gsb`.`fichefrais` SET `montantValide` = '$montantFinalHF', `fichefrais`.`dateModif` = '$date'  WHERE `fichefrais`.`idVisiteur` = '$idUtilisateur' AND `fichefrais`.`mois` = '$dateFicheHFraisM' AND `fichefrais`.`annee` = '$dateFicheHFraisA';");

    echo "total :" .$montantFinalHF;

}

$connexion = null; // On ferme la connexion une fois que tout est terminé
header('Refresh:2;url=../contents/visiteur.php');

?>