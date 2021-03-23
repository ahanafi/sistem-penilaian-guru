<?php
global $page;
global $action;
?>
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Manajemen User</h1>
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Tambah User Baru</h6>
        </div>
        <div class="card-body mb-4">
            <form action="<?php echo base_url("admin/$page/create") ?>" method="POST">
                <div class="row">
                    <div class="offset-3 col-md-6">
                        <label for="fullname" class="form-control-label">Nama Lengkap</label>
                        <input type="text" name="fullname" class="form-control" required placeholder="Nama Lengkap">
                        <br>

                        <label for="kelas" class="form-control-label">Username</label>
                        <input type="text" name="username" class="form-control" required placeholder="Username">
                        <br>

                        <label for="password" class="form-control-label">Password</label>
                        <input type="password" name="password" class="form-control" required placeholder="Password">
                        <br>

                        <label for="level">Level (Tipe User)</label>
                        <select name="level" required class="form-control">
                            <option>-- Pilih Level --</option>
                            <option value="1">Adminstrator</option>
                            <option value="2">Operator</option>
                        </select>
                        <br>

                        <div class="row">
                            <div class="col-md-6">
                                <button class="btn btn-primary btn-block" type="submit" name="submit">
                                    <span>Simpan</span>
                                </button>
                            </div>
                            <div class="col-md-6">
                                <a href="<?php echo base_url("admin/$page") ?>" class="btn btn-secondary btn-block">
                                    <span>Kembali</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>
<!-- /.container-fluid -->

<?php

if (isset($_POST['submit'])) {
    $fullname = getPost('fullname');
    $username = getPost('username');
    $password = getPost('password');
    $level = getPost('level');

    if(!empty(trim($fullname)) && !empty(trim($username)) && !empty(trim($password)) && !empty(trim($level)) && $level != 0) {
        $password = password_hash($password, PASSWORD_DEFAULT);

        $insert= insert("users", "fullname, username, password, level", "'$fullname', '$username', '$password', $level");

        if($insert) {
            alert("Data pengguna baru berhasil disimpan!", base_url("admin/$page"));
        } else {
            alert("Data pengguna baru gagal disimpan!", "back");
        }
    } else {
        alert("Form tidak boleh ada yang kosong!", "back");
    }
}


?>