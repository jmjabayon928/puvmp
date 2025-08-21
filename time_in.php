<?php

include("db.php");
include("sqli.php");
include("html.php");

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>OTC | Attendance</title>

    <!-- Bootstrap -->
    <link href="vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="build/css/custom.min.css" rel="stylesheet">
	<script>
	function ValidateECode(ecode) {
		var ecode = document.getElementById("ecode").value;
		//alert("Employee Code: "+ecode);
		window.location.replace('validate_attendance.php?ecode='+ecode);
		
	}
	</script>
  </head>

  <body onload="document.AttendanceForm.ecode.focus();" style="background: #F7F7F7">
    <div class="container body">
      <div class="main_container">
        <!-- page content -->
        <div class="col-md-12">
          <div class="col-middle">
		  
			  <div class="col-md-4">
				  <div class="login_wrapper">
					<div class="animate form login_form">
					  <div width="100%">
					  <?php
					  if (isset ($_GET['mismatched'])) {
						  if ($_GET['mismatched'] == 1) {
							  // show error in epass
							  ?>
							  <div class="alert alert-danger alert-dismissible fade in" role="alert">
								<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
								<strong>Error: </strong> Employee password does not exist.
							  </div>
							  <?php
						  } elseif ($_GET['mismatched'] == 2) {
							  // show error in epass
							  ?>
							  <div class="alert alert-danger alert-dismissible fade in" role="alert">
								<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
								<strong>Error: </strong> Employee password does not exist.
							  </div>
							  <?php
						  }
					  }
					  
					  if (isset ($_GET['success'])) {
						  $success = $_GET['success'];
						  $eid = $_GET['eid'];
						  // get the picture
						  $sql = "SELECT e.picture, DATE_FORMAT(a.am_in, '%h:%i %p'),
								       DATE_FORMAT(a.am_out, '%h:%i %p'),
									   DATE_FORMAT(a.pm_in, '%h:%i %p'),
									   DATE_FORMAT(a.pm_out, '%h:%i %p'),
									   a.tardiness, a.undertime, a.status, a.remarks
								  FROM employees e INNER JOIN attendance a ON e.eid = a.eid 
								  WHERE e.eid = '$eid' AND TO_DAYS(a.adate) = TO_DAYS(CURDATE())";
						  $res = query($sql); 
						  $row = fetch_array($res); 
						  // show message and picture
						  ?>
						  <div class="col align-self-center">
						    <table border="1" width="100%" style="font-size: medium;">
								<tr>
									<td colspan="4" align="center">Daily Record Details</td>
								</tr>
								<tr>
									<td width="25%" align="center">AM In</td>
									<td width="25%" align="center">AM Out</td>
									<td width="25%" align="center">PM In</td>
									<td width="25%" align="center">PM Out</td>
								</tr>
								<tr>
									<td align="center"><?php echo $row[1] == '12:00 AM' ? '' : $row[1] ?></td>
									<td align="center"><?php echo $row[2] == '12:00 AM' ? '' : $row[2] ?></td>
									<td align="center"><?php echo $row[3] == '12:00 AM' ? '' : $row[3] ?></td>
									<td align="center"><?php echo $row[4] == '12:00 AM' ? '' : $row[4] ?></td>
								</tr>
							</table>
							<img src="<?php echo $row[0] ?>" class="img-thumbnail">
						  </div>
						  <?php
					  }
					  
					  if (isset ($_GET['wrong_timein'])) {
						  $wrong_timein = $_GET['wrong_timein'];
						  // show message
						  ?>
						  <div class="alert alert-danger alert-dismissible fade in" role="alert">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
							<strong>Error: </strong>
							<?php
							if ($wrong_timein == 1) { echo 'You have timed in the whole day';
							} elseif ($wrong_timein == 2) { echo 'You already timed in in the morning';
							} elseif ($wrong_timein == 3) { echo 'You already timed in in the afternoon';
							} elseif ($wrong_timein == 4) { echo 'Invalid PM In. Try again after a few seconds';
							} elseif ($wrong_timein == 5) { echo 'You cannot time in in the afternoon yet. Try again later';
							} elseif ($wrong_timein == 6) { echo 'You cannot time out in the afternoon yet. Try again later';
							
							} elseif ($wrong_timein == 7) { echo 'You are not allowed to time in/out outside the office network';
							} elseif ($wrong_timein == 8) { echo 'You cannot time in in the morning at 12:00pm or later. Time in in the afternoon instead.';
							} elseif ($wrong_timein == 9) { echo 'You may time in in the afternoon at 12:00pm.';
							} elseif ($wrong_timein == 10) { echo 'You cannot time out in the morning at 1:00pm and onwards.';
							}
							?>
						  </div>
						  <?php
					  }
					  ?>
					  </div>
					  <section class="login_content">
						<script src="https://code.jquery.com/jquery-2.1.1.min.js" type="text/javascript"></script>
						<script>
						$(document).ready(function(){
							setInterval(function(){
								$("#clockbox").load('datetime.php')
							}, 2500);
						});
						</script>
						<div id="clockbox" style="font:20pt verdana; color:#000;"></div>
							
							
						<form name="AttendanceForm" method="post" action="validate_attendance.php">
						  <h1>Attendance</h1>
						  <div>
							<input type="password" id="ecode" name="ecode" onChange="ValidateECode(this.value)" class="form-control" placeholder="Employee Code or password" required="" />
						  </div>

						  <div class="clearfix"></div>

							<div class="clearfix"></div>
							<br />
							<div class="clearfix"></div>
							<br />
							<div style="font:16pt verdana; color:#000;">Scan your ID<br /> or type your password then press the enter key</div>
							
							<div class="clearfix"></div>
							<br />
							<div class="clearfix"></div>
							<br />
							<div class="clearfix"></div>
							<br />
							<div class="clearfix"></div>
							<br />
							<div class="clearfix"></div>
							<br />
							<a href="login.php" class="to_register"> Log In </a>

						  </div>
						</form>
					  </section>
					</div>
				  </div>
			  </div>
			  
			  <div class="col-md-8">
				<div class="x_panel">
				  <div class="x_title">
					<h2>Attendance</h2>
					<ul class="nav navbar-right panel_toolbox">
					  <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
					  <li><a class="close-link"><i class="fa fa-close"></i></a></li>
					</ul>
					<div class="clearfix"></div>
				  </div>
				  <div class="x_content">

					<table class="table table-bordered jambo_table">
					  <thead>
						<tr>
							<th rowspan="2" align="center" width="20%">Employee</th>
							<th colspan="2" align="center">A.M.</th>
							<th colspan="2" align="center">P.M.</th>
							<th rowspan="2" align="center" width="07%">Tardiness<br />(Minutes)</th>
							<th rowspan="2" align="center" width="07%">Undertime<br />(Minutes)</th>
							<th rowspan="2" align="center" width="10%">Status</th>
							<th rowspan="2" align="center" width="24%">Remarks</th>
						</tr>
						<tr>
							<th align="center" width="08%">In</th>
							<th align="center" width="08%">Out</th>
							<th align="center" width="08%">In</th>
							<th align="center" width="08%">Out</th>
						</tr>
					  </thead>
					  <tbody>
						<?php
						$sql = "SELECT a.aid, CONCAT(e.lname,', ',e.fname), 
									DATE_FORMAT(a.am_in, '%h:%i %p'), 
									DATE_FORMAT(a.am_out, '%h:%i %p'), 
									DATE_FORMAT(a.pm_in, '%h:%i %p'), 
									DATE_FORMAT(a.pm_out, '%h:%i %p'),
									tardiness, undertime, status, remarks
								FROM attendance a INNER JOIN employees e ON a.eid = e.eid
								WHERE TO_DAYS(CURDATE()) = TO_DAYS(a.adate)
								ORDER BY a.am_in DESC";
						$res = query($sql); 
						while ($row = fetch_array($res)) {
							?>
							<tr>
								<td align="left"><?PHP echo $row[1] ?></td>
								<td align="center"><?PHP echo $row[2] == '12:00 AM' ? '' : $row[2] ?></td>
								<td align="center"><?PHP echo $row[3] == '12:00 AM' ? '' : $row[3] ?></td>
								<td align="center"><?PHP echo $row[4] == '12:00 AM' ? '' : $row[4] ?></td>
								<td align="center"><?PHP echo $row[5] == '12:00 AM' ? '' : $row[5] ?></td>
								<td align="center"><?PHP echo $row[6] ?></td>
								<td align="center"><?PHP echo $row[7] ?></td>
								<td align="left"><?PHP 
									if ($row[8] == -1) { echo 'For Checking';
									} elseif ($row[8] == 1) { echo 'Present';
									} elseif ($row[8] == 2) { echo 'Unauthorized Absent';
									} elseif ($row[8] == 3) { echo 'Vacation Leave';
									} elseif ($row[8] == 4) { echo 'Sick Leave';
									} elseif ($row[8] == 5) { echo 'Forced Leave';
									} elseif ($row[8] == 6) { echo 'Special Leave';
									} elseif ($row[8] == 7) { echo 'CTO';
									} elseif ($row[8] == 8) { echo 'Maternity Leave';
									} elseif ($row[8] == 9) { echo 'Paternity Leave';
									} elseif ($row[8] == 10) { echo 'UML';
									} elseif ($row[8] == 11) { echo 'USPL';
									} elseif ($row[8] == 12) { echo 'Travel Order';
									} elseif ($row[8] == 13) { echo 'DMS';
									} ?></td>
								<td align="left"><?PHP echo $row[9] ?></td>
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
        <!-- /page content -->
      </div>
    </div>

    <!-- jQuery -->
    <script src="vendors/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="vendors/bootstrap/dist/js/bootstrap.min.js"></script>

    <!-- Custom Theme Scripts -->
    <script src="build/js/custom.min.js"></script>
  </body>
</html>
