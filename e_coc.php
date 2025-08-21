<?php

include("db.php");
include("sqli.php");
include("html.php");
define('PAGE', 'e_coc.php'); 

session_start();

if (isset($_SESSION['user_id'])) {
	if (validate_level(PAGE) == true) {

		if(isset($_POST['submitted'])) { // if submit button has been pressed
			$cid = filter_var($_POST['cid'], FILTER_SANITIZE_NUMBER_INT);
			$cnum = filter_var($_POST['cnum'], FILTER_SANITIZE_STRING);
			$cdate = filter_var($_POST['cdate'], FILTER_SANITIZE_STRING);
			$eid = filter_var($_POST['eid'], FILTER_SANITIZE_NUMBER_INT);
			$hrs_num = filter_var($_POST['hrs_num'], FILTER_SANITIZE_NUMBER_INT);
			$exp_date = filter_var($_POST['exp_date'], FILTER_SANITIZE_STRING);
			
			// check if cnum is given
			if ($cnum != '') { $a = TRUE; } 
			else { $a = FALSE; $message[] = "Please enter the control number."; }
			// check if cdate is given
			if ($cdate != '') { $b = TRUE; } 
			else { $b = FALSE; $message[] = "Please enter the date of completion."; }
			// check if eid is given
			if ($eid != 0) { $c = TRUE; } 
			else { $c = FALSE; $message[] = "Please select from the list of employees."; }
			// check if hrs_num is given
			if ($hrs_num != '') { $d = TRUE; } 
			else { $d = FALSE; $message[] = "Please enter the number of hours earned."; }
			// check if exp_date is given
			if ($exp_date != '') { $e = TRUE; } 
			else { $e = FALSE; $message[] = "Please enter the expiry date of coc."; }
			
			// If data pass all tests, proceed
			if ( $a && $b && $c && $d && $e ) {
				// update data
				$sql = "UPDATE cocs
						SET cnum = '$cnum',
							cdate = '$cdate',
							eid = '$eid',
							hrs_num = '$hrs_num',
							exp_date = '$exp_date'
						WHERE cid = '$cid'
						LIMIT 1";
				if (query($sql)) {
					// log the activity
					log_user(2, 'cocs', $cid);
					
					header("Location: cocs.php?&updated_coc=1");
					exit();
				} else {
					?>
						<div class="alert alert-danger alert-dismissible fade in" role="alert">
							<strong>Could not update Certificate of Certification because: </strong><br />
							<b><?php echo mysqli_error($connection) ?></b><br />
							The query was <?php echo $sql ?>.<br />
						</div>
					<?PHP
				}
			} else {
				$serialized_message = serialize($message);
				// redirect the user to index page
				header('location: e_coc.php?serialized_message='.$serialized_message.'&cid='.$cid);
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
				  
				<title>OTC | Update Certificate of Completion</title>

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
							<h3>Update Certificate of Completion</h3>
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
									<?php
									if (isset($_GET['cid'])) {
										$cid = $_GET['cid'];
										
										$sql = "SELECT * FROM cocs WHERE cid = '$cid'";
										$res = query($sql); 
										$row = fetch_array($res); 
										
									}
									?>
								
									<form action="e_coc.php" method="POST" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="cnum">Control Number <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="cnum" name="cnum" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $row[1] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="cdate">Date of CoC <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="date" id="cdate" name="cdate" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $row[2] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="eid">Employee <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <select id="eid" name="eid" required="required" class="form-control col-md-7 col-xs-12">
											<?php
											$sql = "SELECT eid, CONCAT(lname,', ',fname) 
													FROM employees 
													WHERE active = 1 
													ORDER BY lname, fname";
											$eres = query($sql); 
											while ($erow = fetch_array($eres)) {
												if ($row[3] == $erow[0]) {
													?><option value="<?php echo $erow[0] ?>" Selected><?php echo $erow[1] ?></option><?php
												} else {
													?><option value="<?php echo $erow[0] ?>"><?php echo $erow[1] ?></option><?php
												}
											}
											free_result($eres); 
											?>
										  </select>
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="hrs_num">No. of Hours Earned <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="hrs_num" name="hrs_num" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $row[4] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="exp_date">Date of Expiry <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="date" id="exp_date" name="exp_date" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $row[5] ?>">
										</div>
									  </div>
									  <div class="ln_solid"></div>
									  <div class="form-group">
										<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
										  <input type="hidden" name="submitted" value="1">
										  <input type="hidden" name="cid" value="<?php echo $cid ?>">
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

