<?php
$action = getFrom('action');
$id = getFrom('id');

if ($action == "delete-kelas" && $id != 0) {
    $delete = delete("kelas_ajar", $id);
    if ($delete) {
        alert("Data kelas ajar berhasil diperbarui!", "back");
    } else {
        alert("Data kelas ajar gagal diperbarui!", "back");
    }
}
?>