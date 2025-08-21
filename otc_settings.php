<?php

include("db.php");
include("sqli.php");
include("html.php");
define('PAGE', 'otc_settings.php'); 

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

			<title>OTC Settings</title>

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
						<h3>OTC Settings</h3>
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
					
					  <div class="col-md-6 col-sm-6 col-xs-12">
						<div class="x_panel">
						  <div class="x_title">
							<h2>OTC Divisions</h2>
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
									<th width="50%">Division Name</th>
									<th width="10%">Code</th>
									<th width="25%">Head</th>
									<th width="15%"></th>
								</tr>
							  </thead>
							  <tbody>
								<?php
								$sql = "SELECT d.did, d.dname, d.dcode, CONCAT(e.lname,', ',e.fname)
										FROM departments d INNER JOIN employees e ON d.head = e.eid
										ORDER BY d.dname";
								$res = query($sql); 
								while ($row = fetch_array($res)) {
									?>
									<tr>
										<td align="left"><?php echo $row[1] ?></td>
										<td align="center"><?php echo $row[2] ?></td>
										<td align="left"><?php echo $row[3] ?></td>
										<td align="center">
											<a href="e_department.php?did=<?php echo $row[0] ?>" class="btn btn-warning btn-xs"><i class="fa fa-pencil"></i></a>&nbsp; &nbsp;
											<a href="d_department.php?did=<?php echo $row[0] ?>" class="btn btn-danger btn-xs"><i class="fa fa-eraser"></i></a>
										</td>
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

					  <div class="col-md-6 col-sm-6 col-xs-12">
						<div class="x_panel">
						  <div class="x_title">
							<h2>Suppliers</h2>
							<ul class="nav navbar-right panel_toolbox">
							  <li><a href="add_supplier.php"><i class="fa fa-plus"></i></a></li>
							  <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
							  <li><a class="close-link"><i class="fa fa-close"></i></a></li>
							</ul>
							<div class="clearfix"></div>
						  </div>
						  <div class="x_content">

							<table class="table table-striped table-bordered jambo_table">
							  <thead>
								<tr>
									<th width="17%">T.I.N.</th>
									<th width="50%">Company</th>
									<th width="20%">Contact #</th>
									<th width="12%"></th>
								</tr>
							  </thead>
							  <tbody>
								<?php
								$sql = "SELECT sid, tin, company, phone
										FROM suppliers
										ORDER BY company";
								$res = query($sql); 
								while ($row = fetch_array($res)) {
									?>
									<tr>
										<td align="left"><?php echo $row[1] ?></td>
										<td align="left"><?php echo $row[2] ?></td>
										<td align="left"><?php echo $row[3] ?></td>
										<td align="center">
											<a href="e_supplier.php?sid=<?php echo $row[0] ?>" class="btn btn-warning btn-xs"><i class="fa fa-pencil"></i></a>&nbsp; &nbsp;
											<a href="d_supplier.php?sid=<?php echo $row[0] ?>" class="btn btn-danger btn-xs"><i class="fa fa-eraser"></i></a>
										</td>
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
					  
					  
					  <div class="col-md-6 col-sm-6 col-xs-12">
						<div class="x_panel">
						  <div class="x_title">
							<h2>Salary Grades</h2>
							<ul class="nav navbar-right panel_toolbox">
							  <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
							  <li><a class="close-link"><i class="fa fa-close"></i></a></li>
							</ul>
							<div class="clearfix"></div>
						  </div>
						  <div class="x_content">


						  </div>
						</div>
					  </div>

					  <div class="col-md-6 col-sm-6 col-xs-12">
						<div class="x_panel">
						  <div class="x_title">
							<h2>Types of Employees</h2>
							<ul class="nav navbar-right panel_toolbox">
							  <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
							  <li><a class="close-link"><i class="fa fa-close"></i></a></li>
							</ul>
							<div class="clearfix"></div>
						  </div>
						  <div class="x_content">


						  </div>
						</div>
					  </div>

					  <div class="col-md-6 col-sm-6 col-xs-12">
						<div class="x_panel">
						  <div class="x_title">
							<h2>OTC Positions</h2>
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
									<th width="70%">Position Name</th>
									<th width="15%">Salary Grade</th>
									<th width="15%"></th>
								</tr>
							  </thead>
							  <tbody>
								<?php
								$sql = "SELECT p.pid, p.pname, g.sg_num
										FROM positions p LEFT JOIN salary_grades g ON p.sgid = g.sgid
										ORDER BY p.pname";
								$res = query($sql); 
								while ($row = fetch_array($res)) {
									?>
									<tr>
										<td align="left"><?php echo $row[1] ?></td>
										<td align="center"><?php echo $row[2] ?></td>
										<td align="center">
											<a href="e_grade.php?pid=<?php echo $row[0] ?>" class="btn btn-warning btn-xs"><i class="fa fa-pencil"></i></a>&nbsp; &nbsp;
											<a href="d_grade.php?pid=<?php echo $row[0] ?>" class="btn btn-danger btn-xs"><i class="fa fa-eraser"></i></a>
										</td>
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

