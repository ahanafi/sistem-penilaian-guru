<?php
//require_once("vendor/autoload.php");
require_once("core/init.php");

$kelas_sql = select("*", "kelas");
$page = getFrom('page');
$action = getFrom('action');

$pengaturan = select("*", "pengaturan", "id = 1 LIMIT 1");
$pengaturan = result($pengaturan);

$kompetensi = select("*", "kelas");


use Phelium\Component\reCAPTCHA;


$site_key = getenv('RECAPTCHA_SITE_KEY');
$secret_key = getenv('RECAPTCHA_SECRET_KEY');
$reCAPTCHA = new reCAPTCHA($site_key, $secret_key);

if($page != '' && $page != 'welcome' && !cekSessionSiswa()) {
    redirect(base_url("welcome"));
}


?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Welcome page - SKP System.</title>

    <!-- Custom fonts for this template-->
    <link href="<?php echo base_url("assets/vendor/fontawesome-free/css/all.min.css") ?>" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="<?php echo base_url("assets/css/sb-admin-2.min.css") ?>" rel="stylesheet">
    <link rel="shorcut icon" href="<?= base_url("assets/img/logo.png") ?>">
    <link rel="stylesheet" href="<?= base_url("assets/vendor/sweetalert2/sweetalert2.min.css") ?>">

</head>
<style type="text/css">
.form-control{
    font-size: 1.2rem  !important;
    padding: 0.75rem !important;
    height:auto !important;
    text-align: center;
    text-align-last:center;
}

.btn-user{
    font-size: 1.1rem !important;
    text-transform: uppercase;
}
</style>
<body class="bg-gradient-primary">

    <div class="container">

        <!-- Outer Row -->
        <div
            class="row justify-content-center mt-5"
            <?php if($page == "" || $page == "last"): ?>
            style="margin-top: 10px !important;"
            <?php endif; ?>
        >

            <?php
                if($page == "" || empty($page)):
                    if (cekSessionSiswa()) {
                        redirect("landing");
                    } elseif (isset($_SESSION['START_POLLING']) && $_SESSION['START_POLLING'] == TRUE) {
                        redirect(base_url("polling/section/".$_SESSION['guru'][getSession('INDEX')]));
                    }
                    getTemplate("welcome-page");
                elseif($page == "landing" && cekSessionSiswa()):
                    if(!cekSessionSiswa()) {
                        redirect(base_url("welcome"));
                    }
                    getTemplate("landing-page");
                elseif($page == "polling" && $action == "vote" && cekSessionSiswa()):
                    if(!cekSessionSiswa() && !isset($_SESSION['START_POLLING'])) {
                        redirect(base_url("welcome"));
                    }
                    getTemplate("polling");
                elseif($page == "last" && cekSessionSiswa()):
                    if(!cekSessionSiswa()) {
                        redirect(base_url("welcome"));
                    }
                    getTemplate("last-section");
                endif;
            ?>

        </div>

    </div>
    <?php if (cekSessionSiswa()): ?>
        <!-- Logout Modal-->
        <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Konfirmasi Logout.</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">Apakah Anda yakin akan keluar dari Aplikasi ini. ?</div>
                    <div class="modal-footer">
                        <form action="" method="POST" id="form-logout">
                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                            <input type="hidden" name="logout" value="TRUE">
                            <button class="btn btn-danger" type="submit" name="confirm_logout" value="TRUE">Logout</button>                            
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endif ?>

    <!-- Bootstrap core JavaScript-->
    <script src="<?php echo base_url("assets/vendor/jquery/jquery.min.js") ?>"></script>
    <script src="<?php echo base_url("assets/vendor/bootstrap/js/bootstrap.bundle.min.js") ?>"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?php echo base_url("assets/vendor/jquery-easing/jquery.easing.min.js") ?>"></script>
    <?php echo $reCAPTCHA->getScript(); ?>
    <script type="text/javascript" src="<?= base_url('assets/vendor/sweetalert2/sweetalert2.min.js') ?>"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?php echo base_url("assets/js/sb-admin-2.min.js") ?>"></script>
    <?php if($page == "last") : ?>
        <script type="text/javascript">
            var timeleft = 5;
            var downloadTimer = setInterval(function(){
              document.getElementById("countdown").innerHTML = timeleft + " detik.";
              timeleft -= 1;
              if(timeleft <= 0){
                clearInterval(downloadTimer);
                $("#form-logout").submit();
              }
            }, 1000);    
        </script>
    <?php endif; ?>
    <?php if ($page == "polling"): ?>
        <script type="text/javascript">
            $(".btn-form-prev").on("click", function(e){
                e.preventDefault();
                $("#form-back").submit();
            });
        </script>
    <?php endif ?>
    <?php if (cekSessionSiswa()): ?>
        <script type="text/javascript">
            function confirmLogout() {
                $("#logoutModal").modal('show');
            }
            
            <?php if (isset($_SESSION['message']) && $_SESSION['message'] != ''): ?>
                Swal.fire({
                    title: "<?= ucfirst(getFlashMessage('type')) ?>",
                    text: "<?= getFlashMessage('text') ?>",
                    type: "<?= getFlashMessage('type') ?>",
                    timer: 1000
                });
            <?php endif ?>
        </script>
    <?php endif ?>
</body>
</html>

<?php

if (isset($_POST['submit'])) {
    $nis = getPost('nis');
    $id_kelas = getPost('kelas');

    if($reCAPTCHA->isValid($_POST['g-recaptcha-response']) === TRUE) {
        if(!empty(trim($nis)) && !empty(trim($id_kelas))) {
            
            //Cek nis
            $sql_cek = select("*", "siswa", "nis = '$nis' AND id_kelas = '$id_kelas'");
            $cek = cekRow($sql_cek);

            //If nis is exist in table
            if($cek > 0) {
                $siswa = result($sql_cek);
                            
                //Set Session
                setSession('NIS', $siswa->nis);
                setSession('NAMA_SISWA', $siswa->nama);
                setSession('KELAS_ID', $siswa->id_kelas);
                setSession('KODE_SISWA', $siswa->kode);

                //Redirect to Landing Page
                redirect(base_url("landing"));
                
            } else {
                alert("NIS atau Kelas yang Anda pilih salah!", "back");
            }
        } else {
            alert("Silahkan masukkan NIS dan kelas Anda!", "back");
        }
    } else {
        alert("Silahkan centang Captcha terlebih dahulu!", "back");
    }
}

if (isset($_POST['confirm_logout'], $_POST['logout'])) {
    $confirm_logout = getPost('confirm_logout');
    $logout = getPost('logout');

    if($confirm_logout === $logout && $confirm_logout === 'TRUE') {
        unset($_SESSION['NIS']);
        unset($_SESSION['KELAS_ID']);
        unset($_SESSION['KODE_SISWA']);
        unset($_SESSION['TOTAL_GURU']);
        unset($_SESSION['INDEX']);
        session_destroy();
        redirect("welcome");
    }
}

?>