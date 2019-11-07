<?php
    include "con_file.php";
    //returns client users ip address
    function get_client_ip() {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if(getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if(getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if(getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if(getenv('HTTP_FORWARDED'))
           $ipaddress = getenv('HTTP_FORWARDED');
        else if(getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }
    function lockout_user($ip, $time) {
        include "con_file.php";
        $sql = "INSERT INTO locked_out_users (ip, locked_out_time)
            VALUES ('$ip','$time')";
        if ($conn->query($sql) === FALSE) {
            echo "error locking out user" . $conn->error;
        }
    }
    function check_if_user_locked_out($ip) {
        include "con_file.php";
        if ($stmt = $conn->prepare('SELECT locked_out_time FROM locked_out_users WHERE ip = ?')) {
            // Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
            $stmt->bind_param('s', $ip);
            $stmt->execute();
            // Store the result so we can check if the account exists in the database.
            $stmt->store_result();
        }
        if ($stmt->num_rows > 0) {
            $time = time();
            $stmt->bind_result($locked_out_time);
            $stmt->fetch();

            $duration = $time - $locked_out_time;
            if($duration > 30) {
                $sql = "DELETE FROM `locked_out_users` WHERE ip = '$ip'";
                if ($conn->query($sql) === FALSE) {
                    echo "error deleting locked out user" . $conn->error;
                }
                return FALSE;
            }else {
                return TRUE;
            }
        }else {
            return FALSE;
        }
    }
    function login_event_recorder($ip,$username,$successful){
        include "con_file.php";
        $sql = "INSERT INTO login_events (ip, username, successful)
            VALUES ('$ip','$username','$successful')";
        if ($conn->query($sql) === FALSE) {
            echo "error recording login event" . $conn->error;
        }
    }
?>