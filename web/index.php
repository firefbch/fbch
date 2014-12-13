<?php
define("PS", PATH_SEPARATOR);
define('DS', DIRECTORY_SEPARATOR);
define('PHP_OBJ', str_replace( '\\',DS,dirname(dirname( __FILE__ ))));
define('PHP_INCS', PHP_OBJ . DS . "includes");
define('PHP_LIBS', PHP_OBJ . DS . "library");
define('LAYOUT_PATH', PHP_INCS . DS . "html" . DS . "layout");
define('TEMPLATE_PATH', PHP_INCS . DS . "html" . DS . "body");
define("upLoadDir", "/public");

//讀取zend的autoloader
set_include_path(get_include_path() . PS . PHP_LIBS);
require_once(PHP_LIBS . DS . "Zend" . DS . "Loader" . DS . "Autoloader.php");
$autoloader = Zend_Loader_Autoloader::getInstance();
$autoloader -> registerNamespace("Module_");

//連結資料庫
Module_Managedb::getInstance()->connMysqlDb(new Module_Conf());

//執行網頁
$web = new Module_ManageCenter();
$web->init();