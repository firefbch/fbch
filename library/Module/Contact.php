<?php
class Module_Contact extends Module_ObjectDb{
	public function __construct(){
		parent::__construct();
		$this->_layout = "news";
	}
	
	public function index(){
		
	}
	
	public function modify(){
		if ($_POST["action"] == "modify"){
			$cond = array();
			$cond["NAME"] = $_POST["guest_name"];
			$cond["SEX"] = $_POST["guest_sex"];
			$cond["TEL"] = $_POST["guest_tel"];
			$cond["ADDRESS"] = $_POST["guest_addr"];
			$cond["EMAIL"] = $_POST["guest_email"];
			$cond["MEMOIRS"] = $_POST["memoirs"];
			$cond["FDATE"] = date("YmdHis");
			$cond["UDATE"] = date("YmdHis");
			
			$this->insertDb("contact", $cond);
			$this->reDirect("我們已收到您的留言,我們會盡快處理的", "/");
		}
	}
}
?>