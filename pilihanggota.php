<?php
require 'fungsi/fungsi.php';

session_start();

if (!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit;
}

$anggota = query("SELECT * FROM daftar_anggota ORDER BY kelas");



$buku = query("SELECT * FROM daftar_buku");

if (isset($_POST["btnCari"])) {
    $anggota = cariDataAnggota($_POST["pencarian"]);
} elseif (isset($_POST["btnReset"])) {
    $anggota;
}

if (isset($_GET["pilih"]) || isset($_GET["judul"])) {
    $id_buku = $_GET["pilih"];
    $judul_buku = $_GET["judul"];
} else {
    echo "
     <script>
        alert('Silahkan pilih buku terlebih dahulu')
     </script>
    ";
    header('Location: transaksi.php');
}

if (isset($_POST["proses"])) {
    if (validasiTanggal($_POST["tanggalambil"])) {
        if (validasiTanggal($_POST["tanggalkembali"])) {
            if (pinjam($_POST)) {
                if (updateStok($_POST)) {
                    header('Location: transaksi.php');
                }
            }
        } else {
            echo "
                <script>
                    alert('Data tidak berhasil di proses, masukkan tanggal kembali dengan benar !')
                </script>
                ";
        }
    } else {
        echo "
            <script>
                alert('Data tidak berhasil di proses, masukkan tanggal pinjam dengan benar !')
            </script>
            ";
    }
} elseif (isset($_POST["prosesGuru"])) {
    if (pinjam($_POST)) {
        if (updateStok($_POST)) {
            header('Location: transaksi.php');
        }
    }
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
    </style>
</head>

<body>

    <div>
        <div class="sidebar p-4 d-flex flex-column vh-100 flex-shrink-0 p-3 text-white bg-dark" id="sidebar">
            <a class="sidebar-brand logo-sbn">
                <img src="img/logo2.png" alt="logo" height="75" style="padding-right: 20px; padding-bottom: 2px;">
            </a>

            <a class="judul-sbn navbar-brand" style="" href="#myPage">Perpustakaan<span>.</span></a>

            <div class="margin-set">
                <ul class="nav nav-pills flex-column mb-auto">
                    <li class="mar">
                        <a class="nav-link text-white" href="index.php">
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
                        <a class="nav-link text-white active" href="transaksi.php">
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
                <h1><b>TRANSAKSI</b></h1>
                <p style="margin-bottom: -7px; margin-top: -5px; color: #7B8FA1 !important;">SDN Catur </p>
            </div>
            <div class="col-md-2">
                <a href="logout.php" class="btn btn-outline-secondary mc float-end" id="button-toggle">
                    <i class="fa fa-right-from-bracket"></i>
                </a>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <p style="margin-bottom: -7px; margin-top: -5px; color: #7B8FA1 !important; float: right;">pilih
                        buku <i class="fa-solid fa-angle-right fa-sm" style="color: grey;"></i> pilih anggota
                    </p>
                </div>
            </div>
        </div>
        <div class="card mt-4">
            <div class="card-body">
                <div class="row dataku">
                    <div class="col-md-12">
                        <div class="col-md-12 text-center" style="padding-bottom: 20px;">
                            <h1><b>PILIH ANGGOTA</b></h1>
                        </div>
                        <form action="" method="post">
                            <div class="row utama">
                                <div class="col-md-1">
                                    <label style="margin-top: 5px;" for="cari"><b>Cari buku</b></label>
                                </div>
                                <div class="col-md-2">
                                    <input type="text" class="form-control float-start"
                                        style="margin-right: 0px; margin-left: 0px;" name="pencarian" id="cari"
                                        placeholder="masukkan pencarian">
                                </div>
                                <div class="col-md-4" style="margin-left: 0px;">
                                    <button type="submit" class="btn btn-primary float-start" href=""
                                        name="btnCari">CARI</button>
                                    <button type="submit" class="btn btn-danger" href="" name="btnReset"
                                        style="margin-left: 4px;">RESET</button>
                                </div>
                                <div class="col-md-5" style="margin-left: 0px;">
                                    <!-- <button class="btn btn-primary" type="submit" style="float: right;"
                                        name="pinjamGuru" data-toggle="modal" data-target="#transGuru">GURU</button> -->
                                    <button type="button" class="btn btn-primary float-end" data-toggle="modal"
                                        data-target="#transGuru"><i class="fa-solid fa-chalkboard-user"></i>
                                        GURU</button>
                                </div>
                            </div>
                        </form>

                        <!-- modal input data -->
                        <div id="transGuru" class="modal fade" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="text-secondary">PINJAM GURU</h4>
                                        <button type="button" class="btn-close" data-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="" method="post" enctype="multipart/form-data">
                                            <input type="hidden" name="id" value="-">
                                            <input type="hidden" name="kelas" value="guru">
                                            <input type="hidden" name="idBuku" value="<?= $id_buku; ?>">
                                            <input type="hidden" name="judul" value="<?= $judul_buku; ?>">
                                            <input type="hidden" name="tanggalambil" value="-">
                                            <input type="hidden" name="tanggalkembali" value="-">

                                            <label for="nama">Nama Guru</label>
                                            <input type="text" class="form-control" name="nama" id="nama">

                                            <label for="kelas" style="padding-top: 12px;">Kelas</label>
                                            <input type="text" class="form-control" name="kelas" id="kelas" value="guru"
                                                disabled>

                                            <label for="judul" style="padding-top: 12px;">Judul
                                                Buku</label>
                                            <input type="text" class="form-control" name="judul" id="judul"
                                                value="<?= $judul_buku; ?>" disabled>

                                            <label for="tanggalambil" style="padding-top: 12px;">Tanggal
                                                Ambil</label>
                                            <input class="form-control" type="text" name="tanggalambil"
                                                id="tanggalambil" value="-" disabled>

                                            <label for="tanggalkembali" style="padding-top: 12px;">Tanggal
                                                Kembali</label>
                                            <input class="form-control" type="text" name="tanggalkembali"
                                                id="tanggalKembali" value="-" disabled>

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger" data-dismiss="modal"><i
                                                class="fa fa-ban"></i>
                                            CANCEL</button>
                                        <button type="submit" class="btn btn-primary" name="prosesGuru"><i
                                                class="fa fa-arrows-turn-right"></i>
                                            PROSES</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>



                        <div class="col-md-12 table-responsive-md">
                            <div class="table-wrapper-scroll-y my-custom-scrollbar">
                                <table class="table table-hover">
                                    <thead class="thead-dark" style="background-color: #023047; color: #ffffff;">
                                        <tr>
                                            <th class="ctr">NO</th>
                                            <th class="ctr">ID ANGGOTA</th>
                                            <th class="ctr">NAMA</th>
                                            <th class="ctr">KELAS</th>
                                            <th class="ctr">AKSI</th>
                                        </tr>
                                    </thead>
                                    <?php $i = 1; ?>
                                    <?php foreach ($anggota as $row): ?>
                                        <tbody>
                                            <tr>
                                                <!-- <form action="" method="GET"> -->
                                                <input type="hidden" name="id" value="<?= $row["id"]; ?>">
                                                <input type="hidden" name="nama" value="<?= $row["nama"]; ?>">
                                                <td class="align-middle text-center">
                                                    <?= $i; ?>
                                                </td>
                                                <td class="align-middle text-center">
                                                    <?= $row["id"]; ?>
                                                </td>
                                                <td class="align-middle text-center">
                                                    <?= $row["nama"]; ?>
                                                </td>
                                                <td class="align-middle text-center">
                                                    <?= $row["kelas"]; ?>
                                                </td>
                                                <td class="text-center text-center">
                                                    <button style="margin-right: 5px;" type="submit"
                                                        class="btn btn-danger btn-sm" name="ubah" data-toggle="modal"
                                                        data-target="#transModal<?= $row["id"]; ?>" href=""><i
                                                            class="fa fa-circle-check"></i> PILIH</button>
                                                </td>

                                                <!-- </form> -->
                                            </tr>

                                            <!-- modal edit data -->
                                            <div id="transModal<?= $row["id"]; ?>" class="modal fade" tabindex="	-1">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h4 class="text-secondary">KONFIRMASI PINJAM</h4>
                                                            <button type="button" class="btn-close" data-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form action="" method="post" enctype="multipart/form-data">
                                                                <input type="hidden" name="id" value="<?= $row["id"]; ?>">
                                                                <input type="hidden" name="idBuku" value="<?= $id_buku; ?>">
                                                                <input type="hidden" name="nama"
                                                                    value="<?= $row["nama"] ?>">
                                                                <input type="hidden" name="kelas"
                                                                    value="<?= $row["kelas"]; ?>">
                                                                <input type="hidden" name="judul"
                                                                    value="<?= $judul_buku; ?>">

                                                                <label for="nama">Nama</label>
                                                                <input type="text" class="form-control" name="nama"
                                                                    id="nama" value="<?= $row["nama"]; ?>" disabled>

                                                                <label for="kelas" style="padding-top: 12px;">Kelas</label>
                                                                <input type="text" class="form-control" name="kelas"
                                                                    id="kelas" value="<?= $row["kelas"]; ?>" disabled>

                                                                <label for="judul" style="padding-top: 12px;">Judul
                                                                    Buku</label>
                                                                <input type="text" class="form-control" name="judul"
                                                                    id="judul" value="<?= $judul_buku; ?>" disabled>

                                                                <label for="tanggalambil" style="padding-top: 12px;">Tanggal
                                                                    Ambil</label>
                                                                <input class="form-control" type="date" name="tanggalambil"
                                                                    id="tanggalambil">

                                                                <label for="tanggalkembali"
                                                                    style="padding-top: 12px;">Tanggal
                                                                    Kembali</label>
                                                                <input class="form-control" type="date"
                                                                    name="tanggalkembali" id="tanggalKembali">

                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-danger"
                                                                data-dismiss="modal"><i class="fa fa-ban"></i>
                                                                CANCEL</button>
                                                            <button type="submit" class="btn btn-primary" name="proses"><i
                                                                    class="fa fa-arrows-turn-right"></i>
                                                                PROSES</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <?php $i++; ?>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>


                    </div>


                </div>
                <div class="row">
                    <div class="col-md-4" style="padding-top: 100px;">
                        <a class="btn btn-danger" href="transaksi.php"><i class="fa fa-chevron-left"></i><b>
                                KEMBALI</b></a>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
        crossorigin="anonymous"></script>

</body>

</html>