<?php
$guru_sql = select('*', "guru");
$no = 1;

$idx = 0;
$guru_arr = [];
while ($gr = result($guru_sql)) {
    $guru_arr[$idx]['nama'] = $gr->nama; 
    $guru_arr[$idx]['kode'] = $gr->kode; 
    $guru_arr[$idx]['id'] = $gr->id; 
    $idx++;
}

$page = getFrom("page");

$max_sql = select("SUM(nilai_max) AS max", "aspek_penilaian");
$max_nilai = result($max_sql);
?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-3">
        <h1 class="h3 mb-0 text-gray-800">Detail Penilaian</h1>
        <form action="<?php echo base_url("admin/".$page."/export") ?>" target="_blank" class="d-none d-sm-inline-block form-inline" method="POST">
            <button type="submit" name="export" class="btn btn-success" data-toggle="tooltip" title="Export <?= ucwords(str_replace("-", " ", $page)) ?>">
                <i class="fas fa-file-pdf"></i>
                <span class="text">Export PDF</span>
            </button>
        </form>
    </div>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Data Detail Penilaian</h6>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                <thead class="bg-dark text-white">
                    <tr>
                        <th class="align-middle">No.</th>
                        <th class="align-middle text-center">Nama Guru</th>
                        <th class="align-middle">Jumlah Nilai</th>
                        <th class="align-middle">Jumlah Responden</th>
                        <th class="align-middle">Presentase (%)</th>
                        <th class="align-middle">Keterangan</th>
                        <th class="align-middle">Deskripsi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($guru_arr as $g): ?>
                    <tr>
                        <td class="text-center"><?= $no++; ?></td>
                        <td>
                            <a href="<?= base_url("admin/detail-penilaian/".$g['kode']) ?>" target="_blank"><?= $g['nama'] ?></a>
                        </td>
                        <td class="text-center font-weight-bold">
                            <?php
                                $jum_nilai = 0;
                                $sql_nilai = select("SUM(nilai) AS jumlah_nilai", "penilaian", "kode_guru = '$g[kode]'");
                                $nilai = result($sql_nilai);
                                $jum_nilai = ($nilai->jumlah_nilai) ? $nilai->jumlah_nilai : 0;
                                echo $jum_nilai;
                            ?>
                        </td>
                        <td class="text-center font-weight-bold">
                            <?php

                                $sql_res = select("COUNT(DISTINCT(kode_siswa)) as jumlah_siswa", "penilaian", "kode_guru = '$g[kode]'");
                                $res = result($sql_res);
                                $jum_siswa = ($res->jumlah_siswa) ? $res->jumlah_siswa : 0;
                                echo $jum_siswa;
                            ?>
                        </td>
                        <td class="text-center font-weight-bold">
                            <?php
                                $presentase = ($jum_nilai > 0 && $jum_siswa >0 ) ? number_format(($jum_nilai / $jum_siswa), 2) : 0;
                                $presentase = number_format((($presentase/$max_nilai->max) * 100), 2);
                                echo $presentase;
                            ?>                            
                        </td>
                        <td class="text-center font-weight-bold">
                            <?php $ket = getKeterangan($presentase); echo $ket; ?>
                        </td>
                        <td class="text-center font-weight-bold">
                            <?php echo getDeskripsi($ket); ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>
<!-- /.container-fluid -->