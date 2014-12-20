<?php 
class Module_ObjectDb{
	private $_db;
	public $_layout, $_myPage, $_chkf, $_fieldAry;
	
	public function __construct(){
		$this -> _db = Module_Managedb::getInstance();
		$this -> _db -> connMysqlDb(new Module_Conf());
		$this->_layout = "index";
		$this->_myPage = "index";
		$this->_fieldAry = array("ID", "TITLE", "MEMOIRS", "FDATE", "UDATE");
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
	
	//返回筆數
	public function getRows($tbName, $array){
		$data = $this->selectDb(array($tbName, "count(*)"), $array);
		return $data[0]["count(*)"];
	}
	
	/**
	 * 2014-05-13
	 * 建立單筆資料的物件
	 * @param String $tbName		資料庫名稱
	 * @param array $array			查詢條件
	 * @param array $fieldAry		所使用的欄位名稱
	 * 
	 */
	public function getRowData($tbName, $array, $fieldAry = array("ID", "TITLE", "MEMOIRS")){
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
	
	//取得變數
	public function getVariables($name){
		$pathAry = explode("/", $_SERVER["REQUEST_URI"]);
		if (array_search($name, $pathAry)){
			$key = array_search($name, $pathAry);
			return $pathAry[$key + 1];
		}else{
			return null;
		}
	}
	
	//網頁導向
	public function reDirect($mesg, $url){
		require_once PHP_INCS . '/html/layout/redirect.phtml';
		exit;
	}
	
	//上傳檔案
	public function uploadFile($file, $file_name, $file_size, $path, $new_file, $old_file="", $patten="", $max_file_size=30720000, $lang="chinese") {
		$COMMON_UPLOAD_FILETYPE = "gif|jpg|png|jpeg|bmp|zip|rar|doc|pdf|xls";
		$base = $_SERVER['DOCUMENT_ROOT'];
		$path = str_replace("\\",DIRECTORY_SEPARATOR,$path);
		$path = $base.DIRECTORY_SEPARATOR.trim(str_replace($base,"",$path), DIRECTORY_SEPARATOR);

		if(strlen($new_file)) $new_file = $path.DIRECTORY_SEPARATOR.$new_file;
		//if(strlen($old_file)) $old_file = $path.DIRECTORY_SEPARATOR.$old_file;

		$patten = (!strcmp($patten, "")) ? "\.+[" . $COMMON_UPLOAD_FILETYPE . "]+$" : $patten;
		
		if ($file_size > $max_file_size) {
			$errmsg1 = " '$file_name': 上傳檔案大小超過規定的".($max_file_size/1024)."Kb. ";
			$errmsg2 = " '$file_name': The file size is over ".($max_file_size/1024)."Kb. ";
			return (($lang == "chinese") ? $errmsg1 : $errmsg2);
		}
		else if (strcmp($patten, "") && !preg_match("/" . $patten . "/", strtolower($file_name))) {
			$errmsg1 = " '$file_name': 上傳檔案格式不正確. ";
			$errmsg2 = " '$file_name': The file format no match. ";
			return (($lang == "chinese") ? $errmsg1 : $errmsg2);
		} else {
			if (!is_dir($path)) {
				if (!mkdirs($path, 755)) {
					$errmsg1 = " '$file_name': 建立資料夾時發生錯誤. ";
					$errmsg2 = " '$file_name': Make directory fail. ";
					return (($lang == "chinese") ? $errmsg1 : $errmsg2);
				}
			}

			if (file_exists("." . $old_file)) {
				@unlink("." . $old_file);
			}
			if (!@move_uploaded_file($file["tmp_name"], $new_file)) {
				$errmsg1 = " '$file_name': 複製檔案時發生錯誤. ";
				$errmsg2 = " Copy file fail. ";
				return (($lang == "chinese") ? $errmsg1 : $errmsg2);
			}
			return "";
		}
	}
}
?>