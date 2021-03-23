<?php
$action = getFrom('action');
$id = getFrom('id');
$kelas = select("*", "kelas", "id = $id");
$kls   = result($kelas);

?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Data Kelas</h1>
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Edit Data Kelas</h6>
        </div>
        <div class="card-body mb-4">
            <form action="<?php echo base_url("admin/data-kelas/edit/".$kls->id) ?>" method="POST">
                <div class="row">
                    <div class="offset-3 col-md-6">
                        <label for="nama_kelas" class="form-control-label">Nama Kelas</label>
                        <input type="text" name="nama_kelas" class="form-control" required value="<?php echo $kls->nama_kelas; ?>">
                        <br>

                        <label for="kelas" class="form-control-label">Kelas</label>
                        <select name="kelas" id="" class="form-control" required>
                            <option>-- Pilih Kelas --</option>
                            <?php for ($i=10; $i <= 12; $i++): ?>
                                <?php if ($kls->kelas == $i): ?>
                                    <option value="<?php echo $i; ?>" selected><?php echo $i; ?></option>
                                <?php else: ?>
                                    <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                <?php endif ?>
                            <?php endfor; ?>
                        </select>
                        <br>

                        <label for="jurusan" class="form-control-label">Kompetensi Keahlian (Jurusan)</label>
                        <input type="text" name="jurusan" class="form-control" required value="<?php echo $kls->jurusan; ?>">
                        <br>

                        <div class="row">
                            <div class="col-md-6">
                                <button class="btn btn-primary btn-block" type="submit" name="update">
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

if (isset($_POST['update'])) {
    $nama_kelas = getPost('nama_kelas');
    $kelas = getPost('kelas');
    $jurusan = getPost('jurusan');
    $id = $kls->id;

    if(!empty(trim($nama_kelas)) && !empty(trim($kelas)) && !empty(trim($jurusan))) {
        $update= update("kelas", "nama_kelas = '$nama_kelas', kelas = '$kelas', jurusan = '$jurusan'", $id);

        if($update) {
            alert("Data kelas berhasil diperbarui!", base_url("admin/data-kelas"));
        } else {
            alert("Data kelas gagal diperbarui!", "back");
        }
    } else {
        alert("Form tidak boleh ada yang kosong!", "back");
    }
}


?>