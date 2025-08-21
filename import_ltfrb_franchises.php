<?php

include("db.php");
include("sqli.php");
include("html.php");
define('PAGE', 'import_ltfrb_franchises.php'); 

session_start();

if (isset($_SESSION['user_id'])) {
	if (validate_level(PAGE) == true) {

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
						
						echo '<table border=1><tr>
								<td>Case #</td>
								<td>Unit Type</td>
								<td>Owner</td>
								<td>Owner Type</td>
								<td>Units</td>
								<td>DG</td>
								<td>DE</td>
								<td>Code</td>
								<td>Remarks</td>
								<td>Route</td>
								</tr>';
						$counter = 0; 
						
						while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {

							$case_num = $data[1];
							$unit_type = $data[2];
							$owner = $data[3];
							$owner_type = $data[4];
							$units_num = $data[5];
							$dg_date = $data[6];
							$de_date = $data[7];
							$route_code = $data[8];
							$remarks = $data[9];
							$route = $data[10];
							
							/*
							echo '<tr>
								<td>'.$case_num.'</td>
								<td>'.$unit_type.'</td>
								<td>'.$owner.'</td>
								<td>'.$owner_type.'</td>
								<td>'.$units_num.'</td>
								<td>'.$dg_date.'</td>
								<td>'.$de_date.'</td>
								<td>'.$route_code.'</td>
								<td>'.$remarks.'</td>
								<td>'.$route.'</td>
							</tr>';
							*/
							
							// insert into franchises 
							$sql = "INSERT INTO franchises_ltfrb(case_num, unit_type, owner, owner_type, units_num, dg_date, de_date, route_code, route, remarks, user_id, user_dtime)
									VALUES('$case_num', '$unit_type', '$owner', '$owner_type', '$units_num', '$dg_date', '$de_date', '$route_code', '$route', '$remarks', '64', NOW())";
							if (query($sql)) $counter++; 
							
						}
						
						fclose($handle);
					}
					echo 'Imported records: '.$counter;
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

				<title>OTC - Import LTFRB Franchises</title>

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
							<h3>Import LTFRB Franchises</h3>
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
								<form id="contact-form" name="contact-form" action="import_ltfrb_franchises.php" method="POST" enctype="multipart/form-data">					
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
