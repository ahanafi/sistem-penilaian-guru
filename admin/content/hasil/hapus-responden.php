<?php
$page = getFrom('page');
$action = getFrom('action');
$kode_siswa = getFrom('kode-siswa');
if ($page == "responden" && $action == "delete" && $kode_siswa != "") {
    $delete = execute("DELETE FROM penilaian WHERE kode_siswa = '$kode_siswa'");
    if($delete) {
        alert("Data penilaian responden berhasil dihapus!", "back");
    } else {
        alert("Data penilaian responden gagal dihapus!", "back");
    }
}
?>