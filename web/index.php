<?php
define("PS", PATH_SEPARATOR);
define('DS', DIRECTORY_SEPARATOR);
define('PHP_OBJ', str_replace( '\\',DS,dirname(dirname( __FILE__ ))));
define('PHP_INCS', PHP_OBJ . DS . "includes");
define('PHP_LIBS', PHP_OBJ . DS . "library");
define('TEMPLATE_PATH', PHP_INCS . DS . "html" . DS . "body");
define("upLoadDir", "/public");

//讀取zend的autoloader
set_include_path(get_include_path() . PS . PHP_LIBS);
require_once(PHP_LIBS . DS . "Zend" . DS . "Loader" . DS . "Autoloader.php");
$autoloader = Zend_Loader_Autoloader::getInstance();
$autoloader -> registerNamespace("Module_");

$siteDatas = new Zend_Session_Namespace("SITE_DATAS");
$siteDatas -> USER_LOGIN_DATA = (is_object($siteDatas -> USER_LOGIN_DATA)) ? $siteDatas -> USER_LOGIN_DATA : new stdClass();
$siteDatas -> STORE_DB = (strcmp($siteDatas -> STORE_DB, "")) ? $siteDatas -> STORE_DB : null;
$siteDatas -> LANG = (isset($siteDatas -> LANG)) ? $siteDatas -> LANG : "chinese";
$siteDatas -> CLANG = (isset($siteDatas -> CLANG)) ? $siteDatas -> CLANG : "utf8";

//連結資料庫
Module_Managedb::getInstance()->connMysqlDb(new Module_Conf());

//執行網頁
$web = new Module_ManageCenter();
$web->init();