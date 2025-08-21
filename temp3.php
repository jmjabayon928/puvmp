<?php

include("db.php");
include("sqli.php");
include("html.php");

//session_start();

numtowords(16840.31);

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
	echo $rettxt;
} 


/*
// travel orders
$sql = "SELECT tid, to_days(fr_date), to_days(to_date), user_id, user_dtime
		FROM internals_travel_orders
		WHERE to_days(fr_date) >= 737515";
$res = query($sql);
while ($row = fetch_array($res)) {
	$tid = $row[0]; $int_start = $row[1]; $int_end = $row[2]; $user_id = $row[3]; $user_dtime = $row[4]; 
	
	for ($x=$int_start; $x<=$int_end; $x++) {
		
		$sql = "SELECT DATE_FORMAT(FROM_DAYS('$x'), '%w'), DATE_FORMAT(FROM_DAYS('$x'), '%Y-%m-%d')"; 
		$dres = query($sql); $drow = fetch_array($dres); 
		
		if ($drow[0] > 0 && $drow[0] < 6) {
			// get the employees for this travel order
			$sql = "SELECT eid FROM internals_travel_orders_employees WHERE tid = '$tid'";
			$eres = query($sql); 
			while ($erow = fetch_array($eres)) {
				$eid = $erow[0]; 
			
				// check if an attendance already exists
				$sql = "SELECT aid FROM attendance WHERE eid = '$eid' AND TO_DAYS(adate) = '$x'";
				$ares = query($sql); 
				$anum = num_rows($ares); 
				
				if ($anum > 0) {
					$arow = fetch_array($ares); 
					$aid = $arow[0]; 
					
					$sql = "UPDATE attendance
							SET status = '12',
								remarks = 'Updated by Leave Application',
								last_update_id = '$user_id',
								last_update = '$user_dtime'
							WHERE aid = '$aid'
							LIMIT 1";
					query($sql); 
				} else {
					$sql = "INSERT INTO attendance(eid, adate, status, remarks, last_update_id, last_update)
							VALUES('$eid', '$drow[1]', '12', 'Auto-encoded by Travel Order Application', '$user_id', '$user_dtime')";
					query($sql); 
				}
			}				
		}
	}
}
free_result($res); 
*/

/*
// leaves
$sql = "SELECT aid, eid, lid, to_days(fr_date), to_days(to_date), approve_id, approve_dtime
		FROM internals_leave_applications 
		WHERE to_days(fr_date) >= 737515 AND stats = 1";
$res = query($sql); 
while ($row = fetch_array($res)) {
	$aid = $row[0]; $eid = $row[1]; $lid = $row[2]; $start_date = $row[3]; $end_date = $row[4]; $approve_id = $row[5]; $approve_dtime = $row[6]; 
	
	for ($x=$start_date; $x<=$end_date; $x++) {
		$sql = "SELECT DATE_FORMAT(FROM_DAYS('$x'), '%w'), DATE_FORMAT(FROM_DAYS('$x'), '%Y-%m-%d')"; 
		$dres = query($sql); $drow = fetch_array($dres); 
		
		if ($drow[0] > 0 && $drow[0] < 6) {
			if ($lid == 1) { $status = 3;
			} elseif ($lid == 2) { $status = 4;
			} elseif ($lid == 3) { $status = 5;
			} elseif ($lid == 4) { $status = 6;
			} elseif ($lid == 5) { $status = 7;
			} elseif ($lid == 6) { $status = 8;
			} elseif ($lid == 7) { $status = 9;
			} elseif ($lid == 8) { $status = 10;
			} elseif ($lid == 9) { $status = 11;
			}
			// check if an attendance already exists
			$sql = "SELECT aid FROM attendance WHERE eid = '$eid' AND TO_DAYS(adate) = '$x'";
			$ares = query($sql); 
			$anum = num_rows($ares); 
			
			if ($anum > 0) {
				$sql = "UPDATE attendance
						SET status = '$status',
							remarks = 'Updated by Leave Application',
							last_update_id = '$approve_id',
							last_update = '$approve_dtime'
						WHERE aid = '$aid'
						LIMIT 1";
				query($sql); 
			} else {
				$sql = "INSERT INTO attendance(eid, adate, status, remarks, last_update_id, last_update)
						VALUES('$eid', '$drow[1]', '$status', 'Auto-encoded by Leave Application', '$approve_id', '$approve_dtime')";
				query($sql); 
			}
		}
	}
	
}
*/


/*
// dms
$sql = "SELECT did, eid, ddate, user_id, user_dtime
		FROM internals_dms
		WHERE to_days(ddate) >= 737515 AND did != 131";
$res = query($sql); 
while ($row = fetch_array($res)) {
	$did = $row[0]; $eid = $row[1]; $ddate = $row[2]; $user_id = $row[3]; $user_dtime = $row[4]; 
	
	// check if attendance already exists
	$sql = "SELECT aid FROM attendance WHERE eid = '$eid' AND adate = '$ddate'";
	$ares = query($sql); 
	$anum = num_rows($ares); 
	
	if ($anum > 0) {
		$arow = fetch_array($ares); 
		$aid = $arow[0];
		
		$sql = "UPDATE attendance
				SET status = '13',
					remarks = 'Updated by DMS Application',
					last_update_id = '$user_id',
					last_update = '$user_dtime'
				WHERE aid = '$aid'
				LIMIT 1";
		query($sql); 
	} else {
		$sql = "INSERT INTO attendance(eid, adate, status, remarks, last_update_id, last_update)
				VALUES('$eid', '$ddate', '13', 'Auto-encoded by DMS Application', '$user_id', '$user_dtime')";
		query($sql); 
	}
}
free_result($res); 
*/





