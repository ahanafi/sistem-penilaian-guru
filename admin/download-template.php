<?php
require_once("../core/init.php");
$file_name = $_GET['filename'];
if($file_name != "") {
	if (file_exists("../sample/template-".$file_name.".xlsx")) {
	    $file = "../sample/template-".$file_name.".xlsx";
	    header('Content-Description: File Transfer');
	    header('Content-Type: application/octet-stream');
	    header('Content-Disposition: attachment; filename='.basename($file));
	    header('Content-Transfer-Encoding: binary');
	    header('Expires: 0');
	    header('Cache-Control: private');
	    header('Pragma: private');
	    header('Content-Length: ' . filesize($file));
	    ob_clean();
	    flush();
	    readfile($file);
	    exit;
	}
}
?>