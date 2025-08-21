<?php

include("db.php");
include("sqli.php");
include("html.php");
define('PAGE', 'e_education.php'); 

session_start();

if (isset($_SESSION['user_id'])) {
	if (validate_level3(PAGE) == true) {

		if(isset($_POST['submitted'])) { // if submit button has been pressed

			$sid = filter_var($_POST['sid'], FILTER_SANITIZE_NUMBER_INT);
			$slevel = filter_var($_POST['slevel'], FILTER_SANITIZE_NUMBER_INT);
			$school = filter_var($_POST['school'], FILTER_SANITIZE_STRING);
			$degree = filter_var($_POST['degree'], FILTER_SANITIZE_STRING);
			$sstart = filter_var($_POST['sstart'], FILTER_SANITIZE_NUMBER_INT);
			$send = filter_var($_POST['send'], FILTER_SANITIZE_NUMBER_INT);
			$level_earned = filter_var($_POST['level_earned'], FILTER_SANITIZE_NUMBER_INT);
			$grad_yr = filter_var($_POST['grad_yr'], FILTER_SANITIZE_NUMBER_INT);
			$honors = filter_var($_POST['honors'], FILTER_SANITIZE_STRING);
			
			// check if education level is given
			if ($slevel != 0 && $slevel != '') { $a = TRUE; } 
			else { $a = FALSE; $message[] = "Please enter the level of education."; }
			// check if name of school is given
			if ($school != "") { $b = TRUE; } 
			else { $b = FALSE; $message[] = "Please enter the name of school."; }
			// check if degree is given
			if ($degree != "") { $c = TRUE; } 
			else { $c = FALSE; $message[] = "Please enter the degree."; }
			// check if start date is given
			if ($sstart != "") { $d = TRUE; } 
			else { $d = FALSE; $message[] = "Please enter the start date of school."; }
			// check if end date is given
			if ($send != "") { $e = TRUE; } 
			else { $e = FALSE; $message[] = "Please enter the end date of school."; }
			
			// If data pass all tests, proceed
			if ( $a && $b && $c && $d && $e ) {
				// update data
				$sql = "UPDATE employees_schools
						SET slevel = '$slevel',
							school = '$school',
							degree = '$degree',
							sstart = '$sstart',
							send = '$send', 
							level_earned = '$level_earned',
							grad_yr = '$grad_yr',
							honors = '$honors' 
						WHERE sid = '$sid'
						LIMIT 1";
				if (query($sql)) {
					// log the activity
					log_user(2, 'employees_schools', $sid);
					
					$sql = "SELECT eid FROM employees_schools WHERE sid = '$sid'";
					$res = query($sql); 
					$row = fetch_array($res); 
					$eid = $row[0]; 
					
					header("Location:employee.php?eid=".$eid."&success=1");
					exit();
				} else {
					?>
					<div class="alert alert-danger alert-dismissible fade in" role="alert">
						<strong>Could not update employee's educational background because: </strong><br />
						<b><?php echo mysqli_error($connection) ?></b><br />
						The query was <?php echo $sql ?>.<br />
					</div>
					<?PHP
				}
			} else {
				$serialized_message = serialize($message);
				// redirect the user to index page
				header('location: e_education.php?serialized_message='.$serialized_message.'&sid='.$sid);
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
				  
				<title>OTC | Update Employee's Educational Background</title>

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
							<h3>Update Employee's Educational Background</h3>
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
									if (isset($_GET['sid'])) {
										$sid = $_GET['sid'];
										
										$sql = "SELECT * FROM employees_schools WHERE sid = '$sid'";
										$res = query($sql); 
										$row = fetch_array($res); 
										
										$sql = "SELECT CONCAT(lname,', ',fname) FROM employees WHERE eid = '$row[1]'";
										$eres = query($sql); 
										$erow = fetch_array($eres); 
									}
									?>
								
									<form action="e_education.php" method="POST" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="ecode">Employee Name </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="ecode" name="ecode" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $erow[0] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="slevel">Level <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <select id="slevel" name="slevel" required="required" class="form-control col-md-7 col-xs-12">
											<?php
											if ($row[2] == 1) {
												?>
												<option value="1" Selected>Elementary</option>
												<option value="2">Secondary</option>
												<option value="3">Vocational / Trade Course</option>
												<option value="4">College</option>
												<option value="5">Master's Degree</option>
												<option value="6">Doctor of Philosphy</option>
												<?php
											} elseif ($row[2] == 2) {
												?>
												<option value="1">Elementary</option>
												<option value="2" Selected>Secondary</option>
												<option value="3">Vocational / Trade Course</option>
												<option value="4">College</option>
												<option value="5">Master's Degree</option>
												<option value="6">Doctor of Philosphy</option>
												<?php
											} elseif ($row[2] == 3) {
												?>
												<option value="1">Elementary</option>
												<option value="2">Secondary</option>
												<option value="3" Selected>Vocational / Trade Course</option>
												<option value="4">College</option>
												<option value="5">Master's Degree</option>
												<option value="6">Doctor of Philosphy</option>
												<?php
											} elseif ($row[2] == 4) {
												?>
												<option value="1">Elementary</option>
												<option value="2">Secondary</option>
												<option value="3">Vocational / Trade Course</option>
												<option value="4" Selected>College</option>
												<option value="5">Master's Degree</option>
												<option value="6">Doctor of Philosphy</option>
												<?php
											} elseif ($row[2] == 5) {
												?>
												<option value="1">Elementary</option>
												<option value="2">Secondary</option>
												<option value="3">Vocational / Trade Course</option>
												<option value="4">College</option>
												<option value="5" Selected>Master's Degree</option>
												<option value="6">Doctor of Philosphy</option>
												<?php
											} elseif ($row[2] == 6) {
												?>
												<option value="1">Elementary</option>
												<option value="2">Secondary</option>
												<option value="3">Vocational / Trade Course</option>
												<option value="4">College</option>
												<option value="5">Master's Degree</option>
												<option value="6" Selected>Doctor of Philosphy</option>
												<?php
											}
											?>
										  </select>
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="school">Name of School <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="school" name="school" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $row[3] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="degree">Degree <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="degree" name="degree" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $row[4] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="sstart">From (YYYY) <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="sstart" name="sstart" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $row[5] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="send">To (YYYY) <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="send" name="send" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $row[6] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="level_earned">Level / Units Earned </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="level_earned" name="level_earned" class="form-control col-md-7 col-xs-12" value="<?php echo $row[7] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="grad_yr">Year Graduated (YYYY) </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="grad_yr" name="grad_yr" class="form-control col-md-7 col-xs-12" value="<?php echo $row[8] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="honors">Honors Received </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="honors" name="honors" class="form-control col-md-7 col-xs-12" value="<?php echo $row[9] ?>">
										</div>
									  </div>
									  <div class="ln_solid"></div>
									  <div class="form-group">
										<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
										  <input type="hidden" name="submitted" value="1">
										  <input type="hidden" name="sid" value="<?php echo $sid ?>">
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
	} else {
		// check if user is a valid employee
		$sql = "SELECT eid FROM users WHERE user_id = '$_SESSION[user_id]' AND active = 1";
		$vres = query($sql); $vrow = fetch_array($vres); $emp_id = $vrow[0]; 
		
		if ($emp_id != 0) {
			
			if(isset($_POST['submitted'])) { // if submit button has been pressed

				$sid = filter_var($_POST['sid'], FILTER_SANITIZE_NUMBER_INT);
				$slevel = filter_var($_POST['slevel'], FILTER_SANITIZE_NUMBER_INT);
				$school = filter_var($_POST['school'], FILTER_SANITIZE_STRING);
				$degree = filter_var($_POST['degree'], FILTER_SANITIZE_STRING);
				$sstart = filter_var($_POST['sstart'], FILTER_SANITIZE_NUMBER_INT);
				$send = filter_var($_POST['send'], FILTER_SANITIZE_NUMBER_INT);
				$level_earned = filter_var($_POST['level_earned'], FILTER_SANITIZE_NUMBER_INT);
				$grad_yr = filter_var($_POST['grad_yr'], FILTER_SANITIZE_NUMBER_INT);
				$honors = filter_var($_POST['honors'], FILTER_SANITIZE_STRING);
				
				// check if education level is given
				if ($slevel != 0 && $slevel != '') { $a = TRUE; } 
				else { $a = FALSE; $message[] = "Please enter the level of education."; }
				// check if name of school is given
				if ($school != "") { $b = TRUE; } 
				else { $b = FALSE; $message[] = "Please enter the name of school."; }
				// check if degree is given
				if ($degree != "") { $c = TRUE; } 
				else { $c = FALSE; $message[] = "Please enter the degree."; }
				// check if start date is given
				if ($sstart != "") { $d = TRUE; } 
				else { $d = FALSE; $message[] = "Please enter the start date of school."; }
				// check if end date is given
				if ($send != "") { $e = TRUE; } 
				else { $e = FALSE; $message[] = "Please enter the end date of school."; }
				
				// If data pass all tests, proceed
				if ( $a && $b && $c && $d && $e ) {
					// update data
					$sql = "UPDATE employees_schools
							SET slevel = '$slevel',
								school = '$school',
								degree = '$degree',
								sstart = '$sstart',
								send = '$send', 
								level_earned = '$level_earned',
								grad_yr = '$grad_yr',
								honors = '$honors' 
							WHERE sid = '$sid'
							LIMIT 1";
					if (query($sql)) {
						// log the activity
						log_user(2, 'employees_schools', $sid);
						
						$sql = "SELECT eid FROM employees_schools WHERE sid = '$sid'";
						$res = query($sql); 
						$row = fetch_array($res); 
						$eid = $row[0]; 
						
						header("Location:employee.php?eid=".$eid."&success=1");
						exit();
					} else {
						?>
						<div class="alert alert-danger alert-dismissible fade in" role="alert">
							<strong>Could not update employee's educational background because: </strong><br />
							<b><?php echo mysqli_error($connection) ?></b><br />
							The query was <?php echo $sql ?>.<br />
						</div>
						<?PHP
					}
				} else {
					$serialized_message = serialize($message);
					// redirect the user to index page
					header('location: e_education.php?serialized_message='.$serialized_message.'&sid='.$sid);
					exit();
				}
			} else {
				$sid = $_GET['sid'];
				
				$sql = "SELECT eid FROM employees_schools WHERE sid = '$sid'";
				$fres = query($sql); $frow = fetch_array($fres); $eid = $frow[0];
				
				if ($emp_id == $eid) {
					// show form
					?>
					<!DOCTYPE html>
					<html lang="en">
					  <head>
						<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
						<!-- Meta, title, CSS, favicons, etc. -->
						<meta charset="utf-8">
						<meta http-equiv="X-UA-Compatible" content="IE=edge">
						<meta name="viewport" content="width=device-width, initial-scale=1">
						  
						<title>OTC | Update Employee's Educational Background</title>

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
									<h3>Update Employee's Educational Background</h3>
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
											if (isset($_GET['sid'])) {
												$sid = $_GET['sid'];
												
												$sql = "SELECT * FROM employees_schools WHERE sid = '$sid'";
												$res = query($sql); 
												$row = fetch_array($res); 
												
												$sql = "SELECT CONCAT(lname,', ',fname) FROM employees WHERE eid = '$row[1]'";
												$eres = query($sql); 
												$erow = fetch_array($eres); 
											}
											?>
										
											<form action="e_education.php" method="POST" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
											  <div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="ecode">Employee Name </label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <input type="text" id="ecode" name="ecode" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $erow[0] ?>">
												</div>
											  </div>
											  <div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="slevel">Level <span class="required">*</span></label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <select id="slevel" name="slevel" required="required" class="form-control col-md-7 col-xs-12">
													<?php
													if ($row[2] == 1) {
														?>
														<option value="1" Selected>Elementary</option>
														<option value="2">Secondary</option>
														<option value="3">Vocational / Trade Course</option>
														<option value="4">College</option>
														<option value="5">Master's Degree</option>
														<option value="6">Doctor of Philosphy</option>
														<?php
													} elseif ($row[2] == 2) {
														?>
														<option value="1">Elementary</option>
														<option value="2" Selected>Secondary</option>
														<option value="3">Vocational / Trade Course</option>
														<option value="4">College</option>
														<option value="5">Master's Degree</option>
														<option value="6">Doctor of Philosphy</option>
														<?php
													} elseif ($row[2] == 3) {
														?>
														<option value="1">Elementary</option>
														<option value="2">Secondary</option>
														<option value="3" Selected>Vocational / Trade Course</option>
														<option value="4">College</option>
														<option value="5">Master's Degree</option>
														<option value="6">Doctor of Philosphy</option>
														<?php
													} elseif ($row[2] == 4) {
														?>
														<option value="1">Elementary</option>
														<option value="2">Secondary</option>
														<option value="3">Vocational / Trade Course</option>
														<option value="4" Selected>College</option>
														<option value="5">Master's Degree</option>
														<option value="6">Doctor of Philosphy</option>
														<?php
													} elseif ($row[2] == 5) {
														?>
														<option value="1">Elementary</option>
														<option value="2">Secondary</option>
														<option value="3">Vocational / Trade Course</option>
														<option value="4">College</option>
														<option value="5" Selected>Master's Degree</option>
														<option value="6">Doctor of Philosphy</option>
														<?php
													} elseif ($row[2] == 6) {
														?>
														<option value="1">Elementary</option>
														<option value="2">Secondary</option>
														<option value="3">Vocational / Trade Course</option>
														<option value="4">College</option>
														<option value="5">Master's Degree</option>
														<option value="6" Selected>Doctor of Philosphy</option>
														<?php
													}
													?>
												  </select>
												</div>
											  </div>
											  <div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="school">Name of School <span class="required">*</span></label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <input type="text" id="school" name="school" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $row[3] ?>">
												</div>
											  </div>
											  <div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="degree">Degree <span class="required">*</span></label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <input type="text" id="degree" name="degree" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $row[4] ?>">
												</div>
											  </div>
											  <div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="sstart">From (YYYY) <span class="required">*</span></label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <input type="text" id="sstart" name="sstart" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $row[5] ?>">
												</div>
											  </div>
											  <div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="send">To (YYYY) <span class="required">*</span></label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <input type="text" id="send" name="send" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $row[6] ?>">
												</div>
											  </div>
											  <div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="level_earned">Level / Units Earned </label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <input type="text" id="level_earned" name="level_earned" class="form-control col-md-7 col-xs-12" value="<?php echo $row[7] ?>">
												</div>
											  </div>
											  <div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="grad_yr">Year Graduated (YYYY) </label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <input type="text" id="grad_yr" name="grad_yr" class="form-control col-md-7 col-xs-12" value="<?php echo $row[8] ?>">
												</div>
											  </div>
											  <div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="honors">Honors Received </label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <input type="text" id="honors" name="honors" class="form-control col-md-7 col-xs-12" value="<?php echo $row[9] ?>">
												</div>
											  </div>
											  <div class="ln_solid"></div>
											  <div class="form-group">
												<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
												  <input type="hidden" name="submitted" value="1">
												  <input type="hidden" name="sid" value="<?php echo $sid ?>">
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
				} else {
					// hacking attempt
					?>
					<!DOCTYPE html>
					<html lang="en">
					  <head>
						<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
						<!-- Meta, title, CSS, favicons, etc. -->
						<meta charset="utf-8">
						<meta http-equiv="X-UA-Compatible" content="IE=edge">
						<meta name="viewport" content="width=device-width, initial-scale=1">

						<title>OTC - Update Employee-Education</title>

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
									<div class="row">
									
									  <div class="col-md-12 col-sm-12 col-xs-12">
										<div class="x_panel">
											<div class="x_content">
											  <div class="bs-example" data-example-id="simple-jumbotron">
													<blockquote class="blockquote">
													  <p class="h1">Hacking Attempt!</p>
													  <footer class="blockquote-footer"><em>You cannot update other's employee-information. You will be reported to the system administrator.</em></footer>
													</blockquote>
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


					  </body>
					</html>		
					<?php
				}
			}
		} else {
			// invalid user-rights
			?>
			<!DOCTYPE html>
			<html lang="en">
			  <head>
				<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
				<!-- Meta, title, CSS, favicons, etc. -->
				<meta charset="utf-8">
				<meta http-equiv="X-UA-Compatible" content="IE=edge">
				<meta name="viewport" content="width=device-width, initial-scale=1">

				<title>OTC - Update Employee-Education</title>

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
							<div class="row">
							
							  <div class="col-md-12 col-sm-12 col-xs-12">
								<div class="x_panel">
									<div class="x_content">
									  <div class="bs-example" data-example-id="simple-jumbotron">
											<blockquote class="blockquote">
											  <p class="h1">Invalid User-right!</p>
											  <footer class="blockquote-footer"><em>Ask your system administrator for access to this page.</em></footer>
											</blockquote>
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


			  </body>
			</html>		
			<?php
		}
	}
} else {
	header('Location: login.php');	
}

