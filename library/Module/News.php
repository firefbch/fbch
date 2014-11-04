<?php
class Module_News extends Module_ObjectDb{
	private $_data;
	
	public function __construct(){
		parent::__construct();
	}
	
	//主頁
	public function index(){
		$this->_data = $this->selectDb("news", array("strWhe" => array("ACTIVE = 'Y'")));
		return $this->_data;
	}
	
	public function detail(){
		return "內容物";
	}
}