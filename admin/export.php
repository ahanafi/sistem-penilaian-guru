<?php
require_once('../vendor/autoload.php');
require_once('../core/init.php');

use Dompdf\Dompdf;

$URI = explode("/", $_SERVER['REQUEST_URI']);

$action = $URI[4];
$page = $URI[3];

/*print_r($URI);
die();*/


if (isset($_POST['export']) && $action == "export") {
	$kelasID = getPost('kelas');

	if($kelasID != "all" && $kelasID != "") {
		$guru_sql = joinTable("*", "guru", "kelas_ajar", "guru.kode = kelas_ajar.kode_guru", "kelas_ajar.id_kelas = '$kelasID' ORDER BY nama ASC");
		$nama_kelas = select("nama_kelas", "kelas", "id = '$kelasID'");
		$nama_kelas = result($nama_kelas)->nama_kelas;
	} else {
		$guru_sql = select('*', "guru", "kode != '' ORDER BY nama ASC");
		$nama_kelas = "10 - 12";
	}
	$no = 1;

	$idx = 0;
	$guru_arr = [];
	while ($gr = result($guru_sql)) {
	    $guru_arr[$idx]['nama'] = $gr->nama; 
	    $guru_arr[$idx]['kode'] = $gr->kode; 
	    $guru_arr[$idx]['id'] = $gr->id; 
	    $idx++;
	}

	$aspek_sql = select("*", "aspek_penilaian");
	
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
		.sub-header{margin-bottom: 10px;}
		.sub-header > h3{text-align:center;margin-bottom:10px;}
		.nama_kelas{text-align:left;}
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
		<h4 class='nama_kelas'>KELAS : ".$nama_kelas."</h3>
		</div>
		<table width='100%' cellspacing='0' border='1' cellpadding='5'>
		    <thead class='bg-dark text-white'>
		        <tr>
		            <th rowspan='2' class='align-middle'>No.</th>
		            <th rowspan='2' class='align-middle text-center' width='110'>Nama Guru</th>
		            <th colspan='9' class='text-center'>Aspek Penilaian</th>
		            <th rowspan='2' class='align-middle'>Nilai (%)</th>
		        </tr>
		        <tr>";
		                $aspek_arr = [];
		                $index = 0;
		                while($ap = result($aspek_sql)):
							$html .= "<th class='text-center'>$ap->nama_indikator";
		                    $aspek_arr[$index]['id'] = $ap->id;
		                    $index++;
		                    
		                	$html .= "</th>";
		                endwhile;
		                $total_nilai = 0;
$html .=    	"</tr>
		    </thead>
		    <tbody>";
		        	foreach($guru_arr as $g):
		        		$html .= "<tr>
		            		<td class='text-center'>".$no."</td>
		            		<td>".$g['nama']."</td>";
		            		foreach($aspek_arr as $as):
			                $html .= "<td class='text-center'>";
		                        $sql_cek = select('SUM(nilai) as nilai', 'penilaian', "kode_guru = '$g[kode]' AND id_aspek_pn = '$as[id]'");
		                        $nilai = result($sql_cek);
		                        $nilai_aspek = 0;
		                        if($nilai->nilai) {
		                            $total_nilai = $total_nilai + $nilai->nilai;
		                            $nilai_aspek = $nilai->nilai;
		                        }

				                $html .= $nilai_aspek;
				                $html .= "</td>";
			            	endforeach;

			            if($total_nilai > 0) {
			            	$sql_hitung_respon = select("COUNT(DISTINCT(kode_siswa)) as jum_siswa", "penilaian", "kode_guru = '$g[kode]'");
			            	$hitung_respon = result($sql_hitung_respon);

			            	$total_nilai = ($total_nilai / $hitung_respon->jum_siswa);
			            	$total_nilai = number_format((($total_nilai / $max_nilai->max) * 100), 2);
			            }
			            $html .= "<td class='text-center font-weight-bold'>";
			            $html .= number_format($total_nilai, 2);
			            $total_nilai = 0;
			            $html .= "</td>
			        </tr>";
			        $no++;
			        endforeach;
$html .=    "</tbody>
		</table>
	</body>
</html>";

//echo $html;

$dompdf = new Dompdf();
$dompdf->set_option('defaultFont', 'Courier');
$dompdf->loadHtml($html);

// (Optional) Setup the paper size and orientation
$dompdf->setPaper('A4', 'landscape');

// Render the HTML as PDF
$dompdf->render();

// // Output the generated PDF to Browser
$dompdf->stream('Hasil Penilaian.pdf', ['Attachment' => FALSE]);

} else {
	die("Failed to Export Data!");
}
?>