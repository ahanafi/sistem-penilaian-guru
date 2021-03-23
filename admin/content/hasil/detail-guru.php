<?php
$get_kode_guru = isset($_GET['kode-guru']) ? $_GET['kode-guru'] : "";

$guru_sql = select('*', "guru", "kode = '$get_kode_guru'");
$guru = result($guru_sql);
$no = 1;

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
        <form action="<?php echo base_url("admin/detail-penilaian-guru/export/".$get_kode_guru) ?>" target="_blank" class="d-none d-sm-inline-block form-inline" method="POST">
            <input type="hidden" name="kode_guru" value="<?= $get_kode_guru; ?>">
            <button type="submit" name="export" class="btn btn-success" data-toggle="tooltip" title="Export <?= ucwords(str_replace("-", " ", $page)) ?>">
                <i class="fas fa-file-pdf"></i>
                <span class="text">Export PDF</span>
            </button>
        </form>
    </div>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Data Hasil Penilaian</h6>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <tr>
                    <td>ID Guru</td>
                    <td>:</td>
                    <td><?= $guru->kode; ?></td>
                </tr>
                <tr>
                    <td>Nama Guru</td>
                    <td>:</td>
                    <td><?= $guru->nama; ?></td>
                </tr>
                <tr>
                    <td>Jenis Kelamin</td>
                    <td>:</td>
                    <td><?= ($guru->jk == "L") ? "Laki-Laki" : "Perempuan"; ?></td>
                </tr>
                <tr>
                    <td>Jumlah Kelas yang diajar</td>
                    <td>:</td>
                    <td>
                        <?php
                            $sql_jum_kelas = select("*", "kelas_ajar", "kode_guru = '$get_kode_guru'");
                            echo cekRow($sql_jum_kelas);
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>Jumlah Responden</td>
                    <td>:</td>
                    <td>
                         <?php
                            $sql_jum_responden = select("DISTINCT(kode_siswa)", "penilaian", "kode_guru = '$get_kode_guru'");
                            $responden = cekRow($sql_jum_responden);
                            echo $responden;
                        ?> siswa
                    </td>
                </tr>
                <tr>
                    <td>Jumlah Nilai</td>
                    <td>:</td>
                    <td>
                         <?php
                            $sql_nilai = select("SUM(nilai) AS nilai", "penilaian", "kode_guru = '$get_kode_guru'");
                            $jum_nilai = result($sql_nilai);
                            echo str_replace(",", ".", number_format($jum_nilai->nilai));
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>Presentase Nilai</td>
                    <td>:</td>
                    <td>
                         <?php
                            //to get max nilai
                            $max_sql = select("SUM(nilai_max) AS max", "aspek_penilaian");
                            $max_nilai = result($max_sql);

                            $presentase = ($jum_nilai->nilai) ? number_format($jum_nilai->nilai / $responden, 2) : 0;
                            $presentase = number_format((($presentase/$max_nilai->max) * 100), 2);
                            echo $presentase . "%";
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>Keterangan</td>
                    <td>:</td>
                    <td><b>
                         <?php
                            $keterangan = getKeterangan($presentase);
                            echo getDeskripsi($keterangan);
                        ?></b>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Detail Nilai per Aspek</h6>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama Aspek</th>
                        <th class="text-center">Jumlah Nilai</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($nl = result($aspek_sql)): ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= $nl->nama_indikator; ?></td>
                            <td class="text-center font-weight-bold">
                                <?php
                                    $sql_nilai_aspek = select("SUM(nilai) AS nilai", "penilaian", "id_aspek_pn = '$nl->id' AND kode_guru = '$guru->kode'");
                                    $nilai_aspek = result($sql_nilai_aspek);
                                    echo $nilai_aspek->nilai;
                                ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                    <tr>
                        <td colspan="2" class="text-center font-weight-bold">TOTAL</td>
                        <td class="text-center font-weight-bold"><?= str_replace(",", ".", number_format($jum_nilai->nilai)); ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>



</div>
<!-- /.container-fluid -->