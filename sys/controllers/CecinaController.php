<?php

class CecinaController {

    function __construct() {
        global $config;

        $path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

        preg_match("#^/(?P<controller>[^/]+)(/(?P<action>[^/]+))?(/(?P<params>.*))?#", $path, $m);

        $this->context = new Context($m);
        $this->params = (array_key_exists("params", $m) and $m["params"])? preg_split("#/#", $m["params"]): array();
        $this->request = $_REQUEST;

        $this->result = array();
    }


    function dispatch() {
        $controller = ucfirst($this->context->controller) ."Controller";
        $action = $this->context->action;

        $run = new $controller($this->context, $this->params, $this->request);

        if (!method_exists($run, $action))
            throw new Exception('Action not found.');

        $run->before();
        $this->result = $run->$action();
        $run->after();
    }


    function render() {
        try {
            $t = new View($this->context, $this->result, $this->params, $this->request);
            return $t->render();
            
        } catch (Exception $e) {
            die("Render error");
        }
    }
}
