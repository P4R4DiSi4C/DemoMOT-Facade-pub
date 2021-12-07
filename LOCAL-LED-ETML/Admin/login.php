<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Connexion à l'administration du site ETML-LEDs">
    <meta name="author" content="Carvalhoda">

    <title>Admin - Connexion</title>

    <!-- Bootstrap core CSS -->
    <link href="assets/css/bootstrap.css" rel="stylesheet">
    <!--external css-->
    <link href="assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
        
    <!-- Custom styles for this template -->
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="../ressources/Images/favicon.ico" rel="icon" type="image/x-icon" />
    <link href="assets/css/style-responsive.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="../src/js/html5shiv.js"></script>
      <script src="../src/js/respond.js"></script>
    <![endif]-->
  </head>

  <body>

      <!-- **********************************************************************************************************************************************************
      MAIN CONTENT
      *********************************************************************************************************************************************************** -->

        <?php
            include "adminClass.php";
            $adminClass = new adminClass();

            //Check if the $_SESSION is set
            if(isset($_SESSION['username']))
            {
              //CHECK IF HE'S ADMIN
              if($adminClass->isAdmin())
              {
                  //If he's admin and already logged in he's directly redirected to the index page
                 header("location: index.php");
              }
              else
              {
                  //If he's logged in but not admin he goes back to the index page
                  header("location: ../src/php/index.php?notAdmin");
              }
            }

            //Check if string matches pattern
            if(isset($_GET['patternError']))
            {
                //Display a danger message
                echo $adminClass->displayErrorSuccessAlert(0,"L'adresse mail ne correspond pas à une adresse mail valide !");
            }

            //Check if missing values
            if(isset($_GET['missingValues']))
            {
                //Display a danger message
                echo $adminClass->displayErrorSuccessAlert(0,"L'adresse mail ou le mot de passe est manquant/e !");
            }

            //Check if account already activated
            if(isset($_GET['accAlreadyON']))
            {
                //Display a danger message
                echo $adminClass->displayErrorSuccessAlert(0,"Ce compte a déjà été activé, veuillez vous connecter !");
            }

            //Check if incorrect password
            if(isset($_GET['pwError']))
            {
                //Display a danger message
                echo $adminClass->displayErrorSuccessAlert(0,"Le mot de passe est incorrect !");
            }

            //Check if acc wasn't find
            if(isset($_GET['accNotFound']))
            {
                //Display a danger message
                echo $adminClass->displayErrorSuccessAlert(0,"Le compte n'a pas pu être trouvé !");
            }

            //Check if acc wasn't find
            if(isset($_GET['notAdmin']))
            {
                //Display a danger message
                echo $adminClass->displayErrorSuccessAlert(0,"Vous n'êtes pas administrateur !");
            }
        ?>


	  <div id="login-page">
	  	<div class="container">
	  	
		      <form method="POST" class="form-login" action="loginAdmin.php">
		        <h2 class="form-login-heading">Accéder à l'administration</h2>
		        <div class="login-wrap">
		            <input name="adminMail" type="text" class="form-control" placeholder="Adresse mail" pattern = "^[a-z]+@(etml|vd).educanet2.ch$" required autofocus>
		            <br>
		            <input name="adminPassword" type="password" class="form-control" placeholder="Mot de passe" required>
                    <br>
		            <button class="btn btn-theme btn-block" type="submit"><i class="fa fa-lock"></i> SE CONNECTER</button>
                    <br>
                    <a class="btn btn-theme btn-block" href="../index.html">RETOURNER SUR LE SITE</a>
                    <hr>
		        </div>
		      </form>
	  	
	  	</div>
	  </div>

    <!-- js placed at the end of the document so the pages load faster -->
    <script src="assets/js/jquery.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>

    <!--BACKSTRETCH-->
    <!-- You can use an image of whatever size. This script will stretch to fit in any screen size.-->
    <script type="text/javascript" src="assets/js/jquery.backstretch.min.js"></script>
    <script>
        $.backstretch("assets/img/login-bg.jpg", {speed: 500});
    </script>


  </body>
</html>
