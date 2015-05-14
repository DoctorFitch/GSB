<?php
// On récupère la date d'aujourd'hui.
$date = getdate();

// permet de regler un probleme de decalage horaire
$heure = date("H");

switch ($heure) {
    case ($heure >= 24): // il fait nuit
        $val = 1;
        $css = "../css/nuit.css";
        break;

    case ($heure <= 6): // il fait nuit
        $val = 1;
        $css = "../css/nuit.css";
        break;

    default: // si il fait pas nuit alors il fais jour par defaut
        $val = 2;
        $css = "../css/jour.css";
        break;
}
?>


<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Espace visiteur</title>
    <link rel="stylesheet" href="<?php echo($css); ?>">
    <link rel="stylesheet" href="../css/normalize.css">
    <link rel="stylesheet" href="../css/login.css">


</head>

<body>

<!-- Affichage du message de bienvenue -->
<div id="resultatCookie">
    <div id="affichage">
        <p>
            <?php
            if ($val == 1) {
                echo "Bonsoir " . $_COOKIE['user'];
                echo '<span id="virgule">,</div>';
            } else {
                echo "Bonjour " . $_COOKIE['user'];
                echo '<span id="virgule">,</div>';
            }
            ?>
        </p>
    </div>
</div>


<!-- Affichage des formulaire de saisie -->
<div id="continue">

    <h1>Espace visiteur</h1>

    <!-- Visteur frais courant -->
    <div id="formulaireVisiteurF">
        <h3>Frais courants</h3>

        <form method="post" action="../scripts/ficheFraisVisiteur.php" name="feuilleFrais">

            <section class="content">

                    <span class="input input--hoshi">
                        <input class="input__field input__field--hoshiDATE" type="month" name="date" id="date"
                               required="true"/>
                        <label class="input__label input__label--hoshi input__label--hoshi-color-1" for="input-4">
                            <span class="input__label-content input__label-content--hoshi">Date d'engagement</span>
                        </label>
                    </span>

                    <span class="input input--hoshi">
                        <input class="input__field input__field--hoshi" type="text" name="repas" id="repas"/>
                        <label class="input__label input__label--hoshi input__label--hoshi-color-1" for="input-4">
                            <span class="input__label-content input__label-content--hoshi">Repas</span>
                        </label>
                    </span>

                    <span class="input input--hoshi">
                        <input class="input__field input__field--hoshi" type="text" name="nuitee" id="nuitee"/>
                        <label class="input__label input__label--hoshi input__label--hoshi-color-1" for="input-4">
                            <span class="input__label-content input__label-content--hoshi">Nuitée(s)</span>
                        </label>
                    </span>

                    <span class="input input--hoshi">
                        <input class="input__field input__field--hoshi" type="text" name="etape" id="etape"/>
                        <label class="input__label input__label--hoshi input__label--hoshi-color-1" for="input-4">
                            <span class="input__label-content input__label-content--hoshi">Etape(s)</span>
                        </label>
                    </span>

                    <span class="input input--hoshi">
                        <input class="input__field input__field--hoshi" type="text" name="km" id="km"/>
                        <label class="input__label input__label--hoshi input__label--hoshi-color-1" for="input-4">
                            <span class="input__label-content input__label-content--hoshi">Kilomètres</span>
                        </label>
                    </span>

            </section>

            <!-- Bouton -->
            <input class="cssButton" type="submit" name="validation" value="Valider">
        </form>
    </div>
    <!-- Fin div id="formulaireVisiteurF" -->

    <!-- Visiteur frais hors forfait -->
    <div id="formulaireVisiteurHF">
        <h3>Frais hors-forfait</h3>

        <form method="post" action="../scripts/ficheFraisVisiteur.php" name="feuilleHorsFrais">

            <section class="content">

                    <span class="input input--hoshi">
                        <input class="input__field input__field--hoshiDATE" type="month" name="dateHF" id="dateHF"/>
                        <label class="input__label input__label--hoshi input__label--hoshi-color-1" for="input-4">
                            <span class="input__label-content input__label-content--hoshi">Date</span>
                        </label>
                    </span>

                    <span class="input input--hoshi">
                        <input class="input__field input__field--hoshi" type="text" name="libelle" id="libelle"/>
                        <label class="input__label input__label--hoshi input__label--hoshi-color-1" for="input-4">
                            <span class="input__label-content input__label-content--hoshi">Nom</span>
                        </label>
                    </span>

                    <span class="input input--hoshi">
                        <input class="input__field input__field--hoshi" type="text" name="montant" id="montant"/>
                        <label class="input__label input__label--hoshi input__label--hoshi-color-1" for="input-4">
                            <span class="input__label-content input__label-content--hoshi">Montant</span>
                        </label>
                    </span>

            </section>

            <!-- Bouton -->
            <input id="test" class="cssButton" type="submit" name="validationHF" value="Valider">
        </form>
    </div>
    <!-- Fin div id="formulaireVisiteurHF" -->

</div>
<!-- Fin div id="continue" -->


<!-- Consultation des fiches de frais -->
<div id="continueplus">

    <!-- Titre consultation -->
    <h1>Consultation fiche de frais</h1>

    <!-- Bouton qui permet d'afficher ou non le tableau recapitulatif -->
    <button id="afficheT">Faire apparaître tableau</button>

    <!-- Tableau -->
            <span id="tableau">
                <?php

                // On invoque le script de connexion
                include("../scripts/connect.php");


                // Creation et envoi de la requete
                $selectionTableau = "SELECT annee, mois, nbJustificatifs, montantValide, idEtat FROM `gsb`.`fichefrais` ORDER BY mois ASC";

                // On envoie la requete
                $result = $connexion->query($selectionTableau) or die ("Erreur dans la requete SQL '$reqSQL'");

                // On recupere le resultat de la requete dans un tableau associatif
                $ligne = $result->fetch();

                // On initialise un compteur afin de mieux se reperer dans le tableau
                $numero = 1;

                // Verification de présence de données
                if ($ligne == "") { // Si pas de données on averti l'utilisateur
                    echo "Il n'y a aucune fiche de frais renseignés";
                } else { // Sinon on execute la requete

                    // Creation du tableau
                    echo '<table border="1" cellpadding="0" cellspacing="0">';
                    // Creation de la premiere ligne
                    echo '<tr>';
                    // Creation des colonnes
                    echo '<th>Numero</th>';
                    echo '<th>Date</th>';
                    echo '<th>Montant</th>';
                    echo '<th>Modifier</th>';
                    echo '<th>Etat</th>';
                    // Fin de la premiere ligne
                    echo '</tr>';

                    // Recuperation des resultats
                    while ($ligne) {

                        // On recupere les valeurs contenu dans le tableau suivant les champs
                        $annee = $ligne["annee"];
                        $mois = $ligne["mois"];
                        $nbJ = $ligne["nbJustificatifs"];
                        $montantV = $ligne["montantValide"];
                        $etat = $ligne["idEtat"];


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

                        // Lisibilité des status
                        switch ($etat) {
                            case 'CL':
                                $etatAfterS = 'Saisie clôturée';
                                break;
                            case 'CR':
                                $etatAfterS = 'Saisie en cours';
                                break;
                            case 'RB':
                                $etatAfterS = 'Remboursée';
                                break;
                            case 'VA':
                                $etatAfterS = 'Validée et mise en paiement';
                                break;

                        }

                        // On affiche le resultat
                        // Creation de la premiere ligne
                        echo '<tr>';
                        // Creation des colonnes
                        echo '<td>' . $numero . '</td>';
                        echo '<td>' . $dateFinale . '</td>';
                        echo '<td>' . $montantV . '</td>';
                        echo '<td><a href="modification.php" class="target"">Modifier cette fiche</td>';
                        echo '<td>' . $etatAfterS . '</td>';
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
                ?>

            </span> <!-- On ferme la div id="tableau" -->
</div>
<!-- Fin div id="continueplus" -->


<!-- Appel des scripts javascript pour les animations etc -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="../scripts/magnetic/magneticScroll-1.0.js"></script>
<script src="../js/classie.js"></script>
<script src="../scripts/script.js"></script>
<script>
    $(document).ready(function(){
        $('.target').click(function (event){
            var url = $(this).attr("href");
            window.open(url);
            event.preventDefault();
        });
    });﻿
</script>
<script>
    function pop() {
        window.open('pop','height=x,width=y,top=z,left=t,resible=no');
    }
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
<!-- Fin JavaScripts -->

</body>
</html>