<?php

include("db.php");
include("sqli.php");
include("html.php");
define('PAGE', 'add_epicture.php'); 

session_start();

if (isset($_SESSION['user_id'])) {
	if (validate_level(PAGE) == true) {
		if(isset($_POST['submitted'])) { // if submit button has been pressed
			$eid = filter_var($_POST['eid'], FILTER_SANITIZE_NUMBER_INT);
			
					
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
						
						if (strlen($eid) == 1) { $pic_name = '00'.$eid;
						} elseif (strlen($eid) == 2) { $pic_name = '0'.$eid;
						} elseif (strlen($eid) == 3) { $pic_name = $eid;
						}
						// upload
						$uploadpic = 'employees/' . $pic_name . '.' . $ext;
						//echo $uploadpic; 
						if (move_uploaded_file($_FILES['files']['tmp_name'][$i], $uploadpic)) {
							$sql = "UPDATE employees 
									SET picture = '$uploadpic'
									WHERE eid = '$eid'";
							query($sql); 

							// log the activity
							log_user(2, 'employees', $eid);
							
						}
					} else {
						echo 'File too large!<br />';
					}
				} else {
					echo 'Invalid file type!<br />';
				}
		 
		 
			}
			
			header("Location:employee.php?eid=".$eid."&success=1");
			exit();
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
				  
				<title>OTC | Add Employee-Picture</title>

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
						  <div class="col-md-12 col-sm-12 col-xs-12">
							<div class="x_panel">
							  <div class="x_content">
								<br />
									<?php
									  if (isset($_GET['eid'])) {
										  $eid = $_GET['eid'];
									  }
									?>
								
									<form action="add_epicture.php" method="POST" enctype="multipart/form-data" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
									  <div class="form-group">
										<label for="file1" class="control-label col-md-3 col-sm-3 col-xs-12">Picture <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="file" name="files[]" id="files" multiple onChange="makeFileList();" class="form-control col-md-7 col-xs-12">
										</div>
									  </div>
									  <div class="form-group">
										<label for="file2" class="control-label col-md-3 col-sm-3 col-xs-12">Files Selected </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
											<ul id="fileList">
											  <li>Picture size in pixels should be 200px by 200px</li>
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
												li.innerHTML = 'No Picture Selected';
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
										  <input type="hidden" name="eid" value="<?php echo $eid ?>">
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

