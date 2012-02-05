<?php

class Context {
    public $controller;
    public $action;
    public $layout;
    public $media;
    public $params = array();


    function __construct($overwrite=array()) {
        global $config;
        
        $parsed_path = $this->parse_path();
        
        $this->controller   = (array_key_exists("controller", $parsed_path) and $parsed_path["controller"])? 
            strtolower($parsed_path["controller"]): 
            $config->default_controller;

        $this->action       = (array_key_exists("action", $parsed_path) and $parsed_path["action"])?
            strtolower($parsed_path["action"]): 
            $config->default_action;

        $this->layout       = $config->default_layout;
        
        //$this->media        = $config->default_media; // future media support: html, json, xml...
        $this->media 		= (array_key_exists("media", $parsed_path) and $parsed_path["media"])?
            strtolower($parsed_path["media"]): 
            $config->default_media;
        
        $this->params 		= (array_key_exists("params", $parsed_path) and $parsed_path["params"])? 
        	preg_split("#/#", $parsed_path["params"]): 
        	array();
        
        foreach ($overwrite as $k => $v)
        	$this->$k = $v;
    }
    
    
    function parse_path() {
    	$path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
    	preg_match("#^/(?P<controller>[^/]+)(/(?P<action>[^/\.]+)(\.(?P<media>[^/]+))?)?(/(?P<params>.*))?#", $path, $data);
    	
    	return $data;
    }
    
    
    function basic_info() {
    	return array(
    		'controller' => $this->controller,
    		'action' => $this->action,
    		'media' => $this->media
    	);
    }
}
