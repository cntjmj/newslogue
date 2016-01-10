<?php
	require_once __DIR__.'/nl-coder-class.php';
	require_once __DIR__.'/nl-database-class.php';

class CategoryList {
	private $categoryArr = array();
	private $db;
	
	public function __construct() {
		$this->db = new Database();
	}
	
	public function getArray() {
		if ($this->categoryArr == null)
			$this->loadCategory();
		
		return $this->categoryArr;
	}
	
	public function getJson() {
		return json_encode($this->getArray());
	}
	
	private function loadCategory() {
		$query = "select * from `category` order by categoryOrder desc";
		$resultArr = $this->db->query($query);
		if (is_array($resultArr) && count($resultArr)>0) {
			$this->categoryArr["categoryList"] = $resultArr;
		}
	}
}
?>