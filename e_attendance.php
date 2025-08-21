<?php

include("db.php");
include("sqli.php");
include("html.php");
define('PAGE', 'e_attendance.php'); 

$aid = $_GET['aid'];

session_start();

if (isset($_SESSION['user_id'])) {
	if (validate_level(PAGE) == true) {

		if(isset($_POST['submitted'])) { // if submit button has been pressed
			$aid = filter_var($_POST['aid'], FILTER_SANITIZE_NUMBER_INT);
			$eid = filter_var($_POST['eid'], FILTER_SANITIZE_NUMBER_INT);
			$adate = filter_var($_POST['adate'], FILTER_SANITIZE_STRING);
			$am_in = filter_var($_POST['am_in'], FILTER_SANITIZE_STRING);
			$am_out = filter_var($_POST['am_out'], FILTER_SANITIZE_STRING);
			$pm_in = filter_var($_POST['pm_in'], FILTER_SANITIZE_STRING);
			$pm_out = filter_var($_POST['pm_out'], FILTER_SANITIZE_STRING);
			$tardiness = filter_var($_POST['tardiness'], FILTER_SANITIZE_STRING);
			$undertime = filter_var($_POST['undertime'], FILTER_SANITIZE_STRING);
			$status = filter_var($_POST['status'], FILTER_SANITIZE_NUMBER_INT);
			$remarks = filter_var($_POST['remarks'], FILTER_SANITIZE_STRING);
			
			// check if employee is given
			if ($eid != "" && $eid != 0) { $a = TRUE; } 
			else { $a = FALSE; $message[] = "Please select from the list of employees."; }
			// check if date is given
			if ($adate != '') { $b = TRUE; } 
			else { $b = FALSE; $message[] = "Please enter the date."; }
			
			// If data pass all tests, proceed
			if ( $a && $b ) {
				$sql = "UPDATE attendance
						SET eid = '$eid',
							adate = '$adate',
							am_in = '$am_in',
							am_out = '$am_out',
							pm_in = '$pm_in', 
							pm_out = '$pm_out',
							status = '$status',
							remarks = '$remarks',
							tardiness = '$tardiness',
							undertime = '$undertime',
							last_update_id = '$_SESSION[user_id]',
							last_update = NOW()
						WHERE aid = '$aid'
						LIMIT 1";
				if (query($sql)) {
					// update the attendance
					//update_attendance($aid); 
					
					// log the activity
					log_user(2, 'attendance', $aid);
					
					// redirect to communication details
					header("Location: attendance.php?aid=".$aid."&update=1");
					exit();
				} else {
					?>
					<div class="alert alert-danger alert-dismissible fade in" role="alert">
						<strong>Could not update employee-attendance because: </strong><br />
						<b><?php echo mysqli_error($connection) ?></b><br />
						The query was <?php echo $sql ?>.<br />
					</div>
					<?PHP
				}
			} else {
				$serialized_message = serialize($message);
				// redirect the user to index page
				header('location: e_communication.php?serialized_message='.$serialized_message);
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
				  
				<title>OTC | Update Employee-Attendance</title>

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
					
					$sql = "SELECT * FROM attendance WHERE aid = '$aid'";
					$res = query($sql); 
					$row = fetch_array($res); 
					?>

					<!-- page content -->
					<div class="right_col" role="main">
					  <div class="">
						<div class="page-title">
						  <div class="title_left">
							<h3>Update Employee-Attendance</h3>
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
								
									<form action="e_attendance.php" method="POST" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="eid">Employee <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <select id="eid" name="eid" required="required" class="form-control col-md-7 col-xs-12">
											<option value="0">--- Select ---</option>
											<?php
											$sql = "SELECT eid, CONCAT(lname,', ',fname) 
													FROM employees 
													WHERE active = 1
													ORDER BY lname, fname";
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
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="adate">Date <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="date" id="adate" name="adate" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $row[2] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="am_in">AM In <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="time" id="am_in" name="am_in" class="form-control col-md-7 col-xs-12" value="<?php echo $row[3] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="am_out">AM Out <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="time" id="am_out" name="am_out" class="form-control col-md-7 col-xs-12" value="<?php echo $row[4] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="pm_in">PM In <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="time" id="pm_in" name="pm_in" class="form-control col-md-7 col-xs-12" value="<?php echo $row[5] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="pm_out">PM Out <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="time" id="pm_out" name="pm_out" class="form-control col-md-7 col-xs-12" value="<?php echo $row[6] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="status">Status <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <select id="status" name="status" required="required" class="form-control col-md-7 col-xs-12">
										    <?php
											if ($row[7] == 1) {
												?>
												<option value="1" Selected>Present</option>
												<option value="14">Half-Day</option>
												<option value="2">Unauthorized Absent</option>
												<option value="3">Vacation Leave</option>
												<option value="4">Sick Leave</option>
												<option value="5">Forced Leave</option>
												<option value="6">Special Leave</option>
												<option value="7">Compensatory Time Off</option>
												<option value="8">Maternity Leave</option>
												<option value="9">Paternity Leave</option>
												<option value="10">UML</option>
												<option value="11">USPL</option>
												<option value="12">Travel Order</option>
												<option value="13">DMS</option>
												<option value="15">Holiday</option>
												<option value="16">Special Non-Working Holiday</option>
												<option value="17">Memorandum Order</option>
												<option value="18">Special Order</option>
												<?php
											} elseif ($row[7] == 2) {
												?>
												<option value="1">Present</option>
												<option value="14">Half-Day</option>
												<option value="2" Selected>Unauthorized Absent</option>
												<option value="3">Vacation Leave</option>
												<option value="4">Sick Leave</option>
												<option value="5">Forced Leave</option>
												<option value="6">Special Leave</option>
												<option value="7">Compensatory Time Off</option>
												<option value="8">Maternity Leave</option>
												<option value="9">Paternity Leave</option>
												<option value="10">UML</option>
												<option value="11">USPL</option>
												<option value="12">Travel Order</option>
												<option value="13">DMS</option>
												<option value="15">Holiday</option>
												<option value="16">Special Non-Working Holiday</option>
												<option value="17">Memorandum Order</option>
												<option value="18">Special Order</option>
												<?php
											} elseif ($row[7] == 3) {
												?>
												<option value="1">Present</option>
												<option value="14">Half-Day</option>
												<option value="2">Unauthorized Absent</option>
												<option value="3" Selected>Vacation Leave</option>
												<option value="4">Sick Leave</option>
												<option value="5">Forced Leave</option>
												<option value="6">Special Leave</option>
												<option value="7">Compensatory Time Off</option>
												<option value="8">Maternity Leave</option>
												<option value="9">Paternity Leave</option>
												<option value="10">UML</option>
												<option value="11">USPL</option>
												<option value="12">Travel Order</option>
												<option value="13">DMS</option>
												<option value="15">Holiday</option>
												<option value="16">Special Non-Working Holiday</option>
												<option value="17">Memorandum Order</option>
												<option value="18">Special Order</option>
												<?php
											} elseif ($row[7] == 4) {
												?>
												<option value="1">Present</option>
												<option value="14">Half-Day</option>
												<option value="2">Unauthorized Absent</option>
												<option value="3">Vacation Leave</option>
												<option value="4" Selected>Sick Leave</option>
												<option value="5">Forced Leave</option>
												<option value="6">Special Leave</option>
												<option value="7">Compensatory Time Off</option>
												<option value="8">Maternity Leave</option>
												<option value="9">Paternity Leave</option>
												<option value="10">UML</option>
												<option value="11">USPL</option>
												<option value="12">Travel Order</option>
												<option value="13">DMS</option>
												<option value="15">Holiday</option>
												<option value="16">Special Non-Working Holiday</option>
												<option value="17">Memorandum Order</option>
												<option value="18">Special Order</option>
												<?php
											} elseif ($row[7] == 5) {
												?>
												<option value="1">Present</option>
												<option value="14">Half-Day</option>
												<option value="2">Unauthorized Absent</option>
												<option value="3">Vacation Leave</option>
												<option value="4">Sick Leave</option>
												<option value="5" Selected>Forced Leave</option>
												<option value="6">Special Leave</option>
												<option value="7">Compensatory Time Off</option>
												<option value="8">Maternity Leave</option>
												<option value="9">Paternity Leave</option>
												<option value="10">UML</option>
												<option value="11">USPL</option>
												<option value="12">Travel Order</option>
												<option value="13">DMS</option>
												<option value="15">Holiday</option>
												<option value="16">Special Non-Working Holiday</option>
												<option value="17">Memorandum Order</option>
												<option value="18">Special Order</option>
												<?php
											} elseif ($row[7] == 6) {
												?>
												<option value="1">Present</option>
												<option value="14">Half-Day</option>
												<option value="2">Unauthorized Absent</option>
												<option value="3">Vacation Leave</option>
												<option value="4">Sick Leave</option>
												<option value="5">Forced Leave</option>
												<option value="6" Selected>Special Leave</option>
												<option value="7">Compensatory Time Off</option>
												<option value="8">Maternity Leave</option>
												<option value="9">Paternity Leave</option>
												<option value="10">UML</option>
												<option value="11">USPL</option>
												<option value="12">Travel Order</option>
												<option value="13">DMS</option>
												<option value="15">Holiday</option>
												<option value="16">Special Non-Working Holiday</option>
												<option value="17">Memorandum Order</option>
												<option value="18">Special Order</option>
												<?php
											} elseif ($row[7] == 7) {
												?>
												<option value="1">Present</option>
												<option value="14">Half-Day</option>
												<option value="2">Unauthorized Absent</option>
												<option value="3">Vacation Leave</option>
												<option value="4">Sick Leave</option>
												<option value="5">Forced Leave</option>
												<option value="6">Special Leave</option>
												<option value="7" Selected>Compensatory Time Off</option>
												<option value="8">Maternity Leave</option>
												<option value="9">Paternity Leave</option>
												<option value="10">UML</option>
												<option value="11">USPL</option>
												<option value="12">Travel Order</option>
												<option value="13">DMS</option>
												<option value="15">Holiday</option>
												<option value="16">Special Non-Working Holiday</option>
												<option value="17">Memorandum Order</option>
												<option value="18">Special Order</option>
												<?php
											} elseif ($row[7] == 8) {
												?>
												<option value="1">Present</option>
												<option value="14">Half-Day</option>
												<option value="2">Unauthorized Absent</option>
												<option value="3">Vacation Leave</option>
												<option value="4">Sick Leave</option>
												<option value="5">Forced Leave</option>
												<option value="6">Special Leave</option>
												<option value="7">Compensatory Time Off</option>
												<option value="8" Selected>Maternity Leave</option>
												<option value="9">Paternity Leave</option>
												<option value="10">UML</option>
												<option value="11">USPL</option>
												<option value="12">Travel Order</option>
												<option value="13">DMS</option>
												<option value="15">Holiday</option>
												<option value="16">Special Non-Working Holiday</option>
												<option value="17">Memorandum Order</option>
												<option value="18">Special Order</option>
												<?php
											} elseif ($row[7] == 9) {
												?>
												<option value="1">Present</option>
												<option value="14">Half-Day</option>
												<option value="2">Unauthorized Absent</option>
												<option value="3">Vacation Leave</option>
												<option value="4">Sick Leave</option>
												<option value="5">Forced Leave</option>
												<option value="6">Special Leave</option>
												<option value="7">Compensatory Time Off</option>
												<option value="8">Maternity Leave</option>
												<option value="9" Selected>Paternity Leave</option>
												<option value="10">UML</option>
												<option value="11">USPL</option>
												<option value="12">Travel Order</option>
												<option value="13">DMS</option>
												<option value="15">Holiday</option>
												<option value="16">Special Non-Working Holiday</option>
												<option value="17">Memorandum Order</option>
												<option value="18">Special Order</option>
												<?php
											} elseif ($row[7] == 10) {
												?>
												<option value="1">Present</option>
												<option value="14">Half-Day</option>
												<option value="2">Unauthorized Absent</option>
												<option value="3">Vacation Leave</option>
												<option value="4">Sick Leave</option>
												<option value="5">Forced Leave</option>
												<option value="6">Special Leave</option>
												<option value="7">Compensatory Time Off</option>
												<option value="8">Maternity Leave</option>
												<option value="9">Paternity Leave</option>
												<option value="10" Selected>UML</option>
												<option value="11">USPL</option>
												<option value="12">Travel Order</option>
												<option value="13">DMS</option>
												<option value="15">Holiday</option>
												<option value="16">Special Non-Working Holiday</option>
												<option value="17">Memorandum Order</option>
												<option value="18">Special Order</option>
												<?php
											} elseif ($row[7] == 11) {
												?>
												<option value="1">Present</option>
												<option value="14">Half-Day</option>
												<option value="2">Unauthorized Absent</option>
												<option value="3">Vacation Leave</option>
												<option value="4">Sick Leave</option>
												<option value="5">Forced Leave</option>
												<option value="6">Special Leave</option>
												<option value="7">Compensatory Time Off</option>
												<option value="8">Maternity Leave</option>
												<option value="9">Paternity Leave</option>
												<option value="10">UML</option>
												<option value="11" Selected>USPL</option>
												<option value="12">Travel Order</option>
												<option value="13">DMS</option>
												<option value="15">Holiday</option>
												<option value="16">Special Non-Working Holiday</option>
												<option value="17">Memorandum Order</option>
												<option value="18">Special Order</option>
												<?php
											} elseif ($row[7] == 12) {
												?>
												<option value="1">Present</option>
												<option value="14">Half-Day</option>
												<option value="2">Unauthorized Absent</option>
												<option value="3">Vacation Leave</option>
												<option value="4">Sick Leave</option>
												<option value="5">Forced Leave</option>
												<option value="6">Special Leave</option>
												<option value="7">Compensatory Time Off</option>
												<option value="8">Maternity Leave</option>
												<option value="9">Paternity Leave</option>
												<option value="10">UML</option>
												<option value="11">USPL</option>
												<option value="12" Selected>Travel Order</option>
												<option value="13">DMS</option>
												<option value="15">Holiday</option>
												<option value="16">Special Non-Working Holiday</option>
												<option value="17">Memorandum Order</option>
												<option value="18">Special Order</option>
												<?php
											} elseif ($row[7] == 13) {
												?>
												<option value="1">Present</option>
												<option value="14">Half-Day</option>
												<option value="2">Unauthorized Absent</option>
												<option value="3">Vacation Leave</option>
												<option value="4">Sick Leave</option>
												<option value="5">Forced Leave</option>
												<option value="6">Special Leave</option>
												<option value="7">Compensatory Time Off</option>
												<option value="8">Maternity Leave</option>
												<option value="9">Paternity Leave</option>
												<option value="10">UML</option>
												<option value="11">USPL</option>
												<option value="12">Travel Order</option>
												<option value="13" Selected>DMS</option>
												<option value="15">Holiday</option>
												<option value="16">Special Non-Working Holiday</option>
												<option value="17">Memorandum Order</option>
												<option value="18">Special Order</option>
												<?php
											} elseif ($row[7] == 14) {
												?>
												<option value="1">Present</option>
												<option value="14" Selected>Half-Day</option>
												<option value="2">Unauthorized Absent</option>
												<option value="3">Vacation Leave</option>
												<option value="4">Sick Leave</option>
												<option value="5">Forced Leave</option>
												<option value="6">Special Leave</option>
												<option value="7">Compensatory Time Off</option>
												<option value="8">Maternity Leave</option>
												<option value="9">Paternity Leave</option>
												<option value="10">UML</option>
												<option value="11">USPL</option>
												<option value="12">Travel Order</option>
												<option value="13">DMS</option>
												<option value="15">Holiday</option>
												<option value="16">Special Non-Working Holiday</option>
												<option value="17">Memorandum Order</option>
												<option value="18">Special Order</option>
												<?php
											} elseif ($row[7] == 15) {
												?>
												<option value="1">Present</option>
												<option value="14">Half-Day</option>
												<option value="2">Unauthorized Absent</option>
												<option value="3">Vacation Leave</option>
												<option value="4">Sick Leave</option>
												<option value="5">Forced Leave</option>
												<option value="6">Special Leave</option>
												<option value="7">Compensatory Time Off</option>
												<option value="8">Maternity Leave</option>
												<option value="9">Paternity Leave</option>
												<option value="10">UML</option>
												<option value="11">USPL</option>
												<option value="12">Travel Order</option>
												<option value="13">DMS</option>
												<option value="15" Selected>Holiday</option>
												<option value="16">Special Non-Working Holiday</option>
												<option value="17">Memorandum Order</option>
												<option value="18">Special Order</option>
												<?php
											} elseif ($row[7] == 16) {
												?>
												<option value="1">Present</option>
												<option value="14">Half-Day</option>
												<option value="2">Unauthorized Absent</option>
												<option value="3">Vacation Leave</option>
												<option value="4">Sick Leave</option>
												<option value="5">Forced Leave</option>
												<option value="6">Special Leave</option>
												<option value="7">Compensatory Time Off</option>
												<option value="8">Maternity Leave</option>
												<option value="9">Paternity Leave</option>
												<option value="10">UML</option>
												<option value="11">USPL</option>
												<option value="12">Travel Order</option>
												<option value="13">DMS</option>
												<option value="15">Regular Holiday</option>
												<option value="16" Selected>Special Non-Working Holiday</option>
												<option value="17">Memorandum Order</option>
												<option value="18">Special Order</option>
												<?php
											} elseif ($row[7] == 17) {
												?>
												<option value="1">Present</option>
												<option value="14">Half-Day</option>
												<option value="2">Unauthorized Absent</option>
												<option value="3">Vacation Leave</option>
												<option value="4">Sick Leave</option>
												<option value="5">Forced Leave</option>
												<option value="6">Special Leave</option>
												<option value="7">Compensatory Time Off</option>
												<option value="8">Maternity Leave</option>
												<option value="9">Paternity Leave</option>
												<option value="10">UML</option>
												<option value="11">USPL</option>
												<option value="12">Travel Order</option>
												<option value="13">DMS</option>
												<option value="15">Regular Holiday</option>
												<option value="16">Special Non-Working Holiday</option>
												<option value="17" Selected>Memorandum Order</option>
												<option value="18">Special Order</option>
												<?php
											} elseif ($row[7] == 18) {
												?>
												<option value="1">Present</option>
												<option value="14">Half-Day</option>
												<option value="2">Unauthorized Absent</option>
												<option value="3">Vacation Leave</option>
												<option value="4">Sick Leave</option>
												<option value="5">Forced Leave</option>
												<option value="6">Special Leave</option>
												<option value="7">Compensatory Time Off</option>
												<option value="8">Maternity Leave</option>
												<option value="9">Paternity Leave</option>
												<option value="10">UML</option>
												<option value="11">USPL</option>
												<option value="12">Travel Order</option>
												<option value="13">DMS</option>
												<option value="15">Regular Holiday</option>
												<option value="16">Special Non-Working Holiday</option>
												<option value="17">Memorandum Order</option>
												<option value="18" Selected>Special Order</option>
												<?php
											} else {
												?>
												<option value="1">Present</option>
												<option value="14">Half-Day</option>
												<option value="2">Unauthorized Absent</option>
												<option value="3">Vacation Leave</option>
												<option value="4">Sick Leave</option>
												<option value="5">Forced Leave</option>
												<option value="6">Special Leave</option>
												<option value="7">Compensatory Time Off</option>
												<option value="8">Maternity Leave</option>
												<option value="9">Paternity Leave</option>
												<option value="10">UML</option>
												<option value="11">USPL</option>
												<option value="12">Travel Order</option>
												<option value="13">DMS</option>
												<option value="15">Regular Holiday</option>
												<option value="16">Special Non-Working Holiday</option>
												<option value="17">Memorandum Order</option>
												<option value="18">Special Order</option>
												<?php
											}
										    ?>
										  </select>
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="tardiness">Tardiness (minutes) <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="tardiness" name="tardiness" class="form-control col-md-7 col-xs-12" value="<?php echo $row[8] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="undertime">Undertime (minutes) <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="undertime" name="undertime" class="form-control col-md-7 col-xs-12" value="<?php echo $row[9] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="remarks">Remarks <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="remarks" name="remarks" class="form-control col-md-7 col-xs-12" value="<?php echo $row[10] ?>">
										</div>
									  </div>
									  <div class="ln_solid"></div>
									  <div class="form-group">
										<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
										  <input type="hidden" name="submitted" value="1">
										  <input type="hidden" name="aid" value="<?php echo $aid ?>">
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

