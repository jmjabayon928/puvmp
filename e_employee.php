<?php

include("db.php");
include("sqli.php");
include("html.php");
define('PAGE', 'e_employee.php'); 

session_start();

if (isset($_SESSION['user_id'])) {
	if (validate_level3(PAGE) == true) {

		if(isset($_POST['submitted'])) { // if submit button has been pressed
			$eid = filter_var($_POST['eid'], FILTER_SANITIZE_NUMBER_INT);
			$ecode = filter_var($_POST['ecode'], FILTER_SANITIZE_STRING);
			$etype = filter_var($_POST['etype'], FILTER_SANITIZE_NUMBER_INT);
			$fname = filter_var($_POST['fname'], FILTER_SANITIZE_STRING);
			$mname = filter_var($_POST['mname'], FILTER_SANITIZE_STRING);
			$lname = filter_var($_POST['lname'], FILTER_SANITIZE_STRING);
			$ename = filter_var($_POST['ename'], FILTER_SANITIZE_STRING);
			$did = filter_var($_POST['did'], FILTER_SANITIZE_NUMBER_INT);
			$pid = filter_var($_POST['pid'], FILTER_SANITIZE_NUMBER_INT);
			$sgid = filter_var($_POST['sgid'], FILTER_SANITIZE_NUMBER_INT);
			$sglevel = filter_var($_POST['sglevel'], FILTER_SANITIZE_NUMBER_INT);
			$monthly = real_escape_string($_POST['monthly']);
			$daily = real_escape_string($_POST['daily']);
			$estart = filter_var($_POST['estart'], FILTER_SANITIZE_STRING);
			$eend = filter_var($_POST['eend'], FILTER_SANITIZE_STRING);
			$time_in = filter_var($_POST['time_in'], FILTER_SANITIZE_STRING);
			$time_out = filter_var($_POST['time_out'], FILTER_SANITIZE_STRING);
			
			// check if employee-code is given
			if ($ecode != "") { $a = TRUE; } 
			else { $a = FALSE; $message[] = "Please enter the employee code."; }
			// check if employee type is given
			if ($etype != 0 && $etype != '') { $b = TRUE; } 
			else { $b = FALSE; $message[] = "Please select from the list of employee-types."; }
			// check if first name is given
			if ($fname != "") { $c = TRUE; } 
			else { $c = FALSE; $message[] = "Please enter the first name."; }
			// check if middle name is given
			if ($mname != "") { $d = TRUE; } 
			else { $d = FALSE; $message[] = "Please enter the middle name."; }
			// check if last name is given
			if ($lname != "") { $e = TRUE; } 
			else { $e = FALSE; $message[] = "Please enter the last name."; }
			// check if employee type is given
			if ($did != 0 && $did != '') { $f = TRUE; } 
			else { $f = FALSE; $message[] = "Please select from the list of divisions."; }
			// check if employee type is given
			if ($pid != 0 && $pid != '') { $g = TRUE; } 
			else { $g = FALSE; $message[] = "Please select from the list of positions."; }
			// check if employee type is given
			if ($sgid != 0 && $sgid != '') { $h = TRUE; } 
			else { $h = FALSE; $message[] = "Please select from the list of salary grades."; }
			
			// If data pass all tests, proceed
			if ( $a && $b && $c && $d && $e && $f ) {
				// update data
				$sql = "UPDATE employees
						SET ecode = '$ecode',
							etype = '$etype',
							fname = '$fname',
							mname = '$mname',
							lname = '$lname', 
							ename = '$ename',
							did = '$did',
							pid = '$pid',
							sgid = '$sgid',
							sglevel = '$sglevel',
							monthly = '$monthly',
							daily = '$daily',
							estart = '$estart',
							eend = '$eend',
							time_in = '$time_in',
							time_out = '$time_out',
							last_update_id = '$_SESSION[user_id]',
							last_update = NOW()
						WHERE eid = '$eid'
						LIMIT 1";
				if (query($sql)) {
					// log the activity
					log_user(2, 'employees', $eid);
					
					header("Location:employee.php?eid=".$eid."&success=1");
					exit();
				} else {
					?>
					<div class="alert alert-danger alert-dismissible fade in" role="alert">
						<strong>Could not update employee details because: </strong><br />
						<b><?php echo mysqli_error($connection) ?></b><br />
						The query was <?php echo $sql ?>.<br />
					</div>
					<?PHP
				}
			} else {
				$serialized_message = serialize($message);
				// redirect the user to index page
				header('location: e_employee.php?serialized_message='.$serialized_message.'&eid='.$eid);
				exit();
			}
		} else {
			$eid = $_GET['eid'];

			?>
			<!DOCTYPE html>
			<html lang="en">
			  <head>
				<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
				<!-- Meta, title, CSS, favicons, etc. -->
				<meta charset="utf-8">
				<meta http-equiv="X-UA-Compatible" content="IE=edge">
				<meta name="viewport" content="width=device-width, initial-scale=1">
				  
				<title>OTC | Update Employee Details</title>

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
					
					$sql = "SELECT * FROM employees WHERE eid = '$eid'";
					$res = query($sql); 
					$row = fetch_array($res); 
					?>

					<!-- page content -->
					<div class="right_col" role="main">
					  <div class="">
						<div class="page-title">
						  <div class="title_left">
							<h3>Update Employee Details</h3>
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
								
									<form action="e_employee.php" method="POST" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="ecode">Employee No. <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="ecode" name="ecode" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $row[1] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="etype">Employee Type <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <select id="etype" name="etype" required="required" class="form-control col-md-7 col-xs-12">
											<option value="0">--- Select ---</option>
											<?php
											if ($row[2] == 1) {
												?>
												<option value="1" Selected>Presidential Appointee</option>
												<option value="2">Permanent</option>
												<option value="3">Job Order</option>
												<option value="4">Contract of Service</option>
												<?php
											} elseif ($row[2] == 2) {
												?>
												<option value="1">Presidential Appointee</option>
												<option value="2" Selected>Permanent</option>
												<option value="3">Job Order</option>
												<option value="4">Contract of Service</option>
												<?php
											} elseif ($row[2] == 3) {
												?>
												<option value="1">Presidential Appointee</option>
												<option value="2">Permanent</option>
												<option value="3" Selected>Job Order</option>
												<option value="4">Contract of Service</option>
												<?php
											} elseif ($row[2] == 4) {
												?>
												<option value="1">Presidential Appointee</option>
												<option value="2">Permanent</option>
												<option value="3">Job Order</option>
												<option value="4" Selected>Contract of Service</option>
												<?php
											}
											?>
										  </select>
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="lname">Last Name <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="lname" name="lname" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $row[5] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="fname">First Name <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="fname" name="fname" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $row[3] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="mname">Middle Name <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="mname" name="mname" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $row[4] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="ename">Extension Name </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="ename" name="ename" class="form-control col-md-7 col-xs-12" value="<?php echo $row[6] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="did">Division <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <select id="did" name="did" required="required" class="form-control col-md-7 col-xs-12">
											<option value="0">--- Select ---</option>
											<?php
											$sql = "SELECT did, dname FROM departments";
											$dres = query($sql); 
											while ($drow = fetch_array($dres)) {
												if ($row[7] == $drow[0]) {
													?><option value="<?php echo $drow[0] ?>" Selected><?php echo $drow[1] ?></option><?php
												} else {
													?><option value="<?php echo $drow[0] ?>"><?php echo $drow[1] ?></option><?php
												}
											}
											free_result($dres); 
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
											$sql = "SELECT pid, pname FROM positions ORDER BY pname";
											$pres = query($sql); 
											while ($prow = fetch_array($pres)) {
												if ($row[8] == $prow[0]) {
													?><option value="<?php echo $prow[0] ?>" Selected><?php echo $prow[1] ?></option><?php
												} else {
													?><option value="<?php echo $prow[0] ?>"><?php echo $prow[1] ?></option><?php
												}
											}
											free_result($pres); 
											?>
										  </select>
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="sgid">Salary Grade <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <select id="sgid" name="sgid" required="required" class="form-control col-md-7 col-xs-12">
											<option value="0">--- Select ---</option>
											<?php
											$sql = "SELECT sgid, sg_num FROM salary_grades ORDER BY sg_num";
											$sgres = query($sql); 
											while ($sgrow = fetch_array($sgres)) {
												if ($row[9] == $sgrow[0]) {
													?><option value="<?php echo $sgrow[0] ?>" Selected><?php echo $sgrow[1] ?></option><?php
												} else {
													?><option value="<?php echo $sgrow[0] ?>"><?php echo $sgrow[1] ?></option><?php
												}
											}
											free_result($sgres); 
											?>
										  </select>
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="sglevel">Step Increment </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="sglevel" name="sglevel" class="form-control col-md-7 col-xs-12" value="<?php echo $row[10] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="monthly">Monthly Salary </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="monthly" name="monthly" class="form-control col-md-7 col-xs-12" value="<?php echo $row[12] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="daily">Daily Rate </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="daily" name="daily" class="form-control col-md-7 col-xs-12" value="<?php echo $row[13] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label for="estart" class="control-label col-md-3 col-sm-3 col-xs-12">Start Date </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input id="estart" name="estart" class="form-control col-md-7 col-xs-12" type="date" value="<?php echo $row[15] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label for="eend" class="control-label col-md-3 col-sm-3 col-xs-12">End Date </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input id="eend" name="eend" class="form-control col-md-7 col-xs-12" type="date" value="<?php echo $row[16] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label for="time_in" class="control-label col-md-3 col-sm-3 col-xs-12">Start Work Hour </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input id="time_in" name="time_in" class="form-control col-md-7 col-xs-12" type="time" value="<?php echo $row[17] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label for="time_out" class="control-label col-md-3 col-sm-3 col-xs-12">End Work Hour </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input id="time_out" name="time_out" class="form-control col-md-7 col-xs-12" type="time" value="<?php echo $row[18] ?>">
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
				$ecode = filter_var($_POST['ecode'], FILTER_SANITIZE_STRING);
				$etype = filter_var($_POST['etype'], FILTER_SANITIZE_NUMBER_INT);
				$fname = filter_var($_POST['fname'], FILTER_SANITIZE_STRING);
				$mname = filter_var($_POST['mname'], FILTER_SANITIZE_STRING);
				$lname = filter_var($_POST['lname'], FILTER_SANITIZE_STRING);
				$ename = filter_var($_POST['ename'], FILTER_SANITIZE_STRING);
				$did = filter_var($_POST['did'], FILTER_SANITIZE_NUMBER_INT);
				$pid = filter_var($_POST['pid'], FILTER_SANITIZE_NUMBER_INT);
				$sgid = filter_var($_POST['sgid'], FILTER_SANITIZE_NUMBER_INT);
				$sglevel = filter_var($_POST['sglevel'], FILTER_SANITIZE_NUMBER_INT);
				$estart = filter_var($_POST['estart'], FILTER_SANITIZE_STRING);
				$eend = filter_var($_POST['eend'], FILTER_SANITIZE_STRING);
				$monthly = real_escape_string($_POST['monthly']);
				$daily = real_escape_string($_POST['daily']);
				
				// check if employee-code is given
				if ($ecode != "") { $a = TRUE; } 
				else { $a = FALSE; $message[] = "Please enter the employee code."; }
				// check if employee type is given
				if ($etype != 0 && $etype != '') { $b = TRUE; } 
				else { $b = FALSE; $message[] = "Please select from the list of employee-types."; }
				// check if first name is given
				if ($fname != "") { $c = TRUE; } 
				else { $c = FALSE; $message[] = "Please enter the first name."; }
				// check if middle name is given
				if ($mname != "") { $d = TRUE; } 
				else { $d = FALSE; $message[] = "Please enter the middle name."; }
				// check if last name is given
				if ($lname != "") { $e = TRUE; } 
				else { $e = FALSE; $message[] = "Please enter the last name."; }
				// check if employee type is given
				if ($did != 0 && $did != '') { $f = TRUE; } 
				else { $f = FALSE; $message[] = "Please select from the list of divisions."; }
				// check if employee type is given
				if ($pid != 0 && $pid != '') { $g = TRUE; } 
				else { $g = FALSE; $message[] = "Please select from the list of positions."; }
				// check if employee type is given
				if ($sgid != 0 && $sgid != '') { $h = TRUE; } 
				else { $h = FALSE; $message[] = "Please select from the list of salary grades."; }
				
				// If data pass all tests, proceed
				if ( $a && $b && $c && $d && $e && $f ) {
					// update data
					$sql = "UPDATE employees
							SET ecode = '$ecode',
								etype = '$etype',
								fname = '$fname',
								mname = '$mname',
								lname = '$lname', 
								ename = '$ename',
								did = '$did',
								pid = '$pid',
								sgid = '$sgid',
								sglevel = '$sglevel',
								monthly = '$monthly',
								daily = '$daily',
								estart = '$estart',
								eend = '$eend',
								last_update_id = '$_SESSION[user_id]',
								last_update = NOW()
							WHERE eid = '$eid'
							LIMIT 1";
					if (query($sql)) {
						// log the activity
						log_user(2, 'employees', $eid);
						
						header("Location:employee.php?eid=".$eid."&success=1");
						exit();
					} else {
						?>
						<div class="alert alert-danger alert-dismissible fade in" role="alert">
							<strong>Could not update employee details because: </strong><br />
							<b><?php echo mysqli_error($connection) ?></b><br />
							The query was <?php echo $sql ?>.<br />
						</div>
						<?PHP
					}
				} else {
					$serialized_message = serialize($message);
					// redirect the user to index page
					header('location: e_employee.php?serialized_message='.$serialized_message.'&eid='.$eid);
					exit();
				}
			} else {
				$eid = $_GET['eid'];
				
				if ($emp_id == $eid) {
					// show form
					$eid = $_GET['eid'];

					?>
					<!DOCTYPE html>
					<html lang="en">
					  <head>
						<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
						<!-- Meta, title, CSS, favicons, etc. -->
						<meta charset="utf-8">
						<meta http-equiv="X-UA-Compatible" content="IE=edge">
						<meta name="viewport" content="width=device-width, initial-scale=1">
						  
						<title>OTC | Update Employee Details</title>

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
							
							$sql = "SELECT * FROM employees WHERE eid = '$eid'";
							$res = query($sql); 
							$row = fetch_array($res); 
							?>

							<!-- page content -->
							<div class="right_col" role="main">
							  <div class="">
								<div class="page-title">
								  <div class="title_left">
									<h3>Update Employee Details</h3>
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
										
											<form action="e_employee.php" method="POST" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
											  <div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="ecode">Employee No. <span class="required">*</span></label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <input type="text" id="ecode" name="ecode" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $row[1] ?>">
												</div>
											  </div>
											  <div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="etype">Employee Type <span class="required">*</span></label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <select id="etype" name="etype" required="required" class="form-control col-md-7 col-xs-12">
													<option value="0">--- Select ---</option>
													<?php
													if ($row[2] == 1) {
														?>
														<option value="1" Selected>Presidential Appointee</option>
														<option value="2">Permanent</option>
														<option value="3">Job Order</option>
														<option value="4">Contract of Service</option>
														<?php
													} elseif ($row[2] == 2) {
														?>
														<option value="1">Presidential Appointee</option>
														<option value="2" Selected>Permanent</option>
														<option value="3">Job Order</option>
														<option value="4">Contract of Service</option>
														<?php
													} elseif ($row[2] == 3) {
														?>
														<option value="1">Presidential Appointee</option>
														<option value="2">Permanent</option>
														<option value="3" Selected>Job Order</option>
														<option value="4">Contract of Service</option>
														<?php
													} elseif ($row[2] == 4) {
														?>
														<option value="1">Presidential Appointee</option>
														<option value="2">Permanent</option>
														<option value="3">Job Order</option>
														<option value="4" Selected>Contract of Service</option>
														<?php
													}
													?>
												  </select>
												</div>
											  </div>
											  <div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="lname">Last Name <span class="required">*</span></label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <input type="text" id="lname" name="lname" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $row[5] ?>">
												</div>
											  </div>
											  <div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="fname">First Name <span class="required">*</span></label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <input type="text" id="fname" name="fname" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $row[3] ?>">
												</div>
											  </div>
											  <div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="mname">Middle Name <span class="required">*</span></label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <input type="text" id="mname" name="mname" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $row[4] ?>">
												</div>
											  </div>
											  <div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="ename">Extension Name </label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <input type="text" id="ename" name="ename" class="form-control col-md-7 col-xs-12" value="<?php echo $row[6] ?>">
												</div>
											  </div>
											  <div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="did">Division <span class="required">*</span></label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <select id="did" name="did" required="required" class="form-control col-md-7 col-xs-12">
													<option value="0">--- Select ---</option>
													<?php
													$sql = "SELECT did, dname FROM departments";
													$dres = query($sql); 
													while ($drow = fetch_array($dres)) {
														if ($row[7] == $drow[0]) {
															?><option value="<?php echo $drow[0] ?>" Selected><?php echo $drow[1] ?></option><?php
														} else {
															?><option value="<?php echo $drow[0] ?>"><?php echo $drow[1] ?></option><?php
														}
													}
													free_result($dres); 
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
													$sql = "SELECT pid, pname FROM positions ORDER BY pname";
													$pres = query($sql); 
													while ($prow = fetch_array($pres)) {
														if ($row[8] == $prow[0]) {
															?><option value="<?php echo $prow[0] ?>" Selected><?php echo $prow[1] ?></option><?php
														} else {
															?><option value="<?php echo $prow[0] ?>"><?php echo $prow[1] ?></option><?php
														}
													}
													free_result($pres); 
													?>
												  </select>
												</div>
											  </div>
											  <div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="sgid">Salary Grade <span class="required">*</span></label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <select id="sgid" name="sgid" required="required" class="form-control col-md-7 col-xs-12">
													<option value="0">--- Select ---</option>
													<?php
													$sql = "SELECT sgid, sg_num FROM salary_grades ORDER BY sg_num";
													$sgres = query($sql); 
													while ($sgrow = fetch_array($sgres)) {
														if ($row[9] == $sgrow[0]) {
															?><option value="<?php echo $sgrow[0] ?>" Selected><?php echo $sgrow[1] ?></option><?php
														} else {
															?><option value="<?php echo $sgrow[0] ?>"><?php echo $sgrow[1] ?></option><?php
														}
													}
													free_result($sgres); 
													?>
												  </select>
												</div>
											  </div>
											  <div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="sglevel">Step Increment </label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <input type="text" id="sglevel" name="sglevel" class="form-control col-md-7 col-xs-12" value="<?php echo $row[10] ?>">
												</div>
											  </div>
											  <div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="monthly">Monthly Salary </label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <input type="text" id="monthly" name="monthly" class="form-control col-md-7 col-xs-12" value="<?php echo $row[12] ?>">
												</div>
											  </div>
											  <div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="daily">Daily Rate </label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <input type="text" id="daily" name="daily" class="form-control col-md-7 col-xs-12" value="<?php echo $row[13] ?>">
												</div>
											  </div>
											  <div class="form-group">
												<label for="estart" class="control-label col-md-3 col-sm-3 col-xs-12">Start Date </label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <input id="estart" name="estart" class="form-control col-md-7 col-xs-12" type="date" value="<?php echo $row[15] ?>">
												</div>
											  </div>
											  <div class="form-group">
												<label for="eend" class="control-label col-md-3 col-sm-3 col-xs-12">End Date </label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <input id="eend" name="eend" class="form-control col-md-7 col-xs-12" type="date" value="<?php echo $row[16] ?>">
												</div>
											  </div>
											  <div class="form-group">
												<label for="time_in" class="control-label col-md-3 col-sm-3 col-xs-12">Start Work Hour </label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <input id="time_in" name="time_in" class="form-control col-md-7 col-xs-12" type="time" value="<?php echo $row[17] ?>">
												</div>
											  </div>
											  <div class="form-group">
												<label for="time_out" class="control-label col-md-3 col-sm-3 col-xs-12">End Work Hour </label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <input id="time_out" name="time_out" class="form-control col-md-7 col-xs-12" type="time" value="<?php echo $row[18] ?>">
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

						<title>OTC - Update Employee-Information</title>

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

				<title>OTC - Update Employee-Information</title>

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

