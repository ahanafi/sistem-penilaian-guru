<?php
$id = getFrom('id');
$sql_guru = select("*", "guru", "id = $id");
$guru = result($sql_guru);

//$sql_kelas = select("*", "kelas");
$sql_kelas = query("SELECT * FROM kelas WHERE id NOT IN (SELECT id_kelas FROM kelas_ajar WHERE kode_guru = '$guru->kode')");
$kelas_ajar = joinTable("nama_kelas, kelas_ajar.id as ka_id", "kelas", "kelas_ajar", "kelas.id = kelas_ajar.id_kelas", "kelas_ajar.kode_guru = '$guru->kode' ORDER BY ka_id DESC");
$no = 1;
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
    <div class="row">
        <div class="col-md-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Form Tambah Data Guru</h6>
                </div>
                <div class="card-body mb-4">
                    <form action="<?php echo base_url("admin/data-guru/edit/".$guru->id) ?>" method="POST">
                        <div class="row">
                            <div class="col-md-10 offset-1">
                                <label for="kode" class="form-control-label">Nomor ID</label>
                                <input type="text" name="kode" class="form-control" readonly value="<?php echo $guru->kode; ?>">
                                <br>

                                <label for="nama" class="form-control-label">Nama Lengkap</label>
                                <input type="text" name="nama" class="form-control" required value="<?php echo $guru->nama; ?>">
                                <br>

                                <label for="jk" class="form-control-label">Jenis Kelamin</label>
                                <select name="jk" id="" class="form-control" required>
                                    <option>-- Pilih Jenis Kelamin --</option>
                                    <?php if ($guru->jk == "L"): ?>
                                        <option value="L" selected>Laki-Laki</option>
                                        <option value="P">Perempuan</option>
                                    <?php else: ?>
                                        <option value="L">Laki-Laki</option>
                                        <option value="P" selected>Perempuan</option>
                                    <?php endif ?>
                                </select>
                                <br>

                                <label for="telpon" class="form-control-label">Nomor Telpon</label>
                                <input type="text" name="telpon" class="form-control" value="<?php echo $guru->telpon; ?>">
                                <br>

                                <label for="alamat" class="form-control-label">Alamat</label>
                                <textarea name="alamat" rows="2" class="form-control" style="height: 130px;resize: none;"><?php echo $guru->alamat; ?></textarea>
                                <br>
                                
                                <div class="row">
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
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Kelas yang diajar</h6>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped" id="data">
                        <thead>
                            <tr>
                                <th class="text-center">No.</th>
                                <th>Nama Kelas</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($kls = result($kelas_ajar)): ?>
                                <tr>
                                    <td class="text-center"><?php echo $no++; ?></td>
                                    <td><?php echo $kls->nama_kelas ?></td>
                                    <td class="text-center">
                                        <a href="<?php echo base_url("admin/data-guru/delete-kelas/".$kls->ka_id); ?>" class="btn btn-circle btn-danger" onclick="return confirmDelete()">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card shadow mt-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Tambah Kelas yang diajar</h6>
                </div>
                <div class="card-body mb-4">
                    <form action="<?php echo base_url("admin/data-guru/tambah-kelas/".$guru->id) ?>" method="POST">
                        <div class="row">
                            <div class="col-md-8">
                                <select name="kelas_ajar[]" multiple id="list-kelas" class="form-control" required>
                                    <option>-- Pilih Kelas --</option>
                                    <?php while($kls = result($sql_kelas)): ?>
                                        <option value="<?php echo $kls->id ?>"><?php echo $kls->nama_kelas; ?></option>
                                    <?php endwhile; ?> 
                                </select>
                            </div>
                            <div class="col-md-4">
                                <button class="btn btn-primary btn-block" type="submit" name="add-class">
                                    <i class="fas fa-plus"></i>
                                    <span>Tambahkan</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
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

    if(empty(trim($nama))) {
        alert("Nama tidak boleh kosong!", "back");
    } else {
        $nama = addslashes($nama);
        $update_guru = update("guru", "kode = '$kode', nama = '$nama', jk = '$jk', telpon = '$telpon', alamat = '$alamat'", $guru->id);

        if($update_guru) {
            alert("Data guru berhasil diperbarui!", base_url("admin/data-guru"));
        } else {
            alert("Data guru gagal diperbarui!", base_url("admin/data-guru"));
        }
    }
}

if (isset($_POST['add-class'])) {
    $kelas_ajar = getPost('kelas_ajar');
    $totalKelas = count($kelas_ajar);

    $isExists = 0;
    for ($i=0; $i < $totalKelas; $i++) { 
        $cek_kelas = select("*", "kelas_ajar", "id_kelas = '$kelas_ajar[$i]' AND kode_guru = '$guru->kode'");
        if(cekRow($cek_kelas) > 0) {
            alert("Tidak dapat menambah kelas yang telah diajar oleh Guru tersebut!", "back");
            $isExists = 1;
            exit;
        }
    }

    if($isExists == 0) {
        for ($i=0; $i < $totalKelas; $i++) { 
            $insert = insert("kelas_ajar", "kode_guru, id_kelas", "'$guru->kode', '$kelas_ajar[$i]'");
        }

        if($insert) {
            alert("Data kelas ajar baru berhasil ditambahkan!", base_url("admin/data-guru/edit/".$guru->id));
        } else {
            alert("Data kelas ajar baru gagal ditambahkan!", "back");
        }
    } else {
        redirect(base_url("admin/data-guru/edit/".$guru->id));
    }
}
?>