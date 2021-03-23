<?php
require_once('../vendor/autoload.php');
require_once('../core/init.php');

use Dompdf\Dompdf;

$URI = explode("/", $_SERVER['REQUEST_URI']);

$action = $URI[4];
$page = $URI[2];
$uri_nilai = $URI[3];

if (isset($_POST['export']) && $uri_nilai == 'detail-penilaian-guru' && $action == "export") {
	$uri_kode = $URI[5];
	$kode_guru = getPost('kode_guru');
	
	if($uri_kode != $kode_guru && !empty(trim($kode_guru))) {
		die("Failed to Export Data!");
		exit;
	} else {

	$guru_sql = select('*', "guru", "kode = '$kode_guru'");
	$guru = result($guru_sql);
	$no = 1;

	$list_kelas = select("*", "kelas");
	$aspek_sql = select("*", "aspek_penilaian");
	$page = getFrom("page");

	$max_sql = select("SUM(nilai_max) AS max", "aspek_penilaian");
	$max_nilai = result($max_sql);



$html = "
<html>
	<head>
		<title>Hasil Penilaian</title>
	</head>
	<style type='text/css'>
		body{
			margin:0;
			padding:0;
		}
		.align-middle{
			text-align: center;
			vertical-align: middle;
		}
		#header {margin-bottom: 30px;border-bottom:2px solid #000;padding-bottom:10px;}
		.text-center{text-align: center;vertical-align:middle;}
		.logo > img{width: 10%;position: fixed;}
		.kop-text{
			display: block;
			text-align: center;
		}
		h1,h2, h3, h4,h5,h6,p{margin:0;}
		.sub-header{margin-bottom: 20px !important;}
		.sub-header > h3{text-align:center;margin-bottom:20px;}
		.nama_kelas{text-align:left;}
		.box-ket{margin-top:10px;padding:10px;border:1px solid #000;width:280px;}
		.box-ket table tr td:nth-child(1){width:120px;}
		.box-ket table tr td:nth-child(2){width:40px;}
		.text-center{text-align:center;vertical-align:middle;}
		.font-weight-bold{font-weight:bold !important;}
	</style>
	<body>
		<div id='header'>
			<div class='logo'>
				<img src='../assets/img/logo-prov-jabar.png'>
			</div>
			<div class='kop-text'>
				<h4>PEMERINTAH DAERAH PROVINSI JAWA BARAT</h3>
				<h3>DINAS PENDIDIKAN</h2>
				<h2>SMK NEGERI 1 KEDAWUNG</h1>
				<p>
					Jl. Tuparev No. 12 Telp. (0231) 203795 Fax. (0231) 203795, 200653 <br>
					http://smkn1-kedawung.sch.id -- E-mail : kampus@smkn1-kedawung.sch.id <br>
					<b>KABUPATEN CIREBON</b>
				</p>
			</div>
		</div>
		<div class='sub-header'>
			<h3>KEPUASAN PESERTA DIDIK TERHADAP LAYANAN GURU</h2>
			<h3>SMK NEGERI 1 KEDAWUNG KABUPATEN CIREBON</h2>
		</div>
		<br>
           	<h3>I. DETAIL HASIL PENILAIAN</h3>
        <br>
            <table cellpadding='5' cellspacing='0' width='100%' border='1' id='profile'>
                <tr>
                    <td>ID Guru</td>
                    <td>:</td>
                    <td>".$guru->kode."</td>
                </tr>
                <tr>
                    <td>Nama Guru</td>
                    <td>:</td>
                    <td>".$guru->nama."</td>
                </tr>";

                $jk = ($guru->jk == "L") ? "Laki-Laki" : "Perempuan";
                $sql_jum_kelas = select("*", "kelas_ajar", "kode_guru = '$kode_guru'");
				$sql_jum_responden = select("DISTINCT(kode_siswa)", "penilaian", "kode_guru = '$kode_guru'");
                $responden = cekRow($sql_jum_responden);
				
				$sql_nilai = select("SUM(nilai) AS nilai", "penilaian", "kode_guru = '$kode_guru'");
                $jum_nilai = result($sql_nilai);

                $max_sql = select("SUM(nilai_max) AS max", "aspek_penilaian");
                $max_nilai = result($max_sql);

                $presentase = number_format($jum_nilai->nilai / $responden, 2);
                $p1 = $presentase;

                $presentase = number_format((($presentase/$max_nilai->max) * 100), 2);
                $p2 = $presentase;

                $keterangan = getKeterangan($presentase);
$html .=        "<tr>
                    <td>Jenis Kelamin</td>
                    <td>:</td>
                    <td>".$jk."</td>
                </tr>
                <tr>
                    <td>Jumlah Kelas yang diajar</td>
                    <td>:</td>
                    <td>".cekRow($sql_jum_kelas)."</td>
                </tr>
                <tr>
                    <td>Jumlah Responden</td>
                    <td>:</td>
                    <td>".$responden." Siswa</td>
                </tr>
                <tr>
                    <td>Total Nilai</td>
                    <td>:</td>
                    <td>".str_replace(",", ".", number_format($jum_nilai->nilai))."</td>
                </tr>
                <tr>
                    <td>Presentase Nilai</td>
                    <td>:</td>
                    <td>".$presentase . "%</td>
                </tr>
                <tr>
                    <td>Keterangan</td>
                    <td>:</td>
                    <td><b>".getDeskripsi($keterangan)."</b>
                    </td>
                </tr>
            </table>
			<br>
           	<h3>II. DETAIL NILAI PER ASPEK PENILAIAN</h3>
           	<br>
            <table cellspacing='0' cellpadding='5' width='100%' border='1'>
                <thead>
                    <tr>
                        <th style='text-align:center;'>No.</th>
                        <th>Nama Aspek</th>
                        <th class='text-center'>Jumlah Nilai</th>
                    </tr>
                </thead>
                <tbody>";
                    while($nl = result($aspek_sql)):
						$sql_nilai_aspek = select("SUM(nilai) AS nilai", 'penilaian', "id_aspek_pn = '$nl->id' AND kode_guru = '$guru->kode'");
                        $nilai_aspek = result($sql_nilai_aspek);
$html .= 			    "<tr>
                            <td style='text-align:center;'>".$no++."</td>
                            <td>".$nl->nama_indikator."</td>
                            <td class='text-center font-weight-bold'>".$nilai_aspek->nilai."</td>
                        </tr>";
                    endwhile;
$html .= 			"<tr>
                        <td colspan='2' class='text-center font-weight-bold'>TOTAL</td>
                        <td class='text-center font-weight-bold'>".str_replace(',', '.', number_format($jum_nilai->nilai))."</td>
                    </tr>
                </tbody>
            </table>
        </div>
	</body>
</html>";

echo $html;
die();

$dompdf = new Dompdf();
$dompdf->set_option('defaultFont', 'Courier');
$dompdf->loadHtml($html);

// (Optional) Setup the paper size and orientation
$dompdf->setPaper('A4', 'P');

// Render the HTML as PDF
$dompdf->render();

// Output the generated PDF to Browser
$nama_guru = explode(",", $guru->nama);

$file_name = "Hasil-Penilaian-$nama_guru[0]" . ".pdf";
$dompdf->stream($file_name, ['Attachment' => FALSE]);

	}

} else {
	die("Failed to Export Data!");
}
?>