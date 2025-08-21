<?php

include("db.php");
include("sqli.php");
include("html.php");
define('PAGE', 'add_requirement_efile.php'); 

session_start();

if (isset($_SESSION['user_id'])) {
	if (validate_level(PAGE) == true) {

		if(isset($_POST['submitted'])) { // if submit button has been pressed
			$prid = filter_var($_POST['prid'], FILTER_SANITIZE_NUMBER_INT);
			
					
			// Count total files
			$countfiles = count($_FILES['files']['name']);

			// Looping all files
			for($i=0; $i<$countfiles; $i++){
				$filename = $_FILES['files']['name'][$i];
				
				// set the allowed file types
				$allowed_file_types = array('image/gif', 'image/png', 'image/jpeg', 'image/jpg', 'application/pdf');
				// set the max file size - 2MB
				$allowed_file_size = 2000000;
				
				if (in_array($_FILES['files']['type'][$i], $allowed_file_types)) {
					if ($_FILES['files']['size'][$i] <= $allowed_file_size) {
						
						$ext = end(explode(".", $filename)); 
						
						// get the next number
						$sql = "SELECT MAX(rfid) FROM requirements_efiles"; 
						$res = query($sql); $row = fetch_array($res);
						$rfid = $row[0] + 1; $rfid = md5($rfid).".";
						
						// upload
						$uploadfile = 'requirements/' . $rfid . $ext;
						if (move_uploaded_file($_FILES['files']['tmp_name'][$i], $uploadfile)) {
							$sql = "INSERT INTO requirements_efiles( prid, efile ) VALUES( '$prid', '$uploadfile' )";
							query($sql); 
							$rfid = mysqli_insert_id($connection); 
							
							// log the activity
							log_user(1, 'requirements_efiles', $rfid);
							
						}
					} else {
						echo 'File too large!<br />';
					}
				} else {
					echo 'Invalid file type!<br />';
				}
			}
			
			header("Location:requirement.php?prid=".$prid);
			exit();
		} else {
			$prid = $_GET['prid'];
			?>
			<!DOCTYPE html>
			<html lang="en">
			  <head>
				<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
				<!-- Meta, title, CSS, favicons, etc. -->
				<meta charset="utf-8">
				<meta http-equiv="X-UA-Compatible" content="IE=edge">
				<meta name="viewport" content="width=device-width, initial-scale=1">
				  
				<title>OTC | Attach Files</title>

				<!-- Bootstrap -->
				<link href="vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
				<!-- Font Awesome -->
				<link href="vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">

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
							<h3>Add New E-File</h3>
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
						  ?>
						  <div class="col-md-12 col-sm-12 col-xs-12">
							<div class="x_panel">
							  <div class="x_content">
								<br />
								
									<form action="add_requirement_efile.php" method="POST" enctype="multipart/form-data" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
									  <div class="form-group">
										<label for="file1" class="control-label col-md-3 col-sm-3 col-xs-12">Attachments <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="file" name="files[]" id="files" multiple onChange="makeFileList();" class="form-control col-md-7 col-xs-12">
										</div>
									  </div>
									  <div class="form-group">
										<label for="file2" class="control-label col-md-3 col-sm-3 col-xs-12">Files Selected </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
											<ul id="fileList">
											  <li>No Files Selected</li>
											</ul>
											<script>
											function makeFileList() {
											  var input = document.getElementById("files");
											  var ul = document.getElementById("fileList");
											  while (ul.hasChildNodes()) {
												ul.removeChild(ul.firstChild);
											  }
											  for (var i = 0; i < input.files.length; i++) {
												var li = document.createElement("li");
												li.innerHTML = input.files[i].name;
												ul.appendChild(li);
											  }
											  if(!ul.hasChildNodes()) {
												var li = document.createElement("li");
												li.innerHTML = 'No Files Selected';
												ul.appendChild(li);
											  }
											}	
											</script>
										</div>
									  </div>
									  <div class="ln_solid"></div>
									  <div class="form-group">
										<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
										  <input type="hidden" name="submitted" value="1">
										  <input type="hidden" name="prid" value="<?php echo $prid ?>">
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

