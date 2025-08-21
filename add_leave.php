<?php

include("db.php");
include("sqli.php");
include("html.php");
define('PAGE', 'add_leave.php'); 

session_start();

if (isset($_SESSION['user_id'])) {
	if (validate_level3(PAGE) == true) {
		
		if(isset($_POST['submitted'])) { // if submit button has been pressed
			$anum = filter_var($_POST['anum'], FILTER_SANITIZE_STRING);
			$eid = filter_var($_POST['eid'], FILTER_SANITIZE_NUMBER_INT);
			$lid = filter_var($_POST['lid'], FILTER_SANITIZE_NUMBER_INT);
			$fr_date = filter_var($_POST['fr_date'], FILTER_SANITIZE_STRING);
			$to_date = filter_var($_POST['to_date'], FILTER_SANITIZE_STRING);
			$lnum = filter_var($_POST['lnum'], FILTER_SANITIZE_NUMBER_INT);
			$remarks = filter_var($_POST['remarks'], FILTER_SANITIZE_STRING);
			
			// check if anum is given
			if ($anum != '') { $a = TRUE; } 
			else { $a = FALSE; $message[] = "Please enter the control number."; }
			// check if eid is given
			if ($eid != 0) { $b = TRUE; } 
			else { $b = FALSE; $message[] = "Please select from the list of employees."; }
			// check if lid is given
			if ($lid != 0) { $c = TRUE; } 
			else { $c = FALSE; $message[] = "Please select from the list of types of leave."; }
			// check if fr_date is given
			if ($fr_date != '') { $d = TRUE; } 
			else { $d = FALSE; $message[] = "Please enter the start date of leave."; }
			// check if to_date is given
			if ($to_date != '') { $e = TRUE; } 
			else { $e = FALSE; $message[] = "Please enter the end date of leave."; }
			// check if lnum is given
			if ($lnum != '') { $f = TRUE; } 
			else { $f = FALSE; $message[] = "Please enter the number of days of leave."; }
			
			
			// If data pass all tests, proceed
			if ( $a && $b && $c && $d && $e && $f ) {
				// check for double entry first
				$sql = "SELECT aid
						FROM internals_leave_applications
						WHERE anum = '$anum' OR (eid = '$eid' AND lid = '$lid' AND fr_date = '$fr_date' AND to_date = '$to_date' AND lnum = '$lnum')";
				$res = query($sql); 
				$num = num_rows($res); 
				
				if ($num > 0) {
					$row = fetch_array($res); 
					?>
					<div class="alert alert-danger alert-dismissible fade in" role="alert">
						<strong>Record already exists! </strong><br /><br />
						<a href="dm.php?did=<?php echo $row[0] ?>">Click here to view the record</a>
					</div>
					<?php
				} else {
					$sql = "INSERT INTO internals_leave_applications( anum, eid, lid, fr_date, to_date, lnum, remarks, user_id, user_dtime )
							VALUES( '$anum', '$eid', '$lid', '$fr_date', '$to_date', '$lnum', '$remarks', '$_SESSION[user_id]', NOW() )";
					if (query($sql)) {
						$aid = mysqli_insert_id($connection);
						// log the activity
						log_user(1, 'internals_leave_applications', $aid);
						
						header("Location:leaves.php?added_leave=1");
						exit();
					} else {
						?>
						<div class="alert alert-danger alert-dismissible fade in" role="alert">
							<strong>Could not add Leave Application because: </strong><br />
							<b><?php echo mysqli_error($connection) ?></b><br />
							The query was <?php echo $sql ?>.<br />
						</div>
						<?PHP
					}
				}
			} else {
				$serialized_message = serialize($message);
				// redirect the user to index page
				header('location: add_leave.php?serialized_message='.$serialized_message.'&anum='.$anum.'&eid='.$eid.'&lid='.$lid.'&fr_date='.$fr_date.'&to_date='.$to_date.'&lnum='.$lnum.'&remarks='.$remarks);
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
				  
				<title>OTC | Add Leave Application</title>

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
							<h3>Add Leave Application</h3>
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
								
									<form action="add_leave.php" method="POST" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="anum">Control Number <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="anum" name="anum" required="required" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['anum'])) {
											  ?>value="<?php echo $_GET['anum'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="eid">Employee <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <select id="eid" name="eid" required="required" class="form-control col-md-7 col-xs-12">
											<option value="0">--- Select ---</option>
											<?php
											$sql = "SELECT eid, CONCAT(lname,', ',fname) 
													FROM employees 
													WHERE active = 1 
													ORDER BY lname, fname";
											$eres = query($sql); 
											while ($erow = fetch_array($eres)) {
												if (isset ($_GET['eid'])) {
													if ($_GET['eid'] == $erow[0]) {
														?><option value="<?php echo $erow[0] ?>" Selected><?php echo $erow[1] ?></option><?php
													} else {
														?><option value="<?php echo $erow[0] ?>"><?php echo $erow[1] ?></option><?php
													}
												} else {
													?><option value="<?php echo $erow[0] ?>"><?php echo $erow[1] ?></option><?php
												}
											}
											free_result($eres); 
											?>
										  </select>
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="lid">Type of Leave <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <select id="lid" name="lid" required="required" class="form-control col-md-7 col-xs-12">
											<?php
											$sql = "SELECT lid, lname FROM leave_types";
											$lres = query($sql); 
											while ($lrow = fetch_array($lres)) {
												if (isset ($_GET['lid'])) {
													if ($_GET['lid'] == $lrow[0]) {
														?><option value="<?php echo $lrow[0] ?>" Selected><?php echo $lrow[1] ?></option><?php
													} else {
														?><option value="<?php echo $lrow[0] ?>"><?php echo $lrow[1] ?></option><?php
													}
												} else {
													?><option value="<?php echo $lrow[0] ?>"><?php echo $lrow[1] ?></option><?php
												}
											}
											free_result($lres); 
											?>
										  </select>
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="fr_date">Start Date of Leave <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="date" id="fr_date" name="fr_date" required="required" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['fr_date'])) {
											  ?>value="<?php echo $_GET['fr_date'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="to_date">End Date of Leave <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="date" id="to_date" name="to_date" required="required" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['to_date'])) {
											  ?>value="<?php echo $_GET['to_date'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="lnum">No. of Days <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="lnum" name="lnum" required="required" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['lnum'])) {
											  ?>value="<?php echo $_GET['lnum'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="remarks">Remarks <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="remarks" name="remarks" required="required" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['remarks'])) {
											  ?>value="<?php echo $_GET['remarks'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="ln_solid"></div>
									  <div class="form-group">
										<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
										  <input type="hidden" name="submitted" value="1">
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
		$vres = query($sql); $vrow = fetch_array($vres); $eid = $vrow[0]; 
		
		if ($eid != 0) {
			
			if(isset($_POST['submitted'])) { // if submit button has been pressed
				$anum = filter_var($_POST['anum'], FILTER_SANITIZE_STRING);
				$eid2 = filter_var($_POST['eid2'], FILTER_SANITIZE_NUMBER_INT);
				$lid = filter_var($_POST['lid'], FILTER_SANITIZE_NUMBER_INT);
				$fr_date = filter_var($_POST['fr_date'], FILTER_SANITIZE_STRING);
				$to_date = filter_var($_POST['to_date'], FILTER_SANITIZE_STRING);
				$lnum = filter_var($_POST['lnum'], FILTER_SANITIZE_NUMBER_INT);
				$remarks = filter_var($_POST['remarks'], FILTER_SANITIZE_STRING);
				
				// check if anum is given
				if ($anum != '') { $a = TRUE; } 
				else { $a = FALSE; $message[] = "Please enter the control number."; }
				// check if eid2 is given
				if ($eid2 != 0) { $b = TRUE; } 
				else { $b = FALSE; $message[] = "Please select from the list of employees."; }
				// check if lid is given
				if ($lid != 0) { $c = TRUE; } 
				else { $c = FALSE; $message[] = "Please select from the list of types of leave."; }
				// check if fr_date is given
				if ($fr_date != '') { $d = TRUE; } 
				else { $d = FALSE; $message[] = "Please enter the start date of leave."; }
				// check if to_date is given
				if ($to_date != '') { $e = TRUE; } 
				else { $e = FALSE; $message[] = "Please enter the end date of leave."; }
				// check if lnum is given
				if ($lnum != '') { $f = TRUE; } 
				else { $f = FALSE; $message[] = "Please enter the number of days of leave."; }
				
				
				// If data pass all tests, proceed
				if ( $a && $b && $c && $d && $e && $f ) {
					// check for double entry first
					$sql = "SELECT aid
							FROM internals_leave_applications
							WHERE anum = '$anum' OR (eid = '$eid2' AND lid = '$lid' AND fr_date = '$fr_date' AND to_date = '$to_date' AND lnum = '$lnum')";
					$res = query($sql); 
					$num = num_rows($res); 
					
					if ($num > 0) {
						$row = fetch_array($res); 
						?>
						<div class="alert alert-danger alert-dismissible fade in" role="alert">
							<strong>Record already exists! </strong><br /><br />
							<a href="dm.php?did=<?php echo $row[0] ?>">Click here to view the record</a>
						</div>
						<?php
					} else {
						$sql = "INSERT INTO internals_leave_applications( anum, eid, lid, fr_date, to_date, lnum, remarks, user_id, user_dtime )
								VALUES( '$anum', '$eid2', '$lid', '$fr_date', '$to_date', '$lnum', '$remarks', '$_SESSION[user_id]', NOW() )";
						if (query($sql)) {
							$aid = mysqli_insert_id($connection);
							// log the activity
							log_user(1, 'internals_leave_applications', $aid);
							
							header("Location:leaves.php?added_leave=1");
							exit();
						} else {
							?>
							<div class="alert alert-danger alert-dismissible fade in" role="alert">
								<strong>Could not add Leave Application because: </strong><br />
								<b><?php echo mysqli_error($connection) ?></b><br />
								The query was <?php echo $sql ?>.<br />
							</div>
							<?PHP
						}
					}
				} else {
					$serialized_message = serialize($message);
					// redirect the user to index page
					header('location: add_leave.php?serialized_message='.$serialized_message.'&anum='.$anum.'&eid='.$eid2.'&lid='.$lid.'&fr_date='.$fr_date.'&to_date='.$to_date.'&lnum='.$lnum.'&remarks='.$remarks);
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
					  
					<title>OTC | Add Leave Application</title>

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
								<h3>Add Leave Application</h3>
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
									
										<form action="add_leave.php" method="POST" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
										  <div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" for="anum">Control Number <span class="required">*</span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											  <input type="text" id="anum" name="anum" required="required" class="form-control col-md-7 col-xs-12" 
											  <?php
											  if (isset ($_GET['anum'])) {
												  ?>value="<?php echo $_GET['anum'] ?>"<?php
											  }
											  ?>
											  >
											</div>
										  </div>
										  <div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" for="eid2">Employee <span class="required">*</span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											  <select id="eid2" name="eid2" required="required" class="form-control col-md-7 col-xs-12">
												<?php
												$sql = "SELECT eid, CONCAT(lname,', ',fname) 
														FROM employees 
														WHERE active = 1 AND eid = '$eid'
														ORDER BY lname, fname";
												$eres = query($sql); 
												while ($erow = fetch_array($eres)) {
													if (isset ($_GET['eid2'])) {
														if ($_GET['eid2'] == $erow[0]) {
															?><option value="<?php echo $erow[0] ?>" Selected><?php echo $erow[1] ?></option><?php
														} else {
															?><option value="<?php echo $erow[0] ?>"><?php echo $erow[1] ?></option><?php
														}
													} else {
														?><option value="<?php echo $erow[0] ?>"><?php echo $erow[1] ?></option><?php
													}
												}
												free_result($eres); 
												?>
											  </select>
											</div>
										  </div>
										  <div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" for="lid">Type of Leave <span class="required">*</span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											  <select id="lid" name="lid" required="required" class="form-control col-md-7 col-xs-12">
												<?php
												$sql = "SELECT lid, lname FROM leave_types";
												$lres = query($sql); 
												while ($lrow = fetch_array($lres)) {
													if (isset ($_GET['lid'])) {
														if ($_GET['lid'] == $lrow[0]) {
															?><option value="<?php echo $lrow[0] ?>" Selected><?php echo $lrow[1] ?></option><?php
														} else {
															?><option value="<?php echo $lrow[0] ?>"><?php echo $lrow[1] ?></option><?php
														}
													} else {
														?><option value="<?php echo $lrow[0] ?>"><?php echo $lrow[1] ?></option><?php
													}
												}
												free_result($lres); 
												?>
											  </select>
											</div>
										  </div>
										  <div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" for="fr_date">Start Date of Leave <span class="required">*</span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											  <input type="date" id="fr_date" name="fr_date" required="required" class="form-control col-md-7 col-xs-12" 
											  <?php
											  if (isset ($_GET['fr_date'])) {
												  ?>value="<?php echo $_GET['fr_date'] ?>"<?php
											  }
											  ?>
											  >
											</div>
										  </div>
										  <div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" for="to_date">End Date of Leave <span class="required">*</span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											  <input type="date" id="to_date" name="to_date" required="required" class="form-control col-md-7 col-xs-12" 
											  <?php
											  if (isset ($_GET['to_date'])) {
												  ?>value="<?php echo $_GET['to_date'] ?>"<?php
											  }
											  ?>
											  >
											</div>
										  </div>
										  <div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" for="lnum">No. of Days <span class="required">*</span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											  <input type="text" id="lnum" name="lnum" required="required" class="form-control col-md-7 col-xs-12" 
											  <?php
											  if (isset ($_GET['lnum'])) {
												  ?>value="<?php echo $_GET['lnum'] ?>"<?php
											  }
											  ?>
											  >
											</div>
										  </div>
										  <div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" for="remarks">Remarks <span class="required">*</span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											  <input type="text" id="remarks" name="remarks" required="required" class="form-control col-md-7 col-xs-12" 
											  <?php
											  if (isset ($_GET['remarks'])) {
												  ?>value="<?php echo $_GET['remarks'] ?>"<?php
											  }
											  ?>
											  >
											</div>
										  </div>
										  <div class="ln_solid"></div>
										  <div class="form-group">
											<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
											  <input type="hidden" name="submitted" value="1">
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
			?>
			<!DOCTYPE html>
			<html lang="en">
			  <head>
				<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
				<!-- Meta, title, CSS, favicons, etc. -->
				<meta charset="utf-8">
				<meta http-equiv="X-UA-Compatible" content="IE=edge">
				<meta name="viewport" content="width=device-width, initial-scale=1">

				<title>OTC - Employee's Own Leave Applications</title>

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

