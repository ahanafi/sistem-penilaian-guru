<?php
$action = getFrom('action');
$id = getFrom('id');
if ($action == "delete" && $id != 0) {
    $delete = delete("guru", $id);
    if ($delete) {
        alert("Data guru berhasil dihapus!", base_url("admin/data-guru"));
    } else {
        alert("Data guru gagal dihapus!", "back");
    }
}
?>