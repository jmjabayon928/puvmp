<?php

include("db.php");
include("sqli.php");
include("html.php");
define('PAGE', 'd_pmo_report_efile.php'); 

session_start();

if (isset($_SESSION['user_id'])) {
	if (validate_level(PAGE) == true) {
		
		if(isset($_POST['submitted'])) { // if submit button has been pressed
			$rfid = clean_input($_POST['rfid']);

			// check if rfid is given
			if ($rfid != 0) { $a = TRUE; } 
			else { $a = FALSE; $message[] = "Please select from the list of attachments."; }
			
			// If data pass all tests, proceed
			if ( $a ) {
				// get the rid from rfid 
				$sql = "SELECT rid, rfile FROM pmo_reports_efiles WHERE rfid = '$rfid'";
				$rres = query($sql); $rrow = fetch_array($rres); $rid = $rrow[0]; 
				
				if (unlink($rrow[1])) {
					$sql = "DELETE FROM pmo_reports_efiles WHERE rfid = '$rfid' LIMIT 1";
					if (query($sql)) {
						// log the activity
						log_user(3, 'pmo_reports_efiles', $rid);
						
						header("Location: pmo_report.php?rid=".$rid);
						exit();
					} else {
						?>
						<div class="error">Could not delete file because: <b><?PHP echo mysqli_error($connection) ?></b>.
							<br />The query was <?PHP echo $sql ?>.</div><br />
						<?PHP
					}
				} else {
					?>
					<div class="alert alert-danger alert-dismissible fade in" role="alert">
						<strong>Failed to delete file! </strong><br /><br />
						<a href="pmo_report_gallery.php?rid=<?php echo $rid ?>">Click here to go back to gallery</a>
					</div>
					<?php
				}
				
			} else {
				$serialized_message = serialize($message);
				// redirect the user to index page
				header('location: d_pmo_report_efile.php?serialized_message='.$serialized_message.'&rfid='.$rfid);
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
				  
				<title>OTC | Delete Report Attachment</title>

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
							<h3>Delete Report Attachment</h3>
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
						  if (isset($_GET['serialized_message'])) {
							  $message = unserialize($_GET['serialized_message']);
							  ?>
							  <div class="col-md-12 col-sm-12 col-xs-12">
								<div class="x_panel">
								  <div class="x_content">
									  <div class="alert alert-danger alert-dismissible fade in" role="alert">
										<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
										</button>
										<strong>The following problems occurred: </strong><br />
										<?php
										foreach ($message as $key => $value) {
											echo $value; echo '<br />';
										}
										?>
									  </div>
								  </div>
								</div>
							  </div>
							  <div class="clearfix"></div>
							  <?php
						  }
						  
						  $rfid = $_GET['rfid'];
						  
						  ?>
						  <div class="col-md-12 col-sm-12 col-xs-12">
							<div class="x_panel">
							  <div class="x_content">
								<br />
								
									<?php
									// check if the logged in user is the owner of the report
									$sql = "SELECT u.user_id 
											FROM pmo_reports_efiles re 
												INNER JOIN pmo_reports r ON re.rid = r.rid 
												INNER JOIN employees e ON r.eid = e.eid 
												INNER JOIN users u ON u.eid = e.eid 
											WHERE re.rfid = '$rfid'";
									$res = query($sql); 
									$row = fetch_array($res); 
									$user_id = $row[0]; 
									
									if ($user_id == $_SESSION['user_id']) {
										show_efile_form($rfid); 
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


function show_efile_form($rfid) {
	$sql = "SELECT rfile FROM pmo_reports_efiles WHERE rfid = '$rfid'";
	$res = query($sql); $row = fetch_array($res);
	?>
	<form action="d_pmo_report_efile.php" method="POST" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
	  <div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="eid">Attached File <span class="required">*</span></label>
		<div class="col-md-6 col-sm-6 col-xs-12">
		  <img src="<?php echo $row[0] ?>">
		</div>
	  </div>
	  <div class="ln_solid"></div>
	  <div class="form-group">
		<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
		  <input type="hidden" name="submitted" value="1">
		  <input type="hidden" name="rfid" value="<?php echo $rfid ?>">
		  <button class="btn btn-primary" type="reset">Reset</button>
		  <button type="submit" class="btn btn-success">Submit</button>
		</div>
	  </div>

	</form>
	<?php
}