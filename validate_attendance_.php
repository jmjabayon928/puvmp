<?php

include("db.php");
include("sqli.php");
include("html.php");

session_start();

$ip_addr = $_SERVER['REMOTE_ADDR'];
$string = '192.168.0.';

echo $ip_addr;

/*
if ( (strpos($ip_addr, $string) !== false) || $ip_addr == '::1' ) {
	$ecode = htmlentities(trim(real_escape_string($_GET['ecode'])), ENT_NOQUOTES);

	echo $ecode; 
	// handle the form
	if( !empty($ecode) ) {
		$sql = "SELECT eid, TIME_FORMAT(time_in, '%H:%i')
				FROM employees
				WHERE ecode = '$ecode' AND active = 1";
		$res = query($sql); 
		$num = num_rows($res); 
		
		if ($num > 0) {
			$row = fetch_array($res); 
			$eid = $row[0]; $sched_am_in = $row[1]; 
			
			$six_am = 21600; 
			$seven_am = 25200;
			$eight_am = 28800;
			$nine_am = 32400;
			$ten_am = 36000;
			$eleven_am = 39600;
			$twelve_pm = 43200;
			$thirteen_pm = 46800; 
			$fourteen_pm = 50400; 
			$fifteen_pm = 54000; 
			$sixteen_pm = 57600; 
			$seventeen_pm = 61200; 
			$eighteen_pm = 64800;

			if ($sched_am_in == '06:00') {
				$intended_am_in = $six_am;
				$intended_am_out = $ten_am;
				$intended_pm_in = $eleven_am;
				$intended_pm_out = $fifteen_pm;
			} elseif ($sched_am_in == '07:00') {
				$intended_am_in = $seven_am;
				$intended_am_out = $eleven_am;
				$intended_pm_in = $twelve_pm;
				$intended_pm_out = $sixteen_pm;
			} elseif ($sched_am_in == '08:00') {
				$intended_am_in = $eight_am;
				$intended_am_out = $twelve_pm;
				$intended_pm_in = $thirteen_pm;
				$intended_pm_out = $seventeen_pm;
			} elseif ($sched_am_in == '09:00') {
				$intended_am_in = $nine_am;
				$intended_am_out = $thirteen_pm;
				$intended_pm_in = $fourteen_pm;
				$intended_pm_out = $eighteen_pm;
			}
			
			// get the server time and minute
			$sql = "SELECT TIME_TO_SEC(NOW()), HOUR(NOW()), MINUTE(NOW())";
			$sres = query($sql); 
			$srow = fetch_array($sres); 
			$sec_now = $srow[0]; $hour_now = $srow[1]; $minute_now = $srow[2]; 
			
			if (isset($_POST['submit_button'])) {
				
				// check if an attendance for this employee and date already exists
				$sql = "SELECT aid, TIME_TO_SEC(am_in), TIME_TO_SEC(am_out), TIME_TO_SEC(pm_in), TIME_TO_SEC(pm_out) 
						FROM attendance 
						WHERE eid = '$eid' AND TO_DAYS(adate) = TO_DAYS(CURDATE())";
				$res = query($sql); 
				$num = num_rows($res); 
				
				if ($num > 0) {
					$row = fetch_array($res); 
					$aid = $row[0]; $am_in = $row[1]; $am_out = $row[2]; $pm_in = $row[3]; $pm_out = $row[4]; 
					
					if ( $am_in == 0 && $am_out == 0 && $pm_in == 0 && $pm_out == 0 ) {
						// no time in but has attendance for leave, dms, travel order, ob, etc
						
						if ( $sec_now < $intended_am_out ) {
							// update attendance
							$sql = "UPDATE attendance SET am_in = CURTIME() WHERE aid = '$aid'";
							if (@query($sql)) {
								// update time in attendance 
								update_time_in_attendance($aid); 
								// redirect to time_in_.php
								header('location: time_in_.php?eid='.$eid.'&success=1'); 
								exit(); 
							}
						} elseif ( $sec_now >= $intended_am_out && $sec_now < $intended_pm_out ) {
							// update attendance
							$sql = "UPDATE attendance SET pm_in = CURTIME() WHERE aid = '$aid'";
							if (@query($sql)) {
								// update time in attendance 
								update_time_in_attendance($aid); 
								// redirect to time_in_.php
								header('location: time_in_.php?eid='.$eid.'&success=2'); 
								exit(); 
							}
						}
					} elseif ( $am_in != 0 && $am_out == 0 && $pm_in == 0 && $pm_out == 0 ) {
						// already timed in in the morning
						
						if ( $sec_now <= $am_in ) { 
							// time in is less than or equal to recorded time in in the database
							// redirect to time_in_.php
							header('location: time_in_.php?wrong_timein=2'); 
							exit(); 
						} elseif ( $intended_am_in < $sec_now && $sec_now < $intended_pm_in ) {
							// time in is between intended am in and intended pm in
							// update attendance
							$sql = "UPDATE attendance SET am_out = CURTIME() WHERE aid = '$aid'";
							if (@query($sql)) {
								// update time in attendance 
								update_time_in_attendance($aid); 
								// redirect to time_in_.php
								header('location: time_in_.php?eid='.$eid.'&success=2'); 
								exit(); 
							}
						} elseif ( $sec_now >= $intended_pm_in && $sec_now < $intended_pm_out ) {
							// time in is between intended pm in and intended pm out
							// update attendance
							$sql = "UPDATE attendance SET pm_in = CURTIME(), status = -1 WHERE aid = '$aid'";
							if (@query($sql)) {
								// update time in attendance 
								update_time_in_attendance($aid); 
								// redirect to time_in_.php
								header('location: time_in_.php?eid='.$eid.'&success=2'); 
								exit(); 
							}
						}
					} elseif ( $am_in == 0 && $am_out == 0 && $pm_in != 0 && $pm_out == 0 ) {
						// already time in in the afternoon
						
						if ( $sec_now <= $pm_in ) { 
							// time in is less than or equal to recorded time in in the database
							// redirect to time_in_.php
							header('location: time_in_.php?wrong_timein=3'); 
							exit(); 
						} elseif ( $pm_in < $sec_now ) {
							// time in is greater than the recorded pm in
							// update attendance
							$sql = "UPDATE attendance SET pm_out = CURTIME() WHERE aid = '$aid'";
							if (@query($sql)) {
								// update time in attendance 
								update_time_in_attendance($aid); 
								// redirect to time_in_.php
								header('location: time_in_.php?eid='.$eid.'&success=2'); 
								exit(); 
							}
						}
					} elseif ( $am_in != 0 && $am_out != 0 && $pm_in == 0 && $pm_out == 0 ) {
						// already timed in in both morning in and morning out
						
						if ( $sec_now <= $am_out ) {
							// time in is less than or equal to recorded am out in the database
							// redirect to time_in_.php
							header('location: time_in_.php?wrong_timein=4'); 
							exit(); 
						} elseif ( $sec_now < $intended_am_out ) {
							// time in is before the intended am out
							// redirect to time_in_.php
							header('location: time_in_.php?wrong_timein=5'); 
							exit(); 
						} elseif ( $sec_now > $intended_am_out && $sec_now < $intended_pm_out ) {
							// time in is between intended am out and intended pm out
							// update attendance
							$sql = "UPDATE attendance SET pm_in = CURTIME() WHERE aid = '$aid'";
							if (@query($sql)) {
								// update time in attendance 
								update_time_in_attendance($aid); 
								// redirect to time_in_.php
								header('location: time_in_.php?eid='.$eid.'&success=2'); 
								exit(); 
							}
						}
						
					} elseif ( $am_in != 0 && $am_out != 0 && $pm_in != 0 && $pm_out == 0 ) {
						// already timed in in morning in, morning out and pm in
						
						if ( $sec_now <= $pm_in ) {
							// time in is less than or equal to recorded pm in in the database
							// redirect to time_in_.php
							header('location: time_in_.php?wrong_timein=6'); 
							exit(); 
						} elseif ( $sec_now > $intended_pm_in ) {
							// time in is later than intended pm in 
							// update attendance
							$sql = "UPDATE attendance SET pm_out = CURTIME() WHERE aid = '$aid'";
							if (@query($sql)) {
								// update time in attendance 
								update_time_in_attendance($aid); 
								// redirect to time_in_.php
								header('location: time_in_.php?eid='.$eid.'&success=2'); 
								exit(); 
							}
						}
						
					} elseif ( $am_in != 0 && $am_out == 0 && $pm_in != 0 && $pm_out == 0 ) {
						// am in and pm in are only present
						
						if ( $sec_now <= $pm_in ) {
							// time in is less than or equal to recorded pm in in the database
							// redirect to time_in_.php
							header('location: time_in_.php?wrong_timein=6'); 
							exit(); 
						} elseif ( $sec_now > $intended_pm_in ) {
							// time in is later than intended pm in 
							// update attendance
							$sql = "UPDATE attendance SET pm_out = CURTIME() WHERE aid = '$aid'";
							if (@query($sql)) {
								// update time in attendance 
								update_time_in_attendance($aid); 
								// redirect to time_in_.php
								header('location: time_in_.php?eid='.$eid.'&success=2'); 
								exit(); 
							}
						}
						
					}
					
					
					free_result($res); 
				} else {
					if ( ($sec_now <= $intended_am_in) || ($intended_am_in < $sec_now && $sec_now < $intended_am_out) ) { 
						// time in is earlier or same with intended am in OR time in is between intended am in and intended am out
						$sql = "INSERT INTO attendance( adate, am_in, eid, status ) VALUES( CURDATE(), CURTIME(), '$eid', 1 )";
						if (@query($sql)) {
							$aid = mysqli_insert_id($connection);
							// update time in attendance 
							update_time_in_attendance($aid); 
							
							// redirect to time_in_.php
							header('location: time_in_.php?eid='.$eid.'&success=1'); 
							exit(); 
						}
					} elseif ( $sec_now >= $intended_am_out && $sec_now < $intended_pm_out ) {
						// time in is between intended am out and intended pm out
						$sql = "INSERT INTO attendance( adate, pm_in, eid, status, undertime ) VALUES( CURDATE(), CURTIME(), '$eid', 1, 240 )";
						if (@query($sql)) {
							$aid = mysqli_insert_id($connection);
							// update time in attendance 
							update_time_in_attendance($aid); 
							
							// redirect to time_in_.php
							header('location: time_in_.php?eid='.$eid.'&success=3'); 
							exit(); 
						}
					} elseif ( $sec_now >= $intended_pm_out ) {
						// time in is on or later the intended pm out
						header('location: time_in_.php?wrong_timein=1'); 
						exit(); 
					}
				}				
			}			
		} else {
			// redirect to time_in_.php
			header('location: time_in_.php?mismatched=2'); 
			exit(); 
		}
		
		free_result($res); 

	} else {
		// redirect to time_in_.php
		header('location: time_in_.php?mismatched=1'); 
		exit(); 
	}
} else {
	// redirect to time_in_.php
	header('location: time_in_.php?wrong_timein=7'); 
	exit(); 
}

*/
