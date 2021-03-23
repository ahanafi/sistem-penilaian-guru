<?php
$action = getFrom('action');
$id = getFrom('id');
if ($action == "delete" && $id != 0) {
    $delete = delete("mata_pelajaran", $id);
    if ($delete) {
        alert("Data mata pelajaran berhasil dihapus!", base_url("admin/mata-pelajaran"));
    } else {
        alert("Data mata pelajaran gagal dihapus!", "back");
    }
}
?>