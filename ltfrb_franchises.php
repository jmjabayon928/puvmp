<?php

include("db.php");
include("sqli.php");
include("html.php");
define('PAGE', 'ltfrb_franchises.php'); 

session_start();

if (isset($_SESSION['user_id'])) {
	if (validate_level(PAGE) == true) {

		// log the activity
		log_user(5, 'ltfrb_franchises', 0);
		
		if (isset ($_GET['tab'])) {
			$tab = $_GET['tab'];
		} else {
			$tab = 2; 
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

			<title>OTC - LTFRB Franchises</title>

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
						if ($tab == 1) { echo 'City Bus';
						} elseif ($tab == 2) { echo 'PUJ';
						} elseif ($tab == 3) { echo 'Filcab';
						} elseif ($tab == 4) { echo 'Taxi';
						} elseif ($tab == 5) { echo 'Provincial Bus';
						} elseif ($tab == 6) { echo 'School Service';
						} elseif ($tab == 7) { echo 'Shuttle Service';
						} elseif ($tab == 8) { echo 'Trucks';
						} elseif ($tab == 9) { echo 'Tourist Transport';
						} elseif ($tab == 10) { echo 'TNVS';
						} elseif ($tab == 11) { echo 'UV Express';
						}
						?> Franchises
						</h3>
					  </div>

					  <div class="title_right">
						<div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
							<select class="form-control col-md-7 col-xs-12" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
								<?php
								if ($tab == 1) {
									$unit_type = 'City Bus';
									?>
									<option value="ltfrb_franchises.php?tab=1" Selected>City Bus</option>
									<option value="ltfrb_franchises.php?tab=2">PUJ</option>
									<option value="ltfrb_franchises.php?tab=3">FilCab</option>
									<option value="ltfrb_franchises.php?tab=4">Taxi</option>
									<option value="ltfrb_franchises.php?tab=5">Provincial Bus</option>
									<option value="ltfrb_franchises.php?tab=6">School Service</option>
									<option value="ltfrb_franchises.php?tab=7">Shuttle Service</option>
									<option value="ltfrb_franchises.php?tab=8">Trucks</option>
									<option value="ltfrb_franchises.php?tab=9">Tourist Transport</option>
									<option value="ltfrb_franchises.php?tab=10">TNVS</option>
									<option value="ltfrb_franchises.php?tab=11">UV Express</option>
									<?php
								} elseif ($tab == 2) {
									$unit_type = 'PUJ';
									?>
									<option value="ltfrb_franchises.php?tab=1">City Bus</option>
									<option value="ltfrb_franchises.php?tab=2" Selected>PUJ</option>
									<option value="ltfrb_franchises.php?tab=3">FilCab</option>
									<option value="ltfrb_franchises.php?tab=4">Taxi</option>
									<option value="ltfrb_franchises.php?tab=5">Provincial Bus</option>
									<option value="ltfrb_franchises.php?tab=6">School Service</option>
									<option value="ltfrb_franchises.php?tab=7">Shuttle Service</option>
									<option value="ltfrb_franchises.php?tab=8">Trucks</option>
									<option value="ltfrb_franchises.php?tab=9">Tourist Transport</option>
									<option value="ltfrb_franchises.php?tab=10">TNVS</option>
									<option value="ltfrb_franchises.php?tab=11">UV Express</option>
									<?php
								} elseif ($tab == 3) {
									$unit_type = 'FilCab';
									?>
									<option value="ltfrb_franchises.php?tab=1">City Bus</option>
									<option value="ltfrb_franchises.php?tab=2">PUJ</option>
									<option value="ltfrb_franchises.php?tab=3" Selected>FilCab</option>
									<option value="ltfrb_franchises.php?tab=4">Taxi</option>
									<option value="ltfrb_franchises.php?tab=5">Provincial Bus</option>
									<option value="ltfrb_franchises.php?tab=6">School Service</option>
									<option value="ltfrb_franchises.php?tab=7">Shuttle Service</option>
									<option value="ltfrb_franchises.php?tab=8">Trucks</option>
									<option value="ltfrb_franchises.php?tab=9">Tourist Transport</option>
									<option value="ltfrb_franchises.php?tab=10">TNVS</option>
									<option value="ltfrb_franchises.php?tab=11">UV Express</option>
									<?php
								} elseif ($tab == 4) {
									$unit_type = 'Taxi';
									?>
									<option value="ltfrb_franchises.php?tab=1">City Bus</option>
									<option value="ltfrb_franchises.php?tab=2">PUJ</option>
									<option value="ltfrb_franchises.php?tab=3">FilCab</option>
									<option value="ltfrb_franchises.php?tab=4" Selected>Taxi</option>
									<option value="ltfrb_franchises.php?tab=5">Provincial Bus</option>
									<option value="ltfrb_franchises.php?tab=6">School Service</option>
									<option value="ltfrb_franchises.php?tab=7">Shuttle Service</option>
									<option value="ltfrb_franchises.php?tab=8">Trucks</option>
									<option value="ltfrb_franchises.php?tab=9">Tourist Transport</option>
									<option value="ltfrb_franchises.php?tab=10">TNVS</option>
									<option value="ltfrb_franchises.php?tab=11">UV Express</option>
									<?php
								} elseif ($tab == 5) {
									$unit_type = 'Provincial Bus';
									?>
									<option value="ltfrb_franchises.php?tab=1">City Bus</option>
									<option value="ltfrb_franchises.php?tab=2">PUJ</option>
									<option value="ltfrb_franchises.php?tab=3">FilCab</option>
									<option value="ltfrb_franchises.php?tab=4">Taxi</option>
									<option value="ltfrb_franchises.php?tab=5" Selected>Provincial Bus</option>
									<option value="ltfrb_franchises.php?tab=6">School Service</option>
									<option value="ltfrb_franchises.php?tab=7">Shuttle Service</option>
									<option value="ltfrb_franchises.php?tab=8">Trucks</option>
									<option value="ltfrb_franchises.php?tab=9">Tourist Transport</option>
									<option value="ltfrb_franchises.php?tab=10">TNVS</option>
									<option value="ltfrb_franchises.php?tab=11">UV Express</option>
									<?php
								} elseif ($tab == 6) {
									$unit_type = 'School Service';
									?>
									<option value="ltfrb_franchises.php?tab=1">City Bus</option>
									<option value="ltfrb_franchises.php?tab=2">PUJ</option>
									<option value="ltfrb_franchises.php?tab=3">FilCab</option>
									<option value="ltfrb_franchises.php?tab=4">Taxi</option>
									<option value="ltfrb_franchises.php?tab=5">Provincial Bus</option>
									<option value="ltfrb_franchises.php?tab=6" Selected>School Service</option>
									<option value="ltfrb_franchises.php?tab=7">Shuttle Service</option>
									<option value="ltfrb_franchises.php?tab=8">Trucks</option>
									<option value="ltfrb_franchises.php?tab=9">Tourist Transport</option>
									<option value="ltfrb_franchises.php?tab=10">TNVS</option>
									<option value="ltfrb_franchises.php?tab=11">UV Express</option>
									<?php
								} elseif ($tab == 7) {
									$unit_type = 'Shuttle Service';
									?>
									<option value="ltfrb_franchises.php?tab=1">City Bus</option>
									<option value="ltfrb_franchises.php?tab=2">PUJ</option>
									<option value="ltfrb_franchises.php?tab=3">FilCab</option>
									<option value="ltfrb_franchises.php?tab=4">Taxi</option>
									<option value="ltfrb_franchises.php?tab=5">Provincial Bus</option>
									<option value="ltfrb_franchises.php?tab=6">School Service</option>
									<option value="ltfrb_franchises.php?tab=7" Selected>Shuttle Service</option>
									<option value="ltfrb_franchises.php?tab=8">Trucks</option>
									<option value="ltfrb_franchises.php?tab=9">Tourist Transport</option>
									<option value="ltfrb_franchises.php?tab=10">TNVS</option>
									<option value="ltfrb_franchises.php?tab=11">UV Express</option>
									<?php
								} elseif ($tab == 8) {
									$unit_type = 'Trucks';
									?>
									<option value="ltfrb_franchises.php?tab=1">City Bus</option>
									<option value="ltfrb_franchises.php?tab=2">PUJ</option>
									<option value="ltfrb_franchises.php?tab=3">FilCab</option>
									<option value="ltfrb_franchises.php?tab=4">Taxi</option>
									<option value="ltfrb_franchises.php?tab=5">Provincial Bus</option>
									<option value="ltfrb_franchises.php?tab=6">School Service</option>
									<option value="ltfrb_franchises.php?tab=7">Shuttle Service</option>
									<option value="ltfrb_franchises.php?tab=8" Selected>Trucks</option>
									<option value="ltfrb_franchises.php?tab=9">Tourist Transport</option>
									<option value="ltfrb_franchises.php?tab=10">TNVS</option>
									<option value="ltfrb_franchises.php?tab=11">UV Express</option>
									<?php
								} elseif ($tab == 9) {
									$unit_type = 'Tourist Transport';
									?>
									<option value="ltfrb_franchises.php?tab=1">City Bus</option>
									<option value="ltfrb_franchises.php?tab=2">PUJ</option>
									<option value="ltfrb_franchises.php?tab=3">FilCab</option>
									<option value="ltfrb_franchises.php?tab=4">Taxi</option>
									<option value="ltfrb_franchises.php?tab=5">Provincial Bus</option>
									<option value="ltfrb_franchises.php?tab=6">School Service</option>
									<option value="ltfrb_franchises.php?tab=7">Shuttle Service</option>
									<option value="ltfrb_franchises.php?tab=8">Trucks</option>
									<option value="ltfrb_franchises.php?tab=9" Selected>Tourist Transport</option>
									<option value="ltfrb_franchises.php?tab=10">TNVS</option>
									<option value="ltfrb_franchises.php?tab=11">UV Express</option>
									<?php
								} elseif ($tab == 10) {
									$unit_type = 'TNVS';
									?>
									<option value="ltfrb_franchises.php?tab=1">City Bus</option>
									<option value="ltfrb_franchises.php?tab=2">PUJ</option>
									<option value="ltfrb_franchises.php?tab=3">FilCab</option>
									<option value="ltfrb_franchises.php?tab=4">Taxi</option>
									<option value="ltfrb_franchises.php?tab=5">Provincial Bus</option>
									<option value="ltfrb_franchises.php?tab=6">School Service</option>
									<option value="ltfrb_franchises.php?tab=7">Shuttle Service</option>
									<option value="ltfrb_franchises.php?tab=8">Trucks</option>
									<option value="ltfrb_franchises.php?tab=9">Tourist Transport</option>
									<option value="ltfrb_franchises.php?tab=10" Selected>TNVS</option>
									<option value="ltfrb_franchises.php?tab=11">UV Express</option>
									<?php
								} elseif ($tab == 11) {
									$unit_type = 'UV Express';
									?>
									<option value="ltfrb_franchises.php?tab=1">City Bus</option>
									<option value="ltfrb_franchises.php?tab=2">PUJ</option>
									<option value="ltfrb_franchises.php?tab=3">FilCab</option>
									<option value="ltfrb_franchises.php?tab=4">Taxi</option>
									<option value="ltfrb_franchises.php?tab=5">Provincial Bus</option>
									<option value="ltfrb_franchises.php?tab=6">School Service</option>
									<option value="ltfrb_franchises.php?tab=7">Shuttle Service</option>
									<option value="ltfrb_franchises.php?tab=8">Trucks</option>
									<option value="ltfrb_franchises.php?tab=9">Tourist Transport</option>
									<option value="ltfrb_franchises.php?tab=10">TNVS</option>
									<option value="ltfrb_franchises.php?tab=11" Selected>UV Express</option>
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
						if (isset($_GET['added_franchise'])) {
							if ($_GET['added_franchise'] == 1) {
								?>
								<div class="alert alert-success">
								  <strong>Success!</strong> Franchise has been added into the database.
								</div>
								<?php
							}
						}
						?>
					  
						<div class="col-md-12 col-sm-12 col-xs-12">
							<div class="x_panel">
								<div class="x_title">
									<ul class="nav navbar-right panel_toolbox">
										<a href="add_ltfrb_franchise.php" class="btn btn-primary btn-md"><i class="fa fa-plus-square"></i> Add </a>
									</ul>
								<div class="clearfix"></div>
								</div>
								<div class="x_content">
									<table id="datatable-fixed-header" class="table table-striped table-bordered jambo_table">
										<thead>
										<tr>
											<th width="12%"></th>
											<th width="10%">Case #</th>
											<th width="20%">Owner</th>
											<th width="12%">Owner Type</th>
											<th width="08%">Units</th>
											<th width="08%">Granted</th>
											<th width="08%">Expiry</th>
											<th width="18%">Route</th>
										</tr>
										</thead>
										<tbody>
										<?php
										$sql = "SELECT fid, case_num, owner, 
													owner_type, units_num, 
													DATE_FORMAT(dg_date, '%m/%d/%y'), 
													DATE_FORMAT(de_date, '%m/%d/%y'),
													route, foreign_id
												FROM franchises_ltfrb
												WHERE unit_type LIKE '%$unit_type%'
												ORDER BY owner";
										$res = query($sql); 
										while ($row = fetch_array($res)) {
											?>
											<tr>
												<td align="center">
													<?php
													if ($row[3] == 'Cooperative' && $row[8] != 0) {
														?><a target="new" href="tc.php?cid=<?php echo $row[8] ?>" class="btn btn-info btn-xs"><i class="fa fa-search"></i></a><?php
													}
													?>
													<a href="e_ltfrb_franchise.php?fid=<?php echo $row[0] ?>" class="btn btn-warning btn-xs"><i class="fa fa-pencil"></i></a>
													<a href="d_ltfrb_franchise.php?fid=<?php echo $row[0] ?>" class="btn btn-danger btn-xs"><i class="fa fa-eraser"></i></a>
												</td>
												<td align="center"><?php echo $row[1] ?></td>
												<td align="left"><?php echo $row[2] ?></td>
												<td align="left"><?php echo $row[3] ?></td>
												<td align="right"><?php echo number_format($row[4], 0) ?></td>
												<td align="center"><?php echo $row[5] ?></td>
												<td align="center"><?php echo $row[6] ?></td>
												<td align="left"><?php echo $row[7] ?></td>
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
