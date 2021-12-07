<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0" />
    <title>ETML-LEDs-Connexion</title>
    <!-- CSS  -->
    <link href="../../ressources/css/bootstrap.min.css" type="text/css" rel="stylesheet" media="screen" />
    <link href="../../ressources/css/signin.css" type="text/css" rel="stylesheet" media="screen" />
    <link href="../../ressources/Images/favicon.ico" rel="icon" type="image/x-icon" />
    <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
    <script type="text/javascript" src="../js/validator.js"></script>
    <script type="text/javascript" src="../js/bootstrap.js"></script>
</head>
<body>

<?php
/**
 * Author: carvalhoda
 * Date: 25.01.2016
 * Summary : Login page of the led project
 */
?>
    <div class="container">
        <?php
        //Include the class
        include("ledClass.php");

        //Instances the class
        $class = new ledClass();

        //Check if the verification was successfull
        if(isset($_GET['verifSuccess']))
        {
            echo"
                 <div class='alert alert-success'>
                  <button class='close' data-dismiss='alert'>&times;</button>
                  <strong>Succès!</strong> Votre compte à bien été validé !
                    </div>
                 ";
        }

        //Check if string matches pattern
        if(isset($_GET['patternError']))
        {
            //Display a danger message
            echo $class->displayErrorSuccessAlert(0,"L'adresse mail ne correspond pas à une adresse mail valide !");
        }

        //Check if missing values
        if(isset($_GET['missingValues']))
        {
            //Display a danger message
            echo $class->displayErrorSuccessAlert(0,"L'adresse mail ou le mot de passe est manquant/e !");
        }

        //Check if account already activated
        if(isset($_GET['accAlreadyON']))
        {
            //Display a danger message
            echo $class->displayErrorSuccessAlert(0,"Ce compte a déjà été activé, veuillez vous connecter !");
        }

        //Check if incorrect password
        if(isset($_GET['pwError']))
        {
            //Display a danger message
            echo $class->displayErrorSuccessAlert(0,"Le mot de passe est incorrect !");
        }

        //Check if acc wasn't find
        if(isset($_GET['accNotFound']))
        {
            //Display a danger message
            echo $class->displayErrorSuccessAlert(0,"Le compte n'a pas pu être trouvé !");
        }

        //Check if password has been reset
        if(isset($_GET['passwordResOk']))
        {
            //Display a success message
            echo"
                 <div class='alert alert-success'>
                  <button class='close' data-dismiss='alert'>&times;</button>
                  <strong>Succès!</strong> Votre mot de passe à bien été réinitialiser !
                    </div>
                 ";
        }

        //Check if the user is already logged in and redirect him to the index page
        if(isset($_SESSION['username']))
        {
            //Redirect to the index
            header("location: index.php");
        }
        else
        {
        echo('
            <h1 class="text-center" >Bienvenue sur ETML - LEDs</h1 >
            <form data-toggle = "validator" role = "form" class="form-signin" method = "POST" action = "loginPost.php" >
                <h2 class="form-signin-heading" > Connexion</h2 >
                <a href = "registerPage.php" > Je n\'ai pas de compte, m\'inscrire !</a ><br ><br >
                <div class="form-group" >
                    <label for="inputEmail" class="control-label" > Adresse educanet </label >
                    <input type = "text" id = "inputEmail" name = "inputEmail" class="form-control" placeholder = "Adresse educanet" value = "@etml.educanet2.ch" pattern = "^[a-z.]+@(etml|vd).educanet2.ch$" data-error = "Adresse mail invalide !" required >
                    <div class="help-block with-errors"></div >
                </div >
                <label for="inputPassword" class="sr-only" > Mot de passe </label >
                <input type = "password" id = "inputPassword" name = "inputPassword" class="form-control" placeholder = "Mot de passe" required >
                <a href = "forgotPassword.php" > J\'ai oublier mon mot de passe</a ><br><br>
                <div class="center-align" >
                    <button class="btn btn-lg btn-primary btn-block" type = "submit"> Se connecter </button >
                </div>
            </form>');
        }
        ?>
    </div>
<script>
    //Code to fade automatically the alert
    $("#alert").fadeTo(8000, 500).slideUp(500, function(){
        $("#alert").alert('close');
    });
</script>
</body>
</html>
