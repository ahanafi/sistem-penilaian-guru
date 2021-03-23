<?php
$action = getFrom('action');
$id = getFrom('id');
if ($action == "delete" && $id != 0) {
    $delete = delete("kelas", $id);
    if ($delete) {
        alert("Data kelas berhasil dihapus!", base_url("admin/data-kelas"));
    } else {
        alert("Data kelas gagal dihapus!", "back");
    }
}
?>