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

			<title>OTC | Summary of Deductions</title>

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
						
						$sql = "SELECT DATE_FORMAT(sp_start, '%b %e, %Y'), DATE_FORMAT(sp_end, '%b %e, %Y')
								FROM payrolls p INNER JOIN salary_periods sp ON p.sid = sp.sid
								WHERE p.pid = '$pid'";
						$ires = query($sql); 
						$irow = fetch_array($ires); 
						
						$period_start = $irow[0];
						$period_end = $irow[1];
						
						?>
					  
					    <table width="100%">
						<tr>
							<td width="20%" align="left">&nbsp; &nbsp; <img src="images/dotr-200.png"></td>
							<td width="60%" align="center">
								<span style="font-size: medium;">Republic of the Philippines</span><br />
								<span style="font-size: medium;">Department of Transportation</span><br />
								<font size="+3">OFFICE OF TRANSPORTATION COOPERATIVES</font><br /><br />
								<span style="font-size: large; font-weight: bold;">Summary of Deductions</span><br /><br />
								<span style="font-size: large; font-weight: bold;">Period: <?php echo $period_start.' to '.$period_end; ?></span><br /><br />
							</td>
							<td width="20%" align="right"><img src="images/otc-logo-200.png">&nbsp; &nbsp; </td>
						</tr>
						</table>
						<br /><br />
						<table class="table table-striped table-bordered jambo_table">
							<thead>
								<tr>
									<td rowspan="2" width="06%" align="center"></td>
									<td rowspan="2" width="10%" align="center"><br /><br />Employee</td>
									<td rowspan="2" width="07%" align="center"><br /><br />Basic Salary</td>
									<td colspan="2" width="10%" align="center">GSIS Premiums</td>
									<td colspan="6" width="30%" align="center">GSIS Loans</td>
									<td colspan="3" width="15%" align="center">Pagibig</td>
									<td rowspan="2" width="05%" align="center"><br /><br />PHIC EE<br />415</td>
									<td rowspan="2" width="05%" align="center"><br /><br />OTC<br />EMDECO</td>
									<td rowspan="2" width="05%" align="center"><br /><br />BIR Tax<br />412</td>
									<td rowspan="2" width="07%" align="center"><br /><br />Total</td>
								</tr>
								<tr>
									<td align="center">RLIP<br />413 A</td>
									<td align="center">UOLI<br />413 B</td>
									<td align="center">Consoloan<br />413 C</td>
									<td align="center">Cash Adv<br />413 D</td>
									<td align="center">EAL<br />413 E</td>
									<td align="center">EL<br />413 F</td>
									<td align="center">Reg PL<br />413 G</td>
									<td align="center">Optl PL<br />413 H</td>
									<td align="center">HDMF EE<br />414 I</td>
									<td align="center">HDMF HL<br />414 J</td>
									<td align="center">HDMF MPL<br />414 K</td>
								</tr>
							</thead>
							<tbody>
								<?php
								$basic = 0; $gsis_rlip_ee = 0; $gsis_uoli = 0; $gsis_consoloan = 0; $gsis_cash_adv = 0; 
								$gsis_eal = 0; $gsis_el = 0; $gsis_pl = 0; $gsis_opt_pl = 0; $hdmf_ee = 0; 
								$hdmf_hl = 0; $hdmf_mpl = 0; $phic_ee = 0; $emdeco = 0; $bir_tax = 0; $total = 0; 
								
								$sql = "SELECT d.rid, CONCAT(e.lname,', ',e.fname,', ',e.mname), d.basic, 
											d.gsis_rlip_ee, d.gsis_uoli, d.gsis_consoloan, d.gsis_cash_adv, 
											d.gsis_eal, d.gsis_el, d.gsis_pl, d.gsis_opt_pl, d.hdmf_ee, 
											d.hdmf_hl, d.hdmf_mpl, d.phic_ee, d.emdeco, d.bir_tax, d.total
										FROM payrolls_regular d INNER JOIN employees e ON d.eid = e.eid
										WHERE d.pid = '$pid'
										ORDER BY e.lname, e.fname";
								$res = query($sql); 
								while ($row = fetch_array($res)) {
									?>
									<tr>
										<td align="center">
											<a href="e_deduction_entry.php?rid=<?php echo $row[0] ?>" class="btn btn-warning btn-xs"data-toggle="tooltip" data-placement="top" title="Update Deduction Entry"><i class="fa fa-pencil"></i></a>
											<a href="d_deduction_entry.php?rid=<?php echo $row[0] ?>" class="btn btn-danger btn-xs"><i class="fa fa-eraser"data-toggle="tooltip" data-placement="top" title="Delete Deduction Entry"></i></a>
										</td>
										<td align="left"><?php echo $row[1] ?></td>
										<td align="right"><?php echo number_format($row[2], 2) ?></td>
										<td align="right"><?php echo number_format($row[3], 2) ?></td>
										<td align="right"><?php echo number_format($row[4], 2) ?></td>
										<td align="right"><?php echo number_format($row[5], 2) ?></td>
										<td align="right"><?php echo number_format($row[6], 2) ?></td>
										<td align="right"><?php echo number_format($row[7], 2) ?></td>
										<td align="right"><?php echo number_format($row[8], 2) ?></td>
										<td align="right"><?php echo number_format($row[9], 2) ?></td>
										<td align="right"><?php echo number_format($row[10], 2) ?></td>
										<td align="right"><?php echo number_format($row[11], 2) ?></td>
										<td align="right"><?php echo number_format($row[12], 2) ?></td>
										<td align="right"><?php echo number_format($row[13], 2) ?></td>
										<td align="right"><?php echo number_format($row[14], 2) ?></td>
										<td align="right"><?php echo number_format($row[15], 2) ?></td>
										<td align="right"><?php echo number_format($row[16], 2) ?></td>
										<td align="right"><?php echo number_format($row[17], 2) ?></td>
									</tr>
									<?php
									$basic += $row[2]; 
									$gsis_rlip_ee += $row[3]; 
									$gsis_uoli += $row[4]; 
									$gsis_consoloan += $row[5]; 
									$gsis_cash_adv += $row[6]; 
									$gsis_eal += $row[7]; 
									$gsis_el += $row[8]; 
									$gsis_pl += $row[9]; 
									$gsis_opt_pl += $row[10]; 
									$hdmf_ee += $row[11]; 
									$hdmf_hl += $row[12]; 
									$hdmf_mpl += $row[13]; 
									$phic_ee += $row[14]; 
									$emdeco += $row[15]; 
									$bir_tax += $row[16]; 
									$total += $row[17]; 
								}
								free_result($res); 
								?>
								<tr>
									<td></td>
									<td align="left"><b>TOTAL</b></td>
									<td align="right"><?php echo number_format($basic, 2) ?></td>
									<td align="right"><?php echo number_format($gsis_rlip_ee, 2) ?></td>
									<td align="right"><?php echo number_format($gsis_uoli, 2) ?></td>
									<td align="right"><?php echo number_format($gsis_consoloan, 2) ?></td>
									<td align="right"><?php echo number_format($gsis_cash_adv, 2) ?></td>
									<td align="right"><?php echo number_format($gsis_eal, 2) ?></td>
									<td align="right"><?php echo number_format($gsis_el, 2) ?></td>
									<td align="right"><?php echo number_format($gsis_pl, 2) ?></td>
									<td align="right"><?php echo number_format($gsis_opt_pl, 2) ?></td>
									<td align="right"><?php echo number_format($hdmf_ee, 2) ?></td>
									<td align="right"><?php echo number_format($hdmf_hl, 2) ?></td>
									<td align="right"><?php echo number_format($hdmf_mpl, 2) ?></td>
									<td align="right"><?php echo number_format($phic_ee, 2) ?></td>
									<td align="right"><?php echo number_format($emdeco, 2) ?></td>
									<td align="right"><?php echo number_format($bir_tax, 2) ?></td>
									<td align="right"><?php echo number_format($total, 2) ?></td>
								</tr>
							</tbody>
						</table>
					  
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
