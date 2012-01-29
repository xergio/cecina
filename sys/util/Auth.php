<?php

class Auth {
	
	static function start() {
		if (!session_id()) session_start();
	}
	
	static function check() {
		self::start();
		
		return array_key_exists('auth', $_SESSION);
	}
}