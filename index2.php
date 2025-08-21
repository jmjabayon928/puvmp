<?php

include("db.php");
include("sqli.php");
include("html.php");

session_start();

if (isset($_SESSION['user_id'])) {
	if (validate_user() == true) {
		?>
		<!DOCTYPE html>
		<html lang="en">
		  <head>
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
			<!-- Meta, title, CSS, favicons, etc. -->
			<meta charset="utf-8">
			<meta http-equiv="X-UA-Compatible" content="IE=edge">
			<meta name="viewport" content="width=device-width, initial-scale=1">

			<title>OTC - Dashboard</title>

			<!-- Bootstrap -->
			<link href="vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
			<!-- Font Awesome -->
			<link href="vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
			<!-- NProgress -->
			<link href="vendors/nprogress/nprogress.css" rel="stylesheet">
			<!-- iCheck -->
			<link href="vendors/iCheck/skins/flat/green.css" rel="stylesheet">
			
			<!-- bootstrap-progressbar -->
			<link href="vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet">
			<!-- JQVMap -->
			<link href="vendors/jqvmap/dist/jqvmap.min.css" rel="stylesheet"/>
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
						<h3>OTC Dashboard</h3>
					  </div>
					</div>

					<br /><br />
					<?php
					$sql = "SELECT cid FROM cooperatives";
					$cnum_res = query($sql);
					$cnum_num = num_rows($cnum_res); 
					
					// accredited coops
					$sql = "SELECT cid FROM cooperatives WHERE ctype = 2";
					$acc_res = query($sql);
					$acc_num = num_rows($acc_res); 
					
					// puvmp accredited 
					$sql = "SELECT cid FROM cooperatives WHERE TO_DAYS(new_otc_date) >= TO_DAYS('2017-07-01') AND ctype = 2";
					$puvmp_res = query($sql);
					$puvmp_num = num_rows($puvmp_res); 
					
					// validated cooperatives
					$sql = "SELECT cid FROM cooperatives WHERE last_verify_id != 0";
					$verified_res = query($sql);
					$verified_num = num_rows($verified_res); 
					?>
					<div class="row top_tiles">
					  <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
						<div class="tile-stats">
						  <div class="icon"><i class="fa fa-database"></i></div>
						  <div class="count"><?php echo number_format($cnum_num, 0) ?></div>
						  <h3>Transport Cooperatives</h4>
						  <p>Total No. of Cooperatives in Database</p>
						</div>
					  </div>
					  <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
						<div class="tile-stats">
						  <div class="icon"><i class="fa fa-thumbs-up"></i></div>
						  <div class="count blue"><?php echo number_format($acc_num, 0) ?></div>
						  <h3>Accredited Cooperatives</h3>
						  <p>Total No. of Accredited Cooperatives</p>
						</div>
					  </div>
					  <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
						<div class="tile-stats">
						  <div class="icon"><i class="fa fa-star"></i></div>
						  <div class="count green"><?php echo number_format($puvmp_num, 0) ?></div>
						  <h3>PUVMP - Accredited</h3>
						  <p>Total No. of Accredited Coops since June, 2017</p>
						</div>
					  </div>
					  <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
						<div class="tile-stats">
						  <div class="icon"><i class="fa fa-check-square-o"></i></div>
						  <div class="count"><?php echo number_format($verified_num, 0) ?></div>
						  <h3>Validated Transport Coops</h3>
						  <p>Total No. of Validated Coops</p>
						</div>
					  </div>
					</div>
					<br /><br />
					
					<?php
					// incomplete basic information
					$sql = "SELECT cid FROM cooperatives 
							WHERE addr = '' OR chairman = '' OR contact_num = '' OR email = '' OR new_otc_num = '' OR new_otc_date = '0000-00-00' OR new_cda_num = '' OR new_cda_date = '0000-00-00'";
					$basic_res = query($sql);
					$basic_num = num_rows($basic_res); 
					
					// incomplete members information
					$sql = "SELECT cid FROM cooperatives 
							WHERE drivers_num = 0 AND operators_num = 0 AND workers_num = 0 AND others_num = 0";
					$members_res = query($sql);
					$members_num = num_rows($members_res); 
					
					// incomplete vehicle information
					$sql = "SELECT cid FROM cooperatives 
							WHERE puj_num = 0 AND mch_num = 0 AND taxi_num = 0 AND mb_num = 0 AND bus_num = 0 AND mcab_num = 0 AND truck_num = 0 AND tours_num = 0 AND uvx_num = 0 AND banka_num = 0";
					$vehicle_res = query($sql);
					$vehicle_num = num_rows($vehicle_res); 
					
					// incomplete financial information
					$sql = "SELECT cid FROM cooperatives 
							WHERE current_assets = '0.00' AND fixed_assets = '0.00' AND liabilities = '0.00' AND equity = '0.00' AND net_income = '0.00'";
					$financial_res = query($sql);
					$financial_num = num_rows($financial_res); 
					
					?>
					<div class="row top_tiles">
					  <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
						<div class="tile-stats">
						  <div class="icon"><i class="fa fa-exclamation-triangle"></i></div>
						  <div class="count"><?php echo number_format($basic_num, 0) ?></div>
						  <h3>Incomplete Basic Information</h4>
						  <p class="red">Total No. of Cooperatives Without Basic Information</p>
						</div>
					  </div>
					  <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
						<div class="tile-stats">
						  <div class="icon"><i class="fa fa-frown-o"></i></div>
						  <div class="count blue"><?php echo number_format($members_num, 0) ?></div>
						  <h3>Incomplete Members Information</h3>
						  <p class="red">Total No. of Cooperatives Without Members Information</p>
						</div>
					  </div>
					  <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
						<div class="tile-stats">
						  <div class="icon"><i class="fa fa-automobile"></i></div>
						  <div class="count green"><?php echo number_format($vehicle_num, 0) ?></div>
						  <h3>Incomplete Vehicle Information</h3>
						  <p class="red">Total No. of Cooperatives Without Vehicle Information</p>
						</div>
					  </div>
					  <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
						<div class="tile-stats">
						  <div class="icon"><i class="fa fa-money"></i></div>
						  <div class="count"><?php echo number_format($financial_num, 0) ?></div>
						  <h3>Incomplete Financial Information</h3>
						  <p class="red">Total No. of Cooperatives Without Financial Information</p>
						</div>
					  </div>
					</div>
					<br /><br />
					
					<?php
					// updated cgs 
					$sql = "SELECT cid FROM cooperatives WHERE TO_DAYS(cgs_expiry) >= TO_DAYS(CURDATE())";
					$update_cgs_res = query($sql);
					$update_cgs_num = num_rows($update_cgs_res); 
					
					// cgs expired in a year
					$sql = "SELECT cid FROM cooperatives 
							WHERE (TO_DAYS(CURDATE()) - TO_DAYS(cgs_expiry)) < 365
								AND (TO_DAYS(CURDATE()) - TO_DAYS(cgs_expiry)) > 0";
					$expired1_cgs_res = query($sql);
					$expired1_cgs_num = num_rows($expired1_cgs_res); 
					
					// cgs expired in 2 years
					$sql = "SELECT cid FROM cooperatives 
							WHERE (TO_DAYS(CURDATE()) - TO_DAYS(cgs_expiry)) < 730
								AND (TO_DAYS(CURDATE()) - TO_DAYS(cgs_expiry)) > 365";
					$expired2_cgs_res = query($sql);
					$expired2_cgs_num = num_rows($expired2_cgs_res); 
					
					// cgs expired in 3 years
					$sql = "SELECT cid FROM cooperatives 
							WHERE (TO_DAYS(CURDATE()) - TO_DAYS(cgs_expiry)) < 1095
								AND (TO_DAYS(CURDATE()) - TO_DAYS(cgs_expiry)) > 730";
					$expired3_cgs_res = query($sql);
					$expired3_cgs_num = num_rows($expired3_cgs_res); 
					
					?>
					<div class="row top_tiles">
					  <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
						<div class="tile-stats">
						  <div class="icon"><i class="fa fa-check-square-o"></i></div>
						  <div class="count green"><?php echo number_format($update_cgs_num, 0) ?></div>
						  <h3>Updated CGS</h3>
						  <p>Total No. of Coops with Updated CGS</p>
						</div>
					  </div>
					  <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
						<div class="tile-stats">
						  <div class="icon"><i class="fa fa-thumbs-o-down"></i></div>
						  <div class="count red"><?php echo number_format($expired1_cgs_num, 0) ?></div>
						  <h3>1-year Expired CGS</h3>
						  <p class="red">No. of Cooperatives with 1-year Expired CGS</p>
						</div>
					  </div>
					  <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
						<div class="tile-stats">
						  <div class="icon"><i class="fa fa-thumbs-down"></i></div>
						  <div class="count"><?php echo number_format($expired2_cgs_num, 0) ?></div>
						  <h3>2-year Expired CGS</h3>
						  <p class="red">No. of Cooperatives with 2-year Expired CGS</p>
						</div>
					  </div>
					  <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
						<div class="tile-stats">
						  <div class="icon"><i class="fa fa-exclamation-triangle"></i></div>
						  <div class="count red"><?php echo number_format($expired3_cgs_num, 0) ?></div>
						  <h3>3-year Expired CGS</h3>
						  <p class="red">No. of Cooperatives with 3-year Expired CGS</p>
						</div>
					  </div>
					</div>
					<br /><br />
					
					
					<?php
					$sql = "SELECT cid FROM cooperatives
							WHERE TO_DAYS(pa_expiry) - TO_DAYS(CURDATE()) <= 60";
					$pa_res = query($sql); 
					$pa_num = num_rows($pa_res); 
					?>
					<div class="row top_tiles">
					  <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
						<div class="tile-stats">
						  <div class="icon"><i class="fa fa-exclamation-triangle"></i></div>
						  <div class="count red"><?php echo number_format($pa_num, 0) ?></div>
						  <h3>Expiring Provisional Accreditation</h3>
						  <p class="red">Provisional Accreditation Expiring in 60 days or less</p>
						</div>
					  </div>
					</div>
					<br /><br />
					
					<?php
					// total members nationwide
					$sql = "SELECT SUM(drivers_num + operators_num + workers_num + others_num)
							FROM cooperatives 
							WHERE ctype = 2";
					$total_members_res = query($sql); 
					$total_members_row = fetch_array($total_members_res); 
					// total assets nationwide
					$sql = "SELECT SUM(current_assets + fixed_assets)
							FROM cooperatives
							WHERE ctype = 2";
					$total_assets_res = query($sql); 
					$total_assets_row = fetch_array($total_assets_res); 
					// total capitalizations nationwide
					$sql = "SELECT SUM(paid_up)
							FROM cooperatives 
							WHERE ctype = 2";
					$total_capitalizations_res = query($sql); 
					$total_capitalizations_row = fetch_array($total_capitalizations_res); 
					// total net surplus nationwide
					$sql = "SELECT SUM(net_income)
							FROM cooperatives 
							WHERE ctype = 2";
					$total_netsurplus_res = query($sql); 
					$total_netsurplus_row = fetch_array($total_netsurplus_res); 
					?>
					<div class="row top_tiles">
					  <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
						<div class="tile-stats">
						  <div class="icon"><i class="fa fa-user"></i></div>
						  <div class="count green"><?php echo number_format($total_members_row[0], 0) ?></div>
						  <h3>Total Members</h3>
						  <p>Total Number of TC Members Nationwide</p>
						</div>
					  </div>
					  <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
						<div class="tile-stats">
						  <div class="icon"><i class="fa fa-bar-chart"></i></div>
						  <div class="count green"><?php echo number_format($total_assets_row[0], 2) ?></div>
						  <h3>Total Assets</h3>
						  <p>Total Assets of TCs Nationwide</p>
						</div>
					  </div>
					  <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
						<div class="tile-stats">
						  <div class="icon"><i class="fa fa-money"></i></div>
						  <div class="count green"><?php echo number_format($total_capitalizations_row[0], 2) ?></div>
						  <h3>Total Capitalizations</h3>
						  <p>Total Capitalizations of TCs Nationwide</p>
						</div>
					  </div>
					  <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
						<div class="tile-stats">
						  <div class="icon"><i class="fa fa-rub"></i></div>
						  <div class="count green"><?php echo number_format($total_netsurplus_row[0], 2) ?></div>
						  <h3>Total Net Surplus</h3>
						  <p>Total Net Surplus of TCs Nationwide</p>
						</div>
					  </div>
					</div>
					<br /><br />
					
					
					<div class="row top_tiles">
					<?php
					$sql = "SELECT aid, aname FROM tc_areas";
					$ares = query($sql); 
					while ($arow = fetch_array($ares)) {
						$sql = "SELECT SUM(c.drivers_num + c.operators_num + c.workers_num + c.others_num)
								FROM cooperatives c 
									INNER JOIN tc_sectors s ON c.sid = s.sid
								WHERE s.aid = '$arow[0]'";
						$res = query($sql); 
						$row = fetch_array($res); 
						?>
						<div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
							<div class="tile-stats">
							<div class="icon"><i class="fa fa-user"></i></div>
							<div class="count"><?php echo number_format($row[0], 0) ?></div>
							<h3><?php echo $arow[1] ?> Members</h3>
							<p>Total Members of Cooperatives in <?php echo $arow[1] ?></p>
							</div>
						</div>
						<?php
					}
					free_result($ares); 
					?>
					</div>
					<br /><br />
					
					
					<div class="row top_tiles">
					<?php
					$sql = "SELECT aid, aname FROM tc_areas";
					$ares = query($sql); 
					while ($arow = fetch_array($ares)) {
						$sql = "SELECT SUM(c.current_assets + c.fixed_assets)
								FROM cooperatives c 
									INNER JOIN tc_sectors s ON c.sid = s.sid
								WHERE s.aid = '$arow[0]'";
						$res = query($sql); 
						$row = fetch_array($res); 
						?>
						<div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
							<div class="tile-stats">
							<div class="icon"><i class="fa fa-bar-chart"></i></div>
							<div class="count"><?php echo number_format($row[0], 2) ?></div>
							<h3><?php echo $arow[1] ?> Assets</h3>
							<p>Total Assets of Cooperatives in <?php echo $arow[1] ?></p>
							</div>
						</div>
						<?php
					}
					free_result($ares); 
					?>
					</div>
					<br /><br />
					
					<div class="row top_tiles">
					<?php
					$sql = "SELECT aid, aname FROM tc_areas";
					$ares = query($sql); 
					while ($arow = fetch_array($ares)) {
						$sql = "SELECT SUM(c.paid_up)
								FROM cooperatives c 
									INNER JOIN tc_sectors s ON c.sid = s.sid
								WHERE s.aid = '$arow[0]'";
						$res = query($sql); 
						$row = fetch_array($res); 
						?>
						<div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
							<div class="tile-stats">
							<div class="icon"><i class="fa fa-money"></i></div>
							<div class="count"><?php echo number_format($row[0], 2) ?></div>
							<h3><?php echo $arow[1] ?> Capitalizations</h3>
							<p>Total Capitalizations of Cooperatives in <?php echo $arow[1] ?></p>
							</div>
						</div>
						<?php
					}
					free_result($ares); 
					?>
					</div>
					<br /><br />
					
					<div class="row top_tiles">
					<?php
					$sql = "SELECT aid, aname FROM tc_areas";
					$ares = query($sql); 
					while ($arow = fetch_array($ares)) {
						$sql = "SELECT SUM(c.net_income)
								FROM cooperatives c 
									INNER JOIN tc_sectors s ON c.sid = s.sid
								WHERE s.aid = '$arow[0]'";
						$res = query($sql); 
						$row = fetch_array($res); 
						?>
						<div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
							<div class="tile-stats">
							<div class="icon"><i class="fa fa-rub"></i></div>
							<div class="count"><?php echo number_format($row[0], 2) ?></div>
							<h3><?php echo $arow[1] ?> Net Surplus</h3>
							<p>Total Net Surplus of Cooperatives in <?php echo $arow[1] ?></p>
							</div>
						</div>
						<?php
					}
					free_result($ares); 
					?>
					</div>
					<br /><br />
					
					
					
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
			<!-- Chart.js -->
			<script src="vendors/Chart.js/dist/Chart.min.js"></script>
			<!-- gauge.js -->
			<script src="vendors/gauge.js/dist/gauge.min.js"></script>
			<!-- bootstrap-progressbar -->
			<script src="vendors/bootstrap-progressbar/bootstrap-progressbar.min.js"></script>
			<!-- iCheck -->
			<script src="vendors/iCheck/icheck.min.js"></script>
			<!-- Skycons -->
			<script src="vendors/skycons/skycons.js"></script>
			<!-- Flot -->
			<script src="vendors/Flot/jquery.flot.js"></script>
			<script src="vendors/Flot/jquery.flot.pie.js"></script>
			<script src="vendors/Flot/jquery.flot.time.js"></script>
			<script src="vendors/Flot/jquery.flot.stack.js"></script>
			<script src="vendors/Flot/jquery.flot.resize.js"></script>
			<!-- Flot plugins -->
			<script src="vendors/flot.orderbars/js/jquery.flot.orderBars.js"></script>
			<script src="vendors/flot-spline/js/jquery.flot.spline.min.js"></script>
			<script src="vendors/flot.curvedlines/curvedLines.js"></script>
			<!-- DateJS -->
			<script src="vendors/DateJS/build/date.js"></script>
			<!-- JQVMap -->
			<script src="vendors/jqvmap/dist/jquery.vmap.js"></script>
			<script src="vendors/jqvmap/dist/maps/jquery.vmap.world.js"></script>
			<script src="vendors/jqvmap/examples/js/jquery.vmap.sampledata.js"></script>
			<!-- bootstrap-daterangepicker -->
			<script src="vendors/moment/min/moment.min.js"></script>
			<script src="vendors/bootstrap-daterangepicker/daterangepicker.js"></script>

			<!-- Custom Theme Scripts -->
			<script src="build/js/custom.min.js"></script>
			
		  </body>
		</html>
		<?php
	}
} else {
	header('Location: login.php');	
}
