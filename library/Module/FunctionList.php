<?php
class Module_FunctionList{
	private $_array;
	
	public function __construct(){
		$this->fdata();
	}
	
	private function fdata(){
		$this->_array = array("home", "news");
	}
	
	public function chk($name="home"){
		return in_array($name, $this->_array);
	}
}