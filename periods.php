<?php

include("db.php");
include("sqli.php");
include("html.php");
define('PAGE', 'periods.php'); 

session_start();

if (isset($_SESSION['user_id'])) {
	if (validate_level(PAGE) == true) {

		// log the activity
		log_user(5, 'periods', 0);
		
		?>
		<!DOCTYPE html>
		<html lang="en">
		  <head>
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
			<!-- Meta, title, CSS, favicons, etc. -->
			<meta charset="utf-8">
			<meta http-equiv="X-UA-Compatible" content="IE=edge">
			<meta name="viewport" content="width=device-width, initial-scale=1">
			  
			<title>OTC | Salary Periods</title>

			<!-- Bootstrap -->
			<link href="vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
			<!-- Font Awesome -->
			<link href="vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
			<!-- NProgress -->
			<link href="vendors/nprogress/nprogress.css" rel="stylesheet">
			<!-- iCheck -->
			<link href="vendors/iCheck/skins/flat/green.css" rel="stylesheet">
			<!-- bootstrap-wysiwyg -->
			<link href="vendors/google-code-prettify/bin/prettify.min.css" rel="stylesheet">
			<!-- Select2 -->
			<link href="vendors/select2/dist/css/select2.min.css" rel="stylesheet">
			<!-- Switchery -->
			<link href="vendors/switchery/dist/switchery.min.css" rel="stylesheet">
			<!-- starrr -->
			<link href="vendors/starrr/dist/starrr.css" rel="stylesheet">
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
						<h3>Salary Periods</h3>
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
					<div class="row"> <!-- container of all tables -->
					  
					  <?php
					  $start_year = 2018; 
					  
					  if (isset ($_GET['given_year'])) {
						  $given_year = $_GET['given_year']; 
					  } else {
						  $sql = "SELECT YEAR(NOW())";
						  $gres = query($sql); 
						  $grow = fetch_array($gres); 
						  $given_year = $grow[0]; 
						  free_result($gres); 
					  }
					  
					  $sql = "SELECT YEAR(NOW())";
					  $yres = query($sql); 
					  $yrow = fetch_array($yres); 
					  $year_now = $yrow[0]; 
					  free_result($yres); 
					  ?>
					  <!-- Salary Periods -->
					  <div class="col-md-6 col-sm-6 col-xs-12">
						<div class="x_panel">
						  <div class="x_title">
							<h2>Salary Periods Within <?php echo $given_year ?></h2>
							<ul class="nav navbar-right panel_toolbox">
							  <a href="add_period.php" class="btn btn-primary btn-md"><i class="fa fa-plus-square"></i> Add</a>
							</ul>
							<div class="clearfix"></div>
						  </div>
						  <div class="x_content">
							<table class="table table-striped table-bordered jambo_table bulk_action">
								<thead>
								  <tr class="headings">
									  <th width="25%">Period Start</th>
									  <th width="25%">Period End</th>
									  <th width="25%">Salary Date</th>
									  <th width="25%"></th>
								  </tr>
								</thead>
								<tbody>
									<?php
									$sql = "SELECT spid, DATE_FORMAT(sp_start, '%b %e, %Y'), 
												DATE_FORMAT(sp_end, '%b %e, %Y'), DATE_FORMAT(sp_date, '%b %e, %Y')
											FROM salary_periods
											ORDER BY spid DESC";
									$res = query($sql); 
									while ($row = fetch_array($res)) {
										?>
										<tr>
											<td align="center"><?php echo $row[1] ?></td>
											<td align="center"><?php echo $row[2] ?></td>
											<td align="center"><?php echo $row[3] ?></td>
											<td align="center">
												<a href="period_attendance.php?tab=2&spid=<?php echo $row[0] ?>" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i></a>
												<a href="e_period.php?spid=<?php echo $row[0] ?>" class="btn btn-warning btn-xs"><i class="fa fa-pencil"></i></a>
												<a href="d_period.php?spid=<?php echo $row[0] ?>" class="btn btn-danger btn-xs"><i class="fa fa-eraser"></i></a>
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
					  <!-- end of TC Basic Info -->
					
					  <!-- Payroll Groups -->
					  <div class="col-md-6 col-sm-6 col-xs-12">
						<div class="x_panel">
						  <div class="x_title">
							<h2>Payroll Groups</h2>
							<ul class="nav navbar-right panel_toolbox">
							  <a href="add_group.php" class="btn btn-primary btn-md"><i class="fa fa-plus-square"></i> Add</a>
							</ul>
							<div class="clearfix"></div>
						  </div>
						  <div class="x_content">
							<table class="table table-striped table-bordered jambo_table bulk_action">
								<thead>
								  <tr class="headings">
									  <th width="75%">Payroll Group</th>
									  <th width="25%"></th>
								  </tr>
								</thead>
								<tbody>
									<?php
									$sql = "SELECT pg_id, pg_name
											FROM payrolls_groups
											ORDER BY pg_id DESC";
									$res = query($sql); 
									while ($row = fetch_array($res)) {
										?>
										<tr>
											<td align="left"><?php echo $row[1] ?></td>
											<td align="center">
												<a href="e_group.php?pg_id=<?php echo $row[0] ?>" class="btn btn-warning btn-xs"><i class="fa fa-pencil"></i></a>
												<a href="d_group.php?pg_id=<?php echo $row[0] ?>" class="btn btn-danger btn-xs"><i class="fa fa-eraser"></i></a>
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
					  <!-- end of Payroll Groups -->
					</div><!-- end of container -->

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
			<!-- bootstrap-progressbar -->
			<script src="vendors/bootstrap-progressbar/bootstrap-progressbar.min.js"></script>
			<!-- iCheck -->
			<script src="vendors/iCheck/icheck.min.js"></script>
			<!-- bootstrap-daterangepicker -->
			<script src="vendors/moment/min/moment.min.js"></script>
			<script src="vendors/bootstrap-daterangepicker/daterangepicker.js"></script>
			<!-- bootstrap-wysiwyg -->
			<script src="vendors/bootstrap-wysiwyg/js/bootstrap-wysiwyg.min.js"></script>
			<script src="vendors/jquery.hotkeys/jquery.hotkeys.js"></script>
			<script src="vendors/google-code-prettify/src/prettify.js"></script>
			<!-- jQuery Tags Input -->
			<script src="vendors/jquery.tagsinput/src/jquery.tagsinput.js"></script>
			<!-- Switchery -->
			<script src="vendors/switchery/dist/switchery.min.js"></script>
			<!-- Select2 -->
			<script src="vendors/select2/dist/js/select2.full.min.js"></script>
			<!-- Parsley -->
			<script src="vendors/parsleyjs/dist/parsley.min.js"></script>
			<!-- Autosize -->
			<script src="vendors/autosize/dist/autosize.min.js"></script>
			<!-- jQuery autocomplete -->
			<script src="vendors/devbridge-autocomplete/dist/jquery.autocomplete.min.js"></script>
			<!-- starrr -->
			<script src="vendors/starrr/dist/starrr.js"></script>
			<!-- Custom Theme Scripts -->
			<script src="build/js/custom.min.js"></script>
			
		  </body>
		</html>
		<?php
	}
} else {
	header('Location: login.php');	
}


