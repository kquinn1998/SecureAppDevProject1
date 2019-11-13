<?php
	include 'config.php';
	include 'functions.php';
	session_start();
    if (isset($_SESSION['username'])) {
		header('location:WelcomePage.html.php');
	}
	//Check attempts and lockout if 5 or above
	if (isset($_SESSION['attempts'])){
		if ($_SESSION['attempts'] >= 5) {
			$_SESSION['locked_out'] = TRUE;
			lockout_user($_SESSION['ip'], $_SESSION['user_agent'], time());
			$_SESSION['bad_login'] = FALSE;
			$_SESSION['attempts'] = 0;
		}
	}
	if(isset($_SESSION['ip'])) {
		if(!check_if_user_locked_out($_SESSION['ip'], $_SESSION['user_agent'])){
			$_SESSION['locked_out'] = FALSE;
		}
	}
	
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>Login</title>
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
				<form class="login100-form validate-form" action="Login.php" method="POST">
					<span class="login100-form-logo">
						<i class="zmdi zmdi-landscape"></i>
					</span>

					<span class="login100-form-title p-b-34 p-t-27">
						Log in
					</span>

					<div class="wrap-input100 validate-input" data-validate = "Enter username">
						<input class="input100" type="text" name="username" placeholder="Username">
						<span class="focus-input100" data-placeholder="&#xf207;"></span>
					</div>

					<div class="wrap-input100 validate-input" data-validate="Enter password">
						<input class="input100" type="password" name="pass" placeholder="Password">
						<span class="focus-input100" data-placeholder="&#xf191;"></span>
					</div>

					<div class="contact100-form-checkbox">
						<input class="input-checkbox100" id="ckb1" type="checkbox" name="remember-me">
						<label class="label-checkbox100" for="ckb1">
							Remember me
						</label>
					</div>

					<?php
					if (isset($_SESSION['locked_out'])){
						if ($_SESSION['locked_out'] == TRUE) {
							echo "	<div class='text-center p-t-10'>
										<a class='txt1'>
											You are locked out.
										</a>
									</div>";
						}else {
							echo "<div class='container-login100-form-btn'>
									<button class='login100-form-btn'>
										Login
									</button>
								</div>";
						}
					}else {
						echo "<div class='container-login100-form-btn'>
									<button class='login100-form-btn'>
										Login
									</button>
								</div>";
					}
					?>

					<?php
					if (isset($_SESSION['bad_login'])) {
						if($_SESSION['bad_login']){
							echo "	<div class='text-center p-t-10'>
										<a class='txt1'>
											The username " . $_SESSION['invalid_username'] . " or password are invalid.
										</a>
									</div>";
						}
					}	
					if (isset($_SESSION['attempts'])) {
						if ($_SESSION['attempts'] > 0) {
							echo "	<div class='text-center p-t-10'>
										<a class='txt1'>
											You have used " . $_SESSION['attempts'] . " of your 5 attempts.
										</a>
									</div>";
						}
					}	
					?>

                    <div class="text-center p-t-90">
						<a class="txt1 font-weight-bolder " href="CreateAccount.html.php">
							Create Account
						</a>
					</div>

					<div class="text-center p-t-20">
						<a class="txt1" href="#">
							Forgot Password?
						</a>
					</div>
				</form>
			</div>
		</div>
	</div>
	

	<div id="dropDownSelect1"></div>
	
<!--===============================================================================================-->
	<script src="vendor/bootstrap/js/popper.js"></script>
	<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
	<script src="../JS/main.js"></script>

</body>
</html>