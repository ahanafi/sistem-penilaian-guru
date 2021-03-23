<?php
if(!cekSessionSiswa()) {
    redirect("welcome");
}
$id_kelas = getSession('KELAS_ID');
$kelas = result(select('*', "kelas", "id = '$id_kelas'"));

// SELECT nama as Guru FROM guru JOIN kelas_ajar ON guru.kode = kelas_ajar.kode_guru WHERE kelas_ajar.id_kelas = 2
//$guru_ajar = joinTable("nama AS nama_guru, kode_guru", "guru", "kelas_ajar", "kelas_ajar.kode_guru = guru.kode", "kelas_ajar.id_kelas = '$id_kelas'");
$guru_mapel = query("
        SELECT
            guru.nama AS guru,
            guru.kode AS kode_guru,
            mata_pelajaran.nama AS mapel,
            guru_mapel.id AS id_guru_mapel
        FROM
            guru_mapel
        JOIN guru ON guru_mapel.kode_guru = guru.kode
        JOIN mata_pelajaran ON guru_mapel.kode_mapel = mata_pelajaran.kode
        WHERE id_kelas = '$id_kelas'
    ");

$no = 0;
$kode_siswa = getSession('KODE_SISWA');
?>
<style type="text/css">
    .btn-group > a.btn{
        padding-top:10px;
        padding-bottom:10px;
        width: 50%;
    }
    .btn-group > a.btn:first-child{
        border-top-left-radius: 5rem;
        border-bottom-left-radius: 5rem;
    }
    .btn-group > a.btn:last-child {
        border-top-right-radius: 5rem;
        border-bottom-right-radius: 5rem;
    }
</style>
<div class="col-xl-10 col-lg-12 col-md-10">
    <div class="card o-hidden border-0 shadow-lg my-5">
        <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="p-5">
                        <div class="text-center text-gray-900">
                            <h1 class="h3 mb-3">Data Anda.</h1>
                        </div>
                        <table class="table table-bordered table-striped mb-4">
                            <tr>
                                <td>Nomor ID</td>
                                <td class="text-center">:</td>
                                <td><?php echo getSession('KODE_SISWA'); ?></td>
                            </tr>
                            <tr>
                                <td>NIS</td>
                                <td class="text-center">:</td>
                                <td><?php echo getSession('NIS'); ?></td>
                            </tr>
                            <tr>
                                <td>Nama Lengkap</td>
                                <td class="text-center">:</td>
                                <td><?php echo getSession('NAMA_SISWA'); ?></td>
                            </tr>
                            <tr>
                                <td>Kelas</td>
                                <td class="text-center">:</td>
                                <td><?php echo $kelas->nama_kelas; ?></td>
                            </tr>
                            <tr>
                                <td colspan="3" style="color: #000;" class="font-weight-bold">
                                    <strong class="text-danger">PERHATIAN :</strong>
                                    Data nilai yang sudah masuk <u><i><b>tidak dapat</b></i></u> di modifikasi.
                                    Jika sudah siap untuk menilai silahkan pilih <b>(Click)</b> nama guru yang akan dinilai.
                                </td>
                            </tr>
                        </table>

                        <div class="text-center text-gray-900">
                            <h1 class="h4 mb-3">Daftar Guru yang mengajar di kelas <?= $kelas->nama_kelas; ?></h1>
                        </div>
                        <table class="table table-bordered table-striped mb-4 table-responsive">
                            <thead>
                                <tr>
                                    <th class="text-center">No.</th>
                                    <th>Nama Guru</th>
                                    <th>Mata Pelajaran</th>
                                    <th class="text-center">Status Penilaian</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($gr = result($guru_mapel)): ?>
                                <?php $_SESSION['guru'][] = $gr->kode_guru; ?>
                                    <tr>
                                        <td class="text-center"><?php echo $no+1; ?></td>
                                        <td>
                                            <a href="<?php echo base_url("polling/section/".$_SESSION['guru'][$no]) ?>" class="link"><?php echo $gr->guru; ?></a>
                                        </td>
                                        <td>
                                            <?= $gr->mapel; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                                $sql_cek = select("*", "penilaian", "kode_guru = '$gr->kode_guru' AND kode_siswa = '$kode_siswa'");
                                                $ket = "";
                                                if (cekRow($sql_cek) > 0) { ?>
                                                <span class="badge badge-success">
                                                    OK
                                                </span>
                                            <?php } else { ?>
                                                <span class="badge badge-danger">
                                                    BELUM
                                                </span>
                                            <?php } $no++;?>
                                        </td>
                                    </tr>
                                <?php endwhile;
                                    $total_Guru = count($_SESSION['guru']);
                                    if(!isset($_SESSION['TOTAL_GURU']) && !isset($_SESSION['INDEX'])) {
                                        setSession("TOTAL_GURU", $total_Guru);
                                        setSession("INDEX", 0);
                                    }
                                ?>
                            </tbody>
                        </table>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="btn-group btn-block">
                                    <a onclick="return confirmLogout()" href="#" class="btn btn-danger">
                                        <i class="fa fa-power-off"></i>
                                        <span>Logout</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>