<?php

include("db.php");
include("sqli.php");
include("html.php");
define('PAGE', 'report.php'); 

$rid = $_GET['rid'];

session_start();

if (isset($_SESSION['user_id'])) {
	if (validate_level(PAGE) == true) {

		// log the activity
		log_user(5, 'reports', $rid);
		
		?>
		<!DOCTYPE html>
		<html lang="en">
		  <head>
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
			<!-- Meta, title, CSS, favicons, etc. -->
			<meta charset="utf-8">
			<meta http-equiv="X-UA-Compatible" content="IE=edge">
			<meta name="viewport" content="width=device-width, initial-scale=1">
			  
			<title>OTC | Report Details</title>

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
					<div class="page-title">
					  <div class="title_left">
						<h3>Report Details</h3>
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
					
					  <!-- Communication Info -->
						<?php
					    $sql = "SELECT rt.rt_name, d.dname, CONCAT(e.lname,', ',e.fname),
								    DATE_FORMAT(r.due_date, '%b %e, %Y'), 
								    DATE_FORMAT(r.submit_date, '%b %e, %Y'),
								    DATE_FORMAT(r.accepted_date, '%b %e, %Y'), 
									CONCAT(e2.lname,', ',e2.fname), 
									r.status, r.remarks, CONCAT(e3.lname,', ',e3.fname),
									DATE_FORMAT(r.user_dtime, '%b %e, %Y %h:%i %p')
							    FROM reports r 
								    LEFT JOIN reports_types rt ON r.rtid = rt.rtid 
								    LEFT JOIN employees e ON r.eid = e.eid
								    LEFT JOIN employees e2 ON r.accepted_by = e2.eid
								    LEFT JOIN employees e3 ON r.user_id = e3.eid
								    LEFT JOIN departments d ON r.did = d.did
							    WHERE r.rid = '$rid'";
						$res = query($sql); 
						$row = fetch_array($res); 
						free_result($res);
						?>
					  <div class="col-md-12 col-sm-12 col-xs-12">
						<div class="x_panel">
						  <div class="x_title">
							<h2>Report Details</h2>
							<ul class="nav navbar-right panel_toolbox">
							</ul>
							<div class="clearfix"></div>
						  </div>
						  <div class="x_content">
						  
							<ul class="nav navbar-right panel_toolbox">
								<a href="e_report.php?rid=<?php echo $rid ?>" class="btn btn-warning btn-md"><i class="fa fa-pencil"></i> Edit </a>
							</ul>
							<table class="table table-striped table-bordered jambo_table">
							  <thead>
								<tr>
								  <th colspan="4">Report Details</th>
								</tr>
							  </thead>
							  <tbody>
								<tr>
									<td align="left" width="13%">Name of Report</td>
									<td align="left" width="37%"><b><?php echo $row[0] ?></b></td>
									<td align="left" width="13%">Date Accepted</td>
									<td align="left" width="37%"><b><?php echo $row[5] ?></b></td>
								</tr>
								<tr>
									<td align="left">Responsible Unit</td>
									<td align="left"><b><?php echo $row[1] ?></b></td>
									<td align="left">Accepted By</td>
									<td align="left"><b><?php echo $row[6] ?></b></td>
								</tr>
								<tr>
									<td align="left">Responsible Person</td>
									<td align="left"><b><?php echo $row[2] ?></b></td>
									<td align="left">Status</td>
									<td align="left"><b><?php 
									if ($row[7] == 1) { echo 'Accepted';
									} elseif ($row[7] == 0) { echo 'On Process';
									} elseif ($row[7] == -1) { echo 'Returned';
									} elseif ($row[7] == 2) { echo 'To be submitted';
									}
									?></b></td>
								</tr>
								<tr>
									<td align="left">Due Date</td>
									<td align="left"><b><?php echo $row[3] ?></b></td>
									<td align="left">Last Updated By</td>
									<td align="left"><b><?php echo $row[9] ?></b></td>
								</tr>
								<tr>
									<td align="left">Date Submitted</td>
									<td align="left"><b><?php echo $row[4] ?></b></td>
									<td align="left">Date Last Updated</td>
									<td align="left"><b><?php echo $row[10] ?></b></td>
								</tr>
								<tr>
									<td align="left">Remarks</td>
									<td colspan="3" align="left"><b><?php echo $row[8] ?></b></td>
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
							<h2>Attachments</b></h2>
							<ul class="nav navbar-right panel_toolbox">
								<a href="add_report_efile.php?rid=<?php echo $rid ?>" class="btn btn-success btn-md"><i class="fa fa-plus-square"></i> Attach Files </a>
							</ul>
							<div class="clearfix"></div>
						  </div>
						  <div class="x_content">
						  
								<div class="tz-gallery">

									<div class="row">
										<?php
										$sql = "SELECT rfile from reports_efiles WHERE rid = '$rid'";
										$eres = query($sql); 
										while($erow = fetch_array($eres)) {
											?>
											<div class="col-sm-6 col-md-4">
												<a class="lightbox" href="<?php echo $erow[0] ?>">
													<img src="<?php echo $erow[0] ?>" height="100%" width="100%">
												</a>
											</div>
											<?php
										}
										free_result($eres); 
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
			<!-- Custom Theme Scripts -->
			<script src="build/js/custom.min.js"></script>
			
		  </body>
		</html>
		<?php
	}
} else {
	header('Location: login.php');	
}


