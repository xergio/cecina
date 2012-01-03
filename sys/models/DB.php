<?php

/*
	Work in progress, query method, object_builder, __call extended with findByField1AndField2...
*/

class DB {

	public static $_connection = null;

	public function connect() {
		if (DB::$_connection) return;

		if (!(DB::$_connection = @mysql_connect($host, $user, $pass)) or !mysql_select_db($db))
			throw new Exception(@mysql_error());
	}


	public function query($sql) {
		
	}


	public function __call($function, $arguments) {
		$this->connect();

		array_push($arguments, DB::$_connection);
		$return = call_user_func_array("mysql_". $function, $arguments);

		if (!$return)
			throw new Exception(@mysql_error());

		return $return;
	}
}
