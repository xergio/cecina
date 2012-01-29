<?php

/*
	UNTESTED!!!
	Work in progress, query method, object_builder, __call extended with findByField1AndField2...
*/

class DB {

	public static $_connection = null;
	
	public $_sql = "";
	public $_table = "";
	public $_fields = array();


	public function __construct($mixed=null) {
		if (is_array($mixed)) {
			$this->set_attributes($mixed);

		} elseif ($mixed) {
			$this->id = $mixed;
		}
	}


	public function set_attributes($data) {
		$filter = array();
		foreach ((array)$data as $k => $v)
			if (array_key_exists($k, $this->_fields))
				$this->$k = $v;
	}


	public function get_attributes() {
		$filter = array();
		foreach ($this->_fields as $k => $v)
			if (property_exists($this, $k))
				$filter[$k] = $this->$k;
		return $filter;
	}


	public function connect() {
		if (!is_null(DB::$_connection)) return;

		global $config;

		if (!(DB::$_connection = mysql_pconnect($config->db_host, $config->db_user, $config->db_pass)) 
		or !mysql_select_db($config->db_name))
			throw new Exception(@mysql_error());
		mysql_set_charset("UTF8", DB::$_connection);
	}


	public function build_object($data) {
		$cn = get_class($this);
		$item = new $cn($data);
		return $item;
	}


	public function quote($field, $value) {
		if (!array_key_exists($field, $this->_fields))
			throw new Exception("Invalid field");

		$spec = $this->_fields[$field];
		
		switch ($spec[0]) {
			case "varchar":
				return "'". String::mysql_escape(substr($value, 0, $spec[1])) ."'";
			case "datetime":
			case "char":
			case "text":
				return "'". String::mysql_escape($value) ."'";
			default:
				return String::mysql_escape($value);
		}
	}


	public function query($sql) {
		$this->connect();

		clog($sql);

		$result = mysql_query($sql);
		$return = array();

		if ($result and preg_match("#^SELECT #i", trim($sql))) {
			while ($row = mysql_fetch_assoc($result)) {
			    $return[] = $this->build_object($row);
			}

			mysql_free_result($result);

		} else {
			$return = mysql_affected_rows();
		}

		return $return;
	}


	function insert() {
		$filter = $this->get_attributes();
		
		$values = array();
		foreach ($filter as $k => $v)
			$values[] = $this->quote($k, $v);
		
		$sql = "INSERT INTO ". $this->_table ." (". implode(", ", array_keys($filter)) .") VALUES (". implode(", ", $values) .")";

		if (!($return = $this->query($sql)))
			throw new Exception(@mysql_error());
		return $return;
	}


	public function __call($function, $arguments) {

		if (!method_exists($this, $function) and !function_exists("mysql_". $function))
			throw new DBException("Method ". $function ." does not exists.");
		$this->connect();

		array_push($arguments, DB::$_connection);
		$return = call_user_func_array("mysql_". $function, $arguments);

		if (!$return)
			throw new Exception(@mysql_error());

		return $return;
	}

}
