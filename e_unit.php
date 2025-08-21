<?php

include("db.php");
include("sqli.php");
include("html.php");
define('PAGE', 'e_unit.php'); 

session_start();

if (isset($_SESSION['user_id'])) {
	if (validate_level(PAGE) == true) {

		if(isset($_POST['submitted'])) { // if submit button has been pressed
			$uid = filter_var($_POST['uid'], FILTER_SANITIZE_NUMBER_INT);
			$utype = filter_var($_POST['utype'], FILTER_SANITIZE_NUMBER_INT);
			$mv_num = filter_var($_POST['mv_num'], FILTER_SANITIZE_STRING);
			$engine_num = filter_var($_POST['engine_num'], FILTER_SANITIZE_STRING);
			$chassis_num = filter_var($_POST['chassis_num'], FILTER_SANITIZE_STRING);
			$plate_num = filter_var($_POST['plate_num'], FILTER_SANITIZE_STRING);
			$cpc_num = filter_var($_POST['cpc_num'], FILTER_SANITIZE_STRING);
			$cpc_date = filter_var($_POST['cpc_date'], FILTER_SANITIZE_STRING);
			$cpc_exp = filter_var($_POST['cpc_exp'], FILTER_SANITIZE_STRING);
			$route_start = filter_var($_POST['route_start'], FILTER_SANITIZE_STRING);
			$route_end = filter_var($_POST['route_end'], FILTER_SANITIZE_STRING);
			$route_via = filter_var($_POST['route_via'], FILTER_SANITIZE_STRING);
			$vice_versa = filter_var($_POST['vice_versa'], FILTER_SANITIZE_NUMBER_INT);
			$owner_fname = filter_var($_POST['owner_fname'], FILTER_SANITIZE_STRING);
			$owner_mname = filter_var($_POST['owner_mname'], FILTER_SANITIZE_STRING);
			$owner_lname = filter_var($_POST['owner_lname'], FILTER_SANITIZE_STRING);
			
			// check if utype is given
			if ($utype != 0) { $a = TRUE; } 
			else { $a = FALSE; $message[] = "Please select from the types of unit."; }
			// check if mv_num is given
			if ($mv_num != '') { $b = TRUE; } 
			else { $b = FALSE; $message[] = "Please enter the MV File Number."; }
			// check if engine_num is given
			if ($engine_num != '') { $c = TRUE; } 
			else { $c = FALSE; $message[] = "Please enter the Engine Number."; }
			// check if chassis_num is given
			if ($chassis_num != '') { $d = TRUE; } 
			else { $d = FALSE; $message[] = "Please enter the Chassis Number."; }
			// check if plate_num is given
			if ($plate_num != '') { $e = TRUE; } 
			else { $e = FALSE; $message[] = "Please enter the Plate Number."; }
			// check if cpc_num is given
			if ($cpc_num != '') { $f = TRUE; } 
			else { $f = FALSE; $message[] = "Please enter the CPC Number."; }
			// check if cpc_date is given
			if ($cpc_date != '') { $g = TRUE; } 
			else { $g = FALSE; $message[] = "Please enter the CPC Date Granted."; }
			// check if cpc_exp is given
			if ($cpc_exp != '') { $h = TRUE; } 
			else { $h = FALSE; $message[] = "Please enter the CPC Expiry."; }
			// check if route_start is given
			if ($route_start != '') { $i = TRUE; } 
			else { $i = FALSE; $message[] = "Please enter the Route Origin."; }
			// check if route_end is given
			if ($route_end != '') { $j = TRUE; } 
			else { $j = FALSE; $message[] = "Please enter the Route Destination."; }
			// check if route_via is given
			if ($route_via != '') { $k = TRUE; } 
			else { $k = FALSE; $message[] = "Please enter the Route Via."; }
			// check if owner_fname is given
			if ($owner_fname != '') { $l = TRUE; } 
			else { $l = FALSE; $message[] = "Please enter the Owner's First Name."; }
			// check if owner_mname is given
			if ($owner_mname != '') { $m = TRUE; } 
			else { $m = FALSE; $message[] = "Please enter the Owner's Middle Name."; }
			// check if owner_lname is given
			if ($owner_lname != '') { $n = TRUE; } 
			else { $n = FALSE; $message[] = "Please enter the Owner's Last Name."; }

			// If data pass all tests, proceed
			if ( $a && $b && $c && $d && $e && $f && $i && $j && $k && $l && $m && $n ) {
				// update data
				$sql = "UPDATE cooperatives_units2
						SET utype = '$utype',
							mv_num = '$mv_num',
							engine_num = '$engine_num',
							chassis_num = '$chassis_num', 
							plate_num = '$plate_num',
							cpc_num = '$cpc_num',
							cpc_date = '$cpc_date',
							cpc_exp = '$cpc_exp',
							route_start = '$route_start',
							route_end = '$route_end',
							route_via = '$route_via',
							vice_versa = '$vice_versa',
							owner_fname = '$owner_fname',
							owner_mname = '$owner_mname',
							owner_lname = '$owner_lname',
							user_id = '$_SESSION[user_id]',
							user_dtime = NOW()
						WHERE uid = '$uid'
						LIMIT 1";
				if (query($sql)) {
						
					// get the cid of uid 
					$sql = "SELECT cid FROM cooperatives_units2 WHERE uid = '$uid'";
					$cres = query($sql); 
					$crow = fetch_array($cres); 
					$cid = $crow[0]; 
					
					update_units($cid);
					
					// insert logs
					$sql = "INSERT INTO cooperatives_logs(cid, uid, ldtime) VALUES('$cid', '$_SESSION[user_id]', NOW())";
					query($sql);
					
					// log the activity
					log_user(2, 'cooperatives_units', $uid);
					
					header("Location:tc.php?cid=".$cid."&tab=4&updated_units2=1");
					exit();
				} else {
					?>
					<div class="alert alert-danger alert-dismissible fade in" role="alert">
						<strong>Could not update vehicle unit because: </strong><br />
						<b><?php echo mysqli_error($connection) ?></b><br />
						The query was <?php echo $sql ?>.<br />
					</div>
					<?PHP
				}
			} else {
				$serialized_message = serialize($message);
				// redirect the user to PHP_SELF
				header('location: e_unit.php?serialized_message='.$serialized_message.'&uid='.$uid);
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
				  
				<title>OTC | Update Vehicle Unit</title>

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
					
					$uid = $_GET['uid'];

					$sql = "SELECT c.cname
							FROM cooperatives_units2 u INNER JOIN cooperatives c ON u.cid = c.cid
							WHERE u.uid = '$uid'";
					$cres = query($sql); $crow = fetch_array($cres); 
					
					$sql = "SELECT * FROM cooperatives_units2 WHERE uid = '$uid'";
					$res = query($sql); 
					$row = fetch_array($res); 
					?>

					<!-- page content -->
					<div class="right_col" role="main">
					  <div class="">
						<div class="page-title">
						  <div class="title_left">
							<h3>Update Vehicle Unit</h3>
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
								
									<form action="e_unit.php" method="POST" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="cyear">Cooperative Name </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" class="form-control col-md-7 col-xs-12" readonly value="<?php echo $crow[0] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="utype">Type of Unit <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <select id="utype" name="utype" required="required" class="form-control col-md-7 col-xs-12">
										    <option value="0">--- Select ---</option>
											<?php
											if ($row[2] == 1) {
												?>
												<option value="1" Selected>PUJ</option>
												<option value="2">Tricycle</option>
												<option value="3">Taxi</option>
												<option value="4">Mini Bus</option>
												<option value="5">Bus</option>
												<option value="6">Multi Cab</option>
												<option value="7">Truck</option>
												<option value="8">AUV</option>
												<option value="9">Motorized Banca</option>
												<?php
											} elseif ($row[2] == 2) {
												?>
												<option value="1">PUJ</option>
												<option value="2" Selected>Tricycle</option>
												<option value="3">Taxi</option>
												<option value="4">Mini Bus</option>
												<option value="5">Bus</option>
												<option value="6">Multi Cab</option>
												<option value="7">Truck</option>
												<option value="8">AUV</option>
												<option value="9">Motorized Banca</option>
												<?php
											} elseif ($row[2] == 3) {
												?>
												<option value="1">PUJ</option>
												<option value="2">Tricycle</option>
												<option value="3" Selected>Taxi</option>
												<option value="4">Mini Bus</option>
												<option value="5">Bus</option>
												<option value="6">Multi Cab</option>
												<option value="7">Truck</option>
												<option value="8">AUV</option>
												<option value="9">Motorized Banca</option>
												<?php
											} elseif ($row[2] == 4) {
												?>
												<option value="1">PUJ</option>
												<option value="2">Tricycle</option>
												<option value="3">Taxi</option>
												<option value="4" Selected>Mini Bus</option>
												<option value="5">Bus</option>
												<option value="6">Multi Cab</option>
												<option value="7">Truck</option>
												<option value="8">AUV</option>
												<option value="9">Motorized Banca</option>
												<?php
											} elseif ($row[2] == 5) {
												?>
												<option value="1">PUJ</option>
												<option value="2">Tricycle</option>
												<option value="3">Taxi</option>
												<option value="4">Mini Bus</option>
												<option value="5" Selected>Bus</option>
												<option value="6">Multi Cab</option>
												<option value="7">Truck</option>
												<option value="8">AUV</option>
												<option value="9">Motorized Banca</option>
												<?php
											} elseif ($row[2] == 6) {
												?>
												<option value="1">PUJ</option>
												<option value="2">Tricycle</option>
												<option value="3">Taxi</option>
												<option value="4">Mini Bus</option>
												<option value="5">Bus</option>
												<option value="6" Selected>Multi Cab</option>
												<option value="7">Truck</option>
												<option value="8">AUV</option>
												<option value="9">Motorized Banca</option>
												<?php
											} elseif ($row[2] == 7) {
												?>
												<option value="1">PUJ</option>
												<option value="2">Tricycle</option>
												<option value="3">Taxi</option>
												<option value="4">Mini Bus</option>
												<option value="5">Bus</option>
												<option value="6">Multi Cab</option>
												<option value="7" Selected>Truck</option>
												<option value="8">AUV</option>
												<option value="9">Motorized Banca</option>
												<?php
											} elseif ($row[2] == 8) {
												?>
												<option value="1">PUJ</option>
												<option value="2">Tricycle</option>
												<option value="3">Taxi</option>
												<option value="4">Mini Bus</option>
												<option value="5">Bus</option>
												<option value="6">Multi Cab</option>
												<option value="7">Truck</option>
												<option value="8" Selected>AUV</option>
												<option value="9">Motorized Banca</option>
												<?php
											} elseif ($row[2] == 9) {
												?>
												<option value="1">PUJ</option>
												<option value="2">Tricycle</option>
												<option value="3">Taxi</option>
												<option value="4">Mini Bus</option>
												<option value="5">Bus</option>
												<option value="6">Multi Cab</option>
												<option value="7">Truck</option>
												<option value="8">AUV</option>
												<option value="9" Selected>Motorized Banca</option>
												<?php
											}
											?>
										  </select>
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="mv_num">MV File No. <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="mv_num" name="mv_num" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $row[3] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="engine_num">Engine No. <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="engine_num" name="engine_num" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $row[4] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="chassis_num">Chassis No. <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="chassis_num" name="chassis_num" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $row[5] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="plate_num">Plate No. <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="plate_num" name="plate_num" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $row[6] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="cpc_num">Case No. <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="cpc_num" name="cpc_num" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $row[7] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="cpc_date">CPC Date Granted </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="date" id="cpc_date" name="cpc_date" class="form-control col-md-7 col-xs-12" value="<?php echo $row[8] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="cpc_exp">CPC Expiry </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="date" id="cpc_exp" name="cpc_exp" class="form-control col-md-7 col-xs-12" value="<?php echo $row[9] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="route_start">Route Origin <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="route_start" name="route_start" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $row[10] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="route_end">Route Destination <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="route_end" name="route_end" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $row[11] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="route_via">Route Via <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="route_via" name="route_via" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $row[12] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="vice_versa">Vice Versa <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
											<?php
											if ($row[13] == 1) {
												?>
												Yes:
												<input type="radio" class="flat" name="vice_versa" id="vice_versa" value="1" checked="" /> No:
												<input type="radio" class="flat" name="vice_versa" id="vice_versa" value="0" />
												<?php
											} elseif ($row[13] == 0) {
												?>
												Yes:
												<input type="radio" class="flat" name="vice_versa" id="vice_versa" value="1"  /> No:
												<input type="radio" class="flat" name="vice_versa" id="vice_versa" value="0" checked="" />
												<?php
											}
											?>
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="owner_fname">Owner's First Name <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="owner_fname" name="owner_fname" class="form-control col-md-7 col-xs-12" value="<?php echo $row[14] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="owner_mname">Owner's Middle Name <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="owner_mname" name="owner_mname" class="form-control col-md-7 col-xs-12" value="<?php echo $row[15] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="owner_lname">Owner's Last Name <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="owner_lname" name="owner_lname" class="form-control col-md-7 col-xs-12" value="<?php echo $row[16] ?>">
										</div>
									  </div>
									  <div class="ln_solid"></div>
									  <div class="form-group">
										<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
										  <input type="hidden" name="submitted" value="1">
										  <input type="hidden" name="uid" value="<?php echo $uid ?>">
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

