<?php

include("db.php");
include("sqli.php");
include("html.php");
define('PAGE', 'tc_sector.php'); 

session_start();

if (isset($_SESSION['user_id'])) {
	if (validate_level(PAGE) == true) {

		$sid = $_GET['sid'];
	    $tab = $_GET['tab'];
		
		// log the activity
		log_user(5, 'tc_sectors', $sid);
		
		?>
		<!DOCTYPE html>
		<html lang="en">
		  <head>
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
			<!-- Meta, title, CSS, favicons, etc. -->
			<meta charset="utf-8">
			<meta http-equiv="X-UA-Compatible" content="IE=edge">
			<meta name="viewport" content="width=device-width, initial-scale=1">

			<title>OTC - TC Sector</title>

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
						if ($tab == 4) { echo 'TC Report - No. of Units';
						} elseif ($tab == 5) { echo 'TC Report - No. of Members';
						} elseif ($tab == 6) { echo 'TC Report - Assets & Liabilities';
						} elseif ($tab == 7) { echo 'TC Report - Capitalizations';
						} elseif ($tab == 8) { echo 'TC Report - Net Surplus';
						}
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
						  if ($tab == 4) {
							  $sql = "SELECT sname FROM tc_sectors WHERE sid = '$sid'";
							  $sres = query($sql); 
							  $srow = fetch_array($sres);
								  ?>
								  <div class="col-md-12 col-sm-12 col-xs-12">
									<div class="x_panel">
									  <div class="x_title">
										<h2><?php echo $srow[0] ?></h2>
										<ul class="nav navbar-right panel_toolbox">
										  <a href="tcpdf/examples/pdf_tc_reports.php?type=1&sid=<?php echo $sid ?>" target="new" class="btn btn-info btn-md" data-toggle="tooltip" data-placement="top" title="Print Report"><i class="glyphicon glyphicon-print"></i></a>
										</ul>
										<div class="clearfix"></div>
									  </div>
									  <div class="x_content">
										<table class="table table-striped table-bordered jambo_table">
										  <thead>
											<tr>
											  <th width="23%" rowspan="2">Transportation Cooperatives</th>
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
										  $tmembers_num = 0; $tpuj_num = 0; $tmch_num = 0; $ttaxi_num = 0; 
										  $tmb_num = 0; $tbus_num = 0; $tmcab_num = 0; $ttruck_num = 0; 
										  $tauv_num = 0; $tbanka_num = 0; $ttotal = 0; 
										  
										  $sql = "SELECT cid, cname, members_num, puj_num, mch_num, taxi_num, 
													mb_num, bus_num, mcab_num, truck_num, auv_num, banka_num 
												  FROM cooperatives 
												  WHERE sid = '$sid' AND ctype = 2 
												  ORDER BY cname";
										  $res = query($sql); $num = num_rows($res); 
										  while ($row = fetch_array($res)) {
											  ?>
											  <tr>
												<td><a href="tc.php?cid=<?php echo $row[0] ?>"><?php echo $row[1] ?></a></td>
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
											<td><b>TOTAL: <?php echo number_format($num, 0) ?></b></td>
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
							  mysqli_free_result($sres); 
						  } elseif ($tab == 5) {
							  $sql = "SELECT sname FROM tc_sectors WHERE sid = '$sid'";
							  $sres = query($sql); 
							  $srow = fetch_array($sres);
								  ?>
								  <div class="col-md-12 col-sm-12 col-xs-12">
									<div class="x_panel">
									  <div class="x_title">
										<h2><?php echo $srow[0] ?></h2>
										<ul class="nav navbar-right panel_toolbox">
										  <a href="tcpdf/examples/pdf_tc_reports.php?type=2&sid=<?php echo $sid ?>" target="new" class="btn btn-info btn-md" data-toggle="tooltip" data-placement="top" title="Print Report"><i class="glyphicon glyphicon-print"></i></a>
										</ul>
										<div class="clearfix"></div>
									  </div>
									  <div class="x_content">
										<table class="table table-striped table-bordered jambo_table">
										  <thead>
											<tr>
											  <th width="25%" rowspan="2">Transportation Cooperatives</th>
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
										  
										  $sql = "SELECT cid, cname, operators_num, 
													  drivers_num, workers_num, others_num
												  FROM cooperatives 
												  WHERE sid = '$sid' AND ctype = 2 
												  ORDER BY cname";
										  $res = query($sql); 
										  while ($row = fetch_array($res)) {
											  ?>
											  <tr>
												<td><a href="tc.php?cid=<?php echo $row[0] ?>"><?php echo $row[1] ?></a></td>
												<td align="right"><?php echo number_format($row[2], 0) ?></td>
												<td align="right"><?php echo number_format($row[3], 0) ?></td>
												<td align="right"><?php echo number_format($row[4], 0) ?></td>
												<td align="right"><?php echo number_format($row[5], 0) ?></td>
												<td align="right"><?php echo number_format($row[2] + $row[3] + $row[4] + $row[5], 0)?></td>
											  </tr>
											  <?php
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
							  mysqli_free_result($sres); 
						  } elseif ($tab == 6) {
							  $sql = "SELECT sname FROM tc_sectors WHERE sid = '$sid'";
							  $sres = query($sql); 
							  $srow = fetch_array($sres);
								  ?>
								  <div class="col-md-12 col-sm-12 col-xs-12">
									<div class="x_panel">
									  <div class="x_title">
										<h2><?php echo $srow[0] ?></h2>
										<ul class="nav navbar-right panel_toolbox">
										  <a href="tcpdf/examples/pdf_tc_reports.php?type=3&sid=<?php echo $sid ?>" target="new" class="btn btn-info btn-md" data-toggle="tooltip" data-placement="top" title="Print Report"><i class="glyphicon glyphicon-print"></i></a>
										</ul>
										<div class="clearfix"></div>
									  </div>
									  <div class="x_content">
										<table class="table table-striped table-bordered jambo_table">
										  <thead>
											<tr>
											  <th width="22%" rowspan="2">Transportation Cooperatives</th>
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
										  $tcurrent = 0; $tfixed = 0; $ttassets = 0; $tliability = 0; $tequity = 0; $tnet = 0; 
										  
										  $sql = "SELECT cid, cname, current_assets, fixed_assets, 
													  current_assets + fixed_assets, 
													  liabilities, equity, net_income
												  FROM cooperatives 
												  WHERE sid = '$sid' AND ctype = 2 
												  ORDER BY cname";
										  $res = query($sql); 
										  while ($row = fetch_array($res)) {
											  ?>
											  <tr>
												<td><a href="tc.php?cid=<?php echo $row[0] ?>"><?php echo $row[1] ?></a></td>
												<td align="right"><?php echo number_format($row[2], 2) ?></td>
												<td align="right"><?php echo number_format($row[3], 2) ?></td>
												<td align="right"><?php echo number_format($row[4], 2) ?></td>
												<td align="right"><?php echo number_format($row[5], 2) ?></td>
												<td align="right"><?php echo number_format($row[6], 2) ?></td>
												<td align="right"><?php echo number_format($row[7], 2) ?></td>
											  </tr>
											  <?php
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
											<td align="right"><b><?php echo number_format($tcurrent, 2) ?></b></td>
											<td align="right"><b><?php echo number_format($tfixed, 2) ?></b></td>
											<td align="right"><b><?php echo number_format($ttassets, 2) ?></b></td>
											<td align="right"><b><?php echo number_format($tliability, 2) ?></b></td>
											<td align="right"><b><?php echo number_format($tequity, 2) ?></b></td>
											<td align="right"><b><?php echo number_format($tnet, 2) ?></b></td>
										  </tr>
										  </tbody>
										</table>
									  </div><!-- x_content -->
									</div>
								  </div>
								  <?php
							  mysqli_free_result($sres); 
						  } elseif ($tab == 7) {
							  $sql = "SELECT sname FROM tc_sectors WHERE sid = '$sid'";
							  $sres = query($sql); 
							  $srow = fetch_array($sres);
								  ?>
								  <div class="col-md-12 col-sm-12 col-xs-12">
									<div class="x_panel">
									  <div class="x_title">
										<h2><?php echo $srow[0] ?></h2>
										<ul class="nav navbar-right panel_toolbox">
										  <a href="tcpdf/examples/pdf_tc_reports.php?type=4&sid=<?php echo $sid ?>" target="new" class="btn btn-info btn-md" data-toggle="tooltip" data-placement="top" title="Print Report"><i class="glyphicon glyphicon-print"></i></a>
										</ul>
										<div class="clearfix"></div>
									  </div>
									  <div class="x_content">
										<table class="table table-striped table-bordered jambo_table">
										  <thead>
											<tr>
											  <th width="28%" rowspan="2">Transportation Cooperatives</th>
											  <th colspan="4">Type of Capitalization</th>
											</tr>
											<tr>
											  <th width="18%">Initial Authorized Capital Stock</th>
											  <th width="18%">Present Authorized Capital Stock</th>
											  <th width="18%">Subscribed Capital</th>
											  <th width="18%">Paid-Up Capital</th>
											</tr>
										  </thead>
										  <tbody>
										  <?php
										  $tinit = 0; $tpresent = 0; $tsubscribed = 0; $tpaid = 0; 
										  
										  $sql = "SELECT cid, cname, init_stock, 
													  present_stock, subscribed, paid_up
												  FROM cooperatives 
												  WHERE sid = '$sid' AND ctype = 2 
												  ORDER BY cname";
										  $res = query($sql); 
										  while ($row = fetch_array($res)) {
											  ?>
											  <tr>
												<td><a href="tc.php?cid=<?php echo $row[0] ?>"><?php echo $row[1] ?></a></td>
												<td align="right"><?php echo number_format($row[2], 2) ?></td>
												<td align="right"><?php echo number_format($row[3], 2) ?></td>
												<td align="right"><?php echo number_format($row[4], 2) ?></td>
												<td align="right"><?php echo number_format($row[5], 2) ?></td>
											  </tr>
											  <?php
											  $tinit += $row[2]; 
											  $tpresent += $row[3]; 
											  $tsubscribed += $row[4]; 
											  $tpaid += $row[5]; 
										  }
										  mysqli_free_result($res);
										  ?>
										  <tr>
											<td><b>TOTAL</b></td>
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
							  mysqli_free_result($sres); 
						  } elseif ($tab == 8) {
							  $sql = "SELECT sname FROM tc_sectors WHERE sid = '$sid'";
							  $sres = query($sql); 
							  $srow = fetch_array($sres);
								  ?>
								  <div class="col-md-12 col-sm-12 col-xs-12">
									<div class="x_panel">
									  <div class="x_title">
										<h2><?php echo $srow[0] ?></h2>
										<ul class="nav navbar-right panel_toolbox">
										  <a href="tcpdf/examples/pdf_tc_reports.php?type=5&sid=<?php echo $sid ?>" target="new" class="btn btn-info btn-md" data-toggle="tooltip" data-placement="top" title="Print Report"><i class="glyphicon glyphicon-print"></i></a>
										</ul>
										<div class="clearfix"></div>
									  </div>
									  <div class="x_content">
										<table class="table table-striped table-bordered jambo_table">
										  <thead>
											<tr>
											  <th width="25%" rowspan="2">Transportation Cooperatives</th>
											  <th colspan="5">Type of Fund</th>
											</tr>
											<tr>
											  <th width="15%">General Reserve Fund</th>
											  <th width="15%">Education and Training Fund</th>
											  <th width="15%">Community Dev't Fund</th>
											  <th width="15%">Optional Fund</th>
											  <th width="15%">Dividends & Patronage Refund</th>
											</tr>
										  </thead>
										  <tbody>
										  <?php
										  $treserve = 0; $teduc = 0; $tcomm = 0; $toptional = 0; $tdividends = 0; 
										  
										  $sql = "SELECT cid, cname, reserved, educ_training, 
													comm_dev, optional, dividends
												  FROM cooperatives 
												  WHERE sid = '$sid' AND ctype = 2 
												  ORDER BY cname";
										  $res = query($sql); 
										  while ($row = fetch_array($res)) {
											  ?>
											  <tr>
												<td><a href="tc.php?cid=<?php echo $row[0] ?>"><?php echo $row[1] ?></a></td>
												<td align="right"><?php echo number_format($row[2], 2) ?></td>
												<td align="right"><?php echo number_format($row[3], 2) ?></td>
												<td align="right"><?php echo number_format($row[4], 2) ?></td>
												<td align="right"><?php echo number_format($row[5], 2) ?></td>
												<td align="right"><?php echo number_format($row[6], 2) ?></td>
											  </tr>
											  <?php
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
											<td align="right"><b><?php echo number_format($treserve, 2) ?></b></td>
											<td align="right"><b><?php echo number_format($teduc, 2) ?></b></td>
											<td align="right"><b><?php echo number_format($tcomm, 2) ?></b></td>
											<td align="right"><b><?php echo number_format($toptional, 2) ?></b></td>
											<td align="right"><b><?php echo number_format($tdividends, 2) ?></b></td>
										  </tr>
										  </tbody>
										</table>
									  </div><!-- x_content -->
									</div>
								  </div>
								  <?php
							  mysqli_free_result($sres); 
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

