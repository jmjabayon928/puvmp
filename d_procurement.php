<?php

include("db.php");
include("sqli.php");
include("html.php");
define('PAGE', 'd_procurement.php'); 

$pid = $_GET['pid'];

session_start();

if (isset($_SESSION['user_id'])) {
	if (validate_level(PAGE) == true) {

		if(isset($_POST['submitted'])) { // if submit button has been pressed
			$pid = filter_var($_POST['pid'], FILTER_SANITIZE_NUMBER_INT);
			
			$sql = "DELETE FROM procurements WHERE pid = '$pid' LIMIT 1";
			if (query($sql)) {
				// log the activity
				log_user(3, 'procurements', $pid);
				
				// fetch and delete the requirements 
				$sql = "SELECT prid FROM procurement_requirements WHERE pid = '$pid'";
				$pres = query($sql); 
				while ($prow = fetch_array($pres)) {
					// fetch and delete the attachments
					$sql = "SELECT rfid, efile FROM requirements_efiles WHERE prid = '$prow[0]'";
					$ares = query($sql); 
					while ($arow = fetch_array($ares)) {
						unlink($arow[1]); 
						query("DELETE FROM requirements_efiles WHERE rfid = '$arow[0]' LIMIT 1"); 
					}
					free_result($ares); 
					
					query("DELETE FROM procurement_requirements WHERE prid = '$prow[0]' LIMIT 1"); 
				}
				free_result($pres); 
				
				// redirect
				header("Location: procurements.php?tab=4");
				exit();
			} else {
				?>
				<div class="alert alert-danger alert-dismissible fade in" role="alert">
					<strong>Could not delete procurement because: </strong><br />
					<b><?php echo mysqli_error($connection) ?></b><br />
					The query was <?php echo $sql ?>.<br />
				</div>
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
				  
				<title>OTC | Delete Procurement</title>

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
					
					$sql = "SELECT p.ref_num, pt.pt_name, CONCAT(pm.pm_name,' - ',pm.pm_desc), 
								d.dname, CONCAT(e.lname,', ',e.fname), p.proc_name, p.budget, 
								DATE_FORMAT(p.publish_date, '%b %e, %Y'),
								DATE_FORMAT(p.closing_date, '%b %e, %Y'), p.remarks
							FROM procurements p 
								INNER JOIN procurement_types pt ON p.ptid = pt.ptid
								INNER JOIN procurement_modes pm ON p.pmid = pm.pmid
								INNER JOIN departments d ON p.did = d.did
								INNER JOIN employees e ON p.eid = e.eid
							WHERE p.pid = '$pid'";
					$res = query($sql); 
					$row = fetch_array($res); 
					
					?>

					<!-- page content -->
					<div class="right_col" role="main">
					  <div class="">
						<div class="page-title">
						  <div class="title_left">
							<h3>Delete Procurement</h3>
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
								
									<form action="d_procurement.php" method="POST" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12">Reference No.</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $row[0] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12">Type of Procurement</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $row[1] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12">Procurement Mode</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $row[2] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12">Requesting Unit</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $row[3] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12">Requesting Person</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $row[4] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12">Name of Procurement Project</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $row[5] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12">Approved Budget</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $row[6] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12">Publish Date</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $row[7] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12">Closing Date</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $row[8] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12">Remarks</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $row[9] ?>">
										</div>
									  </div>
									  <div class="ln_solid"></div>
									  <div class="form-group">
										<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
										  <input type="hidden" name="submitted" value="1">
										  <input type="hidden" name="pid" value="<?php echo $pid ?>">
										  <button type="submit" class="btn btn-success">Remove Requirement</button>
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

