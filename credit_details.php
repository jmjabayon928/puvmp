<?php

include("db.php");
include("sqli.php");
include("html.php");
define('PAGE', 'attendance.php'); 

session_start();

if (isset($_SESSION['user_id'])) {
	if (validate_level(PAGE) == true) {

		$eid = $_GET['eid'];
		$type = $_GET['type'];
		$gmonth = $_GET['gmonth'];
		$gyear = $_GET['gyear'];

		?>
		<!DOCTYPE html>
		<html lang="en">
		  <head>
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
			<!-- Meta, title, CSS, favicons, etc. -->
			<meta charset="utf-8">
			<meta http-equiv="X-UA-Compatible" content="IE=edge">
			<meta name="viewport" content="width=device-width, initial-scale=1">
			  
			<title>OTC | View Credit Details</title>

			<!-- Bootstrap -->
			<link href="vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
			<!-- Font Awesome -->
			<link href="vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
			<!-- Select2 -->
			<link href="vendors/select2/dist/css/select2.min.css" rel="stylesheet">

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
				
				// get the name of employee
				$sql = "SELECT CONCAT(lname,', ',fname), TIME_TO_SEC(time_in), TIME_TO_SEC(time_out) FROM employees WHERE eid = '$eid'";
				$eres = query($sql); $erow = fetch_array($eres); 
				$employee = $erow[0]; $time_in = $erow[1]; $time_out = $erow[2]; 
				free_result($eres); 
				
				if ($gmonth == 1) { $monthname = 'January';
				} elseif ($gmonth == 2) { $monthname = 'February';
				} elseif ($gmonth == 3) { $monthname = 'March';
				} elseif ($gmonth == 4) { $monthname = 'April';
				} elseif ($gmonth == 5) { $monthname = 'May';
				} elseif ($gmonth == 6) { $monthname = 'June';
				} elseif ($gmonth == 7) { $monthname = 'July';
				} elseif ($gmonth == 8) { $monthname = 'August';
				} elseif ($gmonth == 9) { $monthname = 'September';
				} elseif ($gmonth == 10) { $monthname = 'October';
				} elseif ($gmonth == 11) { $monthname = 'November';
				} elseif ($gmonth == 12) { $monthname = 'December';
				}
				
				?>

				<!-- page content -->
				<div class="right_col" role="main">
				  <div class="">
					<div class="page-title">
					  <div class="title_left">
						<h3>Credit Details for <?php echo $employee.' for '.$monthname.', '.$gyear ?></h3>
					  </div>
					</div>
					<div class="clearfix"></div>
					<div class="row">
					
					  <div class="col-md-12 col-sm-12 col-xs-12">
						<div class="x_panel">
						  <div class="x_content">
						  
						    <?php 
							if ($type == 1) {
								?>
								<table class="table table-striped table-bordered jambo_table bulk_action">
									<thead>
										<tr class="headings">
											<th rowspan="2" align="center" width="10%"></th>
											<th rowspan="2" align="center" width="08%"></th>
											<th colspan="2" align="center">A.M.</th>
											<th colspan="2" align="center">P.M.</th>
											<th rowspan="2" align="center" width="09%">Tardiness</th>
											<th rowspan="2" align="center" width="09%">Undertime</th>
											<th rowspan="2" align="center" width="09%">Status</th>
											<th rowspan="2" align="center" width="19%">Remarks</th>
										</tr>
										<tr class="headings">
											<th align="center" width="09%">In</th>
											<th align="center" width="09%">Out</th>
											<th align="center" width="09%">In</th>
											<th align="center" width="09%">Out</th>
										</tr>
									</thead>
									<tbody>
										<?php
										$sql = "SELECT aid, DATE_FORMAT(adate, '%b %e, %Y'), DATE_FORMAT(am_in, '%h:%i %p'), 
													DATE_FORMAT(am_out, '%h:%i %p'), DATE_FORMAT(pm_in, '%h:%i %p'), 
													DATE_FORMAT(pm_out, '%h:%i %p'), tardiness, undertime, status, remarks
												FROM attendance 
												WHERE eid = '$eid' AND tardiness > 0 
													AND YEAR(adate) = '$gyear' AND MONTH(adate) = '$gmonth'
												ORDER BY adate";
										$res = query($sql); 
										
										while ($row = fetch_array($res)) {
											?>
											<tr>
												<td align="center"></td>
												<td align="center"><?php echo $row[1] ?></td>
												<td align="center"><?PHP echo $row[2] == '12:00 AM' ? '' : $row[2] ?></td>
												<td align="center"><?PHP echo $row[3] == '12:00 AM' ? '' : $row[3] ?></td>
												<td align="center"><?PHP echo $row[4] == '12:00 AM' ? '' : $row[4] ?></td>
												<td align="center"><?PHP echo $row[5] == '12:00 AM' ? '' : $row[5] ?></td>
												<td align="center"><?PHP echo $row[6] ?></td>
												<td align="center"><?PHP echo $row[7] ?></td>
												<td align="center"><?PHP 
													if ($row[8] == -1) { echo 'For Checking';
													} elseif ($row[8] == 1) { echo 'Present';
													} elseif ($row[8] == 2) { echo 'Unauthorized Absent';
													} elseif ($row[8] == 3) { echo 'Vacation Leave';
													} elseif ($row[8] == 4) { echo 'Sick Leave';
													} elseif ($row[8] == 5) { echo 'Forced Leave';
													} elseif ($row[8] == 6) { echo 'Special Leave';
													} elseif ($row[8] == 7) { echo 'Compensatory Time Off';
													} elseif ($row[8] == 8) { echo 'Maternity Leave';
													} elseif ($row[8] == 9) { echo 'Paternity Leave';
													} elseif ($row[8] == 10) { echo 'UML';
													} elseif ($row[8] == 11) { echo 'USPL';
													} elseif ($row[8] == 12) { echo 'Travel Order';
													} elseif ($row[8] == 13) { echo 'DMS';
													} ?></td>
												<td align="left"><?PHP echo $row[9] ?></td>
											</tr>
											<?php
										}
										free_result($res); 
										?>
									</tbody>
								</table>
								<?php
							} elseif ($type == 2) {
								?>
								<table class="table table-striped table-bordered jambo_table bulk_action">
									<thead>
										<tr class="headings">
											<th rowspan="2" align="center" width="10%"></th>
											<th rowspan="2" align="center" width="08%"></th>
											<th colspan="2" align="center">A.M.</th>
											<th colspan="2" align="center">P.M.</th>
											<th rowspan="2" align="center" width="09%">Tardiness</th>
											<th rowspan="2" align="center" width="09%">Undertime</th>
											<th rowspan="2" align="center" width="09%">Status</th>
											<th rowspan="2" align="center" width="19%">Remarks</th>
										</tr>
										<tr class="headings">
											<th align="center" width="09%">In</th>
											<th align="center" width="09%">Out</th>
											<th align="center" width="09%">In</th>
											<th align="center" width="09%">Out</th>
										</tr>
									</thead>
									<tbody>
										<?php
										$sql = "SELECT aid, DATE_FORMAT(adate, '%b %e, %Y'), DATE_FORMAT(am_in, '%h:%i %p'), 
													DATE_FORMAT(am_out, '%h:%i %p'), DATE_FORMAT(pm_in, '%h:%i %p'), 
													DATE_FORMAT(pm_out, '%h:%i %p'), tardiness, undertime, status, remarks
												FROM attendance 
												WHERE eid = '$eid' AND undertime > 0 
													AND YEAR(adate) = '$gyear' AND MONTH(adate) = '$gmonth'
												ORDER BY adate";
										$res = query($sql); 
										
										while ($row = fetch_array($res)) {
											?>
											<tr>
												<td align="center"></td>
												<td align="center"><?php echo $row[1] ?></td>
												<td align="center"><?PHP echo $row[2] == '12:00 AM' ? '' : $row[2] ?></td>
												<td align="center"><?PHP echo $row[3] == '12:00 AM' ? '' : $row[3] ?></td>
												<td align="center"><?PHP echo $row[4] == '12:00 AM' ? '' : $row[4] ?></td>
												<td align="center"><?PHP echo $row[5] == '12:00 AM' ? '' : $row[5] ?></td>
												<td align="center"><?PHP echo $row[6] ?></td>
												<td align="center"><?PHP echo $row[7] ?></td>
												<td align="center"><?PHP 
													if ($row[8] == -1) { echo 'For Checking';
													} elseif ($row[8] == 1) { echo 'Present';
													} elseif ($row[8] == 2) { echo 'Unauthorized Absent';
													} elseif ($row[8] == 3) { echo 'Vacation Leave';
													} elseif ($row[8] == 4) { echo 'Sick Leave';
													} elseif ($row[8] == 5) { echo 'Forced Leave';
													} elseif ($row[8] == 6) { echo 'Special Leave';
													} elseif ($row[8] == 7) { echo 'Compensatory Time Off';
													} elseif ($row[8] == 8) { echo 'Maternity Leave';
													} elseif ($row[8] == 9) { echo 'Paternity Leave';
													} elseif ($row[8] == 10) { echo 'UML';
													} elseif ($row[8] == 11) { echo 'USPL';
													} elseif ($row[8] == 12) { echo 'Travel Order';
													} elseif ($row[8] == 13) { echo 'DMS';
													} ?></td>
												<td align="left"><?PHP echo $row[9] ?></td>
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
			<!-- Select2 -->
			<script src="vendors/select2/dist/js/select2.full.min.js"></script>
			<!-- Autosize -->
			<script src="vendors/autosize/dist/autosize.min.js"></script>
			<!-- Custom Theme Scripts -->
			<script src="build/js/custom.min.js"></script>
			
		  </body>
		</html>
		<?PHP
	}
} else {
	header('Location: login.php');	
}

