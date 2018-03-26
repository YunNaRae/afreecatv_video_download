<?php

class Postman {

	// postman singleton
	static $singleton;

	// mysql connection
	var $mysqlConnection;

	public static function init() {
		if ( Postman::$singleton == null) {

			// create new object
			Postman::$singleton = new Postman();

			// create connection
			Postman::$singleton->connect();
		}

		return Postman::$singleton;
	}

	public function connect() {

		if ($this->mysqlConnection  == null ) {

			$this->mysqlConnection = mysqli_init();

			require_once('/var/www/philgookang/afreecatv/config.php');

			if(mysqli_real_connect($this->mysqlConnection, $host, $username, $password, $database, $port)) {
				mysqli_set_charset( $this->mysqlConnection, 'utf8mb4' );
				mysqli_query($this->mysqlConnection, 'SET NAMES utf8mb4');
			}
		}

		return $this->mysqlConnection;
	}

	function db_bind_param(&$stmt, $params) {
		$f = array($stmt, "bind_param");
		return call_user_func_array($f, $params);
	}

	function __destruct() {
		if ( $this->mysqlConnection != null ) {
			@mysqli_close($this->mysqlConnection);
			$this->mysqlConnection = null;
			Postman::$singleton = null;
		}
	}

	function close() {
		if ( $this->mysqlConnection != null ) {
			@mysqli_close($this->mysqlConnection);
			$this->mysqlConnection = null;
			Postman::$singleton = null;
		}
	}

	// -------------------------------------------------

	function execute($query, $params, $return_insert_idx = false) {

		$stmt = $this->mysqlConnection->stmt_init();
		$stmt = $this->mysqlConnection->prepare($query);

		$this->db_bind_param($stmt, $params);
		$result = $stmt->execute();
		$result = $stmt->get_result();

		if ( $return_insert_idx ) {
			return $stmt->insert_id;
		} else {
			return $result;
		}
	}

	function returnDataList($query, $params) {

		$result = $this->execute($query, $params);

		$return_data = array();
		while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
			$object = new stdClass();
			foreach ($row as $key => $value) {
				$object->$key = $value;
			}
			array_push($return_data, $object);
		}

		return $return_data;
	}

	function returnDataObject($query, $params) {
		$list = $this->returnDataList($query, $params);
		return (isset($list[0])) ? $list[0] : new stdClass();
	}
}
