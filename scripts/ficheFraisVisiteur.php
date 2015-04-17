<?php

include ("connect.php");
include ("checkUser.php");

$dateFicheFrais = $_POST['date'];
$dateFicheHorsFrais = $_POST['dateHF'];
$repasFicheFrais = $_POST['repas'];
$nuiteeFicheFrais = $_POST['nuitee'];
$etapeFicheFrais = $_POST['etape'];
$kilometreFicheFrais = $_POST['km'];


// obtention de l'id de l'utilisateur
$sqlIdentifiant = "SELECT id 
                   FROM Visiteur 
                   WHERE login = 'dandre' AND mdp = 'oppg5';";

$resultIdentifiant = $connexion->query($sqlIdentifiant) or die ("Erreur morray");
$ligne = $resultIdentifiant->fetch();

$idUtilisateur = $ligne["id"];
$resultIdentifiant->closeCursor();
$connexion = null;
// fin obtention de l'id utilisateur

$date = str_replace("-", "", substr($dateFicheFrais,-10, 7));

$sqlInsertRepas = "INSERT INTO ligneFraisForfait VALUES ('$idUtilisateur', '$date', 'REP', '$repasFicheFrais');";

echo "<br>"."Nombre de repas : " .$repasFicheFrais;
echo "<br>"."Nombre de nuitee  : " .$nuiteeFicheFrais;
echo "<br>"."Nombre d'etape  : " .$etapeFicheFrais;
echo "<br>"."Nombre de kilometre  : " .$kilometreFicheFrais;



?>