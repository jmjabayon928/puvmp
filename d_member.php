<?php

include("db.php");
include("sqli.php");
include("html.php");
define('PAGE', 'd_member.php'); 

session_start();

if (isset($_SESSION['user_id'])) {
	if (validate_level(PAGE) == true) {

		if(isset($_POST['submitted'])) { // if submit button has been pressed
			$mid = filter_var($_POST['mid'], FILTER_SANITIZE_NUMBER_INT);
			
			// get the cid of mid 
			$sql = "SELECT cid FROM cooperatives_members WHERE mid = '$mid'";
			$cres = query($sql); 
			$crow = fetch_array($cres); 
			$cid = $crow[0]; 
			
			$sql = "DELETE FROM cooperatives_members
					WHERE mid = '$mid'
					LIMIT 1";
			if (query($sql)) {
				// log the activity
				log_user(3, 'cooperatives_members', $mid);
				
				update_members($cid);
				
				header("Location: tc.php?cid=".$cid."&tab=2");
				exit();
			} else {
				?>
				<div class="error">Could not delete member because: <b><?PHP echo mysqli_error($connection) ?></b>.
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
				  
				<title>OTC | Delete TC Member</title>

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
					
					$mid = $_GET['mid'];

					$sql = "SELECT c.cname, m.mtype, CONCAT(m.lname,', ',m.fname),
								m.addr, m.sex, DATE_FORMAT(m.bdate, '%b %e, %Y'),
								m.contact_num, m.email, m.children, m.hobbies,
								m.income, m.sources, m.remarks, CONCAT(u.lname,', ',u.fname),
								DATE_FORMAT(m.user_dtime, '%b %e, %Y %h:%i %p')
							FROM cooperatives_members m 
								LEFT JOIN cooperatives c ON m.cid = c.cid
								LEFT JOIN users u ON m.user_id = u.user_id
							WHERE m.mid = '$mid'";
					$res = query($sql); 
					$row = fetch_array($res); 
					?>

					<!-- page content -->
					<div class="right_col" role="main">
					  <div class="">
						<div class="page-title">
						  <div class="title_left">
							<h3>Delete Cooperative-Vehicle Units</h3>
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
								
									<form action="d_member.php" method="POST" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="cyear">Cooperative Name </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" class="form-control col-md-7 col-xs-12" readonly value="<?php echo $row[0] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="cyear">Membership Type </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" class="form-control col-md-7 col-xs-12" readonly value="<?php echo $row[1] == 1 ? 'Regular' : 'Associate' ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="operator_rm">Name </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $row[2] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="operator_rf">Address </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $row[3] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="operator_am">Sex </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $row[4] == 'M' ? 'Male' : 'Female' ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="operator_af">Birthdate</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $row[5] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label for="driver_rm" class="control-label col-md-3 col-sm-3 col-xs-12">Contact Number</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input readonly class="form-control col-md-7 col-xs-12" type="text" value="<?php echo $row[6] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label for="driver_rf" class="control-label col-md-3 col-sm-3 col-xs-12">Email </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input readonly class="form-control col-md-7 col-xs-12" type="text" value="<?php echo $row[7] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label for="driver_am" class="control-label col-md-3 col-sm-3 col-xs-12">No. of Children</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input readonly class="form-control col-md-7 col-xs-12" type="text" value="<?php echo $row[8] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label for="driver_af" class="control-label col-md-3 col-sm-3 col-xs-12">Hobbies and Interests</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input readonly class="form-control col-md-7 col-xs-12" type="text" value="<?php echo $row[9] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label for="worker_rm" class="control-label col-md-3 col-sm-3 col-xs-12">Approximate Income</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input readonly class="form-control col-md-7 col-xs-12" type="text" value="<?php echo $row[10] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label for="worker_rf" class="control-label col-md-3 col-sm-3 col-xs-12">Other Sources of Income</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input readonly class="form-control col-md-7 col-xs-12" type="text" value="<?php echo $row[11] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label for="worker_am" class="control-label col-md-3 col-sm-3 col-xs-12">Remarks</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input readonly class="form-control col-md-7 col-xs-12" type="text" value="<?php echo $row[12] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label for="worker_af" class="control-label col-md-3 col-sm-3 col-xs-12">Last Updated By</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input readonly class="form-control col-md-7 col-xs-12" type="text" value="<?php echo $row[13] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label for="other_rm" class="control-label col-md-3 col-sm-3 col-xs-12">Date Last Updated</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input readonly class="form-control col-md-7 col-xs-12" type="text" value="<?php echo $row[14] ?>">
										</div>
									  </div>
									  <div class="ln_solid"></div>
									  <div class="form-group">
										<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
										  <input type="hidden" name="submitted" value="1">
										  <input type="hidden" name="mid" value="<?php echo $mid ?>">
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

