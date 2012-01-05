<?php

class Net {
    static function client_ip() {

        if (isset($_SERVER["REMOTE_ADDR"]))
            return $_SERVER["REMOTE_ADDR"]; 
        
        if (isset($_SERVER["HTTP_X_FORWARDED_FOR"]))
            return $_SERVER["HTTP_X_FORWARDED_FOR"]; 
        
        if (isset($_SERVER["HTTP_CLIENT_IP"])) 
            return $_SERVER["HTTP_CLIENT_IP"]; 
        
        return "unknown.ip"; 
    }
}