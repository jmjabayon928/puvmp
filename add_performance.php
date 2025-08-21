<?php

include("db.php");
include("sqli.php");
include("html.php");
define('PAGE', 'add_performance.php'); 

session_start();

if (isset($_SESSION['user_id'])) {
	if (validate_level(PAGE) == true) {
		
		if(isset($_POST['submitted'])) { // if submit button has been pressed
			$ryear = filter_var($_POST['ryear'], FILTER_SANITIZE_NUMBER_INT);
			$sem = filter_var($_POST['sem'], FILTER_SANITIZE_NUMBER_INT);
			$eid = filter_var($_POST['eid'], FILTER_SANITIZE_NUMBER_INT);
			$pid = filter_var($_POST['pid'], FILTER_SANITIZE_NUMBER_INT);
			$rating = real_escape_string($_POST['rating']);
			
			// check if ryear is given
			if ($ryear != 0 && $ryear != '') { $a = TRUE; } 
			else { $a = FALSE; $message[] = "Please enter the year."; }
			// check if sem is given
			if ($sem != 0 && $sem != '') { $b = TRUE; } 
			else { $b = FALSE; $message[] = "Please select from the list of semesters."; }
			// check if eid is given
			if ($eid != 0 && $eid != '') { $c = TRUE; } 
			else { $c = FALSE; $message[] = "Please select from the list of employees."; }
			// check if pid is given
			if ($pid != 0 && $pid != '') { $d = TRUE; } 
			else { $d = FALSE; $message[] = "Please select from the list of positions."; }
			// check if rating is given
			if ($rating != '') { $e = TRUE; } 
			else { $e = FALSE; $message[] = "Please enter the performance rating."; }
			
			
			// If data pass all tests, proceed
			if ( $a && $b && $c && $d && $e ) {
				// check for double entry first
				$sql = "SELECT rid
						FROM employees_ratings
						WHERE ryear = '$ryear' AND sem = '$sem' AND eid = '$eid' AND pid = '$pid'";
				$res = query($sql); 
				$num = num_rows($res); 
				
				if ($num > 0) {
					$row = fetch_array($res); 
					?>
					<div class="alert alert-danger alert-dismissible fade in" role="alert">
						<strong>Record already exists! </strong><br /><br />
					</div>
					<?php
				} else {
					$sql = "INSERT INTO employees_ratings( ryear, sem, eid, pid, rating, user_id, user_dtime )
							VALUES( '$ryear', '$sem', '$eid', '$pid', '$rating', '$_SESSION[user_id]', NOW() )";
					if (query($sql)) {
						$epid = mysqli_insert_id($connection);
						// log the activity
						log_user(1, 'employees_promotions', $epid);
						
						header("Location: hris.php?tab=4&added_performance=1");
						exit();
					} else {
						?>
						<div class="alert alert-danger alert-dismissible fade in" role="alert">
							<strong>Could not add performance rating because: </strong><br />
							<b><?php echo mysqli_error($connection) ?></b><br />
							The query was <?php echo $sql ?>.<br />
						</div>
						<?PHP
					}
				}
			} else {
				$serialized_message = serialize($message);
				// redirect the user to index page
				header('location: add_performance.php?serialized_message='.$serialized_message.'&ryear='.$ryear.'&sem='.$sem.'&eid='.$eid.'&pid='.$pid.'&rating='.$rating);
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
				  
				<title>OTC | Add Performance Rating</title>

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
							<h3>Add Promotion</h3>
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
										<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-epidden="true">×</span>
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
								
									<form action="add_performance.php" method="POST" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="ryear">Year <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="ryear" name="ryear" required="required" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['ryear'])) {
											  ?>value="<?php echo $_GET['ryear'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="sem">Semester <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <select id="sem" name="sem" required="required" class="form-control col-md-7 col-xs-12">
										    <option value="0">--- Select ---</option>
											<?php
											if (isset ($_GET['sem'])) {
												if ($_GET['sem'] == 1) {
													?>
													<option value="1" Selected>1st</option>
													<option value="2">2nd</option>
													<?php
												} elseif ($_GET['sem'] == 2) {
													?>
													<option value="1">1st</option>
													<option value="2" Selected>2nd</option>
													<?php
												} else {
													?>
													<option value="1">1st</option>
													<option value="2">2nd</option>
													<?php
												}
											} else {
												?>
												<option value="1">1st</option>
												<option value="2">2nd</option>
												<?php
											}
											?>
										  </select>
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="eid">Employee <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <select id="eid" name="eid" required="required" class="form-control col-md-7 col-xs-12">
										    <option value="0">--- Select ---</option>
											<?php
											if (isset ($_GET['eid'])) {
												$sql = "SELECT eid, CONCAT(lname,', ',fname) FROM employees ORDER BY lname, fname";
												$eres = query($sql); 
												while ($erow = fetch_array($eres)) {
													if ($_GET['eid'] == $erow[0]) {
														?><option value="<?php echo $erow[0] ?>" Selected><?php echo $erow[1] ?></option><?php
													} else { 
														?><option value="<?php echo $erow[0] ?>"><?php echo $erow[1] ?></option><?php
													}
												}
												free_result($eres); 
											} else {
												$sql = "SELECT eid, CONCAT(lname,', ',fname) FROM employees ORDER BY lname, fname";
												$eres = query($sql); 
												while ($erow = fetch_array($eres)) {
													?><option value="<?php echo $erow[0] ?>"><?php echo $erow[1] ?></option><?php
												}
												free_result($eres); 
											}
											?>
										  </select>
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="pid">Position <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <select id="pid" name="pid" required="required" class="form-control col-md-7 col-xs-12">
										    <option value="0">--- Select ---</option>
											<?php
											if (isset ($_GET['pid'])) {
												$sql = "SELECT pid, pname FROM positions ORDER BY pname";
												$pres = query($sql); 
												while ($prow = fetch_array($pres)) {
													if ($_GET['pid'] == $prow[0]) {
														?><option value="<?php echo $prow[0] ?>" Selected><?php echo $prow[1] ?></option><?php
													} else { 
														?><option value="<?php echo $prow[0] ?>"><?php echo $prow[1] ?></option><?php
													}
												}
												free_result($pres); 
											} else {
												$sql = "SELECT pid, pname FROM positions ORDER BY pname";
												$pres = query($sql); 
												while ($prow = fetch_array($pres)) {
													?><option value="<?php echo $prow[0] ?>"><?php echo $prow[1] ?></option><?php
												}
												free_result($pres); 
											}
											?>
										  </select>
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="rating">Rating <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="rating" name="rating" required="required" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['rating'])) {
											  ?>value="<?php echo $_GET['rating'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="ln_solid"></div>
									  <div class="form-group">
										<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
										  <input type="hidden" name="submitted" value="1">
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

