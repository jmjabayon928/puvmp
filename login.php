<?php

include("db.php");
include("sqli.php");

delete_inactive();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>OTC | Log In Page</title>

    <!-- Bootstrap -->
    <link href="vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">

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
				<?php
				if (isset($_GET['error'])) {
					if ($_GET['error'] == 1) {
						?><p class="change_link">Username and password do not match!</p><?php
					} elseif ($_GET['error'] == 2) {
						?><p class="change_link">Please enter both username and password!</p><?php
					} elseif ($_GET['error'] == 3) {
						?><p class="change_link">There is an error in sessions or in users-table or in logs-table. Please contact the system administrator.</p><?php
					}
				}
				if (isset($_GET['expire'])) {
					?><p class="change_link">You have been inactive for <?php echo number_format($_GET['expire'], 2) ?> minutes! You have been logged out. Please log in again.</p><?php
				}
				if (isset($_GET['mismatched'])) {
					if ($_GET['mismatched'] == 1) {
						?><p class="change_link">Mismatched User Sessions! <br />You have been logged out. Please log in again.</p><?php
					} elseif ($_GET['mismatched'] == 2) {
						?><p class="change_link">Mismatched User Keys! <br />You have been logged out. Please log in again.</p><?php
					} elseif ($_GET['mismatched'] == 3) {
						?><p class="change_link">Mismatched User Tokens! <br />You have been logged out. Please log in again.</p><?php
					} elseif ($_GET['mismatched'] == 4) {
						?><p class="change_link">Mismatched User IDs! <br />You have been logged out. Please log in again.</p><?php
					}
				}
				?>
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
