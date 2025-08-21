<?php

include("db.php");
include("sqli.php");
include("html.php");
define('PAGE', 'add_eligibility.php'); 

session_start();

if (isset($_SESSION['user_id'])) {
	if (validate_level3(PAGE) == true) {

		if(isset($_POST['submitted'])) { // if submit button has been pressed
			
			$eid = filter_var($_POST['eid'], FILTER_SANITIZE_NUMBER_INT);
			$ename = filter_var($_POST['ename'], FILTER_SANITIZE_STRING);
			$rating = filter_var($_POST['rating'], FILTER_SANITIZE_NUMBER_FLOAT);
			$edate = filter_var($_POST['edate'], FILTER_SANITIZE_STRING);
			$eplace = filter_var($_POST['eplace'], FILTER_SANITIZE_STRING);
			$license_num = filter_var($_POST['license_num'], FILTER_SANITIZE_STRING);
			$license_exp = filter_var($_POST['license_exp'], FILTER_SANITIZE_STRING);
			
			// check if eligibility name is given
			if ($ename != "") { $a = TRUE; } 
			else { $a = FALSE; $message[] = "Please enter the name of eligibility."; }
			
			// If data pass all tests, proceed
			if ( $a ) {
				
				// check for double entry first
				$sql = "SELECT eeid 
						FROM employees_eligibilities 
						WHERE eid = '$eid' AND ename = '$ename' AND rating = '$rating' 
							AND edate = '$edate' AND eplace = '$eplace'";
				$res = query($sql); 
				$num = num_rows($res); 
				
				if ($num > 0) {
					?>
					<div class="alert alert-danger alert-dismissible fade in" role="alert">
						<strong>An entry with the same name of eligibility, rating, date and place of examination already exists! </strong><br /><br />
						<a href="employee.php?eid=<?php echo $eid ?>">Click here to view the record</a>
					</div>
					<?php
				} else {
					$sql = "INSERT INTO employees_eligibilities( eid, ename, rating, edate, eplace, license_num, license_exp )
							VALUES( '$eid', '$ename', '$rating', '$edate', '$eplace', '$license_num', '$license_exp' )";
					if (query($sql)) {
						$eeid = mysqli_insert_id($connection);
						// log the activity
						log_user(1, 'employees_eligibilities', $eeid);
						
						header("Location:employee.php?eid=".$eid);
						exit();
					} else {
						?>
						<div class="alert alert-danger alert-dismissible fade in" role="alert">
							<strong>Could not add employee-eligibility because: </strong><br />
							<b><?php echo mysqli_error($connection) ?></b><br />
							The query was <?php echo $sql ?>.<br />
						</div>
						<?PHP
					}
				}
			} else {
				$serialized_message = serialize($message);
				// redirect the user to index page
				header('location: add_eligibility.php?serialized_message='.$serialized_message.'&eid='.$eid.'&ename='.$ename.'&rating='.$rating.'&edate='.$edate.'&eplace='.$eplace.'&license_num='.$license_num.'&license_exp='.$license_exp);
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
				  
				<title>OTC | Add Employee's Eligibililty</title>

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
							<h3>Add Employee's Eligibililty</h3>
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
									<form action="add_eligibility.php" method="POST" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="ecode">Employee Name </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="ecode" name="ecode" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $erow[0] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="ename">Career Service / RA 1080 <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="ename" name="ename" required="required" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['ename'])) {
											  ?>value="<?php echo $_GET['ename'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="rating">Rating </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="rating" name="rating" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['rating'])) {
											  ?>value="<?php echo $_GET['rating'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="edate">Date of Examination </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="date" id="edate" name="edate" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['edate'])) {
											  ?>value="<?php echo $_GET['edate'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="eplace">Place of Examination </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="eplace" name="eplace" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['eplace'])) {
											  ?>value="<?php echo $_GET['eplace'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="license_num">License No. (If Applicable) </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="license_num" name="license_num" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['license_num'])) {
											  ?>value="<?php echo $_GET['license_num'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="license_exp">Date of Validity (If Applicable) </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="date" id="license_exp" name="license_exp" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['license_exp'])) {
											  ?>value="<?php echo $_GET['license_exp'] ?>"<?php
										  }
										  ?>
										  >
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
				$ename = filter_var($_POST['ename'], FILTER_SANITIZE_STRING);
				$rating = filter_var($_POST['rating'], FILTER_SANITIZE_NUMBER_FLOAT);
				$edate = filter_var($_POST['edate'], FILTER_SANITIZE_STRING);
				$eplace = filter_var($_POST['eplace'], FILTER_SANITIZE_STRING);
				$license_num = filter_var($_POST['license_num'], FILTER_SANITIZE_STRING);
				$license_exp = filter_var($_POST['license_exp'], FILTER_SANITIZE_STRING);
				
				// check if eligibility name is given
				if ($ename != "") { $a = TRUE; } 
				else { $a = FALSE; $message[] = "Please enter the name of eligibility."; }
				
				// If data pass all tests, proceed
				if ( $a ) {
					
					// check for double entry first
					$sql = "SELECT eeid 
							FROM employees_eligibilities 
							WHERE eid = '$eid' AND ename = '$ename' AND rating = '$rating' 
								AND edate = '$edate' AND eplace = '$eplace'";
					$res = query($sql); 
					$num = num_rows($res); 
					
					if ($num > 0) {
						?>
						<div class="alert alert-danger alert-dismissible fade in" role="alert">
							<strong>An entry with the same name of eligibility, rating, date and place of examination already exists! </strong><br /><br />
							<a href="employee.php?eid=<?php echo $eid ?>">Click here to view the record</a>
						</div>
						<?php
					} else {
						$sql = "INSERT INTO employees_eligibilities( eid, ename, rating, edate, eplace, license_num, license_exp )
								VALUES( '$eid', '$ename', '$rating', '$edate', '$eplace', '$license_num', '$license_exp' )";
						if (query($sql)) {
							$eeid = mysqli_insert_id($connection);
							// log the activity
							log_user(1, 'employees_eligibilities', $eeid);
							
							header("Location:employee.php?eid=".$eid);
							exit();
						} else {
							?>
							<div class="alert alert-danger alert-dismissible fade in" role="alert">
								<strong>Could not add employee-eligibility because: </strong><br />
								<b><?php echo mysqli_error($connection) ?></b><br />
								The query was <?php echo $sql ?>.<br />
							</div>
							<?PHP
						}
					}
				} else {
					$serialized_message = serialize($message);
					// redirect the user to index page
					header('location: add_eligibility.php?serialized_message='.$serialized_message.'&eid='.$eid.'&ename='.$ename.'&rating='.$rating.'&edate='.$edate.'&eplace='.$eplace.'&license_num='.$license_num.'&license_exp='.$license_exp);
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
						  
						<title>OTC | Add Employee's Eligibililty</title>

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
									<h3>Add Employee's Eligibililty</h3>
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
											<form action="add_eligibility.php" method="POST" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
											  <div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="ecode">Employee Name </label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <input type="text" id="ecode" name="ecode" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $erow[0] ?>">
												</div>
											  </div>
											  <div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="ename">Career Service / RA 1080 <span class="required">*</span></label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <input type="text" id="ename" name="ename" required="required" class="form-control col-md-7 col-xs-12" 
												  <?php
												  if (isset ($_GET['ename'])) {
													  ?>value="<?php echo $_GET['ename'] ?>"<?php
												  }
												  ?>
												  >
												</div>
											  </div>
											  <div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="rating">Rating </label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <input type="text" id="rating" name="rating" class="form-control col-md-7 col-xs-12" 
												  <?php
												  if (isset ($_GET['rating'])) {
													  ?>value="<?php echo $_GET['rating'] ?>"<?php
												  }
												  ?>
												  >
												</div>
											  </div>
											  <div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="edate">Date of Examination </label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <input type="date" id="edate" name="edate" class="form-control col-md-7 col-xs-12" 
												  <?php
												  if (isset ($_GET['edate'])) {
													  ?>value="<?php echo $_GET['edate'] ?>"<?php
												  }
												  ?>
												  >
												</div>
											  </div>
											  <div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="eplace">Place of Examination </label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <input type="text" id="eplace" name="eplace" class="form-control col-md-7 col-xs-12" 
												  <?php
												  if (isset ($_GET['eplace'])) {
													  ?>value="<?php echo $_GET['eplace'] ?>"<?php
												  }
												  ?>
												  >
												</div>
											  </div>
											  <div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="license_num">License No. (If Applicable) </label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <input type="text" id="license_num" name="license_num" class="form-control col-md-7 col-xs-12" 
												  <?php
												  if (isset ($_GET['license_num'])) {
													  ?>value="<?php echo $_GET['license_num'] ?>"<?php
												  }
												  ?>
												  >
												</div>
											  </div>
											  <div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="license_exp">Date of Validity (If Applicable) </label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <input type="date" id="license_exp" name="license_exp" class="form-control col-md-7 col-xs-12" 
												  <?php
												  if (isset ($_GET['license_exp'])) {
													  ?>value="<?php echo $_GET['license_exp'] ?>"<?php
												  }
												  ?>
												  >
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

						<title>OTC - Add Employee-Eligibility</title>

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

				<title>OTC - Add Employee-Eligibililty</title>

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

