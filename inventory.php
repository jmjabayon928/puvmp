<?php

include("db.php");
include("sqli.php");
include("html.php");
define('PAGE', 'inventory.php'); 

$tab = $_GET['tab'];

session_start();

if (isset($_SESSION['user_id'])) {
	if (validate_level(PAGE) == true) {

		// log the activity
		log_user(5, 'inventory', 0);
		
		?>
		<!DOCTYPE html>
		<html lang="en">
		  <head>
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
			<!-- Meta, title, CSS, favicons, etc. -->
			<meta charset="utf-8">
			<meta http-equiv="X-UA-Compatible" content="IE=edge">
			<meta name="viewport" content="width=device-width, initial-scale=1">

			<title>OTC - Inventory</title>

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
		  <body class="nav-md" onload="document.InventoryForm.property_num.focus();">
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
						<h3>Inventory</h3>
					  </div>

					  <div class="title_right">
						<div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
						  <form name="InventoryForm" method="post" action="item.php">
						  <div class="input-group">
								<input type="text" id="property_num" name="property_num" class="form-control" placeholder="Property Number">
								<span class="input-group-btn">
								  <button class="btn btn-default" type="button">Go!</button>
								</span>
						  </div>
						  </form>
						</div>
					  </div>
					</div>

					<div class="clearfix"></div>

					<div class="row">
					
					  <?php
					  if (isset($_GET['success'])) {
						  if ($_GET['success'] == 1) {
							  ?>
								<div class="alert alert-success">
								  <strong>Success!</strong> Voter has been added into the database.
								</div>
							  <?php
						  }
					  }
					  
					  if (isset ($_GET['cid'])) {
						  $cid = $_GET['cid']; 
					  } else {
						  $cid = 0; 
					  }
					  if (isset ($_GET['did'])) {
						  $did = $_GET['did']; 
					  } else {
						  $did = 0; 
					  }
					  if (isset ($_GET['eid'])) {
						  $eid = $_GET['eid']; 
					  } else {
						  $eid = 0; 
					  }
					  ?>

					  <div class="col-md-12 col-sm-12 col-xs-12">
						<div class="x_panel">
						  <div class="x_title">
							<h2>
							<?php
							if ($tab == 1) {
								?>
								<select class="form-control col-md-7 col-xs-12" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
									<?php
									if ($cid == 0) {
										?>
										<option value="inventory.php?tab=1&cid=0" Selected>All Categories</option>
										<?php
										$sql = "SELECT cid, cname FROM categories ORDER BY cname";
										$cres = query($sql); 
										while ($crow = fetch_array($cres)) {
											?><option value="inventory.php?tab=1&cid=<?php echo $crow[0] ?>"><?php echo $crow[1] ?></option><?php
										}
										free_result($cres); 
									} else {
										?>
										<option value="inventory.php?tab=1&cid=0">All Categories</option>
										<?php
										$sql = "SELECT cid, cname FROM categories ORDER BY cname";
										$cres = query($sql); 
										while ($crow = fetch_array($cres)) {
											if ($cid == $crow[0]) {
												?><option value="inventory.php?tab=1&cid=<?php echo $crow[0] ?>" Selected><?php echo $crow[1] ?></option><?php
											} else {
												?><option value="inventory.php?tab=1&cid=<?php echo $crow[0] ?>"><?php echo $crow[1] ?></option><?php
											}
										}
										free_result($cres); 
									}
									?>
								</select>
								<?php
							} elseif ($tab == 2) {
								?>
								<select class="form-control col-md-7 col-xs-12" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
									<?php
									if ($did == 0) {
										?>
										<option value="inventory.php?tab=2&did=0" Selected>No Assigned Division</option>
										<?php
										$sql = "SELECT did, dname FROM departments ORDER BY dname";
										$dres = query($sql); 
										while ($drow = fetch_array($dres)) {
											?><option value="inventory.php?tab=2&did=<?php echo $drow[0] ?>"><?php echo $drow[1] ?></option><?php
										}
										free_result($dres); 
									} else {
										?>
										<option value="inventory.php?tab=2&did=0">No Assigned Division</option>
										<?php
										$sql = "SELECT did, dname FROM departments ORDER BY dname";
										$dres = query($sql); 
										while ($drow = fetch_array($dres)) {
											if ($did == $drow[0]) {
												?><option value="inventory.php?tab=2&did=<?php echo $drow[0] ?>" Selected><?php echo $drow[1] ?></option><?php
											} else {
												?><option value="inventory.php?tab=2&did=<?php echo $drow[0] ?>"><?php echo $drow[1] ?></option><?php
											}
										}
										free_result($dres); 
									}
									?>
								</select>
								<?php
							} elseif ($tab == 3) {
								?>
								<select class="form-control col-md-7 col-xs-12" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
									<?php
									if ($eid == 0) {
										?>
										<option value="inventory.php?tab=3&eid=0" Selected>Not Issued to Any Employee</option>
										<?php
										$sql = "SELECT eid, CONCAT(lname,', ',fname) FROM employees ORDER BY lname, fname";
										$eres = query($sql); 
										while ($erow = fetch_array($eres)) {
											?><option value="inventory.php?tab=3&eid=<?php echo $erow[0] ?>"><?php echo $erow[1] ?></option><?php
										}
										free_result($eres); 
									} else {
										?>
										<option value="inventory.php?tab=3&eid=0">Not Issued to Any Employee</option>
										<?php
										$sql = "SELECT eid, CONCAT(lname,', ',fname) FROM employees ORDER BY lname, fname";
										$eres = query($sql); 
										while ($erow = fetch_array($eres)) {
											if ($eid == $erow[0]) {
												?><option value="inventory.php?tab=3&eid=<?php echo $erow[0] ?>" Selected><?php echo $erow[1] ?></option><?php
											} else {
												?><option value="inventory.php?tab=3&eid=<?php echo $erow[0] ?>"><?php echo $erow[1] ?></option><?php
											}
										}
										free_result($eres); 
									}
									?>
								</select>
								<?php
							}
							?>
							</h2>
							<ul class="nav navbar-right panel_toolbox">
								<a href="add_item.php" class="btn btn-primary btn-md"><i class="fa fa-plus-square"></i> Add New Item </a>
							</ul>
							<div class="clearfix"></div>
						  </div>
						  <div class="x_content">
						    <?php
							if ($tab == 1) {
								?>
								<table id="datatable-fixed-header" class="table table-striped table-bordered jambo_table">
								  <thead>
									<tr>
									  <th width="09%">Type</th>
									  <th width="18%">Description</th>
									  <th width="07%">Prop #</th>
									  <th width="07%">PAR</th>
									  <th width="07%">ICS#</th>
									  <th width="07%">Brand<br />Model</th>
									  <th width="07%">Serial#</th>
									  <th width="09%">Supplier</th>
									  <th width="07%">Acquired</th>
									  <th width="10%">Assigned To</th>
									  <th width="07%">Value</th>
									  <th width="05%"></th>
									</tr>
								  </thead>
								  <tbody>
									<?php
									if ($cid == 0) {
										$sql = "SELECT i.iid, c.cname, i.idesc, i.property_num, i.property_ack_num, 
													i.ics, i.brand_name, i.model_name, i.serial, s.company,
													DATE_FORMAT(i.acquired_date, '%m/%d/%y'), e.lname, i.acquired_value
												FROM inventory i 
													INNER JOIN categories c ON i.cid = c.cid
													LEFT JOIN suppliers s ON i.sid = s.sid
													LEFT JOIN employees e ON i.eid = e.eid
												ORDER BY c.cname, i.idesc";
									} else {
										$sql = "SELECT i.iid, c.cname, i.idesc, i.property_num, i.property_ack_num, 
													i.ics, i.brand_name, i.model_name, i.serial, s.company,
													DATE_FORMAT(i.acquired_date, '%m/%d/%y'), e.lname, i.acquired_value
												FROM inventory i 
													INNER JOIN categories c ON i.cid = c.cid
													LEFT JOIN suppliers s ON i.sid = s.sid
													LEFT JOIN employees e ON i.eid = e.eid
												WHERE i.cid = '$cid'
												ORDER BY c.cname, i.idesc";
									}
									$res = query($sql);

									while ($row = fetch_array($res)) {
										?>
										<tr>
										  <td align="left"><?php echo $row[1] ?></td>
										  <td align="left"><a href="item.php?iid=<?php echo $row[0] ?>"><?php echo $row[2] ?></a></td>
										  <td align="left"><?php echo $row[3] ?></td>
										  <td align="left"><?php echo $row[4] ?></td>
										  <td align="left"><?php echo $row[5] ?></td>
										  <td align="left"><?php echo $row[6].'<br />'.$row[7] ?></td>
										  <td align="left"><?php echo $row[8] ?></td>
										  <td align="left"><?php echo $row[9] ?></td>
										  <td align="left"><?php echo $row[10] ?></td>
										  <td align="left"><?php echo $row[11] ?></td>
										  <td align="right"><?php echo number_format($row[12], 2) ?></td>
										  <td align="center"><a href="e_item.php?iid=<?php echo $row[0] ?>" class="btn btn-warning btn-xs"><i class="fa fa-pencil"></i></a></td>
										</tr>
										<?php
									}

									mysqli_free_result($res);
									?>
								  </tbody>
								</table>
								<?php
							} elseif ($tab == 2) {
								?>
								<table id="datatable-fixed-header" class="table table-striped table-bordered jambo_table">
								  <thead>
									<tr>
									  <th width="09%">Type</th>
									  <th width="26%">Description</th>
									  <th width="07%">Prop #</th>
									  <th width="07%">PAR</th>
									  <th width="07%">ICS#</th>
									  <th width="07%">Brand<br />Model</th>
									  <th width="07%">Serial#</th>
									  <th width="09%">Employee</th>
									  <th width="07%">Acquired</th>
									  <th width="07%">Value</th>
									  <th width="07%"></th>
									</tr>
								  </thead>
								  <tbody>
									<?php
									if ($did == 0) {
										$sql = "SELECT i.iid, c.cname, i.idesc, i.property_num, i.property_ack_num, 
													i.ics, i.brand_name, i.model_name, i.serial, e.lname,
													DATE_FORMAT(i.acquired_date, '%m/%d/%y'), i.acquired_value
												FROM inventory i 
													INNER JOIN categories c ON i.cid = c.cid
													LEFT JOIN employees e ON i.eid = e.eid
												WHERE i.eid = 0
												ORDER BY c.cname, i.idesc";
									} else {
										$sql = "SELECT i.iid, c.cname, i.idesc, i.property_num, i.property_ack_num, 
													i.ics, i.brand_name, i.model_name, i.serial, e.lname,
													DATE_FORMAT(i.acquired_date, '%m/%d/%y'), i.acquired_value
												FROM inventory i 
													INNER JOIN categories c ON i.cid = c.cid
													INNER JOIN employees e ON i.eid = e.eid
												WHERE e.did = '$did'
												ORDER BY c.cname, i.idesc";
									}
									$res = query($sql);

									while ($row = fetch_array($res)) {
										?>
										<tr>
										  <td align="center"><?php echo $row[1] ?></td>
										  <td align="center"><a href="item.php?iid=<?php echo $row[0] ?>"><?php echo $row[2] ?></a></td>
										  <td align="center"><?php echo $row[3] ?></td>
										  <td align="center"><?php echo $row[4] ?></td>
										  <td align="center"><?php echo $row[5] ?></td>
										  <td align="center"><?php echo $row[6].'<br />'.$row[7] ?></td>
										  <td align="center"><?php echo $row[8] ?></td>
										  <td align="center"><?php echo $row[9] ?></td>
										  <td align="center"><?php echo $row[10] ?></td>
										  <td align="right"><?php echo number_format($row[11], 2) ?></td>
										  <td align="center"><a href="e_item.php?iid=<?php echo $row[0] ?>" class="btn btn-warning btn-xs"><i class="fa fa-pencil"></i> Edit </a></td>
										</tr>
										<?php
									}

									mysqli_free_result($res);
									?>
								  </tbody>
								</table>
								<?php
							} elseif ($tab == 3) {
								?>
								<table id="datatable-fixed-header" class="table table-striped table-bordered jambo_table">
								  <thead>
									<tr>
									  <th width="09%">Type</th>
									  <th width="26%">Description</th>
									  <th width="07%">Prop #</th>
									  <th width="07%">PAR</th>
									  <th width="07%">ICS#</th>
									  <th width="07%">Brand<br />Model</th>
									  <th width="07%">Serial#</th>
									  <th width="09%">Employee</th>
									  <th width="07%">Acquired</th>
									  <th width="07%">Value</th>
									  <th width="07%"></th>
									</tr>
								  </thead>
								  <tbody>
									<?php
									if ($eid == 0) {
										$sql = "SELECT i.iid, c.cname, i.idesc, i.property_num, i.property_ack_num, 
													i.ics, i.brand_name, i.model_name, i.serial, e.lname,
													DATE_FORMAT(i.acquired_date, '%m/%d/%y'), i.acquired_value
												FROM inventory i 
													INNER JOIN categories c ON i.cid = c.cid
													LEFT JOIN employees e ON i.eid = e.eid
												WHERE i.eid = 0
												ORDER BY c.cname, i.idesc";
									} else {
										$sql = "SELECT i.iid, c.cname, i.idesc, i.property_num, i.property_ack_num, 
													i.ics, i.brand_name, i.model_name, i.serial, e.lname,
													DATE_FORMAT(i.acquired_date, '%m/%d/%y'), i.acquired_value
												FROM inventory i 
													INNER JOIN categories c ON i.cid = c.cid
													INNER JOIN employees e ON i.eid = e.eid
												WHERE e.eid = '$eid'
												ORDER BY c.cname, i.idesc";
									}
									$res = query($sql);

									while ($row = fetch_array($res)) {
										?>
										<tr>
										  <td align="center"><?php echo $row[1] ?></td>
										  <td align="center"><a href="item.php?iid=<?php echo $row[0] ?>"><?php echo $row[2] ?></a></td>
										  <td align="center"><?php echo $row[3] ?></td>
										  <td align="center"><?php echo $row[4] ?></td>
										  <td align="center"><?php echo $row[5] ?></td>
										  <td align="center"><?php echo $row[6].'<br />'.$row[7] ?></td>
										  <td align="center"><?php echo $row[8] ?></td>
										  <td align="center"><?php echo $row[9] ?></td>
										  <td align="center"><?php echo $row[10] ?></td>
										  <td align="right"><?php echo number_format($row[11], 2) ?></td>
										  <td align="center"><a href="e_item.php?iid=<?php echo $row[0] ?>" class="btn btn-warning btn-xs"><i class="fa fa-pencil"></i> Edit </a></td>
										</tr>
										<?php
									}

									mysqli_free_result($res);
									?>
								  </tbody>
								</table>
								<?php
							}
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
