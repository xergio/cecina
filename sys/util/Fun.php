<?php

class Fun {    

    static function fortune($plain=false) {
    	exec("/usr/games/fortune", $o);
    	$result = implode(" ", $o);
    	if ($plain)
    		$result = preg_replace("#[\r\n]+#", " ", $result);
    	return utf8_encode($result);
    }
}