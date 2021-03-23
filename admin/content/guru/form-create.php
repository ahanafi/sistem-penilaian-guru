<?php
$sql_kelas = select("*", "kelas");

$sql_kode = select("MAX(kode) as kode", "guru");
$kode_db  = result($sql_kode);
$kode_db  = str_replace("GR-", "", $kode_db->kode);
$kode_db  = (int) $kode_db + 1;

if (strlen($kode_db) == 1) {
    $addZero = "000";
} elseif (strlen($kode_db) == 2) {
    $addZero = "00";
} elseif (strlen($kode_db) == 3) {
    $addZero = "0";
} else {
    $addZero = "";
}

$new_kode = "GR-" . $addZero . $kode_db;

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
    <h1 class="h3 mb-2 text-gray-800">Data Guru</h1>
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Tambah Data Guru</h6>
        </div>
        <div class="card-body mb-4">
            <form action="<?php echo base_url("admin/data-guru/create") ?>" method="POST">
                <div class="row">
                    <div class="col-md-4 offset-2">
                        <label for="kode" class="form-control-label">Nomor ID</label>
                        <input type="text" name="kode" class="form-control" readonly value="<?php echo $new_kode; ?>">
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

                        <label for="telpon" class="form-control-label">Nomor Telpon</label>
                        <input type="text" name="telpon" class="form-control">
                        <br>
                    </div>
                    <div class="col-md-4">
                        <label for="kelas" class="form-control-label">Kelas yang diajar</label>
                        <select name="kelas_ajar[]" multiple id="list-kelas" class="form-control" required>
                            <option>-- Pilih Kelas --</option>
                            <?php while($kls = result($sql_kelas)): ?>
                                <option value="<?php echo $kls->id ?>"><?php echo $kls->nama_kelas; ?></option>
                            <?php endwhile; ?> 
                        </select>
                        <br>

                        <label for="alamat" class="form-control-label mt-4">Alamat</label>
                        <textarea name="alamat" rows="2" class="form-control mb-4" style="height: 130px;resize: none;"></textarea>
                        <br>
                        
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <button class="btn btn-block btn-primary" type="submit" name="submit">
                                    <span>Simpan</span>
                                </button>
                            </div>
                            <div class="col-md-6">
                                <a href="<?php echo base_url("admin/data-guru") ?>" class="btn btn-block btn-secondary">
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
    $nama = getPost('nama');
    $jk   = getPost('jk');
    $telpon = getPost('telpon');
    $alamat = getPost('alamat');
    $kelas_ajar = getPost('kelas_ajar');

    if(empty(trim($nama))) {
        alert("Nama tidak boleh kosong!", "back");
    } else {
        $totalKelas = count($kelas_ajar);
        if ($totalKelas > 0) {
            $nama = addslashes($nama);
            $insert_guru = insert("guru", "kode, nama, jk, telpon, alamat", "'$kode', '$nama', '$jk', '$telpon', '$alamat'");

            for ($i=0; $i < $totalKelas; $i++) { 
                $insert_kelas_ajar = insert("kelas_ajar", "kode_guru, id_kelas", "'$kode', '$kelas_ajar[$i]'");
            }

            if($insert_guru && $insert_kelas_ajar) {
                alert("Data guru baru berhasil ditambahkan!", base_url("admin/data-guru"));
            } else {
                alert("Data guru baru gagal ditambahkan!", base_url("admin/data-guru"));
            }
        } else {
            alert("Silahkan pilih kelas yang diajar oleh Guru tersebut!", "back");
        }
    }
}


?>