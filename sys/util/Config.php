<?php

class Config {

    private $_data = array();


    public function __construct() {
        $this->load(BASEPATH . DS . "sys/etc/common.php");
        $this->load(BASEPATH . DS . "app/etc/". $this->env() .".php");
    }


    public function __set($key, $value) {
        $this->_data[$key] = $value;
    }


    public function __get($key) {
        return $this->_data[$key];
    }


    private function env() {
        if (get_cfg_var('server_environment')) return get_cfg_var('server_environment');
        return (get_cfg_var('dev_server')) ? "dev": "prod";
    }


    private function load($file) {
        clog("Config ". $file);
        
        if (file_exists($file)) {
            require_once($file);
            foreach ((array)get_object_vars($config) as $k => $v)
                $this->$k = $v;
        }
    }
}
