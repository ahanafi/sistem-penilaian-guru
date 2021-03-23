<?php
function base_url($file = NULL) {
	$path = $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['HTTP_HOST'] . "/skp-system/";
	//$path = "http://10.146.229.136/skp-system/";
	$path .= $file;
	return $path;
}

function redirect($loc = "back")
{
	if($loc != "back") {
		return header("Location: $loc");
	} else {
		echo "<script type='text/javascript'>window.history.back();</script>";
	}
}

function alert($text, $location = NULL){
	$alert = "<script type='text/javascript'>alert('$text');";
	if($location != NULL) {
		if($location == "back") {
			$alert .= "window.history.back();";
		} else {
			$alert .= "window.location='$location';";
		}
	}
	$alert .= "</script>";
	echo $alert;
}

function getLevel($index = NULL) {
	$levels = [
		'Administrator', 'Operator', 'Warga / Masyarakat'
	];

	if($index != NULL) {
		return $levels[$index-1];
	} else {
		return $levels;
	}
}

function call_content($menu = "admin", $nama_file) {
	$path_file = "content/".$menu."/".$nama_file.".php";
	if (file_exists($path_file)) {
		$file = require_once($path_file);
		return $file;
	} else {
		die("Template tidak ditemukan!");
	}
}

function getTemplate($file_name, $directory = "content") {
	$file = require_once($directory . "/" . $file_name . ".php");
	return $file;
}

function getKeterangan($nilai) {
	$keterangan = "";
	$nilai  = (int) $nilai;

	//Nilai <=
	if($nilai >= 90 && $nilai <= 100) {
		$keterangan = "SB";
	} elseif ($nilai >= 80 && $nilai <= 89) {
		$keterangan = "B";
	} elseif ($nilai >= 70 && $nilai <= 79) {
		$keterangan = "C";
	} elseif ($nilai >= 60 && $nilai <= 69) {
		$keterangan = "S";
	} else {
		$keterangan = "K";
	}

	return $keterangan;
}

function getDeskripsi($index = NULL) {
	$desc = [
		'SB'	=> 'Sangat Baik',
		'B' 	=> 'Baik',
		'C' 	=> 'Cukup',
		'S'		=> 'Sedang',
		'K'		=> 'Kurang'
	];
	if($index != NULL) {
		return $desc[$index];
	}

	return $desc;
}

function encrypt_decrypt($action, $string) {
    $output = false;

    $encrypt_method = "AES-256-CBC";
    $secret_key = 'This is my secret key';
    $secret_iv = 'This is my secret iv';

    // hash
    $key = hash('sha256', $secret_key);
    
    // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
    $iv = substr(hash('sha256', $secret_iv), 0, 16);

    if( $action == 'encrypt' ) {
        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($output);
    }
    else if( $action == 'decrypt' ){
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    }

    return $output;
}
?>