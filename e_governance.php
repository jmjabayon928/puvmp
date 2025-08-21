<?php

include("db.php");
include("sqli.php");
include("html.php");
define('PAGE', 'e_governance.php'); 

session_start();

if (isset($_SESSION['user_id'])) {
	if (validate_level(PAGE) == true) {

		if(isset($_POST['submitted'])) { // if submit button has been pressed
			$gid = filter_var($_POST['gid'], FILTER_SANITIZE_NUMBER_INT);
			$gdate = filter_var($_POST['gdate'], FILTER_SANITIZE_STRING);
			$gplace = filter_var($_POST['gplace'], FILTER_SANITIZE_STRING);
			$migs = filter_var($_POST['migs'], FILTER_SANITIZE_STRING);
			$quorum = filter_var($_POST['quorum'], FILTER_SANITIZE_STRING);
			$voters = filter_var($_POST['voters'], FILTER_SANITIZE_STRING);
			
			// check if date is given
			if ($gdate != '') { $a = TRUE; } 
			else { $a = FALSE; $message[] = "Please enter the date of meeting."; }
			// check if gplace is given
			if ($gplace != '') { $b = TRUE; } 
			else { $b = FALSE; $message[] = "Please enter the place of meeting."; }
			// check if migs is given
			if ($migs != '') { $c = TRUE; } 
			else { $c = FALSE; $message[] = "Please enter the number of members in good standing."; }
			// check if quorum is given
			if ($quorum != '') { $d = TRUE; } 
			else { $d = FALSE; $message[] = "Please enter the quorum in the conduct of G.A. per by-laws."; }
			// check if voters is given
			if ($voters != '') { $e = TRUE; } 
			else { $e = FALSE; $message[] = "Please enter the number of of members entitled to vote present."; }
			
			// If data pass all tests, proceed
			if ( $a && $b && $c && $d && $e ) {
				// update table
				$sql = "UPDATE cooperatives_governances
						SET gdate = '$gdate',
							gplace = '$gplace',
							migs = '$migs',
							quorum = '$quorum',
							voters = '$voters',
							user_id = '$_SESSION[user_id]',
							user_dtime = NOW()
						WHERE gid = '$gid'
						LIMIT 1";
				if (query($sql)) {
					// log the activity
					log_user(2, 'cooperatives_governances', $gid);
					
					// get the cid of gid 
					$sql = "SELECT cid FROM cooperatives_governances WHERE gid = '$gid'";
					$cres = query($sql); 
					$crow = fetch_array($cres); 
					$cid = $crow[0]; 
					
					header("Location:tc.php?cid=".$cid."&tab=3&updated_governance=1");
					exit();
				} else {
					?>
					<div class="alert alert-danger alert-dismissible fade in" role="alert">
						<strong>Could not update membership classifications because: </strong><br />
						<b><?php echo mysqli_error($connection) ?></b><br />
						The query was <?php echo $sql ?>.<br />
					</div>
					<?PHP
				}
			} else {
				$serialized_message = serialize($message);
				// redirect the user to PHP_SELF
				header('location: e_governance.php?serialized_message='.$serialized_message.'&gid='.$gid);
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
				  
				<title>OTC | Update TC Member Details</title>

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
					
					$gid = $_GET['gid'];

					$sql = "SELECT * FROM cooperatives_governances WHERE gid = '$gid'";
					$res = query($sql); 
					$row = fetch_array($res); 
					?>

					<!-- page content -->
					<div class="right_col" role="main">
					  <div class="">
						<div class="page-title">
						  <div class="title_left">
							<h3>Update General Assembly Meeting Details</h3>
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
								
									<form action="e_governance.php" method="POST" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="gdate"> Date <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="date" id="gdate" name="gdate" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $row[2] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="gplace">Place <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="gplace" name="gplace" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $row[3] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="migs">No. of MIGS <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="migs" name="migs" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $row[4] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="quorum">Quorum in G.A. Per By-Laws <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="quorum" name="quorum" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $row[5] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="voters">Number of Entitle Voters Present <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="voters" name="voters" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $row[6] ?>">
										</div>
									  </div>
									  <div class="ln_solid"></div>
									  <div class="form-group">
										<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
										  <input type="hidden" name="submitted" value="1">
										  <input type="hidden" name="gid" value="<?php echo $gid ?>">
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

