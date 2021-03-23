<?php
$action = getFrom('action');
$id = getFrom('id');
if ($action == "delete" && $id != 0) {
    $delete = delete("siswa", $id);
    if ($delete) {
        alert("Data siswa berhasil dihapus!", base_url("admin/data-siswa"));
    } else {
        alert("Data siswa gagal dihapus!", "back");
    }
}
?>