<?php

include("db.php");
include("sqli.php");
include("html.php");
define('PAGE', 'add_procurement.php'); 

session_start();

if (isset($_SESSION['user_id'])) {
	if (validate_level(PAGE) == true) {

		if(isset($_POST['submitted'])) { // if submit button has been pressed
			$ref_num = clean_input($_POST['ref_num']);
			$did = clean_input($_POST['did']);
			$eid = clean_input($_POST['eid']);
			$proc_name = clean_input($_POST['proc_name']);
			$budget = clean_input($_POST['budget']);
			$ptid = clean_input($_POST['ptid']);
			$pmid = clean_input($_POST['pmid']);
			$publish_date = clean_input($_POST['publish_date']);
			$closing_date = clean_input($_POST['closing_date']);
			$remarks = clean_input($_POST['remarks']);
			
			// check if ref_num is given
			if ($ref_num != "") { $a = TRUE; } 
			else { $a = FALSE; $message[] = "Please enter the reference number."; }
			// check if did is given
			if ($did != 0) { $b = TRUE; } 
			else { $b = FALSE; $message[] = "Please select from the requesting divisions."; }
			// check if eid is given
			if ($eid != 0) { $c = TRUE; } 
			else { $c = FALSE; $message[] = "Please select from the requesting persons."; }
			// check if proc_name is given
			if ($proc_name != "") { $d = TRUE; } 
			else { $d = FALSE; $message[] = "Please enter the name of procurement project."; }
			// check if ptid is given
			if ($ptid != 0) { $e = TRUE; } 
			else { $e = FALSE; $message[] = "Please select from the list of types of procurement."; }
			// check if pmid is given
			if ($pmid != 0) { $f = TRUE; } 
			else { $f = FALSE; $message[] = "Please select from the list of modes of procurement."; }
			
			// If data pass all tests, proceed
			if ( $a && $b && $c && $d && $e  && $f ) {
				
				// check for double entry first 
				$sql = "SELECT pid 
						FROM procurements
						WHERE proc_name = '$proc_name' OR ref_num = '$ref_num'";
				$res = query($sql); 
				$num = num_rows($res); 
				
				if ($num > 0) {
					$row = fetch_array($res); 
					?>
					<div class="alert alert-danger alert-dismissible fade in" role="alert">
						<strong>Procurement already exists! </strong><br /><br />
						<a href="procurement.php?pid=<?php echo $row[0] ?>">Click here to view the record</a>
					</div>
					<?php
				} else {
					$sql = "INSERT INTO procurements( ref_num, did, eid, proc_name, budget, ptid, pmid, publish_date, closing_date, remarks, user_id, user_dtime )
							VALUES( '$ref_num', '$did', '$eid', '$proc_name', '$budget', '$ptid', '$pmid', '$publish_date', '$closing_date', '$remarks', '$_SESSION[user_id]', NOW() )";
					if (@query($sql)) {
						$pid = mysqli_insert_id($connection);
						// log the activity
						log_user(1, 'procurements', $pid);
						
						// add the requirements of the procurement 
						$sql = "SELECT rid, rnum, reid FROM requirements WHERE ptid = '$ptid'";
						$rres = query($sql); 
						while ($rrow = fetch_array($rres)) {
							if ($rrow[2] == 0) {
								$new_eid = $eid; 
							} elseif ($rrow[2] == -1) {
								$new_eid = -1;
							} elseif ($rrow[2] > 0) {
								$new_eid = $rrow[2]; 
							}
							
							$sql = "INSERT INTO procurement_requirements(pid, pr_num, rid, eid) VALUES('$pid', '$rrow[1]', '$rrow[0]', '$new_eid')";
							query($sql); 
						}
						free_result($rres); 
						
						header("Location: procurement.php?pid=".$pid);
						exit();
					} else {
						?>
						<div class="alert alert-danger alert-dismissible fade in" role="alert">
							<strong>Could not add procurement because: </strong><br />
							<b><?php echo mysqli_error($connection) ?></b><br />
							The query was <?php echo $sql ?>.<br />
						</div>
						<?PHP
					}
				}
				
			} else {
				$serialized_message = serialize($message);
				// redirect the user to index page
				header('location: add_procurement.php?serialized_message='.$serialized_message.'&ref_num='.$ref_num.'&eid='.$eid.'&did='.$did.'&proc_name='.$proc_name.'&budget='.$budget.'&ptid='.$ptid.'&pmid='.$pmid.'&publish_date='.$publish_date.'&closing_date='.$closing_date.'&remarks='.$remarks);
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
				  
				<title>OTC | Add Procurement</title>

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
							<h3>Add Procurement</h3>
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
								
									<form action="add_procurement.php" method="POST" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="ref_num">Reference No. <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="ref_num" name="ref_num" required="required" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['ref_num'])) {
											  ?>value="<?php echo $_GET['ref_num'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="ptid">Type of Procurement <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <select id="ptid" name="ptid" class="form-control col-md-7 col-xs-12">
											<option value="0"> -- Select -- </option>
											<?php
											$sql = "SELECT ptid, pt_name FROM procurement_types";
											$res = query($sql); 
											while ($row = fetch_array($res)) {
												if (isset ($_GET['ptid'])) {
													if ($_GET['ptid'] == $row[0]) {
														?><option value="<?php echo $row[0] ?>" Selected><?php echo $row[1] ?></option><?php
													} else {
														?><option value="<?php echo $row[0] ?>"><?php echo $row[1] ?></option><?php
													}
												} else {
													?><option value="<?php echo $row[0] ?>"><?php echo $row[1] ?></option><?php
												}
											}
											free_result($res); 
											?>
										  </select>
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="pmid">Procurement Mode <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <select id="pmid" name="pmid" class="form-control col-md-7 col-xs-12">
											<option value="0"> -- Select -- </option>
											<?php
											$sql = "SELECT pmid, CONCAT(pm_name,' - ',pm_desc) FROM procurement_modes";
											$res = query($sql); 
											while ($row = fetch_array($res)) {
												if (isset ($_GET['pmid'])) {
													if ($_GET['pmid'] == $row[0]) {
														?><option value="<?php echo $row[0] ?>" Selected><?php echo $row[1] ?></option><?php
													} else {
														?><option value="<?php echo $row[0] ?>"><?php echo $row[1] ?></option><?php
													}
												} else {
													?><option value="<?php echo $row[0] ?>"><?php echo $row[1] ?></option><?php
												}
											}
											free_result($res); 
											?>
										  </select>
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="did">Requesting Unit <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <select id="did" name="did" class="form-control col-md-7 col-xs-12">
											<option value="0"> -- Select -- </option>
											<?php
											$sql = "SELECT did, dname FROM departments ORDER BY dname";
											$res = query($sql); 
											while ($row = fetch_array($res)) {
												if (isset ($_GET['did'])) {
													if ($_GET['did'] == $row[0]) {
														?><option value="<?php echo $row[0] ?>" Selected><?php echo $row[1] ?></option><?php
													} else {
														?><option value="<?php echo $row[0] ?>"><?php echo $row[1] ?></option><?php
													}
												} else {
													?><option value="<?php echo $row[0] ?>"><?php echo $row[1] ?></option><?php
												}
											}
											free_result($res); 
											?>
										  </select>
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="eid">Requesting Person <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <select id="eid" name="eid" class="form-control col-md-7 col-xs-12">
											<option value="0"> -- Select -- </option>
											<?php
											$sql = "SELECT eid, CONCAT(lname,', ',fname) FROM employees WHERE active = 1 ORDER BY lname, fname";
											$res = query($sql); 
											while ($row = fetch_array($res)) {
												if (isset ($_GET['eid'])) {
													if ($_GET['eid'] == $row[0]) {
														?><option value="<?php echo $row[0] ?>" Selected><?php echo $row[1] ?></option><?php
													} else {
														?><option value="<?php echo $row[0] ?>"><?php echo $row[1] ?></option><?php
													}
												} else {
													?><option value="<?php echo $row[0] ?>"><?php echo $row[1] ?></option><?php
												}
											}
											free_result($res); 
											?>
										  </select>
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="proc_name">Name of Procurement Project <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="proc_name" name="proc_name" required="required" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['proc_name'])) {
											  ?>value="<?php echo $_GET['proc_name'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="budget">Approved Budget <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="budget" name="budget" required="required" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['budget'])) {
											  ?>value="<?php echo $_GET['budget'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="publish_date">Publish Date </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="date" id="publish_date" name="publish_date" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['publish_date'])) {
											  ?>value="<?php echo $_GET['publish_date'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="closing_date">Closing Date </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="date" id="closing_date" name="closing_date" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['closing_date'])) {
											  ?>value="<?php echo $_GET['closing_date'] ?>"<?php
										  }
										  ?>
										  >
										</div>
									  </div>
									  <div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="remarks">Remarks </label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" id="remarks" name="remarks" class="form-control col-md-7 col-xs-12" 
										  <?php
										  if (isset ($_GET['remarks'])) {
											  ?>value="<?php echo $_GET['remarks'] ?>"<?php
										  }
										  ?>
										  >
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

