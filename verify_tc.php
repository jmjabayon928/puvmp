<?php

include("db.php");
include("sqli.php");
include("html.php");
define('PAGE', 'verify_tc.php'); 

session_start();

if (isset($_SESSION['user_id'])) {
	if (validate_level(PAGE) == true) {

		if(isset($_POST['submitted'])) { // if submit button has been pressed
			$cid = filter_var($_POST['cid'], FILTER_SANITIZE_NUMBER_INT);
			
			$sql = "UPDATE cooperatives 
					SET last_verify_id = '$_SESSION[user_id]', last_verify = NOW()
					WHERE cid = '$cid'
					LIMIT 1";
			if (query($sql)) {
				// log the activity
				log_user(2, 'cooperatives', $cid);
				
				header("Location:tc.php?cid=".$cid);
				exit();
			} else {
				?>
				<div class="error">Could not verify transport cooperative data because: <b><?PHP echo mysqli_error($connection) ?></b>.
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
				  
				<title>OTC | Verify Transport Cooperative Data</title>

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
					
					$cid = $_GET['cid'];

					$sql = "SELECT c.cname, c.addr, s.sname, c.email, 
							    c.chairman, c.chairman_num, c.contact_num, c.route,
								c.last_update_id, DATE_FORMAT(c.last_update, '%m/%d/%y %h:%i %p'),
								c.last_verify_id, DATE_FORMAT(c.last_verify, '%m/%d/%y %h:%i %p'), 
								c.new_otc_num, DATE_FORMAT(c.new_otc_date, '%m/%d/%y'),
								c.new_cda_num, DATE_FORMAT(c.new_cda_date, '%m/%d/%y')
							FROM cooperatives c INNER JOIN tc_sectors s ON c.sid = s.sid
							WHERE c.cid = '$cid'";
					$res = query($sql); 
					$row = fetch_array($res); 
					?>

					<!-- page content -->
					<div class="right_col" role="main">
					  <div class="">
						<div class="page-title">
						  <div class="title_left">
							<h3>Verify CGS</h3>
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
								
									<form action="verify_tc.php" method="POST" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
									<table class="table table-striped table-bordered jambo_table">
										<thead>
										  <tr class="headings">
											  <th colspan="4">Transportation Cooperative Details</th>
										  </tr>
										</thead>
										<tbody>
											<tr>
												<td width="15%">Name</td>
												<td width="30%"><b><?php echo $row[0] ?></b></td>
												<td width="15%">Address</td>
												<td width="40%"><b><?php echo $row[1] ?></b></td>
											</tr>
											<tr>
												<td>Area</td>
												<td><b><?php echo $row[2] ?></b></td>
												<td>Email</td>
												<td><b><?php echo $row[3] ?></b></td>
											</tr>
											<tr>
												<td>Chairman</td>
												<td><b><?php echo $row[4] ?></b></td>
												<td>Contact #s</td>
												<td><b><?php echo $row[5].', '.$row[6] ?></b></td>
											</tr>
											<tr>
												<td>OTC Accreditation No.</td>
												<td><b><?php echo $row[12] ?></b></td>
												<td>CDA Registration No.</td>
												<td><b><?php echo $row[14] ?></b></td>
											</tr>
											<tr>
												<td>OTC Accreditation Date</td>
												<td><b><?php echo $row[13] ?></b></td>
												<td>CDA Registration Date</td>
												<td><b><?php echo $row[15] ?></b></td>
											</tr>
											<tr>
												<td>Certs of Good Standing</td>
												<td colspan="3">
												<?php
												$sql = "SELECT ccid, cgs_num
														FROM cooperatives_cgs 
														WHERE cid = '$cid'
														ORDER BY cgs_num DESC";
												$cg_res = query($sql); 
												while ($cg_row = fetch_array($cg_res)) {
													?><a href="cgs.php?ccid=<?php echo $cg_row[0] ?>"><?php echo $cg_row[1] ?></a>, <?php
												}
												?>
												</td>
											</tr>
											<tr>
												<td>Route(s)</td>
												<td colspan="3"><b><?php echo $row[7] ?></b></td>
											</tr>
											<tr>
												<td>Last Updated By</td>
												<td><b><?php 
													$sql = "SELECT CONCAT(lname,', ',fname)
															FROM users 
															WHERE user_id = '$row[8]'";
													$eres = query($sql); 
													$erow = fetch_array($eres); 
													echo $erow[0] ?></b></td>
												<td>Last Verified By</td>
												<td><b><?php 
													$sql = "SELECT CONCAT(lname,', ',fname)
															FROM users 
															WHERE user_id = '$row[10]'";
													$vres = query($sql); 
													$vrow = fetch_array($vres); 
													echo $vrow[0] ?></b></td>
											</tr>
											<tr>
												<td>Date Updated</td>
												<td><b><?php echo $row[9] == '00/00/00 12:00 AM' ? '' : $row[9] ?></b></td>
												<td>Date Verified</td>
												<td><b><?php echo $row[11] == '00/00/00 12:00 AM' ? '' : $row[10] ?></b></td>
											</tr>
											<tr>
												<td colspan="4" align="center">
												  <input type="hidden" name="submitted" value="1">
												  <input type="hidden" name="cid" value="<?php echo $cid ?>">
												  <button type="submit" class="btn btn-success">Verify</button>
												</td>
											</tr>
										</tbody>
									</table>

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

