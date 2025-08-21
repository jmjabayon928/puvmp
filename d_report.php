<?php

include("db.php");
include("sqli.php");
include("html.php");
define('PAGE', 'd_report.php'); 

$rid = $_GET['rid'];

session_start();

if (isset($_SESSION['user_id'])) {
	if (validate_level(PAGE) == true) {

		if(isset($_POST['submitted'])) { // if submit button has been pressed
			$rid = filter_var($_POST['rid'], FILTER_SANITIZE_NUMBER_INT);
			
			$sql = "DELETE FROM reports
					WHERE rid = '$rid'
					LIMIT 1";
			if (query($sql)) {
				// log the activity
				log_user(3, 'reports', $rid);
				
				header("Location: reports.php");
				exit();
			} else {
				?>
				<div class="error">Could not delete report because: <b><?PHP echo mysqli_error($connection) ?></b>.
					<br />The query was <?PHP echo $sql ?>.</div><br />
				<?PHP
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
				  
				<title>OTC | Delete Report</title>

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
					
					$sql = "SELECT rt.rt_name, d.dname, CONCAT(e.lname,', ',e.fname),
								DATE_FORMAT(r.due_date, '%b %e, %Y'), 
								DATE_FORMAT(r.submit_date, '%b %e, %Y'),
								DATE_FORMAT(r.accepted_date, '%b %e, %Y'), 
								CONCAT(e2.lname,', ',e2.fname), 
								r.status, r.remarks, CONCAT(e3.lname,', ',e3.fname),
								DATE_FORMAT(r.user_dtime, '%b %e, %Y %h:%i %p')
							FROM reports r 
								LEFT JOIN reports_types rt ON r.rtid = rt.rtid 
								LEFT JOIN employees e ON r.eid = e.eid
								LEFT JOIN employees e2 ON r.accepted_by = e2.eid
								LEFT JOIN employees e3 ON r.user_id = e3.eid
								LEFT JOIN departments d ON r.did = d.did
							WHERE r.rid = '$rid'";
					$res = query($sql); 
					$row = fetch_array($res); 
					
					?>

					<!-- page content -->
					<div class="right_col" role="main">
					  <div class="">
						<div class="page-title">
						  <div class="title_left">
							<h3>Delete Communication</h3>
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
								
									<form action="d_report.php" method="POST" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12">Name of Report </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $row[0] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12">Responsible Unit </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $row[1] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12">Name of Person </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $row[2] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12">Due Date </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $row[3] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12">Date Submitted </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $row[4] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12">Date Accepted </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $row[5] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12">Accepted By </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $row[6] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12">Status </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" readonly class="form-control col-md-7 col-xs-12" value="<?php 
											if ($row[7] == 1) { echo 'Accepted';
											} elseif ($row[7] == 0) { echo 'On Process';
											} elseif ($row[7] == -1) { echo 'Returned';
											} elseif ($row[7] == 2) { echo 'To be submitted';
											}
											?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12">Remarks </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $row[8] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12">Last Updated By </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $row[9] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12">Date Last Updated </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $row[10] ?>">
										</div>
									  </div>
									  <div class="ln_solid"></div>
									  <div class="form-group">
										<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
										  <input type="hidden" name="submitted" value="1">
										  <input type="hidden" name="rid" value="<?php echo $rid ?>">
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

