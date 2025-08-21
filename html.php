<?php

function site_title() {
	?><a href="index.php" class="site_title"><img src="images/logo50.png"> <span>OTC</span></a><?php
}

function profile_quick_info() {
	?>
	<div class="profile clearfix">
	  <div class="profile_pic">
		<img src="<?php echo $_SESSION['emp_picture'] ?>" alt="..." class="img-circle profile_img">
	  </div>
	  <div class="profile_info">
		<span>Welcome,</span>
		<h2><?php echo $_SESSION['username'] ?></h2>
	  </div>
	</div>
	<?php
}

function sidebar_menu() {
	?>
	<div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
	  <div class="menu_section">
		<ul class="nav side-menu">
		  <?php
		  $sql = "SELECT c.cid
				  FROM routings r INNER JOIN communications c ON r.cid = c.cid
				  WHERE r.to_user_id = '$_SESSION[user_id]' AND r.receiver_id = 0";
		  $res = query($sql); 
		  $num = num_rows($res); 
		  
		  if ($num > 0) {
			  ?><li><a><i class="fa fa-exclamation-triangle"></i>Alerts &nbsp; <span class="badge bg-red"><?php echo $num ?></span></a></li><?php
		  }
		  ?>
		  <li><a><i class="fa fa-home"></i> Home <span class="fa fa-chevron-down"></span></a>
			<ul class="nav child_menu">
			  <li><a href="index.php">Home Page</a></li>
			  <li><a href="index2.php">Dashboard</a></li>
			  <li><a href="index3.php">Verified TCs</a></li>
			  <li><a href="index4.php">Employees Locator</a></li>
			</ul>
		  </li>
		  <li><a href="transactions.php"><i class="fa fa-edit"></i>Transactions Management </a></li>
		  <li><a><i class="fa fa-files-o"></i>Communications & Docs <span class="fa fa-chevron-down"></span></a>
			<ul class="nav child_menu">
				<li><a>Communications Routing<span class="fa fa-chevron-down"></span></a>
				  <ul class="nav child_menu">
				    <li><a href="communications.php?tab=0">All Communications</a></li>
				    <li><a href="communications.php?tab=1">Incoming</a></li>
				    <li><a href="communications.php?tab=2">Out-Going</a></li>
				    <li><a href="communications.php?tab=3">Internal</a></li>
				  </ul>
				</li>
				<li><a href="efiles.php">Documents Management</a></li>
			</ul>
		  </li>
		  <li><a><i class="fa fa-calendar"></i>Notifications & Calendar <span class="fa fa-chevron-down"></span></a>
			<ul class="nav child_menu">
			  <li><a>Notifications<span class="fa fa-chevron-down"></span></a>
				 <ul class="nav child_menu">
					<li><a href="announcements.php?tab=1">Past Notifications</a></li>
					<li><a href="announcements.php?tab=2">Current</a></li>
				 </ul>
			  </li>
			  <li><a>Calender of Activities<span class="fa fa-chevron-down"></span></a>
				 <ul class="nav child_menu">
					<li><a href="activities.php?tab=1">Past Activities</a></li>
					<li><a href="activities.php?tab=2">Current/Upcoming</a></li>
					<!-- <li><a href="activities.php?tab=3">Reports</a></li> -->
				 </ul>
			  </li>
			</ul>		  
		  </li>
		  <li><a><i class="fa fa-comments-o"></i> Planning & Evaluation <span class="fa fa-chevron-down"></span></a>
			<ul class="nav child_menu">
			  <li><a href="reports.php?tab=1">Accomplishment Reports</a></li>
			  <li><a href="reports.php?tab=2">Reports Monitoring</a></li>
			  <li><a href="reports.php?tab=3">Types of Reports</a></li>
			</ul>
		  </li>
		  <li><a><i class="fa fa-book"></i>Operations Division <span class="fa fa-chevron-down"></span></a>
			<ul class="nav child_menu">
			  <li><a>SMS<span class="fa fa-chevron-down"></span></a>
				 <ul class="nav child_menu">
					<li><a href="tc_monitoring.php?tab=1">Issued CGS</a></li>
					<!--
					<li><a href="tc_monitoring.php?tab=2">CGS Applications</a></li>
					<li><a href="tc_monitoring.php?tab=3">Endorsements</a></li>
					-->
				 </ul>
			  </li>
			  <!--
			  <li><a>TDS<span class="fa fa-chevron-down"></span></a>
				 <ul class="nav child_menu">
					<?php 
					$sql = "SELECT tid, tcode FROM tc_trainings";
					$res = query($sql); 
					while ($row = fetch_array($res)) {
						?><li><a href="tc_training.php?tab=<?PHP echo $row[0] ?>"><?PHP echo $row[1] ?></a></li><?php
					}
					free_result($res); 
					?>
				 </ul>
			  </li>
			  <li><a>POAS<span class="fa fa-chevron-down"></span></a>
				 <ul class="nav child_menu">
					<li><a href="tc_poas.php?tab=1">Endorsements</a></li>
					<li><a href="tc_poas.php?tab=2">Accreditation</a></li>
				 </ul>
			  </li>
			  -->
			  <li><a>TC Directory<span class="fa fa-chevron-down"></span></a>
				 <ul class="nav child_menu">
					<li><a href="tcs.php?tab=1">OTC - Accredited</a></li>
					<li><a href="tcs.php?tab=2">CDA - Registered</a></li>
					<li><a href="tcs.php?tab=3">Applying for CDA</a></li>
					<li><a href="tcs.php?tab=11">TC Federations</a></li>
					<li><a href="tcs.php?tab=12">Delisted / Inactive TCs</a></li>
					<li><a href="import_stat_data.php">Import TC-CGS</a></li>
				 </ul>
			  </li>
			  <li><a>TC Reports<span class="fa fa-chevron-down"></span></a>
				 <ul class="nav child_menu">
					<li><a href="tcs.php?tab=13">Summary</a></li>
					<li><a href="tcs.php?tab=4">No. of Units</a></li>
					<li><a href="tcs.php?tab=5">No. of Members</a></li>
					<li><a href="tcs.php?tab=6">Assets & Liabilities</a></li>
					<li><a href="tcs.php?tab=7">Capitalizations</a></li>
					<li><a href="tcs.php?tab=8">Net Surplus</a></li>
					<li><a href="tcs.php?tab=10">TCs By Type of Unit</a></li>
					<li><a href="tcs.php?tab=14">TCs With Valid CGS</a></li>
				 </ul>
			  </li>
			  <li><a href="tcs.php?tab=9">TC - Incomplete Records</a></li>
			</ul>
		  </li>
		  <li><a href="pmo_reports.php"><i class="fa fa-bus"></i>PMO Reports</a></li>
		  <li><a><i class="fa fa-certificate"></i>LTFRB Franchises <span class="fa fa-chevron-down"></span></a>
			<ul class="nav child_menu">
			  <li><a href="ltfrb_franchises.php">Franchises</a></li>
			  <li><a href="import_ltfrb_franchises.php">Import Franchise</a></li>
			</ul>		  
		  </li>
		  <li><a><i class="fa fa-users"></i>HRIS & Payroll System <span class="fa fa-chevron-down"></span></a>
			<ul class="nav child_menu">
			  <li><a>Human Resource IS<span class="fa fa-chevron-down"></span></a>
				 <ul class="nav child_menu">
					<li><a href="employees.php?tab=1">Employees</a></li>
					<li><a href="hris.php?tab=1">Hirings</a></li>
					<li><a href="hris.php?tab=2">Promotions</a></li>
					<li><a href="hris.php?tab=3">Separations</a></li>
					<li><a href="hris.php?tab=4">Performance Management</a></li>
					<li><a href="hris.php?tab=5">Learning & Development</a></li>
					<li><a href="hris.php?tab=6">Records Management</a></li>
				 </ul>
			  </li>
			  <li><a>Internal Communications<span class="fa fa-chevron-down"></span></a>
				 <ul class="nav child_menu">
				    <li><a href="internals.php?tab=1">Special Orders</a></li>
				    <li><a href="internals.php?tab=2">Office Orders</a></li>
				    <li><a href="internals.php?tab=3">Memorandum Orders</a></li>
				    <li><a href="internals.php?tab=4">Memorandum Circulars</a></li>
				    <li><a href="internals.php?tab=5">Travel Orders</a></li>
				 </ul>
			  </li>
			  <li><a>Payroll System<span class="fa fa-chevron-down"></span></a>
				 <ul class="nav child_menu">
					<li><a href="payrolls.php">Payrolls</a></li>
					<li><a href="payroll_reports.php">Payroll Reports</a></li>
					<!-- <li><a href="periods.php">Payroll Settings</a></li> -->
					<li><a href="credits.php">Leave Credits</a></li>
					<li><a href="leaves.php">Leave Applications</a></li>
					<li><a href="cocs.php">Earned COCs</a></li>
				 </ul>
			  </li>
			  <li><a>Attendance<span class="fa fa-chevron-down"></span></a>
				 <ul class="nav child_menu">
					<li><a href="period_attendance.php?tab=1">By Employee</a></li>
					<li><a href="period_attendance.php?tab=2">By Date</a></li>
					<li><a href="period_attendance.php?tab=3">By Type of Employee</a></li>
					<li><a href="import_attendance.php">Import Attendance</a></li>
					<li><a href="dms.php">Disposition Mission Slip</a></li>
				 </ul>
			  </li>
			</ul>
		  </li>
		  <li><a><i class="fa fa-paypal"></i>Procurement System <span class="fa fa-chevron-down"></span></a>
			<ul class="nav child_menu">
			  <li><a href="procurements.php?tab=1">Types of Procurement</a></li>
			  <li><a href="procurements.php?tab=2">Procurement Modes</a></li>
			  <li><a href="procurements.php?tab=3">Procurement Requirements</a></li>
			  <li><a href="procurements.php?tab=4">Browse Procurements</a></li>
			</ul>
		  </li>
		  <li><a><i class="fa fa-cubes"></i>Inventory Management <span class="fa fa-chevron-down"></span></a>
			<ul class="nav child_menu">
			  <li><a href="inventory.php?tab=1">By Category</a></li>
			  <li><a href="inventory.php?tab=2">By Division</a></li>
			  <li><a href="inventory.php?tab=3">By Employee</a></li>
			  <!-- <li><a href="inventory.php?tab=">By Year Acquired</a></li> -->
			</ul>
		  </li>
		  <li><a><i class="fa fa-cogs"></i>Settings <span class="fa fa-chevron-down"></span></a>
			<ul class="nav child_menu">
			  <li><a>OTC Settings<span class="fa fa-chevron-down"></span></a>
				 <ul class="nav child_menu">
					<li><a href="otc_settings.php?tab=1">Divisions</a></li>
					<li><a href="otc_settings.php?tab=2">Positions</a></li>
					<li><a href="otc_settings.php?tab=3">Employee Types</a></li>
					<li><a href="otc_settings.php?tab=4">Salary Grades</a></li>
					<li><a href="otc_settings.php?tab=5">Suppliers</a></li>
				 </ul>
			  </li>
			  <li><a>System Settings<span class="fa fa-chevron-down"></span></a>
				 <ul class="nav child_menu">
					<li><a href="sys_settings.php?tab=1">Regional Areas</a></li>
					<li><a href="sys_settings.php?tab=2">File Types</a></li>
					<li><a href="sys_settings.php?tab=3">Item Categories</a></li>
					<!-- <li><a href="sys_settings.php?tab=4">Pricings</a></li> -->
				 </ul>
			  </li>
			  <li><a>H.R. Settings<span class="fa fa-chevron-down"></span></a>
				 <ul class="nav child_menu">
					<li><a href="hris_settings.php?tab=1">Salary Standardization</a></li>
					<li><a href="hris_settings.php?tab=2">Salary Periods</a></li>
					<li><a href="hris_settings.php?tab=3">Salary Groups</a></li>
					<li><a href="hris_settings.php?tab=4">BIR Brackets</a></li>
					<li><a href="hris_settings.php?tab=5">GSIS Brackets</a></li>
					<li><a href="hris_settings.php?tab=6">PhilHealth Brackets</a></li>
					<li><a href="hris_settings.php?tab=7">SSS Brackets</a></li>
					<!--
					<li><a href="hris_settings.php?tab=">Types of Benefit</a></li>
					<li><a href="hris_settings.php?tab=">Types of Tax Payer</a></li>
					<li><a href="hris_settings.php?tab=">Taxation Modes</a></li>
					<li><a href="hris_settings.php?tab=">Types of Cash Advance</a></li>
					-->
				 </ul>
			  </li>
			  <li><a>Users' Settings<span class="fa fa-chevron-down"></span></a>
				 <ul class="nav child_menu">
					<li><a href="users.php?tab=1">Users</a></li>
					<li><a href="users.php?tab=2">Users' - Access</a></li>
					<li><a href="users.php?tab=3">Access Details</a></li>
					<li><a href="users.php?tab=4">Online Users</a></li>
				 </ul>
			  </li>
			  <li><a href="logs.php">Transactions Log</a></li>
			</ul>
		  </li>
		  <li><a href="change_password.php"><i class="fa fa-key"></i>Change Password</a></li>
		  <li><a href="logout.php"><i class="fa fa-sign-out"></i>Log Out</a></li>
		</ul>
	  </div>
	</div>
	<!-- /menu footer buttons -->
	<div class="sidebar-footer hidden-small">
	  <a data-toggle="tooltip" data-placement="top" title="FullScreen">
		<span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
	  </a>
	  <a data-toggle="tooltip" data-placement="top" title="Lock">
		<span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
	  </a>
	  <a data-toggle="tooltip" data-placement="top" title="Personal Settings" href="change_password.php">
		<span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
	  </a>
	  <a data-toggle="tooltip" data-placement="top" title="Logout" href="logout.php">
		<span class="glyphicon glyphicon-off" aria-hidden="true"></span>
	  </a>
	</div>
	<!-- /menu footer buttons -->
	<?php
}

function top_navigation() {
	?>
	<div class="top_nav">
	  <div class="nav_menu">
		<nav>
		  <div class="nav toggle">
			<a id="menu_toggle"><i class="fa fa-bars"></i></a>
		  </div>

		  <ul class="nav navbar-nav navbar-right">
			<li role="presentation" class="dropdown">
			<?php
			$sql = "SELECT c.cid, CONCAT(e.lname,', ',e.fname), e.picture, c.subject,
						CONCAT(
						   FLOOR(HOUR(TIMEDIFF(NOW(), r.user_dtime)) / 24), 'd: ',
						   MOD(HOUR(TIMEDIFF(NOW(), r.user_dtime)), 24), 'h: ',
						   MINUTE(TIMEDIFF(NOW(), r.user_dtime)), 'm')
					FROM routings r 
						INNER JOIN communications c ON r.cid = c.cid
						INNER JOIN employees e ON r.from_user_id = e.eid
					WHERE r.to_user_id = '$_SESSION[user_id]' AND r.receiver_id = 0
					ORDER BY r.fdate DESC";
			$res = query($sql); 
			$num = num_rows($res); 
			
			if ($num > 0) {
				?>
                  <a href="javascript:;" class="dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="false">
                    <i class="fa fa-envelope-o"></i>
                    <span class="badge bg-green"><?php echo $num ?></span>
                  </a>
				  <ul id="menu1" class="dropdown-menu list-unstyled msg_list" role="menu">
				<?php
				while ($row = fetch_array($res)) {
					?>
						<li>
						  <a href="communication.php?cid=<?php echo $row[0] ?>">
							<span class="image"><img src="<?php echo $row[2] ?>" alt="Profile Image" /></span>
							<span>
							  <span><?php echo $row[1] ?></span>
							  <span class="time"><?php echo $row[4] ?></span>
							</span>
							<span class="message"><?php echo $row[3] ?></span>
						  </a>
						</li>
					<?php
				}
				?>
				  </ul>
				<?php
			}
			?>
			</li>
			
			<li role="presentation" class="dropdown">
			<?php
			if ($_SESSION['user_id'] == 30 || $_SESSION['user_id'] == 34) { // sir medel and ma'am neliza
				$sql = "SELECT r.rid, CONCAT(e.lname,', ',e.fname), e.picture, 
							rt.rt_name, DATEDIFF(NOW(), r.due_date)
						FROM reports r 
							LEFT JOIN reports_types rt ON r.rtid = rt.rtid 
							LEFT JOIN employees e ON r.eid = e.eid
							LEFT JOIN users u ON u.eid = e.eid 
						WHERE r.status != 1";			
			} else {
				$sql = "SELECT r.rid, CONCAT(e.lname,', ',e.fname), e.picture, 
							rt.rt_name, DATEDIFF(NOW(), r.due_date)
						FROM reports r 
							LEFT JOIN reports_types rt ON r.rtid = rt.rtid 
							LEFT JOIN employees e ON r.eid = e.eid
							LEFT JOIN users u ON u.eid = e.eid 
						WHERE u.user_id = '$_SESSION[user_id]' AND r.status != 1
						UNION
						SELECT r.rid, CONCAT(e.lname,', ',e.fname), e.picture, 
							rt.rt_name, DATEDIFF(NOW(), r.due_date)
						FROM reports r 
							LEFT JOIN reports_types rt ON r.rtid = rt.rtid 
							LEFT JOIN employees e ON r.eid = e.eid 
							LEFT JOIN departments d ON r.did = d.did 
						WHERE r.status != 1 AND d.head = '$_SESSION[user_id]'";			
			}
			$res = query($sql); 
			$num = num_rows($res); 
			
			if ($num > 0) {
				?>
                  <a href="javascript:;" class="dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="false">
                    <i class="fa fa-exclamation-triangle"></i>
                    <span class="badge bg-red"><?php echo $num ?></span>
                  </a>
				  <ul id="menu1" class="dropdown-menu list-unstyled msg_list" role="menu">
				<?php
				while ($row = fetch_array($res)) {
					?>
						<li>
						  <a href="report.php?rid=<?php echo $row[0] ?>">
							<span class="image"><img src="<?php echo $row[2] ?>" alt="Profile Image" /></span>
							<span>
							  <span><?php echo $row[1] ?></span>
							  <span class="time"><?php echo $row[4] ?> days</span>
							</span>
							<span class="message"><?php echo $row[3] ?></span>
						  </a>
						</li>
					<?php
				}
				?>
				  </ul>
				<?php
			}
			?>
			</li>
			
		  </ul>
		</nav>
	  </div>
	</div>
	<!-- /top navigation -->
	<?php
}

function footer() {
	?>
	<!-- footer content -->
	<footer>
	  <div class="pull-right">Gentelella - Bootstrap</div>
	  <div class="clearfix"></div>
	</footer>
	<!-- /footer content -->
	<?php
}