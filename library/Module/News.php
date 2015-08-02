<?php
class Module_News extends Module_ObjectDb{
	private $_data;
	
	public function __construct(){
		parent::__construct();
		$this->_layout = "news";
	}
	
	//主頁
	public function index(){
		$pageNo = $this->getVariables("pageNo");
		$pageNo = (strcmp($pageNo, "")) ? $pageNo : 1;
		$data = $this->selectDb("news", array("strWhe" => array("ACTIVE = 'Y'"), "strOrd" => array("UDATE DESC")));
		
		$this->_data = $this->getPageList($data, "/news/index/", $pageNo);
		$this->_data["PAGENO"] = $pageNo;
	}
	
	public function detail(){
		$id = $this->getVariables("id");
		$pageNo = $this->getVariables("pageNo");
		$this->_fieldAry[] = "ACTIVE";
		$this->_fieldAry[] = "WDATE";
		$this->_fieldAry[] = "FILE1";
		$this->_fieldAry[] = "FILE2";
		$this->_fieldAry[] = "FILE1_PS";
		$this->_fieldAry[] = "FILE2_PS";
		$this->_data = $this->getRowData("news", array("strWhe" => array("ID = '" . $id . "'")), $this->_fieldAry);
		$this->_data -> BACKURL = "/news/index/pageNo/" . $pageNo . "/";
	}
	
	public function getData(){
		return $this->_data;
	}
}