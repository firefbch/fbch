<?php
class Module_MemberData{
	static private $_instance = NULL, $_USERDATA, $_db;
	
	private function  __construct() {}
	private function  __clone(){}
	
	static public function getInstance() {
		if (is_null(self::$_instance) || !isset(self::$_instance)) {
			self::$_instance = new self();
			self::$_db = new Module_ObjectDb();
		}
		return self::$_instance;
	}
	
	public function userLogin(){
		$strWhe = array("USER_ID = '" . $_POST["login_id"] . "'", "PASSWD = '" . $_POST["login_passwd"] . "'");
		if (self::$_db->getRows("user", array("strWhe" => $strWhe))){
			self::$_db->updateDb("user", array("COUNTS" => new Zend_Db_Expr("COUNTS + 1")), $strWhe);
			self::$_USERDATA = self::$_db->selectDb("user", array("strWhe" => $strWhe));
			$_SESSION["USER_PASS"] = true;
			$_SESSION["USER_ID"] = self::$_USERDATA[0]["USER_ID"];
			$_SESSION["USER_NAME"] = self::$_USERDATA[0]["USER_NAME"];
			$_SESSION["COUNTS"] = self::$_USERDATA[0]["COUNTS"];
			return true;
		}else{
			return false;
		}
	}
	
	public function chkLogin(){
		return $_SESSION["USER_PASS"];
	}
}