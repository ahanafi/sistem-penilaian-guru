<?php
global $page;
global $action;

$id = getSessionUser('id');
$user = select("*", "users", "id = $id");
$user   = result($user);

?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Ubah Password</h1>
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Ubah Password User</h6>
        </div>
        <div class="card-body mb-4">
            <form action="<?php echo base_url("admin/$page/$action") ?>" method="POST">
                <div class="row">
                    <div class="offset-3 col-md-6">
                        <label for="password_lama" class="form-control-label">Password Lama</label>
                        <input type="password" name="password_lama" class="form-control" required placeholder="Password Lama">
                        <br>

                        <label for="password_baru" class="form-control-label">Password Baru</label>
                        <input type="password" name="password_baru" class="form-control" required placeholder="Password Baru">
                        <br>

                        <label for="konfirmasi_password" class="form-control-label">Konfirmasi Password Baru</label>
                        <input type="password" name="konfirmasi_password" class="form-control" required placeholder="Konfirmasi Password Baru">
                        <br>

                        <div class="row">
                            <div class="col-md-6">
                                <button class="btn btn-primary btn-block" type="submit" name="submit">
                                    <span>Simpan</span>
                                </button>
                            </div>
                            <div class="col-md-6">
                                <a href="<?php echo base_url("admin/dashboard") ?>" class="btn btn-secondary btn-block">
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
    $password_lama = getPost('password_lama');
    $password_baru = getPost('password_baru');
    $konfirmasi_password = getPost('konfirmasi_password');

    //when all input is not empty
    if(!empty(trim($password_lama)) && !empty(trim($password_baru)) && !empty(trim($konfirmasi_password))) {

        //verify old password
        if(password_verify($password_lama, $user->password)) {

            if($password_baru === $konfirmasi_password) {
                //Set new password
                $password = password_hash($password_baru, PASSWORD_DEFAULT);

                //Update password
                $update = update("users", "password = '$password'", $id);

                if($update) {
                    alert("Password user berhasil diganti!", "back");
                } else {
                    alert("Password user gagal diganti!", "back");
                }
            } else {
               alert("Konfirmasi password tidak sama dengan password baru!", "back");
                exit; 
            }

        } else {
            alert("Password lama yang Anda masukkan salah!", "back");
            exit;
        }
    } else {
        alert("Form tidak boleh ada yang kosong!", "back");
    }
}


?>