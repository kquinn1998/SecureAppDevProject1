<?php
    session_start();

    $servername = "localhost";
    $username = "root";
    $dbpassword = "";
    $dbname = "C00216607_securedev";
    $count = 0;
    // Creating a connection
    $conn = new mysqli($servername, $username, $dbpassword, $dbname);
    // Check connection
    if ($conn->connect_error) {
        $conn->close();
        header('location:Login.html.php');
        die("connection error");
    }
    if ( !isset($_POST['username'], $_POST['pass']) ) {
        // Could not get the data that should have been sent.
        $conn->close();
        echo "no details recieved";
    }
    if ($stmt = $conn->prepare('SELECT id, pass FROM users WHERE username = ?')) {
        // Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
        $stmt->bind_param('s', $_POST['username']);
        $stmt->execute();
        // Store the result so we can check if the account exists in the database.
        $stmt->store_result();
    }
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $password);
        $stmt->fetch();
        if (md5($_POST['pass']) == $password) {
            session_regenerate_id();
		    $_SESSION['loggedin'] = TRUE;
		    $_SESSION['username'] = $_POST['username'];
		    $_SESSION['id'] = $id;
            header("location:WelcomePage.html.php");
        } else {
            header("location:Login.html.php");
        }
    } else {
        header("location:Login.html.php");
    }
    $stmt->close();
?>