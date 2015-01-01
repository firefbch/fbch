<?php
class Module_Member extends Module_ObjectDb{
	private $_data, $_memberData;

	public function __construct(){
		parent::__construct();
		$this->_memberData = Module_MemberData::getInstance();
	}

	//主頁
	public function index(){
		if ($this->_memberData->chkLogin()){
			$this->_data["chkLogin"] = $this->_memberData->chkLogin();
			$this->_data["logMesg"] = "歡迎 " . $_SESSION["USER_NAME"] . " 第 " . $_SESSION["COUNTS"] . " 次光臨本網站.";
		}else{
			$this->login();
		}
	}
	
	public function login(){
		if ($_POST["action"] == "login"){
			$this->_memberData->userLogin();
			if ($this->_memberData->chkLogin()){
				$this->reDirect("登入成功", "/member/");
			}else{
				$this->reDirect("登入失敗,請再次確認您的帳號跟密碼", "/member/login/");
			}
		}
		
		
	}
	
	public function logout(){
		$_SESSION["USER_PASS"] = false;
		$_SESSION["USER_ID"] = "";
		$_SESSION["USER_NAME"] = "";
		$_SESSION["COUNTS"] = "";
		$this->reDirect("您已成功登出", "/");
	}
	
	public function join(){
		$this->reDirect("", "/member/joindata/");
	}
	
	public function joindata(){
		if ($_POST["action"] == "join"){
			if ($_POST["user_passwd"] != $_POST["user_passwd2"])
				$this->reDirect("您的二組密碼不一致,請重新輸入", "/member/joindata/");
			$cond = array();
			$cond["USER_ID"] = $_POST["user_id"];
			$cond["PASSWD"] = $_POST["user_passwd"];
			$cond["USER_NAME"] = $_POST["user_name"];
			$cond["USER_SEX"] = $_POST["user_sex"];
			$cond["USER_TEL"] = $_POST["user_tel"];
			$cond["ACTIVE"] = "Y";
			$cond["FDATE"] = date("YmdHis");
			$cond["UDATE"] = date("YmdHis");
			
			$this->insertDb("user", $cond);
			$this->reDirect("會員資料已送出", "/");
		}
	}
	
	public function delete(){
		
	}

	public function modify(){
		if ($_POST["action"] == "update"){
			$cond = array();
			$cond["PASSWD"] = $_POST["user_passwd"];
			$cond["USER_NAME"] = $_POST["user_name"];
			$cond["USER_SEX"] = $_POST["user_sex"];
			$cond["USER_TEL"] = $_POST["user_tel"];
			$cond["UDATE"] = date("YmdHis");
				
			$this->insertDb("user", $cond);
			$this->reDirect("會員資料已更新", "/member/");
		}
		
		$aaa = $this->selectDb("user", array("strWhe" => array("USER_ID = '" . $_SESSION["USER_ID"] . "'")));
		$this->_data = $aaa[0];
	}

	public function getData(){
		$array = array(
				"login"		=> array("title" => "會員登入", "link" => "/member/login/"),
				"modify"	=> array("title" => "會員資料編輯", "link" => "/member/modify/"),
				"join"		=> array("title" => "加入會員", "link" => "/member/join/"),
				"logout"	=> array("title" => "登出", "link" => "/member/logout/"),
				"delete"	=> array("title" => "退會申請", "link" => "/member/delete/")
		);
		
		foreach ($array as $key => $val){
			if ((in_array($key, array("modify", "logout", "delete")) && !$this->_memberData->chkLogin()) || (in_array($key, array("login", "join")) && $this->_memberData->chkLogin())){
				
			}else{
				$this->_data["item"][] = "<a href=" . $val["link"] . ">" . $val["title"] . "</a>";
			}
		}
		return $this->_data;
	}
}
?>