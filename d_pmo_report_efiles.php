<?php

include("db.php");
include("sqli.php");
include("html.php");
define('PAGE', 'd_pmo_report_efiles.php'); 

session_start();

if (isset($_SESSION['user_id'])) {
	if (validate_level(PAGE) == true) {

		if(isset($_POST['submitted'])) { // if submit button has been pressed
			$rid = filter_var($_POST['rid'], FILTER_SANITIZE_NUMBER_INT);
			
			// check if rid is given
			if ($rid != 0) { $a = TRUE; } 
			else { $a = FALSE; $message[] = "Please select from the list of pmo reports."; }
			
			// If data pass all tests, proceed
			if ( $a ) {
				// get all attachments
				$sql = "SELECT rfid, rfile FROM pmo_reports_efiles WHERE rid = '$rid'";
				$res = query($sql); 
				while ($row = fetch_array($res)) {
					unlink($row[1]);
					$sql = "DELETE FROM pmo_reports_efiles WHERE rfid = '$row[0]' LIMIT 1";
					query($sql); 
					
				}
				free_result($res); 
				
				header("Location: pmo_report.php?rid=".$rid);
				exit();
				
			} else {
				$serialized_message = serialize($message);
				// redirect the user to index page
				header('location: d_pmo_report_efiles.php?serialized_message='.$serialized_message.'&rid='.$rid);
				exit();
			}
		} else {
			?>
			<!DOCTYPE html>
			<html lang="en">
			  <head>
				<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
				<!-- Meta, title, CSS, favicons, etc. -->
				<meta charset="utf-8">
				<meta http-equiv="X-UA-Compatible" content="IE=edge">
				<meta name="viewport" content="width=device-width, initial-scale=1">
				  
				<title>OTC | Delete Attached Files</title>

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
					
					$rid = $_GET['rid'];

					?>

					<!-- page content -->
					<div class="right_col" role="main">
					  <div class="">
						<div class="page-title">
						  <div class="title_left">
							<h3>Delete Attached Files</h3>
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
						<div class="row"> <!-- container of all tables -->
						
						
						  <div class="col-md-12">
							<div class="x_panel">
							  <div class="x_title">
								<h2>Attached Files</h2>
								<div class="clearfix"></div>
							  </div>
							  <div class="x_content">

								<div class="row">

									<?php
									
									// check if the logged in user is the owner of the report
									$sql = "SELECT u.user_id 
											FROM pmo_reports r 
												INNER JOIN employees e ON r.eid = e.eid 
												INNER JOIN users u ON u.eid = e.eid 
											WHERE r.rid = '$rid'";
									$res = query($sql); 
									$row = fetch_array($res); 
									$user_id = $row[0]; 
									
									if ($user_id == $_SESSION['user_id']) {
										show_efile_form($rid); 
									} else {
										?>
										  <div class="col-md-12 col-sm-12 col-xs-12">
											<div class="x_panel">
											  <div class="x_title">
												<h2></h2>
												<div class="clearfix"></div>
											  </div>
											  <div class="x_content">
											  <div class="bs-example" data-example-id="simple-jumbotron">
												<div class="jumbotron">
												  <h1>Access Denied!</h1>
												  <p>You can only delete attachments in your own accomplishment reports</p>
												</div>
											  </div>
											  </div>
											</div>
										  </div>
										<?php
									}
									
									?>

								</div>
							  </div>
							</div>
						  </div>


						  
						</div><!-- end of container -->

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
	}
} else {
	header('Location: login.php');	
}


function show_efile_form($rid) {
	$sql = "SELECT rfid, rfile FROM pmo_reports_efiles WHERE rid = '$rid'";
	$res = query($sql); 
	while($row = fetch_array($res)) {
		?>
	  <div class="col-md-55">
		<div class="thumbnail">
		  <div class="image view view-first">
			<img style="width: 100%; display: block;" src="<?php echo $row[1] ?>" alt="image" />
		  </div>
		</div>
	  </div>
		<?php
	}
	?>

	<form action="d_pmo_report_efiles.php" method="POST">
	  <div class="ln_solid"></div>
	  <div class="form-group">
		<div align="center">
		  <input type="hidden" name="submitted" value="1">
		  <input type="hidden" name="rid" value="<?php echo $rid ?>">
		  <button type="submit" class="btn btn-success">Delete Files</button>
		</div>
	  </div>
	</form>
	<?php
}