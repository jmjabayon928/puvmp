<?php

include("db.php");
include("sqli.php");
include("html.php");
define('PAGE', 'add_transaction.php'); 

session_start();

if (isset($_SESSION['user_id'])) {
	if (validate_level(PAGE) == true) {

		if(isset($_POST['submitted'])) { // if submit button has been pressed
			$tdtime = filter_var($_POST['tdtime'], FILTER_SANITIZE_STRING);
			$ttid = filter_var($_POST['ttid'], FILTER_SANITIZE_NUMBER_INT);
			$tname = filter_var($_POST['tname'], FILTER_SANITIZE_STRING);
			$new_cname = filter_var($_POST['new_cname'], FILTER_SANITIZE_STRING);
			$cid = filter_var($_POST['cid'], FILTER_SANITIZE_NUMBER_INT);
			$status = filter_var($_POST['status'], FILTER_SANITIZE_NUMBER_INT);
			$remarks = filter_var($_POST['remarks'], FILTER_SANITIZE_STRING);
			
			// check if tdtime is given
			if ($tdtime != '') { $b = TRUE; } 
			else { $b = FALSE; $message[] = "Please enter the date and time of transaction."; }
			// check if ttid is given
			if ($ttid != 0 && $ttid != '') { $c = TRUE; } 
			else { $c = FALSE; $message[] = "Please select the type of transaction."; }
			// check if tname is given
			if ($tname != '') { $d = TRUE; } 
			else { $d = FALSE; $message[] = "Please enter the name of transacting person."; }
			// check if status is given
			if ($status != '') { $e = TRUE; } 
			else { $e = FALSE; $message[] = "Please select from the list of statuses."; }
			// if transaction fields are all present or all unselected
			if (($ttid != 0 && $tname != '' && $cid != 0) || ($ttid == 0 && $tname == '' && $cid == 0)) { $f = TRUE; }
			else { $f = FALSE; $message[] = "All fields for transaction must be present or unselected"; }

			// If data pass all tests, proceed
			if ( $b && $c && $d && $e ) {
				
				// check for double entry first
				$sql = "SELECT tid 
						FROM transactions 
						WHERE tdtime = '$tdtime' AND ttid = '$ttid' AND tname = '$tname'";
				$res = query($sql); 
				$num = num_rows($res); 
				
				if ($num > 0) {
					$row = fetch_array($res); 
					?>
					<div class="alert alert-danger alert-dismissible fade in" role="alert">
						<strong>A record with the same date/time of transaction, type of transaction and transacting person already exists! </strong><br /><br />
						<a href="add_transaction.php?tid=<?php echo $row[0] ?>&tab=4">Click here to view the record</a>
					</div>
					<?php
				} else {
					// count the number of transaction for this month
					$sql = "SELECT tid 
							FROM transactions 
							WHERE YEAR(tdtime) = YEAR(CURDATE()) AND MONTH(tdtime) = MONTH(CURDATE())";
					$res = query($sql); 
					$num = num_rows($res); 
					$nxt_num = $num + 1; 
					
					if (strlen($nxt_num == 1)) { $nxt_num = '00'.$nxt_num;
					} elseif (strlen($nxt_num == 2)) { $nxt_num = '0'.$nxt_num;
					}
					
					$ref_num = date("Y-m-").$nxt_num;
					
					if ($new_cname != '' && $cid == 0) {
						// insert into cooperatives
						$sql = "INSERT INTO cooperatives(ctype, cname, last_update_id, last_update)
								VALUES('0', '$new_cname', '$_SESSION[user_id]', NOW())";
						query($sql); 
						$cid = mysqli_insert_id($connection);
						
						// log the activity
						log_user(1, 'cooperatives', $cid);
						
						
						$sql = "INSERT INTO transactions(ref_num, tdtime, ttid, tname, coop_id, status, remarks, user_id, user_dtime)
								VALUES('$ref_num', '$tdtime', '$ttid', '$tname', '$cid', '$status', '$remarks', '$_SESSION[user_id]', NOW())";
						if (query($sql)) {
							$tid = mysqli_insert_id($connection);
							
							// log the activity
							log_user(1, 'transactions', $tid);
							
							header("Location: transaction.php?tid=".$tid);
							exit();
						} else {
							?>
							<div class="alert alert-danger alert-dismissible fade in" role="alert">
								<strong>Could not add OTC Transaction because: </strong><br />
								<b><?php echo mysqli_error($connection) ?></b><br />
								The query was <?php echo $sql ?>.<br />
							</div>
							<?PHP
						}
						
					} elseif ($new_cname == '' && $cid != 0) {

						$sql = "INSERT INTO transactions(ref_num, tdtime, ttid, tname, coop_id, status, remarks, user_id, user_dtime)
								VALUES('$ref_num', '$tdtime', '$ttid', '$tname', '$cid', '$status', '$remarks', '$_SESSION[user_id]', NOW())";
						if (query($sql)) {
							$tid = mysqli_insert_id($connection);
							
							// log the activity
							log_user(1, 'transactions', $tid);
							
							header("Location: transaction.php?tid=".$tid);
							exit();
						} else {
							?>
							<div class="alert alert-danger alert-dismissible fade in" role="alert">
								<strong>Could not add OTC Transaction because: </strong><br />
								<b><?php echo mysqli_error($connection) ?></b><br />
								The query was <?php echo $sql ?>.<br />
							</div>
							<?PHP
						}
						
					} else {
						echo 'No action has been taken. Click the back button to return to form.';
					}

				}
			} else {
				$serialized_message = serialize($message);
				// redirect the user to PHP_SELF
				header('location: add_transaction.php?serialized_message='.$serialized_message.'&tdtime='.$tdtime.'&ttid='.$ttid.'&tname='.$tname.'&cid='.$cid.'&status='.$status.'&remarks='.$remarks);
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
				  
				<title>OTC | Add OTC Transaction</title>

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
							<h3>Add OTC Transaction</h3>
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
								
									<form action="add_transaction.php" method="POST" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="tdtime">Date / Time <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="datetime-local" id="tdtime" name="tdtime" required="required" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['tdtime'])) {
											  ?>value="<?php echo $_GET['tdtime'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="ttid">Type of Transaction <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <select id="ttid" name="ttid" required="required" class="form-control col-md-7 col-xs-12">
											<option value=""> -- Select -- </option>
											<?php
											$sql = "SELECT tid, tname FROM tc_transactions";
											$tres = query($sql); 
											while ($trow = fetch_array($tres)) {
												if (isset ($_GET['ttid'])) {
													if ($_GET['ttid'] == $trow[0]) {
														?><option value="<?php echo $trow[0] ?>" Selected><?php echo $trow[1] ?></option><?php
													} else {
														?><option value="<?php echo $trow[0] ?>"><?php echo $trow[1] ?></option><?php
													}
												} else {
													?><option value="<?php echo $trow[0] ?>"><?php echo $trow[1] ?></option><?php
												}
											}
											?>
										  </select>
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="tname">Transacting Person <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="tname" name="tname" required="required" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['tname'])) {
											  ?>value="<?php echo $_GET['tname'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="cid">Cooperative </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <select id="cid" name="cid" class="form-control col-md-7 col-xs-12">
											<option value=""> -- Select -- </option>
											<?php
											$sql = "SELECT cid, cname 
													FROM cooperatives 
													WHERE ctype = 2
													ORDER BY cname";
											$res = query($sql); 
											while ($row = fetch_array($res)) {
												?><option value="<?php echo $row[0] ?>"><?php echo $row[1] ?></option><?php
											}
											free_result($res); 
											?>
										  </select>
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="new_cname">New Cooperative Name </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="new_cname" name="new_cname" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['new_cname'])) {
											  ?>value="<?php echo $_GET['new_cname'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="status">Status <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <select id="status" name="status" required="required" class="form-control col-md-7 col-xs-12">
											<option value=""> -- Select -- </option>
											<?php
											if (isset ($_GET['status'])) {
												if ($_GET['status'] == 1) {
													?>
													<option value="1" Selected>Received By Records</option>
													<option value="2">On Process By OD</option>
													<option value="3">For OD Chief Verification</option>
													<option value="4">For OC Approval</option>
													<option value="5">For Release By Records</option>
													<?php
												} elseif ($_GET['status'] == 2) {
													?>
													<option value="1">Received By Records</option>
													<option value="2" Selected>On Process By OD</option>
													<option value="3">For OD Chief Verification</option>
													<option value="4">For OC Approval</option>
													<option value="5">For Release By Records</option>
													<?php
												} elseif ($_GET['status'] == 3) {
													?>
													<option value="1">Received By Records</option>
													<option value="2">On Process By OD</option>
													<option value="3" Selected>For OD Chief Verification</option>
													<option value="4">For OC Approval</option>
													<option value="5">For Release By Records</option>
													<?php
												} elseif ($_GET['status'] == 4) {
													?>
													<option value="1">Received By Records</option>
													<option value="2">On Process By OD</option>
													<option value="3">For OD Chief Verification</option>
													<option value="4" Selected>For OC Approval</option>
													<option value="5">For Release By Records</option>
													<?php
												} elseif ($_GET['status'] == 5) {
													?>
													<option value="1">Received By Records</option>
													<option value="2">On Process By OD</option>
													<option value="3">For OD Chief Verification</option>
													<option value="4">For OC Approval</option>
													<option value="5" Selected>For Release By Records</option>
													<?php
												}
											} else {
												?>
												<option value="1">Received By Records</option>
												<option value="2">On Process By OD</option>
												<option value="3">For OD Chief Verification</option>
												<option value="4">For OC Approval</option>
												<option value="5">For Release By Records</option>
												<?php
											}
											?>
										  </select>
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="remarks">Remarks </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="remarks" name="remarks" class="form-control col-md-7 col-xs-12" 
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

