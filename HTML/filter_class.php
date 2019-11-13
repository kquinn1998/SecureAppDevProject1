<?php
    function filterString($string) {
        $check = FALSE;

        while(!$check) {
            if (stripos($string, '<') !== false) {
                $string = str_ireplace("<","&lt",$string);
            }else if (stripos($string, '>') !== false) {
                $string = str_ireplace(">","&gt",$string);
            }else {
                $check = TRUE;
            }
        }
        return $string;
    }
    function checkString($string) {
        if (stripos($string, '<script>') !== false) {
            echo "bad";
            return FALSE;
        }else{
            echo "good";
            return TRUE;
        }
    }
?>