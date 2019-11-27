<?php
    function filterString($string) {
        $check = FALSE;

        while(!$check) {
            if (stripos($string, '<') !== false) {
                $string = str_ireplace("<","&#60",$string);
            }else if (stripos($string, '>') !== false) {
                $string = str_ireplace(">","&#62",$string);
            }else if (stripos($string, ')') !== false) {
                $string = str_ireplace(")","&#41;",$string);
            }else if (stripos($string, '(') !== false) {
                $string = str_ireplace("(","&#40",$string);
            }else if (stripos($string, '}') !== false) {
                $string = str_ireplace("}","&#125",$string);
            }else if (stripos($string, '{') !== false) {
                $string = str_ireplace("{","&#123",$string);
            }else if (stripos($string, ']') !== false) {
                $string = str_ireplace("]","&#93",$string);
            }else if (stripos($string, '[') !== false) {
                $string = str_ireplace("[","&#91",$string);
            }else if (stripos($string, '"') !== false) {
                $string = str_ireplace('"',"&quot",$string);
            }else if (stripos($string, "'") !== false) {
                $string = str_ireplace("'","&#x27",$string);
            }else if (stripos($string, ';') !== false) {
                $string = str_ireplace(";","&#59",$string);
            }else {
                $check = TRUE;
            }
        }
        return $string;
    }
    function checkString($string) {
        if (stripos($string, '<') !== false) {
            return FALSE;
        }else if (stripos($string, '>') !== false) {
            return FALSE;
        }else if (stripos($string, ')') !== false) {
            return FALSE;
        }else if (stripos($string, '(') !== false) {
            return FALSE;
        }else if (stripos($string, '}') !== false) {
            return FALSE;
        }else if (stripos($string, '{') !== false) {
            return FALSE;
        }else if (stripos($string, ']') !== false) {
            return FALSE;
        }else if (stripos($string, '[') !== false) {
            return FALSE;
        }else if (stripos($string, '/') !== false) {
            return FALSE;
        }else if (stripos($string, '"') !== false) {
            return FALSE;
        }else if (stripos($string, "'") !== false) {
            return FALSE;
        }else if (stripos($string, ';') !== false) {
            return FALSE;
        }else{
            return TRUE;
        }
    }
?>