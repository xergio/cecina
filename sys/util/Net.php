<?php

class Net {

    static private $v_client_ip = "unknown.ip";

    static function client_ip() {
        if (self::$v_client_ip) return self::$v_client_ip;

        if (isset($_SERVER["REMOTE_ADDR"]))
            self::$v_client_ip = $_SERVER["REMOTE_ADDR"]; 
        
        if (isset($_SERVER["HTTP_X_FORWARDED_FOR"]))
            self::$v_client_ip = $_SERVER["HTTP_X_FORWARDED_FOR"]; 
        
        if (isset($_SERVER["HTTP_CLIENT_IP"])) 
            self::$v_client_ip = $_SERVER["HTTP_CLIENT_IP"];
        
        return self::$v_client_ip; 
    }
}