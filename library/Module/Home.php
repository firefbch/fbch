<?php
class Module_Home extends Module_ObjectDb{
	private $_data;
	
	public function __construct(){
		parent::__construct();
	}
	
	//主頁
	public function index(){
		$this->_layout = "index";
		$this->_data["NEWS"] = $this->selectDb("news", array("strWhe" => array("ACTIVE = 'Y'"), "strOrd" => array("UDATE DESC"), "strLim" => "1,0"));
		
		$aboutAry = $this->selectDb("aboutus", array("strWhe" => array("ACTIVE = 'Y'", "COM_ID = '0'")));
		$data = array();
		foreach ($aboutAry as $val){
			$this->_data["ABOUT"] = $val;
		}
	}
	
	public function getData(){
		return $this->_data;
	}
	
}