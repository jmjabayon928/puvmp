<?php

include("db.php");
include("sqli.php");
include("html.php");
define('PAGE', 'e_payroll_regular.php'); 

session_start();

if (isset($_SESSION['user_id'])) {
	if (validate_level(PAGE) == true) {

		if(isset($_POST['submitted'])) { // if submit button has been pressed
			$rid = real_escape_string($_POST['rid']);
			$basic = real_escape_string($_POST['basic']);
			$gsis_rlip_ee = real_escape_string($_POST['gsis_rlip_ee']);
			$gsis_uoli = real_escape_string($_POST['gsis_uoli']);
			$gsis_consoloan = real_escape_string($_POST['gsis_consoloan']);
			$gsis_cash_adv = real_escape_string($_POST['gsis_cash_adv']);
			$gsis_eal = real_escape_string($_POST['gsis_eal']);
			$gsis_el = real_escape_string($_POST['gsis_el']);
			$gsis_pl = real_escape_string($_POST['gsis_pl']);
			$gsis_opt_pl = real_escape_string($_POST['gsis_opt_pl']);
			$hdmf_ee = real_escape_string($_POST['hdmf_ee']);
			$hdmf_hl = real_escape_string($_POST['hdmf_hl']);
			$hdmf_mpl = real_escape_string($_POST['hdmf_mpl']);
			$phic_ee = real_escape_string($_POST['phic_ee']);
			$emdeco = real_escape_string($_POST['emdeco']);
			$bir_tax = real_escape_string($_POST['bir_tax']);
			$dad = real_escape_string($_POST['dad']);
			
			// check if rid is given
			if ($rid != 0 && $rid != '') { $a = TRUE; } 
			else { $a = FALSE; $message[] = "Please select from the list of payroll entries."; }
			// check if gsis_rlip_ee is given
			if ($gsis_rlip_ee != 0 && $gsis_rlip_ee != '') { $b = TRUE; } 
			else { $b = FALSE; $message[] = "Please enter the GSIS RLIP."; }
			// check if hdmf_ee is given
			if ($hdmf_ee != 0 && $hdmf_ee != '') { $c = TRUE; } 
			else { $c = FALSE; $message[] = "Please enter the HDMF EE Share."; }
			// check if phic_ee is given
			if ($phic_ee != 0 && $phic_ee != '') { $d = TRUE; } 
			else { $d = FALSE; $message[] = "Please enter the PHIC EE Share."; }
			
			// If data pass all tests, proceed
			if ( $a && $b && $c && $d ) {
				// get the pid of this payroll entry 
				$sql = "SELECT pid FROM payrolls_regular WHERE rid = '$rid'";
				$res = query($sql); $row = fetch_array($res); $pid = $row[0];
				
				// total deductions
				$total = $gsis_rlip_ee + $gsis_uoli + $gsis_consoloan + $gsis_cash_adv + $gsis_eal + $gsis_el + $gsis_pl + $gsis_opt_pl + $hdmf_ee + $hdmf_hl + $hdmf_mpl + $phic_ee + $emdeco + $bir_tax; 
				
				// net pay
				$net = $basic - ($total + $dad);
				$net1 = floor($net / 2);
				$net2 = $net - floor($net / 2);
				
				// update data
				$sql = "UPDATE payrolls_regular
						SET gsis_rlip_ee = '$gsis_rlip_ee',
							gsis_uoli = '$gsis_uoli',
							gsis_consoloan = '$gsis_consoloan',
							gsis_cash_adv = '$gsis_cash_adv',
							gsis_eal = '$gsis_eal',
							gsis_el = '$gsis_el',
							gsis_pl = '$gsis_pl',
							gsis_opt_pl = '$gsis_opt_pl',
							hdmf_ee = '$hdmf_ee',
							hdmf_hl = '$hdmf_hl',
							hdmf_mpl = '$hdmf_mpl',
							phic_ee = '$phic_ee',
							emdeco = '$emdeco',
							bir_tax = '$bir_tax',
							dad = '$dad',
							total = '$total',
							net = '$net',
							net1 = '$net1',
							net2 = '$net2'
						WHERE rid = '$rid'
						LIMIT 1";
				
				if (query($sql)) {
					
					// log the activity
					log_user(2, 'payrolls_regular', $rid);
					
					header("Location: payroll.php?pid=".$pid);
					exit();
				} else {
					?>
					<div class="alert alert-danger alert-dismissible fade in" role="alert">
						<strong>Could not update payroll entry because: </strong><br />
						<b><?php echo mysqli_error($connection) ?></b><br />
						The query was <?php echo $sql ?>.<br />
					</div>
					<?PHP
				}
			} else {
				$serialized_message = serialize($message);
				// redirect the user to index page
				header('location: e_payroll_regular.php?serialized_message='.$serialized_message.'&rid='.$rid);
				exit();
			}
			
		} else {
			?>
			<!DOCTYPE html>
			<html lang="en">
			  <head>
				<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
				<!-- Meta, title, CSS, favicons, etc. -->
				<meta charset="utf-8">
				<meta http-equiv="X-UA-Compatible" content="IE=edge">
				<meta name="viewport" content="width=device-width, initial-scale=1">
				  
				<title>OTC | Update Payroll Entry</title>

				<!-- Bootstrap -->
				<link href="vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
				<!-- Font Awesome -->
				<link href="vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
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
					
					$rid = $_GET['rid'];

					$sql = "SELECT CONCAT(e.lname,', ',e.fname,', ',e.mname), pr.basic,
								pr.gsis_rlip_ee, pr.gsis_uoli, pr.gsis_consoloan,
								pr.gsis_cash_adv, pr.gsis_eal, pr.gsis_el, pr.gsis_pl, 
								pr.gsis_opt_pl, pr.hdmf_ee, pr.hdmf_hl, pr.hdmf_mpl, 
								pr.phic_ee, pr.emdeco, pr.bir_tax, pr.dad
							FROM payrolls_regular pr
								INNER JOIN employees e ON pr.eid = e.eid 
							WHERE pr.rid = '$rid'";
					$res = query($sql); 
					$row = fetch_array($res); 
					
					?>

					<!-- page content -->
					<div class="right_col" role="main">
					  <div class="">
						<div class="page-title">
						  <div class="title_left">
							<h3>Update Payroll Entry</h3>
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
						  <?php
						  if (isset($_GET['serialized_message'])) {
							  $message = unserialize($_GET['serialized_message']);
							  ?>
							  <div class="col-md-12 col-sm-12 col-xs-12">
								<div class="x_panel">
								  <div class="x_content">
									  <div class="alert alert-danger alert-dismissible fade in" role="alert">
										<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
										</button>
										<strong>The following problems occurred: </strong><br />
										<?php
										foreach ($message as $key => $value) {
											echo $value; echo '<br />';
										}
										?>
									  </div>
								  </div>
								</div>
							  </div>
							  <div class="clearfix"></div>
							  <?php
						  }
						  ?>
						  <div class="col-md-12 col-sm-12 col-xs-12">
							<div class="x_panel">
							  <div class="x_content">
								<br />
								
									<form action="e_payroll_regular.php" method="POST" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12">Employee </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" disabled class="form-control col-md-7 col-xs-12" value="<?php echo $row[0] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12">Basic Salary </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="basic" name="basic" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $row[1] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="gsis_rlip_ee">GSIS RLIP <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="gsis_rlip_ee" name="gsis_rlip_ee" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $row[2] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="gsis_uoli">GSIS UOLI <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="gsis_uoli" name="gsis_uoli" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $row[3] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="gsis_consoloan">GSIS ConsoLoan <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="gsis_consoloan" name="gsis_consoloan" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $row[4] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label for="scheme" class="control-label col-md-3 col-sm-3 col-xs-12" for="gsis_cash_adv">GSIS Cash Advance <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="gsis_cash_adv" name="gsis_cash_adv" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $row[5] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label for="scheme" class="control-label col-md-3 col-sm-3 col-xs-12" for="gsis_eal">GSIS Educ Assistance Loan <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="gsis_eal" name="gsis_eal" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $row[6] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label for="scheme" class="control-label col-md-3 col-sm-3 col-xs-12" for="gsis_el">GSIS Emergency Loan <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="gsis_el" name="gsis_el" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $row[7] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label for="scheme" class="control-label col-md-3 col-sm-3 col-xs-12" for="gsis_pl">GSIS Policy Loan <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="gsis_pl" name="gsis_pl" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $row[8] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label for="scheme" class="control-label col-md-3 col-sm-3 col-xs-12" for="gsis_opt_pl">GSIS Optional Policy Loan <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="gsis_opt_pl" name="gsis_opt_pl" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $row[9] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label for="scheme" class="control-label col-md-3 col-sm-3 col-xs-12" for="hdmf_ee">HDMF Premium EE Share <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="hdmf_ee" name="hdmf_ee" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $row[10] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label for="scheme" class="control-label col-md-3 col-sm-3 col-xs-12" for="hdmf_hl">HDMF Housing Loan <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="hdmf_hl" name="hdmf_hl" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $row[11] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label for="scheme" class="control-label col-md-3 col-sm-3 col-xs-12" for="hdmf_mpl">HDMF Multi-Purpose Loan <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="hdmf_mpl" name="hdmf_mpl" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $row[12] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label for="scheme" class="control-label col-md-3 col-sm-3 col-xs-12" for="phic_ee">PHIC Premium EE Share <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="phic_ee" name="phic_ee" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $row[13] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label for="scheme" class="control-label col-md-3 col-sm-3 col-xs-12" for="emdeco">OTC Emdeco <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="emdeco" name="emdeco" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $row[14] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label for="scheme" class="control-label col-md-3 col-sm-3 col-xs-12" for="bir_tax">BIR Income Tax Withheld <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="bir_tax" name="bir_tax" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $row[15] ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label for="scheme" class="control-label col-md-3 col-sm-3 col-xs-12" for="dad">Salary Deductions / Absences / Disallowances <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="dad" name="dad" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $row[16] ?>">
										</div>
									  </div>
									  <div class="ln_solid"></div>
									  <div class="form-group">
										<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
										  <input type="hidden" name="submitted" value="1">
										  <input type="hidden" name="rid" value="<?php echo $rid ?>">
										  <button class="btn btn-primary" type="reset">Reset</button>
										  <button type="submit" class="btn btn-success">Submit</button>
										</div>
									  </div>

									</form>
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
				<!-- Custom Theme Scripts -->
				<script src="build/js/custom.min.js"></script>
				
			  </body>
			</html>
			<?PHP
		}
	}
} else {
	header('Location: login.php');	
}

