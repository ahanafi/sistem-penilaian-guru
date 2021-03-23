<?php
function select($columns, $table, $conditions = NULL) {
	if ($conditions != NULL) {
		$sql = "SELECT $columns FROM $table WHERE $conditions ";
	} else {
		$sql = "SELECT $columns FROM $table";
	}

	return query($sql);
}

function result($query) {
	return mysqli_fetch_object($query);
}

function query($sql){
	global $link;
	if ($data = mysqli_query($link, $sql) or die(mysqli_error($link))) {
		return $data;
	}
}

function execute($sql){
	global $link;
	if (mysqli_query($link, $sql) or die(mysqli_error($link))) {
		return TRUE;
	} else {
		return FALSE;
	}
}

function insert($table, $cols, $values){
	$sql = "INSERT INTO $table ($cols) VALUES ($values) ";
	return execute($sql);
}

function update($table, $data, $id){
	$sql = "UPDATE $table SET $data WHERE id = $id ";
	//die($sql);
	return execute($sql);
}

function delete($table, $id){
	$sql = "DELETE FROM $table WHERE id = $id ";
	return execute($sql);
}

function cekRow($sql) {
	return mysqli_num_rows($sql);
}

function joinTable($columns = '*', $table1, $table2, $conditions, $where = NULL) {
	$sql = "SELECT $columns FROM $table1 JOIN $table2 ON $conditions";
	if($where != NULL) {
		$sql .= " WHERE $where";
	}
	//die($sql);

	return query($sql);
}

function generateCode($table, $str) {
	$sql_kode = select("MAX(kode) as kode", $table);
	$kode_db  = result($sql_kode);
	$kode_db  = str_replace($str . "-", "", $kode_db->kode);
	$kode_db  = (int) $kode_db + 1;

	if (strlen($kode_db) == 1) {
	    $addZero = "000";
	} elseif (strlen($kode_db) == 2) {
	    $addZero = "00";
	} elseif (strlen($kode_db) == 3) {
	    $addZero = "0";
	} else {
	    $addZero = "";
	}

	$new_kode = $str . "-" . $addZero . $kode_db;
	return $new_kode;
}

function escape($str) {
	global $link;
	return mysqli_real_escape_string($link, $str);
}

?>