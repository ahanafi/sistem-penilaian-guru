<?php
require_once("../vendor/autoload.php");

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx as XLSX;
use PhpOffice\PhpSpreadsheet\IOFactory;

$action = getFrom('action');

if ($action == "export") {

	$kelas_sql = select("*", "kelas");
	$totalData = cekRow($kelas_sql);

	$spreadSheet = new Spreadsheet();
	$sheet = $spreadSheet->getActiveSheet();
	$sheet->setCellValue('A1', 'No.');
	$sheet->setCellValue('B1', 'Nama Kelas');
	$sheet->setCellValue('C1', 'Kelas');
	$sheet->setCellValue('D1', 'Komp. Keahlian');

	for ($i=2; $i < $totalData; $i++) { 
		$kelas = result($kelas_sql);
		$sheet->setCellValue('A'.$i, $i-1);
		$sheet->setCellValue('B'.$i, $kelas->nama_kelas);
		$sheet->setCellValue('C'.$i, $kelas->kelas);
		$sheet->setCellValue('D'.$i, $kelas->jurusan);
	}

	$filename = "Data Kelas.xlsx";
	//$writer = new XLSX($spreadSheet);
	$writer = IOFactory::createWriter($spreadSheet, 'Xlsx');

	/*header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename="Data Kelas.xlsx"');*/
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="' . $filename);
    header('Last-Modified:'.date('D, d M Y H:i:s'));
    header('Cache-Control: cache, must-revalidate');
    header('Pragma: public');
	
	$writer->save('php://output');
	ob_flush();
	
	//$writer->save("Data Kelas.xlsx");
} else {
	die("Failed to Export Data!");
}
?>