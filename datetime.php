<?php
date_default_timezone_set("Asia/Manila");
echo date("M j, Y g:i A");
/*
include("db.php");
include("sqli.php");

$sql = "SELECT DATE_FORMAT(NOW(), '%b %e, %Y, %h:%i %p')";
$res = query($sql); 
$row = fetch_array($res); 

echo $row[0]; 
*/