
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Espace modification fiche</title>
    <link rel="stylesheet" href="">
    <link rel="stylesheet" href="../css/modification.css">
    <link rel="stylesheet" href="../css/normalize.css">
    <link rel="stylesheet" href="../css/login.css">


</head>
<body>

<div id="titre">
<h1>Modification frais & hors forfait</h1>
</div>

<?php

session_set_cookie_params(3600, "/");
session_start();

// On invoquel les scripts precedemment crée
include("../scripts/connect.php");
include("../scripts/checkUser.php");

// On obtient les dates actuelles afin de connaitre la date du jour mois/annee
$dateActuelleAnnee = date('Y');
$dateActuelleAnneeMinumum = $dateActuelleAnnee - 1;
$dateActuelleMois = date('m');

// Etablissement d'une variable permettant la comptabilité des cookies sous wamp
$domain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false;

$login = $_COOKIE['user'];

// obtention de l'id de l'utilisateur
$sqlIdentifiant = "SELECT id FROM Visiteur WHERE login = '$login'";

$resultIdentifiant = $connexion->query($sqlIdentifiant) or die ("Erreur morray");
$ligne = $resultIdentifiant->fetch();

$idUtilisateur = $ligne["id"];
$resultIdentifiant->closeCursor();

///////////////////////////////////////////////////////////////////////
//////////////////// CREATION DE LA LISTE DEROULANTE //////////////////
///////////////////////////////////////////////////////////////////////

echo '<div id="listeDeroulante">';
// Ecriture de la requête d'extraction en SQL
$reqSQL = "SELECT annee, mois FROM `gsb`.`fichefrais` ORDER BY mois ASC";

// Envoi de la requête : on récupère le résultat dans le jeu
// d'enregistrement $result
$resultat = $connexion->query($reqSQL) or die ("Erreur dans la requete SQL '$reqSQL'");

// Lecture de la première ligne du jeu d'enregistrements et copie des données dans le tableau associatif à une dimension $ligne
$ligne = $resultat->fetch();

echo '<form method="post" action="../contents/modification.php" name="feuilleFrais">';

echo '<select name="dateFiche">';
echo '<option selected>Choisissez l\'année</option>' . "\n";


// On boucle tant que $ligne #faux
while ($ligne != false) {
    // On stocke le contenu des cellules du tableau associatif dans des variables
    $annee = $ligne["annee"];
    $mois = $ligne["mois"];

    // lisibilité de la date
    switch ($mois) {
        case 1:
            $dateFinale = 'Janvier' . " " . $annee;
            break;

        case 2:
            $dateFinale = 'Fevrier' . " " . $annee;
            break;

        case 3:
            $dateFinale = 'Mars' . " " . $annee;
            break;

        case 4:
            $dateFinale = 'Avril' . " " . $annee;
            break;

        case 5:
            $dateFinale = 'Mai' . " " . $annee;
            break;

        case 6:
            $dateFinale = 'Juin' . " " . $annee;
            break;

        case 7:
            $dateFinale = 'Juillet' . " " . $annee;
            break;

        case 8:
            $dateFinale = 'Aout' . " " . $annee;
            break;

        case 9:
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
    $datePourFormulaire = $annee . " " . $mois;
    // on affiche les informations
    echo "<option value='$datePourFormulaire'>" . $dateFinale;
    echo '</option>' . "\n";

    // Lecture de la ligne suivante du jeu d'enregistrement
    $ligne = $resultat->fetch();
        }


echo '</select>' . "\n";
echo '<input class="cssButton1" type="submit" name="validation" value="Valider">';
echo '</form>';

echo '</div>';


$dateYear = str_replace(" ", "", substr(@$_POST["dateFiche"], -7, 4));
$dateMonth = str_replace(" ", "", substr(@$_POST["dateFiche"], -2, 2));;

@setcookie('dateMonth', $dateMonth, 0, '/', $domain, false);
@setcookie('dateYear', $dateYear, 0, '/', $domain, false);

// Partie de recuperation des valeurs

// On va chercher le montant de "montantValid" dans la fiche de frais
$reqNombreRepas = $connexion->query("SELECT quantite FROM `lignefraisforfait` WHERE idVisiteur='$idUtilisateur' AND annee='$dateYear' AND mois='$dateMonth' AND idfraisforfait = 'REP'");
$ligne1 = $reqNombreRepas->fetch();
$repasTab = $ligne1[0];

// On va chercher le montant de "montantValid" dans la fiche de frais
$reqNombreNuitee = $connexion->query("SELECT quantite FROM `lignefraisforfait` WHERE idVisiteur='$idUtilisateur' AND annee='$dateYear' AND mois='$dateMonth' AND idfraisforfait = 'NUI'");
$ligne2 = $reqNombreNuitee->fetch();
$nuiteeTab = $ligne2[0];

// On va chercher le montant de "montantValid" dans la fiche de frais
$reqNombreEtape = $connexion->query("SELECT quantite FROM `lignefraisforfait` WHERE idVisiteur='$idUtilisateur' AND annee='$dateYear' AND mois='$dateMonth' AND idfraisforfait = 'ETP'");
$ligne3 = $reqNombreEtape->fetch();
$etapeTab = $ligne3[0];

// On va chercher le montant de "montantValid" dans la fiche de frais
$reqNombreKm = $connexion->query("SELECT quantite FROM `lignefraisforfait` WHERE idVisiteur='$idUtilisateur' AND annee='$dateYear' AND mois='$dateMonth' AND idfraisforfait = 'KM'");
$ligne4 = $reqNombreKm->fetch();
$kmTab = $ligne4[0];

///////////////////////////////////////////////////////////////////////
/////////// AFFICHAGE DU TABLEAU DE FICHES HORS FRAIS /////////////////
///////////////////////////////////////////////////////////////////////

echo '<div id="organisationModifDroite">';
echo '<h3>Frais hors-forfait</h3>';
// Creation et envoi de la requete
$selectionTableau = "SELECT id, libelle, montant FROM `gsb`.`lignefraishorsforfait` WHERE mois = '$dateMonth' AND annee = '$dateYear' ORDER BY mois ASC";

// On envoie la requete
$result = $connexion->query($selectionTableau) or die ("Erreur dans la requete SQL '$reqSQL'");

// On recupere le resultat de la requete dans un tableau associatif
$ligne = $result->fetch();

// On initialise un compteur afin de mieux se reperer dans le tableau
$numero = 1;

// Verification de présence de données
if ($ligne == "") { // Si pas de données on averti l'utilisateur

} else { // Sinon on execute la requete

    // Creation du tableau
    echo '<table border="2" cellpadding="10" cellspacing="0">';
    // Creation de la premiere ligne
    echo '<tr>';
    // Creation des colonnes
    echo '<th>Libelle</th>';
    echo '<th>Montant</th>';
    // Fin de la premiere ligne
    echo '</tr>';

    // Recuperation des resultats
    while ($ligne) {

        // On recupere les valeurs contenu dans le tableau suivant les champs
        $libelle = $ligne["libelle"];
        $montant = $ligne["montant"];
        $id = $ligne["id"];

        // lisibilité de la date
        switch ($dateMonth) {
            case 1:
                $dateFinale = 'Janvier' . " " . $annee;
                break;

            case 2:
                $dateFinale = 'Fevrier' . " " . $annee;
                break;

            case 3:
                $dateFinale = 'Mars' . " " . $annee;
                break;

            case 4:
                $dateFinale = 'Avril' . " " . $annee;
                break;

            case 5:
                $dateFinale = 'Mai' . " " . $annee;
                break;

            case 6:
                $dateFinale = 'Juin' . " " . $annee;
                break;

            case 7:
                $dateFinale = 'Juillet' . " " . $annee;
                break;

            case 8:
                $dateFinale = 'Aout' . " " . $annee;
                break;

            case 9:
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

        // On affiche le resultat
        // Creation de la premiere ligne
        echo '<tr>';
        // Creation des colonnes
        echo '<td>' . $libelle . '</td>';
        echo '<td>' . $montant . '</td>';

        echo '<td><a href="modification.php?action=' . $id . '">x</td>';
        // Fin de la creation de la premiere ligne
        echo '</tr>';

        // On passe a la ligne suivante du tableau associatif
        $ligne = $result->fetch();

        // On incremente le compteur de 1 afin d'établir une liste
        $numero++;
    }

    // On ferme le tableau
    echo '</table>';
    }
echo '</div>';

/* suppression par l'url (methode GET) */
if (isset($_GET['action']) && $_GET['action'] != "") {
    $idSuppression = $_GET['action'];
    $connexion->exec("DELETE FROM `gsb`.`lignefraishorsforfait` WHERE `lignefraishorsforfait`.`id` = '$idSuppression'");
}


if (isset($_POST['validation2'])) {


    @$CookieDateMonth = $_COOKIE['dateMonth'];
    @$CookieDateYear = $_COOKIE['dateYear'];
    $fraisETP = $_POST['etape'];
    $fraisKM = $_POST['km'];
    $fraisNUI = $_POST['nuitee'];
    $fraisREP = $_POST['repas'];

/////////////////////////////////////////////////////////////////////////////////////////
/////////////////// UPDATE DES QUANTITÉS POUR LA FICHE FRAIS FORFAIT ////////////////////
/////////////////////////////////////////////////////////////////////////////////////////


    if ($CookieDateMonth < $dateActuelleMois || $CookieDateMonth > $dateActuelleMois) {
        echo '<div id="messageE">Impossible de modifier cette fiche car elle n\'appartient pas au mois en cours</div>';
    } else {


        $connexion->exec("UPDATE `gsb`.`lignefraisforfait` SET `quantite` = '$fraisETP' WHERE `lignefraisforfait`.`idVisiteur` = '$idUtilisateur' AND `lignefraisforfait`.`mois` ='$CookieDateMonth' AND `lignefraisforfait`.`idFraisForfait` = 'ETP'");
        $connexion->exec("UPDATE `gsb`.`lignefraisforfait` SET `quantite` = '$fraisKM' WHERE `lignefraisforfait`.`idVisiteur` = '$idUtilisateur' AND `lignefraisforfait`.`mois` = '$CookieDateMonth' AND `lignefraisforfait`.`idFraisForfait` = 'KM'");
        $connexion->exec("UPDATE `gsb`.`lignefraisforfait` SET `quantite` = '$fraisNUI' WHERE `lignefraisforfait`.`idVisiteur` = '$idUtilisateur' AND `lignefraisforfait`.`mois` = '$CookieDateMonth' AND `lignefraisforfait`.`idFraisForfait` = 'NUI'");
        $connexion->exec("UPDATE `gsb`.`lignefraisforfait` SET `quantite` = '$fraisREP' WHERE `lignefraisforfait`.`idVisiteur` = '$idUtilisateur' AND `lignefraisforfait`.`mois` = '$CookieDateMonth' AND `lignefraisforfait`.`idFraisForfait` = 'REP'");


/////////////////////////////////////////////////////////////////////////////////////////
/////////////////////// MISE A JOUR DU TARIF POUR LA FICHE FRAIS ////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////

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


        echo "<br>Nombre d'étape " . $fraisETP;
        echo "<br>Nombre de kilometres " . $fraisKM;
        echo "<br>Nombre de nuit " . $fraisNUI;
        echo "<br>Nombre de repas " . $fraisREP;

// Variable pour calculer le cout total
        $coutTotal = (($fraisETP * $ETP) + ($fraisKM * $KM) + ($fraisNUI * $NUI) + ($fraisREP * $REP));
        echo "<br>Montant total " . $coutTotal;

        $connexion->exec("UPDATE `gsb`.`fichefrais` SET `montantValide` = '$coutTotal', `fichefrais`.`dateModif` = '$date'  WHERE `fichefrais`.`idVisiteur` = '$idUtilisateur' AND `fichefrais`.`mois` = '$CookieDateMonth' AND `fichefrais`.`annee` = '$CookieDateYear';");
    }
}
?>

<div id="organisationModifGauche">
<h3>Frais forfait</h3>
<!-- Formulaire de modification -->
<form method="post" action="" name="feuilleFrais">
    <section class="content">
                    <span class="input input--hoshi">
                        <input class="input__field input__field--hoshi" type="number" min="0" name="repas" id="repas"
                               value="<?php echo "$repasTab" ?>"/>
                        <label class="input__label input__label--hoshi input__label--hoshi-color-1" for="input-4">
                            <span class="input__label-content input__label-content--hoshi">Repas</span>
                        </label>
                    </span>

                    <span class="input input--hoshi">
                        <input class="input__field input__field--hoshi" type="number" min="0" name="nuitee" id="nuitee"
                               value="<?php echo "$nuiteeTab" ?>"/>
                        <label class="input__label input__label--hoshi input__label--hoshi-color-1" for="input-4">
                            <span class="input__label-content input__label-content--hoshi">Nuitée(s)</span>
                        </label>
                    </span>

                    <span class="input input--hoshi">
                        <input class="input__field input__field--hoshi" type="number" min="0" name="etape" id="etape"
                               value="<?php echo "$etapeTab" ?>"/>
                        <label class="input__label input__label--hoshi input__label--hoshi-color-1" for="input-4">
                            <span class="input__label-content input__label-content--hoshi">Etape(s)</span>
                        </label>
                    </span>

                    <span class="input input--hoshi">
                        <input class="input__field input__field--hoshi" type="number" min="0" name="km" id="km"
                               value="<?php echo "$kmTab" ?>"/>
                        <label class="input__label input__label--hoshi input__label--hoshi-color-1" for="input-4">
                            <span class="input__label-content input__label-content--hoshi">Kilomètres</span>
                        </label>
                    </span>
        <!-- Bouton -->
        <input class="cssButton" type="submit" name="validation2" value="Valider">
    </section>
</form>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="../scripts/magnetic/magneticScroll-1.0.js"></script>
<script src="../js/classie.js"></script>
<script src="../scripts/script.js"></script>
<script>
    $(document).ready(function () {
        $('.target').click(function (event) {
            var url = $(this).attr("href");
            window.open(url);
            event.preventDefault();
        });
    });
</script>
<script>

    (function () {
        // trim polyfill : https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/String/Trim
        if (!String.prototype.trim) {
            (function () {
                // Make sure we trim BOM and NBSP
                var rtrim = /^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g;
                String.prototype.trim = function () {
                    return this.replace(rtrim, '');
                };
            })();
        }

        [].slice.call(document.querySelectorAll('input.input__field')).forEach(function (inputEl) {
            // in case the input is already filled..
            if (inputEl.value.trim() !== '') {
                classie.add(inputEl.parentNode, 'input--filled');
            }

            // events:
            inputEl.addEventListener('focus', onInputFocus);
            inputEl.addEventListener('blur', onInputBlur);
        });

        function onInputFocus(ev) {
            classie.add(ev.target.parentNode, 'input--filled');
        }

        function onInputBlur(ev) {
            if (ev.target.value.trim() === '') {
                classie.remove(ev.target.parentNode, 'input--filled');
            }
        }
    })();

</script>
<script type="text/javascript">
    function afficher($a) {
        alert($a);
    }
</script>
<!-- Fin JavaScripts -->
</body>
</html>