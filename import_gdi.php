<?php

include("db.php");
include("sqli.php");
include("html.php");

session_start();

if (isset($_SESSION['user_id'])) {
	if (validate_user() == true) {

		if(isset($_POST['submitted'])) { // if submit button has been pressed

			if ($_FILES["file"]["error"] > 0) {
				echo "Error: " . $_FILES["file"]["error"] . "<br>";
			} else {
				$extension = end(explode(".", $_FILES["file"]["name"]));
				if ($extension != 'csv') {
					?><div class="error"><b>File not allowed!</b></div><br /><?PHP
					show_form();
				} else {
					$file = $_FILES['file']['tmp_name'];
					if (($handle = fopen($file, "r")) !== FALSE) {
						
						while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {

							$cid = $data[0];
							$cname = $data[1];
							
							$regmem_2013 = $data[2];
							$regmem_2014 = $data[3];
							$regmem_2015 = $data[4];
							$regmem_2016 = $data[5];
							$regmem_2017 = $data[6];
							$regmem_2018 = $data[7];
							
							$assocmem_2013 = $data[8];
							$assocmem_2014 = $data[9];
							$assocmem_2015 = $data[10];
							$assocmem_2016 = $data[11];
							$assocmem_2017 = $data[12];
							$assocmem_2018 = $data[13];
							
							// COOPERATIVE-CLASSES
							$operators_2013 = $data[14];
							$operators_2014 = $data[15];
							$operators_2015 = $data[16];
							$operators_2016 = $data[17];
							$operators_2017 = $data[18];
							$operators_2018 = $data[19];

							$drivers_2013 = $data[20];
							$drivers_2014 = $data[21];
							$drivers_2015 = $data[22];
							$drivers_2016 = $data[23];
							$drivers_2017 = $data[24];
							$drivers_2018 = $data[25];
							
							$cworkers_2013 = $data[26];
							$cworkers_2014 = $data[27];
							$cworkers_2015 = $data[28];
							$cworkers_2016 = $data[29];
							$cworkers_2017 = $data[30];
							$cworkers_2018 = $data[31];
							
							$commuters_2013 = $data[32];
							$commuters_2014 = $data[33];
							$commuters_2015 = $data[34];
							$commuters_2016 = $data[35];
							$commuters_2017 = $data[36];
							$commuters_2018 = $data[37];
							
							$others_2013 = $data[38];
							$others_2014 = $data[39];
							$others_2015 = $data[40];
							$others_2016 = $data[41];
							$others_2017 = $data[42];
							$others_2018 = $data[43];

							if ($operators_2013 != 0 || $drivers_2013 != 0 || $cworkers_2013 != 0 || $commuters_2013 != 0 || $others_2013 != 0) {
								// insert to table
								$sql = "INSERT INTO cooperatives_classes( cid, cyear, coperators, cdrivers, cworkers, ccommuters, cothers )
										VALUES( '$cid', '2013', '$operators_2013', '$drivers_2013', '$cworkers_2013', '$commuters_2013', '$others_2013' )";
								query($sql); 
							}
							if ($operators_2014 != 0 || $drivers_2014 != 0 || $cworkers_2014 != 0 || $commuters_2014 != 0 || $others_2014 != 0) {
								// insert to table
								$sql = "INSERT INTO cooperatives_classes( cid, cyear, coperators, cdrivers, cworkers, ccommuters, cothers )
										VALUES( '$cid', '2014', '$operators_2014', '$drivers_2014', '$cworkers_2014', '$commuters_2014', '$others_2014' )";
								query($sql); 
							}
							if ($operators_2015 != 0 || $drivers_2015 != 0 || $cworkers_2015 != 0 || $commuters_2015 != 0 || $others_2015 != 0) {
								// insert to table
								$sql = "INSERT INTO cooperatives_classes( cid, cyear, coperators, cdrivers, cworkers, ccommuters, cothers )
										VALUES( '$cid', '2015', '$operators_2015', '$drivers_2015', '$cworkers_2015', '$commuters_2015', '$others_2015' )";
								query($sql); 
							}
							if ($operators_2016 != 0 || $drivers_2016 != 0 || $cworkers_2016 != 0 || $commuters_2016 != 0 || $others_2016 != 0) {
								// insert to table
								$sql = "INSERT INTO cooperatives_classes( cid, cyear, coperators, cdrivers, cworkers, ccommuters, cothers )
										VALUES( '$cid', '2016', '$operators_2016', '$drivers_2016', '$cworkers_2016', '$commuters_2016', '$others_2016' )";
								query($sql); 
							}
							if ($operators_2017 != 0 || $drivers_2017 != 0 || $cworkers_2017 != 0 || $commuters_2017 != 0 || $others_2017 != 0) {
								// insert to table
								$sql = "INSERT INTO cooperatives_classes( cid, cyear, coperators, cdrivers, cworkers, ccommuters, cothers )
										VALUES( '$cid', '2017', '$operators_2017', '$drivers_2017', '$cworkers_2017', '$commuters_2017', '$others_2017' )";
								query($sql); 
							}
							if ($operators_2018 != 0 || $drivers_2018 != 0 || $cworkers_2018 != 0 || $commuters_2018 != 0 || $others_2018 != 0) {
								$members_num_2018 = $operators_2018 + $drivers_2018 + $cworkers_2018 + $commuters_2018 + $others_2018;
								
								// insert to table
								$sql = "INSERT INTO cooperatives_classes( cid, cyear, coperators, cdrivers, cworkers, ccommuters, cothers )
										VALUES( '$cid', '2018', '$operators_2018', '$drivers_2018', '$cworkers_2018', '$commuters_2018', '$others_2018' )";
								query($sql); 
								
								// update the cooperatives table
								$sql = "UPDATE cooperatives
										SET members_num = '$members_num_2018' 
										WHERE cid = '$cid' LIMIT 1";
								query($sql); 
							}
							// END OF COOPERATIVE-CLASSES
							
							
							// COOPERATIVE - UNITS
							$puj_2013 = $data[44];
							$puj_2014 = $data[45];
							$puj_2015 = $data[46];
							$puj_2016 = $data[47];
							$puj_2017 = $data[48];
							$puj_2018 = $data[49];
							
							$auv_2013 = $data[50];
							$auv_2014 = $data[51];
							$auv_2015 = $data[52];
							$auv_2016 = $data[53];
							$auv_2017 = $data[54];
							$auv_2018 = $data[55];
							
							$taxi_2013 = $data[56];
							$taxi_2014 = $data[57];
							$taxi_2015 = $data[58];
							$taxi_2016 = $data[59];
							$taxi_2017 = $data[60];
							$taxi_2018 = $data[61];
							
							$mcab_2013 = $data[62];
							$mcab_2014 = $data[63];
							$mcab_2015 = $data[64];
							$mcab_2016 = $data[65];
							$mcab_2017 = $data[66];
							$mcab_2018 = $data[67];
							
							$mbus_2013 = $data[68];
							$mbus_2014 = $data[69];
							$mbus_2015 = $data[70];
							$mbus_2016 = $data[71];
							$mbus_2017 = $data[72];
							$mbus_2018 = $data[73];
							
							$bus_2013 = $data[74];
							$bus_2014 = $data[75];
							$bus_2015 = $data[76];
							$bus_2016 = $data[77];
							$bus_2017 = $data[78];
							$bus_2018 = $data[79];
							
							$mch_2013 = $data[80];
							$mch_2014 = $data[81];
							$mch_2015 = $data[82];
							$mch_2016 = $data[83];
							$mch_2017 = $data[84];
							$mch_2018 = $data[85];
							
							$truck_2013 = $data[86];
							$truck_2014 = $data[87];
							$truck_2015 = $data[88];
							$truck_2016 = $data[89];
							$truck_2017 = $data[90];
							$truck_2018 = $data[91];

							$banca_2013 = $data[92];
							$banca_2014 = $data[93];
							$banca_2015 = $data[94];
							$banca_2016 = $data[95];
							$banca_2017 = $data[96];
							$banca_2018 = $data[97];
							
							$coop_owned_2013 = $data[98];
							$coop_owned_2014 = $data[99];
							$coop_owned_2015 = $data[100];
							$coop_owned_2016 = $data[101];
							$coop_owned_2017 = $data[102];
							$coop_owned_2018 = $data[103];
							
							$ind_owned_2013 = $data[104];
							$ind_owned_2014 = $data[105];
							$ind_owned_2015 = $data[106];
							$ind_owned_2016 = $data[107];
							$ind_owned_2017 = $data[108];
							$ind_owned_2018 = $data[109];
							
							$coop_fran_2013 = $data[110];
							$coop_fran_2014 = $data[111];
							$coop_fran_2015 = $data[112];
							$coop_fran_2016 = $data[113];
							$coop_fran_2017 = $data[114];
							$coop_fran_2018 = $data[115];
							
							$ind_fran_2013 = $data[116];
							$ind_fran_2014 = $data[117];
							$ind_fran_2015 = $data[118];
							$ind_fran_2016 = $data[119];
							$ind_fran_2017 = $data[120];
							$ind_fran_2018 = $data[121];
							
							$no_fran_2013 = $data[122];
							$no_fran_2014 = $data[123];
							$no_fran_2015 = $data[124];
							$no_fran_2016 = $data[125];
							$no_fran_2017 = $data[126];
							$no_fran_2018 = $data[127];
							
							if ($puj_2013 != 0 || $auv_2013 != 0 || $taxi_2013 != 0 || $mcab_2013 != 0 || $mbus_2013 != 0 || $bus_2013 != 0 || $mch_2013 != 0 || $truck_2013 != 0 || $banca_2013 != 0 || $coop_owned_2013 != 0 || $ind_owned_2013 != 0 || $coop_fran_2013 != 0 || $ind_fran_2013 != 0 || $no_fran_2013 != 0) {
								// insert to table
								$sql = "INSERT INTO cooperatives_units( cid, cyear, puj_num, auv_num, taxi_num, mcab_num, mb_num, bus_num, mch_num, truck_num, banka_num, coop_owned, individual_owned, coop_franchised, individual_franchised, wout_franchise )
										VALUES( '$cid', '2013', '$puj_2013', '$auv_2013', '$taxi_2013', '$mcab_2013', '$mbus_2013', '$bus_2013', '$mch_2013', '$truck_2013', '$banca_2013', '$coop_owned_2013', '$ind_owned_2013', '$coop_fran_2013', '$ind_fran_2013', '$no_fran_2013' )";
								query($sql); 
							}
							if ($puj_2014 != 0 || $auv_2014 != 0 || $taxi_2014 != 0 || $mcab_2014 != 0 || $mbus_2014 != 0 || $bus_2014 != 0 || $mch_2014 != 0 || $truck_2014 != 0 || $banca_2014 != 0 || $coop_owned_2014 != 0 || $ind_owned_2014 != 0 || $coop_fran_2014 != 0 || $ind_fran_2014 != 0 || $no_fran_2014 != 0) {
								// insert to table
								$sql = "INSERT INTO cooperatives_units( cid, cyear, puj_num, auv_num, taxi_num, mcab_num, mb_num, bus_num, mch_num, truck_num, banka_num, coop_owned, individual_owned, coop_franchised, individual_franchised, wout_franchise )
										VALUES( '$cid', '2014', '$puj_2014', '$auv_2014', '$taxi_2014', '$mcab_2014', '$mbus_2014', '$bus_2014', '$mch_2014', '$truck_2014', '$banca_2014', '$coop_owned_2014', '$ind_owned_2014', '$coop_fran_2014', '$ind_fran_2014', '$no_fran_2014' )";
								query($sql); 
							}
							if ($puj_2015 != 0 || $auv_2015 != 0 || $taxi_2015 != 0 || $mcab_2015 != 0 || $mbus_2015 != 0 || $bus_2015 != 0 || $mch_2015 != 0 || $truck_2015 != 0 || $banca_2015 != 0 || $coop_owned_2015 != 0 || $ind_owned_2015 != 0 || $coop_fran_2015 != 0 || $ind_fran_2015 != 0 || $no_fran_2015 != 0) {
								// insert to table
								$sql = "INSERT INTO cooperatives_units( cid, cyear, puj_num, auv_num, taxi_num, mcab_num, mb_num, bus_num, mch_num, truck_num, banka_num, coop_owned, individual_owned, coop_franchised, individual_franchised, wout_franchise )
										VALUES( '$cid', '2015', '$puj_2015', '$auv_2015', '$taxi_2015', '$mcab_2015', '$mbus_2015', '$bus_2015', '$mch_2015', '$truck_2015', '$banca_2015', '$coop_owned_2015', '$ind_owned_2015', '$coop_fran_2015', '$ind_fran_2015', '$no_fran_2015' )";
								query($sql); 
							}
							if ($puj_2016 != 0 || $auv_2016 != 0 || $taxi_2016 != 0 || $mcab_2016 != 0 || $mbus_2016 != 0 || $bus_2016 != 0 || $mch_2016 != 0 || $truck_2016 != 0 || $banca_2016 != 0 || $coop_owned_2016 != 0 || $ind_owned_2016 != 0 || $coop_fran_2016 != 0 || $ind_fran_2016 != 0 || $no_fran_2016 != 0) {
								// insert to table
								$sql = "INSERT INTO cooperatives_units( cid, cyear, puj_num, auv_num, taxi_num, mcab_num, mb_num, bus_num, mch_num, truck_num, banka_num, coop_owned, individual_owned, coop_franchised, individual_franchised, wout_franchise )
										VALUES( '$cid', '2016', '$puj_2016', '$auv_2016', '$taxi_2016', '$mcab_2016', '$mbus_2016', '$bus_2016', '$mch_2016', '$truck_2016', '$banca_2016', '$coop_owned_2016', '$ind_owned_2016', '$coop_fran_2016', '$ind_fran_2016', '$no_fran_2016' )";
								query($sql); 
							}
							if ($puj_2017 != 0 || $auv_2017 != 0 || $taxi_2017 != 0 || $mcab_2017 != 0 || $mbus_2017 != 0 || $bus_2017 != 0 || $mch_2017 != 0 || $truck_2017 != 0 || $banca_2017 != 0 || $coop_owned_2017 != 0 || $ind_owned_2017 != 0 || $coop_fran_2017 != 0 || $ind_fran_2017 != 0 || $no_fran_2017 != 0) {
								// insert to table
								$sql = "INSERT INTO cooperatives_units( cid, cyear, puj_num, auv_num, taxi_num, mcab_num, mb_num, bus_num, mch_num, truck_num, banka_num, coop_owned, individual_owned, coop_franchised, individual_franchised, wout_franchise )
										VALUES( '$cid', '2017', '$puj_2017', '$auv_2017', '$taxi_2017', '$mcab_2017', '$mbus_2017', '$bus_2017', '$mch_2017', '$truck_2017', '$banca_2017', '$coop_owned_2017', '$ind_owned_2017', '$coop_fran_2017', '$ind_fran_2017', '$no_fran_2017' )";
								query($sql); 
							}
							if ($puj_2018 != 0 || $auv_2018 != 0 || $taxi_2018 != 0 || $mcab_2018 != 0 || $mbus_2018 != 0 || $bus_2018 != 0 || $mch_2018 != 0 || $truck_2018 != 0 || $banca_2018 != 0 || $coop_owned_2018 != 0 || $ind_owned_2018 != 0 || $coop_fran_2018 != 0 || $ind_fran_2018 != 0 || $no_fran_2018 != 0) {
								// insert to table
								$sql = "INSERT INTO cooperatives_units( cid, cyear, puj_num, auv_num, taxi_num, mcab_num, mb_num, bus_num, mch_num, truck_num, banka_num, coop_owned, individual_owned, coop_franchised, individual_franchised, wout_franchise )
										VALUES( '$cid', '2018', '$puj_2018', '$auv_2018', '$taxi_2018', '$mcab_2018', '$mbus_2018', '$bus_2018', '$mch_2018', '$truck_2018', '$banca_2018', '$coop_owned_2018', '$ind_owned_2018', '$coop_fran_2018', '$ind_fran_2018', '$no_fran_2018' )";
								query($sql); 
								
								// update the cooperatives table
								$sql = "UPDATE cooperatives
										SET puj_num = '$puj_2018', auv_num = '$auv_2018', taxi_num = '$taxi_2018', 
											mcab_num = '$mcab_2018', mb_num = '$mbus_2018', bus_num = '$bus_2018', 
											mch_num = '$mch_2018', truck_num = '$truck_2018', banka_num = '$banca_2018'
										WHERE cid = '$cid' LIMIT 1";
								query($sql); 
							}
							// END OF COOPERATIVE - UNITS



							// COOPERATIVE - FINANCIALS
							$assets_2013 = $data[128];
							$assets_2014 = $data[129];
							$assets_2015 = $data[130];
							$assets_2016 = $data[131];
							$assets_2017 = $data[132];
							$assets_2018 = $data[133];
							
							$capital_2013 = $data[134];
							$capital_2014 = $data[135];
							$capital_2015 = $data[136];
							$capital_2016 = $data[137];
							$capital_2017 = $data[138];
							$capital_2018 = $data[139];
							
							$revenue_2013 = $data[140];
							$revenue_2014 = $data[141];
							$revenue_2015 = $data[142];
							$revenue_2016 = $data[143];
							$revenue_2017 = $data[144];
							$revenue_2018 = $data[145];

							$exp_2013 = $data[146];
							$exp_2014 = $data[147];
							$exp_2015 = $data[148];
							$exp_2016 = $data[149];
							$exp_2017 = $data[150];
							$exp_2018 = $data[151];
							
							$income_2013 = $data[152];
							$income_2014 = $data[153];
							$income_2015 = $data[154];
							$income_2016 = $data[155];
							$income_2017 = $data[156];
							$income_2018 = $data[157];
							
							if ($assets_2013 != 0 || $capital_2013 != 0 || $revenue_2013 != 0 || $exp_2013 != 0 || $income_2013 != 0) {
								// insert to table
								$sql = "INSERT INTO cooperatives_financials( cid, cyear, assets, capital, revenues, expenses, profit )
										VALUES( '$cid', '2013', '$assets_2013', '$capital_2013', '$revenue_2013', '$exp_2013', '$income_2013' )";
								query($sql); 
							}
							if ($assets_2014 != 0 || $capital_2014 != 0 || $revenue_2014 != 0 || $exp_2014 != 0 || $income_2014 != 0) {
								// insert to table
								$sql = "INSERT INTO cooperatives_financials( cid, cyear, assets, capital, revenues, expenses, profit )
										VALUES( '$cid', '2014', '$assets_2014', '$capital_2014', '$revenue_2014', '$exp_2014', '$income_2014' )";
								query($sql); 
							}
							if ($assets_2015 != 0 || $capital_2015 != 0 || $revenue_2015 != 0 || $exp_2015 != 0 || $income_2015 != 0) {
								// insert to table
								$sql = "INSERT INTO cooperatives_financials( cid, cyear, assets, capital, revenues, expenses, profit )
										VALUES( '$cid', '2015', '$assets_2015', '$capital_2015', '$revenue_2015', '$exp_2015', '$income_2015' )";
								query($sql); 
							}
							if ($assets_2016 != 0 || $capital_2016 != 0 || $revenue_2016 != 0 || $exp_2016 != 0 || $income_2016 != 0) {
								// insert to table
								$sql = "INSERT INTO cooperatives_financials( cid, cyear, assets, capital, revenues, expenses, profit )
										VALUES( '$cid', '2016', '$assets_2016', '$capital_2016', '$revenue_2016', '$exp_2016', '$income_2016' )";
								query($sql); 
							}
							if ($assets_2017 != 0 || $capital_2017 != 0 || $revenue_2017 != 0 || $exp_2017 != 0 || $income_2017 != 0) {
								// insert to table
								$sql = "INSERT INTO cooperatives_financials( cid, cyear, assets, capital, revenues, expenses, profit )
										VALUES( '$cid', '2017', '$assets_2017', '$capital_2017', '$revenue_2017', '$exp_2017', '$income_2017' )";
								query($sql); 
							}
							if ($assets_2018 != 0 || $capital_2018 != 0 || $revenue_2018 != 0 || $exp_2018 != 0 || $income_2018 != 0) {
								// insert to table
								$sql = "INSERT INTO cooperatives_financials( cid, cyear, assets, capital, revenues, expenses, profit )
										VALUES( '$cid', '2018', '$assets_2018', '$capital_2018', '$revenue_2018', '$exp_2018', '$income_2018' )";
								query($sql); 
								
								// update the cooperatives table
								$sql = "UPDATE cooperatives
										SET assets = '$assets_2018', capital = '$capital_2018'
										WHERE cid = '$cid' LIMIT 1";
								query($sql); 
							}
							// END OF COOPERATIVE - FINANCIALS

						}
						
						fclose($handle);
					}
					?></table><?PHP
				}
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

				<title>OTC - Import TC GDI</title>

				<!-- Bootstrap -->
				<link href="vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
				<!-- Font Awesome -->
				<link href="vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
				<!-- iCheck -->
				<link href="vendors/iCheck/skins/flat/green.css" rel="stylesheet">
				<!-- Datatables -->
				<link href="vendors/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">

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
					
					<?php
					?>

					<!-- page content -->
					<div class="right_col" role="main">
					  <div class="">
						<div class="page-title">
						  <div class="title_left">
							<h3>Import TC GDI</h3>
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
						
						  <div class="col-md-12 col-sm-12 col-xs-12">
							<div class="x_panel">
							  <div class="x_title">
								<h2>Import TC CGS</h2>
								<div class="clearfix"></div>
							  </div>
							  <div class="x_content">
								<form id="contact-form" name="contact-form" action="import_gdi.php" method="POST" enctype="multipart/form-data">					
									<fieldset>
										<label><span class="text-form">Filename:</span><input type="file" name="file" id="file"></label>
										<div class="buttons">
											<a class="button-2" onClick="document.getElementById('contact-form').submit()">Submit</a>
										</div>
									</fieldset>						
									<input type="hidden" name="token" value="<?PHP echo $token ?>">
									<input type="hidden" name="submitted" value="1">
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
				<!-- FastClick -->
				<script src="vendors/fastclick/lib/fastclick.js"></script>
				<!-- NProgress -->
				<script src="vendors/nprogress/nprogress.js"></script>
				<!-- iCheck -->
				<script src="vendors/iCheck/icheck.min.js"></script>
				<!-- Datatables -->
				<script src="vendors/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>

				<!-- Custom Theme Scripts -->
				<script src="build/js/custom.min.js"></script>

			  </body>
			</html>	
			<?php
		}
	}
} else {
	header('Location: login.php');	
}

