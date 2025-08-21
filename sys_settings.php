<?php

include("db.php");
include("sqli.php");
include("html.php");

$tab = $_GET['tab'];

session_start();

if (isset($_SESSION['user_id'])) {
	if (validate_user() == true) {
		?>
		<!DOCTYPE html>
		<html lang="en">
		  <head>
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
			<!-- Meta, title, CSS, favicons, etc. -->
			<meta charset="utf-8">
			<meta http-equiv="X-UA-Compatible" content="IE=edge">
			<meta name="viewport" content="width=device-width, initial-scale=1">

			<title>OTC - System Settings</title>

			<!-- Bootstrap -->
			<link href="vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
			<!-- Font Awesome -->
			<link href="vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
			<!-- NProgress -->
			<link href="vendors/nprogress/nprogress.css" rel="stylesheet">
			<!-- iCheck -->
			<link href="vendors/iCheck/skins/flat/green.css" rel="stylesheet">
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
				
				if (isset ($_GET['rid'])) {
					$rid = $_GET['rid'];
				} else {
					$rid = 1; 
				}
				?>

				<!-- page content -->
				<div class="right_col" role="main">
				  <div class="">
					<div class="page-title">
					  <div class="title_left">
						<h3>System Settings</h3>
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
						  <div class="x_title">
							<h2>
							<?php 
							if ($tab == 1) { 
								?>
								<select class="form-control col-md-7 col-xs-12" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
									<?php
									$sql = "SELECT rid, rname FROM regions ORDER BY rid";
									$rres = query($sql); 
									while ($rrow = fetch_array($rres)) {
										if ($rid == $rrow[0]) {
											?><option value="sys_settings.php?tab=1&rid=<?php echo $rrow[0] ?>" Selected><?php echo $rrow[1] ?></option><?php
										} else {
											?><option value="sys_settings.php?tab=1&rid=<?php echo $rrow[0] ?>"><?php echo $rrow[1] ?></option><?php
										}
									}
									free_result($rres); 
									?>
								</select>
								<?php
							} elseif ($tab == 2) { echo 'File Types';
							} elseif ($tab == 3) { echo 'Item Categories';
							}
							?>
							</h2>
							<div class="clearfix"></div>
						  </div>
						  <div class="x_content">
							<table class="table table-striped table-bordered jambo_table">
							  <thead>
								<tr>
								  <?php
								  if ($tab == 1) {
									  ?>
									  <th width="20%">Region</th>
									  <th width="20%">Province</th>
									  <th width="20%">District</th>
									  <th width="20%">City/Town</th>
									  <th width="20%">Barangay</th>
									  <?php
								  } elseif ($tab == 2) {
									  ?>
									  <th width="30%">File Type No.</th>
									  <th width="40%">File Type Name</th>
									  <th width="30%"></th>
									  <?php
								  } elseif ($tab == 3) {
									  ?>
									  <th width="30%">Category No.</th>
									  <th width="40%">Category Name</th>
									  <th width="30%"></th>
									  <?php
								  }
								  ?>
								</tr>
							  </thead>
							  <tbody>
								<?php
								if ($tab == 1) {
									$sql = "SELECT r.rid, r.rname, p.pid, p.pname, d.did, d.dnum, t.tid, t.tname, b.bid, b.bname
											FROM regions r 
												LEFT JOIN provinces p ON r.rid = p.rid
												LEFT JOIN districts d ON p.pid = d.pid
												LEFT JOIN towns t ON d.did = t.did 
												LEFT JOIN brgys b ON t.tid = b.tid 
											WHERE r.rid = '$rid'
											ORDER BY p.pname, d.dnum, t.tname, b.bname";
								} elseif ($tab == 2) {
									$sql = "SELECT tid, tname
											FROM file_types
											ORDER BY tname";
								} elseif ($tab == 3) {
									$sql = "SELECT cid, cname
											FROM categories
											ORDER BY cname";
								}
								
								$res = query($sql);

								while ($row = fetch_array($res)) {
									?>
									<tr>
									  <?php
										if ($tab == 1) {
											?>
											  <td align="left"><a href="add_province.php?rid=<?php echo $row[0] ?>" class="btn btn-info btn-xs"><i class="fa fa-plus-square"></i></a>&nbsp;<a href="e_region.php?rid=<?php echo $row[0] ?>" class="btn btn-warning btn-xs"><i class="fa fa-edit"></i></a> &nbsp; <?php echo $row[1] ?></td>
											  <td align="left"><a href="add_district.php?pid=<?php echo $row[2] ?>" class="btn btn-info btn-xs"><i class="fa fa-plus-square"></i></a>&nbsp;<a href="e_province.php?pid=<?php echo $row[2] ?>" class="btn btn-warning btn-xs"><i class="fa fa-edit"></i></a> &nbsp; <?php echo $row[3] ?></td>
											  <td align="left"><a href="add_town.php?did=<?php echo $row[4] ?>" class="btn btn-info btn-xs"><i class="fa fa-plus-square"></i></a>&nbsp;<a href="e_district.php?did=<?php echo $row[4] ?>" class="btn btn-warning btn-xs"><i class="fa fa-edit"></i></a> &nbsp; <?php echo $row[5] ?></td>
											  <td align="left"><a href="add_brgy.php?tid=<?php echo $row[6] ?>" class="btn btn-info btn-xs"><i class="fa fa-plus-square"></i></a>&nbsp;<a href="e_town.php?tid=<?php echo $row[6] ?>" class="btn btn-warning btn-xs"><i class="fa fa-edit"></i></a> &nbsp; <?php echo $row[7] ?></td>
											  <td align="left"><a href="e_brgy.php?did=<?php echo $row[8] ?>" class="btn btn-warning btn-xs"><i class="fa fa-edit"></i></a> &nbsp; <?php echo $row[9] ?></td>
											<?php
										} elseif ($tab == 2) {
											?>
											<td align="center"><?php echo $row[0] ?></td>
											<td align="left"><?php echo $row[1] ?></td>
											<td align="center">
												<a href="e_file_type.php?tid=<?php echo $row[0] ?>" class="btn btn-warning btn-xs"><i class="fa fa-pencil"></i> Edit </a>&nbsp; &nbsp;
												<a href="d_file_type.php?tid=<?php echo $row[0] ?>" class="btn btn-danger btn-xs"><i class="fa fa-eraser"></i> Delete </a>
											</td>
											<?php
										} elseif ($tab == 3) {
											?>
											<td align="center"><?php echo $row[0] ?></td>
											<td align="left"><?php echo $row[1] ?></td>
											<td align="center">
												<a href="e_category.php?tid=<?php echo $row[0] ?>" class="btn btn-warning btn-xs"><i class="fa fa-pencil"></i> Edit </a>&nbsp; &nbsp;
												<a href="d_category.php?tid=<?php echo $row[0] ?>" class="btn btn-danger btn-xs"><i class="fa fa-eraser"></i> Delete </a>
											</td>
											<?php
										}
									  ?>
									</tr>
									<?php
								}

								mysqli_free_result($res);
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
			<!-- FastClick -->
			<script src="vendors/fastclick/lib/fastclick.js"></script>
			<!-- NProgress -->
			<script src="vendors/nprogress/nprogress.js"></script>
			<!-- iCheck -->
			<script src="vendors/iCheck/icheck.min.js"></script>
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
			<script src="vendors/jszip/dist/jszip.min.js"></script>
			<script src="vendors/pdfmake/build/pdfmake.min.js"></script>
			<script src="vendors/pdfmake/build/vfs_fonts.js"></script>

			<!-- Custom Theme Scripts -->
			<script src="build/js/custom.min.js"></script>

		  </body>
		</html>
		<?php
	}
} else {
	header('Location: login.php');	
}

