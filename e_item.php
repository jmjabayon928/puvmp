<?php

include("db.php");
include("sqli.php");
include("html.php");
define('PAGE', 'e_item.php'); 

$iid = $_GET['iid'];

session_start();

if (isset($_SESSION['user_id'])) {
	if (validate_level(PAGE) == true) {

		if(isset($_POST['submitted'])) { // if submit button has been pressed
			$iid = filter_var($_POST['iid'], FILTER_SANITIZE_NUMBER_INT);
			$cid = filter_var($_POST['cid'], FILTER_SANITIZE_NUMBER_INT);
			$idesc = filter_var($_POST['idesc'], FILTER_SANITIZE_STRING);
			$property_num = filter_var($_POST['property_num'], FILTER_SANITIZE_STRING);
			$property_ack_num = filter_var($_POST['property_ack_num'], FILTER_SANITIZE_STRING);
			$ics = filter_var($_POST['ics'], FILTER_SANITIZE_STRING);
			$sid = filter_var($_POST['sid'], FILTER_SANITIZE_NUMBER_INT);
			$brand_name = filter_var($_POST['brand_name'], FILTER_SANITIZE_STRING);
			$model_name = filter_var($_POST['model_name'], FILTER_SANITIZE_STRING);
			$serial = filter_var($_POST['serial'], FILTER_SANITIZE_STRING);
			$acquired_date = filter_var($_POST['acquired_date'], FILTER_SANITIZE_STRING);
			$acquired_value = real_escape_string($_POST['acquired_value']);
			$depreciated_date = filter_var($_POST['depreciated_date'], FILTER_SANITIZE_STRING);
			$depreciated_value = real_escape_string($_POST['depreciated_value']);
			$eid = filter_var($_POST['eid'], FILTER_SANITIZE_NUMBER_INT);
			$remarks = filter_var($_POST['remarks'], FILTER_SANITIZE_STRING);
			
			// check if category is given
			if ($cid != 0 && $cid != '') { $a = TRUE; } 
			else { $a = FALSE; $message[] = "Please select from the list of item categories."; }
			// check if description is given
			if ($idesc != "") { $b = TRUE; } 
			else { $b = FALSE; $message[] = "Please enter the item description."; }
			// check if supplier is given
			if ($sid != 0 && $sid != '') { $c = TRUE; } 
			else { $c = FALSE; $message[] = "Please select from the list of suppliers."; }
			// check if brand is given
			if ($brand_name != "") { $d = TRUE; } 
			else { $d = FALSE; $message[] = "Please enter the brand name."; }
			// check if model is given
			if ($model_name != "") { $e = TRUE; } 
			else { $e = FALSE; $message[] = "Please enter the model name."; }
			// check if serial is given
			if ($serial != "") { $f = TRUE; } 
			else { $f = FALSE; $message[] = "Please enter the serial number."; }
			// check if acquisition date is given
			if ($acquired_date != "") { $g = TRUE; } 
			else { $g = FALSE; $message[] = "Please enter the acquisition date."; }
			// check if acquisition value is given
			if ($acquired_value != "") { $h = TRUE; } 
			else { $h = FALSE; $message[] = "Please enter the acquisition value."; }
			
			// If data pass all tests, proceed
			if ( $a && $b && $c && $d && $e && $f && $g && $h ) {
				// update data
				$sql = "UPDATE inventory
						SET cid = '$cid',
							idesc = '$idesc',
							property_num = '$property_num',
							property_ack_num = '$property_ack_num',
							ics = '$ics',
							sid = '$sid',
							brand_name = '$brand_name',
							model_name = '$model_name',
							serial = '$serial',
							acquired_date = '$acquired_date',
							acquired_value = '$acquired_value',
							depreciated_date = '$depreciated_date',
							depreciated_value = '$depreciated_value',
							eid = '$eid',
							remarks = '$remarks'
						WHERE iid = '$iid'
						LIMIT 1";
				if (query($sql)) {
					// log the activity
					log_user(2, 'inventory', $iid);
					
					header("Location:item.php?iid=".$iid."&success=1");
					exit();
				} else {
					?>
					<div class="alert alert-danger alert-dismissible fade in" role="alert">
						<strong>Could not update inventory item because: </strong><br />
						<b><?php echo mysqli_error($connection) ?></b><br />
						The query was <?php echo $sql ?>.<br />
					</div>
					<?PHP
				}
			} else {
				$serialized_message = serialize($message);
				// redirect the user to index page
				header('location: add_item.php?serialized_message='.$serialized_message.'&iid='.$iid);
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
				  
				<title>OTC | Update Inventory Item</title>

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
							<h3>Update Inventory Item</h3>
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
								
									<form action="e_item.php" method="POST" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
									<?php
									$sql = "SELECT * FROM inventory WHERE iid = '$iid'";
									$ires = query($sql); 
									$irow = fetch_array($ires); 
									mysqli_free_result($ires);
									?>

									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="cid">Inventory Type <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <select id="cid" name="cid" required="required" class="form-control col-md-7 col-xs-12">
											<?php
											$sql = "SELECT cid, cname FROM categories ORDER BY cname";
											$cres = query($sql); 
											while ($crow = fetch_array($cres)) {
												if ($irow[1] == $crow[0]) {
													?><option value="<?php echo $crow[0] ?>" Selected><?php echo $crow[1] ?></option><?php
												} else {
													?><option value="<?php echo $crow[0] ?>"><?php echo $crow[1] ?></option><?php
												}
											}
											mysqli_free_result($cres);
											?>
										  </select>
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="idesc">Description <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="idesc" name="idesc" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $irow[2] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="property_num">Property Number <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="property_num" name="property_num" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $irow[3] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label for="property_ack_num" class="control-label col-md-3 col-sm-3 col-xs-12">Property Acknowledgement Receipt Number </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="property_ack_num" name="property_ack_num" class="form-control col-md-7 col-xs-12" value="<?php echo $irow[4] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label for="ics" class="control-label col-md-3 col-sm-3 col-xs-12">Inventory Custodian Slip Number </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input id="ics" name="ics" class="form-control col-md-7 col-xs-12" type="text" value="<?php echo $irow[5] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="sid">Supplier <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <select id="sid" name="sid" required="required" class="form-control col-md-7 col-xs-12">
											<?php
											$sql = "SELECT sid, company FROM suppliers ORDER BY company";
											$sres = query($sql); 
											while ($srow = fetch_array($sres)) {
												if ($irow[6] == $srow[0]) {
													?><option value="<?php echo $srow[0] ?>" Selected><?php echo $srow[1] ?></option><?php
												} else {
													?><option value="<?php echo $srow[0] ?>"><?php echo $srow[1] ?></option><?php
												}
											}
											mysqli_free_result($sres);
											?>
										  </select>
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="brand_name">Brand Name <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="brand_name" name="brand_name" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $irow[7] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="model_name">Model Name <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="model_name" name="model_name" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $irow[8] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label for="serial" class="control-label col-md-3 col-sm-3 col-xs-12">Product Serial</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="serial" name="serial" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $irow[9] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label for="acquired_date" class="control-label col-md-3 col-sm-3 col-xs-12">Acquired Date <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input id="acquired_date" name="acquired_date" required="required" class="form-control col-md-7 col-xs-12" type="date" value="<?php echo $irow[10] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label for="acquired_value" class="control-label col-md-3 col-sm-3 col-xs-12">Acquired Value</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="acquired_value" name="acquired_value" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $irow[11] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label for="depreciated_date" class="control-label col-md-3 col-sm-3 col-xs-12">Depreciated Date <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input id="depreciated_date" name="depreciated_date" required="required" class="form-control col-md-7 col-xs-12" type="date" value="<?php echo $irow[12] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label for="depreciated_value" class="control-label col-md-3 col-sm-3 col-xs-12">Depreciated Value</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="depreciated_value" name="depreciated_value" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $irow[13] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label for="current_value" class="control-label col-md-3 col-sm-3 col-xs-12">Current Value <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input id="current_value" name="current_value" class="form-control col-md-7 col-xs-12" type="text" value="<?php echo $irow[14] ?>"  disabled>
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="eid">Assigned to <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <select id="eid" name="eid" required="required" class="form-control col-md-7 col-xs-12">\
											<option value="0">--- Select ---</option>
											<?php
											$sql = "SELECT eid, CONCAT(lname,', ',fname) FROM employees ORDER BY lname, fname";
											$eres = query($sql); 
											while ($erow = fetch_array($eres)) {
												if ($irow[15] == $erow[0]) {
													?><option value="<?php echo $erow[0] ?>" Selected><?php echo $erow[1] ?></option><?php
												} else {
													?><option value="<?php echo $erow[0] ?>"><?php echo $erow[1] ?></option><?php
												}
											}
											mysqli_free_result($eres);
											?>
										  </select>
										</div>
									  </div>
									  <div class="form-group">
										<label for="remarks" class="control-label col-md-3 col-sm-3 col-xs-12">Remarks <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input id="remarks" name="remarks" class="form-control col-md-7 col-xs-12" type="text" value="<?php echo $irow[16] ?>">
										</div>
									  </div>
									  <div class="ln_solid"></div>
									  <div class="form-group">
										<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
										  <input type="hidden" name="submitted" value="1">
										  <input type="hidden" name="iid" value="<?php echo $iid ?>">
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

