<?php
	ini_set('session.cookie_httponly', 1);
	session_start();
    if (!isset($_SESSION['loggedin'])) {
		session_destroy();
        header('location:Login.html.php');
        exit();
	}
	if(!isset($_SESSION['last_active_time'])) {
		$_SESSION['last_active_time'] = time();
		$_SESSION['loggedin_time'] = time();
	}else{
		$loggedin_time = time() - $_SESSION['last_active_time'];
		if($loggedin_time > 600) {
			header('location:Logout.php');
		}else{
			$_SESSION['last_active_time'] = time();
		}
		
		$loggedin_time = time() - $_SESSION['loggedin_time'];
		if($loggedin_time > 3600) {
			header('location:Logout.php');
		}
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>Change Password</title>
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
				<form class="login100-form validate-form" action="ChangePassword.php" method="GET">
					<span class="login100-form-logo">
						<i class="zmdi zmdi-landscape"></i>
					</span>

					<span class="login100-form-title p-b-34 p-t-27">
						Change Password
					</span>

					<div class="wrap-input100 validate-input" data-validate="Enter old password">
						<input class="input100" type="password" name="old_password" placeholder="Old Password">
						<span class="focus-input100" data-placeholder="&#xf191;"></span>
					</div>

					<div class="wrap-input100 validate-input" data-validate="Enter old password">
						<input class="input100" type="password" name="new_password" placeholder="New Password">
						<span class="focus-input100" data-placeholder="&#xf191;"></span>
					</div>

					<div class="wrap-input100 validate-input" data-validate="Confirm new password">
						<input class="input100" type="password" name="confirm_password" placeholder="Confirm Password">
						<span class="focus-input100" data-placeholder="&#xf191;"></span>
					</div>

					<input type="hidden" name="token" value="<?php echo $_SESSION['CSRF_token'];?>"/>

					<?php	
					if (isset($_SESSION['error_password'])) {
						echo "	<div class='text-center p-t-10'>
									<a class='txt1'>
										" . $_SESSION['error_password'] . "
									</a>
								</div>";
					}			
					?>

					<div class="container-login100-form-btn p-t-20">
						<button class="login100-form-btn" type="submit">
							Change
						</button>
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