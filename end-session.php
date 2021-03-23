<?php
require_once("core/init.php");
if (cekSessionSiswa()) {
	unset($_SESSION['NIS']);
	unset($_SESSION['KELAS_ID']);
	unset($_SESSION['KODE_SISWA']);
	unset($_SESSION['TOTAL_GURU']);
	unset($_SESSION['INDEX']);
	session_destroy();
	redirect("welcome");
} else {
	redirect("welcome");
}
?>