<?php
include("../scripts/connect.php");
include("../scripts/checkUser.php");


// obtention de l'id de l'utilisateur
$sqlIdentifiant = "SELECT id
                    FROM Visiteur
                    WHERE login = 'dandre'";

$resultIdentifiant = $connexion->query($sqlIdentifiant) or die ("Erreur morray");
$ligne = $resultIdentifiant->fetch();

$idUtilisateur = $ligne["id"];
$resultIdentifiant->closeCursor();


$fraisETP = $_POST['etape'];
$fraisKM = $_POST['km'];
$fraisNUI = $_POST['nuitee'];
$fraisREP = $_POST['repas'];



/////////////////// UPDATE DES QUANTITÉS POUR LA FICHE FRAIS FORFAIT ////////////////////


$connexion->exec("UPDATE `gsb`.`lignefraisforfait` SET `quantite` = 'fraisETP' WHERE `lignefraisforfait`.`idVisiteur` = '$idUtilisateur' AND `lignefraisforfait`.`mois` ='05' AND `lignefraisforfait`.`idFraisForfait` = 'ETP'");
$connexion->exec("UPDATE `gsb`.`lignefraisforfait` SET `quantite` = '$fraisKM' WHERE `lignefraisforfait`.`idVisiteur` = '$idUtilisateur' AND `lignefraisforfait`.`mois` = '$dateMonth' AND `lignefraisforfait`.`idFraisForfait` = 'KM'");
$connexion->exec("UPDATE `gsb`.`lignefraisforfait` SET `quantite` = '$fraisNUI' WHERE `lignefraisforfait`.`idVisiteur` = '$idUtilisateur' AND `lignefraisforfait`.`mois` = '$dateMonth' AND `lignefraisforfait`.`idFraisForfait` = 'NUI'");
$connexion->exec("UPDATE `gsb`.`lignefraisforfait` SET `quantite` = '$fraisREP' WHERE `lignefraisforfait`.`idVisiteur` = '$idUtilisateur' AND `lignefraisforfait`.`mois` = '$dateMonth' AND `lignefraisforfait`.`idFraisForfait` = 'REP'");

/////////////////////// MISE A JOUR DU TARIF POUR LA FICHE FRAIS ///////////////////////
$date = date('Y-m-d');
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

// On recupere les montant fraichement ressaisie ou non dans le formulaire de modification



// On recupere tout les frais pour un combo mois+annee+id
$reqFraisETP = $connexion->query("SELECT quantite FROM `lignefraisforfait` WHERE idVisiteur='$idUtilisateur' AND annee='$dateYear' AND mois='$dateMonth' AND idFraisForfait='etp'");
$ligne = $reqFraisETP->fetch();
$ETP = $ligne[0];

$reqFraisKM = $connexion->query("SELECT quantite FROM `lignefraisforfait` WHERE idVisiteur='$idUtilisateur' AND annee='$dateYear' AND mois='$dateMonth' AND idFraisForfait='km'");
$ligne = $reqFraisKM->fetch();
$KM = $ligne[0];

$reqFraisNUI = $connexion->query("SELECT quantite FROM `lignefraisforfait` WHERE idVisiteur='$idUtilisateur' AND annee='$dateYear' AND mois='$dateMonth' AND idFraisForfait='nui'");
$ligne = $reqFraisNUI->fetch();
$NUI = $ligne[0];

$reqFraisREP = $connexion->query("SELECT quantite FROM `lignefraisforfait` WHERE idVisiteur='$idUtilisateur' AND annee='$dateYear' AND mois='$dateMonth' AND idFraisForfait='rep'");
$ligne = $reqFraisREP->fetch();
$REP = $ligne[0];


echo "<br>Nombre d'étape " . $fraisETP;
echo "<br>Nombre de kilometres " . $fraisKM;
echo "<br>Nombre de nuit " . $fraisNUI;
echo "<br>Nombre de repas " . $fraisREP;

// Variable pour calculer le cout total
$coutTotal = (($fraisETP * $ETP) + ($fraisKM * $KM) + ($fraisNUI * $NUI) + ($fraisREP * $REP));
echo "<br>Montant total " . $coutTotal;

$connexion->exec("UPDATE `gsb`.`fichefrais` SET `montantValide` = '$coutTotal', `fichefrais`.`dateModif` = '$date'  WHERE `fichefrais`.`idVisiteur` = '$idUtilisateur' AND `fichefrais`.`mois` = '$dateMonth' AND `fichefrais`.`annee` = '$dateYear';");


?>