<?php
$index = "";
$data_guru = "";
$data_siswa = "";
$data_kelas = "";
$data_mapel = "";
$responden = "";
$aspek_penilaian = "";
$hasil_penilaian = "";
$detail_penilaian = "";
$grafik = "";

if($page == "") {
    $index = "active";
} elseif($page == "data-guru") {
    $data_guru = "active";
} elseif($page == "data-siswa") {
    $data_siswa = "active";
} elseif ($page == "data-kelas") {
    $data_kelas = "active";
} elseif ($page == "aspek-penilaian") {
    $aspek_penilaian = "active";
} elseif ($page == "aspek-penilaian") {
    $aspek_penilaian = "active";
} elseif ($page == "responden") {
    $responden = "active";
} elseif ($page == "hasil-penilaian") {
    $hasil_penilaian = "active";
} elseif ($page == "detail-penilaian" || $page == "detail-penilaian-guru") {
    $detail_penilaian = "active";
} elseif ($page == "grafik") {
    $grafik = "active";
}

?>
<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar" style="position: fixed;">
    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="#">
        <!-- <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-smile-wink"></i>
        </div> -->
        <div class="sidebar-brand-icon" style="-webkit-transform: rotate(-15deg);
           -moz-transform: rotate(-15deg);
            -ms-transform: rotate(-15deg);
             -o-transform: rotate(-15deg);
                transform: rotate(-15deg);">
            <img src="<?php echo base_url("assets/img/logo.png") ?>" alt="" style="width:60%;">
        </div>
        <div class="sidebar-brand-text mx-3">SKP System</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item <?= $index; ?>">
        <a class="nav-link" href="<?php echo base_url("admin/index.php") ?>">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider">

        <!-- Heading -->
        <div class="sidebar-heading">
            Data Master
        </div>

        <!-- Nav Item - Pages Collapse Menu -->
        <li class="nav-item <?php echo $data_guru; ?>">
            <a class="nav-link" href="<?php echo base_url("admin/data-guru") ?>">
                <i class="fas fa-fw fa-users"></i>
                <span>Data Guru</span>
            </a>
        </li>

        <!-- Nav Item - Pages Collapse Menu -->
        <li class="nav-item <?php echo $data_siswa; ?>">
            <a class="nav-link" href="<?php echo base_url("admin/data-siswa") ?>">
                <i class="fas fa-fw fa-user"></i>
                <span>Data Siswa</span>
            </a>
        </li>

        <!-- Nav Item - Utilities Collapse Menu -->
        <li class="nav-item <?php echo $data_kelas; ?>">
            <a class="nav-link" href="<?php echo base_url("admin/data-kelas") ?>">
                <i class="fas fa-fw fa-home"></i>
                <span>Data Kelas</span>
            </a>
        </li>

        <li class="nav-item <?php echo $data_mapel; ?>">
            <a class="nav-link" href="<?php echo base_url("admin/mata-pelajaran") ?>">
                <i class="fas fa-fw fa-book"></i>
                <span>Data Mata Pelajaran</span>
            </a>
        </li>

        <li class="nav-item <?php echo $aspek_penilaian; ?>">
            <a class="nav-link" href="<?php echo base_url("admin/aspek-penilaian") ?>">
                <i class="fas fa-fw fa-list"></i>
                <span>Aspek Penilaian</span>
            </a>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider">

        <!-- Heading -->
        <div class="sidebar-heading">
            HASIL SURVEI / POLLING
        </div>

        <!-- Nav Item - Pages Collapse Menu -->
        <li class="nav-item <?php echo $data_siswa; ?>">
            <a class="nav-link" href="<?php echo base_url("admin/responden") ?>">
                <i class="fas fa-fw fa-users"></i>
                <span>Responden</span>
            </a>
        </li>

        <!-- Nav Item - Charts -->
        <li class="nav-item <?php echo $hasil_penilaian; ?>">
            <a class="nav-link" href="<?php echo base_url("admin/hasil-penilaian") ?>">
                <i class="fas fa-fw fa-file"></i>
                <span>Hasil Penilaian</span>
            </a>
        </li>
        <li class="nav-item <?php echo $detail_penilaian; ?>">
            <a class="nav-link" href="<?php echo base_url("admin/detail-penilaian") ?>">
                <i class="fas fa-fw fa-info-circle"></i>
                <span>Detail Penilaian</span>
            </a>
        </li>
        <!-- Divider -->
        <?php if (getSessionUser('level') == 1): ?>
        <hr class="sidebar-divider">
        <!-- Heading -->
        <div class="sidebar-heading">
            PENGATURAN
        </div>
        <li class="nav-item <?php echo $detail_penilaian; ?>">
            <a class="nav-link" href="<?php echo base_url("admin/user-management") ?>">
                <i class="fas fa-fw fa-users"></i>
                <span>Manajemen User</span>
            </a>
        </li>
        <?php endif ?>

        <!-- Nav Item - Tables -->
        <li class="nav-item">
            <a class="nav-link" href="#" data-toggle="modal" data-target="#logoutModal">
                <i class="fas fa-fw fa-sign-out-alt"></i>
                <span>Logout</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
<!-- End of Sidebar -->