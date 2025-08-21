<?php

include("db.php");
include("sqli.php");
include("html.php");
define('PAGE', 'e_report.php'); 

$rid = $_GET['rid'];

session_start();

if (isset($_SESSION['user_id'])) {
	if (validate_level(PAGE) == true) {

		if(isset($_POST['submitted'])) { // if submit button has been pressed
			$rid = filter_var($_POST['rid'], FILTER_SANITIZE_NUMBER_INT);
			$rtid = filter_var($_POST['rtid'], FILTER_SANITIZE_NUMBER_INT);
			$eid = filter_var($_POST['eid'], FILTER_SANITIZE_NUMBER_INT);
			$did = filter_var($_POST['did'], FILTER_SANITIZE_NUMBER_INT);
			$accepted_by = filter_var($_POST['accepted_by'], FILTER_SANITIZE_NUMBER_INT);
			$status = filter_var($_POST['status'], FILTER_SANITIZE_NUMBER_INT);
			$due_date = filter_var($_POST['due_date'], FILTER_SANITIZE_STRING);
			$submit_date = filter_var($_POST['submit_date'], FILTER_SANITIZE_STRING);
			$accepted_date = filter_var($_POST['accepted_date'], FILTER_SANITIZE_STRING);
			$remarks = filter_var($_POST['remarks'], FILTER_SANITIZE_STRING);
			
			// check if rtid is given
			if ($rtid != 0) { $a = TRUE; } 
			else { $a = FALSE; $message[] = "Please select from the types of reports."; }
			// check if did is given
			if ($did != 0) { $b = TRUE; } 
			else { $b = FALSE; $message[] = "Please select from the responsible divisions."; }
			// check if eid is given
			if ($eid != 0) { $c = TRUE; } 
			else { $c = FALSE; $message[] = "Please select from the responsible persons."; }
			// check if due_date is given
			if ($due_date != "") { $d = TRUE; } 
			else { $d = FALSE; $message[] = "Please enter the due date of report."; }
			// check if status is given
			if ($status != "") { $e = TRUE; } 
			else { $e = FALSE; $message[] = "Please indicate the status of the report."; }
			
			// If data pass all tests, proceed
			if ( $a && $b && $c && $d && $e ) {
				$sql = "UPDATE reports 
						SET rtid = '$rtid',
							eid = '$eid',
							did = '$did',
							due_date = '$due_date',
							submit_date = '$submit_date',
							accepted_by = '$accepted_by',
							accepted_date = '$accepted_date',
							status = '$status',
							remarks = '$remarks', 
							user_id = '$_SESSION[user_id]',
							user_dtime = NOW()
						WHERE rid = '$rid'
						LIMIT 1";
				if (query($sql)) {
					// log the activity
					log_user(2, 'reports', $rid);
					
					// redirect to communication details
					header("Location:report.php?rid=".$rid."&success=1");
					exit();
				} else {
					?>
					<div class="alert alert-danger alert-dismissible fade in" role="alert">
						<strong>Could not update report details because: </strong><br />
						<b><?php echo mysqli_error($connection) ?></b><br />
						The query was <?php echo $sql ?>.<br />
					</div>
					<?PHP
				}
			} else {
				$serialized_message = serialize($message);
				// redirect the user to index page
				header('location: e_report.php?serialized_message='.$serialized_message.'&rid='.$rid);
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
				  
				<title>OTC | Update Report Details</title>

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
							<h3>Update Report Details</h3>
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
						  
						  $sql = "SELECT * FROM reports WHERE rid = '$rid'";
						  $res = query($sql); 
						  $row = fetch_array($res); 
						  ?>
						  <div class="col-md-12 col-sm-12 col-xs-12">
							<div class="x_panel">
							  <div class="x_content">
								<br />
								
									<form action="e_report.php" method="POST" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="rtid">Type of Report </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <select id="rtid" name="rtid" required="required" class="form-control col-md-7 col-xs-12">
											<option value="0"> -- Select -- </option>
											<?php
											$sql = "SELECT rtid, rt_name FROM reports_types ORDER BY frequency, rt_name";
											$rres = query($sql); 
											while ($rrow = fetch_array($rres)) {
												if ($row[1] == $rrow[0]) {
													?><option value="<?php echo $rrow[0] ?>" Selected><?php echo $rrow[1] ?></option><?php
												} else {
													?><option value="<?php echo $rrow[0] ?>"><?php echo $rrow[1] ?></option><?php
												}
											}
											free_result($rres); 
											?>
										  </select>
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="did">Responsible Unit <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <select id="did" name="did" required="required" class="form-control col-md-7 col-xs-12">
											<option value="0"> -- Select -- </option>
											<?php
											$sql = "SELECT did, dname FROM departments ORDER BY dname";
											$dres = query($sql); 
											while ($drow = fetch_array($dres)) {
												if ($row[3] == $drow[0]) {
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
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="eid">Responsible Person <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <select id="eid" name="eid" required="required" class="form-control col-md-7 col-xs-12">
											<option value="0"> -- Select -- </option>
											<?php
											$sql = "SELECT eid, CONCAT(lname,', ',fname) FROM employees WHERE active = 1 ORDER BY lname, fname";
											$eres = query($sql); 
											while ($erow = fetch_array($eres)) {
												if ($row[2] == $erow[0]) {
													?><option value="<?php echo $erow[0] ?>" Selected><?php echo $erow[1] ?></option><?php
												} else {
													?><option value="<?php echo $erow[0] ?>"><?php echo $erow[1] ?></option><?php
												}
											}
											free_result($eres); 
											?>
										  </select>
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="due_date">Due Date <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="date" id="due_date" name="due_date" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $row[4] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="submit_date">Date Submitted </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="date" id="submit_date" name="submit_date" class="form-control col-md-7 col-xs-12" value="<?php echo $row[5] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="accepted_by">Accepted By</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <select id="accepted_by" name="accepted_by" class="form-control col-md-7 col-xs-12">
											<option value="0"> -- Select -- </option>
											<?php
											$sql = "SELECT eid, CONCAT(lname,', ',fname) FROM employees WHERE active = 1 ORDER BY lname, fname";
											$eres2 = query($sql); 
											while ($erow2 = fetch_array($eres2)) {
												if ($row[6] == $erow2[0]) {
													?><option value="<?php echo $erow2[0] ?>" Selected><?php echo $erow2[1] ?></option><?php
												} else {
													?><option value="<?php echo $erow2[0] ?>"><?php echo $erow2[1] ?></option><?php
												}
											}
											free_result($eres2); 
											?>
										  </select>
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="accepted_date">Date Accepted</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="date" id="accepted_date" name="accepted_date" class="form-control col-md-7 col-xs-12" value="<?php echo $row[7] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="status">Status </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <select id="status" name="status" class="form-control col-md-7 col-xs-12">
										    <?php
											if ($row[8] == 1) {
												?>
												<option value="2">To be submitted</option>
												<option value="-1">Returned</option>
												<option value="0">On Process</option>
												<option value="1" Selected>Accepted</option>
												<?php
											} elseif ($row[8] == 0) {
												?>
												<option value="2">To be submitted</option>
												<option value="-1">Returned</option>
												<option value="0" Selected>On Process</option>
												<option value="1">Accepted</option>
												<?php
											} elseif ($row[8] == -1) {
												?>
												<option value="2">To be submitted</option>
												<option value="-1" Selected>Returned</option>
												<option value="0">On Process</option>
												<option value="1">Accepted</option>
												<?php
											} elseif ($row[8] == 2) {
												?>
												<option value="2" Selected>To be submitted</option>
												<option value="-1">Returned</option>
												<option value="0">On Process</option>
												<option value="1">Accepted</option>
												<?php
											} else {
												?>
												<option value="2">To be submitted</option>
												<option value="-1">Returned</option>
												<option value="0">On Process</option>
												<option value="1">Accepted</option>
												<?php
											}
											?>
										  </select>
										</div>
									  </div>
									  <div class="form-group">
										<label for="remarks" class="control-label col-md-3 col-sm-3 col-xs-12">Remarks</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input id="remarks" name="remarks" required="required" class="form-control col-md-7 col-xs-12" type="text" value="<?php echo $row[9] ?>">
										</div>
									  </div>
									  <div class="ln_solid"></div>
									  <div class="form-group">
										<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
										  <input type="hidden" name="submitted" value="1">
										  <input type="hidden" name="rid" value="<?php echo $rid ?>">
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

