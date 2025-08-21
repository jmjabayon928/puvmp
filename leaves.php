<?php

include("db.php");
include("sqli.php");
include("html.php");
define('PAGE', 'leaves.php'); 

session_start();

if (isset($_SESSION['user_id'])) {
	if (validate_level3(PAGE) == true) {

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

			<title>OTC - Leave Applications</title>

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
						<h3>Leave Applications</h3>
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
										?><option value="leaves.php?yyyymm=<?php echo $ymrow[0] ?>&tab=<?php echo $tab ?>" Selected><?php echo $ymrow[1] ?></option><?php
									} else {
										?><option value="leaves.php?yyyymm=<?php echo $ymrow[0] ?>&tab=<?php echo $tab ?>"><?php echo $ymrow[1] ?></option><?php
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
					  if (isset($_GET['added_leave'])) {
						  if ($_GET['added_leave'] == 1) {
							  ?>
								<div class="alert alert-success">
								  <strong>Success!</strong> Leave Application has been added into the database.
								</div>
							  <?php
						  }
					  }
					  if (isset($_GET['updated_leave'])) {
						  if ($_GET['updated_leave'] == 1) {
							  ?>
								<div class="alert alert-warning">
								  <strong>Success!</strong> Leave Application has been updated.
								</div>
							  <?php
						  }
					  }
					  if (isset($_GET['deleted_leave'])) {
						  if ($_GET['deleted_leave'] == 1) {
							  ?>
								<div class="alert alert-danger">
								  <strong>Success!</strong> Leave Application has been deleted.
								</div>
							  <?php
						  }
					  }
					  if (isset($_GET['approve_leave'])) {
						  if ($_GET['approve_leave'] == 1) {
							  ?>
								<div class="alert alert-success">
								  <strong>Success!</strong> Leave Application has been approved.
								</div>
							  <?php
						  } elseif ($_GET['approve_leave'] == -1) {
							  ?>
								<div class="alert alert-danger">
								  <strong>Success!</strong> Leave Application has been denied.
								</div>
							  <?php
						  }
					  }
					  
					  ?>
					
					  <div class="col-md-12 col-sm-12 col-xs-12">
						<div class="x_panel">
						  <div class="x_title">
							<ul class="nav navbar-right panel_toolbox">
								<a href="add_leave.php" class="btn btn-primary btn-md" data-toggle="tooltip" data-placement="top" title="Add Leave Application"><i class="fa fa-plus-square"></i> Add </a>
							</ul>
							<div class="clearfix"></div>
						  </div>
						  <div class="x_content">

							    <table id="datatable-fixed-header" class="table table-striped table-bordered jambo_table">
								  <thead>
									<tr>
									  <th width="14%"></th>
									  <th width="08%">Control #</th>
									  <th width="18%">Employee</th>
									  <th width="10%">Leave Type</th>
									  <th width="10%">Leave Date</th>
									  <th width="10%">No. of Days</th>
									  <th width="10%">Status</th>
									  <th width="20%">Remarks</th>
									</tr>
								  </thead>
								  <tbody>
								    <?php
									$sql = "SELECT l.aid, l.anum, CONCAT(e.lname,', ',e.fname), 
												lt.lname, DATE_FORMAT(l.fr_date, '%m/%d/%y'), 
												DATE_FORMAT(l.to_date, '%m/%d/%y'), l.lnum,
												l.stats, l.remarks, CONCAT(u.lname,', ',u.fname), 
												DATE_FORMAT(l.user_dtime, '%m/%d/%y %h:%i %p'), 
												CONCAT(u2.lname,', ',u2.fname), 
												DATE_FORMAT(l.approve_dtime, '%m/%d/%y %h:%i %p') 
											FROM internals_leave_applications l
												INNER JOIN employees e ON l.eid = e.eid
												INNER JOIN leave_types lt ON l.lid = lt.lid
												INNER JOIN users u ON l.user_id = u.user_id
												LEFT JOIN users u2 ON l.approve_id = u2.user_id
											ORDER BY l.to_date DESC";
									$res = query($sql); 
									while ($row = fetch_array($res)) {
										?>
										<tr>
											<td align="center">
												<a class="btn btn-info btn-xs" title="View Details" data-toggle="modal" data-target=".bs-example-modal-sm"><i class="fa fa-search"></i></a>

											    <div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-hidden="true">
												  <div class="modal-dialog modal-sm">
												    <div class="modal-content">

													  <div class="modal-header">
													    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
													    <h4 class="modal-title" id="myModalLabel2"><b>Leave Details</b></h4>
													  </div>
													  <div class="modal-body"> 
													  
													    <table class="table table-striped table-bordered jambo_table">
														  <tbody>
														    <tr>
															  <td width="40%" align="left">Encoder</td>
															  <td width="60%" align="left"><?php echo $row[9] ?></td>
															</tr>
														    <tr>
															  <td align="left">Date Encoded</td>
															  <td align="left"><?php echo $row[10] ?></td>
															</tr>
														    <tr>
															  <td align="left">Approved By</td>
															  <td align="left"><?php echo $row[11] ?></td>
															</tr>
														    <tr>
															  <td align="left">Date Approved</td>
															  <td align="left"><?php echo $row[12] == '00/00/00 12:00 AM' ? '' : $row[12] ?></td>
															</tr>
														  </tbody>
														</table>
														
													  </div>

												    </div>
												  </div>
											    </div>

												<?php
												if ($row[7] == 0) {
													?>
													<a href="approve_leave.php?aid=<?php echo $row[0] ?>" class="btn btn-success btn-xs" data-toggle="tooltip" data-placement="top" title="Approve Leave Application"><i class="fa fa-check"></i></a>
													<?php
													?>
													<a href="e_leave.php?aid=<?php echo $row[0] ?>" class="btn btn-warning btn-xs" data-toggle="tooltip" data-placement="top" title="Update Leave Application"><i class="fa fa-pencil"></i></a>
													<a href="d_leave.php?aid=<?php echo $row[0] ?>" class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top" title="Delete Leave Application"><i class="fa fa-eraser"></i></a>
													<?php
												} else {
													?>
													<a href="e_leave.php?aid=<?php echo $row[0] ?>" class="btn btn-warning btn-xs" data-toggle="tooltip" data-placement="top" title="Update Leave Application"><i class="fa fa-pencil"></i></a>
													<a href="d_leave.php?aid=<?php echo $row[0] ?>" class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top" title="Delete Leave Application"><i class="fa fa-eraser"></i></a>
													<?php
												}
												?>
											</td>
											<td align="center"><?php echo $row[1] ?></td>
											<td align="left"><?php echo $row[2] ?></td>
											<td align="left"><?php echo $row[3] ?></td>
											<td align="left"><?php echo 'Fr: '.$row[4].'<br />To: '.$row[5] ?></td>
											<td align="center"><?php echo $row[6] ?></td>
											<td align="left"><?php 
												if ($row[7] == -1) { echo 'Disapproved';
												} elseif ($row[7] == 0) { echo 'Pending';
												} elseif ($row[7] == 1) { echo 'Approved';
												}
												?></td>
											<td align="left"><?php echo $row[8] ?></td>
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
	} else {

		// check if user is a valid employee
		$sql = "SELECT eid FROM users WHERE user_id = '$_SESSION[user_id]' AND active = 1";
		$vres = query($sql); $vrow = fetch_array($vres); $eid = $vrow[0]; 
		
		if ($eid != 0) {

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

				<title>OTC - Employee's Own Leave Applications</title>

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
							<h3>Leave Applications</h3>
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
											?><option value="leaves.php?yyyymm=<?php echo $ymrow[0] ?>&tab=<?php echo $tab ?>" Selected><?php echo $ymrow[1] ?></option><?php
										} else {
											?><option value="leaves.php?yyyymm=<?php echo $ymrow[0] ?>&tab=<?php echo $tab ?>"><?php echo $ymrow[1] ?></option><?php
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
						  if (isset($_GET['added_leave'])) {
							  if ($_GET['added_leave'] == 1) {
								  ?>
									<div class="alert alert-success">
									  <strong>Success!</strong> Leave Application has been added into the database.
									</div>
								  <?php
							  }
						  }
						  if (isset($_GET['updated_leave'])) {
							  if ($_GET['updated_leave'] == 1) {
								  ?>
									<div class="alert alert-warning">
									  <strong>Success!</strong> Leave Application has been updated.
									</div>
								  <?php
							  }
						  }
						  if (isset($_GET['deleted_leave'])) {
							  if ($_GET['deleted_leave'] == 1) {
								  ?>
									<div class="alert alert-danger">
									  <strong>Success!</strong> Leave Application has been deleted.
									</div>
								  <?php
							  }
						  }
						  
						  ?>
						
						  <div class="col-md-12 col-sm-12 col-xs-12">
							<div class="x_panel">
							  <div class="x_title">
								<ul class="nav navbar-right panel_toolbox">
									<a href="add_leave.php" class="btn btn-primary btn-md" data-toggle="tooltip" data-placement="top" title="Add Leave Application"><i class="fa fa-plus-square"></i> Add </a>
								</ul>
								<div class="clearfix"></div>
							  </div>
							  <div class="x_content">

									<table id="datatable-fixed-header" class="table table-striped table-bordered jambo_table">
									  <thead>
										<tr>
										  <th width="14%"></th>
										  <th width="08%">Control #</th>
										  <th width="18%">Employee</th>
										  <th width="10%">Leave Type</th>
										  <th width="10%">Leave Date</th>
										  <th width="10%">No. of Days</th>
										  <th width="10%">Status</th>
										  <th width="20%">Remarks</th>
										</tr>
									  </thead>
									  <tbody>
										<?php
										$sql = "SELECT l.aid, l.anum, CONCAT(e.lname,', ',e.fname), 
													lt.lname, DATE_FORMAT(l.fr_date, '%m/%d/%y'), 
													DATE_FORMAT(l.to_date, '%m/%d/%y'), l.lnum,
													l.stats, l.remarks, CONCAT(u.lname,', ',u.fname), 
													DATE_FORMAT(l.user_dtime, '%m/%d/%y %h:%i %p'), 
													CONCAT(u2.lname,', ',u2.fname), 
													DATE_FORMAT(l.approve_dtime, '%m/%d/%y %h:%i %p') 
												FROM internals_leave_applications l
													INNER JOIN employees e ON l.eid = e.eid
													INNER JOIN leave_types lt ON l.lid = lt.lid
													INNER JOIN users u ON l.user_id = u.user_id
													LEFT JOIN users u2 ON l.approve_id = u2.user_id
												WHERE l.eid = '$eid'
												ORDER BY l.to_date DESC";
										$res = query($sql); 
										while ($row = fetch_array($res)) {
											?>
											<tr>
												<td align="center">
													<a class="btn btn-info btn-xs" title="View Details" data-toggle="modal" data-target=".bs-example-modal-sm"><i class="fa fa-search"></i></a>

													<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-hidden="true">
													  <div class="modal-dialog modal-sm">
														<div class="modal-content">

														  <div class="modal-header">
															<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
															<h4 class="modal-title" id="myModalLabel2"><b>Leave Details</b></h4>
														  </div>
														  <div class="modal-body"> 
														  
															<table class="table table-striped table-bordered jambo_table">
															  <tbody>
																<tr>
																  <td width="40%" align="left">Encoder</td>
																  <td width="60%" align="left"><?php echo $row[9] ?></td>
																</tr>
																<tr>
																  <td align="left">Date Encoded</td>
																  <td align="left"><?php echo $row[10] ?></td>
																</tr>
																<tr>
																  <td align="left">Approved By</td>
																  <td align="left"><?php echo $row[11] ?></td>
																</tr>
																<tr>
																  <td align="left">Date Approved</td>
																  <td align="left"><?php echo $row[12] == '00/00/00 12:00 AM' ? '' : $row[12] ?></td>
																</tr>
															  </tbody>
															</table>
															
														  </div>

														</div>
													  </div>
													</div>

													<?php
													if ($row[7] == 0) {
														?>
														<a href="approve_leave.php?aid=<?php echo $row[0] ?>" class="btn btn-success btn-xs" data-toggle="tooltip" data-placement="top" title="Approve Leave Application"><i class="fa fa-check"></i></a>
														<?php
														?>
														<a href="e_leave.php?aid=<?php echo $row[0] ?>" class="btn btn-warning btn-xs" data-toggle="tooltip" data-placement="top" title="Update Leave Application"><i class="fa fa-pencil"></i></a>
														<a href="d_leave.php?aid=<?php echo $row[0] ?>" class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top" title="Delete Leave Application"><i class="fa fa-eraser"></i></a>
														<?php
													} else {
														?>
														<a href="e_leave.php?aid=<?php echo $row[0] ?>" class="btn btn-warning btn-xs" data-toggle="tooltip" data-placement="top" title="Update Leave Application"><i class="fa fa-pencil"></i></a>
														<a href="d_leave.php?aid=<?php echo $row[0] ?>" class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top" title="Delete Leave Application"><i class="fa fa-eraser"></i></a>
														<?php
													}
													?>
												</td>
												<td align="center"><?php echo $row[1] ?></td>
												<td align="left"><?php echo $row[2] ?></td>
												<td align="left"><?php echo $row[3] ?></td>
												<td align="left"><?php echo 'Fr: '.$row[4].'<br />To: '.$row[5] ?></td>
												<td align="center"><?php echo $row[6] ?></td>
												<td align="left"><?php 
													if ($row[7] == -1) { echo 'Disapproved';
													} elseif ($row[7] == 0) { echo 'Pending';
													} elseif ($row[7] == 1) { echo 'Approved';
													}
													?></td>
												<td align="left"><?php echo $row[8] ?></td>
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
		} else {

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

				<title>OTC - Employee's Own Leave Applications</title>

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
					
					?>

					<!-- page content -->
					<div class="right_col" role="main">
					  <div class="">
							<div class="row">
							
							  <div class="col-md-12 col-sm-12 col-xs-12">
								<div class="x_panel">
									<div class="x_content">
									  <div class="bs-example" data-example-id="simple-jumbotron">
											<blockquote class="blockquote">
											  <p class="h1">Invalid User-right!</p>
											  <footer class="blockquote-footer"><em>Ask your system administrator for access to this page.</em></footer>
											</blockquote>
									  </div>
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


			  </body>
			</html>		
			<?php
		}
	}
} else {
	header('Location: login.php');	
}
