<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0" />
    <title>LEDs-Accueil</title>
    <!-- CSS  AND JAVASCRIPT-->
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
/**
 * Author: carvalhoda
 * Date: 25.01.2016
 * Summary : Index page of the led project
 */

//Include the class
include "ledClass.php";

//Instances the class
$class = new ledClass();
?>

<?php
//Check if the user is logged in
if(isset($_SESSION['username']))
{
    //Include the navbar
    include "navbar.php";
    //Check if he we got an error if he's not admin
    if(isset($_GET['notAdmin']))
    {
        //Display a danger message
        echo $class->displayErrorSuccessAlert(0,"Vous n'êtes pas administrateur !");
    }
    echo('
    <main>
        <div class="container">
            <div class="row center">
            <br>
            <img  class="center responsive-img ng-scope " height="250" width="940" src="../../ressources/Images/header_default.jpg" alt="Bannière ETML">
                <br>
                <h3 class="etmlcolor">Bienvenue sur le site ETML-LEDs</h3>
                <p>Ce site vous permet de définir un message à afficher sur la façade de l\'ETML avec un effet de défilement.</p>
                <p>Les messages que vous afficher seront sauvegardés et pourront être réutilisés plus tard.</p>
            </div>
            <div class="row center">
                <div class="col s12 m4">
                    <div class="icon-block">
                        <h2 class="center etmlcolor"><i class="material-icons">message</i></h2>
                        <h5 class="center">Vos messages</h5>

                        <p class="light">Tous les messages que vous soummetez seront sauvegarder et réutilisables. Vous pourrez les supprimer ou les modifier.</p>
                    </div>
                </div>

                <div class="col s12 m4">
                    <div class="icon-block">
                        <h2 class="center etmlcolor"><i class="material-icons">list</i></h2>
                        <h5 class="center">File d\'attente</h5>

                        <p class="light">Grâce au système de file d\'attente vous pourrez consulter l\'heure de passage des messages en attente d\'affichage. Vous pourrez ainsi suivre l\'état de l\'affichage.</p>
                    </div>
                </div>

                <div class="col s12 m4">
                    <div class="icon-block">
                        <h2 class="center etmlcolor"><i class="material-icons">open_with</i></h2>
                        <h5 class="center">Figures</h5>

                        <p class="light">Grâce à notre système de figures vous pourrez paramétrer le défilement de votre message ainsi que sa couleur et la vitesse de défilement.</p>
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
    //Redirect the user to the page with an error if he's not logged in
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