<?php
require 'fungsi/fungsi.php';
session_start();

if (!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit;
}

$riwayat = query("SELECT * FROM riwayat_pinjam ORDER BY tgl_pinjam DESC");
$pinjam = query("SELECT * FROM peminjaman");

$q1 = mysqli_query($konek, "SELECT COUNT(*) AS jml FROM daftar_buku");
$jumlahBuku = mysqli_fetch_assoc($q1);

$q2 = mysqli_query($konek, "SELECT COUNT(*) AS total FROM daftar_anggota");
$jumlahAnggota = mysqli_fetch_assoc($q2);

$q3 = mysqli_query($konek, "SELECT COUNT(*) AS total FROM peminjaman");
$jumlahBukuPinjam = mysqli_fetch_assoc($q3);

$q4 = mysqli_query($konek, "SELECT COUNT(*) AS total FROM riwayat_pinjam");
$jumlahRiwayat = mysqli_fetch_assoc($q4);

if (isset($_POST["btnCari"])) {
    $riwayat = cariRiwayat($_POST["pencarian"]);
} elseif (isset($_POST["btnReset"])) {
    $riwayat;
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"
        integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- <link rel="stylesheet" href="//use.fontawesome.com/releases/v5.0.7/css/all.css"> -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css" />
    <link rel="stylesheet" href="css/style.css">

    <title>Perpustakaan SDN Catur</title>
    <link rel="icon" href="../image/logo.png">

    <style>
        #main-content {
            transition: 0.4s;
            /* height: 1800px !important; */
        }

        body {
            background-color: #eee;
        }

        .card-body {
            color: #100F0F !important;
        }

        h1 {
            color: #023047 !important;
        }

        .nav-link:hover {
            background-color: #7B8FA1 !important;
        }

        .nav-link .fa {
            transition: all 1s;
        }

        .nav-link:hover .fa {
            transform: rotate(360deg);
        }

        .mar {
            margin: 5px 10px 5px !important;
            font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
        }

        .margin-log {
            margin-top: 280px !important;
        }

        .navbar-brand {
            color: #ffffff !important;
            padding-top: 0px;
            padding-bottom: 0px;
            font-size: 22px !important;
            font-weight: normal !important;
            font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
        }

        .navbar-brand span {
            color: #e63946;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
            font-size: 40px;
        }

        .btn-outline-secondary {
            border-color: #8064A2 !important;
        }

        .btn-outline-secondary:hover {
            background-color: #023047 !important;
            color: antiquewhite !important;
        }

        .btn-outline-secondary:active {
            background-color: #023047 !important;
            color: antiquewhite !important;
        }

        .btn-outline-secondary:visited {
            background-color: #023047 !important;
            color: antiquewhite !important;
        }

        .my-custom-scrollbar {
            position: relative;
            height: 300px;
            overflow: auto;
            padding-top: 8px;
        }

        .table-wrapper-scroll-y {
            display: block;
        }

        .ctr {
            color: white;
        }

        .utama {
            padding-bottom: 10px !important;
        }

        .order-card {
            color: #fff;
        }

        .bg-c-blue {
            background: linear-gradient(45deg, #567189, #BDCDD6);
        }

        .bg-c-green {
            background: linear-gradient(45deg, #F2921D, #FFDB89);
        }

        .bg-c-yellow {
            background: linear-gradient(45deg, #0E8388, #609EA2);
        }

        .bg-c-pink {
            background: linear-gradient(45deg, #FF5370, #ff869a);
        }


        .card {
            border-radius: 5px;
            -webkit-box-shadow: 0 1px 2.94px 0.06px rgba(4, 26, 55, 0.16);
            box-shadow: 0 1px 2.94px 0.06px rgba(4, 26, 55, 0.16);
            border: none;
            margin-bottom: 30px;
            -webkit-transition: all 0.3s ease-in-out;
            transition: all 0.3s ease-in-out;
        }

        .card .card-block {
            padding: 25px;
        }

        .order-card i {
            font-size: 26px;
        }

        .f-left {
            float: left;
        }

        .f-right {
            float: right;
        }

        .cardku {
            height: 400px;
        }

        .bg-c-notif {
            background: linear-gradient(90deg, #023047, #528097);
        }

        .bg-c-cetak {
            background: linear-gradient(90deg, #528097, #023047);
        }
    </style>
</head>

<body>

    <div>
        <div class="sidebar p-4 d-flex flex-column vh-100 flex-shrink-0 p-3 text-white bg-dark" id="sidebar">
            <a class="sidebar-brand logo-sbn">
                <img src="img/ico.png" alt="logo" height="44" style="padding-right: 20px; padding-bottom: 2px;">
            </a>

            <a class="judul-sbn navbar-brand" style="" href="#myPage">Perpustakaan<span>.</span></a>

            <div class="margin-set">
                <ul class="nav nav-pills flex-column mb-auto">
                    <li class="mar">
                        <a class="nav-link text-white active" href="index.php">
                            <i class="fa fa-home ic mr-2"></i>
                            Dashboard
                        </a>
                    </li>
                    <li class="mar">
                        <a class="nav-link text-white" href="anggota.php">
                            <i class="fa fa-users ic mr-2"></i>Data Anggota
                        </a>
                    </li>
                    <li class="mar">
                        <a class="nav-link text-white" href="buku.php">
                            <i class="fa fa-book ic mr-2"></i>
                            Data Buku
                        </a>
                    </li>
                    <li class="mar">
                        <a class="nav-link text-white" href="transaksi.php">
                            <i class="fa fa-money-bill-transfer ic mr-2"></i>Transaksi
                        </a>
                    </li>
                    <li class="mar">
                        <a class="nav-link text-white" href="pinjam.php">
                            <i class="fa fa-arrows-turn-to-dots ic mr-2"></i>Peminjaman
                        </a>
                    </li>
                    <li class="mar">
                        <a class="nav-link text-white" href="riwayat.php">
                            <i class="fa fa-clock-rotate-left ic mr-2"></i>
                            Riwayat
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <section class="p-4" id="main-content">
        <div class="row">
            <div class="col-md-1">
                <button class="btn btn-outline-secondary mc" id="button-toggle">
                    <i class="bi bi-list"></i>
                </button>
            </div>
            <div class="col-md-9 float-start">
                <h1><b>PERPUSTAKAAN</b></h1>
                <p style="margin-bottom: -7px; margin-top: -5px; color: #7B8FA1 !important;">SDN Catur </p>
            </div>
            <div class="col-md-2">
                <a href="logout.php" class="btn btn-outline-secondary mc float-end" id="button-toggle">
                    <i class="fa fa-right-from-bracket" style="color: grey;"></i>
                </a>
            </div>
        </div>

        <div class="container mt-4">
            <div class="row dataku">
                <div class="col-md-4 col-xl-3">
                    <a href="buku.php">
                        <div class="card bg-c-blue order-card">
                            <div class="card-block">
                                <h6 class="m-b-20">Buku</h6>
                                <h2 class="text-right"><i class="fa fa-book f-left"></i><span>
                                        <?= $jumlahBuku["jml"] ?>
                                    </span></h2>
                                <p class="m-b-0">Judul<span class="f-right"><i
                                            class="fa-solid fa-caret-right"></i></span>
                                </p>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-md-4 col-xl-3">
                    <a href="anggota.php">
                        <div class="card bg-c-green order-card">
                            <div class="card-block">
                                <h6 class="m-b-20">Anggota</h6>
                                <h2 class="text-right"><i class="fa fa-users f-left"></i><span>
                                        <?= $jumlahAnggota["total"]; ?>
                                    </span></h2>
                                <p class="m-b-0">Orang<span class="f-right"><i
                                            class="fa-solid fa-caret-right"></i></span>
                                </p>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-md-4 col-xl-3">
                    <a href="pinjam.php">
                        <div class="card bg-c-yellow order-card">
                            <div class="card-block">
                                <h6 class="m-b-20">Peminjaman</h6>
                                <h2 class="text-right"><i class="fa fa-money-bill-transfer f-left"></i><span>
                                        <?= $jumlahBukuPinjam["total"]; ?>
                                    </span></h2>
                                <p class="m-b-0">Buku dipinjam<span class="f-right"><i
                                            class="fa-solid fa-caret-right"></i></span></p>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-md-4 col-xl-3">
                    <a href="riwayat.php">
                        <div class="card bg-c-pink order-card">
                            <div class="card-block">
                                <h6 class="m-b-20">Riwayat</h6>
                                <h2 class="text-right"><i class="fa fa-clock-rotate-left f-left"></i><span>
                                        <?= $jumlahRiwayat["total"]; ?>
                                    </span></h2>
                                <p class="m-b-0">Transaksi<span class="f-right"><i
                                            class="fa-solid fa-caret-right"></i></span></p>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-md-9">
                    <div class="card cardku bg-c-notif order-card">
                        <div class="card-block">
                            <h4 class="m-b-20" style="padding-bottom: 15px;"><i class="fa-solid fa-bell"></i> Notifikasi
                                Peminjaman</h4>

                            <div class="my-custom-scrollbar">
                                <?php foreach ($pinjam as $row) {
                                    $date_now = date("Y-m-d");
                                    $tg_kembali = $row["tanggal_kembali"];
                                    $nama = $row["nama"];
                                    $kelas = $row["kelas"];
                                    $judul = $row["judul"];

                                    if ($date_now > $tg_kembali && $kelas != "guru"): ?>
                                        <div class="alert alert-danger d-flex align-items-center" role="alert">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                                                class="bi bi-exclamation-triangle-fill flex-shrink-0 me-2" viewBox="0 0 16 16"
                                                role="img" aria-label="Warning:">
                                                <path
                                                    d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                                            </svg>
                                            <div>
                                                Siswa
                                                <?= $nama; ?> belum mengembalikan buku
                                                <?= $judul; ?>, jatuh tempo pada tanggal
                                                <?= $tg_kembali; ?>
                                            </div>
                                        </div>
                                    <?php endif;
                                } ?>
                            </div>


                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card cardku bg-c-cetak order-card">
                        <div class="card-block">
                            <h4 class="m-b-20" style="padding-bottom: 15px;"><i class="fa-solid fa-print"></i>
                                Cetak</h4>
                            <div class="text-center" style="padding-top: 0px;">
                                <h5>Kartu Peminjaman</h5>
                                <button class="btn btn-danger btn-lg"
                                    style="padding-top: 12px; padding-bottom: 18px; padding-left: 25px; padding-right: 25px;"
                                    href="" onclick="OpenInNewTabb();"><i
                                        class="fa-solid fa-id-card-clip fa-2xl"></i></button>
                            </div>
                            <div class="text-center" style="padding-top: 15px;">
                                <h5>Daftar Buku</h5>
                                <button class="btn btn-danger btn-lg"
                                    style="padding-top: 12px; padding-bottom: 18px; padding-left: 27px; padding-right: 27px;"
                                    href="" onclick="cetakBuku();"><i class="fa-solid fa fa-book fa-2xl"></i></button>
                            </div>
                            <div class="text-center" style="padding-top: 15px;">
                                <h5>Daftar Anggota</h5>
                                <button class="btn btn-danger btn-lg"
                                    style="padding-top: 12px; padding-bottom: 18px; padding-left: 23px; padding-right: 23px;"
                                    href="" onclick="cetakAnggota();"><i class="fa-solid fa-users fa-2xl"></i></button>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>

    </section>

    <script>

        // event will be executed when the toggle-button is clicked
        document.getElementById("button-toggle").addEventListener("click", () => {

            // when the button-toggle is clicked, it will add/remove the active-sidebar class
            document.getElementById("sidebar").classList.toggle("active-sidebar");

            // when the button-toggle is clicked, it will add/remove the active-main-content class
            document.getElementById("main-content").classList.toggle("active-main-content");
        });


        function OpenInNewTabb(url) {
            var win = window.open('kartu/kartu.pdf', '_blank');
            win.focus();
        }
        function cetakBuku(url) {
            var win = window.open('fungsi/cetak_buku.php', '_blank');
            win.focus();
        }
        function cetakAnggota(url) {
            var win = window.open('fungsi/cetak_anggota.php', '_blank');
            win.focus();
        }
    </script>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
        crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
        crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
        crossorigin="anonymous"></script>

</body>

</html>