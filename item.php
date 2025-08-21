<?php

include("db.php");
include("sqli.php");
include("html.php");
define('PAGE', 'item.php'); 


if (isset($_POST['property_num'])) {
	$property_num = $_POST['property_num'];
	
	// get the iid from property_num
	$sql = "SELECT iid FROM inventory WHERE property_num = '$property_num'";
	$res = query($sql); 
	$row = fetch_array($res); 
	$iid = $row[0]; 
} else {
	$iid = $_GET['iid'];
}

session_start();

if (isset($_SESSION['user_id'])) {
	if (validate_level(PAGE) == true) {

		// log the activity
		log_user(5, 'inventory', $iid);
		
		?>
		<!DOCTYPE html>
		<html lang="en">
		  <head>
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
			<!-- Meta, title, CSS, favicons, etc. -->
			<meta charset="utf-8">
			<meta http-equiv="X-UA-Compatible" content="IE=edge">
			<meta name="viewport" content="width=device-width, initial-scale=1">
			  
			<title>OTC | Inventory Item Details</title>

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
						<h3>Inventory Item Details</h3>
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
							
								<form action="e_item.php" method="POST" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
								<?php
								$sql = "SELECT c.cname, i.idesc, i.property_num, i.property_ack_num, i.ics, s.company, 
											i.brand_name, i.model_name, i.serial, DATE_FORMAT(i.acquired_date, '%b %e, %Y'), 
											i.acquired_value, DATE_FORMAT(i.depreciated_date, '%b %e, %Y'), 
											i.depreciated_value, i.current_value, i.eid, i.remarks
										FROM inventory i 
											INNER JOIN categories c ON i.cid = c.cid
											INNER JOIN suppliers s ON i.sid = s.sid
										WHERE i.iid = '$iid'";
								$ires = query($sql); 
								$irow = fetch_array($ires); 
								mysqli_free_result($ires);
								?>

								  <div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="cid">Inventory Type <span class="required">*</span></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
									  <input type="text" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $irow[0] ?>">
									</div>
								  </div>
								  <div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="idesc">Description <span class="required">*</span></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
									  <input type="text" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $irow[1] ?>">
									</div>
								  </div>
								  <div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="property_num">Property Number <span class="required">*</span></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
									  <input type="text" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $irow[2] ?>">
									</div>
								  </div>
								  <div class="form-group">
									<label for="property_ack_num" class="control-label col-md-3 col-sm-3 col-xs-12">Property Acknowledgement Number <span class="required">*</span></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
									  <input type="text" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $irow[3] ?>">
									</div>
								  </div>
								  <div class="form-group">
									<label for="ics" class="control-label col-md-3 col-sm-3 col-xs-12">Inventory Custodian Slip Number <span class="required">*</span></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
									  <input type="text" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $irow[4] ?>">
									</div>
								  </div>
								  <div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="sid">Supplier <span class="required">*</span></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
									  <input type="text" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $irow[5] ?>">
									</div>
								  </div>
								  <div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="brand_name">Brand Name <span class="required">*</span></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
									  <input type="text" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $irow[6] ?>">
									</div>
								  </div>
								  <div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="model_name">Model Name <span class="required">*</span></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
									  <input type="text" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $irow[7] ?>">
									</div>
								  </div>
								  <div class="form-group">
									<label for="serial" class="control-label col-md-3 col-sm-3 col-xs-12">Product Serial</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
									  <input type="text" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $irow[8] ?>">
									</div>
								  </div>
								  <div class="form-group">
									<label for="acquired_date" class="control-label col-md-3 col-sm-3 col-xs-12">Acquired Date <span class="required">*</span></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
									  <input type="text" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $irow[9] ?>">
									</div>
								  </div>
								  <div class="form-group">
									<label for="acquired_value" class="control-label col-md-3 col-sm-3 col-xs-12">Acquired Value</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
									  <input type="text" readonly class="form-control col-md-7 col-xs-12" value="<?php echo number_format($irow[10], 2) ?>">
									</div>
								  </div>
								  <div class="form-group">
									<label for="depreciated_date" class="control-label col-md-3 col-sm-3 col-xs-12">Depreciated Date <span class="required">*</span></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
									  <input type="text" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $irow[11] ?>">
									</div>
								  </div>
								  <div class="form-group">
									<label for="depreciated_value" class="control-label col-md-3 col-sm-3 col-xs-12">Depreciated Value</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
									  <input type="text" readonly class="form-control col-md-7 col-xs-12" value="<?php echo number_format($irow[12], 2) ?>">
									</div>
								  </div>
								  <div class="form-group">
									<label for="current_value" class="control-label col-md-3 col-sm-3 col-xs-12">Current Value <span class="required">*</span></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
									  <input type="text" readonly class="form-control col-md-7 col-xs-12" value="<?php echo number_format($irow[13], 2) ?>">
									</div>
								  </div>
								  <div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="eid">Assigned to <span class="required">*</span></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
									  <input type="text" readonly class="form-control col-md-7 col-xs-12" value="<?php 
										  if ($irow[14] != 0) {
											  $sql = "SELECT CONCAT(lname,', ',fname) 
													FROM employees
													WHERE eid = '$irow[14]'";
											  $eres = query($sql); $erow = fetch_array($eres); 
											  echo $erow[0];
										  } else echo 'Unassigend';
										?>">
									</div>
								  </div>
								  <div class="form-group">
									<label for="remarks" class="control-label col-md-3 col-sm-3 col-xs-12">Remarks <span class="required">*</span></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
									  <input type="text" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $irow[15] ?>">
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
			<!-- Custom Theme Scripts -->
			<script src="build/js/custom.min.js"></script>
			
		  </body>
		</html>
		<?php
	}
} else {
	header('Location: login.php');	
}


