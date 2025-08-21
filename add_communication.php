<?php

include("db.php");
include("sqli.php");
include("html.php");
define('PAGE', 'add_communication.php'); 

session_start();

if (isset($_SESSION['user_id'])) {
	if (validate_level(PAGE) == true) {

		if(isset($_POST['submitted'])) { // if submit button has been pressed
			$direction = filter_var($_POST['direction'], FILTER_SANITIZE_STRING);
			$ref_num = filter_var($_POST['ref_num'], FILTER_SANITIZE_STRING);
			$source_ofc = filter_var($_POST['source_ofc'], FILTER_SANITIZE_STRING);
			$source_person = filter_var($_POST['source_person'], FILTER_SANITIZE_STRING);
			$source_position = filter_var($_POST['source_position'], FILTER_SANITIZE_STRING);
			$receiver_ofc = filter_var($_POST['receiver_ofc'], FILTER_SANITIZE_STRING);
			$receiver_person = filter_var($_POST['receiver_person'], FILTER_SANITIZE_STRING);
			$receiver_position = filter_var($_POST['receiver_position'], FILTER_SANITIZE_STRING);
			$ddate = filter_var($_POST['ddate'], FILTER_SANITIZE_STRING);
			$subject = filter_var($_POST['subject'], FILTER_SANITIZE_STRING);
			$remarks = filter_var($_POST['remarks'], FILTER_SANITIZE_STRING);
			$rdate = filter_var($_POST['rdate'], FILTER_SANITIZE_STRING);
			
			$ttid = filter_var($_POST['ttid'], FILTER_SANITIZE_NUMBER_INT);
			$tname = filter_var($_POST['tname'], FILTER_SANITIZE_STRING);
			$coop_id = filter_var($_POST['coop_id'], FILTER_SANITIZE_NUMBER_INT);
			$new_cname = filter_var($_POST['new_cname'], FILTER_SANITIZE_STRING);
			$status = filter_var($_POST['status'], FILTER_SANITIZE_NUMBER_INT);
			$remarks2 = filter_var($_POST['remarks2'], FILTER_SANITIZE_STRING);
			
			// check if source_ofc of letter is given
			if ($source_ofc != "") { $b = TRUE; } 
			else { $b = FALSE; $message[] = "Please enter the Originating Office."; }
			// check if source_person of letter is given
			if ($source_person != "") { $c = TRUE; } 
			else { $c = FALSE; $message[] = "Please enter the source person of the letter."; }
			// check if position of sender is given
			if ($source_position != "") { $d = TRUE; } 
			else { $d = FALSE; $message[] = "Please enter the position of the sender."; }
			
			// check if receiver_ofc of letter is given
			if ($receiver_ofc != "") { $e = TRUE; } 
			else { $e = FALSE; $message[] = "Please enter the Destination Office."; }
			// check if receiver_person of letter is given
			if ($receiver_person != "") { $f = TRUE; } 
			else { $f = FALSE; $message[] = "Please enter the intended receiver of the letter."; }
			// check if position of sender is given
			if ($receiver_position != "") { $g = TRUE; } 
			else { $g = FALSE; $message[] = "Please enter the position of the receiver."; }
			
			// check if date of document is given
			if ($ddate != "") { $h = TRUE; } 
			else { $h = FALSE; $message[] = "Please enter the date of document."; }
			// check if subject of document is given
			if ($subject != "") { $i = TRUE; } 
			else { $i = FALSE; $message[] = "Please enter the subject of document."; }
			// check if date received is given
			if ($rdate != "") { $j = TRUE; } 
			else { $j = FALSE; $message[] = "Please enter the date of receipt."; }
			// if transaction fields are all present or all unselected
			if (($ttid != 0 && $tname != '' && $coop_id != 0) || ($ttid == 0 && $tname == '' && $coop_id == 0)) { $k = TRUE; }
			else { $k = FALSE; $message[] = "All fields for transaction must be present or unselected"; }
			
			// If data pass all tests, proceed
			if ( $b && $c && $d && $e && $f && $g && $h && $i && $j && $k ) {
				
				// check for double entry first 
				$sql = "SELECT cid FROM communications
						WHERE ref_num = '$ref_num' AND source_ofc = '$source_ofc' 
							AND source_person = '$source_person' 
							AND receiver_ofc = '$source_ofc' AND receiver_person = '$source_person' 
							AND ddate = '$ddate' AND rdate = '$rdate'";
				$res = query($sql); 
				$num = num_rows($res); 
				
				if ($num > 0) {
					$row = fetch_array($res); 
					?>
					<div class="alert alert-danger alert-dismissible fade in" role="alert">
						<strong>Record already exists! </strong><br /><br />
						<a href="communication.php?cid=<?php echo $row[0] ?>">Click here to view the record</a>
					</div>
					<?php
				} else {
					$sql = "INSERT INTO communications( direction, ref_num, source_ofc, source_person, source_position, receiver_ofc, receiver_person, receiver_position, ddate, subject, remarks, rdate, user_id, user_dtime )
							VALUES( '$direction', '$ref_num', '$source_ofc', '$source_person', '$source_position', '$receiver_ofc', '$receiver_person', '$receiver_position', '$ddate', '$subject', '$remarks', '$rdate', '$_SESSION[user_id]', NOW() )";
					if (@query($sql)) {
						$cid = mysqli_insert_id($connection);
						// log the activity
						log_user(1, 'communications', $cid);
						
						if ($direction == 1 && $ttid != 0 && $tname != '' && ( ($cid != 0 && $new_cname == '') || ($cid == 0 && $new_cname != '') ) ) {
							
							if ($new_cname != '' && $coop_id == 0) {
								// insert into cooperatives
								$sql = "INSERT INTO cooperatives(ctype, cname, last_update_id, last_update)
										VALUES('0', '$new_cname', '$_SESSION[user_id]', NOW())";
								query($sql); 
								$coop_id = mysqli_insert_id($connection);
							}
							
							// insert into transactions
							$sql = "INSERT INTO transactions(ref_num, tdtime, ttid, tname, cid, status, remarks, user_id, user_dtime)
									VALUES('$ref_num', NOW(), '$ttid', '$tname', '$coop_id', '$status', '$remarks2', '$_SESSION[user_id]', NOW())";
							query($sql); 
							$tid = mysqli_insert_id($connection);
							// log the activity
							log_user(1, 'transactions', $tid);
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
				// redirect the user to index page
				header('location: add_communication.php?serialized_message='.$serialized_message.'&ref_num='.$ref_num.'&source_ofc='.$source_ofc.'&source_person='.$source_person.'&source_position='.$source_position.'&ddate='.$ddate.'&subject='.$subject.'&remarks='.$remarks.'&rdate='.$rdate);
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
				  
				<title>OTC | Add Communication</title>

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
								
									<form action="add_communication.php" method="POST" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="direction">Type of Communication </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <select id="direction" name="direction" class="form-control col-md-7 col-xs-12">
											<option value="1">Incoming</option>
											<option value="2">Out-Going</option>
											<option value="3">Internal</option>
										  </select>
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="ref_num">Reference Number </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="ref_num" name="ref_num" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['ref_num'])) {
											  ?>value="<?php echo $_GET['ref_num'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="source_ofc">Originating Office <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="source_ofc" name="source_ofc" required="required" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['source_ofc'])) {
											  ?>value="<?php echo $_GET['source_ofc'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="source_person">Source Person <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="source_person" name="source_person" required="required" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['source_person'])) {
											  ?>value="<?php echo $_GET['source_person'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="source_position">Source Person's Position <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="source_position" name="source_position" required="required" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['source_position'])) {
											  ?>value="<?php echo $_GET['source_position'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="receiver_ofc">Destination Office <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="receiver_ofc" name="receiver_ofc" required="required" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['receiver_ofc'])) {
											  ?>value="<?php echo $_GET['receiver_ofc'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="receiver_person">Intended Receiver <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="receiver_person" name="receiver_person" required="required" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['receiver_person'])) {
											  ?>value="<?php echo $_GET['receiver_person'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="receiver_position">Intended Receiver's Position <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="receiver_position" name="receiver_position" required="required" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['receiver_position'])) {
											  ?>value="<?php echo $_GET['receiver_position'] ?>"<?php
										  }
										  ?>
										  >
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
									  <div class="form-group">
										<label for="rdate" class="control-label col-md-3 col-sm-3 col-xs-12">Date Received </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input id="rdate" name="rdate" required="required" class="form-control col-md-7 col-xs-12" type="date" 
										  <?php
										  if (isset ($_GET['rdate'])) {
											  ?>value="<?php echo $_GET['rdate'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="ln_solid"></div>
									  <?php
									  if (validate_level2('add_transaction.php') == true) {
										  ?>
										  <div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12"> </label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											Fill-Up for Transactions Record
											</div>
										  </div>
										  <div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" for="ttid">Type of Transaction </label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											  <select id="ttid" name="ttid" class="form-control col-md-7 col-xs-12">
												<option value="0"> -- Select -- </option>
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
											<label class="control-label col-md-3 col-sm-3 col-xs-12" for="tname">Transacting Person </label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											  <input type="text" id="tname" name="tname" class="form-control col-md-7 col-xs-12" 
											  <?php
											  if (isset ($_GET['tname'])) {
												  ?>value="<?php echo $_GET['tname'] ?>"<?php
											  }
											  ?>
											  >
											</div>
										  </div>
										  <div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" for="coop_id">Cooperative </label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											  <select id="coop_id" name="coop_id" class="form-control col-md-7 col-xs-12">
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
											<label class="control-label col-md-3 col-sm-3 col-xs-12" for="status">Status </label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											  <select id="status" name="status" class="form-control col-md-7 col-xs-12">
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
											<label class="control-label col-md-3 col-sm-3 col-xs-12" for="remarks2">Remarks </label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											  <input type="text" id="remarks2" name="remarks2" class="form-control col-md-7 col-xs-12" 
											  <?php
											  if (isset ($_GET['remarks'])) {
												  ?>value="<?php echo $_GET['remarks'] ?>"<?php
											  }
											  ?>
											  >
											</div>
										  </div>
										  <div class="ln_solid"></div>
										  <?php
									  }
									  ?>
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

