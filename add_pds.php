<?php

include("db.php");
include("sqli.php");
include("html.php");
define('PAGE', 'add_pds.php'); 

session_start();

if (isset($_SESSION['user_id'])) {
	if (validate_level3(PAGE) == true) {

		if(isset($_POST['submitted'])) { // if submit button has been pressed
			
			$eid = filter_var($_POST['eid'], FILTER_SANITIZE_NUMBER_INT);
			$sex = filter_var($_POST['sex'], FILTER_SANITIZE_STRING);
			$bdate = filter_var($_POST['bdate'], FILTER_SANITIZE_STRING);
			$bplace = filter_var($_POST['bplace'], FILTER_SANITIZE_STRING);
			$civil_stat = filter_var($_POST['civil_stat'], FILTER_SANITIZE_NUMBER_INT);
			$citizenship = filter_var($_POST['citizenship'], FILTER_SANITIZE_NUMBER_INT);
			$citizenship_means = filter_var($_POST['citizenship_means'], FILTER_SANITIZE_NUMBER_INT);
			$country_dual = filter_var($_POST['country_dual'], FILTER_SANITIZE_STRING);
			$height = filter_var($_POST['height'], FILTER_SANITIZE_NUMBER_FLOAT);
			$weight = filter_var($_POST['weight'], FILTER_SANITIZE_NUMBER_FLOAT);
			$blood_type = filter_var($_POST['blood_type'], FILTER_SANITIZE_STRING);
			$res_addr = filter_var($_POST['res_addr'], FILTER_SANITIZE_STRING);
			$perm_addr = filter_var($_POST['perm_addr'], FILTER_SANITIZE_STRING);
			$tel_no = filter_var($_POST['tel_no'], FILTER_SANITIZE_STRING);
			$cp_no = filter_var($_POST['cp_no'], FILTER_SANITIZE_STRING);
			$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
			$tin = filter_var($_POST['tin'], FILTER_SANITIZE_STRING);
			$gsis_id = filter_var($_POST['gsis_id'], FILTER_SANITIZE_STRING);
			$sss_id = filter_var($_POST['sss_id'], FILTER_SANITIZE_STRING);
			$philhealth = filter_var($_POST['philhealth'], FILTER_SANITIZE_STRING);
			$pagibig = filter_var($_POST['pagibig'], FILTER_SANITIZE_STRING);
			$agency_num = filter_var($_POST['agency_num'], FILTER_SANITIZE_STRING);
			
			// check if sex is given
			if ($sex != "") { $a = TRUE; } 
			else { $a = FALSE; $message[] = "Please enter the sex."; }
			// check if bdate is given
			if ($bdate != "") { $b = TRUE; } 
			else { $b = FALSE; $message[] = "Please enter the birth date."; }
			// check if bplace is given
			if ($bplace != "") { $c = TRUE; } 
			else { $c = FALSE; $message[] = "Please enter the birth place."; }
			// check if civil_stat is given
			if ($civil_stat != "") { $d = TRUE; } 
			else { $d = FALSE; $message[] = "Please enter the civil status."; }
			// check if citizenship is given
			if ($citizenship != "") { $e = TRUE; } 
			else { $e = FALSE; $message[] = "Please enter the citizenship."; }
			// check if height is given
			if ($height != "") { $f = TRUE; } 
			else { $f = FALSE; $message[] = "Please enter the height."; }
			// check if weight is given
			if ($weight != "") { $g = TRUE; } 
			else { $g = FALSE; $message[] = "Please enter the weight."; }
			// check if blood_type is given
			if ($blood_type != "") { $h = TRUE; } 
			else { $h = FALSE; $message[] = "Please enter the blood type."; }
			// check if res_addr is given
			if ($res_addr != "") { $i = TRUE; } 
			else { $i = FALSE; $message[] = "Please enter the residential address."; }
			// check if perm_addr is given
			if ($perm_addr != "") { $j = TRUE; } 
			else { $j = FALSE; $message[] = "Please enter the permanent address."; }
			// check if tin is given
			if ($tin != "") { $k = TRUE; } 
			else { $k = FALSE; $message[] = "Please enter the tax identification number."; }
			// check if agency_num is given
			if ($agency_num != "") { $l = TRUE; } 
			else { $l = FALSE; $message[] = "Please enter the agency employee number."; }
			
			// If data pass all tests, proceed
			if ( $a && $b && $c && $d && $e && $f && $g && $h && $i && $j && $k && $l ) {
				
				// check for double entry first
				$sql = "SELECT pid 
						FROM employees_pds 
						WHERE eid = '$eid'";
				$res = query($sql); 
				$num = num_rows($res); 
				
				if ($num > 0) {
					?>
					<div class="alert alert-danger alert-dismissible fade in" role="alert">
						<strong>A record already exists! </strong><br /><br />
						<a href="employee.php?eid=<?php echo $row[0] ?>">Click here to view the record</a>
					</div>
					<?php
				} else {
					$sql = "INSERT INTO employees_pds( 
								eid, sex, bdate, bplace, civil_stat, citizenship, citizenship_means, country_dual, height, weight, 
								blood_type, res_addr, perm_addr, tel_no, cp_no, email, tin, gsis_id, sss_id, philhealth, pagibig, agency_num
							)
							VALUES( 
								'$eid', '$sex', '$bdate', '$bplace', '$civil_stat', '$citizenship', '$citizenship_means', '$country_dual', '$height', '$weight', 
								'$blood_type', '$res_addr', '$perm_addr', '$tel_no', '$cp_no', '$email', '$tin', '$gsis_id', '$sss_id', '$philhealth', '$pagibig', '$agency_num'
							)";
					if (query($sql)) {
						$pid = mysqli_insert_id($connection);
						// log the activity
						log_user(1, 'employees_pds', $pid);
						
						header("Location:employee.php?eid=".$eid."&success=1");
						exit();
					} else {
						?>
						<div class="alert alert-danger alert-dismissible fade in" role="alert">
							<strong>Could not add employee-basic information because: </strong><br />
							<b><?php echo mysqli_error($connection) ?></b><br />
							The query was <?php echo $sql ?>.<br />
						</div>
						<?PHP
					}
				}
			} else {
				$serialized_message = serialize($message);
				// redirect the user to add_pds.php page
				header('location: add_pds.php?serialized_message='.$serialized_message.'&eid='.$eid.'&sex='.$sex.'&bdate='.$bdate.'&bplace='.$bplace.'&civil_stat='.$civil_stat.'&citizenship='.$citizenship.'&citizenship_means='.$citizenship_means.'&country_dual='.$country_dual.'&height='.$height.'&weight='.$weight.'&blood_type='.$blood_type.'&res_addr='.$res_addr.'&perm_addr='.$perm_addr.'&tel_no='.$tel_no.'&cp_no='.$cp_no.'&email='.$email.'&tin='.$tin.'&gsis_id='.$gsis_id.'&sss_id='.$sss_id.'&philhealth='.$philhealth.'&pagibig='.$pagibig.'&agency_num='.$agency_num);
				exit();
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
				  
				<title>OTC | Add Employee-Information</title>

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
							<h3>Add Employee-Information</h3>
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
						  if (isset($_GET['eid'])) {
							  $eid = $_GET['eid'];
							  
							  // check if there is an existing pds record for this employee
							  $sql = "SELECT pid
									  FROM employees_pds
									  WHERE eid = '$eid'";
							  $res = query($sql); 
							  $num = num_rows($res); 
							  
							  if ($num > 0) {
								  $row = fetch_array($res); 
								  
								  // redirect the user to e_pds.php page
								  header('location: e_pds.php?eid='.$eid);
								  exit();
							  } 
						  }
						  
						  if (isset($_GET['serialized_message'])) {
							  $message = unserialize($_GET['serialized_message']);
							  ?>
							  <div class="col-md-12 col-sm-12 col-xs-12">
								<div class="x_panel">
								  <div class="x_content">
									  <div class="alert alert-danger alert-dismissible fade in" role="alert">
										<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
										</button>
										<strong>The following problems occurred: </strong><br />
										<?php
										foreach ($message as $key => $value) {
											echo $value; echo '<br />';
										}
										?>
									  </div>
								  </div>
								</div>
							  </div>
							  <div class="clearfix"></div>
							  <?php
						  }
						  ?>
						  <div class="col-md-12 col-sm-12 col-xs-12">
							<div class="x_panel">
							  <div class="x_content">
								<br />
									<?php
									if (isset($_GET['eid'])) {
										$eid = $_GET['eid'];
										
										$sql = "SELECT CONCAT(lname,', ',fname) FROM employees WHERE eid = '$eid'";
										$eres = query($sql); 
										$erow = fetch_array($eres); 
									}
									?>
									<form action="add_pds.php" method="POST" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="ecode">Employee Name </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="ecode" name="ecode" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $erow[0] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="sex">Sex <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <select id="sex" name="sex" required="required" class="form-control col-md-7 col-xs-12">
										    <?php
											if (isset ($_GET['sex'])) {
												if ($_GET['sex'] == 'M') {
													?>
													<option value="M" Selected>Male</option>
													<option value="F">Female</option>
													<?php
												} elseif ($_GET['sex'] == 'F') {
													?>
													<option value="M">Male</option>
													<option value="F" Selected>Female</option>
													<?php
												}
											} else {
												?>
												<option value="M">Male</option>
												<option value="F">Female</option>
												<?php
											}
											?>
										  </select>
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="bdate">Birth Date <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="date" id="bdate" name="bdate" required="required" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['bdate'])) {
											  ?>value="<?php echo $_GET['bdate'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="bplace">Birth Place <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="bplace" name="bplace" required="required" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['bplace'])) {
											  ?>value="<?php echo $_GET['bplace'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="civil_stat">Civil Status <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <select id="civil_stat" name="civil_stat" required="required" class="form-control col-md-7 col-xs-12">
										    <?php
											if (isset ($_GET['civil_stat'])) {
												if ($_GET['civil_stat'] == 1) {
													?>
													<option value="1" Selected>Single</option>
													<option value="2">Married</option>
													<option value="3">Separated</option>
													<option value="4">Widowed</option>
													<?php
												} elseif ($_GET['civil_stat'] == 2) {
													?>
													<option value="1">Single</option>
													<option value="2" Selected>Married</option>
													<option value="3">Separated</option>
													<option value="4">Widowed</option>
													<?php
												} elseif ($_GET['civil_stat'] == 3) {
													?>
													<option value="1">Single</option>
													<option value="2">Married</option>
													<option value="3" Selected>Separated</option>
													<option value="4">Widowed</option>
													<?php
												} elseif ($_GET['civil_stat'] == 4) {
													?>
													<option value="1">Single</option>
													<option value="2">Married</option>
													<option value="3">Separated</option>
													<option value="4" Selected>Widowed</option>
													<?php
												}
											} else {
												?>
												<option value="1">Single</option>
												<option value="2">Married</option>
												<option value="3">Separated</option>
												<option value="4">Widowed</option>
												<?php
											}
											?>
										  </select>
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="citizenship">Citizenship <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <select id="citizenship" name="citizenship" class="form-control col-md-7 col-xs-12">
										    <?php
											if (isset ($_GET['citizenship'])) {
												if ($_GET['citizenship'] == 1) {
													?>
													<option value="1" Selected>Filipino</option>
													<option value="2">Dual Citizen</option>
													<?php
												} elseif ($_GET['citizenship'] == 2) {
													?>
													<option value="1">Filipino</option>
													<option value="2" Selected>Dual Citizen</option>
													<?php
												}
											} else {
												?>
												<option value="1">Filipino</option>
												<option value="2">Dual Citizen</option>
												<?php
											}
											?>
										  </select>
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="citizenship_means">Citizenship Acquired Thru </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <select id="citizenship_means" name="citizenship_means" class="form-control col-md-7 col-xs-12">
										    <?php
											if (isset ($_GET['citizenship_means'])) {
												if ($_GET['citizenship_means'] == 1) {
													?>
													<option value="1" Selected>By Birth</option>
													<option value="2">By Naturalization</option>
													<?php
												} elseif ($_GET['citizenship_means'] == 2) {
													?>
													<option value="1">By Birth</option>
													<option value="2" Selected>By Naturalization</option>
													<?php
												}
											} else {
												?>
												<option value="1">By Birth</option>
												<option value="2">By Naturalization</option>
												<?php
											}
											?>
										  </select>
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="country_dual">Country (If Dual Citizen) </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="country_dual" name="country_dual" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['country_dual'])) {
											  ?>value="<?php echo $_GET['country_dual'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="height">Height (cm) <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="height" name="height" required="required" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['height'])) {
											  ?>value="<?php echo $_GET['height'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="weight">Weight (kg) <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="weight" name="weight" required="required" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['weight'])) {
											  ?>value="<?php echo $_GET['weight'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="blood_type">Blood Type <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="blood_type" name="blood_type" required="required" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['blood_type'])) {
											  ?>value="<?php echo $_GET['blood_type'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="res_addr">Residencial Address <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="res_addr" name="res_addr" required="required" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['res_addr'])) {
											  ?>value="<?php echo $_GET['res_addr'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="perm_addr">Permanent Address <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="perm_addr" name="perm_addr" required="required" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['perm_addr'])) {
											  ?>value="<?php echo $_GET['perm_addr'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="tel_no">Telephone No. </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="tel_no" name="tel_no" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['tel_no'])) {
											  ?>value="<?php echo $_GET['tel_no'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="cp_no">Cellphone No. </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="cp_no" name="cp_no" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['cp_no'])) {
											  ?>value="<?php echo $_GET['cp_no'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Email Address </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="email" name="email" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['email'])) {
											  ?>value="<?php echo $_GET['email'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="tin">TIN <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="tin" name="tin" required="required" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['tin'])) {
											  ?>value="<?php echo $_GET['tin'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="gsis_id">GSIS ID </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="gsis_id" name="gsis_id" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['gsis_id'])) {
											  ?>value="<?php echo $_GET['gsis_id'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="sss_id">SSS ID </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="sss_id" name="sss_id" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['sss_id'])) {
											  ?>value="<?php echo $_GET['sss_id'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="philhealth">PHILHEALTH ID </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="philhealth" name="philhealth" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['philhealth'])) {
											  ?>value="<?php echo $_GET['philhealth'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="pagibig">PAGIBIG ID </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="pagibig" name="pagibig" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['pagibig'])) {
											  ?>value="<?php echo $_GET['pagibig'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="agency_num">Agency Employee No. </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="agency_num" name="agency_num" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['agency_num'])) {
											  ?>value="<?php echo $_GET['agency_num'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="ln_solid"></div>
									  <div class="form-group">
										<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
										  <input type="hidden" name="submitted" value="1">
										  <input type="hidden" name="eid" value="<?php echo $eid ?>">
										  <button class="btn btn-primary" type="reset">Reset</button>
										  <button type="submit" class="btn btn-success">Submit</button>
										</div>
									  </div>

									</form>
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
				<!-- Custom Theme Scripts -->
				<script src="build/js/custom.min.js"></script>
				
			  </body>
			</html>
			<?PHP
		}
	} else {
		// check if user is a valid employee
		$sql = "SELECT eid FROM users WHERE user_id = '$_SESSION[user_id]' AND active = 1";
		$vres = query($sql); $vrow = fetch_array($vres); $emp_id = $vrow[0]; 
		
		if ($emp_id != 0) {
			
			if(isset($_POST['submitted'])) { // if submit button has been pressed
				$eid = filter_var($_POST['eid'], FILTER_SANITIZE_NUMBER_INT);
				$sex = filter_var($_POST['sex'], FILTER_SANITIZE_STRING);
				$bdate = filter_var($_POST['bdate'], FILTER_SANITIZE_STRING);
				$bplace = filter_var($_POST['bplace'], FILTER_SANITIZE_STRING);
				$civil_stat = filter_var($_POST['civil_stat'], FILTER_SANITIZE_NUMBER_INT);
				$citizenship = filter_var($_POST['citizenship'], FILTER_SANITIZE_NUMBER_INT);
				$citizenship_means = filter_var($_POST['citizenship_means'], FILTER_SANITIZE_NUMBER_INT);
				$country_dual = filter_var($_POST['country_dual'], FILTER_SANITIZE_STRING);
				$height = filter_var($_POST['height'], FILTER_SANITIZE_NUMBER_FLOAT);
				$weight = filter_var($_POST['weight'], FILTER_SANITIZE_NUMBER_FLOAT);
				$blood_type = filter_var($_POST['blood_type'], FILTER_SANITIZE_STRING);
				$res_addr = filter_var($_POST['res_addr'], FILTER_SANITIZE_STRING);
				$perm_addr = filter_var($_POST['perm_addr'], FILTER_SANITIZE_STRING);
				$tel_no = filter_var($_POST['tel_no'], FILTER_SANITIZE_STRING);
				$cp_no = filter_var($_POST['cp_no'], FILTER_SANITIZE_STRING);
				$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
				$tin = filter_var($_POST['tin'], FILTER_SANITIZE_STRING);
				$gsis_id = filter_var($_POST['gsis_id'], FILTER_SANITIZE_STRING);
				$sss_id = filter_var($_POST['sss_id'], FILTER_SANITIZE_STRING);
				$philhealth = filter_var($_POST['philhealth'], FILTER_SANITIZE_STRING);
				$pagibig = filter_var($_POST['pagibig'], FILTER_SANITIZE_STRING);
				$agency_num = filter_var($_POST['agency_num'], FILTER_SANITIZE_STRING);
				
				// check if sex is given
				if ($sex != "") { $a = TRUE; } 
				else { $a = FALSE; $message[] = "Please enter the sex."; }
				// check if bdate is given
				if ($bdate != "") { $b = TRUE; } 
				else { $b = FALSE; $message[] = "Please enter the birth date."; }
				// check if bplace is given
				if ($bplace != "") { $c = TRUE; } 
				else { $c = FALSE; $message[] = "Please enter the birth place."; }
				// check if civil_stat is given
				if ($civil_stat != "") { $d = TRUE; } 
				else { $d = FALSE; $message[] = "Please enter the civil status."; }
				// check if citizenship is given
				if ($citizenship != "") { $e = TRUE; } 
				else { $e = FALSE; $message[] = "Please enter the citizenship."; }
				// check if height is given
				if ($height != "") { $f = TRUE; } 
				else { $f = FALSE; $message[] = "Please enter the height."; }
				// check if weight is given
				if ($weight != "") { $g = TRUE; } 
				else { $g = FALSE; $message[] = "Please enter the weight."; }
				// check if blood_type is given
				if ($blood_type != "") { $h = TRUE; } 
				else { $h = FALSE; $message[] = "Please enter the blood type."; }
				// check if res_addr is given
				if ($res_addr != "") { $i = TRUE; } 
				else { $i = FALSE; $message[] = "Please enter the residential address."; }
				// check if perm_addr is given
				if ($perm_addr != "") { $j = TRUE; } 
				else { $j = FALSE; $message[] = "Please enter the permanent address."; }
				// check if tin is given
				if ($tin != "") { $k = TRUE; } 
				else { $k = FALSE; $message[] = "Please enter the tax identification number."; }
				// check if agency_num is given
				if ($agency_num != "") { $l = TRUE; } 
				else { $l = FALSE; $message[] = "Please enter the agency employee number."; }
				
				// If data pass all tests, proceed
				if ( $a && $b && $c && $d && $e && $f && $g && $h && $i && $j && $k && $l ) {
					
					// check for double entry first
					$sql = "SELECT pid 
							FROM employees_pds 
							WHERE eid = '$eid'";
					$res = query($sql); 
					$num = num_rows($res); 
					
					if ($num > 0) {
						?>
						<div class="alert alert-danger alert-dismissible fade in" role="alert">
							<strong>A record already exists! </strong><br /><br />
							<a href="employee.php?eid=<?php echo $row[0] ?>">Click here to view the record</a>
						</div>
						<?php
					} else {
						$sql = "INSERT INTO employees_pds( 
									eid, sex, bdate, bplace, civil_stat, citizenship, citizenship_means, country_dual, height, weight, 
									blood_type, res_addr, perm_addr, tel_no, cp_no, email, tin, gsis_id, sss_id, philhealth, pagibig, agency_num
								)
								VALUES( 
									'$eid', '$sex', '$bdate', '$bplace', '$civil_stat', '$citizenship', '$citizenship_means', '$country_dual', '$height', '$weight', 
									'$blood_type', '$res_addr', '$perm_addr', '$tel_no', '$cp_no', '$email', '$tin', '$gsis_id', '$sss_id', '$philhealth', '$pagibig', '$agency_num'
								)";
						if (query($sql)) {
							$pid = mysqli_insert_id($connection);
							// log the activity
							log_user(1, 'employees_pds', $pid);
							
							header("Location:employee.php?eid=".$eid."&success=1");
							exit();
						} else {
							?>
							<div class="alert alert-danger alert-dismissible fade in" role="alert">
								<strong>Could not add employee-basic information because: </strong><br />
								<b><?php echo mysqli_error($connection) ?></b><br />
								The query was <?php echo $sql ?>.<br />
							</div>
							<?PHP
						}
					}
				} else {
					$serialized_message = serialize($message);
					// redirect the user to add_pds.php page
					header('location: add_pds.php?serialized_message='.$serialized_message.'&eid='.$eid.'&sex='.$sex.'&bdate='.$bdate.'&bplace='.$bplace.'&civil_stat='.$civil_stat.'&citizenship='.$citizenship.'&citizenship_means='.$citizenship_means.'&country_dual='.$country_dual.'&height='.$height.'&weight='.$weight.'&blood_type='.$blood_type.'&res_addr='.$res_addr.'&perm_addr='.$perm_addr.'&tel_no='.$tel_no.'&cp_no='.$cp_no.'&email='.$email.'&tin='.$tin.'&gsis_id='.$gsis_id.'&sss_id='.$sss_id.'&philhealth='.$philhealth.'&pagibig='.$pagibig.'&agency_num='.$agency_num);
					exit();
				}
			} else {
				$eid = $_GET['eid'];
				
				if ($emp_id == $eid) {
					// show form
					?>
					<!DOCTYPE html>
					<html lang="en">
					  <head>
						<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
						<!-- Meta, title, CSS, favicons, etc. -->
						<meta charset="utf-8">
						<meta http-equiv="X-UA-Compatible" content="IE=edge">
						<meta name="viewport" content="width=device-width, initial-scale=1">
						  
						<title>OTC | Add Employee-Information</title>

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
									<h3>Add Employee-Information</h3>
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
								  if (isset($_GET['eid'])) {
									  $eid = $_GET['eid'];
									  
									  // check if there is an existing pds record for this employee
									  $sql = "SELECT pid
											  FROM employees_pds
											  WHERE eid = '$eid'";
									  $res = query($sql); 
									  $num = num_rows($res); 
									  
									  if ($num > 0) {
										  $row = fetch_array($res); 
										  
										  // redirect the user to e_pds.php page
										  header('location: e_pds.php?eid='.$eid);
										  exit();
									  } 
								  }
								  
								  if (isset($_GET['serialized_message'])) {
									  $message = unserialize($_GET['serialized_message']);
									  ?>
									  <div class="col-md-12 col-sm-12 col-xs-12">
										<div class="x_panel">
										  <div class="x_content">
											  <div class="alert alert-danger alert-dismissible fade in" role="alert">
												<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
												</button>
												<strong>The following problems occurred: </strong><br />
												<?php
												foreach ($message as $key => $value) {
													echo $value; echo '<br />';
												}
												?>
											  </div>
										  </div>
										</div>
									  </div>
									  <div class="clearfix"></div>
									  <?php
								  }
								  ?>
								  <div class="col-md-12 col-sm-12 col-xs-12">
									<div class="x_panel">
									  <div class="x_content">
										<br />
											<?php
											if (isset($_GET['eid'])) {
												$eid = $_GET['eid'];
												
												$sql = "SELECT CONCAT(lname,', ',fname) FROM employees WHERE eid = '$eid'";
												$eres = query($sql); 
												$erow = fetch_array($eres); 
											}
											?>
											<form action="add_pds.php" method="POST" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
											  <div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="ecode">Employee Name </label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <input type="text" id="ecode" name="ecode" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $erow[0] ?>">
												</div>
											  </div>
											  <div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="sex">Sex <span class="required">*</span></label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <select id="sex" name="sex" required="required" class="form-control col-md-7 col-xs-12">
													<?php
													if (isset ($_GET['sex'])) {
														if ($_GET['sex'] == 'M') {
															?>
															<option value="M" Selected>Male</option>
															<option value="F">Female</option>
															<?php
														} elseif ($_GET['sex'] == 'F') {
															?>
															<option value="M">Male</option>
															<option value="F" Selected>Female</option>
															<?php
														}
													} else {
														?>
														<option value="M">Male</option>
														<option value="F">Female</option>
														<?php
													}
													?>
												  </select>
												</div>
											  </div>
											  <div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="bdate">Birth Date <span class="required">*</span></label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <input type="date" id="bdate" name="bdate" required="required" class="form-control col-md-7 col-xs-12" 
												  <?php
												  if (isset ($_GET['bdate'])) {
													  ?>value="<?php echo $_GET['bdate'] ?>"<?php
												  }
												  ?>
												  >
												</div>
											  </div>
											  <div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="bplace">Birth Place <span class="required">*</span></label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <input type="text" id="bplace" name="bplace" required="required" class="form-control col-md-7 col-xs-12" 
												  <?php
												  if (isset ($_GET['bplace'])) {
													  ?>value="<?php echo $_GET['bplace'] ?>"<?php
												  }
												  ?>
												  >
												</div>
											  </div>
											  <div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="civil_stat">Civil Status <span class="required">*</span></label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <select id="civil_stat" name="civil_stat" required="required" class="form-control col-md-7 col-xs-12">
													<?php
													if (isset ($_GET['civil_stat'])) {
														if ($_GET['civil_stat'] == 1) {
															?>
															<option value="1" Selected>Single</option>
															<option value="2">Married</option>
															<option value="3">Separated</option>
															<option value="4">Widowed</option>
															<?php
														} elseif ($_GET['civil_stat'] == 2) {
															?>
															<option value="1">Single</option>
															<option value="2" Selected>Married</option>
															<option value="3">Separated</option>
															<option value="4">Widowed</option>
															<?php
														} elseif ($_GET['civil_stat'] == 3) {
															?>
															<option value="1">Single</option>
															<option value="2">Married</option>
															<option value="3" Selected>Separated</option>
															<option value="4">Widowed</option>
															<?php
														} elseif ($_GET['civil_stat'] == 4) {
															?>
															<option value="1">Single</option>
															<option value="2">Married</option>
															<option value="3">Separated</option>
															<option value="4" Selected>Widowed</option>
															<?php
														}
													} else {
														?>
														<option value="1">Single</option>
														<option value="2">Married</option>
														<option value="3">Separated</option>
														<option value="4">Widowed</option>
														<?php
													}
													?>
												  </select>
												</div>
											  </div>
											  <div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="citizenship">Citizenship <span class="required">*</span></label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <select id="citizenship" name="citizenship" class="form-control col-md-7 col-xs-12">
													<?php
													if (isset ($_GET['citizenship'])) {
														if ($_GET['citizenship'] == 1) {
															?>
															<option value="1" Selected>Filipino</option>
															<option value="2">Dual Citizen</option>
															<?php
														} elseif ($_GET['citizenship'] == 2) {
															?>
															<option value="1">Filipino</option>
															<option value="2" Selected>Dual Citizen</option>
															<?php
														}
													} else {
														?>
														<option value="1">Filipino</option>
														<option value="2">Dual Citizen</option>
														<?php
													}
													?>
												  </select>
												</div>
											  </div>
											  <div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="citizenship_means">Citizenship Acquired Thru </label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <select id="citizenship_means" name="citizenship_means" class="form-control col-md-7 col-xs-12">
													<?php
													if (isset ($_GET['citizenship_means'])) {
														if ($_GET['citizenship_means'] == 1) {
															?>
															<option value="1" Selected>By Birth</option>
															<option value="2">By Naturalization</option>
															<?php
														} elseif ($_GET['citizenship_means'] == 2) {
															?>
															<option value="1">By Birth</option>
															<option value="2" Selected>By Naturalization</option>
															<?php
														}
													} else {
														?>
														<option value="1">By Birth</option>
														<option value="2">By Naturalization</option>
														<?php
													}
													?>
												  </select>
												</div>
											  </div>
											  <div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="country_dual">Country (If Dual Citizen) </label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <input type="text" id="country_dual" name="country_dual" class="form-control col-md-7 col-xs-12" 
												  <?php
												  if (isset ($_GET['country_dual'])) {
													  ?>value="<?php echo $_GET['country_dual'] ?>"<?php
												  }
												  ?>
												  >
												</div>
											  </div>
											  <div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="height">Height (cm) <span class="required">*</span></label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <input type="text" id="height" name="height" required="required" class="form-control col-md-7 col-xs-12" 
												  <?php
												  if (isset ($_GET['height'])) {
													  ?>value="<?php echo $_GET['height'] ?>"<?php
												  }
												  ?>
												  >
												</div>
											  </div>
											  <div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="weight">Weight (kg) <span class="required">*</span></label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <input type="text" id="weight" name="weight" required="required" class="form-control col-md-7 col-xs-12" 
												  <?php
												  if (isset ($_GET['weight'])) {
													  ?>value="<?php echo $_GET['weight'] ?>"<?php
												  }
												  ?>
												  >
												</div>
											  </div>
											  <div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="blood_type">Blood Type <span class="required">*</span></label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <input type="text" id="blood_type" name="blood_type" required="required" class="form-control col-md-7 col-xs-12" 
												  <?php
												  if (isset ($_GET['blood_type'])) {
													  ?>value="<?php echo $_GET['blood_type'] ?>"<?php
												  }
												  ?>
												  >
												</div>
											  </div>
											  <div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="res_addr">Residencial Address <span class="required">*</span></label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <input type="text" id="res_addr" name="res_addr" required="required" class="form-control col-md-7 col-xs-12" 
												  <?php
												  if (isset ($_GET['res_addr'])) {
													  ?>value="<?php echo $_GET['res_addr'] ?>"<?php
												  }
												  ?>
												  >
												</div>
											  </div>
											  <div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="perm_addr">Permanent Address <span class="required">*</span></label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <input type="text" id="perm_addr" name="perm_addr" required="required" class="form-control col-md-7 col-xs-12" 
												  <?php
												  if (isset ($_GET['perm_addr'])) {
													  ?>value="<?php echo $_GET['perm_addr'] ?>"<?php
												  }
												  ?>
												  >
												</div>
											  </div>
											  <div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="tel_no">Telephone No. </label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <input type="text" id="tel_no" name="tel_no" class="form-control col-md-7 col-xs-12" 
												  <?php
												  if (isset ($_GET['tel_no'])) {
													  ?>value="<?php echo $_GET['tel_no'] ?>"<?php
												  }
												  ?>
												  >
												</div>
											  </div>
											  <div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="cp_no">Cellphone No. </label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <input type="text" id="cp_no" name="cp_no" class="form-control col-md-7 col-xs-12" 
												  <?php
												  if (isset ($_GET['cp_no'])) {
													  ?>value="<?php echo $_GET['cp_no'] ?>"<?php
												  }
												  ?>
												  >
												</div>
											  </div>
											  <div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Email Address </label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <input type="text" id="email" name="email" class="form-control col-md-7 col-xs-12" 
												  <?php
												  if (isset ($_GET['email'])) {
													  ?>value="<?php echo $_GET['email'] ?>"<?php
												  }
												  ?>
												  >
												</div>
											  </div>
											  <div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="tin">TIN <span class="required">*</span></label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <input type="text" id="tin" name="tin" required="required" class="form-control col-md-7 col-xs-12" 
												  <?php
												  if (isset ($_GET['tin'])) {
													  ?>value="<?php echo $_GET['tin'] ?>"<?php
												  }
												  ?>
												  >
												</div>
											  </div>
											  <div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="gsis_id">GSIS ID </label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <input type="text" id="gsis_id" name="gsis_id" class="form-control col-md-7 col-xs-12" 
												  <?php
												  if (isset ($_GET['gsis_id'])) {
													  ?>value="<?php echo $_GET['gsis_id'] ?>"<?php
												  }
												  ?>
												  >
												</div>
											  </div>
											  <div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="sss_id">SSS ID </label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <input type="text" id="sss_id" name="sss_id" class="form-control col-md-7 col-xs-12" 
												  <?php
												  if (isset ($_GET['sss_id'])) {
													  ?>value="<?php echo $_GET['sss_id'] ?>"<?php
												  }
												  ?>
												  >
												</div>
											  </div>
											  <div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="philhealth">PHILHEALTH ID </label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <input type="text" id="philhealth" name="philhealth" class="form-control col-md-7 col-xs-12" 
												  <?php
												  if (isset ($_GET['philhealth'])) {
													  ?>value="<?php echo $_GET['philhealth'] ?>"<?php
												  }
												  ?>
												  >
												</div>
											  </div>
											  <div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="pagibig">PAGIBIG ID </label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <input type="text" id="pagibig" name="pagibig" class="form-control col-md-7 col-xs-12" 
												  <?php
												  if (isset ($_GET['pagibig'])) {
													  ?>value="<?php echo $_GET['pagibig'] ?>"<?php
												  }
												  ?>
												  >
												</div>
											  </div>
											  <div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="agency_num">Agency Employee No. </label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <input type="text" id="agency_num" name="agency_num" class="form-control col-md-7 col-xs-12" 
												  <?php
												  if (isset ($_GET['agency_num'])) {
													  ?>value="<?php echo $_GET['agency_num'] ?>"<?php
												  }
												  ?>
												  >
												</div>
											  </div>
											  <div class="ln_solid"></div>
											  <div class="form-group">
												<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
												  <input type="hidden" name="submitted" value="1">
												  <input type="hidden" name="eid" value="<?php echo $eid ?>">
												  <button class="btn btn-primary" type="reset">Reset</button>
												  <button type="submit" class="btn btn-success">Submit</button>
												</div>
											  </div>

											</form>
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
						<!-- Custom Theme Scripts -->
						<script src="build/js/custom.min.js"></script>
						
					  </body>
					</html>
					<?PHP
				} else {
					// hacking attempt
					?>
					<!DOCTYPE html>
					<html lang="en">
					  <head>
						<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
						<!-- Meta, title, CSS, favicons, etc. -->
						<meta charset="utf-8">
						<meta http-equiv="X-UA-Compatible" content="IE=edge">
						<meta name="viewport" content="width=device-width, initial-scale=1">

						<title>OTC - Add Personal Data Information</title>

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
													  <footer class="blockquote-footer"><em>You cannot update other's employee-information. You will be reported to the system administrator.</em></footer>
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
			// invalid user-rights
			?>
			<!DOCTYPE html>
			<html lang="en">
			  <head>
				<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
				<!-- Meta, title, CSS, favicons, etc. -->
				<meta charset="utf-8">
				<meta http-equiv="X-UA-Compatible" content="IE=edge">
				<meta name="viewport" content="width=device-width, initial-scale=1">

				<title>OTC - Add Employee-Personal Information</title>

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

