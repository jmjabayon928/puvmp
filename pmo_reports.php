<?php

include("db.php");
include("sqli.php");
include("html.php");
define('PAGE', 'pmo_reports.php'); 

session_start();

if (isset($_SESSION['user_id'])) {
	if (validate_level(PAGE) == true) {

		// log the activity
		log_user(5, 'pmo_reports', 0);
		
		?>
		<!DOCTYPE html>
		<html lang="en">
		  <head>
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
			<!-- Meta, title, CSS, favicons, etc. -->
			<meta charset="utf-8">
			<meta http-equiv="X-UA-Compatible" content="IE=edge">
			<meta name="viewport" content="width=device-width, initial-scale=1">

			<title>OTC - PMO Reports</title>

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
				
				?>

				<!-- page content -->
				<div class="right_col" role="main">
				  <div class="">
					<div class="page-title">
					  <div class="title_left">
						<h3>PMO Accomplishment Reports</h3>
					  </div>

					  <div class="title_right">
						<div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
						</div>
					  </div>
					</div>

					<div class="clearfix"></div>

					<div class="row">
						<?php
						  if (isset($_GET['added_pmo_report'])) {
							  if ($_GET['added_pmo_report'] == 1) {
								  ?>
									<div class="alert alert-success">
									  <strong>Success!</strong> Accomplishment report has been added into the database.
									</div>
								  <?php
							  }
						  }
						  if (isset($_GET['updated_pmo_report'])) {
							  if ($_GET['updated_pmo_report'] == 1) {
								  ?>
									<div class="alert alert-warning">
									  <strong>Success!</strong> Accomplishment report has been updated.
									</div>
								  <?php
							  }
						  }
						  if (isset($_GET['deleted_pmo_report'])) {
							  if ($_GET['deleted_pmo_report'] == 1) {
								  ?>
									<div class="alert alert-danger">
									  <strong>Success!</strong> Accomplishment report has been deleted.
									</div>
								  <?php
							  }
						  }
						
						?>
					
					  <div class="col-md-12 col-sm-12 col-xs-12">
						<div class="x_panel">
						  <div class="x_title">
							<ul class="nav navbar-right panel_toolbox">
								<a href="add_pmo_report.php" class="btn btn-primary btn-md" data-toggle="tooltip" data-placement="top" title="Add Accomplishment Report"><i class="fa fa-plus-square"></i> Add </a>
							</ul>
							<div class="clearfix"></div>
						  </div>
						  <div class="x_content">

							    <table id="datatable-fixed-header" class="table table-striped table-bordered jambo_table">
								  <thead>
									<tr>
									  <th width="10%"></th>
									  <th width="20%">Regional Office</th>
									  <th width="25%">Employee</th>
									  <th width="15%">Coverage Start</th>
									  <th width="15%">Coverage End</th>
									  <th width="15%">Submitted</th>
									</tr>
								  </thead>
								  <tbody>
								    <?php
									$users = array(34, 36, 38, 85, 101, 102); 
								  
									if ($_SESSION['ulevel'] == 1 || $_SESSION['ulevel'] == 2) { // if admin / super user
										$sql = "SELECT r.rid, o.oname, CONCAT(e.lname,', ',e.fname), 
													DATE_FORMAT(r.rstart, '%e %b %Y'),
													DATE_FORMAT(r.rend, '%e %b %Y'),
													DATE_FORMAT(r.user_dtime, '%e %b %Y %h:%i %p')
												FROM pmo_reports r 
													INNER JOIN employees e ON r.eid = e.eid 
													INNER JOIN pmo_offices o ON e.oid = o. oid 
												ORDER BY r.rstart DESC, o.oname, e.lname";
									} elseif (in_array($_SESSION['user_id'], $users)) { // if user is in the allowed list
										$sql = "SELECT r.rid, o.oname, CONCAT(e.lname,', ',e.fname), 
													DATE_FORMAT(r.rstart, '%e %b %Y'),
													DATE_FORMAT(r.rend, '%e %b %Y'),
													DATE_FORMAT(r.user_dtime, '%e %b %Y %h:%i %p')
												FROM pmo_reports r 
													INNER JOIN employees e ON r.eid = e.eid 
													INNER JOIN pmo_offices o ON e.oid = o. oid 
												ORDER BY r.rstart DESC, o.oname, e.lname";
									} else { // user is RDO
										$sql = "SELECT r.rid, o.oname, CONCAT(e.lname,', ',e.fname), 
													DATE_FORMAT(r.rstart, '%e %b %Y'),
													DATE_FORMAT(r.rend, '%e %b %Y'),
													DATE_FORMAT(r.user_dtime, '%e %b %Y %h:%i %p')
												FROM pmo_reports r 
													INNER JOIN employees e ON r.eid = e.eid 
													INNER JOIN pmo_offices o ON e.oid = o. oid 
													INNER JOIN users u ON u.eid = e.eid 
												WHERE u.user_id = '$_SESSION[user_id]'
												ORDER BY r.rstart DESC, o.oname, e.lname";
									}
									$res = query($sql); 
									while ($row = fetch_array($res)) {
										?>
										<tr>
											<td align="center">
												<a href="pmo_report.php?rid=<?php echo $row[0] ?>" class="btn btn-info btn-xs" data-toggle="tooltip" data-placement="top" title="Update Accomplishment Report"><i class="fa fa-search"></i></a>
												<a href="e_pmo_report.php?rid=<?php echo $row[0] ?>" class="btn btn-warning btn-xs" data-toggle="tooltip" data-placement="top" title="Update Accomplishment Report"><i class="fa fa-pencil"></i></a>
												<a href="d_pmo_report.php?rid=<?php echo $row[0] ?>" class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top" title="Delete Accomplishment Report"><i class="fa fa-eraser"></i></a>
											</td>
											<td align="left"><?php echo $row[1] ?></td>
											<td align="left"><?php echo $row[2] ?></td>
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
