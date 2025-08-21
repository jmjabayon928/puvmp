<?php

include("db.php");
include("sqli.php");
include("html.php");
define('PAGE', 'period_attendance_.php'); 

$tab = $_GET['tab'];

session_start();

if (isset($_SESSION['user_id'])) {
	if (validate_level3(PAGE) == true) {

		// log the activity
		log_user(5, 'attendance', 0);
		
		if (!isset ($_GET['spid'])) {
			$sql = "SELECT spid
					FROM salary_periods 
					WHERE TO_DAYS(sp_start) <= TO_DAYS(NOW()) AND TO_DAYS(NOW()) <= TO_DAYS(sp_end)";
			$spres = query($sql); 
			$sprow = fetch_array($spres); 
			$spid = $sprow[0]; 
			free_result($spres); 
		} else {
			$spid = $_GET['spid']; 
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

			<title>OTC - Employees' Attendance</title>

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
				?>

				<!-- page content -->
				<div class="right_col" role="main">
				  <div class="">
					<div class="page-title">
					  <div class="title_left">
						<h3>
						<?php
						if ($tab == 1) { echo 'Attendance by Employee';
						} elseif ($tab == 2) { echo 'Attendance by Date';
						}
						?>
						</h3>
					  </div>

					  <div class="title_right">
						<div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
						  <div class="input-group">
							<select class="form-control col-md-7 col-xs-12" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
								<option value="">Salary Period</option>
								<?php
								$sql = "SELECT spid, DATE_FORMAT(sp_start, '%b %e, %Y'), DATE_FORMAT(sp_end, '%b %e, %Y')
										FROM salary_periods 
										ORDER BY sp_date DESC";
								$rres = query($sql); 
								while ($rrow = fetch_array($rres)) {
									if ($spid == $rrow[0]) {
										?><option value="period_attendance_.php?tab=<?php echo $tab ?>&spid=<?php echo $rrow[0] ?>" Selected><?php echo $rrow[1].' - '.$rrow[2] ?></option><?php
									} else {
										?><option value="period_attendance_.php?tab=<?php echo $tab ?>&spid=<?php echo $rrow[0] ?>"><?php echo $rrow[1].' - '.$rrow[2] ?></option><?php
									}
								}
								?>
							</select>
						  </div>
						</div>
					  </div>
					</div>

					<div class="clearfix"></div>

					<div class="row">
					
						<?php
						
						if ($tab == 1) {
							// get the details of the salary_period
							$sql = "SELECT TO_DAYS(sp_start), TO_DAYS(sp_end), sp_start, sp_end
									FROM salary_periods
									WHERE spid = '$spid'";
							$sres = query($sql); 
							$srow = fetch_array($sres); 
							$int_start = $srow[0]; $int_end = $srow[1]; 
							
							free_result($sres); 
							
							?>
							<div class="" role="tabpanel" data-example-id="togglable-tabs">
								<ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
									<?php
									$sql = "SELECT did, dcode FROM departments";
									$dres = query($sql); 
									while ($drow = fetch_array($dres)) {
										if ($drow[0] == 1) {
											?><li role="presentation" class="active"><a href="#tab_content<?php echo $drow[0] ?>" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true"><?php echo $drow[1] ?></a></li><?php
										} else {
											?><li role="presentation" class=""><a href="#tab_content<?php echo $drow[0] ?>" id="profile-tab" role="tab" data-toggle="tab" aria-expanded="false"><?php echo $drow[1] ?></a></li><?php
										}
									}
									free_result($dres); 
									?>
							    </ul>
								<div id="myTabContent" class="tab-content">
									<?php
									$sql = "SELECT did, dcode FROM departments";
									$dres = query($sql); 
									while ($drow = fetch_array($dres)) {
										?>
										<div role="tabpanel" class="tab-pane fade active in" id="tab_content<?php echo $drow[0] ?>" aria-labelledby="home-tab">
											<?php
											$sql = "SELECT eid, CONCAT(lname,', ',fname,', ',mname)
													FROM employees
													WHERE did = '$drow[0]' AND active = 1
													ORDER BY lname, fname";
											$eres = query($sql); 
											while ($erow = fetch_array($eres)) {
												?>
												<div class="col-md-12 col-sm-12 col-xs-12">
													<div class="x_panel">
														<div class="x_title">
															<h2><?php echo $erow[1] ?></h2>
															<ul class="nav navbar-right panel_toolbox">
																<li><a href="add_attendance.php?eid=<?php echo $erow[0] ?>"><i class="fa fa-plus"></i></a></li>
																<li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
																<li><a class="close-link"><i class="fa fa-close"></i></a></li>
															</ul>
															<div class="clearfix"></div>
														</div>
														<div class="x_content">
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
																	for ($x=$int_start; $x<=$int_end; $x++) {
																		$sql = "SELECT DATE_FORMAT(FROM_DAYS('$x'), '%b %e, %Y'), DATE_FORMAT(FROM_DAYS('$x'), '%Y-%m-%d')"; 
																		$fres = query($sql); $frow = fetch_array($fres); 
																		
																		$sql = "SELECT aid, DATE_FORMAT(am_in, '%h:%i %p'), 
																					DATE_FORMAT(am_out, '%h:%i %p'), DATE_FORMAT(pm_in, '%h:%i %p'), 
																					DATE_FORMAT(pm_out, '%h:%i %p'), tardiness, undertime, status, remarks
																				FROM attendance 
																				WHERE TO_DAYS(adate) = '$x' AND eid = '$erow[0]'";
																		$ares = query($sql); 
																		$anum = num_rows($ares); 
																		
																		if ($anum > 0) {
																			$arow = fetch_array($ares); 
																			?>
																			<tr>
																				<td align="center">
																					<a href="attendance.php?aid=<?php echo $arow[0] ?>" class="btn btn-info btn-xs"><i class="fa fa-search"></i></a>
																					<a href="e_attendance.php?aid=<?php echo $arow[0] ?>" class="btn btn-warning btn-xs"><i class="fa fa-pencil"></i></a>
																					<a href="d_attendance.php?aid=<?php echo $arow[0] ?>" class="btn btn-danger btn-xs"><i class="fa fa-eraser"></i></a>
																				</td>
																				<td align="center"><?php echo $frow[0] ?></td>
																				<td align="center"><?PHP echo $arow[1] == '12:00 AM' ? '' : $arow[1] ?></td>
																				<td align="center"><?PHP echo $arow[2] == '12:00 AM' ? '' : $arow[2] ?></td>
																				<td align="center"><?PHP echo $arow[3] == '12:00 AM' ? '' : $arow[3] ?></td>
																				<td align="center"><?PHP echo $arow[4] == '12:00 AM' ? '' : $arow[4] ?></td>
																				<td align="center"><?PHP echo $arow[5] ?></td>
																				<td align="center"><?PHP echo $arow[6] ?></td>
																				<td align="center"><?PHP 
																					if ($arow[7] == -1) { echo 'For Checking';
																					} elseif ($arow[7] == 1) { echo 'Present';
																					} elseif ($arow[7] == 2) { echo 'Unauthorized Absent';
																					} elseif ($arow[7] == 3) { echo 'Vacation Leave';
																					} elseif ($arow[7] == 4) { echo 'Sick Leave';
																					} elseif ($arow[7] == 5) { echo 'Forced Leave';
																					} elseif ($arow[7] == 6) { echo 'Special Leave';
																					} elseif ($arow[7] == 7) { echo 'Compensatory Time Off';
																					} elseif ($arow[7] == 8) { echo 'Maternity Leave';
																					} elseif ($arow[7] == 9) { echo 'Paternity Leave';
																					} elseif ($arow[7] == 10) { echo 'UML';
																					} elseif ($arow[7] == 11) { echo 'USPL';
																					} elseif ($arow[7] == 12) { echo 'Travel Order';
																					} elseif ($arow[7] == 13) { echo 'DMS';
																					} elseif ($arow[7] == 14) { echo 'Memo Order';
																					} ?></td>
																				<td align="left"><?PHP echo $arow[8] ?></td>
																			</tr>
																			<?php
																		} else {
																			// check if there are VLs, SLs, FL, SPL, CTO, UML, USPL
																			?>
																			<tr>
																				<td align="center"></td>
																				<td align="center"><?php echo $frow[0] ?></td>
																				<td align="center"></td>
																				<td align="center"></td>
																				<td align="center"></td>
																				<td align="center"></td>
																				<td align="center"></td>
																				<td align="center"></td>
																				<td align="center"></td>
																				<td align="center"></td>
																			</tr>
																			<?php
																		}
																		free_result($ares); 
																		free_result($fres); 
																	}
																	?>
																</tbody>
															</table>
														</div>
													</div>
												</div>
												<?php
											}
											free_result($eres); 
											?>
										</div>
										<?php
									}
									free_result($dres); 
									?>
								</div>
							</div>
							<?php
						} elseif ($tab == 2) {
							// get the details of the salary_period
							$sql = "SELECT TO_DAYS(sp_start), TO_DAYS(sp_end), sp_start, sp_end
									FROM salary_periods
									WHERE spid = '$spid'";
							$sres = query($sql); 
							$srow = fetch_array($sres); 
							$int_start = $srow[0]; $int_end = $srow[1]; 
							
							free_result($sres); 
							
							for ($x=$int_start; $x<=$int_end; $x++) {
								$sql = "SELECT DATE_FORMAT(FROM_DAYS('$x'), '%b %e, %Y - %W')"; 
								$fres = query($sql); $frow = fetch_array($fres); 
								
								?>
								<div class="col-md-12 col-sm-12 col-xs-12">
									<div class="x_panel">
										<div class="x_title">
											<h2><?php echo $frow[0] ?></h2>
											<ul class="nav navbar-right panel_toolbox">
												<li><a href="add_attendance.php"><i class="fa fa-plus"></i></a></li>
												<li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
												<li><a class="close-link"><i class="fa fa-close"></i></a></li>
											</ul>
											<div class="clearfix"></div>
										</div>
										<div class="x_content">
											<table class="table table-striped table-bordered jambo_table bulk_action">
												<thead>
												<tr class="headings">
													<th rowspan="2" align="center" width="10%"></th>
													<th rowspan="2" align="center" width="16%">Employee</th>
													<th colspan="2" align="center">A.M.</th>
													<th colspan="2" align="center">P.M.</th>
													<th rowspan="2" align="center" width="08%">Tardiness</th>
													<th rowspan="2" align="center" width="08%">Undertime</th>
													<th rowspan="2" align="center" width="08%">Status</th>
													<th rowspan="2" align="center" width="18%">Remarks</th>
												</tr>
												<tr class="headings">
													<th align="center" width="08%">In</th>
													<th align="center" width="08%">Out</th>
													<th align="center" width="08%">In</th>
													<th align="center" width="08%">Out</th>
												</tr>
												</thead>
												<tbody>
												<?php
												$sql = "SELECT a.aid, a.eid, CONCAT(e.lname,', ',e.fname), DATE_FORMAT(a.am_in, '%h:%i %p'), 
															DATE_FORMAT(a.am_out, '%h:%i %p'), DATE_FORMAT(a.pm_in, '%h:%i %p'), 
															DATE_FORMAT(a.pm_out, '%h:%i %p'), a.tardiness, a.undertime, a.status, a.remarks
														FROM attendance a INNER JOIN employees e ON a.eid = e.eid
														WHERE TO_DAYS(a.adate) = '$x' 
														ORDER BY e.lname, e.fname";
												$res = query($sql); 
												while ($row = fetch_array($res)) {
													?>
													<tr>
														<td align="center">
															<a href="attendance.php?aid=<?php echo $row[0] ?>" class="btn btn-info btn-xs"><i class="fa fa-search"></i></a>
															<a href="e_attendance.php?aid=<?php echo $row[0] ?>" class="btn btn-warning btn-xs"><i class="fa fa-pencil"></i></a>
															<a href="d_attendance.php?aid=<?php echo $row[0] ?>" class="btn btn-danger btn-xs"><i class="fa fa-eraser"></i></a>
														</td>
														<td align="left"><?PHP echo $row[2] ?></td>
														<td align="center"><?PHP echo $row[3] == '12:00 AM' ? '' : $row[3] ?></td>
														<td align="center"><?PHP echo $row[4] == '12:00 AM' ? '' : $row[4] ?></td>
														<td align="center"><?PHP echo $row[5] == '12:00 AM' ? '' : $row[5] ?></td>
														<td align="center"><?PHP echo $row[6] == '12:00 AM' ? '' : $row[6] ?></td>
														<td align="center"><?PHP echo $row[7] ?></td>
														<td align="center"><?PHP echo $row[8] ?></td>
														<td align="center"><?PHP 
															if ($row[9] == -1) { echo 'For Checking';
															} elseif ($row[9] == 1) { echo 'Present';
															} elseif ($row[9] == 2) { echo 'Unauthorized Absent';
															} elseif ($row[9] == 3) { echo 'Vacation Leave';
															} elseif ($row[9] == 4) { echo 'Sick Leave';
															} elseif ($row[9] == 5) { echo 'Forced Leave';
															} elseif ($row[9] == 6) { echo 'Special Leave';
															} elseif ($row[9] == 7) { echo 'CTO';
															} elseif ($row[9] == 8) { echo 'Maternity Leave';
															} elseif ($row[9] == 9) { echo 'Paternity Leave';
															} elseif ($row[9] == 10) { echo 'UML';
															} elseif ($row[9] == 11) { echo 'USPL';
															} elseif ($row[9] == 12) { echo 'Travel Order';
															} elseif ($row[9] == 13) { echo 'DMS';
															} ?></td>
														<td align="left"><?PHP echo $row[10] ?></td>
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
								<?php
								free_result($fres); 
							}
							
						}
					
						?>

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
			<script src="vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js"></script>
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
	} else {

		// check if user is a valid employee
		$sql = "SELECT eid FROM users WHERE user_id = '$_SESSION[user_id]' AND active = 1";
		$vres = query($sql); $vrow = fetch_array($vres); $eid = $vrow[0]; 
		
		if ($eid != 0) {
			// log the activity
			log_user(5, 'attendance', 0);
			
			if (!isset ($_GET['spid'])) {
				$sql = "SELECT spid
						FROM salary_periods 
						WHERE TO_DAYS(sp_start) <= TO_DAYS(NOW()) AND TO_DAYS(NOW()) <= TO_DAYS(sp_end)";
				$spres = query($sql); 
				$sprow = fetch_array($spres); 
				$spid = $sprow[0]; 
				free_result($spres); 
			} else {
				$spid = $_GET['spid']; 
			}
			
			$sql = "SELECT CONCAT(lname,', ', fname) FROM employees WHERE eid = '$eid'";
			$eres = query($sql); $erow = fetch_array($eres); $ename = $erow[0]; 
			
			?>
			<!DOCTYPE html>
			<html lang="en">
			  <head>
				<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
				<!-- Meta, title, CSS, favicons, etc. -->
				<meta charset="utf-8">
				<meta http-equiv="X-UA-Compatible" content="IE=edge">
				<meta name="viewport" content="width=device-width, initial-scale=1">

				<title>OTC - Employee's Own Attendance</title>

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
					?>

					<!-- page content -->
					<div class="right_col" role="main">
					  <div class="">
						<div class="page-title">
						  <div class="title_left">
							<h3><?php echo $ename ?>'s Attendance</h3>
						  </div>

						  <div class="title_right">
							<div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
							  <div class="input-group">
								<select class="form-control col-md-7 col-xs-12" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
									<option value="">Salary Period</option>
									<?php
									$sql = "SELECT spid, DATE_FORMAT(sp_start, '%b %e, %Y'), DATE_FORMAT(sp_end, '%b %e, %Y')
											FROM salary_periods 
											ORDER BY sp_date DESC";
									$rres = query($sql); 
									while ($rrow = fetch_array($rres)) {
										if ($spid == $rrow[0]) {
											?><option value="period_attendance_.php?tab=<?php echo $tab ?>&spid=<?php echo $rrow[0] ?>" Selected><?php echo $rrow[1].' - '.$rrow[2] ?></option><?php
										} else {
											?><option value="period_attendance_.php?tab=<?php echo $tab ?>&spid=<?php echo $rrow[0] ?>"><?php echo $rrow[1].' - '.$rrow[2] ?></option><?php
										}
									}
									?>
								</select>
							  </div>
							</div>
						  </div>
						</div>

						<div class="clearfix"></div>

						<div class="row">
						
							<?php
							
							// get the details of the salary_period
							$sql = "SELECT TO_DAYS(sp_start), TO_DAYS(sp_end), sp_start, sp_end
									FROM salary_periods
									WHERE spid = '$spid'";
							$sres = query($sql); 
							$srow = fetch_array($sres); 
							$int_start = $srow[0]; $int_end = $srow[1]; 
							
							free_result($sres); 
							
							?>
							<div class="col-md-12 col-sm-12 col-xs-12">
								<div class="x_panel">
									<div class="x_title">
										<h2><?php echo $ename ?></h2>
										<div class="clearfix"></div>
									</div>
									<div class="x_content">
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
												for ($x=$int_start; $x<=$int_end; $x++) {
													$sql = "SELECT DATE_FORMAT(FROM_DAYS('$x'), '%b %e, %Y'), DATE_FORMAT(FROM_DAYS('$x'), '%Y-%m-%d')"; 
													$fres = query($sql); $frow = fetch_array($fres); 
													
													$sql = "SELECT aid, DATE_FORMAT(am_in, '%h:%i %p'), 
																DATE_FORMAT(am_out, '%h:%i %p'), DATE_FORMAT(pm_in, '%h:%i %p'), 
																DATE_FORMAT(pm_out, '%h:%i %p'), tardiness, undertime, status, remarks
															FROM attendance 
															WHERE TO_DAYS(adate) = '$x' AND eid = '$eid'";
													$ares = query($sql); 
													$anum = num_rows($ares); 
													
													if ($anum > 0) {
														$arow = fetch_array($ares); 
														?>
														<tr>
															<td align="center">
																<a href="attendance.php?aid=<?php echo $arow[0] ?>" class="btn btn-info btn-xs"><i class="fa fa-search"></i></a>
																<a href="e_attendance.php?aid=<?php echo $arow[0] ?>" class="btn btn-warning btn-xs"><i class="fa fa-pencil"></i></a>
																<a href="d_attendance.php?aid=<?php echo $arow[0] ?>" class="btn btn-danger btn-xs"><i class="fa fa-eraser"></i></a>
															</td>
															<td align="center"><?php echo $frow[0] ?></td>
															<td align="center"><?PHP echo $arow[1] == '12:00 AM' ? '' : $arow[1] ?></td>
															<td align="center"><?PHP echo $arow[2] == '12:00 AM' ? '' : $arow[2] ?></td>
															<td align="center"><?PHP echo $arow[3] == '12:00 AM' ? '' : $arow[3] ?></td>
															<td align="center"><?PHP echo $arow[4] == '12:00 AM' ? '' : $arow[4] ?></td>
															<td align="center"><?PHP echo $arow[5] ?></td>
															<td align="center"><?PHP echo $arow[6] ?></td>
															<td align="center"><?PHP 
																if ($arow[7] == -1) { echo 'For Checking';
																} elseif ($arow[7] == 1) { echo 'Present';
																} elseif ($arow[7] == 2) { echo 'Unauthorized Absent';
																} elseif ($arow[7] == 3) { echo 'Vacation Leave';
																} elseif ($arow[7] == 4) { echo 'Sick Leave';
																} elseif ($arow[7] == 5) { echo 'Forced Leave';
																} elseif ($arow[7] == 6) { echo 'Special Leave';
																} elseif ($arow[7] == 7) { echo 'Compensatory Time Off';
																} elseif ($arow[7] == 8) { echo 'Maternity Leave';
																} elseif ($arow[7] == 9) { echo 'Paternity Leave';
																} elseif ($arow[7] == 10) { echo 'UML';
																} elseif ($arow[7] == 11) { echo 'USPL';
																} elseif ($arow[7] == 12) { echo 'Travel Order';
																} elseif ($arow[7] == 13) { echo 'DMS';
																} elseif ($arow[7] == 14) { echo 'Memo Order';
																} ?></td>
															<td align="left"><?PHP echo $arow[8] ?></td>
														</tr>
														<?php
													} else {
														// check if there are VLs, SLs, FL, SPL, CTO, UML, USPL
														?>
														<tr>
															<td align="center"></td>
															<td align="center"><?php echo $frow[0] ?></td>
															<td align="center"></td>
															<td align="center"></td>
															<td align="center"></td>
															<td align="center"></td>
															<td align="center"></td>
															<td align="center"></td>
															<td align="center"></td>
															<td align="center"></td>
														</tr>
														<?php
													}
													free_result($ares); 
													free_result($fres); 
												}
												?>
											</tbody>
										</table>
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
				<script src="vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js"></script>
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
		} else {
			// log the activity
			log_user(5, 'attendance', 0);
			
			if (!isset ($_GET['spid'])) {
				$sql = "SELECT spid
						FROM salary_periods 
						WHERE TO_DAYS(sp_start) <= TO_DAYS(NOW()) AND TO_DAYS(NOW()) <= TO_DAYS(sp_end)";
				$spres = query($sql); 
				$sprow = fetch_array($spres); 
				$spid = $sprow[0]; 
				free_result($spres); 
			} else {
				$spid = $_GET['spid']; 
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

				<title>OTC - Employee's Own Attendance</title>

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
					?>

					<!-- page content -->
					<div class="right_col" role="main">
						<div class="">
							<div class="row">
							
							  <div class="col-md-12 col-sm-12 col-xs-12">
								<div class="x_panel">
									<div class="x_content">
									  <div class="bs-example" data-example-id="simple-jumbotron">
											<blockquote class="blockquote">
											  <p class="h1">Invalid User-right!</p>
											  <footer class="blockquote-footer"><em>Ask your system administrator for access to this page.</em></footer>
											</blockquote>
									  </div>
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


			  </body>
			</html>
			<?php
		}
		
	}
} else {
	header('Location: login.php');	
}
