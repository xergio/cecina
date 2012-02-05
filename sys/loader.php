<?php

define('BASEPATH', dirname(dirname(__FILE__)));
define('DS', DIRECTORY_SEPARATOR);


if (DEBUG) {
    openlog("cecina", LOG_PID, LOG_LOCAL0);
    //register_shutdown_function("shutdown");

    function clog($str, $mode=LOG_INFO) {
        syslog($mode, $str);
    }

} else {
    function clog($mode=null) {}
}


function __require($file) {
    if ( !file_exists($file) ) return false;

    require_once($file);
    return true;
}


function __include_once($file) {
    if ( !file_exists($file) ) return false;

    include_once($file);
    return true;
}


function __autoload($class_name) {
    $mainfolders = array("app", "sys");
    $subfolders = array("exceptions", "controllers", "models", "util");

    foreach ($mainfolders as $folder)
        foreach ($subfolders as $sub)
            if ( __require(BASEPATH . DS . $folder . DS . $sub . DS . $class_name .".php") )
                return true;

    if (preg_match("#Exception$#", $class_name)) return eval("class ". $class_name ." extends CustomException { }");

    die("autoload fail in ". $class_name .".");
}

/*
function shutdown() {
    $error = error_get_last(); 
    if (!is_null($error) and $error['type'] == 1)
        call_user_func(array("CustomException", "handle_fatal_error"), $error);

    closelog();
}
*/

$config = new Config();


//$old_error_handler = set_error_handler("exception_error_handler");
$old_error_handler = set_error_handler(array("CustomException", "handle_error"));
$old_exception_handler = set_exception_handler(array("CustomException", "handle_exception"));

//clog("Starting!");
