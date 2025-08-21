<?php

include("db.php");
include("sqli.php");
include("html.php");
define('PAGE', 'e_experience.php'); 

session_start();

if (isset($_SESSION['user_id'])) {
	if (validate_level3(PAGE) == true) {

		if(isset($_POST['submitted'])) { // if submit button has been pressed

			$eeid = filter_var($_POST['eeid'], FILTER_SANITIZE_NUMBER_INT);
			$estart = filter_var($_POST['estart'], FILTER_SANITIZE_STRING);
			$eend = filter_var($_POST['eend'], FILTER_SANITIZE_STRING);
			$position = filter_var($_POST['position'], FILTER_SANITIZE_STRING);
			$company = filter_var($_POST['company'], FILTER_SANITIZE_STRING);
			$salary = real_escape_string($_POST['salary']);
			$sgrade = filter_var($_POST['sgrade'], FILTER_SANITIZE_NUMBER_INT);
			$sstep = filter_var($_POST['sstep'], FILTER_SANITIZE_STRING);
			$status = filter_var($_POST['status'], FILTER_SANITIZE_STRING);
			$govt = filter_var($_POST['govt'], FILTER_SANITIZE_NUMBER_INT);
			
			// check if start date is given
			if ($estart != "") { $a = TRUE; } 
			else { $a = FALSE; $message[] = "Please enter the start date of work."; }
			// check if end date is given
			if ($eend != "") { $b = TRUE; } 
			else { $b = FALSE; $message[] = "Please enter the end date of work."; }
			// check if position is given
			if ($position != "") { $c = TRUE; } 
			else { $c = FALSE; $message[] = "Please enter position."; }
			// check if company name is given
			if ($company != "") { $d = TRUE; } 
			else { $d = FALSE; $message[] = "Please enter the company name."; }
			// check if salary is given
			if ($salary != '0.00' && $salary != '') { $e = TRUE; } 
			else { $e = FALSE; $message[] = "Please enter the salary."; }
			
			// If data pass all tests, proceed
			if ( $a && $b && $c && $d && $e ) {
				// update data
				$sql = "UPDATE employees_experiences
						SET estart = '$estart',
							eend = '$eend',
							position = '$position',
							company = '$company',
							salary = '$salary', 
							sgrade = '$sgrade', 
							sstep = '$sstep',
							status = '$status', 
							govt = '$govt'
						WHERE eeid = '$eeid'
						LIMIT 1";
				if (query($sql)) {
					// log the activity
					log_user(2, 'employees_experiences', $eeid);
					
					$sql = "SELECT eid FROM employees_experiences WHERE eeid = '$eeid'";
					$res = query($sql); 
					$row = fetch_array($res); 
					$eid = $row[0]; 
					
					header("Location:employee.php?eid=".$eid."&success=1");
					exit();
				} else {
					?>
					<div class="alert alert-danger alert-dismissible fade in" role="alert">
						<strong>Could not update employee's work experience because: </strong><br />
						<b><?php echo mysqli_error($connection) ?></b><br />
						The query was <?php echo $sql ?>.<br />
					</div>
					<?PHP
				}
			} else {
				$serialized_message = serialize($message);
				// redirect the user to index page
				header('location: e_experience.php?serialized_message='.$serialized_message.'&eeid='.$eeid);
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
				  
				<title>OTC | Update Employee's Work Experience</title>

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
							<h3>Update Employee's Work Experience</h3>
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
									if (isset($_GET['eeid'])) {
										$eeid = $_GET['eeid'];
										
										$sql = "SELECT * FROM employees_experiences WHERE eeid = '$eeid'";
										$res = query($sql); 
										$row = fetch_array($res); 
										
										$sql = "SELECT CONCAT(lname,', ',fname) FROM employees WHERE eid = '$row[1]'";
										$eres = query($sql); 
										$erow = fetch_array($eres); 
									}
									?>
								
									<form action="e_experience.php" method="POST" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="ecode">Employee Name </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="ecode" name="ecode" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $erow[0] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="estart">From <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="date" id="estart" name="estart" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $row[2] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="eend">To <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="date" id="eend" name="eend" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $row[3] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="position">Position Title <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="position" name="position" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $row[4] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="company">Department / Agency / Company <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="company" name="company" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $row[5] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="salary">Monthly Salary <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="salary" name="salary" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $row[6] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="sgrade">Salary / Pay Grade </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="sgrade" name="sgrade" class="form-control col-md-7 col-xs-12" value="<?php echo $row[7] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="sstep">Step Increment (00-0) </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="sstep" name="sstep" class="form-control col-md-7 col-xs-12" value="<?php echo $row[8] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="status">Status of Appointment </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="status" name="status" class="form-control col-md-7 col-xs-12" value="<?php echo $row[9] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="govt">Government Service <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <select id="govt" name="govt" required="required" class="form-control col-md-7 col-xs-12">
											<?php
											if ($row[10] == 1) {
												?>
												<option value="1" Selected>Yes</option>
												<option value="0">No</option>
												<?php
											} else {
												?>
												<option value="1">Yes</option>
												<option value="0" Selected>No</option>
												<?php
											}
											?>
										  </select>
										</div>
									  </div>
									  <div class="ln_solid"></div>
									  <div class="form-group">
										<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
										  <input type="hidden" name="submitted" value="1">
										  <input type="hidden" name="eeid" value="<?php echo $eeid ?>">
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
				$eeid = filter_var($_POST['eeid'], FILTER_SANITIZE_NUMBER_INT);
				$estart = filter_var($_POST['estart'], FILTER_SANITIZE_STRING);
				$eend = filter_var($_POST['eend'], FILTER_SANITIZE_STRING);
				$position = filter_var($_POST['position'], FILTER_SANITIZE_STRING);
				$company = filter_var($_POST['company'], FILTER_SANITIZE_STRING);
				$salary = real_escape_string($_POST['salary']);
				$sgrade = filter_var($_POST['sgrade'], FILTER_SANITIZE_NUMBER_INT);
				$sstep = filter_var($_POST['sstep'], FILTER_SANITIZE_STRING);
				$status = filter_var($_POST['status'], FILTER_SANITIZE_STRING);
				$govt = filter_var($_POST['govt'], FILTER_SANITIZE_NUMBER_INT);
				
				// check if start date is given
				if ($estart != "") { $a = TRUE; } 
				else { $a = FALSE; $message[] = "Please enter the start date of work."; }
				// check if end date is given
				if ($eend != "") { $b = TRUE; } 
				else { $b = FALSE; $message[] = "Please enter the end date of work."; }
				// check if position is given
				if ($position != "") { $c = TRUE; } 
				else { $c = FALSE; $message[] = "Please enter position."; }
				// check if company name is given
				if ($company != "") { $d = TRUE; } 
				else { $d = FALSE; $message[] = "Please enter the company name."; }
				// check if salary is given
				if ($salary != '0.00' && $salary != '') { $e = TRUE; } 
				else { $e = FALSE; $message[] = "Please enter the salary."; }
				
				// If data pass all tests, proceed
				if ( $a && $b && $c && $d && $e ) {
					// update data
					$sql = "UPDATE employees_experiences
							SET estart = '$estart',
								eend = '$eend',
								position = '$position',
								company = '$company',
								salary = '$salary', 
								sgrade = '$sgrade', 
								sstep = '$sstep',
								status = '$status', 
								govt = '$govt'
							WHERE eeid = '$eeid'
							LIMIT 1";
					if (query($sql)) {
						// log the activity
						log_user(2, 'employees_experiences', $eeid);
						
						$sql = "SELECT eid FROM employees_experiences WHERE eeid = '$eeid'";
						$res = query($sql); 
						$row = fetch_array($res); 
						$eid = $row[0]; 
						
						header("Location:employee.php?eid=".$eid."&success=1");
						exit();
					} else {
						?>
						<div class="alert alert-danger alert-dismissible fade in" role="alert">
							<strong>Could not update employee's work experience because: </strong><br />
							<b><?php echo mysqli_error($connection) ?></b><br />
							The query was <?php echo $sql ?>.<br />
						</div>
						<?PHP
					}
				} else {
					$serialized_message = serialize($message);
					// redirect the user to index page
					header('location: e_experience.php?serialized_message='.$serialized_message.'&eeid='.$eeid);
					exit();
				}
			} else {
				$eeid = $_GET['eeid'];
				
				$sql = "SELECT eid FROM employees_experiences WHERE eeid = '$eeid'";
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
						  
						<title>OTC | Update Employee's Work Experience</title>

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
									<h3>Update Employee's Work Experience</h3>
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
											if (isset($_GET['eeid'])) {
												$eeid = $_GET['eeid'];
												
												$sql = "SELECT * FROM employees_experiences WHERE eeid = '$eeid'";
												$res = query($sql); 
												$row = fetch_array($res); 
												
												$sql = "SELECT CONCAT(lname,', ',fname) FROM employees WHERE eid = '$row[1]'";
												$eres = query($sql); 
												$erow = fetch_array($eres); 
											}
											?>
										
											<form action="e_experience.php" method="POST" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
											  <div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="ecode">Employee Name </label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <input type="text" id="ecode" name="ecode" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $erow[0] ?>">
												</div>
											  </div>
											  <div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="estart">From <span class="required">*</span></label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <input type="date" id="estart" name="estart" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $row[2] ?>">
												</div>
											  </div>
											  <div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="eend">To <span class="required">*</span></label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <input type="date" id="eend" name="eend" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $row[3] ?>">
												</div>
											  </div>
											  <div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="position">Position Title <span class="required">*</span></label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <input type="text" id="position" name="position" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $row[4] ?>">
												</div>
											  </div>
											  <div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="company">Department / Agency / Company <span class="required">*</span></label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <input type="text" id="company" name="company" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $row[5] ?>">
												</div>
											  </div>
											  <div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="salary">Monthly Salary <span class="required">*</span></label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <input type="text" id="salary" name="salary" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $row[6] ?>">
												</div>
											  </div>
											  <div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="sgrade">Salary / Pay Grade </label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <input type="text" id="sgrade" name="sgrade" class="form-control col-md-7 col-xs-12" value="<?php echo $row[7] ?>">
												</div>
											  </div>
											  <div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="sstep">Step Increment (00-0) </label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <input type="text" id="sstep" name="sstep" class="form-control col-md-7 col-xs-12" value="<?php echo $row[8] ?>">
												</div>
											  </div>
											  <div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="status">Status of Appointment </label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <input type="text" id="status" name="status" class="form-control col-md-7 col-xs-12" value="<?php echo $row[9] ?>">
												</div>
											  </div>
											  <div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="govt">Government Service <span class="required">*</span></label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <select id="govt" name="govt" required="required" class="form-control col-md-7 col-xs-12">
													<?php
													if ($row[10] == 1) {
														?>
														<option value="1" Selected>Yes</option>
														<option value="0">No</option>
														<?php
													} else {
														?>
														<option value="1">Yes</option>
														<option value="0" Selected>No</option>
														<?php
													}
													?>
												  </select>
												</div>
											  </div>
											  <div class="ln_solid"></div>
											  <div class="form-group">
												<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
												  <input type="hidden" name="submitted" value="1">
												  <input type="hidden" name="eeid" value="<?php echo $eeid ?>">
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

						<title>OTC - Update Employee-Experience</title>

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

				<title>OTC - Update Employee-Experience</title>

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

