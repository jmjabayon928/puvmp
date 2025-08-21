<?php

include("db.php");
include("sqli.php");
include("html.php");
define('PAGE', 'procurement.php'); 

$pid = $_GET['pid'];

session_start();

if (isset($_SESSION['user_id'])) {
	if (validate_level(PAGE) == true) {

		// log the activity
		log_user(5, 'procurements', $pid);
		
		?>
		<!DOCTYPE html>
		<html lang="en">
		  <head>
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
			<!-- Meta, title, CSS, favicons, etc. -->
			<meta charset="utf-8">
			<meta http-equiv="X-UA-Compatible" content="IE=edge">
			<meta name="viewport" content="width=device-width, initial-scale=1">
			  
			<title>OTC | Procurement Details</title>

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
						<h3>Procurement Details</h3>
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
									DATE_FORMAT(p.user_dtime, '%b %e, %Y %h:%i %p'), p.remarks
								FROM procurements p 
									INNER JOIN departments d ON p.did = d.did
									INNER JOIN employees e ON p.eid = e.eid
									INNER JOIN employees e2 ON p.user_id = e2.eid
									INNER JOIN procurement_types pt ON p.ptid = pt.ptid 
									INNER JOIN procurement_modes pm ON p.pmid = pm.pmid 
								WHERE p.pid = '$pid'";
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
								<a href="e_procurement.php?pid=<?php echo $pid ?>" class="btn btn-warning btn-md"><i class="fa fa-pencil"></i> Edit </a>
								<a href="d_procurement.php?pid=<?php echo $pid ?>" class="btn btn-danger btn-md"><i class="fa fa-eraser"></i> Delete </a>
							</ul>
							<table class="table table-striped table-bordered jambo_table">
							  <thead>
								<tr>
								  <th colspan="4">Procurement Details</th>
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
												WHERE pid = '$pid' AND pr.finish_date = '0000-00-00'
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
						    <br />
							
							<h2>Checklist of Completed Procurement Documents</h2>
							<table class="table table-striped table-bordered jambo_table">
							  <thead>
								<tr>
								  <th width="10%"></th>
								  <th width="06%">No.</th>
								  <th width="29%">Procedural Flow</th>
								  <th width="12%">Responsible Officer</th>
								  <th width="08%">Date Completed</th>
								  <th width="27%">Remarks</th>
								  <th width="08%">With Attachment</th>
								</tr>
							  </thead>
							  <tbody>
							  <?php
							  $sql = "SELECT pr.prid, pr.pr_num, r.rname, pr.eid, 
										  DATE_FORMAT(pr.finish_date, '%b %e, %Y'), pr.remarks
									  FROM procurement_requirements pr 
										  INNER JOIN requirements r ON pr.rid = r.rid
									  WHERE pr.pid = '$pid' 
									  ORDER BY pr.pr_num";
							  $res = query($sql); 
							  while ($row = fetch_array($res)) {
								  ?>
								  <tr>
									<td align="center">
										<a href="requirement.php?prid=<?php echo $row[0] ?>" class="btn btn-info btn-xs" data-toggle="tooltip" data-placement="top" title="View Requirement"><i class="fa fa-search"></i></a>
										<a href="e_proc_requirement.php?prid=<?php echo $row[0] ?>" class="btn btn-warning btn-xs" data-toggle="tooltip" data-placement="top" title="Update Requirement"><i class="fa fa-pencil"></i></a>
										<!-- <a href="d_requirement.php?prid=<?php echo $row[0] ?>" class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top" title="Delete Requirement"><i class="fa fa-eraser"></i></a> -->
									</td>
									<td align="center"><?php echo $row[1] ?></td>
									<td align="left"><?php echo $row[2] ?></td>
									<td align="left"><?php
										if ($row[3] == -1) { echo 'Supplier'; 
										} elseif ($row[3] == 0) { echo 'End-User / Requesting Party'; 
										} elseif ($row[3] > 0) {
											$sql = "SELECT CONCAT(lname,', ',fname) FROM employees WHERE eid = '$row[3]'";
											$eres = query($sql); $erow = fetch_array($eres); echo $erow[0]; 
										}
										?></td>
									<td align="center"><?php echo $row[4] ?></td>
									<td align="left"><?php echo $row[5] ?></td>
									<td align="center"><?php
										$sql = "SELECT rfid FROM requirements_efiles WHERE prid = '$row[0]'";
										$ares = query($sql); $anum = num_rows($ares); 
										echo $anum > 0 ? 'Yes' : 'No';
										?></td>
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
					  <!-- end of Procurement Info -->
					  
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


