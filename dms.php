<?php

include("db.php");
include("sqli.php");
include("html.php");
define('PAGE', 'dms.php'); 

session_start();

if (isset($_SESSION['user_id'])) {
	if (validate_level3(PAGE) == true) {

		// log the activity
		log_user(5, 'dms', 0);
		
		?>
		<!DOCTYPE html>
		<html lang="en">
		  <head>
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
			<!-- Meta, title, CSS, favicons, etc. -->
			<meta charset="utf-8">
			<meta http-equiv="X-UA-Compatible" content="IE=edge">
			<meta name="viewport" content="width=device-width, initial-scale=1">

			<title>OTC - Disposition Mission Slips</title>

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
				
				if (isset ($_GET['yyyymm'])) {
					$yyyymm = $_GET['yyyymm'];
					$gyear = substr($_GET['yyyymm'], 0, 4);
					$gmonth = substr($_GET['yyyymm'], 4, 2);
				} else {
					$gyear = date('Y');
					$gmonth = date('m');
					$yyyymm = date('Ym');
				}
				
				?>

				<!-- page content -->
				<div class="right_col" role="main">
				  <div class="">
					<div class="page-title">
					  <div class="title_left">
						<h3>Disposition Mission Slips</h3>
					  </div>

					  <div class="title_right">
						<div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
							<select class="form-control col-md-7 col-xs-12" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
								<option value="">Month and Year</option>
								<?php
								$sql = "SELECT yyyymm, month_year FROM year_months ORDER BY yyyymm DESC";
								$ymres = query($sql); 
								while ($ymrow = fetch_array($ymres)) {
									if ($yyyymm == $ymrow[0]) {
										?><option value="dms.php?yyyymm=<?php echo $ymrow[0] ?>&tab=<?php echo $tab ?>" Selected><?php echo $ymrow[1] ?></option><?php
									} else {
										?><option value="dms.php?yyyymm=<?php echo $ymrow[0] ?>&tab=<?php echo $tab ?>"><?php echo $ymrow[1] ?></option><?php
									}
								}
								free_result($ymres); 
								?>
							</select>
						</div>
					  </div>
					</div>

					<div class="clearfix"></div>

					<div class="row">
						<?php
						  if (isset($_GET['added_dms'])) {
							  if ($_GET['added_dms'] == 1) {
								  ?>
									<div class="alert alert-success">
									  <strong>Success!</strong> Disposition Mission Slip has been added into the database.
									</div>
								  <?php
							  }
						  }
						  if (isset($_GET['updated_dms'])) {
							  if ($_GET['updated_dms'] == 1) {
								  ?>
									<div class="alert alert-warning">
									  <strong>Success!</strong> Disposition Mission Slip has been updated.
									</div>
								  <?php
							  }
						  }
						  if (isset($_GET['deleted_dms'])) {
							  if ($_GET['deleted_dms'] == 1) {
								  ?>
									<div class="alert alert-danger">
									  <strong>Success!</strong> Disposition Mission Slip has been deleted.
									</div>
								  <?php
							  }
						  }
						
						?>
					
					  <div class="col-md-12 col-sm-12 col-xs-12">
						<div class="x_panel">
						  <div class="x_title">
							<ul class="nav navbar-right panel_toolbox">
								<a href="add_dms.php" class="btn btn-primary btn-md" data-toggle="tooltip" data-placement="top" title="Add Disposition Mission Slip"><i class="fa fa-plus-square"></i> Add </a>
							</ul>
							<div class="clearfix"></div>
						  </div>
						  <div class="x_content">

							    <table id="datatable-fixed-header" class="table table-striped table-bordered jambo_table">
								  <thead>
									<tr>
									  <th width="08%"></th>
									  <th width="08%">Control #</th>
									  <th width="18%">Employee</th>
									  <th width="12%">Date / Time</th>
									  <th width="15%">Destination</th>
									  <th width="15%">Purpose</th>
									  <th width="12%">Encoder</th>
									  <th width="12%">Date Encoded</th>
									</tr>
								  </thead>
								  <tbody>
								    <?php
									$sql = "SELECT d.did, d.dnum, CONCAT(e.lname,', ',e.fname),
												DATE_FORMAT(d.ddate, '%m/%d/%y'), 
												DATE_FORMAT(d.fr_time, '%h:%i %p'),
												DATE_FORMAT(d.to_time, '%h:%i %p'),
												d.destination, d.purpose, CONCAT(u.lname,', ',u.fname), 
												DATE_FORMAT(d.user_dtime, '%m/%d/%y %h:%i %p')
											FROM internals_dms d 
												INNER JOIN employees e ON d.eid = e.eid
												INNER JOIN users u ON d.user_id = u.user_id
											ORDER BY d.dnum DESC";
									$res = query($sql); 
									while ($row = fetch_array($res)) {
										?>
										<tr>
											<td align="center">
												<a href="e_dms.php?did=<?php echo $row[0] ?>" class="btn btn-warning btn-xs" data-toggle="tooltip" data-placement="top" title="Update Disposition Mission Slip"><i class="fa fa-pencil"></i></a>
												<a href="d_dms.php?did=<?php echo $row[0] ?>" class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top" title="Delete Disposition Mission Slip"><i class="fa fa-eraser"></i></a>
											</td>
											<td align="center"><?php echo $row[1] ?></td>
											<td align="left"><?php echo $row[2] ?></td>
											<td align="center"><?php echo $row[3].' '.$row[4].'-'.$row[5] ?></td>
											<td align="left"><?php echo $row[6] ?></td>
											<td align="left"><?php echo $row[7] ?></td>
											<td align="left"><?php echo $row[8] ?></td>
											<td align="center"><?php echo $row[9] ?></td>
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
	} else {
		// check if user is a valid employee
		$sql = "SELECT eid FROM users WHERE user_id = '$_SESSION[user_id]' AND active = 1";
		$vres = query($sql); $vrow = fetch_array($vres); $eid = $vrow[0]; 
		
		if ($eid != 0) {

			// log the activity
			log_user(5, 'dms', 0);
			
			?>
			<!DOCTYPE html>
			<html lang="en">
			  <head>
				<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
				<!-- Meta, title, CSS, favicons, etc. -->
				<meta charset="utf-8">
				<meta http-equiv="X-UA-Compatible" content="IE=edge">
				<meta name="viewport" content="width=device-width, initial-scale=1">

				<title>OTC - Disposition Mission Slips</title>

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
					
					if (isset ($_GET['yyyymm'])) {
						$yyyymm = $_GET['yyyymm'];
						$gyear = substr($_GET['yyyymm'], 0, 4);
						$gmonth = substr($_GET['yyyymm'], 4, 2);
					} else {
						$gyear = date('Y');
						$gmonth = date('m');
						$yyyymm = date('Ym');
					}
					
					?>

					<!-- page content -->
					<div class="right_col" role="main">
					  <div class="">
						<div class="page-title">
						  <div class="title_left">
							<h3>Disposition Mission Slips</h3>
						  </div>

						  <div class="title_right">
							<div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
								<select class="form-control col-md-7 col-xs-12" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
									<option value="">Month and Year</option>
									<?php
									$sql = "SELECT yyyymm, month_year FROM year_months ORDER BY yyyymm DESC";
									$ymres = query($sql); 
									while ($ymrow = fetch_array($ymres)) {
										if ($yyyymm == $ymrow[0]) {
											?><option value="dms.php?yyyymm=<?php echo $ymrow[0] ?>&tab=<?php echo $tab ?>" Selected><?php echo $ymrow[1] ?></option><?php
										} else {
											?><option value="dms.php?yyyymm=<?php echo $ymrow[0] ?>&tab=<?php echo $tab ?>"><?php echo $ymrow[1] ?></option><?php
										}
									}
									free_result($ymres); 
									?>
								</select>
							</div>
						  </div>
						</div>

						<div class="clearfix"></div>

						<div class="row">
							<?php
							  if (isset($_GET['added_dms'])) {
								  if ($_GET['added_dms'] == 1) {
									  ?>
										<div class="alert alert-success">
										  <strong>Success!</strong> Disposition Mission Slip has been added into the database.
										</div>
									  <?php
								  }
							  }
							  if (isset($_GET['updated_dms'])) {
								  if ($_GET['updated_dms'] == 1) {
									  ?>
										<div class="alert alert-warning">
										  <strong>Success!</strong> Disposition Mission Slip has been updated.
										</div>
									  <?php
								  }
							  }
							  if (isset($_GET['deleted_dms'])) {
								  if ($_GET['deleted_dms'] == 1) {
									  ?>
										<div class="alert alert-danger">
										  <strong>Success!</strong> Disposition Mission Slip has been deleted.
										</div>
									  <?php
								  }
							  }
							
							?>
						
						  <div class="col-md-12 col-sm-12 col-xs-12">
							<div class="x_panel">
							  <div class="x_title">
								<ul class="nav navbar-right panel_toolbox">
									<a href="add_dms.php" class="btn btn-primary btn-md" data-toggle="tooltip" data-placement="top" title="Add Disposition Mission Slip"><i class="fa fa-plus-square"></i> Add </a>
								</ul>
								<div class="clearfix"></div>
							  </div>
							  <div class="x_content">

									<table id="datatable-fixed-header" class="table table-striped table-bordered jambo_table">
									  <thead>
										<tr>
										  <th width="08%"></th>
										  <th width="08%">Control #</th>
										  <th width="18%">Employee</th>
										  <th width="12%">Date / Time</th>
										  <th width="15%">Destination</th>
										  <th width="15%">Purpose</th>
										  <th width="12%">Encoder</th>
										  <th width="12%">Date Encoded</th>
										</tr>
									  </thead>
									  <tbody>
										<?php
										$sql = "SELECT d.did, d.dnum, CONCAT(e.lname,', ',e.fname),
													DATE_FORMAT(d.ddate, '%m/%d/%y'), 
													DATE_FORMAT(d.fr_time, '%h:%i %p'),
													DATE_FORMAT(d.to_time, '%h:%i %p'),
													d.destination, d.purpose, CONCAT(u.lname,', ',u.fname), 
													DATE_FORMAT(d.user_dtime, '%m/%d/%y %h:%i %p')
												FROM internals_dms d 
													INNER JOIN employees e ON d.eid = e.eid
													INNER JOIN users u ON d.user_id = u.user_id
												WHERE d.eid = '$eid'
												ORDER BY d.dnum DESC";
										$res = query($sql); 
										while ($row = fetch_array($res)) {
											?>
											<tr>
												<td align="center">
													<a href="e_dms.php?did=<?php echo $row[0] ?>" class="btn btn-warning btn-xs" data-toggle="tooltip" data-placement="top" title="Update Disposition Mission Slip"><i class="fa fa-pencil"></i></a>
													<a href="d_dms.php?did=<?php echo $row[0] ?>" class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top" title="Delete Disposition Mission Slip"><i class="fa fa-eraser"></i></a>
												</td>
												<td align="center"><?php echo $row[1] ?></td>
												<td align="left"><?php echo $row[2] ?></td>
												<td align="center"><?php echo $row[3].' '.$row[4].'-'.$row[5] ?></td>
												<td align="left"><?php echo $row[6] ?></td>
												<td align="left"><?php echo $row[7] ?></td>
												<td align="left"><?php echo $row[8] ?></td>
												<td align="center"><?php echo $row[9] ?></td>
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
		} else {
			?>
			<!DOCTYPE html>
			<html lang="en">
			  <head>
				<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
				<!-- Meta, title, CSS, favicons, etc. -->
				<meta charset="utf-8">
				<meta http-equiv="X-UA-Compatible" content="IE=edge">
				<meta name="viewport" content="width=device-width, initial-scale=1">

				<title>OTC - Disposition Mission Slip</title>

				<!-- Bootstrap -->
				<link href="vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
				<!-- Font Awesome -->
				<link href="vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">

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
							<div class="row">
							
							  <div class="col-md-12 col-sm-12 col-xs-12">
								<div class="x_panel">
									<div class="x_content">
									  <div class="bs-example" data-example-id="simple-jumbotron">
											<blockquote class="blockquote">
											  <p class="h1">Invalid User-right!</p>
											  <footer class="blockquote-footer"><em>Ask your system administrator for access to this page.</em></footer>
											</blockquote>
									  </div>
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


			  </body>
			</html>		
			<?php
		}
	}
} else {
	header('Location: login.php');	
}
