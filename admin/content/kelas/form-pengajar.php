<?php
$id = getFrom('id');
$kelas = select("*", "kelas", "id = '$id'");
$kls = result($kelas);

$guru_ajar = joinTable("nama, guru.id, kelas_ajar.id AS ka_id", "guru", "kelas_ajar", "guru.kode = kelas_ajar.kode_guru", "kelas_ajar.id_kelas = '$id'");
$no = 1;

$sql_guru = query("SELECT * FROM guru WHERE kode NOT IN (SELECT kode_guru FROM kelas_ajar WHERE id_kelas = '$id')");

if (isset($_POST['add-teacher']) && $_SERVER['REQUEST_METHOD'] == "POST") {
    $guru_ajar_baru = getPost('guru_ajar');
    $total_guru = count($guru_ajar_baru);
    $id_kelas = getPost('id_kelas');

    $isExists = FALSE;
    for ($i=0; $i < $total_guru; $i++) { 
        $cek_guru = select("*", "kelas_ajar", "kode_guru = '$guru_ajar_baru[$i]' AND id_kelas = '$id_kelas'");
        if(cekRow($cek_guru) > 0) {
            alert("Guru tersebut telah ada di data pengajar kelas ini!", "back");
            $isExists = TRUE;
            exit;
        }
    }

    if ($isExists != TRUE) {
        for ($x=0; $x < $total_guru; $x++) { 
            $insert = insert("kelas_ajar", "kode_guru, id_kelas", "'$guru_ajar_baru[$x]', '$id_kelas'");
        }

        if($insert) {
            alert("Data guru pengajar kelas berhasil ditambahkan!", base_url("admin/data-kelas/tambah-pengajar/".$id_kelas));
        } else {
            alert("Data guru pengajar kelas gagal ditambahkan!", "back");
        }
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
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Daftar Guru Pengajar</h6>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped" id="data">
                        <thead>
                            <tr>
                                <th class="text-center">No.</th>
                                <th>Nama Guru</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($gr = result($guru_ajar)): ?>
                            <tr>
                                <td class="text-center"><?php echo $no++; ?></td>
                                <td><?php echo $gr->nama; ?></td>
                                <td class="text-center">
                                    <a onclick="return confirmDelete()" href="<?php echo base_url("admin/data-kelas/hapus-pengajar/".$gr->ka_id) ?>" class="btn btn-danger btn-circle" data-toggle="tooltip" title="Hapus data pengajar">
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
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header py3">
                    <h6 class="m-0 font-weight-bold text-primary">Form Tambah Pengajar Kelas</h6>
                </div>
                <div class="card-body">
                    <form action="<?php echo base_url("admin/data-kelas/tambah-pengajar/".$kls->id) ?>" method="POST">
                        <div class="form-group">
                            <label for="">Pilih Guru Pengajar</label>
                            <select name="guru_ajar[]" multiple id="list-kelas" class="form-control" required>
                                <?php while($gr = result($sql_guru)): ?>
                                    <option value="<?php echo $gr->kode ?>"><?php echo $gr->nama; ?></option>
                                <?php endwhile; ?>
                            </select>
                            <input type="hidden" name="id_kelas" value="<?php echo $kls->id; ?>">
                        </div>
                        <div class="form-group">
                            <button class="btn btn-primary btn-block" type="submit" name="add-teacher">
                                <i class="fas fa-plus"></i>
                                <span>Tambahkan</span>
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- /.container-fluid -->