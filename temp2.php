<?php

include("db.php");
include("sqli.php");
include("html.php");

//session_start();

/*
$sql = "SELECT c.cid
		FROM cooperatives c INNER JOIN tc_sectors s ON c.sid = s.sid
		WHERE s.aid = 4";
$res = query($sql); 
while ($row = fetch_array($res)) {
	$cid = $row[0]; 
	
	update_financials($cid); 
	update_surplus($cid); 
	update_capitalizations($cid); 

	update_members($cid); 
	update_units($cid); 
	
	update_cgs_exp($cid); 
	
}
free_result($res); 
*/

//update_attendance(3065);

