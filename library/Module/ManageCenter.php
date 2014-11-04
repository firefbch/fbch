<?php
ini_set("display_errors", "on");
class Module_ManageCenter{
	private $_url_pathAry, $_chkf;
	public $view, $assignTemplate = "home", $assignPage = "index", $_controllCss = array("default"), $_controllJs = array("jquery-1.8.3", "chineseDate");
	
	public function __construct(){
		$this->_url_pathAry = explode("/", $_SERVER["REQUEST_URI"]);
		$this->_chkf = new Module_FunctionList();
	}
	
	public function init(){
		header("Content-type: text/html; charset=utf-8");
		
		if ($this->_chkf->chk($this->_url_pathAry[1]) || !strcmp($this->_url_pathAry[1], "")){
			$this->assignTemplate = $this->_url_pathAry[1];
			$myClass = "Module_" . $this->assignTemplate;
			$obj = new $myClass();
			//檢查物件中是否有方法可用
			if (method_exists($obj, $this->_url_pathAry[2])) $this->assignPage = $this->_url_pathAry[2];
			//var_dump(method_exists($obj, $this->_url_pathAry[2]));
			$myFunc = $this->assignPage;
			$view = $obj->$myFunc();
			$template_dir = PHP_INCS . DS . "html" . DS . "layout" . DS . $view->_layout . ".phtml";
			if (file_exists($template_dir)){
				require_once $template_dir;
			}else{
				require_once PHP_INCS . DS . "html" . DS . "layout" . DS . "index.phtml";;
			}
		}else{
			$myClass = "Module_" . $this->assignTemplate;
			$obj = new $myClass();
			//檢查物件中是否有方法可用
			$myFunc = $this->assignPage;
			$view = $obj->$myFunc();
			$template_dir = PHP_INCS . DS . "html" . DS . "layout" . DS . $view->_layout . ".phtml";
			require_once PHP_INCS . DS . "html" . DS . "layout" . DS . "index.phtml";;
		}
	}
}