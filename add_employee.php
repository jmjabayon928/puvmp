<?php

include("db.php");
include("sqli.php");
include("html.php");
define('PAGE', 'add_employee.php'); 

session_start();

if (isset($_SESSION['user_id'])) {
	if (validate_level(PAGE) == true) {
		if(isset($_POST['submitted'])) { // if submit button has been pressed
			
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
				
				// check for double entry first
				$sql = "SELECT eid 
						FROM employees 
						WHERE fname = '$fname' AND mname = '$mname' AND lname = '$lname' AND ename = '$ename'";
				$res = query($sql); 
				$num = num_rows($res); 
				
				if ($num > 0) {
					?>
					<div class="alert alert-danger alert-dismissible fade in" role="alert">
						<strong>Record already exists! </strong><br /><br />
						<a href="communication.php?cid=<?php echo $row[0] ?>">Click here to view the record</a>
					</div>
					<?php
				} else {
					$sql = "INSERT INTO employees( ecode, etype, fname, mname, lname, ename, did, pid, sgid, sglevel, estart, eend, last_update_id, last_update, active )
							VALUES( '$ecode', '$etype', '$fname', '$mname', '$lname', '$ename', '$did', '$pid', '$sgid', '$sglevel', '$estart', '$eend', '$_SESSION[user_id]', NOW(), 1 )";
					if (query($sql)) {
						$eid = mysqli_insert_id($connection); 
						// log the activity
						log_user(1, 'employees', $eid);
						
						header("Location:employee.php?eid=".$eid."&success=1");
						exit();
					} else {
						?>
						<div class="alert alert-danger alert-dismissible fade in" role="alert">
							<strong>Could not add employee because: </strong><br />
							<b><?php echo mysqli_error($connection) ?></b><br />
							The query was <?php echo $sql ?>.<br />
						</div>
						<?PHP
					}
				}
			} else {
				$serialized_message = serialize($message);
				// redirect the user to index page
				header('location: add_employee.php?serialized_message='.$serialized_message.'&ecode='.$ecode.'&etype='.$etype.'&fname='.$fname.'&mname='.$mname.'&lname='.$lname.'&ename='.$ename.'&did='.$did.'&pid='.$pid.'&sgid='.$sgid.'&sglevel='.$sglevel.'&estart='.$estart.'&eend='.$eend);
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
				  
				<title>OTC | Add Employee</title>

				<!-- Bootstrap -->
				<link href="vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
				<!-- Font Awesome -->
				<link href="vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
				<!-- NProgress -->
				<link href="vendors/nprogress/nprogress.css" rel="stylesheet">
				<!-- iCheck -->
				<link href="vendors/iCheck/skins/flat/green.css" rel="stylesheet">
				<!-- bootstrap-wysiwyg -->
				<link href="vendors/google-code-prettify/bin/prettify.min.css" rel="stylesheet">
				<!-- Select2 -->
				<link href="vendors/select2/dist/css/select2.min.css" rel="stylesheet">
				<!-- Switchery -->
				<link href="vendors/switchery/dist/switchery.min.css" rel="stylesheet">
				<!-- starrr -->
				<link href="vendors/starrr/dist/starrr.css" rel="stylesheet">
				<!-- bootstrap-daterangepicker -->
				<link href="vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">

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
							<h3>Add Employee</h3>
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
								
									<form action="add_employee.php" method="POST" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="ecode">Employee No. <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="ecode" name="ecode" required="required" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['ecode'])) {
											  ?>value="<?php echo $_GET['ecode'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="etype">Employee Type <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <select id="etype" name="etype" required="required" class="form-control col-md-7 col-xs-12">
											<option value="0">--- Select ---</option>
											<?php
										    if (isset ($_GET['etype'])) {
												if ($_GET['etype'] == 1) {
													?>
													<option value="1" Selected>Presidential Appointee</option>
													<option value="2">Permanent</option>
													<option value="3">Job Order</option>
													<option value="4">Contract of Service</option>
													<?php
												} elseif ($_GET['etype'] == 2) {
													?>
													<option value="1">Presidential Appointee</option>
													<option value="2" Selected>Permanent</option>
													<option value="3">Job Order</option>
													<option value="4">Contract of Service</option>
													<?php
												} elseif ($_GET['etype'] == 3) {
													?>
													<option value="1">Presidential Appointee</option>
													<option value="2">Permanent</option>
													<option value="3" Selected>Job Order</option>
													<option value="4">Contract of Service</option>
													<?php
												} elseif ($_GET['etype'] == 4) {
													?>
													<option value="1">Presidential Appointee</option>
													<option value="2">Permanent</option>
													<option value="3">Job Order</option>
													<option value="4" Selected>Contract of Service</option>
													<?php
												}
											} else {
												?>
												<option value="1">Presidential Appointee</option>
												<option value="2">Permanent</option>
												<option value="3">Job Order</option>
												<option value="4">Contract of Service</option>
												<?php
											}
											?>
										  </select>
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="lname">Last Name <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="lname" name="lname" required="required" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['lname'])) {
											  ?>value="<?php echo $_GET['lname'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="fname">First Name <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="fname" name="fname" required="required" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['fname'])) {
											  ?>value="<?php echo $_GET['fname'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="mname">Middle Name <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="mname" name="mname" required="required" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['mname'])) {
											  ?>value="<?php echo $_GET['mname'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="ename">Extension Name </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="ename" name="ename" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['ename'])) {
											  ?>value="<?php echo $_GET['ename'] ?>"<?php
										  }
										  ?>
										  >
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
												if (isset ($_GET['did'])) {
													if ($_GET['did'] == $drow[0]) {
														?><option value="<?php echo $drow[0] ?>" Selected><?php echo $drow[1] ?></option><?php
													} else {
														?><option value="<?php echo $drow[0] ?>"><?php echo $drow[1] ?></option><?php
													}
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
												if (isset ($_GET['pid'])) {
													if ($_GET['pid'] == $prow[0]) {
														?><option value="<?php echo $prow[0] ?>" Selected><?php echo $prow[1] ?></option><?php
													} else {
														?><option value="<?php echo $prow[0] ?>"><?php echo $prow[1] ?></option><?php
													}
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
												if (isset ($_GET['sgid'])) {
													if ($_GET['sgid'] == $sgrow[0]) {
														?><option value="<?php echo $sgrow[0] ?>" Selected><?php echo $sgrow[1] ?></option><?php
													} else {
														?><option value="<?php echo $sgrow[0] ?>"><?php echo $sgrow[1] ?></option><?php
													}
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
										  <input type="text" id="sglevel" name="sglevel" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['sglevel'])) {
											  ?>value="<?php echo $_GET['sglevel'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label for="estart" class="control-label col-md-3 col-sm-3 col-xs-12">Start Date </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input id="estart" name="estart" class="form-control col-md-7 col-xs-12" type="date" 
										  <?php
										  if (isset ($_GET['estart'])) {
											  ?>value="<?php echo $_GET['estart'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label for="eend" class="control-label col-md-3 col-sm-3 col-xs-12">End Date </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input id="eend" name="eend" class="form-control col-md-7 col-xs-12" type="date" 
										  <?php
										  if (isset ($_GET['eend'])) {
											  ?>value="<?php echo $_GET['eend'] ?>"<?php
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
				<!-- FastClick -->
				<script src="vendors/fastclick/lib/fastclick.js"></script>
				<!-- NProgress -->
				<script src="vendors/nprogress/nprogress.js"></script>
				<!-- bootstrap-progressbar -->
				<script src="vendors/bootstrap-progressbar/bootstrap-progressbar.min.js"></script>
				<!-- iCheck -->
				<script src="vendors/iCheck/icheck.min.js"></script>
				<!-- bootstrap-daterangepicker -->
				<script src="vendors/moment/min/moment.min.js"></script>
				<script src="vendors/bootstrap-daterangepicker/daterangepicker.js"></script>
				<!-- bootstrap-wysiwyg -->
				<script src="vendors/bootstrap-wysiwyg/js/bootstrap-wysiwyg.min.js"></script>
				<script src="vendors/jquery.hotkeys/jquery.hotkeys.js"></script>
				<script src="vendors/google-code-prettify/src/prettify.js"></script>
				<!-- jQuery Tags Input -->
				<script src="vendors/jquery.tagsinput/src/jquery.tagsinput.js"></script>
				<!-- Switchery -->
				<script src="vendors/switchery/dist/switchery.min.js"></script>
				<!-- Select2 -->
				<script src="vendors/select2/dist/js/select2.full.min.js"></script>
				<!-- Parsley -->
				<script src="vendors/parsleyjs/dist/parsley.min.js"></script>
				<!-- Autosize -->
				<script src="vendors/autosize/dist/autosize.min.js"></script>
				<!-- jQuery autocomplete -->
				<script src="vendors/devbridge-autocomplete/dist/jquery.autocomplete.min.js"></script>
				<!-- starrr -->
				<script src="vendors/starrr/dist/starrr.js"></script>
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

