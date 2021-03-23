<?php
$action = getFrom('action');
$id = getFrom('id');
if ($action == "hapus-pengajar" && $id != 0) {
    $delete = delete("guru_mapel", $id);
    if ($delete) {
        alert("Data pengajar kelas berhasil dihapus!", "back");
    } else {
        alert("Data pengajar kelas gagal dihapus!", "back");
    }
}
?>