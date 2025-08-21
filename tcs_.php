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
						if ($tab == 3) { echo 'TC Members, Assets, Capital & Routes';
						} else echo 'Transportation Cooperatives';
						?>
						</h3>
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
					  if (isset($_GET['success'])) {
						  if ($_GET['success'] == 1) {
							  ?>
								<div class="alert alert-success">
								  <strong>Success!</strong> Cooperative has been added into the database.
								</div>
							  <?php
						  }
					  }
					  
					  if ($tab == 1) {
						  ?>
						  <div class="col-md-12 col-sm-12 col-xs-12">
							<div class="x_panel">
							  <div class="x_title">
								<h2>Transportation Cooperatives Directory</h2>
								<ul class="nav navbar-right panel_toolbox">
									<a href="add_tc.php" class="btn btn-primary btn-md"><i class="fa fa-plus-square"></i> Add Cooperative </a>
								</ul>
								<div class="clearfix"></div>
							  </div>
							  <div class="x_content">
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
										  <th width="08%">Acc.#</th>
										  <th width="80%">Acc. Date</th>
									  </tr>
								  </thead>
								  <tbody>
								  <?php
									$sql = "SELECT cid, cname, addr, chairman, chairman_num, contact_num, 
												new_otc_num, DATE_FORMAT(new_otc_date, '%m/%d/%y'), 
												new_cda_num, DATE_FORMAT(new_cda_date, '%m/%d/%y'), s.sname
											FROM cooperatives c INNER JOIN tc_sectors s ON c.sid = s.sid
											ORDER BY s.sid, c.cname";
									$res = query($sql);

									while ($row = fetch_array($res)) {
										?>
										<tr>
											<td align="left"><?php echo $row[10] ?></td>
											<td align="left"><a href="tc.php?cid=<?php echo $row[0] ?>"><?php echo $row[1] ?></a></td>
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
								  </tbody>
								</table>
							  </div><!-- x_content -->
						  <?php
					  } elseif ($tab == 2) {
						  
						  if (isset($_GET['aid'])) {
							  $aid = $_GET['aid'];
						  } else {
							  $aid = 1; 
						  }
						  
						  $sql = "SELECT aname FROM tc_areas WHERE aid = '$aid'";
						  $ares = query($sql); 
						  $arow = fetch_array($ares); 
						  ?>
						  <div class="col-md-12 col-sm-12 col-xs-12">
							<div class="x_panel">
							  <div class="x_title">
								<h2><?php echo $arow[0] ?></h2>
								<ul class="nav navbar-right panel_toolbox">
									<select class="form-control col-md-7 col-xs-12" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
										<option value="tcs.php?tab=2&aid=0">Not Assigned</option>
										<?php
										$sql = "SELECT aid, aname FROM tc_areas ORDER BY aid";
										$tres = query($sql); 
										while ($trow = fetch_array($tres)) {
											if ($aid == $trow[0]) {
												?><option value="tcs.php?tab=2&aid=<?php echo $trow[0] ?>" Selected><?php echo $trow[1] ?></option><?php
											} else {
												?><option value="tcs.php?tab=2&aid=<?php echo $trow[0] ?>"><?php echo $trow[1] ?></option><?php
											}
										}
										free_result($tres); 
										?>
									</select>
								</ul>
								<div class="clearfix"></div>
							  </div>
							  <div class="x_content">
								<table id="datatable-fixed-header" class="table table-striped table-bordered jambo_table">
								  <thead>
									<tr>
									  <th width="10%" rowspan="2">Sector</th>
									  <th width="30%" rowspan="2">Cooperative</th>
									  <th colspan="10">TC Vehicle Type</th>
									</tr>
									<tr>
									  <th width="06%">PUJ</th>
									  <th width="06%">MCH</th>
									  <th width="06%">Taxi</th>
									  <th width="06%">MB</th>
									  <th width="06%">Bus</th>
									  <th width="06%">M.Cab</th>
									  <th width="06%">Truck</th>
									  <th width="06%">AUV</th>
									  <th width="06%">Banca</th>
									  <th width="06%">Total</th>
									</tr>
								  </thead>
								  <tbody>
								  <?php
								  if ($aid != 0) {
									  $sql = "SELECT c.cid, s.sname, c.cname, c.puj_num, c.mch_num, 
												  c.taxi_num, c.mb_num, c.bus_num, c.mcab_num, 
												  c.truck_num, c.auv_num, c.banka_num
											  FROM cooperatives c
												  INNER JOIN tc_sectors s ON c.sid = s.sid
											  WHERE s.aid = '$aid'
											  ORDER BY s.sid, c.cname";
								  } else {
									  $sql = "SELECT c.cid, s.sname, c.cname, c.puj_num, c.mch_num, 
												  c.taxi_num, c.mb_num, c.bus_num, c.mcab_num, 
												  c.truck_num, c.auv_num, c.banka_num
											  FROM cooperatives c
												  LEFT JOIN tc_sectors s ON c.sid = s.sid
											  WHERE c.sid = 0
											  ORDER BY s.sid, c.cname";
								  }
								  $res = query($sql); 
								  while ($row = fetch_array($res)) {
									  ?>
									  <tr>
										<td><?php echo $row[1] ?></td>
										<td><a href="tc.php?cid=<?php echo $row[0] ?>"><?php echo $row[2] ?></a></td>
										<td align="right"><?php echo $row[3] ?></td>
										<td align="right"><?php echo $row[4] ?></td>
										<td align="right"><?php echo $row[5] ?></td>
										<td align="right"><?php echo $row[6] ?></td>
										<td align="right"><?php echo $row[7] ?></td>
										<td align="right"><?php echo $row[8] ?></td>
										<td align="right"><?php echo $row[9] ?></td>
										<td align="right"><?php echo $row[10] ?></td>
										<td align="right"><?php echo $row[11] ?></td>
										<td align="right"><?php echo $row[3] + $row[4] + $row[5] + $row[6] + $row[7] + $row[8] + $row[9] + $row[10] + $row[11] ?></td>
									  </tr>
									  <?php
								  }
								  ?>
								  </tbody>
								</table>
							  </div><!-- x_content -->
							</div>
						  </div>
						  <?php
						  mysqli_free_result($ares);
					  } elseif ($tab == 3) {
						  if (isset ($_GET['sortby'])) {
							  $sortby = $_GET['sortby'];
						  } else {
							  $sortby = 1; 
						  }
						  ?>
						  <div class="col-md-12 col-sm-12 col-xs-12">
							<div class="x_panel">
							  <div class="x_title">
								<h2>Members, Assets and Capital</h2>
								<ul class="nav navbar-right panel_toolbox">
									<select class="form-control col-md-7 col-xs-12" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
										<?php
										if ($sortby == 1) {
											?>
											<option value="tcs.php?tab=3&sortby=1" Selected>Desc. Members</option>
											<option value="tcs.php?tab=3&sortby=2">Asc. Members</option>
											<option value="tcs.php?tab=3&sortby=3">Desc. Assets</option>
											<option value="tcs.php?tab=3&sortby=4">Asc. Assets</option>
											<option value="tcs.php?tab=3&sortby=5">Desc. Capital</option>
											<option value="tcs.php?tab=3&sortby=6">Asc. Capital</option>
											<?php
										} elseif ($sortby == 2) {
											?>
											<option value="tcs.php?tab=3&sortby=1">Desc. Members</option>
											<option value="tcs.php?tab=3&sortby=2" Selected>Asc. Members</option>
											<option value="tcs.php?tab=3&sortby=3">Desc. Assets</option>
											<option value="tcs.php?tab=3&sortby=4">Asc. Assets</option>
											<option value="tcs.php?tab=3&sortby=5">Desc. Capital</option>
											<option value="tcs.php?tab=3&sortby=6">Asc. Capital</option>
											<?php
										} elseif ($sortby == 3) {
											?>
											<option value="tcs.php?tab=3&sortby=1">Desc. Members</option>
											<option value="tcs.php?tab=3&sortby=2">Asc. Members</option>
											<option value="tcs.php?tab=3&sortby=3" Selected>Desc. Assets</option>
											<option value="tcs.php?tab=3&sortby=4">Asc. Assets</option>
											<option value="tcs.php?tab=3&sortby=5">Desc. Capital</option>
											<option value="tcs.php?tab=3&sortby=6">Asc. Capital</option>
											<?php
										} elseif ($sortby == 4) {
											?>
											<option value="tcs.php?tab=3&sortby=1">Desc. Members</option>
											<option value="tcs.php?tab=3&sortby=2">Asc. Members</option>
											<option value="tcs.php?tab=3&sortby=3">Desc. Assets</option>
											<option value="tcs.php?tab=3&sortby=4" Selected>Asc. Assets</option>
											<option value="tcs.php?tab=3&sortby=5">Desc. Capital</option>
											<option value="tcs.php?tab=3&sortby=6">Asc. Capital</option>
											<?php
										} elseif ($sortby == 5) {
											?>
											<option value="tcs.php?tab=3&sortby=1">Desc. Members</option>
											<option value="tcs.php?tab=3&sortby=2">Asc. Members</option>
											<option value="tcs.php?tab=3&sortby=3">Desc. Assets</option>
											<option value="tcs.php?tab=3&sortby=4">Asc. Assets</option>
											<option value="tcs.php?tab=3&sortby=5" Selected>Desc. Capital</option>
											<option value="tcs.php?tab=3&sortby=6">Asc. Capital</option>
											<?php
										} elseif ($sortby == 6) {
											?>
											<option value="tcs.php?tab=3&sortby=1">Desc. Members</option>
											<option value="tcs.php?tab=3&sortby=2">Asc. Members</option>
											<option value="tcs.php?tab=3&sortby=3">Desc. Assets</option>
											<option value="tcs.php?tab=3&sortby=4">Asc. Assets</option>
											<option value="tcs.php?tab=3&sortby=5">Desc. Capital</option>
											<option value="tcs.php?tab=3&sortby=6" Selected>Asc. Capital</option>
											<?php
										}
										?>
									</select>
								</ul>
								<div class="clearfix"></div>
							  </div>
							  <div class="x_content">
								<table class="table table-striped table-bordered jambo_table">
								  <thead>
									<tr>
									  <th width="10%">Sector</th>
									  <th width="25%">Cooperative</th>
									  <th width="08%">Members</th>
									  <th width="10%">Total Assets</th>
									  <th width="10%">Total Capital</th>
									  <th width="37%">Route/s</th>
									</tr>
								  </thead>
								  <tbody>
								  <?php
								  if ($sortby == 1) {
									  $sql = "SELECT c.cid, s.sname, c.cname, c.members_num, c.assets, c.capital, c.route
											  FROM cooperatives c
												  INNER JOIN tc_sectors s ON c.sid = s.sid
											  ORDER BY c.members_num DESC";
								  } elseif ($sortby == 2) { 
									  $sql = "SELECT c.cid, s.sname, c.cname, c.members_num, c.assets, c.capital, c.route
											  FROM cooperatives c
												  INNER JOIN tc_sectors s ON c.sid = s.sid
											  ORDER BY c.members_num";
								  } elseif ($sortby == 3) { 
									  $sql = "SELECT c.cid, s.sname, c.cname, c.members_num, c.assets, c.capital, c.route
											  FROM cooperatives c
												  INNER JOIN tc_sectors s ON c.sid = s.sid
											  ORDER BY c.assets DESC";
								  } elseif ($sortby == 4) { 
									  $sql = "SELECT c.cid, s.sname, c.cname, c.members_num, c.assets, c.capital, c.route
											  FROM cooperatives c
												  INNER JOIN tc_sectors s ON c.sid = s.sid
											  ORDER BY c.assets";
								  } elseif ($sortby == 5) { 
									  $sql = "SELECT c.cid, s.sname, c.cname, c.members_num, c.assets, c.capital, c.route
											  FROM cooperatives c
												  INNER JOIN tc_sectors s ON c.sid = s.sid
											  ORDER BY c.capital DESC";
								  } elseif ($sortby == 6) { 
									  $sql = "SELECT c.cid, s.sname, c.cname, c.members_num, c.assets, c.capital, c.route
											  FROM cooperatives c
												  INNER JOIN tc_sectors s ON c.sid = s.sid
											  ORDER BY c.capital";
								  }
								  $res = query($sql); 
								  while ($row = fetch_array($res)) {
									  ?>
									  <tr>
										<td><?php echo $row[1] ?></td>
										<td><a href="tc.php?cid=<?php echo $row[0] ?>"><?php echo $row[2] ?></a></td>
										<td align="right"><?php echo number_format($row[3], 0) ?></td>
										<td align="right"><?php echo number_format($row[4], 2) ?></td>
										<td align="right"><?php echo number_format($row[5], 2) ?></td>
										<td><?php echo $row[6] ?></td>
									  </tr>
									  <?php
								  }
								  ?>
								  </tbody>
								</table>
							  </div><!-- x_content -->
							</div>
						  </div>
						  <?php
						  mysqli_free_result($ares);
					  } elseif ($tab == 4) {
					  } elseif ($tab == 5) {
					  } elseif ($tab == 6) {
						  if (isset ($_GET['period'])) {
							  $period = $_GET['period'];
						  } else {
							  $period = 1; 
						  }
						  ?>
						  <div class="col-md-12 col-sm-12 col-xs-12">
							<div class="x_panel">
							  <div class="x_title">
								<h2>Certificate of Good Standing</h2>
								<ul class="nav navbar-right panel_toolbox">
									<select class="form-control col-md-7 col-xs-12" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
										<?php
										if ($period == 1) {
											?>
											<option value="tcs.php?tab=6&period=1" Selected>2010 - 2012</option>
											<option value="tcs.php?tab=6&period=2">2013 - 2015</option>
											<option value="tcs.php?tab=6&period=3">2016 - 2018</option>
											<option value="tcs.php?tab=6&period=4">2019 - 2021</option>
											<?php
										} elseif ($period == 2) {
											?>
											<option value="tcs.php?tab=6&period=1">2010 - 2012</option>
											<option value="tcs.php?tab=6&period=2" Selected>2013 - 2015</option>
											<option value="tcs.php?tab=6&period=3">2016 - 2018</option>
											<option value="tcs.php?tab=6&period=4">2019 - 2021</option>
											<?php
										} elseif ($period == 3) {
											?>
											<option value="tcs.php?tab=6&period=1">2010 - 2012</option>
											<option value="tcs.php?tab=6&period=2">2013 - 2015</option>
											<option value="tcs.php?tab=6&period=3" Selected>2016 - 2018</option>
											<option value="tcs.php?tab=6&period=4">2019 - 2021</option>
											<?php
										} elseif ($period == 4) {
											?>
											<option value="tcs.php?tab=6&period=1">2010 - 2012</option>
											<option value="tcs.php?tab=6&period=2">2013 - 2015</option>
											<option value="tcs.php?tab=6&period=3">2016 - 2018</option>
											<option value="tcs.php?tab=6&period=4" Selected>2019 - 2021</option>
											<?php
										}
										?>
									</select>
								</ul>
								<div class="clearfix"></div>
							  </div>
							  <div class="x_content">
								<table class="table table-striped table-bordered jambo_table">
								  <thead>
									  <tr>
										  <th width="40%" rowspan="2">TC Name</th>
										  <?php
										  if ($period == 1) {
											  ?>
											  <th width="20%" colspan="3">2010</th>
											  <th width="20%" colspan="3">2011</th>
											  <th width="20%" colspan="3">2012</th>
											  <?php
										  } elseif ($period == 2) {
											  ?>
											  <th width="20%" colspan="3">2013</th>
											  <th width="20%" colspan="3">2014</th>
											  <th width="20%" colspan="3">2015</th>
											  <?php
										  } elseif ($period == 3) {
											  ?>
											  <th width="20%" colspan="3">2016</th>
											  <th width="20%" colspan="3">2017</th>
											  <th width="20%" colspan="3">2018</th>
											  <?php
										  } elseif ($period == 4) {
											  ?>
											  <th width="20%" colspan="3">2019</th>
											  <th width="20%" colspan="3">2020</th>
											  <th width="20%" colspan="3">2021</th>
											  <?php
										  }
										  ?>
									  </tr>
									  <tr>
										  <th>CGS #</th>
										  <th>Issued</th>
										  <th>Expiry</th>
										  <th>CGS #</th>
										  <th>Issued</th>
										  <th>Expiry</th>
										  <th>CGS #</th>
										  <th>Issued</th>
										  <th>Expiry</th>
									  </tr>
								  </thead>
								  <tbody>
								  <?php
									$sql = "SELECT cid, cname
											FROM cooperatives
											ORDER BY cname";
									$res = query($sql);

									while ($row = fetch_array($res)) {
										?>
										<tr>
										  <td align="left"><a href="tc.php?cid=<?php echo $row[0] ?>"><?php echo $row[1] ?></a></td>
										  <?php
										  if ($period == 1) {
											  $low = 2010; $high = 2012;
										  } elseif ($period == 2) {
											  $low = 2013; $high = 2015;
										  } elseif ($period == 3) {
											  $low = 2016; $high = 2018;
										  } elseif ($period == 4) {
											  $low = 2019; $high = 2021;
										  }
										  
										  for ($i=$low; $i<$high+1; $i++) {
											  $sql = "SELECT cgs_num, DATE_FORMAT(cgs_date, '%m/%d/%y'), DATE_FORMAT(cgs_exp, '%m/%d/%y')
													  FROM cooperatives_cgs
													  WHERE cid = '$row[0]' AND YEAR(cgs_date) = '$i'";
											  $cres = query($sql); 
											  $cnum = num_rows($cres); 
											  if ($cnum > 0) {
												  while ($crow = fetch_array($cres)) {
													  ?>
													  <td align="center"><?php echo $crow[0] ?></td>
													  <td align="center"><?php echo $crow[1] ?></td>
													  <td align="center"><?php echo $crow[2] ?></td>
													  <?php
												  }
												  mysqli_free_result($cres);
											  } else {
												  ?>
												  <td>&nbsp;</td>
												  <td>&nbsp;</td>
												  <td>&nbsp;</td>
												  <?php
											  }
										  }
										  ?>
										</tr>
										<?php
									}
									mysqli_free_result($res);
								  ?>
								  </tbody>
								</table>
							  </div><!-- x_content -->
						  <?php
					  } elseif ($tab == 7) {
					  } elseif ($tab == 8) {
						  ?>
						  <div class="col-md-12 col-sm-12 col-xs-12">
							<div class="x_panel">
							  <div class="x_title">
								<h2>National</h2>
								<ul class="nav navbar-right panel_toolbox">
								  <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
								  <li><a class="close-link"><i class="fa fa-close"></i></a></li>
								</ul>
								<div class="clearfix"></div>
							  </div>
							  <div class="x_content">
								<table class="table table-striped table-bordered jambo_table">
								  <thead>
									<tr>
									  <th width="16%" rowspan="2">Area</th>
									  <th width="07%" rowspan="2">Number of TCs</th>
									  <th width="07%" rowspan="2">Number of Members</th>
									  <th colspan="10">TC Vehicle Type</th>
									</tr>
									<tr>
									  <th width="07%">PUJ</th>
									  <th width="07%">MCH</th>
									  <th width="07%">Taxi</th>
									  <th width="07%">MB</th>
									  <th width="07%">Bus</th>
									  <th width="07%">M.Cab</th>
									  <th width="07%">Truck</th>
									  <th width="07%">AUV</th>
									  <th width="07%">Banca</th>
									  <th width="07%">Total</th>
									</tr>
								  </thead>
								  <tbody>
								  <?php
								  $tc_num = 0; $tmembers_num = 0; $tpuj_num = 0; $tmch_num = 0; $ttaxi_num = 0; 
								  $tmb_num = 0; $tbus_num = 0; $tmcab_num = 0; $ttruck_num = 0; 
								  $tauv_num = 0; $tbanka_num = 0; $ttotal = 0; 
								  
								  $sql = "SELECT a.aname, COUNT(c.cid), SUM(c.members_num), 
											  SUM(c.puj_num), SUM(c.mch_num), SUM(c.taxi_num), 
											  SUM(c.mb_num), SUM(c.bus_num), SUM(c.mcab_num), 
											  SUM(c.truck_num), SUM(c.auv_num), SUM(c.banka_num)
										  FROM cooperatives c 
											  INNER JOIN tc_sectors s ON c.sid = s.sid
											  INNER JOIN tc_areas a ON s.aid = a.aid
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
										<td align="right"><?php echo number_format($row[3] + $row[4] + $row[5] + $row[6] + $row[7] + $row[8] + $row[9] + $row[10] + $row[11], 0)?></td>
									  </tr>
									  <?php
									  $tc_num += $row[1]; 
									  $tmembers_num += $row[2]; 
									  $tpuj_num += $row[3]; 
									  $tmch_num += $row[4]; 
									  $ttaxi_num += $row[5]; 
									  $tmb_num += $row[6]; 
									  $tbus_num += $row[7]; 
									  $tmcab_num += $row[8]; 
									  $ttruck_num += $row[9]; 
									  $tauv_num += $row[10]; 
									  $tbanka_num += $row[11]; 
									  $ttotal += $row[3] + $row[4] + $row[5] + $row[6] + $row[7] + $row[8] + $row[9] + $row[10] + $row[11];
								  }
								  mysqli_free_result($res);
								  ?>
								  <tr>
									<td><b>TOTAL</b></td>
									<td align="right"><b><?php echo number_format($tc_num, 0) ?></b></td>
									<td align="right"><b><?php echo number_format($tmembers_num, 0) ?></b></td>
									<td align="right"><b><?php echo number_format($tpuj_num, 0) ?></b></td>
									<td align="right"><b><?php echo number_format($tmch_num, 0) ?></b></td>
									<td align="right"><b><?php echo number_format($ttaxi_num, 0) ?></b></td>
									<td align="right"><b><?php echo number_format($tmb_num, 0) ?></b></td>
									<td align="right"><b><?php echo number_format($tbus_num, 0) ?></b></td>
									<td align="right"><b><?php echo number_format($tmcab_num, 0) ?></b></td>
									<td align="right"><b><?php echo number_format($ttruck_num, 0) ?></b></td>
									<td align="right"><b><?php echo number_format($tauv_num, 0) ?></b></td>
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
									  <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
									  <li><a class="close-link"><i class="fa fa-close"></i></a></li>
									</ul>
									<div class="clearfix"></div>
								  </div>
								  <div class="x_content">
									<table class="table table-striped table-bordered jambo_table">
									  <thead>
										<tr>
										  <th width="16%" rowspan="2">Area</th>
										  <th width="07%" rowspan="2">Number of TCs</th>
										  <th width="07%" rowspan="2">Number of Members</th>
										  <th colspan="10">TC Vehicle Type</th>
										</tr>
										<tr>
										  <th width="07%">PUJ</th>
										  <th width="07%">MCH</th>
										  <th width="07%">Taxi</th>
										  <th width="07%">MB</th>
										  <th width="07%">Bus</th>
										  <th width="07%">M.Cab</th>
										  <th width="07%">Truck</th>
										  <th width="07%">AUV</th>
										  <th width="07%">Banca</th>
										  <th width="07%">Total</th>
										</tr>
									  </thead>
									  <tbody>
									  <?php
									  $tc_num = 0; $tmembers_num = 0; $tpuj_num = 0; $tmch_num = 0; $ttaxi_num = 0; 
									  $tmb_num = 0; $tbus_num = 0; $tmcab_num = 0; $ttruck_num = 0; 
									  $tauv_num = 0; $tbanka_num = 0; $ttotal = 0; 
									  
									  $sql = "SELECT s.sname, COUNT(c.cid), SUM(c.members_num), SUM(c.puj_num), 
												  SUM(c.mch_num), SUM(c.taxi_num), SUM(c.mb_num), 
												  SUM(c.bus_num), SUM(c.mcab_num), SUM(c.truck_num), 
												  SUM(c.auv_num), SUM(c.banka_num), s.sid
											  FROM cooperatives c INNER JOIN tc_sectors s ON c.sid = s.sid
											  WHERE s.aid = '$arow[0]'
											  GROUP BY c.sid
											  ORDER BY s.sid, c.cname";
									  $res = query($sql); 
									  while ($row = fetch_array($res)) {
										  ?>
										  <tr>
											<td><a href="tc_sector.php?sid=<?php echo $row[12] ?>"><?php echo $row[0] ?></a></td>
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
											<td align="right"><?php echo number_format($row[3] + $row[4] + $row[5] + $row[6] + $row[7] + $row[8] + $row[9] + $row[10] + $row[11], 0)?></td>
										  </tr>
										  <?php
										  $tc_num += $row[1]; 
										  $tmembers_num += $row[2]; 
										  $tpuj_num += $row[3]; 
										  $tmch_num += $row[4]; 
										  $ttaxi_num += $row[5]; 
										  $tmb_num += $row[6]; 
										  $tbus_num += $row[7]; 
										  $tmcab_num += $row[8]; 
										  $ttruck_num += $row[9]; 
										  $tauv_num += $row[10]; 
										  $tbanka_num += $row[11]; 
										  $ttotal += $row[3] + $row[4] + $row[5] + $row[6] + $row[7] + $row[8] + $row[9] + $row[10] + $row[11];
									  }
									  mysqli_free_result($res);
									  ?>
									  <tr>
										<td><b>TOTAL</b></td>
										<td align="right"><b><?php echo number_format($tc_num, 0) ?></b></td>
										<td align="right"><b><?php echo number_format($tmembers_num, 0) ?></b></td>
										<td align="right"><b><?php echo number_format($tpuj_num, 0) ?></b></td>
										<td align="right"><b><?php echo number_format($tmch_num, 0) ?></b></td>
										<td align="right"><b><?php echo number_format($ttaxi_num, 0) ?></b></td>
										<td align="right"><b><?php echo number_format($tmb_num, 0) ?></b></td>
										<td align="right"><b><?php echo number_format($tbus_num, 0) ?></b></td>
										<td align="right"><b><?php echo number_format($tmcab_num, 0) ?></b></td>
										<td align="right"><b><?php echo number_format($ttruck_num, 0) ?></b></td>
										<td align="right"><b><?php echo number_format($tauv_num, 0) ?></b></td>
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
