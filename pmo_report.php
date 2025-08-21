<?php

include("db.php");
include("sqli.php");
include("html.php");
define('PAGE', 'pmo_report.php'); 

$rid = $_GET['rid'];

session_start();

if (isset($_SESSION['user_id'])) {
	if (validate_level(PAGE) == true) {

		// log the activity
		log_user(5, 'pmo_reports', $rid);
		
		?>
		<!DOCTYPE html>
		<html lang="en">
		  <head>
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
			<!-- Meta, title, CSS, favicons, etc. -->
			<meta charset="utf-8">
			<meta http-equiv="X-UA-Compatible" content="IE=edge">
			<meta name="viewport" content="width=device-width, initial-scale=1">
			  
			<title>OTC | Accomplishment Report</title>

			<!-- Bootstrap -->
			<link href="vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
			<!-- Font Awesome -->
			<link href="vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">

			<!-- Custom Theme Style -->
			<link href="build/css/custom.min.css" rel="stylesheet">
			<!-- Custom Theme Style -->
			<link href="css/baguetteBox.min.css" rel="stylesheet">
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
						<h3>Accomplishment Report</h3>
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
						$users = array(34, 36, 38, 85, 101, 102); 
					  
						if ($_SESSION['ulevel'] == 1 || $_SESSION['ulevel'] == 2 || in_array($_SESSION['user_id'], $users)) { // if admin / super user / allowed user
							show_pmo_report($rid); 
						} else { // user is RDO
							// check if the logged in user is the owner of the report
							$sql = "SELECT u.user_id 
									FROM pmo_reports r 
										INNER JOIN employees e ON r.eid = e.eid 
										INNER JOIN users u ON u.eid = e.eid 
									WHERE r.rid = '$rid'";
							$res = query($sql); 
							$row = fetch_array($res); 
							$user_id = $row[0]; 
							
							if ($user_id == $_SESSION['user_id']) {
								show_pmo_report($rid); 
							} else {
								?>
								  <div class="col-md-12 col-sm-12 col-xs-12">
									<div class="x_panel">
									  <div class="x_title">
										<h2></h2>
										<div class="clearfix"></div>
									  </div>
									  <div class="x_content">
									  <div class="bs-example" data-example-id="simple-jumbotron">
										<div class="jumbotron">
										  <h1>Access Denied!</h1>
										  <p>You can only view your own accomplishment reports</p>
										</div>
									  </div>
									  </div>
									</div>
								  </div>
								<?php
							}
						}
						?>
					  
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

function show_pmo_report($rid) {
	$sql = "SELECT o.oname, CONCAT(e.lname,', ',e.fname), 
				DATE_FORMAT(r.rstart, '%e %b %Y'),
				DATE_FORMAT(r.rend, '%e %b %Y'), CONCAT(u.lname,', ',u.fname), 
				DATE_FORMAT(r.user_dtime, '%e %b %Y %h:%i %p')
			FROM pmo_reports r 
				INNER JOIN employees e ON r.eid = e.eid 
				INNER JOIN pmo_offices o ON e.oid = o. oid 
				INNER JOIN users u on r.user_id = u.user_id
			WHERE r.rid = '$rid'";
	$res = query($sql); 
	$row = fetch_array($res); 
	free_result($res);
	?>
  <div class="col-md-12 col-sm-12 col-xs-12">
	<div class="x_panel">
	  <div class="x_title">
		<h2>Accomplishment Report</h2>
		<ul class="nav navbar-right panel_toolbox">
		  <a href="tcpdf/examples/pdf_pmo_report.php?rid=<?php echo $rid ?>" target="new" class="btn btn-info btn-md" data-toggle="tooltip" data-placement="top" title="Print Report"><i class="glyphicon glyphicon-print"></i></a>
		  <a href="e_pmo_report.php?rid=<?php echo $rid ?>" class="btn btn-warning btn-md" data-toggle="tooltip" data-placement="top" title="Update Report"><i class="fa fa-pencil"></i></a>
		  <a href="d_pmo_report.php?rid=<?php echo $rid ?>" class="btn btn-danger btn-md" data-toggle="tooltip" data-placement="top" title="Delete Report"><i class="fa fa-eraser"></i></a>
		</ul>
		<div class="clearfix"></div>
	  </div>
	  <div class="x_content">
		<table class="table table-striped table-bordered jambo_table bulk_action">
			<thead>
			  <tr class="headings">
				  <th colspan="4">Report Details</th>
			  </tr>
			</thead>
			<tbody>
				<tr>
					<td width="10%">Regional Office</td>
					<td width="30%"><b><?php echo $row[0] ?></b></td>
					<td width="15%">Coverage Start</td>
					<td width="45%"><b><?php echo $row[2] ?></b></td>
				</tr>
				<tr>
					<td>Employee</td>
					<td><b><?php echo $row[1] ?></b></td>
					<td>Coverage End</td>
					<td><b><?php echo $row[3] ?></b></td>
				</tr>
				<tr>
					<td>Last Updated By</td>
					<td><b><?php echo $row[4] ?></b></td>
					<td>Date Last Updated</td>
					<td><b><?php echo $row[5] ?></b></td>
				</tr>
			</tbody>
		</table>
	  </div>
	</div>
  </div>
  <!-- end of Accomplishment Report Info -->
  
  
  <!-- PUVMP-PMO ACTIVITIES -->
  <div class="col-md-12 col-sm-12 col-xs-12">
	<div class="x_panel">
	  <div class="x_title">
		<h2>PUVMP-PMO ACTIVITIES</h2>
		<ul class="nav navbar-right panel_toolbox">
		  <a href="add_accomplishment.php?rid=<?php echo $rid ?>&cid=1" class="btn btn-primary btn-md" data-toggle="tooltip" data-placement="top" title="Add Activity"><i class="fa fa-plus"></i></a>
		</ul>
		<div class="clearfix"></div>
	  </div>
	  <div class="x_content">
	  
			<table id="datatable-fixed-header" class="table table-striped table-bordered jambo_table">
			  <thead>
				<tr>
				  <th width="10%"></th>
				  <th width="60%">Activities</th>
				  <th width="15%">Last Updated By</th>
				  <th width="15%">Date Last Updated</th>
				</tr>
			  </thead>
			  <tbody>
				<?php
				$sql = "SELECT d.prid, d.rdetails, CONCAT(u.lname,', ',u.fname),
							DATE_FORMAT(d.user_dtime, '%e %b %Y, %h:%i %p')
						FROM pmo_reports_details d 
							INNER JOIN users u ON d.user_id = u.user_id
						WHERE d.rid = '$rid' AND d.cid = 1";
				$res = query($sql); 
				while ($row = fetch_array($res)) {
					?>
					<tr>
						<td align="center">
							<a href="e_accomplishment.php?prid=<?php echo $row[0] ?>" class="btn btn-warning btn-xs" data-toggle="tooltip" data-placement="top" title="Update Activity"><i class="fa fa-pencil"></i></a>
							<a href="d_accomplishment.php?prid=<?php echo $row[0] ?>" class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top" title="Delete Activity"><i class="fa fa-eraser"></i></a>
						</td>
						<td align="left"><?php echo $row[1] ?></td>
						<td align="center"><?php echo $row[2] ?></td>
						<td align="center"><?php echo $row[3] ?></td>
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
  <!-- end of PUVMP-PMO ACTIVITIES -->
  

  <!-- FIELD WORKS, TRAININGS, EVENTS AND MEETINGS -->
  <div class="col-md-12 col-sm-12 col-xs-12">
	<div class="x_panel">
	  <div class="x_title">
		<h2>FIELD WORKS, TRAININGS, EVENTS AND MEETINGS</h2>
		<ul class="nav navbar-right panel_toolbox">
		  <a href="add_accomplishment.php?rid=<?php echo $rid ?>&cid=2" class="btn btn-primary btn-md" data-toggle="tooltip" data-placement="top" title="Add Field Work / Events"><i class="fa fa-plus"></i></a>
		</ul>
		<div class="clearfix"></div>
	  </div>
	  <div class="x_content">
	  
			<table id="datatable-fixed-header" class="table table-striped table-bordered jambo_table">
			  <thead>
				<tr>
				  <th width="10%"></th>
				  <th width="60%">Activities</th>
				  <th width="15%">Last Updated By</th>
				  <th width="15%">Date Last Updated</th>
				</tr>
			  </thead>
			  <tbody>
				<?php
				$sql = "SELECT d.prid, d.rdetails, CONCAT(u.lname,', ',u.fname),
							DATE_FORMAT(d.user_dtime, '%e %b %Y, %h:%i %p')
						FROM pmo_reports_details d 
							INNER JOIN users u ON d.user_id = u.user_id
						WHERE d.rid = '$rid' AND d.cid = 2";
				$res = query($sql); 
				while ($row = fetch_array($res)) {
					?>
					<tr>
						<td align="center">
							<a href="e_accomplishment.php?prid=<?php echo $row[0] ?>" class="btn btn-warning btn-xs" data-toggle="tooltip" data-placement="top" title="Update Activity"><i class="fa fa-pencil"></i></a>
							<a href="d_accomplishment.php?prid=<?php echo $row[0] ?>" class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top" title="Delete Activity"><i class="fa fa-eraser"></i></a>
						</td>
						<td align="left"><?php echo $row[1] ?></td>
						<td align="center"><?php echo $row[2] ?></td>
						<td align="center"><?php echo $row[3] ?></td>
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
  <!-- end of FIELD WORKS, TRAININGS, EVENTS AND MEETINGS -->
  

	
  <div class="col-md-12 col-sm-12 col-xs-12">
	<div class="x_panel">
	  <div class="x_title">
		<h2>Attached Electronic Files</h2>
		<ul class="nav navbar-right panel_toolbox">
			<a href="add_pmo_report_efiles.php?rid=<?php echo $rid ?>" class="btn btn-primary btn-md" data-toggle="tooltip" data-placement="top" title="Attach Files"><i class="fa fa-plus"></i> Attach Files</a>
			<a href="pmo_report_gallery.php?rid=<?php echo $rid ?>" class="btn btn-info btn-md" data-toggle="tooltip" data-placement="top" title="Media Gallery Mode"><i class="fa fa-picture-o"></i> Gallery View</a>
			<a href="d_pmo_report_efiles.php?rid=<?php echo $rid ?>" class="btn btn-danger btn-md" data-toggle="tooltip" data-placement="top" title="Delete Attached Files"><i class="fa fa-eraser"></i> Delete All Files</a>
		</ul>
		<div class="clearfix"></div>
	  </div>
	  <div class="x_content">
	  
		<div class="tz-gallery">
			<div class="row">
				<?php
				$sql = "SELECT rfile from pmo_reports_efiles WHERE rid = '$rid'";
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
	<?php
}

