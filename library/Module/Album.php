<?php
class Module_Album extends Module_ObjectDb{
	private $_data, $_tbName, $_item;
	
	public function __construct(){
		parent::__construct();
		$this->_tbName = "album";
		$this->_item = array("<a href='/album/'>活動剪影</a>", "<a href='/exp/'>經驗分享</a>");
	}
	
	//主頁
	public function index(){
		$pageNo = $this->getVariables("pageNo");
		$pageNo = (strcmp($pageNo, "")) ? $pageNo : 1;
		$parent_id = $this->getVariables("parent_id");
		$parent_id = (strcmp($parent_id, "")) ? $parent_id : 0;
		$data = $this->selectDb($this->_tbName, array("strWhe" => array("ACTIVE = 'Y'", "PARENT_ID = '$parent_id'"), "strOrd" => array("UDATE DESC")));
		
		$this->_data = $this->getPageList($data, "/" . $this->_tbName . "/index/", $pageNo);
		$this->_data["PAGENO"] = $pageNo;
		$this->_data["item"] = $this->_item;
	}
	
	public function mlist(){
		$pageNo = $this->getVariables("pageNo");
		$pageNo = (strcmp($pageNo, "")) ? $pageNo : 1;
		$parent_id = $this->getVariables("parent_id");
		$parent_id = (strcmp($parent_id, "")) ? $parent_id : 0;
		$data = $this->selectDb($this->_tbName, array("strWhe" => array("ACTIVE = 'Y'", "PARENT_ID = '$parent_id'"), "strOrd" => array("UDATE DESC")));
	
		$this->_data = $this->getPageList($data, "/" . $this->_tbName . "/mlist/", $pageNo);
		$this->_data["PAGENO"] = $pageNo;
		$this->_data["item"] = $this->_item;
	}
	
	public function detail(){
		$id = $this->getVariables("id");
		$pageNo = $this->getVariables("pageNo");
		$this->_fieldAry[] = "ACTIVE";
		$this->_fieldAry[] = "FILE1";
		$this->_fieldAry[] = "FILE2";
		$this->_fieldAry[] = "FILE1_PS";
		$this->_fieldAry[] = "FILE2_PS";
		$this->_data = $this->getRowData($this->_tbName, array("strWhe" => array("ID = '" . $id . "'")), $this->_fieldAry);
		$this->_data -> BACKURL = "/" . $this->_tbName . "/index/pageNo/" . $pageNo . "/";
	}
	
	public function getData(){
		return $this->_data;
	}
}