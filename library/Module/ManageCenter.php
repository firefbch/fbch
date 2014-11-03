<?php
class Module_ManageCenter{
	private $_url_pathAry, $_chkf;
	public $assignTemplate = "home", $assignPage = "index", $_controllCss = array("default"), $_controllJs = array("lib", "jquery-1.8.3", "chineseDate", "http://connect.facebook.net/zh_TW/all");
	
	public function __construct(){
		$this->_url_pathAry = explode("/", $_SERVER["REQUEST_URI"]);
		$this->_chkf = new Module_FunctionList();
	}
	
	public function init(){
		header("Content-type: text/html; charset=utf-8");
		
		if ($this->_chkf->chk($this->_url_pathAry[1])){
			require_once PHP_INCS . DS . 'apps' . DS . 'body' . DS . $this->_url_pathAry[1] . ".php";
		}else{
			require_once PHP_INCS . DS . 'apps' . DS . 'body' . DS . "home.php";
		}
	}
}