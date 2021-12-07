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
    <title>LEDs-Messages</title>
    <!-- CSS AND JAVASCRIPT-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="../../ressources/css/materialize.css" type="text/css" rel="stylesheet" media="screen" />
    <link href="../../ressources/css/style.css" type="text/css" rel="stylesheet" media="screen" />
    <link href="../../ressources/css/spectrum.css" type="text/css" rel="stylesheet" media="screen" />
    <link href="../../ressources/Images/favicon.ico" rel="icon" type="image/x-icon" />
    <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
    <script type="text/javascript" src="../js/materialize.js"></script>
    <script type="text/javascript" src="../js/led.js"></script>
    <script type="text/javascript" src="../js/spectrum.js"></script>
    <script type="text/javascript" src="../js/bootstrap.js"></script>

    <!--[if lte IE 8]>
    <script src="../js/selectivizr.js"></script>
    <![endif]-->
    <!--[if lt IE 9]>
    <script src="../js/html5shiv.js"></script>
    <script src="../js/respond.js"></script>
    <![endif]-->

    <!--VERIFICATION TO SEE IF JAVASCRIPT IS ENABLED-->
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
?>
<?php
    //Check if the user is logged in
    if(isset($_SESSION['username']))
    {
        //Display the navbar
        include "navbar.php";

        //Check if we got any error when modifying the message
        if(isset($_GET['noRights']))
        {
            //Call the method to display a success/error message
            echo $class->displayErrorSuccessAlert(0,"Vous n'avez pas les droits pour modifier/supprimer ce message !");
        }

        //Check if we got an error when trying to make a choice/modify or delete something
        if(isset($_GET['nothingToMod']) || isset($_GET['nothingToDel']) || isset($_GET['noChoice']))
        {
            //Call the method to display a success/error message
            echo $class->displayErrorSuccessAlert(0,"Merci de sélectionner au moins 1 message à supprimer/modifier ou à utiliser !");
        }

        //Check if we got a text that is bigger than 55 chars
        if(isset($_GET['addTxtTooLong']))
        {
            //Call the method to display a success/error message
            echo $class->displayErrorSuccessAlert(0,"Le texte que vous avez tenter ajouter dépasse la limite de 55 caractères !");
        }

        //Check if we got an empty message
        if(isset($_GET['addNoText']))
        {
            //Call the method to display a success/error message
            echo $class->displayErrorSuccessAlert(0,"Ne tentez pas d'ajouter du texte vide !");
        }

        //Check if the user altered a value when sending the message
        if(isset($_GET['sendMessageError']))
        {
            //Call the method to display a success/error message
            echo $class->displayErrorSuccessAlert(0,"Une erreur est survenue lors de l'envoi de votre message. Ne modifiez pas les valeurs attribuées aux champs !");
        }

        //Check if the pattern wasn't respected
        if(isset($_GET['patternError']))
        {
            //Call the method to display a success/error message
            echo $class->displayErrorSuccessAlert(0,"Le texte possède des caractères interdits !");
        }

        //Store the display to add a mesasge in a var because we use it 2 times
        $addMessage =
                    '<div class="container">
                        <div class="row">
                            <div class="center input-field col s12 m6 offset-m3">
                                <h5>Ajouter un nouveau texte</h5>
                                    <form id="newMessageForm" action="newMessagePost.php" method="post">
                                        <div class="input-field col s12">
                                            <input id="textMsg" name="textMsg" type="text" class="validate" length="55" required pattern="^[a-z-A-Z -?!0-9\']+$">
                                            <label for="textMsg">Texte du message</label>
                                            <br><br>
                                            <button onclick="return checkTextLength(this)" name="newMessageBtn" type="submit" value="Ajout message" class="waves-effect waves-light btn blue">
                                                <i class="material-icons left">add</i><span class="valign">Ajouter</span>
                                            </button>
                                        </div>
                                    </form>
                            </div>
                        </div>
                    </div>';?>
        <main>
            <div class="pagecontainer">
                <?php
                    //Check to see if the user already has messages to show
                    if($class->getUserMessages() != null)
                    {
                ?>
                    <form id="sendMessage" method="post" action="sendMessageParams.php">
                        <div id="responsive" class="section scrollspy container">
                            <h3 class="center etmlcolor">Messages</h3>
                            <div class="row">
                                <div class="col s12">
                                    <?php echo($class->getUserMessages());?>
                                </div>
                                <input type="hidden" id="hiddenID" name="idMod" value="NULL">

                            </div>
                        </div>
                        <div class="container row">

                            <div class="col s12 m4">
                                <button name="submitBtn" type="submit" value="Choisir message" class="waves-effect waves-light btn blue">
                                    <i class="material-icons left">send</i><span class="valign">ENVOYER</span>
                                </button>
                            </div>

                            <div class="col s12 m3 offset-m1">
                                <button name="saveMod" type="submit" value="Modifications" onclick="return checkTextLengthMod(this)" class="waves-effect waves-light btn green">
                                    <i class="material-icons left">edit</i><span class="valign">Appliquer</span>
                                </button>
                            </div>

                            <div class="col s12 m3 offset-m1">
                                <button name="deleteBtn" type="submit" value="Supprimer" class="waves-effect waves-light btn red">
                                    <i class="material-icons left">delete</i><span class="valign">Supprimer</span>
                                </button>
                            </div>

                        </div>
                    </form>
                <div class="container"><br>
                    <ul>
                        <li class="divider"></li>
                    </ul>
                    <?php
                        //Show the add a message option
                        echo $addMessage;
                    }
                    else
                    {//If the user doesn't have any message we show him a small warning message with the option to add a new one
                    ?>
                        <h3 class="center etmlcolor">Messages</h3>
                        <h5 class="center red-text">Vous n'avez pas encore ajouté de messages</h5>
                        <?php echo $addMessage;
                    }
                    ?>
                </div>
            </div>
        </main>
        <?php
            //Include the footer
            include "footer.php";
    }
    else
    {
        //Redirect the user to a page with an error that says not logged in
        header("location: displayMessages.php?notLogged");
    }
    ?>

<script>
    //Code that's run when the page is ready
    $(document).ready(function() {
        $(".button-collapse").sideNav();
        $('select').material_select();
    });

    //Code to fade automatically the alert
    $("#alert").fadeTo(8000, 500).slideUp(500, function(){
        $("#alert").alert('close');
    });

</script>
</body>
</html>