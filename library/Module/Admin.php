<?php
class Module_Admin extends Module_ObjectDb{
	private $_data, $_upLoadDir = "/public";
	
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
			$data = $this->selectDb("news", array("strOrd" => array("UDATE DESC")));
			
			$this->_data = $this->getPageList($data, "/admin/news/page_name/news_list/", $pageNo);
			$this->_data["PAGENO"] = $pageNo;
		}
		else if ($page_name == "news_modify"){
			$id = $this->getVariables("id");
			$pageNo = $this->getVariables("pageNo");
			$this->_fieldAry[] = "WDATE";
			$this->_fieldAry[] = "ACTIVE";
			$this->_fieldAry[] = "FILE1";
			$this->_fieldAry[] = "FILE2";
			$this->_fieldAry[] = "FILE1_PS";
			$this->_fieldAry[] = "FILE2_PS";
			$this->_data = $this->getRowData("news", array("strWhe" => array("ID = '" . $id . "'")), $this->_fieldAry);
			$this->_data -> BACKURL = "/admin/news/page_name/news_list/pageNo/" . $pageNo . "/";
		}
		else if ($page_name == "update"){
			$upload_dir = $this->_upLoadDir . "/news";
			if (!file_exists("." . $upload_dir)){
				mkdir("." . $upload_dir, 755);
			}
			$errmsg = "";
			$id = $this->getVariables("id");
			$pageNo = $this->getVariables("pageNo");
			$cond = array();
			$file_fileds = array("FILE1", "FILE2");
			
			$data = $this->selectDb("news", array("strWhe" => array("ID = '" . $id . "'")));
			while (list($key, $val) = each($file_fileds)) {
				$field_name = $_FILES[strtolower($val)];
				if (strcmp($field_name["name"], "")) {
					$old_file_path = $upload_dir . "/" . $data[0][strtoupper($val)];
					$new_file_name = md5($field_name["name"] . "-" . microtime()) . strrchr($field_name["name"], ".");
					$upload_file_path = $new_file_name;
					$errmsg = $this->uploadFile($field_name, $field_name["name"], $field_name["size"], $upload_dir, $upload_file_path, $old_file_path, "\.+[jpg|jpeg|gif|png]+$");
					if (!strcmp($errmsg, "")) {
						$cond[strtoupper($val)] = $new_file_name;
					}
				}
			}
			
			if ($this->getRows("news", array("strWhe" => array("ID = '" . $id . "'")))){
				$cond["TITLE"] = addslashes($_POST["title"]);
				$cond["WDATE"] = addslashes($_POST["wdate"]);
				$cond["MEMOIRS"] = addslashes($_POST["memoirs"]);
				$cond["ACTIVE"] = $_POST["active"];
				$cond["UDATE"] = date("YmdHis");
				
				$this->updateDb("news", $cond, array("ID = '" . $id . "'"));
				$mesg = "完成更新" . ((strcmp($errmsg, "")) ? ",但" . $errmsg : ".");
				$this->reDirect($mesg, "/admin/news/page_name/news_modify/pageNo/" . $pageNo . "/id/" . $id . "/");
			}else{
				$cond["TITLE"] = addslashes($_POST["title"]);
				$cond["WDATE"] = addslashes($_POST["wdate"]);
				$cond["MEMOIRS"] = addslashes($_POST["memoirs"]);
				$cond["ACTIVE"] = $_POST["active"];
				$cond["FDATE"] = date("YmdHis");
				$cond["UDATE"] = date("YmdHis");
				
				$this->insertDb("news", $cond);
				$mesg = "完成新增";
				$this->reDirect($mesg, "/admin/news/page_name/news_list/");
			}
		}
		else if ($page_name == "delimg"){
			$id = $this->getVariables("id");
			$field_name = $this->getVariables("field_name");
			$upload_dir = $this->_upLoadDir . "/news";
			$data = $this->selectDb("news", array("strWhe" => array("ID = '" . $id . "'")));
			$mesg = "刪除失敗";
			
			$img_path = $upload_dir . "/" . $data[0][strtoupper($field_name)];
			if (file_exists("." . $img_path)) {
				@unlink("." . $img_path);
				$this->updateDb("news", array("FILE1" => ""), array("ID = '" . $id . "'"));
				$mesg = "檔案已成功刪除";
			}else{
				$mesg = "檔案不存在" . $img_path;
			}
			$this->reDirect($mesg, "/admin/news/page_name/news_list/");
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