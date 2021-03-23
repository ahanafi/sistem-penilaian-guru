<?php
$action = getFrom('action');
$id = getFrom('id');
if($action == "edit" && $id != 0) {
    $aspek = select("*", "aspek_penilaian", $id);
    $apk   = result($aspek);
}

?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Apsek Penilaian</h1>
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Tambah Apsek Penilaian</h6>
        </div>
        <div class="card-body mb-4">
            <form action="<?php echo base_url("admin/aspek-penilaian/edit/".$apk->id) ?>" method="POST">
                <div class="row">
                    <div class="offset-3 col-md-6">
                        <label for="nama_indikator" class="form-control-label">Nama Aspek Penilaian</label>
                        <input type="text" name="nama_indikator" class="form-control" required autocomplete="off" value="<?php echo $apk->nama_indikator; ?>">
                        <br>

                        <div class="row">
                            <div class="col-md-6">
                                <label for="nilai_min" class="form-control-label">Nilai Minimal</label>
                                <input type="number" name="nilai_min" min="1" required class="form-control" autocomplete="off" value="<?php echo $apk->nilai_min; ?>">
                            </div>
                            <div class="col-md-6">
                                <label for="nilai_max" class="form-control-label">Nilai Maximal</label>
                                <input type="number" name="nilai_max" max="100" required class="form-control" autocomplete="off" value="<?php echo $apk->nilai_max; ?>">
                            </div>
                        </div>
                        <br>

                        <label for="keterangan" class="form-control-label">Keterangan (Opsional)</label>
                        <textarea name="keterangan" rows="3" class="form-control" style="resize: none;"><?php echo $apk->keterangan; ?></textarea>
                        <br>

                        <div class="row">
                            <div class="col-md-6">
                                <button class="btn btn-primary btn-block" type="submit" name="submit">
                                    <span>Simpan</span>
                                </button>
                            </div>
                            <div class="col-md-6">
                                <a href="<?php echo base_url("admin/aspek-penilaian") ?>" class="btn btn-secondary btn-block">
                                    <span>Kembali</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>
<!-- /.container-fluid -->

<?php

if (isset($_POST['submit'])) {
    $nama_indikator = getPost('nama_indikator');
    $nilai_min = getPost('nilai_min');
    $nilai_max = getPost('nilai_max');
    $keterangan = getPost('keterangan');
    /*if($keterangan == "") {
        $keterangan = NULL;
    }*/

    if(!empty(trim($nama_indikator)) && !empty(trim($nilai_min)) && !empty(trim($nilai_max))) {
        if($nilai_min == $nilai_max) {
            alert("Nilai minimal dan nilai maksimal tidak boleh sama!", "back");
        } else {
            $update = update ("aspek_penilaian", "nama_indikator = '$nama_indikator', nilai_min = '$nilai_min', nilai_max = '$nilai_max', keterangan = '$keterangan'", $apk->id);

            if($update ) {
                alert("Data aspek penilaian berhasil diperbarui!", base_url("admin/aspek-penilaian"));
            } else {
                alert("Data aspek penilaian gagal diperbarui!", "back");
            }
        }
    } else {
        alert("Form tidak boleh ada yang kosong!", "back");
    }
}


?>