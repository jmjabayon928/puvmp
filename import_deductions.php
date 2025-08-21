<?php

include("db.php");
include("sqli.php");
include("html.php");
define('PAGE', 'import_deductions.php'); 

session_start();

if (isset($_SESSION['user_id'])) {
	if (validate_level(PAGE) == true) {

		if(isset($_POST['submitted'])) { // if submit button has been pressed

			if ($_FILES["file"]["error"] > 0) {
				echo "Error: " . $_FILES["file"]["error"] . "<br>";
			} else {
					echo '<table border=1>';
					$file = $_FILES['file']['tmp_name'];
					if (($handle = fopen($file, "r")) !== FALSE) {
						while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
							$pid = $data[1];
							$eid = $data[2];
							$basic = $data[4];
							$a413 = $data[5];
							$b413 = $data[6];
							$d413 = $data[7];
							$p413 = $data[8];
							$q413 = $data[9];
							$e413 = $data[10];
							$r413 = $data[11];
							$g413 = $data[12];
							$o413 = $data[13];
							$j413 = $data[14];
							$k413 = $data[15];
							$l413 = $data[16];
							$m413 = $data[17];
							$a414 = $data[18];
							$b414 = $data[19];
							$c414 = $data[20];
							$d415 = $data[21];
							$d417 = $data[22];
							$a439 = $data[23];
							$b439 = $data[24];
							$e412 = $data[25];
							$total = $data[26];

							echo '<tr>
									<td>'.$pid.'</td>
									<td>'.$eid.'</td>
									<td>'.$basic.'</td>
								</tr>';
							
							$sql = "UPDATE employees SET monthly = '$basic' WHERE eid = '$eid' LIMIT 1";
							//query($sql); 
							
							$sql = "INSERT INTO payrolls_deductions(pid, eid, gsis_413a, gsis_413b, gsis_413d, gsis_413p, gsis_413q, gsis_413r, gsis_413e, gsis_413g, gsis_413o, gsis_413j, gsis_413k, gsis_413l, gsis_413m, hdmf_414a, hdmf_414b, hdmf_414c, phic_ee, nhmfc_loan, emdeco_439a, quedancor_439b, bir_412, total)
									VALUES('$pid', '$eid', '$a413', '$b413', '$d413', '$p413', '$q413', '$r413', '$e413', '$g413', '$o413', '$j413', '$k413', '$l413', '$m413', '$a414', '$b414', '$c414', '$d415', '$d417', '$a439', '$b439', '$e412', '$total')";
							query($sql); 
							
						}
						
						fclose($handle);
					}
					echo '</table>';
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

				<title>OTC - Import Deductions</title>

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
							<h3>Import Deductions</h3>
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
								<h2>Import Deductions</h2>
								<div class="clearfix"></div>
							  </div>
							  <div class="x_content">
								<form id="contact-form" name="contact-form" action="import_deductions.php" method="POST" enctype="multipart/form-data">					
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
