<?php

include("db.php");
include("sqli.php");

/*
$gyear = 2019; 
$gmonth = 9;
$eid = 44;

$sql = "SELECT aid, TO_DAYS(adate) FROM attendance WHERE eid = '$eid' AND YEAR(adate) = '$gyear' AND MONTH(adate) = '$gmonth'";
$res = query($sql); 
while ($row = fetch_array($res)) {
	// dms
	$sql = "SELECT did, dnum, DATE_FORMAT(fr_time, '%h:%i %p'), DATE_FORMAT(to_time, '%h:%i %p')
			FROM internals_dms 
			WHERE eid = '$eid' AND TO_DAYS(ddate) = '$row[1]'";
	$dres = query($sql); $dnum = num_rows($dres); 
	if ($dnum > 0) {
		$drow = fetch_array($dres); 
		$sql = "UPDATE attendance 
				SET status = '13', remarks = 'DMS ".$drow[1]." [".$drow[2]." - ".$drow[2]."]'
				WHERE aid = '$row[0]'
				LIMIT 1";
		query($sql); 
	}
	// travel order
	$sql = "SELECT o.tid, o.tnum
			FROM internals_travel_orders o 
				INNER JOIN internals_travel_orders_employees oe ON o.tid = oe.tid 
			WHERE oe.eid = '$eid' AND TO_DAYS(o.fr_date) >= '$row[1]' AND TO_DAYS(o.to_date) <= '$row[1]'";
	$ores = query($sql); $onum = num_rows($ores); 
	if ($onum > 0) {
		$orow = fetch_array($ores); 
		$sql = "UPDATE attendance 
				SET status = '12', remarks = 'Travel Order ".$orow[1]."'
				WHERE aid = '$row[0]'
				LIMIT 1";
		query($sql); 
	}
	// leave applications 
	$sql = "SELECT aid, anum, lid
			FROM internals_leave_applications 
			WHERE eid = '$eid' AND TO_DAYS(fr_date) >= '$row[1]' AND TO_DAYS(to_date) <= '$row[1]'";
	$lres = query($sql); $lnum = num_rows($lres);
	if ($lnum > 0) {
		$lrow = fetch_array($lres); 
		$lid = $lrow[2]; 
		
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
		$sql = "UPDATE attendance
				SET status = '$status', remarks = 'Leave Application ".$lrow[1]."'
				WHERE aid = '$row[0]'
				LIMIT 1";
		query($sql); 
	}
}
free_result($res); 
*/

/*
$sql = "SELECT rid, rnum, reid FROM requirements WHERE ptid = 1";
$rres = query($sql); 
while ($rrow = fetch_array($rres)) {
	if ($rrow[2] == 0) {
		$eid = 45; 
	} elseif ($rrow[2] == -1) {
		$eid = -1;
	} elseif ($rrow[2] > 0) {
		$eid = $rrow[2]; 
	}
	
	$sql = "INSERT INTO procurement_requirements(pr_num, rid, eid) VALUES('$rrow[1]', '$rrow[0]', '$eid')";
	query($sql); 
}
free_result($rres); 
*/

/*
for ($i=7; $i<=16; $i++) {
	$sql = "SELECT * FROM payrolls_deductions WHERE pid = 6";
	$res = query($sql); 
	while ($row = fetch_array($res)) {
		$sql = "INSERT INTO payrolls_deductions(pid, eid, basic, gsis_rlip_er, gsis_rlip_ee, gsis_uoli, gsis_consoloan, gsis_cash_adv, gsis_eal, gsis_el, gsis_pl, gsis_opt_pl, hdmf_ee, hdmf_er, hdmf_hl, hdmf_mpl, phic_ee, phic_er, emdeco, bir_tax, dad, total, net, net1, net2)
				VALUES('$i', '$row[2]', '$row[3]', '$row[4]', '$row[5]', '$row[6]', '$row[7]', '$row[8]', '$row[9]', '$row[10]', '$row[11]', '$row[12]', '$row[13]', '$row[14]', '$row[15]', '$row[16]', '$row[17]', '$row[18]', '$row[19]', '$row[20]', '$row[21]', '$row[22]', '$row[23]', '$row[24]', '$row[25]')";
		query($sql); 
	}
	free_result($res); 
}
*/

/*
$sql = "SELECT ef_id, efile
		FROM efiles_files 
		WHERE eid = 10";
$res = query($sql); 
while ($row = fetch_array($res)) {
	unlink($row[1]); 
	query("DELETE FROM efiles_files WHERE ef_id = '$row[0]' LIMIT 1"); 
}
free_result($res); 
*/

/*
$sql = "SELECT eid, monthly FROM `employees` WHERE active = 1 AND etype < 3 ORDER BY lname, fname";
$res = query($sql); 
while ($row = fetch_array($res)) {
	if ($row[0] == 1) {
		$absences = 796.77;
	} else {
		$absences = 0.00;
	}
	
	// get the deductions
	$sql = "SELECT total
			FROM payrolls_deductions 
			WHERE pid = 3 AND eid = '$row[0]' ";
	$dres = query($sql); $drow = fetch_array($dres); $deductions = $drow[0]; 
	
	$net = $row[1] - ($absences + $deductions);
	$net1 = floor($net / 2); 
	$net2 = $net - $net1; 
	
	$sql = "INSERT INTO payrolls_regular(pid, eid, absences, deductions, net, net1, net2) 
			VALUES(3, '$row[0]', '$absences', '$deductions', '$net', '$net1', '$net2')";
	query($sql); 
}
free_result($res); 
*/

/*
$sql = "SELECT eid FROM employees WHERE etype < 3";
$res = query($sql); 
while ($row = fetch_array($res)) {
	$sql = "INSERT INTO attendance(eid, adate, status, remarks)
			VALUES('$row[0]', '2019-12-31', 16, 'Last Day of Year')";
	query($sql); 
}
*/

/*
$sql = "SELECT t.tid, d.pid
		FROM towns t INNER JOIN districts d ON t.did = d.did
		WHERE t.pid = 0";
$res = query($sql); 
while ($row = fetch_array($res)) {
	$tid = $row[0]; $pid = $row[1];
	
	$sql = "UPDATE towns SET pid = '$pid' WHERE tid = '$tid'";
	query($sql); 
}
free_result($res); 

$sql = "SELECT eid FROM employees WHERE active = 1";
$res = query($sql); 
while ($row = fetch_array($res)) {
	$epass = getRandomString(); 
	
	$sql = "UPDATE employees SET epass = '$epass' WHERE eid = '$row[0]'";
	//query($sql); 
}

function getRandomString($length = 8) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $string = '';

    for ($i = 0; $i < $length; $i++) {
        $string .= $characters[mt_rand(0, strlen($characters) - 1)];
    }

    return $string;
}
*/

/*
$firstName = array("Analyn", "Rosamie", "Blessica", "Jaslene", "Benilda", "Flor", "Chesa", "Marisol", "Amor", "Flordeliza", "Imelda", "Liezel", "Nenita", "Rubylyn", "Alejandro", "Crisanto", "Rizalino", "Ernesto", "Vergel", "Juan", "Rodrigo", "Efren", "Mauricio", "Venancio", "Melchor", "Benjie", "Danilo", "Jerome", "Jeff", "Albert", "Nica", "Janet", "Rochelle", "Geraldine", "Mary", "Jean", "John", "Darwin", "Marilyn", "Nilo", "Niel", "Jane", "Joy", "Jacob", "Francis", "Leo", "Maria", "Patricia", "Nicole", "Bianca", "Kate", "Kristine", "Marie", "Joyce", "Hannah", "Angela", "Jane", "Camille", "Anne", "Sophia", "Grace", "Bea", "Diana", "Ella", "Mae", "Michelle", "Karen", "Rose", "Valerie", "Louise", "Aya", "Lea", "Loisa", "Faye", "Aira", "Alexa", "Andrea", "Marian", "Nina", "Kyla", "Nadine", "Nikki", "Isabel", "Kimberly", "Tricia", "Trina", "Cindy", "Danica", "Kathleen", "Paula", "Angelie", "Lyn", "Thea", "Abby", "Irish", "Yana", "Pamela", "Judy", "Pia", "Jilliane", "Jessica", "Mika", "Ian", "Mark", "Daniel", "Kevin", "Joseph", "Melvin", "Jake", "Brian", "Adrian", "Joshua", "Jan", "Anthony", "Jeffrey", "Ben", "Chris", "Paul", "Robert", "Jack", "Mike", "Ken", "Josh", "Jonathan", "Ivan", "Joel", "Carl", "Ryan", "Nathan", "Michael", "Clarence", "Matt", "Peter", "Kenneth", "Ace", "Dale", "Hector", "Conrad", "Wilson", "Harold", "Joven", "Daryll", "Julius", "Davis", "Moses", "Benedict", "Stanley", "Edward");
$middleName = array("Santos", "Reyes", "Cruz", "Bautista", "Ocampo", "Garcia", "Mendoza", "Torres", "Tomas", "Andrada", "Castillo", "Flores", "Villanueva", "Ramos", "Castro", "Rivera", "Aquino", "Navarro", "Salazar", "Mercado", "dela Cruz", "del Rosario", "de Guzman", "Dimaano", "Dimaguiba", "Dimasalang", "Guinto", "Macaraeg", "Lumaban", "Villafuerte", "de Castro", "del Castillo", "Pena", "Fernandez", "Lopez", "De Leon", "Perez", "Gonzales", "Bayani", "Dalisay");
$lastName = array("Santos", "Reyes", "Cruz", "Bautista", "Ocampo", "Garcia", "Mendoza", "Torres", "Tomas", "Andrada", "Castillo", "Flores", "Villanueva", "Ramos", "Castro", "Rivera", "Aquino", "Navarro", "Salazar", "Mercado", "dela Cruz", "del Rosario", "de Guzman", "Dimaano", "Dimaguiba", "Dimasalang", "Guinto", "Macaraeg", "Lumaban", "Villafuerte", "de Castro", "del Castillo", "Pena", "Fernandez", "Lopez", "De Leon", "Perez", "Gonzales", "Bayani", "Dalisay");

for ($i=0; $i<=1500000; $i++) {
	$newLastName = $lastName[rand(0, count($lastName)-1)];
	$newMiddleName = $middleName[rand(0, count($middleName)-1)];
	$newFirstName = $firstName[rand(0, count($firstName)-1)];
	
	$name = $newLastName.", ".$newFirstName.", ".$newMiddleName;
	
	$sql = "INSERT INTO names(name) VALUES('$name')";
	query($sql); 
}
*/