<?php

class Notify {
	
	public static  $strings = array();
	
	
	static function error($str) {
		Notify::$strings['error'][] = $str;
	}
	
	
	static function info($str) {
		Notify::$strings['info'][] = $str;
	}
	
	
	static function ok($str) {
		Notify::$strings['ok'][] = $str;
	}
}