<?php

include("db.php");
include("sqli.php");
include("html.php");
define('PAGE', 'tc.php'); 

$cid = $_GET['cid'];

session_start();

if (isset($_SESSION['user_id'])) {
	if (validate_level(PAGE) == true) {

		// log the activity
		log_user(5, 'cooperatives', $cid);
		
		?>
		<!DOCTYPE html>
		<html lang="en">
		  <head>
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
			<!-- Meta, title, CSS, favicons, etc. -->
			<meta charset="utf-8">
			<meta http-equiv="X-UA-Compatible" content="IE=edge">
			<meta name="viewport" content="width=device-width, initial-scale=1">
			  
			<title>OTC | Transport Cooperative Details</title>

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
				
				if (isset ($_GET['tab'])) {
					$tab = $_GET['tab'];
				} else {
					$tab = 1;
				}
				
				?>

				<!-- page content -->
				<div class="right_col" role="main">
				  <div class="">
					<div class="page-title">
					  <div class="title_left">
						<h3>Transportation Cooperative Details</h3>
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

					<div class="row">
					  <?php
					  if (isset($_GET['added_business'])) {
						  if ($_GET['added_business'] == 1) {
							  ?>
								<div class="alert alert-success">
								  <strong>Success!</strong> TC Business has been added into the database.
								</div>
							  <?php
						  }
					  }
					  if (isset($_GET['added_franchise'])) {
						  if ($_GET['added_franchise'] == 1) {
							  ?>
								<div class="alert alert-success">
								  <strong>Success!</strong> Franchise has been added into the database.
								</div>
							  <?php
						  }
					  }
					  if (isset($_GET['added_units'])) {
						  if ($_GET['added_units'] == 1) {
							  ?>
								<div class="alert alert-success">
								  <strong>Success!</strong> TC vehicle units have been added into the database.
								</div>
							  <?php
						  }
					  }
					  if (isset($_GET['added_loan'])) {
						  if ($_GET['added_loan'] == 1) {
							  ?>
								<div class="alert alert-success">
								  <strong>Success!</strong> TC Loan has been added into the database.
								</div>
							  <?php
						  }
					  }
					  if (isset($_GET['added_capitalization'])) {
						  if ($_GET['added_capitalization'] == 1) {
							  ?>
								<div class="alert alert-success">
								  <strong>Success!</strong> TC Capitalization have been added into the database.
								</div>
							  <?php
						  }
					  }
					  if (isset($_GET['added_surplus'])) {
						  if ($_GET['added_surplus'] == 1) {
							  ?>
								<div class="alert alert-success">
								  <strong>Success!</strong> Net of Surplus have been added into the database.
								</div>
							  <?php
						  }
					  }
					  if (isset($_GET['added_financials'])) {
						  if ($_GET['added_financials'] == 1) {
							  ?>
								<div class="alert alert-success">
								  <strong>Success!</strong> Financial Reports have been added into the database.
								</div>
							  <?php
						  }
					  }
					  if (isset($_GET['added_membership'])) {
						  if ($_GET['added_membership'] == 1) {
							  ?>
								<div class="alert alert-success">
								  <strong>Success!</strong> Membership has been added into the database.
								</div>
							  <?php
						  }
					  }
					  if (isset($_GET['added_member'])) {
						  if ($_GET['added_member'] == 1) {
							  ?>
								<div class="alert alert-success">
								  <strong>Success!</strong> TC member has been added into the database.
								</div>
							  <?php
						  }
					  }
					  if (isset($_GET['added_governance'])) {
						  if ($_GET['added_governance'] == 1) {
							  ?>
								<div class="alert alert-success">
								  <strong>Success!</strong> General Assembly Meeting has been added into the database.
								</div>
							  <?php
						  }
					  }
					  if (isset($_GET['updated_member'])) {
						  if ($_GET['updated_member'] == 1) {
							  ?>
								<div class="alert alert-success">
								  <strong>Success!</strong> TC member information has been updated.
								</div>
							  <?php
						  }
					  }
					  if (isset($_GET['updated_franchise'])) {
						  if ($_GET['updated_franchise'] == 1) {
							  ?>
								<div class="alert alert-success">
								  <strong>Success!</strong> Franchise has been updated.
								</div>
							  <?php
						  }
					  }
					  if (isset($_GET['updated_cgs'])) {
						  if ($_GET['updated_cgs'] == 1) {
							  ?>
								<div class="alert alert-success">
								  <strong>Success!</strong> TC CGS has been updated.
								</div>
							  <?php
						  }
					  }
					  if (isset($_GET['updated_units'])) {
						  if ($_GET['updated_units'] == 1) {
							  ?>
								<div class="alert alert-success">
								  <strong>Success!</strong> TC vehicle units have been updated.
								</div>
							  <?php
						  }
					  }
					  if (isset($_GET['updated_business'])) {
						  if ($_GET['updated_business'] == 1) {
							  ?>
								<div class="alert alert-success">
								  <strong>Success!</strong> TC business has been updated.
								</div>
							  <?php
						  }
					  }
					  if (isset($_GET['updated_loan'])) {
						  if ($_GET['updated_loan'] == 1) {
							  ?>
								<div class="alert alert-success">
								  <strong>Success!</strong> TC loan has been updated.
								</div>
							  <?php
						  }
					  }
					  if (isset($_GET['updated_capitalization'])) {
						  if ($_GET['updated_capitalization'] == 1) {
							  ?>
								<div class="alert alert-success">
								  <strong>Success!</strong> TC Capitalization has been updated.
								</div>
							  <?php
						  }
					  }
					  if (isset($_GET['updated_financials'])) {
						  if ($_GET['updated_financials'] == 1) {
							  ?>
								<div class="alert alert-success">
								  <strong>Success!</strong> Financial Reports have been updated.
								</div>
							  <?php
						  }
					  }
					  if (isset($_GET['updated_governance'])) {
						  if ($_GET['updated_governance'] == 1) {
							  ?>
								<div class="alert alert-success">
								  <strong>Success!</strong> General Assembly Meeting has been updated.
								</div>
							  <?php
						  }
					  }
					  if (isset($_GET['updated_surplus'])) {
						  if ($_GET['updated_surplus'] == 1) {
							  ?>
								<div class="alert alert-success">
								  <strong>Success!</strong> Net Surplus has been updated.
								</div>
							  <?php
						  }
					  }
					  if (isset($_GET['deleted_governance'])) {
						  if ($_GET['deleted_governance'] == 1) {
							  ?>
								<div class="alert alert-success">
								  <strong>Success!</strong> General Assembly Meeting has been deleted from the database.
								</div>
							  <?php
						  }
					  }
					  if (isset($_GET['deleted_units'])) {
						  if ($_GET['deleted_units'] == 1) {
							  ?>
								<div class="alert alert-success">
								  <strong>Success!</strong> TC vehicle units have been deleted from the database.
								</div>
							  <?php
						  }
					  }
					  if (isset($_GET['deleted_cgs'])) {
						  if ($_GET['deleted_cgs'] == 1) {
							  ?>
								<div class="alert alert-success">
								  <strong>Success!</strong> CGS has been deleted from the database.
								</div>
							  <?php
						  }
					  }
					  if (isset($_GET['deleted_franchise'])) {
						  if ($_GET['deleted_franchise'] == 1) {
							  ?>
								<div class="alert alert-success">
								  <strong>Success!</strong> Franchise has been deleted from the database.
								</div>
							  <?php
						  }
					  }
					  if (isset($_GET['deleted_loan'])) {
						  if ($_GET['deleted_loan'] == 1) {
							  ?>
								<div class="alert alert-success">
								  <strong>Success!</strong> TC loan has been deleted from the database.
								</div>
							  <?php
						  }
					  }
					  if (isset($_GET['deleted_business'])) {
						  if ($_GET['deleted_business'] == 1) {
							  ?>
								<div class="alert alert-success">
								  <strong>Success!</strong> TC business has been deleted from the database.
								</div>
							  <?php
						  }
					  }
					  if (isset($_GET['deleted_capitalization'])) {
						  if ($_GET['deleted_capitalization'] == 1) {
							  ?>
								<div class="alert alert-success">
								  <strong>Success!</strong> TC Capitalization has been deleted from the database.
								</div>
							  <?php
						  }
					  }
					  if (isset($_GET['deleted_membership'])) {
						  if ($_GET['deleted_membership'] == 1) {
							  ?>
								<div class="alert alert-success">
								  <strong>Success!</strong> Transport Cooperative membership has been deleted from the database.
								</div>
							  <?php
						  }
					  }
					  if (isset($_GET['deleted_financials'])) {
						  if ($_GET['deleted_financials'] == 1) {
							  ?>
								<div class="alert alert-success">
								  <strong>Success!</strong> Financial Reports have been deleted from the database.
								</div>
							  <?php
						  }
					  }
					  if (isset($_GET['deleted_surplus'])) {
						  if ($_GET['deleted_surplus'] == 1) {
							  ?>
								<div class="alert alert-success">
								  <strong>Success!</strong> Net Surplus have been deleted from the database.
								</div>
							  <?php
						  }
					  }
					  
					  $sql = "SELECT c.cname, c.addr, s.sname, c.email, 
								  c.chairman, c.chairman_num, c.contact_num, 
								  c.last_update_id, DATE_FORMAT(c.last_update, '%m/%d/%Y %h:%i %p'),
								  c.last_verify_id, DATE_FORMAT(c.last_verify, '%m/%d/%Y %h:%i %p'), 
								  c.new_otc_num, DATE_FORMAT(c.new_otc_date, '%m/%d/%Y'),
								  c.new_cda_num, DATE_FORMAT(c.new_cda_date, '%m/%d/%Y'), 
								  DATE_FORMAT(c.pa_expiry, '%m/%d/%y'), c.ctype, c.fed_id
							  FROM cooperatives c INNER JOIN tc_sectors s ON c.sid = s.sid
							  WHERE c.cid = '$cid'";
					  $res = query($sql); 
					  $row = fetch_array($res); 
					  ?>
					  <div class="col-md-12 col-sm-12 col-xs-12">
						<div class="x_panel">
						  <div class="x_title"><h2><?php echo $row[0] ?></h2>
							<ul class="nav navbar-right panel_toolbox">
							</ul>
							<div class="clearfix"></div>
						  </div>
						  <div class="x_content">
						  
							<div class="" role="tabpanel" data-example-id="togglable-tabs">
							  <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
							    <?php 
								
								for ($i=1; $i<9; $i++) {
									if ($i == 1) { $header = 'Basic Information';
									} elseif ($i == 2) { $header = 'Members';
									} elseif ($i == 3) { $header = 'Governance';
									} elseif ($i == 4) { $header = 'Units';
									} elseif ($i == 5) { $header = 'Transport Operations';
									} elseif ($i == 6) { $header = 'Financial & Business';
									} elseif ($i == 7) { $header = 'Capacity Building Program';
									} elseif ($i == 8) { $header = 'Users\' Logs';
									}
									?><li role="presentation" class="<?php echo $i == $tab ? 'active' : '' ?>"><a href="tc.php?cid=<?php echo $cid ?>&tab=<?php echo $i ?>"><?php echo $header ?></a></li><?php
								}
								?>
							  </ul>
							  <div class="tab-content">
								<?php
								if ($tab == 1) {
									?>
									<ul class="nav navbar-right panel_toolbox">
										<a href="verify_tc.php?cid=<?php echo $cid ?>" class="btn btn-success btn-md"><i class="fa fa-check"></i> Verify </a>
										<a href="e_tc.php?cid=<?php echo $cid ?>" class="btn btn-warning btn-md"><i class="fa fa-pencil"></i> Edit </a>
									</ul>
									<table class="table table-striped table-bordered jambo_table">
										<thead>
										  <tr class="headings">
											  <th colspan="4">Transportation Cooperative Details</th>
										  </tr>
										</thead>
										<tbody>
											<tr>
												<td width="15%">Name</td>
												<td width="30%"><b><?php echo $row[0] ?></b></td>
												<td width="15%">Address</td>
												<td width="40%"><b><?php echo $row[1] ?></b></td>
											</tr>
											<tr>
												<td>Area</td>
												<td><b><?php echo $row[2] ?></b></td>
												<td>Email</td>
												<td><b><?php echo $row[3] ?></b></td>
											</tr>
											<tr>
												<td>Chairman</td>
												<td><b><?php echo $row[4] ?></b></td>
												<td>Contact #s</td>
												<td><b><?php echo $row[5].', '.$row[6] ?></b></td>
											</tr>
											<tr>
												<td>OTC Accreditation No.</td>
												<td><b><?php echo $row[11] ?></b></td>
												<td>CDA Registration No.</td>
												<td><b><?php echo $row[13] ?></b></td>
											</tr>
											<tr>
												<td>OTC Accreditation Date</td>
												<td><b><?php echo $row[12] ?></b></td>
												<td>CDA Registration Date</td>
												<td><b><?php echo $row[14] ?></b></td>
											</tr>
											<tr>
												<td>Certs of Good Standing</td>
												<td>
												<?php
												$sql = "SELECT ccid, cgs_num
														FROM cooperatives_cgs 
														WHERE cid = '$cid'
														ORDER BY cgs_num DESC";
												$cg_res = query($sql); 
												while ($cg_row = fetch_array($cg_res)) {
													?><a href="cgs.php?ccid=<?php echo $cg_row[0] ?>"><?php echo $cg_row[1] ?></a>, <?php
												}
												?>
												</td>
												<td>Provisional Acc. Expiry Date</td>
												<td><?php
													if ($row[15] == '00/00/0000') { echo 'Not Applicable';
													} else echo '<b>'.$row[15].'</b>';
													?></td>
											</tr>
											<tr>
												<td>Route(s)</td>
												<td colspan="3"><b><?php 
													$sql = "SELECT DISTINCT route_start, route_end 
															FROM franchises
															WHERE cid = '$cid'";
													$fres = query($sql); 
													while ($frow = fetch_array($fres)) {
														echo $frow[0].' to '.$frow[1].'; ';
													}
													?></b></td>
											</tr>
											<tr>
												<td>Attended Trainings</td>
												<td colspan="3"><b><?php 
													$sql = "SELECT DISTINCT t.tname 
															FROM cooperatives_trainings ct 
																INNER JOIN tc_trainings t ON ct.tid = t.tid
															WHERE ct.cid = '$cid'";
													$tres = query($sql); 
													while ($trow = fetch_array($tres)) {
														echo $trow[0].'; ';
													}
													?></b></td>
											</tr>
											<?php
											if ($row[16] == 3) {
												?>
												<tr>
													<td>Affiliated TCs</td>
													<td colspan="3"><b><ol><?php 
														$sql = "SELECT cid, cname 
																FROM cooperatives WHERE fed_id = '$cid'";
														$ares = query($sql); $anum = num_rows($ares); 
														while ($arow = fetch_array($ares)) {
															?><li><a href="tc.php?cid=<?php echo $arow[0] ?>"><?php echo $arow[1] ?></a></li><?php
														}
														?></ol></b></td>
												</tr>
												<?php
											}
											
											if ($row[17] != 0) {
												?>
												<tr>
													<td>Affiliation</td>
													<td colspan="3"><b><?php 
														$sql = "SELECT cid, cname 
																FROM cooperatives WHERE cid = '$row[17]'";
														$ares = query($sql); $arow = fetch_array($ares); 
														?><a href="tc.php?cid=<?php echo $arow[0] ?>"><?php echo $arow[1] ?></a><?php
														?></b></td>
												</tr>
												<?php
											}
											?>
											<tr>
												<td>Last Updated By</td>
												<td><b><?php 
													$sql = "SELECT CONCAT(lname,', ',fname)
															FROM users 
															WHERE user_id = '$row[7]'";
													$eres = query($sql); 
													$erow = fetch_array($eres); 
													echo $erow[0] ?></b></td>
												<td>Last Verified By</td>
												<td><b><?php 
													if ($row[9] != 0) {
														$sql = "SELECT CONCAT(lname,', ',fname)
																FROM users 
																WHERE user_id = '$row[9]'";
														$vres = query($sql); 
														$vrow = fetch_array($vres); 
														echo $vrow[0]; 
													}
													?></b></td>
											</tr>
											<tr>
												<td>Date Updated</td>
												<td><b><?php echo $row[8] == '00/00/0000 12:00 AM' ? '' : $row[8] ?></b></td>
												<td>Date Verified</td>
												<td><b><?php echo $row[10] == '00/00/0000 12:00 AM' ? '' : $row[10] ?></b></td>
											</tr>
										</tbody>
									</table>
									
									
									
									
									<ul class="nav navbar-right panel_toolbox">
										<a href="add_amendment.php?cid=<?php echo $cid ?>" class="btn btn-primary btn-md"><i class="fa fa-check"></i> Add Amendment </a>
									</ul>
									<table class="table table-striped table-bordered jambo_table">
										<thead>
										  <tr class="headings">
											  <th width="10%"></th>
											  <th width="12%">Date</th>
											  <th width="18%">Amendment No.</th>
											  <th width="45%">Remarks</th>
											  <th width="15%">Encoder</th>
										  </tr>
										</thead>
										<tbody>
										<?php
										$sql = "SELECT a.aid, DATE_FORMAT(a.adate, '%b %e, %Y'), a.anum, a.adesc, CONCAT(u.lname,', ',u.fname)
												FROM cooperatives_amendments a INNER JOIN users u ON a.user_id = u.user_id
												WHERE a.cid = '$cid'
												ORDER BY a.adate DESC";
										$res = query($sql); 
										while ($row = fetch_array($res)) {
											?>
											<tr>
												<td align="center">
													<a href="e_amendment.php?aid=<?php echo $row[0] ?>" class="btn btn-warning btn-xs"><i class="fa fa-pencil"></i></a>
													<a href="d_amendment.php?aid=<?php echo $row[0] ?>" class="btn btn-danger btn-xs"><i class="fa fa-eraser"></i></a>
												</td>
												<td align="center"><?php echo $row[1] ?></td>
												<td align="center"><?php echo $row[2] ?></td>
												<td align="left"><?php echo $row[3] ?></td>
												<td align="center"><?php echo $row[4] ?></td>
											</tr>
											<?php
										}
										free_result($res); 
										?>
										</tbody>
									</table>
									<?php
								} elseif ($tab == 2) {
									// count the type of members
									$sql = "SELECT class_name FROM tc_classes";
									$tc_res = query($sql); 
									$tc_num = num_rows($tc_res); 
									$tc_num = $tc_num + 1; 
									$width = 90 / 5; 
									?>
									<div class="x_title">
									  <h2>Number of Members</h2>
									  <ul class="nav navbar-right panel_toolbox">
										<a href="add_member2.php?cid=<?php echo $cid ?>" class="btn btn-primary btn-md"><i class="fa fa-plus-square"></i> Add </a>
									  </ul>
									  <div class="clearfix"></div>
									</div>
									<table class="table table-striped table-bordered jambo_table">
										<thead class="headings">
										  <tr>
											  <th rowspan="3"></th>
											  <th rowspan="3">Year</th>
											  <th colspan="4">Operators</th>
											  <th colspan="4">Drivers</th>
											  <th colspan="4">Allied Workers</th>
											  <th colspan="4">Others</th>
											  <th colspan="4">Total</th>
										  </tr>
										  <tr>
											  <?php
											  for ($i=1; $i<6; $i++) {
												  ?><th colspan="2">Regular</th><th colspan="2">Associate</th><?php
											  }
											  ?>
										  </tr>
										  <tr>
											  <?php
											  for ($i=1; $i<6; $i++) {
												  ?><th>M</th><th>F</th><th>M</th><th>F</th><?php
											  }
											  ?>
										  </tr>
										</thead>
										<tbody>
											<?php
											$sql = "SELECT * 
													FROM cooperatives_members2
													WHERE cid = '$cid'
													ORDER BY cyear";
											$res = query($sql); 
											while ($row = fetch_array($res)) {
												?>
												<tr>
													<td align="center"><a href="d_members.php?mid=<?php echo $row[0] ?>" class="btn btn-danger btn-xs"><i class="fa fa-eraser"></i></a></td>
													<td align="center"><?php echo $row[2] ?></td>
													<td align="center"><?php echo $row[3] ?></td>
													<td align="center"><?php echo $row[4] ?></td>
													<td align="center"><?php echo $row[5] ?></td>
													<td align="center"><?php echo $row[6] ?></td>
													<td align="center"><?php echo $row[7] ?></td>
													<td align="center"><?php echo $row[8] ?></td>
													<td align="center"><?php echo $row[9] ?></td>
													<td align="center"><?php echo $row[10] ?></td>
													<td align="center"><?php echo $row[11] ?></td>
													<td align="center"><?php echo $row[12] ?></td>
													<td align="center"><?php echo $row[13] ?></td>
													<td align="center"><?php echo $row[14] ?></td>
													<td align="center"><?php echo $row[15] ?></td>
													<td align="center"><?php echo $row[16] ?></td>
													<td align="center"><?php echo $row[17] ?></td>
													<td align="center"><?php echo $row[18] ?></td>
													<td align="center"><?php echo $row[3] + $row[7] + $row[11] + $row[15] ?></td>
													<td align="center"><?php echo $row[4] + $row[8] + $row[12] + $row[16] ?></td>
													<td align="center"><?php echo $row[5] + $row[9] + $row[13] + $row[17] ?></td>
													<td align="center"><?php echo $row[6] + $row[10] + $row[14] + $row[18] ?></td>
												</tr>
												<?php 
											}
											free_result($res); 
											?>
										<tbody>
									</table>
										
									<br />
									<div class="x_title">
									  <h2>Number of Units</h2>
									  <ul class="nav navbar-right panel_toolbox">
										<a href="add_units.php?cid=<?php echo $cid ?>" class="btn btn-primary btn-md"><i class="fa fa-plus-square"></i> Add </a>
									  </ul>
									  <div class="clearfix"></div>
									</div>
									<table class="table table-striped table-bordered jambo_table">
										<thead>
										  <tr class="headings">
											  <th width="05%"></th>
											  <th width="20%">Year</th>
											  <th width="25%">Cooperative-Owned</th>
											  <th width="25%">Individually-Owned</th>
											  <th width="25%">Total Units</th>
										  </tr>
										</thead>
										<tbody>
										<?php
										$sql = "SELECT cuid, YEAR(cyear), coop_owned, individual_owned, coop_owned + individual_owned
												FROM cooperatives_units
												WHERE cid = '$cid'";
										$res = query($sql); 
										while ($row = fetch_array($res)) {
											?>
											<tr>
												<td align="center"><a href="e_units.php?cuid=<?php echo $row[0] ?>" class="btn btn-warning btn-xs"><i class="fa fa-pencil"></i></a></td>
												<td align="center"><?php echo $row[1] ?></td>
												<td align="right"><?php echo number_format($row[2], 0) ?></td>
												<td align="right"><?php echo number_format($row[3], 0) ?></td>
												<td align="right"><?php echo number_format($row[4], 0) ?></td>
											</tr>
											<?php
										}
										free_result($res); 
										?>
										</tbody>
									</table>
									
									<br />
									<div class="x_title">
									  <h2>Members Masterlist</h2>
									  <ul class="nav navbar-right panel_toolbox">
										<a href="add_member.php?cid=<?php echo $cid ?>" class="btn btn-primary btn-md"><i class="fa fa-plus-square"></i> Add </a>
									  </ul>
									  <div class="clearfix"></div>
									</div>
									<table class="table table-striped table-bordered jambo_table">
										<thead>
										  <tr class="headings">
											  <th width="10%"></th>
											  <th width="04%"></th>
											  <th width="18%">Member</th>
											  <th width="16%">Contact Info</th>
											  <th width="20%">Address</th>
											  <th width="18%">Personal Info</th>
											  <th width="14%">Income Info</th>
										  </tr>
										</thead>
										<tbody>
										<?php
										$i = 1; 
										$sql = "SELECT mid, CONCAT(lname,', ',fname), 
													DATE_FORMAT(bdate, '%b %e, %Y'), contact_num, email, addr, 
													children, hobbies, income, sources
												FROM cooperatives_members
												WHERE cid = '$cid'
												ORDER BY lname, fname";
										$res = query($sql); 
										while ($row = fetch_array($res)) {
											?>
											<tr>
												<td align="center">
													<a href="member.php?mid=<?php echo $row[0] ?>" class="btn btn-info btn-xs"><i class="fa fa-search"></i></a>
													<a href="e_member.php?mid=<?php echo $row[0] ?>" class="btn btn-warning btn-xs"><i class="fa fa-pencil"></i></a>
													<a href="d_member.php?mid=<?php echo $row[0] ?>" class="btn btn-danger btn-xs"><i class="fa fa-eraser"></i></a>
												</td>
												<td align="center"><?php echo $i ?></td>
												<td align="left"><?php echo 'Name: <b>'.ucwords(strtolower($row[1])).'</b><br />Birthdate: <b>'.$row[2].'</b>'; ?></td>
												<td align="left"><?php echo 'CP#: <b>'.$row[3].'</b><br />Email: <b>'.$row[4].'</b>'; ?></td>
												<td align="left"><?php echo ucwords(strtolower($row[5])) ?></td>
												<td align="left"><?php echo 'No of Children: <b>'.$row[6].'</b><br />Hobbies: <b>'.$row[7].'</b>'; ?></td>
												<td align="left"><?php echo 'Approximate Income: <b>'.$row[8].'</b><br />Sources of Income: <b>'.$row[9].'</b>'; ?></td>
											</tr>
											<?php
											$i++;
										}
										free_result($res); 
										?>
										</tbody>
									</table>
									
									<?php
								} elseif ($tab == 3) {
									?>
									<div class="x_title">
									  <h2>Aquisition of Certificate of Good Standing</h2>
									  <ul class="nav navbar-right panel_toolbox">
										<a href="add_cgs.php?cid=<?php echo $cid ?>" class="btn btn-primary btn-md"><i class="fa fa-plus-square"></i> Add </a>
									  </ul>
									  <div class="clearfix"></div>
									</div>
									<table class="table table-striped table-bordered jambo_table">
										<thead>
										  <tr class="headings">
											  <th width="16%"></th>
											  <th width="08%">Year</th>
											  <th width="10%">CGS No.</th>
											  <th width="09%">Date Issued</th>
											  <th width="09%">Expiration</th>
											  <th width="15%">Updated By</th>
											  <th width="09%">Date Updated</th>
											  <th width="15%">Verified By</th>
											  <th width="09%">Date Verified</th>
										  </tr>
										</thead>
										<tbody>
										<?php
										$sql = "SELECT c.ccid, DATE_FORMAT(c.cgs_date, '%Y'), c.cgs_num, DATE_FORMAT(c.cgs_date, '%m/%d/%y'),
													DATE_FORMAT(c.cgs_exp, '%m/%d/%y'), e.lname, DATE_FORMAT(c.last_update, '%m/%d/%y'), 
													c.last_verify_id, DATE_FORMAT(c.last_verify, '%m/%d/%y')
												FROM cooperatives_cgs c 
													LEFT JOIN employees e ON c.last_update_id = e.eid
												WHERE c.cid = '$cid'
												ORDER BY c.cgs_date";
										$res = query($sql); 
										while ($row = fetch_array($res)) {
											?>
											<tr>
												<td align="center">
													<a href="cgs.php?ccid=<?php echo $row[0] ?>" class="btn btn-info btn-xs"><i class="fa fa-search"></i></a>
													<?php
													if ($row[7] == 0) {
														?><a href="verify_cgs.php?ccid=<?php echo $row[0] ?>" class="btn btn-success btn-xs"><i class="fa fa-check"></i></a><?php
													}
													?>
													<a href="e_cgs.php?ccid=<?php echo $row[0] ?>" class="btn btn-warning btn-xs"><i class="fa fa-pencil"></i></a>
													<?php
													if ($row[7] == 0) {
														?><a href="d_cgs.php?ccid=<?php echo $row[0] ?>" class="btn btn-danger btn-xs"><i class="fa fa-eraser"></i></a><?php
													}
													?>
												</td>
												<td align="center"><?php echo $row[1] ?></td>
												<td align="center"><?php echo $row[2] ?></td>
												<td align="center"><?php echo $row[3] ?></td>
												<td align="center"><?php echo $row[4] ?></td>
												<td align="left"><?php echo $row[5] ?></td>
												<td align="center"><?php echo $row[6] != '00/00/00' ? $row[6] : '' ?></td>
												<td align="left"><?php 
													if ($row[7] != 0) {
														$sql = "SELECT lname FROM employees WHERE eid = '$row[7]'";
														$eres = query($sql); 
														$erow = fetch_array($eres); 
														echo $erow[0];
														free_result($eres); 
													}
													?></td>
												<td align="center"><?php echo $row[8] != '00/00/00' ? $row[8] : '' ?></td>
											</tr>
											<?php
										}
										free_result($res); 
										?>
										</tbody>
									</table>
									
									
									<br />
									<div class="x_title">
									  <h2>Democratic Governance</h2>
									  <ul class="nav navbar-right panel_toolbox">
										<a href="add_governance.php?cid=<?php echo $cid ?>" class="btn btn-primary btn-md"><i class="fa fa-plus-square"></i> Add </a>
									  </ul>
									  <div class="clearfix"></div>
									</div>
									<table class="table table-striped table-bordered jambo_table">
										<thead>
										  <tr class="headings">
											  <th width="08%"></th>
											  <th width="08%">Year</th>
											  <th width="10%">Date</th>
											  <th width="24%">Place</th>
											  <th width="10%">No. of MIGS</th>
											  <th width="10%">Quorum in G.A.</th>
											  <th width="10%">Voters Present</th>
											  <th width="10%">Updated By</th>
											  <th width="10%">Date Updated</th>
										  </tr>
										</thead>
										<tbody>
										<?php
										$sql = "SELECT g.gid, DATE_FORMAT(g.gdate, '%Y'), DATE_FORMAT(g.gdate, '%m/%d/%y'),
													g.gplace, g.migs, g.quorum, g.voters, u.lname, DATE_FORMAT(g.user_dtime, '%m/%d/%y')
												FROM cooperatives_governances g INNER JOIN users u ON g.user_id = u.user_id
												WHERE g.cid = '$cid'
												ORDER BY g.gdate";
										$res = query($sql); 
										while ($row = fetch_array($res)) {
											?>
											<tr>
												<td align="center">
													<a href="e_governance.php?gid=<?php echo $row[0] ?>" class="btn btn-warning btn-xs"><i class="fa fa-pencil"></i></a>
													<a href="d_governance.php?gid=<?php echo $row[0] ?>" class="btn btn-danger btn-xs"><i class="fa fa-eraser"></i></a>
												</td>
												<td align="center"><?php echo $row[1] ?></td>
												<td align="center"><?php echo $row[2] ?></td>
												<td align="left"><?php echo $row[3] ?></td>
												<td align="center"><?php echo $row[4] ?></td>
												<td align="center"><?php echo $row[5] ?></td>
												<td align="center"><?php echo $row[6] ?></td>
												<td align="center"><?php echo $row[7] ?></td>
												<td align="center"><?php echo $row[8] ?></td>
											</tr>
											<?php
										}
										free_result($res); 
										?>
										</tbody>
									</table>
									
									<?php
									$syear = date('Y');
									if (isset ($_GET['gyear'])) {
										$gyear = $_GET['gyear'];
									} else {
										$gyear = date('Y');
									}
									?>
									<br />
									<div class="x_title">
									  <h2>List of Officers</h2>
										<ul class="nav navbar-right panel_toolbox">
											<select class="form-control col-md-7 col-xs-12" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
												<option value="">Select Year</option>
												<?php
												for ($year=$syear; $year>2009; $year--) {
													if ($gyear == $year) {
														?><option value="tc.php?cid=<?php echo $cid ?>&tab=3&gyear=<?php echo $year ?>" Selected><?php echo $year ?></option><?php
													} else {
														?><option value="tc.php?cid=<?php echo $cid ?>&tab=3&gyear=<?php echo $year ?>"><?php echo $year ?></option><?php
													}
												}
												?>
											</select>
										</ul>
									  <div class="clearfix"></div>
									</div>
									<table class="table table-striped table-bordered jambo_table">
										<thead>
										  <tr class="headings">
											  <th width="05%"></th>
											  <th width="20%">Position</th>
											  <th width="25%">Name</th>
											  <th width="10%">Term</th>
											  <th width="15%">Mobile</th>
											  <th width="20%">Email</th>
										  </tr>
										</thead>
										<tbody>
											<tr>
												<td colspan="2" align="left"><b>BOARD OF DIRECTORS</b></td>
												<td align="center"></td>
												<td align="center"></td>
												<td align="center"></td>
												<td align="center"></td>
											</tr>
											<?php
											$sql = "SELECT pid, pname FROM tc_positions WHERE pid < 8";
											$pres = query($sql); 
											while ($prow = fetch_array($pres)) {
												if ($prow[0] == 3) { // position is board of directors --> populate more
													$x = 1;
													// get from memberships table
													$sql = "SELECT m2.cmid, CONCAT(m1.lname,', ',m1.fname,', ',m1.mname), 
																DATE_FORMAT(m2.ostart, '%Y'), DATE_FORMAT(m2.oend, '%Y'), 
																m1.contact_num, m1.email
															FROM cooperatives_memberships m2 
																INNER JOIN cooperatives_members m1 ON m2.mid = m1.mid
															WHERE m2.cid = '$cid' AND m2.pos_id = '$prow[0]'
																AND $gyear >= YEAR(m2.ostart) 
																AND $gyear <= YEAR(m2.oend)";
													$mres = query($sql); $mnum = num_rows($mres); 
													
													if ($mnum > 0) {
														while ($mrow = fetch_array($mres)) {
															?>
															<tr>
																<td align="center"><a href="d_membership.php?cmid=<?php echo $mrow[0] ?>" class="btn btn-danger btn-xs"><i class="fa fa-eraser"></i></a></td>
																<td align="left"><?php echo $prow[1] ?></td>
																<td align="left"><?php echo $mrow[1] ?></td>
																<td align="center"><?php echo $mrow[2].' - '.$mrow[3] ?></td>
																<td align="left"><?php echo $mrow[4] ?></td>
																<td align="left"><?php echo $mrow[5] ?></td>
															</tr>
															<?php
															$x++;
														}
														for ($y=$x; $y<15; $y++) {
															?>
															<tr>
																<td align="center"><a href="add_membership.php?cid=<?php echo $cid ?>&pos_id=<?php echo $prow[0] ?>" class="btn btn-primary btn-xs"><i class="fa fa-plus-square"></i></a></td>
																<td align="left"><?php echo $prow[1] ?></td>
																<td align="left"></td>
																<td align="left"></td>
																<td align="left"></td>
																<td align="left"></td>
															</tr>
															<?php
														}
														free_result($mres); 
													} else {
														?>
														<tr>
															<td align="center"><a href="add_membership.php?cid=<?php echo $cid ?>&pos_id=<?php echo $prow[0] ?>" class="btn btn-primary btn-xs"><i class="fa fa-plus-square"></i></a></td>
															<td align="left"><?php echo $prow[1] ?></td>
															<td align="left"></td>
															<td align="left"></td>
															<td align="left"></td>
															<td align="left"></td>
														</tr>
														<?php
													}
												} else { // position is not board of directors
													// get from memberships table
													$sql = "SELECT m2.cmid, CONCAT(m1.lname,', ',m1.fname,', ',m1.mname), 
																DATE_FORMAT(m2.ostart, '%Y'), DATE_FORMAT(m2.oend, '%Y'), 
																m1.contact_num, m1.email
															FROM cooperatives_memberships m2 
																INNER JOIN cooperatives_members m1 ON m2.mid = m1.mid
															WHERE m2.cid = '$cid' AND m2.pos_id = '$prow[0]'
																AND $gyear >= YEAR(m2.ostart) 
																AND $gyear <= YEAR(m2.oend)";
													$mres = query($sql); $mnum = num_rows($mres); 
													?>
													<tr>
														<td align="center">
															<?php
															if ($mnum > 0) {
																$mrow = fetch_array($mres);
																?><a href="d_membership.php?cmid=<?php echo $mrow[0] ?>" class="btn btn-danger btn-xs"><i class="fa fa-eraser"></i></a><?php
															} else {
																?><a href="add_membership.php?cid=<?php echo $cid ?>&pos_id=<?php echo $prow[0] ?>" class="btn btn-primary btn-xs"><i class="fa fa-plus-square"></i></a><?php
															}
															?>
														</td>
														<td align="left"><?php echo $prow[1] ?></td>
														<?php
														if ($mnum > 0) {
															?>
															<td align="left"><?php echo $mrow[1] ?></td>
															<td align="center"><?php echo $mrow[2].' - '.$mrow[3] ?></td>
															<td align="left"><?php echo $mrow[4] ?></td>
															<td align="left"><?php echo $mrow[5] ?></td>
															<?php
														} else {
															?>
															<td align="left"></td>
															<td align="left"></td>
															<td align="left"></td>
															<td align="left"></td>
															<?php
														}
														?>
													</tr>
													<?php
													free_result($mres); 
												}
											}
											free_result($pres); 
											
											$sql = "SELECT comm_id, comm_name FROM tc_committees";
											$tc_res = query($sql); 
											while ($tc_row = fetch_array($tc_res)) {
												?>
												<tr>
													<td colspan="2" align="left"><b><?php echo strtoupper($tc_row[1]) ?> COMMITTEE</b></td>
													<td align="center"></td>
													<td align="center"></td>
													<td align="center"></td>
													<td align="center"></td>
												</tr>
												<?php
												$sql = "SELECT pid, pname FROM tc_positions WHERE pid >= 8 AND pid <= 10";
												$tc_pres = query($sql); 
												while ($tc_prow = fetch_array($tc_pres)) {
													$sql = "SELECT m2.cmid, CONCAT(m1.lname,', ',m1.fname,', ',m1.mname), 
															DATE_FORMAT(m2.ostart, '%Y'), DATE_FORMAT(m2.oend, '%Y'), 
															m1.contact_num, m1.email
															FROM cooperatives_memberships m2 
																INNER JOIN cooperatives_members m1 ON m2.mid = m1.mid
															WHERE m2.cid = '$cid' AND m2.pos_id = '$tc_prow[0]'
																AND m2.comm_id = '$tc_row[0]'
																AND $gyear >= YEAR(m2.ostart) 
																AND $gyear <= YEAR(m2.oend)";
													$comm_res = query($sql); 
													$comm_num = num_rows($comm_res); 
													
													if ($comm_num > 0) {
														$comm_row = fetch_array($comm_res); 
														?>
														<tr>
															<td align="center"><a href="d_membership.php?cmid=<?php echo $comm_row[0] ?>" class="btn btn-danger btn-xs"><i class="fa fa-eraser"></i></a></td>
															<td align="left"><?php echo str_replace('Committee ', '', $tc_prow[1]) ?></td>
															<td align="left"><?php echo $comm_row[1] ?></td>
															<td align="center"><?php echo $comm_row[2].' - '.$comm_row[3] ?></td>
															<td align="left"><?php echo $comm_row[4] ?></td>
															<td align="left"><?php echo $comm_row[5] ?></td>
														</tr>
														<?php
													} else {
														?>
														<tr>
															<td align="center"><a href="add_membership.php?cid=<?php echo $cid ?>&pos_id=<?php echo $tc_prow[0] ?>&comm_id=<?php echo $tc_row[0] ?>" class="btn btn-primary btn-xs"><i class="fa fa-plus-square"></i></a></td>
															<td align="left"><?php echo str_replace('Committee ', '', $tc_prow[1]) ?></td>
															<td align="left"></td>
															<td align="center"></td>
															<td align="left"></td>
															<td align="left"></td>
														</tr>
														<?php
													}
													?>
													<?php
												}
												free_result($tc_pres); 
											}
											free_result($tc_res); 
											?>
										</tbody>
									</table>
									<?php
								} elseif ($tab == 4) {
									?>
									<div class="x_title">
									  <h2>Units Masterlist</h2>
									  <ul class="nav navbar-right panel_toolbox">
										<a href="add_unit.php?cid=<?php echo $cid ?>" class="btn btn-primary btn-md"><i class="fa fa-plus-square"></i> Add </a>
									  </ul>
									  <div class="clearfix"></div>
									</div>
									<table class="table table-striped table-bordered jambo_table">
										<thead>
										  <tr class="headings">
											  <th width="10%"></th>
											  <th width="05%"></th>
											  <th width="20%">Unit Details</th>
											  <th width="20%">CPC Details</th>
											  <th width="25%">Route Details</th>
											  <th width="20%">Owner Details</th>
										  </tr>
										</thead>
										<tbody>
										<?php
										$i = 1; 
										$sql = "SELECT uid, utype, mv_num, engine_num, chassis_num, plate_num, cpc_num,
													DATE_FORMAT(cpc_date, '%m/%d/%y'), DATE_FORMAT(cpc_exp, '%m/%d/%y'), 
													route_start, route_end, route_via, vice_versa, 
													owner_fname, owner_mname, owner_lname
												FROM cooperatives_units2
												WHERE cid = '$cid'";
										$res = query($sql); 
										while ($row = fetch_array($res)) {
											if ($row[1] == 1) { $unit = 'PUJ';
											} elseif ($row[1] == 2) { $unit = 'Tricycle';
											} elseif ($row[1] == 3) { $unit = 'Taxi';
											} elseif ($row[1] == 4) { $unit = 'Mini Bus';
											} elseif ($row[1] == 5) { $unit = 'Bus';
											} elseif ($row[1] == 6) { $unit = 'Multi-Cab';
											} elseif ($row[1] == 7) { $unit = 'Truck';
											} elseif ($row[1] == 8) { $unit = 'AUV';
											} elseif ($row[1] == 9) { $unit = 'Motorized Banca';
											}
											
											if ($row[12] == 1) { $vice_versa = 'Yes';
											} elseif ($row[12] == 0) { $vice_versa = 'No';
											}
											?>
											<tr>
												<td align="center">
													<a href="e_unit.php?uid=<?php echo $row[0] ?>" class="btn btn-warning btn-xs"><i class="fa fa-pencil"></i></a>
													<a href="d_unit.php?uid=<?php echo $row[0] ?>" class="btn btn-danger btn-xs"><i class="fa fa-eraser"></i></a>
												</td>
												<td align="center"><?php echo $i ?></td>
												<td align="left"><?php echo 'Unit: <b>'.$unit.'</b><br />MV File No: <b>'.$row[2].'</b><br />Engine No: <b>'.$row[3].'</b><br />Chassis No: <b>'.$row[4].'</b><br />Plate No: <b>'.$row[5].'</b>'; ?></td>
												<td align="left"><?php echo 'CPC No: <b>'.$row[6].'</b><br />Date Granted: <b>'.$row[7].'</b><br />Expiry: <b>'.$row[8].'</b>'; ?></td>
												<td align="left"><?php echo 'Start: <b>'.$row[9].'</b><br />Destination: <b>'.$row[10].'</b><br />Via: <b>'.$row[11].'</b><br />Vice Versa: <b>'.$vice_versa.'</b>'; ?></td>
												<td align="left"><?php echo 'First Name: <b>'.$row[13].'</b><br />Middle Name: <b>'.$row[14].'</b><br />Last Name: <b>'.$row[15].'</b>'; ?></td>
											</tr>
											<?php
											$i++; 
										}
										free_result($res); 
										?>
										</tbody>
									</table>
									<?php
								} elseif ($tab == 5) {
									?>
									<div class="x_title">
									  <h2>Area of Operation</h2>
									  <ul class="nav navbar-right panel_toolbox">
										<a href="add_franchise.php?cid=<?php echo $cid ?>" class="btn btn-primary btn-md"><i class="fa fa-plus-square"></i> Add </a>
									  </ul>
									  <div class="clearfix"></div>
									</div>
									<table class="table table-striped table-bordered jambo_table">
										<thead>
										  <tr class="headings">
											<th width="10%"></th>
											<th width="10%">Route Type</th>
											<th width="10%">Type of Service</th>
											<th width="10%">Units</th>
											<th width="40%">Route</th>
											<th width="10%">LTFRB Case No.</th>
											<th width="10%">Expiry</th>
										  </tr>
										</thead>
										<tbody>
										<?php
										$sql = "SELECT fid, utype2, utype, units_num, route_start, route_end, vice_versa, ltfrb_case, DATE_FORMAT(exp_date, '%m/%d/%y')
												FROM franchises
												WHERE cid = '$cid'";
										$res = query($sql); 
										while ($row = fetch_array($res)) {
											?>
											<tr>
												<td align="center">
													<a href="e_franchise.php?fid=<?php echo $row[0] ?>" class="btn btn-warning btn-xs"><i class="fa fa-pencil"></i></a>
													<a href="d_franchise.php?fid=<?php echo $row[0] ?>" class="btn btn-danger btn-xs"><i class="fa fa-eraser"></i></a>
												</td>
												<td align="center"><?php echo $row[1] == 1 ? 'Existing' : 'Proposed' ?></td>
												<td align="center"><?php 
													if ($row[2] == 1) { echo 'PUJ'; 
													} elseif ($row[2] == 2) { echo 'UV Express'; 
													} elseif ($row[2] == 3) { echo 'Taxi'; 
													} elseif ($row[2] == 4) { echo 'MultiCab'; 
													} elseif ($row[2] == 5) { echo 'Mini Bus'; 
													} elseif ($row[2] == 6) { echo 'Bus'; 
													} elseif ($row[2] == 7) { echo 'Tricycle'; 
													} elseif ($row[2] == 8) { echo 'Truck'; 
													} elseif ($row[2] == 9) { echo 'Motorized Banca'; 
													} elseif ($row[2] == 10) { echo 'Tourist Service'; 
													} elseif ($row[2] == 11) { echo 'PUVM Class 1'; 
													} elseif ($row[2] == 12) { echo 'PUVM Class 2'; 
													} elseif ($row[2] == 13) { echo 'PUVM Class 3'; 
													} elseif ($row[2] == 14) { echo 'PUVM Class 4'; 
													}
													?></td>
												<td align="right"><?php echo number_format($row[3], 0) ?></td>
												<td align="left"><?php echo $row[4].' to '.$row[5]; echo $row[6] == 1 ? ' and Vice-Vera' : '' ?></td>
												<td align="center"><?php echo $row[7] ?></td>
												<td align="center"><?php echo $row[8] ?></td>
											</tr>
											<?php
										}
										free_result($res); 
										?>
										</tbody>
									</table>
									<!-- LTFRB Franchises
									<table class="table table-striped table-bordered jambo_table">
										<thead>
										  <tr class="headings">
											<th width="12%"></th>
											<th width="10%">Case #</th>
											<th width="12%">Owner Type</th>
											<th width="08%">Units</th>
											<th width="08%">Granted</th>
											<th width="08%">Expiry</th>
											<th width="38%">Route</th>
										  </tr>
										</thead>
										<tbody>
										<?php
										/*
										$sql = "SELECT fid, case_num, owner, 
													owner_type, units_num, 
													DATE_FORMAT(dg_date, '%m/%d/%y'), 
													DATE_FORMAT(de_date, '%m/%d/%y'),
													route, foreign_id
												FROM franchises_ltfrb
												WHERE foreign_id = '$cid'
												ORDER BY de_date DESC";
										$res = query($sql); 
										while ($row = fetch_array($res)) {
											?>
											<tr>
												<td align="center">
													<?php
													if ($row[3] == 'Cooperative' && $row[8] != 0) {
														?><a target="new" href="tc.php?cid=<?php echo $row[8] ?>" class="btn btn-info btn-xs"><i class="fa fa-search"></i></a><?php
													}
													?>
													<a href="e_ltfrb_franchise.php?fid=<?php echo $row[0] ?>" class="btn btn-warning btn-xs"><i class="fa fa-pencil"></i></a>
													<a href="d_ltfrb_franchise.php?fid=<?php echo $row[0] ?>" class="btn btn-danger btn-xs"><i class="fa fa-eraser"></i></a>
												</td>
												<td align="center"><?php echo $row[1] ?></td>
												<td align="left"><?php echo $row[3] ?></td>
												<td align="right"><?php echo number_format($row[4], 0) ?></td>
												<td align="center"><?php echo $row[5] ?></td>
												<td align="center"><?php echo $row[6] ?></td>
												<td align="left"><?php echo $row[7] ?></td>
											</tr>
											<?php
										}
										free_result($res); 
										*/
										?>
										</tbody>
									</table>
									-->
									
									<br />
									<div class="x_title">
									  <h2>Type of Service</h2>
									  <ul class="nav navbar-right panel_toolbox">
										<a href="add_units.php?cid=<?php echo $cid ?>" class="btn btn-primary btn-md"><i class="fa fa-plus-square"></i> Add </a>
									  </ul>
									  <div class="clearfix"></div>
									</div>
									<table class="table table-striped table-bordered jambo_table">
										<thead>
										  <tr class="headings">
											  <th width="10%"></th>
											  <th width="06%">Year</th>
											  <th width="06%">Class 1</th>
											  <th width="06%">Class 2</th>
											  <th width="06%">Class 3</th>
											  <th width="06%">Class 4</th>
											  <th width="06%">PUJ</th>
											  <th width="06%">Tourist</th>
											  <th width="06%">UVEx</th>
											  <th width="06%">Taxi</th>
											  <th width="06%">Multi Cab</th>
											  <th width="06%">Mini Bus</th>
											  <th width="06%">Bus</th>
											  <th width="06%">MCH</th>
											  <th width="06%">Truck</th>
											  <th width="06%">M. Banca</th>
										  </tr>
										</thead>
										<tbody>
										<?php
										$sql = "SELECT cuid, cyear, class1_num, class2_num, class3_num, class4_num, 
													puj_num, tours_num, uvx_num, taxi_num, mcab_num, 
													mb_num, bus_num, mch_num, truck_num, banka_num
												FROM cooperatives_units
												WHERE cid = '$cid'
												ORDER BY cyear";
										$res = query($sql); 
										while ($row = fetch_array($res)) {
											?>
											<tr>
												<td align="center">
													<a href="e_units.php?cuid=<?php echo $row[0] ?>" class="btn btn-warning btn-xs"><i class="fa fa-pencil"></i></a>
													<a href="d_units.php?cuid=<?php echo $row[0] ?>" class="btn btn-danger btn-xs"><i class="fa fa-eraser"></i></a>
												</td>
												<td align="center"><?php echo $row[1] ?></td>
												<td align="right"><?php echo number_format($row[2], 0) ?></td>
												<td align="right"><?php echo number_format($row[3], 0) ?></td>
												<td align="right"><?php echo number_format($row[4], 0) ?></td>
												<td align="right"><?php echo number_format($row[5], 0) ?></td>
												<td align="right"><?php echo number_format($row[6], 0) ?></td>
												<td align="right"><?php echo number_format($row[7], 0) ?></td>
												<td align="right"><?php echo number_format($row[8], 0) ?></td>
												<td align="right"><?php echo number_format($row[9], 0) ?></td>
												<td align="right"><?php echo number_format($row[10], 0) ?></td>
												<td align="right"><?php echo number_format($row[11], 0) ?></td>
												<td align="right"><?php echo number_format($row[12], 0) ?></td>
												<td align="right"><?php echo number_format($row[13], 0) ?></td>
												<td align="right"><?php echo number_format($row[14], 0) ?></td>
												<td align="right"><?php echo number_format($row[15], 0) ?></td>
											</tr>
											<?php
										}
										free_result($res); 
										?>
										</tbody>
									</table>
									
									<br />
									<div class="x_title">
									  <h2>Franchises and Ownerships</h2>
									  <ul class="nav navbar-right panel_toolbox">
										<a href="add_units.php?cid=<?php echo $cid ?>" class="btn btn-primary btn-md"><i class="fa fa-plus-square"></i> Add </a>
									  </ul>
									  <div class="clearfix"></div>
									</div>
									<table class="table table-striped table-bordered jambo_table">
										<thead>
										  <tr class="headings">
											  <th width="10%"></th>
											  <th width="10%">Year</th>
											  <th width="16%">Coop-Owned</th>
											  <th width="16%">Individual-Owned</th>
											  <th width="16%">Coop-Franchised</th>
											  <th width="16%">Individual-Franchised</th>
											  <th width="16%">No Franchise</th>
										  </tr>
										</thead>
										<tbody>
										<?php
										$sql = "SELECT cuid, cyear, coop_owned, individual_owned, coop_franchised, individual_franchised, wout_franchise
												FROM cooperatives_units
												WHERE cid = '$cid'
												ORDER BY cyear";
										$res = query($sql); 
										while ($row = fetch_array($res)) {
											?>
											<tr>
												<td align="center">
													<a href="e_units.php?cuid=<?php echo $row[0] ?>" class="btn btn-warning btn-xs"><i class="fa fa-pencil"></i></a>
													<a href="d_units.php?cuid=<?php echo $row[0] ?>" class="btn btn-danger btn-xs"><i class="fa fa-eraser"></i></a>
												</td>
												<td align="center"><?php echo $row[1] ?></td>
												<td align="right"><?php echo number_format($row[2], 0) ?></td>
												<td align="right"><?php echo number_format($row[3], 0) ?></td>
												<td align="right"><?php echo number_format($row[4], 0) ?></td>
												<td align="right"><?php echo number_format($row[5], 0) ?></td>
												<td align="right"><?php echo number_format($row[6], 0) ?></td>
											</tr>
											<?php
										}
										free_result($res); 
										?>
										</tbody>
									</table>
									<?php
								} elseif ($tab == 6) {
									?>
									<div class="x_title">
									  <h2>Financial Statements</h2>
									  <ul class="nav navbar-right panel_toolbox">
										<a href="add_financials.php?cid=<?php echo $cid ?>" class="btn btn-primary btn-md"><i class="fa fa-plus-square"></i> Add </a>
									  </ul>
									  <div class="clearfix"></div>
									</div>
									<table class="table table-striped table-bordered jambo_table">
										<thead>
										  <tr class="headings">
											  <th width="09%"></th>
											  <th width="07%">Year</th>
											  <th width="14%">Current Assets</th>
											  <th width="14%">Fixed Assets</th>
											  <th width="14%">Total Assets</th>
											  <th width="14%">Liabilities</th>
											  <th width="14%">Members Equity</th>
											  <th width="14%">Net Surplus / Loss</th>
										  </tr>
										</thead>
										<tbody>
										<?php
										$sql = "SELECT cfid, cyear, current_assets, fixed_assets, current_assets + fixed_assets, liabilities, equity, net_income, cob
												FROM cooperatives_financials
												WHERE cid = '$cid' 
												ORDER BY cyear";
										$res = query($sql); 
										while ($row = fetch_array($res)) {
											?>
											<tr>
												<td align="center">
													<a href="e_financials.php?cfid=<?php echo $row[0] ?>" class="btn btn-warning btn-xs"><i class="fa fa-pencil"></i></a>
													<a href="d_financials.php?cfid=<?php echo $row[0] ?>" class="btn btn-danger btn-xs"><i class="fa fa-eraser"></i></a>
												</td>
												<td align="left"><?php echo $row[1] ?></td>
												<td align="right"><?php echo number_format($row[2], 2); echo $row[8] == 1 ? '<br /><font color=red>(Cash in Bank)</font>' : '';  ?></td>
												<td align="right"><?php echo number_format($row[3], 2) ?></td>
												<td align="right"><?php echo number_format($row[4], 2) ?></td>
												<td align="right"><?php echo number_format($row[5], 2) ?></td>
												<td align="right"><?php echo number_format($row[6], 2) ?></td>
												<td align="right"><?php echo number_format($row[7], 2) ?></td>
											</tr>
											<?php
										}
										free_result($res); 
										?>
										</tbody>
									</table>
									
									<br />
									<div class="x_title">
									  <h2>Distribution of Net Surplus</h2>
									  <ul class="nav navbar-right panel_toolbox">
										<a href="add_surplus.php?cid=<?php echo $cid ?>" class="btn btn-primary btn-md"><i class="fa fa-plus-square"></i> Add </a>
									  </ul>
									  <div class="clearfix"></div>
									</div>
									<table class="table table-striped table-bordered jambo_table">
										<thead>
										  <tr class="headings">
											  <th width="10%"></th>
											  <th width="10%">Year</th>
											  <th width="16%">General Reserve Fund</th>
											  <th width="16%">Education & Training Fund</th>
											  <th width="16%">Community Development Fund</th>
											  <th width="16%">Optional Fund</th>
											  <th width="16%">Dividends & Patronage Refund</th>
										  </tr>
										</thead>
										<tbody>
										<?php
										$sql = "SELECT cfid, cyear, reserved, educ_training, comm_dev, optional, dividends
												FROM cooperatives_surplus
												WHERE cid = '$cid' 
												ORDER BY cyear";
										$res = query($sql); 
										while ($row = fetch_array($res)) {
											?>
											<tr>
												<td align="center">
													<a href="e_surplus.php?cfid=<?php echo $row[0] ?>" class="btn btn-warning btn-xs"><i class="fa fa-pencil"></i></a>
													<a href="d_surplus.php?cfid=<?php echo $row[0] ?>" class="btn btn-danger btn-xs"><i class="fa fa-eraser"></i></a>
												</td>
												<td align="left"><?php echo $row[1] ?></td>
												<td align="right"><?php echo number_format($row[2], 2) ?></td>
												<td align="right"><?php echo number_format($row[3], 2) ?></td>
												<td align="right"><?php echo number_format($row[4], 2) ?></td>
												<td align="right"><?php echo number_format($row[5], 2) ?></td>
												<td align="right"><?php echo number_format($row[6], 2) ?></td>
											</tr>
											<?php
										}
										free_result($res); 
										?>
										</tbody>
									</table>

									<br />
									<div class="x_title">
									  <h2>Capitalization</h2>
									  <ul class="nav navbar-right panel_toolbox">
										<a href="add_capitalization.php?cid=<?php echo $cid ?>" class="btn btn-primary btn-md"><i class="fa fa-plus-square"></i> Add </a>
									  </ul>
									  <div class="clearfix"></div>
									</div>
									<table class="table table-striped table-bordered jambo_table">
										<thead>
										  <tr class="headings">
											  <th width="10%"></th>
											  <th width="10%">Year</th>
											  <th width="16%">Initial Authorized Capital Stock</th>
											  <th width="16%">Present Authorized Capital Stock</th>
											  <th width="16%">Subscribed Capital</th>
											  <th width="16%">Paid-Up Capital</th>
											  <th width="16%">Capital Build-Up Scheme</th>
										  </tr>
										</thead>
										<tbody>
										<?php
										$sql = "SELECT cfid, cyear, init_stock, present_stock, subscribed, paid_up, scheme
												FROM cooperatives_capitalizations
												WHERE cid = '$cid' 
												ORDER BY cyear";
										$res = query($sql); 
										while ($row = fetch_array($res)) {
											?>
											<tr>
												<td align="center">
													<a href="e_capitalization.php?cfid=<?php echo $row[0] ?>" class="btn btn-warning btn-xs"><i class="fa fa-pencil"></i></a>
													<a href="d_capitalization.php?cfid=<?php echo $row[0] ?>" class="btn btn-danger btn-xs"><i class="fa fa-pencil"></i></a>
												</td>
												<td align="left"><?php echo $row[1] ?></td>
												<td align="right"><?php echo number_format($row[2], 2) ?></td>
												<td align="right"><?php echo number_format($row[3], 2) ?></td>
												<td align="right"><?php echo number_format($row[4], 2) ?></td>
												<td align="right"><?php echo number_format($row[5], 2) ?></td>
												<td align="right"><?php echo $row[6] ?></td>
											</tr>
											<?php
										}
										free_result($res); 
										?>
										</tbody>
									</table>
									
									<br />
									<div class="x_title">
									  <h2>Loans</h2>
									  <ul class="nav navbar-right panel_toolbox">
										<a href="add_loan.php?cid=<?php echo $cid ?>" class="btn btn-primary btn-md"><i class="fa fa-plus-square"></i> Add </a>
									  </ul>
									  <div class="clearfix"></div>
									</div>
									<table class="table table-striped table-bordered jambo_table">
										<thead>
										  <tr class="headings">
											  <th width="10%"></th>
											  <th width="10%">Date Acquired</th>
											  <th width="10%">Loan Amount</th>
											  <th width="25%">Source</th>
											  <th width="20%">Utilization</th>
											  <th width="25%">Status / Remarks</th>
										  </tr>
										</thead>
										<tbody>
										<?php
										$sql = "SELECT l.lid, DATE_FORMAT(l.ldate, '%m/%d/%y'), 
													l.amount, l.source, l.utilization, l.remarks
												FROM cooperatives_loans l
												WHERE l.cid = '$cid'
												ORDER BY l.ldate DESC";
										$res = query($sql); 
										while ($row = fetch_array($res)) {
											?>
											<tr>
												<td align="center">
													<a href="e_loan.php?lid=<?php echo $row[0] ?>" class="btn btn-warning btn-xs"><i class="fa fa-pencil"></i></a>
													<a href="d_loan.php?lid=<?php echo $row[0] ?>" class="btn btn-danger btn-xs"><i class="fa fa-pencil"></i></a>
												</td>
												<td align="center"><?php echo $row[1] ?></td>
												<td align="right"><?php echo number_format($row[2], 2) ?></td>
												<td align="left"><?php echo $row[3] ?></td>
												<td align="left"><?php echo $row[4] ?></td>
												<td align="left"><?php echo $row[5] ?></td>
											</tr>
											<?php
										}
										free_result($res); 
										?>
										</tbody>
									</table>
									
									<br />
									<div class="x_title">
									  <h2>Existing Business of the Cooperative</h2>
									  <ul class="nav navbar-right panel_toolbox">
										<a href="add_business.php?cid=<?php echo $cid ?>" class="btn btn-primary btn-md"><i class="fa fa-plus-square"></i> Add </a>
									  </ul>
									  <div class="clearfix"></div>
									</div>
									<table class="table table-striped table-bordered jambo_table">
										<thead>
										  <tr class="headings">
											  <th width="10%"></th>
											  <th width="10%">Start Date</th>
											  <th width="20%">Nature of Business</th>
											  <th width="15%">Starting Capital</th>
											  <th width="15%">Capital to Date</th>
											  <th width="15%">Years Existence</th>
											  <th width="15%">Status</th>
										  </tr>
										</thead>
										<tbody>
										<?php
										$sql = "SELECT bid, DATE_FORMAT(bdate, '%m/%d/%y'), 
													bname, start_capital, capital_now, 
													CONCAT(TIMESTAMPDIFF( YEAR, bdate, NOW() ),' Years'), status
												FROM cooperatives_businesses 
												WHERE cid = '$cid'
												ORDER BY bdate DESC";
										$res = query($sql); 
										while ($row = fetch_array($res)) {
											?>
											<tr>
												<td align="center">
													<a href="e_business.php?bid=<?php echo $row[0] ?>" class="btn btn-warning btn-xs"><i class="fa fa-pencil"></i></a>
													<a href="d_business.php?bid=<?php echo $row[0] ?>" class="btn btn-danger btn-xs"><i class="fa fa-pencil"></i></a>
												</td>
												<td align="center"><?php echo $row[1] ?></td>
												<td align="left"><?php echo $row[2] ?></td>
												<td align="right"><?php echo number_format($row[3], 2) ?></td>
												<td align="right"><?php echo number_format($row[4], 2) ?></td>
												<td align="center"><?php echo $row[5] ?></td>
												<td align="center"><?php echo $row[6] == 1 ? 'Operational' : 'Stopped' ?></td>
											</tr>
											<?php
										}
										free_result($res); 
										?>
										</tbody>
									</table>
									<?php
								} elseif ($tab == 7) {
									?>
									<div class="x_title">
									  <h2>Trainings Attended By Cooperative / Members</h2>
									  <ul class="nav navbar-right panel_toolbox">
										<a href="add_coop_training.php?cid=<?php echo $cid ?>" class="btn btn-primary btn-md"><i class="fa fa-plus-square"></i> Add </a>
									  </ul>
									  <div class="clearfix"></div>
									</div>
									<table class="table table-striped table-bordered jambo_table">
										<thead>
										  <tr class="headings">
											  <th width="06%"></th>
											  <th width="19%">Training</th>
											  <th width="19%">Venue</th>
											  <th width="08%">Start Date</th>
											  <th width="08%">End Date</th>
											  <th width="13%">Trainor</th>
											  <th width="13%">Attendees</th>
											  <th width="13%">Encoder</th>
										  </tr>
										</thead>
										<tbody>
										<?php
										$sql = "SELECT ct.ctid, t.tname, ct.tvenue, DATE_FORMAT(ct.fdate, '%m/%d/%Y'), 
													DATE_FORMAT(ct.tdate, '%m/%d/%Y'), CONCAT(e.lname,', ',e.fname), 
													ct.mid, CONCAT(cm.lname,', ',cm.fname), CONCAT(u.lname,', ',u.fname), 
													DATE_FORMAT(ct.user_dtime, '%m/%d/%Y %h:%i %p')
												FROM cooperatives_trainings ct 
													INNER JOIN tc_trainings t ON ct.tid = t.tid 
													INNER JOIN employees e ON ct.eid = e.eid 
													INNER JOIN cooperatives_members cm ON ct.mid = cm.mid 
													INNER JOIN users u ON ct.user_id = u.user_id
												WHERE ct.cid = '$cid'
												ORDER BY ct.fdate DESC, t.tname, cm.lname, cm.fname";
										$res = query($sql); 
										while ($row = fetch_array($res)) {
											?>
											<tr>
												<td align="center">
													<a href="e_coop_training.php?ctid=<?php echo $row[0] ?>" class="btn btn-warning btn-xs"><i class="fa fa-pencil"></i></a>
													<a href="d_coop_training.php?ctid=<?php echo $row[0] ?>" class="btn btn-danger btn-xs"><i class="fa fa-eraser"></i></a>
												</td>
												<td align="left"><?php echo $row[1] ?></td>
												<td align="left"><?php echo $row[2] ?></td>
												<td align="center"><?php echo $row[3] ?></td>
												<td align="center"><?php echo $row[4] ?></td>
												<td align="left"><?php echo $row[5] ?></td>
												<td align="left"><a href="member.php?mid=<?php echo $row[6] ?>"><?php echo $row[7] ?></a></td>
												<td align="left"><?php echo $row[8].'<br />'.$row[9] ?></td>
											</tr>
											<?php
										}
										free_result($res);
										?>
										</tbody>
									</table>
									<?php
								} elseif ($tab == 8) {
									if (isset ($_GET['gmonth'])) {
										$gmonth = $_GET['gmonth']; 
									} else {
										$sql = "SELECT MONTH(CURDATE())";
										$res = query($sql); 
										$row = fetch_array($res); 
										$gmonth = $row[0]; 
									}
									if (isset ($_GET['gyear'])) {
										$gyear = $_GET['gyear']; 
									} else {
										$sql = "SELECT YEAR(CURDATE())";
										$res = query($sql); 
										$row = fetch_array($res); 
										$gyear = $row[0]; 
									}
									
									// get the current year
									$sql = "SELECT YEAR(CURDATE())";
									$res = query($sql); 
									$row = fetch_array($res); 
									$cyear = $row[0]; 
									
									?>
									<table width="100%">
										<tr>
											<td width="50%" align="left"><h2>Users' Logs - (THIS FEATURE IS UNDER DEVELOPMENT)</h2>
											</td>
											<td width="50%" align="right">
												<select onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);" style="font-family:arial;text-shadow: 1px 1px #999;font-size:18px;line-height:1.1em;color:#333;">
													<?php
													if ($gmonth == 1) {
														?>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=1" Selected>January</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=2">February</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=3">March</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=4">April</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=5">May</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=6">June</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=7">July</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=8">August</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=9">September</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=10">October</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=11">November</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=12">December</option>
														<?php
													} elseif ($gmonth == 2) {
														?>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=1">January</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=2" Selected>February</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=3">March</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=4">April</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=5">May</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=6">June</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=7">July</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=8">August</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=9">September</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=10">October</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=11">November</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=12">December</option>
														<?php
													} elseif ($gmonth == 3) {
														?>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=1">January</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=2">February</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=3" Selected>March</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=4">April</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=5">May</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=6">June</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=7">July</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=8">August</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=9">September</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=10">October</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=11">November</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=12">December</option>
														<?php
													} elseif ($gmonth == 4) {
														?>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=1">January</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=2">February</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=3">March</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=4" Selected>April</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=5">May</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=6">June</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=7">July</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=8">August</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=9">September</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=10">October</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=11">November</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=12">December</option>
														<?php
													} elseif ($gmonth == 5) {
														?>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=1">January</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=2">February</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=3">March</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=4">April</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=5" Selected>May</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=6">June</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=7">July</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=8">August</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=9">September</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=10">October</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=11">November</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=12">December</option>
														<?php
													} elseif ($gmonth == 6) {
														?>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=1">January</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=2">February</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=3">March</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=4">April</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=5">May</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=6" Selected>June</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=7">July</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=8">August</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=9">September</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=10">October</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=11">November</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=12">December</option>
														<?php
													} elseif ($gmonth == 7) {
														?>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=1">January</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=2">February</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=3">March</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=4">April</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=5">May</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=6">June</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=7" Selected>July</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=8">August</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=9">September</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=10">October</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=11">November</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=12">December</option>
														<?php
													} elseif ($gmonth == 8) {
														?>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=1">January</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=2">February</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=3">March</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=4">April</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=5">May</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=6">June</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=7">July</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=8" Selected>August</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=9">September</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=10">October</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=11">November</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=12">December</option>
														<?php
													} elseif ($gmonth == 9) {
														?>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=1">January</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=2">February</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=3">March</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=4">April</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=5">May</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=6">June</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=7">July</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=8">August</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=9" Selected>September</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=10">October</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=11">November</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=12">December</option>
														<?php
													} elseif ($gmonth == 10) {
														?>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=1">January</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=2">February</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=3">March</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=4">April</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=5">May</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=6">June</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=7">July</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=8">August</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=9">September</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=10" Selected>October</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=11">November</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=12">December</option>
														<?php
													} elseif ($gmonth == 11) {
														?>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=1">January</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=2">February</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=3">March</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=4">April</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=5">May</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=6">June</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=7">July</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=8">August</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=9">September</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=10">October</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=11" Selected>November</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=12">December</option>
														<?php
													} elseif ($gmonth == 12) {
														?>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=1">January</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=2">February</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=3">March</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=4">April</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=5">May</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=6">June</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=7">July</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=8">August</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=9">September</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=10">October</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=11">November</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=12" Selected>December</option>
														<?php
													} else {
														?>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=1">January</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=2">February</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=3">March</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=4">April</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=5">May</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=6">June</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=7">July</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=8">August</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=9">September</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=10">October</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=11">November</option>
														<option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $gyear ?>&gmonth=12">December</option>
														<?php
													}
													?>
												</select>&nbsp; 
												<select onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);" style="font-family:arial;text-shadow: 1px 1px #999;font-size:18px;line-height:1.1em;color:#333;">
													<?php
													for ($i=$cyear; $i>=2018; $i--) {
														if ($gyear == $i) {
															?><option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $i ?>&gmonth=<?php echo $gmonth ?>" Selected><?PHP echo $i ?></option><?PHP
														} else {
															?><option value="tc.php?cid=<?php echo $cid ?>&tab=8&gyear=<?php echo $i ?>&gmonth=<?php echo $gmonth ?>"><?PHP echo $i ?></option><?PHP
														}
													}
													?>
												</select>
											</td>
										</tr>
									</table><br /><br />
									<table class="table table-striped table-bordered jambo_table">
									  <thead>
										<tr>
										  <th width="15%">Log Date/Time</th>
										  <th width="15%">Username</th>
										  <th width="10%">Action Type</th>
										  <th width="30%">Database Name</th>
										  <th width="30%">Details</th>
										</tr>
									  </thead>
									  <tbody>
									    <?php
										if (strlen($gmonth) < 2) $gmonth = '0'.$gmonth;
										$tbl_name = 'logs_'.$gyear.$gmonth;
										$sql = "SELECT l.log_dtime, CONCAT(u.lname,', ',u.fname), l.log_type, l.table_name, l.pkid
												FROM ".$tbl_name." l INNER JOIN users u ON l.user_id = u.user_id
												WHERE l.pkid = '$cid' AND l.table_name IN (
													'cooperatives', 
													'cooperatives_members2', 
													'cooperatives_members', 
													'cooperatives_units', 
													'cooperatives_units2', 
													'cooperatives_cgs', 
													'cooperatives_governances', 
													'cooperatives_memberships',
													'franchises',
													'cooperatives_financials',
													'cooperatives_surplus',
													'cooperatives_capitalizations',
													'cooperatives_loans',
													'cooperatives_businesses',
													'employees_trainings'
													)
												ORDER BY l.log_dtime DESC";
										$res = query($sql); 
										while ($row = fetch_array($res)) {
											?>
											<tr>
												<td align="left"><?php echo $row[0] ?></td>
												<td align="left"><?php echo $row[1] ?></td>
												<td align="left"><?php 
													if ($row[2] == 1) { echo 'Add';
													} elseif ($row[2] == 2) { echo 'Update';
													} elseif ($row[2] == 3) { echo 'Delete';
													} elseif ($row[2] == 4) { echo 'Verify / Confirm';
													} elseif ($row[2] == 5) { echo 'View';
													} elseif ($row[2] == 6) { echo 'Print';
													}
													?></td>
												<td align="left"><?php echo $row[3] ?></td>
												<td align="left"><?php echo $row[4] ?></td>
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


