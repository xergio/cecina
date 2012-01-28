<?php

class AppController {
	
	protected $context;
	protected $request;
	protected $params;
	
	function __construct($context, $request) {
		$this->context = $context;
		$this->request = $request;
		$this->params = $context->params;
	}
	
	function before() {
		// this will by executed BEFORE the action
	}


	function index() {
		return array("hello" => "see you!"); // returned variabled will be passed to the View
	}


	function after() {
		// this will by executed AFTER the action
	}
}
