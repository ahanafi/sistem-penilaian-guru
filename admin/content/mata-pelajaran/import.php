<?php
require_once("../vendor/autoload.php");

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
 
$file_mimes = [
    'text/x-comma-separated-values',
    'text/comma-separated-values',
    'application/octet-stream',
    'application/vnd.ms-excel',
    'application/x-csv',
    'text/x-csv',
    'text/csv',
    'application/csv',
    'application/excel',
    'application/vnd.msexcel',
    'text/plain',
    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    'application/wps-office.xlsx',
    'application/wps-office.xls'
];

?>
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Aspek Penilaian</h1>
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Import Aspek Penilaian</h6>
        </div>
        <div class="card-body mb-4">
            <form action="<?php echo base_url("admin/mata-pelajaran/import") ?>" method="POST" class="mb-4" enctype="multipart/form-data">
                <div class="row">
                    <div class="offset-4 col-md-4">
                        <label for="nama_kelas" class="form-control-label">Sampel Import Data</label>
                        <br>
                        <a href="<?php echo base_url("admin/download-template/mata-pelajaran") ?>" class="btn btn-success btn-block">
                            <i class="fas fa-download"></i>
                            <span class="text">Download Template</span>
                        </a>
                        <br>

                        <label for="import" class="form-control-label">Pilih File</label>
                        <input type="file" class="form-control" name="import">
                        <br>

                        <div class="row">
                            <div class="col-md-6">
                                <button class="btn btn-primary btn-block" type="submit" name="submit">
                                    <span>Simpan</span>
                                </button>
                            </div>
                            <div class="col-md-6">
                                <a href="<?php echo base_url("admin/mata-pelajaran") ?>" class="btn btn-secondary btn-block">
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
    $file_name = getFile('import', 'name');
    $file_type = getFile('import', 'type');
    $temporary = getFile('import', 'tmp_name');
    $directory = "../import/";

    if(!empty(trim($file_name)) && !empty(trim($temporary))) {

        if(in_array($file_type, $file_mimes)) {
         
            $arr_file = explode('.', $file_name);
            $extension = end($arr_file);
             
            if('csv' == $extension) {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
            } else {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            }

            if(move_uploaded_file($temporary, $directory.$file_name)) {
                $spreadsheet = $reader->load("../import/".$file_name);
                $sheetData = $spreadsheet->getActiveSheet()->toArray();

                $total_data = count($sheetData);

                $values = "";
                for ($i=1; $i < $total_data; $i++) { 
                    $values .= "(";
                    for ($j=1; $j < count($sheetData[$i]); $j++) { 
                        $values .= "'".addslashes($sheetData[$i][$j])."'";

                        if($j == count($sheetData[$i]) - 1) {
                            $values .= ")";
                        } else {
                            $values .=",";
                        }
                    }
                    
                    if($i != $total_data - 1) {
                        $values .=",";
                    }
                }

                $insert= execute("INSERT INTO mata_pelajaran (kode, nama) VALUES $values");

                if($insert) {
                    unlink("../import/".$file_name);
                    alert("Data Mata pelajaran berhasil diimport!", base_url("admin/mata-pelajaran"));
                } else {
                    alert("Data Mata pelajaran gagal diimport!", "back");
                }
            } else {
                alert("Gagal meng-upload file!", "back");
            }

        } else {
            alert("Format file import data tidak didukung!", "back");
        }
    } else {
        alert("Silahkan pilih file terlebih dahulu!", "back");
    }
}


?>