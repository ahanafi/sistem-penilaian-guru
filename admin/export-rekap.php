<?php
require_once('../vendor/autoload.php');
require_once('../core/init.php');

use Dompdf\Dompdf;

$URI = explode("/", $_SERVER['REQUEST_URI']);

$action = $URI[4];
$page = $URI[3];

if (isset($_POST['export']) && $action == "export") {
	$guru_sql = select('*', "guru", "kode != '' ORDER BY nama ASC");
	$nama_kelas = "10 - 12";
	$no = 1;

	$idx = 0;
	$guru_arr = [];
	while ($gr = result($guru_sql)) {
	    $guru_arr[$idx]['nama'] = $gr->nama; 
	    $guru_arr[$idx]['kode'] = $gr->kode; 
	    $guru_arr[$idx]['id'] = $gr->id; 
	    $idx++;
	}
	
	$max_sql = select("SUM(nilai_max) AS max", "aspek_penilaian");
	$max_nilai = result($max_sql);

//Count variabel
$sangat_baik = $baik = $cukup = $sedang = $kurang = 0;

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
			<br><br>
		<h4 class='nama_kelas'>KELAS : x".$nama_kelas."</h3>
		</div>
		<table width='100%' cellspacing='0' border='1' cellpadding='5'>
		    <thead class='bg-dark text-white'>
		        <tr>
		            <th class='align-middle'>No.</th>
		            <th class='align-middle text-center' width='160'>Nama Guru</th>
		            <th class='text-center'>Jumlah Nilai</th>
		            <th class='text-center'>Jumlah Responden</th>
		            <th class='align-middle'>Presentase Nilai (%)</th>
		            <th class='align-middle'>Keterangan</th>
		            <th class='align-middle' width='80'>Deskripsi</th>
		        </tr>
		    </thead>
		    <tbody>";
		        	foreach($guru_arr as $g):
		        		$html .= "<tr>
		            		<td class='text-center'>".$no."</td>
		            		<td>".$g['nama']."</td>
		            		<td class='text-center'>";
                            
                            $jum_nilai = 0;
                            $sql_nilai = select("SUM(nilai) AS jumlah_nilai", "penilaian", "kode_guru = '$g[kode]'");
                            $nilai = result($sql_nilai);
                            $jum_nilai = ($nilai->jumlah_nilai) ? $nilai->jumlah_nilai : 0;
                            
                            $html .= $jum_nilai;

                            $sql_res = select("COUNT(DISTINCT(kode_siswa)) as jumlah_siswa", "penilaian", "kode_guru = '$g[kode]'");
                            $res = result($sql_res);
                            $jum_siswa = ($res->jumlah_siswa) ? $res->jumlah_siswa : 0;

						$html .= "</td>
							<td class='text-center'>$jum_siswa</td>
							<td class='text-center'>";
                            
                            $presentase = ($jum_nilai > 0 && $jum_siswa >0 ) ? number_format(($jum_nilai / $jum_siswa), 2) : 0;
                            $presentase = number_format((($presentase/$max_nilai->max) * 100), 2);
                            $html .= $presentase;
                            
                            $ket = getKeterangan($presentase);
                            $des = getDeskripsi($ket);
                            
                            if($ket == "SB") {
                            	$sangat_baik++;
                            } elseif ($ket == "B") {
                            	$baik++;
                            } elseif ($ket == "C") {
                            	$cukup++;
                            } elseif ($ket == "S") {
                            	$sedang++;
                            } elseif ($ket == "K") {
                            	$kurang++;
                            }                         

						$html .= "</td>
							<td class='text-center'>$ket</td>
							<td class='text-center'>$des</td>
			        	</tr>";
			        $no++;
			        endforeach;

$html .=    "</tbody>
		</table>
		<div class='box-ket'>
			<h3>Keterangan : </h3>
			<table cellpadding='3' cellspacing='0'>
				<tr>
					<td>Sangat Baik</td>
					<td class='text-center'>$sangat_baik</td>
					<td>orang</td>
				</tr>
				<tr>
					<td>Baik</td>
					<td class='text-center'>$baik</td>
					<td>orang</td>
				</tr>
				<tr>
					<td>Cukup</td>
					<td class='text-center'>$cukup</td>
					<td>orang</td>
				</tr>
				<tr>
					<td>Sedang</td>
					<td class='text-center'>$sedang</td>
					<td>orang</td>
				</tr>
				<tr>
					<td>Kurang</td>
					<td class='text-center'>$kurang</td>
					<td>orang</td>
				</tr>
			</table>
		</div>
	</body>
</html>";

//echo $html;

$dompdf = new Dompdf();
$dompdf->set_option('defaultFont', 'Courier');
$dompdf->loadHtml($html);

// (Optional) Setup the paper size and orientation
$dompdf->setPaper('A4', 'P');

// Render the HTML as PDF
$dompdf->render();

// Output the generated PDF to Browser
$dompdf->stream('Hasil Penilaian.pdf', ['Attachment' => FALSE]);

} else {
	die("Failed to Export Data!");
}
?>