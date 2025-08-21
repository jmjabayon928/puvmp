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
						
						/*
						echo '<table border=1>
								<tr>
									<td>Entry #</td>
									<td>Sector</td>
									<td>Cooperative Name</td>
									<td>Address</td>
									<td>Chairman</td>
									<td>Chairman\'s Number</td>
									<td>TC Contact #s</td>
									<td>Email</td>
									<td>OLD OTC #</td>
									<td>OLD OTC Date</td>
									<td>NEW OTC #</td>
									<td>NEW OTC Date</td>
									<td>OLD CDA #</td>
									<td>OLD CDA Date</td>
									<td>NEW CDA #</td>
									<td>NEW CDA Date</td>
									<td>Number of Members</td>
									<td>Number of PUJs</td>
									<td>Number of MCHs</td>
									<td>Number of Taxis</td>
									<td>Number of Buses</td>
									<td>Number of M.Cab</td>
									<td>Number of Trucks</td>
									<td>Number of AUVs</td>
									<td>Number of M.Bankas</td>
									<td>Total Assets</td>
									<td>Total Capital</td>
									<td>Route/s</td>
								</tr>';
						*/
						
						while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {

							$entry = $data[0];
							$sname = $data[1];
							$cname = $data[2];
							$addr = $data[3];
							$chairman = $data[4];
							$chairman_num = $data[5];
							$contact_num = $data[6];
							$email = $data[7];
							$old_otc_num = $data[8];
							$old_otc_date = $data[9];
							$new_otc_num = $data[10];
							$new_otc_date = $data[11];
							$old_cda_num = $data[12];
							$old_cda_date = $data[13];
							$new_cda_num = $data[14];
							$new_cda_date = $data[15];
							$members_num = $data[17];
							$puj_num = $data[19];
							$mch_num = $data[20];
							$taxi_num = $data[21];
							$mb_num = $data[22];
							$bus_num = $data[23];
							$mcab_num = $data[24];
							$truck_num = $data[25];
							$auv_num = $data[26];
							$banka_num = $data[27];
							$total_vehicles = $data[28];
							$assets = $data[29];
							$capital = $data[30];
							$route = $data[31];
							
							// check if tc is in the database already
							$sql = "SELECT cid FROM cooperatives WHERE cname = '$cname'";
							$cres = query($sql); 
							$cnum = num_rows($cres); 
							
							
							if ($cnum > 0) {
								$crow = fetch_array($cres); 
								$cid = $crow[0]; 
								
								// get the sector id 
								$sql = "SELECT sid FROM tc_sectors WHERE sname = '$sname'";
								$sres = query($sql); 
								$srow = fetch_array($sres); 
								$sid = $srow[0]; 
								
								// update the tc details
								$sql = "UPDATE cooperatives 
										SET sid = '$sid',
											addr = '$addr',
											chairman = '$chairman',
											chairman_num = '$chairman_num',
											contact_num = '$contact_num',
											email = '$email',
											old_otc_num = '$old_otc_num',
											old_otc_date = '$old_otc_date',
											new_otc_num = '$new_otc_num',
											new_otc_date = '$new_otc_date',
											old_cda_num = '$old_cda_num',
											old_cda_date = '$old_cda_date',
											new_cda_num = '$new_cda_num',
											new_cda_date = '$new_cda_date',
											members_num = '$members_num',
											puj_num = '$puj_num',
											mch_num = '$mch_num',
											taxi_num = '$taxi_num',
											mb_num = '$mb_num',
											bus_num = '$bus_num',
											mcab_num = '$mcab_num',
											truck_num = '$truck_num',
											auv_num = '$auv_num',
											banka_num = '$banka_num',
											assets = '$assets',
											capital = '$capital',
											route = '$route'
										WHERE cid = '$cid'
										LIMIT 1";
								query($sql); 
								
							}
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

				<title>OTC - Import Transport Cooperatives</title>

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
							<h3>Import Transport Cooperatives</h3>
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
								<h2>Import Transport Cooperatives</h2>
								<div class="clearfix"></div>
							  </div>
							  <div class="x_content">
								<form id="contact-form" name="contact-form" action="import_tc.php" method="POST" enctype="multipart/form-data">					
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
