<?php
$responden_sql = query("SELECT DISTINCT(kode_siswa) as kode_siswa, siswa.nama AS nama_siswa, siswa.nis AS nis, siswa.jk AS jk, kelas.id AS id_kelas, kelas.nama_kelas AS nama_kelas FROM penilaian JOIN siswa ON penilaian.kode_siswa = siswa.kode JOIN kelas ON siswa.id_kelas = kelas.id");
//$responden_sql = query("SELECT DISTINCT(kode_siswa), siswa.nis, siswa.nama as nama_siswa, kelas.nama_kelas, siswa.jk AS jk, kelas.id AS id_kelas FROM `penilaian` JOIN siswa ON siswa.kode = penilaian.kode_siswa JOIN kelas ON kelas.id = siswa.id_kelas ORDER BY penilaian.id DESC");
$no = 1;
$page = getFrom('page');
?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-3">
        <h1 class="h3 mb-0 text-gray-800">Data Responden</h1>
        <div class="btn-group-sm">
            <a href="<?php echo base_url("admin/".$page."/not-responding") ?>" class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm" data-toggle="tooltip" title="Tampilkan yang tidak merespon">
                <i class="fas fa-eye text-white-50"></i>
                <span class="text">Show Not Responding</span>
            </a>
        </div>
    </div>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Data Reponden</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nomor ID</th>
                            <th>NIS</th>
                            <th>Nama</th>
                            <th>Kelas</th>
                            <th>Jum. Pengajar</th>
                            <th>Guru yg dinilai</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($g = result($responden_sql)): ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= $g->kode_siswa ?></td>
                            <td><?= $g->nis ?></td>
                            <td><?= $g->nama_siswa ?></td>
                            <td>
                                <a href="<?php echo base_url("admin/data-kelas/detail/".$g->id_kelas) ?>"><?= $g->nama_kelas ?></a>
                            </td>
                            <td class="text-center">
                                <?php
                                    $sql_count_guru_ajar = select("*", "kelas_ajar", "id_kelas = '$g->id_kelas'");
                                    $guru_ajar = cekRow($sql_count_guru_ajar);
                                    echo $guru_ajar;

                                    $sql_cek_penilaian = select("COUNT(DISTINCT(kode_guru)) AS total_guru", "penilaian", "kode_siswa = '$g->kode_siswa'");
                                    $cek_penilaian = result($sql_cek_penilaian);
                                    $warn = "";
                                    if($guru_ajar > $cek_penilaian->total_guru) {
                                        $warn = "bg-warning";
                                    }
                                ?>
                            </td>
                            <td class="text-center <?= $warn ?>">
                                <?php
                                    echo $cek_penilaian->total_guru;
                                ?>
                            </td>
                            <td>
                                <a href="<?php echo base_url("admin/responden/detail/".$g->kode_siswa) ?>" class="btn btn-primary btn-circle" data-toggle="tooltip" title="Detail Penilaian">
                                    <i class="fa fa-eye"></i>
                                </a>
                                <a onclick="return confirmDelete()" href="<?php echo base_url("admin/responden/delete/".$g->kode_siswa) ?>" class="btn btn-danger btn-circle" data-toggle="tooltip" title="Hapus Data">
                                    <i class="fas fa-trash"></i>
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
<!-- /.container-fluid -->