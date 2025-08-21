<?php

include("db.php");
include("sqli.php");
include("html.php");
define('PAGE', 'add_routing.php'); 

session_start();

if (isset($_SESSION['user_id'])) {
	if (validate_level(PAGE) == true) {

		if(isset($_POST['submitted'])) { // if submit button has been pressed
			$cid = filter_var($_POST['cid'], FILTER_SANITIZE_NUMBER_INT);
			$fdate = filter_var($_POST['fdate'], FILTER_SANITIZE_STRING);
			$from_user_id = filter_var($_POST['from_user_id'], FILTER_SANITIZE_NUMBER_INT);
			$to_user_id1 = filter_var($_POST['to_user_id1'], FILTER_SANITIZE_NUMBER_INT);
			$to_user_id2 = filter_var($_POST['to_user_id2'], FILTER_SANITIZE_NUMBER_INT);
			$to_user_id3 = filter_var($_POST['to_user_id3'], FILTER_SANITIZE_NUMBER_INT);
			$remarks = filter_var($_POST['remarks'], FILTER_SANITIZE_STRING);
			$recipient = filter_var($_POST['recipient'], FILTER_SANITIZE_STRING);
			$rdate = filter_var($_POST['rdate'], FILTER_SANITIZE_STRING);
			
			// check if date filled up is given
			if ($fdate != "") { $a = TRUE; } 
			else { $a = FALSE; $message[] = "Please enter the date filled up."; }
			// check if source of routing is given
			if ($from_user_id != 0 && $from_user_id != '') { $b = TRUE; } 
			else { $b = FALSE; $message[] = "Please select from the list of senders."; }
			// check if destination of routing is given
			if ($to_user_id1 != 0 && $to_user_id1 != '') { $c = TRUE; } 
			else { $c = FALSE; $message[] = "Please select from the list of receipts."; }
			// check if remarks is given
			if ($remarks != "") { $d = TRUE; } 
			else { $d = FALSE; $message[] = "Please enter the remarks for routing."; }
			// check if date of receipt is given
			if ($rdate != "") { $e = TRUE; } 
			else { $e  = FALSE; $message[] = "Please enter the date of receipt."; }
			
			// If data pass all tests, proceed
			if ( $a && $b && $c && $d && $e ) {
				
				// check for double entry first
				$sql = "SELECT rid 
						FROM routings 
						WHERE cid = '$cid' AND fdate = '$fdate' AND from_user_id = '$from_user_id' 
							AND to_user_id = '$to_user_id1' AND recipient = '$recipient' AND rdate = '$rdate' AND remarks = '$remarks'";
				$res = query($sql); 
				$num = num_rows($res); 
				
				if ($num > 0) {
					?>
					<div class="alert alert-danger alert-dismissible fade in" role="alert">
						<strong>Record already exists! </strong><br /><br />
						<a href="communication.php?cid=<?php echo $row[0] ?>">Click here to view the record</a>
					</div>
					<?php
				} else {
					$sql = "INSERT INTO routings( cid, fdate, from_user_id, to_user_id, remarks, recipient, rdate, user_id, user_dtime )
							VALUES( '$cid', '$fdate', '$from_user_id', '$to_user_id1', '$remarks', '$recipient', '$rdate', '$_SESSION[user_id]', NOW() )";
					if (@query($sql)) {
						$rid = mysqli_insert_id($connection);
						// log the activity
						log_user(1, 'routings', $rid);
						
						if ($to_user_id2 != 0) {
							$sql = "INSERT INTO routings( cid, fdate, from_user_id, to_user_id, remarks, recipient, rdate, user_id, user_dtime )
									VALUES( '$cid', '$fdate', '$from_user_id', '$to_user_id2', '$remarks', '$recipient', '$rdate', '$_SESSION[user_id]', NOW() )";
							query($sql); 
						}
						
						if ($to_user_id3 != 0) {
							$sql = "INSERT INTO routings( cid, fdate, from_user_id, to_user_id, remarks, recipient, rdate, user_id, user_dtime )
									VALUES( '$cid', '$fdate', '$from_user_id', '$to_user_id3', '$remarks', '$recipient', '$rdate', '$_SESSION[user_id]', NOW() )";
							query($sql); 
						}
						
						header("Location:communication.php?cid=".$cid);
						exit();
					} else {
						?>
						<div class="alert alert-danger alert-dismissible fade in" role="alert">
							<strong>Could not add communication routing because: </strong><br />
							<b><?php echo mysqli_error($connection) ?></b><br />
							The query was <?php echo $sql ?>.<br />
						</div>
						<?PHP
					}
				}
			} else {
				$serialized_message = serialize($message);
				// redirect the user to PHP_SELF
				header('location: add_routing.php?serialized_message='.$serialized_message.'&fdate='.$fdate.'&from_user_id='.$from_user_id.'&to_user_id='.$to_user_id.'&remarks='.$remarks.'&recipient='.$recipient.'&rdate='.$rdate);
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
				  
				<title>OTC | Add Communication Routing</title>

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
							<h3>Add Communication Routing</h3>
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
						  if (isset($_GET['cid'])) {
							  $cid = $_GET['cid'];
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
						  
						  $date_today = date('Y-m-d');
						  ?>
						  <div class="col-md-12 col-sm-12 col-xs-12">
							<div class="x_panel">
							  <div class="x_content">
								<br />
								
									<form action="add_routing.php" method="POST" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="fdate">Date Filled Up <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="date" id="fdate" name="fdate" required="required" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['fdate'])) {
											  ?>value="<?php echo $_GET['fdate'] ?>"<?php
										  } else {
											  ?>value="<?php echo $date_today ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="from_user_id">From <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <select id="from_user_id" name="from_user_id" required="required" class="form-control col-md-7 col-xs-12">
										    <option value="0">--- Select ---</option>
										    <?php
											$sql = "SELECT eid, CONCAT(lname,', ',fname) 
													FROM employees 
													WHERE active = 1 
													ORDER BY lname, fname";
											$fres = query($sql); 
											while ($frow = fetch_array($fres)) {
												if (isset ($_GET['from_user_id'])) {
													if ($_GET['from_user_id'] == $frow[0]) {
														?><option value="<?php echo $frow[0] ?>" Selected><?php echo $frow[1] ?></option><?php
													} else {
														?><option value="<?php echo $frow[0] ?>"><?php echo $frow[1] ?></option><?php
													}
												} else {
													if ($_SESSION['user_id'] == $frow[0]) {
														?><option value="<?php echo $frow[0] ?>" Selected><?php echo $frow[1] ?></option><?php
													} else {
														?><option value="<?php echo $frow[0] ?>"><?php echo $frow[1] ?></option><?php
													}
												}
											}
											free_result($fres); 
											?>
										  </select>
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="to_user_id1">Recipient 1 <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <select id="to_user_id1" name="to_user_id1" required="required" class="form-control col-md-7 col-xs-12">
										    <option value="0">--- Select ---</option>
										    <?php
											$sql = "SELECT eid, CONCAT(lname,', ',fname) 
													FROM employees 
													WHERE active = 1 
													ORDER BY lname, fname";
											$tres = query($sql); 
											while ($trow = fetch_array($tres)) {
												if (isset ($_GET['to_user_id1'])) {
													if ($_GET['to_user_id1'] == $trow[0]) {
														?><option value="<?php echo $trow[0] ?>" Selected><?php echo $trow[1] ?></option><?php
													} else {
														?><option value="<?php echo $trow[0] ?>"><?php echo $trow[1] ?></option><?php
													}
												} else {
													?><option value="<?php echo $trow[0] ?>"><?php echo $trow[1] ?></option><?php
												}
											}
											free_result($tres); 
											?>
										  </select>
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="to_user_id2">Recipient 2 </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <select id="to_user_id2" name="to_user_id2" class="form-control col-md-7 col-xs-12">
										    <option value="0">--- Select ---</option>
										    <?php
											$sql = "SELECT eid, CONCAT(lname,', ',fname) 
													FROM employees 
													WHERE active = 1 
													ORDER BY lname, fname";
											$tres = query($sql); 
											while ($trow = fetch_array($tres)) {
												if (isset ($_GET['to_user_id2'])) {
													if ($_GET['to_user_id2'] == $trow[0]) {
														?><option value="<?php echo $trow[0] ?>" Selected><?php echo $trow[1] ?></option><?php
													} else {
														?><option value="<?php echo $trow[0] ?>"><?php echo $trow[1] ?></option><?php
													}
												} else {
													?><option value="<?php echo $trow[0] ?>"><?php echo $trow[1] ?></option><?php
												}
											}
											free_result($tres); 
											?>
										  </select>
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="to_user_id3">Recipient 3 </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <select id="to_user_id3" name="to_user_id3" class="form-control col-md-7 col-xs-12">
										    <option value="0">--- Select ---</option>
										    <?php
											$sql = "SELECT eid, CONCAT(lname,', ',fname) 
													FROM employees 
													WHERE active = 1 
													ORDER BY lname, fname";
											$tres = query($sql); 
											while ($trow = fetch_array($tres)) {
												if (isset ($_GET['to_user_id3'])) {
													if ($_GET['to_user_id3'] == $trow[0]) {
														?><option value="<?php echo $trow[0] ?>" Selected><?php echo $trow[1] ?></option><?php
													} else {
														?><option value="<?php echo $trow[0] ?>"><?php echo $trow[1] ?></option><?php
													}
												} else {
													?><option value="<?php echo $trow[0] ?>"><?php echo $trow[1] ?></option><?php
												}
											}
											free_result($tres); 
											?>
										  </select>
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
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="recipient">Recipient <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="recipient" name="recipient" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['recipient'])) {
											  ?>value="<?php echo $_GET['recipient'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="rdate">Date Received <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="date" id="rdate" name="rdate" required="required" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['rdate'])) {
											  ?>value="<?php echo $_GET['rdate'] ?>"<?php
										  } else {
											  ?>value="<?php echo $date_today ?>"<?php
										  }
										  ?>
										  >
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

