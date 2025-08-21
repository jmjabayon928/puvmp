<?php

include("db.php");
include("sqli.php");
include("html.php");
define('PAGE', 'e_coop_training.php'); 

session_start();

if (isset($_SESSION['user_id'])) {
	if (validate_level(PAGE) == true) {

		if(isset($_POST['submitted'])) { // if submit button has been pressed
			$ctid = filter_var($_POST['ctid'], FILTER_SANITIZE_NUMBER_INT);
			$tid = filter_var($_POST['tid'], FILTER_SANITIZE_NUMBER_INT);
			$tvenue = filter_var($_POST['tvenue'], FILTER_SANITIZE_STRING);
			$fdate = filter_var($_POST['fdate'], FILTER_SANITIZE_STRING);
			$tdate = filter_var($_POST['tdate'], FILTER_SANITIZE_STRING);
			$eid = filter_var($_POST['eid'], FILTER_SANITIZE_NUMBER_INT);
			$cid = filter_var($_POST['cid'], FILTER_SANITIZE_NUMBER_INT);
			$mid = filter_var($_POST['mid'], FILTER_SANITIZE_NUMBER_INT);
			
			// check if tid is given
			if ($tid != 0) { $a = TRUE; } 
			else { $a = FALSE; $message[] = "Please select from the list of trainings."; }
			// check if tvenue is given
			if ($tvenue != "") { $b = TRUE; } 
			else { $b = FALSE; $message[] = "Please enter the venue of training."; }
			// check if fdate is given
			if ($fdate != "") { $c = TRUE; } 
			else { $c = FALSE; $message[] = "Please enter the start date of training."; }
			// check if tdate is given
			if ($tdate != "") { $d = TRUE; } 
			else { $d = FALSE; $message[] = "Please enter the end date of training."; }
			// check if eid is given
			if ($eid != 0) { $e = TRUE; } 
			else { $e = FALSE; $message[] = "Please select from the list of trainors."; }
			// check if mid is given
			if ($mid != 0) { $f = TRUE; } 
			else { $f = FALSE; $message[] = "Please select from the list of attendees."; }
			
			// If data pass all tests, proceed
			if ( $a && $b && $c && $d && $e && $f ) {
				// update data
				$sql = "UPDATE cooperatives_trainings
						SET tid = '$tid',
							tvenue = '$tvenue',
							fdate = '$fdate',
							tdate = '$tdate',
							eid = '$eid', 
							mid = '$mid',
							user_id = '$_SESSION[user_id]',
							user_dtime = NOW()
						WHERE ctid = '$ctid'
						LIMIT 1";
				if (query($sql)) {
					// log the activity
					log_user(2, 'cooperatives_trainings', $ctid);
					
					header("Location:tc.php?cid=".$cid."&tab=7");
					exit();
				} else {
					?>
					<div class="alert alert-danger alert-dismissible fade in" role="alert">
						<strong>Could not update training attended because: </strong><br />
						<b><?php echo mysqli_error($connection) ?></b><br />
						The query was <?php echo $sql ?>.<br />
					</div>
					<?PHP
				}
			} else {
				$serialized_message = serialize($message);
				// redirect the user to PHP_SELF
				header('location: e_coop_training.php?serialized_message='.$serialized_message.'&fid='.$fid);
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
				  
				<title>OTC | Update Training Attended</title>

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
					
					$ctid = $_GET['ctid'];

					$sql = "SELECT * FROM cooperatives_trainings WHERE ctid = '$ctid'";
					$res = query($sql); 
					$row = fetch_array($res); 
					
					$sql = "SELECT cname FROM cooperatives WHERE cid = '$row[6]'";
					$cres = query($sql); 
					$crow = fetch_array($cres); 
					?>

					<!-- page content -->
					<div class="right_col" role="main">
					  <div class="">
						<div class="page-title">
						  <div class="title_left">
							<h3>Update Training Attended</h3>
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
								
									<form action="e_coop_training.php" method="POST" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="cyear">Cooperative Name </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" class="form-control col-md-7 col-xs-12" readonly value="<?php echo $crow[0] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="tid">Training <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <select id="tid" name="tid" required="required" class="form-control col-md-7 col-xs-12">
											<option value="0">--- Select ---</option>
											<?php
											$sql = "SELECT tid, tname
													FROM tc_trainings 
													ORDER BY tname";
											$tres = query($sql); 
											while ($trow = fetch_array($tres)) {
												if ($row[1] == $trow[0]) {
													?><option value="<?php echo $trow[0] ?>" Selected><?php echo $trow[1] ?></option><?php
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
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="tvenue">Venue <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="tvenue" name="tvenue" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $row[2] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="fdate">From <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="date" id="fdate" name="fdate" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $row[3] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="tdate">To <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="date" id="tdate" name="tdate" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $row[4] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="eid">Conducted By <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <select id="eid" name="eid" required="required" class="form-control col-md-7 col-xs-12">
											<option value="0">--- Select ---</option>
											<?php
											$sql = "SELECT eid, CONCAT(lname,', ',fname)
													FROM employees 
													WHERE active = 1 AND did = 4 AND etype = 2
													ORDER BY lname, fname";
											$tres = query($sql); 
											while ($trow = fetch_array($tres)) {
												if ($row[5] == $trow[0]) {
													?><option value="<?php echo $trow[0] ?>" Selected><?php echo $trow[1] ?></option><?php
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
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="mid">Attendee <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <select id="mid" name="mid" required="required" class="form-control col-md-7 col-xs-12">
										  <option value="0">-- Select --</option>
										  <?php
										  $sql = "SELECT mid, CONCAT(lname,', ',fname) 
												  FROM cooperatives_members
												  WHERE cid = '$row[6]'
												  ORDER BY lname, fname";
										  $mres = query($sql); 
										  while ($mrow = fetch_array($mres)) {
											  if ($row[7] == $mrow[0]) {
												  ?><option value="<?php echo $mrow[0] ?>" Selected><?php echo $mrow[1] ?></option><?php
											  } else {
												  ?><option value="<?php echo $mrow[0] ?>"><?php echo $mrow[1] ?></option><?php
											  }
										  }
										  free_result($mres); 
										  ?>
										  </select>
										</div>
									  </div>
									  <div class="ln_solid"></div>
									  <div class="form-group">
										<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
										  <input type="hidden" name="submitted" value="1">
										  <input type="hidden" name="ctid" value="<?php echo $ctid ?>">
										  <input type="hidden" name="cid" value="<?php echo $row[6] ?>">
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

