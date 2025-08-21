<?php

include("db.php");
include("sqli.php");
include("html.php");
define('PAGE', 'd_units.php'); 

session_start();

if (isset($_SESSION['user_id'])) {
	if (validate_level(PAGE) == true) {

		if(isset($_POST['submitted'])) { // if submit button has been pressed
			$cuid = filter_var($_POST['cuid'], FILTER_SANITIZE_NUMBER_INT);
			
			// get the cid of cuid 
			$sql = "SELECT cid FROM cooperatives_units WHERE cuid = '$cuid'";
			$cres = query($sql); 
			$crow = fetch_array($cres); 
			$cid = $crow[0]; 
			
			$sql = "DELETE FROM cooperatives_units
					WHERE cuid = '$cuid'
					LIMIT 1";
			if (query($sql)) {
				// log the activity
				log_user(3, 'cooperatives_units', $cuid);
				
				update_units($cid);
				
				header("Location:tc.php?cid=".$cid."&tab=4&deleted_units=1");
				exit();
			} else {
				?>
				<div class="error">Could not delete business because: <b><?PHP echo mysqli_error($connection) ?></b>.
					<br />The query was <?PHP echo $sql ?>.</div><br />
				<?PHP
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
				  
				<title>OTC | Delete Cooperative-Vehicle Units</title>

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
					
					$cuid = $_GET['cuid'];

					$sql = "SELECT c.cname
							FROM cooperatives_units u INNER JOIN cooperatives c ON u.cid = c.cid
							WHERE u.cuid = '$cuid'";
					$cres = query($sql); $crow = fetch_array($cres); 
					
					$sql = "SELECT * FROM cooperatives_units WHERE cuid = '$cuid'";
					$res = query($sql); 
					$row = fetch_array($res); 
					?>

					<!-- page content -->
					<div class="right_col" role="main">
					  <div class="">
						<div class="page-title">
						  <div class="title_left">
							<h3>Delete Cooperative-Vehicle Units</h3>
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
						  <div class="col-md-12 col-sm-12 col-xs-12">
							<div class="x_panel">
							  <div class="x_content">
								<br />
								
									<form action="d_units.php" method="POST" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="cyear">Cooperative Name </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" class="form-control col-md-7 col-xs-12" readonly value="<?php echo $crow[0] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="cyear">Year </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" class="form-control col-md-7 col-xs-12" readonly value="<?php echo $row[2] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="cquarter">Quarter </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="cquarter" name="cquarter" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $row[3] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="puj_num">PUVM Class 1</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="puj_num" name="puj_num" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $row[14] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="puj_num">PUVM Class 2</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="puj_num" name="puj_num" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $row[15] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="puj_num">PUVM Class 3</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="puj_num" name="puj_num" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $row[16] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="puj_num">PUVM Class 4</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="puj_num" name="puj_num" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $row[17] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="puj_num">PUJs </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="puj_num" name="puj_num" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $row[4] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="mch_num">MCHs </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="mch_num" name="mch_num" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $row[5] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="taxi_num">Taxis </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="taxi_num" name="taxi_num" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $row[6] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="mb_num">Mini Buses </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="mb_num" name="mb_num" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $row[7] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="bus_num">Buses </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="bus_num" name="bus_num" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $row[8] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="mcab_num">Mini Cabs </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="mcab_num" name="mcab_num" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $row[9] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="truck_num">Trucks </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="truck_num" name="truck_num" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $row[10] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="auv_num">Tourist Service </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="auv_num" name="auv_num" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $row[11] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="auv_num">UV Express </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="auv_num" name="auv_num" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $row[12] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="banka_num">Motorized Bancas </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="banka_num" name="banka_num" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $row[13] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="coop_owned">Cooperative-Owned Units </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="coop_owned" name="coop_owned" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $row[18] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="individual_owned">Individually-Owned Units </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="individual_owned" name="individual_owned" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $row[19] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="coop_franchised">Cooperative-Franchised Units </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="coop_franchised" name="coop_franchised" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $row[20] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="individual_franchised">Individually-Franchised Units </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="individual_franchised" name="individual_franchised" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $row[21] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="wout_franchise">Units Without Franchise </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="wout_franchise" name="wout_franchise" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $row[22] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="remarks">Remarks </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="remarks" name="remarks" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $row[23] ?>">
										</div>
									  </div>
									  <div class="ln_solid"></div>
									  <div class="form-group">
										<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
										  <input type="hidden" name="submitted" value="1">
										  <input type="hidden" name="cuid" value="<?php echo $cuid ?>">
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

