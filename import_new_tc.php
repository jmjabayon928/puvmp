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
						
						echo '<table border=1>
								<tr>
									<td>Sector ID</td>
									<td>Cooperative Name</td>
									<td>Address</td>
									<td>Chairman</td>
									<td>TC Contact #s</td>
									<td>Email</td>
									<td>OTC #</td>
									<td>OTC Date</td>
									<td>CDA #</td>
									<td>CDA Date</td>
									<td>Driver Members</td>
									<td>Operator Members</td>
									<td>Worker Members</td>
									<td>Other Members</td>
									<td>Number of PUJs</td>
									<td>Number of MCHs</td>
									<td>Number of Taxis</td>
									<td>Number of Mini-Buses</td>
									<td>Number of Buses</td>
									<td>Number of M.Cab</td>
									<td>Number of Trucks</td>
									<td>Number of AUVs</td>
									<td>Number of M.Bankas</td>

									<td>Current Assets</td>
									<td>Fixed Assets</td>
									<td>Liabilities</td>
									<td>Equity</td>
									<td>Net Income</td>
									
									<td>Initial Stock</td>
									<td>Present Stock</td>
									<td>Subscribed Capital</td>
									<td>Paid-up Capital</td>
									<td>Build-up Scheme</td>
									
									<td>Reserve Fund</td>
									<td>Educ & Training</td>
									<td>Community Dev Fund</td>
									<td>Optional Fund</td>
									<td>Dividends & Patronage</td>
								</tr>';
						
						while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {

							$sid = $data[2];
							$cname = $data[3];
							$addr = $data[4];
							$chairman = $data[5];
							$contact_num = $data[6];
							$email = $data[7];
							$new_otc_num = $data[8];
							$new_otc_date = $data[9];
							$new_cda_num = $data[10];
							$new_cda_date = $data[11];
							
							$driver_rm = $data[15];
							$operator_rm = $data[16];
							$worker_rm = $data[17];
							$other_rm = $data[18];
							
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
							
							echo '<tr>
									<td>'.$sid.'</td>
									<td>'.$cname.'</td>
									<td>'.$addr.'</td>
									<td>'.$chairman.'</td>
									<td>'.$contact_num.'</td>
									<td>'.$email.'</td>
									<td>'.$new_otc_num.'</td>
									<td>'.$new_otc_date.'</td>
									<td>'.$new_cda_num.'</td>
									<td>'.$new_cda_date.'</td>
									
									<td>'.$driver_rm.'</td>
									<td>'.$operator_rm.'</td>
									<td>'.$worker_rm.'</td>
									<td>'.$other_rm.'</td>
									
									<td>'.$puj_num.'</td>
									<td>'.$mch_num.'</td>
									<td>'.$taxi_num.'</td>
									<td>'.$mb_num.'</td>
									<td>'.$bus_num.'</td>
									<td>'.$mcab_num.'</td>
									<td>'.$truck_num.'</td>
									<td>'.$auv_num.'</td>
									<td>'.$banka_num.'</td>
									
									<td>'.$current_assets.'</td>
									<td>'.$fixed_assets.'</td>
									<td>'.$liabilities.'</td>
									<td>'.$equity.'</td>
									<td>'.$net_income.'</td>
									
									<td>'.$init_stock.'</td>
									<td>'.$present_stock.'</td>
									<td>'.$subscribed.'</td>
									<td>'.$paid_up.'</td>
									<td>'.$scheme.'</td>
									
									<td>'.$reserved.'</td>
									<td>'.$educ_training.'</td>
									<td>'.$comm_dev.'</td>
									<td>'.$optional.'</td>
									<td>'.$dividends.'</td>
									
								</tr>';
								
								$sql = "INSERT INTO cooperatives(
											sid, ctype, cname, addr, chairman, contact_num, email, new_otc_num, new_otc_date, new_cda_num, new_cda_date,
											drivers_num, operators_num, workers_num, others_num, puj_num, mch_num, taxi_num, mb_num, bus_num, mcab_num, 
											truck_num, auv_num, banka_num, current_assets, fixed_assets, liabilities, equity, net_income, 
											init_stock, present_stock, subscribed, paid_up, scheme, reserved, educ_training, comm_dev, optional, dividends,  last_update_id, last_update) 
										VALUES(
											'$sid', '2', '$cname', '$addr', '$chairman', '$contact_num', '$email', '$new_otc_num', '$new_otc_date', '$new_cda_num', '$new_cda_date',
											'$driver_rm', '$operator_rm', '$worker_rm', '$other_rm', '$puj_num', '$mch_num', '$taxi_num', '$mb_num', '$bus_num', '$mcab_num', 
											'$truck_num', '$auv_num', '$banka_num', '$current_assets', '$fixed_assets', '$liabilities', '$equity', '$net_income', 
											'$init_stock', '$present_stock', '$subscribed', '$paid_up', '$scheme', '$reserved', '$educ_training', '$comm_dev', '$optional', '$dividends',  '64', NOW())";
								query($sql); 
								$cid = mysqli_insert_id($connection); 
								
								$sql = "INSERT INTO cooperatives_members2( cid, cyear, operator_rm, driver_rm, worker_rm, other_rm, user_id, user_dtime )
										VALUES( '$cid', '2018', '$operator_rm', '$driver_rm', '$worker_rm', '$other_rm', '64', NOW() )";
								query($sql); 
								
								$sql = "INSERT INTO cooperatives_units( 
											cid, cyear, puj_num, mch_num, taxi_num, mb_num, bus_num, 
											mcab_num, truck_num, auv_num, banka_num )
										VALUES( 
											'$cid', '2018', '$puj_num', '$mch_num', '$taxi_num', '$mb_num', '$bus_num', 
											'$mcab_num', '$truck_num', '$auv_num', '$banka_num' )";
								query($sql); 
								
								$sql = "INSERT INTO cooperatives_financials( cid, cyear, current_assets, fixed_assets, liabilities, equity, net_income, user_id, user_dtime )
										VALUES( '$cid', '2018', '$current_assets', '$fixed_assets', '$liabilities', '$equity', '$net_income', '64', NOW() )";
								query($sql); 
								
								$sql = "INSERT INTO cooperatives_capitalizations( cid, cyear, init_stock, present_stock, subscribed, paid_up, scheme, user_id, user_dtime )
										VALUES( '$cid', '2018', '$init_stock', '$present_stock', '$subscribed', '$paid_up', '$scheme', '64', NOW() )";
								query($sql); 
								
								$sql = "INSERT INTO cooperatives_surplus( cid, cyear, reserved, educ_training, comm_dev, optional, dividends, user_id, user_dtime )
										VALUES( '$cid', '2018', '$reserved', '$educ_training', '$comm_dev', '$optional', '$dividends', '64', NOW() )";
								query($sql); 
							
						}
						
						echo '</table>';
						
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

				<title>OTC - Import NEW Transport Cooperatives</title>

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
							<h3>Import NEW Transport Cooperatives</h3>
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
								<h2>Import NEW Transport Cooperatives</h2>
								<div class="clearfix"></div>
							  </div>
							  <div class="x_content">
								<form id="contact-form" name="contact-form" action="import_new_tc.php" method="POST" enctype="multipart/form-data">					
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
