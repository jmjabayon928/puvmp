<?php

include("db.php");
include("sqli.php");
include("html.php");
define('PAGE', 'add_member2.php'); 

session_start();

if (isset($_SESSION['user_id'])) {
	if (validate_level(PAGE) == true) {

		if(isset($_POST['submitted'])) { // if submit button has been pressed
			$cid = filter_var($_POST['cid'], FILTER_SANITIZE_NUMBER_INT);
			$cyear = filter_var($_POST['cyear'], FILTER_SANITIZE_NUMBER_INT);
			$operator_rm = filter_var($_POST['operator_rm'], FILTER_SANITIZE_NUMBER_INT);
			$operator_rf = filter_var($_POST['operator_rf'], FILTER_SANITIZE_NUMBER_INT);
			$operator_am = filter_var($_POST['operator_am'], FILTER_SANITIZE_NUMBER_INT);
			$operator_af = filter_var($_POST['operator_af'], FILTER_SANITIZE_NUMBER_INT);
			$driver_rm = filter_var($_POST['driver_rm'], FILTER_SANITIZE_NUMBER_INT);
			$driver_rf = filter_var($_POST['driver_rf'], FILTER_SANITIZE_NUMBER_INT);
			$driver_am = filter_var($_POST['driver_am'], FILTER_SANITIZE_NUMBER_INT);
			$driver_af = filter_var($_POST['driver_af'], FILTER_SANITIZE_NUMBER_INT);
			$worker_rm = filter_var($_POST['worker_rm'], FILTER_SANITIZE_NUMBER_INT);
			$worker_rf = filter_var($_POST['worker_rf'], FILTER_SANITIZE_NUMBER_INT);
			$worker_am = filter_var($_POST['worker_am'], FILTER_SANITIZE_NUMBER_INT);
			$worker_af = filter_var($_POST['worker_af'], FILTER_SANITIZE_NUMBER_INT);
			$other_rm = filter_var($_POST['other_rm'], FILTER_SANITIZE_NUMBER_INT);
			$other_rf = filter_var($_POST['other_rf'], FILTER_SANITIZE_NUMBER_INT);
			$other_am = filter_var($_POST['other_am'], FILTER_SANITIZE_NUMBER_INT);
			$other_af = filter_var($_POST['other_af'], FILTER_SANITIZE_NUMBER_INT);

			// check if cyear is given
			if ($cyear != "") { $a = TRUE; } 
			else { $a = FALSE; $message[] = "Please enter the year."; }

			// If data pass all tests, proceed
			if ( $a ) {
				
				// check for double entry first
				$sql = "SELECT mid 
						FROM cooperatives_members2
						WHERE cid = '$cid' AND cyear = '$cyear'";
				$res = query($sql); 
				$num = num_rows($res); 
				
				if ($num > 0) {
					?>
					<div class="alert alert-danger alert-dismissible fade in" role="alert">
						<strong>A record with the same cooperative and year already exists! </strong><br /><br />
						<a href="tc.php?cid=<?php echo $cid ?>">Click here to view the record</a>
					</div>
					<?php
				} else {
					$sql = "INSERT INTO cooperatives_members2( cid, cyear, operator_rm, operator_rf, operator_am, operator_af, driver_rm, driver_rf, driver_am, driver_af, worker_rm, worker_rf, worker_am, worker_af, other_rm, other_rf, other_am, other_af, user_id, user_dtime )
							VALUES( '$cid', '$cyear', '$operator_rm', '$operator_rf', '$operator_am', '$operator_af', '$driver_rm', '$driver_rf', '$driver_am', '$driver_af', '$worker_rm', '$worker_rf', '$worker_am', '$worker_af', '$other_rm', '$other_rf', '$other_am', '$other_af', '$_SESSION[user_id]', NOW() )";
					if (query($sql)) {
						$mid = mysqli_insert_id($connection);
						// log the activity
						log_user(1, 'cooperatives_members2', $mid);
						
						update_members($cid);
						
						header("Location:tc.php?cid=".$cid."&tab=2&added_member=1");
						exit();
					} else {
						?>
						<div class="alert alert-danger alert-dismissible fade in" role="alert">
							<strong>Could not add member details because: </strong><br />
							<b><?php echo mysqli_error($connection) ?></b><br />
							The query was <?php echo $sql ?>.<br />
						</div>
						<?PHP
					}
				}
			} else {
				$serialized_message = serialize($message);
				// redirect the user to index page
				header('location: add_member2.php?serialized_message='.$serialized_message.'&cid='.$cid.'&cyear='.$cyear.'&operator_rm='.$operator_rm.'&operator_rf='.$operator_rf.'&operator_am='.$operator_am.'&operator_af='.$operator_af.'&driver_rm='.$driver_rm.'&driver_rf='.$driver_rf.'&driver_am='.$driver_am.'&driver_af='.$driver_af.'&worker_rm='.$worker_rm.'&worker_rf='.$worker_rf.'&worker_am='.$worker_am.'&worker_af='.$worker_af.'&other_rm='.$other_rm.'&other_rf='.$other_rf.'&other_am='.$other_am.'&other_af='.$other_af);
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
				  
				<title>OTC | Add TC Member</title>

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
							<h3>Add TC Member</h3>
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
						  
						  $cid = $_GET['cid'];

						  
						  ?>
						  <div class="col-md-12 col-sm-12 col-xs-12">
							<div class="x_panel">
							  <div class="x_content">
								<br />
								
									<form action="add_member2.php" method="POST" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="cyear">Year <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <select id="cyear" name="cyear" required="required" class="form-control col-md-7 col-xs-12">
										  <?php
										  $sql = "SELECT DATE_FORMAT(CURDATE(), '%Y')";
										  $dres = query($sql); $drow = fetch_array($dres); 
										  for ($year=$drow[0]; $year>2012; $year--) {
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
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="operator_rm">Operators - Regular - Male <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="operator_rm" name="operator_rm" required="required" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['operator_rm'])) {
											  ?>value="<?php echo $_GET['operator_rm'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="operator_rf">Operators - Regular - Female </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="operator_rf" name="operator_rf" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['operator_rf'])) {
											  ?>value="<?php echo $_GET['operator_rf'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="operator_am">Operators - Associate - Male </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="operator_am" name="operator_am" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['operator_am'])) {
											  ?>value="<?php echo $_GET['operator_am'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="operator_af">Operators - Associate - Female </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="operator_af" name="operator_af" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['operator_af'])) {
											  ?>value="<?php echo $_GET['operator_af'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label for="driver_rm" class="control-label col-md-3 col-sm-3 col-xs-12">Drivers - Regular - Male <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input id="driver_rm" name="driver_rm" required="required" class="form-control col-md-7 col-xs-12" type="text" 
										  <?php
										  if (isset ($_GET['driver_rm'])) {
											  ?>value="<?php echo $_GET['driver_rm'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label for="driver_rf" class="control-label col-md-3 col-sm-3 col-xs-12">Drivers - Regular - Female </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input id="driver_rf" name="driver_rf" class="form-control col-md-7 col-xs-12" type="text" 
										  <?php
										  if (isset ($_GET['driver_rf'])) {
											  ?>value="<?php echo $_GET['driver_rf'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label for="driver_am" class="control-label col-md-3 col-sm-3 col-xs-12">Drivers - Associate - Male </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input id="driver_am" name="driver_am" class="form-control col-md-7 col-xs-12" type="text" 
										  <?php
										  if (isset ($_GET['driver_am'])) {
											  ?>value="<?php echo $_GET['driver_am'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label for="driver_af" class="control-label col-md-3 col-sm-3 col-xs-12">Drivers - Associate - Female </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input id="driver_af" name="driver_af" class="form-control col-md-7 col-xs-12" type="text" 
										  <?php
										  if (isset ($_GET['driver_af'])) {
											  ?>value="<?php echo $_GET['driver_af'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label for="worker_rm" class="control-label col-md-3 col-sm-3 col-xs-12">Workers - Regular - Male </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input id="worker_rm" name="worker_rm" class="form-control col-md-7 col-xs-12" type="text" 
										  <?php
										  if (isset ($_GET['worker_rm'])) {
											  ?>value="<?php echo $_GET['worker_rm'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label for="worker_rf" class="control-label col-md-3 col-sm-3 col-xs-12">Workers - Regular - Female </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input id="worker_rf" name="worker_rf" class="form-control col-md-7 col-xs-12" type="text" 
										  <?php
										  if (isset ($_GET['worker_rf'])) {
											  ?>value="<?php echo $_GET['worker_rf'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label for="worker_am" class="control-label col-md-3 col-sm-3 col-xs-12">Workers - Associate - Male </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input id="worker_am" name="worker_am" class="form-control col-md-7 col-xs-12" type="text" 
										  <?php
										  if (isset ($_GET['worker_am'])) {
											  ?>value="<?php echo $_GET['worker_am'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label for="worker_af" class="control-label col-md-3 col-sm-3 col-xs-12">Workers - Associate - Female </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input id="worker_af" name="worker_af" class="form-control col-md-7 col-xs-12" type="text" 
										  <?php
										  if (isset ($_GET['worker_af'])) {
											  ?>value="<?php echo $_GET['worker_af'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label for="other_rm" class="control-label col-md-3 col-sm-3 col-xs-12">Others - Regular - Male </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input id="other_rm" name="other_rm" class="form-control col-md-7 col-xs-12" type="text" 
										  <?php
										  if (isset ($_GET['other_rm'])) {
											  ?>value="<?php echo $_GET['other_rm'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label for="other_rf" class="control-label col-md-3 col-sm-3 col-xs-12">Others - Regular - Female </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input id="other_rf" name="other_rf" class="form-control col-md-7 col-xs-12" type="text" 
										  <?php
										  if (isset ($_GET['other_rf'])) {
											  ?>value="<?php echo $_GET['other_rf'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label for="other_am" class="control-label col-md-3 col-sm-3 col-xs-12">Others - Associate - Male </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input id="other_am" name="other_am" class="form-control col-md-7 col-xs-12" type="text" 
										  <?php
										  if (isset ($_GET['other_am'])) {
											  ?>value="<?php echo $_GET['other_am'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label for="other_af" class="control-label col-md-3 col-sm-3 col-xs-12">Others - Associate - Female </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input id="other_af" name="other_af" class="form-control col-md-7 col-xs-12" type="text" 
										  <?php
										  if (isset ($_GET['other_af'])) {
											  ?>value="<?php echo $_GET['other_af'] ?>"<?php
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

