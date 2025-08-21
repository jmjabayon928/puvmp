<?php

include("db.php");
include("sqli.php");
include("html.php");
define('PAGE', 'payroll.php'); 

session_start();

if (isset($_SESSION['user_id'])) {
	if (validate_level(PAGE) == true) {
		
		$pid = $_GET['pid'];
		
		// get the month and year of the payroll period
		$sql = "SELECT MONTH(sp.sp_start), YEAR(sp.sp_end), sp.wdays
				FROM salary_periods sp INNER JOIN payrolls p ON sp.sid = p.sid
				WHERE p.pid = '$pid'";
		$pres = query($sql); 
		$prow = fetch_array($pres); 
		
		$pmonth = $prow[0]; $pyear = $prow[1]; $wdays = $prow[2]; 

		// log the activity
		log_user(5, 'payroll', 0);

		?>
		<!DOCTYPE html>
		<html lang="en">
		  <head>
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
			<!-- Meta, title, CSS, favicons, etc. -->
			<meta charset="utf-8">
			<meta http-equiv="X-UA-Compatible" content="IE=edge">
			<meta name="viewport" content="width=device-width, initial-scale=1">

			<title>OTC | Payroll Details</title>

			<!-- Bootstrap -->
			<link href="vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
			<!-- Font Awesome -->
			<link href="vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">

			<!-- Custom Theme Style -->
			<link href="build/css/custom.min.css" rel="stylesheet">
		  </head>

		  <body class="login">
			<div class="container body">
			  <div class="main_container">
			  
					<div class="row">
					  <div class="col-md-12 col-sm-12 col-xs-12">
					  
					    <?php
						$pid = $_GET['pid'];
						
						$sql = "SELECT DATE_FORMAT(sp_start, '%b %e, %Y'), DATE_FORMAT(sp_end, '%b %e, %Y'), p.gid
								FROM payrolls p INNER JOIN salary_periods sp ON p.sid = sp.sid
								WHERE p.pid = '$pid'";
						$ires = query($sql); 
						$irow = fetch_array($ires); 
						
						$period_start = $irow[0];
						$period_end = $irow[1];
						$gid = $irow[2];
						
						if ($gid == 1) { $salary_group = 'Regular Employees';
						} elseif ($gid == 2) { $salary_group = 'COS / JO Employees';
						}
						
						?>
					  
					    <table width="100%">
						<tr>
							<td width="20%" align="left">&nbsp; &nbsp; <img src="images/dotr-200.png"></td>
							<td width="60%" align="center">
								<span style="font-size: medium;">Republic of the Philippines</span><br />
								<span style="font-size: medium;">Department of Transportation</span><br />
								<font size="+3">OFFICE OF TRANSPORTATION COOPERATIVES</font><br /><br />
								<span style="font-size: large; font-weight: bold;"><?php echo $salary_group ?> Payroll</span><br /><br />
								<span style="font-size: large; font-weight: bold;">Period: <?php echo $period_start.' to '.$period_end; ?></span><br /><br />
							</td>
							<td width="20%" align="right"><img src="images/otc-logo-200.png">&nbsp; &nbsp; </td>
						</tr>
						</table>
						<br /><br />
						<?php
						if ($gid == 1) {
							?>
							<table class="table table-striped table-bordered jambo_table">
								<thead>
									<tr>
										<th width="10%" align="center"></th>
										<th width="15%" align="center">Employee</th>
										<th width="15%" align="center">Designation</th>
										<th width="10%" align="center">Basic Salary</th>
										<th width="10%" align="center">Absences Tardiness</th>
										<th width="10%" align="center">Total Deductions</th>
										<th width="10%" align="center">Monthly Net Pay</th>
										<th width="10%" align="center">1st Bi-Monthly Net Pay</th>
										<th width="10%" align="center">2nd Bi-Monthly Net Pay</th>
									</tr>
								</thead>
								<?php
								$basic = 0; $absences = 0; $deductions = 0; $net = 0; $net1 = 0; $net2 = 0; 
								
								$sql = "SELECT r.rid, CONCAT(e.lname,', ',e.fname), p.pname, r.basic,
											r.dad, r.total, r.net, r.net1, r.net2
										FROM payrolls_regular r 
											INNER JOIN employees e ON r.eid = e.eid
											INNER JOIN positions p ON e.pid = p.pid 
										WHERE r.pid = '$pid'
										ORDER BY e.lname, e.fname";
								$res = query($sql); 
								while ($row = fetch_array($res)) {
									?>
									<tr>
										<td align="center">
											<a href="e_payroll_regular.php?rid=<?php echo $row[0] ?>" class="btn btn-warning btn-xs"data-toggle="tooltip" data-placement="top" title="Update Payroll Entry"><i class="fa fa-pencil"></i></a>
											<a href="d_payroll_regular.php?rid=<?php echo $row[0] ?>" class="btn btn-danger btn-xs"><i class="fa fa-eraser"data-toggle="tooltip" data-placement="top" title="Delete Payroll Entry"></i></a>
										</td>
										<td align="left"><?php echo $row[1] ?></td>
										<td align="left"><?php echo $row[2] ?></td>
										<td align="right"><?php echo number_format($row[3], 2) ?></td>
										<td align="right"><?php echo number_format($row[4], 2) ?></td>
										<td align="right"><?php echo number_format($row[5], 2) ?></td>
										<td align="right"><?php echo number_format($row[6], 2) ?></td>
										<td align="right"><?php echo number_format($row[7], 2) ?></td>
										<td align="right"><?php echo number_format($row[8], 2) ?></td>
									</tr>
									<?php
									$basic += $row[3]; 
									$absences += $row[4]; 
									$deductions += $row[5]; 
									$net += $row[6]; 
									$net1 += $row[7]; 
									$net2 += $row[8]; 
								}
								free_result($res); 
								?>
								<tr>
									<td align="center"></td>
									<td align="center"></td>
									<td align="center"></td>
									<td align="right"><b><?php echo number_format($basic, 2) ?></b></td>
									<td align="right"><b><?php echo number_format($absences, 2) ?></b></td>
									<td align="right"><b><?php echo number_format($deductions, 2) ?></b></td>
									<td align="right"><b><?php echo number_format($net, 2) ?></b></td>
									<td align="right"><b><?php echo number_format($net1, 2) ?></b></td>
									<td align="right"><b><?php echo number_format($net2, 2) ?></b></td>
								</tr>
								<tbody>
							</table>
							<?php
						} elseif ($gid == 2) {
							?>
							<table class="table table-striped table-bordered jambo_table">
								<thead>
									<tr>
										<th width="11%" align="center"></th>
										<th width="12%" align="center">Employee</th>
										<th width="07%" align="center">Salary</th>
										<th width="06%" align="center">Work Day</th>
										<th width="06%" align="center">T/UT [Hr]</th>
										<th width="06%" align="center">T/UT [Min]</th>
										<th width="07%" align="center">Gross Salary</th>
										<th width="06%" align="center">T/UT</th>
										<th width="08%" align="center">Net Salary</th>
										<th width="06%" align="center">PHIC</th>
										<th width="06%" align="center">EWT (2%)</th>
										<th width="06%" align="center">VAT (3%)</th>
										<th width="06%" align="center">Deductions</th>
										<th width="07%" align="center">Payable</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$i = 1; 
									
									$total_gross = 0; 
									$total_utt = 0; 
									$total_net = 0; 
									$total_phic = 0; 
									$total_ewt = 0; 
									$total_vat = 0; 
									$total_deductions = 0; 
									$total_payable = 0; 
									
									$sql = "SELECT pj.jid, CONCAT(e.lname,', ',e.fname), pj.daily, pj.salary,
												pj.wdays, pj.tut_hr, pj.tut_min, pj.gross, pj.tut_value, 
												pj.net, pj.phic, pj.ewt, pj.vat, pj.deductions, pj.payable 
											FROM payrolls_jocos pj INNER JOIN employees e ON pj.eid = e.eid
											WHERE pj.pid = '$pid'
											ORDER BY e.lname, e.fname";
									$res = query($sql); 
									while ($row = fetch_array($res)) {
										if ($row[2] > 0 && $row[3] == 0) { 
											$salary = number_format($row[2], 2).'/day';
										} elseif ($row[2] == 0 && $row[3] > 0) { 
											$salary = number_format($row[3], 2).'/mo.';
										}			
										
										?>
										<tr>
											<td align="center">
												<a target="new" href="tcpdf/examples/pdf_ors_dv.php?jid=<?php echo $row[0] ?>" class="btn btn-info btn-xs" data-toggle="tooltip" data-placement="top" title="Print ORS & DV"><i class="fa fa-print"></i></a>
												<a target="new" href="tcpdf/examples/pdf_payslip.php?jid=<?php echo $row[0] ?>" class="btn btn-success btn-xs" data-toggle="tooltip" data-placement="top" title="Print Payslip"><i class="fa fa-rub"></i></a>
												<a href="e_payroll_jocos.php?jid=<?php echo $row[0] ?>" class="btn btn-warning btn-xs"data-toggle="tooltip" data-placement="top" title="Update Payroll Entry"><i class="fa fa-pencil"></i></a>
												<a href="d_payroll_jocos.php?jid=<?php echo $row[0] ?>" class="btn btn-danger btn-xs"><i class="fa fa-eraser"data-toggle="tooltip" data-placement="top" title="Delete Payroll Entry"></i></a>
											</td>
											<td align="left"><?php echo $row[1] ?></td>
											<td align="right"><?php echo $salary ?></td>
											<td align="center"><?php echo $row[4] ?></td>
											<td align="center"><?php echo $row[5] ?></td>
											<td align="center"><?php echo $row[6] ?></td>
											<td align="right"><?php echo number_format($row[7], 2) ?></td>
											<td align="right"><?php echo number_format($row[8], 2) ?></td>
											<td align="right"><?php echo number_format($row[9], 2) ?></td>
											<td align="right"><?php echo number_format($row[10], 2) ?></td>
											<td align="right"><?php echo number_format($row[11], 2) ?></td>
											<td align="right"><?php echo number_format($row[12], 2) ?></td>
											<td align="right"><?php echo number_format($row[13], 2) ?></td>
											<td align="right"><?php echo number_format($row[14], 2) ?></td>
										</tr>
										<?php
										$i++;
										
										$total_gross += $row[7]; 
										$total_utt += $row[8]; 
										$total_net += $row[9]; 
										$total_phic += $row[10]; 
										$total_ewt += $row[11]; 
										$total_vat += $row[12]; 
										$total_deductions += $row[13];
										$total_payable += $row[14]; 
									}
									free_result($res); 
									
									// update the payroll
									$sql = "UPDATE payrolls 
											SET total_gross = '$total_gross',
												total_net = '$total_net',
												phic_ee = '$total_phic',
												per_tax = '$total_vat',
												ewt_tax = '$total_ewt'
											WHERE pid = '$pid'
											LIMIT 1";
									//query($sql); 
									?>
									<tr>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td align="right"><b><?php echo number_format($total_gross, 2) ?></b></td>
										<td align="right"><b><?php echo number_format($total_utt, 2) ?></b></td>
										<td align="right"><b><?php echo number_format($total_net, 2) ?></b></td>
										<td align="right"><b><?php echo number_format($total_phic, 2) ?></b></td>
										<td align="right"><b><?php echo number_format($total_ewt, 2) ?></b></td>
										<td align="right"><b><?php echo number_format($total_vat, 2) ?></b></td>
										<td align="right"><b><?php echo number_format($total_deductions, 2) ?></b></td>
										<td align="right"><b><?php echo number_format($total_payable, 2) ?></b></td>
									</tr>
								</tbody>
							</table>
							<?php
						}
						?>
					  
					  </div>
					</div>
			 
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
