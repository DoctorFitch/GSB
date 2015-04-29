<?php
    // On récupère la date d'aujourd'hui.
    $date = getdate();

    // permet de regler un probleme de decalage horaire
    $heure = date("H"); 

    switch ($heure)
    {
        case ($heure >= 23): // il fait nuit
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
        <link rel="stylesheet" href="<?php echo ($css); ?>">
    </head>
    
    <body>
        
        <!-- Affichage du message de bienvenue -->
        <div id="resultatCookie">
            <div id="affichage">
                <p>
                    <?php
                        if($val == 1)
                        {
                            echo "Bonsoir ". $_COOKIE['user'];
                            echo '<span id="virgule">,</div>';
                        }
                        else 
                        {
                            echo "Bonjour ". $_COOKIE['user'];
                            echo '<span id="virgule">,</div>';
                        }
                    ?>
                </p>
            </div>
        </div>

        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        <!-- Affichage des formulaire de saisie -->
        <div id="continue">
        <h1>Espace visiteur</h1>
            <form method="post" action="../scripts/ficheFraisVisiteur.php" name="feuilleFrais">
                <!-- Forfait classique -->
                <h3>Saisie des frais</h3>
                
                <!-- Saisie de la période d'engagement  -->
                <label for="date">Date d'engagement</label><input type="date" name="date"><br/><br/>                
                
                <!-- Identifiant/Mot de passe -->
                <label for="repas">Repas</label><input type="text" name="repas" id="repas" size="25" maxlength="25"><br/><br/>
                <label for="nuitee">Nuitée(s)</label><input type="text" name="nuitee" id="nuitee" size="25" maxlength="25"><br/><br/>
                <label for="etape">Etape(s)</label><input type="text" name="etape" id="etape" size="25" maxlength="25"><br/><br/>
                <label for="km">Kilomètres</label><input type="text" name="km" id="km" size="25" maxlength="25"><br/><br/>
                
                <!-- Bouton d'envoie -->
                <input class="cssButton" type="reset" name="reset" value="Effacer">
                <input class="cssButton" type="submit" name="validation" value="Valider">
                
            </form>   
            
            <form method="post" action="../scripts/ficheFraisVisiteur.php#box" name="feuilleHorsFrais">
               
                <!-- Hors forfait -->
                <h3>Hors forfait</h3>
                <label for="dateHF"> Date du hors forfait : </label><input type="date" name="dateHF"><br/><br/>      
                <label for="libelle"> Libellé : </label><input type="text" name="libelle"><br/><br/>  
                <label for="montant"> Montant : </label><input type="text" name="montant">
                <input class="cssButton" type="submit" name="validationHorsForfait" value="+"><br/><br/> 
                
                <!-- Bouton d'envoie -->
                <input class="cssButton" type="reset" name="resetHF" value="Effacer">
                <input id="test" class="cssButton" type="submit" name="validationHF" value="Valider">
            </form>  
            
           <span id="loader">Loading...</span>
          
        </div>
             
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
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
                    if($ligne == ""){ // Si pas de données on averti l'utilisateur
                        echo "Il n'y a aucune fiche de frais renseignés";
                    }
                    else{ // Sinon on execute la requete
                    
                    // Creation du tableau
                    echo '<table border="1" cellpadding="0" cellspacing="0">';
                    // Creation de la premiere ligne
                    echo'<tr>';
                    // Creation des colonnes
                    echo'<th>Numero</th>';
                    echo'<th>Date</th>';
                    echo'<th>Montant</th>';
                    echo'<th>Modifier</th>';
                    echo'<th>Etat</th>';
                    // Fin de la premiere ligne
                    echo'</tr>';
                        
                    // Recuperation des resultats
                    while($ligne){
                        
                        // On recupere les valeurs contenu dans le tableau suivant les champs 
                        $annee = $ligne["annee"];
                        $mois = $ligne["mois"];
                        $nbJ = $ligne["nbJustificatifs"];
                        $montantV = $ligne["montantValide"];
                        $etat = $ligne["idEtat"];
                        
                        
                        // lisibilité de la date
                        switch($mois){
                            case 01:
                                $dateFinale = 'Janvier'. " " . $annee;
                                break;
                            
                            case 02:
                                $dateFinale = 'Fevrier'. " " . $annee;
                                break;
                            
                            case 03:
                                $dateFinale = 'Mars'. " " . $annee;
                                break;
                            
                            case 04:
                                $dateFinale = 'Avril'. " " . $annee;
                                break;
                            
                            case 05:
                                $dateFinale = 'Mai'. " " . $annee;
                                break;
                            
                            case 06:
                                $dateFinale = 'Juin'. " " . $annee;
                                break;
                            
                            case 07:
                                $dateFinale = 'Juillet'. " " . $annee;
                                break;
                            
                            case 08:
                                $dateFinale = 'Aout'. " " . $annee;
                                break;
                            
                            case 09:
                                $dateFinale = 'Septembre'. " " . $annee;
                                break;
                            
                            case 10:
                                $dateFinale = 'Octobre'. " " . $annee;
                                break;
                            
                            case 11:
                                $dateFinale = 'Novembre'. " " . $annee;
                                break;
                            
                            case 12:
                                $dateFinale = 'Decembre'. " " . $annee;
                                break; 
                        }
                        
                        // Lisibilité des status
                        switch($etat){
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
                        echo '<td>'.$numero.'</td>';
                        echo '<td>'.$dateFinale.'</td>';
                        echo '<td>'.$montantV.'</td>';
                        echo '<td><a href="modification.php">Modifier cette fiche</td>';
                        echo '<td>'.$etatAfterS.'</td>';
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
        </div> <!-- Fin div id="continueplus" -->

        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        <!-- Appel des scripts javascript pour les animations etc -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
        <script src="../scripts/magnetic/magneticScroll-1.0.js"></script>
        <script src="../scripts/script.js"></script>
        
    </body>
</html>