<?php

class CustomException extends Exception {

    function __construct($message="", $code=0, $previous=null) {
        
        $this->ip           = Net::client_ip();
        $this->referer      = $_SERVER["HTTP_REFERER"];
        $this->user_agent   = $_SERVER["HTTP_USER_AGENT"];
        $this->hostname     = php_uname('n');

        parent::__construct($message, $code, $previous);
    }


    static function handle_exception($exception) {
    	global $config;

        $tpl = new View(
            new Context(array("layout" => $config->default_error_tpl)),
	        array("error" => $exception->getMessage(), "code" => "500", "trace" => str_replace(" ". BASEPATH. "/", " ", $exception->getTraceAsString()) )
	    );
        die($tpl->render(false));
    }


    // http://www.php.net/manual/en/class.errorexception.php
    static function handle_error($errno, $errstr, $errfile, $errline) {
        if ($errno == E_NOTICE) return;

        global $config;

        $tpl = new View(
            new Context(array("layout" => $config->default_error_tpl)), 
            array("error" => $errstr, "code" => "#". $errno, "trace" => String::trace_to_str(debug_backtrace()) )
        );
        die($tpl->render(false));
    }
}
