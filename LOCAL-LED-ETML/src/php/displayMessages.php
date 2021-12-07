<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0" />
    <title>ETML-LEDs</title>
    <!-- CSS  -->
    <link href="../../ressources/css/bootstrap.min.css" type="text/css" rel="stylesheet" media="screen" />
    <link href="../../ressources/css/signin.css" type="text/css" rel="stylesheet" media="screen" />
    <link href="../../ressources/Images/favicon.ico" rel="icon" type="image/x-icon" />
    <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
    <script type="text/javascript" src="../js/bootstrap.js"></script>
</head>
<body>
<?php
/**
 * Author: carvalhoda
 * Date: 25.01.2016
 * Summary : Page to display error/success messages
 */

?>
<div class="container">
    <?php
        //Include the class
        include("ledClass.php");

        //Instances the class
        $class = new ledClass();

        //Check if we got an error if the user isn't logged in
        if(isset($_GET['notLogged']))
        {
            //Call the method to display an error
            echo $class->displayErrorSuccessAlert(0,"Merci de vous connecter pour accéder au site web !");
            echo'<br>
                    <div class="row text-center">
                        <div class="col-md-4">
                            <a class="btn btn-primary " href="loginPage.php" role="button">Se connecter</a>
                        </div>
                        <div class="col-md-4 col-md-offset-4">
                            <a class="btn btn-info" href="registerPage.php" role="button">S\'inscrire</a>
                        </div>
                    </div>';
        }

        //Check if we got an error when sending the mail
        if(isset($_GET['mailError']))
        {
            //Call the method to display an error
            echo $class->displayErrorSuccessAlert(0,"Malheureusement une erreur s'est produite lors de l'envoi de la confirmation du compte sur votre adresse mail.");
            echo'<br>
                    <div class="center-align">
                                <a class="btn btn-primary" href="registerPage.php" role="button">Retourner à l\'inscription</a>
                    </div>';
        }

        //Check if we got an error in the verification of the token
        if(isset($_GET['tokenInvalid']))
        {
            //Call the method to display an error
            echo $class->displayErrorSuccessAlert(0,"Le token est invalide !");
            echo'<br>
                    <div class="center-align">
                                <a class="btn btn-primary" href="registerPage.php" role="button">Retourner à l\'inscription</a>
                    </div>';

        }

        //Check if the mail has been successfully sent
        if(isset($_GET['mailSuccess'],$_GET['email']))
        {
            //Store the email
            $email = $_GET['email'];

            //Display a success message
            echo $class->displayErrorSuccessAlert(1,"Nous avons envoyé un email à: $email
                                    Merci de valider votre compte en appuyant sur le lien qui vous est fourni.");
            echo'<br>
                    <div class="row text-center">
                        <div class="col-md-4">
                            <a class="btn btn-primary " href="registerPage.php" role="button">Retourner à l\'inscription</a>
                        </div>
                        <div class="col-md-4 col-md-offset-4">
                            <a class="btn btn-info" href="http://educanet2.ch" role="button">Se rendre sur educanet2</a>
                        </div>
                    </div>';
        }

        //Check if we got an error when verifying the password
        if(isset($_GET['pwNoMatch']))
        {
            //Call the method to display an error
            echo $class->displayErrorSuccessAlert(0,"Les mots de passes fournis ne correspondent pas !");
            echo'<br>
                    <div class="center-align">
                                <a class="btn btn-primary" role="button" href=\"javascript:history.go(-1)\">Retourner à la page précédente</a>
                    </div>';

        }

        //Check if we got an error when checking for each needed fields
        if(isset($_GET['missingInfos']))
        {
            //Call the method to display an error
            echo $class->displayErrorSuccessAlert(0,"Une erreur est survenue, informations manquantes !");
            echo'<br>
                    <div class="center-align">
                                <a class="btn btn-primary" role="button" href=\"javascript:history.go(-1)\">Retourner à la page précédente</a>
                    </div>';
        }
    ?>
</div>
</body>
</html>
