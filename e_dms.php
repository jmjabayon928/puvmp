<?php

include("db.php");
include("sqli.php");
include("html.php");
define('PAGE', 'e_dms.php'); 

session_start();

if (isset($_SESSION['user_id'])) {
	if (validate_level(PAGE) == true) {

		if(isset($_POST['submitted'])) { // if submit button has been pressed

			$did = filter_var($_POST['did'], FILTER_SANITIZE_NUMBER_INT);
			$dnum = filter_var($_POST['dnum'], FILTER_SANITIZE_STRING);
			$fr_time = filter_var($_POST['fr_time'], FILTER_SANITIZE_STRING);
			$to_time = filter_var($_POST['to_time'], FILTER_SANITIZE_STRING);
			$ddate = filter_var($_POST['ddate'], FILTER_SANITIZE_STRING);
			$destination = filter_var($_POST['destination'], FILTER_SANITIZE_STRING);
			$purpose = filter_var($_POST['purpose'], FILTER_SANITIZE_STRING);
			$eid = filter_var($_POST['eid'], FILTER_SANITIZE_NUMBER_INT);
			
			// check if dnum is given
			if ($dnum != '') { $a = TRUE; } 
			else { $a = FALSE; $message[] = "Please enter the control number."; }
			// check if eid is given
			if ($eid != 0) { $b = TRUE; } 
			else { $b = FALSE; $message[] = "Please select from the list of employees."; }
			// check if fr_time is given
			if ($fr_time != '') { $c = TRUE; } 
			else { $c = FALSE; $message[] = "Please enter the start time of DMS."; }
			// check if to_time is given
			if ($to_time != '') { $d = TRUE; } 
			else { $d = FALSE; $message[] = "Please enter the end time of DMS."; }
			// check if ddate is given
			if ($ddate != '') { $e = TRUE; } 
			else { $e = FALSE; $message[] = "Please enter the date of DMS."; }
			// check if destination is given
			if ($destination != '') { $f = TRUE; } 
			else { $f = FALSE; $message[] = "Please enter the destination DMS."; }
			// check if purpose is given
			if ($purpose != '') { $g = TRUE; } 
			else { $g = FALSE; $message[] = "Please enter the purpose of DMS."; }
			
			
			// If data pass all tests, proceed
			if ( $a && $b && $c && $d && $e && $f && $g ) {
				// get date and employee of the dms
				$sql = "SELECT ddate, eid FROM internals_dms WHERE did = '$did'";
				$res = query($sql); $row = fetch_array($res); 
				$old_ddate = $row[0]; $old_eid = $row[1]; 
				
				// update data
				$sql = "UPDATE internals_dms
						SET dnum = '$dnum',
							eid = '$eid',
							fr_time = '$fr_time',
							to_time = '$to_time',
							ddate = '$ddate',
							destination = '$destination', 
							purpose = '$purpose' 
						WHERE did = '$did'
						LIMIT 1";
				if (query($sql)) {
					// log the activity
					log_user(2, 'internals_dms', $did);
					
					// if dates are different
					if ($old_ddate != $ddate && $old_eid == $eid) {
						// remove the attendance with old date
						// search for an attendance where eid = $eid and ddate = $old_ddate
						$sql = "SELECT aid, am_in, am_out, pm_in, pm_out
								FROM attendance
								WHERE ddate = '$old_ddate' AND eid = '$eid'";
						$ares = query($sql); $anum = num_rows($ares); 
						
						if ($anem > 0) {
							$arow = fetch_array($ares); 
							$aid = $arow[0]; $am_in = $arow[1]; $am_out = $arow[2]; $pm_in = $arow[3]; $pm_out = $arow[4]; 
							
							if ($am_in == '00:00:00' && $am_in == '00:00:00' && $am_in == '00:00:00' && $am_in == '00:00:00') { // no time in or out
								// simply delete the attendance
								$sql = "DELETE FROM attendance WHERE aid = '$aid' LIMIT 1";
								query($sql); 
							} elseif ($am_in == '00:00:00' && $am_in == '00:00:00' && $am_in == '00:00:00' && $am_in == '00:00:00') { // complete attendance
								// update attendance
								$sql = "UPDATE attendance SET status = 1, remarks = '' WHERE aid = '$aid' LIMIT 1";
								query($sql); 
								update_attendance($aid); 
							} else { // atleast one time in/out
								// do not delete attendance, just update
								$sql = "UPDATE attendance SET status = -1, remarks = '' WHERE aid = '$aid' LIMIT 1";
								query($sql); 
								update_attendance($aid); 
							}
						}
						
						// insert into attendance the new date
						$sql = "INSERT INTO attendance(eid, adate, status, remarks, last_update_id, last_update)
								VALUES('$eid', '$ddate', '13', 'DMS ".$dnum."', '$_SESSION[user_id]', NOW())";
						query($sql); 
					}
					
					// if employees are different
					if ($old_eid != $eid && $old_ddate == $ddate) {
						// remove the attendance with old eid
						// search for an attendance where eid = $old_eid and ddate = $ddate
						$sql = "SELECT aid, am_in, am_out, pm_in, pm_out
								FROM attendance
								WHERE ddate = '$ddate' AND eid = '$old_eid'";
						$ares = query($sql); $anum = num_rows($ares); 
						
						if ($anem > 0) {
							$arow = fetch_array($ares); 
							$aid = $arow[0]; $am_in = $arow[1]; $am_out = $arow[2]; $pm_in = $arow[3]; $pm_out = $arow[4]; 
							
							if ($am_in == '00:00:00' && $am_in == '00:00:00' && $am_in == '00:00:00' && $am_in == '00:00:00') { // no time in or out
								// simply delete the attendance
								$sql = "DELETE FROM attendance WHERE aid = '$aid' LIMIT 1";
								query($sql); 
							} elseif ($am_in == '00:00:00' && $am_in == '00:00:00' && $am_in == '00:00:00' && $am_in == '00:00:00') { // complete attendance
								// update attendance
								$sql = "UPDATE attendance SET status = 1, remarks = '' WHERE aid = '$aid' LIMIT 1";
								query($sql); 
								update_attendance($aid); 
							} else { // atleast one time in/out
								// do not delete attendance, just update
								$sql = "UPDATE attendance SET status = -1, remarks = '' WHERE aid = '$aid' LIMIT 1";
								query($sql); 
								update_attendance($aid); 
							}
						}
						
						// insert into attendace the new eid
						$sql = "INSERT INTO attendance(eid, adate, status, remarks, last_update_id, last_update)
								VALUES('$eid', '$ddate', '13', 'DMS ".$dnum."', '$_SESSION[user_id]', NOW())";
						query($sql); 
					}
					
					// if both employees and dates are different
					if ($old_eid != $eid && $old_ddate != $ddate) {
						// remove the attendance with old eid and old date
						// search for an attendance where eid = $old_eid and ddate = $ddate
						$sql = "SELECT aid, am_in, am_out, pm_in, pm_out
								FROM attendance
								WHERE ddate = '$old_ddate' AND eid = '$old_eid'";
						$ares = query($sql); $anum = num_rows($ares); 
						
						if ($anem > 0) {
							$arow = fetch_array($ares); 
							$aid = $arow[0]; $am_in = $arow[1]; $am_out = $arow[2]; $pm_in = $arow[3]; $pm_out = $arow[4]; 
							
							if ($am_in == '00:00:00' && $am_in == '00:00:00' && $am_in == '00:00:00' && $am_in == '00:00:00') { // no time in or out
								// simply delete the attendance
								$sql = "DELETE FROM attendance WHERE aid = '$aid' LIMIT 1";
								query($sql); 
							} elseif ($am_in == '00:00:00' && $am_in == '00:00:00' && $am_in == '00:00:00' && $am_in == '00:00:00') { // complete attendance
								// update attendance
								$sql = "UPDATE attendance SET status = 1, remarks = '' WHERE aid = '$aid' LIMIT 1";
								query($sql); 
								update_attendance($aid); 
							} else { // atleast one time in/out
								// do not delete attendance, just update
								$sql = "UPDATE attendance SET status = -1, remarks = '' WHERE aid = '$aid' LIMIT 1";
								query($sql); 
								update_attendance($aid); 
							}
						}
						
						// insert into attendace the new eid
						$sql = "INSERT INTO attendance(eid, adate, status, remarks, last_update_id, last_update)
								VALUES('$eid', '$ddate', '13', 'DMS ".$dnum."', '$_SESSION[user_id]', NOW())";
						query($sql); 
					}
					
					header("Location: dms.php?updated_dms=1");
					exit();
				} else {
					?>
						<div class="alert alert-danger alert-dismissible fade in" role="alert">
							<strong>Could not update disposition mission slip because: </strong><br />
							<b><?php echo mysqli_error($connection) ?></b><br />
							The query was <?php echo $sql ?>.<br />
						</div>
					<?PHP
				}
			} else {
				$serialized_message = serialize($message);
				// redirect the user to index page
				header('location: e_dms.php?serialized_message='.$serialized_message.'&did='.$did);
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
				  
				<title>OTC | Update Disposition Mission Slip</title>

				<!-- Bootstrap -->
				<link href="vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
				<!-- Font Awesome -->
				<link href="vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
				<!-- NProgress -->
				<link href="vendors/nprogress/nprogress.css" rel="stylesheet">
				<!-- iCheck -->
				<link href="vendors/iCheck/skins/flat/green.css" rel="stylesheet">
				<!-- bootstrap-wysiwyg -->
				<link href="vendors/google-code-prettify/bin/prettify.min.css" rel="stylesheet">
				<!-- Select2 -->
				<link href="vendors/select2/dist/css/select2.min.css" rel="stylesheet">
				<!-- Switchery -->
				<link href="vendors/switchery/dist/switchery.min.css" rel="stylesheet">
				<!-- starrr -->
				<link href="vendors/starrr/dist/starrr.css" rel="stylesheet">
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
							<h3>Update Disposition Mission Slip</h3>
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
									<?php
									if (isset($_GET['did'])) {
										$did = $_GET['did'];
										
										$sql = "SELECT * FROM internals_dms WHERE did = '$did'";
										$res = query($sql); 
										$row = fetch_array($res); 
										
									}
									?>
								
									<form action="e_dms.php" method="POST" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="dnum">Control Number </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="dnum" name="dnum" class="form-control col-md-7 col-xs-12" value="<?php echo $row[1] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="eid">Employee <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <select id="eid" name="eid" required="required" class="form-control col-md-7 col-xs-12">
											<option value="0">--Select--</option>
											<?php
											$sql = "SELECT eid, CONCAT(lname,', ',fname) 
													FROM employees 
													WHERE active = 1 
													ORDER BY lname, fname";
											$eres = query($sql); 
											while ($erow = fetch_array($eres)) {
												if ($row[2] == $erow[0]) {
													?><option value="<?php echo $erow[0] ?>" Selected><?php echo $erow[1] ?></option><?php
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
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="fr_time">Start Time <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="time" id="fr_time" name="fr_time" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $row[3] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="to_time">End Time <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="time" id="to_time" name="to_time" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $row[4] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="ddate">Date <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="date" id="ddate" name="ddate" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $row[5] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="destination">Destination </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="destination" name="destination" class="form-control col-md-7 col-xs-12" value="<?php echo $row[6] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="purpose">Purpose </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="purpose" name="purpose" class="form-control col-md-7 col-xs-12" value="<?php echo $row[7] ?>">
										</div>
									  </div>
									  <div class="ln_solid"></div>
									  <div class="form-group">
										<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
										  <input type="hidden" name="submitted" value="1">
										  <input type="hidden" name="did" value="<?php echo $did ?>">
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
				<!-- FastClick -->
				<script src="vendors/fastclick/lib/fastclick.js"></script>
				<!-- NProgress -->
				<script src="vendors/nprogress/nprogress.js"></script>
				<!-- bootstrap-progressbar -->
				<script src="vendors/bootstrap-progressbar/bootstrap-progressbar.min.js"></script>
				<!-- iCheck -->
				<script src="vendors/iCheck/icheck.min.js"></script>
				<!-- bootstrap-daterangepicker -->
				<script src="vendors/moment/min/moment.min.js"></script>
				<script src="vendors/bootstrap-daterangepicker/daterangepicker.js"></script>
				<!-- bootstrap-wysiwyg -->
				<script src="vendors/bootstrap-wysiwyg/js/bootstrap-wysiwyg.min.js"></script>
				<script src="vendors/jquery.hotkeys/jquery.hotkeys.js"></script>
				<script src="vendors/google-code-prettify/src/prettify.js"></script>
				<!-- jQuery Tags Input -->
				<script src="vendors/jquery.tagsinput/src/jquery.tagsinput.js"></script>
				<!-- Switchery -->
				<script src="vendors/switchery/dist/switchery.min.js"></script>
				<!-- Select2 -->
				<script src="vendors/select2/dist/js/select2.full.min.js"></script>
				<!-- Parsley -->
				<script src="vendors/parsleyjs/dist/parsley.min.js"></script>
				<!-- Autosize -->
				<script src="vendors/autosize/dist/autosize.min.js"></script>
				<!-- jQuery autocomplete -->
				<script src="vendors/devbridge-autocomplete/dist/jquery.autocomplete.min.js"></script>
				<!-- starrr -->
				<script src="vendors/starrr/dist/starrr.js"></script>
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

