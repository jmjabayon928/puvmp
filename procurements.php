<?php

include("db.php");
include("sqli.php");
include("html.php");
define('PAGE', 'procurements.php'); 

session_start();

if (isset($_SESSION['user_id'])) {
	if (validate_level(PAGE) == true) {

		// log the activity
		log_user(5, 'reports', 0);
		
		?>
		<!DOCTYPE html>
		<html lang="en">
		  <head>
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
			<!-- Meta, title, CSS, favicons, etc. -->
			<meta charset="utf-8">
			<meta http-equiv="X-UA-Compatible" content="IE=edge">
			<meta name="viewport" content="width=device-width, initial-scale=1">

			<title>OTC - Procurements</title>

			<!-- Bootstrap -->
			<link href="vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
			<!-- Font Awesome -->
			<link href="vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
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
				
				if (isset ($_GET['tab'])) {
					$tab = $_GET['tab'];
				} else {
					$tab = 1; 
				}
				
				if (isset ($_GET['fr_date'])) {
					$fr_date = $_GET['fr_date']; 
				} else {
					// get the first day of the month
					$sql = "SELECT DATE_SUB(LAST_DAY(NOW()), INTERVAL DAY(LAST_DAY(NOW())) - 1 DAY)";
					$fr_res = query($sql); 
					$fr_row = fetch_array($fr_res); 
					$fr_date = $fr_row[0]; 
				}
				
				if (isset ($_GET['to_date'])) {
					$to_date = $_GET['to_date']; 
				} else {
					// get the last day of the month 
					$sql = "SELECT LAST_DAY(NOW())";
					$to_res = query($sql); 
					$to_row = fetch_array($to_res); 
					$to_date = $to_row[0]; 
				}
				
				?>

				<!-- page content -->
				<div class="right_col" role="main">
				  <div class="">
					<div class="page-title">
					  <div class="title_left">
						<h3>
						<?php
						if ($tab == 1) { echo 'Types of Procurement';
						} elseif ($tab == 2) { echo 'Procurement Modes';
						} elseif ($tab == 3) { echo 'Procurement Requirements';
						} elseif ($tab == 4) { echo 'Browse Procurements';
						}
						?>
						</h3>
					  </div>

					  <div class="title_right">
						<div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
						</div>
					  </div>
					</div>

					<div class="clearfix"></div>

					<div class="row">
					  <div class="col-md-12 col-sm-12 col-xs-12">
						<div class="x_panel">
						  <div class="x_title">
							  <?php
							  ?>

							<ul class="nav navbar-right panel_toolbox">
							<?php
							if ($tab == 1) { 
							} elseif ($tab == 2) { 
							} elseif ($tab == 3) { 
							} elseif ($tab == 4) { 
								?><a href="add_procurement.php" class="btn btn-primary btn-md" data-toggle="tooltip" data-placement="top" title="Add Procurement"><i class="fa fa-plus-square"></i> Add </a><?php
							}
							?>
							</ul>
							<div class="clearfix"></div>
						  </div>
						  <div class="x_content">
							<?php
							if ($tab == 1) {
								?>
								<table class="table table-striped table-bordered jambo_table">
								  <thead>
									<tr>
									  <th width="10%"></th>
									  <th width="45%">Type</th>
									  <th width="45%">Description</th>
									</tr>
								  </thead>
								  <tbody>
								  <?php
								  $sql = "SELECT ptid, pt_name, pt_desc FROM procurement_types";
								  $res = query($sql); 
								  while ($row = fetch_array($res)) {
									  ?>
									  <tr>
										<td align="center">
											<a href="e_procurement_type.php?ptid=<?php echo $row[0] ?>" class="btn btn-warning btn-xs" data-toggle="tooltip" data-placement="top" title="Update Type"><i class="fa fa-pencil"></i></a>
											<a href="d_procurement_type.php?ptid=<?php echo $row[0] ?>" class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top" title="Delete Type"><i class="fa fa-eraser"></i></a>
										</td>
										<td align="left"><?php echo $row[1] ?></td>
										<td align="left"><?php echo $row[2] ?></td>
									  </tr>
									  <?php
								  }
								  free_result($res); 
								  ?>
								  </tbody>
								</table>
								<?php
							} elseif ($tab == 2) {
								?>
								<table class="table table-striped table-bordered jambo_table">
								  <thead>
									<tr>
									  <th width="10%"></th>
									  <th width="45%">Mode</th>
									  <th width="45%">Description</th>
									</tr>
								  </thead>
								  <tbody>
								  <?php
								  $sql = "SELECT pmid, pm_name, pm_desc FROM procurement_modes";
								  $res = query($sql); 
								  while ($row = fetch_array($res)) {
									  ?>
									  <tr>
										<td align="center">
											<a href="e_procurement_mode.php?pmid=<?php echo $row[0] ?>" class="btn btn-warning btn-xs" data-toggle="tooltip" data-placement="top" title="Update Mode"><i class="fa fa-pencil"></i></a>
											<a href="d_procurement_mode.php?pmid=<?php echo $row[0] ?>" class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top" title="Delete Mode"><i class="fa fa-eraser"></i></a>
										</td>
										<td align="left"><?php echo $row[1] ?></td>
										<td align="left"><?php echo $row[2] ?></td>
									  </tr>
									  <?php
								  }
								  free_result($res); 
								  ?>
								  </tbody>
								</table>
								<?php
							} elseif ($tab == 3) {
								$sql = "SELECT ptid, pt_name, pt_desc FROM procurement_types";
								$tres = query($sql); 
								while ($trow = fetch_array($tres)) {
									?>
									<h2><?php echo $trow[1] ?></h2>
									<table class="table table-striped table-bordered jambo_table">
									  <thead>
										<tr>
										  <th width="10%"></th>
										  <th width="10%">Requirement No.</th>
										  <th width="40%">Requirement Details</th>
										  <th width="40%">Responsible Officer</th>
										</tr>
									  </thead>
									  <tbody>
									  <?php
									  $sql = "SELECT rid, rnum, rname, reid 
											  FROM requirements 
											  WHERE ptid = '$trow[0]' 
											  ORDER BY rnum";
									  $res = query($sql); 
									  while ($row = fetch_array($res)) {
										  ?>
										  <tr>
											<td align="center">
												<a href="e_requirement.php?rid=<?php echo $row[0] ?>" class="btn btn-warning btn-xs" data-toggle="tooltip" data-placement="top" title="Update Requirement"><i class="fa fa-pencil"></i></a>
												<a href="d_requirement.php?rid=<?php echo $row[0] ?>" class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top" title="Delete Requirement"><i class="fa fa-eraser"></i></a>
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
										  </tr>
										  <?php
									  }
									  free_result($res); 
									  ?>
									  </tbody>
									</table>
									<br /><br />
									<?php
								} 
								free_result($tres); 
							} elseif ($tab == 4) {
								?>
								<table id="datatable-fixed-header" class="table table-striped table-bordered jambo_table">
								  <thead>
									<tr>
									  <th width="10%"></th>
									  <th width="08%">Reference</th>
									  <th width="15%">Project</th>
									  <th width="15%">Requesting Unit</th>
									  <th width="15%">Mode</th>
									  <th width="08%">ABC</th>
									  <th width="08%">Published</th>
									  <th width="08%">Closing</th>
									  <th width="13%">Stage / Flow</th>
									</tr>
								  </thead>
								  <tbody>
								  <?php
								  $sql = "SELECT p.pid, p.ref_num, p.proc_name, d.dname, m.pm_desc, 
											  p.budget, DATE_FORMAT(p.publish_date, '%m/%d/%Y'), 
											  DATE_FORMAT(p.closing_date, '%m/%d/%Y'), p.ptid, p.status
										  FROM procurements p 
											  INNER JOIN departments d ON p.did = d.did 
											  INNER JOIN procurement_modes m ON p.pmid = m.pmid 
										  ORDER BY ref_num";
								  $res = query($sql); 
								  while ($row = fetch_array($res)) {
									  ?>
									  <tr>
										  <td align="center">
											  <a href="procurement.php?pid=<?php echo $row[0] ?>" class="btn btn-info btn-xs" data-toggle="tooltip" data-placement="top" title="View Procurement"><i class="fa fa-search"></i></a>
											  <a href="e_procurement.php?pid=<?php echo $row[0] ?>" class="btn btn-warning btn-xs" data-toggle="tooltip" data-placement="top" title="Update Procurement"><i class="fa fa-pencil"></i></a>
											  <a href="d_procurement.php?pid=<?php echo $row[0] ?>" class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top" title="Delete Procurement"><i class="fa fa-eraser"></i></a>
										  </td>
										  <td align="center"><?php echo $row[1] ?></td>
										  <td align="left"><?php echo $row[2] ?></td>
										  <td align="left"><?php echo $row[3] ?></td>
										  <td align="left"><?php echo $row[4] ?></td>
										  <td align="right"><?php echo number_format($row[5], 2) ?></td>
										  <td align="center"><?php echo $row[6] ?></td>
										  <td align="center"><?php echo $row[7] ?></td>
										  <td align="left">
										    <?php
											$sql = "SELECT r.rname
											        FROM procurement_requirements pr 
														INNER JOIN requirements r ON pr.rid = r.rid 
													WHERE pid = '$row[0]' AND pr.finish_date = '0000-00-00'
													ORDER BY pr.pr_num 
													LIMIT 0, 1";
											$prres = query($sql); $prrow = fetch_array($prres); echo $prrow[0]; 
											?>
										  </td>
									  </tr>
									  <?php
								  }
								  free_result($res); 
								  ?>
								  </tbody>
								</table>
								<?php
							}
							?>
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

			<!-- Custom Theme Scripts -->
			<script src="build/js/custom.min.js"></script>

		  </body>
		</html>		
		<?php
	}
} else {
	header('Location: login.php');	
}
