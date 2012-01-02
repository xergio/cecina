<?php

class AppController {
	
	function before() {
		// this will by executed BEFORE the action
	}


	function index() {
		return array("hello" => "bye");
	}


	function after() {
		// this will by executed AFTER the action
	}
}
