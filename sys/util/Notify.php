<?php

class Notify {
	
	private static function start($type=null) {
		if (!session_id()) session_start();
		if (!array_key_exists('notify', $_SESSION)) $_SESSION['notify'] = array();
		if ($type and !array_key_exists($type, $_SESSION['notify'])) $_SESSION['notify'][$type] = array();
	}
	
	
	private static function add($type, $msg) {
		self::start($type);
		
		$_SESSION['notify'][$type][] = $msg;
	}
	
	
	static function consume() {
		self::start();
		
		$r = $_SESSION['notify'];
		$_SESSION['notify'] = array();
		return $r;
	}
	
	
	static function info($str) {
		self::add('info', $str);
	}
	
	
	static function error($str) {
		self::add('error', $str);
	}
	
	
	static function success($str) {
		self::add('success', $str);
	}
	
	
	static function warning($str) {
		self::add('warning', $str);
	}
}