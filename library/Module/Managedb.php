<?php 
class Module_Managedb{
	static private $_instance = NULL, $_error = null, $_db, $_conndb, $_data;
	private $_linkn;	
	
	private function  __construct() {}
	private function  __clone(){}
	
	static public function getInstance() {
		if (is_null(self::$_instance) || !isset(self::$_instance)) {
			self::$_instance = new self();
			self::$_db = array();
			self::$_conndb = new Module_Conndb();
		}
		return self::$_instance;
	}
	
	/**
	 * 連結資料庫
	 * @param array $db			//資料庫連線資料
	 * @param string $name		//連線命名
	 * @return boolean			//是否成功
	 */
	public function connMysqlDb($db, $name = "default"){
		$this -> _linkn = $name;
		if (self::$_conndb -> setDbLink($db, "Pdo_Mysql")){
			self::$_db[$this -> _linkn] = self::$_conndb -> getDbConn();
			return true;
		}else{
			//self::$_error = "設定值輸入失敗!";
			self::$_error = self::$_conndb -> getMesg();
			return false;
		}
		
	}
	
	/**
	 * 資料查詢
	 * @param String $tbName
	 * @param 查詢陣列 $array
	 * @return Module_Managedb
	 */
	public function setSelectQuery($tbName, $array){
		//建立物件
		$select = self::$_db[$this -> _linkn] -> select();
	
		if (is_array($tbName)){
			$select -> from($tbName[0], $tbName[1]);
		}else{
			if (strcmp($tbName, "")){
				$select -> from($tbName);
			}else{
				$this -> error["sql_dbname"] = "未輸入資料表名稱";
			}
		}
		//依照$array中的key來決定使用的功能
		foreach ($array as $key => $val){
			switch ($key){
				case "strWhe":
					if (is_array($val)){
						foreach ($val as $value){
							$select -> where($value);
						}
					}else{
						if (strcmp($val, "")) $select -> where($val);
					}
					break;
				case "strGrp":
					if (is_array($val)){
						foreach ($val as $value){
							$select -> group($value);
						}
					}else{
						if (strcmp($val, "")) $select -> group($val);
					}
					break;
				case "strOrd":
					if (is_array($val)){
						$select -> order($val);
					}else{
						$this -> error["sql_order"] = "strOrd 必須為陣列";
					}
					break;
				case "strLim":
					if (strcmp($val, "") && preg_match("/,/", $val)){
						$select -> limit($val);
					}else{
						$this -> error["sql_limit"] = "strLim的格式為：xx, xx";
					}
					break;
			}
		}
	
		self::$_data = self::$_db[$this -> _linkn] -> query($select) -> fetchAll();
		$select -> reset();
		return self::$_instance;
	}
	
	/**
	 * 新增資料
	 * @param String $tbName
	 * @param array $data
	 */
	public function insertDb($tbName, $data){
		self::$_db[$this -> _linkn] -> insert($tbName, $data);
	}
	
	/**
	 * 更新資料
	 * @param String $tbName
	 * @param array $data
	 * @param array $strWhe
	 */
	public function updateDb($tbName, $data, $strWhe){
		self::$_db[$this -> _linkn] -> update($tbName, $data, $strWhe);
	}
	
	public function deleteDb($tbName, $strWhe){
		if (is_array($strWhe)){
			foreach ($strWhe as $del){
				self::$_db[$this -> _linkn] -> delete($tbName, $del);
			}
		}else{
			self::$_db[$this -> _linkn] -> delete($tbName, $strWhe);
		}
	}
	
	/**
	 * 
	 * @param unknown $array
	 * @param unknown $link
	 * @param unknown $pageNo
	 * @param number $limit
	 * @return multitype:string Ambigous <Zend_Paginator, Zend_Paginator> |boolean
	 */
	public function pager_list($array, $link, $pageNo, $limit = 10){
		if (is_array($array)){
			$paginator = Zend_Paginator::factory($array);
			$paginator -> setCurrentPageNumber($pageNo) -> setItemCountPerPage($limit);
			$pageList = $paginator -> getPages();
	
			$page_list = array();
			$page_list[] = "<a href='" . $this -> outPageNoPath($link) . "pageNo/" . $pageList -> first . "/'>第一頁</a>";
			$page_list[] = "<a href='" . $this -> outPageNoPath($link) . "pageNo/" . ((strcmp($pageList -> previous, "")) ? $pageList -> previous : $pageList -> first) . "/'>上一頁</a>";
			for ($x = $pageList -> firstPageInRange;$x <= $pageList -> lastPageInRange;$x++){
				if ($x == $pageNo){
					$page_list[] = "<a href='" . $this -> outPageNoPath($link) . "pageNo/" . $x . "/'><font color='red'>" . $x . "</font></a>";
				}else{
					$page_list[] = "<a href='" . $this -> outPageNoPath($link) . "pageNo/" . $x . "/'>" . $x . "</a>";
				}
			}
			$page_list[] = "<a href='" . $this -> outPageNoPath($link) . "pageNo/" . ((strcmp($pageList -> next, "")) ? $pageList -> next : $pageList -> last) . "/'>下一頁</a>";
			$page_list[] = "<a href='" . $this -> outPageNoPath($link) . "pageNo/" . $pageList -> last . "/'>最後一頁</a>";
			return array($paginator, implode(" | ", $page_list));
		}else{
			return false;
		}
	}
	
	/**
	 * 過濾掉連結中的pageNo的值
	 * @param String $path		連結
	 * @return string			回傳新連結
	 */
	public function outPageNoPath($path){
		$pathAry = explode("/", $path);
		foreach ($pathAry as $key => $val){
			if (preg_match("/pageNo/", $val)){
				unset($pathAry[$key]);
				unset($pathAry[($key+1)]);
				break;
			}
		}
		return implode("/", $pathAry);
	}
	
	public function getData(){
		return self::$_data;
	}
	
	public function getLastInsertId(){
		return self::$_db[$this -> _linkn] -> lastInsertId();
	}
	
	public function changeDbLink($name = "default"){
		$this -> _linkn = $name;
	}
	
	public function getDb(){
		return self::$_db[$this -> _linkn];
	}
	
	public function getErrors(){
		return self::$_error;
	}
}
?>