<?php
$id = getFrom('id');
$kelas = select("*", "kelas", "id = '$id'");
$kls = result($kelas);

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
$no = 1;

$sql_mapel = select("*", "mata_pelajaran");

$sql_guru = query("SELECT * FROM guru ORDER BY nama");
$sql_mapel = query("SELECT * FROM mata_pelajaran WHERE kode NOT IN (
        SELECT kode_mapel FROM guru_mapel WHERE id_kelas = '$id'
    )
");

if (isset($_POST['add-mapel']) && $_SERVER['REQUEST_METHOD'] == "POST") {
    $kode_guru = getPost('guru_kode');
    $kode_mapel = getPost('mapel_kode');
    $id_kelas = getPost('id_kelas');

    if(!empty(trim($kode_guru)) && !empty(trim($kode_mapel)) && !empty(trim($id_kelas))) {
        $insert = insert("guru_mapel", "id_kelas, kode_guru, kode_mapel", "'$id_kelas', '$kode_guru', '$kode_mapel'");

        if($insert) {
            alert("Data guru pengajar kelas berhasil ditambahkan!", base_url("admin/data-kelas/set-mapel/".$id_kelas));
        } else {
            alert("Data guru pengajar kelas gagal ditambahkan!", "back");
        }
    } else {
        alert("Form tidak boleh ada yang kosong!", "back");
    }
}

?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Data Kelas</h1>
    <!-- DataTales Example -->
    <div class="row">
        <div class="col-md-6">
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
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header py3">
                    <h6 class="m-0 font-weight-bold text-primary">Form Tambah Pengajar Kelas</h6>
                </div>
                <div class="card-body">
                    <form action="<?php echo base_url("admin/data-kelas/set-mapel/".$kls->id) ?>" method="POST">
                        <div class="form-group">
                            <label for="">Mata Pelajaran</label>
                            <select name="mapel_kode" id="list-kelas" class="form-control" required autofocus>
                                <option>-- Pilih Mata Pelajaran --</option>
                                <?php while($mapel = result($sql_mapel)): ?>
                                    <option value="<?php echo $mapel->kode ?>"><?php echo $mapel->nama; ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">Pilih Guru Pengajar</label>
                            <select name="guru_kode" id="list-guru" class="form-control" required>
                                <option>-- Pilih Guru --</option>
                                <?php while($gr = result($sql_guru)): ?>
                                    <option value="<?php echo $gr->kode ?>"><?php echo $gr->nama; ?></option>
                                <?php endwhile; ?>
                            </select>
                            <input type="hidden" name="id_kelas" value="<?php echo $kls->id; ?>">
                        </div>
                        <div class="form-group">
                            <button class="btn btn-primary btn-block" type="submit" name="add-mapel">
                                <i class="fas fa-plus"></i>
                                <span>Tambahkan</span>
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row" style="margin-top: 20px;">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Daftar Guru Mata Pelajaran</h6>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped" id="dataTable">
                        <thead>
                            <tr>
                                <th class="text-center">No.</th>
                                <th>Nama Guru</th>
                                <th>Mata Pelajaran</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($gr = result($guru_mapel)): ?>
                            <tr>
                                <td class="text-center"><?php echo $no++; ?></td>
                                <td><?php echo $gr->guru; ?></td>
                                <td><?php echo $gr->mapel; ?></td>
                                <td class="text-center">
                                    <a onclick="return confirmDelete()" href="<?php echo base_url("admin/data-kelas/hapus-pengajar/".$gr->id_guru_mapel) ?>" class="btn btn-danger btn-circle" data-toggle="tooltip" title="Hapus data pengajar">
                                        <i class="fa fa-trash"></i>
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