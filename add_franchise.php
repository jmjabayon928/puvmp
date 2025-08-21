<?php

include("db.php");
include("sqli.php");
include("html.php");
define('PAGE', 'add_franchise.php'); 

session_start();

if (isset($_SESSION['user_id'])) {
	if (validate_level(PAGE) == true) {

		if(isset($_POST['submitted'])) { // if submit button has been pressed
			$cid = filter_var($_POST['cid'], FILTER_SANITIZE_NUMBER_INT);
			$utype2 = filter_var($_POST['utype2'], FILTER_SANITIZE_NUMBER_INT);
			$utype = filter_var($_POST['utype'], FILTER_SANITIZE_NUMBER_INT);
			$units_num = filter_var($_POST['units_num'], FILTER_SANITIZE_NUMBER_INT);
			$route_start = filter_var($_POST['route_start'], FILTER_SANITIZE_STRING);
			$route_end = filter_var($_POST['route_end'], FILTER_SANITIZE_STRING);
			$vice_versa = filter_var($_POST['vice_versa'], FILTER_SANITIZE_NUMBER_INT);
			$ltfrb_case = filter_var($_POST['ltfrb_case'], FILTER_SANITIZE_STRING);
			$exp_date = filter_var($_POST['exp_date'], FILTER_SANITIZE_STRING);
			$own_type = filter_var($_POST['own_type'], FILTER_SANITIZE_NUMBER_INT);
			
			// check if utype is given
			if ($utype != 0 && $utype != '') { $a = TRUE; } 
			else { $a = FALSE; $message[] = "Please select from the list of vehicle types."; }
			// check if units_num is given
			if ($units_num != 0 && $units_num != '') { $b = TRUE; } 
			else { $b = FALSE; $message[] = "Please enter the number of units."; }
			// check if route_start is given
			if ($route_start != '') { $c = TRUE; } 
			else { $c = FALSE; $message[] = "Please enter the start point."; }
			// check if route end is given
			if ($route_end != '') { $d = TRUE; } 
			else { $d = FALSE; $message[] = "Please enter the destination."; }
			// check if ltfrb case number is given
			if ($ltfrb_case != '') { $e = TRUE; } 
			else { $e = FALSE; $message[] = "Please enter the LTFRB case number."; }

			// If data pass all tests, proceed
			if ( $a && $b && $c && $d && $e ) {
				
				// check for double entry first
				$sql = "SELECT fid 
						FROM franchises 
						WHERE ltfrb_case = '$ltfrb_case' AND cid = '$cid' AND route_start = '$route_start' AND route_end = '$route_end' AND utype = '$utype' AND units_num = '$units_num'";
				$res = query($sql); 
				$num = num_rows($res); 
				
				if ($num > 0) {
					?>
					<div class="alert alert-danger alert-dismissible fade in" role="alert">
						<strong>A record with the same LTFRB case number or a record with the same cooperative, start route and end route already exists! </strong><br /><br />
						<a href="tc.php?cid=<?php echo $cid ?>&tab=4">Click here to view the record</a>
					</div>
					<?php
				} else {
					$sql = "INSERT INTO franchises( cid, utype2, utype, units_num, route_start, route_end, vice_versa, ltfrb_case, exp_date, user_id, user_dtime )
							VALUES( '$cid', '$utype2', '$utype', '$units_num', '$route_start', '$route_end', '$vice_versa', '$ltfrb_case', '$exp_date', '$_SESSION[user_id]', NOW() )";
					if (query($sql)) {
						$fid = mysqli_insert_id($connection);
						
						// log the activity
						log_user(1, 'franchises', $fid);
						
						header("Location:tc.php?cid=".$cid."&tab=5&added_franchise=1");
						exit();
					} else {
						?>
						<div class="alert alert-danger alert-dismissible fade in" role="alert">
							<strong>Could not add franchise units because: </strong><br />
							<b><?php echo mysqli_error($connection) ?></b><br />
							The query was <?php echo $sql ?>.<br />
						</div>
						<?PHP
					}
				}
			} else {
				$serialized_message = serialize($message);
				// redirect the user to PHP_SELF
				header('location: add_franchise.php?serialized_message='.$serialized_message.'&cid='.$cid.'&utype2='.$utype2.'&utype='.$utype.'&units_num='.$units_num.'&route_start='.$route_start.'&route_end='.$route_end.'&vice_versa='.$vice_versa.'&ltfrb_case='.$ltfrb_case.'&exp_date='.$exp_date);
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
				  
				<title>OTC | Add Franchise</title>

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
							<h3>Add Franchise</h3>
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
						  $cid = $_GET['cid'];
						  $sql = "SELECT cname FROM cooperatives WHERE cid = '$cid'";
						  $cres = query($sql); 
						  $crow = fetch_array($cres); 
						  free_result($cres); 

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
								
									<form action="add_franchise.php" method="POST" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="cname">Coop Name </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="cname" name="cname" class="form-control col-md-7 col-xs-12" readonly value="<?php echo $crow[0] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="utype2">Route Type <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <select id="utype2" name="utype2" required="required" class="form-control col-md-7 col-xs-12">
											<option value="0"> -- Select -- </option>
											<?php 
											if (isset ($_GET['utype2'])) {
												if ($_GET['utype2'] == 1) {
													?>
													<option value="1" Selected>Existing</option>
													<option value="0">Proposed</option>
													<?php
												} elseif ($_GET['utype2'] == 0) {
													?>
													<option value="1">Existing</option>
													<option value="0" Selected>Proposed</option>
													<?php
												} else {
													?>
													<option value="1">Existing</option>
													<option value="0">Proposed</option>
													<?php
												}
											} else {
												?>
												<option value="1">Existing</option>
												<option value="0">Proposed</option>
												<?php
											}
											?>
										  </select>
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="utype">Type of Service <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <select id="utype" name="utype" required="required" class="form-control col-md-7 col-xs-12">
											<option value="0"> -- Select -- </option>
												<option value="11">PUVM Class 1</option>
												<option value="12">PUVM Class 2</option>
												<option value="13">PUVM Class 3</option>
												<option value="14">PUVM Class 4</option>
												<option value="1">PUJ</option>
												<option value="10">Tourist Service</option>
												<option value="2">UV Express</option>
												<option value="3">Taxi</option>
												<option value="4">Multicab</option>
												<option value="5">Mini Bus</option>
												<option value="6">Bus</option>
												<option value="7">Tricycle</option>
												<option value="8">Truck</option>
												<option value="9">Motorized Banca</option>
										  </select>
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="units_num">Number of Units <span class="required">*</span></label>
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
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="route_start">Route Start <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="route_start" name="route_start" required="required" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['route_start'])) {
											  ?>value="<?php echo $_GET['route_start'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="route_end">Route Destination <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="route_end" name="route_end" required="required" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['route_end'])) {
											  ?>value="<?php echo $_GET['route_end'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="vice_versa">Route Vice-Versa <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <select id="vice_versa" name="vice_versa" required="required" class="form-control col-md-7 col-xs-12">
										    <?php
											if (isset ($_GET['vice_versa'])) {
												if ($_GET['vice_versa'] == 0) {
													?>
													<option value="0" Selected> NO </option>
													<option value="1"> YES </option>
													<?php
												} elseif ($_GET['vice_versa'] == 1) {
													?>
													<option value="0"> NO </option>
													<option value="1" Selected> YES </option>
													<?php
												}
											} else {
												?>
												<option value="0"> NO </option>
												<option value="1"> YES </option>
												<?php
											}
											?>
										  </select>
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="ltfrb_case">LTFRB Case No. <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="ltfrb_case" name="ltfrb_case" required="required" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['ltfrb_case'])) {
											  ?>value="<?php echo $_GET['ltfrb_case'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="exp_date">Expiry Date </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="date" id="exp_date" name="exp_date" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['exp_date'])) {
											  ?>value="<?php echo $_GET['exp_date'] ?>"<?php
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

