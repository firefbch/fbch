<?php
class Module_Link extends Module_ObjectDb{
	private $_data;
	
	public function __construct(){
		parent::__construct();
	}
	
	//主頁
	public function index(){
		$pageNo = $this->getVariables("pageNo");
		$pageNo = (strcmp($pageNo, "")) ? $pageNo : 1;
		$data = $this->selectDb("link", array("strWhe" => array("ACTIVE = 'Y'"), "strOrd" => array("UDATE DESC")));
		return $data;
	}
}