<?php
require_once("core/init.php");

$script_filename = explode("/", $_SERVER['SCRIPT_FILENAME']);
$script_filename = end($script_filename);

$script_name = explode("/", $_SERVER['SCRIPT_NAME']);
$script_name = end($script_name);

$self = explode("/", $_SERVER['PHP_SELF']);
$self = end($self);

$req_uri = explode("/", $_SERVER['REQUEST_URI']);
$req_uri = end($req_uri);

if ($script_filename === $script_name && $self === $req_uri) {
	//redirect("404");
	require_once '404.php';
} else {

	if (isset($_GET['kompetensi']) && $_GET['kompetensi'] != "") {
		$kompetensi = htmlentities(htmlspecialchars(addslashes($_GET['kompetensi'])));
		$kompetensi = str_replace("-", " ", $kompetensi);

		$sql_kelas = select("*", "kelas", "jurusan = '$kompetensi'");
		$arr_kelas = [];
		$i = 0;
		while ($kelas = result($sql_kelas)) {
			$arr_kelas[$i] = [
				'nama_kelas' => $kelas->nama_kelas,
				'id_kelas' => $kelas->id
			];
			$i++;
		}

		echo json_encode($arr_kelas);
	}
}

?>