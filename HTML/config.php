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
        if ($conn->query($sql) === FALSE) {
            echo "error creating users table";
        }
        $sql = "CREATE TABLE IF NOT EXISTS locked_out_users (
                ip VARCHAR(20) NOT NULL PRIMARY KEY,
                user_agent VARCHAR(128) NOT NULL,
                locked_out_time VARCHAR(30) NOT NULL
                )";
        if ($conn->query($sql) === FALSE) {
            echo "error creating locked out table";
        }
        $sql = "CREATE TABLE IF NOT EXISTS login_events (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            ip VARCHAR( 20 ) NOT NULL,
            username VARCHAR(30) DEFAULT 'empty',
            successful BOOLEAN NOT NULL,
            reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )";
        if ($conn->query($sql) === FALSE) {
            echo "error creating login_events table";
        }
    } else {
        echo "Error creating database: " . $conn->error;
    }
    
    // closing connection
    $conn->close();
    
?>