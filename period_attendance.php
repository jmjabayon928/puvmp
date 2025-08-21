<?php

include("db.php");
include("sqli.php");
include("html.php");
define('PAGE', 'period_attendance.php'); 

session_start();

if (isset($_SESSION['user_id'])) {
	if (validate_level3(PAGE) == true) {

		// log the activity
		log_user(5, 'attendance', 0);
		
		$tab = $_GET['tab'];

		if (isset ($_GET['gmonth'])) {
			$gmonth = $_GET['gmonth']; 
		} else {
			$sql = "SELECT MONTH(CURDATE())";
			$res = query($sql); 
			$row = fetch_array($res); 
			$gmonth = $row[0]; 
		}
		if (strlen($gmonth) == 1) $gmonth = '0'.$gmonth;
		
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
		
		if (isset ($_GET['did'])) {
			$did = $_GET['did'];
		} else {
			$did = 1;
		}

		if (isset ($_GET['etype'])) {
			$etype = $_GET['etype'];
		} else {
			$etype = 1;
		}
		
		// get the first and last day of the month in integer
		$sql = "SELECT TO_DAYS('".$gyear."-".$gmonth."-01'), TO_DAYS(LAST_DAY('".$gyear."-".$gmonth."-01'))";
		$ires = query($sql); 
		$irow = fetch_array($ires); 
		
		$int_start = $irow[0];
		$int_end = $irow[1];
		
		?>
		<!DOCTYPE html>
		<html lang="en">
		  <head>
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
			<!-- Meta, title, CSS, favicons, etc. -->
			<meta charset="utf-8">
			<meta http-equiv="X-UA-Compatible" content="IE=edge">
			<meta name="viewport" content="width=device-width, initial-scale=1">

			<title>OTC - Employees' Attendance</title>

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
						<h3>
						<?php
						if ($tab == 1) { echo 'Attendance by Employee';
						} elseif ($tab == 2) { echo 'Attendance by Date';
						} elseif ($tab == 3) { echo 'Attendance by Type of Employee';
						}
						?>
						</h3>
					  </div>

					  <div class="title_right">
							<select class="form-control" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
								<?php
								if ($gmonth == 1) {
									?>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=1" Selected>January</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=2">February</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=3">March</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=4">April</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=5">May</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=6">June</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=7">July</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=8">August</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=9">September</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=10">October</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=11">November</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=12">December</option>
									<?php
								} elseif ($gmonth == 2) {
									?>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=1">January</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=2" Selected>February</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=3">March</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=4">April</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=5">May</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=6">June</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=7">July</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=8">August</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=9">September</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=10">October</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=11">November</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=12">December</option>
									<?php
								} elseif ($gmonth == 3) {
									?>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=1">January</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=2">February</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=3" Selected>March</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=4">April</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=5">May</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=6">June</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=7">July</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=8">August</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=9">September</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=10">October</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=11">November</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=12">December</option>
									<?php
								} elseif ($gmonth == 4) {
									?>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=1">January</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=2">February</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=3">March</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=4" Selected>April</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=5">May</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=6">June</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=7">July</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=8">August</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=9">September</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=10">October</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=11">November</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=12">December</option>
									<?php
								} elseif ($gmonth == 5) {
									?>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=1">January</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=2">February</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=3">March</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=4">April</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=5" Selected>May</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=6">June</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=7">July</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=8">August</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=9">September</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=10">October</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=11">November</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=12">December</option>
									<?php
								} elseif ($gmonth == 6) {
									?>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=1">January</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=2">February</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=3">March</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=4">April</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=5">May</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=6" Selected>June</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=7">July</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=8">August</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=9">September</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=10">October</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=11">November</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=12">December</option>
									<?php
								} elseif ($gmonth == 7) {
									?>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=1">January</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=2">February</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=3">March</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=4">April</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=5">May</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=6">June</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=7" Selected>July</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=8">August</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=9">September</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=10">October</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=11">November</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=12">December</option>
									<?php
								} elseif ($gmonth == 8) {
									?>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=1">January</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=2">February</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=3">March</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=4">April</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=5">May</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=6">June</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=7">July</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=8" Selected>August</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=9">September</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=10">October</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=11">November</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=12">December</option>
									<?php
								} elseif ($gmonth == 9) {
									?>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=1">January</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=2">February</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=3">March</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=4">April</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=5">May</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=6">June</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=7">July</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=8">August</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=9" Selected>September</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=10">October</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=11">November</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=12">December</option>
									<?php
								} elseif ($gmonth == 10) {
									?>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=1">January</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=2">February</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=3">March</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=4">April</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=5">May</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=6">June</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=7">July</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=8">August</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=9">September</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=10" Selected>October</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=11">November</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=12">December</option>
									<?php
								} elseif ($gmonth == 11) {
									?>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=1">January</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=2">February</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=3">March</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=4">April</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=5">May</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=6">June</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=7">July</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=8">August</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=9">September</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=10">October</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=11" Selected>November</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=12">December</option>
									<?php
								} elseif ($gmonth == 12) {
									?>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=1">January</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=2">February</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=3">March</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=4">April</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=5">May</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=6">June</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=7">July</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=8">August</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=9">September</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=10">October</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=11">November</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=12" Selected>December</option>
									<?php
								} else {
									?>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=1">January</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=2">February</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=3">March</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=4">April</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=5">May</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=6">June</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=7">July</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=8">August</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=9">September</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=10">October</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=11">November</option>
									<option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $gyear ?>&gmonth=12">December</option>
									<?php
								}
								?>
							</select>&nbsp; 
							<select class="form-control" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
								<?php
								for ($i=$cyear; $i>=2018; $i--) {
									if ($gyear == $i) {
										?><option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $i ?>&gmonth=<?php echo $gmonth ?>" Selected><?PHP echo $i ?></option><?PHP
									} else {
										?><option value="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $etype ?>&gyear=<?php echo $i ?>&gmonth=<?php echo $gmonth ?>"><?PHP echo $i ?></option><?PHP
									}
								}
								?>
							</select>
					  </div>
					</div>

					<div class="clearfix"></div>

					<div class="row">
					
						<?php
						
						if ($tab == 1) {
							
							?>
							<div class="" role="tabpanel" data-example-id="togglable-tabs">
							  <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
								<?php
								$sql = "SELECT did, dcode FROM departments";
								$dres = query($sql); 
								while ($drow = fetch_array($dres)) {
									?><li role="presentation" class="<?php echo $did == $drow[0] ? 'active' : '' ?>"><a href="period_attendance.php?tab=<?php echo $tab ?>&did=<?php echo $drow[0] ?>&gyear=<?php echo $gyear ?>&gmonth=<?php echo $gmonth ?>"><?php echo $drow[1] ?></a></li><?php
								}
								free_result($dres); 
								?>
							  </ul>
							  <div class="tab-content">
									<?php
									$sql = "SELECT eid, CONCAT(lname,', ',fname,', ',mname)
											FROM employees
											WHERE did = '$did' AND active = 1
											ORDER BY lname, fname";
									$eres = query($sql); 
									while ($erow = fetch_array($eres)) {
										?>
										<div class="col-md-12 col-sm-12 col-xs-12">
											<div class="x_panel">
												<div class="x_title">
													<h2><?php echo $erow[1] ?></h2>
													<ul class="nav navbar-right panel_toolbox">
													  <a href="add_attendance.php?eid=<?php echo $erow[0] ?>" class="btn btn-primary btn-md" data-toggle="tooltip" data-placement="top" title="Add New Communication"><i class="fa fa-plus-square"></i> Add Attendance </a>
													  <a target="new" href="tcpdf/examples/pdf_dtr.php?eid=<?php echo $erow[0] ?>&gyear=<?php echo $gyear ?>&gmonth=<?php echo $gmonth ?>" class="btn btn-info btn-md" data-toggle="tooltip" data-placement="top" title="Print DTR"><i class="glyphicon glyphicon-print"></i> Print DTR </a>
													</ul>
													<div class="clearfix"></div>
												</div>
												<div class="x_content">
													<table class="table table-striped table-bordered jambo_table bulk_action">
														<thead>
															<tr class="headings">
																<th rowspan="2" align="center" width="10%"></th>
																<th rowspan="2" align="center" width="08%"></th>
																<th colspan="2" align="center">A.M.</th>
																<th colspan="2" align="center">P.M.</th>
																<th rowspan="2" align="center" width="09%">Tardiness</th>
																<th rowspan="2" align="center" width="09%">Undertime</th>
																<th rowspan="2" align="center" width="09%">Status</th>
																<th rowspan="2" align="center" width="19%">Remarks</th>
															</tr>
															<tr class="headings">
																<th align="center" width="09%">In</th>
																<th align="center" width="09%">Out</th>
																<th align="center" width="09%">In</th>
																<th align="center" width="09%">Out</th>
															</tr>
														</thead>
														<tbody>
															<?php
															for ($x=$int_start; $x<=$int_end; $x++) {
																$sql = "SELECT DATE_FORMAT(FROM_DAYS('$x'), '%b %e, %Y')"; 
																$fres = query($sql); $frow = fetch_array($fres); 
																
																$sql = "SELECT aid, DATE_FORMAT(am_in, '%h:%i %p'), 
																			DATE_FORMAT(am_out, '%h:%i %p'), DATE_FORMAT(pm_in, '%h:%i %p'), 
																			DATE_FORMAT(pm_out, '%h:%i %p'), tardiness, undertime, status, remarks
																		FROM attendance 
																		WHERE TO_DAYS(adate) = '$x' AND eid = '$erow[0]'";
																$ares = query($sql); 
																$anum = num_rows($ares); 
																
																if ($anum > 0) {
																	$arow = fetch_array($ares); 
																	?>
																	<tr>
																		<td align="center">
																			<a href="attendance.php?aid=<?php echo $arow[0] ?>" class="btn btn-info btn-xs"><i class="fa fa-search"></i></a>
																			<a href="e_attendance.php?aid=<?php echo $arow[0] ?>" class="btn btn-warning btn-xs"><i class="fa fa-pencil"></i></a>
																			<a href="d_attendance.php?aid=<?php echo $arow[0] ?>" class="btn btn-danger btn-xs"><i class="fa fa-eraser"></i></a>
																		</td>
																		<td align="center"><?php echo $frow[0] ?></td>
																		<td align="center"><?PHP echo $arow[1] == '12:00 AM' ? '' : $arow[1] ?></td>
																		<td align="center"><?PHP echo $arow[2] == '12:00 AM' ? '' : $arow[2] ?></td>
																		<td align="center"><?PHP echo $arow[3] == '12:00 AM' ? '' : $arow[3] ?></td>
																		<td align="center"><?PHP echo $arow[4] == '12:00 AM' ? '' : $arow[4] ?></td>
																		<td align="center"><?PHP echo $arow[5] ?></td>
																		<td align="center"><?PHP echo $arow[6] ?></td>
																		<td align="center"><?PHP 
																			if ($arow[7] == -1) { echo 'For Checking';
																			} elseif ($arow[7] == 1) { echo 'Present';
																			} elseif ($arow[7] == 2) { echo 'Unauthorized Absent';
																			} elseif ($arow[7] == 3) { echo 'Vacation Leave';
																			} elseif ($arow[7] == 4) { echo 'Sick Leave';
																			} elseif ($arow[7] == 5) { echo 'Forced Leave';
																			} elseif ($arow[7] == 6) { echo 'Special Leave';
																			} elseif ($arow[7] == 7) { echo 'Compensatory Time Off';
																			} elseif ($arow[7] == 8) { echo 'Maternity Leave';
																			} elseif ($arow[7] == 9) { echo 'Paternity Leave';
																			} elseif ($arow[7] == 10) { echo 'UML';
																			} elseif ($arow[7] == 11) { echo 'USPL';
																			} elseif ($arow[7] == 12) { echo 'Travel Order';
																			} elseif ($arow[7] == 13) { echo 'DMS';
																			} elseif ($arow[7] == 14) { echo 'Half-Day';
																			} elseif ($arow[7] == 15) { echo 'Regular Holiday';
																			} elseif ($arow[7] == 16) { echo 'Special Non-Working Holiday';
																			} elseif ($arow[7] == 17) { echo 'Memorandum Order';
																			} elseif ($arow[7] == 18) { echo 'Special Order';
																			} ?></td>
																		<td align="left"><?PHP echo $arow[8] ?></td>
																	</tr>
																	<?php
																} else {
																	// check if there are VLs, SLs, FL, SPL, CTO, UML, USPL
																	?>
																	<tr>
																		<td align="center"></td>
																		<td align="center"><?php echo $frow[0] ?></td>
																		<td align="center"></td>
																		<td align="center"></td>
																		<td align="center"></td>
																		<td align="center"></td>
																		<td align="center"></td>
																		<td align="center"></td>
																		<td align="center"></td>
																		<td align="center"></td>
																	</tr>
																	<?php
																}
																free_result($ares); 
																free_result($fres); 
															}
															?>
														</tbody>
													</table>
												</div>
											</div>
										</div>
										<?php
									}
									free_result($eres); 
									?>
							  </div>
							</div>
							<?php
						} elseif ($tab == 2) {
							
							for ($x=$int_start; $x<=$int_end; $x++) {
								$sql = "SELECT DATE_FORMAT(FROM_DAYS('$x'), '%b %e, %Y - %W')"; 
								$fres = query($sql); $frow = fetch_array($fres); 
								
								?>
								<div class="col-md-12 col-sm-12 col-xs-12">
									<div class="x_panel">
										<div class="x_title">
											<h2><?php echo $frow[0] ?></h2>
											<ul class="nav navbar-right panel_toolbox">
												<li><a href="add_attendance.php"><i class="fa fa-plus"></i></a></li>
												<li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
												<li><a class="close-link"><i class="fa fa-close"></i></a></li>
											</ul>
											<div class="clearfix"></div>
										</div>
										<div class="x_content">
											<table class="table table-striped table-bordered jambo_table bulk_action">
												<thead>
												<tr class="headings">
													<th rowspan="2" align="center" width="10%"></th>
													<th rowspan="2" align="center" width="16%">Employee</th>
													<th colspan="2" align="center">A.M.</th>
													<th colspan="2" align="center">P.M.</th>
													<th rowspan="2" align="center" width="08%">Tardiness</th>
													<th rowspan="2" align="center" width="08%">Undertime</th>
													<th rowspan="2" align="center" width="08%">Status</th>
													<th rowspan="2" align="center" width="18%">Remarks</th>
												</tr>
												<tr class="headings">
													<th align="center" width="08%">In</th>
													<th align="center" width="08%">Out</th>
													<th align="center" width="08%">In</th>
													<th align="center" width="08%">Out</th>
												</tr>
												</thead>
												<tbody>
												<?php
												$sql = "SELECT a.aid, a.eid, CONCAT(e.lname,', ',e.fname), DATE_FORMAT(a.am_in, '%h:%i %p'), 
															DATE_FORMAT(a.am_out, '%h:%i %p'), DATE_FORMAT(a.pm_in, '%h:%i %p'), 
															DATE_FORMAT(a.pm_out, '%h:%i %p'), a.tardiness, a.undertime, a.status, a.remarks
														FROM attendance a INNER JOIN employees e ON a.eid = e.eid
														WHERE TO_DAYS(a.adate) = '$x' 
														ORDER BY e.lname, e.fname";
												$res = query($sql); 
												while ($row = fetch_array($res)) {
													?>
													<tr>
														<td align="center">
															<a href="attendance.php?aid=<?php echo $row[0] ?>" class="btn btn-info btn-xs"><i class="fa fa-search"></i></a>
															<a href="e_attendance.php?aid=<?php echo $row[0] ?>" class="btn btn-warning btn-xs"><i class="fa fa-pencil"></i></a>
															<a href="d_attendance.php?aid=<?php echo $row[0] ?>" class="btn btn-danger btn-xs"><i class="fa fa-eraser"></i></a>
														</td>
														<td align="left"><?PHP echo $row[2] ?></td>
														<td align="center"><?PHP echo $row[3] == '12:00 AM' ? '' : $row[3] ?></td>
														<td align="center"><?PHP echo $row[4] == '12:00 AM' ? '' : $row[4] ?></td>
														<td align="center"><?PHP echo $row[5] == '12:00 AM' ? '' : $row[5] ?></td>
														<td align="center"><?PHP echo $row[6] == '12:00 AM' ? '' : $row[6] ?></td>
														<td align="center"><?PHP echo $row[7] ?></td>
														<td align="center"><?PHP echo $row[8] ?></td>
														<td align="center"><?PHP 
															if ($row[9] == -1) { echo 'For Checking';
															} elseif ($row[9] == 1) { echo 'Present';
															} elseif ($row[9] == 2) { echo 'Unauthorized Absent';
															} elseif ($row[9] == 3) { echo 'Vacation Leave';
															} elseif ($row[9] == 4) { echo 'Sick Leave';
															} elseif ($row[9] == 5) { echo 'Forced Leave';
															} elseif ($row[9] == 6) { echo 'Special Leave';
															} elseif ($row[9] == 7) { echo 'CTO';
															} elseif ($row[9] == 8) { echo 'Maternity Leave';
															} elseif ($row[9] == 9) { echo 'Paternity Leave';
															} elseif ($row[9] == 10) { echo 'UML';
															} elseif ($row[9] == 11) { echo 'USPL';
															} elseif ($row[9] == 12) { echo 'Travel Order';
															} elseif ($row[9] == 13) { echo 'DMS';
															} elseif ($row[9] == 14) { echo 'Half-Day';
															} elseif ($row[9] == 15) { echo 'Regular Holiday';
															} elseif ($row[9] == 16) { echo 'Special Non-Working Holiday';
															} elseif ($row[9] == 17) { echo 'Memorandum Order';
															} ?></td>
														<td align="left"><?PHP echo $row[10] ?></td>
													</tr>
													<?php
												}
												free_result($res); 
												?>
												</tbody>
											</table>
										</div>
									</div>
								</div>
								<?php
								free_result($fres); 
							}
							
						} elseif ($tab == 3) {
							?>
							<div class="" role="tabpanel" data-example-id="togglable-tabs">
							  <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
								<?php
								for ($i=1; $i<5; $i++) {
									if ($i == 1) { $header = 'Appointees';
									} elseif ($i == 2) { $header = 'Permanent';
									} elseif ($i == 3) { $header = 'Job Orders';
									} elseif ($i == 4) { $header = 'Contract of Service';
									}
									?><li role="presentation" class="<?php echo $i == $etype ? 'active' : '' ?>"><a href="period_attendance.php?tab=<?php echo $tab ?>&etype=<?php echo $i ?>&gyear=<?php echo $gyear ?>&gmonth=<?php echo $gmonth ?>"><?php echo $header ?></a></li><?php
								}
								?>
							  </ul>
							  <div class="tab-content">
									<?php
									$sql = "SELECT eid, CONCAT(lname,', ',fname,', ',mname)
											FROM employees
											WHERE etype = '$etype' AND active = 1
											ORDER BY lname, fname";
									$eres = query($sql); 
									while ($erow = fetch_array($eres)) {
										?>
										<div class="col-md-12 col-sm-12 col-xs-12">
											<div class="x_panel">
												<div class="x_title">
													<h2><?php echo $erow[1] ?></h2>
													<ul class="nav navbar-right panel_toolbox">
													  <a href="add_attendance.php?eid=<?php echo $erow[0] ?>" class="btn btn-primary btn-md" data-toggle="tooltip" data-placement="top" title="Add New Communication"><i class="fa fa-plus-square"></i> Add Attendance </a>
													  <?php
													  if ($etype < 3) {
														  // get the salary period id 
														  $sql = "SELECT sid
																  FROM salary_periods 
																  WHERE gid = 1 AND MONTH(sp_start) = '$gmonth' AND YEAR(sp_start) = '$gyear'";
														  $sres = query($sql); 
														  $srow = fetch_array($sres); 
														  ?>
														  <a target="new" href="tcpdf/examples/pdf_dtr.php?sid=<?php echo $srow[0] ?>&etype=<?php echo $etype ?>" class="btn btn-info btn-md" data-toggle="tooltip" data-placement="top" title="Print DTR"><i class="glyphicon glyphicon-print"></i> Print DTR </a>
														  <?php
													  } elseif ($etype > 2) {
														  // get the salary period id 
														  $sql = "SELECT sid, DATE_FORMAT(sp_start, '%b %e'), DATE_FORMAT(sp_end, '%e')
																  FROM salary_periods 
																  WHERE gid = 2 AND MONTH(sp_start) = '$gmonth' AND YEAR(sp_start) = '$gyear'";
														  $sres = query($sql); 
														  while ($srow = fetch_array($sres)) {
															  ?>
															  <a target="new" href="tcpdf/examples/pdf_dtr.php?sid=<?php echo $srow[0] ?>&etype=<?php echo $etype ?>" class="btn btn-info btn-md" data-toggle="tooltip" data-placement="top" title="Print DTR"><i class="glyphicon glyphicon-print"></i> <?php echo $srow[1].'-'.$srow[2] ?> </a>
															  <?php
														  }
													  }
													  ?>
													</ul>
													<div class="clearfix"></div>
												</div>
												<div class="x_content">
													<table class="table table-striped table-bordered jambo_table bulk_action">
														<thead>
															<tr class="headings">
																<th rowspan="2" align="center" width="10%"></th>
																<th rowspan="2" align="center" width="08%"></th>
																<th colspan="2" align="center">A.M.</th>
																<th colspan="2" align="center">P.M.</th>
																<th rowspan="2" align="center" width="09%">Tardiness</th>
																<th rowspan="2" align="center" width="09%">Undertime</th>
																<th rowspan="2" align="center" width="09%">Status</th>
																<th rowspan="2" align="center" width="19%">Remarks</th>
															</tr>
															<tr class="headings">
																<th align="center" width="09%">In</th>
																<th align="center" width="09%">Out</th>
																<th align="center" width="09%">In</th>
																<th align="center" width="09%">Out</th>
															</tr>
														</thead>
														<tbody>
															<?php
															for ($x=$int_start; $x<=$int_end; $x++) {
																$sql = "SELECT DATE_FORMAT(FROM_DAYS('$x'), '%b %e, %Y')"; 
																$fres = query($sql); $frow = fetch_array($fres); 
																
																$sql = "SELECT aid, DATE_FORMAT(am_in, '%h:%i %p'), 
																			DATE_FORMAT(am_out, '%h:%i %p'), DATE_FORMAT(pm_in, '%h:%i %p'), 
																			DATE_FORMAT(pm_out, '%h:%i %p'), tardiness, undertime, status, remarks
																		FROM attendance 
																		WHERE TO_DAYS(adate) = '$x' AND eid = '$erow[0]'";
																$ares = query($sql); 
																$anum = num_rows($ares); 
																
																if ($anum > 0) {
																	$arow = fetch_array($ares); 
																	?>
																	<tr>
																		<td align="center">
																			<a href="attendance.php?aid=<?php echo $arow[0] ?>" class="btn btn-info btn-xs"><i class="fa fa-search"></i></a>
																			<a href="e_attendance.php?aid=<?php echo $arow[0] ?>" class="btn btn-warning btn-xs"><i class="fa fa-pencil"></i></a>
																			<a href="d_attendance.php?aid=<?php echo $arow[0] ?>" class="btn btn-danger btn-xs"><i class="fa fa-eraser"></i></a>
																		</td>
																		<td align="center"><?php echo $frow[0] ?></td>
																		<td align="center"><?PHP echo $arow[1] == '12:00 AM' ? '' : $arow[1] ?></td>
																		<td align="center"><?PHP echo $arow[2] == '12:00 AM' ? '' : $arow[2] ?></td>
																		<td align="center"><?PHP echo $arow[3] == '12:00 AM' ? '' : $arow[3] ?></td>
																		<td align="center"><?PHP echo $arow[4] == '12:00 AM' ? '' : $arow[4] ?></td>
																		<td align="center"><?PHP echo $arow[5] ?></td>
																		<td align="center"><?PHP echo $arow[6] ?></td>
																		<td align="center"><?PHP 
																			if ($arow[7] == -1) { echo 'For Checking';
																			} elseif ($arow[7] == 1) { echo 'Present';
																			} elseif ($arow[7] == 2) { echo 'Unauthorized Absent';
																			} elseif ($arow[7] == 3) { echo 'Vacation Leave';
																			} elseif ($arow[7] == 4) { echo 'Sick Leave';
																			} elseif ($arow[7] == 5) { echo 'Forced Leave';
																			} elseif ($arow[7] == 6) { echo 'Special Leave';
																			} elseif ($arow[7] == 7) { echo 'Compensatory Time Off';
																			} elseif ($arow[7] == 8) { echo 'Maternity Leave';
																			} elseif ($arow[7] == 9) { echo 'Paternity Leave';
																			} elseif ($arow[7] == 10) { echo 'UML';
																			} elseif ($arow[7] == 11) { echo 'USPL';
																			} elseif ($arow[7] == 12) { echo 'Travel Order';
																			} elseif ($arow[7] == 13) { echo 'DMS';
																			} elseif ($arow[7] == 14) { echo 'Half-Day';
																			} elseif ($arow[7] == 15) { echo 'Regular Holiday';
																			} elseif ($arow[7] == 16) { echo 'Special Non-Working Holiday';
																			} elseif ($arow[7] == 17) { echo 'Memorandum Order';
																			} ?></td>
																		<td align="left"><?PHP echo $arow[8] ?></td>
																	</tr>
																	<?php
																} else {
																	// check if there are VLs, SLs, FL, SPL, CTO, UML, USPL
																	?>
																	<tr>
																		<td align="center"></td>
																		<td align="center"><?php echo $frow[0] ?></td>
																		<td align="center"></td>
																		<td align="center"></td>
																		<td align="center"></td>
																		<td align="center"></td>
																		<td align="center"></td>
																		<td align="center"></td>
																		<td align="center"></td>
																		<td align="center"></td>
																	</tr>
																	<?php
																}
																free_result($ares); 
																free_result($fres); 
															}
															?>
														</tbody>
													</table>
												</div>
											</div>
										</div>
										<?php
									}
									free_result($eres); 
									?>
							  </div>
							</div>
							<?php
						}
					
						?>

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
			<script src="vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js"></script>
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
		
		if ($eid != 0) {
			// log the activity
			log_user(5, 'attendance', 0);
			
			if (isset ($_GET['gmonth'])) {
				$gmonth = $_GET['gmonth']; 
			} else {
				$sql = "SELECT MONTH(CURDATE())";
				$res = query($sql); 
				$row = fetch_array($res); 
				$gmonth = $row[0]; 
			}
			if (strlen ($gmonth == 1)) $gmonth = '0'.$gmonth;
			
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
			
			// get the first and last day of the month in integer
			$sql = "SELECT TO_DAYS('".$gyear."-".$gmonth."-01'), TO_DAYS(LAST_DAY('".$gyear."-".$gmonth."-01'))";
			$ires = query($sql); 
			$irow = fetch_array($ires); 
			
			$int_start = $irow[0];
			$int_end = $irow[1];
			
			$sql = "SELECT CONCAT(lname,', ', fname) FROM employees WHERE eid = '$eid'";
			$eres = query($sql); $erow = fetch_array($eres); $ename = $erow[0]; 
			
			?>
			<!DOCTYPE html>
			<html lang="en">
			  <head>
				<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
				<!-- Meta, title, CSS, favicons, etc. -->
				<meta charset="utf-8">
				<meta http-equiv="X-UA-Compatible" content="IE=edge">
				<meta name="viewport" content="width=device-width, initial-scale=1">

				<title>OTC - Employee's Own Attendance</title>

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
							<h3><?php echo $ename ?>'s Attendance</h3>
						  </div>

						  <div class="title_right">
							  <div class="title_right">
									<select class="form-control" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
										<?php
										if ($gmonth == 1) {
											?>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=1" Selected>January</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=2">February</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=3">March</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=4">April</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=5">May</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=6">June</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=7">July</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=8">August</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=9">September</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=10">October</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=11">November</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=12">December</option>
											<?php
										} elseif ($gmonth == 2) {
											?>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=1">January</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=2" Selected>February</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=3">March</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=4">April</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=5">May</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=6">June</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=7">July</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=8">August</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=9">September</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=10">October</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=11">November</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=12">December</option>
											<?php
										} elseif ($gmonth == 3) {
											?>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=1">January</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=2">February</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=3" Selected>March</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=4">April</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=5">May</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=6">June</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=7">July</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=8">August</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=9">September</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=10">October</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=11">November</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=12">December</option>
											<?php
										} elseif ($gmonth == 4) {
											?>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=1">January</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=2">February</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=3">March</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=4" Selected>April</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=5">May</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=6">June</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=7">July</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=8">August</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=9">September</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=10">October</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=11">November</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=12">December</option>
											<?php
										} elseif ($gmonth == 5) {
											?>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=1">January</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=2">February</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=3">March</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=4">April</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=5" Selected>May</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=6">June</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=7">July</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=8">August</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=9">September</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=10">October</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=11">November</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=12">December</option>
											<?php
										} elseif ($gmonth == 6) {
											?>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=1">January</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=2">February</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=3">March</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=4">April</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=5">May</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=6" Selected>June</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=7">July</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=8">August</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=9">September</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=10">October</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=11">November</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=12">December</option>
											<?php
										} elseif ($gmonth == 7) {
											?>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=1">January</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=2">February</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=3">March</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=4">April</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=5">May</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=6">June</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=7" Selected>July</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=8">August</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=9">September</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=10">October</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=11">November</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=12">December</option>
											<?php
										} elseif ($gmonth == 8) {
											?>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=1">January</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=2">February</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=3">March</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=4">April</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=5">May</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=6">June</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=7">July</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=8" Selected>August</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=9">September</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=10">October</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=11">November</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=12">December</option>
											<?php
										} elseif ($gmonth == 9) {
											?>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=1">January</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=2">February</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=3">March</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=4">April</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=5">May</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=6">June</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=7">July</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=8">August</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=9" Selected>September</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=10">October</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=11">November</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=12">December</option>
											<?php
										} elseif ($gmonth == 10) {
											?>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=1">January</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=2">February</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=3">March</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=4">April</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=5">May</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=6">June</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=7">July</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=8">August</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=9">September</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=10" Selected>October</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=11">November</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=12">December</option>
											<?php
										} elseif ($gmonth == 11) {
											?>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=1">January</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=2">February</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=3">March</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=4">April</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=5">May</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=6">June</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=7">July</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=8">August</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=9">September</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=10">October</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=11" Selected>November</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=12">December</option>
											<?php
										} elseif ($gmonth == 12) {
											?>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=1">January</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=2">February</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=3">March</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=4">April</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=5">May</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=6">June</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=7">July</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=8">August</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=9">September</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=10">October</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=11">November</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=12" Selected>December</option>
											<?php
										} else {
											?>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=1">January</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=2">February</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=3">March</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=4">April</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=5">May</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=6">June</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=7">July</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=8">August</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=9">September</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=10">October</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=11">November</option>
											<option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=12">December</option>
											<?php
										}
										?>
									</select>&nbsp; 
									<select class="form-control" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
										<?php
										for ($i=$cyear; $i>=2018; $i--) {
											if ($gyear == $i) {
												?><option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=<?php echo $gmonth ?>" Selected><?PHP echo $i ?></option><?PHP
											} else {
												?><option value="period_attendance.php?gyear=<?php echo $gyear ?>&gmonth=<?php echo $gmonth ?>"><?PHP echo $i ?></option><?PHP
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
									<div class="x_title">
										<h2><?php echo $ename ?></h2>
										<div class="clearfix"></div>
									</div>
									<div class="x_content">
										<table class="table table-striped table-bordered jambo_table bulk_action">
											<thead>
												<tr class="headings">
													<th rowspan="2" align="center" width="10%"></th>
													<th rowspan="2" align="center" width="08%"></th>
													<th colspan="2" align="center">A.M.</th>
													<th colspan="2" align="center">P.M.</th>
													<th rowspan="2" align="center" width="09%">Tardiness</th>
													<th rowspan="2" align="center" width="09%">Undertime</th>
													<th rowspan="2" align="center" width="09%">Status</th>
													<th rowspan="2" align="center" width="19%">Remarks</th>
												</tr>
												<tr class="headings">
													<th align="center" width="09%">In</th>
													<th align="center" width="09%">Out</th>
													<th align="center" width="09%">In</th>
													<th align="center" width="09%">Out</th>
												</tr>
											</thead>
											<tbody>
												<?php
												for ($x=$int_start; $x<=$int_end; $x++) {
													$sql = "SELECT DATE_FORMAT(FROM_DAYS('$x'), '%b %e, %Y')"; 
													$fres = query($sql); $frow = fetch_array($fres); 
													
													$sql = "SELECT aid, DATE_FORMAT(am_in, '%h:%i %p'), 
																DATE_FORMAT(am_out, '%h:%i %p'), DATE_FORMAT(pm_in, '%h:%i %p'), 
																DATE_FORMAT(pm_out, '%h:%i %p'), tardiness, undertime, status, remarks
															FROM attendance 
															WHERE TO_DAYS(adate) = '$x' AND eid = '$eid'";
													$ares = query($sql); 
													$anum = num_rows($ares); 
													
													if ($anum > 0) {
														$arow = fetch_array($ares); 
														?>
														<tr>
															<td align="center">
																<a href="attendance.php?aid=<?php echo $arow[0] ?>" class="btn btn-info btn-xs"><i class="fa fa-search"></i></a>
																<a href="e_attendance.php?aid=<?php echo $arow[0] ?>" class="btn btn-warning btn-xs"><i class="fa fa-pencil"></i></a>
																<a href="d_attendance.php?aid=<?php echo $arow[0] ?>" class="btn btn-danger btn-xs"><i class="fa fa-eraser"></i></a>
															</td>
															<td align="center"><?php echo $frow[0] ?></td>
															<td align="center"><?PHP echo $arow[1] == '12:00 AM' ? '' : $arow[1] ?></td>
															<td align="center"><?PHP echo $arow[2] == '12:00 AM' ? '' : $arow[2] ?></td>
															<td align="center"><?PHP echo $arow[3] == '12:00 AM' ? '' : $arow[3] ?></td>
															<td align="center"><?PHP echo $arow[4] == '12:00 AM' ? '' : $arow[4] ?></td>
															<td align="center"><?PHP echo $arow[5] ?></td>
															<td align="center"><?PHP echo $arow[6] ?></td>
															<td align="center"><?PHP 
																if ($arow[7] == -1) { echo 'For Checking';
																} elseif ($arow[7] == 1) { echo 'Present';
																} elseif ($arow[7] == 2) { echo 'Unauthorized Absent';
																} elseif ($arow[7] == 3) { echo 'Vacation Leave';
																} elseif ($arow[7] == 4) { echo 'Sick Leave';
																} elseif ($arow[7] == 5) { echo 'Forced Leave';
																} elseif ($arow[7] == 6) { echo 'Special Leave';
																} elseif ($arow[7] == 7) { echo 'Compensatory Time Off';
																} elseif ($arow[7] == 8) { echo 'Maternity Leave';
																} elseif ($arow[7] == 9) { echo 'Paternity Leave';
																} elseif ($arow[7] == 10) { echo 'UML';
																} elseif ($arow[7] == 11) { echo 'USPL';
																} elseif ($arow[7] == 12) { echo 'Travel Order';
																} elseif ($arow[7] == 13) { echo 'DMS';
																} elseif ($arow[7] == 14) { echo 'Half-Day';
																} elseif ($arow[7] == 15) { echo 'Regular Holiday';
																} elseif ($arow[7] == 16) { echo 'Special Non-Working Holiday';
																} ?></td>
															<td align="left"><?PHP echo $arow[8] ?></td>
														</tr>
														<?php
													} else {
														// check if there are VLs, SLs, FL, SPL, CTO, UML, USPL
														?>
														<tr>
															<td align="center"></td>
															<td align="center"><?php echo $frow[0] ?></td>
															<td align="center"></td>
															<td align="center"></td>
															<td align="center"></td>
															<td align="center"></td>
															<td align="center"></td>
															<td align="center"></td>
															<td align="center"></td>
															<td align="center"></td>
														</tr>
														<?php
													}
													free_result($ares); 
													free_result($fres); 
												}
												?>
											</tbody>
										</table>
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
				<!-- Datatables -->
				<script src="vendors/datatables.net/js/jquery.dataTables.min.js"></script>
				<script src="vendors/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
				<script src="vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js"></script>
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
			// log the activity
			log_user(5, 'attendance', 0);
			
			if (!isset ($_GET['spid'])) {
				$sql = "SELECT spid
						FROM salary_periods 
						WHERE TO_DAYS(sp_start) <= TO_DAYS(NOW()) AND TO_DAYS(NOW()) <= TO_DAYS(sp_end)";
				$spres = query($sql); 
				$sprow = fetch_array($spres); 
				$spid = $sprow[0]; 
				free_result($spres); 
			} else {
				$spid = $_GET['spid']; 
			}
			
			
			?>
			<!DOCTYPE html>
			<html lang="en">
			  <head>
				<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
				<!-- Meta, title, CSS, favicons, etc. -->
				<meta charset="utf-8">
				<meta http-equiv="X-UA-Compatible" content="IE=edge">
				<meta name="viewport" content="width=device-width, initial-scale=1">

				<title>OTC - Employee's Own Attendance</title>

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
