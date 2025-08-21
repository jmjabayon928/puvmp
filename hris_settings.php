<?php

include("db.php");
include("sqli.php");
include("html.php");
define('PAGE', 'hris_settings.php'); 

$tab = $_GET['tab'];

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

			<title>HRIS Settings</title>

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

				if (isset($_GET['tab'])) {
					$tab = $_GET['tab'];
				} else {
					$tab = 1; 
				}
				  
				if (isset($_GET['sid'])) {
					$sid = $_GET['sid'];
				} else {
					$sid = 1; 
				}
				
				?>

				<!-- page content -->
				<div class="right_col" role="main">
				  <div class="">
					<div class="page-title">
					  <div class="title_left">
						<h3>Human Resource IS Settings</h3>
					  </div>

					  <div class="title_right">
						<div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
						  <select class="form-control col-md-7 col-xs-12" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
							<?php
							if ($tab == 1) {
								?>
								<option value="hris_settings.php?tab=1" Selected>Salary Standardization</option>
								<option value="hris_settings.php?tab=2">Salary Periods</option>
								<option value="hris_settings.php?tab=3">Salary Groups</option>
								<option value="hris_settings.php?tab=4">BIR Brackets</option>
								<option value="hris_settings.php?tab=5">GSIS Brackets</option>
								<option value="hris_settings.php?tab=6">PhilHealth Brackets</option>
								<option value="hris_settings.php?tab=7">SSS Brackets</option>
								<?php
							} elseif ($tab == 2) {
								?>
								<option value="hris_settings.php?tab=1">Salary Standardization</option>
								<option value="hris_settings.php?tab=2" Selected>Salary Periods</option>
								<option value="hris_settings.php?tab=3">Salary Groups</option>
								<option value="hris_settings.php?tab=4">BIR Brackets</option>
								<option value="hris_settings.php?tab=5">GSIS Brackets</option>
								<option value="hris_settings.php?tab=6">PhilHealth Brackets</option>
								<option value="hris_settings.php?tab=7">SSS Brackets</option>
								<?php
							} elseif ($tab == 3) {
								?>
								<option value="hris_settings.php?tab=1">Salary Standardization</option>
								<option value="hris_settings.php?tab=2">Salary Periods</option>
								<option value="hris_settings.php?tab=3" Selected>Salary Groups</option>
								<option value="hris_settings.php?tab=4">BIR Brackets</option>
								<option value="hris_settings.php?tab=5">GSIS Brackets</option>
								<option value="hris_settings.php?tab=6">PhilHealth Brackets</option>
								<option value="hris_settings.php?tab=7">SSS Brackets</option>
								<?php
							} elseif ($tab == 4) {
								?>
								<option value="hris_settings.php?tab=1">Salary Standardization</option>
								<option value="hris_settings.php?tab=2">Salary Periods</option>
								<option value="hris_settings.php?tab=3">Salary Groups</option>
								<option value="hris_settings.php?tab=4" Selected>BIR Brackets</option>
								<option value="hris_settings.php?tab=5">GSIS Brackets</option>
								<option value="hris_settings.php?tab=6">PhilHealth Brackets</option>
								<option value="hris_settings.php?tab=7">SSS Brackets</option>
								<?php
							} elseif ($tab == 5) {
								?>
								<option value="hris_settings.php?tab=1">Salary Standardization</option>
								<option value="hris_settings.php?tab=2">Salary Periods</option>
								<option value="hris_settings.php?tab=3">Salary Groups</option>
								<option value="hris_settings.php?tab=4">BIR Brackets</option>
								<option value="hris_settings.php?tab=5" Selected>GSIS Brackets</option>
								<option value="hris_settings.php?tab=6">PhilHealth Brackets</option>
								<option value="hris_settings.php?tab=7">SSS Brackets</option>
								<?php
							} elseif ($tab == 6) {
								?>
								<option value="hris_settings.php?tab=1">Salary Standardization</option>
								<option value="hris_settings.php?tab=2">Salary Periods</option>
								<option value="hris_settings.php?tab=3">Salary Groups</option>
								<option value="hris_settings.php?tab=4">BIR Brackets</option>
								<option value="hris_settings.php?tab=5">GSIS Brackets</option>
								<option value="hris_settings.php?tab=6" Selected>PhilHealth Brackets</option>
								<option value="hris_settings.php?tab=7">SSS Brackets</option>
								<?php
							} elseif ($tab == 7) {
								?>
								<option value="hris_settings.php?tab=1">Salary Standardization</option>
								<option value="hris_settings.php?tab=2">Salary Periods</option>
								<option value="hris_settings.php?tab=3">Salary Groups</option>
								<option value="hris_settings.php?tab=4">BIR Brackets</option>
								<option value="hris_settings.php?tab=5">GSIS Brackets</option>
								<option value="hris_settings.php?tab=6">PhilHealth Brackets</option>
								<option value="hris_settings.php?tab=7" Selected>SSS Brackets</option>
								<?php
							} else {
								?>
								<option value="hris_settings.php?tab=1">Salary Standardization</option>
								<option value="hris_settings.php?tab=2">Salary Periods</option>
								<option value="hris_settings.php?tab=3">Salary Groups</option>
								<option value="hris_settings.php?tab=4">BIR Brackets</option>
								<option value="hris_settings.php?tab=5">GSIS Brackets</option>
								<option value="hris_settings.php?tab=6">PhilHealth Brackets</option>
								<option value="hris_settings.php?tab=7">SSS Brackets</option>
								<?php
							}
							?>
						  </select>
					  </div>
					</div>

					<div class="clearfix"></div>

					<div class="row">
					
					  <div class="col-md-12 col-sm-12 col-xs-12">
						<div class="x_panel">
						  <div class="x_title">
							<?php
							if ($tab == 1) { ?><h2>Salary Standardization</h2><?php
							} elseif ($tab == 2) { ?><h2>Salary Periods</h2><?php
							} elseif ($tab == 3) { ?><h2>Salary Groups</h2><?php
							} elseif ($tab == 4) { ?><h2>BIR Brackets</h2><?php
							} elseif ($tab == 5) { ?><h2>GSIS Brackets</h2><?php
							} elseif ($tab == 6) { ?><h2>PhilHealth Brackets</h2><?php
							} elseif ($tab == 7) { ?><h2>SSS Brackets</h2><?php
							}
							?>
							
							<ul class="nav navbar-right panel_toolbox">
								<?php
								if ($tab == 1) { 
									?>
									<select class="form-control col-md-7 col-xs-12" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
									<?php
									$sql = "SELECT sid, eo_num FROM ssls";
									$res = query($sql); 
									while ($row = fetch_array($res)) {
										if ($sid == $row[0]) {
											?><option value="hris_settings.php?tab=1&sid=<?php echo $row[0] ?>" Selected><?php echo $row[1] ?></option><?php
										} else {
											?><option value="hris_settings.php?tab=1&sid=<?php echo $row[0] ?>"><?php echo $row[1] ?></option><?php
										}
									}
									free_result($res); 
									?>
									</select>
									<?php
								} elseif ($tab == 2) { 
									?>
									<?php
								} elseif ($tab == 3) { 
									?>
									<?php
								} elseif ($tab == 4) { 
									?>
									<?php
								} elseif ($tab == 5) { 
									?>
									<?php
								} elseif ($tab == 6) { 
									?>
									<?php
								}
								?>
							</ul>
							<div class="clearfix"></div>
						  </div>
						  <div class="x_content">
						  
						  <?php
							if ($tab == 1) {
								$sql = "SELECT tid, tnum FROM tranches";
								$tres = query($sql); 
								while ($trow = fetch_array($tres)) {
									?>
									<table><tr><td><h2>Tranche <?php echo $trow[1] ?></h2></td></tr></table><br />
									
									<table class="table table-striped table-bordered jambo_table">
									  <thead>
										<tr>
										  <th width="10%"></th>
										  <th width="10%">Salary Grade</th>
										  <th width="10%">Step 1</th>
										  <th width="10%">Step 2</th>
										  <th width="10%">Step 3</th>
										  <th width="10%">Step 4</th>
										  <th width="10%">Step 5</th>
										  <th width="10%">Step 6</th>
										  <th width="10%">Step 7</th>
										  <th width="10%">Step 8</th>
										</tr>
									  </thead>
									  <tbody>
										<?php
										$i = 1; 
										$sql = "SELECT *
												FROM salary_tranches
												WHERE tid = '$trow[1]'";
										$res = query($sql); 
										while ($row = fetch_array($res)) {
											?>
											<tr>
												<td align="center">
													<a href="e_salary_tranche.php?sid=<?php echo $row[0] ?>" class="btn btn-warning btn-xs"><i class="fa fa-pencil"></i></a>
													<a href="e_salary_tranche.php?sid=<?php echo $row[0] ?>" class="btn btn-danger btn-xs"><i class="fa fa-eraser"></i></a>
												</td>
												<td align="center"><?php echo $row[2] ?></td>
												<td align="right"><?php echo number_format($row[3], 0) ?></td>
												<td align="right"><?php echo number_format($row[4], 0) ?></td>
												<td align="right"><?php echo number_format($row[5], 0) ?></td>
												<td align="right"><?php echo number_format($row[6], 0) ?></td>
												<td align="right"><?php echo number_format($row[7], 0) ?></td>
												<td align="right"><?php echo number_format($row[8], 0) ?></td>
												<td align="right"><?php echo number_format($row[9], 0) ?></td>
												<td align="right"><?php echo number_format($row[10], 0) ?></td>
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
								free_result($tres); 
								?>
								<?php
							} elseif ($tab == 2) {
								?>
								<table class="table table-striped table-bordered jambo_table bulk_action">
									<thead>
									  <tr class="headings">
										  <th width="10%"></th>
										  <th width="18%">Payroll Group</th>
										  <th width="18%">Period Start</th>
										  <th width="18%">Period End</th>
										  <th width="18%">Salary Date</th>
										  <th width="18%">Working Days</th>
									  </tr>
									</thead>
									<tbody>
										<?php
										$sql = "SELECT p.sid, g.gname, DATE_FORMAT(p.sp_start, '%b %e, %Y'), 
													DATE_FORMAT(p.sp_end, '%b %e, %Y'), DATE_FORMAT(p.sp_date, '%b %e, %Y'), p.wdays
												FROM salary_periods p INNER JOIN payrolls_groups g ON p.gid = g.gid
												ORDER BY p.sp_date DESC";
										$res = query($sql); 
										while ($row = fetch_array($res)) {
											?>
											<tr>
												<td align="center">
													<a href="e_period.php?sid=<?php echo $row[0] ?>" class="btn btn-warning btn-xs"><i class="fa fa-pencil"></i></a>
													<a href="d_period.php?sid=<?php echo $row[0] ?>" class="btn btn-danger btn-xs"><i class="fa fa-eraser"></i></a>
												</td>
												<td align="center"><?php echo $row[1] ?></td>
												<td align="center"><?php echo $row[2] ?></td>
												<td align="center"><?php echo $row[3] ?></td>
												<td align="center"><?php echo $row[4] ?></td>
												<td align="center"><?php echo $row[5] ?></td>
											</tr>
											<?php
										}
										free_result($res); 
										?>
									</tbody>
								</table>
								<?php
							} elseif ($tab == 3) {
								?>
								<table class="table table-striped table-bordered jambo_table bulk_action">
									<thead>
									  <tr class="headings">
										  <th width="75%">Payroll Group</th>
										  <th width="25%"></th>
									  </tr>
									</thead>
									<tbody>
										<?php
										$sql = "SELECT gid, gname
												FROM payrolls_groups
												ORDER BY gid DESC";
										$res = query($sql); 
										while ($row = fetch_array($res)) {
											?>
											<tr>
												<td align="left"><?php echo $row[1] ?></td>
												<td align="center">
													<a href="e_group.php?gid=<?php echo $row[0] ?>" class="btn btn-warning btn-xs"><i class="fa fa-pencil"></i></a>
													<a href="d_group.php?gid=<?php echo $row[0] ?>" class="btn btn-danger btn-xs"><i class="fa fa-eraser"></i></a>
												</td>
											</tr>
											<?php
										}
										free_result($res); 
										?>
									</tbody>
								</table>
								<?php
							} elseif ($tab == 4) {
								?>
								<?php
							} elseif ($tab == 5) {
								?>
								<?php
							} elseif ($tab == 6) {
								?>
								<?php
							} elseif ($tab == 7) {
								?>
								<?php
							}

						  ?>
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

			<!-- Custom Theme Scripts -->
			<script src="build/js/custom.min.js"></script>

		  </body>
		</html>
		<?php
	}
} else {
	header('Location: login.php');	
}

