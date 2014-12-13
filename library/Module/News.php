<?php
class Module_News extends Module_ObjectDb{
	private $_data;
	
	public function __construct(){
		parent::__construct();
	}
	
	//主頁
	public function index(){
		$this->_layout = "index";
		$pageNo = $this->getVariables("pageNo");
		$pageNo = (strcmp($pageNo, "")) ? $pageNo : 1;
		$data = $this->selectDb("news", array("strWhe" => array("ACTIVE = 'Y'")));
		
		$this->_data = $this->getPageList($data, "/news/index/", $pageNo);
	}
	
	public function detail(){
		return "內容物";
	}
	
	public function getData(){
		return $this->_data;
	}
}