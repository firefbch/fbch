<?php
class Module_Calendar extends Module_ObjectDb{
	private $_data;
	
	public function __construct(){
		parent::__construct();
	}
	
	//主頁
	public function index(){
		
	}
	
	public function getData(){
		return $this->_data;
	}
	
}