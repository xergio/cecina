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


	public function connect() {
		if (!is_null(DB::$_connection)) return;

		global $config;

		if (!(DB::$_connection = mysql_connect($config->db_host, $config->db_user, $config->db_pass)) 
		or !mysql_select_db($config->db_name))
			throw new Exception(@mysql_error());
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

		} else {
			$return = mysql_affected_rows();
		}

		mysql_free_result($result);

		return $return;
	}


	function select($fields=array("*")) {
		
		$this->_sql = "";
		$this->_sql .= "SELECT ". implode(", ", $fields) ." FROM ". $this->_table;
		return $this;
	}


	function insert() {
		
		$this->_sql = "";
		$this->_sql .= "INSERT INTO ". $this->_table;
		return $this;
	}


	function update() {
		
		$this->_sql = "";
		$this->_sql .= "UPDATE ". $this->_table;
		return $this;
	}


	function delete() {
		
		$this->_sql = "";
		$this->_sql .= "DELETE FROM ". $this->_table;
		return $this;
	}


	function where($mixed) {
		$where = array();

		if (is_array($mixed)) {
			foreach ((array)$mixed as $k => $v)
				$where[] = $k ."=". $this->quote($k, $v);

		} elseif ($mixed) {
			$where[] = "id = ". $mixed;
		}

		$this->_sql .= " WHERE ". implode(" AND ", $where);
		return $this;
	}


	function go() {
		return $this->query($this->_sql);
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
