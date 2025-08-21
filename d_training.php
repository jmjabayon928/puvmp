<?php

include("db.php");
include("sqli.php");
include("html.php");
define('PAGE', 'd_training.php'); 

$tid = $_GET['tid'];

session_start();

if (isset($_SESSION['user_id'])) {
	if (validate_level3(PAGE) == true) {

		if(isset($_POST['submitted'])) { // if submit button has been pressed
			$tid = filter_var($_POST['tid'], FILTER_SANITIZE_NUMBER_INT);
			
			// get the cid of cbid 
			$sql = "SELECT eid FROM employees_trainings WHERE tid = '$tid'";
			$cres = query($sql); 
			$crow = fetch_array($cres); 
			$eid = $crow[0]; 
			
			$sql = "DELETE FROM employees_trainings
					WHERE tid = '$tid'
					LIMIT 1";
			if (query($sql)) {
				// log the activity
				log_user(3, 'employees_trainings', $tid);
				
				header("Location:employee.php?eid=".$eid."&success=1");
				exit();
			} else {
				?>
				<div class="error">Could not delete employee's training because: <b><?PHP echo mysqli_error($connection) ?></b>.
					<br />The query was <?PHP echo $sql ?>.</div><br />
				<?PHP
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
				  
				<title>OTC | Delete Employee's LD / Training</title>

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
					
					$sql = "SELECT * FROM employees_trainings WHERE tid = '$tid'";
					$res = query($sql); 
					$row = fetch_array($res); 
					
					$sql = "SELECT CONCAT(lname,', ',fname) FROM employees WHERE eid = '$row[1]'";
					$eres = query($sql); 
					$erow = fetch_array($eres); 
					?>

					<!-- page content -->
					<div class="right_col" role="main">
					  <div class="">
						<div class="page-title">
						  <div class="title_left">
							<h3>Delete Employee's LD / Training</h3>
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
								
									<form action="d_training.php" method="POST" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="ecode">Employee Name </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="ecode" name="ecode" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $erow[0] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="tname">Title of LD / Training </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="tname" name="tname" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $row[2] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="tstart">From </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="date" id="tstart" name="tstart" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $row[3] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="tend">To </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="date" id="tend" name="tend" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $row[4] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="nhours">Number of Hours </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="nhours" name="nhours" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $row[5] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="ttype">Type of LD / Training </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="ttype" name="ttype" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $row[6] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="sponsor">Conducted / Sponsored By </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="sponsor" name="sponsor" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $row[7] ?>">
										</div>
									  </div>
									  <div class="ln_solid"></div>
									  <div class="form-group">
										<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
										  <input type="hidden" name="submitted" value="1">
										  <input type="hidden" name="tid" value="<?php echo $tid ?>">
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
				$tid = filter_var($_POST['tid'], FILTER_SANITIZE_NUMBER_INT);
				
				// get the cid of cbid 
				$sql = "SELECT eid FROM employees_trainings WHERE tid = '$tid'";
				$cres = query($sql); 
				$crow = fetch_array($cres); 
				$eid = $crow[0]; 
				
				$sql = "DELETE FROM employees_trainings
						WHERE tid = '$tid'
						LIMIT 1";
				if (query($sql)) {
					// log the activity
					log_user(3, 'employees_trainings', $tid);
					
					header("Location:employee.php?eid=".$eid."&success=1");
					exit();
				} else {
					?>
					<div class="error">Could not delete employee's training because: <b><?PHP echo mysqli_error($connection) ?></b>.
						<br />The query was <?PHP echo $sql ?>.</div><br />
					<?PHP
				}
			} else {
				$tid = $_GET['tid'];
				
				$sql = "SELECT eid FROM employees_trainings WHERE tid = '$tid'";
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
						  
						<title>OTC | Delete Employee's LD / Training</title>

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
							
							$sql = "SELECT * FROM employees_trainings WHERE tid = '$tid'";
							$res = query($sql); 
							$row = fetch_array($res); 
							
							$sql = "SELECT CONCAT(lname,', ',fname) FROM employees WHERE eid = '$row[1]'";
							$eres = query($sql); 
							$erow = fetch_array($eres); 
							?>

							<!-- page content -->
							<div class="right_col" role="main">
							  <div class="">
								<div class="page-title">
								  <div class="title_left">
									<h3>Delete Employee's LD / Training</h3>
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
										
											<form action="d_training.php" method="POST" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
											  <div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="ecode">Employee Name </label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <input type="text" id="ecode" name="ecode" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $erow[0] ?>">
												</div>
											  </div>
											  <div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="tname">Title of LD / Training </label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <input type="text" id="tname" name="tname" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $row[2] ?>">
												</div>
											  </div>
											  <div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="tstart">From </label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <input type="date" id="tstart" name="tstart" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $row[3] ?>">
												</div>
											  </div>
											  <div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="tend">To </label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <input type="date" id="tend" name="tend" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $row[4] ?>">
												</div>
											  </div>
											  <div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="nhours">Number of Hours </label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <input type="text" id="nhours" name="nhours" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $row[5] ?>">
												</div>
											  </div>
											  <div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="ttype">Type of LD / Training </label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <input type="text" id="ttype" name="ttype" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $row[6] ?>">
												</div>
											  </div>
											  <div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="sponsor">Conducted / Sponsored By </label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <input type="text" id="sponsor" name="sponsor" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $row[7] ?>">
												</div>
											  </div>
											  <div class="ln_solid"></div>
											  <div class="form-group">
												<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
												  <input type="hidden" name="submitted" value="1">
												  <input type="hidden" name="tid" value="<?php echo $tid ?>">
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

						<title>OTC - Delete Employee-Training</title>

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

				<title>OTC - Delete Employee-Training</title>

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

