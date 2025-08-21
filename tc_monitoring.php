<?php

include("db.php");
include("sqli.php");
include("html.php");
define('PAGE', 'tc_monitoring.php'); 

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

			<title>OTC - TC Monitoring Section</title>

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
				
				if (isset ($_GET['period'])) {
					$period = $_GET['period'];
			    } else {
					$period = 3; 
			    }
				
				if ($period == 1) {
					$low = 2010; $high = 2012;
				} elseif ($period == 2) {
					$low = 2013; $high = 2015;
				} elseif ($period == 3) {
					$low = 2016; $high = 2018;
				} elseif ($period == 4) {
					$low = 2019; $high = 2021;
				} elseif ($period == 5) {
					$low = 2022; $high = 2024;
				}
			  
				if (isset ($_GET['aid'])) {
					$aid = $_GET['aid'];
			    } else {
					$aid = 1; 
			    }
				
				?>

				<!-- page content -->
				<div class="right_col" role="main">
				  <div class="">
					<div class="page-title">
					  <div class="title_left">
						<h3>
						<?php
						if ($tab == 1) { echo 'Issued CGS';
						} elseif ($tab == 2) { echo 'CGS Applications';
						} elseif ($tab == 3) { echo 'Endorsements';
						}
						?>
						</h3>
					  </div>

					  <div class="title_right">
						<div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
							<select class="form-control col-md-7 col-xs-12" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
								<?php
								if ($period == 1) {
									?>
									<option value="tc_monitoring.php?tab=1&period=1&aid=<?php echo $aid ?>" Selected>2010 - 2012</option>
									<option value="tc_monitoring.php?tab=1&period=2&aid=<?php echo $aid ?>">2013 - 2015</option>
									<option value="tc_monitoring.php?tab=1&period=3&aid=<?php echo $aid ?>">2016 - 2018</option>
									<option value="tc_monitoring.php?tab=1&period=4&aid=<?php echo $aid ?>">2019 - 2021</option>
									<option value="tc_monitoring.php?tab=1&period=5&aid=<?php echo $aid ?>">2022 - 2024</option>
									<?php
								} elseif ($period == 2) {
									?>
									<option value="tc_monitoring.php?tab=1&period=1&aid=<?php echo $aid ?>">2010 - 2012</option>
									<option value="tc_monitoring.php?tab=1&period=2&aid=<?php echo $aid ?>" Selected>2013 - 2015</option>
									<option value="tc_monitoring.php?tab=1&period=3&aid=<?php echo $aid ?>">2016 - 2018</option>
									<option value="tc_monitoring.php?tab=1&period=4&aid=<?php echo $aid ?>">2019 - 2021</option>
									<option value="tc_monitoring.php?tab=1&period=5&aid=<?php echo $aid ?>">2022 - 2024</option>
									<?php
								} elseif ($period == 3) {
									?>
									<option value="tc_monitoring.php?tab=1&period=1&aid=<?php echo $aid ?>">2010 - 2012</option>
									<option value="tc_monitoring.php?tab=1&period=2&aid=<?php echo $aid ?>">2013 - 2015</option>
									<option value="tc_monitoring.php?tab=1&period=3&aid=<?php echo $aid ?>" Selected>2016 - 2018</option>
									<option value="tc_monitoring.php?tab=1&period=4&aid=<?php echo $aid ?>">2019 - 2021</option>
									<option value="tc_monitoring.php?tab=1&period=5&aid=<?php echo $aid ?>">2022 - 2024</option>
									<?php
								} elseif ($period == 4) {
									?>
									<option value="tc_monitoring.php?tab=1&period=1&aid=<?php echo $aid ?>">2010 - 2012</option>
									<option value="tc_monitoring.php?tab=1&period=2&aid=<?php echo $aid ?>">2013 - 2015</option>
									<option value="tc_monitoring.php?tab=1&period=3&aid=<?php echo $aid ?>">2016 - 2018</option>
									<option value="tc_monitoring.php?tab=1&period=4&aid=<?php echo $aid ?>" Selected>2019 - 2021</option>
									<option value="tc_monitoring.php?tab=1&period=5&aid=<?php echo $aid ?>">2022 - 2024</option>
									<?php
								} elseif ($period == 5) {
									?>
									<option value="tc_monitoring.php?tab=1&period=1&aid=<?php echo $aid ?>">2010 - 2012</option>
									<option value="tc_monitoring.php?tab=1&period=2&aid=<?php echo $aid ?>">2013 - 2015</option>
									<option value="tc_monitoring.php?tab=1&period=3&aid=<?php echo $aid ?>">2016 - 2018</option>
									<option value="tc_monitoring.php?tab=1&period=4&aid=<?php echo $aid ?>">2019 - 2021</option>
									<option value="tc_monitoring.php?tab=1&period=5&aid=<?php echo $aid ?>" Selected>2022 - 2024</option>
									<?php
								}
								?>
							</select>
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
										$sql = "SELECT aid, aname FROM tc_areas";
										$res = query($sql); 
										while ($row = fetch_array($res)) {
											if ($aid == $row[0]) {
												?><option value="tc_monitoring.php?tab=1&period=<?php echo $period ?>&aid=<?php echo $row[0] ?>" Selected><?php echo $row[1] ?></option><?php
											} else {
												?><option value="tc_monitoring.php?tab=1&period=<?php echo $period ?>&aid=<?php echo $row[0] ?>"><?php echo $row[1] ?></option><?php
											}
										}
										free_result($res); 
										?>
									</select>
								</ul>
								<div class="clearfix"></div>
							  </div>
							  <div class="x_content">
								<table class="table table-striped table-bordered jambo_table">
								  <thead>
									  <tr>
										  <th width="10%" rowspan="2">Sector</th>
										  <th width="30%" rowspan="2">TC Name</th>
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
										  } elseif ($period == 5) {
											  ?>
											  <th width="20%" colspan="3">2022</th>
											  <th width="20%" colspan="3">2023</th>
											  <th width="20%" colspan="3">2024</th>
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
									$sql = "SELECT s.sname, c.cid, c.cname
											FROM cooperatives c INNER JOIN tc_sectors s ON c.sid = s.sid
											WHERE s.aid = '$aid'
											ORDER BY s.sname, c.cname";
									$res = query($sql);

									while ($row = fetch_array($res)) {
										?>
										<tr>
										  <td align="left"><?php echo $row[0] ?></td>
										  <td align="left"><a href="tc.php?cid=<?php echo $row[1] ?>"><?php echo $row[2] ?></a></td>
										  <?php
										  for ($i=$low; $i<$high+1; $i++) {
											  $sql = "SELECT cgs_num, DATE_FORMAT(cgs_date, '%m/%d/%y'), DATE_FORMAT(cgs_exp, '%m/%d/%y')
													  FROM cooperatives_cgs
													  WHERE cid = '$row[1]' AND YEAR(cgs_date) = '$i'";
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
					  } elseif ($tab == 2) {
					  } elseif ($tab == 3) {
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
