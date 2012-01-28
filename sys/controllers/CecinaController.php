<?php

class CecinaController {
	
	private $_context;
	private $_request;
	private $_result;
	

    function __construct() {
        global $config;

        $this->_context = new Context();
        $this->_request = $_REQUEST;

        $this->_result = array();
    }


    function dispatch() {
        $controller = ucfirst($this->_context->controller) ."Controller";
        $action = $this->_context->action;

        $run = new $controller($this->_context, $this->_request);

        if (!method_exists($run, $action))
            throw new Exception("Action '$action' not found.");

        $run->before();
        $this->_result = $run->$action();
        $run->result = &$this->_result;
        $run->after();
    }


    function render() {
        try {
            $t = new View($this->_context, $this->_result, $this->_request);
            return $t->render();
            
        } catch (Exception $e) {
            die("Render error");
        }
    }
}
