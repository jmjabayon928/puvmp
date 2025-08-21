<?php

include("db.php");
include("sqli.php");
include("html.php");
define('PAGE', 'add_memo_order.php'); 

session_start();

if (isset($_SESSION['user_id'])) {
	if (validate_level(PAGE) == true) {
		
		if(isset($_POST['submitted'])) { // if submit button has been pressed
			$odate = filter_var($_POST['odate'], FILTER_SANITIZE_STRING);
			$fr_date = filter_var($_POST['fr_date'], FILTER_SANITIZE_STRING);
			$to_date = filter_var($_POST['to_date'], FILTER_SANITIZE_STRING);
			$onum = filter_var($_POST['onum'], FILTER_SANITIZE_STRING);
			$subject = filter_var($_POST['subject'], FILTER_SANITIZE_STRING);
			$eid1 = filter_var($_POST['eid1'], FILTER_SANITIZE_NUMBER_INT);
			$eid2 = filter_var($_POST['eid2'], FILTER_SANITIZE_NUMBER_INT);
			$eid3 = filter_var($_POST['eid3'], FILTER_SANITIZE_NUMBER_INT);
			$eid4 = filter_var($_POST['eid4'], FILTER_SANITIZE_NUMBER_INT);
			$eid5 = filter_var($_POST['eid5'], FILTER_SANITIZE_NUMBER_INT);
			
			// check if odate is given
			if ($odate != '') { $a = TRUE; } 
			else { $a = FALSE; $message[] = "Please enter the date of memorandum order."; }
			// check if onum is given
			if ($onum != '') { $b = TRUE; } 
			else { $b = FALSE; $message[] = "Please enter the control number."; }
			// check if subject is given
			if ($subject != '') { $c = TRUE; } 
			else { $c = FALSE; $message[] = "Please enter the subject of order."; }
			
			// If data pass all tests, proceed
			if ( $a && $b && $c ) {
				// check for double entry first
				$sql = "SELECT oid
						FROM internals_memo_orders
						WHERE onum = '$onum' OR (odate = '$odate' AND fr_date = '$fr_date' AND to_date = '$to_date' AND subject = '$subject')";
				$res = query($sql); 
				$num = num_rows($res); 
				
				if ($num > 0) {
					$row = fetch_array($res); 
					?>
					<div class="alert alert-danger alert-dismissible fade in" role="alert">
						<strong>Record already exists! </strong><br /><br />
						<a href="internals.php?tab=3">Click here to view the records</a>
					</div>
					<?php
				} else {
					$sql = "INSERT INTO internals_memo_orders( odate, fr_date, to_date, onum, subject, user_id, user_dtime )
							VALUES( '$odate', '$fr_date', '$to_date', '$onum', '$subject', '$_SESSION[user_id]', NOW() )";
					if (query($sql)) {
						$oid = mysqli_insert_id($connection);
						// log the activity
						log_user(1, 'internals_memo_orders', $oid);
						
						if ($eid1 != 0) {
							$sql = "INSERT INTO internals_memo_orders_employees (oid, eid) VALUES('$oid', '$eid1')";
							query($sql); 
							// insert into attendance
							insert_mo_attendance($oid, $eid1); 
						}
						if ($eid2 != 0) {
							$sql = "INSERT INTO internals_memo_orders_employees (oid, eid) VALUES('$oid', '$eid2')";
							query($sql); 
							// insert into attendance
							insert_mo_attendance($oid, $eid2); 
						}
						if ($eid3 != 0) {
							$sql = "INSERT INTO internals_memo_orders_employees (oid, eid) VALUES('$oid', '$eid3')";
							query($sql); 
							// insert into attendance
							insert_mo_attendance($oid, $eid3); 
						}
						if ($eid4 != 0) {
							$sql = "INSERT INTO internals_memo_orders_employees (oid, eid) VALUES('$oid', '$eid4')";
							query($sql); 
							// insert into attendance
							insert_mo_attendance($oid, $eid4); 
						}
						if ($eid5 != 0) {
							$sql = "INSERT INTO internals_memo_orders_employees (oid, eid) VALUES('$oid', '$eid5')";
							query($sql); 
							// insert into attendance
							insert_mo_attendance($oid, $eid5); 
						}
						
						header("Location:internals.php?tab=3&added_memo_order=1");
						exit();
					} else {
						?>
						<div class="alert alert-danger alert-dismissible fade in" role="alert">
							<strong>Could not add Memorandum Order because: </strong><br />
							<b><?php echo mysqli_error($connection) ?></b><br />
							The query was <?php echo $sql ?>.<br />
						</div>
						<?PHP
					}
				}
			} else {
				$serialized_message = serialize($message);
				// redirect the user to index page
				header('location: add_memo_order.php?serialized_message='.$serialized_message.'&odate='.$odate.'&onum='.$onum.'&enames='.$enames.'&subject='.$subject);
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
				  
				<title>OTC | Add Memorandum Order</title>

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
							<h3>Add Memorandum Order</h3>
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
								
									<form action="add_memo_order.php" method="POST" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="odate">Date of Memorandum Order <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="date" id="odate" name="odate" required="required" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['odate'])) {
											  ?>value="<?php echo $_GET['odate'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="onum">Control Number <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="onum" name="onum" required="required" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['onum'])) {
											  ?>value="<?php echo $_GET['onum'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="subject">Subject <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="subject" name="subject" required="required" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['subject'])) {
											  ?>value="<?php echo $_GET['subject'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="fr_date">Start of Travel (Optional) </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="date" id="fr_date" name="fr_date" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['fr_date'])) {
											  ?>value="<?php echo $_GET['fr_date'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="to_date">End of Travel (Optional) </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="date" id="to_date" name="to_date" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['to_date'])) {
											  ?>value="<?php echo $_GET['to_date'] ?>"<?php
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
										  <input type="hidden" name="cid" value="<?php echo $cid ?>">
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
	}
} else {
	header('Location: login.php');	
}

