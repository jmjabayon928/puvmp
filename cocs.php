<?php

include("db.php");
include("sqli.php");
include("html.php");
define('PAGE', 'cocs.php'); 

session_start();

if (isset($_SESSION['user_id'])) {
	if (validate_level(PAGE) == true) {

		// log the activity
		log_user(5, 'leaves', 0);
		
		?>
		<!DOCTYPE html>
		<html lang="en">
		  <head>
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
			<!-- Meta, title, CSS, favicons, etc. -->
			<meta charset="utf-8">
			<meta http-equiv="X-UA-Compatible" content="IE=edge">
			<meta name="viewport" content="width=device-width, initial-scale=1">

			<title>OTC - Earned COCs</title>

			<!-- Bootstrap -->
			<link href="vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
			<!-- Font Awesome -->
			<link href="vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
			<!-- Datatables -->
			<link href="vendors/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
			<link href="vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css" rel="stylesheet">
			<link href="vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css" rel="stylesheet">
			<link href="vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css" rel="stylesheet">
			<link href="vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css" rel="stylesheet">

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
				
				if (isset ($_GET['yyyymm'])) {
					$yyyymm = $_GET['yyyymm'];
					$gyear = substr($_GET['yyyymm'], 0, 4);
					$gmonth = substr($_GET['yyyymm'], 4, 2);
				} else {
					$gyear = date('Y');
					$gmonth = date('m');
					$yyyymm = date('Ym');
				}
				
				?>

				<!-- page content -->
				<div class="right_col" role="main">
				  <div class="">
					<div class="page-title">
					  <div class="title_left">
						<h3>Certificates of Overtime Compensation</h3>
					  </div>

					  <div class="title_right">
						<div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
							<select class="form-control col-md-7 col-xs-12" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
								<option value="">Month and Year</option>
								<?php
								$sql = "SELECT yyyymm, month_year FROM year_months ORDER BY yyyymm DESC";
								$ymres = query($sql); 
								while ($ymrow = fetch_array($ymres)) {
									if ($yyyymm == $ymrow[0]) {
										?><option value="cocs.php?yyyymm=<?php echo $ymrow[0] ?>&tab=<?php echo $tab ?>" Selected><?php echo $ymrow[1] ?></option><?php
									} else {
										?><option value="cocs.php?yyyymm=<?php echo $ymrow[0] ?>&tab=<?php echo $tab ?>"><?php echo $ymrow[1] ?></option><?php
									}
								}
								free_result($ymres); 
								?>
							</select>
						</div>
					  </div>
					</div>

					<div class="clearfix"></div>

					<div class="row">
					  <?php
					  if (isset($_GET['added_coc'])) {
						  if ($_GET['added_coc'] == 1) {
							  ?>
								<div class="alert alert-success">
								  <strong>Success!</strong> Certificate of Completion has been added into the database.
								</div>
							  <?php
						  }
					  }
					  if (isset($_GET['updated_coc'])) {
						  if ($_GET['updated_coc'] == 1) {
							  ?>
								<div class="alert alert-warning">
								  <strong>Success!</strong> Certificate of Completion has been updated.
								</div>
							  <?php
						  }
					  }
					  if (isset($_GET['deleted_coc'])) {
						  if ($_GET['deleted_coc'] == 1) {
							  ?>
								<div class="alert alert-danger">
								  <strong>Success!</strong> Certificate of Completion has been deleted.
								</div>
							  <?php
						  }
					  }
					  
					  ?>
					
					  <div class="col-md-12 col-sm-12 col-xs-12">
						<div class="x_panel">
						  <div class="x_title">
							<ul class="nav navbar-right panel_toolbox">
								<a href="add_coc.php" class="btn btn-primary btn-md" data-toggle="tooltip" data-placement="top" title="Add Leave Application"><i class="fa fa-plus-square"></i> Add </a>
							</ul>
							<div class="clearfix"></div>
						  </div>
						  <div class="x_content">

							    <table id="datatable-fixed-header" class="table table-striped table-bordered jambo_table">
								  <thead>
									<tr>
									  <th width="08%"></th>
									  <th width="08%">Control #</th>
									  <th width="08%">Date</th>
									  <th width="20%">Employee</th>
									  <th width="08%">Hours Earned</th>
									  <th width="08%">Expiry</th>
									  <th width="08%">Balance</th>
									  <th width="20%">Encoder</th>
									  <th width="12%">Encoded</th>
									</tr>
								  </thead>
								  <tbody>
								    <?php
									$sql = "SELECT c.cid, c.cnum, DATE_FORMAT(c.cdate, '%m/%d/%y'), 
												CONCAT(e.lname,', ',e.fname), c.hrs_num, 
												DATE_FORMAT(c.exp_date, '%m/%d/%y'), 
												c.hrs_bal, CONCAT(u.lname,', ',u.fname), 
												DATE_FORMAT(c.user_dtime, '%m/%d/%y %h:%i %p')
											FROM cocs c
												INNER JOIN employees e ON c.eid = e.eid
												INNER JOIN users u ON c.user_id = u.user_id
											ORDER BY c.cdate DESC";
									$res = query($sql); 
									while ($row = fetch_array($res)) {
										?>
										<tr>
											<td align="center">
												<a href="e_coc.php?cid=<?php echo $row[0] ?>" class="btn btn-warning btn-xs" data-toggle="tooltip" data-placement="top" title="Update COC"><i class="fa fa-pencil"></i></a>
												<a href="d_coc.php?cid=<?php echo $row[0] ?>" class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top" title="Delete COC"><i class="fa fa-eraser"></i></a>
											</td>
											<td align="center"><?php echo $row[1] ?></td>
											<td align="center"><?php echo $row[2] ?></td>
											<td align="left"><?php echo $row[3] ?></td>
											<td align="center"><?php echo $row[4] ?></td>
											<td align="center"><?php echo $row[5] ?></td>
											<td align="center"><?php echo $row[6] ?></td>
											<td align="left"><?php echo $row[7] ?></td>
											<td align="center"><?php echo $row[8] ?></td>
										</tr>
										<?php
									}
									free_result($res); 
									?>
								  </tbody>
								</table>
								
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
			<!-- Datatables -->
			<script src="vendors/datatables.net/js/jquery.dataTables.min.js"></script>
			<script src="vendors/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
			<script src="vendors/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
			<script src="vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js"></script>
			<script src="vendors/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js"></script>
			<script src="vendors/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
			<script src="vendors/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
			<script src="vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js"></script>
			<script src="vendors/datatables.net-scroller/js/dataTables.scroller.min.js"></script>

			<!-- Custom Theme Scripts -->
			<script src="build/js/custom.min.js"></script>

		  </body>
		</html>		
		<?php
	}
} else {
	header('Location: login.php');	
}
