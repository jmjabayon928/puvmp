<?php

include("db.php");
include("sqli.php");
include("html.php");
define('PAGE', 'tc.php'); 

$cid = $_GET['cid'];

session_start();

if (isset($_SESSION['user_id'])) {
	if (validate_level(PAGE) == true) {
		?>
		<!DOCTYPE html>
		<html lang="en">
		  <head>
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
			<!-- Meta, title, CSS, favicons, etc. -->
			<meta charset="utf-8">
			<meta http-equiv="X-UA-Compatible" content="IE=edge">
			<meta name="viewport" content="width=device-width, initial-scale=1">
			  
			<title>OTC | Transport Cooperative Details</title>

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
						<h3>Transportation Cooperative Details</h3>
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
					<div class="row"> <!-- container of all tables -->
					
					  <!-- TC Basic Info -->
					  <div class="col-md-12 col-sm-12 col-xs-12">
						<div class="x_panel">
						  <div class="x_title">
							<h2>Basic TC Information</h2>
							<ul class="nav navbar-right panel_toolbox">
							  <li><a href="e_tc.php?cid=<?php echo $cid ?>"><i class="fa fa-pencil"></i></a></li>
							  <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
							  <li><a class="close-link"><i class="fa fa-close"></i></a></li>
							</ul>
							<div class="clearfix"></div>
						  </div>
						  <div class="x_content">
							<?php
							$sql = "SELECT c.cname, c.addr, s.sname, c.email, 
										c.chairman, c.chairman_num, c.contact_num, c.route,
										c.last_update_id, DATE_FORMAT(c.last_update, '%m/%d/%y %h:%i %p'),
										c.last_verify_id, DATE_FORMAT(c.last_verify, '%m/%d/%y %h:%i %p')
									FROM cooperatives c INNER JOIN tc_sectors s ON c.sid = s.sid
									WHERE c.cid = '$cid'";
							$res = query($sql); 
							$row = fetch_array($res); 
							mysqli_free_result($res);
							?>
							<table class="table table-striped table-bordered jambo_table bulk_action">
								<thead>
								  <tr class="headings">
									  <th colspan="4">Transportation Cooperative Details</th>
								  </tr>
								</thead>
								<tbody>
									<tr>
										<td width="10%">Name</td>
										<td width="30%"><b><?php echo $row[0] ?></b></td>
										<td width="10%">Address</td>
										<td width="50%"><b><?php echo $row[1] ?></b></td>
									</tr>
									<tr>
										<td>Area</td>
										<td><b><?php echo $row[2] ?></b></td>
										<td>Email</td>
										<td><b><?php echo $row[3] ?></b></td>
									</tr>
									<tr>
										<td>Chairman</td>
										<td><b><?php echo $row[4] ?></b></td>
										<td>Contact #s</td>
										<td><b><?php echo $row[5].', '.$row[6] ?></b></td>
									</tr>
									<tr>
										<td>Route(s)</td>
										<td colspan="3"><b><?php echo $row[7] ?></b></td>
									</tr>
									<tr>
										<td>Last Updated By</td>
										<td><b><?php 
											$sql = "SELECT CONCAT(lname,', ',fname)
													FROM employees 
													WHERE eid = '$row[8]'";
											$eres = query($sql); 
											$erow = fetch_array($eres); 
											echo $erow[0] ?></b></td>
										<td>Last Verified By</td>
										<td><b><?php 
											$sql = "SELECT CONCAT(lname,', ',fname)
													FROM employees 
													WHERE eid = '$row[10]'";
											$vres = query($sql); 
											$vrow = fetch_array($vres); 
											echo $vrow[0] ?></b></td>
									</tr>
									<tr>
										<td>Date Updated</td>
										<td><b><?php echo $row[9] == '00/00/00 12:00 AM' ? '' : $row[9] ?></b></td>
										<td>Date Verified</td>
										<td><b><?php echo $row[11] == '00/00/00 12:00 AM' ? '' : $row[10] ?></b></td>
									</tr>
								</tbody>
							</table>
						  </div>
						</div>
					  </div>
					  <!-- end of TC Basic Info -->
					
					  <!-- Certificate of Good Standing -->
					  <div class="col-md-12 col-sm-12 col-xs-12">
						<div class="x_panel">
						  <div class="x_title">
							<h2>Certificate of Good Standing</h2>
							<ul class="nav navbar-right panel_toolbox">
							  <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
							  <li><a class="close-link"><i class="fa fa-close"></i></a></li>
							</ul>
							<div class="clearfix"></div>
						  </div>
						  <div class="x_content">
							<table class="table table-striped table-bordered jambo_table bulk_action">
								<thead>
								  <tr class="headings">
									  <th rowspan="2"></th>
									  <?php
									  for ($i=2014; $i<2019; $i++) {
										  ?><th colspan="2"><?php echo $i ?></th><?php
									  }
									  ?>
								  </tr>
								  <tr>
									  <?php
									  for ($i=2014; $i<2019; $i++) {
										  ?><th>Issued</th><th>Expiration</th><?php
									  }
									  ?>
								  </tr>
								</thead>
								<tbody>
									<tr>
										<td>OTC</td>
										<?php
										for ($i=2014; $i<2019; $i++) {
										  $sql = "SELECT ccid, DATE_FORMAT(cgs_date, '%m/%d/%y'), DATE_FORMAT(cgs_exp, '%m/%d/%y')
												  FROM cooperatives_cgs
												  WHERE cid = '$cid' AND YEAR(cgs_date) = '$i'";
										  $cres = query($sql); 
										  $cnum = num_rows($cres); 
										  if ($cnum > 0) {
											  while ($crow = fetch_array($cres)) {
												  ?>
												  <td align="center"><a href="cgs.php?ccid=<?php echo $crow[0] ?>"><?php echo $crow[1] ?></a></td>
												  <td align="center"><a href="cgs.php?ccid=<?php echo $crow[0] ?>"><?php echo $crow[2] ?></a></td>
												  <?php
											  }
											  mysqli_free_result($cres);
										  } else {
											  ?>
											  <td align="center"><a href="add_cgs.php?cid=<?php echo $cid ?>&year=<?php echo $i ?>" class="btn btn-primary btn-xs"><i class="fa fa-plus-square"></i> Add </a></td>
											  <td>&nbsp;</td>
											  <?php
										  }
										}
										?>
									</tr>
									<tr>
										<td>CDA</td>
										<?php
										for ($i=2014; $i<2019; $i++) {
										  $sql = "SELECT DATE_FORMAT(cda_date, '%m/%d/%y'), DATE_FORMAT(cda_exp, '%m/%d/%y')
												  FROM cooperatives_cda
												  WHERE cid = '$cid' AND YEAR(cda_date) = '$i'";
										  $cres = query($sql); 
										  $cnum = num_rows($cres); 
										  if ($cnum > 0) {
											  while ($crow = fetch_array($cres)) {
												  ?>
												  <td align="center"><?php echo $crow[0] ?></td>
												  <td align="center"><?php echo $crow[1] ?></td>
												  <?php
											  }
											  mysqli_free_result($cres);
										  } else {
											  ?>
											  <td align="center"><a href="add_cda.php?cid=<?php echo $cid ?>&year=<?php echo $i ?>" class="btn btn-primary btn-xs"><i class="fa fa-plus-square"></i> Add </a></td>
											  <td>&nbsp;</td>
											  <?php
										  }
										}
										?>
									</tr>
								</tbody>
							</table>
						  </div>
						</div>
					  </div>
					  <!-- end of TC Basic Info -->
					
					  <!-- TC Members -->
					  <div class="col-md-12 col-sm-12 col-xs-12">
						<div class="x_panel">
						  <div class="x_title">
							<h2>Cooperative Members</h2>
							<ul class="nav navbar-right panel_toolbox">
							  <li><a href="add_member.php?cid=<?php echo $cid ?>"><i class="fa fa-plus"></i></a></li>
							  <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
							  <li><a class="close-link"><i class="fa fa-close"></i></a></li>
							</ul>
							<div class="clearfix"></div>
						  </div>
						  <div class="x_content">
							<table class="table table-striped table-bordered jambo_table bulk_action">
								<thead>
								  <tr class="headings">
									  <th width="05%"></th>
									  <th width="17%">Name</th>
									  <th width="30%">Address</th>
									  <th width="05%">Sex</th>
									  <th width="07%">B.Date</th>
									  <th width="09%">Type</th>
									  <th width="07%">Active</th>
									  <th width="20%">Remarks</th>
								  </tr>
								</thead>
								<tbody>
									<?php
									$sql = "SELECT cmid, CONCAT(lname,', ',fname,', ',mname), addr, sex, 
												DATE_FORMAT(bdate, '%m/%d/%y'), mtype, active, remarks
											FROM cooperatives_members
											WHERE cid = '$cid'
											ORDER BY lname, fname";
									$res = query($sql); 
									while ($row = fetch_array($res)) {
										?>
										<tr>
											<td align="center"><a href="e_member.php?cmid=<?php echo $row[0] ?>" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i></a></td>
											<td align="left"><a href="member.php?cmid=<?php echo $row[0] ?>"><?php echo $row[1] ?></a></td>
											<td align="left"><?php echo $row[2] ?></td>
											<td align="center"><?php echo $row[3] ?></td>
											<td align="center"><?php echo $row[4] ?></td>
											<td align="center"><?php 
												if ($row[5] == 1) { echo 'Regular'; 
												} elseif ($row[5] == 2) { echo 'Associate';
												} elseif ($row[5] == 0) { echo 'Inactive';
												}
												?></td>
											<td align="center"><?php echo $row[6] == 1 ? 'Yes' : 'No' ?></td>
											<td align="left"><?php echo $row[7] ?></td>
										</tr>
										<?php
									}
									mysqli_free_result($res);
									?>
								</tbody>
							</table>
						  </div>
						</div>
					  </div>
					  <!-- end of TC Members -->
					
					  <!-- Membership Classification -->
					  <div class="col-md-12 col-sm-12 col-xs-12">
						<div class="x_panel">
						  <div class="x_title">
							<h2>Membership Classification</h2>
							<ul class="nav navbar-right panel_toolbox">
							  <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
							  <li><a class="close-link"><i class="fa fa-close"></i></a></li>
							</ul>
							<div class="clearfix"></div>
						  </div>
						  <div class="x_content">
							<table class="table table-striped table-bordered jambo_table bulk_action">
								<thead>
								  <tr class="headings">
									  <th rowspan="2">Classification</th>
									  <?php
									  for ($i=2014; $i<2019; $i++) {
										  ?><th><?php 
										  echo $i;
										  ?><ul class="nav navbar-right"><?php
										  $sql = "SELECT ccid
												  FROM cooperatives_classes 
												  WHERE cid = '$cid' AND YEAR(cyear) = '$i'";
										  $cres = query($sql); 
										  $cnum = num_rows($cres); 
										  if ($cnum > 0) {
											  $crow = fetch_array($cres); 
											  ?><a href="e_classifications.php?ccid=<?php echo $crow[0] ?>" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i></a><?php
										  } else {
											  ?><a href="add_classifications.php?ccid=<?php echo $cid ?>&year=<?php echo $i ?>" class="btn btn-primary btn-xs"><i class="fa fa-plus-square"></i></a><?php
										  }
										  ?></ul></th><?php
										  mysqli_free_result($cres);
									  }
									  ?>
								  </tr>
								</thead>
								<tbody>
									<tr>
										<td>Operators</td>
										<?php
										for ($i=2014; $i<2019; $i++) {
											$sql = "SELECT coperators 
													FROM cooperatives_classes 
													WHERE cid = '$cid' AND YEAR(cyear) = '$i'";
											$res = query($sql); 
											$row = fetch_array($res); 
											?><td align="right"><?php echo $row[0] ?></td><?php
											mysqli_free_result($res);
										}
										?>
									</tr>
									<tr>
										<td>Drivers</td>
										<?php
										for ($i=2014; $i<2019; $i++) {
											$sql = "SELECT cdrivers 
													FROM cooperatives_classes 
													WHERE cid = '$cid' AND YEAR(cyear) = '$i'";
											$res = query($sql); 
											$row = fetch_array($res); 
											?><td align="right"><?php echo $row[0] ?></td><?php
											mysqli_free_result($res);
										}
										?>
									</tr>
									<tr>
										<td>Allied Workers</td>
										<?php
										for ($i=2014; $i<2019; $i++) {
											$sql = "SELECT cworkers 
													FROM cooperatives_classes 
													WHERE cid = '$cid' AND YEAR(cyear) = '$i'";
											$res = query($sql); 
											$row = fetch_array($res); 
											?><td align="right"><?php echo $row[0] ?></td><?php
											mysqli_free_result($res);
										}
										?>
									</tr>
									<tr>
										<td>Commuters</td>
										<?php
										for ($i=2014; $i<2019; $i++) {
											$sql = "SELECT ccommuters 
													FROM cooperatives_classes 
													WHERE cid = '$cid' AND YEAR(cyear) = '$i'";
											$res = query($sql); 
											$row = fetch_array($res); 
											?><td align="right"><?php echo $row[0] ?></td><?php
											mysqli_free_result($res);
										}
										?>
									</tr>
									<tr>
										<td>Others</td>
										<?php
										for ($i=2014; $i<2019; $i++) {
											$sql = "SELECT cothers 
													FROM cooperatives_classes 
													WHERE cid = '$cid' AND YEAR(cyear) = '$i'";
											$res = query($sql); 
											$row = fetch_array($res); 
											?><td align="right"><?php echo $row[0] ?></td><?php
											mysqli_free_result($res);
										}
										?>
									</tr>
								</tbody>
							</table>
						  </div>
						</div>
					  </div>
					  <!-- end of Membership Classification -->
					
					  <!-- Number of Units -->
					  <div class="col-md-12 col-sm-12 col-xs-12">
						<div class="x_panel">
						  <div class="x_title">
							<h2>Number of Units</h2>
							<ul class="nav navbar-right panel_toolbox">
							  <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
							  <li><a class="close-link"><i class="fa fa-close"></i></a></li>
							</ul>
							<div class="clearfix"></div>
						  </div>
						  <div class="x_content">
							<table class="table table-striped table-bordered jambo_table bulk_action">
								<thead>
								  <tr class="headings">
									  <th rowspan="2">Mode</th>
									  <?php
									  for ($i=2014; $i<2019; $i++) {
										  ?><th><?php 
										  echo $i;
										  ?><ul class="nav navbar-right"><?php
										  $sql = "SELECT cuid
												  FROM cooperatives_units
												  WHERE cid = '$cid' AND YEAR(cyear) = '$i'";
										  $cres = query($sql); 
										  $cnum = num_rows($cres); 
										  if ($cnum > 0) {
											  $crow = fetch_array($cres); 
											  ?><a href="e_units.php?cuid=<?php echo $crow[0] ?>" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i></a><?php
										  } else {
											  ?><a href="add_units.php?cid=<?php echo $cid ?>&year=<?php echo $i ?>" class="btn btn-primary btn-xs"><i class="fa fa-plus-square"></i></a><?php
										  }
										  ?></ul></th><?php
										  mysqli_free_result($cres);
									  }
									  ?>
								  </tr>
								</thead>
								<tbody>
									<tr>
										<td>PUJ</td>
										<?php
										for ($i=2014; $i<2019; $i++) {
											$sql = "SELECT puj_num 
													FROM cooperatives_units 
													WHERE cid = '$cid' AND YEAR(cyear) = '$i'";
											$res = query($sql); 
											$row = fetch_array($res); 
											?><td align="right"><?php echo $row[0] ?></td><?php
											mysqli_free_result($res);
										}
										?>
									</tr>
									<tr>
										<td>AUV</td>
										<?php
										for ($i=2014; $i<2019; $i++) {
											$sql = "SELECT auv_num 
													FROM cooperatives_units 
													WHERE cid = '$cid' AND YEAR(cyear) = '$i'";
											$res = query($sql); 
											$row = fetch_array($res); 
											?><td align="right"><?php echo $row[0] ?></td><?php
											mysqli_free_result($res);
										}
										?>
									</tr>
									<tr>
										<td>Taxi</td>
										<?php
										for ($i=2014; $i<2019; $i++) {
											$sql = "SELECT taxi_num 
													FROM cooperatives_units 
													WHERE cid = '$cid' AND YEAR(cyear) = '$i'";
											$res = query($sql); 
											$row = fetch_array($res); 
											?><td align="right"><?php echo $row[0] ?></td><?php
											mysqli_free_result($res);
										}
										?>
									</tr>
									<tr>
										<td>Multi Cab</td>
										<?php
										for ($i=2014; $i<2019; $i++) {
											$sql = "SELECT mcab_num 
													FROM cooperatives_units 
													WHERE cid = '$cid' AND YEAR(cyear) = '$i'";
											$res = query($sql); 
											$row = fetch_array($res); 
											?><td align="right"><?php echo $row[0] ?></td><?php
											mysqli_free_result($res);
										}
										?>
									</tr>
									<tr>
										<td>Mini Bus</td>
										<?php
										for ($i=2014; $i<2019; $i++) {
											$sql = "SELECT mb_num 
													FROM cooperatives_units 
													WHERE cid = '$cid' AND YEAR(cyear) = '$i'";
											$res = query($sql); 
											$row = fetch_array($res); 
											?><td align="right"><?php echo $row[0] ?></td><?php
											mysqli_free_result($res);
										}
										?>
									</tr>
									<tr>
										<td>Bus</td>
										<?php
										for ($i=2014; $i<2019; $i++) {
											$sql = "SELECT bus_num 
													FROM cooperatives_units 
													WHERE cid = '$cid' AND YEAR(cyear) = '$i'";
											$res = query($sql); 
											$row = fetch_array($res); 
											?><td align="right"><?php echo $row[0] ?></td><?php
											mysqli_free_result($res);
										}
										?>
									</tr>
									<tr>
										<td>MCH</td>
										<?php
										for ($i=2014; $i<2019; $i++) {
											$sql = "SELECT mch_num 
													FROM cooperatives_units 
													WHERE cid = '$cid' AND YEAR(cyear) = '$i'";
											$res = query($sql); 
											$row = fetch_array($res); 
											?><td align="right"><?php echo $row[0] ?></td><?php
											mysqli_free_result($res);
										}
										?>
									</tr>
									<tr>
										<td>Truck</td>
										<?php
										for ($i=2014; $i<2019; $i++) {
											$sql = "SELECT truck_num 
													FROM cooperatives_units 
													WHERE cid = '$cid' AND YEAR(cyear) = '$i'";
											$res = query($sql); 
											$row = fetch_array($res); 
											?><td align="right"><?php echo $row[0] ?></td><?php
											mysqli_free_result($res);
										}
										?>
									</tr>
									<tr>
										<td>Motorized Banca</td>
										<?php
										for ($i=2014; $i<2019; $i++) {
											$sql = "SELECT banka_num 
													FROM cooperatives_units 
													WHERE cid = '$cid' AND YEAR(cyear) = '$i'";
											$res = query($sql); 
											$row = fetch_array($res); 
											?><td align="right"><?php echo $row[0] ?></td><?php
											mysqli_free_result($res);
										}
										?>
									</tr>
								</tbody>
							</table>
						  </div>
						</div>
					  </div>
					  <!-- end of Number of Units -->
					
					  <!-- Cooperative-Owned Units -->
					  <div class="col-md-12 col-sm-12 col-xs-12">
						<div class="x_panel">
						  <div class="x_title">
							<h2>Cooperative-Owned Units</h2>
							<ul class="nav navbar-right panel_toolbox">
							  <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
							  <li><a class="close-link"><i class="fa fa-close"></i></a></li>
							</ul>
							<div class="clearfix"></div>
						  </div>
						  <div class="x_content">
							<table class="table table-striped table-bordered jambo_table bulk_action">
								<thead>
								  <tr class="headings">
									  <th rowspan="2"></th>
									  <?php
									  for ($i=2014; $i<2019; $i++) {
										  ?><th><?php 
										  echo $i;
										  ?><ul class="nav navbar-right"><?php
										  $sql = "SELECT cuid
												  FROM cooperatives_units 
												  WHERE cid = '$cid' AND YEAR(cyear) = '$i'";
										  $cres = query($sql); 
										  $cnum = num_rows($cres); 
										  if ($cnum > 0) {
											  ?><a href="e_units.php?cid=<?php echo $cid ?>&year=<?php echo $i ?>" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i></a><?php
										  } else {
											  ?><a href="add_units.php?cid=<?php echo $cid ?>&year=<?php echo $i ?>" class="btn btn-primary btn-xs"><i class="fa fa-plus-square"></i></a><?php
										  }
										  ?></ul></th><?php
										  mysqli_free_result($cres);
									  }
									  ?>
								  </tr>
								</thead>
								<tbody>
									<tr>
										<td>Cooperative-Owned</td>
										<?php
										for ($i=2014; $i<2019; $i++) {
											$sql = "SELECT coop_owned 
													FROM cooperatives_units 
													WHERE cid = '$cid' AND YEAR(cyear) = '$i'";
											$res = query($sql); 
											$row = fetch_array($res); 
											?><td align="right"><?php echo $row[0] ?></td><?php
											mysqli_free_result($res);
										}
										?>
									</tr>
									<tr>
										<td>Individually-Owned</td>
										<?php
										for ($i=2014; $i<2019; $i++) {
											$sql = "SELECT individual_owned 
													FROM cooperatives_units 
													WHERE cid = '$cid' AND YEAR(cyear) = '$i'";
											$res = query($sql); 
											$row = fetch_array($res); 
											?><td align="right"><?php echo $row[0] ?></td><?php
											mysqli_free_result($res);
										}
										?>
									</tr>
								</tbody>
							</table>
						  </div>
						</div>
					  </div>
					  <!-- end of Cooperative-Owned Units -->
					
					  <!-- Franchise Information -->
					  <div class="col-md-12 col-sm-12 col-xs-12">
						<div class="x_panel">
						  <div class="x_title">
							<h2>Franchise Information</h2>
							<ul class="nav navbar-right panel_toolbox">
							  <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
							  <li><a class="close-link"><i class="fa fa-close"></i></a></li>
							</ul>
							<div class="clearfix"></div>
						  </div>
						  <div class="x_content">
							<table class="table table-striped table-bordered jambo_table bulk_action">
								<thead>
								  <tr class="headings">
									  <th rowspan="2"></th>
									  <?php
									  for ($i=2014; $i<2019; $i++) {
										  ?><th><?php 
										  echo $i;
										  ?><ul class="nav navbar-right"><?php
										  $sql = "SELECT cuid
												  FROM cooperatives_units 
												  WHERE cid = '$cid' AND YEAR(cyear) = '$i'";
										  $cres = query($sql); 
										  $cnum = num_rows($cres); 
										  if ($cnum > 0) {
											  ?><a href="e_units.php?cid=<?php echo $cid ?>&year=<?php echo $i ?>" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i></a><?php
										  } else {
											  ?><a href="add_units.php?cid=<?php echo $cid ?>&year=<?php echo $i ?>" class="btn btn-primary btn-xs"><i class="fa fa-plus-square"></i></a><?php
										  }
										  ?></ul></th><?php
										  mysqli_free_result($cres);
									  }
									  ?>
								  </tr>
								</thead>
								<tbody>
									<tr>
										<td>Cooperative Franchise</td>
										<?php
										for ($i=2014; $i<2019; $i++) {
											$sql = "SELECT coop_franchised
													FROM cooperatives_units 
													WHERE cid = '$cid' AND YEAR(cyear) = '$i'";
											$res = query($sql); 
											$row = fetch_array($res); 
											?><td align="right"><?php echo $row[0] ?></td><?php
											mysqli_free_result($res);
										}
										?>
									</tr>
									<tr>
										<td>Individually Franchise</td>
										<?php
										for ($i=2014; $i<2019; $i++) {
											$sql = "SELECT individual_franchised 
													FROM cooperatives_units 
													WHERE cid = '$cid' AND YEAR(cyear) = '$i'";
											$res = query($sql); 
											$row = fetch_array($res); 
											?><td align="right"><?php echo $row[0] ?></td><?php
											mysqli_free_result($res);
										}
										?>
									</tr>
									<tr>
										<td>Without Franchise</td>
										<?php
										for ($i=2014; $i<2019; $i++) {
											$sql = "SELECT wout_franchise 
													FROM cooperatives_units 
													WHERE cid = '$cid' AND YEAR(cyear) = '$i'";
											$res = query($sql); 
											$row = fetch_array($res); 
											?><td align="right"><?php echo $row[0] ?></td><?php
											mysqli_free_result($res);
										}
										?>
									</tr>
								</tbody>
							</table>
						  </div>
						</div>
					  </div>
					  <!-- end of Franchise Information -->
					
					  <!-- Financial Statements -->
					  <div class="col-md-12 col-sm-12 col-xs-12">
						<div class="x_panel">
						  <div class="x_title">
							<h2>Financial Statement</h2>
							<ul class="nav navbar-right panel_toolbox">
							  <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
							  <li><a class="close-link"><i class="fa fa-close"></i></a></li>
							</ul>
							<div class="clearfix"></div>
						  </div>
						  <div class="x_content">
							<table class="table table-striped table-bordered jambo_table bulk_action">
								<thead>
								  <tr class="headings">
									  <th rowspan="2"></th>
									  <?php
									  for ($i=2014; $i<2019; $i++) {
										  ?><th><?php 
										  echo $i;
										  ?><ul class="nav navbar-right"><?php
										  $sql = "SELECT cfid
												  FROM cooperatives_financials
												  WHERE cid = '$cid' AND YEAR(cyear) = '$i'";
										  $cres = query($sql); 
										  $cnum = num_rows($cres); 
										  if ($cnum > 0) {
											  $crow = fetch_array($cres); 
											  ?><a href="e_financials.php?cfid=<?php echo $crow[0] ?>" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i></a><?php
										  } else {
											  ?><a href="add_financials.php?cid=<?php echo $cid ?>&year=<?php echo $i ?>" class="btn btn-primary btn-xs"><i class="fa fa-plus-square"></i></a><?php
										  }
										  ?></ul></th><?php
										  mysqli_free_result($cres);
									  }
									  ?>
								  </tr>
								</thead>
								<tbody>
									<tr>
										<td>Assets</td>
										<?php
										for ($i=2014; $i<2019; $i++) {
											$sql = "SELECT assets
													FROM cooperatives_financials
													WHERE cid = '$cid' AND YEAR(cyear) = '$i'";
											$res = query($sql); 
											$row = fetch_array($res); 
											?><td align="right"><?php echo number_format($row[0], 2) ?></td><?php
											mysqli_free_result($res);
										}
										?>
									</tr>
									<tr>
										<td>Paid-Up Capital</td>
										<?php
										for ($i=2014; $i<2019; $i++) {
											$sql = "SELECT capital
													FROM cooperatives_financials
													WHERE cid = '$cid' AND YEAR(cyear) = '$i'";
											$res = query($sql); 
											$row = fetch_array($res); 
											?><td align="right"><?php echo number_format($row[0], 2) ?></td><?php
											mysqli_free_result($res);
										}
										?>
									</tr>
									<tr>
										<td>Revenues</td>
										<?php
										for ($i=2014; $i<2019; $i++) {
											$sql = "SELECT revenues
													FROM cooperatives_financials
													WHERE cid = '$cid' AND YEAR(cyear) = '$i'";
											$res = query($sql); 
											$row = fetch_array($res); 
											?><td align="right"><?php echo number_format($row[0], 2) ?></td><?php
											mysqli_free_result($res);
										}
										?>
									</tr>
									<tr>
										<td>Expenses</td>
										<?php
										for ($i=2014; $i<2019; $i++) {
											$sql = "SELECT expenses
													FROM cooperatives_financials
													WHERE cid = '$cid' AND YEAR(cyear) = '$i'";
											$res = query($sql); 
											$row = fetch_array($res); 
											?><td align="right"><?php echo number_format($row[0], 2) ?></td><?php
											mysqli_free_result($res);
										}
										?>
									</tr>
									<tr>
										<td>Profit</td>
										<?php
										for ($i=2014; $i<2019; $i++) {
											$sql = "SELECT profit
													FROM cooperatives_financials
													WHERE cid = '$cid' AND YEAR(cyear) = '$i'";
											$res = query($sql); 
											$row = fetch_array($res); 
											?><td align="right"><?php echo number_format($row[0], 2) ?></td><?php
											mysqli_free_result($res);
										}
										?>
									</tr>
								</tbody>
							</table>
						  </div>
						</div>
					  </div>
					  <!-- end of Financial Statements -->
					
					  <!-- Businesses -->
					  <div class="col-md-12 col-sm-12 col-xs-12">
						<div class="x_panel">
						  <div class="x_title">
							<h2>Existing Businesses</h2>
							<ul class="nav navbar-right panel_toolbox">
							  <li><a href="add_business.php?cid=<?php echo $cid ?>"><i class="fa fa-plus"></i></a></li>
							  <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
							  <li><a class="close-link"><i class="fa fa-close"></i></a></li>
							</ul>
							<div class="clearfix"></div>
						  </div>
						  <div class="x_content">
							<table class="table table-striped table-bordered jambo_table bulk_action">
								<thead>
								  <tr class="headings">
									  <th rowspan="2">Business</th>
									  <th colspan="6">Status</th>
								  </tr>
								  <tr>
									  <?php
									  for ($i=2014; $i<2019; $i++) {
										  ?><th><?php echo $i ?></th><?php
									  }
									  ?>
								  </tr>
								</thead>
								<tbody>
								<?php
								$sql = "SELECT DISTINCT bname 
										FROM cooperatives_businesses 
										WHERE cid = '$cid' 
										ORDER BY bname";
								$res = query($sql); 
								while ($row = fetch_array($res)) {
									?>
									<tr>
										<td align="left"><?php echo $row[0] ?></td>
										<?php
										for ($i=2014; $i<2019; $i++) {
											$sql = "SELECT cbid, status
													FROM cooperatives_businesses
													WHERE cid = '$cid' AND bname = '$row[0]' AND YEAR(cyear) = '$i'";
											$bres = query($sql); 
											$brow = fetch_array($bres); 
											?>
											<td align="left"><?php 
												if ($brow[1] == 1) {
													echo 'Operational';
													?>
													<ul class="nav navbar-right">
													  <a href="d_business.php?cbid=<?php echo $brow[0] ?>" class="btn btn-danger btn-xs"><i class="fa fa-eraser"></i></a>
													</ul>
													<?php
												} else {
													echo 'Inactive';
												}
												?>
											</td>
											<?php
										}
										?>
									</tr>
									<?php
								}
								mysqli_free_result($res);
								?>
								</tbody>
							</table>
						  </div>
						</div>
					  </div>
					  <!-- end of Businesses -->
						
					</div><!-- end of container -->

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
		<?php
	}
} else {
	header('Location: login.php');	
}


