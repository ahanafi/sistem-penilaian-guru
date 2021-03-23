<?php
$sql_kelas = select("kelas.nama_kelas, kelas.id AS id_kelas", "kelas");
$sql_guru = select("guru.nama, guru.kode AS kode_guru", "guru");
$sql_mapel = select("mata_pelajaran.nama, mata_pelajaran.kode AS kode_mapel", "mata_pelajaran");

?>

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
            <form action="<?php echo base_url("admin/data-kelas/set-mapel-global") ?>" method="POST">
                <div class="row">
                    <div class="offset-3 col-md-6">
                        <label for="nama_kelas" class="form-control-label">Nama Kelas</label>
                        <select name="kelas[]" id="list-kelas" class="form-control" multiple>
                            <option>-- Pilih Kelas --</option>
                            <?php while($kelas = result($sql_kelas)): ?>
                                <option value="<?= $kelas->id_kelas; ?>"><?= $kelas->nama_kelas; ?></option>
                            <?php endwhile; ?>
                        </select>
                        <br><br>

                        <label for="kelas" class="form-control-label">Mata Pelajaran</label>
                        <select name="mapel[]" id="list-mapel" class="form-control" required multiple>
                            <option>-- Pilih Mata Pelajaran --</option>
                            <?php while($mapel = result($sql_mapel)): ?>
                                <option value="<?= $mapel->kode_mapel; ?>"><?= $mapel->nama; ?></option>
                            <?php endwhile; ?>
                        </select>
                        <br><br>

                        <label for="jurusan" class="form-control-label">Guru Pengajar</label>
                        <select name="guru[]" id="list-guru" class="form-control" required multiple>
                            <option>-- Pilih Guru Pengajar --</option>
                            <?php while($guru = result($sql_guru)): ?>
                                <option value="<?= $guru->kode_guru; ?>"><?= $guru->nama; ?></option>
                            <?php endwhile; ?>
                        </select>
                        <br>
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
    $kelas = getPost('kelas');
    $mapel = getPost('mapel');
    $guru = getPost('guru');

    //Cek Kelas
    if(count($kelas) > 0) {

        if (count($mapel) != count($guru)) {
            alert("Data mata pelajaran dan guru pengajar tidak sama!", "back");
        } else {
            $values = "";
            for ($i = 0; $i < count($kelas); $i++) {
                for ($j = 0; $j < count($mapel); $j++) {

                    $sql_cek = select("*", "guru_mapel", "id_kelas = '$kelas[$i]' AND kode_mapel = '$mapel[$j]' AND kode_guru = '$guru[$j]'");
                    if(cekRow($sql_cek) <= 0) {
                        $values .= "('".escape($kelas[$i])."', '".escape($mapel[$j])."', '".escape($guru[$j])."')";

                        if($j != count($mapel) - 1) {
                            $values .= ",";
                        }
                    }

                }

                if(cekRow($sql_cek) <= 0) {
                    if($i != count($kelas) - 1) {
                        $values .= ",";
                    }                    
                }
            }

            if(substr($values, -1) === ',') {
                $length = strlen($values)-1;
                $values = substr($values, 0, $length);
            } else if(substr($values, 0,1) === ',') {
                $length = strlen($values);
                $values = substr($values, 1, $length);
            }

            //die($values);

            $queries = "INSERT INTO guru_mapel (id_kelas, kode_mapel, kode_guru) VALUES $values";
            $insert = execute($queries);

            if($insert) {
                alert("Data guru mata pelajaran berhasil disimpan!", base_url("admin/data-kelas"));
            } else {
                alert("Data guru mata pelajaran gagal disimpan!", "back");
            }            
        }


    }
}


?>