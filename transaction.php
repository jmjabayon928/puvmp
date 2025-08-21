<?php

include("db.php");
include("sqli.php");
include("html.php");
define('PAGE', 'transaction.php'); 

$tid = $_GET['tid'];

session_start();

if (isset($_SESSION['user_id'])) {
	if (validate_level(PAGE) == true) {

		// log the activity
		log_user(5, 'transactions', $tid);
		
		?>
		<!DOCTYPE html>
		<html lang="en">
		  <head>
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
			<!-- Meta, title, CSS, favicons, etc. -->
			<meta charset="utf-8">
			<meta http-equiv="X-UA-Compatible" content="IE=edge">
			<meta name="viewport" content="width=device-width, initial-scale=1">
			  
			<title>OTC | Transaction Details</title>

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
						<h3>OTC Transaction Details</h3>
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
					
					  <!-- Transaction Info -->
					  <div class="col-md-12 col-sm-12 col-xs-12">
						<div class="x_panel">
						  <div class="x_title">
							<h2>Transaction Details</h2>
							<ul class="nav navbar-right panel_toolbox">
							  <a href="e_transaction.php?tid=<?php echo $tid ?>" class="btn btn-warning btn-md" data-toggle="tooltip" data-placement="top" title="Update Transaction"><i class="fa fa-pencil"></i></a>
							  <a href="d_transaction.php?tid=<?php echo $tid ?>" class="btn btn-danger btn-md" data-toggle="tooltip" data-placement="top" title="Delete Transaction"><i class="fa fa-eraser"></i></a>
							</ul>
							<div class="clearfix"></div>
						  </div>
						  <div class="x_content">
							<?php
							$sql = "SELECT t.tid, t.ref_num, tc.tname, c.cname, t.tname, 
										DATE_FORMAT(t.fr_dtime, '%m/%d/%y %h:%i %p'), 
										DATE_FORMAT(t.to_dtime, '%m/%d/%y %h:%i %p'), 
										CONCAT(
										   FLOOR(HOUR(TIMEDIFF(t.to_dtime, t.fr_dtime)) / 24), 'd: ',
										   MOD(HOUR(TIMEDIFF(t.to_dtime, t.fr_dtime)), 24), 'h: ',
										   MINUTE(TIMEDIFF(t.to_dtime, t.fr_dtime)), 'm'),
										t.status, t.remarks,
										CONCAT(u.lname,', ',u.fname), DATE_FORMAT(t.user_dtime, '%m/%d/%Y %h:%i%p')
									FROM transactions t 
										INNER JOIN tc_transactions tc ON t.ttid = tc.tid
										INNER JOIN cooperatives c ON t.coop_id = c.cid
										INNER JOIN users u ON t.user_id = u.user_id
									WHERE t.tid = '$tid'";
							$res = query($sql); 
							$row = fetch_array($res); 
							mysqli_free_result($res);
							?>
							<table class="table table-striped table-bordered jambo_table bulk_action">
								<thead>
								  <tr class="headings">
									  <th colspan="4">Transaction Details</th>
								  </tr>
								</thead>
								<tbody>
									<tr>
										<td width="10%">Reference No.</td>
										<td width="30%"><b><?php echo $row[1] ?></b></td>
										<td width="10%">Start</td>
										<td width="50%"><b><?php echo $row[5] ?></b></td>
									</tr>
									<tr>
										<td>Type of Transaction</td>
										<td><b><?php echo $row[2] ?></b></td>
										<td>End</td>
										<td><b><?php echo $row[6] ?></b></td>
									</tr>
									<tr>
										<td>Cooperative</td>
										<td><b><?php echo $row[3] ?></b></td>
										<td>Duration</td>
										<td><b><?php echo $row[7] ?></b></td>
									</tr>
									<tr>
										<td>Transacting Person</td>
										<td><b><?php echo $row[4] ?></b></td>
										<td>Status</td>
										<td><b><?php 
											if ($row[8] == 1) { echo 'Received by Records';
											} elseif ($row[8] == 2) { echo 'On Process By OD';
											} elseif ($row[8] == 3) { echo 'For OD Chief Verification';
											} elseif ($row[8] == 4) { echo 'For OC Approval';
											} elseif ($row[8] == 5) { echo 'For Release By Records';
											} elseif ($row[8] == 6) { echo 'Finished';
											}
											?></b></td>
									</tr>
									<tr>
										<td>Remarks</td>
										<td colspan="3"><b><?php echo $row[9] ?></b></td>
									</tr>
									<tr>
										<td>Last Updated By</td>
										<td><b><?php echo $row[10] ?></b></td>
										<td>Date Last Updated</td>
										<td><b><?php echo $row[11] ?></b></td>
									</tr>
								</tbody>
							</table>
						  </div>
						</div>
					  </div>
					  <!-- end of Communication Info -->
					  
					  <div class="col-md-12 col-sm-12 col-xs-12">
						<div class="x_panel">
						  <div class="x_title">
							<h2>Transaction Requirements</h2>
							<ul class="nav navbar-right panel_toolbox">
								<a href="add_requirement.php?tid=<?php echo $tid ?>" class="btn btn-primary btn-md" data-toggle="tooltip" data-placement="top" title="Add Requirement"><i class="fa fa-plus"></i></a>
							</ul>
							<div class="clearfix"></div>
						  </div>
						  <div class="x_content">
							<table class="table table-striped table-bordered jambo_table bulk_action">
								<thead>
								  <tr class="headings">
									  <th width="10%"></th>
									  <th width="15%">Requirement</th>
									  <th width="10%">From</th>
									  <th width="10%">To</th>
									  <th width="15%">Processed By</th>
									  <th width="10%">Status</th>
									  <th width="30%">Remarks</th>
								  </tr>
								</thead>
								<tbody>
								<?php
								$sql = "SELECT r.rid, r.rname, DATE_FORMAT(r.fr_dtime, '%m/%d/%Y %h:%i %p'), 
											DATE_FORMAT(r.to_dtime, '%m/%d/%Y %h:%i %p'), 
											CONCAT(u.lname,', ',u.fname), r.status, r.remarks
										FROM transactions_requirements r
											INNER JOIN users u ON r.user_id = u.user_id
										WHERE r.tid = '$tid'";
								$rres = query($sql); 
								while ($rrow = fetch_array($rres)) {
									?>
									<tr>
										<td align="center">
											<a href="e_requirement.php?rid=<?php echo $rrow[0] ?>" class="btn btn-warning btn-xs" data-toggle="tooltip" data-placement="top" title="Update Requirement"><i class="fa fa-pencil"></i></a>
											<a href="d_requirement.php?rid=<?php echo $rrow[0] ?>" class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top" title="Delete Requirement"><i class="fa fa-eraser"></i></a>
										</td>
										<td align="left"><?php echo $rrow[1] ?></td>
										<td align="left"><?php echo $rrow[2] ?></td>
										<td align="left"><?php echo $rrow[3] ?></td>
										<td align="left"><?php echo $rrow[4] ?></td>
										<td align="left"><?php 
											if ($rrow[5] == 0) { echo 'Failed';
											} elseif ($rrow[5] == 1) { echo 'Passed';
											} ?></td>
										<td align="left"><?php echo $rrow[6] ?></td>
									</tr>
									<?php
								}
								mysqli_free_result($rres);
								?>
								</tbody>
							</table>
						  </div>
						</div>
					  </div>
					  <!-- end of Routing Details -->
						
					  <div class="col-md-12 col-sm-12 col-xs-12">
						<div class="x_panel">
						  <div class="x_title">
							<h2>Attached Electronic Files</h2>
							<ul class="nav navbar-right panel_toolbox">
								<a href="add_transaction_efiles.php?tid=<?php echo $tid ?>" class="btn btn-primary btn-md" data-toggle="tooltip" data-placement="top" title="Attach Files"><i class="fa fa-plus"></i></a>
							</ul>
							<div class="clearfix"></div>
						  </div>
						  <div class="x_content">
						  
							<div class="tz-gallery">
								<div class="row">
									<?php
									$sql = "SELECT tfile from transactions_efiles WHERE tid = '$tid'";
									$res = query($sql); 
									while($row = fetch_array($res)) {
										?>
										<div class="col-sm-6 col-md-4">
											<a class="lightbox" href="<?php echo $row[0] ?>">
												<img src="<?php echo $row[0] ?>" height="100%" width="100%">
											</a>
										</div>
										<?php
									}
									?>
								</div>
							</div>
							<script src="js/baguetteBox.min.js"></script>
							<script>
								baguetteBox.run('.tz-gallery');
							</script>
							
						  </div>
						</div>
					  </div>
					  
					  
					
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


