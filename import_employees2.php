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
						
						//echo '<table><tr><td>Name</td><td>Position</td><td>Position #</td><td>Department</td><td>Department #</td><td>Sex</td><td>Start</td><td>End</td></tr>';
						
						while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {

							$name = $data[0];
							$position = $data[1];
							$department = $data[2];
							$sex = $data[3];
							$estart = $data[4];
							$eend = $data[5];
							
							$pcs1 = explode(",", $name);
							$lname = $pcs1[0]; $fname = $pcs1[1]; $mname = $pcs1[2];
							$lname = ucwords(strtolower($lname));
							$fname = ucwords(strtolower($fname));
							
							$pcs2 = explode("/", $estart);
							$sday = $pcs2[0]; $smonth = $pcs2[1]; $syear = $pcs2[2];
							$estart1 = $syear.'-'.$smonth.'-'.$sday;
							
							$pcs3 = explode("/", $eend);
							$eday = $pcs3[0]; $emonth = $pcs3[1]; $eyear = $pcs3[2];
							$eend1 = $eyear.'-'.$emonth.'-'.$eday;
							
							// get the pid of the position
							$sql = "SELECT pid FROM positions WHERE pname = '$position'";
							$res = query($sql); 
							$row = fetch_array($res); 
							$pid = $row[0]; 
							
							// get the did of the department
							$sql = "SELECT did FROM departments WHERE dname = '$department'";
							$res = query($sql); 
							$row = fetch_array($res); 
							$did = $row[0]; 
							
							/*
							echo '<tr>
									<td>'.$name.'</td>
									<td>'.$position.'</td>
									<td>'.$pid.'</td>
									<td>'.$department.'</td>
									<td>'.$did.'</td>
									<td>'.$sex.'</td>
									<td>'.$estart1.'</td>
									<td>'.$eend1.'</td>
								</tr>';
							*/
							
							$sql = "INSERT INTO employees( etype, fname, mname, lname, sex, did, pid, estart, eend, active )
									VALUES( '4', '$fname', '$mname', '$lname', '$sex', '$did', '$pid', '$estart1', '$eend1', '1' )";
							query($sql); 
							
							$i++;
						}
						
						//echo '</table>';
						
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

				<title>OTC - Import Employees</title>

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
							<h3>Import Employees</h3>
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
								<h2>Import Employees</h2>
								<div class="clearfix"></div>
							  </div>
							  <div class="x_content">
								<form id="contact-form" name="contact-form" action="import_employees2.php" method="POST" enctype="multipart/form-data">					
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
