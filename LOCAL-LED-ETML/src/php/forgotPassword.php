<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0" />
    <title>ETML-LEDs-Mot de passe oublié</title>
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
 * Date: 03.11.2016
 * Summary : Forgot password page of the led project
 */
?>

<div class="container">
    <?php
    //Include the class
    include("ledClass.php");

    //Instances the class
    $class = new ledClass();

    //****************Display the corresponding alert messages*******************//
    //If the mail wasn't find in the db
    if(isset($_GET['notInDb']))
    {
        //Display a danger message
        echo $class->displayErrorSuccessAlert(0,"Votre adresse mail n'a pas été activée ou ne figure pas dans la base de données !");
    }

    //If the mail doesn't correspond to the pattern
    if(isset($_GET['patternError']))
    {
        //Display a danger message
        echo $class->displayErrorSuccessAlert(0,"L'adresse mail ne correspond pas à une adresse mail valide !");
    }

    //If the mail is missing
    if(isset($_GET['missingMail']))
    {
        //Display a danger message
        echo $class->displayErrorSuccessAlert(0,"L'adresse mail est manquante !");
    }

    //Check if the user is already logged in and redirect him to the index page
    if(isset($_SESSION['username']))
    {
        header("location: index.php");
    }
    else
    {
        echo('
                    <h1 class="text-center" >Bienvenue sur ETML - LEDs</h1 >
                    <form data-toggle = "validator" role = "form" class="form-signin" method = "POST" action = "forgotPasswordPost.php" >
                        <h2 class="form-signin-heading" > Mot de passe oublié</h2 >
                        <a href="loginPage.php" > J\'ai déjà un compte, me connecter !</a><br><br>
                        <div class="form-group">
                            <label for="inputEmail" class="control-label">Adresse mail educanet:</label>
                            <input type="email" id="inputEmail" name="inputEmail" class="form-control"
                            placeholder="Adresse educanet"
                            value="@etml.educanet2.ch"
                            pattern="^[a-z]+@(etml|vd).educanet2.ch$" data-error="Adresse mail invalide !"
                            required autofocus>
                            <div class="help-block with-errors"></div>
                        </div><br>
                        <div class="center-align">
                            <button class="btn btn-lg btn-primary btn-block" type="submit">Réinitialiser mon mot de passe </button >
                        </div >
                    </form >');
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