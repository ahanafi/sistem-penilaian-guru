<?php
$guru_sql = select('*', "guru", "kode != '' ORDER BY nama ASC");
$no = 1;
$page = getFrom('page');
?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-3">
        <h1 class="h3 mb-0 text-gray-800">Data Guru</h1>
        <div class="btn-group-sm">
            <a href="<?php echo base_url("admin/".$page."/create") ?>" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" data-toggle="tooltip" title="Tambah <?= ucwords(str_replace("-", " ", $page)) ?>">
                <i class="fas fa-plus text-white-50"></i>
                <span class="text">Tambah Data</span>
            </a>
            <a href="<?php echo base_url("admin/".$page."/import") ?>" class="d-none d-sm-inline-block btn btn-sm btn-info shadow-sm" data-toggle="tooltip" title="Import <?= ucwords(str_replace("-", " ", $page)) ?>">
                <i class="fas fa-download text-white-50"></i>
                <span class="text">Import Data</span>
            </a>
        </div>
    </div>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Data Guru</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nomor ID</th>
                            <th>Nama</th>
                            <th>JK</th>
                            <th>Alamat</th>
                            <th>Jumlah kelas yang diajar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($g = result($guru_sql)): ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= $g->kode ?></td>
                            <td><?= $g->nama ?></td>
                            <td><?= $g->jk ?></td>
                            <td><?= $g->alamat ?></td>
                            <td class="text-center">
                                <?php
                                    $sql_count = select("COUNT(*) AS total", "guru_mapel", "kode_guru = '$g->kode'");
                                    $count = result($sql_count);
                                    echo $count->total;
                                ?>
                            </td>
                            <td>
                                <a href="<?php echo base_url("admin/data-guru/edit/".$g->id) ?>" class="btn btn-primary btn-circle">
                                    <i class="fas fa-pencil-alt"></i>
                                </a>
                                <a href="<?php echo base_url("admin/data-guru/delete/".$g->id) ?>" class="btn btn-danger btn-circle">
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