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
    function lockout_user($ip, $user_agent, $time) {
        include "con_file.php";
        $sql = "INSERT INTO locked_out_users (ip, user_agent, locked_out_time)
            VALUES ('$ip','$user_agent','$time')";
        if ($conn->query($sql) === FALSE) {
            echo "error locking out user" . $conn->error;
        }
        clear_attempts($ip,$user_agent);
    }
    function add_attempt($ip, $user_agent) {
        include "con_file.php";
        //check if attempts in db
        $sql = "SELECT id FROM attempts WHERE ip = '$ip' AND user_agent = '$user_agent'";
        $result = $conn->query($sql);
        $count = mysqli_num_rows($result);
        if(!count) {
            //init attempts
            $sql = "INSERT INTO attempts (ip, user_agent, attempts)
            VALUES ('$ip','$user_agent',1)";
            if ($conn->query($sql) === FALSE) {
                echo "error locking out user" . $conn->error;
            }
        }else{
            $sql = "UPDATE attempts SET attempts = attempts + 1 WHERE ip = '$ip' AND user_agent = '$user_agent'";
            if ($conn->query($sql) === FALSE) {
                echo "error locking out user" . $conn->error;
            }
        }
    }
    function check_attempts($ip,$user_agent){
        include "con_file.php";
        $sql = "SELECT attempts FROM attempts WHERE ip = '$ip' AND user_agent = '$user_agent'";
        $result = $conn->query($sql);
        $count = mysqli_num_rows($result);
        if(!$count) {
            //init attempts
            $sql = "INSERT INTO attempts (ip, user_agent, attempts)
            VALUES ('$ip','$user_agent',0)";
            if ($conn->query($sql) === FALSE) {
                echo "error locking out user" . $conn->error;
            }
            return 0;
        }else {
            $row = mysqli_fetch_array($result);
            return $row['attempts'];
        }
    }
    function clear_attempts($ip,$user_agent){
        include "con_file.php";
        $sql = "UPDATE attempts SET attempts = 0 WHERE ip = '$ip' AND user_agent = '$user_agent'";
            if ($conn->query($sql) === FALSE) {
                echo "error locking out user" . $conn->error;
            }
    }
    function check_if_user_locked_out($ip, $user_agent) {
        include "con_file.php";
        if ($stmt = $conn->prepare('SELECT locked_out_time FROM locked_out_users WHERE ip = ? AND user_agent = ?')) {
            // Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
            $stmt->bind_param('ss', $ip, $user_agent);
            $stmt->execute();
            // Store the result so we can check if the account exists in the database.
            $stmt->store_result();
        }
        if ($stmt->num_rows > 0) {
            $time = time();
            $stmt->bind_result($locked_out_time);
            $stmt->fetch();

            $duration = $time - $locked_out_time;
            if($duration > 180) {
                $sql = "DELETE FROM `locked_out_users` WHERE ip = '$ip' AND user_agent = '$user_agent'";
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
    function get_user_agent(){
        $agent = $_SERVER['HTTP_USER_AGENT']; 
        $browserArray = array(
                'Windows Mobile' => 'IEMobile',
                'Android Mobile' => 'Android',
                'iPhone Mobile' => 'iPhone',
                'Firefox' => 'Firefox',
                'Google Chrome' => 'Chrome',
                'Internet Explorer' => 'MSIE',
                'Opera' => 'Opera',
                'Safari' => 'Safari'
        ); 
        foreach ($browserArray as $k => $v) {
            if (preg_match("/$v/", $agent)) {
                break;
            }else {
                $k = "Browser Unknown";
            }
        } 
        $browser = $k;
        $osArray = array(
                'Windows 98' => '(Win98)|(Windows 98)',
                'Windows 2000' => '(Windows 2000)|(Windows NT 5.0)',
                'Windows ME' => 'Windows ME',
                'Windows XP' => '(Windows XP)|(Windows NT 5.1)',
                'Windows Vista' => 'Windows NT 6.0',
                'Windows 7' => '(Windows NT 6.1)|(Windows NT 7.0)',
                'Windows NT 4.0' => '(WinNT)|(Windows NT 4.0)|(WinNT4.0)|(Windows NT)',
                'Linux' => '(X11)|(Linux)',
                'Mac OS' => '(Mac_PowerPC)|(Macintosh)|(Mac OS)'
        ); 
        foreach ($osArray as $k => $v) {
            if (preg_match("/$v/", $agent)) {
                break;
            }else {
                $k = "Unknown OS";
            }
        } 
        $os = $k;
        return $browser . " : " . $os;
    }
    function check_password_strength($password) {
        if( strlen($password) < 8 ) {
            $error .= "Password must be minimum 8 charachters in length.<br>";
        } 
            
        if( strlen($password) > 32 ) {
            $error .= "Password must be maximum 32 charachters in length.<br>";
        }
            
        if( !preg_match("#[0-9]+#", $password) ) {
            $error .= "Password must include at least one number.<br>";
        }
            
        if( !preg_match("#[a-z]+#", $password) ) {
            $error .= "Password must include at least one lowercase letter.<br>";
        }
            
        if( !preg_match("#[A-Z]+#", $password) ) {
            $error .= "Password must include at least one uppercase letter.<br>";
        }
            
        if( !preg_match("#\W+#", $password) ) {
            $error .= "Password must include at least one symbol.<br>";
        }
            
        if($error){
            return $error;
        } 
        else {
            return 1;
        }
    }
?>