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

    if(isset($_SESSION['admin'])){
        if(!$_SESSION['admin']){
            header('location:WelcomePage.html.php');
        }
    }else{
        header('location:WelcomePage.html.php');
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>Admin Page</title>
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
				<form class="login100-form validate-form" action="WelcomePage.html.php">
					<span class="login100-form-logo">
						<i class="zmdi zmdi-landscape"></i>
					</span>

					<span class="login100-form-title p-b-34 p-t-27">
						Admin Page!
					</span>

                    <?php
                        include "con_file.php";
                        $sql = "SELECT ip, username, successful, reg_date FROM login_events"; //You don't need a ; like you do in SQL
                        $result = $conn->query($sql);
                        
                        echo "<center><table class='txt1'>"; // start a table tag in the HTML
                        echo "<tr><td class='p-r-20'>IP</td><td class='p-r-20'>Username</td><td class='p-r-20'>Successful</td><td class='p-r-20'>Timestamp</td></tr>";
                        while($row = mysqli_fetch_array($result)){   //Creates a loop to loop through results
                        echo "<tr><td class='p-r-20'>" . $row['ip'] . "</td><td class='p-r-20'>" . $row['username'] . "</td><td class='p-r-20'>" . $row['successful'] . "</td><td class='p-r-20'>" . $row['reg_date'] . "</td></tr>";  //$row['index'] the index here is a field name
                        }
                        
                        echo "</table></center>";
                    ?>

					<div class="container-login100-form-btn p-b-34 p-t-45">
						<button class="login100-form-btn">
							Back
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