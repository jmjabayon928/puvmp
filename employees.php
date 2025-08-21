<?php

include("db.php");
include("sqli.php");
include("html.php");
define('PAGE', 'employees.php'); 

session_start();

if (isset($_SESSION['user_id'])) {
	if (validate_level3(PAGE) == true) {

		// log the activity
		log_user(5, 'employees', 0);
		
		?>
		<!DOCTYPE html>
		<html lang="en">
		  <head>
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
			<!-- Meta, title, CSS, favicons, etc. -->
			<meta charset="utf-8">
			<meta http-equiv="X-UA-Compatible" content="IE=edge">
			<meta name="viewport" content="width=device-width, initial-scale=1">

			<title>OTC - Employees</title>

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
				
				if (isset($_GET['tab'])) {
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
						<h3>Employees</h3>
					  </div>

					  <div class="title_right">
						<div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
						  <select class="form-control col-md-7 col-xs-12" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
							<?php
							if ($tab == 1) {
								?>
								<option value="employees.php?tab=1" Selected>Masterlist of Employees</option>
								<option value="employees.php?tab=2">Employees By Division</option>
								<option value="employees.php?tab=3">Employees By Employment Status</option>
								<?php
							} elseif ($tab == 2) {
								?>
								<option value="employees.php?tab=1">Masterlist of Employees</option>
								<option value="employees.php?tab=2" Selected>Employees By Division</option>
								<option value="employees.php?tab=3">Employees By Employment Status</option>
								<?php
							} elseif ($tab == 3) {
								?>
								<option value="employees.php?tab=1">Masterlist of Employees</option>
								<option value="employees.php?tab=2">Employees By Division</option>
								<option value="employees.php?tab=3" Selected>Employees By Employment Status</option>
								<?php
							} else {
								?>
								<option value="employees.php?tab=1">Masterlist of Employees</option>
								<option value="employees.php?tab=2">Employees By Division</option>
								<option value="employees.php?tab=3">Employees By Employment Status</option>
								<?php
							}
							?>
						  </select>
						</div>
					  </div>
					</div>

					<div class="clearfix"></div>

					<div class="row">
					
					  <?php
					  if (isset($_GET['success'])) {
						  if ($_GET['success'] == 1) {
							  ?>
								<div class="alert alert-success">
								  <strong>Success!</strong> Employee has been added into the database.
								</div>
							  <?php
						  }
					  }
					  
					  ?>

					  <div class="col-md-12 col-sm-12 col-xs-12">
						<div class="x_panel">
						  <div class="x_title">
							<?php
							if ($tab == 1) { ?><h2>Masterlist of Employees</h2><?php
							} elseif ($tab == 2) { ?><h2>Employees By Division</h2><?php
							} elseif ($tab == 3) { ?><h2>Employees By Employment Status</h2><?php
							}
							?>
							
							<ul class="nav navbar-right panel_toolbox">
								<a href="add_employee.php" class="btn btn-primary btn-md"><i class="fa fa-plus-square"></i> Add Employee </a>
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
										  <th width="10%">Code</th>
										  <th width="18%">Name</th>
										  <th width="19%">Position</th>
										  <th width="19%">Department</th>
										  <th width="05%">S.G.</th>
										  <th width="05%">Level</th>
										  <th width="10%">Status</th>
										  <th width="07%">Start</th>
										  <th width="07%">End</th>
										</tr>
									  </thead>
									  <tbody>
										<?php
										$sql = "SELECT e.eid, CONCAT(e.lname,', ',e.fname,', ',e.mname),
													p.pname, d.dname, g.sg_num, e.sglevel, e.etype, 
													DATE_FORMAT(e.estart, '%m/%d/%y'), DATE_FORMAT(e.eend, '%m/%d/%y'), e.ecode
												FROM employees e 
													INNER JOIN departments d ON e.did = d.did
													INNER JOIN positions p ON e.pid = p.pid
													LEFT JOIN salary_grades g ON e.sgid = g.sgid
												ORDER BY e.lname, e.fname";
										$res = query($sql);

										while ($row = fetch_array($res)) {
											?>
											<tr>
											  <td align="center"><?php echo $row[9] ?></td>
											  <td align="left"><a href="employee.php?eid=<?php echo $row[0] ?>"><?php echo $row[1] ?></a></td>
											  <td align="left"><?php echo $row[2] ?></td>
											  <td align="left"><?php echo $row[3] ?></td>
											  <td align="center"><?php echo $row[4] ?></td>
											  <td align="center"><?php echo $row[5] == '0' ? '' : $row[5] ?></td>
											  <td><?php 
												if ($row[6] == 1) {
													echo 'Appointee';
												} elseif ($row[6] == 2) {
													echo 'Permanent';
												} elseif ($row[6] == 3) {
													echo 'Job Order';
												} elseif ($row[6] == 4) {
													echo 'COS';
												}
												?></td>
											  <td align="center"><?php echo $row[7] != '00/00/00' ? $row[7] : ''?></td>
											  <td align="center"><?php echo $row[8] != '00/00/00' ? $row[8] : ''?></td>
											</tr>
											<?php
										}

										mysqli_free_result($res);
										?>
									  </tbody>
									</table>
								<?php
							} elseif ($tab == 2) {
								?>
								<div class="" role="tabpanel" data-example-id="togglable-tabs">
								  <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
									<li role="presentation" class="active"><a href="#tab_content0" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true">All Divisions</a></li>
									<?php
									$sql = "SELECT did, dcode FROM departments";
									$dres = query($sql); 
									while ($drow = fetch_array($dres)) {
										?><li role="presentation" class=""><a href="#tab_content<?php echo $drow[0] ?>" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false"><?php echo $drow[1] ?></a></li><?php
									}
									free_result($dres); 
									?>
								  </ul>
								  
								  <div id="myTabContent" class="tab-content">
									<div role="tabpanel" class="tab-pane fade active in" id="tab_content0" aria-labelledby="home-tab">
										<table class="table table-bordered jambo_table">
										  <thead>
											<tr>
											  <th width="18%"></th>
											  <th width="32%">Name</th>
											  <th width="25%">Employment Details</th>
											  <th width="25%">Employment Dates</th>
											</tr>
										  </thead>
										  <tbody>
											<?php
											$sql = "SELECT e.eid, e.picture, CONCAT(e.lname,', ',e.fname,', ',e.mname),
														p.pname, d.dname, g.sg_num, e.sglevel, e.etype, 
														DATE_FORMAT(e.estart, '%m/%d/%Y'), DATE_FORMAT(e.eend, '%m/%d/%Y'), e.ecode
													FROM employees e 
														INNER JOIN departments d ON e.did = d.did
														INNER JOIN positions p ON e.pid = p.pid
														LEFT JOIN salary_grades g ON e.sgid = g.sgid
													WHERE e.active = 1
													ORDER BY e.lname, e.fname";
											$res = query($sql);

											while ($row = fetch_array($res)) {
												?>
												<tr>
												  <td align="center"><a href="employee.php?eid=<?php echo $row[0] ?>"><img src="<?php echo $row[1] ?>" width="75%" height="75%" class="img-thumbnail"></a></td>
												  <td align="left"><?php echo 'Employee ID: <b>'.$row[10].'</b><br /><b>'.$row[2].'</b><br />'.$row[3].'<br />'.$row[4] ?></td>
												  <td><?php echo 'Salary Grade : '.$row[5].'<br />Step Level : '.$row[6].'<br />Status : '; 
													if ($row[7] == 1) {
														echo 'Appointee';
													} elseif ($row[7] == 2) {
														echo 'Permanent';
													} elseif ($row[7] == 3) {
														echo 'Job Order';
													} elseif ($row[7] == 4) {
														echo 'COS';
													}
													?></td>
												  <td><?php echo 'Date Started : ';
														echo $row[8] != '00/00/00' ? $row[8] : ''; 
														echo '<br />End Date : ';
														echo $row[9] != '00/00/00' ? $row[9] : ''?></td>
												</tr>
												<?php
											}

											mysqli_free_result($res);
											?>
										  </tbody>
										</table>
									</div>
									
									<?php
									$sql = "SELECT did, dcode FROM departments";
									$dres = query($sql); 
									while ($drow = fetch_array($dres)) {
										?>
										<div role="tabpanel" class="tab-pane fade" id="tab_content<?php echo $drow[0] ?>" aria-labelledby="profile-tab">
											<table class="table table-bordered jambo_table">
											  <thead>
												<tr>
												  <th width="18%"></th>
												  <th width="32%">Name</th>
												  <th width="25%">Employment Details</th>
												  <th width="25%">Employment Dates</th>
												</tr>
											  </thead>
											  <tbody>
												<?php
												$sql = "SELECT e.eid, e.picture, CONCAT(e.lname,', ',e.fname,', ',e.mname),
															p.pname, d.dname, g.sg_num, e.sglevel, e.etype, 
															DATE_FORMAT(e.estart, '%m/%d/%Y'), DATE_FORMAT(e.eend, '%m/%d/%Y'), e.ecode
														FROM employees e 
															INNER JOIN departments d ON e.did = d.did
															INNER JOIN positions p ON e.pid = p.pid
															LEFT JOIN salary_grades g ON e.sgid = g.sgid
														WHERE e.did = '$drow[0]'
														ORDER BY e.lname, e.fname";
												$res = query($sql);

												while ($row = fetch_array($res)) {
													?>
													<tr>
													  <td align="center"><a href="employee.php?eid=<?php echo $row[0] ?>"><img src="<?php echo $row[1] ?>" width="75%" height="75%" class="img-thumbnail"></a></td>
													  <td align="left"><?php echo 'Employee ID: <b>'.$row[10].'</b><br /><b>'.$row[2].'</b><br />'.$row[3].'<br />'.$row[4] ?></td>
													  <td><?php echo 'Salary Grade : '.$row[5].'<br />Step Level : '.$row[6].'<br />Status : '; 
														if ($row[7] == 1) {
															echo 'Appointee';
														} elseif ($row[7] == 2) {
															echo 'Permanent';
														} elseif ($row[7] == 3) {
															echo 'Job Order';
														} elseif ($row[7] == 4) {
															echo 'COS';
														}
														?></td>
													  <td><?php echo 'Date Started : ';
															echo $row[8] != '00/00/00' ? $row[8] : ''; 
															echo '<br />End Date : ';
															echo $row[9] != '00/00/00' ? $row[9] : ''?></td>
													</tr>
													<?php
												}

												mysqli_free_result($res);
												?>
											  </tbody>
											</table>
										</div>
										<?php
									}
									free_result($dres); 
									?>
								  </div>
								</div>
								<?php
							} elseif ($tab == 3) {
								?>
								<div class="" role="tabpanel" data-example-id="togglable-tabs">
								  <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
								    <?php
									for ($i=1; $i<5; $i++) {
										if ($i == 1) {
											?><li role="presentation" class="active"><a href="#tab_content1" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true">Appointees</a></li><?php
										} else {
											if ($i == 2) { $label = 'Permanent';
											} elseif ($i == 3) { $label = 'Job Order';
											} elseif ($i == 4) { $label = 'Contract of Service';
											}
											?><li role="presentation" class=""><a href="#tab_content<?php echo $i ?>" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false"><?php echo $label ?></a></li><?php
										}
									}
									?>
								  </ul>
								  <div id="myTabContent" class="tab-content">
									<div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="home-tab">
										<table class="table table-bordered jambo_table">
										  <thead>
											<tr>
											  <th width="18%"></th>
											  <th width="32%">Name</th>
											  <th width="25%">Employment Details</th>
											  <th width="25%">Employment Dates</th>
											</tr>
										  </thead>
										  <tbody>
											<?php
											$sql = "SELECT e.eid, e.picture, CONCAT(e.lname,', ',e.fname,', ',e.mname),
														p.pname, d.dname, g.sg_num, e.sglevel, e.etype, 
														DATE_FORMAT(e.estart, '%m/%d/%Y'), DATE_FORMAT(e.eend, '%m/%d/%Y'), e.ecode
													FROM employees e 
														INNER JOIN departments d ON e.did = d.did
														INNER JOIN positions p ON e.pid = p.pid
														LEFT JOIN salary_grades g ON e.sgid = g.sgid
													WHERE e.etype = 1 AND e.active = 1
													ORDER BY e.lname, e.fname";
											$res = query($sql);

											while ($row = fetch_array($res)) {
												?>
												<tr>
												  <td align="center"><a href="employee.php?eid=<?php echo $row[0] ?>"><img src="<?php echo $row[1] ?>" width="75%" height="75%" class="img-thumbnail"></a></td>
												  <td align="left"><?php echo 'Employee ID: <b>'.$row[10].'</b><br /><b>'.$row[2].'</b><br />'.$row[3].'<br />'.$row[4] ?></td>
												  <td><?php echo 'Salary Grade : '.$row[5].'<br />Step Level : '.$row[6].'<br />Status : '; 
													if ($row[7] == 1) {
														echo 'Appointee';
													} elseif ($row[7] == 2) {
														echo 'Permanent';
													} elseif ($row[7] == 3) {
														echo 'Job Order';
													} elseif ($row[7] == 4) {
														echo 'COS';
													}
													?></td>
												  <td><?php echo 'Date Started : ';
														echo $row[8] != '00/00/00' ? $row[8] : ''; 
														echo '<br />End Date : ';
														echo $row[9] != '00/00/00' ? $row[9] : ''?></td>
												</tr>
												<?php
											}

											mysqli_free_result($res);
											?>
										  </tbody>
										</table>
									</div>
									<div role="tabpanel" class="tab-pane fade" id="tab_content2" aria-labelledby="profile-tab">
										<table class="table table-bordered jambo_table">
										  <thead>
											<tr>
											  <th width="18%"></th>
											  <th width="32%">Name</th>
											  <th width="25%">Employment Details</th>
											  <th width="25%">Employment Dates</th>
											</tr>
										  </thead>
										  <tbody>
											<?php
											$sql = "SELECT e.eid, e.picture, CONCAT(e.lname,', ',e.fname,', ',e.mname),
														p.pname, d.dname, g.sg_num, e.sglevel, e.etype, 
														DATE_FORMAT(e.estart, '%m/%d/%Y'), DATE_FORMAT(e.eend, '%m/%d/%Y'), e.ecode
													FROM employees e 
														INNER JOIN departments d ON e.did = d.did
														INNER JOIN positions p ON e.pid = p.pid
														LEFT JOIN salary_grades g ON e.sgid = g.sgid
													WHERE e.etype = 2 AND e.active = 1
													ORDER BY e.lname, e.fname";
											$res = query($sql);

											while ($row = fetch_array($res)) {
												?>
												<tr>
												  <td align="center"><a href="employee.php?eid=<?php echo $row[0] ?>"><img src="<?php echo $row[1] ?>" width="75%" height="75%" class="img-thumbnail"></a></td>
												  <td align="left"><?php echo 'Employee ID: <b>'.$row[10].'</b><br /><b>'.$row[2].'</b><br />'.$row[3].'<br />'.$row[4] ?></td>
												  <td><?php echo 'Salary Grade : '.$row[5].'<br />Step Level : '.$row[6].'<br />Status : '; 
													if ($row[7] == 1) {
														echo 'Appointee';
													} elseif ($row[7] == 2) {
														echo 'Permanent';
													} elseif ($row[7] == 3) {
														echo 'Job Order';
													} elseif ($row[7] == 4) {
														echo 'COS';
													}
													?></td>
												  <td><?php echo 'Date Started : ';
														echo $row[8] != '00/00/00' ? $row[8] : ''; 
														echo '<br />End Date : ';
														echo $row[9] != '00/00/00' ? $row[9] : ''?></td>
												</tr>
												<?php
											}

											mysqli_free_result($res);
											?>
										  </tbody>
										</table>
									</div>
									<div role="tabpanel" class="tab-pane fade" id="tab_content3" aria-labelledby="profile-tab">
										<table class="table table-bordered jambo_table">
										  <thead>
											<tr>
											  <th width="18%"></th>
											  <th width="32%">Name</th>
											  <th width="25%">Employment Details</th>
											  <th width="25%">Employment Dates</th>
											</tr>
										  </thead>
										  <tbody>
											<?php
											$sql = "SELECT e.eid, e.picture, CONCAT(e.lname,', ',e.fname,', ',e.mname),
														p.pname, d.dname, g.sg_num, e.sglevel, e.etype, 
														DATE_FORMAT(e.estart, '%m/%d/%Y'), DATE_FORMAT(e.eend, '%m/%d/%Y'), e.ecode
													FROM employees e 
														INNER JOIN departments d ON e.did = d.did
														INNER JOIN positions p ON e.pid = p.pid
														LEFT JOIN salary_grades g ON e.sgid = g.sgid
													WHERE e.etype = 3 AND e.active = 1
													ORDER BY e.lname, e.fname";
											$res = query($sql);

											while ($row = fetch_array($res)) {
												?>
												<tr>
												  <td align="center"><a href="employee.php?eid=<?php echo $row[0] ?>"><img src="<?php echo $row[1] ?>" width="75%" height="75%" class="img-thumbnail"></a></td>
												  <td align="left"><?php echo 'Employee ID: <b>'.$row[10].'</b><br /><b>'.$row[2].'</b><br />'.$row[3].'<br />'.$row[4] ?></td>
												  <td><?php echo 'Salary Grade : '.$row[5].'<br />Step Level : '.$row[6].'<br />Status : '; 
													if ($row[7] == 1) {
														echo 'Appointee';
													} elseif ($row[7] == 2) {
														echo 'Permanent';
													} elseif ($row[7] == 3) {
														echo 'Job Order';
													} elseif ($row[7] == 4) {
														echo 'COS';
													}
													?></td>
												  <td><?php echo 'Date Started : ';
														echo $row[8] != '00/00/00' ? $row[8] : ''; 
														echo '<br />End Date : ';
														echo $row[9] != '00/00/00' ? $row[9] : ''?></td>
												</tr>
												<?php
											}

											mysqli_free_result($res);
											?>
										  </tbody>
										</table>
									</div>
									<div role="tabpanel" class="tab-pane fade" id="tab_content4" aria-labelledby="profile-tab">
										<table class="table table-bordered jambo_table">
										  <thead>
											<tr>
											  <th width="18%"></th>
											  <th width="32%">Name</th>
											  <th width="25%">Employment Details</th>
											  <th width="25%">Employment Dates</th>
											</tr>
										  </thead>
										  <tbody>
											<?php
											$sql = "SELECT e.eid, e.picture, CONCAT(e.lname,', ',e.fname,', ',e.mname),
														p.pname, d.dname, g.sg_num, e.sglevel, e.etype, 
														DATE_FORMAT(e.estart, '%m/%d/%Y'), DATE_FORMAT(e.eend, '%m/%d/%Y'), e.ecode
													FROM employees e 
														INNER JOIN departments d ON e.did = d.did
														INNER JOIN positions p ON e.pid = p.pid
														LEFT JOIN salary_grades g ON e.sgid = g.sgid
													WHERE e.etype = 4 AND e.active = 1
													ORDER BY e.lname, e.fname";
											$res = query($sql);

											while ($row = fetch_array($res)) {
												?>
												<tr>
												  <td align="center"><a href="employee.php?eid=<?php echo $row[0] ?>"><img src="<?php echo $row[1] ?>" width="75%" height="75%" class="img-thumbnail"></a></td>
												  <td align="left"><?php echo 'Employee ID: <b>'.$row[10].'</b><br /><b>'.$row[2].'</b><br />'.$row[3].'<br />'.$row[4] ?></td>
												  <td><?php echo 'Salary Grade : '.$row[5].'<br />Step Level : '.$row[6].'<br />Status : '; 
													if ($row[7] == 1) {
														echo 'Appointee';
													} elseif ($row[7] == 2) {
														echo 'Permanent';
													} elseif ($row[7] == 3) {
														echo 'Job Order';
													} elseif ($row[7] == 4) {
														echo 'COS';
													}
													?></td>
												  <td><?php echo 'Date Started : ';
														echo $row[8] != '00/00/00' ? $row[8] : ''; 
														echo '<br />End Date : ';
														echo $row[9] != '00/00/00' ? $row[9] : ''?></td>
												</tr>
												<?php
											}

											mysqli_free_result($res);
											?>
										  </tbody>
										</table>
									</div>
								  </div>
								</div>
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
	} else {
	
		// check if user is a valid employee
		$sql = "SELECT eid FROM users WHERE user_id = '$_SESSION[user_id]' AND active = 1";
		$vres = query($sql); $vrow = fetch_array($vres); $eid = $vrow[0]; 
		
		if ($eid != 0) {

			// log the activity
			log_user(5, 'employees', 0);
			
			?>
			<!DOCTYPE html>
			<html lang="en">
			  <head>
				<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
				<!-- Meta, title, CSS, favicons, etc. -->
				<meta charset="utf-8">
				<meta http-equiv="X-UA-Compatible" content="IE=edge">
				<meta name="viewport" content="width=device-width, initial-scale=1">

				<title>OTC - Employees</title>

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
							<h3>Employees</h3>
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
								<h2>Masterlist of Employees</h2>
								<div class="clearfix"></div>
							  </div>
							  <div class="x_content">
							  
								<table id="datatable-fixed-header" class="table table-striped table-bordered jambo_table">
								  <thead>
									<tr>
									  <th width="05%">ID</th>
									  <th width="21%">Name</th>
									  <th width="20%">Position</th>
									  <th width="20%">Department</th>
									  <th width="05%">S.G.</th>
									  <th width="05%">Level</th>
									  <th width="10%">Status</th>
									  <th width="07%">Start</th>
									  <th width="07%">End</th>
									</tr>
								  </thead>
								  <tbody>
									<?php
									$sql = "SELECT e.eid, CONCAT(e.lname,', ',e.fname,', ',e.mname),
												p.pname, d.dname, g.sg_num, e.sglevel, e.etype, 
												DATE_FORMAT(e.estart, '%m/%d/%y'), DATE_FORMAT(e.eend, '%m/%d/%y')
											FROM employees e 
												INNER JOIN departments d ON e.did = d.did
												INNER JOIN positions p ON e.pid = p.pid
												LEFT JOIN salary_grades g ON e.sgid = g.sgid
											WHERE e.eid = '$eid'
											ORDER BY e.lname, e.fname";
									$res = query($sql);

									while ($row = fetch_array($res)) {
										?>
										<tr>
										  <td align="center"><?php echo $row[0] ?></td>
										  <td align="left"><a href="employee.php?eid=<?php echo $row[0] ?>"><?php echo $row[1] ?></a></td>
										  <td align="left"><?php echo $row[2] ?></td>
										  <td align="left"><?php echo $row[3] ?></td>
										  <td align="center"><?php echo $row[4] ?></td>
										  <td align="center"><?php echo $row[5] == '0' ? '' : $row[5] ?></td>
										  <td><?php 
											if ($row[6] == 1) {
												echo 'Appointee';
											} elseif ($row[6] == 2) {
												echo 'Permanent';
											} elseif ($row[6] == 3) {
												echo 'Job Order';
											} elseif ($row[6] == 4) {
												echo 'COS';
											}
											?></td>
										  <td align="center"><?php echo $row[7] != '00/00/00' ? $row[7] : ''?></td>
										  <td align="center"><?php echo $row[8] != '00/00/00' ? $row[8] : ''?></td>
										</tr>
										<?php
									}

									mysqli_free_result($res);
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
		} else {
			?>
			<!DOCTYPE html>
			<html lang="en">
			  <head>
				<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
				<!-- Meta, title, CSS, favicons, etc. -->
				<meta charset="utf-8">
				<meta http-equiv="X-UA-Compatible" content="IE=edge">
				<meta name="viewport" content="width=device-width, initial-scale=1">

				<title>OTC - Delete Leave Applications</title>

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
