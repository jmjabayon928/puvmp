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

			<title>OTC - Verified Cooperatives</title>

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
						<h3>Verified Cooperatives</h3>
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
						
						$sql = "SELECT DISTINCT c.last_verify_id, CONCAT(e.lname,', ',e.fname)
								FROM cooperatives c INNER JOIN employees e ON c.last_verify_id = e.eid
								ORDER BY e.lname, e.fname";
						$eres = query($sql); 
						while ($erow = fetch_array($eres)) {
							?>
							<div class="col-md-12 col-sm-12 col-xs-12">
								<div class="x_panel">
									<div class="x_title">
										<h2><?php echo $erow[1] ?></h2>
										<ul class="nav navbar-right panel_toolbox">
											<a href="tcpdf/examples/pdf_tc_reports.php?type=10" target="new" class="btn btn-info btn-md" data-toggle="tooltip" data-placement="top" title="Print Report"><i class="glyphicon glyphicon-print"></i></a>
										</ul>
										<div class="clearfix"></div>
									</div>
									<div class="x_content">
									
										<table class="table table-striped table-bordered jambo_table">
										    <thead>
											    <tr>
												    <th width="15%">Sector</th>
												    <th width="40%">TC Name</th>
												    <th width="15%">Date Verified</th>
												    <th width="15%">Last Updated By</th>
												    <th width="15%">Date Last Updated</th>
											    </tr>
										    </thead>
										    <tbody>
										    <?php
										    $sql = "SELECT c.cid, s.sname, c.cname, DATE_FORMAT(c.last_verify, '%m/%d/%y %h:%i %p'),
													    CONCAT(u.lname,', ',u.fname), DATE_FORMAT(c.last_update, '%m/%d/%y %h:%i %p')
													FROM cooperatives c 
														INNER JOIN tc_sectors s ON c.sid = s.sid
													    LEFT JOIN employees e ON c.last_verify_id = e.eid
													    LEFT JOIN users u ON c.last_update_id = u.user_id
													WHERE c.last_verify_id = '$erow[0]'
													ORDER BY c.last_verify DESC";
											$res = query($sql); 
											$num = num_rows($res); 
											while ($row = fetch_array($res)) {
												?>
												<tr>
													<td align="left"><?php echo $row[1] ?></td>
													<td align="left"><a href="tc.php?cid=<?php echo $row[0] ?>"><?php echo $row[2] ?></a></td>
													<td align="center"><?php echo $row[3] ?></td>
													<td align="center"><?php echo $row[4] ?></td>
													<td align="center"><?php echo $row[5] ?></td>
												</tr>
												<?php
											}
											free_result($res); 
										    ?>
												<tr>
													<td><b>Total Verified</b></td>
													<td><b><?php echo number_format($num, 0) ?></td>
													<td></td>
													<td></td>
													<td></td>
												</tr>
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
