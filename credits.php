<?php

include("db.php");
include("sqli.php");
include("html.php");
define('PAGE', 'credits.php'); 

session_start();

if (isset($_SESSION['user_id'])) {
	if (validate_level3(PAGE) == true) {
		// log the activity
		log_user(5, 'credits', 0);
		
		if (isset ($_GET['gmonth'])) {
			$gmonth = $_GET['gmonth']; 
		} else {
			$sql = "SELECT MONTH(CURDATE())";
			$res = query($sql); 
			$row = fetch_array($res); 
			$gmonth = $row[0]; 
		}
		if (isset ($_GET['gyear'])) {
			$gyear = $_GET['gyear']; 
		} else {
			$sql = "SELECT YEAR(CURDATE())";
			$res = query($sql); 
			$row = fetch_array($res); 
			$gyear = $row[0]; 
		}
		
		// get the current year
		$sql = "SELECT YEAR(CURDATE())";
		$res = query($sql); 
		$row = fetch_array($res); 
		$cyear = $row[0]; 
		
		?>
		<!DOCTYPE html>
		<html lang="en">
		  <head>
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
			<!-- Meta, title, CSS, favicons, etc. -->
			<meta charset="utf-8">
			<meta http-equiv="X-UA-Compatible" content="IE=edge">
			<meta name="viewport" content="width=device-width, initial-scale=1">

			<title>OTC - Employees' Leave Credits</title>

			<!-- Bootstrap -->
			<link href="vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
			<!-- Font Awesome -->
			<link href="vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
			<!-- Datatables -->
			<link href="vendors/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
			<link href="vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css" rel="stylesheet">
			<link href="vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css" rel="stylesheet">
			<link href="vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css" rel="stylesheet">
			<link href="vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css" rel="stylesheet">

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
						<h3>Employees Leave Credits</h3>
					  </div>

					  <div class="title_right">
						<div class="col-xs-4 form-group pull-right top_search">
							<select onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);" style="font-family:arial;text-shadow: 1px 1px #999;font-size:18px;line-height:1.1em;color:#333;">
								<?php
								if ($gmonth == 1) {
									?>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=1" Selected>January</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=2">February</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=3">March</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=4">April</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=5">May</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=6">June</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=7">July</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=8">August</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=9">September</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=10">October</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=11">November</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=12">December</option>
									<?php
								} elseif ($gmonth == 2) {
									?>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=1">January</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=2" Selected>February</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=3">March</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=4">April</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=5">May</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=6">June</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=7">July</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=8">August</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=9">September</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=10">October</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=11">November</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=12">December</option>
									<?php
								} elseif ($gmonth == 3) {
									?>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=1">January</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=2">February</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=3" Selected>March</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=4">April</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=5">May</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=6">June</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=7">July</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=8">August</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=9">September</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=10">October</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=11">November</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=12">December</option>
									<?php
								} elseif ($gmonth == 4) {
									?>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=1">January</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=2">February</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=3">March</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=4" Selected>April</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=5">May</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=6">June</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=7">July</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=8">August</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=9">September</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=10">October</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=11">November</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=12">December</option>
									<?php
								} elseif ($gmonth == 5) {
									?>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=1">January</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=2">February</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=3">March</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=4">April</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=5" Selected>May</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=6">June</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=7">July</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=8">August</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=9">September</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=10">October</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=11">November</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=12">December</option>
									<?php
								} elseif ($gmonth == 6) {
									?>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=1">January</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=2">February</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=3">March</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=4">April</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=5">May</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=6" Selected>June</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=7">July</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=8">August</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=9">September</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=10">October</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=11">November</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=12">December</option>
									<?php
								} elseif ($gmonth == 7) {
									?>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=1">January</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=2">February</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=3">March</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=4">April</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=5">May</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=6">June</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=7" Selected>July</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=8">August</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=9">September</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=10">October</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=11">November</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=12">December</option>
									<?php
								} elseif ($gmonth == 8) {
									?>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=1">January</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=2">February</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=3">March</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=4">April</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=5">May</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=6">June</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=7">July</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=8" Selected>August</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=9">September</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=10">October</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=11">November</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=12">December</option>
									<?php
								} elseif ($gmonth == 9) {
									?>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=1">January</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=2">February</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=3">March</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=4">April</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=5">May</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=6">June</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=7">July</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=8">August</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=9" Selected>September</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=10">October</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=11">November</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=12">December</option>
									<?php
								} elseif ($gmonth == 10) {
									?>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=1">January</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=2">February</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=3">March</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=4">April</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=5">May</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=6">June</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=7">July</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=8">August</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=9">September</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=10" Selected>October</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=11">November</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=12">December</option>
									<?php
								} elseif ($gmonth == 11) {
									?>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=1">January</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=2">February</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=3">March</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=4">April</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=5">May</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=6">June</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=7">July</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=8">August</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=9">September</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=10">October</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=11" Selected>November</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=12">December</option>
									<?php
								} elseif ($gmonth == 12) {
									?>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=1">January</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=2">February</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=3">March</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=4">April</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=5">May</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=6">June</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=7">July</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=8">August</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=9">September</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=10">October</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=11">November</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=12" Selected>December</option>
									<?php
								} else {
									?>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=1">January</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=2">February</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=3">March</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=4">April</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=5">May</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=6">June</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=7">July</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=8">August</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=9">September</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=10">October</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=11">November</option>
									<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=12">December</option>
									<?php
								}
								?>
							</select>&nbsp; 
							<select onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);" style="font-family:arial;text-shadow: 1px 1px #999;font-size:18px;line-height:1.1em;color:#333;">
								<?php
								for ($i=$cyear; $i>=2018; $i--) {
									if ($gyear == $i) {
										?><option value="credits.php?gyear=<?php echo $i ?>&gmonth=<?php echo $gmonth ?>" Selected><?PHP echo $i ?></option><?PHP
									} else {
										?><option value="credits.php?gyear=<?php echo $i ?>&gmonth=<?php echo $gmonth ?>"><?PHP echo $i ?></option><?PHP
									}
								}
								?>
							</select>
						</div>
					  </div>
					</div>

					<div class="clearfix"></div>

					<div class="row">
					
						<div class="col-md-12 col-sm-12 col-xs-12">
							<div class="x_panel">
								<div class="x_content">
									<table class="table table-striped table-bordered jambo_table">
										<thead>
											<tr>
												<th width="08%" rowspan="2">NAME</th>
												<th colspan="3">FORWARDED</th>
												<th colspan="3">TARDINESS</th>
												<th colspan="3">UNDERTIME</th>
												<th width="04%" rowspan="2">UA</th>
												<th colspan="7">USED LEAVE CREDITS</th>
												<th colspan="3">ADDITIONAL</th>
												<th colspan="3">ENDING</th>
											</tr>
											<tr>
												<th width="04%">VL</th>
												<th width="04%">SL</th>
												<th width="04%">COC</th>
												<th width="04%">Freq</th>
												<th width="04%">Min.</th>
												<th width="04%">Credits</th>
												<th width="04%">Freq</th>
												<th width="04%">Min.</th>
												<th width="04%">Credits</th>
												<th width="04%">VL</th>
												<th width="04%">SL</th>
												<th width="04%">FL</th>
												<th width="04%">SPL</th>
												<th width="04%">CTO</th>
												<th width="04%">UML</th>
												<th width="04%">USPL</th>
												<th width="04%">VL</th>
												<th width="04%">SL</th>
												<th width="04%">COC</th>
												<th width="04%">VL</th>
												<th width="04%">SL</th>
												<th width="04%">COC</th>
											</tr>
										</thead>
										<tbody>
										<?php
										if ($gmonth == 12) {
											$nxt_month = 1;
											$nxt_year = $gyear + 1; 
										} else {
											$nxt_month = $gmonth + 1;
											$nxt_year = $gyear; 
										}
										
										$sql = "SELECT eid, CONCAT(lname,', ',fname), TIME_TO_SEC(time_in), TIME_TO_SEC(time_out)
												FROM employees
												WHERE active = 1 AND (etype = 1 OR etype = 2)
												ORDER BY lname, fname";
										$res = query($sql); 
										while ($row = fetch_array($res)) {
											$tardy_conversion = 0.00; 
											$ut_conversion = 0.00; 
											$ending_vl = 0.00;
											$ending_sl = 0.00;
											$ending_coc = 0;
											
											$hour_tardy = 0;
											$minute_tardy = 0; 
											
											$hour_ut = 0; 
											$minute_ut = 0; 
											
											// get forwarded credits
											$sql = "SELECT vl, sl, coc
													FROM employees_credits 
													WHERE eid = '$row[0]' AND MONTH(ldate) = '$gmonth' AND YEAR(ldate) = '$gyear'";
											$cres = query($sql); $crow = fetch_array($cres); 
											// get the number of tardiness
											$sql = "SELECT COUNT(aid), SUM(tardiness)
													FROM attendance
													WHERE eid = '$row[0]' AND tardiness > 0
														AND YEAR(adate) = '$gyear' AND MONTH(adate) = '$gmonth'";
											$tardy_res = query($sql); $tardy_row = fetch_array($tardy_res); 
											
											if ($tardy_row[1] > 0) {
												
												$hours = floor($tardy_row[1] / 60);
												$minutes = fmod($tardy_row[1], 60);
												
												if ($hours > 0) {
													// get the conversion for 60 minutes
													$sql = "SELECT conversion FROM leave_conversions WHERE minute = 60";
													$mres = query($sql); $mrow = fetch_array($mres); 
													$hour_tardy = $hours * $mrow[0]; 
												}
												if ($minutes > 0) {
													// get the conversion for 60 minutes
													$sql = "SELECT conversion FROM leave_conversions WHERE minute = '$minutes'";
													$mres = query($sql); $mrow = fetch_array($mres); 
													$minute_tardy = $mrow[0]; 
												}
												
												$tardy_conversion = $hour_tardy + $minute_tardy; 
											}
											
											// get the number of undertime
											$sql = "SELECT COUNT(aid), SUM(undertime)
													FROM attendance
													WHERE eid = '$row[0]' AND undertime > 0 
														AND YEAR(adate) = '$gyear' AND MONTH(adate) = '$gmonth'";
											$ut_res = query($sql); $ut_row = fetch_array($ut_res); 
											
											if ($ut_row[1] > 0) {
												$hours = floor($ut_row[1] / 60);
												$minutes = fmod($ut_row[1], 60);
												
												if ($hours > 0) {
													// get the conversion for 60 minutes
													$sql = "SELECT conversion FROM leave_conversions WHERE minute = 60";
													$mres = query($sql); $mrow = fetch_array($mres); 
													$hour_ut = $hours * $mrow[0]; 
												}
												if ($minutes > 0) {
													// get the conversion for 60 minutes
													$sql = "SELECT conversion FROM leave_conversions WHERE minute = '$minutes'";
													$mres = query($sql); $mrow = fetch_array($mres); 
													$minute_ut = $mrow[0]; 
												}
												
												$ut_conversion = $hour_ut + $minute_ut; 
											}
											
											// get the number of Unauthorized Absent
											$sql = "SELECT COUNT(aid) 
													FROM attendance
													WHERE eid = '$row[0]' AND status = 2 AND YEAR(adate) = '$gyear' AND MONTH(adate) = '$gmonth'";
											$ua_res = query($sql); $ua_row = fetch_array($ua_res); 
											// get the number of VL
											$sql = "SELECT SUM(lnum)
													FROM internals_leave_applications
													WHERE eid = '$row[0]' AND lid = 1 AND stats = 1 
														AND YEAR(fr_date) = '$gyear' AND MONTH(fr_date) = '$gmonth'
														AND YEAR(to_date) = '$gyear' AND MONTH(to_date) = '$gmonth'";
											$vl_res = query($sql); $vl_row = fetch_array($vl_res); 
											// get the number of SL
											$sql = "SELECT SUM(lnum)
													FROM internals_leave_applications
													WHERE eid = '$row[0]' AND lid = 2 AND stats = 1 
														AND YEAR(fr_date) = '$gyear' AND MONTH(fr_date) = '$gmonth'
														AND YEAR(to_date) = '$gyear' AND MONTH(to_date) = '$gmonth'";
											$sl_res = query($sql); $sl_row = fetch_array($sl_res); 
											// get the number of FL
											$sql = "SELECT SUM(lnum)
													FROM internals_leave_applications
													WHERE eid = '$row[0]' AND lid = 3 AND stats = 1 
														AND YEAR(fr_date) = '$gyear' AND MONTH(fr_date) = '$gmonth'
														AND YEAR(to_date) = '$gyear' AND MONTH(to_date) = '$gmonth'";
											$fl_res = query($sql); $fl_row = fetch_array($fl_res); 
											// get the number of SPL
											$sql = "SELECT SUM(lnum)
													FROM internals_leave_applications
													WHERE eid = '$row[0]' AND lid = 4 AND stats = 1 
														AND YEAR(fr_date) = '$gyear' AND MONTH(fr_date) = '$gmonth'
														AND YEAR(to_date) = '$gyear' AND MONTH(to_date) = '$gmonth'";
											$spl_res = query($sql); $spl_row = fetch_array($spl_res); 
											// get the number of CTO
											$sql = "SELECT SUM(lnum * 8)
													FROM internals_leave_applications
													WHERE eid = '$row[0]' AND lid = 5 AND stats = 1 
														AND YEAR(fr_date) = '$gyear' AND MONTH(fr_date) = '$gmonth'
														AND YEAR(to_date) = '$gyear' AND MONTH(to_date) = '$gmonth'";
											$cto_res = query($sql); $cto_row = fetch_array($cto_res); 
											// get the number of UML
											$sql = "SELECT SUM(lnum)
													FROM internals_leave_applications
													WHERE eid = '$row[0]' AND lid = 8 AND stats = 1 
														AND YEAR(fr_date) = '$gyear' AND MONTH(fr_date) = '$gmonth'
														AND YEAR(to_date) = '$gyear' AND MONTH(to_date) = '$gmonth'";
											$uml_res = query($sql); $uml_row = fetch_array($uml_res); 
											// get the number of USPL
											$sql = "SELECT SUM(lnum)
													FROM internals_leave_applications
													WHERE eid = '$row[0]' AND lid = 9 AND stats = 1 
														AND YEAR(fr_date) = '$gyear' AND MONTH(fr_date) = '$gmonth'
														AND YEAR(to_date) = '$gyear' AND MONTH(to_date) = '$gmonth'";
											$uspl_res = query($sql); $uspl_row = fetch_array($uspl_res); 
											// get the earned COCs
											$sql = "SELECT SUM(hrs_num)
													FROM cocs 
													WHERE eid = '$row[0]' AND YEAR(cdate) = '$gyear' AND MONTH(cdate) = '$gmonth'";
											$coc_res = query($sql); $coc_row = fetch_array($coc_res); 
											
											$ending_vl = $crow[0] + 1.25 - ($tardy_conversion + $ut_conversion + $vl_row[0] + $fl_row[0]);
											$ending_sl = $crow[1] + 1.25 - $sl_row[0]; 
											$ending_coc = $crow[2] + $coc_row[0] - $cto_row[0];
											
											if ($ending_sl < 0) {
												$diff = abs($ending_sl); 
												
												$ending_vl = $ending_vl - $diff;
												$ending_sl = 0; 
											}
											?>
											<tr>
												<td align="left"><a href="employee.php?eid=<?php echo $row[0] ?>"><?php echo $row[1] ?></a></td>
												<td align="right"><?php echo $crow[0] ?></td>
												<td align="right"><?php echo $crow[1] ?></td>
												<td align="right"><?php echo $crow[2] ?></td>
												<td align="right"><a target="new" href="credit_details.php?eid=<?php echo $row[0] ?>&type=1&gmonth=<?php echo $gmonth ?>&gyear=<?php echo $gyear ?>"><?php echo number_format($tardy_row[0], 0) ?></a></td>
												<td align="right"><a target="new" href="credit_details.php?eid=<?php echo $row[0] ?>&type=1&gmonth=<?php echo $gmonth ?>&gyear=<?php echo $gyear ?>"><?php echo number_format($tardy_row[1], 0) ?></a></td>
												<td align="right"><?php echo $tardy_conversion ?></td>
												<td align="right"><a target="new" href="credit_details.php?eid=<?php echo $row[0] ?>&type=2&gmonth=<?php echo $gmonth ?>&gyear=<?php echo $gyear ?>"><?php echo number_format($ut_row[0], 0) ?></a></td>
												<td align="right"><a target="new" href="credit_details.php?eid=<?php echo $row[0] ?>&type=2&gmonth=<?php echo $gmonth ?>&gyear=<?php echo $gyear ?>"><?php echo number_format($ut_row[1], 0) ?></a></td>
												<td align="right"><?php echo $ut_conversion ?></td>
												<td align="right"><?php echo number_format($ua_row[0], 0) ?></td>
												<td align="right"><?php echo number_format($vl_row[0], 0) ?></td>
												<td align="right"><?php echo number_format($sl_row[0], 0) ?></td>
												<td align="right"><?php echo number_format($fl_row[0], 0) ?></td>
												<td align="right"><?php echo number_format($spl_row[0], 0) ?></td>
												<td align="right"><?php echo number_format($cto_row[0], 0) ?></td>
												<td align="right"><?php echo number_format($uml_row[0], 0) ?></td>
												<td align="right"><?php echo number_format($uspl_row[0], 0) ?></td>
												<td align="right">1.25</td>
												<td align="right">1.25</td>
												<td align="right"><?php echo number_format($coc_row[0], 0) ?></td>
												<td align="right"><?php echo number_format($ending_vl, 3) ?></td>
												<td align="right"><?php echo number_format($ending_sl, 3) ?></td>
												<td align="right"><?php echo number_format($ending_coc, 3) ?></td>
											</tr>
											<?php
											free_result($cres); 
											free_result($tardy_res); 
											free_result($ut_res); 
											free_result($vl_res); 
											free_result($sl_res); 
											free_result($fl_res); 
											free_result($spl_res); 
											free_result($cto_res); 
											free_result($uml_res); 
											free_result($uspl_res); 
											
											// fetch the beginning of next month
											$sql = "SELECT lid FROM employees_credits WHERE eid = '$row[0]' AND MONTH(ldate) = '$nxt_month' AND YEAR(ldate) = '$nxt_year'";
											$nres = query($sql); 
											$nnum = num_rows($nres); 
											
											if ($nnum > 0) {
												$nrow = fetch_array($nres); 
												
												// update the employees_credits
												$sql = "UPDATE employees_credits 
														SET vl = '$ending_vl',
															sl = '$ending_sl',
															coc = '$ending_coc' 
														WHERE lid = '$nrow[0]' 
														LIMIT 1";
												//query($sql);
											} else {
												if (strlen($nxt_month) == 1) $nxt_month = '0'.$nxt_month; 
												
												$nxt_ldate = $nxt_year.'-'.$nxt_month.'-01';
												// insert a new record
												$sql = "INSERT INTO employees_credits(ldate, eid, vl, sl, coc)
														VALUES('$nxt_ldate', '$row[0]', '$ending_vl', '$ending_sl', '$ending_coc')";
												//query($sql); 
											}
										}
										free_result($res);
										?>
										</tbody>
									</table>
								</div><!-- x_content -->
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
			<!-- Datatables -->
			<script src="vendors/datatables.net/js/jquery.dataTables.min.js"></script>
			<script src="vendors/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
			<script src="vendors/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
			<script src="vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js"></script>
			<script src="vendors/datatables.net-buttons/js/buttons.flash.min.js"></script>
			<script src="vendors/datatables.net-buttons/js/buttons.html5.min.js"></script>
			<script src="vendors/datatables.net-buttons/js/buttons.print.min.js"></script>
			<script src="vendors/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js"></script>
			<script src="vendors/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
			<script src="vendors/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
			<script src="vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js"></script>
			<script src="vendors/datatables.net-scroller/js/dataTables.scroller.min.js"></script>

			<!-- Custom Theme Scripts -->
			<script src="build/js/custom.min.js"></script>

		  </body>
		</html>
		<?php
	} else {
		// check if user is a valid employee
		$sql = "SELECT eid FROM users WHERE user_id = '$_SESSION[user_id]' AND active = 1";
		$vres = query($sql); $vrow = fetch_array($vres); $eid = $vrow[0];
		
		// get the type of employee
		$sql = "SELECT etype FROM employees WHERE eid = '$eid'";
		$eres = query($sql); $erow = fetch_array($eres); $etype = $erow[0];
		
		if ($eid != 0 && $etype < 3) {
			// log the activity
			log_user(5, 'credits', 0);
			
			if (isset ($_GET['gmonth'])) {
				$gmonth = $_GET['gmonth']; 
			} else {
				$sql = "SELECT MONTH(CURDATE())";
				$res = query($sql); 
				$row = fetch_array($res); 
				$gmonth = $row[0]; 
			}
			if (isset ($_GET['gyear'])) {
				$gyear = $_GET['gyear']; 
			} else {
				$sql = "SELECT YEAR(CURDATE())";
				$res = query($sql); 
				$row = fetch_array($res); 
				$gyear = $row[0]; 
			}
			
			// get the current year
			$sql = "SELECT YEAR(CURDATE())";
			$res = query($sql); 
			$row = fetch_array($res); 
			$cyear = $row[0]; 
			
			?>
			<!DOCTYPE html>
			<html lang="en">
			  <head>
				<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
				<!-- Meta, title, CSS, favicons, etc. -->
				<meta charset="utf-8">
				<meta http-equiv="X-UA-Compatible" content="IE=edge">
				<meta name="viewport" content="width=device-width, initial-scale=1">

				<title>OTC - Employees' Leave Credits</title>

				<!-- Bootstrap -->
				<link href="vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
				<!-- Font Awesome -->
				<link href="vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
				<!-- Datatables -->
				<link href="vendors/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
				<link href="vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css" rel="stylesheet">
				<link href="vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css" rel="stylesheet">
				<link href="vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css" rel="stylesheet">
				<link href="vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css" rel="stylesheet">

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
							<h3>Employees Leave Credits</h3>
						  </div>

						  <div class="title_right">
							<div class="col-xs-4 form-group pull-right top_search">
								<select onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);" style="font-family:arial;text-shadow: 1px 1px #999;font-size:18px;line-height:1.1em;color:#333;">
									<?php
									if ($gmonth == 1) {
										?>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=1" Selected>January</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=2">February</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=3">March</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=4">April</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=5">May</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=6">June</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=7">July</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=8">August</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=9">September</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=10">October</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=11">November</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=12">December</option>
										<?php
									} elseif ($gmonth == 2) {
										?>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=1">January</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=2" Selected>February</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=3">March</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=4">April</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=5">May</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=6">June</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=7">July</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=8">August</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=9">September</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=10">October</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=11">November</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=12">December</option>
										<?php
									} elseif ($gmonth == 3) {
										?>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=1">January</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=2">February</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=3" Selected>March</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=4">April</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=5">May</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=6">June</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=7">July</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=8">August</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=9">September</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=10">October</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=11">November</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=12">December</option>
										<?php
									} elseif ($gmonth == 4) {
										?>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=1">January</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=2">February</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=3">March</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=4" Selected>April</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=5">May</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=6">June</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=7">July</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=8">August</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=9">September</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=10">October</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=11">November</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=12">December</option>
										<?php
									} elseif ($gmonth == 5) {
										?>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=1">January</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=2">February</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=3">March</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=4">April</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=5" Selected>May</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=6">June</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=7">July</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=8">August</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=9">September</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=10">October</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=11">November</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=12">December</option>
										<?php
									} elseif ($gmonth == 6) {
										?>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=1">January</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=2">February</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=3">March</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=4">April</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=5">May</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=6" Selected>June</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=7">July</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=8">August</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=9">September</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=10">October</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=11">November</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=12">December</option>
										<?php
									} elseif ($gmonth == 7) {
										?>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=1">January</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=2">February</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=3">March</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=4">April</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=5">May</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=6">June</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=7" Selected>July</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=8">August</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=9">September</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=10">October</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=11">November</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=12">December</option>
										<?php
									} elseif ($gmonth == 8) {
										?>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=1">January</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=2">February</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=3">March</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=4">April</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=5">May</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=6">June</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=7">July</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=8" Selected>August</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=9">September</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=10">October</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=11">November</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=12">December</option>
										<?php
									} elseif ($gmonth == 9) {
										?>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=1">January</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=2">February</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=3">March</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=4">April</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=5">May</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=6">June</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=7">July</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=8">August</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=9" Selected>September</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=10">October</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=11">November</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=12">December</option>
										<?php
									} elseif ($gmonth == 10) {
										?>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=1">January</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=2">February</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=3">March</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=4">April</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=5">May</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=6">June</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=7">July</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=8">August</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=9">September</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=10" Selected>October</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=11">November</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=12">December</option>
										<?php
									} elseif ($gmonth == 11) {
										?>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=1">January</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=2">February</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=3">March</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=4">April</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=5">May</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=6">June</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=7">July</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=8">August</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=9">September</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=10">October</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=11" Selected>November</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=12">December</option>
										<?php
									} elseif ($gmonth == 12) {
										?>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=1">January</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=2">February</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=3">March</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=4">April</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=5">May</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=6">June</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=7">July</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=8">August</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=9">September</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=10">October</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=11">November</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=12" Selected>December</option>
										<?php
									} else {
										?>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=1">January</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=2">February</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=3">March</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=4">April</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=5">May</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=6">June</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=7">July</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=8">August</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=9">September</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=10">October</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=11">November</option>
										<option value="credits.php?gyear=<?php echo $gyear ?>&gmonth=12">December</option>
										<?php
									}
									?>
								</select>&nbsp; 
								<select onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);" style="font-family:arial;text-shadow: 1px 1px #999;font-size:18px;line-height:1.1em;color:#333;">
									<?php
									for ($i=$cyear; $i>=2018; $i--) {
										if ($gyear == $i) {
											?><option value="credits.php?gyear=<?php echo $i ?>&gmonth=<?php echo $gmonth ?>" Selected><?PHP echo $i ?></option><?PHP
										} else {
											?><option value="credits.php?gyear=<?php echo $i ?>&gmonth=<?php echo $gmonth ?>"><?PHP echo $i ?></option><?PHP
										}
									}
									?>
								</select>
							</div>
						  </div>
						</div>

						<div class="clearfix"></div>

						<div class="row">
						
							<div class="col-md-12 col-sm-12 col-xs-12">
								<div class="x_panel">
									<div class="x_content">
										<table id="datatable-fixed-header" class="table table-striped table-bordered jambo_table">
											<thead>
												<tr>
													<th width="14%" rowspan="2">NAME</th>
													<th colspan="2">FORWARDED</th>
													<th colspan="3">TARDINESS</th>
													<th colspan="3">UNDERTIME</th>
													<th width="04%" rowspan="2">UA</th>
													<th colspan="7">USED LEAVE CREDITS</th>
													<th colspan="2">ADDITIONAL</th>
													<th colspan="2">ENDING</th>
												</tr>
												<tr>
													<th width="05%">VL</th>
													<th width="05%">SL</th>
													<th width="04%">Freq</th>
													<th width="04%">Minutes</th>
													<th width="04%">Credits</th>
													<th width="04%">Freq</th>
													<th width="04%">Minutes</th>
													<th width="04%">Credits</th>
													<th width="04%">VL</th>
													<th width="04%">SL</th>
													<th width="04%">FL</th>
													<th width="04%">SPL</th>
													<th width="04%">CTO</th>
													<th width="04%">UML</th>
													<th width="04%">USPL</th>
													<th width="05%">VL</th>
													<th width="05%">SL</th>
													<th width="05%">VL</th>
													<th width="05%">SL</th>
												</tr>
											</thead>
											<tbody>
											<?php
											$sql = "SELECT eid, CONCAT(lname,', ',fname), TIME_TO_SEC(time_in), TIME_TO_SEC(time_out)
													FROM employees
													WHERE eid = '$eid'
													ORDER BY lname, fname";
											$res = query($sql); 
											while ($row = fetch_array($res)) {
												$tardy_conversion = 0.00; 
												$ut_conversion = 0.00; 
												$ending_vl = 0.00;
												$ending_sl = 0.00;
												
												$hour_tardy = 0;
												$minute_tardy = 0; 
												
												$hour_ut = 0; 
												$minute_ut = 0; 
												
												// get forwarded credits
												$sql = "SELECT vl, sl 
														FROM employees_credits 
														WHERE eid = '$row[0]' AND MONTH(ldate) = '$gmonth' AND YEAR(ldate) = '$gyear'";
												$cres = query($sql); $crow = fetch_array($cres); 
												// get the number of tardiness
												$sql = "SELECT COUNT(aid), SUM(tardiness)
														FROM attendance
														WHERE eid = '$row[0]' AND tardiness > 0
															AND YEAR(adate) = '$gyear' AND MONTH(adate) = '$gmonth'";
												$tardy_res = query($sql); $tardy_row = fetch_array($tardy_res); 
												
												if ($tardy_row[1] > 0) {
													
													$hours = floor($tardy_row[1] / 60);
													$minutes = fmod($tardy_row[1], 60);
													
													if ($hours > 0) {
														// get the conversion for 60 minutes
														$sql = "SELECT conversion FROM leave_conversions WHERE minute = 60";
														$mres = query($sql); $mrow = fetch_array($mres); 
														$hour_tardy = $hours * $mrow[0]; 
													}
													if ($minutes > 0) {
														// get the conversion for 60 minutes
														$sql = "SELECT conversion FROM leave_conversions WHERE minute = '$minutes'";
														$mres = query($sql); $mrow = fetch_array($mres); 
														$minute_tardy = $mrow[0]; 
													}
													
													$tardy_conversion = $hour_tardy + $minute_tardy; 
												}
												
												// get the number of undertime
												$sql = "SELECT COUNT(aid), SUM(undertime)
														FROM attendance
														WHERE eid = '$row[0]' AND undertime > 0 
															AND YEAR(adate) = '$gyear' AND MONTH(adate) = '$gmonth'";
												$ut_res = query($sql); $ut_row = fetch_array($ut_res); 
												
												if ($ut_row[1] > 0) {
													$hours = floor($ut_row[1] / 60);
													$minutes = fmod($ut_row[1], 60);
													
													if ($hours > 0) {
														// get the conversion for 60 minutes
														$sql = "SELECT conversion FROM leave_conversions WHERE minute = 60";
														$mres = query($sql); $mrow = fetch_array($mres); 
														$hour_ut = $hours * $mrow[0]; 
													}
													if ($minutes > 0) {
														// get the conversion for 60 minutes
														$sql = "SELECT conversion FROM leave_conversions WHERE minute = '$minutes'";
														$mres = query($sql); $mrow = fetch_array($mres); 
														$minute_ut = $mrow[0]; 
													}
													
													$ut_conversion = $hour_ut + $minute_ut; 
												}
												
												// get the number of Unauthorized Absent
												$sql = "SELECT COUNT(aid) 
														FROM attendance
														WHERE eid = '$row[0]' AND status = 2 AND YEAR(adate) = '$gyear' AND MONTH(adate) = '$gmonth'";
												$ua_res = query($sql); $ua_row = fetch_array($ua_res); 
												// get the number of VL
												$sql = "SELECT SUM(lnum)
														FROM internals_leave_applications
														WHERE eid = '$row[0]' AND lid = 1 AND stats = 1 
															AND YEAR(fr_date) = '$gyear' AND MONTH(fr_date) = '$gmonth'
															AND YEAR(to_date) = '$gyear' AND MONTH(to_date) = '$gmonth'";
												$vl_res = query($sql); $vl_row = fetch_array($vl_res); 
												// get the number of SL
												$sql = "SELECT SUM(lnum)
														FROM internals_leave_applications
														WHERE eid = '$row[0]' AND lid = 2 AND stats = 1 
															AND YEAR(fr_date) = '$gyear' AND MONTH(fr_date) = '$gmonth'
															AND YEAR(to_date) = '$gyear' AND MONTH(to_date) = '$gmonth'";
												$sl_res = query($sql); $sl_row = fetch_array($sl_res); 
												// get the number of FL
												$sql = "SELECT SUM(lnum)
														FROM internals_leave_applications
														WHERE eid = '$row[0]' AND lid = 3 AND stats = 1 
															AND YEAR(fr_date) = '$gyear' AND MONTH(fr_date) = '$gmonth'
															AND YEAR(to_date) = '$gyear' AND MONTH(to_date) = '$gmonth'";
												$fl_res = query($sql); $fl_row = fetch_array($fl_res); 
												// get the number of SPL
												$sql = "SELECT SUM(lnum)
														FROM internals_leave_applications
														WHERE eid = '$row[0]' AND lid = 4 AND stats = 1 
															AND YEAR(fr_date) = '$gyear' AND MONTH(fr_date) = '$gmonth'
															AND YEAR(to_date) = '$gyear' AND MONTH(to_date) = '$gmonth'";
												$spl_res = query($sql); $spl_row = fetch_array($spl_res); 
												// get the number of CTO
												$sql = "SELECT SUM(lnum)
														FROM internals_leave_applications
														WHERE eid = '$row[0]' AND lid = 5 AND stats = 1 
															AND YEAR(fr_date) = '$gyear' AND MONTH(fr_date) = '$gmonth'
															AND YEAR(to_date) = '$gyear' AND MONTH(to_date) = '$gmonth'";
												$cto_res = query($sql); $cto_row = fetch_array($cto_res); 
												// get the number of UML
												$sql = "SELECT SUM(lnum)
														FROM internals_leave_applications
														WHERE eid = '$row[0]' AND lid = 8 AND stats = 1 
															AND YEAR(fr_date) = '$gyear' AND MONTH(fr_date) = '$gmonth'
															AND YEAR(to_date) = '$gyear' AND MONTH(to_date) = '$gmonth'";
												$uml_res = query($sql); $uml_row = fetch_array($uml_res); 
												// get the number of USPL
												$sql = "SELECT SUM(lnum)
														FROM internals_leave_applications
														WHERE eid = '$row[0]' AND lid = 9 AND stats = 1 
															AND YEAR(fr_date) = '$gyear' AND MONTH(fr_date) = '$gmonth'
															AND YEAR(to_date) = '$gyear' AND MONTH(to_date) = '$gmonth'";
												$uspl_res = query($sql); $uspl_row = fetch_array($uspl_res); 
												
												$ending_vl = $crow[0] + 1.25 - ($tardy_conversion + $ut_conversion + $vl_row[0]);
												$ending_sl = $crow[1] + 1.25 - $sl_row[0]; 
												
												?>
												<tr>
													<td align="left">
														<a href="employee.php?eid=<?php echo $row[0] ?>"><?php echo $row[1] ?></a>
													</td>
													<td align="right"><?php echo $crow[0] ?></td>
													<td align="right"><?php echo $crow[1] ?></td>
													<td align="right"><a target="new" href="credit_details.php?eid=<?php echo $row[0] ?>&type=1&gmonth=<?php echo $gmonth ?>&gyear=<?php echo $gyear ?>"><?php echo number_format($tardy_row[0], 0) ?></a></td>
													<td align="right"><a target="new" href="credit_details.php?eid=<?php echo $row[0] ?>&type=1&gmonth=<?php echo $gmonth ?>&gyear=<?php echo $gyear ?>"><?php echo number_format($tardy_row[1], 0) ?></a></td>
													<td align="right"><?php echo $tardy_conversion ?></td>
													<td align="right"><a target="new" href="credit_details.php?eid=<?php echo $row[0] ?>&type=2&gmonth=<?php echo $gmonth ?>&gyear=<?php echo $gyear ?>"><?php echo number_format($ut_row[0], 0) ?></a></td>
													<td align="right"><a target="new" href="credit_details.php?eid=<?php echo $row[0] ?>&type=2&gmonth=<?php echo $gmonth ?>&gyear=<?php echo $gyear ?>"><?php echo number_format($ut_row[1], 0) ?></a></td>
													<td align="right"><?php echo $ut_conversion ?></td>
													<td align="right"><?php echo number_format($ua_row[0], 0) ?></td>
													<td align="right"><?php echo number_format($vl_row[0], 0) ?></td>
													<td align="right"><?php echo number_format($sl_row[0], 0) ?></td>
													<td align="right"><?php echo number_format($fl_row[0], 0) ?></td>
													<td align="right"><?php echo number_format($spl_row[0], 0) ?></td>
													<td align="right"><?php echo number_format($cto_row[0], 0) ?></td>
													<td align="right"><?php echo number_format($uml_row[0], 0) ?></td>
													<td align="right"><?php echo number_format($uspl_row[0], 0) ?></td>
													<td align="right">1.25</td>
													<td align="right">1.25</td>
													<td align="right"><?php echo number_format($ending_vl, 3) ?></td>
													<td align="right"><?php echo number_format($ending_sl, 3) ?></td>
												</tr>
												<?php
												free_result($cres); 
												free_result($tardy_res); 
												free_result($ut_res); 
												free_result($vl_res); 
												free_result($sl_res); 
												free_result($fl_res); 
												free_result($spl_res); 
												free_result($cto_res); 
												free_result($uml_res); 
												free_result($uspl_res); 
											}
											free_result($res);
											?>
											</tbody>
										</table>
									</div><!-- x_content -->
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
				<!-- Datatables -->
				<script src="vendors/datatables.net/js/jquery.dataTables.min.js"></script>
				<script src="vendors/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
				<script src="vendors/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
				<script src="vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js"></script>
				<script src="vendors/datatables.net-buttons/js/buttons.flash.min.js"></script>
				<script src="vendors/datatables.net-buttons/js/buttons.html5.min.js"></script>
				<script src="vendors/datatables.net-buttons/js/buttons.print.min.js"></script>
				<script src="vendors/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js"></script>
				<script src="vendors/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
				<script src="vendors/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
				<script src="vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js"></script>
				<script src="vendors/datatables.net-scroller/js/dataTables.scroller.min.js"></script>

				<!-- Custom Theme Scripts -->
				<script src="build/js/custom.min.js"></script>

			  </body>
			</html>
			<?php
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

				<title>OTC - View Leave Credits</title>

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
											  <footer class="blockquote-footer"><em>You cannot view leave credits.</em></footer>
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
