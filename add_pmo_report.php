<?php

include("db.php");
include("sqli.php");
include("html.php");
define('PAGE', 'add_pmo_report.php'); 

session_start();

if (isset($_SESSION['user_id'])) {
	if (validate_level(PAGE) == true) {
		
		if(isset($_POST['submitted'])) { // if submit button has been pressed
			$eid = clean_input($_POST['eid']);
			$rstart = clean_input($_POST['rstart']);
			$rend = clean_input($_POST['rend']);

			$rdetails1 = clean_input($_POST['rdetails1']);
			$rdetails2 = clean_input($_POST['rdetails2']);
			$rdetails3 = clean_input($_POST['rdetails3']);
			$rdetails4 = clean_input($_POST['rdetails4']);
			$rdetails5 = clean_input($_POST['rdetails5']);
		
			$rrdetails1 = clean_input($_POST['rrdetails1']);
			$rrdetails2 = clean_input($_POST['rrdetails2']);
			$rrdetails3 = clean_input($_POST['rrdetails3']);
			$rrdetails4 = clean_input($_POST['rrdetails4']);
			$rrdetails5 = clean_input($_POST['rrdetails5']);

			// check if eid is given
			if ($eid != 0) { $a = TRUE; } 
			else { $a = FALSE; $message[] = "Please select from the list of employees."; }
			// check if rstart is given
			if ($rstart != '') { $b = TRUE; } 
			else { $b = FALSE; $message[] = "Please enter the start date of coverage."; }
			// check if rend is given
			if ($rend != '') { $c = TRUE; } 
			else { $c = FALSE; $message[] = "Please enter the end date of coverage."; }
			// check if rdetails1 is given
			if ($rdetails1 != '') { $d = TRUE; } 
			else { $d = FALSE; $message[] = "Please enter at least one PUVMP-PMO activity."; }
			// check if rrdetails1 is given
			if ($rrdetails1 != '') { $e = TRUE; } 
			else { $e = FALSE; $message[] = "Please enter at least one field work / training / event  or meeting."; }
			
			// If data pass all tests, proceed
			if ( $a && $b && $c && $d && $e ) {
				// check for double entry first
				$sql = "SELECT rid
						FROM pmo_reports
						WHERE eid = '$eid' AND rstart = '$rstart' AND rend = '$rend'";
				$res = query($sql); 
				$num = num_rows($res); 
				
				if ($num > 0) {
					$row = fetch_array($res); 
					?>
					<div class="alert alert-danger alert-dismissible fade in" role="alert">
						<strong>Record already exists! </strong><br /><br />
						<a href="pmo_report.php?rid=<?php echo $row[0] ?>">Click here to view the records</a>
					</div>
					<?php
				} else {
					$sql = "INSERT INTO pmo_reports( eid, rstart, rend, user_id, user_dtime )
							VALUES( '$eid', '$rstart', '$rend', '$_SESSION[user_id]', NOW() )";
					if (query($sql)) {
						$rid = mysqli_insert_id($connection);
						// log the activity
						log_user(1, 'pmo_reports', $rid);
						
						if ($rdetails1 != '') {
							$sql = "INSERT INTO pmo_reports_details (rid, cid, rdetails, user_id, user_dtime) VALUES('$rid', 1, '$rdetails1', '$_SESSION[user_id]', NOW())";
							query($sql); 
						}
						if ($rdetails2 != '') {
							$sql = "INSERT INTO pmo_reports_details (rid, cid, rdetails, user_id, user_dtime) VALUES('$rid', 1, '$rdetails2', '$_SESSION[user_id]', NOW())";
							query($sql); 
						}
						if ($rdetails3 != '') {
							$sql = "INSERT INTO pmo_reports_details (rid, cid, rdetails, user_id, user_dtime) VALUES('$rid', 1, '$rdetails3', '$_SESSION[user_id]', NOW())";
							query($sql); 
						}
						if ($rdetails4 != '') {
							$sql = "INSERT INTO pmo_reports_details (rid, cid, rdetails, user_id, user_dtime) VALUES('$rid', 1, '$rdetails4', '$_SESSION[user_id]', NOW())";
							query($sql); 
						}
						if ($rdetails5 != '') {
							$sql = "INSERT INTO pmo_reports_details (rid, cid, rdetails, user_id, user_dtime) VALUES('$rid', 1, '$rdetails5', '$_SESSION[user_id]', NOW())";
							query($sql); 
						}
						
						if ($rrdetails1 != '') {
							$sql = "INSERT INTO pmo_reports_details (rid, cid, rdetails, user_id, user_dtime) VALUES('$rid', 2, '$rrdetails1', '$_SESSION[user_id]', NOW())";
							query($sql); 
						}
						if ($rrdetails2 != '') {
							$sql = "INSERT INTO pmo_reports_details (rid, cid, rdetails, user_id, user_dtime) VALUES('$rid', 2, '$rrdetails2', '$_SESSION[user_id]', NOW())";
							query($sql); 
						}
						if ($rrdetails3 != '') {
							$sql = "INSERT INTO pmo_reports_details (rid, cid, rdetails, user_id, user_dtime) VALUES('$rid', 2, '$rrdetails3', '$_SESSION[user_id]', NOW())";
							query($sql); 
						}
						if ($rrdetails4 != '') {
							$sql = "INSERT INTO pmo_reports_details (rid, cid, rdetails, user_id, user_dtime) VALUES('$rid', 2, '$rrdetails4', '$_SESSION[user_id]', NOW())";
							query($sql); 
						}
						if ($rrdetails5 != '') {
							$sql = "INSERT INTO pmo_reports_details (rid, cid, rdetails, user_id, user_dtime) VALUES('$rid', 2, '$rrdetails5', '$_SESSION[user_id]', NOW())";
							query($sql); 
						}
						
						header("Location: pmo_report.php?rid=".$rid);
						exit();
					} else {
						?>
						<div class="alert alert-danger alert-dismissible fade in" role="alert">
							<strong>Could not add PMO report because: </strong><br />
							<b><?php echo mysqli_error($connection) ?></b><br />
							The query was <?php echo $sql ?>.<br />
						</div>
						<?PHP
					}
				}
			} else {
				$serialized_message = serialize($message);
				// redirect the user to index page
				header('location: add_pmo_report.php?serialized_message='.$serialized_message.'&eid='.$eid.'&rstart='.$rstart.'&rend='.$rend);
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
				  
				<title>OTC | Add Accomplishment Report</title>

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
							<h3>Add Accomplishment Report</h3>
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
						  
						  ?>
						  <div class="col-md-12 col-sm-12 col-xs-12">
							<div class="x_panel">
							  <div class="x_content">
								<br />
								
									<form action="add_pmo_report.php" method="POST" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="eid">Employee <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <select id="eid" name="eid" required="required" class="form-control col-md-7 col-xs-12">
											<option value="0">--- Select ---</option>
											<?php
											$sql = "SELECT eid, CONCAT(lname,', ',fname) FROM employees WHERE active = 1 AND oid != 0 ORDER BY lname, fname";
											$eres = query($sql); 
											while ($erow = fetch_array($eres)) {
												if (isset ($_GET['eid'])) {
													if ($_GET['eid'] == $erow[0]) {
														?><option value="<?php echo $erow[0] ?>" Selected><?php echo $erow[1] ?></option><?php
													} else {
														?><option value="<?php echo $erow[0] ?>"><?php echo $erow[1] ?></option><?php
													}
												} else {
													?><option value="<?php echo $erow[0] ?>"><?php echo $erow[1] ?></option><?php
												}
											}
											free_result($eres); 
											?>
										  </select>
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="rstart">Coverage Start <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="date" id="rstart" name="rstart" required="required" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['rstart'])) {
											  ?>value="<?php echo $_GET['rstart'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="rend">Coverage End <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="date" id="rend" name="rend" required="required" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['rend'])) {
											  ?>value="<?php echo $_GET['rend'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="ln_solid"></div>
									  <h2>A. PUVMP-PMO ACTIVITIES</h2>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="rdetails1">Activity Entry <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <textarea class="resizable_textarea form-control" id="rdetails1" name="rdetails1" required="required"></textarea>
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="rdetails2">Activity Entry </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <textarea class="resizable_textarea form-control" id="rdetails2" name="rdetails2"></textarea>
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="rdetails3">Activity Entry </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <textarea class="resizable_textarea form-control" id="rdetails3" name="rdetails3"></textarea>
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="rdetails4">Activity Entry </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <textarea class="resizable_textarea form-control" id="rdetails4" name="rdetails4"></textarea>
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="rdetails5">Activity Entry </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <textarea class="resizable_textarea form-control" id="rdetails5" name="rdetails5"></textarea>
										</div>
									  </div>
									  <div class="ln_solid"></div>
									  <h2>B. FIELD WORKS, TRAININGS, EVENTS AND MEETINGS</h2>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="rrdetails1">Event Entry <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <textarea class="resizable_textarea form-control" id="rrdetails1" name="rrdetails1" required="required"></textarea>
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="rrdetails2">Event Entry </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <textarea class="resizable_textarea form-control" id="rrdetails2" name="rrdetails2"></textarea>
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="rrdetails3">Event Entry </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <textarea class="resizable_textarea form-control" id="rrdetails3" name="rrdetails3"></textarea>
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="rrdetails4">Event Entry </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <textarea class="resizable_textarea form-control" id="rrdetails4" name="rrdetails4"></textarea>
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="rrdetails5">Event Entry </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <textarea class="resizable_textarea form-control" id="rrdetails5" name="rrdetails5"></textarea>
										</div>
									  </div>
									  <div class="ln_solid"></div>
									  <div class="form-group">
										<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
										  <input type="hidden" name="submitted" value="1">
										  <button class="btn btn-primary" type="reset">Reset</button>
										  <button type="submit" class="btn btn-success">Submit</button>
										</div>
									  </div>

									</form>
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

