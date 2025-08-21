<?php

include("db.php");
include("sqli.php");
include("html.php");

session_start();

if (isset($_SESSION['user_id'])) {
	if (validate_user() == true) {
		?>
		<!DOCTYPE html>
		<html lang="en">
		  <head>
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
			<!-- Meta, title, CSS, favicons, etc. -->
			<meta charset="utf-8">
			<meta http-equiv="X-UA-Compatible" content="IE=edge">
			<meta name="viewport" content="width=device-width, initial-scale=1">

			<title>OTC - Home</title>

			<!-- Bootstrap -->
			<link href="vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
			<!-- Font Awesome -->
			<link href="vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
			<!-- NProgress -->
			<link href="vendors/nprogress/nprogress.css" rel="stylesheet">
			<!-- iCheck -->
			<link href="vendors/iCheck/skins/flat/green.css" rel="stylesheet">
			
			<!-- bootstrap-progressbar -->
			<link href="vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet">
			<!-- JQVMap -->
			<link href="vendors/jqvmap/dist/jqvmap.min.css" rel="stylesheet"/>
			<!-- bootstrap-daterangepicker -->
			<link href="vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">

			<!-- Custom Theme Style -->
			<link href="build/css/custom.min.css" rel="stylesheet">
		  </head>

		  <body class="nav-md">
			<div class="container body">
			  <div class="main_container">
				<div class="col-md-3 left_col">
				  <div class="left_col scroll-view">
					<div class="navbar nav_title" style="border: 0;">
					  <?PHP site_title(); ?>
					</div>

					<div class="clearfix"></div>

					<?php
					profile_quick_info();
					?>
					
					<br />

					<?php
					sidebar_menu();
					?>
					
				  </div>
				</div>

				<?php
				top_navigation();
				?>
				
				<!-- page content -->
				<div class="right_col" role="main">
				  <div class="">
					<div class="page-title">
					  <div class="title_left">
						<h3>Quote of the Day</h3>
					  </div>

					  <div class="title_right">
						<div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
						  <div class="input-group">
							<input type="text" class="form-control" placeholder="Search for...">
							<span class="input-group-btn">
							  <button class="btn btn-default" type="button">Go!</button>
							</span>
						  </div>
						</div>
					  </div>
					</div>

					<div class="clearfix"></div>
					<div class="row">
					  <div class="col-md-12 col-sm-12 col-xs-12">
						<div class="x_panel">
							<div class="x_content">
							  <div class="bs-example" data-example-id="simple-jumbotron">
									<blockquote class="blockquote">
									  <?php
									  $qid = rand(1, 100);
									  $sql = "SELECT quote, author FROM quotes WHERE qid = '$qid'";
									  $qres = query($sql); 
									  $qrow = fetch_array($qres); 
									  free_result($qres); 
									  ?>
									  <p class="h1"><?php echo $qrow[0] ?></p>
									  <footer class="blockquote-footer"><em><?php echo $qrow[1] ?></em></footer>
									</blockquote>
							  </div>
							</div>
						</div>
					  </div>
					  <div class="clearfix"></div>
					  <div class="col-md-12 col-sm-12 col-xs-12">
						<div class="x_panel">
							<div class="x_content">
							  <div class="bs-example" data-example-id="simple-jumbotron">
								<div class="jumbotron">
								  <h1>Mission</h1>
								  <p>To facilitate the transformation of Transport Cooperatives to become highly competitive transport and business organization through genuine cooperative operations.</p>
								</div>
							  </div>
							  <div class="bs-example" data-example-id="simple-jumbotron">
								<div class="jumbotron">
								  <h1>Vision</h1>
								  <p>By 2040, transport cooperatives shall enjoy sustainable income and improved quality of life for its members.<br /><br />In 2040, the Office of Transportation Cooperatives shall be a dynamic and responsive government agency providing guidance and sound management, for transport cooperatives to experience increased and sustainable income and social benefits, through the effective and efficient provision of integrated public transport service systems.</p>
								</div>
							  </div>
							  <div class="bs-example" data-example-id="simple-jumbotron">
								<div class="jumbotron">
								  <h1>Mandate</h1>
								  <p>The Office of Transportation Cooperatives (OTC) was created through Executive Order No. 898, signed on May 28, 1983. It is mandated to promulgate and implement rules and regulations that will govern the promotion, organization, registration (accreditation), regulation, supervision and development of  transportation cooperatives, subject to the approval of the Department of Transportation and Communications.</p>
								</div>
							  </div>
							</div>
						</div>
					  </div>
					</div>
				  </div>
				</div>
				<!-- /page content -->

				<?php
				footer();
				?>
			  </div>
			</div>

			<!-- jQuery -->
			<script src="vendors/jquery/dist/jquery.min.js"></script>
			<!-- Bootstrap -->
			<script src="vendors/bootstrap/dist/js/bootstrap.min.js"></script>
			<!-- FastClick -->
			<script src="vendors/fastclick/lib/fastclick.js"></script>
			<!-- NProgress -->
			<script src="vendors/nprogress/nprogress.js"></script>
			<!-- Chart.js -->
			<script src="vendors/Chart.js/dist/Chart.min.js"></script>
			<!-- gauge.js -->
			<script src="vendors/gauge.js/dist/gauge.min.js"></script>
			<!-- bootstrap-progressbar -->
			<script src="vendors/bootstrap-progressbar/bootstrap-progressbar.min.js"></script>
			<!-- iCheck -->
			<script src="vendors/iCheck/icheck.min.js"></script>
			<!-- Skycons -->
			<script src="vendors/skycons/skycons.js"></script>
			<!-- Flot -->
			<script src="vendors/Flot/jquery.flot.js"></script>
			<script src="vendors/Flot/jquery.flot.pie.js"></script>
			<script src="vendors/Flot/jquery.flot.time.js"></script>
			<script src="vendors/Flot/jquery.flot.stack.js"></script>
			<script src="vendors/Flot/jquery.flot.resize.js"></script>
			<!-- Flot plugins -->
			<script src="vendors/flot.orderbars/js/jquery.flot.orderBars.js"></script>
			<script src="vendors/flot-spline/js/jquery.flot.spline.min.js"></script>
			<script src="vendors/flot.curvedlines/curvedLines.js"></script>
			<!-- DateJS -->
			<script src="vendors/DateJS/build/date.js"></script>
			<!-- JQVMap -->
			<script src="vendors/jqvmap/dist/jquery.vmap.js"></script>
			<script src="vendors/jqvmap/dist/maps/jquery.vmap.world.js"></script>
			<script src="vendors/jqvmap/examples/js/jquery.vmap.sampledata.js"></script>
			<!-- bootstrap-daterangepicker -->
			<script src="vendors/moment/min/moment.min.js"></script>
			<script src="vendors/bootstrap-daterangepicker/daterangepicker.js"></script>

			<!-- Custom Theme Scripts -->
			<script src="build/js/custom.min.js"></script>
			
		  </body>
		</html>
		<?php
	}
} else {
	header('Location: login.php');	
}
