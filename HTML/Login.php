<?php
    session_start();

    //db connection
    include "con_file.php";
    //filter class
    include "filter_class.php";
    //functions class
    include "functions.php";

    //if attempts hasnt started init
    if(!isset($_SESSION['attempts'])){
        $_SESSION['attempts'] = 0;
    }else if{
        
    }else {
        //checking if limit reached
        if($_SESSION['attempts'] >= 5){
            $IP = get_client_ip();
            $locked_out_time = time();
            $sql = "INSERT INTO lockedOutUser (IP,locked_out_time)
            VALUES ('$IP','$locked_out_time')";
            if ($conn->query($sql) === FALSE) {
                echo "error locking out user"
            }
            $conn->close();
            header("location:Login.html.php");
        }
    }

    //user input credentials;
    $username = $_POST['username'];
    $password = $_POST['pass'];

    //filter username
    $username = filterString($username);

    if ( !isset($username, $password) ) {
        // Could not get the data that should have been sent.
        $conn->close();
        echo "no details recieved";
    }
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
                session_destroy();
                $stmt->close();
            }

            header("location:WelcomePage.html.php");
        } else {
            //wrong password
            $_SESSION['invalid_username'] = $username;
            $_SESSION['attempts'] = $_SESSION['attempts'] + 1;
            header("location:Login.html.php");
        }
    } else {
        //wrong username
        $_SESSION['invalid_username'] = $username;
        $_SESSION['attempts'] = $_SESSION['attempts'] + 1;
        header("location:Login.html.php");
    }
    $stmt->close();

?>