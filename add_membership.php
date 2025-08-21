<?php

include("db.php");
include("sqli.php");
include("html.php");
define('PAGE', 'add_membership.php'); 

session_start();

if (isset($_SESSION['user_id'])) {
	if (validate_level(PAGE) == true) {

		if(isset($_POST['submitted'])) { // if submit button has been pressed
			$cid = filter_var($_POST['cid'], FILTER_SANITIZE_NUMBER_INT);
			$mid = filter_var($_POST['mid'], FILTER_SANITIZE_NUMBER_INT);
			$pos_id = filter_var($_POST['pos_id'], FILTER_SANITIZE_NUMBER_INT);
			$class_id = filter_var($_POST['class_id'], FILTER_SANITIZE_NUMBER_INT);
			$comm_id = filter_var($_POST['comm_id'], FILTER_SANITIZE_NUMBER_INT);
			$ostart = filter_var($_POST['ostart'], FILTER_SANITIZE_STRING);
			$oend = filter_var($_POST['oend'], FILTER_SANITIZE_STRING);
			$remarks = filter_var($_POST['remarks'], FILTER_SANITIZE_STRING);

			// check if mid is given
			if ($mid != 0 && $mid != '') { $a = TRUE; } 
			else { $a = FALSE; $message[] = "Please select from the list of members."; }
			// check if pos_id is given
			if ($pos_id != 0 && $pos_id != '') { $b = TRUE; } 
			else { $b = FALSE; $message[] = "Please select from the list of positions."; }
			// check if class_id is given
			if ($class_id != 0 && $class_id != "") { $c = TRUE; } 
			else { $c = FALSE; $message[] = "Please select from the list of membership type."; }

			// If data pass all tests, proceed
			if ( $a && $b && $c ) {
				
				// check for double entry first
				$sql = "SELECT cmid 
						FROM cooperatives_memberships 
						WHERE cid = '$cid' AND comm_id = '$comm_id' AND mid = '$mid' 
							AND pos_id = '$pos_id' AND class_id = '$class_id' 
							AND ostart = '$ostart' AND oend = '$oend'";
				$res = query($sql); 
				$num = num_rows($res); 
				
				if ($num > 0) {
					?>
					<div class="alert alert-danger alert-dismissible fade in" role="alert">
						<strong>A record with the same member, position and membership type already exists! </strong><br /><br />
						<a href="tc.php?cid=<?php echo $cid ?>&tab=3">Click here to view the record</a>
					</div>
					<?php
				} else {
					$sql = "INSERT INTO cooperatives_memberships( cid, pos_id, class_id, comm_id, mid, ostart, oend, remarks, user_id, user_dtime )
							VALUES( '$cid', '$pos_id', '$class_id', '$comm_id', '$mid', '$ostart', '$oend', '$remarks', '$_SESSION[user_id]', NOW() )";
					if (query($sql)) {
						$cmid = mysqli_insert_id($connection);
						// log the activity
						log_user(1, 'cooperatives_memberships', $cmid);
						
						header("Location:tc.php?cid=".$cid."&tab=3&added_membership=1");
						exit();
					} else {
						?>
						<div class="alert alert-danger alert-dismissible fade in" role="alert">
							<strong>Could not add membership details because: </strong><br />
							<b><?php echo mysqli_error($connection) ?></b><br />
							The query was <?php echo $sql ?>.<br />
						</div>
						<?PHP
					}
				}
			} else {
				$serialized_message = serialize($message);
				// redirect the user to index page
				header('location: add_membership.php?serialized_message='.$serialized_message.'&cid='.$cid.'&pos_id='.$pos_id.'&class_id='.$class_id.'&mid='.$mid.'&comm_id='.$comm_id.'&ostart='.$ostart.'&oend='.$oend.'&remarks='.$remarks);
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
				  
				<title>OTC | Add TC Membership</title>

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
					?>

					<!-- page content -->
					<div class="right_col" role="main">
					  <div class="">
						<div class="page-title">
						  <div class="title_left">
							<h3>Add TC Membership</h3>
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
						  
						  $cid = $_GET['cid'];
						  $sql = "SELECT cname FROM cooperatives WHERE cid = '$cid'";
						  $cres = query($sql); $crow = fetch_array($cres); 

						  
						  ?>
						  <div class="col-md-12 col-sm-12 col-xs-12">
							<div class="x_panel">
							  <div class="x_content">
								<br />
								
									<form action="add_membership.php" method="POST" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12">Cooperative Name </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $crow[0] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="mid">Member <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <select id="mid" name="mid" required="required" class="form-control col-md-7 col-xs-12">
										  <option value="0">-- Select --</option>
										  <?php
										  $sql = "SELECT mid, CONCAT(lname,', ',fname) 
												  FROM cooperatives_members
												  WHERE cid = '$cid'
												  ORDER BY lname, fname";
										  $mres = query($sql); 
										  while ($mrow = fetch_array($mres)) {
											  if (isset ($_GET['mid'])) {
												  if ($_GET['mid'] == $mrow[0]) {
													  ?><option value="<?php echo $mrow[0] ?>" Selected><?php echo $mrow[1] ?></option><?php
												  } else {
													  ?><option value="<?php echo $mrow[0] ?>"><?php echo $mrow[1] ?></option><?php
												  }
											  } else {
												  ?><option value="<?php echo $mrow[0] ?>"><?php echo $mrow[1] ?></option><?php
											  }
										  }
										  free_result($mres); 
										  ?>
										  </select>
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="pos_id">Position <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <select id="pos_id" name="pos_id" required="required" class="form-control col-md-7 col-xs-12">
										  <option value="0">-- Select --</option>
										  <?php
										  $sql = "SELECT pid, pname FROM tc_positions";
										  $pres = query($sql); 
										  while ($prow = fetch_array($pres)) {
											  if (isset ($_GET['pos_id'])) {
												  if ($_GET['pos_id'] == $prow[0]) {
													  ?><option value="<?php echo $prow[0] ?>" Selected><?php echo $prow[1] ?></option><?php
												  } else {
													  ?><option value="<?php echo $prow[0] ?>"><?php echo $prow[1] ?></option><?php
												  }
											  } else {
												  ?><option value="<?php echo $prow[0] ?>"><?php echo $prow[1] ?></option><?php
											  }
										  }
										  free_result($pres); 
										  ?>
										  </select>
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="comm_id">Committee </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <select id="comm_id" name="comm_id" class="form-control col-md-7 col-xs-12">
										  <option value="0">-- Select --</option>
										  <?php
										  $sql = "SELECT comm_id, comm_name FROM tc_committees";
										  $cm_res = query($sql); 
										  while ($cm_row = fetch_array($cm_res)) {
											  if (isset ($_GET['comm_id'])) {
												  if ($_GET['comm_id'] == $cm_row[0]) {
													  ?><option value="<?php echo $cm_row[0] ?>" Selected><?php echo $cm_row[1].' Committee' ?></option><?php
												  } else {
													  ?><option value="<?php echo $cm_row[0] ?>"><?php echo $cm_row[1].' Committee' ?></option><?php
												  }
											  } else {
												  ?><option value="<?php echo $cm_row[0] ?>"><?php echo $cm_row[1].' Committee' ?></option><?php
											  }
										  }
										  free_result($cm_res); 
										  ?>
										  </select>
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="class_id">Membership Type <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <select id="class_id" name="class_id" required="required" class="form-control col-md-7 col-xs-12">
										  <option value="0">-- Select --</option>
										  <?php
										  $sql = "SELECT tcid, class_name FROM tc_classes";
										  $cres = query($sql); 
										  while ($crow = fetch_array($cres)) {
											  if (isset ($_GET['class_id'])) {
												  if ($_GET['class_id'] == $crow[0]) {
													  ?><option value="<?php echo $crow[0] ?>" Selected><?php echo $crow[1] ?></option><?php
												  } else {
													  ?><option value="<?php echo $crow[0] ?>"><?php echo $crow[1] ?></option><?php
												  }
											  } else {
												  ?><option value="<?php echo $crow[0] ?>"><?php echo $crow[1] ?></option><?php
											  }
										  }
										  free_result($cres); 
										  ?>
										  </select>
										</div>
									  </div>
									  <div class="form-group">
										<label for="ostart" class="control-label col-md-3 col-sm-3 col-xs-12">Start Date </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input id="ostart" name="ostart" class="form-control col-md-7 col-xs-12" type="date" 
										  <?php
										  if (isset ($_GET['ostart'])) {
											  ?>value="<?php echo $_GET['ostart'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label for="oend" class="control-label col-md-3 col-sm-3 col-xs-12">End Date </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input id="oend" name="oend" class="form-control col-md-7 col-xs-12" type="date" 
										  <?php
										  if (isset ($_GET['oend'])) {
											  ?>value="<?php echo $_GET['oend'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label for="remarks" class="control-label col-md-3 col-sm-3 col-xs-12">Remarks </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input id="remarks" name="remarks" class="form-control col-md-7 col-xs-12" type="text" 
										  <?php
										  if (isset ($_GET['remarks'])) {
											  ?>value="<?php echo $_GET['remarks'] ?>"<?php
										  }
										  ?>
										  >
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

