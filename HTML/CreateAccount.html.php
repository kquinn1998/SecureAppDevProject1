<?php
	include 'config.php';
	include 'functions.php';
	ini_set('session.cookie_httponly', 1);
	session_start();
    if (isset($_SESSION['loggedin'])) {
		header('location:WelcomePage.html.php');
	}
	
	if(!isset($_SESSION['ip'])){
        $_SESSION['ip'] = get_client_ip();
    }
    if(!isset($_SESSION['user_agent'])){
        $_SESSION['user_agent'] = get_user_agent();
    }

	$_SESSION['attempts_create_account'] = check_attempts_create_account($_SESSION['ip'],$_SESSION['user_agent']);

	//Check attempts and lockout if 5 or above
	if (isset($_SESSION['attempts_create_account'])){
		if ($_SESSION['attempts_create_account'] >= 5) {
			$_SESSION['locked_out_create_account'] = TRUE;
			clear_attempts_create_account($_SESSION['ip'], $_SESSION['user_agent']);
			lockout_user_create_account($_SESSION['ip'], $_SESSION['user_agent'], time());
			$_SESSION['attempts_create_account'] = 0;
		}
	}
	if(isset($_SESSION['ip'])) {
		if(!check_if_user_locked_out_create_account($_SESSION['ip'], $_SESSION['user_agent'])){
			$_SESSION['locked_out_create_account'] = FALSE;
		}
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>Create Account</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->	
	<link rel="icon" type="image/png" href="../Resources/icons/favicon.ico"/>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="../fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="../fonts/iconic/css/material-design-iconic-font.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="vendor/css-hamburgers/hamburgers.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/animsition/css/animsition.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="vendor/daterangepicker/daterangepicker.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="../CSS/util.css">
	<link rel="stylesheet" type="text/css" href="../CSS/main.css">
<!--===============================================================================================-->
</head>
<body>
	
	<div class="limiter">
		<div class="container-login100" style="background-image: url('images/bg-01.jpg');">
			<div class="wrap-login100">
				<form class="login100-form validate-form" action="CreateAccount.php" method="POST">
					<span class="login100-form-logo">
						<i class="zmdi zmdi-landscape"></i>
					</span>

					<span class="login100-form-title p-b-34 p-t-27">
						Create Account
					</span>

					<div class="wrap-input100 validate-input" data-validate = "Enter username">
						<input class="input100" type="text" name="username" placeholder="Username">
						<span class="focus-input100" data-placeholder="&#xf207;"></span>
					</div>

					<div class="wrap-input100 validate-input" data-validate="Enter password">
						<input class="input100" type="password" name="pass" placeholder="Password">
						<span class="focus-input100" data-placeholder="&#xf191;"></span>
					</div>

					<div class="wrap-input100 validate-input" data-validate="Confirm password">
						<input class="input100" type="password" name="confirm_pass" placeholder="Confirm Password">
						<span class="focus-input100" data-placeholder="&#xf191;"></span>
					</div>

					<?php
						if (isset($_SESSION['error_username'])) {
							echo "	<div class='text-center p-t-10'>
										<a class='txt1'>
											" . $_SESSION['error_username'] . "
										</a>
									</div>";
						}		
						if (isset($_SESSION['error_password'])) {
							echo "	<div class='text-center p-t-10'>
										<a class='txt1'>
											" . $_SESSION['error_password'] . "
										</a>
									</div>";
						}			
					?>

					<?php
						if (isset($_SESSION['locked_out_create_account'])){
							if ($_SESSION['locked_out_create_account'] == TRUE) {
								echo "	<div class='text-center p-t-10'>
											<a class='txt1'>
												You are locked out.
											</a>
										</div>";
							}else {
								echo "<div class='container-login100-form-btn'>
										<button class='login100-form-btn'>
											Create Account
										</button>
									</div>";
							}
						}else {
							echo "<div class='container-login100-form-btn'>
										<button class='login100-form-btn'>
											Create Account
										</button>
									</div>";
						}
						?>

						<?php	
						if (isset($_SESSION['attempts_create_account'])) {
							if ($_SESSION['attempts_create_account'] > 0) {
								echo "	<div class='text-center p-t-10'>
											<a class='txt1'>
												You have used " . $_SESSION['attempts_create_account'] . " of your 5 attempts.
											</a>
										</div>";
							}
						}	
					?>
					</div>
				</form>
			</div>
		</div>
	</div>
	

	<div id="dropDownSelect1"></div>
	
	<script src="vendor/bootstrap/js/popper.js"></script>
	<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
	<script src="../JS/main.js"></script>

</body>
</html>