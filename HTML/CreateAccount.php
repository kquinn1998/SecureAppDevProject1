<?php
	ini_set('session.cookie_httponly', 1);
    session_start();

    //connection made
    include 'con_file.php';
    include 'filter_class.php';
    include 'functions.php';

    //init session variables
    if(!isset($_SESSION['locked_out_create_account'])){
        $_SESSION['locked_out'] = FALSE;
    }
    if(!isset($_SESSION['ip'])){
        $_SESSION['ip'] = get_client_ip();
    }
    if(!isset($_SESSION['user_agent'])){
        $_SESSION['user_agent'] = get_user_agent();
    }
    if(!isset($_SESSION['attempts_create_account'])){
        $_SESSION['attempts'] = check_attempts_create_account($_SESSION['ip'],$_SESSION['user_agent']);
    }

    if(!check_if_user_locked_out_create_account($_SESSION['ip'], $_SESSION['user_agent'])){
        $_SESSION['locked_out_create_account'] = FALSE;
    }else{
        $_SESSION['locked_out_create_account'] = TRUE;
        header("location:CreateAccount.html.php");
    }

    $username = $_POST['username'];
    $password = $_POST['pass'];
    $confirm_password = $_POST['confirm_pass'];
    $reg_time = time();

    if($password != $confirm_password){
        $_SESSION['error_password'] = "Passwords Dont Match.<br>";
        add_attempt_create_account($_SESSION['ip'],$_SESSION['user_agent']);
        header("location:CreateAccount.html.php");
        die();
    }
    unset($_SESSION['error_password']);

    //password check
    $result = check_password_strength($password);
    if($result == 1) {
        unset($_SESSION['error_password']);
    }else {
        $_SESSION['error_password'] = $result;
        add_attempt_create_account($_SESSION['ip'],$_SESSION['user_agent']);
        header('location:CreateAccount.html.php');
        die();
    }

    if(!checkString($username)) {
        $_SESSION['error_username'] = "Cannot use this username";
        add_attempt_create_account($_SESSION['ip'],$_SESSION['user_agent']);
        header('location:CreateAccount.html.php');    
    }else{
        //Checking if username exists
        $sql = "SELECT id FROM users WHERE username = '$username'";
        $result = $conn->query($sql);
        $count = mysqli_num_rows($result);
        if($count > 0) {
            $_SESSION['error_username'] = "Cannot use this username";
            add_attempt_create_account($_SESSION['ip'],$_SESSION['user_agent']);
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