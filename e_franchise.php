<?php

include("db.php");
include("sqli.php");
include("html.php");
define('PAGE', 'e_franchise.php'); 

session_start();

if (isset($_SESSION['user_id'])) {
	if (validate_level(PAGE) == true) {

		if(isset($_POST['submitted'])) { // if submit button has been pressed
			$fid = filter_var($_POST['fid'], FILTER_SANITIZE_NUMBER_INT);
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
				// update data
				$sql = "UPDATE franchises
						SET utype2 = '$utype2',
							utype = '$utype',
							units_num = '$units_num',
							route_start = '$route_start',
							route_end = '$route_end', 
							vice_versa = '$vice_versa',
							ltfrb_case = '$ltfrb_case',
							exp_date = '$exp_date',
							user_id = '$_SESSION[user_id]',
							user_dtime = NOW()
						WHERE fid = '$fid'
						LIMIT 1";
				if (query($sql)) {
					// log the activity
					log_user(2, 'franchises', $fid);
					
					// get the cid of fid 
					$sql = "SELECT cid FROM franchises WHERE fid = '$fid'";
					$cres = query($sql); 
					$crow = fetch_array($cres); 
					$cid = $crow[0]; 
					
					header("Location:tc.php?cid=".$cid."&tab=5&updated_franchise=1");
					exit();
				} else {
					?>
					<div class="alert alert-danger alert-dismissible fade in" role="alert">
						<strong>Could not update franchise units because: </strong><br />
						<b><?php echo mysqli_error($connection) ?></b><br />
						The query was <?php echo $sql ?>.<br />
					</div>
					<?PHP
				}
			} else {
				$serialized_message = serialize($message);
				// redirect the user to PHP_SELF
				header('location: e_franchise.php?serialized_message='.$serialized_message.'&fid='.$fid);
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
				  
				<title>OTC | Update Franchise</title>

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
					
					$fid = $_GET['fid'];

					$sql = "SELECT c.cname
							FROM franchises f INNER JOIN cooperatives c ON f.cid = c.cid
							WHERE f.fid = '$fid'";
					$cres = query($sql); $crow = fetch_array($cres); 
					
					$sql = "SELECT * FROM franchises WHERE fid = '$fid'";
					$res = query($sql); 
					$row = fetch_array($res); 
					?>

					<!-- page content -->
					<div class="right_col" role="main">
					  <div class="">
						<div class="page-title">
						  <div class="title_left">
							<h3>Update Franchise</h3>
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
								
									<form action="e_franchise.php" method="POST" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="cyear">Cooperative Name </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" class="form-control col-md-7 col-xs-12" readonly value="<?php echo $crow[0] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="utype2">Route Type <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <select id="utype2" name="utype2" required="required" class="form-control col-md-7 col-xs-12">
											<option value="0"> -- Select -- </option>
											<?php 
											if ($row[2] == 1) {
												?>
												<option value="1" Selected>Existing</option>
												<option value="0">Proposed</option>
												<?php
											} elseif ($row[2] == 0) {
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
											?>
										  </select>
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="utype">Type of Service <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <select id="utype" name="utype" required="required" class="form-control col-md-7 col-xs-12">
											<option value="0"> -- Select -- </option>
											<?php
												if ($row[3] == 1) {
													?>
													<option value="11">PUVM Class 1</option>
													<option value="12">PUVM Class 2</option>
													<option value="13">PUVM Class 3</option>
													<option value="14">PUVM Class 4</option>
													<option value="1" Selected>PUJ</option>
													<option value="10">Tourist Service</option>
													<option value="2">UV Express</option>
													<option value="3">Taxi</option>
													<option value="4">Multicab</option>
													<option value="5">Mini Bus</option>
													<option value="6">Bus</option>
													<option value="7">Tricycle</option>
													<option value="8">Truck</option>
													<option value="9">Motirized Banca</option>
													<?php
												} elseif ($row[3] == 2) {
													?>
													<option value="11">PUVM Class 1</option>
													<option value="12">PUVM Class 2</option>
													<option value="13">PUVM Class 3</option>
													<option value="14">PUVM Class 4</option>
													<option value="1">PUJ</option>
													<option value="10">Tourist Service</option>
													<option value="2" Selected>UV Express</option>
													<option value="3">Taxi</option>
													<option value="4">Multicab</option>
													<option value="5">Mini Bus</option>
													<option value="6">Bus</option>
													<option value="7">Tricycle</option>
													<option value="8">Truck</option>
													<option value="9">Motirized Banca</option>
													<?php
												} elseif ($row[3] == 3) {
													?>
													<option value="11">PUVM Class 1</option>
													<option value="12">PUVM Class 2</option>
													<option value="13">PUVM Class 3</option>
													<option value="14">PUVM Class 4</option>
													<option value="1">PUJ</option>
													<option value="10">Tourist Service</option>
													<option value="2">UV Express</option>
													<option value="3" Selected>Taxi</option>
													<option value="4">Multicab</option>
													<option value="5">Mini Bus</option>
													<option value="6">Bus</option>
													<option value="7">Tricycle</option>
													<option value="8">Truck</option>
													<option value="9">Motirized Banca</option>
													<?php
												} elseif ($row[3] == 4) {
													?>
													<option value="11">PUVM Class 1</option>
													<option value="12">PUVM Class 2</option>
													<option value="13">PUVM Class 3</option>
													<option value="14">PUVM Class 4</option>
													<option value="1">PUJ</option>
													<option value="10">Tourist Service</option>
													<option value="2">UV Express</option>
													<option value="3">Taxi</option>
													<option value="4" Selected>Multicab</option>
													<option value="5">Mini Bus</option>
													<option value="6">Bus</option>
													<option value="7">Tricycle</option>
													<option value="8">Truck</option>
													<option value="9">Motirized Banca</option>
													<?php
												} elseif ($row[3] == 5) {
													?>
													<option value="11">PUVM Class 1</option>
													<option value="12">PUVM Class 2</option>
													<option value="13">PUVM Class 3</option>
													<option value="14">PUVM Class 4</option>
													<option value="1">PUJ</option>
													<option value="10">Tourist Service</option>
													<option value="2">UV Express</option>
													<option value="3">Taxi</option>
													<option value="4">Multicab</option>
													<option value="5" Selected>Mini Bus</option>
													<option value="6">Bus</option>
													<option value="7">Tricycle</option>
													<option value="8">Truck</option>
													<option value="9">Motirized Banca</option>
													<?php
												} elseif ($row[3] == 6) {
													?>
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
													<option value="6" Selected>Bus</option>
													<option value="7">Tricycle</option>
													<option value="8">Truck</option>
													<option value="9">Motirized Banca</option>
													<?php
												} elseif ($row[3] == 7) {
													?>
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
													<option value="7" Selected>Tricycle</option>
													<option value="8">Truck</option>
													<option value="9">Motirized Banca</option>
													<?php
												} elseif ($row[3] == 8) {
													?>
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
													<option value="8" Selected>Truck</option>
													<option value="9">Motirized Banca</option>
													<?php
												} elseif ($row[3] == 9) {
													?>
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
													<option value="9" Selected>Motirized Banca</option>
													<?php
												} elseif ($row[3] == 10) {
													?>
													<option value="11">PUVM Class 1</option>
													<option value="12">PUVM Class 2</option>
													<option value="13">PUVM Class 3</option>
													<option value="14">PUVM Class 4</option>
													<option value="1">PUJ</option>
													<option value="10" Selected>Tourist Service</option>
													<option value="2">UV Express</option>
													<option value="3">Taxi</option>
													<option value="4">Multicab</option>
													<option value="5">Mini Bus</option>
													<option value="6">Bus</option>
													<option value="7">Tricycle</option>
													<option value="8">Truck</option>
													<option value="9">Motirized Banca</option>
													<?php
												} elseif ($row[3] == 11) {
													?>
													<option value="11" Selected>PUVM Class 1</option>
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
													<option value="9">Motirized Banca</option>
													<?php
												} elseif ($row[3] == 12) {
													?>
													<option value="11">PUVM Class 1</option>
													<option value="12" Selected>PUVM Class 2</option>
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
													<option value="9">Motirized Banca</option>
													<?php
												} elseif ($row[3] == 13) {
													?>
													<option value="11">PUVM Class 1</option>
													<option value="12">PUVM Class 2</option>
													<option value="13" Selected>PUVM Class 3</option>
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
													<option value="9">Motirized Banca</option>
													<?php
												} elseif ($row[3] == 14) {
													?>
													<option value="11">PUVM Class 1</option>
													<option value="12">PUVM Class 2</option>
													<option value="13">PUVM Class 3</option>
													<option value="14" Selected>PUVM Class 4</option>
													<option value="1">PUJ</option>
													<option value="10">Tourist Service</option>
													<option value="2">UV Express</option>
													<option value="3">Taxi</option>
													<option value="4">Multicab</option>
													<option value="5">Mini Bus</option>
													<option value="6">Bus</option>
													<option value="7">Tricycle</option>
													<option value="8">Truck</option>
													<option value="9">Motirized Banca</option>
													<?php
												} else {
													?>
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
													<option value="9">Motirized Banca</option>
													<?php
												}
											?>
										  </select>
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="units_num">Number of Units <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="units_num" name="units_num" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $row[4] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="route_start">Route Start <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="route_start" name="route_start" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $row[5] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="route_end">Route Destination <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="route_end" name="route_end" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $row[6] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="vice_versa">Route Vice-Versa <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <select id="vice_versa" name="vice_versa" required="required" class="form-control col-md-7 col-xs-12">
										    <?php
											if ($row[7] == 0) {
												?>
												<option value="0" Selected> NO </option>
												<option value="1"> YES </option>
												<?php
											} elseif ($row[7] == 1) {
												?>
												<option value="0"> NO </option>
												<option value="1" Selected> YES </option>
												<?php
											}
											?>
										  </select>
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="ltfrb_case">LTFRB Case No. <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="ltfrb_case" name="ltfrb_case" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $row[8] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="exp_date">Expiry Date </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="date" id="exp_date" name="exp_date" class="form-control col-md-7 col-xs-12" value="<?php echo $row[9] ?>">
										</div>
									  </div>
									  <div class="ln_solid"></div>
									  <div class="form-group">
										<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
										  <input type="hidden" name="submitted" value="1">
										  <input type="hidden" name="fid" value="<?php echo $fid ?>">
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

