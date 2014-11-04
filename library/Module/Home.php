<?php
class Module_Home extends Module_ObjectDb{
	private $_data;
	
	public function __construct(){
		parent::__construct();
	}
	
	//主頁
	public function index(){
		$this->_data = $this->selectDb("news", array("strWhe" => array("ACTIVE = 'Y'")));
		return $this->_data;
	}
	
}