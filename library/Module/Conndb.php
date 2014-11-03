<?php 
/**
 * 2014-01-21
 * 驗證連線
 * 
 * @package 資料庫連線管理
 * @author カゲオ
 * @version 1.0.0
 */
class Module_Conndb{
	private $_db, $_params, $_error, $_sqls;
	
	public function __construct(){
		
	}
	
	/**
	 * 連線資料庫
	 * @param Array $db			//連線資料
	 * @param string $sqls		//資料庫類型
	 * @return boolean			//回傳連線是否成功
	 */
	public function setDbLink($db, $sqls = "Pdo_Mysql"){
		$this -> _params = $db -> _conf;
		$this -> _sqls = $sqls;
		if ($this -> runConnDb()){
			$this -> _error = "連線正常";
			return true;
		}else{
			//$this -> _error = "無法連線到資料庫!";
			return false;
		}
	}
	
	/**
	 * 執行連線測試
	 * @return boolean		//回傳是否連線成功
	 */
	private function runConnDb(){
		if ($this -> checkLinkDb()){
			$this -> _db = new Zend_Db_Adapter_Pdo_Mysql($this -> _params);
			$this -> _error = "連線成功！";
			return true;
		}else{
			return false;
		}
	}
	
	/**
	 * 嘗試連線，產生錯誤訊息
	 * @return boolean		//是否連線
	 */
	private function checkLinkDb(){
		try {
			$dbck = Zend_Db::factory($this -> _sqls, $this -> _params);
			$dbck -> getConnection();
		}catch (Zend_Db_Adapter_Exception $e){
			if ($e -> getcode() == "1044"){
				$this -> _error = "沒有權限可以連線到資料庫！（code: " . $e -> getcode() . "）";
				return false;
			} else if ($e -> getcode() == "1049"){
				$this -> _error = "資料庫尚未建立！（code: " . $e -> getcode() . "）";
				return false;
			}
		}
		return true;
	}
	
	/**
	 * 回傳資料庫的物件
	 * @return Zend_Db_Adapter_Pdo_Mysql
	 */
	public function getDbConn(){
		return $this -> _db;
	}
	
	/**
	 * 取得錯誤訊息
	 * @return string
	 */
	public function getMesg(){
		return $this -> _error;
	}
}
?>