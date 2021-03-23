<?php
if(strpos($_SERVER['DOCUMENT_ROOT'], 'skp-system') === true) {
	$__ROOT__ = $_SERVER['DOCUMENT_ROOT'];
} else {
	$__ROOT__ = $_SERVER['DOCUMENT_ROOT'] . '/skp-system/';
}

$root = explode("/", $__ROOT__);
if(count($root) > 4) {
	$strRoot = "";
	for($i=0; $i<=4; $i++) {
		$strRoot = $strRoot . $root[$i] . '/';
	}

	$__ROOT__ = $strRoot;
}

require_once $__ROOT__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::create($__ROOT__);
$dotenv->load();

if(strtoupper(getenv('APP_DEBUG')) === TRUE || strtoupper(getenv('APP_DEBUG')) === 'TRUE') {
	error_reporting(E_ALL | E_STRICT);
	ini_set('display_errors', 'On');
} else {
	error_reporting(0);
	ini_set('display_errors', 'Off');
}

ini_set('session.save_path', realpath(dirname($_SERVER['DOCUMENT_ROOT']) . '/../session'));
session_start();
ob_start();

require_once 'session.php';
require_once 'other.php';
require_once 'connect.php';
require_once 'crud.php';
require_once 'method.php';
require_once 'auth.php';
$no = 1;
?>