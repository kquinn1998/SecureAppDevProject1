<?php
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

	if(!isset($_SESSION['CSRF_token'])){
		$_SESSION['CSRF_token'] = bin2hex(random_bytes(32));
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>Welcome</title>
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
				<form class="login100-form validate-form" action="Logout.php" method="POST">
					<span class="login100-form-logo">
						<i class="zmdi zmdi-landscape"></i>
					</span>

					<span class="login100-form-title p-b-34 p-t-27">
						Welcome <?php echo $_SESSION['username']; ?>!
					</span>

					<div class="text-center p-t-45">
						<a class="txt1 font-weight-bolder " href="ChangePassword.html.php">
							Change Password
						</a>
					</div>

					<?php	
					if ($_SESSION['admin']) {
						echo "	<div class='text-center p-t-10'>
									<a class='txt1'>
										You have admin priv.
									</a>
								</div>";
					}			
					?>

					<div class="container-login100-form-btn p-b-34 p-t-45">
						<button class="login100-form-btn">
							Logout
						</button>
					</div>
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