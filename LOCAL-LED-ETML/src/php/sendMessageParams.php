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
    <title>LEDs-Paramètres</title>
    <!-- CSS AND JAVASCRIPT -->
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

    //Count is at 0 by default
    $count = 0;

    //************CHECK WHAT SUBMIT BUTTON HAS BEEN USED*********************//

    //Check if the modification button has been used
    if(isset($_POST['saveMod']))
    {
        //Check if we got all the variables we needed
        if(isset($_POST['idMod']) && ($_POST['idMod'] != "NULL"))
        {
            //Check if the users has the rights to modify
            if ($class->checkIfRightsToModify($_POST['idMod']))
            {
                //Check if the text matches the regex
                if(preg_match($class->textPattern,$_POST['messageInput']))
                {
                    //Call the method to modify the text
                    $class->modifyText($_POST['idMod'], $_POST['messageInput']);
                }
                else
                {
                    //Redirect the user to a page with a pattern error message
                    header("location:messages.php?patternError");
                }
            }
            else
            {
                //Redirect the user to a page with a no rights error message
                header("location:messages.php?noRights");
            }
        }
        else
        {
            //Redirect the user to a page with an error nothing to modify
            header("location:messages.php?nothingToMod");
        }
    }
    else
    {
        //Check if delete button has been used
        if (isset($_POST['deleteBtn']))
        {
            //Check if any checkbox has been checked
            if(isset($_POST['DeleteCheck']))
            {
                //Create a session var to store an array of ids to delete
                $_SESSION['idsArray'] = $_POST['DeleteCheck'];

                //Check if the user has the right to delete these values
                //to prevent any deletion of another user texte
                if ($class->checkIfRightsToDelete())
                {
                    //If he has the rights we redirect him to the page to confirm deletion
                    header("location:deletePage.php");
                }
                else
                {
                    //If he doesn't, we redirect him to the messages page again and display a toast with an error
                    header("location:messages.php?noRights");
                }
            }
            else
            {
                //Redirect the user to a page with an error nothing to delete
                header("location:messages.php?nothingToDel");
            }
        }
        else
        {
            //Check if he made a choice
            if (isset($_POST['submitBtn'],$_POST['mesGroup']))
            {
                //Get the id of the text
                $idText = $_POST['mesGroup'];

                //Check if he has the rights to use the text
                //To prevent any use of another user text
                if ($class->checkIfRightsToUse($idText))
                {
                    //Count the lenght of the text
                    $count = strlen($class->getTextWithID($idText));
                }
                else
                {
                    //If he doesn't, we redirect him to the messages page again and display a toast with an error
                    header("location:messages.php?noRights");
                }
            }
            else
            {
                //If he didn't make a choice we redirect him to the messages page
                header("location:messages.php?noChoice");
            }
        }
    }
?>
<main>
    <div class="pagecontainer">
        <form id="newMessageForm" class="formNewMessage row center container" action="addMessageParam.php" method="post">
            <h3 class="etmlcolor">Paramètres</h3>
            <div class="row">
                <?php
                    //Check if the text is sufficiently long to set the parameters
                    if($count > 5)
                    {
                        echo'
                            <div class="input-field col s12 m6">
                                <select id="speedList" name="speedList" required>
                                    <option value="1" selected>1 sec</option>
                                    <option value="2">2 sec</option>
                                    <option value="3">3 sec</option>
                                    <option value="4">4 sec</option>
                                    <option value="5">5 sec</option>
                                </select>
                                <label for="speedList">Vitesse de défilement</label>
                            </div>
                            <div class="input-field col s12 m6">
                                <select id="figList" name="figList" required>';
                                        //Get the figures in a select
                                        $data=$class->getAllFigures();

                                        //Loop the ids and add each of them to the select
                                        foreach($data as $row)
                                        {
                                            if($row["figData"] != 0)
                                            {
                                                echo'<option value="'.$row['idFigure'].'">'.$row["figMove"].'</option>';
                                            }
                                        }
                        echo'
                                </select>
                                <label for="figList">Effet de défilement</label>
                            </div>';
                    }
                    else
                    {
                        //Show a panel with informations if we cant parameter it
                        //Create 2 hidden input with default values
                        echo('
                        <input type="hidden" id="speedList" name="speedList" value="0">
                        <input type="hidden" id="figList" name="figList" value="1">

                        <div class="card-panel white-text cyan darken-4 center col s12 m8 offset-m2">
                             <i class="medium material-icons">info_outline</i>
                             <h4>Non paramétrable</h4>
                                <span>
                                    Pour pouvoir paramétrer la vitesse de défilement et l\'effet de défilement, votre message doit faire plus de 5 caractères.<br>
                                    Au contraire, si votre message fait 5 caractères ou moins, il sera considéré comme un texte fixe et ne défilera pas.
                                </span></br></br>
                        </div>');
                     }
                ?>

            </div>
            <div class="row">
                <input type="hidden" name="idText" value="<?php echo($idText); ?>">
                <div class="icon-block col s12 m4 offset-m4"><br>
                    <h5 class="etmlcolor left">Couleur du texte:</h5>
                    <div class="input-field">
                        <input name="colorPicker" type="text" id="colorPicker"/>
                    </div>
                </div>
            </div>
            <div class="center-align col s12 m4 offset-m4">
                <button name="submitBtn" type="submit" value="Envoyer" class="btnAdd btn-large waves-effect waves-light blue" onclick="return checkNewMessageForm(this)">
                    <i class="material-icons left">add</i><span class="valign">Envoyer</span>
                </button>
            </div><br>
        </form>
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

    //Custom options for the color picker
    $("#colorPicker").spectrum({
        color: "rgb(255,0,0)",
        clickoutFiresChange: true,
        showButtons: false,
        preferredFormat: "rgb",
        showInput: true,
        showPalette: true,
        hideAfterPaletteSelect:true,
        palette: [["red", "rgb(0, 255, 0)", "rgb(0, 0, 255)"]],
        maxSelectionSize: 5
    });
</script>
</body>
</html>