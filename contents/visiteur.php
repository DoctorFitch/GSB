<?php

// On récupère la date d'aujourd'hui.
$date = getdate();

// permet de regler un probleme de decalage horaire
$heure = date("H"); 

switch ($heure){

    case ($heure >= 22): // il fait nuit
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
        
        
        <div id="resultatCookie">
            <div id="affichage">
            <p>
                <?php

                    if($val == 1){
                        echo "Bonsoir ". $_COOKIE['user'];
                        echo '<span id="virgule">,</div>';
                    }
                    else {
                        echo "Bonjour ". $_COOKIE['user'];
                        echo '<span id="virgule">,</div>';
                    }

                ?>
            </p>
            </div>
        </div>

        
        
        
        
        
        
        

            
        <div id="continue">.selector
        <h1>Espace visiteur</h1>
            <form method="post" action="../scripts/ficheFraisVisiteur.php" name="feuilleFrais">
                <h3>Saisie des frais</h3>
                
                <!-- Saisie de la période d'engagement  -->
                <label for="date">Date d'engagement</label><input type="date" name="date"><br/><br/>                
                
                <!-- Identifiant/Mot de passe -->
                <label for="repas">Repas</label><input type="text" name="repas" id="repas" size="25" maxlength="25"><br/><br/>
                <label for="nuitee">Nuitée(s)</label><input type="text" name="nuitee" id="nuitee" size="25" maxlength="25"><br/><br/>
                <label for="etape">Etape(s)</label><input type="text" name="etape" id="etape" size="25" maxlength="25"><br/><br/>
                <label for="km">Kilomètres</label><input type="text" name="km" id="km" size="25" maxlength="25"><br/><br/>
                
                <!-- Hors forfait -->
                <h3>Hors forfait</h3>
                <label for="dateHF"> Date du hors forfait : </label><input type="date" name="dateHF"><br/><br/>      
                <label for="libelle"> Libellé : </label><input type="text" name="libelle"><br/><br/>  
                <label for="montant"> Montant : </label><input type="text" name="montant">
                <input class="cssButton" type="submit" name="validationHorsForfait" value="+"><br/><br/> 
                
                <!-- Bouton d'envoie -->
                <input class="cssButton" type="submit" name="validation" value="Effacer">
                <input class="cssButton" type="submit" name="validation" value="Valider">
                
            </form>
        </div>
            

        
        
        
        
        
        
        
        
    

        <div id="continueplus">
         <h1>Consultation fiche de frais</h1>
            
            <?php
            
                include("../scripts/connect.php");
        
                
                // Création du tableau
                // Appel du script de connexion


                // Ecriture de la requête d'extraction en SQL
                $reqSQL= "SELECT nom, prenom FROM visiteur ORDER BY nom ASC;";

                // Envoi de la requête : on récupère le résultat dans le jeu
                // d'enregistrement $result
                $resultat= $connexion->query($reqSQL) or die ("Erreur dans la requete SQL '$reqSQL'");

                // Affichage de la requête
                echo "Résultat de la requête : ".$reqSQL."<hr>";
                

                // Lecture de la première ligne du jeu d'enregistrements et copie
                // des données dans le tableau associatif à une dimension $ligEleve
                $ligne= $resultat->fetch();
                echo '<select size=1 name="cat">'."\n"; 
                echo '<option value="-1">Choisissez le visiteur<option>'."\n";
                
                // On boucle tant que $ligne # faux
                while($ligne != false)
                {
                    // On stocke le contenu des cellules du
                    // tableau associatif dans des variables
                    $nom=$ligne["nom"];
                    $prenom=$ligne["prenom"];
                    
                    // on affiche les informations
                    echo '<option value="$nom">'.$nom." ".$prenom; 
                    echo '</option>'."\n"; 
                    
                    // Lecture de la ligne suivante du jeu d'enregistrementse
                    $ligne= $resultat->fetch();
                }
                    
                echo '</select>'."\n";




            ?>
            
        </div>

        
        
        
        
        
        
        
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
        <script src="../scripts/magnetic/magneticScroll-1.0.js"></script>
        <script src="../scripts/script.js"></script>
        
    
    
    </body>
</html>