<?php

include("db.php");
include("sqli.php");
include("html.php");
define('PAGE', 'tcs.php'); 

$tab = $_GET['tab'];

session_start();

if (isset($_SESSION['user_id'])) {
	if (validate_level(PAGE) == true) {

		// log the activity
		log_user(5, 'cooperatives', 0);
		
		?>
		<!DOCTYPE html>
		<html lang="en">
		  <head>
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
			<!-- Meta, title, CSS, favicons, etc. -->
			<meta charset="utf-8">
			<meta http-equiv="X-UA-Compatible" content="IE=edge">
			<meta name="viewport" content="width=device-width, initial-scale=1">

			<title>OTC - Transport Cooperatives</title>

			<!-- Bootstrap -->
			<link href="vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
			<!-- Font Awesome -->
			<link href="vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
			<!-- NProgress -->
			<link href="vendors/nprogress/nprogress.css" rel="stylesheet">
			<!-- iCheck -->
			<link href="vendors/iCheck/skins/flat/green.css" rel="stylesheet">
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
						if ($tab == 1) { echo 'Accredited Transport Cooperatives';
						} elseif ($tab == 2) { echo 'Accreditation Applicants';
						} elseif ($tab == 3) { echo 'Endorsement Applicants';
						} elseif ($tab == 4) { echo 'TC Report - No. of Units';
						} elseif ($tab == 5) { echo 'TC Report - No. of Members';
						} elseif ($tab == 6) { echo 'TC Report - Assets & Liabilities';
						} elseif ($tab == 7) { echo 'TC Report - Capitalizations';
						} elseif ($tab == 8) { echo 'TC Report - Net Surplus';
						} elseif ($tab == 9) { echo 'TCs With Incomplete Records';
						} elseif ($tab == 10) { echo 'TCs By Types of Unit';
						} elseif ($tab == 11) { echo 'TC Federations';
						} elseif ($tab == 12) { echo 'Delisted / Inactive TCs';
						} elseif ($tab == 13) { echo 'Summarized Statistical Data';
						} elseif ($tab == 14) { echo 'TCs with Valid CGS';
						}
						?>
						</h3>
					  </div>

					  <div class="title_right">
						<div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
						  <?php
						  if ($tab == 14) {
							  if (isset ($_GET['sortby'])) {
								  $sortby = $_GET['sortby'];
							  } else {
								  $sortby = 1;
							  }
							  ?>
							  <select class="form-control col-md-7 col-xs-12" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
								<?php
								if ($sortby == 1) {
									?>
									<option value="tcs.php?tab=<?php echo $tab ?>&sortby=1" Selected>Sort by: Area and Sectors</option>
									<option value="tcs.php?tab=<?php echo $tab ?>&sortby=2">Sort by: CGS Number</option>
									<?php
								} elseif ($sortby == 2) {
									?>
									<option value="tcs.php?tab=<?php echo $tab ?>&sortby=1">Sort by: Area and Sectors</option>
									<option value="tcs.php?tab=<?php echo $tab ?>&sortby=2" Selected>Sort by: CGS Number</option>
									<?php
								} else {
									?>
									<option value="tcs.php?tab=<?php echo $tab ?>&sortby=1">Sort by: Area and Sectors</option>
									<option value="tcs.php?tab=<?php echo $tab ?>&sortby=2">Sort by: CGS Number</option>
									<?php
								}
								?>
							  </select>
							  <?php
						  }
						  ?>
						</div>
					  </div>
					</div>

					<div class="clearfix"></div>

					<div class="row">
					
					  <?php
					  if (isset($_GET['success'])) {
						  if ($_GET['success'] == 1) {
							  ?>
								<div class="alert alert-success">
								  <strong>Success!</strong> Cooperative has been added into the database.
								</div>
							  <?php
						  }
					  }
					  
					  if ($tab == 4) {
						  ?>
						  <div class="col-md-12 col-sm-12 col-xs-12">
							<div class="x_panel">
							  <div class="x_title">
								<h2>National</h2>
								<ul class="nav navbar-right panel_toolbox">
								  <a href="tcpdf/examples/pdf_tc_reports.php?type=1&area_id=0" target="new" class="btn btn-info btn-md" data-toggle="tooltip" data-placement="top" title="Print Report"><i class="glyphicon glyphicon-print"></i></a>
								</ul>
								<div class="clearfix"></div>
							  </div>
							  <div class="x_content">
								<table class="table table-striped table-bordered jambo_table">
								  <thead>
									<tr>
									  <th width="13%" rowspan="2">Area</th>
									  <th width="05%" rowspan="2">No. of TCs</th>
									  <th colspan="4">PUVM Units</th>
									  <th colspan="11">TC Vehicle Type</th>
									</tr>
									<tr>
									  <th width="05%">Class 1</th>
									  <th width="05%">Class 2</th>
									  <th width="05%">Class 3</th>
									  <th width="05%">Class 4</th>
									  <th width="05%">PUJ</th>
									  <th width="05%">MCH</th>
									  <th width="05%">Taxi</th>
									  <th width="05%">MB</th>
									  <th width="05%">Bus</th>
									  <th width="05%">M.Cab</th>
									  <th width="05%">Truck</th>
									  <th width="05%">Tourist</th>
									  <th width="05%">UVEx</th>
									  <th width="05%">Banca</th>
									  <th width="07%">Total</th>
									</tr>
								  </thead>
								  <tbody>
								  <?php
								  $tc_num = 0; 
								  $tclass1_num = 0; $tclass2_num = 0; $tclass3_num = 0; $tclass4_num = 0; 
								  $tpuj_num = 0; $tmch_num = 0; $ttaxi_num = 0; 
								  $tmb_num = 0; $tbus_num = 0; $tmcab_num = 0; $ttruck_num = 0; 
								  $ttour_num = 0; $tuvx_num = 0; $tbanka_num = 0; $ttotal = 0; 
								  
								  $sql = "SELECT a.aname, COUNT(c.cid),  
											  SUM(c.class1_num), SUM(c.class2_num), SUM(c.class3_num), SUM(c.class4_num), 
											  SUM(c.puj_num), SUM(c.mch_num), SUM(c.taxi_num), 
											  SUM(c.mb_num), SUM(c.bus_num), SUM(c.mcab_num), 
											  SUM(c.truck_num), SUM(c.tours_num), SUM(c.uvx_num), SUM(c.banka_num)
										  FROM cooperatives c 
											  INNER JOIN tc_sectors s ON c.sid = s.sid
											  INNER JOIN tc_areas a ON s.aid = a.aid
										  WHERE c.ctype = 2
										  GROUP BY s.aid
										  ORDER BY a.aid";
								  $res = query($sql); 
								  while ($row = fetch_array($res)) {
									  ?>
									  <tr>
										<td><?php echo $row[0] ?></td>
										<td align="right"><?php echo number_format($row[1], 0) ?></td>
										<td align="right"><?php echo number_format($row[2], 0) ?></td>
										<td align="right"><?php echo number_format($row[3], 0) ?></td>
										<td align="right"><?php echo number_format($row[4], 0) ?></td>
										<td align="right"><?php echo number_format($row[5], 0) ?></td>
										<td align="right"><?php echo number_format($row[6], 0) ?></td>
										<td align="right"><?php echo number_format($row[7], 0) ?></td>
										<td align="right"><?php echo number_format($row[8], 0) ?></td>
										<td align="right"><?php echo number_format($row[9], 0) ?></td>
										<td align="right"><?php echo number_format($row[10], 0) ?></td>
										<td align="right"><?php echo number_format($row[11], 0) ?></td>
										<td align="right"><?php echo number_format($row[12], 0) ?></td>
										<td align="right"><?php echo number_format($row[13], 0) ?></td>
										<td align="right"><?php echo number_format($row[14], 0) ?></td>
										<td align="right"><?php echo number_format($row[15], 0) ?></td>
										<td align="right"><?php echo number_format($row[2] + $row[3] + $row[4] + $row[5] + $row[6] + $row[7] + $row[8] + $row[9] + $row[10] + $row[11] + $row[12] + $row[13] + $row[14] + $row[15], 0)?></td>
									  </tr>
									  <?php
									  $tc_num += $row[1]; 
									  $tclass1_num += $row[2]; 
									  $tclass2_num += $row[3]; 
									  $tclass3_num += $row[4]; 
									  $tclass4_num += $row[5]; 
									  $tpuj_num += $row[6]; 
									  $tmch_num += $row[7]; 
									  $ttaxi_num += $row[8]; 
									  $tmb_num += $row[9]; 
									  $tbus_num += $row[10]; 
									  $tmcab_num += $row[11]; 
									  $ttruck_num += $row[12];
									  $ttour_num += $row[13]; 
									  $tuvx_num += $row[14]; 
									  $tbanka_num += $row[15]; 
									  $ttotal += $row[2] + $row[3] + $row[4] + $row[5] + $row[6] + $row[7] + $row[8] + $row[9] + $row[10] + $row[11] + $row[12] + $row[13] + $row[14] + $row[15];
								  }
								  mysqli_free_result($res);
								  ?>
								  <tr>
									<td><b>TOTAL</b></td>
									<td align="right"><b><?php echo number_format($tc_num, 0) ?></b></td>
									<td align="right"><b><?php echo number_format($tclass1_num, 0) ?></b></td>
									<td align="right"><b><?php echo number_format($tclass2_num, 0) ?></b></td>
									<td align="right"><b><?php echo number_format($tclass3_num, 0) ?></b></td>
									<td align="right"><b><?php echo number_format($tclass4_num, 0) ?></b></td>
									<td align="right"><b><?php echo number_format($tpuj_num, 0) ?></b></td>
									<td align="right"><b><?php echo number_format($tmch_num, 0) ?></b></td>
									<td align="right"><b><?php echo number_format($ttaxi_num, 0) ?></b></td>
									<td align="right"><b><?php echo number_format($tmb_num, 0) ?></b></td>
									<td align="right"><b><?php echo number_format($tbus_num, 0) ?></b></td>
									<td align="right"><b><?php echo number_format($tmcab_num, 0) ?></b></td>
									<td align="right"><b><?php echo number_format($ttruck_num, 0) ?></b></td>
									<td align="right"><b><?php echo number_format($ttour_num, 0) ?></b></td>
									<td align="right"><b><?php echo number_format($tuvx_num, 0) ?></b></td>
									<td align="right"><b><?php echo number_format($tbanka_num, 0) ?></b></td>
									<td align="right"><b><?php echo number_format($ttotal, 0) ?></b></td>
								  </tr>
								  </tbody>
								</table>
							  </div><!-- x_content -->
							</div>
						  </div>
						  <?php
						  
						  $sql = "SELECT aid, aname FROM tc_areas";
						  $ares = query($sql); 
						  while ($arow = fetch_array($ares)) {
							  ?>
							  <div class="col-md-12 col-sm-12 col-xs-12">
								<div class="x_panel">
								  <div class="x_title">
									<h2><?php echo $arow[1] ?></h2>
									<ul class="nav navbar-right panel_toolbox">
									  <a href="tcpdf/examples/pdf_tc_reports.php?type=1&area_id=<?php echo $arow[0] ?>" target="new" class="btn btn-info btn-md" data-toggle="tooltip" data-placement="top" title="Print Report"><i class="glyphicon glyphicon-print"></i></a>
									</ul>
									<div class="clearfix"></div>
								  </div>
								  <div class="x_content">
									<table class="table table-striped table-bordered jambo_table">
									  <thead>
										<tr>
										  <th width="13%" rowspan="2">Area</th>
										  <th width="05%" rowspan="2">No. of TCs</th>
										  <th colspan="4">PUVM Units</th>
										  <th colspan="11">TC Vehicle Type</th>
										</tr>
										<tr>
										  <th width="05%">Class 1</th>
										  <th width="05%">Class 2</th>
										  <th width="05%">Class 3</th>
										  <th width="05%">Class 4</th>
										  <th width="05%">PUJ</th>
										  <th width="05%">MCH</th>
										  <th width="05%">Taxi</th>
										  <th width="05%">MB</th>
										  <th width="05%">Bus</th>
										  <th width="05%">M.Cab</th>
										  <th width="05%">Truck</th>
										  <th width="05%">Tourist</th>
										  <th width="05%">UVEx</th>
										  <th width="05%">Banca</th>
										  <th width="07%">Total</th>
										</tr>
									  </thead>
									  <tbody>
									  <?php
									  $tc_num = 0; 
									  $tclass1_num = 0; $tclass2_num = 0; $tclass3_num = 0; $tclass4_num = 0; 
									  $tpuj_num = 0; $tmch_num = 0; $ttaxi_num = 0; 
									  $tmb_num = 0; $tbus_num = 0; $tmcab_num = 0; $ttruck_num = 0; 
									  $ttour_num = 0; $tuvx_num = 0; $tbanka_num = 0; $ttotal = 0; 
									  
									  $sql = "SELECT s.sname, COUNT(c.cid), 
												  SUM(c.class1_num), SUM(c.class2_num), SUM(c.class3_num), SUM(c.class4_num), 
												  SUM(c.puj_num), SUM(c.mch_num), SUM(c.taxi_num), SUM(c.mb_num), 
												  SUM(c.bus_num), SUM(c.mcab_num), SUM(c.truck_num), 
												  SUM(c.tours_num), SUM(c.uvx_num), SUM(c.banka_num), s.sid
											  FROM cooperatives c INNER JOIN tc_sectors s ON c.sid = s.sid
											  WHERE s.aid = '$arow[0]' AND c.ctype = 2
											  GROUP BY c.sid
											  ORDER BY s.sid, c.cname";
									  $res = query($sql); 
									  while ($row = fetch_array($res)) {
										  ?>
										  <tr>
											<td><a href="tc_sector.php?tab=4&sid=<?php echo $row[16] ?>"><?php echo $row[0] ?></a></td>
											<td align="right"><?php echo number_format($row[1], 0) ?></td>
											<td align="right"><?php echo number_format($row[2], 0) ?></td>
											<td align="right"><?php echo number_format($row[3], 0) ?></td>
											<td align="right"><?php echo number_format($row[4], 0) ?></td>
											<td align="right"><?php echo number_format($row[5], 0) ?></td>
											<td align="right"><?php echo number_format($row[6], 0) ?></td>
											<td align="right"><?php echo number_format($row[7], 0) ?></td>
											<td align="right"><?php echo number_format($row[8], 0) ?></td>
											<td align="right"><?php echo number_format($row[9], 0) ?></td>
											<td align="right"><?php echo number_format($row[10], 0) ?></td>
											<td align="right"><?php echo number_format($row[11], 0) ?></td>
											<td align="right"><?php echo number_format($row[12], 0) ?></td>
											<td align="right"><?php echo number_format($row[13], 0) ?></td>
											<td align="right"><?php echo number_format($row[14], 0) ?></td>
											<td align="right"><?php echo number_format($row[15], 0) ?></td>
											<td align="right"><?php echo number_format($row[2] + $row[3] + $row[4] + $row[5] + $row[6] + $row[7] + $row[8] + $row[9] + $row[10] + $row[11] + $row[12] + $row[13] + $row[14] + $row[15], 0)?></td>
										  </tr>
										  <?php
										  $tc_num += $row[1]; 
										  $tclass1_num += $row[2]; 
										  $tclass2_num += $row[3]; 
										  $tclass3_num += $row[4]; 
										  $tclass4_num += $row[5]; 
										  $tpuj_num += $row[6]; 
										  $tmch_num += $row[7]; 
										  $ttaxi_num += $row[8]; 
										  $tmb_num += $row[9]; 
										  $tbus_num += $row[10]; 
										  $tmcab_num += $row[11]; 
										  $ttruck_num += $row[12];
										  $ttour_num += $row[13]; 
										  $tuvx_num += $row[14]; 
										  $tbanka_num += $row[15]; 
										  $ttotal += $row[2] + $row[3] + $row[4] + $row[5] + $row[6] + $row[7] + $row[8] + $row[9] + $row[10] + $row[11] + $row[12] + $row[13] + $row[14] + $row[15];
									  }
									  mysqli_free_result($res);
									  ?>
									  <tr>
										<td><b>TOTAL</b></td>
										<td align="right"><b><?php echo number_format($tc_num, 0) ?></b></td>
										<td align="right"><b><?php echo number_format($tclass1_num, 0) ?></b></td>
										<td align="right"><b><?php echo number_format($tclass2_num, 0) ?></b></td>
										<td align="right"><b><?php echo number_format($tclass3_num, 0) ?></b></td>
										<td align="right"><b><?php echo number_format($tclass4_num, 0) ?></b></td>
										<td align="right"><b><?php echo number_format($tpuj_num, 0) ?></b></td>
										<td align="right"><b><?php echo number_format($tmch_num, 0) ?></b></td>
										<td align="right"><b><?php echo number_format($ttaxi_num, 0) ?></b></td>
										<td align="right"><b><?php echo number_format($tmb_num, 0) ?></b></td>
										<td align="right"><b><?php echo number_format($tbus_num, 0) ?></b></td>
										<td align="right"><b><?php echo number_format($tmcab_num, 0) ?></b></td>
										<td align="right"><b><?php echo number_format($ttruck_num, 0) ?></b></td>
										<td align="right"><b><?php echo number_format($ttour_num, 0) ?></b></td>
										<td align="right"><b><?php echo number_format($tuvx_num, 0) ?></b></td>
										<td align="right"><b><?php echo number_format($tbanka_num, 0) ?></b></td>
										<td align="right"><b><?php echo number_format($ttotal, 0) ?></b></td>
									  </tr>
									  </tbody>
									</table>
								  </div><!-- x_content -->
								</div>
							  </div>
							  <?php
						  }
						  free_result($ares); 
					  } elseif ($tab == 5) {
						  ?>
						  <div class="col-md-12 col-sm-12 col-xs-12">
							<div class="x_panel">
							  <div class="x_title">
								<h2>National</h2>
								<ul class="nav navbar-right panel_toolbox">
								  <a href="tcpdf/examples/pdf_tc_reports.php?type=2&area_id=0" target="new" class="btn btn-info btn-md" data-toggle="tooltip" data-placement="top" title="Print Report"><i class="glyphicon glyphicon-print"></i></a>
								</ul>
								<div class="clearfix"></div>
							  </div>
							  <div class="x_content">
								<table class="table table-striped table-bordered jambo_table">
								  <thead>
									<tr>
									  <th width="10%" rowspan="2">Area</th>
									  <th width="15%" rowspan="2">Number of TCs</th>
									  <th colspan="5">Type of Member</th>
									</tr>
									<tr>
									  <th width="15%">Operators</th>
									  <th width="15%">Drivers</th>
									  <th width="15%">Allied Workers</th>
									  <th width="15%">Others</th>
									  <th width="15%">Total</th>
									</tr>
								  </thead>
								  <tbody>
								  <?php
								  $tc_num = 0; $toperators = 0; $tdrivers = 0; $tworkers = 0; $tothers = 0; $ttotal = 0; 
								  
								  $sql = "SELECT a.aname, COUNT(c.cid),  
											SUM(c.operators_num), SUM(c.drivers_num), 
											SUM(c.workers_num), SUM(c.others_num) 
										  FROM cooperatives c 
											  INNER JOIN tc_sectors s ON c.sid = s.sid
											  INNER JOIN tc_areas a ON s.aid = a.aid
										  WHERE c.ctype = 2
										  GROUP BY s.aid
										  ORDER BY a.aid";
								  $res = query($sql); 
								  while ($row = fetch_array($res)) {
									  ?>
									  <tr>
										<td align="left"><?php echo $row[0] ?></td>
										<td align="right"><?php echo number_format($row[1], 0) ?></td>
										<td align="right"><?php echo number_format($row[2], 0) ?></td>
										<td align="right"><?php echo number_format($row[3], 0) ?></td>
										<td align="right"><?php echo number_format($row[4], 0) ?></td>
										<td align="right"><?php echo number_format($row[5], 0) ?></td>
										<td align="right"><?php echo number_format($row[2] + $row[3] + $row[4] + $row[5], 0)?></td>
									  </tr>
									  <?php
									  $tc_num += $row[1]; 
									  $toperators += $row[2]; 
									  $tdrivers += $row[3]; 
									  $tworkers += $row[4]; 
									  $tothers += $row[5]; 
									  $ttotal += $row[2] + $row[3] + $row[4] + $row[5];
								  }
								  mysqli_free_result($res);
								  ?>
								  <tr>
									<td><b>TOTAL</b></td>
									<td align="right"><b><?php echo number_format($tc_num, 0) ?></b></td>
									<td align="right"><b><?php echo number_format($toperators, 0) ?></b></td>
									<td align="right"><b><?php echo number_format($tdrivers, 0) ?></b></td>
									<td align="right"><b><?php echo number_format($tworkers, 0) ?></b></td>
									<td align="right"><b><?php echo number_format($tothers, 0) ?></b></td>
									<td align="right"><b><?php echo number_format($ttotal, 0) ?></b></td>
								  </tr>
								  </tbody>
								</table>
							  </div><!-- x_content -->
							</div>
						  </div>
						  <?php
						  
						  $sql = "SELECT aid, aname FROM tc_areas";
						  $ares = query($sql); 
						  while ($arow = fetch_array($ares)) {
							  ?>
							  <div class="col-md-12 col-sm-12 col-xs-12">
								<div class="x_panel">
								  <div class="x_title">
									<h2><?php echo $arow[1] ?></h2>
									<ul class="nav navbar-right panel_toolbox">
									  <a href="tcpdf/examples/pdf_tc_reports.php?type=2&area_id=<?php echo $arow[0] ?>" target="new" class="btn btn-info btn-md" data-toggle="tooltip" data-placement="top" title="Print Report"><i class="glyphicon glyphicon-print"></i></a>
									</ul>
									<div class="clearfix"></div>
								  </div>
								  <div class="x_content">
									<table class="table table-striped table-bordered jambo_table">
									  <thead>
										<tr>
										  <th width="10%" rowspan="2">Area</th>
										  <th width="15%" rowspan="2">Number of TCs</th>
										  <th colspan="5">Type of Member</th>
										</tr>
										<tr>
										  <th width="15%">Operators</th>
										  <th width="15%">Drivers</th>
										  <th width="15%">Allied Workers</th>
										  <th width="15%">Others</th>
										  <th width="15%">Total</th>
										</tr>
									  </thead>
									  <tbody>
									  <?php
									  $toperators = 0; $tdrivers = 0; $tworkers = 0; $tothers = 0; $ttotal = 0; 
									  
									  $sql = "SELECT s.sname, COUNT(c.cid), 
												  SUM(c.operators_num), SUM(c.drivers_num), 
												  SUM(c.workers_num), SUM(c.others_num), s.sid
											  FROM cooperatives c INNER JOIN tc_sectors s ON c.sid = s.sid
											  WHERE s.aid = '$arow[0]' AND c.ctype = 2
											  GROUP BY c.sid
											  ORDER BY s.sid, c.cname";
									  $res = query($sql); 
									  while ($row = fetch_array($res)) {
										  ?>
										  <tr>
											<td><a href="tc_sector.php?tab=5&sid=<?php echo $row[6] ?>"><?php echo $row[0] ?></a></td>
											<td align="right"><?php echo number_format($row[1], 0) ?></td>
											<td align="right"><?php echo number_format($row[2], 0) ?></td>
											<td align="right"><?php echo number_format($row[3], 0) ?></td>
											<td align="right"><?php echo number_format($row[4], 0) ?></td>
											<td align="right"><?php echo number_format($row[5], 0) ?></td>
											<td align="right"><?php echo number_format($row[2] + $row[3] + $row[4] + $row[5], 0)?></td>
										  </tr>
										  <?php
										  $tc_num += $row[1]; 
										  $toperators += $row[2]; 
										  $tdrivers += $row[3]; 
										  $tworkers += $row[4]; 
										  $tothers += $row[5]; 
										  $ttotal += $row[2] + $row[3] + $row[4] + $row[5];
									  }
									  mysqli_free_result($res);
									  ?>
									  <tr>
										<td><b>TOTAL</b></td>
										<td align="right"><b><?php echo number_format($tc_num, 0) ?></b></td>
										<td align="right"><b><?php echo number_format($toperators, 0) ?></b></td>
										<td align="right"><b><?php echo number_format($tdrivers, 0) ?></b></td>
										<td align="right"><b><?php echo number_format($tworkers, 0) ?></b></td>
										<td align="right"><b><?php echo number_format($tothers, 0) ?></b></td>
										<td align="right"><b><?php echo number_format($ttotal, 0) ?></b></td>
									  </tr>
									  </tbody>
									</table>
								  </div><!-- x_content -->
								</div>
							  </div>
							  <?php
						  }
						  free_result($ares); 
					  } elseif ($tab == 6) {
						  ?>
						  <div class="col-md-12 col-sm-12 col-xs-12">
							<div class="x_panel">
							  <div class="x_title">
								<h2>National</h2>
								<ul class="nav navbar-right panel_toolbox">
								  <a href="tcpdf/examples/pdf_tc_reports.php?type=3&area_id=0" target="new" class="btn btn-info btn-md" data-toggle="tooltip" data-placement="top" title="Print Report"><i class="glyphicon glyphicon-print"></i></a>
								</ul>
								<div class="clearfix"></div>
							  </div>
							  <div class="x_content">
								<table class="table table-striped table-bordered jambo_table">
								  <thead>
									<tr>
									  <th width="10%" rowspan="2">Area</th>
									  <th width="12%" rowspan="2">Number of TCs</th>
									  <th colspan="6">Type of Asset</th>
									</tr>
									<tr>
									  <th width="13%">Current Assets</th>
									  <th width="13%">Fixed Assets</th>
									  <th width="13%">Total Assets</th>
									  <th width="13%">Liabilities</th>
									  <th width="13%">Equity</th>
									  <th width="13%">Net Income</th>
									</tr>
								  </thead>
								  <tbody>
								  <?php
								  $tc_num = 0; $tcurrent = 0; $tfixed = 0; $ttassets = 0; $tliability = 0; $tequity = 0; $tnet = 0; 
								  
								  $sql = "SELECT a.aname, COUNT(c.cid),  
											SUM(c.current_assets), SUM(c.fixed_assets), SUM(c.current_assets + c.fixed_assets), 
											SUM(c.liabilities), SUM(c.equity), SUM(c.net_income) 
										  FROM cooperatives c 
											  INNER JOIN tc_sectors s ON c.sid = s.sid
											  INNER JOIN tc_areas a ON s.aid = a.aid
										  WHERE c.ctype = 2 
										  GROUP BY s.aid
										  ORDER BY a.aid";
								  $res = query($sql); 
								  while ($row = fetch_array($res)) {
									  ?>
									  <tr>
										<td align="left"><?php echo $row[0] ?></td>
										<td align="right"><?php echo number_format($row[1], 0) ?></td>
										<td align="right"><?php echo number_format($row[2], 0) ?></td>
										<td align="right"><?php echo number_format($row[3], 0) ?></td>
										<td align="right"><?php echo number_format($row[4], 0) ?></td>
										<td align="right"><?php echo number_format($row[5], 0) ?></td>
										<td align="right"><?php echo number_format($row[6], 0) ?></td>
										<td align="right"><?php echo number_format($row[7], 0) ?></td>
									  </tr>
									  <?php
									  $tc_num += $row[1]; 
									  $tcurrent += $row[2]; 
									  $tfixed += $row[3]; 
									  $ttassets += $row[4]; 
									  $tliability += $row[5]; 
									  $tequity += $row[6]; 
									  $tnet += $row[7]; 
								  }
								  mysqli_free_result($res);
								  ?>
								  <tr>
									<td><b>TOTAL</b></td>
									<td align="right"><b><?php echo number_format($tc_num, 0) ?></b></td>
									<td align="right"><b><?php echo number_format($tcurrent, 0) ?></b></td>
									<td align="right"><b><?php echo number_format($tfixed, 0) ?></b></td>
									<td align="right"><b><?php echo number_format($ttassets, 0) ?></b></td>
									<td align="right"><b><?php echo number_format($tliability, 0) ?></b></td>
									<td align="right"><b><?php echo number_format($tequity, 0) ?></b></td>
									<td align="right"><b><?php echo number_format($tnet, 0) ?></b></td>
								  </tr>
								  </tbody>
								</table>
							  </div><!-- x_content -->
							</div>
						  </div>
						  <?php
						  
						  $sql = "SELECT aid, aname FROM tc_areas";
						  $ares = query($sql); 
						  while ($arow = fetch_array($ares)) {
							  ?>
							  <div class="col-md-12 col-sm-12 col-xs-12">
								<div class="x_panel">
								  <div class="x_title">
									<h2><?php echo $arow[1] ?></h2>
									<ul class="nav navbar-right panel_toolbox">
									  <a href="tcpdf/examples/pdf_tc_reports.php?type=3&area_id=<?php echo $arow[0] ?>" target="new" class="btn btn-info btn-md" data-toggle="tooltip" data-placement="top" title="Print Report"><i class="glyphicon glyphicon-print"></i></a>
									</ul>
									<div class="clearfix"></div>
								  </div>
								  <div class="x_content">
									<table class="table table-striped table-bordered jambo_table">
									  <thead>
										<tr>
										  <th width="10%" rowspan="2">Area</th>
										  <th width="12%" rowspan="2">Number of TCs</th>
										  <th colspan="6">Type of Asset</th>
										</tr>
										<tr>
										  <th width="13%">Current Assets</th>
										  <th width="13%">Fixed Assets</th>
										  <th width="13%">Total Assets</th>
										  <th width="13%">Liabilities</th>
										  <th width="13%">Equity</th>
										  <th width="13%">Net Income</th>
										</tr>
									  </thead>
									  <tbody>
									  <?php
									  $tc_num = 0; $tcurrent = 0; $tfixed = 0; $ttassets = 0; $tliability = 0; $tequity = 0; $tnet = 0; 
									  
									  $sql = "SELECT s.sname, COUNT(c.cid), 
												  SUM(c.current_assets), SUM(c.fixed_assets), SUM(c.current_assets + c.fixed_assets), 
												  SUM(c.liabilities), SUM(c.equity), SUM(c.net_income), s.sid
											  FROM cooperatives c INNER JOIN tc_sectors s ON c.sid = s.sid
											  WHERE s.aid = '$arow[0]' AND c.ctype = 2
											  GROUP BY c.sid
											  ORDER BY s.sid, c.cname";
									  $res = query($sql); 
									  while ($row = fetch_array($res)) {
										  ?>
										  <tr>
											<td><a href="tc_sector.php?tab=6&sid=<?php echo $row[8] ?>"><?php echo $row[0] ?></a></td>
											<td align="right"><?php echo number_format($row[1], 0) ?></td>
											<td align="right"><?php echo number_format($row[2], 0) ?></td>
											<td align="right"><?php echo number_format($row[3], 0) ?></td>
											<td align="right"><?php echo number_format($row[4], 0) ?></td>
											<td align="right"><?php echo number_format($row[5], 0) ?></td>
											<td align="right"><?php echo number_format($row[6], 0) ?></td>
											<td align="right"><?php echo number_format($row[7], 0) ?></td>
										  </tr>
										  <?php
										  $tc_num += $row[1]; 
										  $tcurrent += $row[2]; 
										  $tfixed += $row[3]; 
										  $ttassets += $row[4]; 
										  $tliability += $row[5]; 
										  $tequity += $row[6]; 
										  $tnet += $row[7]; 
									  }
									  mysqli_free_result($res);
									  ?>
									  <tr>
										<td><b>TOTAL</b></td>
										<td align="right"><b><?php echo number_format($tc_num, 0) ?></b></td>
										<td align="right"><b><?php echo number_format($tcurrent, 0) ?></b></td>
										<td align="right"><b><?php echo number_format($tfixed, 0) ?></b></td>
										<td align="right"><b><?php echo number_format($ttassets, 0) ?></b></td>
										<td align="right"><b><?php echo number_format($tliability, 0) ?></b></td>
										<td align="right"><b><?php echo number_format($tequity, 0) ?></b></td>
										<td align="right"><b><?php echo number_format($tnet, 0) ?></b></td>
									  </tr>
									  </tbody>
									</table>
								  </div><!-- x_content -->
								</div>
							  </div>
							  <?php
						  }
						  free_result($ares); 
					  } elseif ($tab == 7) {
						  ?>
						  <div class="col-md-12 col-sm-12 col-xs-12">
							<div class="x_panel">
							  <div class="x_title">
								<h2>National</h2>
								<ul class="nav navbar-right panel_toolbox">
								  <a href="tcpdf/examples/pdf_tc_reports.php?type=4&area_id=0" target="new" class="btn btn-info btn-md" data-toggle="tooltip" data-placement="top" title="Print Report"><i class="glyphicon glyphicon-print"></i></a>
								</ul>
								<div class="clearfix"></div>
							  </div>
							  <div class="x_content">
								<table class="table table-striped table-bordered jambo_table">
								  <thead>
									<tr>
									  <th width="15%" rowspan="2">Area</th>
									  <th width="17%" rowspan="2">Number of TCs</th>
									  <th colspan="4">Type of Stock</th>
									</tr>
									<tr>
									  <th width="17%">Initial Authorized Capital Stock</th>
									  <th width="17%">Present Authorized Capital Stock</th>
									  <th width="17%">Subscribed Capital</th>
									  <th width="17%">Paid-Up Capital</th>
									</tr>
								  </thead>
								  <tbody>
								  <?php
								  $tc_num = 0; $tinit = 0; $tpresent = 0; $tsubscribed = 0; $tpaid = 0; 
								  
								  $sql = "SELECT a.aname, COUNT(c.cid),  
											SUM(c.init_stock), SUM(c.present_stock), 
											SUM(c.subscribed), SUM(c.paid_up) 
										  FROM cooperatives c 
											  INNER JOIN tc_sectors s ON c.sid = s.sid
											  INNER JOIN tc_areas a ON s.aid = a.aid
										  WHERE c.ctype = 2 
										  GROUP BY s.aid
										  ORDER BY a.aid";
								  $res = query($sql); 
								  while ($row = fetch_array($res)) {
									  ?>
									  <tr>
										<td align="left"><?php echo $row[0] ?></td>
										<td align="right"><?php echo number_format($row[1], 0) ?></td>
										<td align="right"><?php echo number_format($row[2], 2) ?></td>
										<td align="right"><?php echo number_format($row[3], 2) ?></td>
										<td align="right"><?php echo number_format($row[4], 2) ?></td>
										<td align="right"><?php echo number_format($row[5], 2) ?></td>
									  </tr>
									  <?php
									  $tc_num += $row[1]; 
									  $tinit += $row[2]; 
									  $tpresent += $row[3]; 
									  $tsubscribed += $row[4]; 
									  $tpaid += $row[5]; 
								  }
								  mysqli_free_result($res);
								  ?>
								  <tr>
									<td><b>TOTAL</b></td>
									<td align="right"><b><?php echo number_format($tc_num, 0) ?></b></td>
									<td align="right"><b><?php echo number_format($tinit, 2) ?></b></td>
									<td align="right"><b><?php echo number_format($tpresent, 2) ?></b></td>
									<td align="right"><b><?php echo number_format($tsubscribed, 2) ?></b></td>
									<td align="right"><b><?php echo number_format($tpaid, 2) ?></b></td>
								  </tr>
								  </tbody>
								</table>
							  </div><!-- x_content -->
							</div>
						  </div>
						  <?php
						  
						  $sql = "SELECT aid, aname FROM tc_areas";
						  $ares = query($sql); 
						  while ($arow = fetch_array($ares)) {
							  ?>
							  <div class="col-md-12 col-sm-12 col-xs-12">
								<div class="x_panel">
								  <div class="x_title">
									<h2><?php echo $arow[1] ?></h2>
									<ul class="nav navbar-right panel_toolbox">
									  <a href="tcpdf/examples/pdf_tc_reports.php?type=4&area_id=<?php echo $arow[0] ?>" target="new" class="btn btn-info btn-md" data-toggle="tooltip" data-placement="top" title="Print Report"><i class="glyphicon glyphicon-print"></i></a>
									</ul>
									<div class="clearfix"></div>
								  </div>
								  <div class="x_content">
									<table class="table table-striped table-bordered jambo_table">
									  <thead>
										<tr>
										  <th width="15%" rowspan="2">Area</th>
										  <th width="17%" rowspan="2">Number of TCs</th>
										  <th colspan="4">Type of Stock</th>
										</tr>
										<tr>
										  <th width="17%">Initial Authorized Capital Stock</th>
										  <th width="17%">Present Authorized Capital Stock</th>
										  <th width="17%">Subscribed Capital</th>
										  <th width="17%">Paid-Up Capital</th>
										</tr>
									  </thead>
									  <tbody>
									  <?php
									  $tc_num = 0; $tinit = 0; $tpresent = 0; $tsubscribed = 0; $tpaid = 0; 
									  
									  $sql = "SELECT s.sname, COUNT(c.cid), 
												  SUM(c.init_stock), SUM(c.present_stock), 
												  SUM(c.subscribed), SUM(c.paid_up), s.sid
											  FROM cooperatives c INNER JOIN tc_sectors s ON c.sid = s.sid
											  WHERE s.aid = '$arow[0]' AND c.ctype = 2
											  GROUP BY c.sid
											  ORDER BY s.sid, c.cname";
									  $res = query($sql); 
									  while ($row = fetch_array($res)) {
										  ?>
										  <tr>
											<td><a href="tc_sector.php?tab=7&sid=<?php echo $row[6] ?>"><?php echo $row[0] ?></a></td>
											<td align="right"><?php echo number_format($row[1], 0) ?></td>
											<td align="right"><?php echo number_format($row[2], 0) ?></td>
											<td align="right"><?php echo number_format($row[3], 0) ?></td>
											<td align="right"><?php echo number_format($row[4], 0) ?></td>
											<td align="right"><?php echo number_format($row[5], 0) ?></td>
										  </tr>
										  <?php
										  $tc_num += $row[1]; 
										  $tinit += $row[2]; 
										  $tpresent += $row[3]; 
										  $tsubscribed += $row[4]; 
										  $tpaid += $row[5]; 
									  }
									  mysqli_free_result($res);
									  ?>
									  <tr>
										<td><b>TOTAL</b></td>
										<td align="right"><b><?php echo number_format($tc_num, 0) ?></b></td>
										<td align="right"><b><?php echo number_format($tinit, 0) ?></b></td>
										<td align="right"><b><?php echo number_format($tpresent, 0) ?></b></td>
										<td align="right"><b><?php echo number_format($tsubscribed, 0) ?></b></td>
										<td align="right"><b><?php echo number_format($tpaid, 0) ?></b></td>
									  </tr>
									  </tbody>
									</table>
								  </div><!-- x_content -->
								</div>
							  </div>
							  <?php
						  }
						  free_result($ares); 
					  } elseif ($tab == 8) {
						  ?>
						  <div class="col-md-12 col-sm-12 col-xs-12">
							<div class="x_panel">
							  <div class="x_title">
								<h2>National</h2>
								<ul class="nav navbar-right panel_toolbox">
								  <a href="tcpdf/examples/pdf_tc_reports.php?type=5&area_id=0" target="new" class="btn btn-info btn-md" data-toggle="tooltip" data-placement="top" title="Print Report"><i class="glyphicon glyphicon-print"></i></a>
								</ul>
								<div class="clearfix"></div>
							  </div>
							  <div class="x_content">
								<table class="table table-striped table-bordered jambo_table">
								  <thead>
									<tr>
									  <th width="16%" rowspan="2">Area</th>
									  <th width="14%" rowspan="2">Number of TCs</th>
									  <th colspan="5">Type of Fund</th>
									</tr>
									<tr>
									  <th width="14%">General Reserve Fund</th>
									  <th width="14%">Education and Training Fund</th>
									  <th width="14%">Community Dev't Fund</th>
									  <th width="14%">Optional Fund</th>
									  <th width="14%">Dividends & Patronage Refund</th>
									</tr>
								  </thead>
								  <tbody>
								  <?php
								  $tc_num = 0; $treserve = 0; $teduc = 0; $tcomm = 0; $toptional = 0; $tdividends = 0; 
								  
								  $sql = "SELECT a.aname, COUNT(c.cid),  
											SUM(c.reserved), SUM(c.educ_training), 
											SUM(c.comm_dev), SUM(c.optional), SUM(c.dividends) 
										  FROM cooperatives c 
											  INNER JOIN tc_sectors s ON c.sid = s.sid
											  INNER JOIN tc_areas a ON s.aid = a.aid
										  WHERE c.ctype = 2 
										  GROUP BY s.aid
										  ORDER BY a.aid";
								  $res = query($sql); 
								  while ($row = fetch_array($res)) {
									  ?>
									  <tr>
										<td align="left"><?php echo $row[0] ?></td>
										<td align="right"><?php echo number_format($row[1], 0) ?></td>
										<td align="right"><?php echo number_format($row[2], 0) ?></td>
										<td align="right"><?php echo number_format($row[3], 0) ?></td>
										<td align="right"><?php echo number_format($row[4], 0) ?></td>
										<td align="right"><?php echo number_format($row[5], 0) ?></td>
										<td align="right"><?php echo number_format($row[6], 0) ?></td>
									  </tr>
									  <?php
									  $tc_num += $row[1]; 
									  $treserve += $row[2]; 
									  $teduc += $row[3]; 
									  $tcomm += $row[4]; 
									  $toptional += $row[5]; 
									  $tdividends += $row[6]; 
								  }
								  mysqli_free_result($res);
								  ?>
								  <tr>
									<td><b>TOTAL</b></td>
									<td align="right"><b><?php echo number_format($tc_num, 0) ?></b></td>
									<td align="right"><b><?php echo number_format($treserve, 0) ?></b></td>
									<td align="right"><b><?php echo number_format($teduc, 0) ?></b></td>
									<td align="right"><b><?php echo number_format($tcomm, 0) ?></b></td>
									<td align="right"><b><?php echo number_format($toptional, 0) ?></b></td>
									<td align="right"><b><?php echo number_format($tdividends, 0) ?></b></td>
								  </tr>
								  </tbody>
								</table>
							  </div><!-- x_content -->
							</div>
						  </div>
						  <?php
						  
						  $sql = "SELECT aid, aname FROM tc_areas";
						  $ares = query($sql); 
						  while ($arow = fetch_array($ares)) {
							  ?>
							  <div class="col-md-12 col-sm-12 col-xs-12">
								<div class="x_panel">
								  <div class="x_title">
									<h2><?php echo $arow[1] ?></h2>
									<ul class="nav navbar-right panel_toolbox">
									  <a href="tcpdf/examples/pdf_tc_reports.php?type=5&area_id=<?php echo $arow[0] ?>" target="new" class="btn btn-info btn-md" data-toggle="tooltip" data-placement="top" title="Print Report"><i class="glyphicon glyphicon-print"></i></a>
									</ul>
									<div class="clearfix"></div>
								  </div>
								  <div class="x_content">
									<table class="table table-striped table-bordered jambo_table">
									  <thead>
										<tr>
										  <th width="16%" rowspan="2">Area</th>
										  <th width="14%" rowspan="2">Number of TCs</th>
										  <th colspan="5">Type of Fund</th>
										</tr>
										<tr>
										  <th width="14%">General Reserve Fund</th>
										  <th width="14%">Education and Training Fund</th>
										  <th width="14%">Community Dev't Fund</th>
										  <th width="14%">Optional Fund</th>
										  <th width="14%">Dividends & Patronage Refund</th>
										</tr>
									  </thead>
									  <tbody>
									  <?php
									  $tc_num = 0; $treserve = 0; $teduc = 0; $tcomm = 0; $toptional = 0; $tdividends = 0; 
									  
									  $sql = "SELECT s.sname, COUNT(c.cid), 
												  SUM(c.reserved), SUM(c.educ_training), 
												  SUM(c.comm_dev), SUM(c.optional), SUM(c.dividends), s.sid
											  FROM cooperatives c INNER JOIN tc_sectors s ON c.sid = s.sid
											  WHERE s.aid = '$arow[0]' AND c.ctype = 2 
											  GROUP BY c.sid
											  ORDER BY s.sid, c.cname";
									  $res = query($sql); 
									  while ($row = fetch_array($res)) {
										  ?>
										  <tr>
											<td><a href="tc_sector.php?tab=8&sid=<?php echo $row[7] ?>"><?php echo $row[0] ?></a></td>
											<td align="right"><?php echo number_format($row[1], 0) ?></td>
											<td align="right"><?php echo number_format($row[2], 0) ?></td>
											<td align="right"><?php echo number_format($row[3], 0) ?></td>
											<td align="right"><?php echo number_format($row[4], 0) ?></td>
											<td align="right"><?php echo number_format($row[5], 0) ?></td>
											<td align="right"><?php echo number_format($row[6], 0) ?></td>
										  </tr>
										  <?php
										  $tc_num += $row[1]; 
										  $treserve += $row[2]; 
										  $teduc += $row[3]; 
										  $tcomm += $row[4]; 
										  $toptional += $row[5]; 
										  $tdividends += $row[6]; 
									  }
									  mysqli_free_result($res);
									  ?>
									  <tr>
										<td><b>TOTAL</b></td>
										<td align="right"><b><?php echo number_format($tc_num, 0) ?></b></td>
										<td align="right"><b><?php echo number_format($treserve, 0) ?></b></td>
										<td align="right"><b><?php echo number_format($teduc, 0) ?></b></td>
										<td align="right"><b><?php echo number_format($tcomm, 0) ?></b></td>
										<td align="right"><b><?php echo number_format($toptional, 0) ?></b></td>
										<td align="right"><b><?php echo number_format($tdividends, 0) ?></b></td>
									  </tr>
									  </tbody>
									</table>
								  </div><!-- x_content -->
								</div>
							  </div>
							  <?php
						  }
						  free_result($ares); 
					  } elseif ($tab == 9) {
						  if (isset ($_GET['category'])) {
							  $category = $_GET['category'];
						  } else {
							  $category = 1; 
						  }
						  ?>
						  <div class="col-md-12 col-sm-12 col-xs-12">
							<div class="x_panel">
							  <div class="x_title"><h2>Incomple Records</h2>
								<ul class="nav navbar-right panel_toolbox">
								</ul>
								<div class="clearfix"></div>
							  </div>
							  <div class="x_content">
							  
								<div class="" role="tabpanel" data-example-id="togglable-tabs">
								  <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
								  <?php 
								
								  for ($i=1; $i<8; $i++) {
									  if ($i == 1) { $header = 'Basic Information';
									  } elseif ($i == 2) { $header = 'Members Information';
									  } elseif ($i == 3) { $header = 'Vehicle Information';
									  } elseif ($i == 4) { $header = 'Financial Information';
									  } elseif ($i == 5) { $header = 'Capitalizations';
									  } elseif ($i == 6) { $header = 'Routes';
									  } elseif ($i == 7) { $header = 'Email';
									  } 
									  ?><li role="presentation" class="<?php echo $i == $category ? 'active' : '' ?>"><a href="tcs.php?tab=9&category=<?php echo $i ?>"><?php echo $header ?></a></li><?php
								  }
								  ?>
								  </ul>
								  <div class="tab-content">
									<?php
									if ($category == 1) {
										?>
									    <div class="x_title">
										  <ul class="nav navbar-right panel_toolbox">
										    <a href="tcpdf/examples/pdf_tc_reports.php?type=6" target="new" class="btn btn-info btn-md" data-toggle="tooltip" data-placement="top" title="Print Report"><i class="glyphicon glyphicon-print"></i></a>
										  </ul>
										  <div class="clearfix"></div>
									    </div>
										<table class="table table-striped table-bordered jambo_table">
										  <thead>
											  <tr>
												  <th width="08%" rowspan="2">Sector</th>
												  <th width="15%" rowspan="2">TC Name</th>
												  <th width="25%" rowspan="2">Address</th>
												  <th width="10%" rowspan="2">Chairman</th>
												  <th width="10%" rowspan="2">Contact #s</th>
												  <th colspan="2">OTC</th>
												  <th colspan="2">CDA</th>
											  </tr>
											  <tr>
												  <th width="08%">Acc.#</th>
												  <th width="08%">Acc. Date</th>
												  <th width="08%">Reg.#</th>
												  <th width="80%">Reg. Date</th>
											  </tr>
										  </thead>
										  <tbody>
										  <?php
											$sql = "SELECT cid, cname, addr, chairman, chairman_num, contact_num, 
														new_otc_num, DATE_FORMAT(new_otc_date, '%m/%d/%y'), 
														new_cda_num, DATE_FORMAT(new_cda_date, '%m/%d/%y'), s.sname
													FROM cooperatives c INNER JOIN tc_sectors s ON c.sid = s.sid
													WHERE c.ctype = 2 AND (addr = '' OR chairman = '' OR chairman_num = '' OR contact_num = '' OR new_otc_num = '' OR new_cda_num = '')
													ORDER BY c.cname";
											$res = query($sql); 
											$num = num_rows($res); 

											while ($row = fetch_array($res)) {
												?>
												<tr>
													<td align="left"><?php echo $row[10] ?></td>
													<td align="left"><a href="tc.php?cid=<?php echo $row[0] ?>"><?php echo ucwords(strtolower($row[1])) ?></a></td>
													<td align="left"><?php echo $row[2] ?></td>
													<td align="left"><?php echo $row[3].'<br />'.$row[4] ?></td>
													<td align="left"><?php echo $row[5] ?></td>
													<td align="center"><?php echo $row[6] ?></td>
													<td align="center"><?php echo $row[7] ?></td>
													<td align="center"><?php echo $row[8] ?></td>
													<td align="center"><?php echo $row[9] ?></td>
												</tr>
												<?php
											}
											mysqli_free_result($res);
										  ?>
										  <tr>
											<td colspan="2" align="right"><b>Total Number</b></td>
											<td align="right"><b><?php echo number_format($num, 0) ?></b></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
										  </tr>
										  </tbody>
										</table>
										<?php
									} elseif ($category == 2) {
										?>
									    <div class="x_title">
										  <ul class="nav navbar-right panel_toolbox">
										    <a href="tcpdf/examples/pdf_tc_reports.php?type=7" target="new" class="btn btn-info btn-md" data-toggle="tooltip" data-placement="top" title="Print Report"><i class="glyphicon glyphicon-print"></i></a>
										  </ul>
										  <div class="clearfix"></div>
									    </div>
										<table class="table table-striped table-bordered jambo_table">
										  <thead>
											<tr>
											  <th width="10%" rowspan="2">Sector</th>
											  <th width="35%" rowspan="2">Transportation Cooperatives</th>
											  <th width="15%" rowspan="2">Contact Numbers</th>
											  <th colspan="5">Type of Member</th>
											</tr>
											<tr>
											  <th width="08%">Operators</th>
											  <th width="08%">Drivers</th>
											  <th width="08%">Allied Workers</th>
											  <th width="08%">Others</th>
											  <th width="08%">Total</th>
											</tr>
										  </thead>
										  <tbody>
										  <?php
										  $sql = "SELECT c.cid, s.sname, c.cname, c.chairman_num, c.contact_num, c.operators_num, c.drivers_num, c.workers_num, c.others_num
												  FROM cooperatives c INNER JOIN tc_sectors s ON c.sid = s.sid
												  WHERE c.ctype = 2 AND c.operators_num = 0 AND c.drivers_num = 0 AND c.workers_num = 0 AND c.others_num = 0
												  ORDER BY s.sid, c.cname";
										  $res = query($sql); $num = num_rows($res); 
										  while ($row = fetch_array($res)) {
											  ?>
											  <tr>
												<td align="left"><?php echo $row[1] ?></td>
												<td align="left"><a href="tc.php?cid=<?php echo $row[0] ?>"><?php echo ucwords(strtolower($row[2])) ?></a></td>
												<td align="left"><?php echo $row[3].' '.$row[4] ?></td>
												<td align="right"><?php echo number_format($row[5], 0) ?></td>
												<td align="right"><?php echo number_format($row[6], 0) ?></td>
												<td align="right"><?php echo number_format($row[7], 0) ?></td>
												<td align="right"><?php echo number_format($row[8], 0) ?></td>
												<td align="right"><?php echo number_format($row[5] + $row[6] + $row[7] + $row[8], 0)?></td>
											  </tr>
											  <?php
										  }
										  free_result($res);
										  ?>
										  <tr>
											<td><b>TOTAL: <?php echo number_format($num, 0) ?></b></td>
											<td align="right"></td>
											<td align="right"></td>
											<td align="right"></td>
											<td align="right"></td>
											<td align="right"></td>
											<td align="right"></td>
											<td align="right"></td>
										  </tr>
										  </tbody>
										</table>
										<?php
									} elseif ($category == 3) {
										?>
									    <div class="x_title">
										  <ul class="nav navbar-right panel_toolbox">
										    <a href="tcpdf/examples/pdf_tc_reports.php?type=8" target="new" class="btn btn-info btn-md" data-toggle="tooltip" data-placement="top" title="Print Report"><i class="glyphicon glyphicon-print"></i></a>
										  </ul>
										  <div class="clearfix"></div>
									    </div>
										<table class="table table-striped table-bordered jambo_table">
										  <thead>
											<tr>
											  <th width="10%" rowspan="2">Sector</th>
											  <th width="20%" rowspan="2">Transportation Cooperatives</th>
											  <th colspan="4">PUVMP Vehicle</th>
											  <th colspan="11">TC Vehicle Type</th>
											</tr>
											<tr>
											  <th width="05%">Class 1</th>
											  <th width="05%">Class 2</th>
											  <th width="05%">Class 3</th>
											  <th width="05%">Class 4</th>
											  <th width="05%">PUJ</th>
											  <th width="05%">MCH</th>
											  <th width="05%">Taxi</th>
											  <th width="05%">MB</th>
											  <th width="05%">Bus</th>
											  <th width="05%">M.Cab</th>
											  <th width="05%">Truck</th>
											  <th width="05%">Tourist</th>
											  <th width="05%">UVEx</th>
											  <th width="05%">Banca</th>
											</tr>
										  </thead>
										  <tbody>
										  <?php
										  $sql = "SELECT c.cid, s.sname, c.cname, c.class1_num, c.class2_num, c.class3_num, c.class4_num,
													c.puj_num, c.mch_num, c.taxi_num, c.mb_num, c.bus_num, c.mcab_num, c.truck_num, c.tours_num, c.uvx_num, c.banka_num 
												  FROM cooperatives c INNER JOIN tc_sectors s ON c.sid = s.sid 
												  WHERE c.ctype = 2 AND c.puj_num = 0 AND c.mch_num = 0 AND c.taxi_num = 0 AND c.mb_num = 0 AND c.bus_num = 0 AND c.mcab_num = 0 AND c.truck_num = 0 AND c.tours_num = 0 AND c.uvx_num = 0 AND c.banka_num = 0
												  ORDER BY s.sid, c.cname";
										  $res = query($sql); $num = num_rows($res); 
										  while ($row = fetch_array($res)) {
											  ?>
											  <tr>
												<td align="left"><?php echo $row[1] ?></td>
												<td align="left"><a href="tc.php?cid=<?php echo $row[0] ?>"><?php echo ucwords(strtolower($row[2])) ?></a></td>
												<td align="right"><?php echo number_format($row[3], 0) ?></td>
												<td align="right"><?php echo number_format($row[4], 0) ?></td>
												<td align="right"><?php echo number_format($row[5], 0) ?></td>
												<td align="right"><?php echo number_format($row[6], 0) ?></td>
												<td align="right"><?php echo number_format($row[7], 0) ?></td>
												<td align="right"><?php echo number_format($row[8], 0) ?></td>
												<td align="right"><?php echo number_format($row[9], 0) ?></td>
												<td align="right"><?php echo number_format($row[10], 0) ?></td>
												<td align="right"><?php echo number_format($row[11], 0) ?></td>
												<td align="right"><?php echo number_format($row[12], 0) ?></td>
												<td align="right"><?php echo number_format($row[13], 0) ?></td>
												<td align="right"><?php echo number_format($row[14], 0) ?></td>
												<td align="right"><?php echo number_format($row[15], 0) ?></td>
												<td align="right"><?php echo number_format($row[16], 0) ?></td>
											  </tr>
											  <?php
										  }
										  free_result($res);
										  ?>
										  <tr>
											<td><b>TOTAL: <?php echo number_format($num, 0) ?></b></td>
											<td align="right"></td>
											<td align="right"></td>
											<td align="right"></td>
											<td align="right"></td>
											<td align="right"></td>
											<td align="right"></td>
											<td align="right"></td>
											<td align="right"></td>
											<td align="right"></td>
											<td align="right"></td>
											<td align="right"></td>
											<td align="right"></td>
											<td align="right"></td>
											<td align="right"></td>
											<td align="right"></td>
										  </tr>
										  </tbody>
										</table>
										<?php
									} elseif ($category == 4) {
										?>
									    <div class="x_title">
										  <ul class="nav navbar-right panel_toolbox">
										    <a href="tcpdf/examples/pdf_tc_reports.php?type=9" target="new" class="btn btn-info btn-md" data-toggle="tooltip" data-placement="top" title="Print Report"><i class="glyphicon glyphicon-print"></i></a>
										  </ul>
										  <div class="clearfix"></div>
									    </div>
										<table class="table table-striped table-bordered jambo_table">
										  <thead>
											<tr>
											  <th width="10%" rowspan="2">Sector</th>
											  <th width="20%" rowspan="2">Transportation Cooperatives</th>
											  <th width="10%" rowspan="2">Contact Numbers</th>
											  <th colspan="6">Type of Asset</th>
											</tr>
											<tr>
											  <th width="10%">Current Assets</th>
											  <th width="10%">Fixed Assets</th>
											  <th width="10%">Total Assets</th>
											  <th width="10%">Liabilities</th>
											  <th width="10%">Equity</th>
											  <th width="10%">Net Income</th>
											</tr>
										  </thead>
										  <tbody>
										  <?php
										  $sql = "SELECT c.cid, s.sname, c.cname, c.chairman_num, c.contact_num, c.current_assets, c.fixed_assets, 
													  c.current_assets + c.fixed_assets, 
													  c.liabilities, c.equity, c.net_income
												  FROM cooperatives c INNER JOIN tc_sectors s ON c.sid = s.sid  
												  WHERE c.ctype = 2 AND c.current_assets = 0 AND c.fixed_assets = 0 AND c.liabilities = 0 AND c.equity = 0 AND c.net_income = 0
												  ORDER BY s.sid, c.cname";
										  $res = query($sql); $num = num_rows($res); 
										  while ($row = fetch_array($res)) {
											  ?>
											  <tr>
												<td align="left"><?php echo $row[1] ?></td>
												<td align="left"><a href="tc.php?cid=<?php echo $row[0] ?>"><?php echo ucwords(strtolower($row[2])) ?></a></td>
												<td align="left"><?php echo $row[3].' '.$row[4] ?></td>
												<td align="right"><?php echo number_format($row[5], 2) ?></td>
												<td align="right"><?php echo number_format($row[6], 2) ?></td>
												<td align="right"><?php echo number_format($row[7], 2) ?></td>
												<td align="right"><?php echo number_format($row[8], 2) ?></td>
												<td align="right"><?php echo number_format($row[9], 2) ?></td>
												<td align="right"><?php echo number_format($row[10], 2) ?></td>
											  </tr>
											  <?php
										  }
										  free_result($res);
										  ?>
										  <tr>
											<td><b>TOTAL: <?php echo number_format($num, 0) ?></b></td>
											<td align="right"></td>
											<td align="right"></td>
											<td align="right"></td>
											<td align="right"></td>
											<td align="right"></td>
											<td align="right"></td>
											<td align="right"></td>
											<td align="right"></td>
										  </tr>
										  </tbody>
										</table>
										<?php
									} elseif ($category == 5) {
										?>
									    <div class="x_title">
										  <ul class="nav navbar-right panel_toolbox">
										    <a href="tcpdf/examples/pdf_tc_reports.php?type=16" target="new" class="btn btn-info btn-md" data-toggle="tooltip" data-placement="top" title="Print Report"><i class="glyphicon glyphicon-print"></i></a>
										  </ul>
										  <div class="clearfix"></div>
									    </div>
										<table class="table table-striped table-bordered jambo_table">
										  <thead>
											<tr>
											  <th width="10%" rowspan="2">Sector</th>
											  <th width="27%" rowspan="2">Transportation Cooperatives</th>
											  <th width="15%" rowspan="2">Contact Numbers</th>
											  <th colspan="4">Type of Capitalization</th>
											</tr>
											<tr>
											  <th width="12%">Initial Authorized Stock</th>
											  <th width="12%">Present Authorized Capital Stock</th>
											  <th width="12%">Subscribed Capital</th>
											  <th width="12%">Paid-Up Capital</th>
											</tr>
										  </thead>
										  <tbody>
										  <?php
										  $sql = "SELECT c.cid, s.sname, c.cname, c.chairman_num, c.contact_num, 
													  c.init_stock, c.present_stock, c.subscribed, c.paid_up
												  FROM cooperatives c INNER JOIN tc_sectors s ON c.sid = s.sid  
												  WHERE c.ctype = 2 AND c.paid_up = 0
												  ORDER BY s.sid, c.cname";
										  $res = query($sql); $num = num_rows($res); 
										  while ($row = fetch_array($res)) {
											  ?>
											  <tr>
												<td align="left"><?php echo $row[1] ?></td>
												<td align="left"><a href="tc.php?cid=<?php echo $row[0] ?>"><?php echo ucwords(strtolower($row[2])) ?></a></td>
												<td align="left"><?php echo $row[3].' '.$row[4] ?></td>
												<td align="right"><?php echo number_format($row[5], 2) ?></td>
												<td align="right"><?php echo number_format($row[6], 2) ?></td>
												<td align="right"><?php echo number_format($row[7], 2) ?></td>
												<td align="right"><?php echo number_format($row[8], 2) ?></td>
											  </tr>
											  <?php
										  }
										  free_result($res);
										  ?>
										  <tr>
											<td><b>TOTAL: <?php echo number_format($num, 0) ?></b></td>
											<td align="right"></td>
											<td align="right"></td>
											<td align="right"></td>
											<td align="right"></td>
											<td align="right"></td>
											<td align="right"></td>
										  </tr>
										  </tbody>
										</table>
										<?php
									} elseif ($category == 6) {
										?>
									    <div class="x_title">
										  <ul class="nav navbar-right panel_toolbox">
										    <a href="tcpdf/examples/pdf_tc_reports.php?type=17" target="new" class="btn btn-info btn-md" data-toggle="tooltip" data-placement="top" title="Print Report"><i class="glyphicon glyphicon-print"></i></a>
										  </ul>
										  <div class="clearfix"></div>
									    </div>
										<table class="table table-striped table-bordered jambo_table">
										  <thead>
											<tr>
											  <th width="10%">Sector</th>
											  <th width="27%">Transportation Cooperatives</th>
											  <th width="15%">Contact Numbers</th>
											  <th width="12%">Accreditation Date</th>
											  <th width="12%">Last CGS</th>
											  <th width="12%">P.A. (Y/N)</th>
											  <th width="12%">P.A. Expiry</th>
											</tr>
										  </thead>
										  <tbody>
										  <?php
										  $sql = "SELECT c.cid, s.sname, c.cname, c.chairman_num, c.contact_num, 
													  DATE_FORMAT(c.new_otc_date, '%b %e, %Y'), DATE_FORMAT(c.last_cgs, '%b %e, %Y'), 
													  c.pa_expiry, DATE_FORMAT(c.pa_expiry, '%b %e, %Y')
												  FROM cooperatives c INNER JOIN tc_sectors s ON c.sid = s.sid  
												  WHERE c.cid NOT IN (SELECT DISTINCT cid FROM franchises) 
												  ORDER BY s.sid, c.cname";
										  $res = query($sql); $num = num_rows($res); 
										  while ($row = fetch_array($res)) {
											  ?>
											  <tr>
												<td align="left"><?php echo $row[1] ?></td>
												<td align="left"><a href="tc.php?cid=<?php echo $row[0] ?>"><?php echo ucwords(strtolower($row[2])) ?></a></td>
												<td align="left"><?php echo $row[3].' '.$row[4] ?></td>
												<td align="center"><?php echo $row[5] ?></td>
												<td align="center"><?php echo $row[6] ?></td>
												<td align="center"><?php echo $row[7] != '0000-00-00' ? 'Yes' : 'No' ?></td>
												<td align="center"><?php echo $row[8] ?></td>
											  </tr>
											  <?php
										  }
										  free_result($res);
										  ?>
										  <tr>
											<td><b>TOTAL: <?php echo number_format($num, 0) ?></b></td>
											<td align="right"></td>
											<td align="right"></td>
											<td align="right"></td>
											<td align="right"></td>
											<td align="right"></td>
											<td align="right"></td>
										  </tr>
										  </tbody>
										</table>
										<?php
									} elseif ($category == 7) {
										?>
									    <div class="x_title">
										  <ul class="nav navbar-right panel_toolbox">
										    <a href="tcpdf/examples/pdf_tc_reports.php?type=13" target="new" class="btn btn-info btn-md" data-toggle="tooltip" data-placement="top" title="Print Report"><i class="glyphicon glyphicon-print"></i></a>
										  </ul>
										  <div class="clearfix"></div>
									    </div>
										<table class="table table-striped table-bordered jambo_table">
										  <thead>
											<tr>
											  <th width="05%">Sector</th>
											  <th width="10%">Sector</th>
											  <th width="35%">Transportation Cooperatives</th>
											  <th width="15%">Chairman</th>
											  <th width="15%">Contact Numbers</th>
											  <th width="20%">Email Address</th>
											</tr>
										  </thead>
										  <tbody>
										  <?php
										  $i = 1; 
										  $sql = "SELECT c.cid, s.sname, c.cname, c.chairman, c.chairman_num, c.contact_num, c.email
												  FROM cooperatives c INNER JOIN tc_sectors s ON c.sid = s.sid
												  WHERE c.email = ''
												  ORDER BY s.sid, c.cname";
										  $res = query($sql); $num = num_rows($res); 
										  while ($row = fetch_array($res)) {
											  ?>
											  <tr>
												<td align="center"><?php echo $i ?></td>
												<td align="left"><?php echo $row[1] ?></td>
												<td align="left"><a href="tc.php?cid=<?php echo $row[0] ?>"><?php echo ucwords(strtolower($row[2])) ?></a></td>
												<td align="left"><?php echo $row[3].'<br />'.$row[4] ?></td>
												<td align="left"><?php echo $row[5] ?></td>
												<td align="left"><?php echo $row[6] ?></td>
											  </tr>
											  <?php
											  $i++; 
										  }
										  free_result($res);
										  ?>
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
						  <?php
					  } elseif ($tab == 10) {
						  if (isset ($_GET['utype'])) {
							  $utype = $_GET['utype'];
						  } else {
							  $utype = 1; 
						  }
						  ?>
						  <div class="col-md-12 col-sm-12 col-xs-12">
							<div class="x_panel">
							  <div class="x_title"><h2>Types of Unit</h2>
								<ul class="nav navbar-right panel_toolbox">
								</ul>
								<div class="clearfix"></div>
							  </div>
							  <div class="x_content">
							  
								<div class="" role="tabpanel" data-example-id="togglable-tabs">
								  <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
								  <?php 
								
								  for ($i=1; $i<10; $i++) {
									  if ($i == 1) { $header = 'PUJs'; $fieldname = 'puj_num';
									  } elseif ($i == 2) { $header = 'Tricycles'; $fieldname = 'mch_num';
									  } elseif ($i == 3) { $header = 'Taxis'; $fieldname = 'taxi_num';
									  } elseif ($i == 4) { $header = 'Mini Buses'; $fieldname = 'mb_num';
									  } elseif ($i == 5) { $header = 'Buses'; $fieldname = 'bus_num';
									  } elseif ($i == 6) { $header = 'Multi Cabs'; $fieldname = 'mcab_num';
									  } elseif ($i == 7) { $header = 'Trucks'; $fieldname = 'truck_num';
									  } elseif ($i == 8) { $header = 'AUVs'; $fieldname = 'uvx_num';
									  } elseif ($i == 9) { $header = 'M. Bancas'; $fieldname = 'banka_num';
									  } 
									  ?><li role="presentation" class="<?php echo $i == $utype ? 'active' : '' ?>"><a href="tcs.php?tab=10&utype=<?php echo $i ?>"><?php echo $header ?></a></li><?php
								  }
								  
								  if ($utype == 1) { $header2 = 'PUJs'; $fieldname = 'puj_num';
								  } elseif ($utype == 2) { $header2 = 'Tricycles'; $fieldname = 'mch_num';
								  } elseif ($utype == 3) { $header2 = 'Taxis'; $fieldname = 'taxi_num';
								  } elseif ($utype == 4) { $header2 = 'Mini Buses'; $fieldname = 'mb_num';
								  } elseif ($utype == 5) { $header2 = 'Buses'; $fieldname = 'bus_num';
								  } elseif ($utype == 6) { $header2 = 'Multi Cabs'; $fieldname = 'mcab_num';
								  } elseif ($utype == 7) { $header2 = 'Trucks'; $fieldname = 'truck_num';
								  } elseif ($utype == 8) { $header2 = 'AUVs'; $fieldname = 'uvx_num';
								  } elseif ($utype == 9) { $header2 = 'M. Bancas'; $fieldname = 'banka_num';
								  } 
								  
								  ?>
								  </ul>
								  <div class="tab-content">
								    
									<div class="x_title">
									  <ul class="nav navbar-right panel_toolbox">
										<a href="tcpdf/examples/pdf_tc_reports.php?type=11&utype=<?php echo $utype ?>" target="new" class="btn btn-info btn-md" data-toggle="tooltip" data-placement="top" title="Print Report"><i class="glyphicon glyphicon-print"></i></a>
									  </ul>
									  <div class="clearfix"></div>
									</div>
									
									<table><tr><td><h2>Metro Manila Area</h2></td></tr></table>
									<table class="table table-striped table-bordered jambo_table">
									  <thead>
										  <tr>
											  <th width="10%">Area</th>
											  <th width="10%">Sector</th>
											  <th width="20%">TC Name</th>
											  <th width="20%">Address</th>
											  <th width="15%">Chairman</th>
											  <th width="15%">Contact #s</th>
											  <th width="10%">No. of <?php echo $header2 ?></th>
										  </tr>
									  </thead>
									  <tbody>
									  <?php
									    $total = 0; 
										$sql = "SELECT c.cid, s.sname, c.cname, c.addr, c.chairman, c.chairman_num, c.contact_num, ". $fieldname ."
												FROM cooperatives c INNER JOIN tc_sectors s ON c.sid = s.sid
												WHERE c.ctype = 2 AND s.aid = 1 AND ". $fieldname ." > 0
												ORDER BY s.sid, c.cname";
										$res = query($sql); 
										$num = num_rows($res); 

										while ($row = fetch_array($res)) {
											?>
											<tr>
												<td align="left">Metro Manila</td>
												<td align="left"><?php echo $row[1] ?></td>
												<td align="left"><a href="tc.php?cid=<?php echo $row[0] ?>"><?php echo ucwords(strtolower($row[2])) ?></a></td>
												<td align="left"><?php echo ucwords(strtolower($row[3])) ?></td>
												<td align="left"><?php echo ucwords(strtolower($row[4])) ?></td>
												<td align="left"><?php echo $row[5].'<br />'.$row[6] ?></td>
												<td align="right"><?php echo number_format($row[7], 0) ?></td>
											</tr>
											<?php
											$total += $row[7]; 
										}
										free_result($res);
									  ?>
									    <tr>
											<td align="right"><b>Total</b></td>
											<td align="right"><b><?php echo number_format($num, 0) ?></b></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td align="right"><b><?php echo number_format($total, 0) ?></b></td>
										</tr>
									  </tbody>
									</table>
									
									<?php
									// get the areas
									$sql = "SELECT aid, aname FROM tc_areas WHERE aid != 1";
									$ares = query($sql); 
									while ($arow = fetch_array($ares)) {
										?>
										<table><tr><td><h2><?php echo $arow[1] ?> Area</h2></td></tr></table>
										<table class="table table-striped table-bordered jambo_table">
										  <thead>
											  <tr>
												  <th width="10%">Region</th>
												  <th width="10%">Province</th>
												  <th width="20%">TC Name</th>
												  <th width="20%">Address</th>
												  <th width="15%">Chairman</th>
												  <th width="15%">Contact #s</th>
												  <th width="10%">No. of <?php echo $header2 ?></th>
											  </tr>
										  </thead>
										  <tbody>
										  <?php
										    $total = 0; 
											$sql = "SELECT c.cid, s.sname, p.pname, c.cname, c.addr, c.chairman, c.chairman_num, c.contact_num, ". $fieldname ."
													FROM cooperatives c 
														INNER JOIN tc_sectors s ON c.sid = s.sid
														LEFT JOIN towns t ON c.tid = t.tid
														LEFT JOIN provinces p ON t.pid = p.pid
													WHERE c.ctype = 2 AND s.aid = '$arow[0]' AND ". $fieldname ." > 0
													ORDER BY s.sid, p.pname, c.cname";
											$res = query($sql); 
											$num = num_rows($res); 

											while ($row = fetch_array($res)) {
												?>
												<tr>
													<td align="left"><?php echo $row[1] ?></td>
													<td align="left"><?php echo $row[2] ?></td>
													<td align="left"><a href="tc.php?cid=<?php echo $row[0] ?>"><?php echo ucwords(strtolower($row[3])) ?></a></td>
													<td align="left"><?php echo ucwords(strtolower($row[4])) ?></td>
													<td align="left"><?php echo ucwords(strtolower($row[5])).'<br />'.$row[6] ?></td>
													<td align="left"><?php echo $row[7] ?></td>
													<td align="right"><?php echo number_format($row[8], 0) ?></td>
												</tr>
												<?php
												$total += $row[8]; 
											}
											mysqli_free_result($res);
										  ?>
											<tr>
												<td align="right"><b>Total</b></td>
												<td align="right"><b><?php echo number_format($num, 0) ?></b></td>
												<td></td>
												<td></td>
												<td></td>
												<td></td>
												<td align="right"><b><?php echo number_format($total, 0) ?></b></td>
											</tr>
										  </tbody>
										</table>
										<?php
									}
									free_result($ares); 
									?>
								  </div>
								</div>
							  
							  </div>
							</div>
						  </div>
						  <?php
					  } elseif ($tab == 11) {
							?>
							<table class="table table-striped table-bordered jambo_table">
							  <thead>
								  <tr>
									  <th width="08%" rowspan="2">Sector</th>
									  <th width="15%" rowspan="2">TC Name</th>
									  <th width="25%" rowspan="2">Address</th>
									  <th width="10%" rowspan="2">Chairman</th>
									  <th width="10%" rowspan="2">Contact #s</th>
									  <th colspan="2">OTC</th>
									  <th colspan="2">CDA</th>
								  </tr>
								  <tr>
									  <th width="08%">Acc.#</th>
									  <th width="08%">Acc. Date</th>
									  <th width="08%">Reg.#</th>
									  <th width="80%">Reg. Date</th>
								  </tr>
							  </thead>
							  <tbody>
							  <?php
								$sql = "SELECT cid, cname, addr, chairman, chairman_num, contact_num, 
											new_otc_num, DATE_FORMAT(new_otc_date, '%m/%d/%y'), 
											new_cda_num, DATE_FORMAT(new_cda_date, '%m/%d/%y'), s.sname
										FROM cooperatives c INNER JOIN tc_sectors s ON c.sid = s.sid
										WHERE c.ctype = 3
										ORDER BY c.cname";
								$res = query($sql); 
								$num = num_rows($res); 

								while ($row = fetch_array($res)) {
									?>
									<tr>
										<td align="left"><?php echo $row[10] ?></td>
										<td align="left"><a href="tc.php?cid=<?php echo $row[0] ?>"><?php echo ucwords(strtolower($row[1])) ?></a></td>
										<td align="left"><?php echo ucwords(strtolower($row[2])) ?></td>
										<td align="left"><?php echo ucwords(strtolower($row[3])).'<br />'.$row[4] ?></td>
										<td align="left"><?php echo $row[5] ?></td>
										<td align="center"><?php echo $row[6] ?></td>
										<td align="center"><?php echo $row[7] ?></td>
										<td align="center"><?php echo $row[8] ?></td>
										<td align="center"><?php echo $row[9] ?></td>
									</tr>
									<?php
								}
								mysqli_free_result($res);
							  ?>
							  <tr>
								<td colspan="2" align="right"><b>Total Number</b></td>
								<td align="right"><b><?php echo number_format($num, 0) ?></b></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
							  </tr>
							  </tbody>
							</table>
							<?php
					  } elseif ($tab == 12) {
							?>
							<table class="table table-striped table-bordered jambo_table">
							  <thead>
								  <tr>
									  <th width="08%" rowspan="2">Sector</th>
									  <th width="15%" rowspan="2">TC Name</th>
									  <th width="25%" rowspan="2">Address</th>
									  <th width="10%" rowspan="2">Chairman</th>
									  <th width="10%" rowspan="2">Contact #s</th>
									  <th colspan="2">OTC</th>
									  <th colspan="2">CDA</th>
								  </tr>
								  <tr>
									  <th width="08%">Acc.#</th>
									  <th width="08%">Acc. Date</th>
									  <th width="08%">Reg.#</th>
									  <th width="80%">Reg. Date</th>
								  </tr>
							  </thead>
							  <tbody>
							  <?php
								$sql = "SELECT cid, cname, addr, chairman, chairman_num, contact_num, 
											new_otc_num, DATE_FORMAT(new_otc_date, '%m/%d/%y'), 
											new_cda_num, DATE_FORMAT(new_cda_date, '%m/%d/%y'), s.sname
										FROM cooperatives c INNER JOIN tc_sectors s ON c.sid = s.sid
										WHERE c.ctype = 4
										ORDER BY c.cname";
								$res = query($sql); 
								$num = num_rows($res); 

								while ($row = fetch_array($res)) {
									?>
									<tr>
										<td align="left"><?php echo $row[10] ?></td>
										<td align="left"><a href="tc.php?cid=<?php echo $row[0] ?>"><?php echo ucwords(strtolower($row[1])) ?></a></td>
										<td align="left"><?php echo ucwords(strtolower($row[2])) ?></td>
										<td align="left"><?php echo ucwords(strtolower($row[3])).'<br />'.$row[4] ?></td>
										<td align="left"><?php echo $row[5] ?></td>
										<td align="center"><?php echo $row[6] ?></td>
										<td align="center"><?php echo $row[7] ?></td>
										<td align="center"><?php echo $row[8] ?></td>
										<td align="center"><?php echo $row[9] ?></td>
									</tr>
									<?php
								}
								mysqli_free_result($res);
							  ?>
							  <tr>
								<td colspan="2" align="right"><b>Total Number</b></td>
								<td align="right"><b><?php echo number_format($num, 0) ?></b></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
							  </tr>
							  </tbody>
							</table>
							<?php
					  } elseif ($tab == 13) {
						  ?>
						  <div class="col-md-12 col-sm-12 col-xs-12">
							<div class="x_panel">
							  <div class="x_title">
								<h2>National</h2>
								<ul class="nav navbar-right panel_toolbox">
								  <a href="tcpdf/examples/pdf_tc_reports.php?type=12" target="new" class="btn btn-info btn-md" data-toggle="tooltip" data-placement="top" title="Print Report"><i class="glyphicon glyphicon-print"></i></a>
								</ul>
								<div class="clearfix"></div>
							  </div>
							  <div class="x_content">
								<table class="table table-striped table-bordered jambo_table">
								  <thead>
									<tr>
									  <th width="06%" rowspan="2">Area</th>
									  <th width="05%" rowspan="2">TCs</th>
									  <th width="05%" rowspan="2">Members</th>
									  <th colspan="14">TC Vehicle Type</th>
									  <th width="07%" rowspan="2">Total Assets</th>
									  <th width="07%" rowspan="2">Total Capital</th>
									</tr>
									<tr>
									  <th width="05%">Class 1</th>
									  <th width="05%">Class 2</th>
									  <th width="05%">Class 3</th>
									  <th width="05%">PUJ</th>
									  <th width="05%">MCH</th>
									  <th width="05%">Taxi</th>
									  <th width="05%">MB</th>
									  <th width="05%">Bus</th>
									  <th width="05%">M.Cab</th>
									  <th width="05%">Truck</th>
									  <th width="05%">Tourist</th>
									  <th width="05%">UVEx</th>
									  <th width="05%">Banca</th>
									  <th width="05%">Total</th>
									</tr>
								  </thead>
								  <tbody>
								  <?php
								  $tcs = 0; $tc_members = 0; $tclass1_num = 0; $tclass2_num = 0; $tclass3_num = 0; $tpuj_num = 0; 
								  $tmch_num = 0; $ttaxi_num = 0; $tmb_num = 0; $tbus_num = 0; $tmcab_num = 0; $ttruck_num = 0; $ttours_num = 0; 
								  $tuvx_num = 0; $tbanka_num = 0; $ttotal = 0; $total_capital = 0; $total_assets = 0; 
								  
								  $sql = "SELECT a.aname, SUM(c.operators_num + c.drivers_num + c.workers_num + c.others_num),
											  SUM(c.class1_num), SUM(c.class2_num), SUM(c.class3_num), SUM(c.puj_num), 
											  SUM(c.mch_num), SUM(c.taxi_num), SUM(c.mb_num), SUM(c.bus_num), SUM(c.mcab_num), 
											  SUM(c.truck_num), SUM(c.tours_num), SUM(c.uvx_num), SUM(c.banka_num), 
											  SUM(c.current_assets + c.fixed_assets), SUM(c.paid_up), COUNT(cid)
										  FROM cooperatives c 
											  INNER JOIN tc_sectors s ON c.sid = s.sid
											  INNER JOIN tc_areas a ON s.aid = a.aid
										  WHERE c.ctype = 2 
										  GROUP BY s.aid
										  ORDER BY a.aid";
								  $res = query($sql); 
								  while ($row = fetch_array($res)) {
									  ?>
									  <tr>
										<td align="left"><?php echo $row[0] ?></td>
										<td align="right"><?php echo $row[17] ?></td>
										<td align="right"><?php echo number_format($row[1], 0) ?></td>
										<td align="right"><?php echo number_format($row[2], 0) ?></td>
										<td align="right"><?php echo number_format($row[3], 0) ?></td>
										<td align="right"><?php echo number_format($row[4], 0) ?></td>
										<td align="right"><?php echo number_format($row[5], 0) ?></td>
										<td align="right"><?php echo number_format($row[6], 0) ?></td>
										<td align="right"><?php echo number_format($row[7], 0) ?></td>
										<td align="right"><?php echo number_format($row[8], 0) ?></td>
										<td align="right"><?php echo number_format($row[9], 0) ?></td>
										<td align="right"><?php echo number_format($row[10], 0) ?></td>
										<td align="right"><?php echo number_format($row[11], 0) ?></td>
										<td align="right"><?php echo number_format($row[12], 0) ?></td>
										<td align="right"><?php echo number_format($row[13], 0) ?></td>
										<td align="right"><?php echo number_format($row[14], 0) ?></td>
										<td align="right"><?php echo number_format($row[2] + $row[3] + $row[4] + $row[5] + $row[6] + $row[7] + $row[8] + $row[9] + $row[10] + $row[11] + $row[12] + $row[13] + $row[14], 0)?></td>
										<td align="right"><?php echo number_format($row[15], 2) ?></td>
										<td align="right"><?php echo number_format($row[16], 2) ?></td>
									  </tr>
									  <?php
									  $tcs += $row[17]; 
									  $tc_members += $row[1]; 
									  $tclass1_num += $row[2]; 
									  $tclass2_num += $row[3]; 
									  $tclass3_num += $row[4]; 
									  $tpuj_num += $row[5]; 
									  $tmch_num += $row[6]; 
									  $ttaxi_num += $row[7]; 
									  $tmb_num += $row[8]; 
									  $tbus_num += $row[9]; 
									  $tmcab_num += $row[10]; 
									  $ttruck_num += $row[11]; 
									  $ttours_num += $row[12]; 
									  $tuvx_num += $row[13]; 
									  $tbanka_num += $row[14]; 
									  $ttotal += $row[2] + $row[3] + $row[4] + $row[5] + $row[6] + $row[7] + $row[8] + $row[9] + $row[10] + $row[11] + $row[12] + $row[13] + $row[14];
									  $total_assets += $row[15]; 
									  $total_capital += $row[16]; 
								  }
								  mysqli_free_result($res);
								  ?>
								  <tr>
									<td><b>TOTAL</b></td>
									<td align="right"><b><?php echo number_format($tcs, 0) ?></b></td>
									<td align="right"><b><?php echo number_format($tc_members, 0) ?></b></td>
									<td align="right"><b><?php echo number_format($tclass1_num, 0) ?></b></td>
									<td align="right"><b><?php echo number_format($tclass2_num, 0) ?></b></td>
									<td align="right"><b><?php echo number_format($tclass3_num, 0) ?></b></td>
									<td align="right"><b><?php echo number_format($tpuj_num, 0) ?></b></td>
									<td align="right"><b><?php echo number_format($tmch_num, 0) ?></b></td>
									<td align="right"><b><?php echo number_format($ttaxi_num, 0) ?></b></td>
									<td align="right"><b><?php echo number_format($tmb_num, 0) ?></b></td>
									<td align="right"><b><?php echo number_format($tbus_num, 0) ?></b></td>
									<td align="right"><b><?php echo number_format($tmcab_num, 0) ?></b></td>
									<td align="right"><b><?php echo number_format($ttruck_num, 0) ?></b></td>
									<td align="right"><b><?php echo number_format($ttours_num, 0) ?></b></td>
									<td align="right"><b><?php echo number_format($tuvx_num, 0) ?></b></td>
									<td align="right"><b><?php echo number_format($tbanka_num, 0) ?></b></td>
									<td align="right"><b><?php echo number_format($ttotal, 0) ?></b></td>
									<td align="right"><b><?php echo number_format($total_assets, 2) ?></b></td>
									<td align="right"><b><?php echo number_format($total_capital, 2) ?></b></td>
								  </tr>
								  </tbody>
								</table>
							  </div><!-- x_content -->
							</div>
						  </div>
						  <?php
						  
						  $sql = "SELECT aid, aname FROM tc_areas";
						  $ares = query($sql); 
						  while ($arow = fetch_array($ares)) {
							  ?>
							  <div class="col-md-12 col-sm-12 col-xs-12">
								<div class="x_panel">
								  <div class="x_title">
									<h2><?php echo $arow[1] ?></h2>
									<div class="clearfix"></div>
								  </div>
								  <div class="x_content">
									<table id="datatable-fixed-header<?php echo $arow[0] ?>" class="table table-striped table-bordered jambo_table">
									  <thead>
										<tr>
										  <th width="07%" rowspan="2">Sector</th>
										  <th width="14%" rowspan="2">TC</th>
										  <th width="05%" rowspan="2">Members</th>
										  <th colspan="10">TC Vehicle Type</th>
										  <th width="05%" rowspan="2">Total Assets</th>
										  <th width="05%" rowspan="2">Total Capital</th>
										  <th width="14%" rowspan="2">Route</th>
										</tr>
										<tr>
										  <th width="04%">PUJ</th>
										  <th width="04%">MCH</th>
										  <th width="04%">Taxi</th>
										  <th width="04%">MB</th>
										  <th width="04%">Bus</th>
										  <th width="04%">M.Cab</th>
										  <th width="04%">Truck</th>
										  <th width="04%">UVEx</th>
										  <th width="04%">Banca</th>
										  <th width="04%">Total</th>
										</tr>
									  </thead>
									  <tbody>
									  <?php
									  $tc_members = 0; $tpuj_num = 0; $tmch_num = 0; $ttaxi_num = 0; 
									  $tmb_num = 0; $tbus_num = 0; $tmcab_num = 0; $ttruck_num = 0; 
									  $tuvx_num = 0; $tbanka_num = 0; $ttotal = 0; $total_capital = 0; $total_assets = 0;  
									  
									  $sql = "SELECT s.sname, c.operators_num + c.drivers_num + c.workers_num + c.others_num, 
												  c.puj_num, c.mch_num, c.taxi_num, 
												  c.mb_num, c.bus_num, c.mcab_num, 
												  c.truck_num, c.uvx_num, c.banka_num, s.sid, 
												  c.current_assets + c.fixed_assets, c.paid_up, c.cname, c.cid
											  FROM cooperatives c INNER JOIN tc_sectors s ON c.sid = s.sid
											  WHERE c.ctype = 2 AND s.aid = '$arow[0]'
											  ORDER BY s.sid, c.cname";
									  $res = query($sql); 
									  while ($row = fetch_array($res)) {
										  ?>
										  <tr>
											<td align="left"><a href="tc_sector.php?tab=4&sid=<?php echo $row[11] ?>"><?php echo $row[0] ?></a></td>
											<td align="left"><a href="tc.php?cid=<?php echo $row[15] ?>"><?php echo ucwords(strtolower($row[14])) ?></a></td>
											<td align="right"><?php echo number_format($row[1], 0) ?></td>
											<td align="right"><?php echo number_format($row[2], 0) ?></td>
											<td align="right"><?php echo number_format($row[3], 0) ?></td>
											<td align="right"><?php echo number_format($row[4], 0) ?></td>
											<td align="right"><?php echo number_format($row[5], 0) ?></td>
											<td align="right"><?php echo number_format($row[6], 0) ?></td>
											<td align="right"><?php echo number_format($row[7], 0) ?></td>
											<td align="right"><?php echo number_format($row[8], 0) ?></td>
											<td align="right"><?php echo number_format($row[9], 0) ?></td>
											<td align="right"><?php echo number_format($row[10], 0) ?></td>
											<td align="right"><?php echo number_format($row[2] + $row[3] + $row[4] + $row[5] + $row[6] + $row[7] + $row[8] + $row[9] + $row[10], 0)?></td>
											<td align="right"><?php echo number_format($row[12], 0) ?></td>
											<td align="right"><?php echo number_format($row[13], 0) ?></td>
											<td align="left"><?php
												$sql = "SELECT DISTINCT route_start, route_end 
														FROM franchises
														WHERE cid = '$row[15]'";
												$fres = query($sql); 
												while ($frow = fetch_array($fres)) {
													echo ucwords(strtolower($frow[0])).' to '.ucwords(strtolower($frow[1])).'; ';
												}
												?></td>
										  </tr>
										  <?php
										  $tc_members += $row[1]; 
										  $tpuj_num += $row[2]; 
										  $tmch_num += $row[3]; 
										  $ttaxi_num += $row[4]; 
										  $tmb_num += $row[5]; 
										  $tbus_num += $row[6]; 
										  $tmcab_num += $row[7]; 
										  $ttruck_num += $row[8]; 
										  $tuvx_num += $row[9]; 
										  $tbanka_num += $row[10]; 
										  $ttotal += $row[2] + $row[3] + $row[4] + $row[5] + $row[6] + $row[7] + $row[8] + $row[9] + $row[10];
										  $total_assets += $row[12]; 
										  $total_capital += $row[13]; 
									  }
									  mysqli_free_result($res);
									  ?>
									  <tr>
										<td align="left"></td>
										<td align="left"><b>TOTAL</b></td>
										<td align="right"><b><?php echo number_format($tc_members, 0) ?></b></td>
										<td align="right"><b><?php echo number_format($tpuj_num, 0) ?></b></td>
										<td align="right"><b><?php echo number_format($tmch_num, 0) ?></b></td>
										<td align="right"><b><?php echo number_format($ttaxi_num, 0) ?></b></td>
										<td align="right"><b><?php echo number_format($tmb_num, 0) ?></b></td>
										<td align="right"><b><?php echo number_format($tbus_num, 0) ?></b></td>
										<td align="right"><b><?php echo number_format($tmcab_num, 0) ?></b></td>
										<td align="right"><b><?php echo number_format($ttruck_num, 0) ?></b></td>
										<td align="right"><b><?php echo number_format($tuvx_num, 0) ?></b></td>
										<td align="right"><b><?php echo number_format($tbanka_num, 0) ?></b></td>
										<td align="right"><b><?php echo number_format($ttotal, 0) ?></b></td>
										<td align="right"><b><?php echo number_format($total_assets, 0) ?></b></td>
										<td align="right"><b><?php echo number_format($total_capital, 0) ?></b></td>
										<td align="left"></td>
									  </tr>
									  </tbody>
									</table>
								  </div><!-- x_content -->
								</div>
							  </div>
							  <?php
						  }
						  free_result($ares); 
					  } elseif ($tab == 14) {
						if (isset ($_GET['sortby'])) {
							$sortby = $_GET['sortby'];
						} else {
							$sortby = 1;
						}
						
						if ($sortby == 1) {
							?>
							<div class="col-md-12 col-sm-12 col-xs-12">
								<div class="x_panel">
									<div class="x_title">
										<h2>Metro Manila Area</h2>
										<ul class="nav navbar-right panel_toolbox">
											<a href="tcpdf/examples/pdf_tc_valid_cgs.php?sortby=1" target="new" class="btn btn-info btn-md" data-toggle="tooltip" data-placement="top" title="Print Report"><i class="glyphicon glyphicon-print"></i></a>
										</ul>
										<div class="clearfix"></div>
									</div>
									<div class="x_content">
									
										<table class="table table-striped table-bordered jambo_table">
										  <thead>
											  <tr>
												  <th width="10%">Area</th>
												  <th width="10%">Sector</th>
												  <th width="40%">TC Name</th>
												  <th width="10%">CGS Number</th>
												  <th width="10%">CGS Issued</th>
												  <th width="10%">CGS Expiry</th>
												  <th width="10%">Days To Expiry</th>
											  </tr>
										  </thead>
										  <tbody>
										  <?php
											$sql = "SELECT c.cid, s.sname, c.cname 
													FROM cooperatives c INNER JOIN tc_sectors s ON c.sid = s.sid
													WHERE c.ctype = 2 AND s.aid = 1 AND c.cid IN (
														SELECT DISTINCT cid 
														FROM cooperatives_cgs 
														WHERE TO_DAYS(cgs_exp) >= TO_DAYS(CURDATE())
														)
													ORDER BY s.sid, c.cname";
											$res = query($sql); 
											$num = num_rows($res); 

											while ($row = fetch_array($res)) {
												$sql = "SELECT cgs_num, 
															DATE_FORMAT(cgs_date, '%m/%d/%Y'),
															DATE_FORMAT(cgs_exp, '%m/%d/%Y'), 
															TO_DAYS(cgs_exp) - TO_DAYS(CURDATE())
														FROM cooperatives_cgs
														WHERE cid = '$row[0]'
														ORDER BY cgs_exp DESC
														LIMIT 0, 1";
												$cres = query($sql); 
												$crow = fetch_array($cres); 
												?>
												<tr>
													<td align="left">Metro Manila</td>
													<td align="left"><?php echo $row[1] ?></td>
													<td align="left"><a href="tc.php?cid=<?php echo $row[0] ?>"><?php echo ucwords(strtolower($row[2])) ?></a></td>
													<td align="center"><?php echo $crow[0] ?></td>
													<td align="center"><?php echo $crow[1] ?></td>
													<td align="center"><?php echo $crow[2] ?></td>
													<td align="center"><?php echo number_format($crow[3], 0) ?></td>
												</tr>
												<?php
											}
											mysqli_free_result($res);
										  ?>
										  </tbody>
										</table>
									
									</div><!-- x_content -->
								</div>
							</div>
							<?php
							// get the areas
							$sql = "SELECT aid, aname FROM tc_areas WHERE aid != 1";
							$ares = query($sql); 
							while ($arow = fetch_array($ares)) {
								?>
								<div class="col-md-12 col-sm-12 col-xs-12">
									<div class="x_panel">
										<div class="x_title">
											<h2><?php echo $arow[1] ?> Area</h2>
											<ul class="nav navbar-right panel_toolbox">
												<a href="tcpdf/examples/pdf_tc_valid_cgs.php?sortby=1" target="new" class="btn btn-info btn-md" data-toggle="tooltip" data-placement="top" title="Print Report"><i class="glyphicon glyphicon-print"></i></a>
											</ul>
											<div class="clearfix"></div>
										</div>
										<div class="x_content">

											<table class="table table-striped table-bordered jambo_table">
											  <thead>
												  <tr>
													  <th width="10%">Region</th>
													  <th width="10%">Province</th>
													  <th width="40%">TC Name</th>
													  <th width="10%">CGS Number</th>
													  <th width="10%">Chairman</th>
													  <th width="10%">Contact #s</th>
													  <th width="10%">Days To Expiry</th>
												  </tr>
											  </thead>
											  <tbody>
											  <?php
												$sql = "SELECT c.cid, s.sname, p.pname, c.cname 
														FROM cooperatives c 
															INNER JOIN tc_sectors s ON c.sid = s.sid
															LEFT JOIN towns t ON c.tid = t.tid
															LEFT JOIN provinces p ON t.pid = p.pid
														WHERE c.ctype = 2 AND s.aid = '$arow[0]' AND c.cid IN (
															SELECT DISTINCT cid 
															FROM cooperatives_cgs 
															WHERE TO_DAYS(cgs_exp) >= TO_DAYS(CURDATE())
															)
														ORDER BY s.sid, p.pname, c.cname";
												$res = query($sql); 
												$num = num_rows($res); 

												while ($row = fetch_array($res)) {
													$sql = "SELECT cgs_num, 
																DATE_FORMAT(cgs_date, '%m/%d/%Y'),
																DATE_FORMAT(cgs_exp, '%m/%d/%Y'), 
																TO_DAYS(cgs_exp) - TO_DAYS(CURDATE())
															FROM cooperatives_cgs
															WHERE cid = '$row[0]'
															ORDER BY cgs_exp DESC
															LIMIT 0, 1";
													$cres = query($sql); 
													$crow = fetch_array($cres); 
													?>
													<tr>
														<td align="left"><?php echo $row[1] ?></td>
														<td align="left"><?php echo $row[2] ?></td>
														<td align="left"><a href="tc.php?cid=<?php echo $row[0] ?>"><?php echo ucwords(strtolower($row[3])) ?></a></td>
														<td align="center"><?php echo $crow[0] ?></td>
														<td align="center"><?php echo $crow[1] ?></td>
														<td align="center"><?php echo $crow[2] ?></td>
														<td align="center"><?php echo number_format($crow[3], 0) ?></td>
													</tr>
													<?php
												}
												mysqli_free_result($res);
											  ?>
											  </tbody>
											</table>

										</div><!-- x_content -->
									</div>
								</div>
								<?php
							}
							free_result($ares); 
						} elseif ($sortby == 2) {
							?>
							<div class="col-md-12 col-sm-12 col-xs-12">
								<div class="x_panel">
									<div class="x_title">
										<h2>All TCs with Valid CGS</h2>
										<ul class="nav navbar-right panel_toolbox">
											<a href="tcpdf/examples/pdf_tc_valid_cgs.php?sortby=2" target="new" class="btn btn-info btn-md" data-toggle="tooltip" data-placement="top" title="Print Report"><i class="glyphicon glyphicon-print"></i></a>
										</ul>
										<div class="clearfix"></div>
									</div>
									<div class="x_content">
									
										<table class="table table-striped table-bordered jambo_table">
										  <thead>
											  <tr>
												  <th width="10%">Area</th>
												  <th width="10%">Sector</th>
												  <th width="40%">TC Name</th>
												  <th width="10%">CGS Number</th>
												  <th width="10%">CGS Issued</th>
												  <th width="10%">CGS Expiry</th>
												  <th width="10%">Days To Expiry</th>
											  </tr>
										  </thead>
										  <tbody>
										  <?php
											$sql = "SELECT c.cid, s.sname, c.cname, cgs.cgs_num, 
														DATE_FORMAT(cgs.cgs_date, '%m/%d/%Y'),
														DATE_FORMAT(cgs.cgs_exp, '%m/%d/%Y'), 
														TO_DAYS(cgs.cgs_exp) - TO_DAYS(CURDATE()), a.aname
													FROM cooperatives c 
														INNER JOIN tc_sectors s ON c.sid = s.sid
														INNER JOIN tc_areas a ON s.aid = a.aid
														INNER JOIN cooperatives_cgs cgs ON cgs.cid = c.cid
													WHERE c.ctype = 2 
														AND TO_DAYS(cgs.cgs_exp) >= TO_DAYS(CURDATE())
													ORDER BY cgs.cgs_num DESC";
											$res = query($sql); 
											$num = num_rows($res); 

											while ($row = fetch_array($res)) {
												?>
												<tr>
													<td align="left"><?php echo $row[7] ?></td>
													<td align="left"><?php echo $row[1] ?></td>
													<td align="left"><a href="tc.php?cid=<?php echo $row[0] ?>"><?php echo ucwords(strtolower($row[2])) ?></a></td>
													<td align="center"><?php echo $row[3] ?></td>
													<td align="center"><?php echo $row[4] ?></td>
													<td align="center"><?php echo $row[5] ?></td>
													<td align="center"><?php echo number_format($row[6], 0) ?></td>
												</tr>
												<?php
											}
											mysqli_free_result($res);
										  ?>
										  </tbody>
										</table>
									
									</div><!-- x_content -->
								</div>
							</div>
							<?php
						}
					  } else {
						  if (isset ($_GET['sortby'])) {
							  $sortby = $_GET['sortby'];
						  } else {
							  $sortby = 1;
						  }
						  
						  if ($tab == 1) { $ctype = 2; 
						  } elseif ($tab == 2) { $ctype = 1;
						  } elseif ($tab == 3) { $ctype = 0; 
						  }
						  ?>
						  <div class="col-md-12 col-sm-12 col-xs-12">
							<div class="x_panel">
							  <div class="x_title">
								<a href="tcpdf/examples/pdf_tc_reports.php?type=0&ctype=<?php echo $ctype ?>&sortby=<?php echo $sortby ?>" target="new" class="btn btn-info btn-md" data-toggle="tooltip" data-placement="top" title="Print Report"><i class="glyphicon glyphicon-print"></i> Print </a>
								<a href="add_tc.php" class="btn btn-primary btn-md"><i class="fa fa-plus-square"></i> Add New </a>
								<ul class="nav navbar-right panel_toolbox">
								  <select class="form-control col-md-7 col-xs-12" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
								    <?php
									if ($sortby == 1) {
										?>
										<option value="tcs.php?tab=<?php echo $tab ?>&sortby=1" Selected>Sort By: TC Name</option>
										<option value="tcs.php?tab=<?php echo $tab ?>&sortby=2">Sort By: Area & Sectors</option>
										<option value="tcs.php?tab=<?php echo $tab ?>&sortby=3">Sort By: Region & Province</option>
										<option value="tcs.php?tab=<?php echo $tab ?>&sortby=4">Sort By: OTC Accreditation Date</option>
										<?php
									} elseif ($sortby == 2) {
										?>
										<option value="tcs.php?tab=<?php echo $tab ?>&sortby=1">Sort By: TC Name</option>
										<option value="tcs.php?tab=<?php echo $tab ?>&sortby=2" Selected>Sort By: Area & Sectors</option>
										<option value="tcs.php?tab=<?php echo $tab ?>&sortby=3">Sort By: Region & Province</option>
										<option value="tcs.php?tab=<?php echo $tab ?>&sortby=4">Sort By: OTC Accreditation Date</option>
										<?php
									} elseif ($sortby == 3) {
										?>
										<option value="tcs.php?tab=<?php echo $tab ?>&sortby=1">Sort By: TC Name</option>
										<option value="tcs.php?tab=<?php echo $tab ?>&sortby=2">Sort By: Area & Sectors</option>
										<option value="tcs.php?tab=<?php echo $tab ?>&sortby=3" Selected>Sort By: Region & Province</option>
										<option value="tcs.php?tab=<?php echo $tab ?>&sortby=4">Sort By: OTC Accreditation Date</option>
										<?php
									} elseif ($sortby == 4) {
										?>
										<option value="tcs.php?tab=<?php echo $tab ?>&sortby=1">Sort By: TC Name</option>
										<option value="tcs.php?tab=<?php echo $tab ?>&sortby=2">Sort By: Area & Sectors</option>
										<option value="tcs.php?tab=<?php echo $tab ?>&sortby=3">Sort By: Region & Province</option>
										<option value="tcs.php?tab=<?php echo $tab ?>&sortby=4" Selected>Sort By: OTC Accreditation Date</option>
										<?php
									} else {
										?>
										<option value="tcs.php?tab=<?php echo $tab ?>&sortby=1">Sort By: TC Name</option>
										<option value="tcs.php?tab=<?php echo $tab ?>&sortby=2">Sort By: Area & Sectors</option>
										<option value="tcs.php?tab=<?php echo $tab ?>&sortby=3">Sort By: Region & Province</option>
										<option value="tcs.php?tab=<?php echo $tab ?>&sortby=4">Sort By: OTC Accreditation Date</option>
										<?php
									}
									?>
								  </select>
								</ul>
								<div class="clearfix"></div>
							  </div>
							  <div class="x_content">
							    <?php
								if ($sortby == 1) {
									?>
									<table class="table table-striped table-bordered jambo_table">
									  <thead>
										  <tr>
											  <th width="08%" rowspan="2">Sector</th>
											  <th width="15%" rowspan="2">TC Name</th>
											  <th width="25%" rowspan="2">Address</th>
											  <th width="10%" rowspan="2">Chairman</th>
											  <th width="10%" rowspan="2">Contact #s</th>
											  <th colspan="2">OTC</th>
											  <th colspan="2">CDA</th>
										  </tr>
										  <tr>
											  <th width="08%">Acc.#</th>
											  <th width="08%">Acc. Date</th>
											  <th width="08%">Reg.#</th>
											  <th width="80%">Reg. Date</th>
										  </tr>
									  </thead>
									  <tbody>
									  <?php
										$sql = "SELECT cid, cname, addr, chairman, chairman_num, contact_num, 
													new_otc_num, DATE_FORMAT(new_otc_date, '%m/%d/%y'), 
													new_cda_num, DATE_FORMAT(new_cda_date, '%m/%d/%y'), s.sname
												FROM cooperatives c INNER JOIN tc_sectors s ON c.sid = s.sid
												WHERE c.ctype = '$ctype'
												ORDER BY c.cname";
										$res = query($sql); 
										$num = num_rows($res); 

										while ($row = fetch_array($res)) {
											?>
											<tr>
												<td align="left"><?php echo $row[10] ?></td>
												<td align="left"><a href="tc.php?cid=<?php echo $row[0] ?>"><?php echo ucwords(strtolower($row[1])) ?></a></td>
												<td align="left"><?php echo ucwords(strtolower($row[2])) ?></td>
												<td align="left"><?php echo ucwords(strtolower($row[3])).'<br />'.$row[4] ?></td>
												<td align="left"><?php echo $row[5] ?></td>
												<td align="center"><?php echo $row[6] ?></td>
												<td align="center"><?php echo $row[7] ?></td>
												<td align="center"><?php echo $row[8] ?></td>
												<td align="center"><?php echo $row[9] ?></td>
											</tr>
											<?php
										}
										mysqli_free_result($res);
									  ?>
									  <tr>
									    <td colspan="2" align="right"><b>Total Number</b></td>
										<td align="right"><b><?php echo number_format($num, 0) ?></b></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
									  </tr>
									  </tbody>
									</table>
									<?php
								} elseif ($sortby == 2) {
									// get the areas
									$sql = "SELECT aid, aname FROM tc_areas";
									$ares = query($sql); 
									while ($arow = fetch_array($ares)) {
										?>
										<table><tr><td><h2><?php echo $arow[1] ?> Area</h2></td></tr></table>
										<table class="table table-striped table-bordered jambo_table">
										  <thead>
											  <tr>
												  <th width="08%" rowspan="2">Sector</th>
												  <th width="15%" rowspan="2">TC Name</th>
												  <th width="25%" rowspan="2">Address</th>
												  <th width="10%" rowspan="2">Chairman</th>
												  <th width="10%" rowspan="2">Contact #s</th>
												  <th colspan="2">OTC</th>
												  <th colspan="2">CDA</th>
											  </tr>
											  <tr>
												  <th width="08%">Acc.#</th>
												  <th width="08%">Acc. Date</th>
												  <th width="08%">Reg.#</th>
												  <th width="80%">Reg. Date</th>
											  </tr>
										  </thead>
										  <tbody>
										  <?php
											$sql = "SELECT cid, cname, addr, chairman, chairman_num, contact_num, 
														new_otc_num, DATE_FORMAT(new_otc_date, '%m/%d/%y'), 
														new_cda_num, DATE_FORMAT(new_cda_date, '%m/%d/%y'), s.sname
													FROM cooperatives c INNER JOIN tc_sectors s ON c.sid = s.sid
													WHERE c.ctype = '$ctype' AND s.aid = '$arow[0]'
													ORDER BY s.sid, c.cname";
											$res = query($sql); 
											$num = num_rows($res); 

											while ($row = fetch_array($res)) {
												?>
												<tr>
													<td align="left"><?php echo $row[10] ?></td>
													<td align="left"><a href="tc.php?cid=<?php echo $row[0] ?>"><?php echo ucwords(strtolower($row[1])) ?></a></td>
													<td align="left"><?php echo ucwords(strtolower($row[2])) ?></td>
													<td align="left"><?php echo ucwords(strtolower($row[3])).'<br />'.$row[4] ?></td>
													<td align="left"><?php echo $row[5] ?></td>
													<td align="center"><?php echo $row[6] ?></td>
													<td align="center"><?php echo $row[7] ?></td>
													<td align="center"><?php echo $row[8] ?></td>
													<td align="center"><?php echo $row[9] ?></td>
												</tr>
												<?php
											}
											mysqli_free_result($res);
										  ?>
										  <tr>
											<td colspan="2" align="right"><b>Total Number</b></td>
											<td align="right"><b><?php echo number_format($num, 0) ?></b></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
										  </tr>
										  </tbody>
										</table>
										<?php
									}
									free_result($ares); 
								} elseif ($sortby == 3) {
									?>
									<table><tr><td><h2>Metro Manila Area</h2></td></tr></table>
									<table class="table table-striped table-bordered jambo_table">
									  <thead>
										  <tr>
											  <th width="10%">Area</th>
											  <th width="10%">Sector</th>
											  <th width="20%">TC Name</th>
											  <th width="30%">Address</th>
											  <th width="15%">Chairman</th>
											  <th width="15%">Contact #s</th>
										  </tr>
									  </thead>
									  <tbody>
									  <?php
										$sql = "SELECT c.cid, s.sname, c.cname, c.addr, c.chairman, c.chairman_num, c.contact_num
												FROM cooperatives c 
													INNER JOIN tc_sectors s ON c.sid = s.sid
												WHERE c.ctype = '$ctype' AND s.aid = 1
												ORDER BY s.sid, c.cname";
										$res = query($sql); 
										$num = num_rows($res); 

										while ($row = fetch_array($res)) {
											?>
											<tr>
												<td align="left">Metro Manila</td>
												<td align="left"><?php echo $row[1] ?></td>
												<td align="left"><a href="tc.php?cid=<?php echo $row[0] ?>"><?php echo ucwords(strtolower($row[2])) ?></a></td>
												<td align="left"><?php echo ucwords(strtolower($row[3])) ?></td>
												<td align="left"><?php echo ucwords(strtolower($row[4])) ?></td>
												<td align="left"><?php echo $row[5].'<br />'.$row[6] ?></td>
											</tr>
											<?php
										}
										mysqli_free_result($res);
									  ?>
									  </tbody>
									</table>
									<?php
									// get the areas
									$sql = "SELECT aid, aname FROM tc_areas WHERE aid != 1";
									$ares = query($sql); 
									while ($arow = fetch_array($ares)) {
										?>
										<table><tr><td><h2><?php echo $arow[1] ?> Area</h2></td></tr></table>
										<table class="table table-striped table-bordered jambo_table">
										  <thead>
											  <tr>
												  <th width="10%">Region</th>
												  <th width="10%">Province</th>
												  <th width="20%">TC Name</th>
												  <th width="30%">Address</th>
												  <th width="15%">Chairman</th>
												  <th width="15%">Contact #s</th>
											  </tr>
										  </thead>
										  <tbody>
										  <?php
											$sql = "SELECT c.cid, s.sname, p.pname, c.cname, c.addr, c.chairman, c.chairman_num, c.contact_num
													FROM cooperatives c 
														INNER JOIN tc_sectors s ON c.sid = s.sid
														LEFT JOIN towns t ON c.tid = t.tid
														LEFT JOIN provinces p ON t.pid = p.pid
													WHERE c.ctype = '$ctype' AND s.aid = '$arow[0]'
													ORDER BY s.sid, p.pname, c.cname";
											$res = query($sql); 
											$num = num_rows($res); 

											while ($row = fetch_array($res)) {
												?>
												<tr>
													<td align="left"><?php echo $row[1] ?></td>
													<td align="left"><?php echo $row[2] ?></td>
													<td align="left"><a href="tc.php?cid=<?php echo $row[0] ?>"><?php echo ucwords(strtolower($row[3])) ?></a></td>
													<td align="left"><?php echo ucwords(strtolower($row[4])) ?></td>
													<td align="left"><?php echo ucwords(strtolower($row[5])).'<br />'.$row[6] ?></td>
													<td align="left"><?php echo $row[7] ?></td>
												</tr>
												<?php
											}
											mysqli_free_result($res);
										  ?>
										  </tbody>
										</table>
										<?php
									}
									free_result($ares); 
								} elseif ($sortby == 4) {
									?>
									<table class="table table-striped table-bordered jambo_table">
									  <thead>
										  <tr>
											  <th width="08%" rowspan="2">Sector</th>
											  <th width="15%" rowspan="2">TC Name</th>
											  <th width="25%" rowspan="2">Address</th>
											  <th width="10%" rowspan="2">Chairman</th>
											  <th width="10%" rowspan="2">Contact #s</th>
											  <th colspan="2">OTC</th>
											  <th colspan="2">CDA</th>
										  </tr>
										  <tr>
											  <th width="08%">Acc.#</th>
											  <th width="08%">Acc. Date</th>
											  <th width="08%">Reg.#</th>
											  <th width="80%">Reg. Date</th>
										  </tr>
									  </thead>
									  <tbody>
									  <?php
										$sql = "SELECT cid, cname, addr, chairman, chairman_num, contact_num, 
													new_otc_num, DATE_FORMAT(new_otc_date, '%m/%d/%y'), 
													new_cda_num, DATE_FORMAT(new_cda_date, '%m/%d/%y'), s.sname
												FROM cooperatives c INNER JOIN tc_sectors s ON c.sid = s.sid
												WHERE c.ctype = '$ctype'
												ORDER BY c.new_otc_date DESC, c.cname";
										$res = query($sql); 
										$num = num_rows($res); 

										while ($row = fetch_array($res)) {
											?>
											<tr>
												<td align="left"><?php echo $row[10] ?></td>
												<td align="left"><a href="tc.php?cid=<?php echo $row[0] ?>"><?php echo ucwords(strtolower($row[1])) ?></a></td>
												<td align="left"><?php echo ucwords(strtolower($row[2])) ?></td>
												<td align="left"><?php echo ucwords(strtolower($row[3])).'<br />'.$row[4] ?></td>
												<td align="left"><?php echo $row[5] ?></td>
												<td align="center"><?php echo $row[6] ?></td>
												<td align="center"><?php echo $row[7] ?></td>
												<td align="center"><?php echo $row[8] ?></td>
												<td align="center"><?php echo $row[9] ?></td>
											</tr>
											<?php
										}
										mysqli_free_result($res);
									  ?>
									  <tr>
									    <td colspan="2" align="right"><b>Total Number</b></td>
										<td align="right"><b><?php echo number_format($num, 0) ?></b></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
									  </tr>
									  </tbody>
									</table>
									<?php
								}
								?>
							  </div><!-- x_content -->
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
			<!-- FastClick -->
			<script src="vendors/fastclick/lib/fastclick.js"></script>
			<!-- NProgress -->
			<script src="vendors/nprogress/nprogress.js"></script>
			<!-- iCheck -->
			<script src="vendors/iCheck/icheck.min.js"></script>
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
			<script src="vendors/jszip/dist/jszip.min.js"></script>
			<script src="vendors/pdfmake/build/pdfmake.min.js"></script>
			<script src="vendors/pdfmake/build/vfs_fonts.js"></script>

			<!-- Custom Theme Scripts -->
			<script src="build/js/custom.min.js"></script>

		  </body>
		</html>
		<?php
	}
} else {
	header('Location: login.php');	
}
