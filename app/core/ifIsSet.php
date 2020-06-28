<?php
class IfIsSet {
    public static function verify($var, $json = FALSE, $encode = FALSE, $strip = FALSE) 
    {
        
        if(isset($var))
        {
            
            if($json == FALSE && $encode == FALSE && $strip == FALSE)
            {
                return $var; 
            }
            
            if($json == true) 
            {
                return json_encode($var);
            } 
            elseif($encode == true)
            {
                return base64_encode($var);
            }
            elseif($strip == true)
            {
                return strip_tags($var);
            }
           
        }
        elseif(!isset($var) || empty($var))
        {
            return NULL;
        }
        
    }
}