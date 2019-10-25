<?php
    session_start();

    //connection made
    include 'con_file.php';
    include 'filter_class.php';

    $username = $_POST['username'];
    $password = $_POST['pass'];
    $reg_time = time();

    if(!checkString($username)) {
        $_SESSION['error_username'] = "Cannot use this username";
        header('location:CreateAccount.html.php');
    }else{
        //Salting pass
        $password = $password . $reg_time . $username;

        $hash = md5($password);


        $sql = "INSERT INTO users (username, pass, reg_time, active)
            VALUES ('$username','$hash','$reg_time', 'FALSE')";

        if ($conn->query($sql) === TRUE) {
            header('location:Login.html.php');
        }
        else {
            echo "error in creating user: " . $conn->error;
        }
    }
    
    // closing connection
    $conn->close();
?>