<?php

include("db.php");
include("sqli.php");
include("html.php");
define('PAGE', 'e_payroll_jocos.php'); 

session_start();

if (isset($_SESSION['user_id'])) {
	if (validate_level(PAGE) == true) {

		if(isset($_POST['submitted'])) { // if submit button has been pressed
			$jid = real_escape_string($_POST['jid']);
			$wdays = real_escape_string($_POST['wdays']);
			$tut_hr = real_escape_string($_POST['tut_hr']);
			$tut_min = real_escape_string($_POST['tut_min']);
			$phic = real_escape_string($_POST['phic']);
			
			// check if jid is given
			if ($jid != 0 && $jid != '') { $a = TRUE; } 
			else { $a = FALSE; $message[] = "Please select from the list of payroll entries."; }
			// check if wdays is given
			if ($wdays != 0 && $wdays != '') { $b = TRUE; } 
			else { $b = FALSE; $message[] = "Please enter the number of days worked."; }
			// check if tut_hr is given
			if ($tut_hr != '') { $c = TRUE; } 
			else { $c = FALSE; $message[] = "Please enter the Total Tardiness/UT in hours."; }
			// check if tut_min is given
			if ($tut_min != '') { $d = TRUE; } 
			else { $d = FALSE; $message[] = "Please enter the Total Tardiness/UT in minutes."; }
			// check if phic is given
			if ($phic != '') { $e = TRUE; } 
			else { $e = FALSE; $message[] = "Please enter the PHIC Contribution."; }
			
			// If data pass all tests, proceed
			if ( $a && $b && $c && $d && $e ) {
				// get the month and year of salary period
				$sql = "SELECT MONTH(sp.sp_start), YEAR(sp.sp_start), pj.salary, pj.daily, pj.pid
						FROM payrolls_jocos pj 
							INNER JOIN payrolls p ON pj.pid = p.pid 
							INNER JOIN salary_periods sp ON p.sid = sp.sid
						WHERE jid = '$jid'";
				$sp_res = query($sql); $sp_row = fetch_array($sp_res); 
				
				$pmonth = $sp_row[0];
				$pyear = $sp_row[1];
				$monthly_salary = $sp_row[2];
				$daily_salary = $sp_row[3];
				$pid = $sp_row[4];
				
				// get the total number of working days for this month
				$sql = "SELECT SUM(wdays) 
						FROM salary_periods
						WHERE MONTH(sp_start) = '$pmonth' AND YEAR(sp_start) = '$pyear'";
				$wres = query($sql); 
				$wrow = fetch_array($wres); 
				
				if ($monthly_salary > 0 && $daily_salary == 0.00) {
					$annual = $monthly_salary * 12; 
					$daily = round(($monthly_salary / $wrow[0]), 2); 
					$hourly = round((($monthly_salary / $wrow[0]) / 8), 3);
					$per_minute = round((($monthly_salary / $wrow[0]) / 480), 5);
					
				} elseif ($monthly_salary == 0 && $daily_salary > 0.00) {
					$annual = ($daily_salary * 22) * 12; 
					$daily = $daily_salary; 
					$hourly = round(($daily_salary / 8), 3);
					$per_minute = round(($daily_salary / 480), 5);
					
				}
				
				$gross = $wdays * $daily;
				$total_tardy = ($tut_hr * $hourly) + ($tut_min * $per_minute);
				$net_salary = $gross - $total_tardy; 
				
				if ($annual > 250000) {
					$ewt = round(($net_salary * 0.02), 2);
					$vat = round(($net_salary * 0.03), 2);
				} else {
					$ewt = 0; 
					$vat = 0; 
				}
				
				$deductions = $phic + $ewt + $vat;
				$payable = $net_salary - $deductions;
				
				// update data
				$sql = "UPDATE payrolls_jocos
						SET wdays = '$wdays',
							tut_hr = '$tut_hr',
							tut_min = '$tut_min',
							gross = '$gross',
							tut_value = '$total_tardy',
							net = '$net_salary',
							phic = '$phic',
							ewt = '$ewt',
							vat = '$vat',
							deductions = '$deductions',
							payable = '$payable'
						WHERE jid = '$jid'
						LIMIT 1";
				
				if (query($sql)) {
					
					// log the activity
					log_user(2, 'payrolls_jocos', $jid);
					
					header("Location: payroll.php?pid=".$pid);
					exit();
				} else {
					?>
					<div class="alert alert-danger alert-dismissible fade in" role="alert">
						<strong>Could not update payroll entry because: </strong><br />
						<b><?php echo mysqli_error($connection) ?></b><br />
						The query was <?php echo $sql ?>.<br />
					</div>
					<?PHP
				}
			} else {
				$serialized_message = serialize($message);
				// redirect the user to index page
				header('location: e_payroll_jocos.php?serialized_message='.$serialized_message.'&jid='.$jid);
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
				  
				<title>OTC | Update Payroll Entry</title>

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
					
					$jid = $_GET['jid'];

					$sql = "SELECT CONCAT(e.lname,', ',e.fname,', ',e.mname), pj.daily, 
								pj.salary, pj.wdays, pj.tut_hr, pj.tut_min, pj.phic
							FROM payrolls_jocos pj 
								INNER JOIN employees e ON pj.eid = e.eid 
							WHERE pj.jid = '$jid'";
					$res = query($sql); 
					$row = fetch_array($res); 
					
					if ($row[1] != 0 && $row[2] == 0) { $rate = number_format($row[1], 2).' / day';
					} elseif ($row[1] == 0 && $row[2] != 0) { $rate = number_format($row[2], 2).' / month';
					}
					?>

					<!-- page content -->
					<div class="right_col" role="main">
					  <div class="">
						<div class="page-title">
						  <div class="title_left">
							<h3>Update Payroll Entry</h3>
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
								
									<form action="e_payroll_jocos.php" method="POST" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12">Employee </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" disabled class="form-control col-md-7 col-xs-12" value="<?php echo $row[0] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12">Rate </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" disabled class="form-control col-md-7 col-xs-12" value="<?php echo $rate ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="wdays">Days Worked <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="wdays" name="wdays" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $row[3] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="tut_hr">Total Tardy/UT in hours <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="tut_hr" name="tut_hr" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $row[4] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="tut_min">Total Tardy/UT in minutes <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="tut_min" name="tut_min" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $row[5] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label for="scheme" class="control-label col-md-3 col-sm-3 col-xs-12" for="phic">PHIC Contribution <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="phic" name="phic" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $row[6] ?>">
										</div>
									  </div>
									  <div class="ln_solid"></div>
									  <div class="form-group">
										<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
										  <input type="hidden" name="submitted" value="1">
										  <input type="hidden" name="jid" value="<?php echo $jid ?>">
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

