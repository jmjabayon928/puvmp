<?php

include("db.php");
include("sqli.php");
include("html.php");
define('PAGE', 'reports.php'); 

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

			<title>OTC - Accomplishment Reports</title>

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
				
				if (isset ($_GET['frequency'])) {
					$frequency = $_GET['frequency'];
				} else {
					$frequency = 0; 
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
						if ($tab == 1) { echo 'Accomplishment Reports';
						} elseif ($tab == 2) { echo 'Reports Monitoring';
						} elseif ($tab == 3) { echo 'Types of Report';
						}
						?>
						</h3>
					  </div>

					  <div class="title_right">
						<div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
							<?php
							if ($tab != 3) {
								?>
								From: <input type="date" id="fr_date" value="<?php echo $fr_date ?>" onchange="myFunction('fr_date', 'to_date');">
								To: <input type="date" id="to_date" value="<?php echo $to_date ?>" onchange="myFunction2('fr_date', 'to_date');">
								<script>
								  function myFunction(FrDate, ToDate) {
									var FrDate = document.getElementById(FrDate).value;
									var ToDate = document.getElementById(ToDate).value;
									var tab = <?php echo $tab ?>;
									var frequency = <?php echo $frequency ?>;
									window.location.replace('reports.php?tab='+tab+'&fr_date='+FrDate+'&to_date='+ToDate+'&frequency='+frequency)
								  }
								  function myFunction2(FrDate, ToDate) {
									var FrDate = document.getElementById(FrDate).value;
									var ToDate = document.getElementById(ToDate).value;
									var tab = <?php echo $tab ?>;
									var frequency = <?php echo $frequency ?>;
									window.location.replace('reports.php?tab='+tab+'&fr_date='+FrDate+'&to_date='+ToDate+'&frequency='+frequency)
								  }
								</script>
								<?php
							}
							?>
						</div>
					  </div>
					</div>

					<div class="clearfix"></div>

					<div class="row">
					  <div class="col-md-12 col-sm-12 col-xs-12">
						<div class="x_panel">
						  <div class="x_title">
							  <?php
							  if ($tab == 1) {
								  ?>
								  <a href="add_report.php" class="btn btn-primary btn-md" data-toggle="tooltip" data-placement="top" title="Add New Report"><i class="fa fa-plus-square"></i> Add </a>
								  <?php
							  } elseif ($tab == 2) {
								  ?>
								  <?php
							  } elseif ($tab == 3) {
								  ?>
								  <?php
							  }
							  ?>
							<!-- <a href="tcpdf/examples/pdf_communications.php?fr_date=<?php echo $fr_date ?>&to_date=<?php echo $to_date ?>&tab=<?php echo $tab ?>&sortby=<?php echo $sortby ?>" target="new" class="btn btn-info btn-md" data-toggle="tooltip" data-placement="top" title="Print Communications"><i class="glyphicon glyphicon-print"></i> Print </a> -->

							<ul class="nav navbar-right panel_toolbox">
							  <?php
							  if ($tab != 3) {
								  ?>
								  <select class="form-control col-md-7 col-xs-12" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
									<?php
									if ($frequency == 0) {
										?>
										<option value="reports.php?fr_date=<?php echo $fr_date ?>&to_date=<?php echo $to_date ?>&tab=<?php echo $tab ?>&frequency=0" Selected>All Types</option>
										<option value="reports.php?fr_date=<?php echo $fr_date ?>&to_date=<?php echo $to_date ?>&tab=<?php echo $tab ?>&frequency=1">Monthly</option>
										<option value="reports.php?fr_date=<?php echo $fr_date ?>&to_date=<?php echo $to_date ?>&tab=<?php echo $tab ?>&frequency=2">Quarterly</option>
										<option value="reports.php?fr_date=<?php echo $fr_date ?>&to_date=<?php echo $to_date ?>&tab=<?php echo $tab ?>&frequency=3">Semi-Annual</option>
										<option value="reports.php?fr_date=<?php echo $fr_date ?>&to_date=<?php echo $to_date ?>&tab=<?php echo $tab ?>&frequency=4">Annual</option>
										<option value="reports.php?fr_date=<?php echo $fr_date ?>&to_date=<?php echo $to_date ?>&tab=<?php echo $tab ?>&frequency=5">Other</option>
										<?php 
									} elseif ($frequency == 1) {
										?>
										<option value="reports.php?fr_date=<?php echo $fr_date ?>&to_date=<?php echo $to_date ?>&tab=<?php echo $tab ?>&frequency=0">All Types</option>
										<option value="reports.php?fr_date=<?php echo $fr_date ?>&to_date=<?php echo $to_date ?>&tab=<?php echo $tab ?>&frequency=1" Selected>Monthly</option>
										<option value="reports.php?fr_date=<?php echo $fr_date ?>&to_date=<?php echo $to_date ?>&tab=<?php echo $tab ?>&frequency=2">Quarterly</option>
										<option value="reports.php?fr_date=<?php echo $fr_date ?>&to_date=<?php echo $to_date ?>&tab=<?php echo $tab ?>&frequency=3">Semi-Annual</option>
										<option value="reports.php?fr_date=<?php echo $fr_date ?>&to_date=<?php echo $to_date ?>&tab=<?php echo $tab ?>&frequency=4">Annual</option>
										<option value="reports.php?fr_date=<?php echo $fr_date ?>&to_date=<?php echo $to_date ?>&tab=<?php echo $tab ?>&frequency=5">Other</option>
										<?php 
									} elseif ($frequency == 2) {
										?>
										<option value="reports.php?fr_date=<?php echo $fr_date ?>&to_date=<?php echo $to_date ?>&tab=<?php echo $tab ?>&frequency=0">All Types</option>
										<option value="reports.php?fr_date=<?php echo $fr_date ?>&to_date=<?php echo $to_date ?>&tab=<?php echo $tab ?>&frequency=1">Monthly</option>
										<option value="reports.php?fr_date=<?php echo $fr_date ?>&to_date=<?php echo $to_date ?>&tab=<?php echo $tab ?>&frequency=2" Selected>Quarterly</option>
										<option value="reports.php?fr_date=<?php echo $fr_date ?>&to_date=<?php echo $to_date ?>&tab=<?php echo $tab ?>&frequency=3">Semi-Annual</option>
										<option value="reports.php?fr_date=<?php echo $fr_date ?>&to_date=<?php echo $to_date ?>&tab=<?php echo $tab ?>&frequency=4">Annual</option>
										<option value="reports.php?fr_date=<?php echo $fr_date ?>&to_date=<?php echo $to_date ?>&tab=<?php echo $tab ?>&frequency=5">Other</option>
										<?php 
									} elseif ($frequency == 3) {
										?>
										<option value="reports.php?fr_date=<?php echo $fr_date ?>&to_date=<?php echo $to_date ?>&tab=<?php echo $tab ?>&frequency=0">All Types</option>
										<option value="reports.php?fr_date=<?php echo $fr_date ?>&to_date=<?php echo $to_date ?>&tab=<?php echo $tab ?>&frequency=1">Monthly</option>
										<option value="reports.php?fr_date=<?php echo $fr_date ?>&to_date=<?php echo $to_date ?>&tab=<?php echo $tab ?>&frequency=2">Quarterly</option>
										<option value="reports.php?fr_date=<?php echo $fr_date ?>&to_date=<?php echo $to_date ?>&tab=<?php echo $tab ?>&frequency=3" Selected>Semi-Annual</option>
										<option value="reports.php?fr_date=<?php echo $fr_date ?>&to_date=<?php echo $to_date ?>&tab=<?php echo $tab ?>&frequency=4">Annual</option>
										<option value="reports.php?fr_date=<?php echo $fr_date ?>&to_date=<?php echo $to_date ?>&tab=<?php echo $tab ?>&frequency=5">Other</option>
										<?php 
									} elseif ($frequency == 4) {
										?>
										<option value="reports.php?fr_date=<?php echo $fr_date ?>&to_date=<?php echo $to_date ?>&tab=<?php echo $tab ?>&frequency=0">All Types</option>
										<option value="reports.php?fr_date=<?php echo $fr_date ?>&to_date=<?php echo $to_date ?>&tab=<?php echo $tab ?>&frequency=1">Monthly</option>
										<option value="reports.php?fr_date=<?php echo $fr_date ?>&to_date=<?php echo $to_date ?>&tab=<?php echo $tab ?>&frequency=2">Quarterly</option>
										<option value="reports.php?fr_date=<?php echo $fr_date ?>&to_date=<?php echo $to_date ?>&tab=<?php echo $tab ?>&frequency=3">Semi-Annual</option>
										<option value="reports.php?fr_date=<?php echo $fr_date ?>&to_date=<?php echo $to_date ?>&tab=<?php echo $tab ?>&frequency=4" Selected>Annual</option>
										<option value="reports.php?fr_date=<?php echo $fr_date ?>&to_date=<?php echo $to_date ?>&tab=<?php echo $tab ?>&frequency=5">Other</option>
										<?php 
									} elseif ($frequency == 5) {
										?>
										<option value="reports.php?fr_date=<?php echo $fr_date ?>&to_date=<?php echo $to_date ?>&tab=<?php echo $tab ?>&frequency=0">All Types</option>
										<option value="reports.php?fr_date=<?php echo $fr_date ?>&to_date=<?php echo $to_date ?>&tab=<?php echo $tab ?>&frequency=1">Monthly</option>
										<option value="reports.php?fr_date=<?php echo $fr_date ?>&to_date=<?php echo $to_date ?>&tab=<?php echo $tab ?>&frequency=2">Quarterly</option>
										<option value="reports.php?fr_date=<?php echo $fr_date ?>&to_date=<?php echo $to_date ?>&tab=<?php echo $tab ?>&frequency=3">Semi-Annual</option>
										<option value="reports.php?fr_date=<?php echo $fr_date ?>&to_date=<?php echo $to_date ?>&tab=<?php echo $tab ?>&frequency=4">Annual</option>
										<option value="reports.php?fr_date=<?php echo $fr_date ?>&to_date=<?php echo $to_date ?>&tab=<?php echo $tab ?>&frequency=5" Selected>Other</option>
										<?php 
									}
									?>
								  </select>
								  <?php
							  } else {
								  ?>
								  <?php
							  }
							  ?>
							</ul>
							<div class="clearfix"></div>
						  </div>
						  <div class="x_content">
							<?php
							if ($tab == 1) {
								?>
								<table id="datatable-fixed-header" class="table table-striped table-bordered jambo_table">
								  <thead>
									<tr>
									  <th width="10%"></th>
									  <th width="20%">Name of Report</th>
									  <th width="20%">Responsible Unit/Person</th>
									  <th width="08%">Due Date</th>
									  <th width="08%">Submitted</th>
									  <th width="08%">Accepted</th>
									  <th width="08%">Status</th>
									  <th width="18%">Remarks</th>
									</tr>
								  </thead>
								  <tbody>
								  <?php
								  if ($frequency != 0) {
									  $sql = "SELECT r.rid, rt.rt_name, d.dname, CONCAT(e.lname,', ',e.fname),
												  DATE_FORMAT(r.due_date, '%m/%d/%Y'), 
												  DATE_FORMAT(r.submit_date, '%m/%d/%Y'),
												  DATE_FORMAT(r.accepted_date, '%m/%d/%Y'), r.status, r.remarks
											  FROM reports r 
												  LEFT JOIN reports_types rt ON r.rtid = rt.rtid 
												  LEFT JOIN employees e ON r.eid = e.eid
												  LEFT JOIN departments d ON r.did = d.did
											  WHERE rt.frequency = '$frequency' 
												  AND TO_DAYS(r.due_date) >= TO_DAYS('$fr_date') AND TO_DAYS(r.due_date) <= TO_DAYS('$to_date')
											  ORDER BY r.due_date DESC";
								  } else {
									  $sql = "SELECT r.rid, rt.rt_name, d.dname, CONCAT(e.lname,', ',e.fname),
												  DATE_FORMAT(r.due_date, '%m/%d/%Y'), 
												  DATE_FORMAT(r.submit_date, '%m/%d/%Y'),
												  DATE_FORMAT(r.accepted_date, '%m/%d/%Y'), r.status, r.remarks
											  FROM reports r 
												  LEFT JOIN reports_types rt ON r.rtid = rt.rtid 
												  LEFT JOIN employees e ON r.eid = e.eid
												  LEFT JOIN departments d ON r.did = d.did
											  WHERE TO_DAYS(r.due_date) >= TO_DAYS('$fr_date') AND TO_DAYS(r.due_date) <= TO_DAYS('$to_date')
											  ORDER BY r.due_date DESC";
								  }
								  $res = query($sql); 
								  while ($row = fetch_array($res)) {
									  ?>
									  <tr>
										<td align="center">
											<!-- <a href="add_report_efile.php?rid=<?php echo $row[0] ?>" class="btn btn-primary btn-xs" data-toggle="tooltip" data-placement="top" title="Attach Files"><i class="fa fa-plus-square"></i></a> -->
											<a href="report.php?rid=<?php echo $row[0] ?>" class="btn btn-info btn-xs" data-toggle="tooltip" data-placement="top" title="View Details"><i class="fa fa-search"></i></a>
											<a href="e_report.php?rid=<?php echo $row[0] ?>" class="btn btn-warning btn-xs" data-toggle="tooltip" data-placement="top" title="Update Report"><i class="fa fa-pencil"></i></a>
											<a href="d_report.php?rid=<?php echo $row[0] ?>" class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top" title="Delete Report"><i class="fa fa-eraser"></i></a>
										</td>
										<td align="left"><?php echo $row[1] ?></td>
										<td align="left"><b><?php echo $row[2].'</b><br />'.$row[3] ?></td>
										<td align="center"><?php echo $row[4] != '00/00/0000' ? $row[4] : '' ?></td>
										<td align="center"><?php echo $row[5] != '00/00/0000' ? $row[5] : '' ?></td>
										<td align="center"><?php echo $row[6] != '00/00/0000' ? $row[6] : '' ?></td>
										<td align="left"><?php
											if ($row[7] == 1) { echo 'Accepted';
											} elseif ($row[7] == 0) { echo 'On process';
											} elseif ($row[7] == -1) { echo 'Returned';
											} elseif ($row[7] == 2) { echo 'To be submitted';
											}
										?></td>
										<td align="left"><?php echo $row[8] ?></td>
									  </tr>
									  <?php
								  }
								  free_result($res); 
								  ?>
								  </tbody>
								</table>
								<?php
							} elseif ($tab == 2) {
								$sql = "SELECT did, dname FROM departments";
								$res = query($sql);
								while ($row = fetch_array($res)) {
									// submitted and accepted on or before the due date
									$sql = "SELECT rid FROM reports 
											WHERE did = '$row[0]' 
												AND TO_DAYS(due_date) >= TO_DAYS('$fr_date') 
												AND TO_DAYS(due_date) <= TO_DAYS('$to_date') 
												AND TO_DAYS(accepted_date) <= TO_DAYS(due_date) AND status = 1";
									$before_res = query($sql); $before_num = num_rows($before_res); 
									// submitted on or before the due date BUT accepted after the due date
									$sql = "SELECT rid FROM reports 
											WHERE did = '$row[0]' 
												AND TO_DAYS(due_date) >= TO_DAYS('$fr_date') 
												AND TO_DAYS(due_date) <= TO_DAYS('$to_date') 
												AND TO_DAYS(submit_date) <= TO_DAYS(due_date)
												AND TO_DAYS(accepted_date) > TO_DAYS(due_date) AND status = 1";
									$after_res = query($sql); $after_num = num_rows($after_res); 
									// submitted after due date but accepted
									$sql = "SELECT rid FROM reports 
											WHERE did = '$row[0]' 
												AND TO_DAYS(due_date) >= TO_DAYS('$fr_date') 
												AND TO_DAYS(due_date) <= TO_DAYS('$to_date') 
												AND TO_DAYS(submit_date) > TO_DAYS(due_date)
												AND TO_DAYS(accepted_date) > TO_DAYS(due_date) AND status = 1";
									$late_accepted_res = query($sql); $late_accepted_num = num_rows($late_accepted_res); 
									// submitted after due date and still not accepted
									$sql = "SELECT rid FROM reports 
											WHERE did = '$row[0]' 
												AND TO_DAYS(due_date) >= TO_DAYS('$fr_date') 
												AND TO_DAYS(due_date) <= TO_DAYS('$to_date') 
												AND TO_DAYS(submit_date) > TO_DAYS(due_date) AND status = -1";
									$not_accepted_res = query($sql); $not_accepted_num = num_rows($not_accepted_res); 
									?>
									<div class="row top_tiles">
									  <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
										<div class="tile-stats">
										  <div class="icon"><i class="fa fa-star"></i></div>
										  <div class="count green"><?php echo number_format($before_num, 0) ?></div>
										  <h3><?php echo $row[1] ?></h3>
										  <p>Submitted and accepted on or before due date</p>
										</div>
									  </div>
									  <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
										<div class="tile-stats">
										  <div class="icon"><i class="fa fa-thumbs-up"></i></div>
										  <div class="count blue"><?php echo number_format($after_num, 0) ?></div>
										  <h3><?php echo $row[1] ?></h3>
										  <p>Submitted on time but accepted after due date</p>
										</div>
									  </div>
									  <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
										<div class="tile-stats">
										  <div class="icon"><i class="fa fa-database"></i></div>
										  <div class="count"><?php echo number_format($late_accepted_num, 0) ?></div>
										  <h3><?php echo $row[1] ?></h3>
										  <p>Submitted late but is accepted</p>
										</div>
									  </div>
									  <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
										<div class="tile-stats">
										  <div class="icon"><i class="fa fa-thumbs-o-down"></i></div>
										  <div class="count red"><?php echo number_format($not_accepted_num, 0) ?></div>
										  <h3><?php echo $row[1] ?></h3>
										  <p class="red">Submitted late and still not accepted</p>
										</div>
									  </div>
									</div>
									<br /><br />
									<?php
								}
							} elseif ($tab == 3) {
								?>
								<table class="table table-striped table-bordered jambo_table">
								  <thead>
									<tr>
									  <th width="10%"></th>
									  <th width="30%">Name of Report</th>
									  <th width="30%">Frequency</th>
									  <th width="30%">Submission</th>
									</tr>
								  </thead>
								  <tbody>
								  <?php
								  $sql = "SELECT rtid, rt_name, frequency, due_date FROM reports_types";
								  $res = query($sql); 
								  while ($row = fetch_array($res)) {
									?>
									<tr>
									  <td align="center">
										<a href="e_report_type.php?rtid=<?php echo $row[0] ?>" class="btn btn-warning btn-xs" data-toggle="tooltip" data-placement="top" title="Update Type of Report"><i class="fa fa-pencil"></i></a>
										<a href="d_report_type.php?rtid=<?php echo $row[0] ?>" class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top" title="Delete Type of Report"><i class="fa fa-eraser"></i></a>
									  </td>
									  <td align="left"><?php echo $row[1] ?></td>
									  <td align="left"><?php 
										if ($row[2] == 1) { echo 'Monthly';
										} elseif ($row[2] == 2) { echo 'Quarterly';
										} elseif ($row[2] == 3) { echo 'Semi-Annually';
										} elseif ($row[2] == 4) { echo 'Annually';
										} ?></td>
									  <td align="left"><?php echo $row[3] ?> days after 
										<?php
										if ($row[2] == 1) { echo ' the previous month';
										} elseif ($row[2] == 2) { echo ' the previous quarter';
										} elseif ($row[2] == 3) { echo ' the previous half of year';
										} elseif ($row[2] == 4) { echo ' the previous year';
										}
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
