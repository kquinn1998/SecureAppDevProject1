<?php
    function filterString($string) {
        $check = FALSE;

        while(!$check) {
            if (stripos($string, '<script>') !== false) {
                $string = str_ireplace("<script>","",$string);
            }else {
                $check = TRUE;
            }
        }
        return $string;
    }
?>