<?php
class Module_Business extends Module_ObjectDb{
	private $_data;
	
	public function __construct(){
		parent::__construct();
	}
	
	//主頁
	public function index(){
		$com_id = $this->getVariables("com_id");
		$com_id = (strcmp($com_id, "")) ? $com_id : 1;
		$item = array();
		
		$aboutAry = $this->selectDb("business", array("strWhe" => array("ACTIVE = 'Y'")));
		$data = array();
		foreach ($aboutAry as $val){
			$item[] = "<a href=\"/business/com_id/" . $val["COM_ID"] . "/\">" . $val["TITLE"] . "</a>";
			$data[$val["COM_ID"]] = $val;
		}
		$this->_data = $data[$com_id];
		$this->_data["item"] = $item;
	}
		
	public function getData(){
		return $this->_data;
	}
}