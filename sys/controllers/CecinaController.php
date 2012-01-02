<?php

class CecinaController {

    function __construct() {
        
        $path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

        preg_match("#^/(?P<controller>[^/]+)(/(?P<action>[^/]+))?(/(?P<params>.*))?#", $path, $m);

        $this->context = array('server' => $_SERVER);
        $this->context['controller']    = $m["controller"]? strtolower($m["controller"]): "app";
        $this->context['action']        = $m["action"]? strtolower($m["action"]): "index";
        $this->context['layout']        = "public";
        $this->context['media']         = "html"; // future media support: html, json, xml...
        $this->params                   = $m["params"]? preg_split("#/#", $m["params"]): array();
        $this->request                  = $_REQUEST;

        $this->result = array();
    }


    function dispatch() {
        $controller = ucfirst($this->context['controller']) ."Controller";
        $action = $this->context['action'];

        $run = new $controller($this->context, $this->params, $this->request);

        if (!method_exists($run, $action))
            throw new Exception('Action not found.');

        $run->before();
        $this->result = $run->$action();
        $run->after();
    }


    function render() {
        try {
            $t = new View($this->result, $this->context, $this->params, $this->request);
            return $t->render();
            
        } catch (Exception $e) {
            die("Render error");
        }
    }
}
