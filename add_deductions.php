<?php

include("db.php");
include("sqli.php");
include("html.php");
define('PAGE', 'add_deductions.php'); 

session_start();

if (isset($_SESSION['user_id'])) {
	if (validate_level(PAGE) == true) {

		if(isset($_POST['submitted'])) { // if submit button has been pressed
			
			$pid = clean_input($_POST['pid']);
			$eid = clean_input($_POST['eid']);
			$basic = clean_input($_POST['basic']);
			$gsis_413a = clean_input($_POST['gsis_413a']);
			$gsis_413b = clean_input($_POST['gsis_413b']);
			$gsis_413p = clean_input($_POST['gsis_413p']);
			$gsis_413q = clean_input($_POST['gsis_413q']);
			$gsis_413r = clean_input($_POST['gsis_413r']);
			$gsis_413o = clean_input($_POST['gsis_413o']);
			$gsis_413j = clean_input($_POST['gsis_413j']);
			$gsis_413l = clean_input($_POST['gsis_413l']);
			$hdmf_414a = clean_input($_POST['hdmf_414a']);
			$hdmf_414b = clean_input($_POST['hdmf_414b']);
			$hdmf_414c = clean_input($_POST['hdmf_414c']);
			$phic_ee = clean_input($_POST['phic_ee']);
			$emdeco_439a = clean_input($_POST['emdeco_439a']);
			$bir_412 = clean_input($_POST['bir_412']);
			$dad = clean_input($_POST['dad']);
			
			// check if gsis_413a is given
			if ($gsis_413a != 0.00) { $a = TRUE; } 
			else { $a = FALSE; $message[] = "Please enter the Life and Retirement Insurance Premium."; }
			// check if hdmf_414a is given
			if ($hdmf_414a != 0.00) { $b = TRUE; } 
			else { $b = FALSE; $message[] = "Please enter the HDMF EE Share."; }
			// check if phic_ee is given
			if ($phic_ee != 0.00) { $c = TRUE; } 
			else { $c = FALSE; $message[] = "Please enter the PHIC EE Share."; }
			// check if pid is given
			if ($pid != 0) { $d = TRUE; } 
			else { $d = FALSE; $message[] = "Please select from the list of payrolls."; }
			// check if eid is given
			if ($eid != 0) { $e = TRUE; } 
			else { $e = FALSE; $message[] = "Please select from the list of employees."; }
			
			// If data pass all tests, proceed
			if ( $a && $b && $c && $d && $e ) {
				
				// check for double entry first
				$sql = "SELECT did 
						FROM payrolls_deductions 
						WHERE pid = '$pid' AND eid = '$eid'";
				$res = query($sql); 
				$num = num_rows($res); 
				
				if ($num > 0) {
					?>
					<div class="alert alert-danger alert-dismissible fade in" role="alert">
						<strong>Record already exists! </strong><br /><br />
					</div>
					<?php
				} else {
					$total = $gsis_413a + $gsis_413b + $gsis_413p + $gsis_413q + $gsis_413r + $gsis_413o + $gsis_413j + $gsis_413l + $hdmf_414a + $hdmf_414b + $hdmf_414c + $phic_ee + $emdeco_439a + $bir_412;
					
					$sql = "INSERT INTO payrolls_deductions( 
								pid, eid, basic, gsis_413a, gsis_413b, gsis_413p, gsis_413q, gsis_413r, gsis_413o, gsis_413j, gsis_413l, hdmf_414a, hdmf_414b, hdmf_414c, phic_ee, emdeco_439a, bir_412, dad, total
							)
							VALUES( 
								'$pid', '$eid', '$basic', '$gsis_413a', '$gsis_413b', '$gsis_413p', '$gsis_413q', '$gsis_413r', '$gsis_413o', '$gsis_413j', '$gsis_413l', '$hdmf_414a', '$hdmf_414b', '$hdmf_414c', '$phic_ee', '$emdeco_439a', '$bir_412', '$dad', '$total'
							)";
					if (query($sql)) {
						$did = mysqli_insert_id($connection);
						// log the activity
						log_user(1, 'payrolls_deductions', $did);
						
						header("Location: payroll_deductions.php?pid=".$pid);
						exit();
					} else {
						?>
						<div class="alert alert-danger alert-dismissible fade in" role="alert">
							<strong>Could not add payroll deductions because: </strong><br />
							<b><?php echo mysqli_error($connection) ?></b><br />
							The query was <?php echo $sql ?>.<br />
						</div>
						<?PHP
					}
				}
			} else {
				$serialized_message = serialize($message);
				// redirect the user to index page
				header('location: add_deductions.php?serialized_message='.$serialized_message.'&pid='.$pid.'&eid='.$eid.'&gsis_413b='.$gsis_413b.'&gsis_413p='.$gsis_413p.'&gsis_413q='.$gsis_413q.'&gsis_413r='.$gsis_413r.'&gsis_413o='.$gsis_413o.'&gsis_413j='.$gsis_413j.'&gsis_413l='.$gsis_413l.'&hdmf_414a='.$hdmf_414a.'&hdmf_414b='.$hdmf_414b.'&hdmf_414c='.$hdmf_414c.'&phic_ee='.$phic_ee.'&emdeco_439a='.$emdeco_439a.'&bir_412='.$bir_412.'&dad='.$dad);
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
				  
				<title>OTC | Add Payroll Deductions</title>

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
					?>

					<!-- page content -->
					<div class="right_col" role="main">
					  <div class="">
						<div class="page-title">
						  <div class="title_left">
							<h3>Add Payroll Deductions</h3>
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
									<?php
									if (isset($_GET['pid'])) {
										$pid = $_GET['pid'];
									}
									if (isset($_GET['eid'])) {
										$eid = $_GET['eid'];
										
										// check if there is an existing family-record for this employee
										$sql = "SELECT monthly, monthly * 0.09, (monthly * 0.0275) / 2 FROM employees WHERE eid = '$eid'";
										$res = query($sql); 
										$row = fetch_array($res); 
										$monthly = $row[0]; 
										$rlip = $row[1]; 
										$phic_ee = number_format($row[2], 2); 
									} else {
										$monthly = '0.00';
										$rlip = '0.00';
										$phic_ee = '0.00';
									}
									?>
									<form action="add_deductions.php" method="POST" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="eid2">Employee Name <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <select id="eid2" name="eid2" class="form-control" required="required" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
											<option value="add_deductions.php?pid=<?php echo $pid ?>&eid=0">--- Select ---</option>
											<?php
											$sql = "SELECT eid, CONCAT(lname,', ',fname) 
													FROM employees 
													WHERE active = 1 AND etype < 3
													ORDER BY lname, fname";
											$eres = query($sql); 
											while ($erow = fetch_array($eres)) {
												if ($eid == $erow[0]) {
													?><option value="add_deductions.php?pid=<?php echo $pid ?>&eid=<?php echo $erow[0] ?>" Selected><?php echo $erow[1] ?></option><?php
												} else {
													?><option value="add_deductions.php?pid=<?php echo $pid ?>&eid=<?php echo $erow[0] ?>"><?php echo $erow[1] ?></option><?php
												}
											}
											free_result($eres); 
											?>
										  </select>
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="basic">Monthly Basic </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="basic" name="basic" class="form-control col-md-7 col-xs-12" value="<?php echo $monthly ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="gsis_413a">GSIS RLIP <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="gsis_413a" name="gsis_413a" required="required" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['gsis_413a'])) {
											  ?>value="<?php echo $_GET['gsis_413a'] ?>"<?php
										  } else {
											  ?>value="<?php echo $rlip ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="gsis_413b">GSIS UOLI </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="gsis_413b" name="gsis_413b" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['gsis_413b'])) {
											  ?>value="<?php echo $_GET['gsis_413b'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="gsis_413p">GSIS Consoloan </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="gsis_413p" name="gsis_413p" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['gsis_413p'])) {
											  ?>value="<?php echo $_GET['gsis_413p'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="gsis_413q">GSIS Cash Advance </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="gsis_413q" name="gsis_413q" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['gsis_413q'])) {
											  ?>value="<?php echo $_GET['gsis_413q'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="gsis_413r">GSIS EAL </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="gsis_413r" name="gsis_413r" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['gsis_413r'])) {
											  ?>value="<?php echo $_GET['gsis_413r'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="gsis_413o">GSIS EL </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="gsis_413o" name="gsis_413o" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['gsis_413o'])) {
											  ?>value="<?php echo $_GET['gsis_413o'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="gsis_413j">GSIS Reg PL </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="gsis_413j" name="gsis_413j" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['gsis_413j'])) {
											  ?>value="<?php echo $_GET['gsis_413j'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="gsis_413l">GSIS Optional PL </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="gsis_413l" name="gsis_413l" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['gsis_413l'])) {
											  ?>value="<?php echo $_GET['gsis_413l'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="hdmf_414a">HDMF EE <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="hdmf_414a" name="hdmf_414a" required="required" class="form-control col-md-7 col-xs-12" value="100.00">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="hdmf_414b">HDMF HL </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="hdmf_414b" name="hdmf_414b" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['hdmf_414b'])) {
											  ?>value="<?php echo $_GET['hdmf_414b'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="hdmf_414c">HDMF MPL </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="hdmf_414c" name="hdmf_414c" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['hdmf_414c'])) {
											  ?>value="<?php echo $_GET['hdmf_414c'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="phic_ee">PHIC EE <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="phic_ee" name="phic_ee" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $phic_ee ?>">
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="emdeco_439a">OTC Emdeco </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="emdeco_439a" name="emdeco_439a" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['emdeco_439a'])) {
											  ?>value="<?php echo $_GET['emdeco_439a'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="bir_412">BIR Tax </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="bir_412" name="bir_412" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['bir_412'])) {
											  ?>value="<?php echo $_GET['bir_412'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="dad">Salary Deductions </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="dad" name="dad" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['dad'])) {
											  ?>value="<?php echo $_GET['dad'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="ln_solid"></div>
									  <div class="form-group">
										<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
										  <input type="hidden" name="submitted" value="1">
										  <input type="hidden" name="pid" value="<?php echo $pid ?>">
										  <input type="hidden" name="eid" value="<?php echo $eid ?>">
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

