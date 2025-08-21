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
					$i = 0; 
					$file = $_FILES['file']['tmp_name'];
					if (($handle = fopen($file, "r")) !== FALSE) {
						
						echo '<table border=1><tr><td>TC Name</td><td>CGS #</td><td>CGS Date</td><td>CGS Expiry</td></tr>';
						
						while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {

							$cname = $data[1];
							
							/*
							// check if tc is in the database already
							$sql = "SELECT cid FROM cooperatives WHERE cname = '$cname'";
							$cres = query($sql); 
							$cnum = num_rows($cres); 
							
							if ($cnum > 0) {
								$crow = fetch_array($cres); 
								$cid = $crow[0]; 
							} else {
								$sql = "INSERT INTO cooperatives(cname) VALUES('$cname')";
								query($sql); 
								$cid = mysqli_insert_id($connection); 
							}
							*/
							
							$cgs_num = $data[17];
							$cgs_date = $data[18];
							$cgs_exp = $data[19];
							
							if ($cgs_date != '') {
								$pcs1 = explode("/", $cgs_date);
								$dday = $pcs1[1]; $dmonth = $pcs1[0]; $dyear = $pcs1[2];
								$cgs_date = $dyear.'-'.$dmonth.'-'.$dday;
							}
							
							if ($cgs_exp != '') {
								$pcs2 = explode("/", $cgs_exp);
								$eday = $pcs2[1]; $emonth = $pcs2[0]; $eyear = $pcs2[2];
								$cgs_exp = $eyear.'-'.$emonth.'-'.$eday;
							}
							
							echo '<tr>
									<td>'.$cname.'</td>
									<td>'.$cgs_num.'</td>
									<td>'.$cgs_date.'</td>
									<td>'.$cgs_exp.'</td>
								</tr>';
							/*
							$sql = "INSERT INTO inventory( cid, idesc, property_num, property_ack_num, ics, sid, brand_name, model_name, serial, acquired_date, acquired_value )
									VALUES( '$cid', '$idesc', '$property_num', '$property_ack_num', '$ics', '$sid', '$brand_name', '$model_name', '$serial', '$acquired_date', '$value' )";
							query($sql); 
							*/
							
							$i++;
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

				<title>OTC - Import TC CGS</title>

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
							<h3>Import TC CGS</h3>
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
								<h2>Import TC CGS</h2>
								<div class="clearfix"></div>
							  </div>
							  <div class="x_content">
								<form id="contact-form" name="contact-form" action="import_cgs_.php" method="POST" enctype="multipart/form-data">					
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
				<!-- NProgress -->
				<script src="vendors/nprogress/nprogress.js"></script>
				<!-- iCheck -->
				<script src="vendors/iCheck/icheck.min.js"></script>
				<!-- Datatables -->
				<script src="vendors/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>

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
