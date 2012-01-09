<?php

class CustomException extends Exception {

    static public $reverse_errors = array(
        "1" => "E_ERROR", 
        "2" => "E_WARNING", 
        "4" => "E_PARSE", 
        "8" => "E_NOTICE", 
        "16" => "E_CORE_ERROR", 
        "32" => "E_CORE_WARNING" ,
        "64" => "E_COMPILE_ERROR" ,
        "128" => "E_COMPILE_WARNING" ,
        "256" => "E_USER_ERROR" ,
        "512" => "E_USER_WARNING" ,
        "1024" => "E_USER_NOTICE" ,
        "2048" => "E_STRICT" ,
        "4096" => "E_RECOVERABLE_ERROR" ,
        "8192" => "E_DEPRECATED", 
        "16384" => "E_USER_DEPRECATED", 
        "32767" => "E_ALL"
    );

    function __construct($message="", $code=0, $previous=null) {
        
        $this->ip           = Net::client_ip();
        $this->referer      = $_SERVER["HTTP_REFERER"];
        $this->user_agent   = $_SERVER["HTTP_USER_AGENT"];
        $this->hostname     = php_uname('n');

        parent::__construct($message, $code, $previous);
    }


    static function handle_exception($exception) {
    	global $config;

        header("HTTP/1.1 500 Internal Server Error");

        $tpl = new View(
            new Context(array("layout" => $config->default_error_tpl)),
	        array("error" => $exception->getMessage(), "code" => "500", "trace" => str_replace(" ". BASEPATH. "/", " ", $exception->getTraceAsString()) )
	    );
        die($tpl->render(false));
    }


    static function handle_error($errno, $errstr, $errfile, $errline) {
        if ($errno == E_NOTICE) return;

        global $config;

        header("HTTP/1.1 500 Internal Server Error");

        $tpl = new View(
            new Context(array("layout" => $config->default_error_tpl)), 
            array("error" => $errstr, "code" => "#". CustomException::$reverse_errors[$errno], "trace" => String::trace_to_str(debug_backtrace()) )
        );
        die($tpl->render(false));
    }


    static function handle_fatal_error($arr) {
        CustomException::handle_error($arr['type'], $arr['message'], $arr['file'], $arr['line']);
    }
}
