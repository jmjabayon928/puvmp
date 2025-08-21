<?php

include("db.php");
include("sqli.php");
include("html.php");
define('PAGE', 'e_member.php'); 

session_start();

if (isset($_SESSION['user_id'])) {
	if (validate_level(PAGE) == true) {

		if(isset($_POST['submitted'])) { // if submit button has been pressed
			$mid = filter_var($_POST['mid'], FILTER_SANITIZE_NUMBER_INT);
			$mtype = filter_var($_POST['mtype'], FILTER_SANITIZE_NUMBER_INT);
			$lname = filter_var($_POST['lname'], FILTER_SANITIZE_STRING);
			$mname = filter_var($_POST['mname'], FILTER_SANITIZE_STRING);
			$fname = filter_var($_POST['fname'], FILTER_SANITIZE_STRING);
			$addr = filter_var($_POST['addr'], FILTER_SANITIZE_STRING);
			$sex = filter_var($_POST['sex'], FILTER_SANITIZE_STRING);
			$bdate = filter_var($_POST['bdate'], FILTER_SANITIZE_STRING);
			$contact_num = filter_var($_POST['contact_num'], FILTER_SANITIZE_STRING);
			$email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
			$children = filter_var($_POST['children'], FILTER_SANITIZE_STRING);
			$hobbies = filter_var($_POST['hobbies'], FILTER_SANITIZE_STRING);
			$income = filter_var($_POST['income'], FILTER_SANITIZE_STRING);
			$sources = filter_var($_POST['sources'], FILTER_SANITIZE_STRING);
			$remarks = filter_var($_POST['remarks'], FILTER_SANITIZE_STRING);

			// check if last name is given
			if ($lname != "") { $a = TRUE; } 
			else { $a = FALSE; $message[] = "Please enter the last name."; }
			// check if first name is given
			if ($fname != "") { $b = TRUE; } 
			else { $b = FALSE; $message[] = "Please enter the first name."; }
			// check if sex is given
			if ($sex != "") { $c = TRUE; } 
			else { $c = FALSE; $message[] = "Please enter the sex."; }

			// If data pass all tests, proceed
			if ( $a && $b && $c ) {
				// update data
				$sql = "UPDATE cooperatives_members
						SET mtype = '$mtype',
							lname = '$lname',
							mname = '$mname',
							fname = '$fname',
							addr = '$addr',
							sex = '$sex', 
							bdate = '$bdate',
							contact_num = '$contact_num',
							email = '$email',
							children = '$children',
							hobbies = '$hobbies',
							income = '$income',
							sources = '$sources',
							active = '$active',
							remarks = '$remarks',
							user_id = '$_SESSION[user_id]',
							user_dtime = NOW()
						WHERE mid = '$mid'
						LIMIT 1";
				if (query($sql)) {
					// log the activity
					log_user(2, 'cooperatives_members', $mid);
					
					// get the cid of mid 
					$sql = "SELECT cid FROM cooperatives_members WHERE mid = '$mid'";
					$cres = query($sql); 
					$crow = fetch_array($cres); 
					$cid = $crow[0]; 
					
					header("Location:tc.php?cid=".$cid."&tab=2&updated_member=1");
					exit();
				} else {
					?>
					<div class="alert alert-danger alert-dismissible fade in" role="alert">
						<strong>Could not update member details because: </strong><br />
						<b><?php echo mysqli_error($connection) ?></b><br />
						The query was <?php echo $sql ?>.<br />
					</div>
					<?PHP
				}
			} else {
				$serialized_message = serialize($message);
				// redirect the user to index page
				header('location: e_member.php?serialized_message='.$serialized_message.'&mid='.$mid);
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
					
					$mid = $_GET['mid'];

					$sql = "SELECT * FROM cooperatives_members WHERE mid = '$mid'";
					$res = query($sql); 
					$row = fetch_array($res); 
					?>

					<!-- page content -->
					<div class="right_col" role="main">
					  <div class="">
						<div class="page-title">
						  <div class="title_left">
							<h3>Update TC Member Details</h3>
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
								
									<form action="e_member.php" method="POST" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="mtype">Type of Member <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
											<?php
											if ($row[2] == 1) {
												?>
												Regular:
												<input type="radio" class="flat" name="mtype" id="mtype" value="1" checked="" /> Associate:
												<input type="radio" class="flat" name="mtype" id="mtype" value="0" />
												<?php
											} elseif ($row[2] == 0) {
												?>
												Regular:
												<input type="radio" class="flat" name="mtype" id="mtype" value="1"  /> Associate:
												<input type="radio" class="flat" name="mtype" id="mtype" value="0" checked="" />
												<?php
											}
											?>
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="lname">Last Name <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="lname" name="lname" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $row[3] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="mname">Middle Name </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="mname" name="mname" class="form-control col-md-7 col-xs-12" value="<?php echo $row[4] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="fname">First Name <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="fname" name="fname" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $row[5] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="addr">Address </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="addr" name="addr" class="form-control col-md-7 col-xs-12" value="<?php echo $row[6] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="sid">Sex <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <select id="sex" name="sex" required="required" class="form-control col-md-7 col-xs-12">
											<option value="">--- Select ---</option>
											<?php
											if ($row[7] == "M") {
												?>
												<option value="M" Selected>Male</option>
												<option value="F">Female</option>
												<?php
											} elseif ($row[7] == "F") {
												?>
												<option value="M">Male</option>
												<option value="F" Selected>Female</option>
												<?php
											} else {
												?>
												<option value="M">Male</option>
												<option value="F" Selected>Female</option>
												<?php
											}
											?>
										  </select>
										</div>
									  </div>
									  <div class="form-group">
										<label for="bdate" class="control-label col-md-3 col-sm-3 col-xs-12">Birth Date </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input id="bdate" name="bdate" class="form-control col-md-7 col-xs-12" type="date" value="<?php echo $row[8] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label for="contact_num" class="control-label col-md-3 col-sm-3 col-xs-12">Contact Number </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input id="contact_num" name="contact_num" class="form-control col-md-7 col-xs-12" type="text" value="<?php echo $row[9] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label for="email" class="control-label col-md-3 col-sm-3 col-xs-12">Email </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input id="email" name="email" class="form-control col-md-7 col-xs-12" type="text" value="<?php echo $row[10] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label for="children" class="control-label col-md-3 col-sm-3 col-xs-12">No. of Children </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input id="children" name="children" class="form-control col-md-7 col-xs-12" type="text" value="<?php echo $row[11] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label for="hobbies" class="control-label col-md-3 col-sm-3 col-xs-12">Hobbies / Interests </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input id="hobbies" name="hobbies" class="form-control col-md-7 col-xs-12" type="text" value="<?php echo $row[12] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label for="income" class="control-label col-md-3 col-sm-3 col-xs-12">Approximate Income </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input id="income" name="income" class="form-control col-md-7 col-xs-12" type="text" value="<?php echo $row[13] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label for="sources" class="control-label col-md-3 col-sm-3 col-xs-12">Other Sources of Income </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input id="sources" name="sources" class="form-control col-md-7 col-xs-12" type="text" value="<?php echo $row[14] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label for="remarks" class="control-label col-md-3 col-sm-3 col-xs-12">Remarks </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input id="remarks" name="remarks" class="form-control col-md-7 col-xs-12" type="text" value="<?php echo $row[15] ?>">
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

