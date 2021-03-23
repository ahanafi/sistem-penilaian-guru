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
    <h1 class="h3 mb-2 text-gray-800">Data Siswa</h1>
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Import Data Siswa</h6>
        </div>
        <div class="card-body mb-4">
            <form action="<?php echo base_url("admin/data-siswa/import") ?>" method="POST" class="mb-4" enctype="multipart/form-data">
                <div class="row">
                    <div class="offset-4 col-md-4">
                        <label for="nama_kelas" class="form-control-label">Sampel Import Data</label>
                        <br>
                        <a href="<?php echo base_url("admin/download-template/siswa") ?>" class="btn btn-success btn-block">
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
                                <a href="<?php echo base_url("admin/data-siswa") ?>" class="btn btn-secondary btn-block">
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

            for ($j=1; $j < count($sheetData[$i]); $j++) {
                if($j ==  1) {
                    //Check length of the code
                    if (strlen($sheetData[$i][0]) == 1) {
                        $addZero = "000";
                    } elseif (strlen($sheetData[$i][0]) == 2) {
                        $addZero = "00";
                    } elseif (strlen($sheetData[$i][0]) == 3) {
                        $addZero = "0";
                    } else {
                        $addZero = "";
                    }

                    //Create new code
                    $new_kode = "STD-" . $addZero . $sheetData[$i][0];
                    $values .= "'$new_kode', ";
                }


                if($j != count($sheetData[$i]) - 1) {
                    $values .= "'".addslashes($sheetData[$i][$j])."'";
                }

                if($j == count($sheetData[$i]) - 1) {
                    $cek_kelas = select("id", "kelas", "nama_kelas = '".$sheetData[$i][$j]."'");
                    $kelas = result($cek_kelas);
                    
                    if($kelas) {
                        $values .= "'$kelas->id'";
                    }

                    $values .= ")";
                } else {
                    $values .=",";
                }
            }
            
            if($i != $total_data - 1) {
                $values .=",";
            }
        }

//        die($values);

        $insert= execute("INSERT INTO siswa (kode, nis, nama, jk, id_kelas) VALUES $values");

        if($insert) {
            unlink("../import/".$file_name);
            alert("Data siswa berhasil diimport!", base_url("admin/data-siswa"));
        } else {
            alert("Data siswa gagal diimport!", "back");
        }

        } else {
            alert("Format file import data tidak didukung!", "back");
        }
    } else {
        alert("Silahkan pilih file terlebih dahulu!", "back");
    }
}


?>