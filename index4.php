<?php

include("db.php");
include("sqli.php");
include("html.php");

session_start();

if (isset($_SESSION['user_id'])) {
	if (validate_user() == true) {
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

			<title>OTC - Employees Locator</title>

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
						<h3>Employees Locator</h3>
					  </div>

					  <div class="title_right">
						<div class="col-xs-4 form-group pull-right top_search">
							<select onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);" style="font-family:arial;text-shadow: 1px 1px #999;font-size:18px;line-height:1.1em;color:#333;">
								<?php
								if ($gmonth == 1) {
									?>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=1" Selected>January</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=2">February</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=3">March</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=4">April</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=5">May</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=6">June</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=7">July</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=8">August</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=9">September</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=10">October</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=11">November</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=12">December</option>
									<?php
								} elseif ($gmonth == 2) {
									?>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=1">January</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=2" Selected>February</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=3">March</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=4">April</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=5">May</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=6">June</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=7">July</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=8">August</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=9">September</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=10">October</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=11">November</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=12">December</option>
									<?php
								} elseif ($gmonth == 3) {
									?>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=1">January</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=2">February</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=3" Selected>March</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=4">April</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=5">May</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=6">June</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=7">July</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=8">August</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=9">September</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=10">October</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=11">November</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=12">December</option>
									<?php
								} elseif ($gmonth == 4) {
									?>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=1">January</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=2">February</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=3">March</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=4" Selected>April</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=5">May</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=6">June</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=7">July</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=8">August</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=9">September</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=10">October</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=11">November</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=12">December</option>
									<?php
								} elseif ($gmonth == 5) {
									?>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=1">January</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=2">February</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=3">March</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=4">April</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=5" Selected>May</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=6">June</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=7">July</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=8">August</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=9">September</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=10">October</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=11">November</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=12">December</option>
									<?php
								} elseif ($gmonth == 6) {
									?>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=1">January</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=2">February</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=3">March</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=4">April</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=5">May</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=6" Selected>June</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=7">July</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=8">August</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=9">September</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=10">October</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=11">November</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=12">December</option>
									<?php
								} elseif ($gmonth == 7) {
									?>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=1">January</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=2">February</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=3">March</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=4">April</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=5">May</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=6">June</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=7" Selected>July</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=8">August</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=9">September</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=10">October</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=11">November</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=12">December</option>
									<?php
								} elseif ($gmonth == 8) {
									?>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=1">January</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=2">February</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=3">March</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=4">April</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=5">May</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=6">June</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=7">July</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=8" Selected>August</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=9">September</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=10">October</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=11">November</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=12">December</option>
									<?php
								} elseif ($gmonth == 9) {
									?>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=1">January</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=2">February</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=3">March</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=4">April</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=5">May</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=6">June</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=7">July</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=8">August</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=9" Selected>September</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=10">October</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=11">November</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=12">December</option>
									<?php
								} elseif ($gmonth == 10) {
									?>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=1">January</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=2">February</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=3">March</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=4">April</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=5">May</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=6">June</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=7">July</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=8">August</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=9">September</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=10" Selected>October</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=11">November</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=12">December</option>
									<?php
								} elseif ($gmonth == 11) {
									?>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=1">January</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=2">February</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=3">March</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=4">April</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=5">May</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=6">June</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=7">July</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=8">August</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=9">September</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=10">October</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=11" Selected>November</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=12">December</option>
									<?php
								} elseif ($gmonth == 12) {
									?>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=1">January</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=2">February</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=3">March</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=4">April</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=5">May</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=6">June</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=7">July</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=8">August</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=9">September</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=10">October</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=11">November</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=12" Selected>December</option>
									<?php
								} else {
									?>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=1">January</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=2">February</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=3">March</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=4">April</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=5">May</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=6">June</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=7">July</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=8">August</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=9">September</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=10">October</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=11">November</option>
									<option value="index4.php?gyear=<?php echo $gyear ?>&gmonth=12">December</option>
									<?php
								}
								?>
							</select>&nbsp; 
							<select onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);" style="font-family:arial;text-shadow: 1px 1px #999;font-size:18px;line-height:1.1em;color:#333;">
								<?php
								for ($i=$cyear; $i>=2018; $i--) {
									if ($gyear == $i) {
										?><option value="index4.php?gyear=<?php echo $i ?>&gmonth=<?php echo $gmonth ?>" Selected><?PHP echo $i ?></option><?PHP
									} else {
										?><option value="index4.php?gyear=<?php echo $i ?>&gmonth=<?php echo $gmonth ?>"><?PHP echo $i ?></option><?PHP
									}
								}
								?>
							</select>
						</div>
					  </div>
					</div>

					<div class="clearfix"></div>
					
						<div class="row">
						<!--
						DMS, LEAVES, MEMORANDUM ORDERS, TRAVEL ORDERS
						-->
						
							<div class="col-md-12 col-sm-12 col-xs-12">
								<div class="x_panel">
									<div class="x_title"><h2>Memorandum Orders</h2> 
										<div class="clearfix"></div>
										<div class="x_content">

											<table class="table table-striped table-bordered jambo_table">
											  <thead>
												<tr>
												  <th width="08%">Issued</th>
												  <th width="08%">Control #</th>
												  <th width="12%">Travel Date</th>
												  <th width="18%">Employee</th>
												  <th width="30%">Subject</th>
												  <th width="10%">Encoder</th>
												  <th width="10%">Encoded</th>
												</tr>
											  </thead>
											  <tbody>
												<?php
												$sql = "SELECT mo.oid, DATE_FORMAT(mo.odate, '%m/%d/%y'), mo.onum, 
															DATE_FORMAT(mo.fr_date, '%m/%d/%y'), DATE_FORMAT(mo.to_date, '%m/%d/%y'), 
															mo.subject, u.lname,
															DATE_FORMAT(mo.user_dtime, '%m/%d/%y %H:%i')
														FROM internals_memo_orders mo 
															INNER JOIN users u ON mo.user_id = u.user_id
														WHERE MONTH(mo.fr_date) = '$gmonth' AND YEAR(mo.fr_date) = '$gyear'
														ORDER BY mo.fr_date DESC";
												$res = query($sql); 
												while ($row = fetch_array($res)) {
													?>
													<tr>
														<td align="center"><?php echo $row[1] ?></td>
														<td align="center"><?php echo $row[2] ?></td>
														<td align="left"><?php echo $row[3].' - '.$row[4] ?></td>
														<td align="left">
															<table width="100%">
																<?php
																$sql = "SELECT m.me_id, CONCAT(e.lname,', ',e.fname)
																		FROM internals_memo_orders_employees m
																			INNER JOIN employees e ON m.eid = e.eid
																		WHERE m.oid = '$row[0]'";
																$rres = query($sql);
																while ($rrow = fetch_array($rres)) {
																	?>
																	<tr>
																		<td width="100%" align="left"><?php echo $rrow[1] ?></td>
																	</tr>
																	<?php
																}
																free_result($rres); 
																?>
															</table>
														</td>
														<td align="left"><?php echo $row[5] ?></td>
														<td align="center"><?php echo $row[6] ?></td>
														<td align="center"><?php echo $row[7] ?></td>
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
							</div>
						
							<div class="col-md-12 col-sm-12 col-xs-12">
								<div class="x_panel">
									<div class="x_title"><h2>Travel Orders</h2> 
										<div class="clearfix"></div>
										<div class="x_content">

											<table class="table table-striped table-bordered jambo_table">
											  <thead>
												<tr>
												  <th width="10%">Control #</th>
												  <th width="15%">Employee</th>
												  <th width="08%">Start Date</th>
												  <th width="08%">End Date</th>
												  <th width="25%">Destination</th>
												  <th width="34%">Purpose</th>
												</tr>
											  </thead>
											  <tbody>
												<?php
												$sql = "SELECT ito.tid, ito.tnum, CONCAT(e.lname,', ',e.fname), 
															DATE_FORMAT(ito.fr_date, '%m/%d/%y'), 
															DATE_FORMAT(ito.to_date, '%m/%d/%y'), 
															ito.destination, ito.purpose
														FROM internals_travel_orders ito
															INNER JOIN internals_travel_orders_employees itoe ON itoe.tid = ito.tid
															INNER JOIN employees e ON itoe.eid = e.eid
														WHERE MONTH(ito.fr_date) = '$gmonth' AND YEAR(ito.fr_date) = '$gyear'
														ORDER BY ito.fr_date DESC";
												$res = query($sql); 
												while ($row = fetch_array($res)) {
													?>
													<tr>
														<td align="center"><?php echo $row[1] ?></td>
														<td align="left"><?php echo $row[2] ?></td>
														<td align="center"><?php echo $row[3] ?></td>
														<td align="center"><?php echo $row[4] ?></td>
														<td align="left"><?php echo $row[5] ?></td>
														<td align="left"><?php echo $row[6] ?></td>
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
							</div>
						
							<div class="col-md-12 col-sm-12 col-xs-12">
								<div class="x_panel">
									<div class="x_title"><h2>Disposition Mission Slip</h2>  
										<div class="clearfix"></div>
										<div class="x_content">
										
											<table class="table table-striped table-bordered jambo_table">
											  <thead>
												<tr>
												  <th width="10%">Control #</th>
												  <th width="15%">Employee</th>
												  <th width="15%">Date / Time</th>
												  <th width="25%">Destination</th>
												  <th width="35%">Purpose</th>
												</tr>
											  </thead>
											  <tbody>
												<?php
												$sql = "SELECT d.did, d.dnum, CONCAT(e.lname,', ',e.fname),
															DATE_FORMAT(d.ddate, '%m/%d/%y'), 
															DATE_FORMAT(d.fr_time, '%h:%i %p'),
															DATE_FORMAT(d.to_time, '%h:%i %p'),
															d.destination, d.purpose
														FROM internals_dms d 
															INNER JOIN employees e ON d.eid = e.eid
															INNER JOIN users u ON d.user_id = u.user_id
														WHERE MONTH(d.ddate) = '$gmonth' AND YEAR(d.ddate) = '$gyear'
														ORDER BY d.ddate DESC";
												$res = query($sql); 
												while ($row = fetch_array($res)) {
													?>
													<tr>
														<td align="center"><?php echo $row[1] ?></td>
														<td align="left"><?php echo $row[2] ?></td>
														<td align="center"><?php echo $row[3].' '.$row[4].'-'.$row[5] ?></td>
														<td align="left"><?php echo $row[6] ?></td>
														<td align="left"><?php echo $row[7] ?></td>
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
							</div>
							
							<div class="col-md-12 col-sm-12 col-xs-12">
								<div class="x_panel">
									<div class="x_title"><h2>Leaves</h2>  
										<div class="clearfix"></div>
										<div class="x_content">
										
											<table class="table table-striped table-bordered jambo_table">
											  <thead>
												<tr>
												  <th width="12%">Control #</th>
												  <th width="18%">Employee</th>
												  <th width="10%">Leave Type</th>
												  <th width="10%">Start Date</th>
												  <th width="10%">End Date</th>
												  <th width="10%">No. of Days</th>
												  <th width="10%">Status</th>
												  <th width="20%">Remarks</th>
												</tr>
											  </thead>
											  <tbody>
												<?php
												$sql = "SELECT l.aid, l.anum, CONCAT(e.lname,', ',e.fname), 
															lt.lname, DATE_FORMAT(l.fr_date, '%m/%d/%y'), 
															DATE_FORMAT(l.to_date, '%m/%d/%y'), l.lnum,
															l.stats, l.remarks, CONCAT(u.lname,', ',u.fname), 
															DATE_FORMAT(l.user_dtime, '%m/%d/%y %h:%i %p'), 
															CONCAT(u2.lname,', ',u2.fname), 
															DATE_FORMAT(l.approve_dtime, '%m/%d/%y %h:%i %p') 
														FROM internals_leave_applications l
															INNER JOIN employees e ON l.eid = e.eid
															INNER JOIN leave_types lt ON l.lid = lt.lid
															INNER JOIN users u ON l.user_id = u.user_id
															LEFT JOIN users u2 ON l.approve_id = u2.user_id
														WHERE MONTH(l.fr_date) = '$gmonth' AND YEAR(l.fr_date) = '$gyear'
														ORDER BY l.to_date DESC";
												$res = query($sql); 
												while ($row = fetch_array($res)) {
													?>
													<tr>
														<td align="center"><?php echo $row[1] ?></td>
														<td align="left"><?php echo $row[2] ?></td>
														<td align="left"><?php echo $row[3] ?></td>
														<td align="left"><?php echo $row[4] ?></td>
														<td align="left"><?php echo $row[5] ?></td>
														<td align="center"><?php echo $row[6] ?></td>
														<td align="left"><?php 
															if ($row[7] == -1) { echo 'Disapproved';
															} elseif ($row[7] == 0) { echo 'Pending';
															} elseif ($row[7] == 1) { echo 'Approved';
															}
															?></td>
														<td align="left"><?php echo $row[8] ?></td>
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
		<?php
	}
} else {
	header('Location: login.php');	
}
