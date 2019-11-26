<?php
    session_start();

    //connection made
    include 'con_file.php';
    include 'filter_class.php';
    include 'functions.php';

    //using get as we are asked to in doc even though this is unsecure
    $old_password = $_GET['old_password'];
    $new_password = $_GET['new_password'];
    $confirm_password = $_GET['confirm_password'];
    $form_token = $_GET['token'];
    $username = $_SESSION['username'];

    //CSRF Check
    if(!hash_equals($_SESSION['CSRF_token'], $form_token)){
        $_SESSION['error_password'] = "CSRF token did not verify try login and try again.<br>";
        header("location:ChangePassword.html.php");
        die();
    }

    //checking if old password is correct
    if ($stmt = $conn->prepare('SELECT id, pass, reg_time FROM users WHERE username = ?')) {
        // Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
        $stmt->bind_param('s', $username);
        $stmt->execute();
        // Store the result so we can check if the account exists in the database.
        $stmt->store_result();
    }
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $hashedpassword, $reg_time);
        $stmt->fetch();

        //ready for decrypt from hashed salt
        $time = md5($reg_time);
        $hash = $old_password . $time;
        $hash = md5($hash);
        if($hash != $hashedpassword){
            $_SESSION['error_password'] = "Old password is incorrect.<br>";
            header("location:ChangePassword.html.php");
            die();
        }
    } else {
        header("location:Logout.php");
        die();
    }
    unset($_SESSION['error_password']);

    //check if new password matches confirmed password;
    if($new_password != $confirm_password){
        $_SESSION['error_password'] = "New Password and Confirmed Password do not match.<br>";
        header("location:ChangePassword.html.php");
        die();
    }
    unset($_SESSION['error_password']);

    //password check
    $result = check_password_strength($new_password);
    if($result == 1) {
        unset($_SESSION['error_password']);
    }else {
        $_SESSION['error_password'] = $result;
        header('location:ChangePassword.html.php');
        die();
    }
    $time = md5($reg_time);
    $hash = $new_password . $time;
    $hash = md5($hash);

    $sql = "UPDATE users SET pass = '$hash' WHERE username = '" . $_SESSION['username'] . "'";
    if ($conn->query($sql) === TRUE) {
        header('location:Logout.php');
    }
    
?>