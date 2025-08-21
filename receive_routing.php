<?php

include("db.php");
include("sqli.php");
include("html.php");
define('PAGE', 'receive_routing.php'); 

$rid = $_GET['rid'];

session_start();

if (isset($_SESSION['user_id'])) {
	if (validate_level(PAGE) == true) {

		if(isset($_POST['submitted'])) { // if submit button has been pressed
			$rid = filter_var($_POST['rid'], FILTER_SANITIZE_NUMBER_INT);
			$remarks = filter_var($_POST['remarks'], FILTER_SANITIZE_STRING);
			
			// check if date filled up is given
			if ($rid != "") { $a = TRUE; } 
			else { $a = FALSE; $message[] = "Please select from the list of routes."; }
			// check if remarks is given
			if ($remarks != "") { $b = TRUE; } 
			else { $b = FALSE; $message[] = "Please enter remarks."; }
			
			// If data pass all tests, proceed
			if ( $a && $b ) {
				$sql = "UPDATE routings
						SET remarks = '$remarks',
							receiver_id = '$_SESSION[user_id]',
							receiver_dtime = NOW()
						WHERE rid = '$rid'
						LIMIT 1";
				if (query($sql)) {
					// log the activity
					log_user(2, 'routings', $rid);
					
					// get the cid from routings
					$sql = "SELECT cid FROM routings WHERE rid = '$rid'";
					$cres = query($sql); 
					$crow = fetch_array($cres); 
					$cid = $crow[0]; 
					
					// redirect to communication details
					header("Location:communication.php?cid=".$cid."&success=1");
					exit();
				} else {
					?>
					<div class="alert alert-danger alert-dismissible fade in" role="alert">
						<strong>Could not update communication routing because: </strong><br />
						<b><?php echo mysqli_error($connection) ?></b><br />
						The query was <?php echo $sql ?>.<br />
					</div>
					<?PHP
				}
			} else {
				$serialized_message = serialize($message);
				// redirect the user to index page
				header('location: receive_routing.php?serialized_message='.$serialized_message);
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
				  
				<title>OTC | Communications Routing</title>

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
					
					$sql = "SELECT from_user_id, to_user_id, remarks, receiver_id
							FROM routings 
							WHERE rid = '$rid'";
					$res = query($sql); 
					$row = fetch_array($res); 
					?>

					<!-- page content -->
					<div class="right_col" role="main">
					  <div class="">
						<div class="page-title">
						  <div class="title_left">
							<h3>Receive Routing Details</h3>
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
						  
						  <?php 
						  
						  if ($row[3] == 0) {
							  // get the user_id of the employee_id (to_user_id)
							  $sql = "SELECT user_id FROM users WHERE eid = '$row[1]'";
							  $ures = query($sql); 
							  $urow = fetch_array($ures); 
							  if ($urow[0] == $_SESSION['user_id']) {
								  ?>
								  <div class="col-md-12 col-sm-12 col-xs-12">
									<div class="x_panel">
									  <div class="x_content">
										<br />
										
											<form action="receive_routing.php" method="POST" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
											  <div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="from_user_id">From <span class="required">*</span></label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <select id="from_user_id" name="from_user_id" disabled required="required" class="form-control col-md-7 col-xs-12">
													<option value="0">--- Select ---</option>
													<?php
													$sql = "SELECT eid, CONCAT(lname,', ',fname) 
															FROM employees 
															WHERE active = 1 
															ORDER BY lname, fname";
													$fres = query($sql); 
													while ($frow = fetch_array($fres)) {
														if ($row[0] == $frow[0]) {
															?><option value="<?php echo $frow[0] ?>" Selected><?php echo $frow[1] ?></option><?php
														} else {
															?><option value="<?php echo $frow[0] ?>"><?php echo $frow[1] ?></option><?php
														}
													}
													free_result($fres); 
													?>
												  </select>
												</div>
											  </div>
											  <div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="to_user_id">To <span class="required">*</span></label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <select id="to_user_id" name="to_user_id" disabled required="required" class="form-control col-md-7 col-xs-12">
													<option value="0">--- Select ---</option>
													<?php
													$sql = "SELECT eid, CONCAT(lname,', ',fname) 
															FROM employees 
															WHERE active = 1 
															ORDER BY lname, fname";
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
													<option value="1">Regular</option>
													<option value="2">Associate</option>
												  </select>
												</div>
											  </div>
											  <div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="remarks">Remarks <span class="required">*</span></label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <input type="text" id="remarks" name="remarks" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $row[2] ?>">
												</div>
											  </div>
											  <div class="ln_solid"></div>
											  <div class="form-group">
												<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
												  <input type="hidden" name="submitted" value="1">
												  <input type="hidden" name="rid" value="<?php echo $rid ?>">
												  <button type="submit" class="btn btn-success">Receive</button>
												</div>
											  </div>

											</form>
									  </div>
									</div>
								  </div>
								</div>
								  <?php
							  } else {
								  ?>
								  <div class="col-md-12 col-sm-12 col-xs-12">
									<div class="x_panel">
									  <div class="x_title">
										<h2></h2>
										<div class="clearfix"></div>
									  </div>
									  <div class="x_content">
									  <div class="bs-example" data-example-id="simple-jumbotron">
										<div class="jumbotron">
										  <h1>Operation Denied!</h1>
										  <p>You are not the intended recipient of this communication.</p>
										</div>
									  </div>
									  </div>
									</div>
								  </div>
								  <?php
							  }
							  free_result($ures); 
						  } else {
							  ?>
							  <div class="col-md-12 col-sm-12 col-xs-12">
								<div class="x_panel">
								  <div class="x_title">
									<h2></h2>
									<div class="clearfix"></div>
								  </div>
								  <div class="x_content">
								  <div class="bs-example" data-example-id="simple-jumbotron">
									<div class="jumbotron">
									  <h1>Operation Denied!</h1>
									  <p>This communication has been received already.</p>
									</div>
								  </div>
								  </div>
								</div>
							  </div>
							  <?php
						  }
						  
						  ?>

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

