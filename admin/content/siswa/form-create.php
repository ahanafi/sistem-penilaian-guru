<?php
$sql_kelas = select("*", "kelas");

$new_kode = generateCode("siswa", "STD");

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
            <form action="<?php echo base_url("admin/data-siswa/create") ?>" method="POST">
                <div class="row">
                    <div class="col-md-6 offset-3">
                        <label for="kode" class="form-control-label">Nomor ID</label>
                        <input type="text" name="kode" class="form-control" readonly value="<?php echo $new_kode; ?>">
                        <br>

                        <label for="nis" class="form-control-label">Nomor Induk Siswa (NIS)</label>
                        <input type="text" name="nis" class="form-control" required>
                        <br>

                        <label for="nama" class="form-control-label">Nama Lengkap</label>
                        <input type="text" name="nama" class="form-control" required>
                        <br>

                        <label for="jk" class="form-control-label">Jenis Kelamin</label>
                        <select name="jk" id="" class="form-control" required>
                            <option>-- Pilih Jenis Kelamin --</option>
                            <option value="L">Laki-Laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                        <br>

                        <label for="kelas" class="form-control-label">Kelas</label>
                        <select name="kelas" id="list-kelas" class="form-control" required>
                            <option>-- Pilih Kelas --</option>
                            <?php while($kls = result($sql_kelas)): ?>
                                <option value="<?php echo $kls->id ?>"><?php echo $kls->nama_kelas; ?></option>
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
        $cek_nis = select("*", "siswa", "kode = '$kode' OR nis = '$nis'");
        if(cekRow($cek_nis) > 0) {
            alert("Nomor Induk Siswa atau Kode Siswa sudah dipakai!", "back");
        } else {
            $nama = addslashes($nama);
            $insert = insert("siswa", "kode, nis, nama, jk, id_kelas, is_deleted", "'$kode', '$nis', '$nama', '$jk', '$kelas', 0");

            if($insert) {
                alert("Data siswa baru berhasil ditambahkan!", base_url("admin/data-siswa"));
            } else {
                alert("Data siswa baru gagal ditambahkan!", base_url("admin/data-siswa"));
            }
        }
    }
}


?>