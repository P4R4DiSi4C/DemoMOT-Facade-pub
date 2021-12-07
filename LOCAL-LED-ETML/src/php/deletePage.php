<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="msapplication-tap-highlight" content="no">
    <title>LEDs-Supression</title>
    <!-- CSS  AND JAVASCRIPT-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="../../ressources/css/materialize.css" type="text/css" rel="stylesheet" media="screen" />
    <link href="../../ressources/css/style.css" type="text/css" rel="stylesheet" media="screen" />
    <link href="../../ressources/css/spectrum.css" type="text/css" rel="stylesheet" media="screen" />
    <link href="../../ressources/Images/favicon.ico" rel="icon" type="image/x-icon" />
    <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
    <script type="text/javascript" src="../js/materialize.js"></script>
    <script type="text/javascript" src="../js/led.js"></script>
    <script type="text/javascript" src="../js/spectrum.js"></script>

    <!--[if lte IE 8]>
    <script src="../js/selectivizr.js"></script>
    <![endif]-->
    <!--[if lt IE 9]>
    <script src="../js/html5shiv.js"></script>
    <script src="../js/respond.js"></script>
    <![endif]-->

    <!--VERIFICATION SI JAVASCRIPT EST BIEN ACTIVER-->
    <noscript>
        <style type="text/css">
            .pagecontainer {display:none;}
        </style>
        <div class="noscriptmsg">
            <h1 class="red-text">CE SITE NÉCESSITE JAVASCRIPT POUR FONCTIONNER !</h1>
        </div>
    </noscript>
</head>
<body>
<?php
    //Include the class
    include "ledClass.php";

    //Instances the class
    $class = new ledClass();

    //Include the navbar
    include "navbar.php";
?>
<main>
    <div class="container">
        <div class="card-panel white-text red darken-2"><h5>Voulez-vous vraiment supprimer le/s textes sélectionnés ?</h5></div>
        <div class="center-align col s12 m6">
            <?php
            echo "
                    <a href='archiveTextFunction.php' class='waves-effect waves-light btn red'>
                        Supprimer
                    </a>
                    <a href='messages.php' class='waves-effect waves-light btn green'>
                        Annuler
                    </a>";
            ?>
        </div>
        <br>
    </div>
</main>
<?php
    //Include the footer
    include "footer.php";
?>

<script>
    //Code that's run when the page is ready
    $(document).ready(function() {
        $(".button-collapse").sideNav();
        $('select').material_select();
    });
</script>
</body>
</html>