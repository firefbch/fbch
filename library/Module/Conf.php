<?php 
class Module_Conf{
	private $_options;
	public $_conf;
	
	public function __construct($tbName = "duty_firefighters"){
		$this -> _options = array(
				Zend_Db::ALLOW_SERIALIZATION => false
				);
		date_default_timezone_set('Asia/Taipei');
		$this -> setConfig($tbName);
		return $this -> _conf;
	}
	
	private function setConfig($tbName){
		$this -> _conf = array(
				'host' 			=> "localhost",
				'username'		=> "root",
				'password'		=> "123456",
				'dbname'		=> $tbName,
				'charset'		=> 'utf8',
				'options'		=> $this -> _options
		);
	}
}
?>