<?php
$new_kode = generateCode("mata_pelajaran", "MPL");
?>
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Mata Pelajaran</h1>
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Tambah Mata Pelajaran</h6>
        </div>
        <div class="card-body mb-4">
            <form action="<?php echo base_url("admin/mata-pelajaran/create") ?>" method="POST">
                <div class="row">
                    <div class="col-md-6 offset-3">
                        <label for="kode" class="form-control-label">Nomor ID</label>
                        <input type="text" name="kode" class="form-control" readonly value="<?php echo $new_kode; ?>">
                        <br>

                        <label for="nama" class="form-control-label">Nama Mata Pelajaran</label>
                        <input type="text" name="nama" class="form-control" required>
                        <br>
                        
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <button class="btn btn-block btn-primary" type="submit" name="submit">
                                    <span>Simpan</span>
                                </button>
                            </div>
                            <div class="col-md-6">
                                <a href="<?php echo base_url("admin/data-siswa") ?>" class="btn btn-block btn-secondary">
                                    <span>Kembali</span>
                                </a>
                            </div>
                        </div>
                        <br>
                    </div>
                </div>
            </form>>
        </div>
    </div>

</div>
<!-- /.container-fluid -->

<?php

if (isset($_POST['submit'])) {
    $nama = getPost('nama');
    $kode = $new_kode;

    if(!empty(trim($nama))) {
        $insert= insert("mata_pelajaran", "kode, nama", "'$kode', '$nama'");

        if($insert) {
            alert("Data mata pelajaran berhasil disimpan!", base_url("admin/mata-pelajaran"));
        } else {
            alert("Data mata pelajaran gagal disimpan!", "back");
        }
    } else {
        alert("Form tidak boleh ada yang kosong!", "back");
    }
}


?>