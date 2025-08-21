<?php

include("db.php");
include("sqli.php");
include("html.php");
define('PAGE', 'add_experience.php'); 

session_start();

if (isset($_SESSION['user_id'])) {
	if (validate_level3(PAGE) == true) {

		if(isset($_POST['submitted'])) { // if submit button has been pressed
			
			$eid = filter_var($_POST['eid'], FILTER_SANITIZE_NUMBER_INT);
			$estart = filter_var($_POST['estart'], FILTER_SANITIZE_STRING);
			$eend = filter_var($_POST['eend'], FILTER_SANITIZE_STRING);
			$position = filter_var($_POST['position'], FILTER_SANITIZE_STRING);
			$company = filter_var($_POST['company'], FILTER_SANITIZE_STRING);
			$salary = filter_var($_POST['salary'], FILTER_SANITIZE_NUMBER_FLOAT);
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
			if ( $a && $b && $c && $d && $e) {
				
				// check for double entry first
				$sql = "SELECT eeid 
						FROM employees_experiences 
						WHERE eid = '$eid' AND estart = '$estart' AND eend = '$eend' AND position = '$position' AND company = '$company'";
				$res = query($sql); 
				$num = num_rows($res); 
				
				if ($num > 0) {
					?>
					<div class="alert alert-danger alert-dismissible fade in" role="alert">
						<strong>Record already exists! </strong><br /><br />
						<a href="employee.php?eid=<?php echo $eid ?>">Click here to view the record</a>
					</div>
					<?php
				} else {
					$sql = "INSERT INTO employees_experiences( eid, estart, eend, position, company, salary, sgrade, sstep, status, govt )
							VALUES( '$eid', '$estart', '$eend', '$position', '$company', '$salary', '$sgrade', '$sstep', '$status', '$govt' )";
					if (query($sql)) {
						$eeid = mysqli_insert_id($connection);
						// log the activity
						log_user(1, 'employees_experiences', $eeid);
						
						header("Location:employee.php?eid=".$eid);
						exit();
					} else {
						?>
						<div class="alert alert-danger alert-dismissible fade in" role="alert">
							<strong>Could not add employee's work experience because: </strong><br />
							<b><?php echo mysqli_error($connection) ?></b><br />
							The query was <?php echo $sql ?>.<br />
						</div>
						<?PHP
					}
				}
			} else {
				$serialized_message = serialize($message);
				// redirect the user to index page
				header('location: add_experience.php?serialized_message='.$serialized_message.'&eid='.$eid.'&estart='.$estart.'&eend='.$eend.'&position='.$position.'&company='.$company.'&salary='.$salary.'&sgrade='.$sgrade.'&sstep='.$sstep.'&status='.$status.'&govt='.$govt);
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
				  
				<title>OTC | Add Employee's Work Experience</title>

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
							<h3>Add Employee's Work Experience</h3>
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
									if (isset($_GET['eid'])) {
										$eid = $_GET['eid'];
										
										$sql = "SELECT CONCAT(lname,', ',fname) FROM employees WHERE eid = '$eid'";
										$eres = query($sql); 
										$erow = fetch_array($eres); 
										
									}
									?>
									<form action="add_experience.php" method="POST" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="ecode">Employee Name </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="ecode" name="ecode" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $erow[0] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="estart">From <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="date" id="estart" name="estart" required="required" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['estart'])) {
											  ?>value="<?php echo $_GET['estart'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="eend">To <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="date" id="eend" name="eend" required="required" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['eend'])) {
											  ?>value="<?php echo $_GET['eend'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="position">Position Title <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="position" name="position" required="required" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['position'])) {
											  ?>value="<?php echo $_GET['position'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="company">Department / Agency / Company <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="company" name="company" required="required" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['company'])) {
											  ?>value="<?php echo $_GET['company'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="salary">Monthly Salary <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="salary" name="salary" required="required" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['salary'])) {
											  ?>value="<?php echo $_GET['salary'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="sgrade">Salary / Pay Grade </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="sgrade" name="sgrade" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['sgrade'])) {
											  ?>value="<?php echo $_GET['sgrade'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="sstep">Step Increment (00-0) </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="sstep" name="sstep" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['sstep'])) {
											  ?>value="<?php echo $_GET['sstep'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="status">Status of Appointment </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="status" name="status" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['status'])) {
											  ?>value="<?php echo $_GET['status'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="govt">Government Service <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <select id="govt" name="govt" required="required" class="form-control col-md-7 col-xs-12">
										    <?php
											if (isset ($_GET['govt'])) {
												if ($_GET['govt'] == 1) {
													?>
													<option value="1" Selected>Yes</option>
													<option value="0">No</option>
													<?php
												} elseif ($_GET['govt'] == 0) {
													?>
													<option value="1">Yes</option>
													<option value="0" Selected>No</option>
													<?php
												}
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
										  <input type="hidden" name="eid" value="<?php echo $eid ?>">
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
				$eid = filter_var($_POST['eid'], FILTER_SANITIZE_NUMBER_INT);
				$estart = filter_var($_POST['estart'], FILTER_SANITIZE_STRING);
				$eend = filter_var($_POST['eend'], FILTER_SANITIZE_STRING);
				$position = filter_var($_POST['position'], FILTER_SANITIZE_STRING);
				$company = filter_var($_POST['company'], FILTER_SANITIZE_STRING);
				$salary = filter_var($_POST['salary'], FILTER_SANITIZE_NUMBER_FLOAT);
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
				if ( $a && $b && $c && $d && $e) {
					
					// check for double entry first
					$sql = "SELECT eeid 
							FROM employees_experiences 
							WHERE eid = '$eid' AND estart = '$estart' AND eend = '$eend' AND position = '$position' AND company = '$company'";
					$res = query($sql); 
					$num = num_rows($res); 
					
					if ($num > 0) {
						?>
						<div class="alert alert-danger alert-dismissible fade in" role="alert">
							<strong>Record already exists! </strong><br /><br />
							<a href="employee.php?eid=<?php echo $eid ?>">Click here to view the record</a>
						</div>
						<?php
					} else {
						$sql = "INSERT INTO employees_experiences( eid, estart, eend, position, company, salary, sgrade, sstep, status, govt )
								VALUES( '$eid', '$estart', '$eend', '$position', '$company', '$salary', '$sgrade', '$sstep', '$status', '$govt' )";
						if (query($sql)) {
							$eeid = mysqli_insert_id($connection);
							// log the activity
							log_user(1, 'employees_experiences', $eeid);
							
							header("Location:employee.php?eid=".$eid);
							exit();
						} else {
							?>
							<div class="alert alert-danger alert-dismissible fade in" role="alert">
								<strong>Could not add employee's work experience because: </strong><br />
								<b><?php echo mysqli_error($connection) ?></b><br />
								The query was <?php echo $sql ?>.<br />
							</div>
							<?PHP
						}
					}
				} else {
					$serialized_message = serialize($message);
					// redirect the user to index page
					header('location: add_experience.php?serialized_message='.$serialized_message.'&eid='.$eid.'&estart='.$estart.'&eend='.$eend.'&position='.$position.'&company='.$company.'&salary='.$salary.'&sgrade='.$sgrade.'&sstep='.$sstep.'&status='.$status.'&govt='.$govt);
					exit();
				}
			} else {
				$eid = $_GET['eid'];
				
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
						  
						<title>OTC | Add Employee's Work Experience</title>

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
									<h3>Add Employee's Work Experience</h3>
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
											if (isset($_GET['eid'])) {
												$eid = $_GET['eid'];
												
												$sql = "SELECT CONCAT(lname,', ',fname) FROM employees WHERE eid = '$eid'";
												$eres = query($sql); 
												$erow = fetch_array($eres); 
												
											}
											?>
											<form action="add_experience.php" method="POST" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
											  <div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="ecode">Employee Name </label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <input type="text" id="ecode" name="ecode" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $erow[0] ?>">
												</div>
											  </div>
											  <div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="estart">From <span class="required">*</span></label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <input type="date" id="estart" name="estart" required="required" class="form-control col-md-7 col-xs-12" 
												  <?php
												  if (isset ($_GET['estart'])) {
													  ?>value="<?php echo $_GET['estart'] ?>"<?php
												  }
												  ?>
												  >
												</div>
											  </div>
											  <div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="eend">To <span class="required">*</span></label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <input type="date" id="eend" name="eend" required="required" class="form-control col-md-7 col-xs-12" 
												  <?php
												  if (isset ($_GET['eend'])) {
													  ?>value="<?php echo $_GET['eend'] ?>"<?php
												  }
												  ?>
												  >
												</div>
											  </div>
											  <div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="position">Position Title <span class="required">*</span></label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <input type="text" id="position" name="position" required="required" class="form-control col-md-7 col-xs-12" 
												  <?php
												  if (isset ($_GET['position'])) {
													  ?>value="<?php echo $_GET['position'] ?>"<?php
												  }
												  ?>
												  >
												</div>
											  </div>
											  <div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="company">Department / Agency / Company <span class="required">*</span></label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <input type="text" id="company" name="company" required="required" class="form-control col-md-7 col-xs-12" 
												  <?php
												  if (isset ($_GET['company'])) {
													  ?>value="<?php echo $_GET['company'] ?>"<?php
												  }
												  ?>
												  >
												</div>
											  </div>
											  <div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="salary">Monthly Salary <span class="required">*</span></label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <input type="text" id="salary" name="salary" required="required" class="form-control col-md-7 col-xs-12" 
												  <?php
												  if (isset ($_GET['salary'])) {
													  ?>value="<?php echo $_GET['salary'] ?>"<?php
												  }
												  ?>
												  >
												</div>
											  </div>
											  <div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="sgrade">Salary / Pay Grade </label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <input type="text" id="sgrade" name="sgrade" class="form-control col-md-7 col-xs-12" 
												  <?php
												  if (isset ($_GET['sgrade'])) {
													  ?>value="<?php echo $_GET['sgrade'] ?>"<?php
												  }
												  ?>
												  >
												</div>
											  </div>
											  <div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="sstep">Step Increment (00-0) </label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <input type="text" id="sstep" name="sstep" class="form-control col-md-7 col-xs-12" 
												  <?php
												  if (isset ($_GET['sstep'])) {
													  ?>value="<?php echo $_GET['sstep'] ?>"<?php
												  }
												  ?>
												  >
												</div>
											  </div>
											  <div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="status">Status of Appointment </label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <input type="text" id="status" name="status" class="form-control col-md-7 col-xs-12" 
												  <?php
												  if (isset ($_GET['status'])) {
													  ?>value="<?php echo $_GET['status'] ?>"<?php
												  }
												  ?>
												  >
												</div>
											  </div>
											  <div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="govt">Government Service <span class="required">*</span></label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <select id="govt" name="govt" required="required" class="form-control col-md-7 col-xs-12">
													<?php
													if (isset ($_GET['govt'])) {
														if ($_GET['govt'] == 1) {
															?>
															<option value="1" Selected>Yes</option>
															<option value="0">No</option>
															<?php
														} elseif ($_GET['govt'] == 0) {
															?>
															<option value="1">Yes</option>
															<option value="0" Selected>No</option>
															<?php
														}
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
												  <input type="hidden" name="eid" value="<?php echo $eid ?>">
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

						<title>OTC - Add Employee-Experience</title>

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

				<title>OTC - Add Employee-Experience</title>

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


