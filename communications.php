<?php

include("db.php");
include("sqli.php");
include("html.php");
define('PAGE', 'communications.php'); 

session_start();

if (isset($_SESSION['user_id'])) {
	if (validate_level(PAGE) == true) {

		// log the activity
		log_user(5, 'communications', 0);
		
		?>
		<!DOCTYPE html>
		<html lang="en">
		  <head>
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
			<!-- Meta, title, CSS, favicons, etc. -->
			<meta charset="utf-8">
			<meta http-equiv="X-UA-Compatible" content="IE=edge">
			<meta name="viewport" content="width=device-width, initial-scale=1">

			<title>OTC - Communications Routing</title>

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
				
				if (isset ($_GET['sortby'])) {
					$sortby = $_GET['sortby'];
				} else {
					$sortby = 0;
				}
				
				if (isset ($_GET['yyyymm'])) {
					$yyyymm = $_GET['yyyymm'];
					$gyear = substr($_GET['yyyymm'], 0, 4);
					$gmonth = substr($_GET['yyyymm'], 4, 2);
				} else {
					$gyear = date('Y');
					$gmonth = date('m');
					$yyyymm = date('Ym');
				}
				
				if (isset ($_GET['tab'])) {
					$tab = $_GET['tab'];
				} else {
					$tab = 0; 
				}
				
				if ($tab == 1) { $direction = 1; 
				} elseif ($tab == 2) { $direction = 2; 
				} elseif ($tab == 3) { $direction = 3; 
				}
				
				if (isset ($_GET['fr_date'])) {
					$fr_date = $_GET['fr_date']; 
				} else {
					// get the monday of the week
					//$sql = "SELECT DATE_ADD(CURDATE(), INTERVAL - WEEKDAY(CURDATE()) DAY)";
					// get last week's monday date
					$sql = "SELECT CURRENT_DATE - INTERVAL DAYOFWEEK(CURRENT_DATE) + 5 DAY";
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
						if ($tab == 4) { echo 'Special Orders';
						} elseif ($tab == 5) { echo 'Office Orders';
						} elseif ($tab == 6) { echo 'Memorandum Orders';
						} elseif ($tab == 7) { echo 'Memorandum Circulars';
						} elseif ($tab == 8) { echo 'Travel Orders';
						} else { echo 'Communications Routing';
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
								window.location.replace('communications.php?tab='+tab+'&fr_date='+FrDate+'&to_date='+ToDate)
							  }
							  function myFunction2(FrDate, ToDate) {
								var FrDate = document.getElementById(FrDate).value;
								var ToDate = document.getElementById(ToDate).value;
								var tab = <?php echo $tab ?>;
								window.location.replace('communications.php?tab='+tab+'&fr_date='+FrDate+'&to_date='+ToDate)
							  }
							</script>
						</div>
					  </div>
					</div>

					<div class="clearfix"></div>

					<div class="row">
					  <div class="col-md-12 col-sm-12 col-xs-12">
						<div class="x_panel">
						  <div class="x_title">
						    <?php
							if ($tab == 3) {
								?><a href="add_internal_communication.php" class="btn btn-primary btn-md" data-toggle="tooltip" data-placement="top" title="Add New Communication"><i class="fa fa-plus-square"></i> Add </a><?php
							} else {
								?><a href="add_communication.php" class="btn btn-primary btn-md" data-toggle="tooltip" data-placement="top" title="Add New Communication"><i class="fa fa-plus-square"></i> Add </a><?php
							}
							?>
							<a href="tcpdf/examples/pdf_communications.php?fr_date=<?php echo $fr_date ?>&to_date=<?php echo $to_date ?>&tab=<?php echo $tab ?>&sortby=<?php echo $sortby ?>" target="new" class="btn btn-info btn-md" data-toggle="tooltip" data-placement="top" title="Print Communications"><i class="glyphicon glyphicon-print"></i> Print </a>

							<ul class="nav navbar-right panel_toolbox">
							  <select class="form-control col-md-7 col-xs-12" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
								<?php
								if ($sortby == 0) {
									?>
									<option value="communications.php?fr_date=<?php echo $fr_date ?>&to_date=<?php echo $to_date ?>&tab=<?php echo $tab ?>&sortby=0" Selected>All Entries</option>
									<option value="communications.php?fr_date=<?php echo $fr_date ?>&to_date=<?php echo $to_date ?>&tab=<?php echo $tab ?>&sortby=1">Own Entries</option>
									<?php
								} elseif ($sortby == 1) {
									?>
									<option value="communications.php?fr_date=<?php echo $fr_date ?>&to_date=<?php echo $to_date ?>&tab=<?php echo $tab ?>&sortby=0">All Entries</option>
									<option value="communications.php?fr_date=<?php echo $fr_date ?>&to_date=<?php echo $to_date ?>&tab=<?php echo $tab ?>&sortby=1" Selected>Own Entries</option>
									<?php
								} else {
									?>
									<option value="communications.php?fr_date=<?php echo $fr_date ?>&to_date=<?php echo $to_date ?>&tab=<?php echo $tab ?>&sortby=0">All Entries</option>
									<option value="communications.php?fr_date=<?php echo $fr_date ?>&to_date=<?php echo $to_date ?>&tab=<?php echo $tab ?>&sortby=1">Own Entries</option>
									<?php
								}
								?>
							  </select>
							</ul>
							<div class="clearfix"></div>
						  </div>
						  <div class="x_content">
							<table id="datatable-fixed-header" class="table table-striped table-bordered jambo_table">
							  <thead>
								<tr>
								  <th width="10%"></th>
								  <th width="10%">Reference</th>
								  <th width="10%">Document Dates</th>
								  <th width="18%">Source</th>
								  <th width="18%">Destination</th>
								  <th width="20%">Subject</th>
								  <th width="14%">Status</th>
								</tr>
							  </thead>
							  <tbody>
								<?php
								if ($tab == 0) {
									if ($sortby == 0) {
										$sql = "SELECT cid, ref_num, DATE_FORMAT(ddate, '%m/%d/%y'), DATE_FORMAT(rdate, '%m/%d/%y'), 
													source_ofc, source_person, source_position, 
													receiver_ofc, receiver_person, receiver_position, subject, direction, user_id
												FROM communications
												WHERE TO_DAYS(rdate) >= TO_DAYS('$fr_date') AND TO_DAYS(rdate) <= TO_DAYS('$to_date')
												ORDER BY rdate DESC";
									} elseif ($sortby == 1) {
										$sql = "SELECT cid, ref_num, DATE_FORMAT(ddate, '%m/%d/%y'), DATE_FORMAT(rdate, '%m/%d/%y'), 
													source_ofc, source_person, source_position, 
													receiver_ofc, receiver_person, receiver_position, subject, direction, user_id
												FROM communications
												WHERE TO_DAYS(rdate) >= TO_DAYS('$fr_date') AND TO_DAYS(rdate) <= TO_DAYS('$to_date') AND user_id = '$_SESSION[user_id]'
												ORDER BY rdate DESC";
									}
								} else {
									if ($sortby == 0) {
										$sql = "SELECT cid, ref_num, DATE_FORMAT(ddate, '%m/%d/%y'), DATE_FORMAT(rdate, '%m/%d/%y'), 
													source_ofc, source_person, source_position, 
													receiver_ofc, receiver_person, receiver_position, subject, direction, user_id
												FROM communications
												WHERE direction = '$direction' AND TO_DAYS(rdate) >= TO_DAYS('$fr_date') AND TO_DAYS(rdate) <= TO_DAYS('$to_date')
												ORDER BY rdate DESC";
									} elseif ($sortby == 1) {
										$sql = "SELECT cid, ref_num, DATE_FORMAT(ddate, '%m/%d/%y'), DATE_FORMAT(rdate, '%m/%d/%y'), 
													source_ofc, source_person, source_position, 
													receiver_ofc, receiver_person, receiver_position, subject, direction, user_id
												FROM communications
												WHERE direction = '$direction' AND TO_DAYS(rdate) >= TO_DAYS('$fr_date') AND TO_DAYS(rdate) <= TO_DAYS('$to_date') AND user_id = '$_SESSION[user_id]'
												ORDER BY rdate DESC";
									}
								}
								$res = query($sql);

								while ($row = fetch_array($res)) {
									// get the last remarks in the routing
									$sql = "SELECT remarks
											FROM routings 
											WHERE cid = '$row[0]'
											ORDER BY rid DESC
											LIMIT 0, 1";
									$stat_res = query($sql); 
									$stat_row = fetch_array($stat_res); 
									?>
									<tr>
									  <td align="center">
										<a href="communication.php?cid=<?php echo $row[0] ?>" class="btn btn-info btn-xs" data-toggle="tooltip" data-placement="top" title="View Details"><i class="fa fa-search"></i></a>
										<?php
										if ($_SESSION['user_id'] == $row[12]) {
											?>
											<a href="e_communication.php?cid=<?php echo $row[0] ?>" class="btn btn-warning btn-xs" data-toggle="tooltip" data-placement="top" title="Update Communication"><i class="fa fa-pencil"></i></a>
											<a href="d_communication.php?cid=<?php echo $row[0] ?>" class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top" title="Delete Communication"><i class="fa fa-eraser"></i></a>
											<?php
										}
										?>
									  </td>
									  <td align="center"><?php 
										if ($row[11] == 1) { echo 'I';
										} elseif ($row[11] == 2) { echo 'O';
										} elseif ($row[11] == 3) { echo 'X'; }
										echo $row[1] ?></td>
									  <td align="left"><?php echo 'Doc Date: '.$row[2].'<br />Received: '.$row[3] ?></td>
									  <td align="left"><?php echo $row[4].'<br /><b>'.$row[5].'</b><br />'.$row[6] ?></td>
									  <td align="left"><?php echo $row[7].'<br /><b>'.$row[8].'</b><br />'.$row[9] ?></td>
									  <td align="left"><?php echo $row[10] ?></td>
									  <td align="left"><?php echo $stat_row[0] ?></td>
									</tr>
									<?php
								}

								mysqli_free_result($res);
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
