<?php
$sql_kelas = select("*", "kelas");
$id = getFrom('id');
$siswa = select("*", "siswa", "id = '$id'");
$siswa = result($siswa);

?>
<style>
    .select2-container--bootstrap4 .select2-dropdown .select2-results__option[aria-selected=true] {
        background-color: #4f85bb !important;
        color: #fff !important;
    }
</style>
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Data Siswa</h1>
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Tambah Data Siswa</h6>
        </div>
        <div class="card-body mb-4">
            <form action="<?php echo base_url("admin/data-siswa/edit/".$siswa->id) ?>" method="POST">
                <div class="row">
                    <div class="col-md-6 offset-3">
                        <label for="kode" class="form-control-label">Nomor ID</label>
                        <input type="text" name="kode" class="form-control" readonly value="<?php echo $siswa->kode; ?>">
                        <br>

                        <label for="nis" class="form-control-label">Nomor Induk Siswa (NIS)</label>
                        <input type="text" name="nis" value="<?= $siswa->nis ?>" class="form-control" required>
                        <br>

                        <label for="nama" class="form-control-label">Nama Lengkap</label>
                        <input type="text" name="nama" value="<?= $siswa->nama ?>" class="form-control" required>
                        <br>

                        <label for="jk" class="form-control-label">Jenis Kelamin</label>
                        <select name="jk" id="" class="form-control" required>
                            <option>-- Pilih Jenis Kelamin --</option>
                            <?php if ($siswa->jk == "L"): ?>
                                <option value="L" selected>Laki-Laki</option>
                                <option value="P">Perempuan</option>
                            <?php else: ?>
                                <option value="L">Laki-Laki</option>
                                <option value="P" selected>Perempuan</option>
                            <?php endif ?>
                        </select>
                        <br>

                        <label for="kelas" class="form-control-label">Kelas</label>
                        <select name="kelas" id="list-kelas" class="form-control" required>
                            <option>-- Pilih Kelas --</option>
                            <?php while($kls = result($sql_kelas)): ?>
                                <?php if ($kls->id == $siswa->id_kelas): ?>
                                    <option value="<?php echo $kls->id ?>" selected><?php echo $kls->nama_kelas; ?></option>
                                <?php else: ?>
                                    <option value="<?php echo $kls->id ?>"><?php echo $kls->nama_kelas; ?></option>
                                <?php endif ?>
                            <?php endwhile; ?> 
                        </select>
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
            </form>
        </div>
    </div>

</div>
<!-- /.container-fluid -->

<?php

if (isset($_POST['submit'])) {
    $kode = getPost('kode');
    $nis = getPost('nis');
    $nama = getPost('nama');
    $jk   = getPost('jk');
    $kelas = getPost('kelas');

    if(empty(trim($nama)) || empty(trim($nis)) || empty(trim($jk)) || empty(trim($kelas))) {
        alert("Form tidak boleh ada kosong!", "back");
    } else {
        $nama = addslashes($nama);
        $update = update("siswa", "nis = '$nis', nama = '$nama', jk = '$jk', id_kelas = '$kelas'", $id);

        if($update) {
            alert("Data siswa baru berhasil diperbarui!", base_url("admin/data-siswa"));
        } else {
            alert("Data siswa baru gagal diperbarui!", base_url("admin/data-siswa"));
            
        }
    }
}


?>