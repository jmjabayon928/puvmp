<?php
include("db.php");
include("sqli.php");
include("html.php");

session_start();

if (isset($_SESSION['user_id'])) {
	// delete from sessions table
	$sql = "DELETE FROM sessions WHERE user_id = '$_SESSION[user_id]' LIMIT 1";
	
	if (@query($sql)) {

	$sql = "SELECT DATE_FORMAT(CURDATE(),'%Y%m')";
		$dres = query($sql); 
		$drow = fetch_array($dres); 
		$logs_tbl = 'logs_'.$drow[0]; 
		
		// insert into logs
		$sql = "INSERT INTO ".$logs_tbl."( log_dtime, log_type, user_id ) VALUES( NOW(), '-1', '$_SESSION[user_id]' )";
		query($sql); 
		
		// destroy session
		unset($_SESSION);
		session_destroy(); 
		
		// destroy cookie
		setcookie('user_id', '', time() - 3600);
		setcookie('cookie_id', '', time() - 3600);
		setcookie('cookie_token', '', time() - 3600);
		
		?>
		<!DOCTYPE html>
		<html lang="en">
		  <head>
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
			<!-- Meta, title, CSS, favicons, etc. -->
			<meta charset="utf-8">
			<meta http-equiv="X-UA-Compatible" content="IE=edge">
			<meta name="viewport" content="width=device-width, initial-scale=1">

			<title>OTC | Log-out Page</title>

			<!-- Bootstrap -->
			<link href="vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
			<!-- Font Awesome -->
			<link href="vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
			<!-- NProgress -->
			<link href="vendors/nprogress/nprogress.css" rel="stylesheet">
			<!-- Animate.css -->
			<link href="vendors/animate.css/animate.min.css" rel="stylesheet">

			<!-- Custom Theme Style -->
			<link href="build/css/custom.min.css" rel="stylesheet">
		  </head>

		  <body class="login">
			<div>
			  <a class="hiddenanchor" id="signup"></a>
			  <a class="hiddenanchor" id="signin"></a>

			  <div class="login_wrapper">
				<div class="animate form login_form">
				  <div width="100%">
					  <img src="images/otc-logo-300.png" height="40%" width="40%">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <img src="images/dotr-logo.png" height="40%" width="40%">
				  </div>
				  <section class="login_content">
					<form method="post" action="validate_user.php">
					  <h1>Login Form</h1>
					  <div>
						<input type="text" name="username" class="form-control" placeholder="Username" required="" />
					  </div>
					  <div>
						<input type="password" name="password" class="form-control" placeholder="Password" required="" />
					  </div>
					  <div>
						<button type="submit" class="btn btn-primary">Log in</button>
					  </div>

					  <div class="clearfix"></div>

					  <div class="separator">
						
						<a href="time_in.php" class="to_register"> Time In / Time Out </a>
						
						<p class="change_link">You are logged out now.</p>
						<div class="clearfix"></div>
						<br />
						<div class="clearfix"></div>
						<br />
						<div class="clearfix"></div>
						<br />
						<div class="clearfix"></div>
						<br />
						<div class="clearfix"></div>
						<br />

						<div>
						  <p>Free Template by Gentelella Alela!</p>
						</div>
					  </div>
					</form>
				  </section>
				</div>
			  </div>
			</div>
		  </body>
		</html>
		<?php
	}
	
} else { // haven't logged in yet
	?>
	<!DOCTYPE html>
	<html lang="en">
	  <head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<!-- Meta, title, CSS, favicons, etc. -->
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<title>OTC | Log-out Page</title>

		<!-- Bootstrap -->
		<link href="vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
		<!-- Font Awesome -->
		<link href="vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
		<!-- NProgress -->
		<link href="vendors/nprogress/nprogress.css" rel="stylesheet">
		<!-- Animate.css -->
		<link href="vendors/animate.css/animate.min.css" rel="stylesheet">

		<!-- Custom Theme Style -->
		<link href="build/css/custom.min.css" rel="stylesheet">
	  </head>

	  <body class="login">
		<div>
		  <a class="hiddenanchor" id="signup"></a>
		  <a class="hiddenanchor" id="signin"></a>

		  <div class="login_wrapper">
			<div class="animate form login_form">
			  <div width="100%">
				  <img src="images/otc-logo-300.png" height="45%" width="45%">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <img src="images/dotr-logo.png" height="36%" width="36%">
			  </div>
			  <section class="login_content">
				<form method="post" action="validate_user.php">
				  <h1>Login Form</h1>
				  <div>
					<input type="text" name="username" class="form-control" placeholder="Username" required="" />
				  </div>
				  <div>
					<input type="password" name="password" class="form-control" placeholder="Password" required="" />
				  </div>
				  <div>
					<button type="submit" class="btn btn-primary">Log in</button>
				  </div>

				  <div class="clearfix"></div>

				  <div class="separator">
					
					<a href="time_in.php" class="to_register"> Time In / Time Out </a><br /><br />
					<p class="change_link">You haven't logged in yet!</p>
					<div class="clearfix"></div>
					<br />
					<div class="clearfix"></div>
					<br />
					<div class="clearfix"></div>
					<br />
					<div class="clearfix"></div>
					<br />
					<div class="clearfix"></div>
					<br />

					<div>
					  <p>Free Template by Gentelella Alela!</p>
					</div>
				  </div>
				</form>
			  </section>
			</div>
		  </div>
		</div>
	  </body>
	</html>
	<?php
}


