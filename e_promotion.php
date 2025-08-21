<?php

include("db.php");
include("sqli.php");
include("html.php");
define('PAGE', 'e_promotion.php'); 

session_start();

if (isset($_SESSION['user_id'])) {
	if (validate_level(PAGE) == true) {

		if(isset($_POST['submitted'])) { // if submit button has been pressed
			$epid = filter_var($_POST['epid'], FILTER_SANITIZE_NUMBER_INT);
			$eid = filter_var($_POST['eid'], FILTER_SANITIZE_NUMBER_INT);
			$pid = filter_var($_POST['pid'], FILTER_SANITIZE_NUMBER_INT);
			$sgid = filter_var($_POST['sgid'], FILTER_SANITIZE_NUMBER_INT);
			$status = filter_var($_POST['status'], FILTER_SANITIZE_NUMBER_INT);
			$adate = filter_var($_POST['adate'], FILTER_SANITIZE_STRING);
			$mode = filter_var($_POST['mode'], FILTER_SANITIZE_NUMBER_INT);
			
			// check if eid is given
			if ($eid != 0 && $eid != '') { $a = TRUE; } 
			else { $a = FALSE; $message[] = "Please select from the list of employees."; }
			// check if pid is given
			if ($pid != 0 && $pid != '') { $b = TRUE; } 
			else { $b = FALSE; $message[] = "Please select from the list of positions."; }
			// check if sgid is given
			if ($sgid != 0 && $sgid != '') { $c = TRUE; } 
			else { $c = FALSE; $message[] = "Please select from the list of salary grades."; }
			// check if status is given
			if ($status != 0 && $status != '') { $d = TRUE; } 
			else { $d = FALSE; $message[] = "Please select from the list of status of promotion."; }
			// check if adate is given
			if ($adate != '') { $e = TRUE; } 
			else { $e = FALSE; $message[] = "Please enter the date of promotion."; }
			// check if mode is given
			if ($mode != 0 && $mode != '') { $f = TRUE; } 
			else { $f = FALSE; $message[] = "Please select from the list of modes of promotion."; }
			
			
			// If data pass all tests, proceed
			if ( $a && $b && $c && $d && $e && $f ) {
				$sql = "UPDATE employees_promotions
						SET eid = '$eid',
							pid = '$pid',
							sgid = '$sgid',
							status = '$status',
							adate = '$adate',
							mode = '$mode',
							user_id = '$_SESSION[user_id]',
							user_dtime = NOW()
						WHERE epid = '$epid'
						LIMIT 1";
				if (query($sql)) {
					// log the activity
					log_user(2, 'activities', $epid);
					
					// redirect to hris.php
					header("Location: hris.php?tab=2&update_promotion=1");
					exit();
				} else {
					?>
					<div class="alert alert-danger alert-dismissible fade in" role="alert">
						<strong>Could not update activity because: </strong><br />
						<b><?php echo mysqli_error($connection) ?></b><br />
						The query was <?php echo $sql ?>.<br />
					</div>
					<?PHP
				}
			} else {
				$serialized_message = serialize($message);
				// redirect the user to index page
				header('location: e_promotion.php?serialized_message='.$serialized_message.'&epid='.$epid);
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
				  
				<title>OTC | Update Promotion</title>

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
							<h3>Update Promotion</h3>
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
						  
						  $epid = $_GET['epid'];
						  
						  $sql = "SELECT * FROM employees_promotions WHERE epid = '$epid'";
						  $res = query($sql); 
						  $row = fetch_array($res); 
						
						  ?>
						  <div class="col-md-12 col-sm-12 col-xs-12">
							<div class="x_panel">
							  <div class="x_content">
								<br />
								
									<form action="e_promotion.php" method="POST" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="eid">Employee <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <select id="eid" name="eid" required="required" class="form-control col-md-7 col-xs-12">
										    <option value="0">--- Select ---</option>
											<?php
											$sql = "SELECT eid, CONCAT(lname,', ',fname) FROM employees ORDER BY lname, fname";
											$eres = query($sql); 
											while ($erow = fetch_array($eres)) {
												if ($row[1] == $erow[0]) {
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
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="pid">Position <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <select id="pid" name="pid" required="required" class="form-control col-md-7 col-xs-12">
										    <option value="0">--- Select ---</option>
											<?php
											$sql = "SELECT pid, pname FROM positions ORDER BY pname";
											$pres = query($sql); 
											while ($prow = fetch_array($pres)) {
												if ($row[2] == $prow[0]) {
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
											$sres = query($sql); 
											while ($srow = fetch_array($sres)) {
												if ($row[3] == $srow[0]) {
													?><option value="<?php echo $srow[0] ?>" Selected><?php echo $srow[1] ?></option><?php
												} else {
													?><option value="<?php echo $srow[0] ?>"><?php echo $srow[1] ?></option><?php
												}
											}
											free_result($sres); 
											?>
										  </select>
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="status">Status <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <select id="status" name="status" required="required" class="form-control col-md-7 col-xs-12">
										    <option value="0">--- Select ---</option>
											<?php
											if ($row[4] == 1) {
												?>
												<option value="1" Selected>Permanent</option>
												<?php
											} else {
												?>
												<option value="1">Permanent</option>
												<?php
											}
											?>
										  </select>
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="adate">Date of Promotion <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="date" id="adate" name="adate" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $row[5] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="mode">Mode of Promotion <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <select id="mode" name="mode" required="required" class="form-control col-md-7 col-xs-12">
										    <option value="0">--- Select ---</option>
											<?php
											if ($row[6] == 1) {
												?>
												<option value="1" Selected>Original Appointment</option>
												<option value="2">Transfer</option>
												<option value="3">Promotion</option>
												<?php
											} elseif ($row[6] == 2) {
												?>
												<option value="1">Original Appointment</option>
												<option value="2" Selected>Transfer</option>
												<option value="3">Promotion</option>
												<?php
											} elseif ($row[6] == 3) {
												?>
												<option value="1">Original Appointment</option>
												<option value="2">Transfer</option>
												<option value="3" Selected>Promotion</option>
												<?php
											} else {
												?>
												<option value="1">Original Appointment</option>
												<option value="2">Transfer</option>
												<option value="3">Promotion</option>
												<?php
											}
											?>
										  </select>
										</div>
									  </div>
									  <div class="ln_solid"></div>
									  <div class="form-group">
										<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
										  <input type="hidden" name="submitted" value="1">
										  <input type="hidden" name="epid" value="<?php echo $epid ?>">
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

