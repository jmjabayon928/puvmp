<?php

include("db.php");
include("sqli.php");
include("html.php");
define('PAGE', 'd_membership.php'); 

session_start();

if (isset($_SESSION['user_id'])) {
	if (validate_level(PAGE) == true) {

		if(isset($_POST['submitted'])) { // if submit button has been pressed
			$cmid = filter_var($_POST['cmid'], FILTER_SANITIZE_NUMBER_INT);

			// get the cid of cmid 
			$sql = "SELECT cid FROM cooperatives_memberships WHERE cmid = '$cmid'";
			$cres = query($sql); 
			$crow = fetch_array($cres); 
			$cid = $crow[0]; 
			
			$sql = "DELETE FROM cooperatives_memberships
					WHERE cmid = '$cmid'
					LIMIT 1";
			if (query($sql)) {
				// log the activity
				log_user(3, 'cooperatives_memberships', $cmid);
				
				// redirect
				header("Location:tc.php?cid=".$cid."&tab=3&deleted_membership=1");
				exit();
			} else {
				?>
				<div class="alert alert-danger alert-dismissible fade in" role="alert">
					<strong>Could not delete membership because: </strong><br />
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
				  
				<title>OTC | Delete Membership</title>

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
					
					$cmid = $_GET['cmid'];

					$sql = "SELECT c.cname, CONCAT(m1.lname,', ',m1.fname), 
								tp.pname, comm.comm_name, tc.class_name, 
								DATE_FORMAT(m2.ostart, '%b %e, %Y'), 
								DATE_FORMAT(m2.oend, '%b %e, %Y'), m2.remarks
							FROM cooperatives_memberships m2 
								INNER JOIN cooperatives_members m1 ON m2.mid = m1.mid
								INNER JOIN cooperatives c ON m2.cid = c.cid
								INNER JOIN tc_positions tp ON m2.pos_id = tp.pid
								INNER JOIN tc_classes tc ON m2.class_id = tc.tcid
								LEFT JOIN tc_committees comm ON m2.comm_id = comm.comm_id
								INNER JOIN users u ON m2.user_id = u.user_id
							WHERE m2.cmid = '$cmid'";
					$res = query($sql); 
					$row = fetch_array($res); 
					
					?>

					<!-- page content -->
					<div class="right_col" role="main">
					  <div class="">
						<div class="page-title">
						  <div class="title_left">
							<h3>Delete Membership</h3>
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
								
									<form action="d_membership.php" method="POST" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12"> Coop Name </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $row[0] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="gdate"> Member </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $row[1] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="gplace">Position </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $row[2] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="gplace">Committee </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $row[3].' Committee' ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="migs">Membership Type </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $row[4] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="quorum">Start Date </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $row[5] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="voters">End Date </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $row[6] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="voters">Remarks </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $row[7] ?>">
										</div>
									  </div>
									  <div class="ln_solid"></div>
									  <div class="form-group">
										<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
										  <input type="hidden" name="submitted" value="1">
										  <input type="hidden" name="cmid" value="<?php echo $cmid ?>">
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
				<script src="vendors/devbcmidge-autocomplete/dist/jquery.autocomplete.min.js"></script>
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

