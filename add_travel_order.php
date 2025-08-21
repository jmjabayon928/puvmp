<?php

include("db.php");
include("sqli.php");
include("html.php");
define('PAGE', 'add_travel_order.php'); 

session_start();

if (isset($_SESSION['user_id'])) {
	if (validate_level(PAGE) == true) {
		
		if(isset($_POST['submitted'])) { // if submit button has been pressed
			$fr_date = filter_var($_POST['fr_date'], FILTER_SANITIZE_STRING);
			$to_date = filter_var($_POST['to_date'], FILTER_SANITIZE_STRING);
			$tnum = filter_var($_POST['tnum'], FILTER_SANITIZE_STRING);
			$destination = filter_var($_POST['destination'], FILTER_SANITIZE_STRING);
			$purpose = filter_var($_POST['purpose'], FILTER_SANITIZE_STRING);
			$eid1 = filter_var($_POST['eid1'], FILTER_SANITIZE_NUMBER_INT);
			$eid2 = filter_var($_POST['eid2'], FILTER_SANITIZE_NUMBER_INT);
			$eid3 = filter_var($_POST['eid3'], FILTER_SANITIZE_NUMBER_INT);
			$eid4 = filter_var($_POST['eid4'], FILTER_SANITIZE_NUMBER_INT);
			$eid5 = filter_var($_POST['eid5'], FILTER_SANITIZE_NUMBER_INT);
			
			// check if fr_date is given
			if ($fr_date != '') { $a = TRUE; } 
			else { $a = FALSE; $message[] = "Please enter the start date."; }
			// check if to_date is given
			if ($to_date != '') { $b = TRUE; } 
			else { $b = FALSE; $message[] = "Please enter the end date."; }
			// check if tnum is given
			if ($tnum != '') { $c = TRUE; } 
			else { $c = FALSE; $message[] = "Please enter the control number."; }
			// check if destination is given
			if ($destination != '') { $d = TRUE; } 
			else { $d = FALSE; $message[] = "Please enter the destination of the circular."; }
			// check if purpose is given
			if ($purpose != '') { $e = TRUE; } 
			else { $e = FALSE; $message[] = "Please enter the purpose of circular."; }
			
			// If data pass all tests, proceed
			if ( $a && $b && $c && $d && $e ) {
				// check for double entry first
				$sql = "SELECT tid
						FROM internals_travel_orders
						WHERE tnum = '$tnum' OR (fr_date = '$fr_date' AND to_date = '$to_date' AND destination = '$destination' AND purpose = '$purpose')";
				$res = query($sql); 
				$num = num_rows($res); 
				
				if ($num > 0) {
					$row = fetch_array($res); 
					?>
					<div class="alert alert-danger alert-dismissible fade in" role="alert">
						<strong>Record already exists! </strong><br /><br />
						<a href="internals.php?tab=5">Click here to view the records</a>
					</div>
					<?php
				} else {
					$sql = "INSERT INTO internals_travel_orders( fr_date, to_date, tnum, destination, purpose, user_id, user_dtime )
							VALUES( '$fr_date', '$to_date', '$tnum', '$destination', '$purpose', '$_SESSION[user_id]', NOW() )";
					if (query($sql)) {
						$tid = mysqli_insert_id($connection);
						// log the activity
						log_user(1, 'internals_travel_orders', $tid);
						
						if ($eid1 != 0) {
							$sql = "INSERT INTO internals_travel_orders_employees (tid, eid) VALUES('$tid', '$eid1')";
							query($sql); 
							// insert into attendance
							insert_to_attendance($tid, $eid1); 
						}
						if ($eid2 != 0) {
							$sql = "INSERT INTO internals_travel_orders_employees (tid, eid) VALUES('$tid', '$eid2')";
							query($sql); 
							// insert into attendance
							insert_to_attendance($tid, $eid2); 
						}
						if ($eid3 != 0) {
							$sql = "INSERT INTO internals_travel_orders_employees (tid, eid) VALUES('$tid', '$eid3')";
							query($sql); 
							// insert into attendance
							insert_to_attendance($tid, $eid3); 
						}
						if ($eid4 != 0) {
							$sql = "INSERT INTO internals_travel_orders_employees (tid, eid) VALUES('$tid', '$eid4')";
							query($sql); 
							// insert into attendance
							insert_to_attendance($tid, $eid4); 
						}
						if ($eid5 != 0) {
							$sql = "INSERT INTO internals_travel_orders_employees (tid, eid) VALUES('$tid', '$eid5')";
							query($sql); 
							// insert into attendance
							insert_to_attendance($tid, $eid5); 
						}
						
						// insert into attendance with status T.O. (consider start and end date of T.O.)
						
						header("Location: internals.php?tab=5&added_travel_order=1");
						exit();
					} else {
						?>
						<div class="alert alert-danger alert-dismissible fade in" role="alert">
							<strong>Could not add Travel Order because: </strong><br />
							<b><?php echo mysqli_error($connection) ?></b><br />
							The query was <?php echo $sql ?>.<br />
						</div>
						<?PHP
					}
				}
			} else {
				$serialized_message = serialize($message);
				// redirect the user to index page
				header('location: add_travel_order.php?serialized_message='.$serialized_message.'&fr_date='.$fr_date.'&to_date='.$to_date.'&eid='.$eid.'&tnum='.$tnum.'&destination='.$destination.'&purpose='.$purpose);
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
				  
				<title>OTC | Add Travel Order</title>

				<!-- Bootstrap -->
				<link href="vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
				<!-- Font Awesome -->
				<link href="vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
				<!-- NProgress -->
				<link href="vendors/nprogress/nprogress.css" rel="stylesheet">
				<!-- iCheck -->
				<link href="vendors/iCheck/skins/flat/green.css" rel="stylesheet">
				<!-- bootstrap-wysiwyg -->
				<link href="vendors/google-code-prettify/bin/prettify.min.css" rel="stylesheet">
				<!-- Select2 -->
				<link href="vendors/select2/dist/css/select2.min.css" rel="stylesheet">
				<!-- Switchery -->
				<link href="vendors/switchery/dist/switchery.min.css" rel="stylesheet">
				<!-- starrr -->
				<link href="vendors/starrr/dist/starrr.css" rel="stylesheet">
				<!-- bootstrap-daterangepicker -->
				<link href="vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">

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
							<h3>Add Travel Order</h3>
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
								
									<form action="add_travel_order.php" method="POST" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="tnum">Control Number <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="tnum" name="tnum" required="required" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['tnum'])) {
											  ?>value="<?php echo $_GET['tnum'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="fr_date">Start Date <span class="required">*</span></label>
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
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="to_date">End Date <span class="required">*</span></label>
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
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="destination">Destination <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="destination" name="destination" required="required" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['destination'])) {
											  ?>value="<?php echo $_GET['destination'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="purpose">Purpose <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="purpose" name="purpose" required="required" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['purpose'])) {
											  ?>value="<?php echo $_GET['purpose'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="eid1">Employee 1</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <select id="eid1" name="eid1" class="form-control col-md-7 col-xs-12">
											<option value="0">--- Select ---</option>
											<?php
											$sql = "SELECT eid, CONCAT(lname,', ',fname) 
													FROM employees 
													WHERE active = 1 
													ORDER BY lname, fname";
											$eres = query($sql); 
											while ($erow = fetch_array($eres)) {
												if (isset ($_GET['eid1'])) {
													if ($_GET['eid1'] == $erow[0]) {
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
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="eid2">Employee 2</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <select id="eid2" name="eid2" class="form-control col-md-7 col-xs-12">
											<option value="0">--- Select ---</option>
											<?php
											$sql = "SELECT eid, CONCAT(lname,', ',fname) 
													FROM employees 
													WHERE active = 1 
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
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="eid3">Employee 3</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <select id="eid3" name="eid3" class="form-control col-md-7 col-xs-12">
											<option value="0">--- Select ---</option>
											<?php
											$sql = "SELECT eid, CONCAT(lname,', ',fname) 
													FROM employees 
													WHERE active = 1 
													ORDER BY lname, fname";
											$eres = query($sql); 
											while ($erow = fetch_array($eres)) {
												if (isset ($_GET['eid3'])) {
													if ($_GET['eid3'] == $erow[0]) {
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
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="eid4">Employee 4</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <select id="eid4" name="eid4" class="form-control col-md-7 col-xs-12">
											<option value="0">--- Select ---</option>
											<?php
											$sql = "SELECT eid, CONCAT(lname,', ',fname) 
													FROM employees 
													WHERE active = 1 
													ORDER BY lname, fname";
											$eres = query($sql); 
											while ($erow = fetch_array($eres)) {
												if (isset ($_GET['eid4'])) {
													if ($_GET['eid4'] == $erow[0]) {
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
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="eid5">Employee 5</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <select id="eid5" name="eid5" class="form-control col-md-7 col-xs-12">
											<option value="0">--- Select ---</option>
											<?php
											$sql = "SELECT eid, CONCAT(lname,', ',fname) 
													FROM employees 
													WHERE active = 1 
													ORDER BY lname, fname";
											$eres = query($sql); 
											while ($erow = fetch_array($eres)) {
												if (isset ($_GET['eid5'])) {
													if ($_GET['eid5'] == $erow[0]) {
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
			<?PHP
		}
	}
} else {
	header('Location: login.php');	
}

