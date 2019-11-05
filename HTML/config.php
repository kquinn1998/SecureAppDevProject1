<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "C00216607_securedev";
    
    // Creating a connection
    $conn = new mysqli($servername, $username, $password);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } 
    // Creating a database named newDB
    $sql = "CREATE DATABASE IF NOT EXISTS C00216607_securedev";
    if ($conn->query($sql) === TRUE) {
        $conn = new mysqli($servername, $username, $password, $dbname);
        $sql = "CREATE TABLE IF NOT EXISTS users (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(30) NOT NULL,
            pass VARCHAR(128) NOT NULL,
            reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            reg_time VARCHAR(30) NOT NULL,
            active BOOLEAN NOT NULL
            )";
        if ($conn->query($sql) === TRUE) {
            $sql = "CREATE TABLE IF NOT EXISTS lockedOutUser (
                    `IP` VARCHAR( 20 ) NOT NULL PRIMARY KEY,
                    locked_out_time VARCHAR(30) NOT NULL
                    )";
            if ($conn->query($sql) === FALSE) {
                echo "error creating locked out table";
            }
        } else {
            echo "Error creating users table: " . $conn->server;
        }
    } else {
        echo "Error creating database: " . $conn->error;
    }
    
    // closing connection
    $conn->close();
    
?>