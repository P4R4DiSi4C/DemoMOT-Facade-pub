<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Administration du site ETML-LEDs">
    <meta name="author" content="Carvalhoda">

    <title>ETML-LEDs Administration</title>

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
                      <a class="active" href="index.php">
                          <i class="fa fa-dashboard"></i>
                          <span>Accueil</span>
                      </a>
                  </li>

                  <li class="sub-menu">
                      <a href="accounts.php">
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
          <section class="wrapper">

              <div class="row">
                  <div class="col-lg-9 main-chart">
                  
                  	<div class="row mtbox">
                  		<div class="col-lg-4 col-md-4 col-sm-4 mb box0">
                  			<div class="box1">
					  			<span class="li_note"></span>
					  			<h3><?php echo($class->countAllMessages()[0][0]); ?></h3>
                  			</div>
					  			<p>Nombre total de messages.</p>
                  		</div>
                  		<div class="col-lg-4 col-md-4 col-sm-4 mb box0">
                  			<div class="box1">
					  			<span class="li_mail"></span>
					  			<h3>+<?php echo($class->countNewMessages()[0][0]) ?></h3>
                  			</div>
					  			<p>Nombre de messages ajoutés ces dernières 24h.</p>
                  		</div>
                  		<div class="col-lg-4 col-md-4 col-sm-4 mb box0">
                  			<div class="box1">
                                <?php
                                    $color = $class->mostUsedColor()[0][0];
                                    $colorUsedTimes = $class->mostUsedColor()[0][1];
                                ?>
					  			<span class="li_star"></span>
					  			<h3 style="color:<?php echo($color)?>">Couleur la + utilisée</h3>
                  			</div>
					  			<p>Cette couleur à été utilisée <?php echo $colorUsedTimes; ?> fois.</p>
                  		</div>
                  	</div><!-- /row mt -->	
                  
                      
                      <div class="row mt">
                      <!-- SERVER STATUS PANELS -->
                      	<div class="col-md-6 mb">
                      		<div class="white-panel pn donut-chart">
                      			<div class="white-header">
						  			<h5>Comptes activés</h5>
                      			</div>
								<div class="row">
									<div class="col-sm-6 col-xs-6 goleft">
                                        <?php
                                            $activatedAccs = $class->getActivatedAccs()[0][0];
                                            $totalUsers = $class->getNbUsers()[0][0];
                                            $percentage = ($activatedAccs / $totalUsers) * 100;
                                            $total = 100 - $percentage;
                                        ?>
										<p><i class="fa fa-database"></i><?php echo $percentage ?>%</p>
									</div>
	                      		</div>
								<canvas id="activatedAccs" height="120" width="120"></canvas>
								<script>
									var pieData = [
											{
												value: <?php echo $percentage;?>,
												color:"#68dff0"
											},
											{
												value : <?php echo $total;?>,
												color : "#fdfdfd"
											}
										];
										var myPieChart = new Chart(document.getElementById("activatedAccs").getContext("2d")).Doughnut(pieData);
								</script>
	                      	</div><! --/grey-panel -->
                      	</div><!-- /col-md-4-->

                      	
						<div class="col-md-6 mb">
							<!-- WHITE PANEL - TOP USER -->
							<div class="white-panel pn">
								<div class="white-header">
                                    <?php
                                        $userWithMostMessages = $class->userMostMessages();
                                        $userMostMessages = $userWithMostMessages[0][0];
                                        $nbOfMessages = $userWithMostMessages[0][1]
                                    ?>
									<h5>Utilisateur avec le + de messages</h5>
								</div>
								<p><img src="assets/img/ui-zac.jpg" class="img-circle" width="80"></p>
								<p><b><?php echo($userMostMessages); ?></b></p>
								<div class="row">
									<div class="col-md-12">
										<p class="small mt">Nb. de messages</p>
										<p><?php echo($nbOfMessages); ?></p>
									</div>
								</div>
							</div>
						</div><!-- /col-md-4 -->
                      	

                    </div><!-- /row -->
                  </div><!-- /col-lg-9 END SECTION MIDDLE -->
                  
                  
      <!-- **********************************************************************************************************************************************************
      RIGHT SIDEBAR CONTENT
      *********************************************************************************************************************************************************** -->                  
                  
                  <div class="col-lg-3 ds">
                    <!--COMPLETED ACTIONS DONUTS CHART-->
						<h3>Derniers messages</h3>
                                        
                      <?php
                        $allMessages = $class->getRecentMessages();
                        foreach($allMessages as $row)
                        {
                            if($row['mesTimePassage'] < $timeNow = date("Y-m-d H:i:s"))
                            {
                                $time = $class->humanTiming(strtotime($row['mesTimePassage']));

                                echo '
                                <div class="desc">
                                    <div class="thumb">
                                        <span class="badge bg-theme"><i class="fa fa-clock-o"></i></span>
                                    </div>
                                    <div class="details">
                                        <p><muted>Il y a ' . $time . ' par '.$row['useUsername'].'</muted><br/>
                                           ' . $row['mesMessage'] . '<br/>
                                        </p>
                                    </div>
                                </div>
                            ';

                            }
                        }
                      ?>

                  </div><!-- /col-lg-3 -->
              </div><! --/row -->
          </section>
      </section>

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

    <!--script for this page-->
    <script src="assets/js/sparkline-chart.js"></script>    
	<script src="assets/js/zabuto_calendar.js"></script>	
	
	<script type="text/javascript">

	</script>
	
	<script type="application/javascript">
        $(document).ready(function () {
            $("#date-popover").popover({html: true, trigger: "manual"});
            $("#date-popover").hide();
            $("#date-popover").click(function (e) {
                $(this).hide();
            });
        
            $("#my-calendar").zabuto_calendar({
                action: function () {
                    return myDateFunction(this.id, false);
                },
                action_nav: function () {
                    return myNavFunction(this.id);
                },
                ajax: {
                    url: "show_data.php?action=1",
                    modal: true
                },
                legend: [
                    {type: "text", label: "Special event", badge: "00"},
                    {type: "block", label: "Regular event", }
                ]
            });
        });
        
        
        function myNavFunction(id) {
            $("#date-popover").hide();
            var nav = $("#" + id).data("navigation");
            var to = $("#" + id).data("to");
            console.log('nav ' + nav + ' to: ' + to.month + '/' + to.year);
        }
    </script>
  

  </body>
</html>
