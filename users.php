<?php

include("db.php");
include("sqli.php");
include("html.php");
define('PAGE', 'users.php'); 

$tab = $_GET['tab'];

session_start();

if (isset($_SESSION['user_id'])) {
	if (validate_level(PAGE) == true) {

		// log the activity
		log_user(5, 'users', 0);
		
		?>
		<!DOCTYPE html>
		<html lang="en">
		  <head>
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
			<!-- Meta, title, CSS, favicons, etc. -->
			<meta charset="utf-8">
			<meta http-equiv="X-UA-Compatible" content="IE=edge">
			<meta name="viewport" content="width=device-width, initial-scale=1">

			<title>OTC - Users Settings</title>

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
						<h3>Users Settings</h3>
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
								  <strong>Success!</strong> User has been added into the database.
								</div>
							  <?php
						  }
					  }
					  
					  if ($tab == 1) {
						  ?>
						  <div class="col-md-12 col-sm-12 col-xs-12">
							<div class="x_panel">
							  <div class="x_title">
								<h2>Active Users</h2>
								<ul class="nav navbar-right panel_toolbox">
									<a href="add_user.php" class="btn btn-primary btn-md"><i class="fa fa-plus-square"></i> Add User </a>
								</ul>
								<div class="clearfix"></div>
							  </div>
							  <div class="x_content">
								<table class="table table-striped table-bordered jambo_table">
								  <thead>
									  <tr>
										  <th width="20%">Name</th>
										  <th width="15%">Level</th>
										  <th width="20%">Last Login</th>
										  <th width="15%">Last IP</th>
										  <th width="15%">Username</th>
										  <th width="05%">Active</th>
										  <th width="10%"></th>
									  </tr>
								  </thead>
								  <tbody>
								  <?php
									$sql = "SELECT user_id, CONCAT(lname,', ',fname), ulevel, 
												DATE_FORMAT(last_login, '%m/%d/%y %h:%i %p'), last_ip, username, active
											FROM users 
											ORDER BY active DESC, lname, fname";
									$res = query($sql);

									while ($row = fetch_array($res)) {
										?>
										<tr>
											<td align="left"><?php echo $row[1] ?></a></td>
											<td align="left"><?php 
												if ($row[2] == 1) { echo 'Admin';
												} elseif ($row[2] == 0) { echo 'Custom User';
												} ?></td>
											<td align="center"><?php echo $row[3] == '00/00/00 12:00 AM' ? '' : $row[3] ?></td>
											<td align="left"><?php echo $row[4] ?></td>
											<td align="left"><?php echo $row[5] ?></td>
											<td align="center"><?php echo $row[6] == 1 ? 'Yes' : 'No' ?></td>
											<td align="center">
												<a href="e_user.php?temp_user_id=<?php echo $row[0] ?>" class="btn btn-warning btn-xs"><i class="fa fa-pencil"></i></a>
												<a href="e_user_password.php?temp_user_id=<?php echo $row[0] ?>" class="btn btn-default btn-xs"><i class="fa fa-key"></i></a>
											</td>
										</tr>
										<?php
									}
									free_result($res);
								  ?>
								  </tbody>
								</table>
							  </div><!-- x_content -->
							</div>
						  </div>
						  <?php
					  } elseif ($tab == 2) {
						  ?>
						  <div class="col-md-12 col-sm-12 col-xs-12">
							<div class="x_panel">
							  <div class="x_title">
								<h2>Users' Access</h2>
								<ul class="nav navbar-right panel_toolbox">
									<a href="add_user_access.php" class="btn btn-primary btn-md"><i class="fa fa-plus-square"></i> Add User-Access </a>
								</ul>
								<div class="clearfix"></div>
							  </div>
							  <div class="x_content">
							  
								<table class="table table-striped table-bordered jambo_table">
									<thead>
									  <tr class="headings">
										<th width="25%">Users</th>
										<th width="70%">Access</th>
										<th width="05%"></th>
									  </tr>
									</thead>
									<tbody>
										<?php
										$sql = "SELECT ua_id, CONCAT(u.lname,', ',u.fname), a.aname
												FROM users u 
													INNER JOIN users_accesses ua ON u.user_id = ua.user_id
													INNER JOIN accesses a ON ua.aid = a.aid
												WHERE u.active = 1 
												ORDER BY u.lname, u.fname";
										$ures = query($sql); 
										while ($urow = fetch_array($ures)) {
											?>
											<tr>
												<td align="left"><?php echo $urow[1] ?></td>
												<td align="left"><?php echo $urow[2] ?></td>
												<td align="center">
													<a href="d_user_access.php?ua_id=<?php echo $urow[0] ?>" class="btn btn-danger btn-xs"><i class="fa fa-eraser"></i></a>
												</td>
											</tr>
											<?php
										}
										free_result($ures); 
										?>
									</tbody>
								</table>
							  
							  </div>
							</div>
						  </div>
						  <?php
					  } elseif ($tab == 3) {
						  $sql = "SELECT aid, aname FROM accesses ORDER BY aname";
						  $ares = query($sql); 
						  while ($arow = fetch_array($ares)) {
							  ?>
							  <div class="col-md-6 col-sm-6 col-xs-12">
								<div class="x_panel">
								  <div class="x_title">
									<h2><?php echo $arow[1].' ['.$arow[0].']'; ?></h2>
									<ul class="nav navbar-right panel_toolbox">
										<a href="add_access_page.php?aid=<?php echo $arow[0] ?>" class="btn btn-primary btn-md"><i class="fa fa-plus-square"></i> Add Access-Page </a>
									</ul>
									<div class="clearfix"></div>
								  </div>
								  <div class="x_content">
								  
									<table class="table table-striped table-bordered jambo_table">
										<thead>
										  <tr class="headings">
											<th width="10%"></th>
											<th width="50%">Description</th>
											<th width="30%">Page Name</th>
											<th width="10%"></th>
										  </tr>
										</thead>
										<tbody>
											<?php
											$sql = "SELECT ap.page_id, p.page_desc, p.page_name, ap.ap_id
													FROM accesses_pages ap INNER JOIN pages p ON ap.page_id = p.page_id
													WHERE ap.aid = '$arow[0]'
													ORDER BY p.page_desc";
											$res = query($sql); 
											while ($row = fetch_array($res)) {
												?>
												<tr>
													<td align="center"><?php echo $row[0] ?></td>
													<td align="left"><?php echo $row[1] ?></td>
													<td align="left"><?php echo $row[2] ?></td>
													<td align="center">
														<a href="d_access_page.php?ap_id=<?php echo $row[3] ?>" class="btn btn-danger btn-xs"><i class="fa fa-eraser"></i></a>
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
							  <?php
						  }
						  free_result($ares); 
					  } elseif ($tab == 4) {
						  ?>
						  <div class="col-md-12 col-sm-12 col-xs-12">
							<div class="x_panel">
							  <div class="x_title">
								<h2>Online Users</h2>
								<ul class="nav navbar-right panel_toolbox">
								  <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
								  <li><a class="close-link"><i class="fa fa-close"></i></a></li>
								</ul>
								<div class="clearfix"></div>
							  </div>
							  <div class="x_content">
							  
								<table class="table table-striped table-bordered jambo_table">
									<thead>
									  <tr class="headings">
										<th width="05%" align="center"></th>
										<th width="20%" align="center">Name</th>
										<th width="10%" align="center">User Level</th>
										<th width="15%" align="center">I.P. Address</th>
										<th width="50%" align="center">URL</th>
									  </tr>
									</thead>
									<tbody>
										<?PHP
										$sql = "SELECT s.user_id, CONCAT(u.lname,', ',u.fname), u.ulevel, s.session_ip, s.refurl
												FROM sessions s INNER JOIN users u ON s.user_id = u.user_id
												ORDER BY u.lname, u.fname";
										$res = query($sql);
										while ($row = fetch_array($res)) {
											?>
											<tr>
												<td align="center">
												</td>
												<td align="left"><?PHP echo $row[1] ?></td>
												<td align="center"><?PHP 
													if ($row[2] == 2) { echo 'Admin';
													} else { echo 'Custom Access';
													} ?></td>
												<td align="left"><?PHP echo $row[3] ?></td>
												<td align="left"><?PHP 
													$url = str_replace("http://192.168.0.250/otc/", "", $row[4]);
													$url = str_replace("http://122.54.185.113/otc/", "", $row[4]);
													$url = str_replace("http://localhost/otc/", "", $row[4]);
													echo $url; 
													?></td>
											</tr>
											<?PHP
										}
										?>
									</tbody>
								</table>
							  
							  </div>
							</div>
						  </div>
						  <?php
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
