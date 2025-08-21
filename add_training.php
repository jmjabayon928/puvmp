<?php

include("db.php");
include("sqli.php");
include("html.php");
define('PAGE', 'add_training.php'); 

session_start();

if (isset($_SESSION['user_id'])) {
	if (validate_level3(PAGE) == true) {

		if(isset($_POST['submitted'])) { // if submit button has been pressed
			
			$eid = filter_var($_POST['eid'], FILTER_SANITIZE_NUMBER_INT);
			$tname = filter_var($_POST['tname'], FILTER_SANITIZE_STRING);
			$tstart = filter_var($_POST['tstart'], FILTER_SANITIZE_STRING);
			$tend = filter_var($_POST['tend'], FILTER_SANITIZE_STRING);
			$nhours = filter_var($_POST['nhours'], FILTER_SANITIZE_NUMBER_INT);
			$ttype = filter_var($_POST['ttype'], FILTER_SANITIZE_STRING);
			$sponsor = filter_var($_POST['sponsor'], FILTER_SANITIZE_STRING);
			
			// check if tname is given
			if ($tname != "") { $a = TRUE; } 
			else { $a = FALSE; $message[] = "Please enter the name of training."; }
			// check if tstart is given
			if ($tstart != "") { $b = TRUE; } 
			else { $b = FALSE; $message[] = "Please enter the start date of training."; }
			// check if tend is given
			if ($tend != "") { $c = TRUE; } 
			else { $c = FALSE; $message[] = "Please enter the end date of training."; }
			// check if nhours is given
			if ($nhours != "") { $d = TRUE; } 
			else { $d = FALSE; $message[] = "Please enter the number of hours of training."; }
			// check if ttype is given
			if ($ttype != "") { $e = TRUE; } 
			else { $e = FALSE; $message[] = "Please enter the type of training."; }
			// check if sponsor is given
			if ($sponsor != "") { $f = TRUE; } 
			else { $f = FALSE; $message[] = "Please enter the sponsor of training."; }
			
			// If data pass all tests, proceed
			if ( $a && $b && $c && $d && $e && $f ) {
				
				// check for double entry first
				$sql = "SELECT tid 
						FROM employees_trainings 
						WHERE eid = '$eid' AND tname = '$tname' AND tstart = '$tstart' AND tend = '$tend'";
				$res = query($sql); 
				$num = num_rows($res); 
				
				if ($num > 0) {
					?>
					<div class="alert alert-danger alert-dismissible fade in" role="alert">
						<strong>A record with the same employee, training, start & end dates of training already exists! </strong><br /><br />
						<a href="employee.php?eid=<?php echo $eid ?>">Click here to view the record</a>
					</div>
					<?php
				} else {
					$sql = "INSERT INTO employees_trainings( eid, tname, tstart, tend, nhours, ttype, sponsor )
							VALUES( '$eid', '$tname', '$tstart', '$tend', '$nhours', '$ttype', '$sponsor' )";
					if (query($sql)) {
						$tid = mysqli_insert_id($connection);
						// log the activity
						log_user(1, 'employees_trainings', $tid);
						
						header("Location:employee.php?eid=".$eid);
						exit();
					} else {
						?>
						<div class="alert alert-danger alert-dismissible fade in" role="alert">
							<strong>Could not add employee's training because: </strong><br />
							<b><?php echo mysqli_error($connection) ?></b><br />
							The query was <?php echo $sql ?>.<br />
						</div>
						<?PHP
					}
				}
			} else {
				$serialized_message = serialize($message);
				// redirect the user to index page
				header('location: add_training.php?serialized_message='.$serialized_message.'&eid='.$eid.'&tname='.$tname.'&tstart='.$tstart.'&tend='.$tend.'&nhours='.$nhours.'&ttype='.$ttype.'&sponsor='.$sponsor);
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
				  
				<title>OTC | Add Employee's Training</title>

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
							<h3>Add Employee's Training</h3>
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
									<form action="add_training.php" method="POST" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="ecode">Employee Name </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="ecode" name="ecode" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $erow[0] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="tname">Title of LD / Training <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="tname" name="tname" required="required" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['tname'])) {
											  ?>value="<?php echo $_GET['tname'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="tstart">From <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="date" id="tstart" name="tstart" required="required" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['tstart'])) {
											  ?>value="<?php echo $_GET['tstart'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="tend">To <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="date" id="tend" name="tend" required="required" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['tend'])) {
											  ?>value="<?php echo $_GET['tend'] ?>"<?php
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
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="ttype">Type of LD / Training <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="ttype" name="ttype" required="required" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['ttype'])) {
											  ?>value="<?php echo $_GET['ttype'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="sponsor">Conducted / Sponsored By <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="sponsor" name="sponsor" required="required" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['sponsor'])) {
											  ?>value="<?php echo $_GET['sponsor'] ?>"<?php
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
				$tname = filter_var($_POST['tname'], FILTER_SANITIZE_STRING);
				$tstart = filter_var($_POST['tstart'], FILTER_SANITIZE_STRING);
				$tend = filter_var($_POST['tend'], FILTER_SANITIZE_STRING);
				$nhours = filter_var($_POST['nhours'], FILTER_SANITIZE_NUMBER_INT);
				$ttype = filter_var($_POST['ttype'], FILTER_SANITIZE_STRING);
				$sponsor = filter_var($_POST['sponsor'], FILTER_SANITIZE_STRING);
				
				// check if tname is given
				if ($tname != "") { $a = TRUE; } 
				else { $a = FALSE; $message[] = "Please enter the name of training."; }
				// check if tstart is given
				if ($tstart != "") { $b = TRUE; } 
				else { $b = FALSE; $message[] = "Please enter the start date of training."; }
				// check if tend is given
				if ($tend != "") { $c = TRUE; } 
				else { $c = FALSE; $message[] = "Please enter the end date of training."; }
				// check if nhours is given
				if ($nhours != "") { $d = TRUE; } 
				else { $d = FALSE; $message[] = "Please enter the number of hours of training."; }
				// check if ttype is given
				if ($ttype != "") { $e = TRUE; } 
				else { $e = FALSE; $message[] = "Please enter the type of training."; }
				// check if sponsor is given
				if ($sponsor != "") { $f = TRUE; } 
				else { $f = FALSE; $message[] = "Please enter the sponsor of training."; }
				
				// If data pass all tests, proceed
				if ( $a && $b && $c && $d && $e && $f ) {
					
					// check for double entry first
					$sql = "SELECT tid 
							FROM employees_trainings 
							WHERE eid = '$eid' AND tname = '$tname' AND tstart = '$tstart' AND tend = '$tend'";
					$res = query($sql); 
					$num = num_rows($res); 
					
					if ($num > 0) {
						?>
						<div class="alert alert-danger alert-dismissible fade in" role="alert">
							<strong>A record with the same employee, training, start & end dates of training already exists! </strong><br /><br />
							<a href="employee.php?eid=<?php echo $eid ?>">Click here to view the record</a>
						</div>
						<?php
					} else {
						$sql = "INSERT INTO employees_trainings( eid, tname, tstart, tend, nhours, ttype, sponsor )
								VALUES( '$eid', '$tname', '$tstart', '$tend', '$nhours', '$ttype', '$sponsor' )";
						if (query($sql)) {
							$tid = mysqli_insert_id($connection);
							// log the activity
							log_user(1, 'employees_trainings', $tid);
							
							header("Location:employee.php?eid=".$eid);
							exit();
						} else {
							?>
							<div class="alert alert-danger alert-dismissible fade in" role="alert">
								<strong>Could not add employee's training because: </strong><br />
								<b><?php echo mysqli_error($connection) ?></b><br />
								The query was <?php echo $sql ?>.<br />
							</div>
							<?PHP
						}
					}
				} else {
					$serialized_message = serialize($message);
					// redirect the user to index page
					header('location: add_training.php?serialized_message='.$serialized_message.'&eid='.$eid.'&tname='.$tname.'&tstart='.$tstart.'&tend='.$tend.'&nhours='.$nhours.'&ttype='.$ttype.'&sponsor='.$sponsor);
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
						  
						<title>OTC | Add Employee's Training</title>

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
									<h3>Add Employee's Training</h3>
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
											<form action="add_training.php" method="POST" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
											  <div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="ecode">Employee Name </label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <input type="text" id="ecode" name="ecode" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $erow[0] ?>">
												</div>
											  </div>
											  <div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="tname">Title of LD / Training <span class="required">*</span></label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <input type="text" id="tname" name="tname" required="required" class="form-control col-md-7 col-xs-12" 
												  <?php
												  if (isset ($_GET['tname'])) {
													  ?>value="<?php echo $_GET['tname'] ?>"<?php
												  }
												  ?>
												  >
												</div>
											  </div>
											  <div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="tstart">From <span class="required">*</span></label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <input type="date" id="tstart" name="tstart" required="required" class="form-control col-md-7 col-xs-12" 
												  <?php
												  if (isset ($_GET['tstart'])) {
													  ?>value="<?php echo $_GET['tstart'] ?>"<?php
												  }
												  ?>
												  >
												</div>
											  </div>
											  <div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="tend">To <span class="required">*</span></label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <input type="date" id="tend" name="tend" required="required" class="form-control col-md-7 col-xs-12" 
												  <?php
												  if (isset ($_GET['tend'])) {
													  ?>value="<?php echo $_GET['tend'] ?>"<?php
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
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="ttype">Type of LD / Training <span class="required">*</span></label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <input type="text" id="ttype" name="ttype" required="required" class="form-control col-md-7 col-xs-12" 
												  <?php
												  if (isset ($_GET['ttype'])) {
													  ?>value="<?php echo $_GET['ttype'] ?>"<?php
												  }
												  ?>
												  >
												</div>
											  </div>
											  <div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="sponsor">Conducted / Sponsored By <span class="required">*</span></label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <input type="text" id="sponsor" name="sponsor" required="required" class="form-control col-md-7 col-xs-12" 
												  <?php
												  if (isset ($_GET['sponsor'])) {
													  ?>value="<?php echo $_GET['sponsor'] ?>"<?php
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

						<title>OTC - Add Employee-Training</title>

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

				<title>OTC - Add Employee-Training</title>

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

