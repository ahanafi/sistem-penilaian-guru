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
            <form action="<?php echo base_url("admin/aspek-penilaian/create") ?>" method="POST">
                <div class="row">
                    <div class="offset-3 col-md-6">
                        <label for="nama_indikator" class="form-control-label">Nama Aspek Penilaian</label>
                        <input type="text" name="nama_indikator" class="form-control" required autocomplete="off">
                        <br>

                        <div class="row">
                            <div class="col-md-6">
                                <label for="nilai_min" class="form-control-label">Nilai Minimal</label>
                                <input type="number" name="nilai_min" min="1" required class="form-control" autocomplete="off">
                            </div>
                            <div class="col-md-6">
                                <label for="nilai_max" class="form-control-label">Nilai Maximal</label>
                                <input type="number" name="nilai_max" max="100" required class="form-control" autocomplete="off">
                            </div>
                        </div>
                        <br>

                        <label for="keterangan" class="form-control-label">Keterangan (Opsional)</label>
                        <textarea name="keterangan" rows="3" class="form-control" style="resize: none;"></textarea>
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
            $insert= insert("aspek_penilaian", "nama_indikator, nilai_min, nilai_max, keterangan", "'$nama_indikator', '$nilai_min', '$nilai_max', '$keterangan'");

            if($insert) {
                alert("Data aspek penilaian berhasil disimpan!", base_url("admin/aspek-penilaian"));
            } else {
                alert("Data aspek penilaian gagal disimpan!", "back");
            }
        }
    } else {
        alert("Form tidak boleh ada yang kosong!", "back");
    }
}


?>