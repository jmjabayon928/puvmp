<?php

include("db.php");
include("sqli.php");
include("html.php");
define('PAGE', 'e_eligibility.php'); 

session_start();

if (isset($_SESSION['user_id'])) {
	if (validate_level3(PAGE) == true) {

		if(isset($_POST['submitted'])) { // if submit button has been pressed

			$eeid = filter_var($_POST['eeid'], FILTER_SANITIZE_NUMBER_INT);
			$ename = filter_var($_POST['ename'], FILTER_SANITIZE_STRING);
			$erating = real_escape_string($_POST['erating']);
			$edate = filter_var($_POST['edate'], FILTER_SANITIZE_STRING);
			$eplace = filter_var($_POST['eplace'], FILTER_SANITIZE_STRING);
			$license_num = filter_var($_POST['license_num'], FILTER_SANITIZE_STRING);
			$license_exp = filter_var($_POST['license_exp'], FILTER_SANITIZE_STRING);
			
			// check if eligibility name is given
			if ($ename != "") { $a = TRUE; } 
			else { $a = FALSE; $message[] = "Please enter the name of eligibility."; }
			// check if erating is given
			if ($erating != 0 && $erating != '') { $b = TRUE; } 
			else { $b = FALSE; $message[] = "Please enter the rating."; }
			// check if date of eligibility is given
			if ($edate != "") { $c = TRUE; } 
			else { $c = FALSE; $message[] = "Please enter the date of eligibility."; }
			// check if place of exam is given
			if ($eplace != '') { $d = TRUE; } 
			else { $d = FALSE; $message[] = "Please enter the place of examination."; }
			
			// If data pass all tests, proceed
			if ( $a && $b && $c && $d ) {
				// update data
				$sql = "UPDATE employees_eligibilities
						SET ename = '$ename',
							rating = '$erating',
							edate = '$edate',
							eplace = '$eplace',
							license_num = '$license_num', 
							license_exp = '$license_exp' 
						WHERE eeid = '$eeid'
						LIMIT 1";
				if (query($sql)) {
					// log the activity
					log_user(2, 'employees_eligibilities', $eeid);
					
					$sql = "SELECT eid FROM employees_eligibilities WHERE eeid = '$eeid'";
					$res = query($sql); 
					$row = fetch_array($res); 
					$eid = $row[0]; 
					
					header("Location:employee.php?eid=".$eid."&success=1");
					exit();
				} else {
					?>
						<div class="alert alert-danger alert-dismissible fade in" role="alert">
							<strong>Could not update employee-eligibility because: </strong><br />
							<b><?php echo mysqli_error($connection) ?></b><br />
							The query was <?php echo $sql ?>.<br />
						</div>
					<?PHP
				}
			} else {
				$serialized_message = serialize($message);
				// redirect the user to index page
				header('location: e_eligibility.php?serialized_message='.$serialized_message.'&eeid='.$eeid);
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
				  
				<title>OTC | Update Employee's Eligibililty</title>

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
							<h3>Update Employee's Eligibililty</h3>
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
										
										$sql = "SELECT * FROM employees_eligibilities WHERE eeid = '$eeid'";
										$res = query($sql); 
										$row = fetch_array($res); 
										
										$sql = "SELECT CONCAT(lname,', ',fname) FROM employees WHERE eid = '$row[1]'";
										$eres = query($sql); 
										$erow = fetch_array($eres); 
									}
									?>
								
									<form action="e_eligibility.php" method="POST" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="ecode">Employee Name </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="ecode" name="ecode" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $erow[0] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="ename">Career Service / RA 1080 <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="ename" name="ename" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $row[2] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="erating">Rating <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="erating" name="erating" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $row[3] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="edate">Date of Examination <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="date" id="edate" name="edate" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $row[4] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="eplace">Place of Examination <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="eplace" name="eplace" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $row[5] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="license_num">License No. (If Applicable) </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="license_num" name="license_num" class="form-control col-md-7 col-xs-12" value="<?php echo $row[6] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="license_exp">Date of Validity (If Applicable) </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="date" id="license_exp" name="license_exp" class="form-control col-md-7 col-xs-12" value="<?php echo $row[7] ?>">
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
				$ename = filter_var($_POST['ename'], FILTER_SANITIZE_STRING);
				$erating = real_escape_string($_POST['erating']);
				$edate = filter_var($_POST['edate'], FILTER_SANITIZE_STRING);
				$eplace = filter_var($_POST['eplace'], FILTER_SANITIZE_STRING);
				$license_num = filter_var($_POST['license_num'], FILTER_SANITIZE_STRING);
				$license_exp = filter_var($_POST['license_exp'], FILTER_SANITIZE_STRING);
				
				// check if eligibility name is given
				if ($ename != "") { $a = TRUE; } 
				else { $a = FALSE; $message[] = "Please enter the name of eligibility."; }
				// check if erating is given
				if ($erating != 0 && $erating != '') { $b = TRUE; } 
				else { $b = FALSE; $message[] = "Please enter the rating."; }
				// check if date of eligibility is given
				if ($edate != "") { $c = TRUE; } 
				else { $c = FALSE; $message[] = "Please enter the date of eligibility."; }
				// check if place of exam is given
				if ($eplace != '') { $d = TRUE; } 
				else { $d = FALSE; $message[] = "Please enter the place of examination."; }
				
				// If data pass all tests, proceed
				if ( $a && $b && $c && $d ) {
					// update data
					$sql = "UPDATE employees_eligibilities
							SET ename = '$ename',
								rating = '$erating',
								edate = '$edate',
								eplace = '$eplace',
								license_num = '$license_num', 
								license_exp = '$license_exp' 
							WHERE eeid = '$eeid'
							LIMIT 1";
					if (query($sql)) {
						// log the activity
						log_user(2, 'employees_eligibilities', $eeid);
						
						$sql = "SELECT eid FROM employees_eligibilities WHERE eeid = '$eeid'";
						$res = query($sql); 
						$row = fetch_array($res); 
						$eid = $row[0]; 
						
						header("Location:employee.php?eid=".$eid."&success=1");
						exit();
					} else {
						?>
							<div class="alert alert-danger alert-dismissible fade in" role="alert">
								<strong>Could not update employee-eligibility because: </strong><br />
								<b><?php echo mysqli_error($connection) ?></b><br />
								The query was <?php echo $sql ?>.<br />
							</div>
						<?PHP
					}
				} else {
					$serialized_message = serialize($message);
					// redirect the user to index page
					header('location: e_eligibility.php?serialized_message='.$serialized_message.'&eeid='.$eeid);
					exit();
				}
			} else {
				$eeid = $_GET['eeid'];
				
				$sql = "SELECT eid FROM employees_eligibilities WHERE eeid = '$eeid'";
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
						  
						<title>OTC | Update Employee's Eligibililty</title>

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
									<h3>Update Employee's Eligibililty</h3>
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
												
												$sql = "SELECT * FROM employees_eligibilities WHERE eeid = '$eeid'";
												$res = query($sql); 
												$row = fetch_array($res); 
												
												$sql = "SELECT CONCAT(lname,', ',fname) FROM employees WHERE eid = '$row[1]'";
												$eres = query($sql); 
												$erow = fetch_array($eres); 
											}
											?>
										
											<form action="e_eligibility.php" method="POST" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
											  <div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="ecode">Employee Name </label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <input type="text" id="ecode" name="ecode" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $erow[0] ?>">
												</div>
											  </div>
											  <div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="ename">Career Service / RA 1080 <span class="required">*</span></label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <input type="text" id="ename" name="ename" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $row[2] ?>">
												</div>
											  </div>
											  <div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="erating">Rating <span class="required">*</span></label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <input type="text" id="erating" name="erating" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $row[3] ?>">
												</div>
											  </div>
											  <div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="edate">Date of Examination <span class="required">*</span></label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <input type="date" id="edate" name="edate" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $row[4] ?>">
												</div>
											  </div>
											  <div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="eplace">Place of Examination <span class="required">*</span></label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <input type="text" id="eplace" name="eplace" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $row[5] ?>">
												</div>
											  </div>
											  <div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="license_num">License No. (If Applicable) </label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <input type="text" id="license_num" name="license_num" class="form-control col-md-7 col-xs-12" value="<?php echo $row[6] ?>">
												</div>
											  </div>
											  <div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="license_exp">Date of Validity (If Applicable) </label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <input type="date" id="license_exp" name="license_exp" class="form-control col-md-7 col-xs-12" value="<?php echo $row[7] ?>">
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

						<title>OTC - Update Employee-Eligibility</title>

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

				<title>OTC - Update Employee-Eligibililty</title>

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

