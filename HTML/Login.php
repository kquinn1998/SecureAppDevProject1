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
    if(!isset($_SESSION['attempts'])){
        $_SESSION['attempts'] = 0;
    }
    if(!isset($_SESSION['ip'])){
        $_SESSION['ip'] = get_client_ip();
    }
    if(!isset($_SESSION['ip'])){
        $_SESSION['bad_login'] = FALSE;
    }

    //filter username
    $username = filterString($username);

    //check if user is locked out and do not perform login check else do.
    if($_SESSION['locked_out']){
        header("location:Login.html.php");
    }else {
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
            $hash = $password . $reg_time . $username;

            //password check
            if (md5($hash) == $hashedpassword) {
                //successful login
                session_regenerate_id();
                $_SESSION['loggedin'] = TRUE;
                $_SESSION['username'] = $username;
                $_SESSION['id'] = $id;
                
                $sql = "UPDATE users SET active = TRUE WHERE username = '$username'";
                if ($conn->query($sql) === FALSE) {
                    echo "could not access server";
                    $stmt->close();
                }
                login_event_recorder($_SESSION['ip'], $username, TRUE);
                echo $username;
                header("location:WelcomePage.html.php");
            } else {
                //wrong password
                login_event_recorder($_SESSION['ip'], $username, 0);
                $_SESSION['invalid_username'] = $username;
                $_SESSION['bad_login'] = TRUE;
                $_SESSION['attempts'] = $_SESSION['attempts'] + 1;
                header("location:Login.html.php");
            }
        } else {
            //wrong username
            login_event_recorder($_SESSION['ip'], $username, 0);
            $_SESSION['invalid_username'] = $username;
            $_SESSION['bad_login'] = TRUE;
            $_SESSION['attempts'] = $_SESSION['attempts'] + 1;
            header("location:Login.html.php");
        }
        $stmt->close();
    }

?>