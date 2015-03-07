<?php
ini_set("display_errors", "on");
class Module_ManageCenter{
	private $_url_pathAry, $_chkf;
	public $siteDatas, $view, $assignTemplate = "Home", $assignPage = "index", $_controllCss = array("default"), $_controllJs = array("jquery-1.8.3", "jquery-ui-1.9.2.custom", "chineseDate", "lib", "jquery.ui.datepicker", "jquery.ad-gallery");
	
	public function __construct(){
		$this->_url_pathAry = explode("/", $_SERVER["REQUEST_URI"]);
		$this->_chkf = new Module_FunctionList();
		$this->siteDatas = new Zend_Session_Namespace("SITE_DATAS");
		$this->siteDatas -> USER_LOGIN_DATA = (is_object($siteDatas -> USER_LOGIN_DATA)) ? $siteDatas -> USER_LOGIN_DATA : new stdClass();
		$this->siteDatas -> STORE_DB = (strcmp($siteDatas -> STORE_DB, "")) ? $siteDatas -> STORE_DB : null;
		$this->siteDatas -> LANG = (isset($siteDatas -> LANG)) ? $siteDatas -> LANG : "chinese";
		$this->siteDatas -> CLANG = (isset($siteDatas -> CLANG)) ? $siteDatas -> CLANG : "utf8";
		$this->siteDatas -> SITE_CONFIG = $this->_chkf->getMyWebSite();
	}
	
	public function init(){
		header("Content-type: text/html; charset=utf-8");
		//讀取連結資料
		$link = new Module_Link();
		$linkData = $link->index();
		
		if ($this->_chkf->chk($this->_url_pathAry[1]) || $this->_url_pathAry[1] == "admin"){
			
			$this->assignTemplate = (strcmp($this->_url_pathAry[1], "")) ? $this->_url_pathAry[1] : "home";
			$myClass = "Module_" . $this->assignTemplate;
			$view = new $myClass();
			//後台檢查登入狀態
			if ($this->_url_pathAry[1] == "admin" && $this->_url_pathAry[2] != "index" && strcmp($this->_url_pathAry[2], "") && $_POST["action"] != "login"){
				$view->chklogin();
			}
			//檢查物件中是否有方法可用
			if (method_exists($view, $this->_url_pathAry[2])) $this->assignPage = $this->_url_pathAry[2];
			//var_dump(method_exists($obj, $this->_url_pathAry[2]));
			$myFunc = $this->assignPage;
			$view->_myPage = $myFunc;
			//echo $myFunc . "///" . $this->_url_pathAry[2] . "///" . $myClass;
			$view->$myFunc();
			$template_dir = LAYOUT_PATH . DS . $view->_layout . ".phtml";
			if (file_exists($template_dir)){
				require_once $template_dir;
			}else{
				require_once PHP_INCS . DS . "html" . DS . "layout" . DS . "index.phtml";;
			}
		}else{
			//$this->assignTemplate = (strcmp($this->_url_pathAry[1], "")) ? $this->_url_pathAry[1] : "home";
			$myClass = "Module_" . $this->assignTemplate;
			$view = new $myClass();
			//檢查物件中是否有方法可用
			$myFunc = $this->assignPage;
			$view->$myFunc();
			$template_dir = LAYOUT_PATH . DS . $view->_layout . ".phtml";
			require_once $template_dir;
		}
	}
}