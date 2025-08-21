<?php

include("db.php");
include("sqli.php");
include("html.php");
define('PAGE', 'payroll_reports.php'); 

session_start();

if (isset($_SESSION['user_id'])) {
	if (validate_level(PAGE) == true) {

		// log the activity
		log_user(5, 'payroll_reports', 0);
		
		if (isset ($_GET['tab'])) {
			$tab = $_GET['tab'];
		} else {
			$tab = 1;
		}

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
		
		?>
		<!DOCTYPE html>
		<html lang="en">
		  <head>
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
			<!-- Meta, title, CSS, favicons, etc. -->
			<meta charset="utf-8">
			<meta http-equiv="X-UA-Compatible" content="IE=edge">
			<meta name="viewport" content="width=device-width, initial-scale=1">

			<title>OTC - Payroll Reports</title>

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
						if ($tab == 1) { echo 'GSIS Premium & Loans';
						} elseif ($tab == 2) { echo 'GSIS RLIP EE';
						} elseif ($tab == 3) { echo 'GSIS RLIP ER';
						} elseif ($tab == 4) { echo 'GSIS ECP';
						} elseif ($tab == 5) { echo 'PHIC Contributions';
						} elseif ($tab == 6) { echo 'HDMF MSRF';
						} elseif ($tab == 7) { echo 'HDMF HL';
						} elseif ($tab == 8) { echo 'HDMF MPL';
						} elseif ($tab == 9) { echo 'BIR Tax';
						}
						?>
						</h3>
					  </div>

					  <div class="title_right">
							<select class="form-control" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
								<?php
								if ($gmonth == 1) {
									?>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=1" Selected>January</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=2">February</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=3">March</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=4">April</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=5">May</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=6">June</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=7">July</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=8">August</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=9">September</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=10">October</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=11">November</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=12">December</option>
									<?php
								} elseif ($gmonth == 2) {
									?>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=1">January</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=2" Selected>February</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=3">March</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=4">April</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=5">May</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=6">June</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=7">July</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=8">August</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=9">September</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=10">October</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=11">November</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=12">December</option>
									<?php
								} elseif ($gmonth == 3) {
									?>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=1">January</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=2">February</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=3" Selected>March</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=4">April</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=5">May</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=6">June</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=7">July</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=8">August</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=9">September</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=10">October</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=11">November</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=12">December</option>
									<?php
								} elseif ($gmonth == 4) {
									?>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=1">January</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=2">February</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=3">March</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=4" Selected>April</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=5">May</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=6">June</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=7">July</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=8">August</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=9">September</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=10">October</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=11">November</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=12">December</option>
									<?php
								} elseif ($gmonth == 5) {
									?>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=1">January</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=2">February</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=3">March</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=4">April</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=5" Selected>May</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=6">June</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=7">July</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=8">August</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=9">September</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=10">October</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=11">November</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=12">December</option>
									<?php
								} elseif ($gmonth == 6) {
									?>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=1">January</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=2">February</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=3">March</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=4">April</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=5">May</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=6" Selected>June</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=7">July</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=8">August</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=9">September</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=10">October</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=11">November</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=12">December</option>
									<?php
								} elseif ($gmonth == 7) {
									?>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=1">January</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=2">February</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=3">March</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=4">April</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=5">May</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=6">June</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=7" Selected>July</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=8">August</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=9">September</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=10">October</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=11">November</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=12">December</option>
									<?php
								} elseif ($gmonth == 8) {
									?>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=1">January</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=2">February</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=3">March</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=4">April</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=5">May</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=6">June</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=7">July</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=8" Selected>August</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=9">September</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=10">October</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=11">November</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=12">December</option>
									<?php
								} elseif ($gmonth == 9) {
									?>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=1">January</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=2">February</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=3">March</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=4">April</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=5">May</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=6">June</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=7">July</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=8">August</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=9" Selected>September</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=10">October</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=11">November</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=12">December</option>
									<?php
								} elseif ($gmonth == 10) {
									?>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=1">January</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=2">February</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=3">March</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=4">April</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=5">May</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=6">June</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=7">July</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=8">August</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=9">September</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=10" Selected>October</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=11">November</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=12">December</option>
									<?php
								} elseif ($gmonth == 11) {
									?>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=1">January</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=2">February</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=3">March</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=4">April</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=5">May</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=6">June</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=7">July</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=8">August</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=9">September</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=10">October</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=11" Selected>November</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=12">December</option>
									<?php
								} elseif ($gmonth == 12) {
									?>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=1">January</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=2">February</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=3">March</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=4">April</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=5">May</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=6">June</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=7">July</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=8">August</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=9">September</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=10">October</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=11">November</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=12" Selected>December</option>
									<?php
								} else {
									?>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=1">January</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=2">February</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=3">March</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=4">April</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=5">May</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=6">June</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=7">July</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=8">August</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=9">September</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=10">October</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=11">November</option>
									<option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=12">December</option>
									<?php
								}
								?>
							</select>&nbsp; 
							<select class="form-control" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
								<?php
								for ($i=$cyear; $i>=2018; $i--) {
									if ($gyear == $i) {
										?><option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $i ?>&gmonth=<?php echo $gmonth ?>" Selected><?PHP echo $i ?></option><?PHP
									} else {
										?><option value="payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $i ?>&gmonth=<?php echo $gmonth ?>"><?PHP echo $i ?></option><?PHP
									}
								}
								?>
							</select>
					  </div>
					</div>

					<div class="clearfix"></div>

					<div class="row">
						<br />
						<div class="" role="tabpanel" data-example-id="togglable-tabs">
						  <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
							<?php
							for ($i=1; $i<10; $i++) {
								if ($i == 1) { $header = 'GSIS Premium & Loans';
								} elseif ($i == 2) { $header = 'GSIS RLIP EE';
								} elseif ($i == 3) { $header = 'GSIS RLIP ER';
								} elseif ($i == 4) { $header = 'GSIS ECP';
								} elseif ($i == 5) { $header = 'PHIC Contributions';
								} elseif ($i == 6) { $header = 'HDMF MSRF';
								} elseif ($i == 7) { $header = 'HDMF HL';
								} elseif ($i == 8) { $header = 'HDMF MPL';
								} elseif ($i == 9) { $header = 'BIR Tax';
								}
								?><li role="presentation" class="<?php echo $i == $tab ? 'active' : '' ?>"><a href="payroll_reports.php?tab=<?php echo $i ?>&gyear=<?php echo $gyear ?>&gmonth=<?php echo $gmonth ?>"><?php echo $header ?></a></li><?php
							}
							?>
						  </ul>
						  <div class="tab-content">
						  
						  <?php
						  if ($tab == 1) {
						    ?>
							<ul class="nav navbar-right panel_toolbox">
								<a target="new" href="tcpdf/examples/pdf_payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=<?php echo $gmonth ?>" class="btn btn-info btn-md"><i class="fa fa-print"></i> Print </a>
							</ul>
							<table class="table table-striped table-bordered jambo_table">
							  <thead>
								<tr>
								  <th width="6.25%">BPNO</th>
								  <th width="8.25%">Surname</th>
								  <th width="9.25%">First Name</th>
								  <th width="7.25%">Middle Name</th>
								  <th width="5.25%">Birthdate</th>
								  <th width="6.25%">CRN</th>
								  <th width="6.25%">Basic</th>
								  <th width="5.25%">Effectivity</th>
								  <th width="6.25%">PS</th>
								  <th width="6.25%">GS</th>
								  <th width="5.25%">EC</th>
								  <th width="6.25%">ConsoLoan</th>
								  <th width="5.25%">EL</th>
								  <th width="6.25%">Educ Ass</th>
								  <th width="5.25%">Reg PL</th>
								  <th width="5.25%">Cash Adv</th>
								</tr>
							  </thead>
							  <tbody>
							  <?php
							  $basic = 0; $rlip_ee = 0; $rlip_er = 0; $ec = 0; $consoloan = 0; $el = 0; $eal = 0; $pl = 0; $cash_adv = 0; 
							  
							  $sql = "SELECT ep.gsis_id, e.lname, e.fname, e.mname, DATE_FORMAT(ep.bdate, '%m/%d/%y'),
										  ep.crn, pr.basic, pr.gsis_rlip_ee, pr.gsis_rlip_er, pr.gsis_consoloan,
										  pr.gsis_el, pr.gsis_eal, pr.gsis_pl, pr.gsis_cash_adv
									  FROM payrolls_regular pr 
										  INNER JOIN payrolls p ON pr.pid = p.pid
										  INNER JOIN salary_periods sp ON p.sid = sp.sid
										  INNER JOIN employees e ON pr.eid = e.eid 
										  INNER JOIN employees_pds ep ON e.eid = ep.eid 
									  WHERE p.gid = 1 AND MONTH(sp.sp_start) = '$gmonth' AND YEAR(sp.sp_start) = '$gyear'
									  ORDER BY e.lname, e.fname";
							  $res = query($sql); 
							  while ($row = fetch_array($res)) {
								  ?>
								  <tr>
									<td align="center"><?php echo $row[0] ?></td>
									<td align="left"><?php echo $row[1] ?></td>
									<td align="left"><?php echo $row[2] ?></td>
									<td align="left"><?php echo $row[3] ?></td>
									<td align="center"><?php echo $row[4] ?></td>
									<td align="center"><?php echo $row[5] ?></td>
									<td align="right"><?php echo number_format($row[6], 2) ?></td>
									<td align="center">01/01/19</td>
									<td align="right"><?php echo number_format($row[7], 2) ?></td>
									<td align="right"><?php echo number_format($row[8], 2) ?></td>
									<td align="right">100.00</td>
									<td align="right"><?php echo number_format($row[9], 2) ?></td>
									<td align="right"><?php echo number_format($row[10], 2) ?></td>
									<td align="right"><?php echo number_format($row[11], 2) ?></td>
									<td align="right"><?php echo number_format($row[12], 2) ?></td>
									<td align="right"><?php echo number_format($row[13], 2) ?></td>
								  </tr>
								  <?php
								  $basic += $row[6]; 
								  $rlip_ee += $row[7]; 
								  $rlip_er += $row[8]; 
								  $ec += 100; 
								  $consoloan += $row[9]; 
								  $el += $row[10]; 
								  $eal += $row[11]; 
								  $pl += $row[12]; 
								  $cash_adv += $row[13]; 
							  }
							  free_result($res); 
							  ?>
							    <tr>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td align="right"><?php echo number_format($basic, 2) ?></td>
									<td>&nbsp;</td>
									<td align="right"><?php echo number_format($rlip_ee, 2) ?></td>
									<td align="right"><?php echo number_format($rlip_er, 2) ?></td>
									<td align="right"><?php echo number_format($ec, 2) ?></td>
									<td align="right"><?php echo number_format($consoloan, 2) ?></td>
									<td align="right"><?php echo number_format($el, 2) ?></td>
									<td align="right"><?php echo number_format($eal, 2) ?></td>
									<td align="right"><?php echo number_format($pl, 2) ?></td>
									<td align="right"><?php echo number_format($cash_adv, 2) ?></td>
								</tr>
							  </tbody>
							</table>
							<?php
						  } elseif ($tab == 2) {
						    ?>
							<ul class="nav navbar-right panel_toolbox">
								<a target="new" href="tcpdf/examples/pdf_payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=<?php echo $gmonth ?>" class="btn btn-info btn-md"><i class="fa fa-print"></i> Print </a>
							</ul>
							<table class="table table-striped table-bordered jambo_table">
							  <thead>
								<tr>
								  <th width="10%"></th>
								  <th width="30%">Employee</th>
								  <th width="30%">Monthly Basic Pay</th>
								  <th width="30%">Amount</th>
								</tr>
							  </thead>
							  <tbody>
							  <?php
							  $i = 1; $total1 = 0; $total2 = 0; 
							  $sql = "SELECT CONCAT(e.lname,', ',e.fname), d.basic, d.gsis_rlip_ee
									  FROM payrolls_regular d 
									      INNER JOIN employees e ON d.eid = e.eid 
										  INNER JOIN payrolls p ON d.pid = p.pid
										  INNER JOIN salary_periods sp ON p.sid = sp.sid
									  WHERE p.gid = 1 AND MONTH(sp.sp_start) = '$gmonth' AND YEAR(sp.sp_start) = '$gyear'
									  ORDER BY e.lname, e.fname";
							  $res = query($sql); 
							  while ($row = fetch_array($res)) {
							    ?>
								<tr>
									<td align="center"><?php echo $i ?></td>
									<td align="left"><?php echo $row[0] ?></td>
									<td align="right"><?php echo number_format($row[1], 2) ?></td>
									<td align="right"><?php echo number_format($row[2], 2) ?></td>
								</tr>
								<?php
								$i++; $total1 += $row[1]; $total2 += $row[2]; 
							  }
							  free_result($res); 
							  ?>
							    <tr>
									<td align="center">&nbsp;</td>
									<td align="left"><b>Total</b></td>
									<td align="right"><b><?php echo number_format($total1, 2) ?></b></td>
									<td align="right"><b><?php echo number_format($total2, 2) ?></b></td>
								</tr>
							  </tbody>
							</table>
							<?php
						  } elseif ($tab == 3) {
						    ?>
							<ul class="nav navbar-right panel_toolbox">
								<a target="new" href="tcpdf/examples/pdf_payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=<?php echo $gmonth ?>" class="btn btn-info btn-md"><i class="fa fa-print"></i> Print </a>
							</ul>
							<table class="table table-striped table-bordered jambo_table">
							  <thead>
								<tr>
								  <th width="10%"></th>
								  <th width="30%">Employee</th>
								  <th width="30%">Monthly Basic Pay</th>
								  <th width="30%">Amount</th>
								</tr>
							  </thead>
							  <tbody>
							  <?php
							  $i = 1; $total1 = 0; $total2 = 0; 
							  $sql = "SELECT CONCAT(e.lname,', ',e.fname), d.basic, d.gsis_rlip_er
									  FROM payrolls_regular d 
									      INNER JOIN employees e ON d.eid = e.eid 
										  INNER JOIN payrolls p ON d.pid = p.pid
										  INNER JOIN salary_periods sp ON p.sid = sp.sid
									  WHERE p.gid = 1 AND MONTH(sp.sp_start) = '$gmonth' AND YEAR(sp.sp_start) = '$gyear'
									  ORDER BY e.lname, e.fname";
							  $res = query($sql); 
							  while ($row = fetch_array($res)) {
							    ?>
								<tr>
									<td align="center"><?php echo $i ?></td>
									<td align="left"><?php echo $row[0] ?></td>
									<td align="right"><?php echo number_format($row[1], 2) ?></td>
									<td align="right"><?php echo number_format($row[2], 2) ?></td>
								</tr>
								<?php
								$i++; $total1 += $row[1]; $total2 += $row[2]; 
							  }
							  free_result($res); 
							  ?>
							    <tr>
									<td align="center">&nbsp;</td>
									<td align="left"><b>Total</b></td>
									<td align="right"><b><?php echo number_format($total1, 2) ?></b></td>
									<td align="right"><b><?php echo number_format($total2, 2) ?></b></td>
								</tr>
							  </tbody>
							</table>
							<?php
						  } elseif ($tab == 4) {
						    ?>
							<ul class="nav navbar-right panel_toolbox">
								<a target="new" href="tcpdf/examples/pdf_payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=<?php echo $gmonth ?>" class="btn btn-info btn-md"><i class="fa fa-print"></i> Print </a>
							</ul>
							<table class="table table-striped table-bordered jambo_table">
							  <thead>
								<tr>
								  <th width="10%"></th>
								  <th width="30%">Employee</th>
								  <th width="30%">Monthly Basic Pay</th>
								  <th width="30%">Amount</th>
								</tr>
							  </thead>
							  <tbody>
							  <?php
							  $i = 1; $total1 = 0; $total2 = 0; 
							  $sql = "SELECT CONCAT(e.lname,', ',e.fname), d.basic
									  FROM payrolls_regular d 
									      INNER JOIN employees e ON d.eid = e.eid 
										  INNER JOIN payrolls p ON d.pid = p.pid
										  INNER JOIN salary_periods sp ON p.sid = sp.sid
									  WHERE p.gid = 1 AND MONTH(sp.sp_start) = '$gmonth' AND YEAR(sp.sp_start) = '$gyear'
									  ORDER BY e.lname, e.fname";
							  $res = query($sql); 
							  while ($row = fetch_array($res)) {
							    ?>
								<tr>
									<td align="center"><?php echo $i ?></td>
									<td align="left"><?php echo $row[0] ?></td>
									<td align="right"><?php echo number_format($row[1], 2) ?></td>
									<td align="right"><?php echo '100.00' ?></td>
								</tr>
								<?php
								$i++; $total1 += $row[1]; $total2 += 100; 
							  }
							  free_result($res); 
							  ?>
							    <tr>
									<td align="center">&nbsp;</td>
									<td align="left"><b>Total</b></td>
									<td align="right"><b><?php echo number_format($total1, 2) ?></b></td>
									<td align="right"><b><?php echo number_format($total2, 2) ?></b></td>
								</tr>
							  </tbody>
							</table>
							<?php
						  } elseif ($tab == 5) {
							if ($gmonth == 1) { $monthname = 'Jan';
							} elseif ($gmonth == 2) { $monthname = 'Feb';
							} elseif ($gmonth == 3) { $monthname = 'Mar';
							} elseif ($gmonth == 4) { $monthname = 'Apr';
							} elseif ($gmonth == 5) { $monthname = 'May';
							} elseif ($gmonth == 6) { $monthname = 'Jun';
							} elseif ($gmonth == 7) { $monthname = 'Jul';
							} elseif ($gmonth == 8) { $monthname = 'Aug';
							} elseif ($gmonth == 9) { $monthname = 'Sep';
							} elseif ($gmonth == 10) { $monthname = 'Oct';
							} elseif ($gmonth == 11) { $monthname = 'Nov';
							} elseif ($gmonth == 12) { $monthname = 'Dec';
							}
						    ?>
							<ul class="nav navbar-right panel_toolbox">
								<a target="new" href="tcpdf/examples/pdf_payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=<?php echo $gmonth ?>" class="btn btn-info btn-md"><i class="fa fa-print"></i> Print </a>
							</ul>
							<table class="table table-striped table-bordered jambo_table">
							  <thead>
								<tr>
								  <th width="20%">Employee</th>
								  <th width="10%">PHIC ID No.</th>
								  <th width="20%">Position/Title</th>
								  <th width="10%">Monthly Basic</th>
								  <th width="10%">Month Covered</th>
								  <th width="10%">EE Share</th>
								  <th width="10%">ER Share</th>
								  <th width="10%">Total</th>
								</tr>
							  </thead>
							  <tbody>
							  <?php
							  $tmonth = 0; $ee_share = 0; $er_share = 0; $total = 0; 
							  $sql = "SELECT CONCAT(e.lname,', ',e.fname), pds.philhealth, pos.pname, d.basic, d.phic_ee, d.phic_er
									  FROM payrolls_regular d 
									      INNER JOIN employees e ON d.eid = e.eid 
										  INNER JOIN employees_pds pds ON pds.eid = e.eid
										  INNER JOIN positions pos ON e.pid = pos.pid
										  INNER JOIN payrolls p ON d.pid = p.pid
										  INNER JOIN salary_periods sp ON p.sid = sp.sid
									  WHERE p.gid = 1 AND MONTH(sp.sp_start) = '$gmonth' AND YEAR(sp.sp_start) = '$gyear'
									  ORDER BY e.lname, e.fname";
							  $res = query($sql); 
							  while ($row = fetch_array($res)) {
							    ?>
								<tr>
									<td align="left"><?php echo $row[0] ?></td>
									<td align="left"><?php echo $row[1] ?></td>
									<td align="left"><?php echo $row[2] ?></td>
									<td align="right"><?php echo number_format($row[3], 2) ?></td>
									<td align="center"><?php echo $monthname.', '.$gyear ?></td>
									<td align="right"><?php echo number_format($row[4], 2) ?></td>
									<td align="right"><?php echo number_format($row[5], 2) ?></td>
									<td align="right"><?php echo number_format(($row[4] + $row[5]), 2) ?></td>
								</tr>
								<?php
								$tmonth += $row[3]; $ee_share += $row[4]; $er_share += $row[5]; $total += $row[4] + $row[5];
							  }
							  free_result($res); 
							  ?>
							    <tr>
									<td align="left"><b>Total</b></td>
									<td align="center">&nbsp;</td>
									<td align="center">&nbsp;</td>
									<td align="right"><b><?php echo number_format($tmonth, 2) ?></b></td>
									<td align="center">&nbsp;</td>
									<td align="right"><b><?php echo number_format($ee_share, 2) ?></b></td>
									<td align="right"><b><?php echo number_format($er_share, 2) ?></b></td>
									<td align="right"><b><?php echo number_format($total, 2) ?></b></td>
								</tr>
							  </tbody>
							</table>
							<?php
						  } elseif ($tab == 6) {
						    ?>
							<ul class="nav navbar-right panel_toolbox">
								<a target="new" href="tcpdf/examples/pdf_payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=<?php echo $gmonth ?>" class="btn btn-info btn-md"><i class="fa fa-print"></i> Print </a>
							</ul>
							<table class="table table-striped table-bordered jambo_table">
							  <thead>
								<tr>
								  <th width="10%">Pag-IBIG ID</th>
								  <th width="10%">Account No.</th>
								  <th width="10%">Program</th>
								  <th width="20%">Member</th>
								  <th width="10%">Period</th>
								  <th width="10%">Monthly Basic</th>
								  <th width="10%">EE Share</th>
								  <th width="10%">ER Share</th>
								  <th width="10%">Total Share</th>
								</tr>
							  </thead>
							  <tbody>
							  <?php
							  $tbasic = 0; $ee_share = 0; $er_share = 0; $total = 0; 
							  $sql = "SELECT pds.pagibig, CONCAT(e.lname,', ',e.fname,', ',e.mname), d.basic, d.hdmf_ee, d.hdmf_er
									  FROM payrolls_regular d 
									      INNER JOIN employees e ON d.eid = e.eid 
										  INNER JOIN employees_pds pds ON pds.eid = e.eid
										  INNER JOIN positions pos ON e.pid = pos.pid
										  INNER JOIN payrolls p ON d.pid = p.pid
										  INNER JOIN salary_periods sp ON p.sid = sp.sid
									  WHERE p.gid = 1 AND MONTH(sp.sp_start) = '$gmonth' AND YEAR(sp.sp_start) = '$gyear'
									  ORDER BY e.lname, e.fname";
							  $res = query($sql); 
							  while ($row = fetch_array($res)) {
								  if (strlen($gmonth) == 1) $gmonth = '0'.$gmonth; 
								  ?>
								  <tr>
									<td align="center"><?php echo $row[0] ?></td>
									<td align="center"></td>
									<td align="center">Pag-IBIG I</td>
									<td align="left"><?php echo $row[1] ?></td>
									<td align="center"><?php echo $gyear.$gmonth ?></td>
									<td align="right"><?php echo number_format($row[2], 2) ?></td>
									<td align="right"><?php echo number_format($row[3], 2) ?></td>
									<td align="right"><?php echo number_format($row[4], 2) ?></td>
									<td align="right"><?php echo number_format(($row[3] + $row[4]), 2) ?></td>
								  </tr>
								  <?php
								  $tbasic += $row[2]; $ee_share += $row[3]; $er_share += $row[4]; $total += $row[3] + $row[4]; 
							  }
							  free_result($res); 
							  ?>
							  <tr>
								<td align="left"></td>
								<td align="left"></td>
								<td align="left"></td>
								<td align="left"><b>Total</b></td>
								<td align="left"></td>
								<td align="right"><b><?php echo number_format($tbasic, 2) ?></b></td>
								<td align="right"><b><?php echo number_format($ee_share, 2) ?></b></td>
								<td align="right"><b><?php echo number_format($er_share, 2) ?></b></td>
								<td align="right"><b><?php echo number_format($total, 2) ?></b></td>
							  </tr>
							  </tbody>
							</table>
							<?php
						  } elseif ($tab == 7) {
						    ?>
							<ul class="nav navbar-right panel_toolbox">
								<a target="new" href="tcpdf/examples/pdf_payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=<?php echo $gmonth ?>" class="btn btn-info btn-md"><i class="fa fa-print"></i> Print </a>
							</ul>
							<table class="table table-striped table-bordered jambo_table">
							  <thead>
								<tr>
								  <th width="10%"></th>
								  <th width="30%">Employee</th>
								  <th width="30%">Monthly Basic Pay</th>
								  <th width="30%">HDMF HL</th>
								</tr>
							  </thead>
							  <tbody>
							  <?php
							  $i = 1; $total1 = 0; $total2 = 0; 
							  $sql = "SELECT CONCAT(e.lname,', ',e.fname), d.basic, d.hdmf_hl
									  FROM payrolls_regular d 
									      INNER JOIN employees e ON d.eid = e.eid 
										  INNER JOIN payrolls p ON d.pid = p.pid
										  INNER JOIN salary_periods sp ON p.sid = sp.sid
									  WHERE p.gid = 1 AND MONTH(sp.sp_start) = '$gmonth' AND YEAR(sp.sp_start) = '$gyear'
									  ORDER BY e.lname, e.fname";
							  $res = query($sql); 
							  while ($row = fetch_array($res)) {
							    ?>
								<tr>
									<td align="center"><?php echo $i ?></td>
									<td align="left"><?php echo $row[0] ?></td>
									<td align="right"><?php echo number_format($row[1], 2) ?></td>
									<td align="right"><?php echo number_format($row[2], 2) ?></td>
								</tr>
								<?php
								$i++; $total1 += $row[1]; $total2 += $row[2]; 
							  }
							  free_result($res); 
							  ?>
							    <tr>
									<td align="center">&nbsp;</td>
									<td align="left"><b>Total</b></td>
									<td align="right"><b><?php echo number_format($total1, 2) ?></b></td>
									<td align="right"><b><?php echo number_format($total2, 2) ?></b></td>
								</tr>
							  </tbody>
							</table>
							<?php
						  } elseif ($tab == 8) {
						    ?>
							<ul class="nav navbar-right panel_toolbox">
								<a target="new" href="tcpdf/examples/pdf_payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=<?php echo $gmonth ?>" class="btn btn-info btn-md"><i class="fa fa-print"></i> Print </a>
							</ul>
							<table class="table table-striped table-bordered jambo_table">
							  <thead>
								<tr>
								  <th width="10%"></th>
								  <th width="30%">Employee</th>
								  <th width="30%">Monthly Basic Pay</th>
								  <th width="30%">HDMF MPL</th>
								</tr>
							  </thead>
							  <tbody>
							  <?php
							  $i = 1; $total1 = 0; $total2 = 0; 
							  $sql = "SELECT CONCAT(e.lname,', ',e.fname), d.basic, d.hdmf_mpl
									  FROM payrolls_regular d 
									      INNER JOIN employees e ON d.eid = e.eid 
										  INNER JOIN payrolls p ON d.pid = p.pid
										  INNER JOIN salary_periods sp ON p.sid = sp.sid
									  WHERE p.gid = 1 AND MONTH(sp.sp_start) = '$gmonth' AND YEAR(sp.sp_start) = '$gyear'
									  ORDER BY e.lname, e.fname";
							  $res = query($sql); 
							  while ($row = fetch_array($res)) {
							    ?>
								<tr>
									<td align="center"><?php echo $i ?></td>
									<td align="left"><?php echo $row[0] ?></td>
									<td align="right"><?php echo number_format($row[1], 2) ?></td>
									<td align="right"><?php echo number_format($row[2], 2) ?></td>
								</tr>
								<?php
								$i++; $total1 += $row[1]; $total2 += $row[2]; 
							  }
							  free_result($res); 
							  ?>
							    <tr>
									<td align="center">&nbsp;</td>
									<td align="left"><b>Total</b></td>
									<td align="right"><b><?php echo number_format($total1, 2) ?></b></td>
									<td align="right"><b><?php echo number_format($total2, 2) ?></b></td>
								</tr>
							  </tbody>
							</table>
							<?php
						  } elseif ($tab == 9) {
						    ?>
							<ul class="nav navbar-right panel_toolbox">
								<a target="new" href="tcpdf/examples/pdf_payroll_reports.php?tab=<?php echo $tab ?>&gyear=<?php echo $gyear ?>&gmonth=<?php echo $gmonth ?>" class="btn btn-info btn-md"><i class="fa fa-print"></i> Print </a>
							</ul>
							<table class="table table-striped table-bordered jambo_table">
							  <thead>
								<tr>
								  <th width="10%"></th>
								  <th width="30%">Employee</th>
								  <th width="30%">Monthly Basic Pay</th>
								  <th width="30%">Amount</th>
								</tr>
							  </thead>
							  <tbody>
							  <?php
							  $i = 1; $total1 = 0; $total2 = 0; 
							  $sql = "SELECT CONCAT(e.lname,', ',e.fname), d.basic, d.bir_tax
									  FROM payrolls_regular d 
									      INNER JOIN employees e ON d.eid = e.eid 
										  INNER JOIN payrolls p ON d.pid = p.pid
										  INNER JOIN salary_periods sp ON p.sid = sp.sid
									  WHERE p.gid = 1 AND MONTH(sp.sp_start) = '$gmonth' AND YEAR(sp.sp_start) = '$gyear'
									  ORDER BY e.lname, e.fname";
							  $res = query($sql); 
							  while ($row = fetch_array($res)) {
							    ?>
								<tr>
									<td align="center"><?php echo $i ?></td>
									<td align="left"><?php echo $row[0] ?></td>
									<td align="right"><?php echo number_format($row[1], 2) ?></td>
									<td align="right"><?php echo number_format($row[2], 2) ?></td>
								</tr>
								<?php
								$i++; $total1 += $row[1]; $total2 += $row[2]; 
							  }
							  free_result($res); 
							  ?>
							    <tr>
									<td align="center">&nbsp;</td>
									<td align="left"><b>Total</b></td>
									<td align="right"><b><?php echo number_format($total1, 2) ?></b></td>
									<td align="right"><b><?php echo number_format($total2, 2) ?></b></td>
								</tr>
							  </tbody>
							</table>
							<?php
						  }
						  ?>
						  
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
	}
} else {
	header('Location: login.php');	
}
