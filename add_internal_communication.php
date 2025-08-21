<?php

include("db.php");
include("sqli.php");
include("html.php");
define('PAGE', 'add_internal_communication.php'); 

session_start();

if (isset($_SESSION['user_id'])) {
	if (validate_level(PAGE) == true) {

		if(isset($_POST['submitted'])) { // if submit button has been pressed
			$ref_num = filter_var($_POST['ref_num'], FILTER_SANITIZE_STRING);
			$eid1 = filter_var($_POST['eid1'], FILTER_SANITIZE_NUMBER_INT);
			$eid2 = filter_var($_POST['eid2'], FILTER_SANITIZE_NUMBER_INT);
			$ddate = filter_var($_POST['ddate'], FILTER_SANITIZE_STRING);
			$subject = filter_var($_POST['subject'], FILTER_SANITIZE_STRING);
			$remarks = filter_var($_POST['remarks'], FILTER_SANITIZE_STRING);
			
			// check if eid1 is given
			if ($eid1 != 0) { $a = TRUE; } 
			else { $a = FALSE; $message[] = "Please select from the list of senders."; }
			// check if eid2 is given
			if ($eid2 != 0) { $b = TRUE; } 
			else { $b = FALSE; $message[] = "Please select from the list of receivers."; }
			// check if ddate is given
			if ($ddate != "") { $c = TRUE; } 
			else { $c = FALSE; $message[] = "Please enter the document date."; }
			// check if subject is given
			if ($subject != "") { $d = TRUE; } 
			else { $d = FALSE; $message[] = "Please enter the subject."; }
			// check if ref_num is given
			if ($ref_num != "") { $e = TRUE; } 
			else { $e = FALSE; $message[] = "Please enter the reference number."; }
			
			// If data pass all tests, proceed
			if ( $a && $b && $c && $d ) {
				
				// get the sender's office, name and position
				$sql = "SELECT d.dname, CONCAT(e.fname,' ',e.lname), p.pname
						FROM employees e 
							INNER JOIN positions p ON e.pid = p.pid
							INNER JOIN departments d ON e.did = d.did
						WHERE e.eid = '$eid1'";
				$sres = query($sql); 
				$srow = fetch_array($sres); 
				// get the receiver's office, name and position
				$sql = "SELECT d.dname, CONCAT(e.fname,' ',e.lname), p.pname
						FROM employees e 
							INNER JOIN positions p ON e.pid = p.pid
							INNER JOIN departments d ON e.did = d.did
						WHERE e.eid = '$eid2'";
				$rres = query($sql); 
				$rrow = fetch_array($rres); 
				
					$sql = "INSERT INTO communications( direction, ref_num, source_ofc, source_person, source_position, receiver_ofc, receiver_person, receiver_position, ddate, subject, remarks, rdate, user_id, user_dtime )
							VALUES( '3', '$ref_num', '$srow[0]', '$srow[1]', '$srow[2]', '$rrow[0]', '$rrow[1]', '$rrow[2]', '$ddate', '$subject', '$remarks', CURDATE(), '$_SESSION[user_id]', NOW() )";
					if (@query($sql)) {
						$cid = mysqli_insert_id($connection);
						// log the activity
						log_user(1, 'communications', $cid);
						
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
				
			} else {
				$serialized_message = serialize($message);
				// redirect the user to index page
				header('location: add_internal_communication.php?serialized_message='.$serialized_message.'&ref_num='.$ref_num.'&eid1='.$eid1.'&eid2='.$eid2.'&ddate='.$ddate.'&subject='.$subject.'&remarks='.$remarks);
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
				  
				<title>OTC | Add Internal Communication</title>

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
							<h3>Add Communication</h3>
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
								
									<form action="add_internal_communication.php" method="POST" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
									  <div class="form-group">
										<label for="ref_num" class="control-label col-md-3 col-sm-3 col-xs-12">Control Number <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input id="ref_num" name="ref_num" required="required" class="form-control col-md-7 col-xs-12" type="text" 
										  <?php
										  if (isset ($_GET['ref_num'])) {
											  ?>value="<?php echo $_GET['ref_num'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="eid1">Source Person <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <select id="eid1" name="eid1" required="required" class="form-control col-md-7 col-xs-12">
										    <option value="0">--- Select ---</option>
											<?php
											$sql = "SELECT eid, CONCAT(lname,', ',fname)
													FROM employees
													WHERE active = 1
													ORDER BY lname, fname";
											$res = query($sql); 
											while ($row = fetch_array($res)) {
												if (isset ($_GET['eid1'])) {
													if ($_GET['eid1'] == $row[0]) {
														?><option value="<?php echo $row[0] ?>" Selected><?php echo $row[1] ?></option><?php
													} else {
														if ($row[0] == $_SESSION['user_id']) {
															?><option value="<?php echo $row[0] ?>" Selected><?php echo $row[1] ?></option><?php
														} else {
															?><option value="<?php echo $row[0] ?>"><?php echo $row[1] ?></option><?php
														}
													}
												} else {
													if ($row[0] == $_SESSION['user_id']) {
														?><option value="<?php echo $row[0] ?>" Selected><?php echo $row[1] ?></option><?php
													} else {
														?><option value="<?php echo $row[0] ?>"><?php echo $row[1] ?></option><?php
													}
												}
											}
											?>
										  </select>
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="eid2">Intended Receiver <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <select id="eid2" name="eid2" required="required" class="form-control col-md-7 col-xs-12">
										    <option value="0">--- Select ---</option>
											<?php
											$sql = "SELECT eid, CONCAT(lname,', ',fname)
													FROM employees
													WHERE active = 1
													ORDER BY lname, fname";
											$res = query($sql); 
											while ($row = fetch_array($res)) {
												if (isset ($_GET['eid1'])) {
													if ($_GET['eid1'] == $row[0]) {
														?><option value="<?php echo $row[0] ?>" Selected><?php echo $row[1] ?></option><?php
													} else {
														if ($row[0] == $_SESSION['user_id']) {
															?><option value="<?php echo $row[0] ?>" Selected><?php echo $row[1] ?></option><?php
														} else {
															?><option value="<?php echo $row[0] ?>"><?php echo $row[1] ?></option><?php
														}
													}
												} else {
													?><option value="<?php echo $row[0] ?>"><?php echo $row[1] ?></option><?php
												}
											}
											?>
										  </select>
										</div>
									  </div>
									  <div class="form-group">
										<label for="ddate" class="control-label col-md-3 col-sm-3 col-xs-12">Date of Document <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input id="ddate" name="ddate" required="required" class="form-control col-md-7 col-xs-12" type="date" 
										  <?php
										  if (isset ($_GET['ddate'])) {
											  ?>value="<?php echo $_GET['ddate'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label for="subject" class="control-label col-md-3 col-sm-3 col-xs-12">Subject <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input id="subject" name="subject" required="required" class="form-control col-md-7 col-xs-12" type="text" 
										  <?php
										  if (isset ($_GET['subject'])) {
											  ?>value="<?php echo $_GET['subject'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label for="remarks" class="control-label col-md-3 col-sm-3 col-xs-12">Remarks </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input id="remarks" name="remarks" class="form-control col-md-7 col-xs-12" type="text" 
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

