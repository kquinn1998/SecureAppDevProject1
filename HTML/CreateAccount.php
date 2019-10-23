<?php
    $servername = "localhost";
    $username = "root";
    $dbpassword = "";
    $dbname = "C00216607_securedev";
    
    // Creating a connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } 
    $username = $_POST['username'];
    $password = $_POST['pass'];

    $hash = $hash = md5($password); // works, but dangerous

    $sql = "INSERT INTO users (username, pass)
            VALUES ('$username','$hash')";

    if ($conn->query($sql) === TRUE) {
        header('location:Login.html.php');
    }
    else {
        echo "error in creating user: " . $conn->error;
    }
    
    // closing connection
    $conn->close();
?>