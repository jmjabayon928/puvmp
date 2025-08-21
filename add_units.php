<?php

include("db.php");
include("sqli.php");
include("html.php");
define('PAGE', 'add_units.php'); 

session_start();

if (isset($_SESSION['user_id'])) {
	if (validate_level(PAGE) == true) {

		if(isset($_POST['submitted'])) { // if submit button has been pressed
			$cid = filter_var($_POST['cid'], FILTER_SANITIZE_NUMBER_INT);
			$cyear = filter_var($_POST['cyear'], FILTER_SANITIZE_NUMBER_INT);
			$class1_num = filter_var($_POST['class1_num'], FILTER_SANITIZE_NUMBER_INT);
			$class2_num = filter_var($_POST['class2_num'], FILTER_SANITIZE_NUMBER_INT);
			$class3_num = filter_var($_POST['class3_num'], FILTER_SANITIZE_NUMBER_INT);
			$class4_num = filter_var($_POST['class4_num'], FILTER_SANITIZE_NUMBER_INT);
			$puj_num = filter_var($_POST['puj_num'], FILTER_SANITIZE_NUMBER_INT);
			$mch_num = filter_var($_POST['mch_num'], FILTER_SANITIZE_NUMBER_INT);
			$taxi_num = filter_var($_POST['taxi_num'], FILTER_SANITIZE_NUMBER_INT);
			$mb_num = filter_var($_POST['mb_num'], FILTER_SANITIZE_NUMBER_INT);
			$bus_num = filter_var($_POST['bus_num'], FILTER_SANITIZE_NUMBER_INT);
			$mcab_num = filter_var($_POST['mcab_num'], FILTER_SANITIZE_NUMBER_INT);
			$truck_num = filter_var($_POST['truck_num'], FILTER_SANITIZE_NUMBER_INT);
			$tours_num = filter_var($_POST['tours_num'], FILTER_SANITIZE_NUMBER_INT);
			$uvx_num = filter_var($_POST['uvx_num'], FILTER_SANITIZE_NUMBER_INT);
			$banka_num = filter_var($_POST['banka_num'], FILTER_SANITIZE_NUMBER_INT);
			$coop_owned = filter_var($_POST['coop_owned'], FILTER_SANITIZE_NUMBER_INT);
			$individual_owned = filter_var($_POST['individual_owned'], FILTER_SANITIZE_NUMBER_INT);
			$coop_franchised = filter_var($_POST['coop_franchised'], FILTER_SANITIZE_NUMBER_INT);
			$individual_franchised = filter_var($_POST['individual_franchised'], FILTER_SANITIZE_NUMBER_INT);
			$wout_franchise = filter_var($_POST['wout_franchise'], FILTER_SANITIZE_NUMBER_INT);
			$remarks = filter_var($_POST['remarks'], FILTER_SANITIZE_STRING);
			
			// check if year is given
			if ($cyear != 0 && $cyear != '') { $a = TRUE; } 
			else { $a = FALSE; $message[] = "Please enter the year."; }
			// check if all numbers are zero is given
			if ( ($class1_num == 0 && $class1_num == '') &&
				 ($class2_num == 0 && $class2_num == '') &&
				 ($class3_num == 0 && $class3_num == '') &&
				 ($class4_num == 0 && $class4_num == '') &&
				 ($puj_num == 0 && $puj_num == '') &&
				 ($mch_num == 0 && $mch_num == '') &&
				 ($taxi_num == 0 && $taxi_num == '') &&
				 ($mb_num == 0 && $mb_num == '') &&
				 ($bus_num == 0 && $bus_num == '') &&
				 ($mcab_num == 0 && $mcab_num == '') &&
				 ($truck_num == 0 && $truck_num == '') &&
				 ($tours_num == 0 && $tours_num == '') &&
				 ($uvx_num == 0 && $uvx_num == '') &&
				 ($banka_num == 0 && $banka_num == '') &&
				 ($coop_owned == 0 && $coop_owned == '') &&
				 ($individual_owned == 0 && $individual_owned == '') &&
				 ($coop_franchised == 0 && $coop_franchised == '') &&
				 ($individual_franchised == 0 && $individual_franchised == '') &&
				 ($wout_franchise == 0 && $wout_franchise == '') 
			    ) { $b = FALSE; $message[] = "Values cannot be all zeroes."; } 
			else { $b = TRUE; }

			// If data pass all tests, proceed
			if ( $a && $b ) {
				
				// check for double entry first
				$sql = "SELECT cuid 
						FROM cooperatives_units 
						WHERE cid = '$cid' AND cyear = '$cyear'";
				$res = query($sql); 
				$num = num_rows($res); 
				
				if ($num > 0) {
					$sql = "UPDATE cooperatives_units
							SET cyear = '$cyear',
								class1_num = '$class1_num',
								class2_num = '$class2_num',
								class3_num = '$class3_num',
								class4_num = '$class4_num',
								puj_num = '$puj_num',
								mch_num = '$mch_num',
								taxi_num = '$taxi_num', 
								mb_num = '$mb_num',
								bus_num = '$bus_num',
								mcab_num = '$mcab_num',
								truck_num = '$truck_num',
								tours_num = '$tours_num',
								uvx_num = '$uvx_num',
								banka_num = '$banka_num',
								coop_owned = '$coop_owned',
								individual_owned = '$individual_owned',
								coop_franchised = '$coop_franchised',
								individual_franchised = '$individual_franchised',
								wout_franchise = '$wout_franchise',
								remarks = '$remarks',
								user_id = '$_SESSION[user_id]',
								user_dtime = NOW()
							WHERE cuid = '$cuid' AND cyear = '$cyear'
							LIMIT 1";
					if (query($sql)) {
							
						update_units($cid);
						
						// insert logs
						$sql = "INSERT INTO cooperatives_logs(cid, uid, ldtime) VALUES('$cid', '$_SESSION[user_id]', NOW())";
						query($sql);
						
						// log the activity
						log_user(2, 'cooperatives_units', $cuid);
						
						header("Location:tc.php?cid=".$cid."&tab=2&updated_units=1");
						exit();
					} else {
						?>
						<div class="alert alert-danger alert-dismissible fade in" role="alert">
							<strong>Could not update cooperative vehicle units because: </strong><br />
							<b><?php echo mysqli_error($connection) ?></b><br />
							The query was <?php echo $sql ?>.<br />
						</div>
						<?PHP
					}
				} else {
					$sql = "INSERT INTO cooperatives_units( 
								cid, cyear, class1_num, class2_num, class3_num, class4_num, puj_num, mch_num, 
								taxi_num, mb_num, bus_num, mcab_num, truck_num, 
								tours_num, uvx_num, banka_num, coop_owned, individual_owned, coop_franchised, 
								individual_franchised, wout_franchise, remarks, user_id, user_dtime )
							VALUES( 
								'$cid', '$cyear', '$class1_num', '$class2_num', '$class3_num', '$class4_num', '$puj_num', '$mch_num', 
								'$taxi_num', '$mb_num', '$bus_num', '$mcab_num', '$truck_num', 
								'$tours_num', '$uvx_num', '$banka_num', '$coop_owned', '$individual_owned', '$coop_franchised', 
								'$individual_franchised', '$wout_franchise', '$remarks', '$_SESSION[user_id]', NOW() )";
					if (query($sql)) {
						$cuid = mysqli_insert_id($connection);
						
						update_units($cid);
						
						// insert logs
						$sql = "INSERT INTO cooperatives_logs(cid, uid, ldtime) VALUES('$cid', '$_SESSION[user_id]', NOW())";
						query($sql);
						
						// log the activity
						log_user(1, 'cooperatives_units', $cuid);
						
						header("Location:tc.php?cid=".$cid."&tab=2&added_units=1");
						exit();
					} else {
						?>
						<div class="alert alert-danger alert-dismissible fade in" role="alert">
							<strong>Could not add cooperative vehicle units because: </strong><br />
							<b><?php echo mysqli_error($connection) ?></b><br />
							The query was <?php echo $sql ?>.<br />
						</div>
						<?PHP
					}
				}
			} else {
				$serialized_message = serialize($message);
				// redirect the user to PHP_SELF
				header('location: add_units.php?serialized_message='.$serialized_message.'&cid='.$cid.'&cyear='.$cyear.'&puj_num='.$puj_num.'&mch_num='.$mch_num.'&taxi_num='.$taxi_num.'&mb_num='.$mb_num.'&bus_num='.$bus_num.'&mcab_num='.$mcab_num.'&truck_num='.$truck_num.'&auv_num='.$auv_num.'&banka_num='.$banka_num.'&coop_owned='.$coop_owned.'&individual_owned='.$individual_owned.'&coop_franchised='.$coop_franchised.'&individual_franchised='.$individual_franchised.'&wout_franchise='.$wout_franchise.'&remarks='.$remarks);
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
				  
				<title>OTC | Add Number of Vehicle Units</title>

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
							<h3>Add Number of Vehicle Units</h3>
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
								
									<form action="add_units.php" method="POST" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="cname">Coop Name </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="cname" name="cname" class="form-control col-md-7 col-xs-12" readonly value="<?php echo $crow[0] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="cyear">Year <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <select id="cyear" name="cyear" required="required" class="form-control col-md-7 col-xs-12">
										  <?php
										  $sql = "SELECT DATE_FORMAT(CURDATE(), '%Y')";
										  $dres = query($sql); $drow = fetch_array($dres); 
										  for ($year=$drow[0]; $year>2009; $year--) {
											  if (isset ($_GET['cyear'])) {
												  if ($_GET['cyear'] == $year) {
													  ?><option value="<?php echo $year ?>" Selected><?php echo $year ?></option><?php
												  } else {
													  ?><option value="<?php echo $year ?>"><?php echo $year ?></option><?php
												  }
											  } else {
												  ?><option value="<?php echo $year ?>"><?php echo $year ?></option><?php
											  }
										  }
										  ?>
										  </select>
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="class1_num">PUVM Class 1</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="class1_num" name="class1_num" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['class1_num'])) {
											  ?>value="<?php echo $_GET['class1_num'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="class2_num">PUVM Class 2</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="class2_num" name="class2_num" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['class2_num'])) {
											  ?>value="<?php echo $_GET['class2_num'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="class3_num">PUVM Class 3</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="class3_num" name="class3_num" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['class3_num'])) {
											  ?>value="<?php echo $_GET['class3_num'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="class4_num">PUVM Class 4</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="class4_num" name="class4_num" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['class4_num'])) {
											  ?>value="<?php echo $_GET['class4_num'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="puj_num">PUJs </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="puj_num" name="puj_num" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['puj_num'])) {
											  ?>value="<?php echo $_GET['puj_num'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="mch_num">MCHs </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="mch_num" name="mch_num" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['mch_num'])) {
											  ?>value="<?php echo $_GET['mch_num'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="taxi_num">Taxis </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="taxi_num" name="taxi_num" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['taxi_num'])) {
											  ?>value="<?php echo $_GET['taxi_num'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="mb_num">Mini Buses </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="mb_num" name="mb_num" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['mb_num'])) {
											  ?>value="<?php echo $_GET['mb_num'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="bus_num">Buses </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="bus_num" name="bus_num" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['bus_num'])) {
											  ?>value="<?php echo $_GET['bus_num'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="mcab_num">Mini Cabs </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="mcab_num" name="mcab_num" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['mcab_num'])) {
											  ?>value="<?php echo $_GET['mcab_num'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="truck_num">Trucks </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="truck_num" name="truck_num" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['truck_num'])) {
											  ?>value="<?php echo $_GET['truck_num'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="tours_num">Tourist Service </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="tours_num" name="tours_num" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['tours_num'])) {
											  ?>value="<?php echo $_GET['tours_num'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="uvx_num">UV Express </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="uvx_num" name="uvx_num" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['uvx_num'])) {
											  ?>value="<?php echo $_GET['uvx_num'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="banka_num">Motorized Bancas </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="banka_num" name="banka_num" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['banka_num'])) {
											  ?>value="<?php echo $_GET['banka_num'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="coop_owned">Cooperative-Owned Units </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="coop_owned" name="coop_owned" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['coop_owned'])) {
											  ?>value="<?php echo $_GET['coop_owned'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="individual_owned">Individually-Owned Units </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="individual_owned" name="individual_owned" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['individual_owned'])) {
											  ?>value="<?php echo $_GET['individual_owned'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="coop_franchised">Cooperative-Franchised Units </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="coop_franchised" name="coop_franchised" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['coop_franchised'])) {
											  ?>value="<?php echo $_GET['coop_franchised'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="individual_franchised">Individually-Franchised Units </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="individual_franchised" name="individual_franchised" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['individual_franchised'])) {
											  ?>value="<?php echo $_GET['individual_franchised'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="wout_franchise">Units Without Franchise </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="wout_franchise" name="wout_franchise" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['wout_franchise'])) {
											  ?>value="<?php echo $_GET['wout_franchise'] ?>"<?php
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

