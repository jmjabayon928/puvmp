<?php

include("db.php");
include("sqli.php");
include("html.php");
define('PAGE', 'd_leave.php'); 

session_start();

if (isset($_SESSION['user_id'])) {
	if (validate_level3(PAGE) == true) {

		if(isset($_POST['submitted'])) { // if submit button has been pressed
			$aid = filter_var($_POST['aid'], FILTER_SANITIZE_NUMBER_INT);
			
			// get the details of the leave
			$sql = "SELECT eid, TO_DAYS(fr_date), TO_DAYS(to_date), stats 
					FROM internals_leave_applications
					WHERE aid = '$aid'";
			$res = query($sql); 
			$row = fetch_array($res); 
			
			$eid = $row[0]; $int_start = $row[1]; $int_end = $row[2]; $stats = $row[3]; 
			
			$sql = "DELETE FROM internals_leave_applications
					WHERE aid = '$aid'
					LIMIT 1";
			if (query($sql)) {
				// log the activity
				log_user(3, 'internals_leave_applications', $aid);
				
				if ($stats == 1) { // if status is approved, then there is an attendance to delete
					for ($x=$int_start; $x<=$int_end; $x++) {
						// check if an attendance already exists
						$sql = "SELECT aid, am_in, am_out, pm_in, pm_out
								FROM attendance 
								WHERE eid = '$eid' AND TO_DAYS(adate) = '$x'";
						$rres = query($sql); 
						$rnum = num_rows($rres); 
						
						if ($rnum > 0) {
							$rrow = fetch_array($rres); 
							$r_aid = $rrow[0]; $am_in = $rrow[1]; $am_out = $rrow[2]; $pm_in = $rrow[3]; $pm_out = $rrow[4]; 
							
							if ($am_in != '00:00:00' && $am_in != '00:00:00' && $am_in != '00:00:00' && $am_in != '00:00:00') {
								$sql = "UPDATE attendance SET status = 1, remarks = '' WHERE aid = '$aid' LIMIT 1";
								query($sql); 
								
								update_attendance($aid); 
								
							} elseif ($am_in != '00:00:00' || $am_in != '00:00:00' || $am_in != '00:00:00' || $am_in != '00:00:00') {
								$sql = "UPDATE attendance SET status = -1, remarks = '' WHERE aid = '$aid' LIMIT 1";
								query($sql); 
								
								update_attendance($aid); 
								
							} elseif ($am_in == '00:00:00' && $am_in == '00:00:00' && $am_in == '00:00:00' && $am_in == '00:00:00') {
								$sql = "DELETE FROM attendance WHERE aid = '$aid' LIMIT 1";
								query($sql); 
							}
						}
					}
				}
				
				header("Location: leaves.php?&deleted_leave=1");
				exit();
			} else {
				?>
				<div class="error">Could not delete leave application because: <b><?PHP echo mysqli_error($connection) ?></b>.
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
				  
				<title>OTC | Delete Leave Application</title>

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
					
					$aid = $_GET['aid'];

					$sql = "SELECT l.anum, CONCAT(e.lname,', ',e.fname), 
								lt.lname, DATE_FORMAT(l.fr_date, '%m/%d/%y'), 
								DATE_FORMAT(l.to_date, '%m/%d/%y'), l.lnum,
								l.stats, l.remarks, CONCAT(u.lname,', ',u.fname), 
								DATE_FORMAT(l.user_dtime, '%m/%d/%y %h:%i %p')
							FROM internals_leave_applications l 
								INNER JOIN employees e ON l.eid = e.eid
								INNER JOIN leave_types lt ON l.lid = lt.lid
								INNER JOIN users u ON l.user_id = u.user_id
							WHERE aid = '$aid'";
					$res = query($sql); 
					$row = fetch_array($res); 
					
					?>

					<!-- page content -->
					<div class="right_col" role="main">
					  <div class="">
						<div class="page-title">
						  <div class="title_left">
							<h3>Delete Leave Application</h3>
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
								
									<form action="d_leave.php" method="POST" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12">Control Number </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $row[0] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12">Employee </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $row[1] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12">Type of Leave </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $row[2] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12">Date of Leave </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $row[3] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12">Date of Leave </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $row[4] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12">No. of Days </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $row[5] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12">Status </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" readonly class="form-control col-md-7 col-xs-12" value="<?php 
											if ($row[6] == -1) { echo 'Disapproved';
											} elseif ($row[6] == 0) { echo 'Pending';
											} elseif ($row[6] == 1) { echo 'Approved';
											} ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12">Remarks </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $row[7] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12">Encoder </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $row[8] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12">Date Encoded </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $row[9] ?>">
										</div>
									  </div>
									  <div class="ln_solid"></div>
									  <div class="form-group">
										<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
										  <input type="hidden" name="submitted" value="1">
										  <input type="hidden" name="aid" value="<?php echo $aid ?>">
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

				<!-- Bootstrap -->
				<script src="vendors/bootstrap/dist/js/bootstrap.min.js"></script>
				<!-- Custom Theme Scripts -->
				<script src="build/js/custom.min.js"></script>
				
			  </body>
			</html>
			<?PHP
		}
	} else {
		
		$aid = $_GET['aid'];
		
		// check if user is a valid employee
		$sql = "SELECT eid FROM users WHERE user_id = '$_SESSION[user_id]' AND active = 1";
		$vres = query($sql); $vrow = fetch_array($vres); $eid = $vrow[0]; 
		
		if ($eid != 0) {
			
			// check if leave is not approved yet
			$sql = "SELECT approve_id, eid FROM internals_leave_applications WHERE aid = '$aid'";
			$ares = query($sql); $arow = fetch_array($ares); 
			$approve_id = $arow[0]; $emp_id = $arow[1]; 
			
			if ($approve_id == 0) {
				
				if ($emp_id == $eid ) {
					if(isset($_POST['submitted'])) { // if submit button has been pressed
						$aid = filter_var($_POST['aid'], FILTER_SANITIZE_NUMBER_INT);
						
						$sql = "DELETE FROM internals_leave_applications
								WHERE aid = '$aid'
								LIMIT 1";
						if (query($sql)) {
							// log the activity
							log_user(3, 'internals_leave_applications', $aid);
							
							header("Location: leaves.php?&deleted_leave=1");
							exit();
						} else {
							?>
							<div class="error">Could not delete leave application because: <b><?PHP echo mysqli_error($connection) ?></b>.
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
							  
							<title>OTC | Delete Leave Application</title>

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
								
								$aid = $_GET['aid'];

								$sql = "SELECT l.anum, CONCAT(e.lname,', ',e.fname), 
											lt.lname, DATE_FORMAT(l.fr_date, '%m/%d/%y'), 
											DATE_FORMAT(l.to_date, '%m/%d/%y'), l.lnum,
											l.stats, l.remarks, CONCAT(u.lname,', ',u.fname), 
											DATE_FORMAT(l.user_dtime, '%m/%d/%y %h:%i %p')
										FROM internals_leave_applications l 
											INNER JOIN employees e ON l.eid = e.eid
											INNER JOIN leave_types lt ON l.lid = lt.lid
											INNER JOIN users u ON l.user_id = u.user_id
										WHERE aid = '$aid'";
								$res = query($sql); 
								$row = fetch_array($res); 
								
								?>

								<!-- page content -->
								<div class="right_col" role="main">
								  <div class="">
									<div class="page-title">
									  <div class="title_left">
										<h3>Delete Leave Application</h3>
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
											
												<form action="d_leave.php" method="POST" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
												  <div class="form-group">
													<label class="control-label col-md-3 col-sm-3 col-xs-12">Control Number </label>
													<div class="col-md-6 col-sm-6 col-xs-12">
													  <input type="text" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $row[0] ?>">
													</div>
												  </div>
												  <div class="form-group">
													<label class="control-label col-md-3 col-sm-3 col-xs-12">Employee </label>
													<div class="col-md-6 col-sm-6 col-xs-12">
													  <input type="text" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $row[1] ?>">
													</div>
												  </div>
												  <div class="form-group">
													<label class="control-label col-md-3 col-sm-3 col-xs-12">Type of Leave </label>
													<div class="col-md-6 col-sm-6 col-xs-12">
													  <input type="text" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $row[2] ?>">
													</div>
												  </div>
												  <div class="form-group">
													<label class="control-label col-md-3 col-sm-3 col-xs-12">Date of Leave </label>
													<div class="col-md-6 col-sm-6 col-xs-12">
													  <input type="text" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $row[3] ?>">
													</div>
												  </div>
												  <div class="form-group">
													<label class="control-label col-md-3 col-sm-3 col-xs-12">Date of Leave </label>
													<div class="col-md-6 col-sm-6 col-xs-12">
													  <input type="text" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $row[4] ?>">
													</div>
												  </div>
												  <div class="form-group">
													<label class="control-label col-md-3 col-sm-3 col-xs-12">No. of Days </label>
													<div class="col-md-6 col-sm-6 col-xs-12">
													  <input type="text" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $row[5] ?>">
													</div>
												  </div>
												  <div class="form-group">
													<label class="control-label col-md-3 col-sm-3 col-xs-12">Status </label>
													<div class="col-md-6 col-sm-6 col-xs-12">
													  <input type="text" readonly class="form-control col-md-7 col-xs-12" value="<?php 
														if ($row[6] == -1) { echo 'Disapproved';
														} elseif ($row[6] == 0) { echo 'Pending';
														} elseif ($row[6] == 1) { echo 'Approved';
														} ?>">
													</div>
												  </div>
												  <div class="form-group">
													<label class="control-label col-md-3 col-sm-3 col-xs-12">Remarks </label>
													<div class="col-md-6 col-sm-6 col-xs-12">
													  <input type="text" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $row[7] ?>">
													</div>
												  </div>
												  <div class="form-group">
													<label class="control-label col-md-3 col-sm-3 col-xs-12">Encoder </label>
													<div class="col-md-6 col-sm-6 col-xs-12">
													  <input type="text" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $row[8] ?>">
													</div>
												  </div>
												  <div class="form-group">
													<label class="control-label col-md-3 col-sm-3 col-xs-12">Date Encoded </label>
													<div class="col-md-6 col-sm-6 col-xs-12">
													  <input type="text" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $row[9] ?>">
													</div>
												  </div>
												  <div class="ln_solid"></div>
												  <div class="form-group">
													<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
													  <input type="hidden" name="submitted" value="1">
													  <input type="hidden" name="aid" value="<?php echo $aid ?>">
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

							<!-- Bootstrap -->
							<script src="vendors/bootstrap/dist/js/bootstrap.min.js"></script>
							<!-- Custom Theme Scripts -->
							<script src="build/js/custom.min.js"></script>
							
						  </body>
						</html>
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

						<title>OTC - Update Leave Applications</title>

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
													  <footer class="blockquote-footer"><em>You cannot update other's leave application. You will be reported to the system administrator.</em></footer>
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

					<title>OTC - Delete Leave Applications</title>

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
												  <p class="h1">Invalid action!</p>
												  <footer class="blockquote-footer"><em>Approved Leave Applications cannot be updated anymore.</em></footer>
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

				<title>OTC - Delete Leave Applications</title>

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

