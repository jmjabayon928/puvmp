<?php

include("db.php");
include("sqli.php");
include("html.php");
define('PAGE', 'e_efile.php'); 

$eid = $_GET['eid'];

session_start();

if (isset($_SESSION['user_id'])) {
	if (validate_level(PAGE) == true) {

		if(isset($_POST['submitted'])) { // if submit button has been pressed
			$eid = filter_var($_POST['eid'], FILTER_SANITIZE_NUMBER_INT);
			$edate = filter_var($_POST['edate'], FILTER_SANITIZE_STRING);
			$tid = filter_var($_POST['tid'], FILTER_SANITIZE_NUMBER_INT);
			$did = filter_var($_POST['did'], FILTER_SANITIZE_NUMBER_INT);
			$etitle = filter_var($_POST['etitle'], FILTER_SANITIZE_STRING);
			$edesc = filter_var($_POST['edesc'], FILTER_SANITIZE_STRING);
			
			// check if date is given
			if ($edate != "") { $a = TRUE; } 
			else { $a = FALSE; $message[] = "Please enter the date."; }
			// check if file type is given
			if ($tid != 0 && $tid != '') { $b = TRUE; } 
			else { $b = FALSE; $message[] = "Please select from the list of file types."; }
			// check if division is given
			if ($did != 0 && $did != '') { $c = TRUE; } 
			else { $c = FALSE; $message[] = "Please select from the list of divisions."; }
			// check if title is given
			if ($etitle != "") { $d = TRUE; } 
			else { $d = FALSE; $message[] = "Please enter the title of document."; }
			// check if description is given
			if ($edesc != "") { $e = TRUE; } 
			else { $e = FALSE; $message[] = "Please enter the description."; }
			
			// If data pass all tests, proceed
			if ( $a && $b && $c && $d && $e ) {
				$sql = "UPDATE efiles
						SET edate = '$edate',
							tid = '$tid',
							did = '$did',
							etitle = '$etitle',
							edesc = '$edesc'
						WHERE eid = '$eid'
						LIMIT 1";
				if (query($sql)) {
					// log the activity
					log_user(2, 'efiles', $eid);
					
					header("Location:efile.php?eid=".$eid."&success=1");
					exit();
				} else {
					?>
					<div class="alert alert-danger alert-dismissible fade in" role="alert">
						<strong>Could not update electronic file because: </strong><br />
						<b><?php echo mysqli_error($connection) ?></b><br />
						The query was <?php echo $sql ?>.<br />
					</div>
					<?PHP
				}
			} else {
				$serialized_message = serialize($message);
				// redirect the user to index page
				header('location: e_efile.php?serialized_message='.$serialized_message.'&eid='.$eid);
				exit();
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
				  
				<title>OTC | Update Inventory Item</title>

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
							<h3>Update Inventory Item</h3>
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
								
									<form action="e_efile.php" method="POST" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
									<?php
									$sql = "SELECT * FROM efiles WHERE eid = '$eid'";
									$ires = query($sql); 
									$irow = fetch_array($ires); 
									mysqli_free_result($ires);
									?>

									  <div class="form-group">
										<label for="edate" class="control-label col-md-3 col-sm-3 col-xs-12">E-File Date <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input id="edate" name="edate" required="required" class="form-control col-md-7 col-xs-12" type="date" value="<?php echo $irow[1] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="tid">File Type <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <select id="tid" name="tid" required="required" class="form-control col-md-7 col-xs-12">
											<option value="0">--- Select ---</option>
											<?php
											$sql = "SELECT tid, tname FROM file_types ORDER BY tname";
											$tres = query($sql); 
											while ($trow = fetch_array($tres)) {
												if ($irow[2] == $trow[0]) {
													?><option value="<?php echo $trow[0] ?>" Selected><?php echo $trow[1] ?></option><?php
												} else {
													?><option value="<?php echo $trow[0] ?>"><?php echo $trow[1] ?></option><?php
												}
											}
											mysqli_free_result($tres);
											?>
										  </select>
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="sid">Department <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <select id="did" name="did" required="required" class="form-control col-md-7 col-xs-12">
											<option value="0">--- Select ---</option>
											<?php
											$sql = "SELECT did, dname FROM departments ORDER BY dname";
											$dres = query($sql); 
											while ($drow = fetch_array($dres)) {
												if ($irow[3] == $drow[0]) {
													?><option value="<?php echo $drow[0] ?>" Selected><?php echo $drow[1] ?></option><?php
												} else {
													?><option value="<?php echo $drow[0] ?>"><?php echo $drow[1] ?></option><?php
												}
											}
											mysqli_free_result($dres);
											?>
										  </select>
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="etitle">Title <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="etitle" name="etitle" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $irow[4] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="edesc">Description <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="edesc" name="edesc" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $irow[5] ?>">
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

