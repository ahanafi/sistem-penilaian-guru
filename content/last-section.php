<?php
if (!cekSessionSiswa()) {
    redirect("welcome");
}
?>
<div class="col-xl-6 col-lg-12 col-md-9">
    <div class="card o-hidden border-0 shadow-lg my-5">
        <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="p-5 text-center">
                        <div class="text-center text-gray-900">
                            <h1 class="h2">Hi Users!</h1>
                            <h1 class="h4 mb-4">Terima Kasih atas waktu Anda.</h1>
                        </div>
                        <a href="<?php echo base_url("logout") ?>" class="btn btn-circle btn-success" style="padding:4rem;">
                            <i class="fas fa-check fa-5x"></i>
                        </a>
                        <div class="text-center mt-3 text-gray-900">
                            Silahkan klik <u>Lingkaran hijau</u> diatas atau anda akan di alihkan ke Halaman Awal dalam watku <span id="countdown"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>