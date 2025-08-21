<?php

include("db.php");
include("sqli.php");
include("html.php");
define('PAGE', 'add_family.php'); 

session_start();

if (isset($_SESSION['user_id'])) {
	if (validate_level3(PAGE) == true) {

		if(isset($_POST['submitted'])) { // if submit button has been pressed
			
			$eid = filter_var($_POST['eid'], FILTER_SANITIZE_NUMBER_INT);
			$s_lname = filter_var($_POST['s_lname'], FILTER_SANITIZE_STRING);
			$s_fname = filter_var($_POST['s_fname'], FILTER_SANITIZE_STRING);
			$s_mname = filter_var($_POST['s_mname'], FILTER_SANITIZE_STRING);
			$s_ename = filter_var($_POST['s_ename'], FILTER_SANITIZE_STRING);
			$s_occupation = filter_var($_POST['s_occupation'], FILTER_SANITIZE_STRING);
			$s_employer = filter_var($_POST['s_employer'], FILTER_SANITIZE_STRING);
			$s_employer_addr = filter_var($_POST['s_employer_addr'], FILTER_SANITIZE_STRING);
			$s_employer_num = filter_var($_POST['s_employer_num'], FILTER_SANITIZE_STRING);
			$f_lname = filter_var($_POST['f_lname'], FILTER_SANITIZE_STRING);
			$f_fname = filter_var($_POST['f_fname'], FILTER_SANITIZE_STRING);
			$f_mname = filter_var($_POST['f_mname'], FILTER_SANITIZE_STRING);
			$f_ename = filter_var($_POST['f_ename'], FILTER_SANITIZE_STRING);
			$m_lname = filter_var($_POST['m_lname'], FILTER_SANITIZE_STRING);
			$m_fname = filter_var($_POST['m_fname'], FILTER_SANITIZE_STRING);
			$m_mname = filter_var($_POST['m_mname'], FILTER_SANITIZE_STRING);
			$m_ename = filter_var($_POST['m_ename'], FILTER_SANITIZE_STRING);
			
			// check if father's first name is given
			if ($f_fname != "") { $a = TRUE; } 
			else { $a = FALSE; $message[] = "Please enter the first name of father."; }
			// check if father's middle name is given
			if ($f_mname != "") { $b = TRUE; } 
			else { $b = FALSE; $message[] = "Please enter the middle name of father."; }
			// check if father's last name is given
			if ($f_lname != "") { $c = TRUE; } 
			else { $c = FALSE; $message[] = "Please enter the last name of father."; }
			// check if mother's first name is given
			if ($m_fname != "") { $d = TRUE; } 
			else { $d = FALSE; $message[] = "Please enter the first name of mother."; }
			// check if mother's middle name is given
			if ($m_mname != "") { $e = TRUE; } 
			else { $e = FALSE; $message[] = "Please enter the middle name of mother."; }
			// check if mother's last name is given
			if ($m_lname != "") { $f = TRUE; } 
			else { $f = FALSE; $message[] = "Please enter the last name of mother."; }
			
			// If data pass all tests, proceed
			if ( $a && $b && $c && $d && $e && $f ) {
				
				// check for double entry first
				$sql = "SELECT fid 
						FROM employees_families 
						WHERE eid = '$eid'";
				$res = query($sql); 
				$num = num_rows($res); 
				
				if ($num > 0) {
					?>
					<div class="alert alert-danger alert-dismissible fade in" role="alert">
						<strong>Record already exists! </strong><br /><br />
						<a href="employee.php?eid=<?php echo $eid ?>">Click here to view the record</a>
					</div>
					<?php
				} else {
					$sql = "INSERT INTO employees_families( 
								eid, s_lname, s_fname, s_mname, s_ename, s_occupation, s_employer, s_employer_addr, 
								s_employer_num, f_lname, f_fname, f_mname, f_ename, m_lname, m_fname, m_mname, m_ename
							)
							VALUES( 
								'$eid', '$s_lname', '$s_fname', '$s_mname', '$s_ename', '$s_occupation', '$s_employer', '$s_employer_addr', 
								'$s_employer_num', '$f_lname', '$f_fname', '$f_mname', '$f_ename', '$m_lname', '$m_fname', '$m_mname', '$m_ename'
							)";
					if (query($sql)) {
						$fid = mysqli_insert_id($connection);
						// log the activity
						log_user(1, 'employees_families', $fid);
						
						header("Location:employee.php?eid=".$eid."#tab_content2");
						exit();
					} else {
						?>
						<div class="alert alert-danger alert-dismissible fade in" role="alert">
							<strong>Could not add employee-family because: </strong><br />
							<b><?php echo mysqli_error($connection) ?></b><br />
							The query was <?php echo $sql ?>.<br />
						</div>
						<?PHP
					}
				}
			} else {
				$serialized_message = serialize($message);
				// redirect the user to index page
				header('location: add_family.php?serialized_message='.$serialized_message.'&fdate='.$fdate.'&from_user_id='.$from_user_id.'&to_user_id='.$to_user_id.'&remarks='.$remarks.'&recipient='.$recipient.'&rdate='.$rdate);
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
				  
				<title>OTC | Add Employee's Family Background</title>

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
							<h3>Add Employee's Family Background</h3>
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
										
										// check if there is an existing family-record for this employee
										$sql = "SELECT fid 
												FROM employees_families
												WHERE eid = '$eid'";
										$res = query($sql); 
										$num = num_rows($res); 
										
										if ($num > 0) {
											$row = fetch_array($res); 
											header("Location:e_family.php?fid=".$row[0]);
											exit();
										} else {
											$sql = "SELECT CONCAT(lname,', ',fname) FROM employees WHERE eid = '$eid'";
											$eres = query($sql); 
											$erow = fetch_array($eres); 
										}
										
									}
									?>
									<form action="add_family.php" method="POST" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="ecode">Employee Name </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="ecode" name="ecode" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $erow[0] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="s_lname">Spouse's Surname </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="s_lname" name="s_lname" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['s_lname'])) {
											  ?>value="<?php echo $_GET['s_lname'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="s_fname">Spouse's First Name </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="s_fname" name="s_fname" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['s_fname'])) {
											  ?>value="<?php echo $_GET['s_fname'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="s_mname">Spouse's Middle Name </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="s_mname" name="s_mname" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['s_mname'])) {
											  ?>value="<?php echo $_GET['s_mname'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="s_ename">Spouse's Extension Name </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="s_ename" name="s_ename" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['s_ename'])) {
											  ?>value="<?php echo $_GET['s_ename'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="s_occupation">Spouse's Occupation </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="s_occupation" name="s_occupation" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['s_occupation'])) {
											  ?>value="<?php echo $_GET['s_occupation'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="s_employer">Spouse's Employer </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="s_employer" name="s_employer" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['s_employer'])) {
											  ?>value="<?php echo $_GET['s_employer'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="s_employer_addr">Employer's Address </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="s_employer_addr" name="s_employer_addr" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['s_employer_addr'])) {
											  ?>value="<?php echo $_GET['s_employer_addr'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="s_employer_num">Employer's Telephone No. </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="s_employer_num" name="s_employer_num" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['s_employer_num'])) {
											  ?>value="<?php echo $_GET['s_employer_num'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="f_lname">Father's Surname <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="f_lname" name="f_lname" required="required" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['f_lname'])) {
											  ?>value="<?php echo $_GET['f_lname'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="f_fname">Father's First Name <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="f_fname" name="f_fname" required="required" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['f_fname'])) {
											  ?>value="<?php echo $_GET['f_fname'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="f_mname">Father's Middle Name <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="f_mname" name="f_mname" required="required" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['f_mname'])) {
											  ?>value="<?php echo $_GET['f_mname'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="f_ename">Father's Extension Name </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="f_ename" name="f_ename" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['f_ename'])) {
											  ?>value="<?php echo $_GET['f_ename'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="m_lname">Mothers's Surname <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="m_lname" name="m_lname" required="required" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['m_lname'])) {
											  ?>value="<?php echo $_GET['m_lname'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="m_fname">Mothers's First Name <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="m_fname" name="m_fname" required="required" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['m_fname'])) {
											  ?>value="<?php echo $_GET['m_fname'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="m_mname">Mothers's Middle Name <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="m_mname" name="m_mname" required="required" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['m_mname'])) {
											  ?>value="<?php echo $_GET['m_mname'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="m_ename">Mothers's Extension Name </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="m_ename" name="m_ename" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['m_ename'])) {
											  ?>value="<?php echo $_GET['m_ename'] ?>"<?php
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
				$s_lname = filter_var($_POST['s_lname'], FILTER_SANITIZE_STRING);
				$s_fname = filter_var($_POST['s_fname'], FILTER_SANITIZE_STRING);
				$s_mname = filter_var($_POST['s_mname'], FILTER_SANITIZE_STRING);
				$s_ename = filter_var($_POST['s_ename'], FILTER_SANITIZE_STRING);
				$s_occupation = filter_var($_POST['s_occupation'], FILTER_SANITIZE_STRING);
				$s_employer = filter_var($_POST['s_employer'], FILTER_SANITIZE_STRING);
				$s_employer_addr = filter_var($_POST['s_employer_addr'], FILTER_SANITIZE_STRING);
				$s_employer_num = filter_var($_POST['s_employer_num'], FILTER_SANITIZE_STRING);
				$f_lname = filter_var($_POST['f_lname'], FILTER_SANITIZE_STRING);
				$f_fname = filter_var($_POST['f_fname'], FILTER_SANITIZE_STRING);
				$f_mname = filter_var($_POST['f_mname'], FILTER_SANITIZE_STRING);
				$f_ename = filter_var($_POST['f_ename'], FILTER_SANITIZE_STRING);
				$m_lname = filter_var($_POST['m_lname'], FILTER_SANITIZE_STRING);
				$m_fname = filter_var($_POST['m_fname'], FILTER_SANITIZE_STRING);
				$m_mname = filter_var($_POST['m_mname'], FILTER_SANITIZE_STRING);
				$m_ename = filter_var($_POST['m_ename'], FILTER_SANITIZE_STRING);
				
				// check if father's first name is given
				if ($f_fname != "") { $a = TRUE; } 
				else { $a = FALSE; $message[] = "Please enter the first name of father."; }
				// check if father's middle name is given
				if ($f_mname != "") { $b = TRUE; } 
				else { $b = FALSE; $message[] = "Please enter the middle name of father."; }
				// check if father's last name is given
				if ($f_lname != "") { $c = TRUE; } 
				else { $c = FALSE; $message[] = "Please enter the last name of father."; }
				// check if mother's first name is given
				if ($m_fname != "") { $d = TRUE; } 
				else { $d = FALSE; $message[] = "Please enter the first name of mother."; }
				// check if mother's middle name is given
				if ($m_mname != "") { $e = TRUE; } 
				else { $e = FALSE; $message[] = "Please enter the middle name of mother."; }
				// check if mother's last name is given
				if ($m_lname != "") { $f = TRUE; } 
				else { $f = FALSE; $message[] = "Please enter the last name of mother."; }
				
				// If data pass all tests, proceed
				if ( $a && $b && $c && $d && $e && $f ) {
					
					// check for double entry first
					$sql = "SELECT fid 
							FROM employees_families 
							WHERE eid = '$eid'";
					$res = query($sql); 
					$num = num_rows($res); 
					
					if ($num > 0) {
						?>
						<div class="alert alert-danger alert-dismissible fade in" role="alert">
							<strong>Record already exists! </strong><br /><br />
							<a href="employee.php?eid=<?php echo $eid ?>">Click here to view the record</a>
						</div>
						<?php
					} else {
						$sql = "INSERT INTO employees_families( 
									eid, s_lname, s_fname, s_mname, s_ename, s_occupation, s_employer, s_employer_addr, 
									s_employer_num, f_lname, f_fname, f_mname, f_ename, m_lname, m_fname, m_mname, m_ename
								)
								VALUES( 
									'$eid', '$s_lname', '$s_fname', '$s_mname', '$s_ename', '$s_occupation', '$s_employer', '$s_employer_addr', 
									'$s_employer_num', '$f_lname', '$f_fname', '$f_mname', '$f_ename', '$m_lname', '$m_fname', '$m_mname', '$m_ename'
								)";
						if (query($sql)) {
							$fid = mysqli_insert_id($connection);
							// log the activity
							log_user(1, 'employees_families', $fid);
							
							header("Location:employee.php?eid=".$eid."#tab_content2");
							exit();
						} else {
							?>
							<div class="alert alert-danger alert-dismissible fade in" role="alert">
								<strong>Could not add employee-family because: </strong><br />
								<b><?php echo mysqli_error($connection) ?></b><br />
								The query was <?php echo $sql ?>.<br />
							</div>
							<?PHP
						}
					}
				} else {
					$serialized_message = serialize($message);
					// redirect the user to index page
					header('location: add_family.php?serialized_message='.$serialized_message.'&fdate='.$fdate.'&from_user_id='.$from_user_id.'&to_user_id='.$to_user_id.'&remarks='.$remarks.'&recipient='.$recipient.'&rdate='.$rdate);
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
						  
						<title>OTC | Add Family Information</title>

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
									<h3>Add Employee's Family Background</h3>
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
												
												// check if there is an existing family-record for this employee
												$sql = "SELECT fid 
														FROM employees_families
														WHERE eid = '$eid'";
												$res = query($sql); 
												$num = num_rows($res); 
												
												if ($num > 0) {
													$row = fetch_array($res); 
													header("Location:e_family.php?fid=".$row[0]);
													exit();
												} else {
													$sql = "SELECT CONCAT(lname,', ',fname) FROM employees WHERE eid = '$eid'";
													$eres = query($sql); 
													$erow = fetch_array($eres); 
												}
												
											}
											?>
											<form action="add_family.php" method="POST" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
											  <div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="ecode">Employee Name </label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <input type="text" id="ecode" name="ecode" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $erow[0] ?>">
												</div>
											  </div>
											  <div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="s_lname">Spouse's Surname </label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <input type="text" id="s_lname" name="s_lname" class="form-control col-md-7 col-xs-12" 
												  <?php
												  if (isset ($_GET['s_lname'])) {
													  ?>value="<?php echo $_GET['s_lname'] ?>"<?php
												  }
												  ?>
												  >
												</div>
											  </div>
											  <div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="s_fname">Spouse's First Name </label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <input type="text" id="s_fname" name="s_fname" class="form-control col-md-7 col-xs-12" 
												  <?php
												  if (isset ($_GET['s_fname'])) {
													  ?>value="<?php echo $_GET['s_fname'] ?>"<?php
												  }
												  ?>
												  >
												</div>
											  </div>
											  <div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="s_mname">Spouse's Middle Name </label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <input type="text" id="s_mname" name="s_mname" class="form-control col-md-7 col-xs-12" 
												  <?php
												  if (isset ($_GET['s_mname'])) {
													  ?>value="<?php echo $_GET['s_mname'] ?>"<?php
												  }
												  ?>
												  >
												</div>
											  </div>
											  <div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="s_ename">Spouse's Extension Name </label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <input type="text" id="s_ename" name="s_ename" class="form-control col-md-7 col-xs-12" 
												  <?php
												  if (isset ($_GET['s_ename'])) {
													  ?>value="<?php echo $_GET['s_ename'] ?>"<?php
												  }
												  ?>
												  >
												</div>
											  </div>
											  <div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="s_occupation">Spouse's Occupation </label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <input type="text" id="s_occupation" name="s_occupation" class="form-control col-md-7 col-xs-12" 
												  <?php
												  if (isset ($_GET['s_occupation'])) {
													  ?>value="<?php echo $_GET['s_occupation'] ?>"<?php
												  }
												  ?>
												  >
												</div>
											  </div>
											  <div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="s_employer">Spouse's Employer </label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <input type="text" id="s_employer" name="s_employer" class="form-control col-md-7 col-xs-12" 
												  <?php
												  if (isset ($_GET['s_employer'])) {
													  ?>value="<?php echo $_GET['s_employer'] ?>"<?php
												  }
												  ?>
												  >
												</div>
											  </div>
											  <div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="s_employer_addr">Employer's Address </label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <input type="text" id="s_employer_addr" name="s_employer_addr" class="form-control col-md-7 col-xs-12" 
												  <?php
												  if (isset ($_GET['s_employer_addr'])) {
													  ?>value="<?php echo $_GET['s_employer_addr'] ?>"<?php
												  }
												  ?>
												  >
												</div>
											  </div>
											  <div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="s_employer_num">Employer's Telephone No. </label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <input type="text" id="s_employer_num" name="s_employer_num" class="form-control col-md-7 col-xs-12" 
												  <?php
												  if (isset ($_GET['s_employer_num'])) {
													  ?>value="<?php echo $_GET['s_employer_num'] ?>"<?php
												  }
												  ?>
												  >
												</div>
											  </div>
											  <div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="f_lname">Father's Surname <span class="required">*</span></label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <input type="text" id="f_lname" name="f_lname" required="required" class="form-control col-md-7 col-xs-12" 
												  <?php
												  if (isset ($_GET['f_lname'])) {
													  ?>value="<?php echo $_GET['f_lname'] ?>"<?php
												  }
												  ?>
												  >
												</div>
											  </div>
											  <div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="f_fname">Father's First Name <span class="required">*</span></label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <input type="text" id="f_fname" name="f_fname" required="required" class="form-control col-md-7 col-xs-12" 
												  <?php
												  if (isset ($_GET['f_fname'])) {
													  ?>value="<?php echo $_GET['f_fname'] ?>"<?php
												  }
												  ?>
												  >
												</div>
											  </div>
											  <div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="f_mname">Father's Middle Name <span class="required">*</span></label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <input type="text" id="f_mname" name="f_mname" required="required" class="form-control col-md-7 col-xs-12" 
												  <?php
												  if (isset ($_GET['f_mname'])) {
													  ?>value="<?php echo $_GET['f_mname'] ?>"<?php
												  }
												  ?>
												  >
												</div>
											  </div>
											  <div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="f_ename">Father's Extension Name </label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <input type="text" id="f_ename" name="f_ename" class="form-control col-md-7 col-xs-12" 
												  <?php
												  if (isset ($_GET['f_ename'])) {
													  ?>value="<?php echo $_GET['f_ename'] ?>"<?php
												  }
												  ?>
												  >
												</div>
											  </div>
											  <div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="m_lname">Mothers's Surname <span class="required">*</span></label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <input type="text" id="m_lname" name="m_lname" required="required" class="form-control col-md-7 col-xs-12" 
												  <?php
												  if (isset ($_GET['m_lname'])) {
													  ?>value="<?php echo $_GET['m_lname'] ?>"<?php
												  }
												  ?>
												  >
												</div>
											  </div>
											  <div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="m_fname">Mothers's First Name <span class="required">*</span></label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <input type="text" id="m_fname" name="m_fname" required="required" class="form-control col-md-7 col-xs-12" 
												  <?php
												  if (isset ($_GET['m_fname'])) {
													  ?>value="<?php echo $_GET['m_fname'] ?>"<?php
												  }
												  ?>
												  >
												</div>
											  </div>
											  <div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="m_mname">Mothers's Middle Name <span class="required">*</span></label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <input type="text" id="m_mname" name="m_mname" required="required" class="form-control col-md-7 col-xs-12" 
												  <?php
												  if (isset ($_GET['m_mname'])) {
													  ?>value="<?php echo $_GET['m_mname'] ?>"<?php
												  }
												  ?>
												  >
												</div>
											  </div>
											  <div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="m_ename">Mothers's Extension Name </label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <input type="text" id="m_ename" name="m_ename" class="form-control col-md-7 col-xs-12" 
												  <?php
												  if (isset ($_GET['m_ename'])) {
													  ?>value="<?php echo $_GET['m_ename'] ?>"<?php
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

						<title>OTC - Add Family Information</title>

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

				<title>OTC - Add Family Information</title>

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

