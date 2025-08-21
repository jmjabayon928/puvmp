<?php

include("db.php");
include("sqli.php");
include("html.php");
define('PAGE', 'add_financials.php'); 

session_start();

if (isset($_SESSION['user_id'])) {
	if (validate_level(PAGE) == true) {

		if(isset($_POST['submitted'])) { // if submit button has been pressed
			$cid = filter_var($_POST['cid'], FILTER_SANITIZE_NUMBER_INT);
			$cyear = filter_var($_POST['cyear'], FILTER_SANITIZE_NUMBER_INT);
			$current_assets = real_escape_string($_POST['current_assets']);
			$fixed_assets = real_escape_string($_POST['fixed_assets']);
			$liabilities = real_escape_string($_POST['liabilities']);
			$equity = real_escape_string($_POST['equity']);
			$net_income = real_escape_string($_POST['net_income']);
			$cob = filter_var($_POST['cob'], FILTER_SANITIZE_NUMBER_INT);
			
			// check if year is given
			if ($cyear != 0 && $cyear != '') { $a = TRUE; } 
			else { $a = FALSE; $message[] = "Please enter the year."; }
			// check if current_assets is given
			if ($current_assets != '') { $b = TRUE; } 
			else { $b = FALSE; $message[] = "Please enter the current assets."; }
			// check if fixed_assets is given
			if ($fixed_assets != '') { $c = TRUE; } 
			else { $c = FALSE; $message[] = "Please enter the fixed assets."; }
			// check if liabilities is given
			if ($liabilities != '') { $d = TRUE; } 
			else { $d = FALSE; $message[] = "Please enter the liabilities."; }
			// check if equity is given
			if ($equity != '') { $e = TRUE; } 
			else { $e = FALSE; $message[] = "Please enter the members equity."; }
			// check if net_income is given
			if ($net_income != '') { $f = TRUE; } 
			else { $f = FALSE; $message[] = "Please enter the net surplus / loss."; }
			
			// If data pass all tests, proceed
			if ( $a && $b && $c && $d && $e && $f ) {
				
				// check for double entry first
				$sql = "SELECT cfid 
						FROM cooperatives_financials 
						WHERE cid = '$cid' AND cyear = '$cyear'";
				$res = query($sql); 
				$num = num_rows($res); 
				
				if ($num > 0) {
					?>
					<div class="alert alert-danger alert-dismissible fade in" role="alert">
						<strong>Record already exists! </strong><br /><br />
						<a href="tc.php?cid=<?php echo $cid ?>">Click here to view the record</a>
					</div>
					<?php
				} else {
					$sql = "INSERT INTO cooperatives_financials( cid, cyear, current_assets, fixed_assets, liabilities, equity, net_income, cob, user_id, user_dtime )
							VALUES( '$cid', '$cyear', '$current_assets', '$fixed_assets', '$liabilities', '$equity', '$net_income', '$cob', '$_SESSION[user_id]', NOW() )";
					if (query($sql)) {
						$cfid = mysqli_insert_id($connection);
						
						update_financials($cid);
						
						// insert logs
						$sql = "INSERT INTO cooperatives_logs(cid, uid, ldtime) VALUES('$cid', '$_SESSION[user_id]', NOW())";
						query($sql);
						
						// log the activity
						log_user(1, 'cooperatives_financials', $cfid);
						
						header("Location:tc.php?cid=".$cid."&tab=6&added_financials=1");
						exit();
					} else {
						?>
						<div class="alert alert-danger alert-dismissible fade in" role="alert">
							<strong>Could not add financial report because: </strong><br />
							<b><?php echo mysqli_error($connection) ?></b><br />
							The query was <?php echo $sql ?>.<br />
						</div>
						<?PHP
					}
				}
			} else {
				$serialized_message = serialize($message);
				// redirect the user to index page
				header('location: add_financials.php?serialized_message='.$serialized_message.'&cid='.$cid.'&cyear='.$cyear.'&current_assets='.$current_assets.'&fixed_assets='.$fixed_assets.'&liabilities='.$liabilities.'&equity='.$equity.'&net_income='.$net_income.'&cob='.$cob);
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
				  
				<title>OTC | Add Annual Financial Report</title>

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
							<h3>Add Annual Financial Report</h3>
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
						  
						  // get current year
						  $gyear = date('Y');

						  ?>
						  <div class="col-md-12 col-sm-12 col-xs-12">
							<div class="x_panel">
							  <div class="x_content">
								<br />
								
									<form action="add_financials.php" method="POST" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="cyear">Year <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <select id="cyear" name="cyear" required="required" class="form-control col-md-7 col-xs-12">
										  <?php
										  for ($i=$gyear; $i>2009; $i--) {
											  ?><option value="<?php echo $i ?>"><?php echo $i ?></option><?php
										  }
										  ?>
										  </select>
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="current_assets">Current Assets <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="current_assets" name="current_assets" required="required" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['current_assets'])) {
											  ?>value="<?php echo $_GET['current_assets'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="cob">Cash in Bank</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  Yes: <input type="radio" class="flat" name="cob" id="cob" value="1" /> 
										  No: <input type="radio" class="flat" name="cob" id="cob" value="0" checked="" />
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="fixed_assets">Fixed Assets <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="fixed_assets" name="fixed_assets" required="required" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['fixed_assets'])) {
											  ?>value="<?php echo $_GET['fixed_assets'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="liabilities">Liabilities <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="liabilities" name="liabilities" required="required" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['liabilities'])) {
											  ?>value="<?php echo $_GET['liabilities'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="equity">Members' Equity <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="equity" name="equity" required="required" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['equity'])) {
											  ?>value="<?php echo $_GET['equity'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label for="net_income" class="control-label col-md-3 col-sm-3 col-xs-12">Net Surplus / Loss <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input id="net_income" name="net_income" required="required" class="form-control col-md-7 col-xs-12" type="text" 
										  <?php
										  if (isset ($_GET['net_income'])) {
											  ?>value="<?php echo $_GET['net_income'] ?>"<?php
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

