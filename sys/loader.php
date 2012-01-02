<?php

define('BASEPATH', dirname(dirname(__FILE__)));
define('DS', DIRECTORY_SEPARATOR);


if (DEBUG) {
    openlog("cecina", LOG_PID, LOG_LOCAL0);
    register_shutdown_function("shutdown");

    function clog($str, $mode=LOG_INFO) {
        syslog($mode, $str);
    }

} else {
    function clog($mode=null) {}
}


function __include($file) {
    if ( !file_exists($file) ) return false;

    require_once($file);
    return true;
}


function __autoload($class_name) {
    $mainfolders = array("sys", "app");
    $subfolders = array("etc", "exceptions", "controllers", "models", "util");

    foreach ($mainfolders as $folder)
        foreach ($subfolders as $sub)
            if ( __include(BASEPATH . DS . $folder . DS . $sub . DS . $class_name .".php") )
                return true;

    die("autoload fail in ". $class_name .".");
}


function shutdown() {
    closelog();
}


//clog("Starting!");
