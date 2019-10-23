<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "C00216607_securedev";
    
    // Creating a connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } 
    // Creating a database named newDB
    $sql = "INSERT INTO users (username, pass)
            VALUES ('" . $_POST['username'] . "','" .  $_POST['pass'] . "')";

    if ($conn->query($sql) === TRUE) {
        header('location:Login.html.php');
    }
    else {
        echo "error in creating user: " . $conn->error;
    }
    
    // closing connection
    $conn->close();
?>