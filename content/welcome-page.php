<?php
global $kompetensi;
global $reCAPTCHA;

?>
<div class="col-xl-6 col-lg-12 col-md-9">
    <div class="card o-hidden border-0 shadow-lg my-5">
        <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="p-5">
                        <div class="text-center text-gray-900">
                            <img src="<?= base_url("assets/img/logo.png") ?>" alt="" style="width: 25%;padding-bottom: 10px;">
                            <h1 class="h4">
                                Aplikasi Penilaian Guru 
                            </h1>
                            <h2 class="h2 mb-4">SMK Negeri 1 Kedawung</h2>
                        </div>
                        <form class="user text-center" method="POST" action="<?php echo base_url("welcome"); ?>">
                            <div class="form-group">
                                <input type="number" class="form-control text-center" placeholder="Masukkan NIS" name="nis" required autocomplete="off" minlength="9" maxlength="10" onkeypress="return event.charCode &gt;= 48 &amp;&amp; event.charCode &lt;= 57">
                            </div>

                            <div class="form-group">
                                <select name="kelas" id="list-komp" class="form-control font-weight-bold" required>
                                    <option value="">-- Pilih Kelas Anda --</option>
                                    <?php while($kmp = result($kompetensi)): ?>
                                        <option value="<?php echo $kmp->id; ?>"><?php echo $kmp->nama_kelas; ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <?php echo $reCAPTCHA->getHtml(); ?>

                            <div class="form-group mb-4 mt-4">
                                <button type="submit" class="btn btn-success btn-user btn-block mt-4" name="submit">
                                    Masuk.
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>