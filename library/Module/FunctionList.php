<?php
class Module_FunctionList extends Module_ObjectDb{
	private $_array, $_titleAry;
	
	public function __construct(){
		parent::__construct();
		$this->fdata();
	}
	
	private function fdata(){
		$this->_array = array("home", "news","about","download","business","member", "album", "exp","contact", "calendar");
		$this->_titleAry = array(
							"home"		=> array("title" => "首頁", "active" => "Y"),
							"about"		=> array("title" => "協會簡介", "active" => "Y"),
							"news"		=> array("title" => "最新消息", "active" => "Y"),
							"business"	=> array("title" => "各項業務申請", "active" => "Y"),
							"download"	=> array("title" => "檔案下載", "active" => "Y"),
							"contact"	=> array("title" => "聯絡我們", "active" => "Y"),
							"album"		=> array("title" => "打火弟兄園地", "active" => "Y"),
							"exp"		=> array("title" => "打火弟兄園地 - 經驗分享", "active" => "N"),
							//"member"	=> array("title" => "會員專區", "active" => "Y"),
							"calendar"	=> array("title" => "協會行事曆", "active" => "Y"),
							"link"		=> array("title" => "全國警義消單位網站連結", "active" => "N")
		);
	}
	
	public function chk($name="home"){
		return in_array($name, $this->_array);
	}
	
	//取出網站資訊
	public function getMyWebSite(){
		return $this->getRowData("site_config", array("strWhe" => array("ID = '1'")), array("TITLE", "TEL", "FAX", "EMAIL", "ADDRESS", "CALENDAR", "IMAGE1", "MEMOIRS"));
	}
	
	public function getAry(){
		return $this -> _titleAry;
	}
	
	//取出功能名稱
	public function getFuName($name){
		return $this->_titleAry[$name]["title"];
	}
	
	//指定文字長度
	public function getLimitStr($str, $limit, $repstr){
		if (mb_strlen($str, "utf8") > $limit) {
			$retval = mb_substr($str, 0, ($limit - mb_strlen($repstr, "utf8")), "utf8") . $repstr;
		}
		else {
			$retval = $str;
		}
		return $retval;
	}

}