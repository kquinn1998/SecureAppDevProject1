# SecureAppDevProject1
function lockout_user($ip, $time) {
        $sql = "INSERT INTO locked_out_users (ip, locked_out_time)
            VALUES ('$ip','$time')";
    }
    function check_if_user_locked_out($ip) {
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
                return FALSE;
            }else {
                return TRUE;
            }
        }else {
            return FALSE;
        }
    }