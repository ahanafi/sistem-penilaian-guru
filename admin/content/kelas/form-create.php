<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Data Kelas</h1>
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Tambah Data Kelas</h6>
        </div>
        <div class="card-body mb-4">
            <form action="<?php echo base_url("admin/data-kelas/create") ?>" method="POST">
                <div class="row">
                    <div class="offset-3 col-md-6">
                        <label for="nama_kelas" class="form-control-label">Nama Kelas</label>
                        <input type="text" name="nama_kelas" class="form-control" required>
                        <br>

                        <label for="kelas" class="form-control-label">Kelas</label>
                        <select name="kelas" id="" class="form-control" required>
                            <option>-- Pilih Kelas --</option>
                            <?php for ($i=10; $i <= 12; $i++): ?>
                                <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                            <?php endfor; ?>
                        </select>
                        <br>

                        <label for="jurusan" class="form-control-label">Kompetensi Keahlian (Jurusan)</label>
                        <input type="text" name="jurusan" class="form-control" required>
                        <br>

                        <div class="row">
                            <div class="col-md-6">
                                <button class="btn btn-primary btn-block" type="submit" name="submit">
                                    <span>Simpan</span>
                                </button>
                            </div>
                            <div class="col-md-6">
                                <a href="<?php echo base_url("admin/data-kelas") ?>" class="btn btn-secondary btn-block">
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
    $nama_kelas = getPost('nama_kelas');
    $kelas = getPost('kelas');
    $jurusan = getPost('jurusan');

    if(!empty(trim($nama_kelas)) && !empty(trim($kelas)) && !empty(trim($jurusan))) {
        $insert= insert("kelas", "nama_kelas, kelas, jurusan", "'$nama_kelas', '$kelas', '$jurusan'");

        if($insert) {
            alert("Data kelas baru berhasil disimpan!", base_url("admin/data-kelas"));
        } else {
            alert("Data kelas baru gagal disimpan!", "back");
        }
    } else {
        alert("Form tidak boleh ada yang kosong!", "back");
    }
}


?>