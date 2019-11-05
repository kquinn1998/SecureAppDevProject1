<?php
    $servername = "localhost";
    $username = "root";
    $dbpassword = "";
    $dbname = "C00216607_securedev";
    // Creating a connection
    $conn = new mysqli($servername, $username, $dbpassword, $dbname);
    // Check connection
    if ($conn->connect_error) {
        $conn->close();
        die("connection error");
    }
    
?>