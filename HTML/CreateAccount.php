<?php
    session_start();

    //connection made
    include 'con_file.php';
    include 'filter_class.php';
    include 'functions.php';

    $username = $_POST['username'];
    $password = $_POST['pass'];
    $reg_time = time();

    //password check
    $result = check_password_strength($password);
    if($result == 1) {
        unset($_SESSION['error_password']);
    }else {
        $_SESSION['error_password'] = $result;
        header('location:CreateAccount.html.php');
        die();
    }

    if(!checkString($username)) {
        $_SESSION['error_username'] = "Cannot use this username";
        header('location:CreateAccount.html.php');    
    }else{
        //Checking if username exists
        $sql = "SELECT id FROM users WHERE username = '$username'";
        $result = $conn->query($sql);
        $count = mysqli_num_rows($result);
        if($count > 0) {
            $_SESSION['error_username'] = "Cannot use this username";
            header('location:CreateAccount.html.php');
            die();
        }
        //Password Storage Process
        $salt = md5($reg_time);

        $password = $password . $salt;

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