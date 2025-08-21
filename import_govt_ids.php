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
						
						while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {

							$eid = $data[0];
							$gsis_id = $data[1];
							$hdmf_id = $data[2];
							$phic_id = $data[3];
							
							// check for existing records
							$sql = "SELECT pid FROM employees_pds WHERE eid = '$eid'";
							$cres = query($sql); $cnum = num_rows($cres); 
							
							if ($cnum > 0) {
								$sql = "UPDATE employees_pds
										SET gsis_id = '$gsis_id',
											pagibig = '$hdmf_id',
											philhealth = '$phic_id'
										WHERE eid = '$eid'
										LIMIT 1";
								query($sql); 
							} else {
								$sql = "INSERT INTO employees_pds(eid, gsis_id, pagibig, philhealth)
										VALUES('$eid', '$gsis_id', '$hdmf_id', '$phic_id')";
								query($sql); 
							}
							
							$i++;
						}
						
						fclose($handle);
					}
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

				<title>OTC - Government IDs</title>

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
							<h3>Import Government IDs</h3>
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
								<h2>Import Government IDs</h2>
								<div class="clearfix"></div>
							  </div>
							  <div class="x_content">
								<form id="contact-form" name="contact-form" action="import_govt_ids.php" method="POST" enctype="multipart/form-data">					
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
