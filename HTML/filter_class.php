<?php
    function filterString($string) {
        $check = FALSE;

        while(!$check) {
            if (stripos($string, '<script>') !== false) {
                $string = str_ireplace("<script>","",$string);
            }else if (stripos($string, '<') !== false) {
                $string = str_ireplace("<","",$string);
            }else if (stripos($string, '>') !== false) {
                $string = str_ireplace(">","",$string);
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