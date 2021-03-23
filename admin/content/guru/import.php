<?php
require_once("../vendor/autoload.php");

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
 
$file_mimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/wps-office.xlsx');

?>
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Data Guru</h1>
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Import Data Guru</h6>
        </div>
        <div class="card-body mb-4">
            <form action="<?php echo base_url("admin/data-guru/import") ?>" method="POST" class="mb-4" enctype="multipart/form-data">
                <div class="row">
                    <div class="offset-4 col-md-4">
                        <label for="nama_kelas" class="form-control-label">Sampel Import Data</label>
                        <br>
                        <a href="<?php echo base_url("admin/download-template/guru") ?>" class="btn btn-success btn-block">
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
                                <a href="<?php echo base_url("admin/data-guru") ?>" class="btn btn-secondary btn-block">
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
        move_uploaded_file($temporary, $directory.$file_name);
        $spreadsheet = $reader->load("../import/".$file_name);
        $sheetData = $spreadsheet->getActiveSheet()->toArray();

        $total_data = count($sheetData);

        $values = "";
        for ($i=1; $i < $total_data; $i++) { 
            $values .= "(";
            
            //Start Check the last code
            $sql_kode = select("MAX(kode) as kode", "guru");
            $kode_db  = result($sql_kode);
            $kode_db  = str_replace("GR-", "", $kode_db->kode);
            $kode_db  = (int) $kode_db + $i;

            //Check length of the code
            if (strlen($kode_db) == 1) {
                $addZero = "000";
            } elseif (strlen($kode_db) == 2) {
                $addZero = "00";
            } elseif (strlen($kode_db) == 3) {
                $addZero = "0";
            } else {
                $addZero = "";
            }

            //Create new code
            $new_kode = "GR-" . $addZero . $kode_db;
            $values .= "'$new_kode', ";
            for ($j=1; $j < count($sheetData[$i]); $j++) { 
                $values .= "'".$sheetData[$i][$j]."'";

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

        //die($values);

        $insert= execute("INSERT INTO guru (kode, nama, jk, telpon, alamat) VALUES $values");

        if($insert) {
            unlink("../import/".$file_name);
            alert("Data guru berhasil diimport!", base_url("admin/data-guru"));
        } else {
            alert("Data guru gagal diimport!", "back");
        }

        } else {
            alert("Format file import data tidak didukung!", "back");
        }
    } else {
        alert("Silahkan pilih file terlebih dahulu!", "back");
    }
}


?>