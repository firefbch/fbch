<?php
class Module_About extends Module_ObjectDb{
	private $_data;
	
	public function __construct(){
		parent::__construct();
	}
	
	//主頁
	public function index(){
		$com_id = $this->getVariables("com_id");
		$com_id = (strcmp($com_id, "")) ? $com_id : 1;
		$item = array();
		
		$aboutAry = $this->selectDb("aboutus", array("strWhe" => array("ACTIVE = 'Y'")));
		$data = array();
		foreach ($aboutAry as $val){
			$item[] = "<a href=\"/about/com_id/" . $val["COM_ID"] . "/\">" . $val["TITLE"] . "</a>";
			$data[$val["COM_ID"]] = $val;
		}
		$this->_data = $data[$com_id];
		$this->_data["item"] = $item;
	}
		
	public function getData(){
		return $this->_data;
	}
}