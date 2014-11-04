<?php 
class Module_ObjectDb{
	private $_db;
	public $_layout;
	
	public function __construct(){
		$this -> _db = Module_Managedb::getInstance();
		$this -> _db -> connMysqlDb(new Module_Conf());
		$this->_layout = "index";
	}
	
	//查詢
	public function selectDb($tbName, $array){
		$mnAry = $this -> _db -> setSelectQuery($tbName, $array) -> getData();
		return $mnAry;
	}
	
	//更新資料
	public function updateDb($tbName, $data, $strWhe){
		return $this -> _db -> updateDb($tbName, $data, $strWhe);
	}
	
	//新增資料
	public function insertDb($tbName, $data){
		$this -> _db -> insertDb($tbName, $data);
		return $this -> _db -> getLastInsertId();
	}
	
	//刪除
	public function deleteDb($tbName, $strWhe){
		return $this -> _db -> deleteDb($tbName, $strWhe);
	}
	
	/**
	 * 2014-05-13
	 * 建立單筆資料的物件
	 * @param String $tbName		資料庫名稱
	 * @param array $array			查詢條件
	 * @param array $fieldAry		所使用的欄位名稱
	 * 
	 */
	public function getRowData($tbName, $array, $fieldAry){
		$attr = new stdClass();
		//取得資料
		$data = $this -> selectDb($tbName, $array);
		
		//依不同類別分別執行不一樣的做法
		foreach ($fieldAry as $val){
			$attr -> $val = $data[0][$val];		
		}
		return $attr;
	}
	
	//取得新增ID
	public function getLastId(){
		return $this -> _db -> getLastInsertId();
	}

	//設定分頁
	public function getPageList($array, $link, $pageNo, $limit = 10){
		return $this -> _db -> pager_list($array, $link, $pageNo, $limit);
	}
	
}
?>