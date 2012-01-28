<?php

class Config {

    private $_data = array();


    public function __construct() {
        $this->load(BASEPATH . DS . "settings.php");
    }


    public function __set($key, $value) {
        $this->_data[$key] = $value;
    }


    public function __get($key) {
        return $this->_data[$key];
    }


    private function load($file) {
        if (file_exists($file)) {
            require_once($file);
            foreach ((array)get_object_vars($config) as $k => $v)
                $this->$k = $v;
        }
    }
}
