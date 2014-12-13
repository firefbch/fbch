<?php
class Module_Home extends Module_ObjectDb{
	private $_data;
	
	public function __construct(){
		parent::__construct();
	}
	
	//主頁
	public function index(){
		$this->_layout = "index";
		$this->_data = $this->selectDb("news", array("strWhe" => array("ACTIVE = 'Y'")));
	}
	
	public function getData(){
		return $this->_data;
	}
	
}