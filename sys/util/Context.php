<?php

class Context {
    public $server;
    public $controller;
    public $action;
    public $layout;
    public $media;


    function __construct($data) {
        global $config;

        $this->server       = $_SERVER;
        $this->controller   = (array_key_exists("controller", $data) and $data["controller"])? 
            strtolower($data["controller"]): 
            $config->default_controller;

        $this->action       = (array_key_exists("action", $data) and $data["action"])?
            strtolower($data["action"]): 
            $config->default_action;

        $this->layout       = (array_key_exists("layout", $data) and $data["layout"])?
            $data["layout"]: 
            $config->default_layout;

        $this->media        = $config->default_media; // future media support: html, json, xml...
    }
}
