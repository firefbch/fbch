<?php
class Module_Album extends Module_ObjectDb{
	private $_data, $_tbName, $_item, $_file_fileds;
	
	public function __construct(){
		parent::__construct();
		$this->_tbName = "album";
		$this->_item = array("<a href='/album/'>活動剪影</a>", "<a href='/exp/'>經驗分享</a>");
		$this->_file_fileds = array("IMAGE", "FILE1", "FILE2", "FILE3", "FILE4", "FILE5", "FILE6", "FILE7", "FILE8", "FILE9", "FILE10");
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
		$this->_data["parent_id"] = $parent_id;
	}
	
	public function detail(){
		$id = $this->getVariables("id");
		$parent_id = $this->getVariables("parent_id");
		$parent_id = (strcmp($parent_id, "")) ? $parent_id : 0;
		$pageNo = $this->getVariables("pageNo");
		$this->_fieldAry[] = "WDATE";
		$this->_fieldAry[] = "MEMOIRS";
		$this->_fieldAry[] = "ACTIVE";
		
		foreach ($this->_file_fileds as $val){
			$this->_fieldAry[] = $val;
			$this->_fieldAry[] = $val . "_PS";
		}
		$this->_data = $this->getRowData($this->_tbName, array("strWhe" => array("ID = '" . $id . "'")), $this->_fieldAry);
		$this->_data -> BACKURL = "/" . $this->_tbName . "/mlist/parent_id/$parent_id/pageNo/" . $pageNo . "/";
		$this->_data -> item = $this->_item;
		$this->_data->file_fileds = $this->_file_fileds;
	}
	
	public function getData(){
		return $this->_data;
	}
}