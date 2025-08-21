<?php

include("db.php");
include("sqli.php");
include("html.php");
define('PAGE', 'approve_leave.php'); 

session_start();

if (isset($_SESSION['user_id'])) {
	if (validate_level(PAGE) == true) {

		if(isset($_POST['submitted'])) { // if submit button has been pressed
			$aid = filter_var($_POST['aid'], FILTER_SANITIZE_NUMBER_INT);
			$stats = filter_var($_POST['stats'], FILTER_SANITIZE_NUMBER_INT);
			$remarks = filter_var($_POST['remarks'], FILTER_SANITIZE_STRING);

			$sql = "UPDATE internals_leave_applications
					SET stats = '$stats',
						remarks = '$remarks',
						approve_id = '$_SESSION[user_id]',
						approve_dtime = NOW()
					WHERE aid = '$aid'
					LIMIT 1";
			if (query($sql)) {
				// log the activity
				log_user(2, 'internals_leave_applications', $aid);
				
				$sql = "SELECT eid, anum, lid, to_days(fr_date), to_days(to_date), approve_id, approve_dtime
						FROM internals_leave_applications 
						WHERE aid = '$aid'";
				$res = query($sql); 
				$row = fetch_array($res); 
				
				$eid = $row[0]; $anum = $row[1]; $lid = $row[2]; $start_date = $row[3]; $end_date = $row[4]; $approve_id = $row[5]; $approve_dtime = $row[6]; 
				
				for ($x=$start_date; $x<=$end_date; $x++) {
					$sql = "SELECT DATE_FORMAT(FROM_DAYS('$x'), '%w'), DATE_FORMAT(FROM_DAYS('$x'), '%Y-%m-%d')"; 
					$dres = query($sql); $drow = fetch_array($dres); 
					
					if ($drow[0] > 0 && $drow[0] < 6) {
						if ($lid == 1) { $status = 3;
						} elseif ($lid == 2) { $status = 4;
						} elseif ($lid == 3) { $status = 5;
						} elseif ($lid == 4) { $status = 6;
						} elseif ($lid == 5) { $status = 7;
						} elseif ($lid == 6) { $status = 8;
						} elseif ($lid == 7) { $status = 9;
						} elseif ($lid == 8) { $status = 10;
						} elseif ($lid == 9) { $status = 11;
						}
						// check if an attendance already exists
						$sql = "SELECT aid FROM attendance WHERE eid = '$eid' AND TO_DAYS(adate) = '$x'";
						$rres = query($sql); 
						$rnum = num_rows($rres); 
						
						if ($rnum > 0) {
							$sql = "UPDATE attendance
									SET status = '$status',
										remarks = 'Leave Application ".$anum."',
										last_update_id = '$approve_id',
										last_update = '$approve_dtime'
									WHERE aid = '$aid'
									LIMIT 1";
							query($sql); 
						} else {
							$sql = "INSERT INTO attendance(eid, adate, status, remarks, last_update_id, last_update)
									VALUES('$eid', '$drow[1]', '$status', 'Leave Application ".$anum."', '$approve_id', '$approve_dtime')";
							query($sql); 
						}
					}
				}
				
				// redirect
				header("Location: leaves.php?approve_leave=".$stats);
				exit();
			} else {
				?>
				<div class="alert alert-danger alert-dismissible fade in" role="alert">
					<strong>Could not approve leave application because: </strong><br />
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
				  
				<title>OTC | Approve Leave Application</title>

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
					
					$aid = $_GET['aid'];

					$sql = "SELECT l.anum, CONCAT(e.lname,', ',e.fname), 
								lt.lname, DATE_FORMAT(l.fr_date, '%m/%d/%y'), DATE_FORMAT(l.to_date, '%m/%d/%y'), l.lnum,
								l.stats, l.remarks, CONCAT(u.lname,', ',u.fname), 
								DATE_FORMAT(l.user_dtime, '%m/%d/%y %h:%i %p')
							FROM internals_leave_applications l
								INNER JOIN employees e ON l.eid = e.eid
								INNER JOIN leave_types lt ON l.lid = lt.lid
								INNER JOIN users u ON l.user_id = u.user_id
							WHERE l.aid = '$aid'";
					$res = query($sql); 
					$row = fetch_array($res); 
					
					?>

					<!-- page content -->
					<div class="right_col" role="main">
					  <div class="">
						<div class="page-title">
						  <div class="title_left">
							<h3>Approve Leave Application</h3>
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
								
									<form action="approve_leave.php" method="POST" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12">Control No. </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $row[0] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12">Employee </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $row[1] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12">Type of Leave </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $row[2] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12">Start Date of Leave </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $row[3] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12">End Date of Leave </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $row[4] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12">No. of Days </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $row[5] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12">Status </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" readonly class="form-control col-md-7 col-xs-12" value="<?php 
											if ($row[6] == -1) { echo 'Disapproved';
											} elseif ($row[6] == 0) { echo 'Pending';
											} elseif ($row[6] == 1) { echo 'Approved';
											}
											?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12">Encoder </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $row[8] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12">Date Encoded </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $row[9] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12">Decision <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <select id="stats" name="stats" required="required" class="form-control col-md-7 col-xs-12">
											<option value="-1">Disapprove</option><option value="1">Approve</option>
										  </select>
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="remarks">Remarks </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="remarks" name="remarks" class="form-control col-md-7 col-xs-12" value="<?php echo $row[7] ?>">
										</div>
									  </div>
									  <div class="ln_solid"></div>
									  <div class="form-group">
										<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
										  <input type="hidden" name="submitted" value="1">
										  <input type="hidden" name="aid" value="<?php echo $aid ?>">
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
				<script src="vendors/devbcfidge-autocomplete/dist/jquery.autocomplete.min.js"></script>
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

