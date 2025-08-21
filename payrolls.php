<?php

include("db.php");
include("sqli.php");
include("html.php");
define('PAGE', 'payrolls.php'); 

session_start();

if (isset($_SESSION['user_id'])) {
	if (validate_level(PAGE) == true) {

		// log the activity
		log_user(5, 'payrolls', 0);
		
		?>
		<!DOCTYPE html>
		<html lang="en">
		  <head>
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
			<!-- Meta, title, CSS, favicons, etc. -->
			<meta charset="utf-8">
			<meta http-equiv="X-UA-Compatible" content="IE=edge">
			<meta name="viewport" content="width=device-width, initial-scale=1">

			<title>OTC - Payrolls</title>

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
					<div class="page-title">
					  <div class="title_left">
						<h3>Payrolls</h3>
					  </div>

					  <div class="title_right">
						<div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
						</div>
					  </div>
					</div>

					<div class="clearfix"></div>

					<div class="row">
					  <div class="col-md-12 col-sm-12 col-xs-12">
						<div class="x_panel">
						  <div class="x_title">

							<ul class="nav navbar-right panel_toolbox">
							</ul>
							<div class="clearfix"></div>
						  </div>
						  <div class="x_content">
							<table id="datatable-fixed-header" class="table table-striped table-bordered jambo_table">
							  <thead>
								<tr>
								  <th width="18%"></th>
								  <th width="08%">Prepared</th>
								  <th width="12%">Payroll Group</th>
								  <th width="12%">Period</th>
								  <th width="10%">Payroll Total</th>
								  <th width="08%">VAT</th>
								  <th width="08%">EWT</th>
								  <th width="12%">Prepared By</th>
								  <th width="12%">Verified By</th>
								</tr>
							  </thead>
							  <tbody>
								<?php
								$sql = "SELECT p.pid, DATE_FORMAT(p.user_dtime, '%m/%d/%y'), 
											pg.gname, DATE_FORMAT(sp.sp_start, '%m/%d/%y'), 
											DATE_FORMAT(sp.sp_end, '%m/%d/%y'), p.total_gross, 
											p.per_tax, p.ewt_tax, CONCAT(e.lname,', ',e.fname),
											CONCAT(e2.lname,', ',e2.fname), p.gid
										FROM payrolls p 
											INNER JOIN salary_periods sp ON p.sid = sp.sid
											INNER JOIN payrolls_groups pg ON p.gid = pg.gid
											LEFT JOIN employees e ON p.user_id = e.eid
											LEFT JOIN employees e2 ON p.user2_id = e2.eid
										ORDER BY p.user_dtime DESC";
								$res = query($sql);

								while ($row = fetch_array($res)) {
									?>
									<tr>
									  <td align="center">
										<a target="new" href="payroll.php?pid=<?php echo $row[0] ?>" class="btn btn-info btn-xs" data-toggle="tooltip" data-placement="top" title="View Details"><i class="fa fa-search"></i></a>
										<a target="new" href="tcpdf/examples/pdf_payroll.php?pid=<?php echo $row[0] ?>" class="btn btn-success btn-xs" data-toggle="tooltip" data-placement="top" title="Print Payroll"><i class="fa fa-rub"></i></a>
										<a target="new" href="tcpdf/examples/pdf_ors_dv.php?pid=<?php echo $row[0] ?>" class="btn btn-warning btn-xs" data-toggle="tooltip" data-placement="top" title="Print ORS / DV"><i class="fa fa-files-o"></i></a>
										<?php
										if ($row[10] == 1) {
											?>
											<a target="new" href="tcpdf/examples/pdf_deductions.php?pid=<?php echo $row[0] ?>" class="btn btn-primary btn-xs" data-toggle="tooltip" data-placement="top" title="Print Deductions"><i class="fa fa-print"></i></a>
											<a target="new" href="payroll_deductions.php?pid=<?php echo $row[0] ?>" class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top" title="View Deductions"><i class="fa fa-minus"></i></a>
											<?php
										}
										?>
									  </td>
									  <td align="center"><?php echo $row[1] ?></td>
									  <td align="left"><?php echo $row[2] ?></td>
									  <td align="center"><?php echo $row[3].' - '.$row[4] ?></td>
									  <td align="right"><?php echo number_format($row[5], 2) ?></td>
									  <td align="right"><?php echo number_format($row[6], 2) ?></td>
									  <td align="right"><?php echo number_format($row[7], 2) ?></td>
									  <td align="center"><?php echo $row[8] ?></td>
									  <td align="center"><?php echo $row[9] ?></td>
									</tr>
									<?php
								}

								free_result($res);
								?>
							  </tbody>
							</table>
							<?php
							?>
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
			<script src="vendors/datatables.net-buttons/js/buttons.flash.min.js"></script>
			<script src="vendors/datatables.net-buttons/js/buttons.html5.min.js"></script>
			<script src="vendors/datatables.net-buttons/js/buttons.print.min.js"></script>
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
