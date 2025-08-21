<?php

include("db.php");
include("sqli.php");
include("html.php");
define('PAGE', 'communication.php'); 

$cid = $_GET['cid'];

session_start();

if (isset($_SESSION['user_id'])) {
	if (validate_level(PAGE) == true) {

		// log the activity
		log_user(5, 'communications', $cid);
		
		?>
		<!DOCTYPE html>
		<html lang="en">
		  <head>
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
			<!-- Meta, title, CSS, favicons, etc. -->
			<meta charset="utf-8">
			<meta http-equiv="X-UA-Compatible" content="IE=edge">
			<meta name="viewport" content="width=device-width, initial-scale=1">
			  
			<title>OTC | Communication Details</title>

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
						<h3>Routine Action Slip</h3>
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
						$sql = "SELECT c.ref_num, c.direction, c.source_ofc, c.receiver_ofc, 
									c.source_person, c.receiver_person, c.source_position, c.receiver_position,
									DATE_FORMAT(c.ddate, '%m/%d/%Y'), DATE_FORMAT(c.rdate, '%m/%d/%Y'), 
									c.subject, c.remarks, CONCAT(u.lname,', ',u.fname), 
									DATE_FORMAT(c.user_dtime, '%m/%d/%Y %h:%i%p'), c.user_id
								FROM communications c INNER JOIN users u ON c.user_id = u.user_id
								WHERE c.cid = '$cid'";
						$res = query($sql); 
						$row = fetch_array($res); 
						free_result($res);
						?>
					  <div class="col-md-12 col-sm-12 col-xs-12">
						<div class="x_panel">
						  <div class="x_title">
							<h2>Communication Details</h2>
							<ul class="nav navbar-right panel_toolbox">
							  <a href="tcpdf/examples/pdf_route.php?cid=<?php echo $cid ?>" target="new" class="btn btn-info btn-md" data-toggle="tooltip" data-placement="top" title="Print Communication"><i class="glyphicon glyphicon-print"></i></a>
							  <?php
							  if ($row[14] == $_SESSION['user_id']) {
								  ?>
								  <a href="e_communication.php?cid=<?php echo $cid ?>" class="btn btn-warning btn-md" data-toggle="tooltip" data-placement="top" title="Update Communication"><i class="fa fa-pencil"></i></a>
								  <a href="d_communication.php?cid=<?php echo $cid ?>" class="btn btn-danger btn-md" data-toggle="tooltip" data-placement="top" title="Delete Communication"><i class="fa fa-eraser"></i></a>
								  <?php
							  }
							  ?>
							</ul>
							<div class="clearfix"></div>
						  </div>
						  <div class="x_content">
							<table class="table table-striped table-bordered jambo_table bulk_action">
								<thead>
								  <tr class="headings">
									  <th colspan="4">Communication Details</th>
								  </tr>
								</thead>
								<tbody>
									<tr>
										<td width="10%">Reference No.</td>
										<td width="30%"><b><?php echo $row[0] ?></b></td>
										<td width="15%">Type of Communication</td>
										<td width="45%"><b><?php 
											if ($row[1] == 1) { echo 'Incoming'; 
											} elseif ($row[1] == 2) { echo 'Out-going'; 
											} elseif ($row[1] == 3) { echo 'Internal'; } ?></b></td>
									</tr>
									<tr>
										<td>Sender's Office</td>
										<td><b><?php echo $row[2] ?></b></td>
										<td>Receiver's Office</td>
										<td><b><?php echo $row[3] ?></b></td>
									</tr>
									<tr>
										<td>Sender</td>
										<td><b><?php echo $row[4] ?></b></td>
										<td>Receiver</td>
										<td><b><?php echo $row[5] ?></b></td>
									</tr>
									<tr>
										<td>Sender's Position</td>
										<td><b><?php echo $row[6] ?></b></td>
										<td>Receiver's Position</td>
										<td><b><?php echo $row[7] ?></b></td>
									</tr>
									<tr>
										<td>Document Date</td>
										<td><b><?php echo $row[8] ?></b></td>
										<td>Date Received</td>
										<td><b><?php echo $row[9] ?></b></td>
									</tr>
									<tr>
										<td>Subject</td>
										<td><b><?php echo $row[10] ?></b></td>
										<td>Remarks</td>
										<td><b><?php echo $row[11] ?></b></td>
									</tr>
									<tr>
										<td>Updated By</td>
										<td><b><?php echo $row[12] ?></b></td>
										<td>Date Updated</td>
										<td><b><?php echo $row[13] ?></b></td>
									</tr>
								</tbody>
							</table>
						  </div>
						</div>
					  </div>
					  <!-- end of Communication Info -->
					
					  <!-- Routing Details -->
					  <div class="col-md-12 col-sm-12 col-xs-12">
						<div class="x_panel">
						  <div class="x_title">
							<h2>Routing Details</h2>
							<ul class="nav navbar-right panel_toolbox">
							  <?php
							  // if no routing exists, anyone can add a RAS. 
							  // otherwise, the last receiver of RAS can enter a new route
							  $sql = "SELECT rid FROM routings WHERE cid = '$cid'";
							  $rres = query($sql); 
							  $rnum = num_rows($rres); 
							  if ($rnum == 0) {
								  ?><a href="add_routing.php?cid=<?php echo $cid ?>" class="btn btn-primary btn-md" data-toggle="tooltip" data-placement="top" title="Add New Route"><i class="fa fa-plus"></i></a><?php
							  } else {
								  // get the last receiver, compare with session user_id
								  $sql = "SELECT rid 
										  FROM routings 
										  WHERE cid = '$cid' AND to_user_id = '$_SESSION[user_id]'
										  ORDER BY rid DESC
										  LIMIT 0, 1";
								  $lres = query($sql); 
								  $lnum = num_rows($lres); 
								  if ($lnum > 0) {
									  ?><a href="add_routing.php?cid=<?php echo $cid ?>" class="btn btn-primary btn-md" data-toggle="tooltip" data-placement="top" title="Add New Route"><i class="fa fa-plus"></i></a><?php
								  }
							  }
							  ?>
							</ul>
							<div class="clearfix"></div>
						  </div>
						  <div class="x_content">
							<table class="table table-striped table-bordered jambo_table bulk_action">
								<thead>
								  <tr class="headings">
									  <th width="10%"></th>
									  <th width="15%">Encoder /<br />Date Encoded</th>
									  <th width="15%">From Name /<br />Position</th>
									  <th width="15%">To Name /<br />Position</th>
									  <th width="15%">Received By /<br />Date Received</th>
									  <th width="30%">Remarks /<br />Instructions</th>
								  </tr>
								</thead>
								<tbody>
								<?php
								$sql = "SELECT r.rid, CONCAT(u.lname,', ',u.fname), DATE_FORMAT(r.user_dtime, '%m/%d/%Y %h:%i %p'), 
											CONCAT(e.lname,', ',e.fname), p.pname, r.to_user_id, r.receiver_id, 
											DATE_FORMAT(r.receiver_dtime, '%m/%d/%Y %h:%i %p'), r.remarks, r.user_id
										FROM routings r 
											INNER JOIN employees e ON r.from_user_id = e.eid
											INNER JOIN positions p ON e.pid = p.pid
											INNER JOIN users u ON r.user_id = u.user_id
										WHERE r.cid = '$cid'";
								$rres = query($sql); 
								while ($rrow = fetch_array($rres)) {
									// get the receiving person
									$sql = "SELECT CONCAT(e.lname,', ',e.fname), p.pname
											FROM employees e INNER JOIN positions p ON e.pid = p.pid
											WHERE e.eid = '$rrow[5]'";
									$pres = query($sql); 
									$prow = fetch_array($pres); 
									free_result ($pres); 
									?>
									<tr>
										<td align="center">
											<?php
											if ($rrow[6] == 0) {
												if ($rrow[9] == $_SESSION['user_id']) {
													?>
													<a href="e_routing.php?rid=<?php echo $rrow[0] ?>" class="btn btn-warning btn-xs" data-toggle="tooltip" data-placement="top" title="Update Route"><i class="fa fa-pencil"></i></a>
													<a href="d_routing.php?rid=<?php echo $rrow[0] ?>" class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top" title="Delete Route"><i class="fa fa-eraser"></i></a>
													<?php
												}
												// get the user_id of the employee_id (to_user_id)
												$sql = "SELECT user_id FROM users WHERE eid = '$rrow[5]'";
												$ures = query($sql); 
												$urow = fetch_array($ures); 
												if ($urow[0] == $_SESSION['user_id']) {
													?><a href="receive_routing.php?rid=<?php echo $rrow[0] ?>" class="btn btn-info btn-xs" data-toggle="tooltip" data-placement="top" title="Receive Route"><i class="fa fa-check"></i></a><?php
												}
												free_result($ures); 
											}
											?>
										</td>
										<td align="left"><?php echo $rrow[1].'<br />'.$rrow[2] ?></td>
										<td align="left"><?php echo $rrow[3].'<br />'.$rrow[4] ?></td>
										<td align="left"><?php echo $prow[0].'<br />'.$prow[1] ?></td>
										<td align="left"><?php 
											if ($rrow[6] != 0) {
												$sql = "SELECT CONCAT(lname,', ',fname) FROM employees WHERE eid = '$rrow[6]'";
												$res2 = query($sql); $row2 = fetch_array($res2); echo $row2[0].'<br />';
												free_result($res2); 
											}
											if ($rrow[7] != '00/00/0000 12:00 AM') echo $rrow[7];
											?></td>
										<td align="left"><?php echo $rrow[8] ?></td>
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
								<a href="add_communications_efiles.php?cid=<?php echo $cid ?>" class="btn btn-primary btn-md" data-toggle="tooltip" data-placement="top" title="Attach Files"><i class="fa fa-plus"></i></a>
							</ul>
							<div class="clearfix"></div>
						  </div>
						  <div class="x_content">
						  
							<div class="tz-gallery">
								<div class="row">
									<?php
									$sql = "SELECT cfile from communications_efiles WHERE cid = '$cid'";
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
			<!-- Custom Theme Scripts -->
			<script src="build/js/custom.min.js"></script>
			
		  </body>
		</html>
		<?php
	}
} else {
	header('Location: login.php');	
}


