<?php
require_once("../core/init.php");

if (!cekSessionUser()) {
    redirect(base_url("admin/login"));
}

$page = getFrom('page');
$action = getFrom('action');
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Sistem Penilaian Guru - Dashboard</title>

    <!-- Custom fonts for this template-->
    <link href="<?php echo base_url("assets/vendor/fontawesome-free/css/all.min.css") ?>" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url("assets/vendor/select2/select2.min.css") ?>" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url("assets/vendor/select2/select2-bootstrap4.min.css") ?>" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url("assets/vendor/sweetalert2/sweetalert2.min.css") ?>" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link rel="shorcut icon" href="<?= base_url("assets/img/logo.png") ?>">

    <!-- Custom styles for this template-->
    <link href="<?php echo base_url("assets/css/sb-admin-2.min.css") ?>" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <?php require_once("templates/sidebar.php"); ?>

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column" style="margin-left: 14rem;">

            <!-- Main Content -->
            <div id="content">

                <?php require_once("templates/navbar.php") ?>

                <?php
                    if($page == ""):

                        //DATA FOR PIE CHART
                       $total_nilai_per_aspek = joinTable("SUM(nilai) as total, nama_indikator as nama_aspek", "penilaian", "aspek_penilaian", "penilaian.id_aspek_pn = aspek_penilaian.id GROUP BY id_aspek_pn");
                        $data_nilai_aspek = [];
                        $data_colors = [
                            "#e74c3c", "#e67e22", "#f1c40f", "#16a085", "#2980b9",
                            "#8e44ad", "#2c3e50", "#95a5a6", "#ecf0f1"
                        ];
                        while ($tn = result($total_nilai_per_aspek)) { 
                            $data_nilai_aspek['label'][] = $tn->nama_aspek;
                            $data_nilai_aspek['nilai'][] = $tn->total;
                        }

                        //DATA FOR BAR CHART
                        //$list_guru = joinTable("nama AS nama_guru, kode_guru AS kode_guru, SUM(nilai) AS total", "guru", "penilaian", "guru.kode = penilaian.kode_guru GROUP BY kode_guru ORDER BY total DESC LIMIT 0,5");
                        $list_guru = query("
                                SELECT
                                    nama AS nama_guru,
                                    kode_guru AS kode_guru,
                                    SUM(nilai) AS total,
                                    COUNT(DISTINCT(kode_siswa)) AS responden,
                                    ((SUM(nilai)/(COUNT(DISTINCT(kode_siswa))))/(
                                        SELECT SUM(nilai_max) FROM aspek_penilaian
                                        ))*100 AS real_nilai
                                FROM guru
                                    JOIN penilaian ON guru.kode = penilaian.kode_guru
                                GROUP BY kode_guru
                                ORDER BY real_nilai DESC LIMIT 0,10
                            ");
                        $guru_arr = [];

                        while ($gr = result($list_guru)) {
                            $guru_arr['nama'][] = $gr->nama_guru;
                            $guru_arr['jumlah'][] = number_format($gr->real_nilai, 2);
                        }

                        //print_r($data_nilai_aspek);

                        require_once("content/main-page.php");

                    # Guru
                    elseif ($page == "data-guru"):
                        if($action == ""):
                            call_content("guru","index");
                        elseif($action == "create" || $action == "tambah"):
                            call_content("guru", "form-create");
                        elseif($action == "edit" || $action == "tambah-kelas"):
                            call_content("guru", "form-edit");
                        elseif($action == "delete"):
                            call_content("guru", "delete");
                        elseif($action == "delete-kelas"):
                            call_content("guru", "delete-kelas");
                        elseif($action == "import"):
                            call_content("guru", "import");
                        endif;

                    # MAPEL
                    elseif ($page == "mata-pelajaran"):
                        if($action == ""):
                            call_content($page,"index");
                        elseif($action == "create" || $action == "tambah"):
                            call_content($page, "form-create");
                        elseif($action == "edit"):
                            call_content($page, "form-edit");
                        elseif($action == "delete"):
                            call_content($page, "delete");
                        elseif($action == "import"):
                            call_content($page, "import");
                        endif;

                    # USER
                    elseif ($page == "user-management" || $page == "profil"):
                        if($page == "profil" && $action == "ubah-password"):
                            call_content("user", $action);
                        else:
                            if($action == ""):
                                call_content("user","index");
                            else:
                                call_content("user", $action);
                            endif;
                        endif;

                    # SISWA
                    elseif ($page == "data-siswa"):
                        if($action == ""):
                            call_content("siswa","index");
                        elseif($action == "create" || $action == "tambah"):
                            call_content("siswa", "form-create");
                        elseif($action == "edit"):
                            call_content("siswa", "form-edit");
                        elseif($action == "delete"):
                            call_content("siswa", "delete");
                        elseif($action == "import"):
                            call_content("siswa", "import");
                        endif;

                    # Kelas
                    elseif ($page == "data-kelas"):
                        if($action == ""):
                            call_content("kelas","index");
                        elseif($action == "create" || $action == "tambah"):
                            call_content("kelas", "form-create");
                        elseif($action == "edit"):
                            call_content("kelas", "form-edit");
                        elseif($action == "delete"):
                            call_content("kelas", "delete");
                        elseif($action == "detail"):
                            call_content("kelas", "detail");
                        elseif($action == "import"):
                            call_content("kelas", "import");
                        elseif($action == "export"):
                            call_content("kelas", "export");
                        elseif($action == "tambah-pengajar"):
                            call_content("kelas", "form-pengajar");
                        elseif($action == "hapus-pengajar"):
                            call_content("kelas", "delete-pengajar");
                        elseif($action == "set-mapel"):
                            call_content("kelas", "form-set-mapel");
                        elseif($action == "set-mapel-global"):
                            call_content("kelas", "form-set-mapel-global");
                        endif;

                    #Aspek Penilaian
                    elseif ($page == "aspek-penilaian"):
                        if($action == ""):
                            call_content("aspek-penilaian","index");
                        elseif($action == "create" || $action == "tambah"):
                            call_content("aspek-penilaian", "form-create");
                        elseif($action == "edit"):
                            call_content("aspek-penilaian", "form-edit");
                        elseif($action == "delete"):
                            call_content("aspek-penilaian", "delete");
                        elseif($action == "import"):
                            call_content("aspek-penilaian", "import");
                        endif;

                    #Hasil
                    elseif ($page == "hasil-penilaian"):
                        call_content("hasil", "penilaian");
                    elseif($page == "responden"):
                        if ($action == ""):
                            call_content("hasil", "responden");
                        elseif($action == "detail"):
                            call_content("hasil", "detail-responden");
                        elseif($action == "real"):
                            call_content("hasil", "real-responden");
                        elseif($action == "delete"):
                            call_content("hasil", "hapus-responden");
                        elseif($action == "not-responding"):
                            call_content("hasil", "not-responding");
                        endif;
                    elseif($page == "detail-penilaian"):
                        $get_kode_guru = isset($_GET['kode-guru']) ? $_GET['kode-guru'] : "";
                        if($get_kode_guru != "" && !empty(trim($get_kode_guru))) {
                            call_content("hasil", "detail-guru");
                        } else {
                            call_content("hasil", "detail");
                        }
                    elseif ($page == "grafik"):
                        call_content("hasil", "grafik");
                    else:
                        require_once("content/404.php");
                    endif;

                ?>

            </div>
            <!-- End of Main Content -->

            <?php require_once("templates/footer.php"); ?>

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Konfirmasi Logout.</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Apakah Anda yakin akan keluar dari Aplikasi ini. ?</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-danger" href="<?php echo base_url("admin/logout") ?>">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="<?php echo base_url("assets/vendor/jquery/jquery.min.js") ?>"></script>
    <script src="<?php echo base_url("assets/vendor/bootstrap/js/bootstrap.bundle.min.js") ?>"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?php echo base_url("assets/vendor/jquery-easing/jquery.easing.min.js") ?>"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?php echo base_url("assets/js/sb-admin-2.min.js") ?>"></script>

    <!-- Page level plugins -->
    <script src="<?php echo base_url("assets/vendor/chart.js/Chart.min.js") ?>"></script>

    <script src="<?php echo base_url("assets/vendor/datatables/jquery.dataTables.min.js") ?>"></script>
    <script src="<?php echo base_url("assets/vendor/datatables/dataTables.bootstrap4.min.js") ?>"></script>
    <script src="<?php echo base_url("assets/vendor/select2/select2.min.js") ?>"></script>

    <!-- Page level custom scripts -->
    <script src="<?php echo base_url("assets/vendor/chart.js/chartjs-plugin-labels.js") ?>"></script>
    <script src="<?php echo base_url("assets/js/demo/datatables-demo.js") ?>"></script>
    <script type="text/javascript" src="<?= base_url('assets/vendor/sweetalert2/sweetalert2.min.js') ?>"></script>
    <script>
        $(document).ready(function() {
            $("a[data-toggle=tooltip]").tooltip();
            $("#list-kelas, #list-mapel, #list-guru").select2({
                theme: 'bootstrap4'
            });
            $("#list-kelas, #list-mapel, #list-guru").on("select2:select", function (evt) {
                var element = evt.params.data.element;
                var $element = $(element);

                $element.detach();
                $(this).append($element);
                $(this).trigger("change");
            });
            $('#data').DataTable({
                "ordering": false,
                "info":     false,
                "pageLength": 5
            });
            $("#data2").DataTable();
            $("#data_wrapper > .row:first-child").remove();
            $(".paging_simple_numbers").addClass('float-right');
            <?php if($page === 'responden' && $action === 'not-responding'):?>
                $("#filter_kelas").on('change', function() {
                    var kelas = $("#filter_kelas > option:selected").val();
                    if(kelas != '') {
                        window.location='<?= base_url("admin/" . $page . "/" . $action) ?>' + '/kelas/' + kelas;
                    } else {
                        window.location='<?= base_url("admin/" . $page . "/" . $action) ?>';
                    }
                });
                $("#filter_jenjang").on('change', function() {
                    var jenjang = $("#filter_jenjang > option:selected").val();
                    if(jenjang != '') {
                        window.location='<?= base_url("admin/" . $page . "/" . $action) ?>' + '/jenjang/' + jenjang;
                    } else {
                        window.location='<?= base_url("admin/" . $page . "/" . $action) ?>';
                    }
                });
            <?php endif; ?>
        });
        function confirmDelete() {
            let ask = confirm("Apakah Anda yakin akan menghapus data ini?");
            return ask;
        }
    </script>
    <?php if ($page == ""): ?>
        <script type="text/javascript">
            // Set new default font family and font color to mimic Bootstrap's default styling
            Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
            Chart.defaults.global.defaultFontColor = '#858796';

            // Pie Chart Example
            var ctx = document.getElementById("myPieChart");
            var myPieChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: <?php echo json_encode($data_nilai_aspek['label']); ?>,
                    datasets: [{
                        data: <?php echo json_encode($data_nilai_aspek['nilai']); ?>,
                        backgroundColor: <?php echo json_encode($data_colors); ?>,
                    }],
                },
                options: {
                    plugins: {
                        labels:{                        
                            render: 'value',
                            fontSize: 14,
                            fontStyle: 'bold',
                            fontColor: '#000',
                        }
                    },
                    maintainAspectRatio: false,
                    tooltips: {
                        backgroundColor: "rgb(255,255,255)",
                        bodyFontColor: "#858796",
                        borderColor: '#dddfeb',
                        borderWidth: 1,
                        xPadding: 15,
                        yPadding: 15,
                        displayColors: true,
                        caretPadding: 10,
                    },
                    legend: {
                        display: false
                    },
                    cutoutPercentage: 50,
                },
            });

            // Bar Chart Example
            var ctx = document.getElementById("myBarChart");
            var myBarChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: <?php echo json_encode($guru_arr['nama']); ?>,
                    datasets: [{
                        label: "Total Nilai (%)",
                        backgroundColor: "#4e73df",
                        hoverBackgroundColor: "#2e59d9",
                        borderColor: "#4e73df",
                        data: <?php echo json_encode($guru_arr['jumlah']); ?>,
                    }],
                },
                options: {
                    plugins: {
                        labels:{                        
                            render: 'value',
                            fontSize: 14,
                            fontStyle: 'bold',
                            fontColor: '#000',
                        }
                    },
                    maintainAspectRatio: false,
                    layout: {
                        padding: {
                            left: 10,
                            right: 25,
                            top: 25,
                            bottom: 0
                        }
                    },
                    scales: {
                        xAxes: [{
                            time: {
                                unit: 'month'
                            },
                            gridLines: {
                                display: false,
                                drawBorder: false
                            },
                            ticks: {
                                maxTicksLimit: 6
                            },
                            maxBarThickness: 25,
                        }],
                        yAxes: [{
                            gridLines: {
                                color: "rgb(234, 236, 244)",
                                zeroLineColor: "rgb(234, 236, 244)",
                                drawBorder: false,
                                borderDash: [2],
                                zeroLineBorderDash: [2]
                            }
                        }],
                    },
                    legend: {
                        display: false
                    },
                    tooltips: {
                        titleMarginBottom: 10,
                        titleFontColor: '#6e707e',
                        titleFontSize: 14,
                        backgroundColor: "rgb(255,255,255)",
                        bodyFontColor: "#858796",
                        borderColor: '#dddfeb',
                        borderWidth: 1,
                        xPadding: 15,
                        yPadding: 15,
                        displayColors: false,
                        caretPadding: 10,
                    },
                }
            });
        </script>
    <?php endif ?>
 
</body>

</html>
