<?php
$kode_siswa = getFrom('kode-siswa');
$siswa_sql = joinTable("siswa.nama, siswa.jk, siswa.nis, siswa.kode, siswa.id_kelas, kelas.nama_kelas", "siswa", "kelas", "siswa.id_kelas = kelas.id", "siswa.kode = '$kode_siswa'");
$sw = result($siswa_sql);

$guru_ajar = joinTable("nama, guru.id, guru.kode", "guru", "guru_mapel", "guru.kode = guru_mapel.kode_guru", "guru_mapel.id_kelas = '$sw->id_kelas'");
$no = 1;
?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Data Responden</h1>
    <!-- DataTales Example -->
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Data Siswa</h6>
                </div>
                <div class="card-body mb-4">
                    <table class="table table-bordered table-striped">
                        <tr>
                            <td style="width: 240px;">Nama Lengkap</td>
                            <td style="width: 50px;">:</td>
                            <td><?php echo $sw->nama; ?></td>
                        </tr>
                        <tr>
                            <td>Nomor Induk Siswa</td>
                            <td>:</td>
                            <td><?php echo $sw->nis; ?></td>
                        </tr>
                        <tr>
                            <td>Nomor ID</td>
                            <td>:</td>
                            <td><?php echo $sw->kode; ?></td>
                        </tr>
                        <tr>
                            <td>Kelas</td>
                            <td>:</td>
                            <td><?php echo $sw->nama_kelas; ?></td>
                        </tr>
                        <tr>
                            <td>Jenis Kelamin</td>
                            <td>:</td>
                            <td><?php echo ($sw->jk == "L") ? "Laki-laki" : "Perempuan"; ?></td>
                        </tr>
                    </table>
                    <a href="<?php echo base_url("admin/responden") ?>" class="btn btn-secondary">
                        Kembali
                    </a>
                </div>
            </div>
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Daftar Guru Pengajar</h6>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th class="text-center">No.</th>
                                <th>Kode Guru</th>
                                <th>Nama Guru</th>
                                <th class="text-center">Status Penilaian</th>
                                <th class="text-center">Total Item Nilai</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($gr = result($guru_ajar)): ?>
                            <tr>
                                <td class="text-center"><?php echo $no++; ?></td>
                                <td class="text-center"><?php echo $gr->kode; ?></td>
                                <td><?php echo $gr->nama; ?></td>
                                <td class="text-center">
                                    <?php
                                        $sql_cek_nilai = select("*", "penilaian", "kode_siswa = '$kode_siswa' AND kode_guru = '$gr->kode'");
                                        $cek_nilai = cekRow($sql_cek_nilai);
                                        if ($cek_nilai == 9) :
                                    ?>
                                        <span class="badge badge-success p-2">SUDAH</span>
                                    <?php else: ?>
                                        <span class="badge badge-danger p-2">BELUM</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php echo $cek_nilai; ?>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- /.container-fluid -->