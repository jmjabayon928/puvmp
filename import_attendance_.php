<?php

ini_set('display_errors', 1); 
error_reporting(E_ALL & ~E_NOTICE); 

include("db.php");
include("sqli.php");
include("html.php");

session_start();

if (isset($_SESSION['user_id'])) {
	if (validate_user() == true) {
		if(isset($_POST['submitted'])) { // if submit button has been pressed

			if ($_FILES["file"]["error"] > 0) {
				echo "Error: " . $_FILES["file"]["error"] . "<br>";
			} else {
				$extension = end(explode(".", $_FILES["file"]["name"]));
				if ($extension != 'csv') {
					?><div class="error"><b>File not allowed!</b></div><br /><?PHP
					show_form();
				} else {
					$file = $_FILES['file']['tmp_name'];
					if (($handle = fopen($file, "r")) !== FALSE) {
						
						echo '<table border=1>
								<tr>
									<td>Employee No.</td>
									<td>Given Date</td>
									<td>Given Time</td>
									<td>AM/PM</td>
									
									<td>Formatted Year</td>
									<td>Formatted Month</td>
									<td>Formatted Day</td>
									
									<td>Final Date</td>
									<td>Final Time</td>
								</tr>';
						
						while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {

							$eid = $data[0];
							$ename = $data[1];
							$datetime = $data[5];
							
							$datetime_pcs = explode(" ", $datetime);
							$att_date = $datetime_pcs[0]; $att_time = $datetime_pcs[1]; $att_day = $datetime_pcs[2]; 

							$date_pcs = explode("/", $att_date);
							$year = $date_pcs[2]; $month = $date_pcs[0]; $day = $date_pcs[1]; 
							
							$day_length = strlen($day); 
							if ($day_length == 1) $day = '0'.$day;
							if (strlen($month < 2)) $month = '0'.$month; 
							
							
							$time_pcs = explode(":", $att_time);
							$hour = $time_pcs[0]; $minutes = $time_pcs[1]; $seconds = $time_pcs[2]; 
							
							if ($att_day == "PM") {
								if ($hour < 12) {
									$hour = $hour + 12;
								}
							}
							$hour_length = strlen($hour); 
							if ($hour_length == 1) $hour = '0'.$hour;

							$final_date = $year.'-'.$month.'-'.$day; 
							$final_time = $hour.':'.$minutes.':00'; 
								
							echo '<tr>
									<td>'.$eid.'</td>

									<td>'.$att_date.'</td>
									<td>'.$att_time.'</td>
									<td>'.$att_day.'</td>
									
									<td>'.$year.'</td>
									<td>'.$month.'</td>
									<td>'.$day.'</td>
									
									
									<td>'.$final_date.'</td>
									<td>'.$final_time.'</td>
								</tr>';
								
							// get the TIME_TO_SEC($final_time)
							$sql = "SELECT TIME_TO_SEC('$final_time')";
							$gres = query($sql); 
							$grow = fetch_array($gres); 
							$gsec = $grow[0]; 
								
							// get the work hours of the employee
							$sql = "SELECT HOUR(time_in) FROM employees WHERE eid = '$eid'";
							$hres = query($sql); 
							$hrow = fetch_array($hres); 
							$time_in = $hrow[0]; 
							
							$six_am = 21600; 
							$seven_am = 25200;
							$eight_am = 28800;
							$nine_am = 32400;
							$ten_am = 36000;
							$eleven_am = 39600;
							$twelve_pm = 43200;
							$thirteen_pm = 46800; 
							$fourteen_pm = 50400; 
							$fifteen_pm = 54000; 
							$sixteen_pm = 57600; 
							$seventeen_pm = 61200; 
							$eighteen_pm = 64800;
							
							if ($time_in == 6) {
								$intended_am_in = $six_am;
								$intended_am_out = $ten_am;
								$intended_pm_in = $eleven_am;
								$intended_pm_out = $fifteen_pm;
							} elseif ($time_in == 7) {
								$intended_am_in = $seven_am;
								$intended_am_out = $eleven_am;
								$intended_pm_in = $twelve_pm;
								$intended_pm_out = $sixteen_pm;
							} elseif ($time_in == 8) {
								$intended_am_in = $eight_am;
								$intended_am_out = $twelve_pm;
								$intended_pm_in = $thirteen_pm;
								$intended_pm_out = $seventeen_pm;
							} elseif ($time_in == 9) {
								$intended_am_in = $nine_am;
								$intended_am_out = $thirteen_pm;
								$intended_pm_in = $fourteen_pm;
								$intended_pm_out = $eighteen_pm;
							}
							
							// check if an attendance for this employee and date already exists
							$sql = "SELECT aid, TIME_TO_SEC(am_in), TIME_TO_SEC(am_out), TIME_TO_SEC(pm_in), TIME_TO_SEC(pm_out) 
									FROM attendance 
									WHERE eid = '$eid' AND TO_DAYS(adate) = TO_DAYS('$final_date')";
							$res = query($sql); 
							$num = num_rows($res); 
							
							if ($num > 0) {
								$row = fetch_array($res); 
								$aid = $row[0]; $am_in = $row[1]; $am_out = $row[2]; $pm_in = $row[3]; $pm_out = $row[4]; 
								
								// update the attendance
								
								if ( $am_in == 0 && $am_out == 0 && $pm_in == 0 && $pm_out == 0 ) {
									// no time in but has attendance for leave, dms, travel order, ob, etc
									
									if ( $gsec < $intended_am_out ) {
										// update attendance
										$sql = "UPDATE attendance SET am_in = '$final_time' WHERE aid = '$aid' LIMIT 1";
										if (@query($sql)) {
											// update time in attendance 
											update_time_in_attendance($aid); 
										}
									} elseif ( $gsec >= $intended_am_out && $gsec < $intended_pm_out ) {
										// update attendance
										$sql = "UPDATE attendance SET pm_in = '$final_time' WHERE aid = '$aid' LIMIT 1";
										if (@query($sql)) {
											// update time in attendance 
											update_time_in_attendance($aid); 
										}
									}
								} elseif ( $am_in != 0 && $am_out == 0 && $pm_in == 0 && $pm_out == 0 ) {
									// already timed in in the morning
									
									if ( $intended_am_in < $gsec && $gsec < $intended_pm_in ) {
										// time in is between intended am in and intended pm in
										// update attendance
										$sql = "UPDATE attendance SET am_out = '$final_time' WHERE aid = '$aid' LIMIT 1";
										if (@query($sql)) {
											// update time in attendance 
											update_time_in_attendance($aid); 
										}
									} elseif ( $gsec >= $intended_pm_in && $gsec < $intended_pm_out ) {
										// time in is between intended pm in and intended pm out
										// update attendance
										$sql = "UPDATE attendance SET pm_in = '$final_time', status = -1 WHERE aid = '$aid' LIMIT 1";
										if (@query($sql)) {
											// update time in attendance 
											update_time_in_attendance($aid); 
										}
									}
								} elseif ( $am_in == 0 && $am_out == 0 && $pm_in != 0 && $pm_out == 0 ) {
									// already time in in the afternoon
									
									if ( $pm_in < $gsec ) {
										// time in is greater than the recorded pm in
										// update attendance
										$sql = "UPDATE attendance SET pm_out = '$final_time' WHERE aid = '$aid' LIMIT 1";
										if (@query($sql)) {
											// update time in attendance 
											update_time_in_attendance($aid); 
										}
									}
								} elseif ( $am_in != 0 && $am_out != 0 && $pm_in == 0 && $pm_out == 0 ) {
									// already timed in in both morning in and morning out
									
									if ( $gsec > $intended_am_out && $gsec < $intended_pm_out ) {
										// time in is between intended am out and intended pm out
										// update attendance
										$sql = "UPDATE attendance SET pm_in = '$final_time' WHERE aid = '$aid' LIMIT 1";
										if (@query($sql)) {
											// update time in attendance 
											update_time_in_attendance($aid); 
										}
									}
									
								} elseif ( $am_in != 0 && $am_out != 0 && $pm_in != 0 && $pm_out == 0 ) {
									// already timed in in morning in, morning out and pm in
									
									if ( $gsec > $intended_pm_in ) {
										// time in is later than intended pm in 
										// update attendance
										$sql = "UPDATE attendance SET pm_out = '$final_time' WHERE aid = '$aid' LIMIT 1";
										if (@query($sql)) {
											// update time in attendance 
											update_time_in_attendance($aid); 
										}
									}
									
								} elseif ( $am_in != 0 && $am_out == 0 && $pm_in != 0 && $pm_out == 0 ) {
									// am in and pm in are only present
									
									if ( $gsec > $intended_pm_in ) {
										// time in is later than intended pm in 
										// update attendance
										$sql = "UPDATE attendance SET pm_out = '$final_time' WHERE aid = '$aid' LIMIT 1";
										if (@query($sql)) {
											// update time in attendance 
											update_time_in_attendance($aid); 
										}
									}
									
								}
							} else {
								// there is none so get the time to consider where to insert
								
								if ( ($gsec <= $intended_am_in) || ($intended_am_in < $gsec && $gsec < $intended_am_out) ) { 
									// time in is earlier or same with intended am in OR time in is between intended am in and intended am out
									$sql = "INSERT INTO attendance(eid, adate, am_in, remarks, status, last_update_id, last_update) 
											VALUES('$eid', '$final_date', '$final_time', 'Imported from Excel File', 1, '$_SESSION[user_id]', NOW())";
									if (@query($sql)) {
										$aid = mysqli_insert_id($connection);
										// update time in attendance 
										update_time_in_attendance($aid); 
									}
								} elseif ( $gsec >= $intended_am_out && $gsec < $intended_pm_out ) {
									// time in is between intended am out and intended pm out
									$sql = "INSERT INTO attendance(eid, adate, pm_in, , remarks, status, last_update_id, last_update) 
											VALUES('$eid', '$final_date', '$final_time', 'Imported from Excel File', 1, '$_SESSION[user_id]', NOW())";
									if (@query($sql)) {
										$aid = mysqli_insert_id($connection);
										// update time in attendance 
										update_time_in_attendance($aid); 
									}
								}
								
							}
							
						}
						
						echo '</table>';
						
						fclose($handle);
					}
					?></table><?PHP
				}
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

				<title>OTC - Import Employees' Attendance</title>

				<!-- Bootstrap -->
				<link href="vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
				<!-- Font Awesome -->
				<link href="vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
				<!-- iCheck -->
				<link href="vendors/iCheck/skins/flat/green.css" rel="stylesheet">
				<!-- Datatables -->
				<link href="vendors/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">

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
					
					<?php
					?>

					<!-- page content -->
					<div class="right_col" role="main">
					  <div class="">
						<div class="page-title">
						  <div class="title_left">
							<h3>Import Employees' Attendance</h3>
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
							  <div class="x_title">
								<h2>Import Employees' Attendance</h2>
								<div class="clearfix"></div>
							  </div>
							  <div class="x_content">
								<form id="contact-form" name="contact-form" action="import_attendance.php" method="POST" enctype="multipart/form-data">					
									<fieldset>
										<label><span class="text-form">Filename:</span><input type="file" name="file" id="file"></label>
										<div class="buttons">
											<a class="button-2" onClick="document.getElementById('contact-form').submit()">Submit</a>
										</div>
									</fieldset>						
									<input type="hidden" name="token" value="<?PHP echo $token ?>">
									<input type="hidden" name="submitted" value="1">
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
				<!-- iCheck -->
				<script src="vendors/iCheck/icheck.min.js"></script>
				<!-- Datatables -->
				<script src="vendors/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>

				<!-- Custom Theme Scripts -->
				<script src="build/js/custom.min.js"></script>

			  </body>
			</html>	
			<?php
		}
	}
} else {
	header('Location: login.php');	
}
