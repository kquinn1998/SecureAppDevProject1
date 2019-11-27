<?php
    session_start();

    //db connection
    include "con_file.php";
    //filter class
    include "filter_class.php";
    //functions class
    include "functions.php";

    //user input credentials;
    $username = $_POST['username'];
    $password = $_POST['pass'];

    //init session variables
    if(!isset($_SESSION['locked_out'])){
        $_SESSION['locked_out'] = FALSE;
    }
    if(!isset($_SESSION['ip'])){
        $_SESSION['ip'] = get_client_ip();
    }
    if(!isset($_SESSION['user_agent'])){
        $_SESSION['user_agent'] = get_user_agent();
    }
    if(!isset($_SESSION['attempts'])){
        $_SESSION['attempts'] = check_attempts($_SESSION['ip'],$_SESSION['user_agent']);
    }
    if(!isset($_SESSION['bad_login'])){
        $_SESSION['bad_login'] = FALSE;
    }

    //filter username
    $username = filterString($username);

    if(!check_if_user_locked_out($_SESSION['ip'], $_SESSION['user_agent'])){
        $_SESSION['locked_out'] = FALSE;
    }else{
        $_SESSION['locked_out'] = TRUE;
        header("location:Login.html.php");
    }

    //check if user is locked out and do not perform login check else do.
    if($_SESSION['locked_out']){
        header("location:Login.html.php");
    }else {
        if ($stmt = $conn->prepare('SELECT id, pass, reg_time, priv FROM users WHERE username = ?')) {
            // Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
            $stmt->bind_param('s', $username);
            $stmt->execute();
            // Store the result so we can check if the account exists in the database.
            $stmt->store_result();
        }
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $hashedpassword, $reg_time, $admin);
            $stmt->fetch();

            //ready for decrypt from hashed salt
            $reg_time = md5($reg_time);
            $hash = $password . $reg_time;

            //password check
            if (md5($hash) == $hashedpassword) {
                //successful login
                session_regenerate_id();
                $_SESSION['loggedin'] = TRUE;
                $_SESSION['username'] = $username;
                $_SESSION['id'] = $id;
                $_SESSION['admin'] = $admin;
                
                $sql = "UPDATE users SET active = TRUE WHERE username = '$username'";
                if ($conn->query($sql) === FALSE) {
                    echo "could not access server";
                    $stmt->close();
                }
                clear_attempts($_SESSION['ip'],$_SESSION['user_agent']);
                login_event_recorder($_SESSION['ip'], $username, TRUE);
                header("location:WelcomePage.html.php");
            } else {
                //wrong password
                login_event_recorder($_SESSION['ip'], $username, 0);
                $_SESSION['invalid_username'] = $username;
                $_SESSION['bad_login'] = TRUE;
                add_attempt($_SESSION['ip'],$_SESSION['user_agent']);
                $_SESSION['attempts'] = check_attempts($_SESSION['ip'],$_SESSION['user_agent']);
                header("location:Login.html.php");
            }
        } else {
            //wrong username
            login_event_recorder($_SESSION['ip'], $username, 0);
            $_SESSION['invalid_username'] = $username;
            $_SESSION['bad_login'] = TRUE;
            add_attempt($_SESSION['ip'],$_SESSION['user_agent']);
            $_SESSION['attempts'] = check_attempts($_SESSION['ip'],$_SESSION['user_agent']);
            header("location:Login.html.php");
        }
        $stmt->close();
    }

?>