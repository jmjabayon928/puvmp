<?php

include("db.php");
include("sqli.php");
include("html.php");

session_start();

if (isset($_SESSION['user_id'])) {
	if (validate_user() == true) {

		if(isset($_POST['submitted'])) { // if submit button has been pressed

			if ($_FILES["file"]["error"] > 0) {
				echo "Error: " . $_FILES["file"]["error"] . "<br>";
			} else {
				$extension = end(explode(".", $_FILES["file"]["name"]));
				if ($extension != 'csv') {
					?><div class="error"><b>File not allowed!</b></div><br /><?PHP
					show_form();
				} else {
					$file = $_FILES['file']['tmp_name'];
					if (($handle = fopen($file, "r")) !== FALSE) {
						
						while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {

							$num = $data[0];
							$cid = $data[1];
							$sector_name = $data[2];
							$cname = $data[3];
							$addr = $data[4];
							$chairman = $data[5];
							$contact_num = $data[6];
							$email = $data[7];
							$otc_num = $data[8];
							$otc_acc_date = $data[9];
							$cda_reg_num = $data[10];
							$cda_reg_date = $data[11];
							$cgs_num = $data[12];
							$cgs_date = $data[13];
							$cgs_exp = $data[14];
							$drivers_num = $data[15];
							$operators_num = $data[16];
							$workers_num = $data[17];
							$others_num = $data[18];
							$puj_num = $data[19];
							$mch_num = $data[20];
							$taxi_num = $data[21];
							$mb_num = $data[22];
							$bus_num = $data[23];
							$mcab_num = $data[24];
							$truck_num = $data[25];
							$auv_num = $data[26];
							$banka_num = $data[27];
							$current_assets = $data[28];
							$fixed_assets = $data[29];
							$liabilities = $data[30];
							$equity = $data[31];
							$net_income = $data[32];
							$init_stock = $data[33];
							$present_stock = $data[34];
							$subscribed = $data[35];
							$paid_up = $data[36];
							$scheme = $data[37];
							$reserved = $data[38];
							$educ_training = $data[39];
							$comm_dev = $data[40];
							$optional = $data[41];
							$dividends = $data[42];
							$ltfrb1 = $data[43];
							$ltfrb2 = $data[44];
							$ltfrb3 = $data[45];
							$ltfrb4 = $data[46];
							$ltfrb5 = $data[47];
							
							// get the sid of sector name
							$sql = "SELECT sid FROM tc_sectors WHERE sname = '$sector_name'";
							$sres = query($sql); $srow = fetch_array($sres); $sid = $srow[0]; 
							
							if ($cid == '' || $cid == 0) {
								$members_num = $drivers_num + $operators_num + $workers_num + $others_num; 
								
								$sql = "INSERT INTO cooperatives(sid, ctype, cname, addr, chairman, contact_num, email, new_otc_num, new_otc_date, new_cda_num, new_cda_date, drivers_num, operators_num, workers_num, others_num, members_num, puj_num, mch_num, taxi_num, mb_num, bus_num, mcab_num, truck_num, auv_num, banka_num, current_assets, fixed_assets, liabilities, equity, net_income, init_stock, present_stock, subscribed, paid_up, scheme, reserved, educ_training, comm_dev, optional, dividends, last_update_id, last_update)
										VALUES('$sid', '2', '$cname', '$addr', '$chairman', '$contact_num', '$email', '$otc_num', '$otc_acc_date', '$cda_reg_num', '$cda_reg_date', '$drivers_num', '$operators_num', '$workers_num', '$others_num', '$members_num', '$puj_num', '$mch_num', '$taxi_num', '$mb_num', '$bus_num', '$mcab_num', '$truck_num', '$auv_num', '$banka_num', '$current_assets', '$fixed_assets', '$liabilities', '$equity', '$net_income', '$init_stock', '$present_stock', '$subscribed', '$paid_up', '$scheme', '$reserved', '$educ_training', '$comm_dev', '$optional', '$dividends', '64', NOW())";
								query($sql); 
								$cid = mysqli_insert_id($connection);
							}
							
							if ($cgs_num != '' && $cgs_date != '' && $cgs_exp != '') {
								// get the year in cgs_num
								$cyear = substr($cgs_num, 0, 4); 
								
								// insert into cgs 
								$sql = "INSERT INTO cooperatives_cgs(cid, cgs_num, cgs_date, cgs_exp, last_update_id, last_update)
										VALUES('$cid', '$cgs_num', '$cgs_date', '$cgs_exp', '64', NOW())";
								query($sql); 
								
							} else { // use the accreditation date
								// get the year in cgs_num
								$cyear = substr($otc_num, 0, 4); 
								
							}
							
							// insert into capitalizations
							$sql = "INSERT INTO cooperatives_capitalizations(cid, cyear, init_stock, present_stock, subscribed, paid_up, scheme, user_id, user_dtime)
									VALUES('$cid', '$cyear', '$init_stock', '$present_stock', '$subscribed', '$paid_up', '$scheme', '64', NOW())";
							query($sql); 
							
							// insert into financials2
							$sql = "INSERT INTO cooperatives_financials(cid, cyear, current_assets, fixed_assets, liabilities, equity, net_income, user_id, user_dtime)
									VALUES('$cid', '$cyear', '$current_assets', '$fixed_assets', '$liabilities', '$equity', '$net_income', '64', NOW())";
							query($sql); 
							
							// insert into surplus
							$sql = "INSERT INTO cooperatives_surplus(cid, cyear, reserved, educ_training, comm_dev, optional, dividends, user_id, user_dtime)
									VALUES('$cid', '$cyear', '$reserved', '$educ_training', '$comm_dev', '$optional', '$dividends', '64', NOW())";
							query($sql); 
							
							// insert into units
							$sql = "INSERT INTO cooperatives_units(cid, cyear, puj_num, mch_num, taxi_num, mb_num, bus_num, mcab_num, truck_num, auv_num, banka_num, user_id, user_dtime)
									VALUES('$cid', '$cyear', '$reserved', '$educ_training', '$comm_dev', '$optional', '$dividends', '64', NOW())";
							query($sql); 
							
							
						}
						
						fclose($handle);
					}
					?></table><?PHP
				}
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

				<title>OTC - Import Statistical Data</title>

				<!-- Bootstrap -->
				<link href="vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
				<!-- Font Awesome -->
				<link href="vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
				<!-- iCheck -->
				<link href="vendors/iCheck/skins/flat/green.css" rel="stylesheet">
				<!-- Datatables -->
				<link href="vendors/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">

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
					
					<?php
					?>

					<!-- page content -->
					<div class="right_col" role="main">
					  <div class="">
						<div class="page-title">
						  <div class="title_left">
							<h3>Import Statistical Data</h3>
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
							  <div class="x_title">
								<h2>Import Statistical Data</h2>
								<div class="clearfix"></div>
							  </div>
							  <div class="x_content">
								<form id="contact-form" name="contact-form" action="import_stat_data.php" method="POST" enctype="multipart/form-data">					
									<fieldset>
										<label><span class="text-form">Filename:</span><input type="file" name="file" id="file"></label>
										<div class="buttons">
											<a class="button-2" onClick="document.getElementById('contact-form').submit()">Submit</a>
										</div>
									</fieldset>						
									<input type="hidden" name="token" value="<?PHP echo $token ?>">
									<input type="hidden" name="submitted" value="1">
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

				<!-- Custom Theme Scripts -->
				<script src="build/js/custom.min.js"></script>

			  </body>
			</html>	
			<?php
		}
	}
} else {
	header('Location: login.php');	
}
