<?php
    include 'con_file.php';

    session_start();

    //set user as inactive
    $username = $_SESSION['username'];
    $sql = "UPDATE users SET active = FALSE WHERE username = '$username'";
    $conn->query($sql);
    
    //destroy session
    session_destroy();
    // Redirect to the login page:
    header('location:Login.html.php');
?>