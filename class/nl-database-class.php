<?php
	require_once dirname(__FILE__).'/../nl-config.php';

class Database {
	private $mysqli;
	
	public function __construct() {
		$this->real_connect(CONFIG_DB::HOSTNAME, CONFIG_DB::USERNAME, CONFIG_DB::PASSWORD, CONFIG_DB::INSTNAME);
	}
	
	public function __destruct() {
		if ($this->mysqli) {
			$this->mysqli->close();
			$this->mysqli = null;
		}
	}
	
	public function query($query) {
		$lower_query = strtolower(trim($query));
		$op = substr($lower_query, 0, 6);
	
		switch ($op) {
			case "insert":
				return $this->insert($query);
			case "update":
				return $this->update($query);
			case "delete":
				return $this->delete($query);
			case "select":
				$lower_query = trim(substr($lower_query, 7));
				if (0 == strncmp($lower_query, "count(", 6) ||
					0 == strncmp($lower_query, "count (", 7)) {
					/*  this is not a perfect method to
					 *  identify "select count(*) ...",
					 *  but should be viable enough
					 */
					return $this->count($query);
				} else {
					return $this->select($query);
				}
			default:
				return false;
		}
	}
	
	public function select($query) {
		$resultArr = array();
		$result = $this->mysqli->query($query);
		
		if ($result != null && $result->num_rows > 0) {
			while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
				//foreach ($row as $field => $value)
			 	//	echo "$field = $value <br>";
			 	$resultArr[] = $row;
			}
			$result->free();
		}
		
		return $resultArr;
	}
	
	public function count($query) {
		$count = 0;

		$row = $this->mysqli->query($query)->fetch_row();
		if (is_array($row))
			$count = $row[0];
		
		return $count;
	}
	
	public function insert($query) {
		$this->mysqli->query($query);
		return $this->mysqli->insert_id;
	}
	
	public function update($query) {
		$this->mysqli->query($query);
		return $this->mysqli->affected_rows;
	}
	
	public function delete($query) {
		$this->mysqli->query($query);
		return $this->mysqli->affected_rows;
	}
	
	public function real_escape_string($string) {
		return $this->mysqli->real_escape_string($string);
	}
	
	protected function real_connect($hostname, $username, $password, $instname) {
		$this->mysqli = new mysqli($hostname, $username, $password, $instname);
		
		if (mysqli_connect_errno()) {
			// TODO:
		}
	}
}
?>