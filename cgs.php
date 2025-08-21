<?php

include("db.php");
include("sqli.php");
include("html.php");
define('PAGE', 'cgs.php'); 

session_start();

if (isset($_SESSION['user_id'])) {
	if (validate_level(PAGE) == true) {

		// log the activity
		log_user(5, 'cooperatives_cgs', 0);
		
		?>
		<!DOCTYPE html>
		<html lang="en">
		  <head>
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
			<!-- Meta, title, CSS, favicons, etc. -->
			<meta charset="utf-8">
			<meta http-equiv="X-UA-Compatible" content="IE=edge">
			<meta name="viewport" content="width=device-width, initial-scale=1">
			  
			<title>OTC | CGS Details</title>

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
			<!-- Custom Theme Style -->
			<link href="css/baguetteBox.min.css" rel="stylesheet">
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
				
				$ccid = $_GET['ccid'];

				$sql = "SELECT g.cgs_num, c.cname, DATE_FORMAT(g.cgs_date, '%m/%d/%Y'), 
							DATE_FORMAT(g.cgs_exp, '%m/%d/%Y'), CONCAT(e.lname,', ',e.fname),
							DATE_FORMAT(g.last_update, '%m/%d/%Y'), CONCAT(ee.lname,', ',ee.fname),
							DATE_FORMAT(g.last_verify, '%m/%d/%Y'), g.cid
						FROM cooperatives_cgs g
							INNER JOIN cooperatives c ON g.cid = c.cid
							LEFT JOIN employees e ON g.last_update_id = e.eid
							LEFT JOIN employees ee ON g.last_verify_id = ee.eid
						WHERE g.ccid = '$ccid'";
				$res = query($sql); 
				$row = fetch_array($res); 
				
				?>

				<!-- page content -->
				<div class="right_col" role="main">
				  <div class="">
					<div class="page-title">
					  <div class="title_left">
						<h3>Certificate of Good Standing</h3>
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
					<div class="row"> <!-- container of all tables -->
					
					  <!-- TC Basic Info -->
					  <div class="col-md-12 col-sm-12 col-xs-12">
						<div class="x_panel">
						  <div class="x_title">
							<h2><a href="tc.php?cid=<?php echo $row[8] ?>"><?php echo $row[1] ?></a></h2>
							<ul class="nav navbar-right panel_toolbox">
							  <li><a href="e_cgs.php?ccid=<?php echo $ccid ?>"><i class="fa fa-pencil"></i></a></li>
							  <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
							  <li><a class="close-link"><i class="fa fa-close"></i></a></li>
							</ul>
							<div class="clearfix"></div>
						  </div>
						  <div class="x_content">
							<table class="table table-striped table-bordered jambo_table bulk_action">
								<thead>
								  <tr class="headings">
									  <th colspan="4">Certificate of Good Standing Details</th>
								  </tr>
								</thead>
								<tbody>
									<tr>
										<td width="13%">CGS No.</td>
										<td width="37%"><b><?php echo $row[0] ?></b></td>
										<td width="13%">CGS Date</td>
										<td width="37%"><b><?php echo $row[2] ?></b></td>
									</tr>
									<tr>
										<td>TC Name</td>
										<td><b><?php echo $row[1] ?></b></td>
										<td>CGS Expiration</td>
										<td><b><?php echo $row[3] ?></b></td>
									</tr>
									<tr>
										<td>Last Updated By</td>
										<td><b><?php echo $row[4] ?></b></td>
										<td>Last Verified By</td>
										<td><b><?php echo $row[6] ?></b></td>
									</tr>
									<tr>
										<td>Last Date Updated</td>
										<td><b><?php echo $row[5] != '00/00/0000' ? $row[5] : '' ?></b></td>
										<td>Last Date Verified</td>
										<td><b><?php echo $row[7] != '00/00/0000' ? $row[7] : '' ?></b></td>
									</tr>
								</tbody>
							</table>
						  </div>
						</div>
					  </div>
					  <div class="clearfix"></div>
					  <!-- end of TC Basic Info -->
					
					  <div class="col-md-12 col-sm-12 col-xs-12">
						<div class="x_panel">
						  <div class="x_title">
							<h2>Attachments</h2>
							<ul class="nav navbar-right panel_toolbox">
							  <li><a href="add_cgs_efiles.php?ccid=<?php echo $ccid ?>"><i class="fa fa-plus"></i></a></li>
							  <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
							  <li><a class="close-link"><i class="fa fa-close"></i></a></li>
							</ul>
							<div class="clearfix"></div>
						  </div>
						  <div class="x_content">
						  
							<div class="tz-gallery">
								<div class="row">
									<?php
									$sql = "SELECT cfile from cgs_efiles WHERE ccid = '$ccid'";
									$res = query($sql); 
									while($row = fetch_array($res)) {
										?>
										<div class="col-sm-6 col-md-4">
											<a class="lightbox" href="<?php echo $row[0] ?>">
												<img src="<?php echo $row[0] ?>" height="100%" width="100%">
											</a>
										</div>
										<?php
									}
									?>
								</div>
							</div>
							<script src="js/baguetteBox.min.js"></script>
							<script>
								baguetteBox.run('.tz-gallery');
							</script>
						  
						  </div>
						</div>
					  </div>
					  <?php
					  /*
					  for ($i=1; $i<6; $i++) {
					     if ($i == 1) { $ctype = 'Letter of Intent';
						 } elseif ($i == 2) { $ctype = 'Audited Financial Statements';
						 } elseif ($i == 3) { $ctype = 'Duly accomplished OTC Annual Report';
						 } elseif ($i == 4) { $ctype = 'Certificate Of Compliance from CDA';
						 } elseif ($i == 5) { $ctype = 'Affidavit of Authenticity of Docs';
						 }
						 
						 ?>
						  <div class="col-md-12 col-sm-12 col-xs-12">
							<div class="x_panel">
							  <div class="x_title">
								<h2><?php echo $ctype ?></h2>
								<ul class="nav navbar-right panel_toolbox">
								  <li><a href="add_cgs_efiles.php?ccid=<?php echo $ccid ?>&ctype=<?php echo $i ?>"><i class="fa fa-plus"></i></a></li>
								  <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
								  <li><a class="close-link"><i class="fa fa-close"></i></a></li>
								</ul>
								<div class="clearfix"></div>
							  </div>
							  <div class="x_content">
							  
								<div class="tz-gallery<?php echo $i ?>">
									<div class="row">
										<?php
										$sql = "SELECT cfile from cgs_efiles WHERE ccid = '$ccid' AND ctype = '$i'";
										$res = query($sql); 
										while($row = fetch_array($res)) {
											?>
											<div class="col-sm-6 col-md-4">
												<a class="lightbox" href="<?php echo $row[0] ?>">
													<img src="<?php echo $row[0] ?>" height="100%" width="100%">
												</a>
											</div>
											<?php
										}
										?>
									</div>
								</div>
								<script src="js/baguetteBox.min.js"></script>
								<script>
									baguetteBox.run('.tz-gallery<?php echo $i ?>');
								</script>
							  
							  </div>
							</div>
						  </div>
						 <?php
					  }
					  */
					  ?>
						
					</div><!-- end of container -->

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
		<?php
	}
} else {
	header('Location: login.php');	
}


