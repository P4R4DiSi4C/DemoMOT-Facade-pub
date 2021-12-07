<?php
/**
 * Created by PhpStorm.
 * User: carvalhoda
 * Date: 30.05.2016
 * Time: 17:26
 * Summary: Footer to be included
 */
?>
<footer class="page-footer">
    <div class="container">
        <div class="row">
            <div class="col l6 s12">
                <h5 class="etmlcolor"><span class="etmlfont">ETML</span>-<span class="light">LEDs</span></h5>
                <p class="black-text text-lighten-4">Définir un message à afficher sur la façade de l'ETML</p>
                <p class="black-text text-lighten-4">Site web réalisé par David Carvalho</p><br>
            </div>
            <div class="col l4 offset-l2 s12">
                <h5 class="black-text">Liens</h5>
                <ul>
                    <li><a class="etmlcolor" href="messages.php">Messages</a></li>
                    <li><a class="etmlcolor" href="#">File d'attente</a></li>
                    <li><a class="etmlcolor" href="about.php">À propos</a></li>
                    <?php
                    //Check if the user is logged in
                    if(isset($_SESSION['username']))
                    {
                        if($class->isAdmin())
                        {
                            echo('<li><a href="../../Admin/login.php" class="etmlcolor">Administration</a></li>');
                        }
                        echo('<li><a href="logoutFunction.php" class="etmlcolor">Déconnexion</a></li>');
                    }
                    else
                    {
                        echo('<li><a href="loginPage.php" class="etmlcolor">Connexion</a></li>');
                    }
                    ?>
                </ul>
            </div>
        </div>
    </div>
    <div class="footer-copyright">
        <div class="container">
            <span class="black-text text-lighten-3">Copyright © ETML 2016</span>
        </div>
    </div>
</footer>