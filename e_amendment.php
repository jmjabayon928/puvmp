<?php

include("db.php");
include("sqli.php");
include("html.php");
define('PAGE', 'e_amendment.php'); 

session_start();

if (isset($_SESSION['user_id'])) {
	if (validate_level(PAGE) == true) {
		if(isset($_POST['submitted'])) { // if submit button has been pressed
			$aid = filter_var($_POST['aid'], FILTER_SANITIZE_NUMBER_INT);
			$adate = filter_var($_POST['adate'], FILTER_SANITIZE_STRING);
			$anum = filter_var($_POST['anum'], FILTER_SANITIZE_STRING);
			$adesc = filter_var($_POST['adesc'], FILTER_SANITIZE_STRING);
			
			// check if date is given
			if ($adate != '') { $a = TRUE; } 
			else { $a = FALSE; $message[] = "Please enter the date of amendment."; }
			// check if amendment number is given
			if ($anum != '') { $b = TRUE; } 
			else { $b = FALSE; $message[] = "Please enter the amendment number."; }
			// check if description of admendment is given
			if ($adesc != '') { $c = TRUE; } 
			else { $c = FALSE; $message[] = "Please enter the description of admendment."; }
			
			// If data pass all tests, proceed
			if ( $a && $b && $c ) {
				$sql = "UPDATE cooperatives_amendments
						SET adate = '$adate', 
							anum = '$anum', 
							adesc = '$adesc', 
							user_id = '$_SESSION[user_id]', 
							user_dtime = NOW()
						WHERE aid = '$aid'
						LIMIT 1";
				if (query($sql)) {
					// log the activity
					log_user(2, 'cooperatives_amendments', $aid);
					
					// get the cid of aid 
					$sql = "SELECT cid FROM cooperatives_amendments WHERE aid = '$aid'";
					$cres = query($sql); 
					$crow = fetch_array($cres); 
					$cid = $crow[0]; 
					
					header("Location:tc.php?cid=".$cid."&tab=1&updated_cgs=1");
					exit();
				} else {
					?>
					<div class="alert alert-danger alert-dismissible fade in" role="alert">
						<strong>Could not update cooperative amendment because: </strong><br />
						<b><?php echo mysqli_error($connection) ?></b><br />
						The query was <?php echo $sql ?>.<br />
					</div>
					<?PHP
				}
			} else {
				$serialized_message = serialize($message);
				// redirect the user to index page
				header('location: e_amendment.php?serialized_message='.$serialized_message.'&aid='.$aid);
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
				  
				<title>OTC | Update Cooperative Amendment</title>

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

					$aid = $_GET['aid'];
					
					$sql = "SELECT * FROM cooperatives_amendments WHERE aid = '$aid'";
					$res = query($sql); 
					$row = fetch_array($res); 

					?>

					<!-- page content -->
					<div class="right_col" role="main">
					  <div class="">
						<div class="page-title">
						  <div class="title_left">
							<h3>Update Cooperative Amendment</h3>
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
								
									<form action="e_amendment.php" method="POST" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="adate">Date of Amendment <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="date" id="adate" name="adate" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $row[2] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="anum">Amendment Number <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="anum" name="anum" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $row[3] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="adesc">Description of Amendment <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="adesc" name="adesc" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $row[4] ?>">
										</div>
									  </div>
									  <div class="ln_solid"></div>
									  <div class="form-group">
										<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
										  <input type="hidden" name="submitted" value="1">
										  <input type="hidden" name="aid" value="<?php echo $aid ?>">
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

