<?php
$responden_sql = query("
        SELECT
            siswa.*, siswa.nama AS nama_siswa, kelas.nama_kelas AS nama_kelas
        FROM siswa JOIN kelas ON siswa.id_kelas = kelas.id
        WHERE siswa.kode NOT IN (
            SELECT DISTINCT(kode_siswa) FROM penilaian
        )
    ");

$selected_kelas = getFrom('kelas');
$selected_jenjang = getFrom('jenjang');

if($selected_kelas != '') {
    $selected_kelas = str_replace("-", " ", $selected_kelas);
    $responden_sql = query("
        SELECT
            siswa.*, siswa.nama AS nama_siswa, kelas.nama_kelas AS nama_kelas
        FROM siswa JOIN kelas ON siswa.id_kelas = kelas.id
        WHERE nama_kelas = '$selected_kelas' AND siswa.kode NOT IN (
            SELECT DISTINCT(kode_siswa) FROM penilaian
        )
    ");
}

if($selected_jenjang != '') {
    $responden_sql = query("
        SELECT
            siswa.*, siswa.nama AS nama_siswa, kelas.nama_kelas AS nama_kelas, kelas.kelas AS jenjang_kelas
        FROM siswa JOIN kelas ON siswa.id_kelas = kelas.id
        WHERE kelas = '$selected_jenjang' AND siswa.kode NOT IN (
            SELECT DISTINCT(kode_siswa) FROM penilaian
        )
    ");
}


$list_kelas = select("*", "kelas");
$no = 1;
$page = getFrom('page');
$action = getFrom('action');
?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-3">
        <h1 class="h3 mb-0 text-gray-800">Data Siswa yang belum melakukan penilaian</h1>
        <div class="d-none d-sm-inline-block form-inline">
            <select id="filter_kelas" class="form-control">
                <option value="">Semua Kelas</option>
                <?php while($kls = result($list_kelas)): ?>
                    <?php if (str_replace("-", " ", $selected_kelas) == strtolower($kls->nama_kelas)): ?>
                        <option value="<?php echo strtolower(str_replace(" ", "-", $kls->nama_kelas)); ?>" selected><?php echo $kls->nama_kelas; ?></option>
                    <?php else: ?>
                        <option value="<?php echo strtolower(str_replace(" ", "-", $kls->nama_kelas)); ?>"><?php echo $kls->nama_kelas; ?></option>
                    <?php endif ?>
                <?php endwhile; ?>
            </select>
            <select id="filter_jenjang" class="form-control">
                <option value="">Semua Jenjang</option>
                <option value="10" <?= ($selected_jenjang == 10) ? 'selected' : '' ?>>10</option>
                <option value="11" <?= ($selected_jenjang == 11) ? 'selected' : '' ?>>11</option>
                <option value="12" <?= ($selected_jenjang == 12) ? 'selected' : '' ?>>12</option>
            </select>
            <a href="<?php echo base_url("admin/".$page) ?>" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm" data-toggle="tooltip" title="Tampilkan responden">
                <i class="fas fa-eye text-white-50"></i>
                <span class="text">Lihat Responden</span>
            </a>
            
        </div>
    </div>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Data Siswa</h6>
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
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($g = result($responden_sql)): ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= $g->kode ?></td>
                            <td><?= $g->nis ?></td>
                            <td><?= $g->nama_siswa ?></td>
                            <td>
                                <a href="<?php echo base_url("admin/data-kelas/detail/".$g->id_kelas) ?>"><?= $g->nama_kelas ?></a>
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