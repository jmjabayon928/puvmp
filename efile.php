<?php

include("db.php");
include("sqli.php");
include("html.php");
define('PAGE', 'efile.php'); 

$eid = $_GET['eid'];

session_start();

if (isset($_SESSION['user_id'])) {
	if (validate_level(PAGE) == true) {

		// log the activity
		log_user(5, 'efiles', $eid);
		
		?>
		<!DOCTYPE html>
		<html lang="en">
		  <head>
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
			<!-- Meta, title, CSS, favicons, etc. -->
			<meta charset="utf-8">
			<meta http-equiv="X-UA-Compatible" content="IE=edge">
			<meta name="viewport" content="width=device-width, initial-scale=1">

			<title>OTC - Electronic File Details</title>

			<!-- Bootstrap -->
			<link href="vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
			<!-- Font Awesome -->
			<link href="vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">

			<!-- Custom Theme Style -->
			<link href="build/css/custom.min.css" rel="stylesheet">
			<!-- Custom Theme Style -->
			<link href="css/baguetteBox.min.css" rel="stylesheet">
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
						<h3>Electronic File Details</h3>
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
					
					  <?php
					  if (isset($_GET['success'])) {
						  if ($_GET['success'] == 1) {
							  ?>
								<div class="alert alert-success">
								  <strong>Success!</strong> Electronic file has been added into the database.
								</div>
							  <?php
						  }
					  }
					  
					  $sql = "SELECT etitle, edesc FROM efiles WHERE eid = '$eid'";
					  $eres = query($sql); 
					  $erow = fetch_array($eres); 
					  
					  ?>

					  <div class="col-md-12 col-sm-12 col-xs-12">
						<div class="x_panel">
						  <div class="x_title">
							<h2><?php echo $erow[0] ?></h2>
							<ul class="nav navbar-right panel_toolbox">
								<a href="e_efile.php?eid=<?php echo $eid ?>" class="btn btn-warning btn-md"><i class="fa fa-edit"></i> Edit File </a>
								<a href="add_efile.php?eid=<?php echo $eid ?>" class="btn btn-success btn-md"><i class="fa fa-plus-square"></i> Attach More </a>
							</ul>
							<div class="clearfix"></div>
						  </div>
						  <div class="x_content">
						  
									<h3><?php echo $erow[1] ?></h3>

									
									<div class="tz-gallery">

										<div class="row">
											<?php
											$sql = "SELECT efile from efiles_files WHERE eid = '$eid'";
											$res = query($sql); 
											while($row = fetch_array($res)) {
												?>
												<div class="col-sm-6 col-md-4">
													<a class="lightbox" href="<?php echo $row[0] ?>">
														<img src="<?php echo $row[0] ?>" height="100%" width="100%">
													</a>
												</div>
												<?php
											}
											?>
										</div>

									</div>

								<script src="js/baguetteBox.min.js"></script>
								<script>
									baguetteBox.run('.tz-gallery');
								</script>
						  
						  
						  
						  
						  
						  
						  
						  
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

			<!-- Custom Theme Scripts -->
			<script src="build/js/custom.min.js"></script>

		  </body>
		</html>		
		<?php
	}
} else {
	header('Location: login.php');	
}
