<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Administration des comptes du site ETML-LEDs">
    <meta name="author" content="Carvalhoda">

    <title>ETML-LEDs Administration comptes</title>

  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css">
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>


    <!-- Bootstrap core CSS -->
    <link href="assets/css/bootstrap.css" rel="stylesheet">
    <!--external css-->
    <link href="assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
    <link href="../ressources/Images/favicon.ico" rel="icon" type="image/x-icon" />
    <link rel="stylesheet" type="text/css" href="assets/css/zabuto_calendar.css">
    <link rel="stylesheet" type="text/css" href="assets/js/gritter/css/jquery.gritter.css" />
    <link rel="stylesheet" type="text/css" href="assets/lineicons/style.css">

      <!-- Custom styles for this template -->
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="assets/css/style-responsive.css" rel="stylesheet">

    <script src="assets/js/chart-master/Chart.js"></script>
    
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="../src/js/html5shiv.js"></script>
      <script src="../src/js/respond.js"></script>
    <![endif]-->

  </head>
  <?php
    include "adminClass.php";
    $class = new adminClass();

    //If the user isn't connected we redirect him to login page
    if(!isset($_SESSION['username']))
    {
        header("location:login.php");
    }
    else
    {
        if(!$class->isAdmin())
        {
            header("location: ../src/php/index.php?notAdmin");
        }
    }
  ?>
  <body>

  <section id="container" >
      <!-- **********************************************************************************************************************************************************
      TOP BAR CONTENT & NOTIFICATIONS
      *********************************************************************************************************************************************************** -->
      <!--header start-->
      <header class="header black-bg">
              <div class="sidebar-toggle-box">
                  <div class="fa fa-bars tooltips" data-placement="right" data-original-title="Toggle Navigation"></div>
              </div>
            <!--logo start-->
            <a href="index.php" class="logo"><b>ETML-LEDs Admin</b></a>
            <!--logo end-->
          <div class="top-menu">
            	<ul class="nav pull-right top-menu">
                    <li><a class="logout" href="../src/php/index.php">Retourner sur le site</a></li>
                    <li><a class="logout" href="./logoutFunction.php">Se déconnecter</a></li>
            	</ul>
            </div>
        </header>
      <!--header end-->
      
      <!-- **********************************************************************************************************************************************************
      MAIN SIDEBAR MENU
      *********************************************************************************************************************************************************** -->
      <!--sidebar start-->
      <aside>
          <div id="sidebar"  class="nav-collapse ">
              <!-- sidebar menu start-->
              <ul class="sidebar-menu" id="nav-accordion">
              	  <p class="centered"><img src="http://icons.iconarchive.com/icons/paomedia/small-n-flat/1024/user-male-icon.png" class="img-circle" width="60"></p>
              	  <h5 class="centered"><?php echo($_SESSION['username']); ?></h5>

                  <li class="mt">
                      <a href="index.php">
                          <i class="fa fa-dashboard"></i>
                          <span>Accueil</span>
                      </a>
                  </li>

                  <li class="sub-menu">
                      <a class="active" href="accounts.php">
                          <i class="fa fa-book"></i>
                          <span>Comptes</span>
                      </a>
                  </li>
              </ul>
              <!-- sidebar menu end-->
          </div>
      </aside>
      <!--sidebar end-->
      
      <!-- **********************************************************************************************************************************************************
      MAIN CONTENT
      *********************************************************************************************************************************************************** -->
      <!--main content start-->
      <section id="main-content">
          <section class="wrapper site-min-height">
              <h3><i class="fa fa-angle-right"></i> Administration des comptes</h3>
              <div class="row mt">
                  <div class="col-lg-12">
                      <?php
                        $accounts = $class->getActivatedAccsDetails();
                        echo'<div class="row">';
                        foreach($accounts as $row)
                        {
                            $id = $row['idUser'];

                            echo('

                              <div class="col-lg-3 col-md-3 col-sm-3 mb">
                                  <div class="white-panel pn">
                                      <div class="white-header">
                                        <h5>ID #'.$id.'</h5>
                                      </div>
                                      <p><img src="assets/img/ui-zac.jpg" class="img-circle" width="50"></p>
                                      <p><b><h5> '.$row['useUsername'].'</h5></b></p><br>');

                            if(!$class->AccountIsAdmin($id))
                            {
                                echo('
                                <div class="row">
                                          <div class="col-md-6">
                                              <a href = "promoteAdminFunction.php?id='.$id.'" class="btn btn-theme" ><i class="fa fa-level-up" ></i ></i > Promouvoir admin</a>
                                          </div>
                                          <div class="col-md-6">
                                              <a href = "resetAccountFunction.php?id='.$id.'" class="btn btn-theme04" ><i class="fa fa-power-off" ></i ></i > Reset le compte</a>
                                          </div>
                                      </div>
                                  </div>');
                            }
                            else
                            {
                                echo('
                                <div class="row">
                                          <div class="col-md-6">
                                              <a href = "revokeAdminFunction.php?id='.$id.'" class="btn btn-theme" ><i class="fa fa-level-down" ></i ></i > Révoquer admin</a>
                                          </div>
                                          <div class="col-md-6">
                                              <a href = "resetAccountFunction.php?id='.$id.'" class="btn btn-theme04" ><i class="fa fa-power-off" ></i ></i > Reset le compte</a>
                                          </div>
                                      </div>
                                  </div>');
                            }
                            echo('
                              </div>
                            ');
                        }
                      echo'</div>';
                      ?>
          </section><! --/wrapper -->
      </section><!-- /MAIN CONTENT -->

      <!--main content end-->
      <!--footer start-->
      <footer class="site-footer">
          <div class="text-center">
              Copyright © ETML 2016 - David Carvalho
              <a href="index.php#" class="go-top">
                  <i class="fa fa-angle-up"></i>
              </a>
          </div>
      </footer>
      <!--footer end-->
  </section>

    <!-- js placed at the end of the document so the pages load faster -->
    <script src="assets/js/jquery.js"></script>
    <script src="assets/js/jquery-1.8.3.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script class="include" type="text/javascript" src="assets/js/jquery.dcjqaccordion.2.7.js"></script>
    <script src="assets/js/jquery.scrollTo.min.js"></script>
    <script src="assets/js/jquery.nicescroll.js" type="text/javascript"></script>
    <script src="assets/js/jquery.sparkline.js"></script>


    <!--common script for all pages-->
    <script src="assets/js/common-scripts.js"></script>

    <script type="text/javascript" src="assets/js/gritter/js/jquery.gritter.js"></script>
    <script type="text/javascript" src="assets/js/gritter-conf.js"></script>

	<script type="application/javascript">

        function myNavFunction(id) {
            $("#date-popover").hide();
            var nav = $("#" + id).data("navigation");
            var to = $("#" + id).data("to");
            console.log('nav ' + nav + ' to: ' + to.month + '/' + to.year);
        }
    </script>
  

  </body>
</html>
