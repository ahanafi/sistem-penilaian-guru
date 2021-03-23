<?php
$action = getFrom('action');
$id = getFrom('id');
if ($action == "delete" && $id != 0) {
    $delete = delete("aspek_penilaian", $id);
    if ($delete) {
        alert("Data aspek penilaian berhasil dihapus!", base_url("admin/aspek-penilaian"));
    } else {
        alert("Data aspek penilaian gagal dihapus!", "back");
    }
}
?>