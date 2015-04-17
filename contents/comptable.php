<?php

// On récupère la date d'aujourd'hui.
$date = getdate();

// permet de regler un probleme de decalage horaire
$heure = date("H") + 2; 

switch ($heure){

    case ($heure >= 20): // il fait nuit
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

        <div id="continue">
            <h1>Espace comptabilité</h1>
            
        </div>


        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
        <script src="../scripts/script.js"></script>
    </body>
</html>