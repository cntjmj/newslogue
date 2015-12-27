<?php
	require_once dirname(__FILE__).'/nl-coder-class.php';
	require_once dirname(__FILE__).'/nl-database-class.php';

class NewsList {
	private $num_per_page, $page_num, $categoryID, $summary_len;
	private $newsMetaArr = array();
	private $total_news_num = -1;
	private $db;

	public function __construct($page_num, $num_per_page, $categoryID, $summary_len) {
		$this->page_num = $page_num;
		$this->num_per_page = $num_per_page;
		$this->categoryID = $categoryID;
		$this->summary_len = $summary_len;
		
		$this->db = new Database();
	}

	public function getJson() {
		 return json_encode($this->getArray());
	}

	public function getArray() {
		if ($this->newsMetaArr == null)
			$this->loadNewsList();

		return $this->newsMetaArr;
	}

	protected function loadNewsList() {
		$this->num_per_page = $this->db->real_escape_string($this->num_per_page);
		$this->page_num = $this->db->real_escape_string($this->page_num);
		$this->categoryID = trim($this->db->real_escape_string($this->categoryID));
		
		$start_record = $this->page_num * $this->num_per_page;

		$query = "select *,na.createdDateTime as nacreatedDateTime from `newsarticle` na 
				inner join `category` c on na.categoryID = c.categoryID
				where na.newsStatus = 'active'";
		
		if ($this->categoryID != "")
			$query .= " and na.categoryID = ".$this->categoryID;
		
		$query .= " order by na.createdDateTime desc";
		$query .= " limit $start_record, $this->num_per_page";

		$newsArray = $this->db->query($query);

		if (is_array($newsArray) && count($newsArray) > 0) {
			$newsObj = new News(0, $this->summary_len);
			foreach ($newsArray as $id => $news) {
				$newsResult = $newsObj->setModelNews($news)->getArray();
				$newsArray[$id] = $newsResult["news"];
			}
			$this->newsMetaArr["newsMetaList"] = $newsArray;
		}
	}
}

class News {
	private $newsID;
	private $summary_len;
	private $newsResult = array();	// it should ALWAYS be view ready
	private $db;
	
	public function __construct($newsID = 0, $summary_len = -1) {
		$this->newsID = $newsID;
		$this->summary_len = $summary_len;
		
		$this->db = new Database();
	}
	
	public function getArray() {
		if ($this->newsResult == null)
			$this->loadNews();

		return $this->newsResult;
	}
	
	public function getJson() {
		return json_encode($this->getArray());
	}
	
	public function setModelNews($model) {
		if (is_array($model) && isset($model["newsID"])) {
			$this->newsResult["news"] = $this->model2view($model);
			$this->newsID = $this->newsResult["news"]["newsID"];
			$this->summarize();
		} else {
			// something is wrong
			$this->newsResult = array();
			$this->newsID = 0;
		}
		
		return $this;
	}
	
	protected function loadNews() {
		$this->newsID = $this->db->real_escape_string($this->newsID);

		$query = "select * from newsarticle na inner join `category` c 
				on na.categoryID = c.categoryID where newsID = $this->newsID";

		$result = $this->db->query($query);
		
		if (is_array($result) && is_array($result[0]))
			$this->setModelNews($result[0]);
	}
	
	protected function summarize() {
		Coder::summarize($this->newsResult["news"]["newsContent"], $this->summary_len);
	}
	
	protected function model2view(&$model) {
		foreach ($model as $key => $value)
			Coder::cleanData($model[$key]);
		Coder::dbstr2date($model["nacreatedDateTime"]);
		Coder::htmldecode($model["newsContent"]);

		return $model;
	}
	
	protected function view2model() {
		;
	}
}
?>