<?php

include("db.php");
include("sqli.php");
include("html.php");
define('PAGE', 'add_vwork.php'); 

session_start();

if (isset($_SESSION['user_id'])) {
	if (validate_level3(PAGE) == true) {

		if(isset($_POST['submitted'])) { // if submit button has been pressed
			
			$eid = filter_var($_POST['eid'], FILTER_SANITIZE_NUMBER_INT);
			$wname = filter_var($_POST['wname'], FILTER_SANITIZE_STRING);
			$waddr = filter_var($_POST['waddr'], FILTER_SANITIZE_STRING);
			$wstart = filter_var($_POST['wstart'], FILTER_SANITIZE_STRING);
			$wend = filter_var($_POST['wend'], FILTER_SANITIZE_STRING);
			$nhours = filter_var($_POST['nhours'], FILTER_SANITIZE_NUMBER_INT);
			$job_desc = filter_var($_POST['job_desc'], FILTER_SANITIZE_STRING);
			
			// check if wname is given
			if ($wname != "") { $a = TRUE; } 
			else { $a = FALSE; $message[] = "Please enter the name of voluntary work."; }
			// check if waddr is given
			if ($waddr != "") { $b = TRUE; } 
			else { $b = FALSE; $message[] = "Please enter the address of voluntary work."; }
			// check if wstart is given
			if ($wstart != "") { $c = TRUE; } 
			else { $c = FALSE; $message[] = "Please enter the start of voluntary work."; }
			// check if wend is given
			if ($wend != "") { $d = TRUE; } 
			else { $d = FALSE; $message[] = "Please enter the end date of voluntary work."; }
			// check if nhours is given
			if ($nhours != "") { $e = TRUE; } 
			else { $e = FALSE; $message[] = "Please enter the number of hourse of voluntary work."; }
			// check if job_desc is given
			if ($job_desc != "") { $f = TRUE; } 
			else { $f = FALSE; $message[] = "Please enter the job description of voluntary work."; }
			
			// If data pass all tests, proceed
			if ( $a && $b && $c && $d && $e && $f ) {
				
				// check for double entry first
				$sql = "SELECT wid 
						FROM employees_voluntaries 
						WHERE eid = '$eid' AND wname = '$wname' AND waddr = '$waddr' AND wstart = '$wstart' AND wend = '$wend'";
				$res = query($sql); 
				$num = num_rows($res); 
				
				if ($num > 0) {
					?>
					<div class="alert alert-danger alert-dismissible fade in" role="alert">
						<strong>A record with the same voluntary work already exists! </strong><br /><br />
						<a href="employee.php?eid=<?php echo $eid ?>">Click here to view the record</a>
					</div>
					<?php
				} else {
					$sql = "INSERT INTO employees_voluntaries( eid, wname, waddr, wstart, wend, nhours, job_desc )
							VALUES( '$eid', '$wname', '$waddr', '$wstart', '$wend', '$nhours', '$job_desc' )";
					if (query($sql)) {
						$wid = mysqli_insert_id($connection);
						// log the activity
						log_user(1, 'employees_voluntaries', $wid);
						
						header("Location:employee.php?eid=".$eid);
						exit();
					} else {
						?>
						<div class="alert alert-danger alert-dismissible fade in" role="alert">
							<strong>Could not add employee's voluntary work because: </strong><br />
							<b><?php echo mysqli_error($connection) ?></b><br />
							The query was <?php echo $sql ?>.<br />
						</div>
						<?PHP
					}
				}
			} else {
				$serialized_message = serialize($message);
				// redirect the user to PHP_SELF
				header('location: add_vwork.php?serialized_message='.$serialized_message.'&eid='.$eid.'&wname='.$wname.'&waddr='.$waddr.'&wstart='.$wstart.'&wend='.$wend.'&nhours='.$nhours.'&job_desc='.$job_desc);
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
				  
				<title>OTC | Add Employee's Voluntary Works</title>

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
							<h3>Add Employee's Voluntary Works</h3>
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
									<form action="add_vwork.php" method="POST" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="ecode">Employee Name </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="ecode" name="ecode" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $erow[0] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="wname">Name of Organization <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="wname" name="wname" required="required" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['wname'])) {
											  ?>value="<?php echo $_GET['wname'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="waddr">Address of Organization <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="waddr" name="waddr" required="required" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['waddr'])) {
											  ?>value="<?php echo $_GET['waddr'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="wstart">From <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="date" id="wstart" name="wstart" required="required" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['wstart'])) {
											  ?>value="<?php echo $_GET['wstart'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="wend">To <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="date" id="wend" name="wend" required="required" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['wend'])) {
											  ?>value="<?php echo $_GET['wend'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="nhours">Number of Hours <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="nhours" name="nhours" required="required" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['nhours'])) {
											  ?>value="<?php echo $_GET['nhours'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="job_desc">Position / Nature of Work <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="job_desc" name="job_desc" required="required" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['job_desc'])) {
											  ?>value="<?php echo $_GET['job_desc'] ?>"<?php
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
				$wname = filter_var($_POST['wname'], FILTER_SANITIZE_STRING);
				$waddr = filter_var($_POST['waddr'], FILTER_SANITIZE_STRING);
				$wstart = filter_var($_POST['wstart'], FILTER_SANITIZE_STRING);
				$wend = filter_var($_POST['wend'], FILTER_SANITIZE_STRING);
				$nhours = filter_var($_POST['nhours'], FILTER_SANITIZE_NUMBER_INT);
				$job_desc = filter_var($_POST['job_desc'], FILTER_SANITIZE_STRING);
				
				// check if wname is given
				if ($wname != "") { $a = TRUE; } 
				else { $a = FALSE; $message[] = "Please enter the name of voluntary work."; }
				// check if waddr is given
				if ($waddr != "") { $b = TRUE; } 
				else { $b = FALSE; $message[] = "Please enter the address of voluntary work."; }
				// check if wstart is given
				if ($wstart != "") { $c = TRUE; } 
				else { $c = FALSE; $message[] = "Please enter the start of voluntary work."; }
				// check if wend is given
				if ($wend != "") { $d = TRUE; } 
				else { $d = FALSE; $message[] = "Please enter the end date of voluntary work."; }
				// check if nhours is given
				if ($nhours != "") { $e = TRUE; } 
				else { $e = FALSE; $message[] = "Please enter the number of hourse of voluntary work."; }
				// check if job_desc is given
				if ($job_desc != "") { $f = TRUE; } 
				else { $f = FALSE; $message[] = "Please enter the job description of voluntary work."; }
				
				// If data pass all tests, proceed
				if ( $a && $b && $c && $d && $e && $f ) {
					
					// check for double entry first
					$sql = "SELECT wid 
							FROM employees_voluntaries 
							WHERE eid = '$eid' AND wname = '$wname' AND waddr = '$waddr' AND wstart = '$wstart' AND wend = '$wend'";
					$res = query($sql); 
					$num = num_rows($res); 
					
					if ($num > 0) {
						?>
						<div class="alert alert-danger alert-dismissible fade in" role="alert">
							<strong>A record with the same voluntary work already exists! </strong><br /><br />
							<a href="employee.php?eid=<?php echo $eid ?>">Click here to view the record</a>
						</div>
						<?php
					} else {
						$sql = "INSERT INTO employees_voluntaries( eid, wname, waddr, wstart, wend, nhours, job_desc )
								VALUES( '$eid', '$wname', '$waddr', '$wstart', '$wend', '$nhours', '$job_desc' )";
						if (query($sql)) {
							$wid = mysqli_insert_id($connection);
							// log the activity
							log_user(1, 'employees_voluntaries', $wid);
							
							header("Location:employee.php?eid=".$eid);
							exit();
						} else {
							?>
							<div class="alert alert-danger alert-dismissible fade in" role="alert">
								<strong>Could not add employee's voluntary work because: </strong><br />
								<b><?php echo mysqli_error($connection) ?></b><br />
								The query was <?php echo $sql ?>.<br />
							</div>
							<?PHP
						}
					}
				} else {
					$serialized_message = serialize($message);
					// redirect the user to PHP_SELF
					header('location: add_vwork.php?serialized_message='.$serialized_message.'&eid='.$eid.'&wname='.$wname.'&waddr='.$waddr.'&wstart='.$wstart.'&wend='.$wend.'&nhours='.$nhours.'&job_desc='.$job_desc);
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
						  
						<title>OTC | Add Employee's Voluntary Works</title>

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
									<h3>Add Employee's Voluntary Works</h3>
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
											<form action="add_vwork.php" method="POST" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
											  <div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="ecode">Employee Name </label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <input type="text" id="ecode" name="ecode" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $erow[0] ?>">
												</div>
											  </div>
											  <div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="wname">Name of Organization <span class="required">*</span></label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <input type="text" id="wname" name="wname" required="required" class="form-control col-md-7 col-xs-12" 
												  <?php
												  if (isset ($_GET['wname'])) {
													  ?>value="<?php echo $_GET['wname'] ?>"<?php
												  }
												  ?>
												  >
												</div>
											  </div>
											  <div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="waddr">Address of Organization <span class="required">*</span></label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <input type="text" id="waddr" name="waddr" required="required" class="form-control col-md-7 col-xs-12" 
												  <?php
												  if (isset ($_GET['waddr'])) {
													  ?>value="<?php echo $_GET['waddr'] ?>"<?php
												  }
												  ?>
												  >
												</div>
											  </div>
											  <div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="wstart">From <span class="required">*</span></label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <input type="date" id="wstart" name="wstart" required="required" class="form-control col-md-7 col-xs-12" 
												  <?php
												  if (isset ($_GET['wstart'])) {
													  ?>value="<?php echo $_GET['wstart'] ?>"<?php
												  }
												  ?>
												  >
												</div>
											  </div>
											  <div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="wend">To <span class="required">*</span></label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <input type="date" id="wend" name="wend" required="required" class="form-control col-md-7 col-xs-12" 
												  <?php
												  if (isset ($_GET['wend'])) {
													  ?>value="<?php echo $_GET['wend'] ?>"<?php
												  }
												  ?>
												  >
												</div>
											  </div>
											  <div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="nhours">Number of Hours <span class="required">*</span></label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <input type="text" id="nhours" name="nhours" required="required" class="form-control col-md-7 col-xs-12" 
												  <?php
												  if (isset ($_GET['nhours'])) {
													  ?>value="<?php echo $_GET['nhours'] ?>"<?php
												  }
												  ?>
												  >
												</div>
											  </div>
											  <div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="job_desc">Position / Nature of Work <span class="required">*</span></label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <input type="text" id="job_desc" name="job_desc" required="required" class="form-control col-md-7 col-xs-12" 
												  <?php
												  if (isset ($_GET['job_desc'])) {
													  ?>value="<?php echo $_GET['job_desc'] ?>"<?php
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

						<title>OTC - Add Employee-Organization</title>

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

				<title>OTC - Add Employee-Organization</title>

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

