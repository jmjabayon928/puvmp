<?php

include("db.php");
include("sqli.php");
include("html.php");
define('PAGE', 'requirement.php'); 

$prid = $_GET['prid'];

session_start();

if (isset($_SESSION['user_id'])) {
	if (validate_level(PAGE) == true) {

		// log the activity
		log_user(5, 'procurement_requirements', $prid);
		
		?>
		<!DOCTYPE html>
		<html lang="en">
		  <head>
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
			<!-- Meta, title, CSS, favicons, etc. -->
			<meta charset="utf-8">
			<meta http-equiv="X-UA-Compatible" content="IE=edge">
			<meta name="viewport" content="width=device-width, initial-scale=1">
			  
			<title>OTC | Procurement Requirement Details</title>

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
						<h3>Procurement Requirement Details</h3>
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
					
					  <!-- Procurement Info -->
						<?php
						$sql = "SELECT p.ref_num, p.budget, p.proc_name, DATE_FORMAT(p.publish_date, '%b %e, %Y'), 
									d.dname, DATE_FORMAT(p.closing_date, '%b %e, %Y'), CONCAT(e.lname,', ',e.fname),
									p.ptid, p.status, pt.pt_name, CONCAT(e2.lname,', ',e2.fname), pm.pm_name, 
									DATE_FORMAT(p.user_dtime, '%b %e, %Y %h:%i %p'), p.remarks, p.pid
								FROM procurements p 
								    INNER JOIN procurement_requirements pr ON pr.pid = p.pid
									INNER JOIN departments d ON p.did = d.did
									INNER JOIN employees e ON p.eid = e.eid
									INNER JOIN employees e2 ON p.user_id = e2.eid
									INNER JOIN procurement_types pt ON p.ptid = pt.ptid 
									INNER JOIN procurement_modes pm ON p.pmid = pm.pmid 
								WHERE pr.prid = '$prid'";
						$res = query($sql); 
						$row = fetch_array($res); 
						free_result($res);
						?>
					  <div class="col-md-12 col-sm-12 col-xs-12">
						<div class="x_panel">
						  <div class="x_title">
							<h2>Requirement Details</h2>
							<ul class="nav navbar-right panel_toolbox">
							</ul>
							<div class="clearfix"></div>
						  </div>
						  <div class="x_content">
						  
							<ul class="nav navbar-right panel_toolbox">
								<a href="e_requirement.php?pid=<?php echo $row[14] ?>" class="btn btn-warning btn-md"><i class="fa fa-pencil"></i> Edit </a>
							</ul>
							<table class="table table-striped table-bordered jambo_table">
							  <thead>
								<tr>
								  <th colspan="5">Procurement Details</th>
								</tr>
							  </thead>
							  <tbody>
								<tr>
									<td align="left" width="13%">Reference No.</td>
									<td align="left" width="37%"><b><?php echo $row[0] ?></b></td>
									<td align="left" width="13%">Budget</td>
									<td align="left" width="37%"><b><?php echo number_format($row[1], 2) ?></b></td>
								</tr>
								<tr>
									<td align="left">Procurement Project</td>
									<td align="left"><b><?php echo $row[2] ?></b></td>
									<td align="left">Publish Date</td>
									<td align="left"><b><?php echo $row[3] ?></b></td>
								</tr>
								<tr>
									<td align="left">Requesting Unit</td>
									<td align="left"><b><?php echo $row[4] ?></b></td>
									<td align="left">Closing Date</td>
									<td align="left"><b><?php echo $row[5] ?></b></td>
								</tr>
								<tr>
									<td align="left">End User</td>
									<td align="left"><b><?php echo $row[6] ?></b></td>
									<td align="left">Stage / Flow</td>
									<td align="left"><b><?php 
										$sql = "SELECT r.rname
												FROM procurement_requirements pr 
													INNER JOIN requirements r ON pr.rid = r.rid 
												WHERE pr.pid = '$row[14]' AND pr.finish_date = '0000-00-00'
												ORDER BY pr.pr_num 
												LIMIT 0, 1";
										$prres = query($sql); $prrow = fetch_array($prres); echo $prrow[0]; 
										?></b></td>
								</tr>
								<tr>
									<td align="left">Procurement Type</td>
									<td align="left"><b><?php echo $row[9] ?></b></td>
									<td align="left">Last Updated By</td>
									<td align="left"><b><?php echo $row[10] ?></b></td>
								</tr>
								<tr>
									<td align="left">Procurement Mode</td>
									<td align="left"><b><?php echo $row[11] ?></b></td>
									<td align="left">Date Last Updated</td>
									<td align="left"><b><?php echo $row[12] ?></b></td>
								</tr>
								<tr>
									<td align="left">Remarks</td>
									<td colspan="3" align="left"><b><?php echo $row[13] ?></b></td>
								</tr>
							  </tbody>
							</table>
						  
						  </div>
						</div>
					  </div>
					  <!-- end of Procurement Info -->
					  
					  <?php
					  $sql = "SELECT r.rname
							  FROM procurement_requirements pr 
								  INNER JOIN requirements r ON pr.rid = r.rid
							  WHERE pr.prid = '$prid'";
					  $pres = query($sql); $prow = fetch_array($pres); 
					  ?>
					  <div class="col-md-12 col-sm-12 col-xs-12">
						<div class="x_panel">
						  <div class="x_title">
							<h2>Attachments for <b><?php echo $prow[0] ?></b></h2>
							<ul class="nav navbar-right panel_toolbox">
								<a href="add_requirement_efile.php?prid=<?php echo $prid ?>" class="btn btn-success btn-md"><i class="fa fa-plus-square"></i> Attach Files </a>
							</ul>
							<div class="clearfix"></div>
						  </div>
						  <div class="x_content">
						  
									<div class="tz-gallery">

										<div class="row">
											<?php
											$sql = "SELECT efile from requirements_efiles WHERE prid = '$prid'";
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


