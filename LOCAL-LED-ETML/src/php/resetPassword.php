<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0" />
    <title>ETML-LEDs-Choix du nouveau mot de passe</title>
    <!-- CSS AMD JAVASCRIPT -->
    <link href="../../ressources/css/bootstrap.min.css" type="text/css" rel="stylesheet" media="screen" />
    <link href="../../ressources/css/signin.css" type="text/css" rel="stylesheet" media="screen" />
    <link href="../../ressources/Images/favicon.ico" rel="icon" type="image/x-icon" />
    <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
    <script type="text/javascript" src="../js/bootstrap.js"></script>
    <script type="text/javascript" src="../js/validator.js"></script>
</head>
<body>

<?php
/**
 * Author: carvalhoda
 * Date: 03.11.2016
 * Summary : Page to validate the new password
 */
//Include the class
include("ledClass.php");

//Instances the class
$class = new ledClass();
?>
<div class="container">
    <?php
    //Check if the username and password are set
    if(isset($_GET['id'],$_GET['token']))
    {
        //Create 2 sessions to pass secretly the id and the token
        //Decode the ID
        $_SESSION["idToConfirm"] = base64_decode($_GET['id']);
        $_SESSION["tokenToConfirm"] = $_GET['token'];

        //Check if the token corresponds
        if (!$class->checkUserToken($_SESSION["idToConfirm"], $_SESSION["tokenToConfirm"]))
        {
            header("location:displayMessages.php?tokenInvalid");
        }
        else
        {
            echo "
                     <div class='alert alert-warning'>
                      <button class='close' data-dismiss='alert'>&times;</button>
                      <strong>Presque !</strong> Pour réinitialiser votre mot de passe, veuillez entre le nouveau mot de passe !
                       </div>
                     ";
            echo '
                    <form data-toggle="validator" role="form" class="form-signin" method="POST" action="resetPasswordPost.php">
                        <h2 class="form-signin-heading">Choix du nouveau mot de passe</h2>
                         <div class="form-group">
                            <label for="inputPW" class="control-label">Mot de passe:</label>
                            <input type="password" id="inputPW" name="inputPW" class="form-control" placeholder="Mot de passe" required autofocus><br>
                         </div>
                         <div class="form-group">
                            <label for="confirmPW" class="control-label">Confirmer nouveau mot de passe:</label>
                            <input type="password" id="confirmPW" name="confirmPW" class="form-control" placeholder="Confirmer mot de passe" required data-match="#inputPW" data-match-error="Les mots de passe ne correspondent pas !"><br>
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="center-align">
                            <button class="btn btn-lg btn-primary btn-block" type="submit">Valider le nouveau mot de passe</button>
                        </div>
                    </form>
                    ';
        }

    }
    else
    {
        echo $class->displayErrorSuccessAlert(0,"Error:Informations manquantes dans l'URL !");
        echo'<br>
                        <div class="center-align">
                                    <a class="btn btn-primary" href="registerPage.php" role="button">Retourner à l\'inscription</a>
                        </div>';
    }
    ?>
</div>

<script>
    //Javascript verification for the passwords
    $('button').on ('click', function () {
        if ($('#confirmPW').val() === $('#inputPW').val())
        {
            return true;
        }
        else {
            alert("Les mots de passe ne correspondent pas !");
            return false;
        }
    })
</script>
</body>
</html>
