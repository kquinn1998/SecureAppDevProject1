<?php
    $servername = "localhost";
    $user = "root";
    $dbpassword = "";
    $dbname = "C00216607_securedev";
    // Creating a connection
    $conn = new mysqli($servername, $user, $dbpassword, $dbname);
    // Check connection
    if ($conn->connect_error) {
        $conn->close();
        die("connection error");
    }
?>