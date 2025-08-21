<?php

include("db.php");
include("sqli.php");
include("html.php");


function validate_user() {
	$sql = "SELECT ip_address, session_id, token1, token2, token3, token4, user_agent, activity
			FROM users_online
			WHERE user_id = '$_SESSION[user_id]'";
	$res = mysql_query($sql); $row = mysql_fetch_array($res);
	
	$ip_address = $row[0]; 
	$session_id = $row[1]; 
	$token1 = $row[2]; 
	$token2 = $row[3]; 
	$token3 = $row[4]; 
	$token4 = $row[5]; 
	$user_agent = $row[6]; 
	$activity = $row[7]; 
	
	$inactivity = 60;
	
	// verify if the the session id and user's session id are the same
	if ($session_id != session_id()) {
		show_invalid_sid(0);
	}
	// verify if session token1 is the same with token1 in db
	elseif($_SESSION['token1'] != $token1) {
		show_invalid_sid(1);
	}
	// verify if session token2 is the same with token2 in db
	elseif($_SESSION['token2'] != $token2) {
		show_invalid_sid(2);
	}
	// verify if session token3 is the same with token in db
	elseif($_SESSION['token3'] != $token3) {
		show_invalid_sid(3);
	}
	// verify if session token4 is the same with token4 in db
	elseif($_SESSION['token4'] != $token4) {
		show_invalid_sid(4);
	}
	// verify if the browser software is the same
	elseif($user_agent != $_SERVER['HTTP_USER_AGENT']) {
		show_invalid_user_agent();
	}
	// verify if the user has been inactive for $inactivity mins
	elseif( time() > ( $activity + ($inactivity * 60) ) ) {
		show_session_expired($inactivity);
	} else { 
		update_user();
		return true;
	}
	mysql_free_result($res); 
	mysql_free_result($res2); 
}

function update_user() {
	$maxtime = time() - 1800;

	mysql_query("UPDATE users_online SET refurl='{$_SERVER['HTTP_REFERER']}', activity = '".time()."' WHERE user_id = '$_SESSION[user_id]'");
	mysql_query("UPDATE users SET last_url = '{$_SERVER['HTTP_REFERER']}' WHERE user_id = '$_SESSION[user_id]' LIMIT 1");
	mysql_query("DELETE FROM users_online WHERE activity < '$maxtime'");
}

function clear_inactive() {
	$maxtime = time() - 1800;
	mysql_query("DELETE FROM users_online WHERE activity < '$maxtime'");
}

