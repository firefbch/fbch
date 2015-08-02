<?php
class Module_Exp extends Module_ObjectDb{
	private $_data, $_tbName = "exp", $_item;
	
	public function __construct(){
		parent::__construct();
		$this->_item = array("<a href='/album/'>活動剪影</a>", "<a href='/exp/'>經驗分享</a>");
	}
	
	//主頁
	public function index(){
		$pageNo = $this->getVariables("pageNo");
		$pageNo = (strcmp($pageNo, "")) ? $pageNo : 1;
		$data = $this->selectDb($this->_tbName, array("strWhe" => array("ACTIVE = 'Y'", "PARENT_ID = '1'"), "strOrd" => array("UDATE DESC")));
		
		$this->_data = $this->getPageList($data, "/" . $this->_tbName . "/index/", $pageNo);
		$this->_data["PAGENO"] = $pageNo;
		$this->_data["item"] = $this->_item;
	}
	
	public function detail(){
		$id = $this->getVariables("id");
		$pageNo = $this->getVariables("pageNo");
		$this->_fieldAry[] = "ACTIVE";
		$this->_fieldAry[] = "NAME";
		$this->_fieldAry[] = "DATE";
		$this->_data = $this->getRowData($this->_tbName, array("strWhe" => array("ID = '" . $id . "'", "ACTIVE = 'Y'")), $this->_fieldAry);
		$this->_data->sdata = $this->selectDb($this->_tbName, array("strWhe" => array("PARENT_ID = '" . $id . "'", "ACTIVE = 'Y'"), "strOrd" => array("PARENT_ID ASC", "UDATE DESC")));
		$this->_data -> BACKURL = "/" . $this->_tbName . "/index/pageNo/" . $pageNo . "/";
		$this->_data->item = $this->_item;
	}
	
	public function getData(){
		return $this->_data;
	}
	
	public function newpost(){
		$this->_data["item"] = $this->_item;
	}
	public function rpost(){
		$id = $this->getVariables("id");
		$this->_data = $this->selectDb($this->_tbName, array("strWhe" => array("ID = '" . $id . "'")));
		$this->_data["item"] = $this->_item;
	}
	
	public function newtitle(){
		if ($_POST["action"] == "update"){
			$cond = array();
			$cond["PARENT_ID"] = 1;
			$cond["TITLE"] = $_POST["title"];
			$cond["NAME"] = $_POST["name"];
			$cond["DATE"] = date("YmdHis");
			$cond["MEMOIRS"] = $_POST["memoirs"];
			$cond["LASTRTIME"] = date("YmdHis");
			$cond["LASTRNAME"] = $_POST["name"];
			$cond["FDATE"] = date("YmdHis");
			$cond["UDATE"] = date("YmdHis");
			
			$this->insertDb($this->_tbName, $cond);
			$this->reDirect("已完成發表文章,待管理者確認後才會顯示!", "/" . $this->_tbName . "/");
		}
	}
	
	public function rtitle(){
		if ($_POST["action"] == "update"){
			$cond = array();
			$cond["PARENT_ID"] = $_POST["id"];
			$cond["TITLE"] = $_POST["title"];
			$cond["NAME"] = $_POST["name"];
			$cond["DATE"] = date("YmdHis");
			$cond["MEMOIRS"] = $_POST["memoirs"];
			$cond["FDATE"] = date("YmdHis");
			$cond["UDATE"] = date("YmdHis");
				
			$this->insertDb($this->_tbName, $cond);
			
			//更新主題
			$sond = array();
			$sond["LASTRTIME"] = date("YmdHis");
			$sond["LASTRNAME"] = $_POST["name"];
			$sond["COUNTS"] = new Zend_Db_Expr("COUNTS+1");
			
			$this->updateDb($this->_tbName, $sond, array("ID = '" . $_POST["id"] . "'"));
			$this->reDirect("已完成發表文章,待管理者確認後才會顯示!", "/" . $this->_tbName . "/");
		}
	}
}