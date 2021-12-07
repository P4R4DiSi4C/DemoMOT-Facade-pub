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
    <title>LEDs-File d'attente</title>
    <!-- CSS  -->
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
    //Include the navbar
    include "navbar.php";

    echo('
        <main>
            <div id="responsive" class="section scrollspy container">
                <h3 class="center etmlcolor">File d\'attente</h3>
                <div class="row">
                    <div class="col s12">');
                        $class->getPendingMessages();
        echo('
                    </div>
                </div>
            </div>
        </main>');

    //Include the footer
    include "footer.php";
}
else
{
    //Redirect the user to a page with an error that says that the user is not logged in
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