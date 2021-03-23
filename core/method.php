<?php

function getPost($key) {
	if(is_array($_POST[$key])) {
		return $_POST[$key];
	} else {
		return escape($_POST[$key]);
	}
}

function getFrom($index) {
	$get = isset($_GET[$index]) ? $_GET[$index] : "";
	return $get;
}

function getFile($main, $index) {
	return $_FILES[$main][$index];
}