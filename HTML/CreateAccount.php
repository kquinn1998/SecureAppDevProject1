<?php
    include 'con_file.php';

    $username = $_POST['username'];
    $password = $_POST['pass'];
    $reg_time = time();

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
    
    // closing connection
    $conn->close();

    function generateRandomString($length) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
?>