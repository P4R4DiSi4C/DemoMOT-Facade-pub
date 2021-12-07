<?php
/**
 * Created by PhpStorm.
 * User: carvalhoda
 * Date: 30.05.2016
 * Time: 15:40
 * Summary: Navbar to be included
 */
?>
<header>
    <nav>
        <div class="nav-wrapper container">
            <a href="index.php" class="brand-logo">etml</a>
            <a href="#" data-activates="mobile-demo" class="button-collapse"><i class="material-icons black-text">menu</i></a>
            <ul class="right hide-on-med-and-down">
                <li><a href="messages.php" class="black-text">Messages</a></li>
                <li><a href="pending.php" class="black-text">File d'attente</a></li>
                <li><a href="about.php" class="black-text">À propos</a></li>
            <?php
                //Check if the user is logged in
                if(isset($_SESSION['username']))
                {
                    if($class->isAdmin())
                    {
                        echo('<li><a href="../../Admin/login.php" class="black-text">Administration</a></li>');
                    }
                    echo('<li><a href="logoutFunction.php" class="black-text">Déconnexion</a></li>');
                }
                else
                {
                    echo('<li><a href="loginPage.php" class="black-text">Connexion</a></li>');
                }
            ?>
            </ul>
            <ul class="side-nav" id="mobile-demo">
                <li><a href="index.php">Accueil</a></li>
                <li><a href="messages.php">Messages</a></li>
                <li><a href="pending.php">File d'attente</a></li>
                <li><a href="about.php">À propos</a></li>
                <li class="divider"></li>
                <?php
                    //Check if the user is logged in
                    if(isset($_SESSION['username']))
                    {
                        if($class->isAdmin())
                        {
                            echo('<li><a href="../../Admin/login.php" class="black-text">Administration</a></li>');
                        }
                        echo('<li><a href="logoutFunction.php" class="black-text">Déconnexion</a></li>');
                    }
                    else
                    {
                        echo('<li><a href="loginPage.php" class="black-text">Connexion</a></li>');
                    }
                ?>
            </ul>
        </div>
    </nav>
</header>

