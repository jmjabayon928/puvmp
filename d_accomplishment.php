<?php

include("db.php");
include("sqli.php");
include("html.php");
define('PAGE', 'd_accomplishment.php'); 

session_start();

if (isset($_SESSION['user_id'])) {
	if (validate_level(PAGE) == true) {

		if(isset($_POST['submitted'])) { // if submit button has been pressed
			$prid = clean_input($_POST['prid']);
			
			// get the rid from prid
			$sql = "SELECT rid FROM pmo_reports_details WHERE prid = '$prid'";
			$rres = query($sql); $rrow = fetch_array($rres); $rid = $rrow[0]; 
			
			$sql = "DELETE FROM pmo_reports_details
					WHERE prid = '$prid'
					LIMIT 1";
			if (query($sql)) {
				// log the activity
				log_user(3, 'pmo_reports_details', $prid);
				
				// update the report
				$sql = "UPDATE pmo_reports 
						SET user_id = '$_SESSION[user_id]',
							user_dtime = NOW()
						WHERE rid = '$rid'
						LIMIT 1";
				query($sql); 
				
				header("Location: pmo_report.php?rid=".$rid);
				exit();
			} else {
				?>
				<div class="error">Could not delete accomplishment because: <b><?PHP echo mysqli_error($connection) ?></b>.
					<br />The query was <?PHP echo $sql ?>.</div><br />
				<?PHP
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
				  
				<title>OTC | Delete Accomplishemnt</title>

				<!-- Bootstrap -->
				<link href="vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
				<!-- Font Awesome -->
				<link href="vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">

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
						
						$prid = $_GET['prid'];
						
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
							<h3>Delete Accomplishemnt</h3>
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
								<br />
								<?php
								// check if the logged in user is the owner of the report
								$sql = "SELECT u.user_id 
										FROM pmo_reports_details rd 
											INNER JOIN pmo_reports r ON rd.rid = r.rid 
											INNER JOIN employees e ON r.eid = e.eid 
											INNER JOIN users u ON u.eid = e.eid 
										WHERE rd.prid = '$prid'";
								$res = query($sql); 
								$row = fetch_array($res); 
								$user_id = $row[0]; 
								
								if ($user_id == $_SESSION['user_id']) {
									show_accomplishment_form($prid); 
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
											  <p>You can only delete your own accomplishment reports</p>
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
			<?PHP
		}
	}
} else {
	header('Location: login.php');	
}

function show_accomplishment_form($prid) {
	$sql = "SELECT * FROM pmo_reports_details WHERE prid = '$prid'";
	$res = query($sql); 
	$row = fetch_array($res); 
	
	if ($row[2] == 1) { $category = 'PUVMP-PMO Activities';
	} elseif ($row[2] == 2) { $category = 'Field Works, Trainings, Events and Meetings';
	}
	?>
	<form action="d_accomplishment.php" method="POST" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
	  <div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="category">Report Category </label>
		<div class="col-md-6 col-sm-6 col-xs-12">
		  <input type="text" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $category ?>">
		</div>
	  </div>
	  <div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="rdetails">Activity Details</label>
		<div class="col-md-6 col-sm-6 col-xs-12">
		  <textarea class="resizable_textarea form-control" readonly id="rdetails" name="rdetails"><?php echo $row[3] ?></textarea>
		</div>
	  </div>
	  <div class="ln_solid"></div>
	  <div class="form-group">
		<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
		  <input type="hidden" name="submitted" value="1">
		  <input type="hidden" name="prid" value="<?php echo $prid ?>">
		  <button class="btn btn-primary" type="reset">Reset</button>
		  <button type="submit" class="btn btn-success">Submit</button>
		</div>
	  </div>

	</form>
	<?php
}