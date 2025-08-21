<?php

include("db.php");
include("sqli.php");
include("html.php");
define('PAGE', 'internals.php'); 

session_start();

if (isset($_SESSION['user_id'])) {
	if (validate_level(PAGE) == true) {

		// log the activity
		log_user(5, 'internals', 0);
		
		?>
		<!DOCTYPE html>
		<html lang="en">
		  <head>
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
			<!-- Meta, title, CSS, favicons, etc. -->
			<meta charset="utf-8">
			<meta http-equiv="X-UA-Compatible" content="IE=edge">
			<meta name="viewport" content="width=device-width, initial-scale=1">

			<title>OTC - Internal Communications</title>

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
				
				if (isset ($_GET['tab'])) {
					$tab = $_GET['tab'];
				} else {
					$tab = 0; 
				}
				
				if (isset ($_GET['fr_date'])) {
					$fr_date = $_GET['fr_date']; 
				} else {
					// get the monday of the week
					$sql = "SELECT DATE_ADD(CURDATE(), INTERVAL - WEEKDAY(CURDATE()) DAY)";
					$fr_res = query($sql); 
					$fr_row = fetch_array($fr_res); 
					$fr_date = $fr_row[0]; 
				}
				
				if (isset ($_GET['to_date'])) {
					$to_date = $_GET['to_date']; 
				} else {
					// get the friday of the week
					$sql = "SELECT DATE_ADD(DATE_ADD(CURDATE(), INTERVAL - WEEKDAY(CURDATE()) DAY), INTERVAL 4 DAY)";
					$to_res = query($sql); 
					$to_row = fetch_array($to_res); 
					$to_date = $to_row[0]; 
				}
				
				?>

				<!-- page content -->
				<div class="right_col" role="main">
				  <div class="">
					<div class="page-title">
					  <div class="title_left">
						<h3>
						<?php
						if ($tab == 1) { echo 'Special Orders';
						} elseif ($tab == 2) { echo 'Office Orders';
						} elseif ($tab == 3) { echo 'Memorandum Orders';
						} elseif ($tab == 4) { echo 'Memorandum Circulars';
						} elseif ($tab == 5) { echo 'Travel Orders';
						}
						?>
						</h3>
					  </div>

					  <div class="title_right">
						<div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
							From: <input type="date" id="fr_date" value="<?php echo $fr_date ?>" onchange="myFunction('fr_date', 'to_date');">
							To: <input type="date" id="to_date" value="<?php echo $to_date ?>" onchange="myFunction2('fr_date', 'to_date');">
							<script>
							  function myFunction(FrDate, ToDate) {
								var FrDate = document.getElementById(FrDate).value;
								var ToDate = document.getElementById(ToDate).value;
								var tab = <?php echo $tab ?>;
								window.location.replace('internals.php?tab='+tab+'&fr_date='+FrDate+'&to_date='+ToDate)
							  }
							  function myFunction2(FrDate, ToDate) {
								var FrDate = document.getElementById(FrDate).value;
								var ToDate = document.getElementById(ToDate).value;
								var tab = <?php echo $tab ?>;
								window.location.replace('internals.php?tab='+tab+'&fr_date='+FrDate+'&to_date='+ToDate)
							  }
							</script>
						
						</div>
					  </div>
					</div>

					<div class="clearfix"></div>

					<div class="row">
					  <?php
					  if (isset($_GET['added_special_order'])) {
						  if ($_GET['added_special_order'] == 1) {
							  ?>
								<div class="alert alert-success">
								  <strong>Success!</strong> Special Order has been added into the database.
								</div>
							  <?php
						  }
					  }
					  if (isset($_GET['updated_special_order'])) {
						  if ($_GET['updated_special_order'] == 1) {
							  ?>
								<div class="alert alert-warning">
								  <strong>Success!</strong> Special Order has been updated.
								</div>
							  <?php
						  }
					  }
					  if (isset($_GET['deleted_special_order'])) {
						  if ($_GET['deleted_special_order'] == 1) {
							  ?>
								<div class="alert alert-danger">
								  <strong>Success!</strong> Special Order has been deleted.
								</div>
							  <?php
						  }
					  }
					  
					  if (isset($_GET['added_office_order'])) {
						  if ($_GET['added_office_order'] == 1) {
							  ?>
								<div class="alert alert-success">
								  <strong>Success!</strong> Office Order has been added into the database.
								</div>
							  <?php
						  }
					  }
					  if (isset($_GET['updated_office_order'])) {
						  if ($_GET['updated_office_order'] == 1) {
							  ?>
								<div class="alert alert-warning">
								  <strong>Success!</strong> Office Order has been updated.
								</div>
							  <?php
						  }
					  }
					  if (isset($_GET['deleted_office_order'])) {
						  if ($_GET['deleted_office_order'] == 1) {
							  ?>
								<div class="alert alert-danger">
								  <strong>Success!</strong> Office Order has been deleted.
								</div>
							  <?php
						  }
					  }
					  
					  if (isset($_GET['added_memo_order'])) {
						  if ($_GET['added_memo_order'] == 1) {
							  ?>
								<div class="alert alert-success">
								  <strong>Success!</strong> Memorandum Order has been added into the database.
								</div>
							  <?php
						  }
					  }
					  if (isset($_GET['updated_memo_order'])) {
						  if ($_GET['updated_memo_order'] == 1) {
							  ?>
								<div class="alert alert-warning">
								  <strong>Success!</strong> Memorandum Order has been updated.
								</div>
							  <?php
						  }
					  }
					  if (isset($_GET['deleted_memo_order'])) {
						  if ($_GET['deleted_memo_order'] == 1) {
							  ?>
								<div class="alert alert-danger">
								  <strong>Success!</strong> Memorandum Order has been deleted.
								</div>
							  <?php
						  }
					  }
					  
					  if (isset($_GET['added_memo_circular'])) {
						  if ($_GET['added_memo_circular'] == 1) {
							  ?>
								<div class="alert alert-success">
								  <strong>Success!</strong> Memorandum Circular has been added into the database.
								</div>
							  <?php
						  }
					  }
					  if (isset($_GET['updated_memo_circular'])) {
						  if ($_GET['updated_memo_circular'] == 1) {
							  ?>
								<div class="alert alert-warning">
								  <strong>Success!</strong> Memorandum Circular has been updated.
								</div>
							  <?php
						  }
					  }
					  if (isset($_GET['deleted_memo_circular'])) {
						  if ($_GET['deleted_memo_circular'] == 1) {
							  ?>
								<div class="alert alert-danger">
								  <strong>Success!</strong> Memorandum Circular has been deleted.
								</div>
							  <?php
						  }
					  }
					  
					  if (isset($_GET['added_travel_order'])) {
						  if ($_GET['added_travel_order'] == 1) {
							  ?>
								<div class="alert alert-success">
								  <strong>Success!</strong> Travel Order has been added into the database.
								</div>
							  <?php
						  }
					  }
					  if (isset($_GET['updated_travel_order'])) {
						  if ($_GET['updated_travel_order'] == 1) {
							  ?>
								<div class="alert alert-warning">
								  <strong>Success!</strong> Travel Order has been updated.
								</div>
							  <?php
						  }
					  }
					  if (isset($_GET['deleted_travel_order'])) {
						  if ($_GET['deleted_travel_order'] == 1) {
							  ?>
								<div class="alert alert-danger">
								  <strong>Success!</strong> Travel Order has been deleted.
								</div>
							  <?php
						  }
					  }
					  
					  ?>
					
					  <div class="col-md-12 col-sm-12 col-xs-12">
						<div class="x_panel">
						  <div class="x_title">
						    <?php
							if ($tab == 1) {
								?>
								<a href="add_special_order.php" class="btn btn-primary btn-md" data-toggle="tooltip" data-placement="top" title="Add New Special Order"><i class="fa fa-plus-square"></i> Add </a>
								<?php
							} elseif ($tab == 2) {
								?>
								<a href="add_office_order.php" class="btn btn-primary btn-md" data-toggle="tooltip" data-placement="top" title="Add New Office Order"><i class="fa fa-plus-square"></i> Add </a>
								<?php
							} elseif ($tab == 3) {
								?>
								<a href="add_memo_order.php" class="btn btn-primary btn-md" data-toggle="tooltip" data-placement="top" title="Add New Memorandum Order"><i class="fa fa-plus-square"></i> Add </a>
								<?php
							} elseif ($tab == 4) {
								?>
								<a href="add_memo_circular.php" class="btn btn-primary btn-md" data-toggle="tooltip" data-placement="top" title="Add New Memorandum Circular"><i class="fa fa-plus-square"></i> Add </a>
								<?php
							} elseif ($tab == 5) {
								?>
								<a href="add_travel_order.php" class="btn btn-primary btn-md" data-toggle="tooltip" data-placement="top" title="Add New Travel Order"><i class="fa fa-plus-square"></i> Add </a>
								<?php
							}
							?>
							<a href="tcpdf/examples/pdf_internals.php?fr_date=<?php echo $fr_date ?>&to_date=<?php echo $to_date ?>&tab=<?php echo $tab ?>" target="new" class="btn btn-info btn-md" data-toggle="tooltip" data-placement="top" title="Print Communications"><i class="glyphicon glyphicon-print"></i> Print </a>

							<ul class="nav navbar-right panel_toolbox">
							  <select class="form-control col-md-7 col-xs-12" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
							    <?php
								if ($tab == 1) {
									?>
									<option value="internals.php?fr_date=<?php echo $fr_date ?>&to_date=<?php echo $to_date ?>&tab=1" Selected>Special Orders</option>
									<option value="internals.php?fr_date=<?php echo $fr_date ?>&to_date=<?php echo $to_date ?>&tab=2">Office Orders</option>
									<option value="internals.php?fr_date=<?php echo $fr_date ?>&to_date=<?php echo $to_date ?>&tab=3">Memorandum Orders</option>
									<option value="internals.php?fr_date=<?php echo $fr_date ?>&to_date=<?php echo $to_date ?>&tab=4">Memorandum Circulars</option>
									<option value="internals.php?fr_date=<?php echo $fr_date ?>&to_date=<?php echo $to_date ?>&tab=5">Travel Orders</option>
									<?php
								} elseif ($tab == 2) {
									?>
									<option value="internals.php?fr_date=<?php echo $fr_date ?>&to_date=<?php echo $to_date ?>&tab=1">Special Orders</option>
									<option value="internals.php?fr_date=<?php echo $fr_date ?>&to_date=<?php echo $to_date ?>&tab=2" Selected>Office Orders</option>
									<option value="internals.php?fr_date=<?php echo $fr_date ?>&to_date=<?php echo $to_date ?>&tab=3">Memorandum Orders</option>
									<option value="internals.php?fr_date=<?php echo $fr_date ?>&to_date=<?php echo $to_date ?>&tab=4">Memorandum Circulars</option>
									<option value="internals.php?fr_date=<?php echo $fr_date ?>&to_date=<?php echo $to_date ?>&tab=5">Travel Orders</option>
									<?php
								} elseif ($tab == 3) {
									?>
									<option value="internals.php?fr_date=<?php echo $fr_date ?>&to_date=<?php echo $to_date ?>&tab=1">Special Orders</option>
									<option value="internals.php?fr_date=<?php echo $fr_date ?>&to_date=<?php echo $to_date ?>&tab=2">Office Orders</option>
									<option value="internals.php?fr_date=<?php echo $fr_date ?>&to_date=<?php echo $to_date ?>&tab=3" Selected>Memorandum Orders</option>
									<option value="internals.php?fr_date=<?php echo $fr_date ?>&to_date=<?php echo $to_date ?>&tab=4">Memorandum Circulars</option>
									<option value="internals.php?fr_date=<?php echo $fr_date ?>&to_date=<?php echo $to_date ?>&tab=5">Travel Orders</option>
									<?php
								} elseif ($tab == 4) {
									?>
									<option value="internals.php?fr_date=<?php echo $fr_date ?>&to_date=<?php echo $to_date ?>&tab=1">Special Orders</option>
									<option value="internals.php?fr_date=<?php echo $fr_date ?>&to_date=<?php echo $to_date ?>&tab=2">Office Orders</option>
									<option value="internals.php?fr_date=<?php echo $fr_date ?>&to_date=<?php echo $to_date ?>&tab=3">Memorandum Orders</option>
									<option value="internals.php?fr_date=<?php echo $fr_date ?>&to_date=<?php echo $to_date ?>&tab=4" Selected>Memorandum Circulars</option>
									<option value="internals.php?fr_date=<?php echo $fr_date ?>&to_date=<?php echo $to_date ?>&tab=5">Travel Orders</option>
									<?php
								} elseif ($tab == 5) {
									?>
									<option value="internals.php?fr_date=<?php echo $fr_date ?>&to_date=<?php echo $to_date ?>&tab=1">Special Orders</option>
									<option value="internals.php?fr_date=<?php echo $fr_date ?>&to_date=<?php echo $to_date ?>&tab=2">Office Orders</option>
									<option value="internals.php?fr_date=<?php echo $fr_date ?>&to_date=<?php echo $to_date ?>&tab=3">Memorandum Orders</option>
									<option value="internals.php?fr_date=<?php echo $fr_date ?>&to_date=<?php echo $to_date ?>&tab=4">Memorandum Circulars</option>
									<option value="internals.php?fr_date=<?php echo $fr_date ?>&to_date=<?php echo $to_date ?>&tab=5" Selected>Travel Orders</option>
									<?php
								} else {
									?>
									<option value="internals.php?fr_date=<?php echo $fr_date ?>&to_date=<?php echo $to_date ?>&tab=1">Special Orders</option>
									<option value="internals.php?fr_date=<?php echo $fr_date ?>&to_date=<?php echo $to_date ?>&tab=2">Office Orders</option>
									<option value="internals.php?fr_date=<?php echo $fr_date ?>&to_date=<?php echo $to_date ?>&tab=3">Memorandum Orders</option>
									<option value="internals.php?fr_date=<?php echo $fr_date ?>&to_date=<?php echo $to_date ?>&tab=4">Memorandum Circulars</option>
									<option value="internals.php?fr_date=<?php echo $fr_date ?>&to_date=<?php echo $to_date ?>&tab=5">Travel Orders</option>
									<?php
								}
								?>
							  </select>
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
									  <th width="08%"></th>
									  <th width="08%">Date</th>
									  <th width="08%">Control #</th>
									  <th width="23%">Employee/s</th>
									  <th width="25%">Subject</th>
									  <th width="12%">Encoder</th>
									  <th width="12%">Date Encoded</th>
									</tr>
								  </thead>
								  <tbody>
								    <?php
									$sql = "SELECT so.sid, DATE_FORMAT(so.sdate, '%m/%d/%y'), so.snum, 
												so.enames, so.subject, CONCAT(u.lname,', ',u.fname), 
												DATE_FORMAT(so.user_dtime, '%m/%d/%y %h:%i %p')
											FROM internals_special_orders so 
												INNER JOIN users u ON so.user_id = u.user_id
											WHERE TO_DAYS(so.sdate) >= TO_DAYS('$fr_date') AND TO_DAYS(so.sdate) <= TO_DAYS('$to_date')
											ORDER BY so.sdate";
									$res = query($sql); 
									while ($row = fetch_array($res)) {
										?>
										<tr>
											<td align="center">
												<a href="e_special_order.php?sid=<?php echo $row[0] ?>" class="btn btn-warning btn-xs" data-toggle="tooltip" data-placement="top" title="Update Special Order"><i class="fa fa-pencil"></i></a>
												<a href="d_special_order.php?sid=<?php echo $row[0] ?>" class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top" title="Delete Special Order"><i class="fa fa-eraser"></i></a>
											</td>
											<td align="center"><?php echo $row[1] ?></td>
											<td align="center"><?php echo $row[2] ?></td>
											<td align="left"><?php echo $row[3] ?></td>
											<td align="left"><?php echo $row[4] ?></td>
											<td align="center"><?php echo $row[5] ?></td>
											<td align="center"><?php echo $row[6] ?></td>
										</tr>
										<?php
									}
									free_result($res); 
									?>
								  </tbody>
								</table>
								<?php
							} elseif ($tab == 2) {
								?>
								<table id="datatable-fixed-header" class="table table-striped table-bordered jambo_table">
								  <thead>
									<tr>
									  <th width="08%"></th>
									  <th width="08%">Date</th>
									  <th width="08%">Control #</th>
									  <th width="23%">Employee/s</th>
									  <th width="25%">Subject</th>
									  <th width="12%">Encoder</th>
									  <th width="12%">Date Encoded</th>
									</tr>
								  </thead>
								  <tbody>
								    <?php
									$sql = "SELECT oo.oid, DATE_FORMAT(oo.odate, '%m/%d/%y'), oo.onum, 
												oo.enames, oo.subject, CONCAT(u.lname,', ',u.fname), 
												DATE_FORMAT(oo.user_dtime, '%m/%d/%y %h:%i %p')
											FROM internals_office_orders oo 
												INNER JOIN users u ON oo.user_id = u.user_id
											WHERE TO_DAYS(oo.odate) >= TO_DAYS('$fr_date') AND TO_DAYS(oo.odate) <= TO_DAYS('$to_date')
											ORDER BY oo.odate";
									$res = query($sql); 
									while ($row = fetch_array($res)) {
										?>
										<tr>
											<td align="center">
												<a href="e_office_order.php?oid=<?php echo $row[0] ?>" class="btn btn-warning btn-xs" data-toggle="tooltip" data-placement="top" title="Update Office Order"><i class="fa fa-pencil"></i></a>
												<a href="d_office_order.php?oid=<?php echo $row[0] ?>" class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top" title="Delete Office Order"><i class="fa fa-eraser"></i></a>
											</td>
											<td align="center"><?php echo $row[1] ?></td>
											<td align="center"><?php echo $row[2] ?></td>
											<td align="left"><?php echo $row[3] ?></td>
											<td align="left"><?php echo $row[4] ?></td>
											<td align="center"><?php echo $row[5] ?></td>
											<td align="center"><?php echo $row[6] ?></td>
										</tr>
										<?php
									}
									free_result($res); 
									?>
								  </tbody>
								</table>
								<?php
							} elseif ($tab == 3) {
								?>
								<table id="datatable-fixed-header" class="table table-striped table-bordered jambo_table">
								  <thead>
									<tr>
									  <th width="10%"></th>
									  <th width="08%">Issued</th>
									  <th width="08%">Control #</th>
									  <th width="12%">Travel Date</th>
									  <th width="18%">Employee</th>
									  <th width="22%">Subject</th>
									  <th width="08%">Encoder</th>
									  <th width="10%">Encoded</th>
									</tr>
								  </thead>
								  <tbody>
								    <?php
									$sql = "SELECT mo.oid, DATE_FORMAT(mo.odate, '%m/%d/%y'), mo.onum, 
												DATE_FORMAT(mo.fr_date, '%m/%d/%y'), DATE_FORMAT(mo.to_date, '%m/%d/%y'), 
												mo.subject, u.lname,
												DATE_FORMAT(mo.user_dtime, '%m/%d/%y %H:%i')
											FROM internals_memo_orders mo 
												INNER JOIN users u ON mo.user_id = u.user_id
											WHERE TO_DAYS(mo.odate) >= TO_DAYS('$fr_date') AND TO_DAYS(mo.odate) <= TO_DAYS('$to_date')
											ORDER BY mo.odate";
									$res = query($sql); 
									while ($row = fetch_array($res)) {
										?>
										<tr>
											<td align="center">
												<a href="add_mo_employee.php?oid=<?php echo $row[0] ?>" class="btn btn-primary btn-xs" data-toggle="tooltip" data-placement="top" title="Add Another Employee"><i class="fa fa-plus-square"></i></a>
												<a href="e_memo_order.php?oid=<?php echo $row[0] ?>" class="btn btn-warning btn-xs" data-toggle="tooltip" data-placement="top" title="Update Memorandum Order"><i class="fa fa-pencil"></i></a>
												<a href="d_memo_order.php?oid=<?php echo $row[0] ?>" class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top" title="Delete Memorandum Order"><i class="fa fa-eraser"></i></a>
											</td>
											<td align="center"><?php echo $row[1] ?></td>
											<td align="center"><?php echo $row[2] ?></td>
											<td align="left"><?php echo $row[3].' - '.$row[4] ?></td>
											<td align="left">
												<table width="100%">
													<?php
													$sql = "SELECT m.me_id, CONCAT(e.lname,', ',e.fname)
															FROM internals_memo_orders_employees m
																INNER JOIN employees e ON m.eid = e.eid
															WHERE m.oid = '$row[0]'";
													$rres = query($sql);
													while ($rrow = fetch_array($rres)) {
														?>
														<tr>
															<td width="20%" align="left">
																<a href="d_mo_employee.php?me_id=<?php echo $rrow[0] ?>" class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top" title="Remove Employee"><i class="fa fa-eraser"></i></a>
															</td>
															<td width="80%" align="left"><?php echo $rrow[1] ?></td>
														</tr>
														<?php
													}
													free_result($rres); 
													?>
												</table>
											</td>
											<td align="left"><?php echo $row[5] ?></td>
											<td align="center"><?php echo $row[6] ?></td>
											<td align="center"><?php echo $row[7] ?></td>
										</tr>
										<?php
									}
									free_result($res); 
									?>
								  </tbody>
								</table>
								<?php
							} elseif ($tab == 4) {
								?>
								<table id="datatable-fixed-header" class="table table-striped table-bordered jambo_table">
								  <thead>
									<tr>
									  <th width="08%"></th>
									  <th width="08%">Date</th>
									  <th width="08%">Control #</th>
									  <th width="23%">Addressee</th>
									  <th width="25%">Subject</th>
									  <th width="12%">Encoder</th>
									  <th width="12%">Date Encoded</th>
									</tr>
								  </thead>
								  <tbody>
								    <?php
									$sql = "SELECT mc.cid, DATE_FORMAT(mc.cdate, '%m/%d/%y'), mc.cnum, 
												mc.addressee, mc.subject, CONCAT(u.lname,', ',u.fname), 
												DATE_FORMAT(mc.user_dtime, '%m/%d/%y %h:%i %p')
											FROM internals_memo_circulars mc 
												INNER JOIN users u ON mc.user_id = u.user_id
											WHERE TO_DAYS(mc.cdate) >= TO_DAYS('$fr_date') AND TO_DAYS(mc.cdate) <= TO_DAYS('$to_date')
											ORDER BY mc.cdate";
									$res = query($sql); 
									while ($row = fetch_array($res)) {
										?>
										<tr>
											<td align="center">
												<a href="e_memo_circular.php?cid=<?php echo $row[0] ?>" class="btn btn-warning btn-xs" data-toggle="tooltip" data-placement="top" title="Update Memorandum Circular"><i class="fa fa-pencil"></i></a>
												<a href="d_memo_circular.php?cid=<?php echo $row[0] ?>" class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top" title="Delete Memorandum Circular"><i class="fa fa-eraser"></i></a>
											</td>
											<td align="center"><?php echo $row[1] ?></td>
											<td align="center"><?php echo $row[2] ?></td>
											<td align="left"><?php echo $row[3] ?></td>
											<td align="left"><?php echo $row[4] ?></td>
											<td align="center"><?php echo $row[5] ?></td>
											<td align="center"><?php echo $row[6] ?></td>
										</tr>
										<?php
									}
									free_result($res); 
									?>
								  </tbody>
								</table>
								<?php
							} elseif ($tab == 5) {
								?>
								<table id="datatable-fixed-header" class="table table-striped table-bordered jambo_table">
								  <thead>
									<tr>
									  <th width="08%"></th>
									  <th width="08%">Control #</th>
									  <th width="16%">Employee</th>
									  <th width="10%">Date</th>
									  <th width="18%">Destination</th>
									  <th width="20%">Purpose</th>
									  <th width="10%">Encoder</th>
									  <th width="10%">Date Encoded</th>
									</tr>
								  </thead>
								  <tbody>
								    <?php
									$sql = "SELECT ito.tid, ito.tnum, DATE_FORMAT(ito.fr_date, '%m/%d/%y'), DATE_FORMAT(ito.to_date, '%m/%d/%y'), ito.destination, ito.purpose,
												CONCAT(u.lname,', ',u.fname), DATE_FORMAT(ito.user_dtime, '%m/%d/%y %h:%i %p')
											FROM internals_travel_orders ito
												INNER JOIN users u ON ito.user_id = u.user_id
											WHERE TO_DAYS(ito.fr_date) >= TO_DAYS('$fr_date') AND TO_DAYS(ito.fr_date) <= TO_DAYS('$to_date')
											ORDER BY ito.fr_date";
									$res = query($sql); 
									while ($row = fetch_array($res)) {
										?>
										<tr>
											<td align="center">
												<a href="add_to_employee.php?tid=<?php echo $row[0] ?>" class="btn btn-primary btn-xs" data-toggle="tooltip" data-placement="top" title="Add Another Employee"><i class="fa fa-plus-square"></i></a>
												<a href="e_travel_order.php?tid=<?php echo $row[0] ?>" class="btn btn-warning btn-xs" data-toggle="tooltip" data-placement="top" title="Update Travel Order"><i class="fa fa-pencil"></i></a>
												<a href="d_travel_order.php?tid=<?php echo $row[0] ?>" class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top" title="Delete Travel Order"><i class="fa fa-eraser"></i></a>
											</td>
											<td align="center"><?php echo $row[1] ?></td>
											<td align="left">
												<table width="100%">
													<?php
													$sql = "SELECT t.te_id, CONCAT(e.lname,', ',e.fname)
															FROM internals_travel_orders_employees t
																INNER JOIN employees e ON t.eid = e.eid
															WHERE t.tid = '$row[0]'";
													$rres = query($sql);
													while ($rrow = fetch_array($rres)) {
														?>
														<tr>
															<td width="20%" align="left">
																<a href="d_to_employee.php?te_id=<?php echo $rrow[0] ?>" class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top" title="Remove Employee"><i class="fa fa-eraser"></i></a>
															</td>
															<td width="80%" align="left"><?php echo $rrow[1] ?></td>
														</tr>
														<?php
													}
													free_result($rres); 
													?>
												</table>
											
											<?php
												?></td>
											<td align="center"><?php echo $row[2].' - '.$row[3] ?></td>
											<td align="left"><?php echo $row[4] ?></td>
											<td align="left"><?php echo $row[5] ?></td>
											<td align="left"><?php echo $row[6] ?></td>
											<td align="center"><?php echo $row[7] ?></td>
										</tr>
										<?php
									}
									free_result($res); 
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
