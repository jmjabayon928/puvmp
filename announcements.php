<?php

include("db.php");
include("sqli.php");
include("html.php");
define('PAGE', 'announcements.php'); 

$tab = $_GET['tab'];

session_start();

if (isset($_SESSION['user_id'])) {
	if (validate_level(PAGE) == true) {

		// log the activity
		log_user(5, 'announcements', 0);
		
		?>
		<!DOCTYPE html>
		<html lang="en">
		  <head>
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
			<!-- Meta, title, CSS, favicons, etc. -->
			<meta charset="utf-8">
			<meta http-equiv="X-UA-Compatible" content="IE=edge">
			<meta name="viewport" content="width=device-width, initial-scale=1">

			<title>OTC - Announcements</title>

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
						<h3>Announcements</h3>
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
					  if (isset($_GET['success'])) {
						  if ($_GET['success'] == 1) {
							  ?>
								<div class="alert alert-success">
								  <strong>Success!</strong> Announcement has been added into the database.
								</div>
							  <?php
						  }
					  }
					  ?>

					  <div class="col-md-12 col-sm-12 col-xs-12">
						<div class="x_panel">
						  <div class="x_title">
							<h2><?php
								echo $tab == 1 ? 'Past ' : 'Present '
								?> Announcements</h2>
							<ul class="nav navbar-right panel_toolbox">
								<a href="add_announcement.php" class="btn btn-primary btn-md"><i class="fa fa-plus-square"></i> Add Announcement </a>
							</ul>
							<div class="clearfix"></div>
						  </div>
						  <div class="x_content">
							<table id="datatable-fixed-header" class="table table-striped table-bordered jambo_table">
							  <thead>
								  <tr>
									  <th width="10%"></th>
									  <th width="10%">Date/Time</th>
									  <th width="15%">Division</th>
									  <th width="17%">Announcement</th>
									  <th width="23%">Description</th>
									  <th width="25%">Remarks</th>
								  </tr>
							  </thead>
							  <tbody>
								<?php
								
								if ($tab == 1) {
									$sql = "SELECT a.aid, DATE_FORMAT(a.adtime, '%m/%d/%y<br />%h:%i%p'), 
												d.dname, a.aname, a.adesc, a.remarks, a.last_update_id
											FROM announcements a
												LEFT JOIN departments d ON a.did = d.did
											WHERE TO_DAYS(NOW()) > TO_DAYS(a.adtime)
											ORDER BY a.adtime";
								} elseif ($tab == 2) {
									$sql = "SELECT a.aid, DATE_FORMAT(a.adtime, '%m/%d/%y<br />%h:%i%p'), 
												d.dname, a.aname, a.adesc, a.remarks, a.last_update_id
											FROM announcements a
												LEFT JOIN departments d ON a.did = d.did
											WHERE TO_DAYS(a.adtime) >= TO_DAYS(NOW())
											ORDER BY a.adtime";
								}
								$res = query($sql);

								while ($row = fetch_array($res)) {
									?>
									<tr>
									  <td align="center">
										<a href="announcement.php?aid=<?php echo $row[0] ?>" class="btn btn-info btn-xs"><i class="fa fa-search"></i></a>
										<?php
										if ($tab == 2 && $_SESSION['user_id'] == $row[6]) {
											?>
											<a href="e_announcement.php?aid=<?php echo $row[0] ?>" class="btn btn-warning btn-xs"><i class="fa fa-pencil"></i></a>
											<a href="d_announcement.php?aid=<?php echo $row[0] ?>" class="btn btn-danger btn-xs"><i class="fa fa-eraser"></i></a>
											<?PHP
										}
										?>
									  </td>
									  <td align="center"><?php echo $row[1] ?></td>
									  <td align="left"><?php echo $row[2] == NULL ? 'All Divisions' : $row[2] ?></td>
									  <td align="left"><?php echo $row[3] ?></td>
									  <td align="left"><?php echo $row[4] ?></td>
									  <td align="left"><?php echo $row[5] ?></td>
									</tr>
									<?php
								}
								mysqli_free_result($res);
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
