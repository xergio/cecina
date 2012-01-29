<?php

class HTTP {

	public static function get($url, $timeout=30, $followlocation=true) {
		$cookieFileLocation = '/tmp/cookie.txt';

		$s = curl_init();

		curl_setopt($s, CURLOPT_URL,			$url);
		curl_setopt($s, CURLOPT_HTTPHEADER,		array("Expect:"));
		curl_setopt($s, CURLOPT_TIMEOUT,		$timeout);
		curl_setopt($s, CURLOPT_MAXREDIRS,		5);
		curl_setopt($s, CURLOPT_RETURNTRANSFER,	true);
		curl_setopt($s, CURLOPT_FOLLOWLOCATION,	$followlocation);
		curl_setopt($s, CURLOPT_COOKIEJAR,		$cookieFileLocation);
		curl_setopt($s, CURLOPT_COOKIEFILE,		$cookieFileLocation);
		curl_setopt($s, CURLOPT_USERAGENT,		"Mozilla/5.0 (Ubuntu; X11; Linux x86_64; rv:9.0.1) Gecko/20100101 Firefox/9.0.1");
		curl_setopt($s, CURLOPT_REFERER,		"http://google.com");
		curl_setopt($s, CURLOPT_HEADER, 		true);
/*
		if($this->authentication == 1){
			curl_setopt($s, CURLOPT_USERPWD, $this->auth_name.':'.$this->auth_pass);
		}
		if($this->_post)
		{
			curl_setopt($s,CURLOPT_POST,true);
			curl_setopt($s,CURLOPT_POSTFIELDS,$this->_postFields);

		}
*/
		$c = curl_exec($s);
		$parts = preg_split("#\r?\n\r?\n#", $c, 2);
		
		$return = array(
			"content" 	=> $parts[1],
			"status"	=> curl_getinfo($s, CURLINFO_HTTP_CODE),
			"headers"	=> $parts[0]
		);
		curl_close($s);

		return $return;
	}
	
}
