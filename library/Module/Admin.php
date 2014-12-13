<?php
class Module_Admin extends Module_ObjectDb{
	private $_data;
	
	public function __construct(){
		parent::__construct();
		$this->_layout = "admin";
	}
	
	//主頁
	public function index(){
		if ($_SESSION["ADMIN_LOGIN_PASS"]){
			$this->_myPage = "index";
		}else{
			$this->_myPage = "login";
		}
	}
	
	public function news(){
		$page_name = $this->getVariables("page_name");
		$page_name = (strcmp($page_name, "")) ? $page_name : "news_list";
		$this->_myPage = $page_name;
		
		if ($page_name == "news_list"){
			$pageNo = $this->getVariables("pageNo");
			$pageNo = (strcmp($pageNo, "")) ? $pageNo : 1;
			$data = $this->selectDb("news", array("strWhe" => array("ACTIVE = 'Y'")));
			
			$this->_data = $this->getPageList($data, "/admin/news/page_name/news_list/", $pageNo);
		}
		else if ($page_name == "news_modify"){
			
		}
	}
	
	public function getData(){
		return $this->_data;
	}
	
	public function login(){
		$login_id = addslashes($_POST["login_id"]);
		$login_passwd = addslashes($_POST["login_passwd"]);
		$mesg = "帳號密碼錯誤";
		if ($this->getRows($tbName, array("strWhe" => array("ACCOUNT = '" . $login_id . "'", "PASSWD = '" . $login_passwd . "'"))) && strcmp($login_id, "")){
			$_SESSION["ADMIN_LOGIN_ID"] = $login_id;
			$_SESSION["ADMIN_LOGIN_PASS"] = true;
			$mesg = "登入成功";
		}else{
			$_SESSION["ADMIN_LOGIN_ID"] = "";
			$_SESSION["ADMIN_LOGIN_PASS"] = false;
		}
		$url = "/admin/";
		$this->reDirect($mesg, $url);
	}
	
	public function chklogin(){
		if (!$_SESSION["ADMIN_LOGIN_PASS"]){
			$this->reDirect("請先登入", "/admin/");
		}
	}
	
	public function logout(){
		$_SESSION["ADMIN_LOGIN_ID"] = "";
		$_SESSION["ADMIN_LOGIN_PASS"] = false;
		$mesg = "已成功登出";
		$url = "/admin/";
		
		$this->reDirect($mesg, $url);
	}
}