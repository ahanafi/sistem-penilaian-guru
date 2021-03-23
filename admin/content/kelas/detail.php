<?php
$id = getFrom('id');
$kelas = select("*", "kelas", "id = '$id'");
$kls = result($kelas);

$guru_ajar = joinTable("nama, guru.id", "guru", "kelas_ajar", "guru.kode = kelas_ajar.kode_guru", "kelas_ajar.id_kelas = '$id'");
$no = 1;

$guru_mapel = query("
        SELECT
            guru.nama AS guru,
            mata_pelajaran.nama AS mapel,
            guru_mapel.id AS id_guru_mapel
        FROM
            guru_mapel
        JOIN guru ON guru_mapel.kode_guru = guru.kode
        JOIN mata_pelajaran ON guru_mapel.kode_mapel = mata_pelajaran.kode
        WHERE id_kelas = '$kls->id'
    ");

$siswa_kelas = select("*", "siswa", "id_kelas = '$id'");
$jumlah_siswa = cekRow($siswa_kelas);
?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Data Kelas</h1>
    <!-- DataTales Example -->
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Detail Data Kelas</h6>
                </div>
                <div class="card-body mb-4">
                    <table class="table table-bordered table-striped">
                        <tr>
                            <td>Nama Kelas</td>
                            <td>:</td>
                            <td><?php echo $kls->nama_kelas; ?></td>
                        </tr>
                        <tr>
                            <td>Jumlah Siswa</td>
                            <td>:</td>
                            <td><?php echo $jumlah_siswa; ?></td>
                        </tr>
                        <tr>
                            <td>Kelas (Jenjang)</td>
                            <td>:</td>
                            <td><?php echo $kls->kelas; ?></td>
                        </tr>
                        <tr>
                            <td>Komp. Keahlian (Jurusan)</td>
                            <td>:</td>
                            <td><?php echo $kls->jurusan; ?></td>
                        </tr>
                    </table>
                    <a href="<?php echo base_url("admin/data-kelas") ?>" class="btn btn-secondary">
                        Kembali
                    </a>
                </div>
            </div>
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Daftar Guru Pengajar</h6>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped" id="data">
                        <thead>
                            <tr>
                                <th class="text-center">No.</th>
                                <th>Nama Guru</th>
                                <th>Mata Pelajaran</th>
                                <!-- <th class="text-center">Aksi</th> -->
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($gr = result($guru_mapel)): ?>
                            <tr>
                                <td class="text-center"><?php echo $no++; ?></td>
                                <td><?php echo $gr->guru; ?></td>
                                <td><?php echo $gr->mapel; ?></td>
                                <!-- <td class="text-center">
                                    <a href="<?php echo base_url("admin/data-guru/edit/".$gr->id) ?>" class="btn btn-sm btn-info">
                                        <i class="fa fa-eye"></i>
                                        <span>Detail</span>
                                    </a>
                                </td> -->
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Daftar Siswa</h6>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped" id="data2">
                        <thead>
                            <tr>
                                <th class="text-center">No.</th>
                                <th class="text-center">NIS</th>
                                <th>Nama Siswa</th>
                                <th class="text-center">JK</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; while($std = result($siswa_kelas)): ?>
                            <tr>
                                <td class="text-center"><?php echo $no++; ?></td>
                                <td><?php echo $std->nis; ?></td>
                                <td><?php echo $std->nama; ?></td>
                                <td><?php echo $std->jk; ?></td>
                                <td class="text-center">
                                    <a href="<?php echo base_url("admin/data-siswa/edit/".$std->id) ?>" class="btn btn-sm btn-info">
                                        <i class="fa fa-eye"></i>
                                        <span>Detail</span>
                                    </a>
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