<?php

include("db.php");
include("sqli.php");
include("html.php");
define('PAGE', 'users_logs.php'); 

session_start();

if (isset($_SESSION['user_id'])) {
	if (validate_level(PAGE) == true) {

		// log the activity
		log_user(5, 'logs', 0);
		
		?>
		<!DOCTYPE html>
		<html lang="en">
		  <head>
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
			<!-- Meta, title, CSS, favicons, etc. -->
			<meta charset="utf-8">
			<meta http-equiv="X-UA-Compatible" content="IE=edge">
			<meta name="viewport" content="width=device-width, initial-scale=1">

			<title>OTC - Users' Logs</title>

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
				
				if (isset ($_GET['yyyymm'])) {
					$gyear = substr($_GET['yyyymm'], 0, 4);
					$gmonth = substr($_GET['yyyymm'], 4, 2);
					$yyyymm = $_GET['yyyymm'];
				} else {
					$gyear = date('Y');
					$gmonth = date('m');
					$yyyymm = $gyear.$gmonth; 
				}
				
				if (isset ($_GET['user_id'])) {
					$user_id = $_GET['user_id'];
				} else {
					$user_id = 0; 
				}
				
				?>

				<!-- page content -->
				<div class="right_col" role="main">
				  <div class="">
					<div class="page-title">
					  <div class="title_left">
						<h3>Users' Logs</h3>
					  </div>

					  <div class="title_right">
						<div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
						  <div class="input-group">
							<select class="form-control col-md-7 col-xs-12" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
								<option value="">Month and Year</option>
								<?php
								$sql = "SELECT yyyymm, month_year FROM year_months ORDER BY yyyymm DESC";
								$ymres = query($sql); 
								while ($ymrow = fetch_array($ymres)) {
									if ($_GET['yyyymm'] == $ymrow[0]) {
										?><option value="logs.php?yyyymm=<?php echo $ymrow[0] ?>&user_id=<?php echo $user_id ?>" Selected><?php echo $ymrow[1] ?></option><?php
									} else {
										?><option value="logs.php?yyyymm=<?php echo $ymrow[0] ?>&user_id=<?php echo $user_id ?>"><?php echo $ymrow[1] ?></option><?php
									}
								}
								free_result($ymres); 
								?>
							</select>
						  </div>
						</div>
					  </div>
					</div>

					<div class="clearfix"></div>

					<div class="row">
					
					  <div class="col-md-12 col-sm-12 col-xs-12">
						<div class="x_panel">
						  <div class="x_title">
							<h2>Users' Logs -  
							<?php
							if ($gmonth == '01') { $show_month = 'January';
							} elseif ($gmonth == '02') { $show_month = 'February';
							} elseif ($gmonth == '03') { $show_month = 'March';
							} elseif ($gmonth == '04') { $show_month = 'April';
							} elseif ($gmonth == '05') { $show_month = 'May';
							} elseif ($gmonth == '06') { $show_month = 'June';
							} elseif ($gmonth == '07') { $show_month = 'July';
							} elseif ($gmonth == '08') { $show_month = 'August';
							} elseif ($gmonth == '09') { $show_month = 'September';
							} elseif ($gmonth == '10') { $show_month = 'October';
							} elseif ($gmonth == '11') { $show_month = 'November';
							} elseif ($gmonth == '12') { $show_month = 'December';
							}
							echo $show_month.', '.$gyear;
							?>
							</h2>
							<ul class="nav navbar-right">
								<select class="form-control col-md-7 col-xs-12" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
									<option value="logs.php?yyyymm=<?php echo $yyyymm ?>&user_id=0">All Users</option>
									<?php
									$sql = "SELECT user_id, CONCAT(lname,', ',fname) 
											FROM users 
											WHERE active = 1 
											ORDER BY lname, fname";
									$ures = query($sql); 
									while ($urow = fetch_array($ures)) {
										if ($_GET['user_id'] == $urow[0]) {
											?><option value="logs.php?yyyymm=<?php echo $yyyymm ?>&user_id=<?php echo $urow[0] ?>" Selected><?php echo $urow[1] ?></option><?php
										} else {
											?><option value="logs.php?yyyymm=<?php echo $yyyymm ?>&user_id=<?php echo $urow[0] ?>"><?php echo $urow[1] ?></option><?php
										}
									}
									free_result($ymres); 
									?>
								</select>
							</ul>
							<div class="clearfix"></div>
						  </div>
						  <div class="x_content">
							<table class="table table-striped table-bordered jambo_table">
							  <thead>
								<tr>
								  <th width="10%">Date / Time</th>
								  <th width="07%">Action</th>
								  <th width="16%">User Name</th>
								  <th width="10%">Records Name</th>
								  <th width="07%">Record No.</th>
								  <th width="50%">Details</th>
								</tr>
							  </thead>
							  <tbody>
								<?php
								$logs_table = 'logs_'.$yyyymm;

								if ($user_id == 0) {
									$sql = "SELECT DATE_FORMAT(l.log_dtime, '%m/%d/%y %h:%i %p'), l.log_type, 
												CONCAT(u.lname,', ',u.fname), l.table_name, l.pkid
											FROM ".$logs_table." l INNER JOIN users u ON l.user_id = u.user_id
											ORDER BY l.log_dtime DESC";
								} else {
									$sql = "SELECT DATE_FORMAT(l.log_dtime, '%m/%d/%y %h:%i %p'), l.log_type, 
												CONCAT(u.lname,', ',u.fname), l.table_name, l.pkid
											FROM ".$logs_table." l INNER JOIN users u ON l.user_id = u.user_id
											WHERE u.user_id = '$user_id'
											ORDER BY l.log_dtime DESC";
								}
								$res = query($sql);

								while ($row = fetch_array($res)) {
									?>
									<tr>
									  <td align="center"><?php echo $row[0] ?></td>
									  <td align="center"><?php 
										if ($row[1] == 0) { echo 'Log In';
										} elseif ($row[1] == 1) { echo 'Add';
										} elseif ($row[1] == 2) { echo 'Update';
										} elseif ($row[1] == 3) { echo 'Delete';
										} elseif ($row[1] == 4) { echo 'Verify';
										} elseif ($row[1] == 5) { echo 'View';
										} elseif ($row[1] == 6) { echo 'Print';
										} elseif ($row[1] == -1) { echo 'Log Out';
										} ?></td>
									  <td align="left"><?php echo $row[2] ?></td>
									  <td align="left"><?php echo $row[3] ?></td>
									  <td align="center"><?php echo $row[4] ?></td>
									  <td align="center">
										<?php
										if ($row[1] == 1) {
											// action is creating new record
										} elseif ($row[1] == 2) {
											// action is updating a record
										} elseif ($row[1] == 3) {
											// action is deleting a record
										} elseif ($row[1] == 4) {
											// action is verifying a record
										} elseif ($row[1] == 5) {
											// action is viewing onlye
											if ($row[4] != 0) {
												if ($row[3] == 'activities') {
												} elseif ($row[3] == 'announcements') {
												} elseif ($row[3] == 'attendance') {
												}
											}
										} elseif ($row[1] == 6) {
											// action is printing
										}
										?>
									  </td>
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
