<?php

include("db.php");
include("sqli.php");
include("html.php");

session_start();

$username = htmlentities(trim(real_escape_string($_POST['username'])), ENT_NOQUOTES);
$password = htmlentities(trim(real_escape_string($_POST['password'])), ENT_NOQUOTES);


// handle the form
if( (!empty($username)) && (!empty($password)) ) {
	$sql = "SELECT user_id, CONCAT(fname,' ',lname), ulevel, eid
			FROM users 
			WHERE username = '$username' AND password = md5('$password') AND active = 1";
	$res = query($sql);
	$num = num_rows($res); 
	
	if ($num > 0) {
		$sql = "SELECT DATE_FORMAT(CURDATE(),'%Y%m')";
		$dres = query($sql); 
		$drow = fetch_array($dres); 
		$logs_tbl = 'logs_'.$drow[0]; 
		
		$row = fetch_array($res); 
		
		$eid = $row[3];
		
		if ($eid != 0) {
			// user is employee of OTC
			$sql = "SELECT picture FROM employees WHERE eid = '$eid'";
			$eres = query($sql); 
			$erow = fetch_array($eres); 
			$_SESSION['emp_picture'] = $erow[0];
			free_result($eres); 
		} else {
			// user is NOT an employee of OTC
			$_SESSION['emp_picture'] = 'images/user.png';
		}
		
		// register into sessions
		$_SESSION['user_id'] = $row[0];
		$_SESSION['username'] = $row[1];
		$_SESSION['ulevel'] = $row[2];
		$_SESSION['session_key'] = md5("OTC S3cret K3y".session_id());
		$_SESSION['session_token'] = md5("sodium chloride".session_id());
		//$_SESSION['emp_picture'] = $row[3];
		
		// create cookies
		setcookie('user_id', $row[0]);
		setcookie('cookie_id', session_id());
		setcookie('cookie_key', md5("OTC S3cret K3y".session_id())); 
		setcookie('cookie_token', md5("sodium chloride".session_id())); 
		
		// if user_id is in sessions table already, update session. insert user_id in sessions otherwise
		
		$sql = "SELECT user_id FROM sessions WHERE user_id = '$row[0]'";
		$sres = query($sql); 
		$snum = num_rows($sres); 
		
		if ($snum > 0) {
			$query1 = "UPDATE sessions 
					   SET session_id = '".session_id()."', 
					       session_key = '$_SESSION[session_key]',
						   session_token = '$_SESSION[session_token]',
						   activity = '".time()."',
						   session_ip = '$_SERVER[REMOTE_ADDR]'
					   WHERE user_id = '$row[0]'";
		} else {
			// insert into users sessions
			$query1 = "INSERT INTO sessions( user_id, session_id, session_key, session_token, activity, session_ip ) 
					   VALUES( '$row[0]', '".session_id()."', '$_SESSION[session_key]', '$_SESSION[session_token]', ".time().", '$_SERVER[REMOTE_ADDR]' )";
		}
		// update employee's last logged in date and time
		$query2 = "UPDATE users SET last_login = NOW(), last_ip = '$_SERVER[REMOTE_ADDR]' 
				   WHERE user_id = '$_SESSION[user_id]' LIMIT 1";
		// insert into logs
		$query3 = "INSERT INTO ".$logs_tbl."( log_dtime, log_type, user_id ) VALUES( NOW(), 0, '$_SESSION[user_id]' )";
		
		if( @query($query1) && @query($query2) && @query($query3) ) {
			// redirect the user to index page
			header('location: index.php');
			exit();
		} else {
			// redirect back to login page
			header('location: login.php?error=3');
			exit();
		}
	} else {
		// redirect back to login page
		header('location: login.php?error=1');
		exit();
	}
	free_result($res); 
} else {
	// redirect back to login page
	header('location: login.php?error=2');
	exit();
}
