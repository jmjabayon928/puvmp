<?php

include("db.php");
include("sqli.php");
include("html.php");
define('PAGE', 'add_ltfrb_franchise.php'); 

session_start();

if (isset($_SESSION['user_id'])) {
	if (validate_level(PAGE) == true) {

		if(isset($_POST['submitted'])) { // if submit button has been pressed
			$case_num = filter_var($_POST['case_num'], FILTER_SANITIZE_STRING);
			$unit_type = filter_var($_POST['unit_type'], FILTER_SANITIZE_STRING);
			$owner = filter_var($_POST['owner'], FILTER_SANITIZE_STRING);
			$owner_type = filter_var($_POST['owner_type'], FILTER_SANITIZE_STRING);
			$units_num = filter_var($_POST['units_num'], FILTER_SANITIZE_NUMBER_INT);
			$dg_date = filter_var($_POST['dg_date'], FILTER_SANITIZE_STRING);
			$de_date = filter_var($_POST['de_date'], FILTER_SANITIZE_STRING);
			$route_code = filter_var($_POST['route_code'], FILTER_SANITIZE_STRING);
			$route = filter_var($_POST['route'], FILTER_SANITIZE_STRING);
			$remarks = filter_var($_POST['remarks'], FILTER_SANITIZE_STRING);
			
			// check if case_num is given
			if ($case_num != 0 && $case_num != '') { $a = TRUE; } 
			else { $a = FALSE; $message[] = "Please enter the LTFRB Case No."; }
			// check if unit_type is given
			if ($unit_type != '') { $b = TRUE; } 
			else { $b = FALSE; $message[] = "Please select from the list of types of units."; }
			// check if owner is given
			if ($owner != '') { $c = TRUE; } 
			else { $c = FALSE; $message[] = "Please enter the owner."; }
			// check if owner_type is given
			if ($owner_type != '') { $d = TRUE; } 
			else { $d = FALSE; $message[] = "Please select from the list of types of owners."; }
			// check if units_num is given
			if ($units_num != 0 && $units_num != '') { $e = TRUE; } 
			else { $e = FALSE; $message[] = "Please enter the number of authorized units."; }
			// check if dg_date is given
			if ($dg_date != '') { $f = TRUE; } 
			else { $f = FALSE; $message[] = "Please enter the date granted."; }
			// check if de_date is given
			if ($de_date != '') { $g = TRUE; } 
			else { $g = FALSE; $message[] = "Please enter the date of expiration."; }
			// check if route is given
			if ($route != '') { $h = TRUE; } 
			else { $h = FALSE; $message[] = "Please enter the route."; }

			// If data pass all tests, proceed
			if ( $a && $b && $c && $d && $e && $f && $g && $h ) {
				
				// check for double entry first
				$sql = "SELECT fid 
						FROM franchises_ltfrb 
						WHERE case_num = '$case_num' OR 
							(unit_type = '$unit_type' AND owner = '$owner' AND owner_type = '$owner_type' AND units_num = '$units_num')";
				$res = query($sql); 
				$num = num_rows($res); 
				
				if ($num > 0) {
					$row = fetch_array($res); 
					?>
					<div class="alert alert-danger alert-dismissible fade in" role="alert">
						<strong>A record with the same LTFRB case number or a record with the same type of unit, owner, type of owner and number of authorized units already exists! </strong><br /><br />
						<a href="ltfrb_franchise.php?fid=<?php echo $row[0] ?>&tab=4">Click here to view the record</a>
					</div>
					<?php
				} else {
					$sql = "INSERT INTO franchises_ltfrb(case_num, unit_type, owner, owner_type, units_num, dg_date, de_date, route_code, route, remarks, user_id, user_dtime)
							VALUES('$case_num', '$unit_type', '$owner', '$owner_type', '$units_num', '$dg_date', '$de_date', '$route_code', '$route', '$remarks', '$_SESSION[user_id]', NOW())";
					if (query($sql)) {
						$fid = mysqli_insert_id($connection);
						
						// log the activity
						log_user(1, 'franchises_ltfrb', $fid);
						
						header("Location: ltfrb_franchise.php?fid=".$fid);
						exit();
					} else {
						?>
						<div class="alert alert-danger alert-dismissible fade in" role="alert">
							<strong>Could not add LTFRB Franchise because: </strong><br />
							<b><?php echo mysqli_error($connection) ?></b><br />
							The query was <?php echo $sql ?>.<br />
						</div>
						<?PHP
					}
				}
			} else {
				$serialized_message = serialize($message);
				// redirect the user to PHP_SELF
				header('location: add_ltfrb_franchise.php?serialized_message='.$serialized_message.'&case_num='.$case_num.'&unit_type='.$unit_type.'&owner='.$owner.'&owner_type='.$owner_type.'&units_num='.$units_num.'&dg_date='.$dg_date.'&de_date='.$de_date.'&route_code='.$route_code.'&route='.$route.'&remarks='.$remarks);
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
				  
				<title>OTC | Add LTFRB Franchise</title>

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
							<h3>Add LTFRB Franchise</h3>
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
								
									<form action="add_ltfrb_franchise.php" method="POST" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="case_num">Case Number <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="case_num" name="case_num" required="required" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['case_num'])) {
											  ?>value="<?php echo $_GET['case_num'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="unit_type">Type of Vehicle <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <select id="unit_type" name="unit_type" required="required" class="form-control col-md-7 col-xs-12">
											<option value=""> -- Select -- </option>
											<?php
											if (isset ($_GET['unit_type'])) {
												if ($_GET['unit_type'] == 'City Bus') {
													?>
													<option value="City Bus" Selected>City Bus</option>
													<option value="PUJ">PUJ</option>
													<option value="FilCab">FilCab</option>
													<option value="Taxi">Taxi</option>
													<option value="Provincial Bus">Provincial Bus</option>
													<option value="School Service">School Service</option>
													<option value="Shuttle Service">Shuttle Service</option>
													<option value="Trucks">Trucks</option>
													<option value="Tourist Transport">Tourist Transport</option>
													<option value="TNVS">TNVS</option>
													<option value="UV Express">UV Express</option>
													<?php
												} elseif ($_GET['unit_type'] == 'PUJ') {
													?>
													<option value="City Bus">City Bus</option>
													<option value="PUJ" Selected>PUJ</option>
													<option value="FilCab">FilCab</option>
													<option value="Taxi">Taxi</option>
													<option value="Provincial Bus">Provincial Bus</option>
													<option value="School Service">School Service</option>
													<option value="Shuttle Service">Shuttle Service</option>
													<option value="Trucks">Trucks</option>
													<option value="Tourist Transport">Tourist Transport</option>
													<option value="TNVS">TNVS</option>
													<option value="UV Express">UV Express</option>
													<?php
												} elseif ($_GET['unit_type'] == 'FilCab') {
													?>
													<option value="City Bus">City Bus</option>
													<option value="PUJ">PUJ</option>
													<option value="FilCab" Selected>FilCab</option>
													<option value="Taxi">Taxi</option>
													<option value="Provincial Bus">Provincial Bus</option>
													<option value="School Service">School Service</option>
													<option value="Shuttle Service">Shuttle Service</option>
													<option value="Trucks">Trucks</option>
													<option value="Tourist Transport">Tourist Transport</option>
													<option value="TNVS">TNVS</option>
													<option value="UV Express">UV Express</option>
													<?php
												} elseif ($_GET['unit_type'] == 'Taxi') {
													?>
													<option value="City Bus">City Bus</option>
													<option value="PUJ">PUJ</option>
													<option value="FilCab">FilCab</option>
													<option value="Taxi" Selected>Taxi</option>
													<option value="Provincial Bus">Provincial Bus</option>
													<option value="School Service">School Service</option>
													<option value="Shuttle Service">Shuttle Service</option>
													<option value="Trucks">Trucks</option>
													<option value="Tourist Transport">Tourist Transport</option>
													<option value="TNVS">TNVS</option>
													<option value="UV Express">UV Express</option>
													<?php
												} elseif ($_GET['unit_type'] == 'Provincial Bus') {
													?>
													<option value="City Bus">City Bus</option>
													<option value="PUJ">PUJ</option>
													<option value="FilCab">FilCab</option>
													<option value="Taxi">Taxi</option>
													<option value="Provincial Bus" Selected>Provincial Bus</option>
													<option value="School Service">School Service</option>
													<option value="Shuttle Service">Shuttle Service</option>
													<option value="Trucks">Trucks</option>
													<option value="Tourist Transport">Tourist Transport</option>
													<option value="TNVS">TNVS</option>
													<option value="UV Express">UV Express</option>
													<?php
												} elseif ($_GET['unit_type'] == 'School Service') {
													?>
													<option value="City Bus">City Bus</option>
													<option value="PUJ">PUJ</option>
													<option value="FilCab">FilCab</option>
													<option value="Taxi">Taxi</option>
													<option value="Provincial Bus">Provincial Bus</option>
													<option value="School Service" Selected>School Service</option>
													<option value="Shuttle Service">Shuttle Service</option>
													<option value="Trucks">Trucks</option>
													<option value="Tourist Transport">Tourist Transport</option>
													<option value="TNVS">TNVS</option>
													<option value="UV Express">UV Express</option>
													<?php
												} elseif ($_GET['unit_type'] == 'Shuttle Service') {
													?>
													<option value="City Bus">City Bus</option>
													<option value="PUJ">PUJ</option>
													<option value="FilCab">FilCab</option>
													<option value="Taxi">Taxi</option>
													<option value="Provincial Bus">Provincial Bus</option>
													<option value="School Service">School Service</option>
													<option value="Shuttle Service" Selected>Shuttle Service</option>
													<option value="Trucks">Trucks</option>
													<option value="Tourist Transport">Tourist Transport</option>
													<option value="TNVS">TNVS</option>
													<option value="UV Express">UV Express</option>
													<?php
												} elseif ($_GET['unit_type'] == 'Trucks') {
													?>
													<option value="City Bus">City Bus</option>
													<option value="PUJ">PUJ</option>
													<option value="FilCab">FilCab</option>
													<option value="Taxi">Taxi</option>
													<option value="Provincial Bus">Provincial Bus</option>
													<option value="School Service">School Service</option>
													<option value="Shuttle Service">Shuttle Service</option>
													<option value="Trucks" Selected>Trucks</option>
													<option value="Tourist Transport">Tourist Transport</option>
													<option value="TNVS">TNVS</option>
													<option value="UV Express">UV Express</option>
													<?php
												} elseif ($_GET['unit_type'] == 'Tourist Transport') {
													?>
													<option value="City Bus">City Bus</option>
													<option value="PUJ">PUJ</option>
													<option value="FilCab">FilCab</option>
													<option value="Taxi">Taxi</option>
													<option value="Provincial Bus">Provincial Bus</option>
													<option value="School Service">School Service</option>
													<option value="Shuttle Service">Shuttle Service</option>
													<option value="Trucks">Trucks</option>
													<option value="Tourist Transport" Selected>Tourist Transport</option>
													<option value="TNVS">TNVS</option>
													<option value="UV Express">UV Express</option>
													<?php
												} elseif ($_GET['unit_type'] == 'TNVS') {
													?>
													<option value="City Bus">City Bus</option>
													<option value="PUJ">PUJ</option>
													<option value="FilCab">FilCab</option>
													<option value="Taxi">Taxi</option>
													<option value="Provincial Bus">Provincial Bus</option>
													<option value="School Service">School Service</option>
													<option value="Shuttle Service">Shuttle Service</option>
													<option value="Trucks">Trucks</option>
													<option value="Tourist Transport">Tourist Transport</option>
													<option value="TNVS" Selected>TNVS</option>
													<option value="UV Express">UV Express</option>
													<?php
												} elseif ($_GET['unit_type'] == 'UV Express') {
													?>
													<option value="City Bus">City Bus</option>
													<option value="PUJ">PUJ</option>
													<option value="FilCab">FilCab</option>
													<option value="Taxi">Taxi</option>
													<option value="Provincial Bus">Provincial Bus</option>
													<option value="School Service">School Service</option>
													<option value="Shuttle Service">Shuttle Service</option>
													<option value="Trucks">Trucks</option>
													<option value="Tourist Transport">Tourist Transport</option>
													<option value="TNVS">TNVS</option>
													<option value="UV Express" Selected>UV Express</option>
													<?php
												}
											} else {
												?>
												<option value="City Bus">City Bus</option>
												<option value="PUJ">PUJ</option>
												<option value="FilCab">FilCab</option>
												<option value="Taxi">Taxi</option>
												<option value="Provincial Bus">Provincial Bus</option>
												<option value="School Service">School Service</option>
												<option value="Shuttle Service">Shuttle Service</option>
												<option value="Trucks">Trucks</option>
												<option value="Tourist Transport">Tourist Transport</option>
												<option value="TNVS">TNVS</option>
												<option value="UV Express">UV Express</option>
												<?php
											}
											?>
										  </select>
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="owner">Owner <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="owner" name="owner" required="required" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['owner'])) {
											  ?>value="<?php echo $_GET['owner'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="owner_type">Owner Type <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <select id="owner_type" name="owner_type" required="required" class="form-control col-md-7 col-xs-12">
											<option value=""> -- Select -- </option>
											<?php
											if (isset ($_GET['owner_type'])) {
												if ($_GET['owner_type'] == 'Single Ownership') {
													?>
													<option value="Single Ownership" Selected>Single Ownership</option>
													<option value="Cooperative">Cooperative</option>
													<option value="Corporation">Corporation</option>
													<?php
												} elseif ($_GET['owner_type'] == 'Cooperative') {
													?>
													<option value="Single Ownership">Single Ownership</option>
													<option value="Cooperative" Selected>Cooperative</option>
													<option value="Corporation">Corporation</option>
													<?php
												} elseif ($_GET['owner_type'] == 'Corporation') {
													?>
													<option value="Single Ownership">Single Ownership</option>
													<option value="Cooperative">Cooperative</option>
													<option value="Corporation" Selected>Corporation</option>
													<?php
												}
											} else {
												?>
												<option value="Single Ownership">Single Ownership</option>
												<option value="Cooperative">Cooperative</option>
												<option value="Corporation">Corporation</option>
												<?php
											}
											?>
										  </select>
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="units_num">Units Authorized <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="units_num" name="units_num" required="required" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['units_num'])) {
											  ?>value="<?php echo $_GET['units_num'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="dg_date">Date Granted <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="date" id="dg_date" name="dg_date" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['dg_date'])) {
											  ?>value="<?php echo $_GET['dg_date'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="de_date">Expiry Date <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="date" id="de_date" name="de_date" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['de_date'])) {
											  ?>value="<?php echo $_GET['de_date'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="route_code">Route Code </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="route_code" name="route_code" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['route_code'])) {
											  ?>value="<?php echo $_GET['route_code'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="route">Route <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="route" name="route" required="required" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['route'])) {
											  ?>value="<?php echo $_GET['route'] ?>"<?php
										  }
										  ?>
										  >
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

