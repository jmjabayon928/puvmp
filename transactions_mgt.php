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

    <title>OTC Transactions Management</title>

    <!-- Bootstrap -->
    <link href="vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- Animate.css -->
    <link href="vendors/animate.css/animate.min.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="build/css/custom.min.css" rel="stylesheet">
  </head>

  <body class="login">
  
    <marquee behavior=scroll direction="up" scrollamount="5">

    <?php
	$sql = "SELECT t.tid, t.ref_num, tc.tname, c.cname, t.tname, 
				DATE_FORMAT(t.fr_dtime, '%m/%d/%y %h:%i %p'), 
				DATE_FORMAT(t.to_dtime, '%m/%d/%y %h:%i %p'), 
				CONCAT(
				   FLOOR(HOUR(TIMEDIFF(t.to_dtime, t.fr_dtime)) / 24), 'd: ',
				   MOD(HOUR(TIMEDIFF(t.to_dtime, t.fr_dtime)), 24), 'h: ',
				   MINUTE(TIMEDIFF(t.to_dtime, t.fr_dtime)), 'm'),
				t.status, t.remarks,
				CONCAT(u.lname,', ',u.fname), DATE_FORMAT(t.user_dtime, '%m/%d/%Y %h:%i%p')
			FROM transactions t 
				INNER JOIN tc_transactions tc ON t.ttid = tc.tid
				INNER JOIN cooperatives c ON t.coop_id = c.cid
				INNER JOIN users u ON t.user_id = u.user_id
			WHERE t.status != 6
			ORDER BY t.fr_dtime DESC";
	$res = query($sql); 
	while ($row = fetch_array($res)) {
		?>
		<div class="col-md-12 col-sm-12 col-xs-12">
			<div class="x_panel">
				<div class="x_title">
					<h2><?php echo ucwords(strtolower($row[3])) ?></h2>
					<div class="clearfix"></div>
				</div>
				<div class="x_content">
				
					<table class="table table-striped table-bordered jambo_table bulk_action">
						<thead>
						  <tr class="headings">
							  <th colspan="4">Transaction Details</th>
						  </tr>
						</thead>
						<tbody>
							<tr>
								<td width="10%">Reference No.</td>
								<td width="30%"><b><?php echo $row[1] ?></b></td>
								<td width="10%">Start</td>
								<td width="50%"><b><?php echo $row[5] ?></b></td>
							</tr>
							<tr>
								<td>Type of Transaction</td>
								<td><b><?php echo $row[2] ?></b></td>
								<td>End</td>
								<td><b><?php echo $row[6] ?></b></td>
							</tr>
							<tr>
								<td>Cooperative</td>
								<td><b><?php echo $row[3] ?></b></td>
								<td>Duration</td>
								<td><b><?php echo $row[7] ?></b></td>
							</tr>
							<tr>
								<td>Transacting Person</td>
								<td><b><?php echo $row[4] ?></b></td>
								<td>Status</td>
								<td><b><?php 
									if ($row[8] == 1) { echo 'Received by Records';
									} elseif ($row[8] == 2) { echo 'On Process By OD';
									} elseif ($row[8] == 3) { echo 'For OD Chief Verification';
									} elseif ($row[8] == 4) { echo 'For OC Approval';
									} elseif ($row[8] == 5) { echo 'For Release By Records';
									} elseif ($row[8] == 6) { echo 'Finished';
									}
									?></b></td>
							</tr>
							<tr>
								<td>Remarks</td>
								<td colspan="3"><b><?php echo $row[9] ?></b></td>
							</tr>
							<tr>
								<td>Last Updated By</td>
								<td><b><?php echo $row[10] ?></b></td>
								<td>Date Last Updated</td>
								<td><b><?php echo $row[11] ?></b></td>
							</tr>
						</tbody>
					</table>
				
					<?php 
					$sql = "SELECT rid 
							FROM transactions_requirements 
							WHERE tid = '$row[0]'";
					$cres = query($sql); 
					$cnum = num_rows($cres); 
					
					if ($cnum > 0) {
						?>
						<table class="table table-striped table-bordered">
							<thead>
							  <tr class="headings">
								  <th width="15%">Requirement</th>
								  <th width="10%">From</th>
								  <th width="10%">To</th>
								  <th width="15%">Processed By</th>
								  <th width="10%">Status</th>
								  <th width="40%">Remarks</th>
							  </tr>
							</thead>
							<tbody>
							<?php
							$sql = "SELECT r.rid, r.rname, DATE_FORMAT(r.fr_dtime, '%m/%d/%Y %h:%i %p'), 
										DATE_FORMAT(r.to_dtime, '%m/%d/%Y %h:%i %p'), 
										CONCAT(u.lname,', ',u.fname), r.status, r.remarks
									FROM transactions_requirements r
										INNER JOIN users u ON r.user_id = u.user_id
									WHERE r.tid = '$row[0]'";
							$rres = query($sql); 
							while ($rrow = fetch_array($rres)) {
								?>
								<tr>
									<td align="left"><?php echo $rrow[1] ?></td>
									<td align="left"><?php echo $rrow[2] ?></td>
									<td align="left"><?php echo $rrow[3] ?></td>
									<td align="left"><?php echo $rrow[4] ?></td>
									<td align="left"><?php 
										if ($rrow[5] == 0) { echo 'Failed';
										} elseif ($rrow[5] == 1) { echo 'Passed';
										} ?></td>
									<td align="left"><?php echo $rrow[6] ?></td>
								</tr>
								<?php
							}
							mysqli_free_result($rres);
							?>
							</tbody>
						</table>
						<?php
					}
					?>
				
				</div>
			</div>
		</div>
		<?php
	}
	free_result($res); 
	?>
  
    </marquee>
  </body>
</html>
