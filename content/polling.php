<?php
setSession('START_POLLING', TRUE);

if (isset($_SESSION['message'])) {
    $_SESSION['message'] = '';
    resetFlashMessage();
}

//Cek Session Siswa
if(!cekSessionSiswa()) {
    redirect("welcome");
}

//Get Detail Guru
$kode = getFrom('id');
$guru = select("nama", "guru", "kode = '$kode'");
$guru = result($guru);

$no = 1;
$kode_siswa = getSession('KODE_SISWA');

//Get Aspek Penilaian
$aspek = select("*", "aspek_penilaian");

//Cek NIlai
$cek_nilai = select("*", "penilaian", "kode_siswa = '$kode_siswa' AND kode_guru = '$kode'");
$no = 1;
?>
<style type="text/css">
    .form-control{
        border-radius: 5rem;
        padding:0.25rem !important;
    }
    .btn-group .btn{
        padding-top:10px;
        padding-bottom:10px;
        width: 50%;
    }
    .btn-group > form{
        width: 50% !important;
    }
    .btn-group > form > button {
        width: 100% !important;
    }
    .btn-prev{
        border-bottom-right-radius: 0px;
        border-top-right-radius: 0px;
        border-top-left-radius: 5rem;
        border-bottom-left-radius: 5rem;
    }
    .btn-next{
        border-bottom-right-radius: 5rem;
        border-top-right-radius: 5rem;
        border-top-left-radius: 0px;
        border-bottom-left-radius: 0px;
    }
</style>
<div class="col-md-5">
    <div class="card o-hidden border-0 shadow-lg my-5">
        <div class="card-header">
            <h5 class="card-title mb-0" style="color: #000;">
                <i class="fas fa-info-circle text-primary"></i>
                Petunjuk Pengisian Nilai
            </h5>
        </div>
        <div class="card-body p-3">
            <ul class="list-group">
                <li class="list-group-item">1. Baca terlebih dahulu <u><b>Nama Guru</b></u> yang akan diberi nilai.</li>
                <li class="list-group-item">2. Baca jenis-jenis aspek penilaian yang akan diberikan nilainya.</li>
                <li class="list-group-item">3. Input nilai pada masing-masing inputan tiap aspek penilaian.</li>
                <li class="list-group-item">4. Nilai yang diperbolehkan adalah angka 1 sampai dengan 5.</li>
                <li class="list-group-item">5. Ketentuan Nilai : </li>
                <li class="list-group-item">
                    <p class="pl-4">
                        <b>5</b> = Sangat Baik <br>
                        <b>4</b> = Baik<br>
                        <b>3</b> = Cukup<br>
                        <b>2</b> = Sedang<br>
                        <b>1</b> = Kurang<br>
                    </p>
                </li>
                <li class="list-group-item">6. Pastikan semua aspek penilain telah diisi</li>
                <li class="list-group-item">7. Klik tombol <b><i>Simpan</i></b></li>
            </ul>
        </div>
    </div>
</div>
<div class="col-md-7">
    <div class="card o-hidden border-0 shadow-lg my-5">
        <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="p-5">
                        <div class="text-gray-900 text-center p-1">
                            <h1 class="h3" style="border-bottom: 2px solid #000;">
                                <?php echo  $guru->nama; ?>
                            </h1>
                        </div>
                        <?php if (cekRow($cek_nilai) > 0): ?>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Aspek Penilaian</th>
                                        <th>Nilai</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while($apk = result($aspek)): ?>
                                        <tr>
                                            <td><?php echo $no++; ?></td>
                                            <td><?php echo $apk->nama_indikator; ?></td>
                                            <td class="text-center font-weight-bold">
                                                <?php
                                                    $nilai = select("*", "penilaian", "kode_siswa = '$kode_siswa' AND kode_guru = '$kode' AND id_aspek_pn = '$apk->id'");
                                                    $nilai = result($nilai);
                                                    echo $nilai->nilai;
                                                ?>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="btn-group btn-block">
                                        <a href="<?= base_url("landing") ?>" class="btn btn-primary" style="border-radius: 5rem;">
                                            <i class="fas fa-chevron-left"></i>
                                            <span>Home</span>
                                        </a>
                                    </div>
                                </div>
                            </div>

                        <?php else: ?>
                            <h3 class="h5 mb-3">Aspek Penilaian</h1>
                            <br>
                            <form action="" method="POST">
                                <?php while($apk = result($aspek)): ?>
                                    <div class="form-group row">
                                        <label for="" class="form-control-label col-md-7">
                                            <?php echo $apk->nama_indikator; ?>
                                        </label>
                                        <div class="col-md-5">
                                            <input type="hidden" name="aspek_id[]" value="<?php echo $apk->id; ?>">
                                            <input type="number" class="form-control" min="<?php echo $apk->nilai_min ?>" max="<?php echo $apk->nilai_max ?>" placeholder="Nilai: <?php echo $apk->nilai_min . " - ". $apk->nilai_max ?>" required name="nilai[]">
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                                <?php
                                    $link = '';
                                    if(getSession('INDEX') == 0) {
                                        $link = base_url("landing");
                                    } else {
                                        $index = getSession('INDEX') - 1;
                                        $link = base_url("polling/section/".$_SESSION['guru'][$index]);
                                    }
                                ?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="btn-group btn-block">
                                            <a href="<?= base_url("landing") ?>" class="btn btn-primary btn-prev">
                                                <i class="fas fa-chevron-left"></i>
                                                <span>Home</span>
                                            </a>
                                            <button class="btn btn-success btn-next" name="submit" type="submit">
                                                <span>Simpan</span>
                                                <i class="fas fa-chevron-right"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        <?php endif ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
if (isset($_POST['submit'])) {
    $aspek_id = getPost('aspek_id');
    $nilai = getPost('nilai');

    if(count($aspek_id) != count($nilai)) {
        setFlashMessage([
            'type' => 'error',
            'text' => 'Anda harus mengisi semua aspek penilaian!'
        ]);
        redirect("back");
        exit;
    } else {
        $total = count($aspek_id);

        $values = "";
        for ($i=0; $i < $total; $i++) {
            if($nilai[$i] <= 0 || $nilai[$i] > 5) {
                setFlashMessage([
                    'type' => 'error',
                    'text' => 'Nilai tidak boleh kurang dari 1 atau lebih dari 5'
                ]);
                redirect("back");
                exit;
            }

            $cek_aspek_nilai = select("*", "penilaian", "kode_siswa = '$kode_siswa' AND kode_guru = '$kode' AND id_aspek_pn = '$aspek_id[$i]'");
            if(cekRow($cek_aspek_nilai) == 0) {
                $values .= "('$kode', '$kode_siswa', '$aspek_id[$i]', '$nilai[$i]')";
                if($i < $total-1) {
                    $values .= ",";
                }
            }
        }

        $insert = execute("INSERT INTO penilaian (kode_guru, kode_siswa, id_aspek_pn, nilai) VALUES $values");

        if($insert) {
            setFlashMessage([
                'type' => 'success',
                'text' => 'Data nilai berhasil disimpan!'
            ]);

            redirect(base_url("landing"));
        }
    }
}
?>