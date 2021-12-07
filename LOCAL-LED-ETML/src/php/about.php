<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0" />
    <title>LEDs-À propos</title>
    <!-- CSS AND JAVASCRIPT -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="../../ressources/css/materialize.css" type="text/css" rel="stylesheet" media="screen" />
    <link href="../../ressources/css/style.css" type="text/css" rel="stylesheet" media="screen" />
    <link href="../../ressources/Images/favicon.ico" rel="icon" type="image/x-icon" />
    <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
    <script type="text/javascript" src="../js/materialize.js"></script>

    <!--[if lte IE 8]>
    <script src="../js/selectivizr.js"></script>
    <![endif]-->
    <!--[if lt IE 9]>
    <script src="../js/html5shiv.js"></script>
    <script src="../js/respond.js"></script>
    <![endif]-->
</head>
<body>
<?php
include "ledClass.php";
$class = new ledClass();

//Check if user is logged in
if(isset($_SESSION['username']))
{
    //Include the navbar
    include "navbar.php";
    echo('
    <main>
        <div class="section no-pad-bot" id="index-banner">
            <div class="container">
                <h1 class="header center etmlcolor">À propos</h1>
                <ul>
                    <li class="divider"></li>
                </ul><br>
                <div class="row center">
                    <div class ="icon-block">
                        <div class="col s12 m6">
                            <img  class="responsive-img ng-scope" src="../../ressources/Images/etml100ans.jpg" alt="Image ETML 100 ANS">
                            <h4 class="header etmlcolor col s12">L\'ETML à 100 ans !</h4>
                        </div>
                    </div>
                    <div class ="icon-block">
                        <div class="col s12 m6">
                            <h4 class="header s12">Créée en 1916, l\'ETML fête ses 100 ans d\'existence.</h4><br>
                            <img  class="responsive-img ng-scope col s12" src="../../ressources/Images/LogoETMLled3.jpg" alt="Logo ETML Leds"><br>
                        </div>
                    </div>
                    <div class ="icon-block">
                        <div class="col s12 m6">
                            <h5 class="header s12">Ce projet créé à l\'attention des 100 ans permet à tous les élèves de l\'ETML d\'afficher un message sur la façade de l\'ETML.</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>');

    //Include the footer
    include "footer.php";
}
else
{
    //Redirect the user to a page with an error
    header("location: displayMessages.php?notLogged");
}
?>
<script>
    $(document).ready(function() {
        $(".button-collapse").sideNav();
    });
</script>
</body>
</html>