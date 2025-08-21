<?php

include("db.php");
include("sqli.php");
include("html.php");
define('PAGE', 'add_tc.php'); 

session_start();

if (isset($_SESSION['user_id'])) {
	if (validate_level(PAGE) == true) {

		if(isset($_POST['submitted'])) { // if submit button has been pressed
			$sid = filter_var($_POST['sid'], FILTER_SANITIZE_NUMBER_INT);
			$tid = filter_var($_POST['tid'], FILTER_SANITIZE_NUMBER_INT);
			$cname = filter_var($_POST['cname'], FILTER_SANITIZE_STRING);
			$addr = filter_var($_POST['addr'], FILTER_SANITIZE_STRING);
			$chairman = filter_var($_POST['chairman'], FILTER_SANITIZE_STRING);
			$chairman_num = filter_var($_POST['chairman_num'], FILTER_SANITIZE_STRING);
			$contact_num = filter_var($_POST['contact_num'], FILTER_SANITIZE_STRING);
			$email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
			$new_otc_num = filter_var($_POST['new_otc_num'], FILTER_SANITIZE_STRING);
			$new_otc_date = filter_var($_POST['new_otc_date'], FILTER_SANITIZE_STRING);
			$new_cda_num = filter_var($_POST['new_cda_num'], FILTER_SANITIZE_STRING);
			$new_cda_date = filter_var($_POST['new_cda_date'], FILTER_SANITIZE_STRING);
			$pa_expiry = filter_var($_POST['pa_expiry'], FILTER_SANITIZE_STRING);
			$fed_id = filter_var($_POST['fed_id'], FILTER_SANITIZE_NUMBER_INT);
			
			// check if sector is given
			if ($sid != 0) { $a = TRUE; } 
			else { $a = FALSE; $message[] = "Please select from the list of sectors."; }
			// check if town is given
			if ($tid != 0) { $b = TRUE; } 
			else { $b = FALSE; $message[] = "Please select from the list of towns."; }
			// check if cooperative name is given
			if ($cname != "") { $c = TRUE; } 
			else { $c = FALSE; $message[] = "Please enter the name of cooperative."; }
			// check if address is given
			if ($addr != "") { $d = TRUE; } 
			else { $d = FALSE; $message[] = "Please enter the address."; }
			// check if chairman is given
			if ($chairman != "") { $e = TRUE; } 
			else { $e = FALSE; $message[] = "Please enter the name of chairman."; }
			
			// If data pass all tests, proceed
			if ( $a && $b && $c && $d && $e ) {
				// check for double entry first
				$sql = "SELECT cid 
						FROM cooperatives 
						WHERE cname = '$cname'";
				$res = query($sql); 
				$num = num_rows($res); 
				
				if ($num > 0) {
					?>
					<div class="alert alert-danger alert-dismissible fade in" role="alert">
						<strong>Transport cooperative already exists! </strong><br /><br />
						<a href="tc.php?cid=<?php echo $eid ?>">Click here to view the record</a>
					</div>
					<?php
				} else {
					$sql = "INSERT INTO cooperatives(sid, tid, ctype, fed_id, cname, addr, chairman, chairman_num, contact_num, email, new_otc_num, new_otc_date, new_cda_num, new_cda_date, pa_expiry, last_update_id, last_update)
							VALUES('$sid', '$tid', '2', '$fed_id', '$cname', '$addr', '$chairman', '$chairman_num', '$contact_num', '$email', '$new_otc_num', '$new_otc_date', '$new_cda_num', '$new_cda_date', '$pa_expiry', '$_SESSION[user_id]', NOW())";
					if (query($sql)) {
						$cid = mysqli_insert_id($connection);
						// log the activity
						log_user(1, 'cooperatives', $cid);
						
						header("Location:tc.php?cid=".$cid."&success=1");
						exit();
					} else {
						?>
						<div class="alert alert-danger alert-dismissible fade in" role="alert">
							<strong>Could not add transport cooperative because: </strong><br />
							<b><?php echo mysqli_error($connection) ?></b><br />
							The query was <?php echo $sql ?>.<br />
						</div>
						<?PHP
					}
				}
			} else {
				$serialized_message = serialize($message);
				// redirect the user to index page
				header('location: add_tc.php?serialized_message='.$serialized_message.'&sid='.$sid.'&tid='.$tid.'&cname='.$cname.'&addr='.$addr.'&chairman='.$chairman.'&chairman_num='.$chairman_num.'&email='.$email.'&new_otc_num='.$new_otc_num.'&new_otc_date='.$new_otc_date.'&new_cda_num='.$new_cda_num.'&new_cda_date='.$new_cda_date.'&pa_expiry='.$pa_expiry.'&fed_id='.$fed_id);
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
				  
				<title>OTC | Add New Transport Cooperative</title>

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
							<h3>Add New Transport Cooperative</h3>
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
							  <div class="x_content">
								<br />
								
									<form action="add_tc.php" method="POST" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="sid">Area <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <select id="sid" name="sid" required="required" class="form-control col-md-7 col-xs-12">
											<option value="0">--- Select ---</option>
											<?php
											$sql = "SELECT s.sid, CONCAT(a.aname,' - ',s.sname)
													FROM tc_areas a INNER JOIN tc_sectors s ON a.aid = s.aid 
													ORDER BY a.aid, s.sname";
											$sres = query($sql); 
											while ($srow = fetch_array($sres)) {
												if ($_GET['sid'] == $srow[0]) {
													?><option value="<?php echo $srow[0] ?>" Selected><?php echo $srow[1] ?></option><?php
												} else {
													?><option value="<?php echo $srow[0] ?>"><?php echo $srow[1] ?></option><?php
												}
											}
											mysqli_free_result($sres);
											?>
										  </select>
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="tid">City / Municipality <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <select id="tid" name="tid" required="required" class="form-control col-md-7 col-xs-12">
											<option value="0">--- Select ---</option>
											<?php
											$sql = "SELECT t.tid, CONCAT(p.pname,' - ',t.tname)
													FROM towns t 
														INNER JOIN districts d ON t.did = d.did
														INNER JOIN provinces p ON d.pid = p.pid
													ORDER BY p.pname, t.tname";
											$tres = query($sql); 
											while ($trow = fetch_array($tres)) {
												if ($_GET['tid'] == $trow[0]) {
													?><option value="<?php echo $trow[0] ?>" Selected><?php echo $trow[1] ?></option><?php
												} else {
													?><option value="<?php echo $trow[0] ?>"><?php echo $trow[1] ?></option><?php
												}
											}
											mysqli_free_result($tres);
											?>
										  </select>
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="cname">TC Name <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="cname" name="cname" required="required" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['cname'])) {
											  ?>value="<?php echo $_GET['cname'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="addr">Address <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="addr" name="addr" required="required" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['addr'])) {
											  ?>value="<?php echo $_GET['addr'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="chairman">Chairman <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="chairman" name="chairman" required="required" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['chairman'])) {
											  ?>value="<?php echo $_GET['chairman'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="chairman_num">Chairman's Number </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="chairman_num" name="chairman_num" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['chairman_num'])) {
											  ?>value="<?php echo $_GET['chairman_num'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="contact_num">Other Numbers </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="contact_num" name="contact_num" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['contact_num'])) {
											  ?>value="<?php echo $_GET['contact_num'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Email </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="email" name="email" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['email'])) {
											  ?>value="<?php echo $_GET['email'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="new_otc_num">OTC Accreditation Number </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="new_otc_num" name="new_otc_num" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['new_otc_num'])) {
											  ?>value="<?php echo $_GET['new_otc_num'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label for="new_otc_date" class="control-label col-md-3 col-sm-3 col-xs-12">OTC Accreditation Date </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input id="new_otc_date" name="new_otc_date" class="form-control col-md-7 col-xs-12" type="date" 
										  <?php
										  if (isset ($_GET['new_otc_date'])) {
											  ?>value="<?php echo $_GET['new_otc_date'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="new_cda_num">CDA Registration Number </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="new_cda_num" name="new_cda_num" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['new_cda_num'])) {
											  ?>value="<?php echo $_GET['new_cda_num'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label for="new_cda_date" class="control-label col-md-3 col-sm-3 col-xs-12">CDA Registration Date </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input id="new_cda_date" name="new_cda_date" class="form-control col-md-7 col-xs-12" type="date" 
										  <?php
										  if (isset ($_GET['new_cda_date'])) {
											  ?>value="<?php echo $_GET['new_cda_date'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label for="pa_expiry" class="control-label col-md-3 col-sm-3 col-xs-12">Provisional Accreditation Date </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input id="pa_expiry" name="pa_expiry" class="form-control col-md-7 col-xs-12" type="date" 
										  <?php
										  if (isset ($_GET['pa_expiry'])) {
											  ?>value="<?php echo $_GET['pa_expiry'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="fed_id">Federation </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <select id="fed_id" name="fed_id" class="form-control col-md-7 col-xs-12">
											<option value="0">--- Select ---</option>
											<?php
											$sql = "SELECT cid, cname FROM cooperatives WHERE ctype = 3 ORDER BY cname";
											$fres = query($sql); 
											while ($frow = fetch_array($fres)) {
												if ($_GET['fed_id'] == $frow[0]) {
													?><option value="<?php echo $frow[0] ?>" Selected><?php echo $frow[1] ?></option><?php
												} else {
													?><option value="<?php echo $frow[0] ?>"><?php echo $frow[1] ?></option><?php
												}
											}
											free_result($fres);
											?>
										  </select>
										</div>
									  </div>
									  
									  <div class="ln_solid"></div>
									  <div class="form-group">
										<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
										  <input type="hidden" name="submitted" value="1">
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
				<!-- Autosize -->
				<script src="vendors/autosize/dist/autosize.min.js"></script>
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

