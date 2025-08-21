<?php

include("db.php");
include("sqli.php");
include("html.php");
define('PAGE', 'employee.php'); 

$eid = $_GET['eid'];

session_start();

if (isset($_SESSION['user_id'])) {
	if (validate_level3(PAGE) == true) {

		// log the activity
		log_user(5, 'employees', $eid);
		
		?>
		<!DOCTYPE html>
		<html lang="en">
		  <head>
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
			<!-- Meta, title, CSS, favicons, etc. -->
			<meta charset="utf-8">
			<meta http-equiv="X-UA-Compatible" content="IE=edge">
			<meta name="viewport" content="width=device-width, initial-scale=1">
			  
			<title>OTC | Employee Details</title>

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
						<h3>Employee Details</h3>
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
					
					<div class=""> <!-- container of all tables -->
					
					  <div class="col-md-12 col-sm-12 col-xs-12">
						<div class="x_panel">
						  <div class="x_title">
							<h2>Employee Information</h2>
							<ul class="nav navbar-right panel_toolbox">
							  <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
							  <li><a class="close-link"><i class="fa fa-close"></i></a></li>
							</ul>
							<div class="clearfix"></div>
						  </div>
						  <div class="x_content">
						  
							<div class="" role="tabpanel" data-example-id="togglable-tabs">
							  <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
								<li role="presentation" class="active"><a href="#tab_content1" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true">Employment</a></li>
								<li role="presentation" class=""><a href="#tab_content2" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false">Basic Information</a></li>
								<li role="presentation" class=""><a href="#tab_content3" role="tab" id="profile-tab2" data-toggle="tab" aria-expanded="false">Work Experience</a></li>
								<li role="presentation" class=""><a href="#tab_content4" role="tab" id="profile-tab3" data-toggle="tab" aria-expanded="false">Voluntary Work</a></li>
								<li role="presentation" class=""><a href="#tab_content5" role="tab" id="profile-tab4" data-toggle="tab" aria-expanded="false">Issued Properties</a></li>
							  </ul>
							  
							  
							  <div id="myTabContent" class="tab-content">
								<!-- employment details -->
								<div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="home-tab">
									<?php
									$sql = "SELECT e.picture, e.ecode, CONCAT(e.lname,', ',e.fname,', ',e.mname),
												p.pname, d.dname, g.sg_num, e.sglevel, e.etype, 
												DATE_FORMAT(e.estart, '%m/%d/%Y'), DATE_FORMAT(e.eend, '%m/%d/%Y'), 
												e.time_in, e.time_out, e.oid
											FROM employees e 
												INNER JOIN departments d ON e.did = d.did
												INNER JOIN positions p ON e.pid = p.pid
												LEFT JOIN salary_grades g ON e.sgid = g.sgid
											WHERE e.eid = '$eid'";
									$res = query($sql); 
									$row = fetch_array($res); 
									free_result($res); 
									
									?>
									<ul class="nav navbar-right panel_toolbox">
										<a href="e_employee.php?eid=<?php echo $eid ?>" class="btn btn-warning btn-md"><i class="fa fa-pencil"></i> Edit </a>
									</ul>
									<table class="table table-striped table-bordered jambo_table">
									  <thead>
										<tr>
										  <th colspan="5">Employment Details</th>
										</tr>
									  </thead>
									  <tbody>
										<tr>
											<td rowspan="5" align="center" width="18%">
												<img src="<?php echo $row[0] ?>" class="img-thumbnail">
												<?php
												if ($row[0] == '') {
													?><a href="add_epicture.php?eid=<?php echo $eid ?>" class="btn btn-primary btn-xs"><i class="fa fa-plus-square"></i> Add Picture </a><?php
												} else {
													?><a href="d_epicture.php?eid=<?php echo $eid ?>" class="btn btn-danger btn-xs"><i class="fa fa-eraser"></i> Delete Picture </a><?php
												}
												?>												
											</td>
											<td align="left" width="15%">Employee #</td>
											<td align="left" width="22%"><b><?php echo $row[1] ?></b></td>
											<td align="left" width="15%">Position</td>
											<td align="left" width="30%"><b><?php echo $row[3] ?></b></td>
										</tr>
										<tr>
											<td align="left">Name</td>
											<td align="left"><b><?php echo $row[2] ?></b></td>
											<td align="left">Department</td>
											<td align="left"><b><?php echo $row[4] ?></b></td>
										</tr>
										<tr>
											<td align="left">Work Hours</td>
											<td align="left"><b><?php echo $row[10].' - '.$row[11] ?></b></td>
											<td align="left">Employment Status</td>
											<td align="left"><b><?php
												if ($row[7] == 1) {
													echo 'Appointee';
												} elseif ($row[7] == 2) {
													echo 'Permanent';
												} elseif ($row[7] == 3) {
													echo 'Job Order';
												} elseif ($row[7] == 4) {
													echo 'COS';
												}
												?></b></td>
										</tr>
										<tr>
											<td align="left">Employment Date</td>
											<td align="left"><b><?php echo $row[8] != '00/00/0000' ? $row[8] : '' ?> - <?php echo $row[9] != '00/00/0000' ? $row[9] : 'Present' ?></b></td>
											<td align="left">Salary Grade</td>
											<td align="left"><b><?php echo $row[5] ?></b></td>
										</tr>
										<tr>
											<td align="left">
											<?php
											if ($row[12] != 0) { echo 'PMO Assignment';
											}
											?>
											</td>
											<td align="left">
											<?php
											if ($row[12] != 0) {
												$sql = "SELECT oname FROM pmo_offices WHERE oid = '$row[12]'";
												$pm_res = query($sql); 
												$pm_row = fetch_array($pm_res); 
												echo '<b>'.$pm_row[0].'</b>';
											}
											?>
											</td>
											<td align="left">Step Increment</td>
											<td align="left"><b><?php echo $row[6] ?></b></td>
										</tr>
									  </tbody>
									</table>
								</div>
								<!-- employment details -->
								
								
								<!-- personal information, family background, educational background -->
								<div role="tabpanel" class="tab-pane fade" id="tab_content2" aria-labelledby="profile-tab">
									<ul class="nav navbar-right panel_toolbox">
									<?php
									$sql = "SELECT e.lname, e.fname, e.mname, e.ename, 
												DATE_FORMAT(p.bdate, '%m/%d/%Y'), p.citizenship,
												p.bplace, p.citizenship_means, p.sex, p.country_dual,
												p.civil_stat, p.res_addr, p.height, p.weight, 
												p.perm_addr, p.blood_type, p.gsis_id, p.tel_no,
												p.pagibig, p.cp_no, p.philhealth, p.email, 
												p.sss_id, p.agency_num, p.tin, p.crn
											FROM employees_pds p 
												INNER JOIN employees e ON p.eid = e.eid
											WHERE e.eid = '$eid'";
									$pds_res = query($sql); 
									$pds_num = num_rows($pds_res);
									$pds_row = fetch_array($pds_res); 
								  
									if ($pds_num > 0) {
										?><a href="e_pds.php?eid=<?php echo $eid ?>" class="btn btn-warning btn-md"><i class="fa fa-pencil"></i> Edit </a><?php
									} else {
										?><a href="add_pds.php?eid=<?php echo $eid ?>" class="btn btn-primary btn-md"><i class="fa fa-plus-square"></i> Add </a><?php
									}
									free_result($pds_res); 
									?>
									</ul>
									<table class="table table-striped table-bordered jambo_table">
									  <thead>
										<tr>
										  <th colspan="4">
											Personal Information
											<ul class="nav navbar-right panel_toolbox">
											</ul>
										  </th>
										</tr>
									  </thead>
									  <tbody>
										<tr>
											<td width="15%" align="left">Last Name</td>
											<td width="35%" align="left"><b><?php echo $pds_num > 0 ? $pds_row[0] : '' ?></b></td>
											<td width="15%" align="left">First Name</td>
											<td width="35%" align="left"><b><?php echo $pds_num > 0 ? $pds_row[1] : '' ?></b></td>
										</tr>
										<tr>
											<td align="left">Middle Name</td>
											<td align="left"><b><?php echo $pds_num > 0 ? $pds_row[2] : '' ?></b></td>
											<td align="left">Name Extension</td>
											<td align="left"><b><?php echo $pds_num > 0 ? $pds_row[3] : '' ?></b></td>
										</tr>
										<tr>
											<td align="left">Birth Date</td>
											<td align="left"><b><?php echo $pds_num > 0 ? $pds_row[4] : '' ?></b></td>
											<td align="left">Citizenship</td>
											<td align="left"><b><?php 
												if ($pds_num > 0) {
													if ($pds_row[5] == 1) { echo 'Filipino';
													} elseif ($pds_row[5] == 2) { echo 'Dual Citizen';
													}
												}
												?></b></td>
										</tr>
										<tr>
											<td align="left">Birth Place</td>
											<td align="left"><b><?php echo $pds_num > 0 ? $pds_row[6] : '' ?></b></td>
											<td align="left">&raquo;&raquo;Aquired Thru:</td>
											<td align="left"><b><?php 
												if ($pds_num > 0) {
													if ($pds_row[7] == 1) { echo 'By Birth';
													} elseif ($pds_row[7] == 2) { echo 'Naturalization';
													}
													}
												?></b></td>
										</tr>
										<tr>
											<td align="left">Sex</td>
											<td align="left"><b><?php 
												if ($pds_num > 0) {
													if ($pds_row[8] == 'M') { echo 'Male';
													} elseif ($pds_row[8] == 'F') { echo 'Female';
													}
												}
												?></b></td>
											<td align="left">&raquo;&raquo;Country (If Dual-Citizen)</td>
											<td align="left"><b><?php echo $pds_num > 0 ? $pds_row[9] : '' ?></b></td>
										</tr>
										<tr>
											<td align="left">Civil Status</td>
											<td align="left"><b><?php 
												if ($pds_num > 0) {
													if ($pds_row[10] == 1) { echo 'Single';
													} elseif ($pds_row[10] == 2) { echo 'Married';
													} elseif ($pds_row[10] == 3) { echo 'Separated';
													} elseif ($pds_row[10] == 4) { echo 'Widowed';
													}
												}
												?></b></td>
											<td align="left">Residencial Address</td>
											<td align="left" rowspan="2"><b><?php echo $pds_num > 0 ? $pds_row[11] : '' ?></b></td>
										</tr>
										<tr>
											<td align="left">Height</td>
											<td align="left"><b><?php echo $pds_num > 0 ? $pds_row[12] : '' ?></b></td>
											<td align="left"></td>
										</tr>
										<tr>
											<td align="left">Weight</td>
											<td align="left"><b><?php echo $pds_num > 0 ? $pds_row[13] : '' ?></b></td>
											<td align="left">Permanent Address</td>
											<td align="left" rowspan="2"><b><?php echo $pds_num > 0 ? $pds_row[14] : '' ?></b></td>
										</tr>
										<tr>
											<td align="left">Blood Type</td>
											<td align="left"><b><?php echo $pds_num > 0 ? $pds_row[15] : '' ?></b></td>
											<td align="left"></td>
										</tr>
										<tr>
											<td align="left">GSID ID No.</td>
											<td align="left"><b><?php echo $pds_num > 0 ? $pds_row[16] : '' ?></b></td>
											<td align="left">Telephone No.</td>
											<td align="left"><b><?php echo $pds_num > 0 ? $pds_row[17] : '' ?></b></td>
										</tr>
										<tr>
											<td align="left">PAG-IBIG ID No.</td>
											<td align="left"><b><?php echo $pds_num > 0 ? $pds_row[18] : '' ?></b></td>
											<td align="left">Mobile No.</td>
											<td align="left"><b><?php echo $pds_num > 0 ? $pds_row[19] : '' ?></b></td>
										</tr>
										<tr>
											<td align="left">PHILHEALTH No.</td>
											<td align="left"><b><?php echo $pds_num > 0 ? $pds_row[20] : '' ?></b></td>
											<td align="left">Email Address</td>
											<td align="left"><b><?php echo $pds_num > 0 ? $pds_row[21] : '' ?></b></td>
										</tr>
										<tr>
											<td align="left">SSS No.</td>
											<td align="left"><b><?php echo $pds_num > 0 ? $pds_row[22] : '' ?></b></td>
											<td align="left">Agency Employee No.</td>
											<td align="left"><b><?php echo $pds_num > 0 ? $pds_row[23] : '' ?></b></td>
										</tr>
										<tr>
											<td align="left">TIN No.</td>
											<td align="left"><b><?php echo $pds_num > 0 ? $pds_row[24] : '' ?></b></td>
											<td align="left">Common Reference No. (CRN)</td>
											<td align="left"><b><?php echo $pds_num > 0 ? $pds_row[25] : '' ?></b></td>
										</tr>
									  </tbody>
									</table>
									
									<?php
									$sql = "SELECT * FROM employees_families WHERE eid = '$eid'";
									$fres = query($sql); 
									$fnum = num_rows($fres); 
									$frow = fetch_array($fres); 
									free_result($fres); 
									?>
									<ul class="nav navbar-right panel_toolbox">
										<?php
										if ($fnum > 0) {
											  ?><a href="e_family.php?fid=<?php echo $frow[0] ?>" class="btn btn-warning btn-md"><i class="fa fa-pencil"></i> Edit </a><?php
										  } else {
											  ?><a href="add_family.php?eid=<?php echo $eid ?>" class="btn btn-primary btn-md"><i class="fa fa-plus-square"></i> Add </a><?php
										}
										?>
									</ul>
									<table class="table table-striped table-bordered jambo_table">
									  <thead>
										<tr>
										  <th colspan="4">Family Background</th>
										</tr>
									  </thead>
									  <tbody>
										<tr>
											<td width="15%" align="left">Spouse's Surname</td>
											<td width="35%" align="left"><b><?php echo $fnum > 0 ? $frow[2] : '' ?></b></td>
											<td width="15%" align="left">Spouse's First Name</td>
											<td width="35%" align="left"><b><?php echo $fnum > 0 ? $frow[4] : '' ?></b></td>
										</tr>
										<tr>
											<td align="left">Spouse's Middle Name</td>
											<td align="left"><b><?php echo $fnum > 0 ? $frow[3] : '' ?></b></td>
											<td align="left">Spouse's Name Extension</td>
											<td align="left"><b><?php echo $fnum > 0 ? $frow[5] : '' ?></b></td>
										</tr>
										<tr>
											<td align="left">Spouse's Employer</td>
											<td align="left"><b><?php echo $fnum > 0 ? $frow[7] : '' ?></b></td>
											<td align="left">Spouse's Occupation</td>
											<td align="left"><b><?php echo $fnum > 0 ? $frow[6] : '' ?></b></td>
										</tr>
										<tr>
											<td align="left">Employer's Address</td>
											<td align="left"><b><?php echo $fnum > 0 ? $frow[8] : '' ?></b></td>
											<td align="left">Telephone No.</td>
											<td align="left"><b><?php echo $fnum > 0 ? $frow[9] : '' ?></b></td>
										</tr>
										
										<tr>
											<td align="left">Father's Surname</td>
											<td align="left"><b><?php echo $fnum > 0 ? $frow[10] : '' ?></b></td>
											<td align="left">Father's First Name</td>
											<td align="left"><b><?php echo $fnum > 0 ? $frow[11] : '' ?></b></td>
										</tr>
										<tr>
											<td align="left">Father's Middle Name</td>
											<td align="left"><b><?php echo $fnum > 0 ? $frow[12] : '' ?></b></td>
											<td align="left">Father's Name Extension</td>
											<td align="left"><b><?php echo $fnum > 0 ? $frow[13] : '' ?></b></td>
										</tr>
										
										<tr>
											<td align="left">Mother's Surname</td>
											<td align="left"><b><?php echo $fnum > 0 ? $frow[14] : '' ?></b></td>
											<td align="left">Mother's First Name</td>
											<td align="left"><b><?php echo $fnum > 0 ? $frow[15] : '' ?></b></td>
										</tr>
										<tr>
											<td align="left">Mother's Middle Name</td>
											<td align="left"><b><?php echo $fnum > 0 ? $frow[16] : '' ?></b></td>
											<td align="left">Mother's Extension Name</td>
											<td align="left"><b><?php echo $fnum > 0 ? $frow[17] : '' ?></b></td>
										</tr>
									  </tbody>
									</table>
									
									<ul class="nav navbar-right panel_toolbox">
										<?php
										$sql = "SELECT cid, cname, DATE_FORMAT(bdate, '%m/%d/%Y'), bplace
												FROM employees_children
												WHERE eid = '$eid'";
										$child_res = query($sql); 
										$child_num = num_rows($child_res);
										
										?><a href="add_child.php?eid=<?php echo $eid ?>" class="btn btn-primary btn-md"><i class="fa fa-plus-square"></i> Add </a><?php
										?>
									</ul>
									<table class="table table-striped table-bordered jambo_table">
									  <thead>
										<tr>
										  <th colspan="4">Children</th>
										</tr>
										<tr>
											<th width="10%"></th>
											<th width="25%">Name</th>
											<th width="15%">Birth Date</th>
											<th width="50%">Birth Place</th>
										</tr>
									  </thead>
									  <tbody>
										<?php
										while ($child_row = fetch_array($child_res)) {
											?>
											<tr>
												<td align="center"><a href="d_child.php?cid=<?php echo $child_row[0] ?>" class="btn btn-danger btn-xs"><i class="fa fa-eraser"></i> Delete </a></td>
												<td align="left"><?php echo $child_row[1] ?></td>
												<td align="center"><?php echo $child_row[2] ?></td>
												<td align="left"><?php echo $child_row[3] ?></td>
											</tr>
											<?php
										}
										free_result($child_res); 
										?>
									  </tbody>
									</table>
									
									<table class="table table-striped table-bordered jambo_table">
									  <thead>
										<tr>
										  <th colspan="9">Educational Background</th>
										</tr>
										<tr>
											<th rowspan="2" width="07%"></th>
											<th rowspan="2" width="12%">Level</th>
											<th rowspan="2" width="25%">Name of School</th>
											<th rowspan="2" width="16%">Degree/Course</th>
											<th colspan="2">Period</th>
											<th rowspan="2" width="10%">Highest Level /<br />Units Earned</th>
											<th rowspan="2" width="06%">Year Graduated</th>
											<th rowspan="2" width="12%">Scholarship /<br />Honors Received</th>
										</tr>
										<tr>
											<th width="06%">From</th>
											<th width="06%">To</th>
										</tr>
									  </thead>
									  <tbody>
										<?php
										$sql = "SELECT *
												FROM employees_schools
												WHERE eid = '$eid' AND slevel = 1";
										$s1_res = query($sql); 
										$s1_num = num_rows($s1_res);
										$s1_row = fetch_array($s1_res); 
										?>
										<tr>
											<td align="center"><?php
												if ($s1_num > 0) {
													?>
													<a href="e_education.php?sid=<?php echo $s1_row[0] ?>" class="btn btn-warning btn-xs"><i class="fa fa-pencil"></i></a> 
													<a href="d_education.php?sid=<?php echo $s1_row[0] ?>" class="btn btn-danger btn-xs"><i class="fa fa-eraser"></i></a>
													<?php
												} else {
													?><a href="add_education.php?eid=<?php echo $eid ?>&slevel=1" class="btn btn-primary btn-xs"><i class="fa fa-plus-square"></i></a><?php
												}
												?></td>
											<td align="left">Elementary</td>
											<td align="left"><?php echo $s1_num > 0 ? $s1_row[3] : '' ?></td>
											<td align="left"><?php echo $s1_num > 0 ? $s1_row[4] : '' ?></td>
											<td align="left"><?php echo $s1_num > 0 ? $s1_row[5] : '' ?></td>
											<td align="left"><?php echo $s1_num > 0 ? $s1_row[6] : '' ?></td>
											<td align="left"><?php echo $s1_num > 0 ? $s1_row[7] : '' ?></td>
											<td align="left"><?php echo $s1_num > 0 ? $s1_row[8] : '' ?></td>
											<td align="left"><?php echo $s1_num > 0 ? $s1_row[9] : '' ?></td>
										</tr>
										<?php
										$sql = "SELECT *
												FROM employees_schools
												WHERE eid = '$eid' AND slevel = 2";
										$s2_res = query($sql); 
										$s2_num = num_rows($s2_res);
										$s2_row = fetch_array($s2_res); 
										?>
										<tr>
											<td align="center"><?php
												if ($s2_num > 0) {
													?>
													<a href="e_education.php?sid=<?php echo $s1_row[0] ?>" class="btn btn-warning btn-xs"><i class="fa fa-pencil"></i></a> 
													<a href="d_education.php?sid=<?php echo $s1_row[0] ?>" class="btn btn-danger btn-xs"><i class="fa fa-eraser"></i></a>
													<?php
												} else {
													?><a href="add_education.php?eid=<?php echo $eid ?>&slevel=2" class="btn btn-primary btn-xs"><i class="fa fa-plus-square"></i></a><?php
												}
												?></td>
											<td align="left">Secondary</td>
											<td align="left"><?php echo $s2_num > 0 ? $s2_row[3] : '' ?></td>
											<td align="left"><?php echo $s2_num > 0 ? $s2_row[4] : '' ?></td>
											<td align="left"><?php echo $s2_num > 0 ? $s2_row[5] : '' ?></td>
											<td align="left"><?php echo $s2_num > 0 ? $s2_row[6] : '' ?></td>
											<td align="left"><?php echo $s2_num > 0 ? $s2_row[7] : '' ?></td>
											<td align="left"><?php echo $s2_num > 0 ? $s2_row[8] : '' ?></td>
											<td align="left"><?php echo $s2_num > 0 ? $s2_row[9] : '' ?></td>
										</tr>
										<?php
										$sql = "SELECT *
												FROM employees_schools
												WHERE eid = '$eid' AND slevel = 3";
										$s3_res = query($sql); 
										$s3_num = num_rows($s3_res);
										$s3_row = fetch_array($s3_res); 
										?>
										<tr>
											<td align="center"><?php
												if ($s3_num > 0) {
													?>
													<a href="e_education.php?sid=<?php echo $s1_row[0] ?>" class="btn btn-warning btn-xs"><i class="fa fa-pencil"></i></a> 
													<a href="d_education.php?sid=<?php echo $s1_row[0] ?>" class="btn btn-danger btn-xs"><i class="fa fa-eraser"></i></a>
													<?php
												} else {
													?><a href="add_education.php?eid=<?php echo $eid ?>&slevel=3" class="btn btn-primary btn-xs"><i class="fa fa-plus-square"></i></a><?php
												}
												?></td>
											<td align="left">Vocational / Trade</td>
											<td align="left"><?php echo $s3_num > 0 ? $s3_row[3] : '' ?></td>
											<td align="left"><?php echo $s3_num > 0 ? $s3_row[4] : '' ?></td>
											<td align="left"><?php echo $s3_num > 0 ? $s3_row[5] : '' ?></td>
											<td align="left"><?php echo $s3_num > 0 ? $s3_row[6] : '' ?></td>
											<td align="left"><?php echo $s3_num > 0 ? $s3_row[7] : '' ?></td>
											<td align="left"><?php echo $s3_num > 0 ? $s3_row[8] : '' ?></td>
											<td align="left"><?php echo $s3_num > 0 ? $s3_row[9] : '' ?></td>
										</tr>
										<?php
										$sql = "SELECT *
												FROM employees_schools
												WHERE eid = '$eid' AND slevel = 4";
										$s4_res = query($sql); 
										$s4_num = num_rows($s4_res);
										$s4_row = fetch_array($s4_res); 
										?>
										<tr>
											<td align="center"><?php
												if ($s4_num > 0) {
													?>
													<a href="e_education.php?sid=<?php echo $s1_row[0] ?>" class="btn btn-warning btn-xs"><i class="fa fa-pencil"></i></a> 
													<a href="d_education.php?sid=<?php echo $s1_row[0] ?>" class="btn btn-danger btn-xs"><i class="fa fa-eraser"></i></a>
													<?php
												} else {
													?><a href="add_education.php?eid=<?php echo $eid ?>&slevel=4" class="btn btn-primary btn-xs"><i class="fa fa-plus-square"></i></a><?php
												}
												?></td>
											<td align="left">College</td>
											<td align="left"><?php echo $s4_num > 0 ? $s4_row[3] : '' ?></td>
											<td align="left"><?php echo $s4_num > 0 ? $s4_row[4] : '' ?></td>
											<td align="left"><?php echo $s4_num > 0 ? $s4_row[5] : '' ?></td>
											<td align="left"><?php echo $s4_num > 0 ? $s4_row[6] : '' ?></td>
											<td align="left"><?php echo $s4_num > 0 ? $s4_row[7] : '' ?></td>
											<td align="left"><?php echo $s4_num > 0 ? $s4_row[8] : '' ?></td>
											<td align="left"><?php echo $s4_num > 0 ? $s4_row[9] : '' ?></td>
										</tr>
										<?php
										$sql = "SELECT *
												FROM employees_schools
												WHERE eid = '$eid' AND slevel = 5";
										$s5_res = query($sql); 
										$s5_num = num_rows($s5_res);
										$s5_row = fetch_array($s5_res); 
										?>
										<tr>
											<td align="center"><?php
												if ($s5_num > 0) {
													?>
													<a href="e_education.php?sid=<?php echo $s1_row[0] ?>" class="btn btn-warning btn-xs"><i class="fa fa-pencil"></i></a> 
													<a href="d_education.php?sid=<?php echo $s1_row[0] ?>" class="btn btn-danger btn-xs"><i class="fa fa-eraser"></i></a>
													<?php
												} else {
													?><a href="add_education.php?eid=<?php echo $eid ?>&slevel=5" class="btn btn-primary btn-xs"><i class="fa fa-plus-square"></i></a><?php
												}
												?></td>
											<td align="left">Master's Degree</td>
											<td align="left"><?php echo $s5_num > 0 ? $s5_row[3] : '' ?></td>
											<td align="left"><?php echo $s5_num > 0 ? $s5_row[4] : '' ?></td>
											<td align="left"><?php echo $s5_num > 0 ? $s5_row[5] : '' ?></td>
											<td align="left"><?php echo $s5_num > 0 ? $s5_row[6] : '' ?></td>
											<td align="left"><?php echo $s5_num > 0 ? $s5_row[7] : '' ?></td>
											<td align="left"><?php echo $s5_num > 0 ? $s5_row[8] : '' ?></td>
											<td align="left"><?php echo $s5_num > 0 ? $s5_row[9] : '' ?></td>
										</tr>
										<?php
										$sql = "SELECT *
												FROM employees_schools
												WHERE eid = '$eid' AND slevel = 6";
										$s6_res = query($sql); 
										$s6_num = num_rows($s6_res);
										$s6_row = fetch_array($s6_res); 
										?>
										<tr>
											<td align="center"><?php
												if ($s6_num > 0) {
													?>
													<a href="e_education.php?sid=<?php echo $s1_row[0] ?>" class="btn btn-warning btn-xs"><i class="fa fa-pencil"></i></a> 
													<a href="d_education.php?sid=<?php echo $s1_row[0] ?>" class="btn btn-danger btn-xs"><i class="fa fa-eraser"></i></a>
													<?php
												} else {
													?><a href="add_education.php?eid=<?php echo $eid ?>&slevel=6" class="btn btn-primary btn-xs"><i class="fa fa-plus-square"></i></a><?php
												}
												?></td>
											<td align="left">Doctor of Philosophy</td>
											<td align="left"><?php echo $s6_num > 0 ? $s6_row[3] : '' ?></td>
											<td align="left"><?php echo $s6_num > 0 ? $s6_row[4] : '' ?></td>
											<td align="left"><?php echo $s6_num > 0 ? $s6_row[5] : '' ?></td>
											<td align="left"><?php echo $s6_num > 0 ? $s6_row[6] : '' ?></td>
											<td align="left"><?php echo $s6_num > 0 ? $s6_row[7] : '' ?></td>
											<td align="left"><?php echo $s6_num > 0 ? $s6_row[8] : '' ?></td>
											<td align="left"><?php echo $s6_num > 0 ? $s6_row[9] : '' ?></td>
										</tr>
									  </tbody>
									</table>
									
								</div>
								<!-- personal information, family background, educational background -->
								
								
								<!-- civil service eligibility, work experience -->
								<div role="tabpanel" class="tab-pane fade" id="tab_content3" aria-labelledby="profile-tab">
								
									<ul class="nav navbar-right panel_toolbox">
										<a href="add_eligibility.php?eid=<?php echo $eid ?>" class="btn btn-primary btn-md"><i class="fa fa-plus-square"></i> Add </a>
									</ul>
									<table class="table table-striped table-bordered jambo_table">
									  <thead>
										<tr>
										  <th colspan="7">Civil Service Eligibility</th>
										</tr>
										<tr>
										  <th rowspan="2" width="08%"></th>
										  <th rowspan="2" width="37%">Career Service / RA 1080</th>
										  <th rowspan="2" width="07%">Rating</th>
										  <th rowspan="2" width="10%">Date of Exam</th>
										  <th rowspan="2" width="20%">Place of Exam</th>
										  <th colspan="2">License</th>
										</tr>
										<tr>
										  <th width="10%">Number</th>
										  <th width="08%">Validity</th>
										</tr>
									  </thead>
									  <tbody>
										<?php
										$i = 1; 
										$sql = "SELECT eeid, ename, rating, DATE_FORMAT(edate, '%m/%d/%Y'), 
													eplace, license_num, DATE_FORMAT(license_exp, '%m/%d/%Y')
												FROM employees_eligibilities
												WHERE eid = '$eid'";
										$res = query($sql); 
										while ($row = fetch_array($res)) {
											?>
											<tr>
												<td align="center">
													<a href="e_eligibility.php?eeid=<?php echo $row[0] ?>" class="btn btn-warning btn-xs"><i class="fa fa-pencil"></i></a>
													<a href="d_eligibility.php?eeid=<?php echo $row[0] ?>" class="btn btn-danger btn-xs"><i class="fa fa-eraser"></i></a>
												</td>
												<td align="left"><?php echo $row[1] ?></td>
												<td align="right"><?php echo number_format($row[2], 2) ?></td>
												<td align="center"><?php echo $row[3] ?></td>
												<td align="left"><?php echo $row[4] ?></td>
												<td align="left"><?php echo $row[5] ?></td>
												<td align="center"><?php echo $row[6] == '00/00/0000' ? '' : $row[6] ?></td>
											</tr>
											<?php
											$i++; 
										}
										free_result($res); 
										if ($i<8) {
											for ($j=$i; $j<8; $j++) {
												?>
												<tr>
													<td>&nbsp;</td>
													<td>&nbsp;</td>
													<td>&nbsp;</td>
													<td>&nbsp;</td>
													<td>&nbsp;</td>
													<td>&nbsp;</td>
													<td>&nbsp;</td>
												</tr>
												<?php
											}
										}
										?>
									  </tbody>
									</table>
									
									<ul class="nav navbar-right panel_toolbox">
										<a href="add_experience.php?eid=<?php echo $eid ?>" class="btn btn-primary btn-md"><i class="fa fa-plus-square"></i> Add </a>
									</ul>
									<table class="table table-striped table-bordered jambo_table">
									  <thead>
										<tr>
										  <th colspan="9">Work Experience</th>
										</tr>
										<tr>
										  <th rowspan="2" width="07%"></th>
										  <th colspan="2">Inclusive Dates</th>
										  <th rowspan="2" width="24%">Position / Title</th>
										  <th rowspan="2" width="24%">Department / Agency / Office / Company</th>
										  <th rowspan="2" width="08%">Monthly Salary</th>
										  <th rowspan="2" width="08%">Pay Grade</th>
										  <th rowspan="2" width="10%">Appointment Status</th>
										  <th rowspan="2" width="05%">Govt Service</th>
										</tr>
										<tr>
										  <th width="07%">From</th>
										  <th width="07%">To</th>
										</tr>
									  </thead>
									  <tbody>
										<?php
										$i = 1; 
										$sql = "SELECT eeid, DATE_FORMAT(estart, '%m/%d/%y'), DATE_FORMAT(eend, '%m/%d/%y'), 
													position, company, salary, CONCAT(sgrade,'-',sstep), status, govt
												FROM employees_experiences
												WHERE eid = '$eid'";
										$res = query($sql); 
										while ($row = fetch_array($res)) {
											?>
											<tr>
												<td align="center">
													<a href="e_experience.php?eeid=<?php echo $row[0] ?>" class="btn btn-warning btn-xs"><i class="fa fa-pencil"></i></a>
													<a href="d_experience.php?eeid=<?php echo $row[0] ?>" class="btn btn-danger btn-xs"><i class="fa fa-eraser"></i></a>
												</td>
												<td align="center"><?php echo $row[1] ?></td>
												<td align="center"><?php echo $row[2] ?></td>
												<td align="left"><?php echo $row[3] ?></td>
												<td align="left"><?php echo $row[4] ?></td>
												<td align="right"><?php echo number_format($row[5], 2) ?></td>
												<td align="right"><?php echo $row[6] ?></td>
												<td align="center"><?php echo $row[7] ?></td>
												<td align="center"><?php echo $row[8] == 1 ? 'Yes' : 'No' ?></td>
											</tr>
											<?php
											$i++; 
										}
										free_result($res); 
										if ($i<16) {
											for ($j=$i; $j<16; $j++) {
												?>
												<tr>
													<td>&nbsp;</td>
													<td>&nbsp;</td>
													<td>&nbsp;</td>
													<td>&nbsp;</td>
													<td>&nbsp;</td>
													<td>&nbsp;</td>
													<td>&nbsp;</td>
													<td>&nbsp;</td>
													<td>&nbsp;</td>
												</tr>
												<?php
											}
										}
										?>
									  </tbody>
									</table>
									
								</div>
								<!-- civil service eligibility, work experience -->
								
								
								<!-- voluntary work, learning and development -->
								<div role="tabpanel" class="tab-pane fade" id="tab_content4" aria-labelledby="profile-tab">
								
									<ul class="nav navbar-right panel_toolbox">
										<a href="add_vwork.php?eid=<?php echo $eid ?>" class="btn btn-primary btn-md"><i class="fa fa-plus-square"></i> Add </a>
									</ul>
									<table class="table table-striped table-bordered jambo_table">
									  <thead>
										<tr>
										  <th colspan="6">Voluntary Work / Civic Organizations</th>
										</tr>
										<tr>
										  <th rowspan="2" width="07%"></th>
										  <th rowspan="2" width="39%">Name & Address of Organization</th>
										  <th colspan="2">Inclusive Dates</th>
										  <th rowspan="2" width="10%">No. of Hours</th>
										  <th rowspan="2" width="30%">Position / Nature of Work</th>
										</tr>
										<tr>
										  <th width="07%">From</th>
										  <th width="07%">To</th>
										</tr>
									  </thead>
									  <tbody>
										<?php
										$i = 1; 
										$sql = "SELECT wid, wname, waddr, DATE_FORMAT(wstart, '%m/%d/%y'), 
													DATE_FORMAT(wend, '%m/%d/%y'), nhours, job_desc
												FROM employees_voluntaries
												WHERE eid = '$eid'";
										$res = query($sql); 
										while ($row = fetch_array($res)) {
											?>
											<tr>
												<td align="center">
													<a href="e_vwork.php?wid=<?php echo $row[0] ?>" class="btn btn-warning btn-xs"><i class="fa fa-pencil"></i></a>
													<a href="d_vwork.php?wid=<?php echo $row[0] ?>" class="btn btn-danger btn-xs"><i class="fa fa-eraser"></i></a>
												</td>
												<td align="left"><?php echo $row[1].'<br />'.$row[2] ?></td>
												<td align="center"><?php echo $row[3] ?></td>
												<td align="center"><?php echo $row[4] ?></td>
												<td align="right"><?php echo $row[5] ?></td>
												<td align="left"><?php echo $row[6] ?></td>
											</tr>
											<?php
											$i++; 
										}
										free_result($res); 
										if ($i<8) {
											for ($j=$i; $j<8; $j++) {
												?>
												<tr>
													<td>&nbsp;</td>
													<td>&nbsp;</td>
													<td>&nbsp;</td>
													<td>&nbsp;</td>
													<td>&nbsp;</td>
													<td>&nbsp;</td>
												</tr>
												<?php
											}
										}
										?>
									  </tbody>
									</table>
									
									<ul class="nav navbar-right panel_toolbox">
										<a href="add_training.php?eid=<?php echo $eid ?>" class="btn btn-primary btn-md"><i class="fa fa-plus-square"></i> Add </a>
									</ul>
									<table class="table table-striped table-bordered jambo_table">
									  <thead>
										<tr>
										  <th colspan="7">Learning & Development / Trainings</th>
										</tr>
										<tr>
										  <th rowspan="2" width="07%"></th>
										  <th rowspan="2" width="39%">Title of Learning & Development / Training</th>
										  <th colspan="2">Inclusive Dates</th>
										  <th rowspan="2" width="10%">No. of Hours</th>
										  <th rowspan="2" width="10%">Type of L&D</th>
										  <th rowspan="2" width="20%">Sponsored By</th>
										</tr>
										<tr>
										  <th rowspan="2" width="07%">From</th>
										  <th rowspan="2" width="07%">To</th>
										</tr>
									  </thead>
									  <tbody>
										<?php
										$i = 1; 
										$sql = "SELECT tid, tname, DATE_FORMAT(tstart, '%m/%d/%y'), 
													DATE_FORMAT(tend, '%m/%d/%y'), nhours, ttype, sponsor
												FROM employees_trainings 
												WHERE eid = '$eid'";
										$res = query($sql); 
										while ($row = fetch_array($res)) {
											?>
											<tr>
												<td align="center">
													<a href="e_training.php?tid=<?php echo $row[0] ?>" class="btn btn-warning btn-xs"><i class="fa fa-pencil"></i></a>
													<a href="d_training.php?tid=<?php echo $row[0] ?>" class="btn btn-danger btn-xs"><i class="fa fa-eraser"></i></a>
												</td>
												<td align="left"><?php echo $row[1] ?></td>
												<td align="center"><?php echo $row[2] ?></td>
												<td align="center"><?php echo $row[3] ?></td>
												<td align="right"><?php echo $row[4] ?></td>
												<td align="center"><?php echo $row[5] ?></td>
												<td align="left"><?php echo $row[6] ?></td>
											</tr>
											<?php
											$i++; 
										}
										free_result($res); 
										if ($i<8) {
											for ($j=$i; $j<8; $j++) {
												?>
												<tr>
													<td>&nbsp;</td>
													<td>&nbsp;</td>
													<td>&nbsp;</td>
													<td>&nbsp;</td>
													<td>&nbsp;</td>
													<td>&nbsp;</td>
													<td>&nbsp;</td>
												</tr>
												<?php
											}
										}
										?>
									  </tbody>
									</table>
								
									<table class="table table-striped table-bordered jambo_table">
									  <thead>
										<tr>
										  <th colspan="6">Other Information</th>
										</tr>
										<tr>
										  <th width="07%"></th>
										  <th width="26%">Special Skills / Hobbies</th>
										  <th width="07%"></th>
										  <th width="26%">Non-Academic Recognitions</th>
										  <th width="07%"></th>
										  <th width="26%">Membership in Organizations</th>
										</tr>
									  </thead>
									  <tbody>
									  <?php
									  $init = 0; 
									  for ($i=1; $i<7; $i++) {
										  ?>
										  <tr>
											<td align="center"><?php
												$sql = "SELECT iid, iinfo
														FROM employees_info
														WHERE itype = 1 AND eid = '$eid'
														LIMIT $init, 1";
												$r1_res = query($sql);
												$r1_num = num_rows($r1_res); 
												if ($r1_num > 0) {
													$r1_row = fetch_array($r1_res); 
													?>
													<a href="e_info.php?iid=<?php echo $r1_row[0] ?>" class="btn btn-warning btn-xs"><i class="fa fa-pencil"></i></a>
													<a href="d_info.php?iid=<?php echo $r1_row[0] ?>" class="btn btn-danger btn-xs"><i class="fa fa-eraser"></i></a>
													<?php
												} else {
													?><a href="add_info.php?eid=<?php echo $eid ?>&itype=1" class="btn btn-primary btn-xs"><i class="fa fa-plus-square"></i></a><?php
												}
												?></td>
											<td align="left"><?php
												if ($r1_num > 0) { echo $r1_row[1];
												} else echo '&nbsp;';
												free_result($r1_res); 
												?></td>
											<td align="center"><?php
												$sql = "SELECT iid, iinfo
														FROM employees_info
														WHERE itype = 2 AND eid = '$eid'
														LIMIT $init, 1";
												$r2_res = query($sql);
												$r2_num = num_rows($r2_res); 
												if ($r2_num > 0) {
													$r2_row = fetch_array($r2_res); 
													?>
													<a href="e_info.php?iid=<?php echo $r2_row[0] ?>" class="btn btn-warning btn-xs"><i class="fa fa-pencil"></i></a>
													<a href="d_info.php?iid=<?php echo $r2_row[0] ?>" class="btn btn-danger btn-xs"><i class="fa fa-eraser"></i></a>
													<?php
												} else {
													?><a href="add_info.php?eid=<?php echo $eid ?>&itype=2" class="btn btn-primary btn-xs"><i class="fa fa-plus-square"></i></a><?php
												}
												?></td>
											<td align="left"><?php
												if ($r2_num > 0) { echo $r2_row[1];
												} else echo '&nbsp;';
												free_result($r2_res); 
												?></td>
											<td align="center"><?php
												$sql = "SELECT iid, iinfo
														FROM employees_info
														WHERE itype = 3 AND eid = '$eid'
														LIMIT $init, 1";
												$r3_res = query($sql);
												$r3_num = num_rows($r3_res); 
												if ($r3_num > 0) {
													$r3_row = fetch_array($r3_res); 
													?>
													<a href="e_info.php?iid=<?php echo $r3_row[0] ?>" class="btn btn-warning btn-xs"><i class="fa fa-pencil"></i></a>
													<a href="d_info.php?iid=<?php echo $r3_row[0] ?>" class="btn btn-danger btn-xs"><i class="fa fa-eraser"></i></a>
													<?php
												} else {
													?><a href="add_info.php?eid=<?php echo $eid ?>&itype=2" class="btn btn-primary btn-xs"><i class="fa fa-plus-square"></i></a><?php
												}
												?></td>
											<td align="left"><?php
												if ($r3_num > 0) { echo $r3_row[1];
												} else echo '&nbsp;';
												free_result($r3_res); 
												?></td>
										  </tr>
										  <?php
										  $init++; 
									  }
									  ?>
									  </tbody>
									</table>
									
								</div>
								<!-- voluntary work, learning and development -->
								
								
								<!-- other information -->
								<div role="tabpanel" class="tab-pane fade" id="tab_content5" aria-labelledby="profile-tab">
									<table id="datatable-fixed-header" class="table table-striped table-bordered jambo_table">
									  <thead>
										<tr>
										  <th width="09%">Type</th>
										  <th width="28%">Description</th>
										  <th width="07%">Prop #</th>
										  <th width="07%">PAR</th>
										  <th width="07%">ICS#</th>
										  <th width="07%">Brand<br />Model</th>
										  <th width="07%">Serial#</th>
										  <th width="09%">Supplier</th>
										  <th width="07%">Acquired</th>
										  <th width="07%">Value</th>
										  <th width="05%"></th>
										</tr>
									  </thead>
									  <tbody>
										<?php
										$sql = "SELECT i.iid, c.cname, i.idesc, i.property_num, i.property_ack_num, 
													i.ics, i.brand_name, i.model_name, i.serial, s.company,
													DATE_FORMAT(i.acquired_date, '%m/%d/%y'), i.acquired_value
												FROM inventory i 
													INNER JOIN categories c ON i.cid = c.cid
													INNER JOIN suppliers s ON i.sid = s.sid
												WHERE i.eid = '$eid'
												ORDER BY c.cname, i.idesc";
										$res = query($sql);

										while ($row = fetch_array($res)) {
											?>
											<tr>
											  <td align="center"><?php echo $row[1] ?></td>
											  <td align="left"><a href="item.php?iid=<?php echo $row[0] ?>"><?php echo $row[2] ?></a></td>
											  <td align="left"><?php echo $row[3] ?></td>
											  <td align="left"><?php echo $row[4] ?></td>
											  <td align="left"><?php echo $row[5] ?></td>
											  <td align="left"><?php echo $row[6].'<br />'.$row[7] ?></td>
											  <td align="left"><?php echo $row[8] ?></td>
											  <td align="left"><?php echo $row[9] ?></td>
											  <td align="left"><?php echo $row[10] ?></td>
											  <td align="right"><?php echo number_format($row[11], 2) ?></td>
											  <td align="center"><a href="e_item.php?iid=<?php echo $row[0] ?>" class="btn btn-warning btn-xs"><i class="fa fa-pencil"></i></a></td>
											</tr>
											<?php
										}

										mysqli_free_result($res);
										?>
									  </tbody>
									</table>
									
								</div>
								<!-- other information -->
								
								
							  </div>
							</div>
						  
						  </div>
						</div>
					  </div>
					
					</div> <!-- end of container of all tables -->

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
	} else {
	
		// check if user is a valid employee
		$sql = "SELECT eid FROM users WHERE user_id = '$_SESSION[user_id]' AND active = 1";
		$vres = query($sql); $vrow = fetch_array($vres); $emp_id = $vrow[0]; 
		
		if ($emp_id != 0) {
			
			if ($emp_id == $eid) {

				// log the activity
				log_user(5, 'employees', $eid);
				
				?>
				<!DOCTYPE html>
				<html lang="en">
				  <head>
					<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
					<!-- Meta, title, CSS, favicons, etc. -->
					<meta charset="utf-8">
					<meta http-equiv="X-UA-Compatible" content="IE=edge">
					<meta name="viewport" content="width=device-width, initial-scale=1">
					  
					<title>OTC | Employee Details</title>

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
								<h3>Employee Details</h3>
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
							
							<div class=""> <!-- container of all tables -->
							
							  <div class="col-md-12 col-sm-12 col-xs-12">
								<div class="x_panel">
								  <div class="x_title">
									<h2>Employee Information</h2>
									<ul class="nav navbar-right panel_toolbox">
									  <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
									  <li><a class="close-link"><i class="fa fa-close"></i></a></li>
									</ul>
									<div class="clearfix"></div>
								  </div>
								  <div class="x_content">
								  
									<div class="" role="tabpanel" data-example-id="togglable-tabs">
									  <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
										<li role="presentation" class="active"><a href="#tab_content1" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true">Employment</a></li>
										<li role="presentation" class=""><a href="#tab_content2" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false">Basic Information</a></li>
										<li role="presentation" class=""><a href="#tab_content3" role="tab" id="profile-tab2" data-toggle="tab" aria-expanded="false">Work Experience</a></li>
										<li role="presentation" class=""><a href="#tab_content4" role="tab" id="profile-tab3" data-toggle="tab" aria-expanded="false">Voluntary Work</a></li>
										<li role="presentation" class=""><a href="#tab_content5" role="tab" id="profile-tab4" data-toggle="tab" aria-expanded="false">Issued Properties</a></li>
									  </ul>
									  
									  
									  <div id="myTabContent" class="tab-content">
										<!-- employment details -->
										<div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="home-tab">
											<?php
											$sql = "SELECT e.picture, e.ecode, CONCAT(e.lname,', ',e.fname,', ',e.mname),
														p.pname, d.dname, g.sg_num, e.sglevel, e.etype, 
														DATE_FORMAT(e.estart, '%m/%d/%Y'), DATE_FORMAT(e.eend, '%m/%d/%Y'), 
														e.time_in, e.time_out
													FROM employees e 
														INNER JOIN departments d ON e.did = d.did
														INNER JOIN positions p ON e.pid = p.pid
														LEFT JOIN salary_grades g ON e.sgid = g.sgid
													WHERE e.eid = '$eid'";
											$res = query($sql); 
											$row = fetch_array($res); 
											free_result($res); 
											
											?>
											<ul class="nav navbar-right panel_toolbox">
												<a href="e_employee.php?eid=<?php echo $eid ?>" class="btn btn-warning btn-md"><i class="fa fa-pencil"></i> Edit </a>
											</ul>
											<table class="table table-striped table-bordered jambo_table">
											  <thead>
												<tr>
												  <th colspan="5">Employment Details</th>
												</tr>
											  </thead>
											  <tbody>
												<tr>
													<td rowspan="5" align="center" width="18%"><img src="<?php echo $row[0] ?>" class="img-thumbnail"></td>
													<td align="left" width="15%">Employee #</td>
													<td align="left" width="22%"><b><?php echo $row[1] ?></b></td>
													<td align="left" width="15%">Position</td>
													<td align="left" width="30%"><b><?php echo $row[3] ?></b></td>
												</tr>
												<tr>
													<td align="left">Name</td>
													<td align="left"><b><?php echo $row[2] ?></b></td>
													<td align="left">Department</td>
													<td align="left"><b><?php echo $row[4] ?></b></td>
												</tr>
												<tr>
													<td align="left">Work Hours</td>
													<td align="left"><b><?php echo $row[10].' - '.$row[11] ?></b></td>
													<td align="left">Employment Status</td>
													<td align="left"><b><?php
														if ($row[7] == 1) {
															echo 'Appointee';
														} elseif ($row[7] == 2) {
															echo 'Permanent';
														} elseif ($row[7] == 3) {
															echo 'Job Order';
														} elseif ($row[7] == 4) {
															echo 'COS';
														}
														?></b></td>
												</tr>
												<tr>
													<td align="left">Employment Date</td>
													<td align="left"><b><?php echo $row[8] != '00/00/0000' ? $row[8] : '' ?> - <?php echo $row[9] != '00/00/0000' ? $row[9] : 'Present' ?></b></td>
													<td align="left">Salary Grade</td>
													<td align="left"><b><?php echo $row[5] ?></b></td>
												</tr>
												<tr>
													<td align="left"><?php
														if ($row[0] == '') {
															?><a href="add_epicture.php?eid=<?php echo $eid ?>" class="btn btn-primary btn-xs"><i class="fa fa-plus-square"></i> Add Picture </a><?php
														} else {
															?><a href="d_epicture.php?eid=<?php echo $eid ?>" class="btn btn-danger btn-xs"><i class="fa fa-eraser"></i> Delete Picture </a><?php
														}
														?></td>
													<td align="left"></td>
													<td align="left">Step Increment</td>
													<td align="left"><b><?php echo $row[6] ?></b></td>
												</tr>
											  </tbody>
											</table>
										</div>
										<!-- employment details -->
										
										
										<!-- personal information, family background, educational background -->
										<div role="tabpanel" class="tab-pane fade" id="tab_content2" aria-labelledby="profile-tab">
											<ul class="nav navbar-right panel_toolbox">
											<?php
											$sql = "SELECT e.lname, e.fname, e.mname, e.ename, 
														DATE_FORMAT(p.bdate, '%m/%d/%Y'), p.citizenship,
														p.bplace, p.citizenship_means, p.sex, p.country_dual,
														p.civil_stat, p.res_addr, p.height, p.weight, 
														p.perm_addr, p.blood_type, p.gsis_id, p.tel_no,
														p.pagibig, p.cp_no, p.philhealth, p.email, 
														p.sss_id, p.agency_num, p.tin
													FROM employees_pds p 
														INNER JOIN employees e ON p.eid = e.eid
													WHERE e.eid = '$eid'";
											$pds_res = query($sql); 
											$pds_num = num_rows($pds_res);
											$pds_row = fetch_array($pds_res); 
										  
											if ($pds_num > 0) {
												?><a href="e_pds.php?eid=<?php echo $eid ?>" class="btn btn-warning btn-md"><i class="fa fa-pencil"></i> Edit </a><?php
											} else {
												?><a href="add_pds.php?eid=<?php echo $eid ?>" class="btn btn-primary btn-md"><i class="fa fa-pencil"></i> Add </a><?php
											}
											free_result($pds_res); 
											?>
											</ul>
											<table class="table table-striped table-bordered jambo_table">
											  <thead>
												<tr>
												  <th colspan="4">
													Personal Information
													<ul class="nav navbar-right panel_toolbox">
													</ul>
												  </th>
												</tr>
											  </thead>
											  <tbody>
												<tr>
													<td width="15%" align="left">Last Name</td>
													<td width="35%" align="left"><b><?php echo $pds_row[0] ?></b></td>
													<td width="15%" align="left">First Name</td>
													<td width="35%" align="left"><b><?php echo $pds_row[1] ?></b></td>
												</tr>
												<tr>
													<td align="left">Middle Name</td>
													<td align="left"><b><?php echo $pds_row[2] ?></b></td>
													<td align="left">Name Extension</td>
													<td align="left"><b><?php echo $pds_row[3] ?></b></td>
												</tr>
												<tr>
													<td align="left">Birth Date</td>
													<td align="left"><b><?php echo $pds_row[4] ?></b></td>
													<td align="left">Citizenship</td>
													<td align="left"><b><?php 
														if ($pds_row[5] == 1) { echo 'Filipino';
														} elseif ($pds_row[5] == 2) { echo 'Dual Citizen';
														}
														?></b></td>
												</tr>
												<tr>
													<td align="left">Birth Place</td>
													<td align="left"><b><?php echo $pds_row[6] ?></b></td>
													<td align="left">&raquo;&raquo;Aquired Thru:</td>
													<td align="left"><b><?php 
														if ($pds_row[7] == 1) { echo 'By Birth';
														} elseif ($pds_row[7] == 2) { echo 'Naturalization';
														}
														?></b></td>
												</tr>
												<tr>
													<td align="left">Sex</td>
													<td align="left"><b><?php 
														if ($pds_row[8] == 'M') { echo 'Male';
														} elseif ($pds_row[8] == 'F') { echo 'Female';
														}
														?></b></td>
													<td align="left">&raquo;&raquo;Country (If Dual-Citizen)</td>
													<td align="left"><b><?php echo $pds_row[9] ?></b></td>
												</tr>
												<tr>
													<td align="left">Civil Status</td>
													<td align="left"><b><?php 
														if ($pds_row[10] == 1) { echo 'Single';
														} elseif ($pds_row[10] == 2) { echo 'Married';
														} elseif ($pds_row[10] == 3) { echo 'Separated';
														} elseif ($pds_row[10] == 4) { echo 'Widowed';
														} ?></b></td>
													<td align="left">Residencial Address</td>
													<td align="left" rowspan="2"><b><?php echo $pds_row[11] ?></b></td>
												</tr>
												<tr>
													<td align="left">Height</td>
													<td align="left"><b><?php echo $pds_row[12] ?></b></td>
													<td align="left"></td>
												</tr>
												<tr>
													<td align="left">Weight</td>
													<td align="left"><b><?php echo $pds_row[13] ?></b></td>
													<td align="left">Permanent Address</td>
													<td align="left" rowspan="2"><b><?php echo $pds_row[14] ?></b></td>
												</tr>
												<tr>
													<td align="left">Blood Type</td>
													<td align="left"><b><?php echo $pds_row[15] ?></b></td>
													<td align="left"></td>
												</tr>
												<tr>
													<td align="left">GSID ID No.</td>
													<td align="left"><b><?php echo $pds_row[16] ?></b></td>
													<td align="left">Telephone No.</td>
													<td align="left"><b><?php echo $pds_row[17] ?></b></td>
												</tr>
												<tr>
													<td align="left">PAG-IBIG ID No.</td>
													<td align="left"><b><?php echo $pds_row[18] ?></b></td>
													<td align="left">Mobile No.</td>
													<td align="left"><b><?php echo $pds_row[19] ?></b></td>
												</tr>
												<tr>
													<td align="left">PHILHEALTH No.</td>
													<td align="left"><b><?php echo $pds_row[20] ?></b></td>
													<td align="left">Email Address</td>
													<td align="left"><b><?php echo $pds_row[21] ?></b></td>
												</tr>
												<tr>
													<td align="left">SSS No.</td>
													<td align="left"><b><?php echo $pds_row[22] ?></b></td>
													<td align="left">Agency Employee No.</td>
													<td align="left"><b><?php echo $pds_row[23] ?></b></td>
												</tr>
												<tr>
													<td align="left">TIN No.</td>
													<td align="left"><b><?php echo $pds_row[24] ?></b></td>
													<td align="left"></td>
													<td align="left"></td>
												</tr>
											  </tbody>
											</table>
											
											<?php
											$sql = "SELECT * FROM employees_families WHERE eid = '$eid'";
											$fres = query($sql); 
											$fnum = num_rows($fres); 
											$frow = fetch_array($fres); 
											free_result($fres); 
											?>
											<ul class="nav navbar-right panel_toolbox">
												<?php
												if ($fnum > 0) {
													  ?><a href="e_family.php?fid=<?php echo $frow[0] ?>" class="btn btn-warning btn-md"><i class="fa fa-pencil"></i> Edit </a><?php
												  } else {
													  ?><a href="add_family.php?eid=<?php echo $eid ?>" class="btn btn-primary btn-md"><i class="fa fa-plus-square"></i> Add </a><?php
												}
												?>
											</ul>
											<table class="table table-striped table-bordered jambo_table">
											  <thead>
												<tr>
												  <th colspan="4">Family Background</th>
												</tr>
											  </thead>
											  <tbody>
												<tr>
													<td width="15%" align="left">Spouse's Surname</td>
													<td width="35%" align="left"><b><?php echo $frow[2] ?></b></td>
													<td width="15%" align="left">Spouse's First Name</td>
													<td width="35%" align="left"><b><?php echo $frow[4] ?></b></td>
												</tr>
												<tr>
													<td align="left">Spouse's Middle Name</td>
													<td align="left"><b><?php echo $frow[3] ?></b></td>
													<td align="left">Spouse's Name Extension</td>
													<td align="left"><b><?php echo $frow[5] ?></b></td>
												</tr>
												<tr>
													<td align="left">Spouse's Employer</td>
													<td align="left"><b><?php echo $frow[7] ?></b></td>
													<td align="left">Spouse's Occupation</td>
													<td align="left"><b><?php echo $frow[6] ?></b></td>
												</tr>
												<tr>
													<td align="left">Employer's Address</td>
													<td align="left"><b><?php echo $frow[8] ?></b></td>
													<td align="left">Telephone No.</td>
													<td align="left"><b><?php echo $frow[9] ?></b></td>
												</tr>
												
												<tr>
													<td align="left">Father's Surname</td>
													<td align="left"><b><?php echo $frow[10] ?></b></td>
													<td align="left">Father's First Name</td>
													<td align="left"><b><?php echo $frow[11] ?></b></td>
												</tr>
												<tr>
													<td align="left">Father's Middle Name</td>
													<td align="left"><b><?php echo $frow[12] ?></b></td>
													<td align="left">Father's Name Extension</td>
													<td align="left"><b><?php echo $frow[13] ?></b></td>
												</tr>
												
												<tr>
													<td align="left">Mother's Surname</td>
													<td align="left"><b><?php echo $frow[14] ?></b></td>
													<td align="left">Mother's First Name</td>
													<td align="left"><b><?php echo $frow[15] ?></b></td>
												</tr>
												<tr>
													<td align="left">Mother's Middle Name</td>
													<td align="left"><b><?php echo $frow[16] ?></b></td>
													<td align="left">Mother's Extension Name</td>
													<td align="left"><b><?php echo $frow[17] ?></b></td>
												</tr>
											  </tbody>
											</table>
											
											<ul class="nav navbar-right panel_toolbox">
												<?php
												$sql = "SELECT cid, cname, DATE_FORMAT(bdate, '%m/%d/%Y'), bplace
														FROM employees_children
														WHERE eid = '$eid'";
												$child_res = query($sql); 
												$child_num = num_rows($child_res);
												
												?><a href="add_child.php?eid=<?php echo $eid ?>" class="btn btn-primary btn-md"><i class="fa fa-plus-square"></i> Add </a><?php
												?>
											</ul>
											<table class="table table-striped table-bordered jambo_table">
											  <thead>
												<tr>
												  <th colspan="4">Children</th>
												</tr>
												<tr>
													<th width="10%"></th>
													<th width="25%">Name</th>
													<th width="15%">Birth Date</th>
													<th width="50%">Birth Place</th>
												</tr>
											  </thead>
											  <tbody>
												<?php
												while ($child_row = fetch_array($child_res)) {
													?>
													<tr>
														<td align="center"><a href="d_child.php?cid=<?php echo $child_row[0] ?>" class="btn btn-danger btn-xs"><i class="fa fa-eraser"></i> Delete </a></td>
														<td align="left"><?php echo $child_row[1] ?></td>
														<td align="center"><?php echo $child_row[2] ?></td>
														<td align="left"><?php echo $child_row[3] ?></td>
													</tr>
													<?php
												}
												free_result($child_res); 
												?>
											  </tbody>
											</table>
											
											<table class="table table-striped table-bordered jambo_table">
											  <thead>
												<tr>
												  <th colspan="9">Educational Background</th>
												</tr>
												<tr>
													<th rowspan="2" width="07%"></th>
													<th rowspan="2" width="12%">Level</th>
													<th rowspan="2" width="25%">Name of School</th>
													<th rowspan="2" width="16%">Degree/Course</th>
													<th colspan="2">Period</th>
													<th rowspan="2" width="10%">Highest Level /<br />Units Earned</th>
													<th rowspan="2" width="06%">Year Graduated</th>
													<th rowspan="2" width="12%">Scholarship /<br />Honors Received</th>
												</tr>
												<tr>
													<th width="06%">From</th>
													<th width="06%">To</th>
												</tr>
											  </thead>
											  <tbody>
												<?php
												$sql = "SELECT *
														FROM employees_schools
														WHERE eid = '$eid' AND slevel = 1";
												$s1_res = query($sql); 
												$s1_num = num_rows($s1_res);
												$s1_row = fetch_array($s1_res); 
												?>
												<tr>
													<td align="center"><?php
														if ($s1_num > 0) {
															?>
															<a href="e_education.php?sid=<?php echo $s1_row[0] ?>" class="btn btn-warning btn-xs"><i class="fa fa-pencil"></i></a> 
															<a href="d_education.php?sid=<?php echo $s1_row[0] ?>" class="btn btn-danger btn-xs"><i class="fa fa-eraser"></i></a>
															<?php
														} else {
															?><a href="add_education.php?eid=<?php echo $eid ?>&slevel=1" class="btn btn-primary btn-xs"><i class="fa fa-plus-square"></i></a><?php
														}
														?></td>
													<td align="left">Elementary</td>
													<td align="left"><?php echo $s1_row[3] ?></td>
													<td align="left"><?php echo $s1_row[4] ?></td>
													<td align="left"><?php echo $s1_row[5] ?></td>
													<td align="left"><?php echo $s1_row[6] ?></td>
													<td align="left"><?php echo $s1_row[7] ?></td>
													<td align="left"><?php echo $s1_row[8] ?></td>
													<td align="left"><?php echo $s1_row[9] ?></td>
												</tr>
												<?php
												$sql = "SELECT *
														FROM employees_schools
														WHERE eid = '$eid' AND slevel = 2";
												$s2_res = query($sql); 
												$s2_num = num_rows($s2_res);
												$s2_row = fetch_array($s2_res); 
												?>
												<tr>
													<td align="center"><?php
														if ($s2_num > 0) {
															?>
															<a href="e_education.php?sid=<?php echo $s1_row[0] ?>" class="btn btn-warning btn-xs"><i class="fa fa-pencil"></i></a> 
															<a href="d_education.php?sid=<?php echo $s1_row[0] ?>" class="btn btn-danger btn-xs"><i class="fa fa-eraser"></i></a>
															<?php
														} else {
															?><a href="add_education.php?eid=<?php echo $eid ?>&slevel=2" class="btn btn-primary btn-xs"><i class="fa fa-plus-square"></i></a><?php
														}
														?></td>
													<td align="left">Secondary</td>
													<td align="left"><?php echo $s2_row[3] ?></td>
													<td align="left"><?php echo $s2_row[4] ?></td>
													<td align="left"><?php echo $s2_row[5] ?></td>
													<td align="left"><?php echo $s2_row[6] ?></td>
													<td align="left"><?php echo $s2_row[7] ?></td>
													<td align="left"><?php echo $s2_row[8] ?></td>
													<td align="left"><?php echo $s2_row[9] ?></td>
												</tr>
												<?php
												$sql = "SELECT *
														FROM employees_schools
														WHERE eid = '$eid' AND slevel = 3";
												$s3_res = query($sql); 
												$s3_num = num_rows($s3_res);
												$s3_row = fetch_array($s3_res); 
												?>
												<tr>
													<td align="center"><?php
														if ($s3_num > 0) {
															?>
															<a href="e_education.php?sid=<?php echo $s1_row[0] ?>" class="btn btn-warning btn-xs"><i class="fa fa-pencil"></i></a> 
															<a href="d_education.php?sid=<?php echo $s1_row[0] ?>" class="btn btn-danger btn-xs"><i class="fa fa-eraser"></i></a>
															<?php
														} else {
															?><a href="add_education.php?eid=<?php echo $eid ?>&slevel=3" class="btn btn-primary btn-xs"><i class="fa fa-plus-square"></i></a><?php
														}
														?></td>
													<td align="left">Vocational / Trade</td>
													<td align="left"><?php echo $s3_row[3] ?></td>
													<td align="left"><?php echo $s3_row[4] ?></td>
													<td align="left"><?php echo $s3_row[5] ?></td>
													<td align="left"><?php echo $s3_row[6] ?></td>
													<td align="left"><?php echo $s3_row[7] ?></td>
													<td align="left"><?php echo $s3_row[8] ?></td>
													<td align="left"><?php echo $s3_row[9] ?></td>
												</tr>
												<?php
												$sql = "SELECT *
														FROM employees_schools
														WHERE eid = '$eid' AND slevel = 4";
												$s4_res = query($sql); 
												$s4_num = num_rows($s4_res);
												$s4_row = fetch_array($s4_res); 
												?>
												<tr>
													<td align="center"><?php
														if ($s4_num > 0) {
															?>
															<a href="e_education.php?sid=<?php echo $s1_row[0] ?>" class="btn btn-warning btn-xs"><i class="fa fa-pencil"></i></a> 
															<a href="d_education.php?sid=<?php echo $s1_row[0] ?>" class="btn btn-danger btn-xs"><i class="fa fa-eraser"></i></a>
															<?php
														} else {
															?><a href="add_education.php?eid=<?php echo $eid ?>&slevel=4" class="btn btn-primary btn-xs"><i class="fa fa-plus-square"></i></a><?php
														}
														?></td>
													<td align="left">College</td>
													<td align="left"><?php echo $s4_row[3] ?></td>
													<td align="left"><?php echo $s4_row[4] ?></td>
													<td align="left"><?php echo $s4_row[5] ?></td>
													<td align="left"><?php echo $s4_row[6] ?></td>
													<td align="left"><?php echo $s4_row[7] ?></td>
													<td align="left"><?php echo $s4_row[8] ?></td>
													<td align="left"><?php echo $s4_row[9] ?></td>
												</tr>
												<?php
												$sql = "SELECT *
														FROM employees_schools
														WHERE eid = '$eid' AND slevel = 5";
												$s5_res = query($sql); 
												$s5_num = num_rows($s5_res);
												$s5_row = fetch_array($s5_res); 
												?>
												<tr>
													<td align="center"><?php
														if ($s5_num > 0) {
															?>
															<a href="e_education.php?sid=<?php echo $s1_row[0] ?>" class="btn btn-warning btn-xs"><i class="fa fa-pencil"></i></a> 
															<a href="d_education.php?sid=<?php echo $s1_row[0] ?>" class="btn btn-danger btn-xs"><i class="fa fa-eraser"></i></a>
															<?php
														} else {
															?><a href="add_education.php?eid=<?php echo $eid ?>&slevel=5" class="btn btn-primary btn-xs"><i class="fa fa-plus-square"></i></a><?php
														}
														?></td>
													<td align="left">Master's Degree</td>
													<td align="left"><?php echo $s5_row[3] ?></td>
													<td align="left"><?php echo $s5_row[4] ?></td>
													<td align="left"><?php echo $s5_row[5] ?></td>
													<td align="left"><?php echo $s5_row[6] ?></td>
													<td align="left"><?php echo $s5_row[7] ?></td>
													<td align="left"><?php echo $s5_row[8] ?></td>
													<td align="left"><?php echo $s5_row[9] ?></td>
												</tr>
												<?php
												$sql = "SELECT *
														FROM employees_schools
														WHERE eid = '$eid' AND slevel = 6";
												$s6_res = query($sql); 
												$s6_num = num_rows($s6_res);
												$s6_row = fetch_array($s6_res); 
												?>
												<tr>
													<td align="center"><?php
														if ($s6_num > 0) {
															?>
															<a href="e_education.php?sid=<?php echo $s1_row[0] ?>" class="btn btn-warning btn-xs"><i class="fa fa-pencil"></i></a> 
															<a href="d_education.php?sid=<?php echo $s1_row[0] ?>" class="btn btn-danger btn-xs"><i class="fa fa-eraser"></i></a>
															<?php
														} else {
															?><a href="add_education.php?eid=<?php echo $eid ?>&slevel=6" class="btn btn-primary btn-xs"><i class="fa fa-plus-square"></i></a><?php
														}
														?></td>
													<td align="left">Doctor of Philosophy</td>
													<td align="left"><?php echo $s6_row[3] ?></td>
													<td align="left"><?php echo $s6_row[4] ?></td>
													<td align="left"><?php echo $s6_row[5] ?></td>
													<td align="left"><?php echo $s6_row[6] ?></td>
													<td align="left"><?php echo $s6_row[7] ?></td>
													<td align="left"><?php echo $s6_row[8] ?></td>
													<td align="left"><?php echo $s6_row[9] ?></td>
												</tr>
											  </tbody>
											</table>
											
										</div>
										<!-- personal information, family background, educational background -->
										
										
										<!-- civil service eligibility, work experience -->
										<div role="tabpanel" class="tab-pane fade" id="tab_content3" aria-labelledby="profile-tab">
										
											<ul class="nav navbar-right panel_toolbox">
												<a href="add_eligibility.php?eid=<?php echo $eid ?>" class="btn btn-primary btn-md"><i class="fa fa-plus-square"></i> Add </a>
											</ul>
											<table class="table table-striped table-bordered jambo_table">
											  <thead>
												<tr>
												  <th colspan="7">Civil Service Eligibility</th>
												</tr>
												<tr>
												  <th rowspan="2" width="08%"></th>
												  <th rowspan="2" width="37%">Career Service / RA 1080</th>
												  <th rowspan="2" width="07%">Rating</th>
												  <th rowspan="2" width="10%">Date of Exam</th>
												  <th rowspan="2" width="20%">Place of Exam</th>
												  <th colspan="2">License</th>
												</tr>
												<tr>
												  <th width="10%">Number</th>
												  <th width="08%">Validity</th>
												</tr>
											  </thead>
											  <tbody>
												<?php
												$i = 1; 
												$sql = "SELECT eeid, ename, rating, DATE_FORMAT(edate, '%m/%d/%Y'), 
															eplace, license_num, DATE_FORMAT(license_exp, '%m/%d/%Y')
														FROM employees_eligibilities
														WHERE eid = '$eid'";
												$res = query($sql); 
												while ($row = fetch_array($res)) {
													?>
													<tr>
														<td align="center">
															<a href="e_eligibility.php?eeid=<?php echo $row[0] ?>" class="btn btn-warning btn-xs"><i class="fa fa-pencil"></i></a>
															<a href="d_eligibility.php?eeid=<?php echo $row[0] ?>" class="btn btn-danger btn-xs"><i class="fa fa-eraser"></i></a>
														</td>
														<td align="left"><?php echo $row[1] ?></td>
														<td align="right"><?php echo number_format($row[2], 2) ?></td>
														<td align="center"><?php echo $row[3] ?></td>
														<td align="left"><?php echo $row[4] ?></td>
														<td align="left"><?php echo $row[5] ?></td>
														<td align="center"><?php echo $row[6] == '00/00/0000' ? '' : $row[6] ?></td>
													</tr>
													<?php
													$i++; 
												}
												free_result($res); 
												if ($i<8) {
													for ($j=$i; $j<8; $j++) {
														?>
														<tr>
															<td>&nbsp;</td>
															<td>&nbsp;</td>
															<td>&nbsp;</td>
															<td>&nbsp;</td>
															<td>&nbsp;</td>
															<td>&nbsp;</td>
															<td>&nbsp;</td>
														</tr>
														<?php
													}
												}
												?>
											  </tbody>
											</table>
											
											<ul class="nav navbar-right panel_toolbox">
												<a href="add_experience.php?eid=<?php echo $eid ?>" class="btn btn-primary btn-md"><i class="fa fa-plus-square"></i> Add </a>
											</ul>
											<table class="table table-striped table-bordered jambo_table">
											  <thead>
												<tr>
												  <th colspan="9">Work Experience</th>
												</tr>
												<tr>
												  <th rowspan="2" width="07%"></th>
												  <th colspan="2">Inclusive Dates</th>
												  <th rowspan="2" width="24%">Position / Title</th>
												  <th rowspan="2" width="24%">Department / Agency / Office / Company</th>
												  <th rowspan="2" width="08%">Monthly Salary</th>
												  <th rowspan="2" width="08%">Pay Grade</th>
												  <th rowspan="2" width="10%">Appointment Status</th>
												  <th rowspan="2" width="05%">Govt Service</th>
												</tr>
												<tr>
												  <th width="07%">From</th>
												  <th width="07%">To</th>
												</tr>
											  </thead>
											  <tbody>
												<?php
												$i = 1; 
												$sql = "SELECT eeid, DATE_FORMAT(estart, '%m/%d/%y'), DATE_FORMAT(eend, '%m/%d/%y'), 
															position, company, salary, CONCAT(sgrade,'-',sstep), status, govt
														FROM employees_experiences
														WHERE eid = '$eid'";
												$res = query($sql); 
												while ($row = fetch_array($res)) {
													?>
													<tr>
														<td align="center">
															<a href="e_experience.php?eeid=<?php echo $row[0] ?>" class="btn btn-warning btn-xs"><i class="fa fa-pencil"></i></a>
															<a href="d_experience.php?eeid=<?php echo $row[0] ?>" class="btn btn-danger btn-xs"><i class="fa fa-eraser"></i></a>
														</td>
														<td align="center"><?php echo $row[1] ?></td>
														<td align="center"><?php echo $row[2] ?></td>
														<td align="left"><?php echo $row[3] ?></td>
														<td align="left"><?php echo $row[4] ?></td>
														<td align="right"><?php echo number_format($row[5], 2) ?></td>
														<td align="right"><?php echo $row[6] ?></td>
														<td align="center"><?php echo $row[7] ?></td>
														<td align="center"><?php echo $row[8] == 1 ? 'Yes' : 'No' ?></td>
													</tr>
													<?php
													$i++; 
												}
												free_result($res); 
												if ($i<16) {
													for ($j=$i; $j<16; $j++) {
														?>
														<tr>
															<td>&nbsp;</td>
															<td>&nbsp;</td>
															<td>&nbsp;</td>
															<td>&nbsp;</td>
															<td>&nbsp;</td>
															<td>&nbsp;</td>
															<td>&nbsp;</td>
															<td>&nbsp;</td>
															<td>&nbsp;</td>
														</tr>
														<?php
													}
												}
												?>
											  </tbody>
											</table>
											
										</div>
										<!-- civil service eligibility, work experience -->
										
										
										<!-- voluntary work, learning and development -->
										<div role="tabpanel" class="tab-pane fade" id="tab_content4" aria-labelledby="profile-tab">
										
											<ul class="nav navbar-right panel_toolbox">
												<a href="add_vwork.php?eid=<?php echo $eid ?>" class="btn btn-primary btn-md"><i class="fa fa-plus-square"></i> Add </a>
											</ul>
											<table class="table table-striped table-bordered jambo_table">
											  <thead>
												<tr>
												  <th colspan="6">Voluntary Work / Civic Organizations</th>
												</tr>
												<tr>
												  <th rowspan="2" width="07%"></th>
												  <th rowspan="2" width="39%">Name & Address of Organization</th>
												  <th colspan="2">Inclusive Dates</th>
												  <th rowspan="2" width="10%">No. of Hours</th>
												  <th rowspan="2" width="30%">Position / Nature of Work</th>
												</tr>
												<tr>
												  <th width="07%">From</th>
												  <th width="07%">To</th>
												</tr>
											  </thead>
											  <tbody>
												<?php
												$i = 1; 
												$sql = "SELECT wid, wname, waddr, DATE_FORMAT(wstart, '%m/%d/%y'), 
															DATE_FORMAT(wend, '%m/%d/%y'), nhours, job_desc
														FROM employees_voluntaries
														WHERE eid = '$eid'";
												$res = query($sql); 
												while ($row = fetch_array($res)) {
													?>
													<tr>
														<td align="center">
															<a href="e_vwork.php?wid=<?php echo $row[0] ?>" class="btn btn-warning btn-xs"><i class="fa fa-pencil"></i></a>
															<a href="d_vwork.php?wid=<?php echo $row[0] ?>" class="btn btn-danger btn-xs"><i class="fa fa-eraser"></i></a>
														</td>
														<td align="left"><?php echo $row[1].'<br />'.$row[2] ?></td>
														<td align="center"><?php echo $row[3] ?></td>
														<td align="center"><?php echo $row[4] ?></td>
														<td align="right"><?php echo $row[5] ?></td>
														<td align="left"><?php echo $row[6] ?></td>
													</tr>
													<?php
													$i++; 
												}
												free_result($res); 
												if ($i<8) {
													for ($j=$i; $j<8; $j++) {
														?>
														<tr>
															<td>&nbsp;</td>
															<td>&nbsp;</td>
															<td>&nbsp;</td>
															<td>&nbsp;</td>
															<td>&nbsp;</td>
															<td>&nbsp;</td>
														</tr>
														<?php
													}
												}
												?>
											  </tbody>
											</table>
											
											<ul class="nav navbar-right panel_toolbox">
												<a href="add_training.php?eid=<?php echo $eid ?>" class="btn btn-primary btn-md"><i class="fa fa-plus-square"></i> Add </a>
											</ul>
											<table class="table table-striped table-bordered jambo_table">
											  <thead>
												<tr>
												  <th colspan="7">Learning & Development / Trainings</th>
												</tr>
												<tr>
												  <th rowspan="2" width="07%"></th>
												  <th rowspan="2" width="39%">Title of Learning & Development / Training</th>
												  <th colspan="2">Inclusive Dates</th>
												  <th rowspan="2" width="10%">No. of Hours</th>
												  <th rowspan="2" width="10%">Type of L&D</th>
												  <th rowspan="2" width="20%">Sponsored By</th>
												</tr>
												<tr>
												  <th rowspan="2" width="07%">From</th>
												  <th rowspan="2" width="07%">To</th>
												</tr>
											  </thead>
											  <tbody>
												<?php
												$i = 1; 
												$sql = "SELECT tid, tname, DATE_FORMAT(tstart, '%m/%d/%y'), 
															DATE_FORMAT(tend, '%m/%d/%y'), nhours, ttype, sponsor
														FROM employees_trainings 
														WHERE eid = '$eid'";
												$res = query($sql); 
												while ($row = fetch_array($res)) {
													?>
													<tr>
														<td align="center">
															<a href="e_training.php?tid=<?php echo $row[0] ?>" class="btn btn-warning btn-xs"><i class="fa fa-pencil"></i></a>
															<a href="d_training.php?tid=<?php echo $row[0] ?>" class="btn btn-danger btn-xs"><i class="fa fa-eraser"></i></a>
														</td>
														<td align="left"><?php echo $row[1] ?></td>
														<td align="center"><?php echo $row[2] ?></td>
														<td align="center"><?php echo $row[3] ?></td>
														<td align="right"><?php echo $row[4] ?></td>
														<td align="center"><?php echo $row[5] ?></td>
														<td align="left"><?php echo $row[6] ?></td>
													</tr>
													<?php
													$i++; 
												}
												free_result($res); 
												if ($i<8) {
													for ($j=$i; $j<8; $j++) {
														?>
														<tr>
															<td>&nbsp;</td>
															<td>&nbsp;</td>
															<td>&nbsp;</td>
															<td>&nbsp;</td>
															<td>&nbsp;</td>
															<td>&nbsp;</td>
															<td>&nbsp;</td>
														</tr>
														<?php
													}
												}
												?>
											  </tbody>
											</table>
										
											<table class="table table-striped table-bordered jambo_table">
											  <thead>
												<tr>
												  <th colspan="6">Other Information</th>
												</tr>
												<tr>
												  <th width="07%"></th>
												  <th width="26%">Special Skills / Hobbies</th>
												  <th width="07%"></th>
												  <th width="26%">Non-Academic Recognitions</th>
												  <th width="07%"></th>
												  <th width="26%">Membership in Organizations</th>
												</tr>
											  </thead>
											  <tbody>
											  <?php
											  $init = 0; 
											  for ($i=1; $i<7; $i++) {
												  ?>
												  <tr>
													<td align="center"><?php
														$sql = "SELECT iid, iinfo
																FROM employees_info
																WHERE itype = 1 AND eid = '$eid'
																LIMIT $init, 1";
														$r1_res = query($sql);
														$r1_num = num_rows($r1_res); 
														if ($r1_num > 0) {
															$r1_row = fetch_array($r1_res); 
															?>
															<a href="e_info.php?iid=<?php echo $r1_row[0] ?>" class="btn btn-warning btn-xs"><i class="fa fa-pencil"></i></a>
															<a href="d_info.php?iid=<?php echo $r1_row[0] ?>" class="btn btn-danger btn-xs"><i class="fa fa-eraser"></i></a>
															<?php
														} else {
															?><a href="add_info.php?eid=<?php echo $eid ?>&itype=1" class="btn btn-primary btn-xs"><i class="fa fa-plus-square"></i></a><?php
														}
														?></td>
													<td align="left"><?php
														if ($r1_num > 0) { echo $r1_row[1];
														} else echo '&nbsp;';
														free_result($r1_res); 
														?></td>
													<td align="center"><?php
														$sql = "SELECT iid, iinfo
																FROM employees_info
																WHERE itype = 2 AND eid = '$eid'
																LIMIT $init, 1";
														$r2_res = query($sql);
														$r2_num = num_rows($r2_res); 
														if ($r2_num > 0) {
															$r2_row = fetch_array($r2_res); 
															?>
															<a href="e_info.php?iid=<?php echo $r2_row[0] ?>" class="btn btn-warning btn-xs"><i class="fa fa-pencil"></i></a>
															<a href="d_info.php?iid=<?php echo $r2_row[0] ?>" class="btn btn-danger btn-xs"><i class="fa fa-eraser"></i></a>
															<?php
														} else {
															?><a href="add_info.php?eid=<?php echo $eid ?>&itype=2" class="btn btn-primary btn-xs"><i class="fa fa-plus-square"></i></a><?php
														}
														?></td>
													<td align="left"><?php
														if ($r2_num > 0) { echo $r2_row[1];
														} else echo '&nbsp;';
														free_result($r2_res); 
														?></td>
													<td align="center"><?php
														$sql = "SELECT iid, iinfo
																FROM employees_info
																WHERE itype = 3 AND eid = '$eid'
																LIMIT $init, 1";
														$r3_res = query($sql);
														$r3_num = num_rows($r3_res); 
														if ($r3_num > 0) {
															$r3_row = fetch_array($r3_res); 
															?>
															<a href="e_info.php?iid=<?php echo $r3_row[0] ?>" class="btn btn-warning btn-xs"><i class="fa fa-pencil"></i></a>
															<a href="d_info.php?iid=<?php echo $r3_row[0] ?>" class="btn btn-danger btn-xs"><i class="fa fa-eraser"></i></a>
															<?php
														} else {
															?><a href="add_info.php?eid=<?php echo $eid ?>&itype=2" class="btn btn-primary btn-xs"><i class="fa fa-plus-square"></i></a><?php
														}
														?></td>
													<td align="left"><?php
														if ($r3_num > 0) { echo $r3_row[1];
														} else echo '&nbsp;';
														free_result($r3_res); 
														?></td>
												  </tr>
												  <?php
												  $init++; 
											  }
											  ?>
											  </tbody>
											</table>
											
										</div>
										<!-- voluntary work, learning and development -->
										
										
										<!-- other information -->
										<div role="tabpanel" class="tab-pane fade" id="tab_content5" aria-labelledby="profile-tab">
											<table id="datatable-fixed-header" class="table table-striped table-bordered jambo_table">
											  <thead>
												<tr>
												  <th width="09%">Type</th>
												  <th width="26%">Description</th>
												  <th width="07%">Prop #</th>
												  <th width="07%">PAR</th>
												  <th width="07%">ICS#</th>
												  <th width="07%">Brand<br />Model</th>
												  <th width="07%">Serial#</th>
												  <th width="09%">Supplier</th>
												  <th width="07%">Acquired</th>
												  <th width="07%">Value</th>
												  <th width="07%"></th>
												</tr>
											  </thead>
											  <tbody>
												<?php
												$sql = "SELECT i.iid, c.cname, i.idesc, i.property_num, i.property_ack_num, 
															i.ics, i.brand_name, i.model_name, i.serial, s.company,
															DATE_FORMAT(i.acquired_date, '%m/%d/%y'), i.acquired_value
														FROM inventory i 
															INNER JOIN categories c ON i.cid = c.cid
															INNER JOIN suppliers s ON i.sid = s.sid
														WHERE i.eid = '$eid'
														ORDER BY c.cname, i.idesc";
												$res = query($sql);

												while ($row = fetch_array($res)) {
													?>
													<tr>
													  <td align="center"><?php echo $row[1] ?></td>
													  <td align="center"><a href="item.php?iid=<?php echo $row[0] ?>"><?php echo $row[2] ?></a></td>
													  <td align="center"><?php echo $row[3] ?></td>
													  <td align="center"><?php echo $row[4] ?></td>
													  <td align="center"><?php echo $row[5] ?></td>
													  <td align="center"><?php echo $row[6].'<br />'.$row[7] ?></td>
													  <td align="center"><?php echo $row[8] ?></td>
													  <td align="center"><?php echo $row[9] ?></td>
													  <td align="center"><?php echo $row[10] ?></td>
													  <td align="right"><?php echo number_format($row[11], 2) ?></td>
													  <td align="center"><a href="e_item.php?iid=<?php echo $row[0] ?>" class="btn btn-warning btn-xs"><i class="fa fa-pencil"></i> Edit </a></td>
													</tr>
													<?php
												}

												mysqli_free_result($res);
												?>
											  </tbody>
											</table>
											
										</div>
										<!-- other information -->
										
										
									  </div>
									</div>
								  
								  </div>
								</div>
							  </div>
							
							</div> <!-- end of container of all tables -->

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

					<title>OTC - View Employee-Details</title>

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
												  <p class="h1">Hacking Attempt!</p>
												  <footer class="blockquote-footer"><em>You cannot view other's employee-information. You will be reported to the system administrator.</em></footer>
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

				<title>OTC - View Employee-Details</title>

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
