<?php

function log_user($log_type, $table_name, $pkid) {
	// if not a super user, log the action
	if ($_SESSION['ulevel'] != 1) {
		$sql = "SELECT DATE_FORMAT(CURDATE(),'%Y%m')";
		$dres = query($sql); 
		$drow = fetch_array($dres); 
		$logs_tbl = 'logs_'.$drow[0]; 
		
		$sql = "INSERT INTO ".$logs_tbl."( log_dtime, log_type, user_id, table_name, pkid ) 
				VALUES( NOW(), '$log_type', '$_SESSION[user_id]', '$table_name', '$pkid' )";
		query($sql); 
	}
}

function query($sql) {
	global $connection;
	
	$result = mysqli_query($connection, $sql) or die(); 
	
	return $result; 
}

function num_rows($sql) {
	$num = mysqli_num_rows($sql); 
	
	return $num;
}

function fetch_array($sql) {
	$fetch = mysqli_fetch_array($sql, MYSQLI_NUM); 
	return $fetch; 
}

function free_result($sql) {
	mysqli_free_result($sql);
}

function real_escape_string($string) {
	global $connection;
	
	$escaped_string = mysqli_real_escape_string($connection, $string);
	
	return $escaped_string; 
}

function clean_input($data) {
	$data = trim($data); 
	$data = stripslashes($data); 
	$data = htmlspecialchars($data); 
	$data = real_escape_string($data); 
	return $data; 
}

function validate_level($page) {
	// 1 is super user, 2 is admin
	if ( $_SESSION['ulevel'] == 1 || $_SESSION['ulevel'] == 2 ) { 
		if (validate_user() == true) return true;
	} else {
		// check if user is allowed in this page
		$sql = "SELECT ap.ap_id
				FROM users_accesses ua 
					INNER JOIN accesses a ON ua.aid = a.aid
					INNER JOIN accesses_pages ap ON a.aid = ap.aid
					INNER JOIN pages p ON ap.page_id = p.page_id
				WHERE ua.user_id = '$_SESSION[user_id]' AND p.page_name = '$page'";
		$res = query($sql); 
		$num = num_rows($res); 
		
		if ($num > 0) { 
			if (validate_user() == true) return true;
		} else { 
			// redirect the user to not-allowed page
			header('location: denied.php');
			exit();
		}
		free_result($res); 
	}
}

function validate_level2($page) {
	// 1 is super user, 2 is admin
	if ( $_SESSION['ulevel'] == 1 || $_SESSION['ulevel'] == 2 ) { 
		if (validate_user() == true) return true;
	} else {
		// check if user is allowed in this page
		$sql = "SELECT ap.ap_id
				FROM users_accesses ua 
					INNER JOIN accesses a ON ua.aid = a.aid
					INNER JOIN accesses_pages ap ON a.aid = ap.aid
					INNER JOIN pages p ON ap.page_id = p.page_id
				WHERE ua.user_id = '$_SESSION[user_id]' AND p.page_name = '$page'";
		$res = query($sql); 
		$num = num_rows($res); 
		
		if ($num > 0) { 
			if (validate_user() == true) return true;
		} else { 
		}
		free_result($res); 
	}
}

function validate_level3($page) {
	// 1 is super user, 2 is admin
	if ( $_SESSION['ulevel'] == 1 || $_SESSION['ulevel'] == 2 ) { 
		if (validate_user() == true) return true;
	} else {
		// check if user is allowed in this page
		$sql = "SELECT ap.ap_id
				FROM users_accesses ua 
					INNER JOIN accesses a ON ua.aid = a.aid
					INNER JOIN accesses_pages ap ON a.aid = ap.aid
					INNER JOIN pages p ON ap.page_id = p.page_id
				WHERE ua.user_id = '$_SESSION[user_id]' AND p.page_name = '$page'";
		$res = query($sql); 
		$num = num_rows($res); 
		
		if ($num > 0) { 
			if (validate_user() == true) return true;
		} else { 
			return false; 
		}
		free_result($res); 
	}
}

function validate_level4($page) {
	// 1 is super user, 2 is admin
	if ( $_SESSION['ulevel'] == 1 || $_SESSION['ulevel'] == 2 ) { 
		if (validate_user() == true) return true;
	} else {
		// check if user is allowed in this page
		$sql = "SELECT ap.ap_id
				FROM users_accesses ua 
					INNER JOIN accesses a ON ua.aid = a.aid
					INNER JOIN accesses_pages ap ON a.aid = ap.aid
					INNER JOIN pages p ON ap.page_id = p.page_id
				WHERE ua.user_id = '$_SESSION[user_id]' AND p.page_name = '$page'";
		$res = query($sql); 
		$num = num_rows($res); 
		
		if ($num > 0) { 
			if (validate_user() == true) return true;
		} else { 
			return false; 
		}
		free_result($res); 
	}
}

function validate_user() {
	// inactivity period
	$inactivity = 30;
	
	// cookie values
	$user_id = $_COOKIE['user_id'];
	$cookie_id = $_COOKIE['cookie_id'];
	$cookie_key = $_COOKIE['cookie_key'];
	$cookie_token = $_COOKIE['cookie_token'];
	
	// session-database values
	$sql = "SELECT session_id, session_key, session_token, activity
			FROM sessions
			WHERE user_id = '$_SESSION[user_id]'";
	$res = query($sql); 
	$row = fetch_array($res);
	
	$session_id = $row[0]; 
	$session_key = $row[1]; 
	$session_token = $row[2]; 
	$activity = $row[3]; 
	
	free_result($res); 
	
	// check if session id in database is the same with the server's session and and cookie session id
	if ( ($session_id != session_id()) || ($session_id != $_COOKIE['cookie_id']) ) {
		mismatched_ids();
	} 
	// check if key in database is the same witht the server's session key and cookie key
	elseif ( ($session_key != $_SESSION['session_key']) || ($session_key != $_COOKIE['cookie_key']) ) {
		mismatched_keys();
	} 
	// check if token in database is the same witht the server's session token and cookie token
	elseif ( ($session_token != $_SESSION['session_token']) || ($session_token != $_COOKIE['cookie_token']) ) {
		mismatched_tokens();
	} 
	// check if session user id is the same with cookie user id
	elseif ( $_SESSION['user_id'] != $_COOKIE['user_id'] ) {
		mismatched_users();
	}
	// verify if the user has been inactive for $inactivity mins
	elseif( time() > ( $activity + ($inactivity * 60) ) ) {
		$inactive = (time() - $activity) / 60; 
		show_session_expired($inactive);
	} else { 
		update_user();
		delete_inactive();
		return true;
	}
}

function delete_inactive() {
	$maxtime = time() - 1800;
	query("DELETE FROM sessions WHERE activity < '$maxtime'");
}

function update_user() {
	/*
	// update the session id
	session_regenerate_id();
	
	// update sessions table
	$sql = "UPDATE sessions 
			SET session_id = '".session_id()."',
				session_token = '".md5("sodium chloride".session_id())."',
				refurl = '$_SERVER[HTTP_REFERER]', 
				activity = '".time()."' 
			WHERE user_id = '$_SESSION[user_id]' LIMIT 1";
	query($sql);
	
	// update the cookies
	setcookie('cookie_id', session_id());
	setcookie('cookie_token', md5("sodium chloride".session_id())); 
	*/
	
	// update sessions table
	$sql = "UPDATE sessions 
			SET activity = '".time()."' 
			WHERE user_id = '$_SESSION[user_id]' LIMIT 1";
	query($sql);
}

function mismatched_ids() {
	$sql = "DELETE FROM sessions WHERE user_id = '$_SESSION[user_id]' LIMIT 1";
	
	if (@query($sql)) {
		// destroy session
		unset($_SESSION);
		session_destroy(); 
		
		// destroy cookie
		setcookie('user_id', '', time() - 3600);
		setcookie('cookie_id', '', time() - 3600);
		setcookie('cookie_key', '', time() - 3600);
		setcookie('cookie_token', '', time() - 3600);
		
		// redirect the user to login page
		header('location: login.php?mismatched=1');
		exit();
	}
}

function mismatched_keys() {
	$sql = "DELETE FROM sessions WHERE user_id = '$_SESSION[user_id]' LIMIT 1";
	
	if (@query($sql)) {
		// destroy session
		unset($_SESSION);
		session_destroy(); 
		
		// destroy cookie
		setcookie('user_id', '', time() - 3600);
		setcookie('cookie_id', '', time() - 3600);
		setcookie('cookie_key', '', time() - 3600);
		setcookie('cookie_token', '', time() - 3600);
		
		// redirect the user to login page
		header('location: login.php?mismatched=2');
		exit();
	}
}

function mismatched_tokens() {
	$sql = "DELETE FROM sessions WHERE user_id = '$_SESSION[user_id]' LIMIT 1";
	
	if (@query($sql)) {
		// destroy session
		unset($_SESSION);
		session_destroy(); 
		
		// destroy cookie
		setcookie('user_id', '', time() - 3600);
		setcookie('cookie_id', '', time() - 3600);
		setcookie('cookie_key', '', time() - 3600);
		setcookie('cookie_token', '', time() - 3600);
		
		// redirect the user to login page
		header('location: login.php?mismatched=3');
		exit();
	}
}

function mismatched_users() {
	$sql = "DELETE FROM sessions WHERE user_id = '$_SESSION[user_id]' LIMIT 1";
	
	if (@query($sql)) {
		// destroy session
		unset($_SESSION);
		session_destroy(); 
		
		// destroy cookie
		setcookie('user_id', '', time() - 3600);
		setcookie('cookie_id', '', time() - 3600);
		setcookie('cookie_key', '', time() - 3600);
		setcookie('cookie_token', '', time() - 3600);
		
		// redirect the user to login page
		header('location: login.php?mismatched=4');
		exit();
	}
}

function show_session_expired($inactive) {
	$sql = "DELETE FROM sessions WHERE user_id = '$_SESSION[user_id]' LIMIT 1";
	
	if (@query($sql)) {
		// destroy session
		unset($_SESSION);
		session_destroy(); 
		
		// destroy cookie
		setcookie('user_id', '', time() - 3600);
		setcookie('cookie_id', '', time() - 3600);
		setcookie('cookie_key', '', time() - 3600);
		setcookie('cookie_token', '', time() - 3600);
		
		// redirect the user to login page
		header('location: login.php?expire='.$inactive);
		exit();
	}
}


function update_capitalizations($cid) {
	$sql = "SELECT init_stock, present_stock, subscribed, paid_up, scheme
			FROM cooperatives_capitalizations
			WHERE cid = '$cid' AND (init_stock != '0.00' OR present_stock != '0.00' OR subscribed != '0.00' OR paid_up != '0.00' OR scheme != '')
			ORDER BY cyear DESC
			LIMIT 0, 1";
	$res = query($sql); 
	$row = fetch_array($res); 

	$sql = "UPDATE cooperatives
			SET init_stock = '$row[0]',
				present_stock = '$row[1]',
				subscribed = '$row[2]',
				paid_up = '$row[3]',
				scheme = '$row[4]'
			WHERE cid = '$cid'
			LIMIT 1";
	query($sql);
}

function update_financials($cid) {
	$sql = "SELECT current_assets, fixed_assets, liabilities, equity, net_income
			FROM cooperatives_financials
			WHERE cid = '$cid' AND (current_assets != '0.00' OR fixed_assets != '0.00' OR liabilities != '0.00' OR equity != '0.00' OR net_income != '0.00')
			ORDER BY cyear DESC
			LIMIT 0, 1";
	$res = query($sql); 
	$row = fetch_array($res); 
	
	$sql = "UPDATE cooperatives
			SET current_assets = '$row[0]',
				fixed_assets = '$row[1]',
				liabilities = '$row[2]',
				equity = '$row[3]',
				net_income = '$row[4]'
			WHERE cid = '$cid'
			LIMIT 1";
	query($sql);
}

function update_members($cid) {
	$sql = "SELECT operator_rm + operator_rf + operator_am + operator_af,
				driver_rm + driver_rf + driver_am + driver_af,
				worker_rm + worker_rf + worker_am + worker_af,
				other_rm + other_rf + other_am + other_af
			FROM cooperatives_members2
			WHERE cid = '$cid' 
				AND (operator_rm != 0 OR operator_rf != 0 OR operator_am != 0 OR operator_af != 0 
					OR driver_rm != 0 OR driver_rf != 0 OR driver_am != 0 OR driver_af != 0 
					OR worker_rm != 0 OR worker_rf != 0 OR worker_am != 0 OR worker_af != 0
					OR other_rm != 0 OR other_rf != 0 OR other_am != 0 OR other_af != 0)
			ORDER BY cyear DESC
			LIMIT 0, 1";
	$res = query($sql); 
	$row = fetch_array($res); 
	
	$sql = "UPDATE cooperatives
			SET operators_num = '$row[0]',
				drivers_num = '$row[1]', 
				workers_num = '$row[2]', 
				others_num = '$row[3]' 
			WHERE cid = '$cid'
			LIMIT 1";
	query($sql);
}

function update_surplus($cid) {
	$sql = "SELECT reserved, educ_training, comm_dev, optional, dividends
			FROM cooperatives_surplus 
			WHERE cid = '$cid' AND (reserved != '0.00' OR educ_training != '0.00' OR comm_dev != '0.00' OR optional != '0.00' OR dividends != '0.00')
			ORDER BY cyear DESC
			LIMIT 0, 1";
	$res = query($sql); 
	$row = fetch_array($res); 
	
	$sql = "UPDATE cooperatives
			SET reserved = '$row[0]', 
				educ_training = '$row[1]', 
				comm_dev = '$row[2]', 
				optional = '$row[3]', 
				dividends = '$row[4]'
			WHERE cid = '$cid'
			LIMIT 1";
	query($sql);
}

function update_units($cid) {
	$sql = "SELECT puj_num, mch_num, taxi_num, mb_num, bus_num, mcab_num, truck_num, tours_num, uvx_num, banka_num
			FROM cooperatives_units 
			WHERE cid = '$cid' AND (puj_num != 0 OR mch_num != 0 OR taxi_num != 0 OR mb_num != 0 OR bus_num != 0 OR mcab_num != 0 OR truck_num != 0 OR tours_num != 0 OR uvx_num != 0 OR banka_num != 0)
			ORDER BY cyear DESC
			LIMIT 0, 1";
	$res = query($sql); 
	$row = fetch_array($res); 
	
	$sql = "UPDATE cooperatives
			SET puj_num = '$row[0]',
				mch_num = '$row[1]',
				taxi_num = '$row[2]',
				mb_num = '$row[3]',
				bus_num = '$row[4]',
				mcab_num = '$row[5]',
				truck_num = '$row[6]',
				tours_num = '$row[7]',
				uvx_num = '$row[8]',
				banka_num = '$row[9]'
			WHERE cid = '$cid'
			LIMIT 1";
	query($sql);
}

function update_cgs_exp($cid) {
	$sql = "SELECT cgs_date, cgs_exp
			FROM cooperatives_cgs
			WHERE cid = '$cid'
			ORDER BY cgs_date DESC
			LIMIT 0, 1";
	$cres = query($sql); 
	$crow = fetch_array($cres); 
	
	$sql = "UPDATE cooperatives
			SET last_cgs = '$crow[0]', cgs_expiry = '$crow[1]'
			WHERE cid = '$cid'
			LIMIT 1";
	query($sql); 
	
	free_result($cres); 
}

function update_time_in_attendance($aid) {
	$tardy_am = 0; $tardy_pm = 0; $undertime_am = 0; $undertime_pm = 0; 
	$tardy = 0; $undertime = 0; $status = -1; 
	
	$sql = "SELECT eid, adate, TIME_FORMAT(am_in, '%H:%i'), 
				TIME_FORMAT(am_out, '%H:%i'), 
				TIME_FORMAT(pm_in, '%H:%i'), 
				TIME_FORMAT(pm_out, '%H:%i') 
			FROM attendance 
			WHERE aid = '$aid'";
	$eres = query($sql); 
	$erow = fetch_array($eres); 
	
	$eid = $erow[0]; $adate = $erow[1]; $am_in = $erow[2]; $am_out = $erow[3]; $pm_in = $erow[4]; $pm_out = $erow[5]; 
	
	$sql = "SELECT TIME_FORMAT(time_in, '%H:%i')
			FROM employees 
			WHERE eid = '$eid'";
	$time_res = query($sql); $time_row = fetch_array($time_res); 
	
	$intended_am_in = $time_row[0];
	
	if ($intended_am_in == '06:00') {
		$intended_am_out = '10:00';
		$intended_pm_in = '11:00';
		$intended_pm_out = '15:00';
	} elseif ($intended_am_in == '07:00') {
		$intended_am_out = '11:00';
		$intended_pm_in = '12:00';
		$intended_pm_out = '16:00';
	} elseif ($intended_am_in == '08:00') {
		$intended_am_out = '12:00';
		$intended_pm_in = '13:00';
		$intended_pm_out = '17:00';
	} elseif ($intended_am_in == '09:00') {
		$intended_am_out = '13:00';
		$intended_pm_in = '14:00';
		$intended_pm_out = '18:00';
	}

	if ( $am_in != '00:00' && $am_out == '00:00' && $pm_in == '00:00' && $pm_out == '00:00' ) { // am in
		$status = -1;
		$sql = "SELECT TIMESTAMPDIFF(MINUTE, '".$adate." ".$intended_am_in."', '".$adate." ".$am_in."')";
		$td_res = query($sql); $td_row = fetch_array($td_res); 

		if ($td_row[0] > 0) $tardy = $td_row[0];
		
		$sql = "UPDATE attendance
				SET tardiness = '$tardy' 
				WHERE aid = '$aid'
				LIMIT 1";
		query($sql); 
	} elseif ( $am_in != '00:00' && $am_out != '00:00' && $pm_in == '00:00' && $pm_out == '00:00') { // am in, am out
		$status = -1;
		$sql = "SELECT TIMESTAMPDIFF(MINUTE, '".$adate." ".$intended_am_in."', '".$adate." ".$am_in."'), 
					TIMESTAMPDIFF(MINUTE, '".$adate." ".$am_out."', '".$adate." ".$intended_am_out."')";
		$td_res = query($sql); $td_row = fetch_array($td_res); 
		
		if ($td_row[0] > 0) $tardy_am = $td_row[0];
		if ($td_row[1] > 0) $undertime_am = $td_row[1]; 
		
		$tardy = $tardy_am;
		$undertime = $undertime_am;
		
		$sql = "UPDATE attendance
				SET status = '$status', tardiness = '$tardy', undertime = '$undertime'
				WHERE aid = '$aid'
				LIMIT 1";
		query($sql); 
		
	} elseif ( $am_in == '00:00' && $am_out == '00:00' && $pm_in != '00:00' && $pm_out == '00:00') { // pm in
		$status = -1;
		$sql = "SELECT TIMESTAMPDIFF(MINUTE, '".$adate." ".$intended_pm_in."', '".$adate." ".$pm_in."' )";
		$td_res = query($sql); $td_row = fetch_array($td_res); 
		
		if ($td_row[0] > 0) $tardy = $td_row[0];
		
		$sql = "UPDATE attendance
				SET status = '$status', tardiness = '$tardy' 
				WHERE aid = '$aid'
				LIMIT 1";
		query($sql); 
	} elseif ( $am_in == '00:00' && $am_out == '00:00' && $pm_in != '00:00' && $pm_out != '00:00') { // pm in, pm out
		$status = -1;
		$sql = "SELECT TIMESTAMPDIFF(MINUTE, '".$adate." ".$intended_pm_in."', '".$adate." ".$pm_in."' ),
					TIMESTAMPDIFF(MINUTE, '".$adate." ".$pm_out."', '".$adate." ".$intended_pm_out."')";
		$td_res = query($sql); $td_row = fetch_array($td_res); 
		
		if ($td_row[0] > 0) $tardy = 240 + $td_row[0];
		if ($td_row[1] > 0) $undertime = $td_row[1]; 
		
		$sql = "UPDATE attendance
				SET status = '$status', tardiness = '$tardy' 
				WHERE aid = '$aid'
				LIMIT 1";
		query($sql); 
	} elseif ( $am_in != '00:00' && $am_out != '00:00' && $pm_in != '00:00' && $pm_out == '00:00' ) { // am in, am out, pm in
		$status = -1;
		$sql = "SELECT TIMESTAMPDIFF(MINUTE, '".$adate." ".$intended_am_in."', '".$adate." ".$am_in."'), 
					TIMESTAMPDIFF(MINUTE, '".$adate." ".$am_out."', '".$adate." ".$intended_am_out."'),
					TIMESTAMPDIFF(MINUTE, '".$adate." ".$intended_pm_in."', '".$adate." ".$pm_in."')";
		$td_res = query($sql); $td_row = fetch_array($td_res); 
		
		if ($td_row[0] > 0) $tardy_am = $td_row[0];
		if ($td_row[1] > 0) $undertime_am = $td_row[1]; 
		if ($td_row[2] > 0) $tardy_pm = $td_row[2];
		
		$tardy = $tardy_am + $tardy_pm;
		$undertime = $undertime_am;
		
		$sql = "UPDATE attendance
				SET status = '$status', tardiness = '$tardy', undertime = '$undertime'
				WHERE aid = '$aid'
				LIMIT 1";
		query($sql); 
		
	} elseif ( $am_in != '00:00' && $am_out != '00:00' && $pm_in != '00:00' && $pm_out != '00:00' ) { // whole day present
		$status = 1;
		$sql = "SELECT TIMESTAMPDIFF(MINUTE, '".$adate." ".$intended_am_in."', '".$adate." ".$am_in."'), 
					TIMESTAMPDIFF(MINUTE, '".$adate." ".$am_out."', '".$adate." ".$intended_am_out."'),
					TIMESTAMPDIFF(MINUTE, '".$adate." ".$intended_pm_in."', '".$adate." ".$pm_in."'), 
					TIMESTAMPDIFF(MINUTE, '".$adate." ".$pm_out."', '".$adate." ".$intended_pm_out."')";
		$td_res = query($sql); $td_row = fetch_array($td_res); 
		
		if ($td_row[0] > 0) $tardy_am = $td_row[0];
		if ($td_row[1] > 0) $undertime_am = $td_row[1]; 
		if ($td_row[2] > 0) $tardy_pm = $td_row[2];
		if ($td_row[3] > 0) $undertime_pm = $td_row[3]; 
		
		$tardy = $tardy_am + $tardy_pm;
		$undertime = $undertime_am + $undertime_pm;
		
		$sql = "UPDATE attendance
				SET status = '$status', tardiness = '$tardy', undertime = '$undertime'
				WHERE aid = '$aid'
				LIMIT 1";
		query($sql); 
		
	} elseif ( $am_in != '00:00' && $am_out == '00:00' && $pm_in != '00:00' && $pm_out != '00:00' ) { // all are present except am out
		$sql = "SELECT TIMESTAMPDIFF(MINUTE, '".$adate." ".$intended_am_in."', '".$adate." ".$am_in."'), 
					TIMESTAMPDIFF(MINUTE, '".$adate." ".$intended_pm_in."', '".$adate." ".$pm_in."'), 
					TIMESTAMPDIFF(MINUTE, '".$adate." ".$pm_out."', '".$adate." ".$intended_pm_out."')";
		$td_res = query($sql); $td_row = fetch_array($td_res); 
		
		if ($td_row[0] > 0) $tardy_am = $td_row[0];
		if ($td_row[1] > 0) $tardy_pm = $td_row[1];
		if ($td_row[2] > 0) $undertime_pm = $td_row[2]; 
		
		$tardy = $tardy_am + $tardy_pm;
		$undertime = $undertime_pm;
		
		$sql = "UPDATE attendance
				SET status = -1, tardiness = '$tardy', undertime = '$undertime'
				WHERE aid = '$aid'
				LIMIT 1";
		query($sql); 
		
	} elseif ( $am_in != '00:00' && $am_out != '00:00' && $pm_in == '00:00' && $pm_out != '00:00' ) { // all are present except pm in
		$sql = "SELECT TIMESTAMPDIFF(MINUTE, '".$adate." ".$intended_am_in."', '".$adate." ".$am_in."'), 
					TIMESTAMPDIFF(MINUTE, '".$adate." ".$am_out."', '".$adate." ".$intended_am_out."'),
					TIMESTAMPDIFF(MINUTE, '".$adate." ".$pm_out."', '".$adate." ".$intended_pm_out."')";
		$td_res = query($sql); $td_row = fetch_array($td_res); 
		
		if ($td_row[0] > 0) $tardy_am = $td_row[0];
		if ($td_row[1] > 0) $undertime_am = $td_row[1]; 
		if ($td_row[2] > 0) $undertime_pm = $td_row[2]; 
		
		$tardy = $tardy_am;
		$undertime = $undertime_am + $undertime_pm;
		
		$sql = "UPDATE attendance
				SET status = -1, tardiness = '$tardy', undertime = '$undertime'
				WHERE aid = '$aid'
				LIMIT 1";
		query($sql); 
		
	}

}

function update_attendance($aid) {
	$tardy_am = 0; $tardy_pm = 0; $undertime_am = 0; $undertime_pm = 0; 
	$tardy = 0; $undertime = 0; $status = -1; 
	
	$sql = "SELECT eid, adate, TIME_FORMAT(am_in, '%H:%i'), 
				TIME_FORMAT(am_out, '%H:%i'), 
				TIME_FORMAT(pm_in, '%H:%i'), 
				TIME_FORMAT(pm_out, '%H:%i'), status
			FROM attendance 
			WHERE aid = '$aid'";
	$eres = query($sql); 
	$erow = fetch_array($eres); 
	
	$eid = $erow[0]; $adate = $erow[1]; $am_in = $erow[2]; $am_out = $erow[3]; $pm_in = $erow[4]; $pm_out = $erow[5]; $input_status = $erow[6]; 
	
	$sql = "SELECT TIME_FORMAT(time_in, '%H:%i')
			FROM employees 
			WHERE eid = '$eid'";
	$time_res = query($sql); $time_row = fetch_array($time_res); 
	
	$intended_am_in = $time_row[0];
	
	if ($intended_am_in == '06:00') {
		$intended_am_out = '10:00';
		$intended_pm_in = '11:00';
		$intended_pm_out = '13:00';
	} elseif ($intended_am_in == '07:00') {
		$intended_am_out = '11:00';
		$intended_pm_in = '12:00';
		$intended_pm_out = '16:00';
	} elseif ($intended_am_in == '08:00') {
		$intended_am_out = '12:00';
		$intended_pm_in = '13:00';
		$intended_pm_out = '17:00';
	} elseif ($intended_am_in == '09:00') {
		$intended_am_out = '13:00';
		$intended_pm_in = '14:00';
		$intended_pm_out = '18:00';
	}

	if ($am_in != '00:00' && $am_out != '00:00' && $pm_in != '00:00' && $pm_out != '00:00') { // whole day present
		$status = 1;
		$sql = "SELECT TIMESTAMPDIFF(MINUTE, '".$adate." ".$intended_am_in."', '".$adate." ".$am_in."'), 
					TIMESTAMPDIFF(MINUTE, '".$adate." ".$am_out."', '".$adate." ".$intended_am_out."'),
					TIMESTAMPDIFF(MINUTE, '".$adate." ".$intended_pm_in."', '".$adate." ".$pm_in."'), 
					TIMESTAMPDIFF(MINUTE, '".$adate." ".$pm_out."', '".$adate." ".$intended_pm_out."')";
		$td_res = query($sql); $td_row = fetch_array($td_res); 
		
		if ($td_row[0] > 0) $tardy_am = $td_row[0];
		if ($td_row[1] > 0) $undertime_am = $td_row[1]; 
		if ($td_row[2] > 0) $tardy_pm = $td_row[2];
		if ($td_row[3] > 0) $undertime_pm = $td_row[3]; 
		
		$tardy = $tardy_am + $tardy_pm;
		$undertime = $undertime_am + $undertime_pm;
		
		$sql = "UPDATE attendance
				SET status = '$status', tardiness = '$tardy', undertime = '$undertime'
				WHERE aid = '$aid'
				LIMIT 1";
		query($sql); 
		
	} elseif ($am_in != '00:00' && $am_out != '00:00' && $pm_in == '00:00' && $pm_out == '00:00') { // am present
		$status = 1;
		$sql = "SELECT TIMESTAMPDIFF(MINUTE, '".$adate." ".$intended_am_in."', '".$adate." ".$am_in."'), 
					TIMESTAMPDIFF(MINUTE, '".$adate." ".$am_out."', '".$adate." ".$intended_am_out."')";
		$td_res = query($sql); $td_row = fetch_array($td_res); 
		
		if ($input_status == 14) {
			$undertime = 0;
			
			if ($td_row[0] > 0) $tardy = $td_row[0];
			if ($td_row[1] > 0) $undertime += $td_row[1]; 
			
			$sql = "UPDATE attendance
					SET status = '$input_status', tardiness = '$tardy', undertime = '$undertime'
					WHERE aid = '$aid'
					LIMIT 1";
			query($sql); 
		} else {
			$undertime = 240;
			
			if ($td_row[0] > 0) $tardy = $td_row[0];
			if ($td_row[1] > 0) $undertime += $td_row[1]; 
			
			$sql = "UPDATE attendance
					SET status = '$status', tardiness = '$tardy', undertime = '$undertime'
					WHERE aid = '$aid'
					LIMIT 1";
			query($sql); 
		}
		
	} elseif ($am_in == '00:00' && $am_out == '00:00' && $pm_in != '00:00' && $pm_out != '00:00') { // pm present
		$status = 1;
		$sql = "SELECT TIMESTAMPDIFF(MINUTE, '".$adate." ".$intended_pm_in."', '".$adate." ".$pm_in."'), 
					TIMESTAMPDIFF(MINUTE, '".$adate." ".$pm_out."', '".$adate." ".$intended_pm_out."')";
		$td_res = query($sql); $td_row = fetch_array($td_res); 
		
		$tardy = 240;
		
		if ($td_row[0] > 0) $tardy += $td_row[0];
		if ($td_row[1] > 0) $undertime += $td_row[1]; 
		
		$sql = "UPDATE attendance
				SET status = '$status', tardiness = '$tardy', undertime = '$undertime'
				WHERE aid = '$aid'
				LIMIT 1";
		query($sql); 
	} else {
		$sql = "UPDATE attendance
				SET status = '$status' 
				WHERE aid = '$aid'
				LIMIT 1";
		query($sql); 
		
	}
	
}

function insert_to_attendance($tid, $eid) {
	// get the type of employee - COS or JO
	$sql = "SELECT etype FROM employees WHERE eid = '$eid'";
	$eres = query($sql); $erow = fetch_array($eres); $etype = $erow[0]; 
	
	$sql = "SELECT to_days(fr_date), to_days(to_date), user_id, user_dtime, tnum
			FROM internals_travel_orders
			WHERE tid = '$tid'";
	$res = query($sql);
	while ($row = fetch_array($res)) {
		$int_start = $row[0]; $int_end = $row[1]; $user_id = $row[2]; $user_dtime = $row[3]; $tnum = $row[4]; 
		
		for ($x=$int_start; $x<=$int_end; $x++) {
			
			$sql = "SELECT DATE_FORMAT(FROM_DAYS('$x'), '%w'), DATE_FORMAT(FROM_DAYS('$x'), '%Y-%m-%d')"; 
			$dres = query($sql); $drow = fetch_array($dres); 
			
			if ($etype == 2) {
				$sql = "SELECT aid FROM attendance WHERE eid = '$eid' AND TO_DAYS(adate) = '$x'";
				$ares = query($sql); 
				$anum = num_rows($ares); 
				
				if ($anum > 0) {
					$arow = fetch_array($ares); 
					$aid = $arow[0]; 
					
					$sql = "UPDATE attendance
							SET status = '12',
								remarks = 'Travel Order ".$tnum."',
								last_update_id = '$approve_id',
								last_update = '$approve_dtime'
							WHERE aid = '$aid'
							LIMIT 1";
					query($sql); 
				} else {
					$sql = "INSERT INTO attendance(eid, adate, status, remarks, last_update_id, last_update)
							VALUES('$eid', '$drow[1]', '12', 'Travel Order ".$tnum."', '$user_id', '$user_dtime')";
					query($sql); 
				}
			} elseif ($etype == 3) {
				// if employee is JO, include any day
				// check if an attendance already exists
				$sql = "SELECT aid FROM attendance WHERE eid = '$eid' AND TO_DAYS(adate) = '$x'";
				$ares = query($sql); 
				$anum = num_rows($ares); 
				
				if ($anum > 0) {
					$arow = fetch_array($ares); 
					$aid = $arow[0]; 
					
					$sql = "UPDATE attendance
							SET status = '12',
								remarks = 'Travel Order ".$tnum."',
								last_update_id = '$approve_id',
								last_update = '$approve_dtime'
							WHERE aid = '$aid'
							LIMIT 1";
					query($sql); 
				} else {
					$sql = "INSERT INTO attendance(eid, adate, status, remarks, last_update_id, last_update)
							VALUES('$eid', '$drow[1]', '12', 'Travel Order ".$tnum."', '$user_id', '$user_dtime')";
					query($sql); 
				}
			} elseif ($etype == 4) { 
				// if employee is COS, do not include saturdays and sundays
				if ($drow[0] > 0 && $drow[0] < 6) {
					
					// check if an attendance already exists
					$sql = "SELECT aid FROM attendance WHERE eid = '$eid' AND TO_DAYS(adate) = '$x'";
					$ares = query($sql); 
					$anum = num_rows($ares); 
					
					if ($anum > 0) {
						$arow = fetch_array($ares); 
						$aid = $arow[0]; 
						
						$sql = "UPDATE attendance
								SET status = '12',
									remarks = 'Travel Order ".$tnum."',
									last_update_id = '$approve_id',
									last_update = '$approve_dtime'
								WHERE aid = '$aid'
								LIMIT 1";
						query($sql); 
					} else {
						$sql = "INSERT INTO attendance(eid, adate, status, remarks, last_update_id, last_update)
								VALUES('$eid', '$drow[1]', '12', 'Travel Order ".$tnum."', '$user_id', '$user_dtime')";
						query($sql); 
					}
					
				}
				
			}
			
		}
	}
	free_result($res); 
}

function insert_mo_attendance($oid, $eid) {
	// for consideration:
	// JO employees must have attendance during week ends
	$sql = "SELECT to_days(fr_date), to_days(to_date), user_id, user_dtime, onum
			FROM internals_memo_orders
			WHERE oid = '$oid'";
	$res = query($sql);
	while ($row = fetch_array($res)) {
		$int_start = $row[0]; $int_end = $row[1]; $user_id = $row[2]; $user_dtime = $row[3]; $onum = $row[4]; 
		
		if ( $int_start != '' && $int_end != '') {
			for ($x=$int_start; $x<=$int_end; $x++) {
				
				// if employee is NOT a JO
				// else
				$sql = "SELECT DATE_FORMAT(FROM_DAYS('$x'), '%w'), DATE_FORMAT(FROM_DAYS('$x'), '%Y-%m-%d')"; 
				$dres = query($sql); $drow = fetch_array($dres); 
				
				if ($drow[0] > 0 && $drow[0] < 6) {
					
					// check if an attendance already exists
					$sql = "SELECT aid FROM attendance WHERE eid = '$eid' AND TO_DAYS(adate) = '$x'";
					$ares = query($sql); 
					$anum = num_rows($ares); 
					
					if ($anum > 0) {
						$arow = fetch_array($ares); 
						$aid = $arow[0]; 
						
						$sql = "UPDATE attendance
								SET status = '17',
									remarks = 'Memo Order ".$onum."',
									last_update_id = '$approve_id',
									last_update = '$approve_dtime'
								WHERE aid = '$aid'
								LIMIT 1";
						query($sql); 
					} else {
						$sql = "INSERT INTO attendance(eid, adate, status, remarks, last_update_id, last_update)
								VALUES('$eid', '$drow[1]', '17', 'Memo Order ".$onum."', '$user_id', '$user_dtime')";
						query($sql); 
					}
					
				}
			}
		}
		
	}
	free_result($res); 
}


function numtowords($num) { 
	$decones = array( 
				'01' => "One", 
				'02' => "Two", 
				'03' => "Three", 
				'04' => "Four", 
				'05' => "Five", 
				'06' => "Six", 
				'07' => "Seven", 
				'08' => "Eight", 
				'09' => "Nine", 
				'10' => "Ten", 
				'11' => "Eleven", 
				'12' => "Twelve", 
				'13' => "Thirteen", 
				'14' => "Fourteen", 
				'15' => "Fifteen", 
				'16' => "Sixteen", 
				'17' => "Seventeen", 
				'18' => "Eighteen", 
				'19' => "Nineteen" 
				);
	$ones = array( 
				0 => " ",
				1 => "One",     
				2 => "Two", 
				3 => "Three", 
				4 => "Four", 
				5 => "Five", 
				6 => "Six", 
				7 => "Seven", 
				8 => "Eight", 
				9 => "Nine", 
				10 => "Ten", 
				11 => "Eleven", 
				12 => "Twelve", 
				13 => "Thirteen", 
				14 => "Fourteen", 
				15 => "Fifteen", 
				16 => "Sixteen", 
				17 => "Seventeen", 
				18 => "Eighteen", 
				19 => "Nineteen" 
				); 
	$tens = array( 
				0 => "",
				2 => "Twenty", 
				3 => "Thirty", 
				4 => "Forty", 
				5 => "Fifty", 
				6 => "Sixty", 
				7 => "Seventy", 
				8 => "Eighty", 
				9 => "Ninety" 
				); 
	$hundreds = array( 
				"Hundred", 
				"Thousand", 
				"Million", 
				"Billion", 
				"Trillion", 
				"Quadrillion" 
				); //limit t quadrillion 
					
	$num = number_format($num,2,".",","); 
	$num_arr = explode(".",$num); 
	$wholenum = $num_arr[0]; 
	$decnum = $num_arr[1]; 
	$whole_arr = array_reverse(explode(",",$wholenum)); 
	krsort($whole_arr); 
	$rettxt = ""; 
	foreach($whole_arr as $key => $i){ 
		if($i < 20){ 
			$rettxt .= $ones[$i]; 
		}
		elseif($i < 100){ 
			$rettxt .= $tens[substr($i,0,1)]; 
			$rettxt .= " ".$ones[substr($i,1,1)]; 
		}
		else{ 
			$rettxt .= $ones[substr($i,0,1)]." ".$hundreds[0]; 
			$rettxt .= " ".$tens[substr($i,1,1)]; 
			$rettxt .= " ".$ones[substr($i,2,1)]; 
		} 
		if($key > 0){ 
			$rettxt .= " ".$hundreds[$key]." "; 
		} 

	} 
	$rettxt = $rettxt." pesos";

	if($decnum > 0){ 
		$rettxt .= " and "; 
		if($decnum < 20){ 
			$rettxt .= $decones[$decnum]; 
		}
		elseif($decnum < 100){ 
			$rettxt .= $tens[substr($decnum,0,1)]; 
			$rettxt .= " ".$ones[substr($decnum,1,1)]; 
		}
		$rettxt = $rettxt." cents"; 
	} 
	return $rettxt;
} 






