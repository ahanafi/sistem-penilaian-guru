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
$list_kelas = select("*", "kelas");
$aspek_sql = select("*", "aspek_penilaian");
$page = getFrom("page");

$max_sql = select("SUM(nilai_max) AS max", "aspek_penilaian");
$max_nilai = result($max_sql);
?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-3">
        <h1 class="h3 mb-0 text-gray-800">Hasil Penilaian</h1>
        <form action="<?php echo base_url("admin/".$page."/export") ?>" target="_blank" class="d-none d-sm-inline-block form-inline" method="POST">
            <div class="input-group">
                <select name="kelas" id="" class="form-control">
                    <option value="all">Semua Kelas</option>
                    <?php while($kls = result($list_kelas)): ?>
                        <option value="<?php echo $kls->id; ?>"><?php echo $kls->nama_kelas; ?></option>
                    <?php endwhile; ?>
                </select>
                <div class="input-group-append">
                    <button type="submit" name="export" class="btn btn-success" data-toggle="tooltip" title="Export <?= ucwords(str_replace("-", " ", $page)) ?>">
                        <i class="fas fa-file-pdf"></i>
                        <span class="text">Export PDF</span>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Data Hasil Penilaian</h6>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                <thead class="bg-dark text-white">
                    <tr>
                        <th rowspan="2" class="align-middle">No.</th>
                        <th rowspan="2" class="align-middle text-center">Nama Guru</th>
                        <th colspan="9" class="text-center">Aspek Penilaian</th>
                        <th rowspan="2" class="align-middle">Total Nilai (%)</th>
                    </tr>
                    <tr>
                        <?php
                            $aspek_arr = [];
                            $index = 0;
                            while($ap = result($aspek_sql)): ?>
                            <th>
                                <?php
                                    echo $ap->nama_indikator;
                                    $aspek_arr[$index]['id'] = $ap->id;
                                    $index++;
                                ?>
                            </th>
                        <?php
                            endwhile;
                            $total_nilai = 0;
                        ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($guru_arr as $g): ?>
                    <tr>
                        <td class="text-center"><?= $no++; ?></td>
                        <td><?= $g['nama'] ?></td>
                        <?php foreach($aspek_arr as $as): ?>
                            <td class="text-center">
                                <?php
                                    $sql_cek = select("SUM(nilai) as nilai", "penilaian", "kode_guru = '$g[kode]' AND id_aspek_pn = '$as[id]'");

                                    $nilai = result($sql_cek);
                                    $nilai_aspek = 0;
                                    if($nilai->nilai) {
                                        $total_nilai = $total_nilai + $nilai->nilai;
                                        $nilai_aspek = $nilai->nilai;
                                    }

                                    echo $nilai_aspek;
                                ?>
                            </td>
                        <?php endforeach; ?>
                        <td class="text-center font-weight-bold">
                            <?php
                                if($total_nilai > 0) {
                                    $sql_hitung_siswa = select("COUNT(DISTINCT(kode_siswa)) as jm_siswa", "penilaian", "kode_guru = '$g[kode]'");
                                    $hitung_siswa = result($sql_hitung_siswa);

                                    //NILAI = (TOTAL_NILAI / JUMLAH_SISWA) / 45

                                    $total_nilai = ($total_nilai / $hitung_siswa->jm_siswa);
                                    $total_nilai = number_format((($total_nilai/$max_nilai->max) * 100), 2);
                                }
                                echo number_format($total_nilai, 2);
                                $total_nilai = 0;
                            ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>
<!-- /.container-fluid -->