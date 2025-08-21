<?php

include("db.php");
include("sqli.php");
include("html.php");
define('PAGE', 'hris.php'); 

session_start();

if (isset($_SESSION['user_id'])) {
	if (validate_level(PAGE) == true) {

		// log the activity
		log_user(5, 'employees', 0);
		
		?>
		<!DOCTYPE html>
		<html lang="en">
		  <head>
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
			<!-- Meta, title, CSS, favicons, etc. -->
			<meta charset="utf-8">
			<meta http-equiv="X-UA-Compatible" content="IE=edge">
			<meta name="viewport" content="width=device-width, initial-scale=1">

			<title>OTC - Human Resource Information System</title>

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
				  
				
				?>

				<!-- page content -->
				<div class="right_col" role="main">
				  <div class="">
					<div class="page-title">
					  <div class="title_left">
						<h3>Human Resource Information System</h3>
					  </div>

					  <div class="title_right">
						<div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
						  <select class="form-control col-md-7 col-xs-12" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
							<?php
							if ($tab == 1) {
								?>
								<option value="hris.php?tab=1" Selected>Hirings</option>
								<option value="hris.php?tab=2">Promotions</option>
								<option value="hris.php?tab=3">Separations</option>
								<option value="hris.php?tab=4">Performance Management</option>
								<option value="hris.php?tab=5">Learning and Development</option>
								<option value="hris.php?tab=6">Records Management</option>
								<option value="hris.php?tab=7">Plantilla Items</option>
								<?php
							} elseif ($tab == 2) {
								?>
								<option value="hris.php?tab=1">Hirings</option>
								<option value="hris.php?tab=2" Selected>Promotions</option>
								<option value="hris.php?tab=3">Separations</option>
								<option value="hris.php?tab=4">Performance Management</option>
								<option value="hris.php?tab=5">Learning and Development</option>
								<option value="hris.php?tab=6">Records Management</option>
								<option value="hris.php?tab=7">Plantilla Items</option>
								<?php
							} elseif ($tab == 3) {
								?>
								<option value="hris.php?tab=1">Hirings</option>
								<option value="hris.php?tab=2">Promotions</option>
								<option value="hris.php?tab=3" Selected>Separations</option>
								<option value="hris.php?tab=4">Performance Management</option>
								<option value="hris.php?tab=5">Learning and Development</option>
								<option value="hris.php?tab=6">Records Management</option>
								<option value="hris.php?tab=7">Plantilla Items</option>
								<?php
							} elseif ($tab == 4) {
								?>
								<option value="hris.php?tab=1">Hirings</option>
								<option value="hris.php?tab=2">Promotions</option>
								<option value="hris.php?tab=3">Separations</option>
								<option value="hris.php?tab=4" Selected>Performance Management</option>
								<option value="hris.php?tab=5">Learning and Development</option>
								<option value="hris.php?tab=6">Records Management</option>
								<option value="hris.php?tab=7">Plantilla Items</option>
								<?php
							} elseif ($tab == 5) {
								?>
								<option value="hris.php?tab=1">Hirings</option>
								<option value="hris.php?tab=2">Promotions</option>
								<option value="hris.php?tab=3">Separations</option>
								<option value="hris.php?tab=4">Performance Management</option>
								<option value="hris.php?tab=5" Selected>Learning and Development</option>
								<option value="hris.php?tab=6">Records Management</option>
								<option value="hris.php?tab=7">Plantilla Items</option>
								<?php
							} elseif ($tab == 6) {
								?>
								<option value="hris.php?tab=1">Hirings</option>
								<option value="hris.php?tab=2">Promotions</option>
								<option value="hris.php?tab=3">Separations</option>
								<option value="hris.php?tab=4">Performance Management</option>
								<option value="hris.php?tab=5">Learning and Development</option>
								<option value="hris.php?tab=6" Selected>Records Management</option>
								<option value="hris.php?tab=7">Plantilla Items</option>
								<?php
							} elseif ($tab == 7) {
								?>
								<option value="hris.php?tab=1">Hirings</option>
								<option value="hris.php?tab=2">Promotions</option>
								<option value="hris.php?tab=3">Separations</option>
								<option value="hris.php?tab=4">Performance Management</option>
								<option value="hris.php?tab=5">Learning and Development</option>
								<option value="hris.php?tab=6">Records Management</option>
								<option value="hris.php?tab=7" Selected>Plantilla Items</option>
								<?php
							} else {
								?>
								<option value="hris.php?tab=1">Hirings</option>
								<option value="hris.php?tab=2">Promotions</option>
								<option value="hris.php?tab=3">Separations</option>
								<option value="hris.php?tab=4">Performance Management</option>
								<option value="hris.php?tab=5">Learning and Development</option>
								<option value="hris.php?tab=6">Records Management</option>
								<option value="hris.php?tab=7">Plantilla Items</option>
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
								  <strong>Success!</strong> Employee has been added into the database.
								</div>
							  <?php
						  }
					  }
					  
					  ?>

					  <div class="col-md-12 col-sm-12 col-xs-12">
						<div class="x_panel">
						  <div class="x_title">
							<?php
							if ($tab == 1) { ?><h2>Hirings</h2><?php
							} elseif ($tab == 2) { ?><h2>Promotions</h2><?php
							} elseif ($tab == 3) { ?><h2>Separations</h2><?php
							} elseif ($tab == 4) { ?><h2>Performance Management</h2><?php
							} elseif ($tab == 5) { ?><h2>Learning and Development</h2><?php
							} elseif ($tab == 6) { ?><h2>Records Management</h2><?php
							} elseif ($tab == 7) { ?><h2>Plantilla Items</h2><?php
							}
							?>
							
							<ul class="nav navbar-right panel_toolbox">
								<?php
								if ($tab == 1) { ?><a href="add_hiring.php" class="btn btn-primary btn-md"><i class="fa fa-plus-square"></i> Add Hiring </a><?php
								} elseif ($tab == 2) { ?><a href="add_promotion.php" class="btn btn-primary btn-md"><i class="fa fa-plus-square"></i> Add Promotion </a><?php
								} elseif ($tab == 3) { ?><a href="add_separation.php" class="btn btn-primary btn-md"><i class="fa fa-plus-square"></i> Add Separation </a><?php
								} elseif ($tab == 4) { ?><a href="add_performance.php" class="btn btn-primary btn-md"><i class="fa fa-plus-square"></i> Add Employee's Performance </a><?php
								} elseif ($tab == 5) { ?><a href="add_training.php" class="btn btn-primary btn-md"><i class="fa fa-plus-square"></i> Add Employee-Training </a><?php
								} elseif ($tab == 6) { ?><?php
								} elseif ($tab == 7) { ?><a href="add_plantilla.php" class="btn btn-primary btn-md"><i class="fa fa-plus-square"></i> Add Plantilla Item </a><?php
								}
								?>
							</ul>
							<div class="clearfix"></div>
						  </div>
						  <div class="x_content">
						  
							<?php
							if ($tab == 1) {
								?>
								<table id="datatable-fixed-header" class="table table-striped table-bordered jambo_table">
								  <thead>
									<tr>
									  <th width="10%"></th>
									  <th width="15%">Employee</th>
									  <th width="20%">Position</th>
									  <th width="08%">Grade</th>
									  <th width="10%">Status</th>
									  <th width="08%">Date</th>
									  <th width="17%">Encoder</th>
									  <th width="12%">Updated</th>
									</tr>
								  </thead>
								  <tbody>
									<?php
									$i = 1; 
									$sql = "SELECT h.hid, CONCAT(e.lname,', ',e.fname), p.pname, 
												g.sg_num, h.status, DATE_FORMAT(h.hdate, '%m/%d/%y'),
												CONCAT(U.lname,', ',U.fname), 
												DATE_FORMAT(h.user_dtime, '%m/%d/%y %h:%i%p')
											FROM employees_hirings h
												INNER JOIN employees e ON h.eid = e.eid
												INNER JOIN positions p ON h.pid = p.pid
												INNER JOIN salary_grades g ON h.sgid = g.sgid
												INNER JOIN users u ON h.user_id = u.user_id
											ORDER BY h.hdate DESC";
									$res = query($sql); 
									while ($row = fetch_array($res)) {
										?>
										<tr>
											<td align="center">
												<a href="e_hiring.php?hid=<?php echo $row[0] ?>" class="btn btn-warning btn-xs"><i class="fa fa-pencil"></i></a>
												<a href="d_hiring.php?hid=<?php echo $row[0] ?>" class="btn btn-danger btn-xs"><i class="fa fa-eraser"></i></a>
											</td>
											<td align="left"><?php echo $row[1] ?></td>
											<td align="left"><?php echo $row[2] ?></td>
											<td align="center"><?php echo $row[3] ?></td>
											<td align="center"><?php 
												if ($row[4] == 1) {
													echo 'Appointee';
												} elseif ($row[4] == 2) {
													echo 'Permanent';
												} elseif ($row[4] == 3) {
													echo 'Job Order';
												} elseif ($row[4] == 4) {
													echo 'COS';
												}
												?></td>
											<td align="center"><?php echo $row[5] ?></td>
											<td align="left"><?php echo $row[6] ?></td>
											<td align="center"><?php echo $row[7] ?></td>
										</tr>
										<?php
										$i++; 
									}
									free_result($res); 
									?>
								  </tbody>
								</table>
								<?php
							} elseif ($tab == 2) {
								?>
								<table id="datatable-fixed-header" class="table table-striped table-bordered jambo_table">
								  <thead>
									<tr>
									  <th width="10%"></th>
									  <th width="12%">Employee</th>
									  <th width="15%">Position</th>
									  <th width="08%">Grade</th>
									  <th width="10%">Status</th>
									  <th width="08%">Date</th>
									  <th width="10%">Mode</th>
									  <th width="15%">Encoder</th>
									  <th width="12%">Updated</th>
									</tr>
								  </thead>
								  <tbody>
									<?php
									$i = 1; 
									$sql = "SELECT pr.epid, CONCAT(e.lname,', ',e.fname), p.pname, 
												g.sg_num, pr.status, DATE_FORMAT(pr.adate, '%m/%d/%y'),
												pr.mode, CONCAT(u.lname,', ',u.fname), 
												DATE_FORMAT(pr.user_dtime, '%m/%d/%y %h:%i%p')
											FROM employees_promotions pr
												INNER JOIN employees e ON pr.eid = e.eid
												INNER JOIN positions p ON pr.pid = p.pid
												INNER JOIN salary_grades g ON pr.sgid = g.sgid
												INNER JOIN users u ON pr.user_id = u.user_id
											ORDER BY pr.adate DESC";
									$res = query($sql); 
									while ($row = fetch_array($res)) {
										?>
										<tr>
											<td align="center">
												<a href="e_promotion.php?epid=<?php echo $row[0] ?>" class="btn btn-warning btn-xs"><i class="fa fa-pencil"></i></a>
												<a href="d_promotion.php?epid=<?php echo $row[0] ?>" class="btn btn-danger btn-xs"><i class="fa fa-eraser"></i></a>
											</td>
											<td align="left"><?php echo $row[1] ?></td>
											<td align="left"><?php echo $row[2] ?></td>
											<td align="center"><?php echo $row[3] ?></td>
											<td align="center"><?php 
												if ($row[4] == 1) {
													echo 'Permanent';
												}
												?></td>
											<td align="center"><?php echo $row[5] ?></td>
											<td align="center"><?php 
												if ($row[6] == 1) { echo 'Orig. Appointment';
												} elseif ($row[6] == 2) { echo 'Transfer';
												} elseif ($row[6] == 3) { echo 'Promotion';
												}
												?></td>
											<td align="center"><?php echo $row[7] ?></td>
											<td align="center"><?php echo $row[8] ?></td>
										</tr>
										<?php
										$i++; 
									}
									free_result($res); 
									?>
								  </tbody>
								</table>
								<?php
							} elseif ($tab == 3) {
								?>
								<table id="datatable-fixed-header" class="table table-striped table-bordered jambo_table">
								  <thead>
									<tr>
									  <th width="10%"></th>
									  <th width="12%">Employee</th>
									  <th width="15%">Position</th>
									  <th width="08%">Grade</th>
									  <th width="10%">Status</th>
									  <th width="08%">Date</th>
									  <th width="10%">Mode</th>
									  <th width="15%">Encoder</th>
									  <th width="12%">Updated</th>
									</tr>
								  </thead>
								  <tbody>
									<?php
									$i = 1; 
									$sql = "SELECT s.sid, CONCAT(e.lname,', ',e.fname), p.pname, 
												g.sg_num, s.status, DATE_FORMAT(s.sdate, '%m/%d/%y'),
												s.mode, CONCAT(u.lname,', ',u.fname), 
												DATE_FORMAT(s.user_dtime, '%m/%d/%y %h:%i%p')
											FROM employees_separations s
												INNER JOIN employees e ON s.eid = e.eid
												INNER JOIN positions p ON s.pid = p.pid
												INNER JOIN salary_grades g ON s.sgid = g.sgid
												INNER JOIN users u ON s.user_id = u.user_id
											ORDER BY s.sdate DESC";
									$res = query($sql); 
									while ($row = fetch_array($res)) {
										?>
										<tr>
											<td align="center">
												<a href="e_separation.php?sid=<?php echo $row[0] ?>" class="btn btn-warning btn-xs"><i class="fa fa-pencil"></i></a>
												<a href="d_separation.php?sid=<?php echo $row[0] ?>" class="btn btn-danger btn-xs"><i class="fa fa-eraser"></i></a>
											</td>
											<td align="left"><?php echo $row[1] ?></td>
											<td align="left"><?php echo $row[2] ?></td>
											<td align="center"><?php echo $row[3] ?></td>
											<td align="center"><?php 
												if ($row[4] == 1) {
													echo 'Permanent';
												}
												?></td>
											<td align="center"><?php echo $row[5] ?></td>
											<td align="center"><?php 
												if ($row[6] == 1) { echo 'Transfer';
												} elseif ($row[6] == 2) { echo 'Retirement';
												} elseif ($row[6] == 3) { echo 'Resignation';
												} elseif ($row[6] == 4) { echo 'Termination';
												} elseif ($row[6] == 5) { echo 'Dismissal';
												} elseif ($row[6] == 6) { echo 'Dropped';
												}
												?></td>
											<td align="center"><?php echo $row[7] ?></td>
											<td align="center"><?php echo $row[8] ?></td>
										</tr>
										<?php
										$i++; 
									}
									free_result($res); 
									?>
								  </tbody>
								</table>
								<?php
							} elseif ($tab == 4) {
								?>
								<table id="datatable-fixed-header" class="table table-striped table-bordered jambo_table">
								  <thead>
									<tr>
									  <th width="10%"></th>
									  <th width="08%">Year</th>
									  <th width="08%">Semester</th>
									  <th width="16%">Employee</th>
									  <th width="20%">Position</th>
									  <th width="08%">Rating</th>
									  <th width="16%">Encoder</th>
									  <th width="14%">Updated</th>
									</tr>
								  </thead>
								  <tbody>
									<?php
									$i = 1; 
									$sql = "SELECT r.rid, r.ryear, r.sem, 
												CONCAT(e.lname,', ',e.fname), p.pname, 
												r.rating, CONCAT(u.lname,', ',u.fname), 
												DATE_FORMAT(r.user_dtime, '%m/%d/%y %h:%i%p')
											FROM employees_ratings r
												INNER JOIN employees e ON r.eid = e.eid
												INNER JOIN positions p ON r.pid = p.pid
												INNER JOIN users u ON r.user_id = u.user_id
											ORDER BY r.ryear DESC, r.sem DESC";
									$res = query($sql); 
									while ($row = fetch_array($res)) {
										?>
										<tr>
											<td align="center">
												<a href="e_performance.php?rid=<?php echo $row[0] ?>" class="btn btn-warning btn-xs"><i class="fa fa-pencil"></i></a>
												<a href="d_performance.php?rid=<?php echo $row[0] ?>" class="btn btn-danger btn-xs"><i class="fa fa-eraser"></i></a>
											</td>
											<td align="center"><?php echo $row[1] ?></td>
											<td align="center"><?php echo $row[2] == 1 ? $row[2].'st' : $row[2].'nd' ?></td>
											<td align="left"><?php echo $row[3] ?></td>
											<td align="left"><?php echo $row[4] ?></td>
											<td align="center"><?php echo $row[5] ?></td>
											<td align="left"><?php echo $row[6] ?></td>
											<td align="center"><?php echo $row[7] ?></td>
										</tr>
										<?php
										$i++; 
									}
									free_result($res); 
									?>
								  </tbody>
								</table>
								<?php
							} elseif ($tab == 5) {
								?>
								<table id="datatable-fixed-header" class="table table-striped table-bordered jambo_table">
								  <thead>
									<tr>
									  <th rowspan="2" width="18%">Employee</th>
									  <th rowspan="2" width="34%">Title of Learning & Development / Training</th>
									  <th colspan="2">Inclusive Dates</th>
									  <th rowspan="2" width="08%">No. of Hours</th>
									  <th rowspan="2" width="10%">Type of L&D</th>
									  <th rowspan="2" width="16%">Sponsored By</th>
									</tr>
									<tr>
									  <th width="07%">From</th>
									  <th width="07%">To</th>
									</tr>
								  </thead>
								  <tbody>
									<?php
									$i = 1; 
									$sql = "SELECT CONCAT(e.lname,', ',e.fname), t.tname, DATE_FORMAT(t.tstart, '%m/%d/%y'), 
												DATE_FORMAT(t.tend, '%m/%d/%y'), t.nhours, t.ttype, t.sponsor
											FROM employees_trainings t 
												INNER JOIN employees e ON t.eid = e.eid
											ORDER BY t.tstart DESC, e.lname";
									$res = query($sql); 
									while ($row = fetch_array($res)) {
										?>
										<tr>
											<td align="left"><?php echo $row[0] ?></td>
											<td align="left"><?php echo $row[1] ?></td>
											<td align="center"><?php echo $row[2] ?></td>
											<td align="center"><?php echo $row[3] ?></td>
											<td align="right"><?php echo $row[4] ?></td>
											<td align="center"><?php echo $row[5] ?></td>
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
							} elseif ($tab == 6) {
								?>
								<?php
							} elseif ($tab == 7) {
								?>
								<table id="datatable-fixed-header" class="table table-striped table-bordered jambo_table">
								  <thead>
									<tr>
									  <th width="10%"></th>
									</tr>
								  </thead>
								  <tbody>
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
