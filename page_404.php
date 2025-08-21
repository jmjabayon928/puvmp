<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Gentelella Alela! | </title>

    <!-- Bootstrap -->
    <link href="vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="vendors/nprogress/nprogress.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="build/css/custom.min.css" rel="stylesheet">
  </head>

  <body style="background: #F7F7F7">
    <div class="container body">
      <div class="main_container">
        <!-- page content -->
        <div class="col-md-12">
          <div class="col-middle">
		  
					  <div class="col-md-6 col-sm-6 col-xs-12">

      <div class="login_wrapper">
        <div class="animate form login_form">
		  <div width="100%">
			  <img src="images/otc-logo.png" height="45%" width="45%">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <img src="images/dotr-logo.png" height="36%" width="36%">
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
				
				<?php
				if (isset($_GET['error'])) {
					if ($_GET['error'] == 1) {
						?><p class="change_link">Username and password do not match!</p><?php
					}
				}
				if (isset($_GET['expire'])) {
					?><p class="change_link">You have been inactive for <?php echo $_GET['expire'] ?> minutes! You have been logged out. Please log in again.</p><?php
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
					  
					  <div class="col-md-6 col-sm-6 col-xs-12">
						<div class="x_panel">
						  <div class="x_title">
							<h2>Up-Coming Activities <small>MIS / IT Division</small></h2>
							<ul class="nav navbar-right panel_toolbox">
							  <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
							  <li><a class="close-link"><i class="fa fa-close"></i></a></li>
							</ul>
							<div class="clearfix"></div>
						  </div>
						  <div class="x_content">

							<table class="table table-bordered jambo_table">
							  <thead>
								<tr>
								  <th width="08%">#</th>
								  <th width="12%">Date</th>
								  <th width="40%">Announcements</th>
								  <th width="40%">Details</th>
								</tr>
							  </thead>
							  <tbody>
								<tr>
								  <th scope="row">1</th>
								  <td>07/02/2018</td>
								  <td>DoTr Consultative Meeting</td>
								  <td>Details</td>
								</tr>
								<tr>
								  <th scope="row">2</th>
								  <td>07/03/2018</td>
								  <td>DICT IT Security Training</td>
								  <td>Details</td>
								</tr>
								<tr>
								  <th scope="row">3</th>
								  <td>07/04/2018</td>
								  <td>LTFRB Collaboration Meeting</td>
								  <td>Details</td>
								</tr>
							  </tbody>
							</table>

						  </div>
						</div>
					  </div>
		  
		  
          </div>
        </div>
        <!-- /page content -->
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

    <!-- Custom Theme Scripts -->
    <script src="build/js/custom.min.js"></script>
  </body>
</html>
