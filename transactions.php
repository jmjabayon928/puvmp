<?php

include("db.php");
include("sqli.php");
include("html.php");
define('PAGE', 'transactions.php'); 

session_start();

if (isset($_SESSION['user_id'])) {
	if (validate_level(PAGE) == true) {
		// log the activity
		log_user(5, 'transactions', 0);
		
		if (isset ($_GET['ttid'])) {
			$ttid = $_GET['ttid'];
		} else {
			$ttid = 0; 
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

			<title>OTC - Transactions</title>

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
				
				if ($ttid == 0) { $tname = 'All Transactions';
				} else {
					$sql = "SELECT tname FROM tc_transactions WHERE tid = '$ttid'";
					$tres = query($sql); 
					$trow = fetch_array($tres); 
					$tname = $trow[0]; 
				}
				?>

				<!-- page content -->
				<div class="right_col" role="main">
				  <div class="">
					<div class="page-title">
					  <div class="title_left">
						<h3><?php echo $tname ?></h3>
					  </div>

					  <div class="title_right">
						<div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
							<select class="form-control col-md-7 col-xs-12" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
								<?php
								if ($ttid == 0) {
									?><option value="transactions.php?ttid=0" Selected>All Transactions</option><?php
								} else {
									?><option value="transactions.php?ttid=0">All Transactions</option><?php
								}
								$sql = "SELECT tid, tname FROM tc_transactions";
								$tres = query($sql); 
								while ($trow = fetch_array($tres)) {
									if ($ttid == $trow[0]) {
										?><option value="transactions.php?ttid=<?php echo $trow[0] ?>" Selected><?php echo $trow[1] ?></option><?php
									} else {
										?><option value="transactions.php?ttid=<?php echo $trow[0] ?>"><?php echo $trow[1] ?></option><?php
									}
								}
								free_result($tres); 
								?>
							</select>
						</div>
					  </div>
					</div>

					<div class="clearfix"></div>

					<div class="row">
					
						<?php
						if (isset($_GET['added_transaction'])) {
							if ($_GET['added_transaction'] == 1) {
								?>
								<div class="alert alert-success">
								  <strong>Success!</strong> Transaction has been added into the database.
								</div>
								<?php
							}
						}
						?>
					  
						<div class="col-md-12 col-sm-12 col-xs-12">
							<div class="x_panel">
								<div class="x_title">
									<ul class="nav navbar-right panel_toolbox">
										<a href="add_transaction.php" class="btn btn-primary btn-md"><i class="fa fa-plus-square"></i> Add </a>
									</ul>
								<div class="clearfix"></div>
								</div>
								<div class="x_content">
									<table id="datatable-fixed-header" class="table table-striped table-bordered jambo_table">
										<thead>
										<tr>
											<th width="10%"></th>
											<th width="08%">Reference</th>
											<th width="10%">Start</th>
											<th width="10%">End</th>
											<th width="08%">Duration</th>
											<th width="14%">Transaction</th>
											<th width="28%">Cooperative</th>
											<th width="12%">Status</th>
										</tr>
										</thead>
										<tbody>
										<?php
										if ($ttid != 0) {
											$sql = "SELECT t.tid, t.ref_num, DATE_FORMAT(t.fr_dtime, '%m/%d/%y %h:%i %p'), 
														DATE_FORMAT(t.to_dtime, '%m/%d/%y %h:%i %p'), 
														CONCAT(
														   FLOOR(HOUR(TIMEDIFF(t.to_dtime, t.fr_dtime)) / 24), 'd: ',
														   MOD(HOUR(TIMEDIFF(t.to_dtime, t.fr_dtime)), 24), 'h: ',
														   MINUTE(TIMEDIFF(t.to_dtime, t.fr_dtime)), 'm'),
														tc.tname, c.cname, t.status
													FROM transactions t 
														INNER JOIN tc_transactions tc ON t.ttid = tc.tid
														INNER JOIN cooperatives c ON t.coop_id = c.cid
													WHERE t.ttid = '$ttid'
													ORDER BY t.fr_dtime DESC";
										} else {
											$sql = "SELECT t.tid, t.ref_num, DATE_FORMAT(t.fr_dtime, '%m/%d/%y %h:%i %p'), 
														DATE_FORMAT(t.to_dtime, '%m/%d/%y %h:%i %p'), 
														CONCAT(
														   FLOOR(HOUR(TIMEDIFF(t.to_dtime, t.fr_dtime)) / 24), 'd: ',
														   MOD(HOUR(TIMEDIFF(t.to_dtime, t.fr_dtime)), 24), 'h: ',
														   MINUTE(TIMEDIFF(t.to_dtime, t.fr_dtime)), 'm'),
														tc.tname, c.cname, t.status
													FROM transactions t 
														INNER JOIN tc_transactions tc ON t.ttid = tc.tid
														INNER JOIN cooperatives c ON t.coop_id = c.cid
													ORDER BY t.fr_dtime DESC";
										}
										$res = query($sql); 
										while ($row = fetch_array($res)) {
											?>
											<tr>
												<td align="center">
													<a href="transaction.php?tid=<?php echo $row[0] ?>" class="btn btn-info btn-xs"><i class="fa fa-search"></i></a>
													<a href="e_transaction.php?tid=<?php echo $row[0] ?>" class="btn btn-warning btn-xs"><i class="fa fa-pencil"></i></a>
													<a href="d_transaction.php?tid=<?php echo $row[0] ?>" class="btn btn-danger btn-xs"><i class="fa fa-eraser"></i></a>
												</td>
												<td align="center"><?php echo $row[1] ?></td>
												<td align="center"><?php echo $row[2] ?></td>
												<td align="left"><?php echo $row[3] ?></td>
												<td align="left"><?php echo $row[4]?></td>
												<td align="left"><?php echo $row[5] ?></td>
												<td align="left"><?php echo ucwords(strtolower($row[6])) ?></td>
												<td align="left"><?php 
													if ($row[7] == 1) { echo 'Received by Records';
													} elseif ($row[7] == 2) { echo 'On Process By OD';
													} elseif ($row[7] == 3) { echo 'For OD Chief Verification';
													} elseif ($row[7] == 4) { echo 'For OC Approval';
													} elseif ($row[7] == 5) { echo 'For Release By Records';
													} elseif ($row[7] == 6) { echo 'Finished';
													}
													?></td>
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
