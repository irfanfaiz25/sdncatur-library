<?php
require 'fungsi/fungsi.php';

session_start();

if (!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit;
}

date_default_timezone_set('Asia/Jakarta');
$hariIni = date("Y-m-d");

$pinjam = query("SELECT * FROM peminjaman ORDER BY id_pinjam DESC");
$pinjamSemua = query("SELECT * FROM peminjaman");

if (isset($_POST["btnCari"])) {
    // if (!isset($_POST["tgawal"]) && !isset($_POST["tgakhir"])) 
    $pinjam = cariDataPinjam($_POST["pencarian"]);
} elseif (isset($_POST["btnReset"])) {
    $pinjam;
} else if (isset($_POST["cariTgl"])) {
    $pinjam = cariTglPinjam($_POST);
}



if (isset($_POST["selesai"])) {
    if (validasiTanggal($_POST["dikembalikan"])) {
        if (kembali($_POST)) {
            if (updateSelesai($_POST)) {
                header('Location: pinjam.php');
            }
        } else {
            echo "
                <script>
                    alert('Data gagal di proses !')
                </script>
            ";
        }
    } else {
        echo "
            <script>
                alert('Masukkan tanggal di kembalikan dengan benar !')
            </script>
        ";
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
            height: 410px;
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

        .card {
            height: 620px;
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
                        <a class="nav-link text-white" href="transaksi.php">
                            <i class="fa fa-money-bill-transfer ic mr-2"></i>Transaksi
                        </a>
                    </li>
                    <li class="mar">
                        <a class="nav-link text-white active" href="pinjam.php">
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
                <h1><b>PEMINJAMAN</b></h1>
                <p style="margin-bottom: -7px; margin-top: -5px; color: #7B8FA1 !important;">SDN Catur </p>
            </div>
            <div class="col-md-2">
                <a href="logout.php" class="btn btn-outline-secondary mc float-end" id="button-toggle">
                    <i class="fa fa-right-from-bracket" style="color: grey;"></i>
                </a>
            </div>
        </div>
        <div class="card mt-4">
            <div class="card-body">
                <div class="row dataku">
                    <div class="col-md-12">
                        <div class="col-md-12 text-center" style="padding-bottom: 20px;">
                            <h1><b>DATA PEMINJAMAN</b></h1>
                        </div>
                        <form action="" method="post">
                            <div class="row utama">
                                <div class="col-md-1">
                                    <label style="margin-top: 5px;" for="cari"><b>Cari data</b></label>
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
                                <div class="col-md-2" style="margin-left: 0px;">
                                    <input class="form-control" placeholder="tanggal awal" onfocus="(this.type='date')"
                                        onblur="(this.type='text')" type="text" name="tgawal" id="tgawal">
                                </div>
                                <div class="col-md-2" style="margin-left: 0px;">
                                    <input class="form-control" placeholder="tanggal akhir" onfocus="(this.type='date')"
                                        onblur="(this.type='text')" type="text" name="tgakhir" id="tgakhir">
                                </div>
                                <div class="col-md-1" style="margin-left: 0px;">
                                    <button class="btn btn-primary" type="submit" name="cariTgl">CARI</button>
                                </div>
                        </form>
                    </div>


                    <div class="col-md-12 table-responsive-md">
                        <div class="table-wrapper-scroll-y my-custom-scrollbar">
                            <table class="table table-hover">
                                <thead class="thead-dark" style="background-color: #023047; color: #ffffff;">
                                    <tr>
                                        <th class="ctr">NO</th>
                                        <th class="ctr">ID PINJAM</th>
                                        <th class="ctr">KODE BUKU</th>
                                        <th class="ctr">NISN</th>
                                        <th class="ctr">NAMA</th>
                                        <th class="ctr">KELAS</th>
                                        <th class="ctr">JUDUL</th>
                                        <th class="ctr">TANGGAL PINJAM</th>
                                        <th class="ctr">TANGGAL KEMBALI</th>
                                        <th class="ctr">KETERANGAN</th>
                                    </tr>
                                </thead>
                                <?php $i = 1; ?>
                                <?php foreach ($pinjam as $row): ?>
                                    <tbody>
                                        <tr>
                                            <!-- <form action="" method="GET"> -->
                                            <input type="hidden" name="id" value="<?= $row["id_pinjam"]; ?>">
                                            <input type="hidden" name="judul" value="<?= $row["judul"]; ?>">
                                            <td class="align-middle text-center">
                                                <?= $i; ?>
                                            </td>
                                            <td class="align-middle text-center">
                                                <?= $row["id_pinjam"]; ?>
                                            </td>
                                            <td class="align-middle text-center">
                                                <?= $row["id_buku"]; ?>
                                            </td>
                                            <td class="align-middle text-center">
                                                <?= $row["id_anggota"]; ?>
                                            </td>
                                            <td class="align-middle text-center">
                                                <?= $row["nama"]; ?>
                                            </td>
                                            <td class="align-middle text-center">
                                                <?= $row["kelas"]; ?>
                                            </td>
                                            <td class="align-middle text-center">
                                                <?= $row["judul"]; ?>
                                            </td>
                                            <td class="align-middle text-center">
                                                <?= $row["tanggal_pinjam"]; ?>
                                            </td>
                                            <td class="align-middle text-center">
                                                <?= $row["tanggal_kembali"]; ?>
                                            </td>
                                            <td class="text-center text-center">
                                                <button style="margin-right: 5px;" type="submit"
                                                    class="btn btn-success btn-sm" name="ubah" data-toggle="modal"
                                                    data-target="#transModal<?= $row["id_pinjam"]; ?>" href=""><i
                                                        class="fa fa-circle-check"></i> SELESAI</button>
                                            </td>

                                            <!-- </form> -->
                                        </tr>

                                        <!-- modal edit data -->
                                        <div id="transModal<?= $row["id_pinjam"]; ?>" class="modal fade" tabindex="	-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="text-secondary">KONFIRMASI PINJAMAN SELESAI</h4>
                                                        <button type="button" class="btn-close" data-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="" method="post" enctype="multipart/form-data">
                                                            <input type="hidden" name="id"
                                                                value="<?= $row["id_pinjam"]; ?>">
                                                            <input type="hidden" name="idBuku"
                                                                value="<?= $row["id_buku"]; ?>">
                                                            <input type="hidden" name="idAnggota"
                                                                value="<?= $row["id_anggota"]; ?>">
                                                            <input type="hidden" name="nama" value="<?= $row["nama"] ?>">
                                                            <input type="hidden" name="kelas" value="<?= $row["kelas"]; ?>">
                                                            <input type="hidden" name="judul" value="<?= $row["judul"]; ?>">
                                                            <input type="hidden" name="tanggalpinjam"
                                                                value="<?= $row["tanggal_pinjam"]; ?>">
                                                            <input type="hidden" name="tanggalkembali"
                                                                value="<?= $row["tanggal_kembali"]; ?>">

                                                            <div class="row">
                                                                <div class="col-md-12 text-center">
                                                                    <h6>Nama peminjam :
                                                                        <?= $row["nama"]; ?>
                                                                        (
                                                                        <?= $row["kelas"]; ?> )
                                                                    </h6>
                                                                    <h6>Judul buku :
                                                                        <?= $row["judul"]; ?>
                                                                    </h6>
                                                                    <label for="tanggalkembali" style="padding-top: 12px;">
                                                                        <h6>Tanggal
                                                                            dikembalikan</h6>
                                                                    </label>
                                                                    <input class="form-control" type="date"
                                                                        name="dikembalikan" id="dikembalikan">
                                                                    <label for="kondisi">
                                                                        <h6>Kondisi buku</h6>
                                                                    </label>
                                                                    <select class="form-select"
                                                                        aria-label="Default select example" name="kondisi"
                                                                        id="kondisi">
                                                                        <option selected>pilih kondisi</option>
                                                                        <option value="baik">Baik</option>
                                                                        <option value="rusak">Rusak</option>
                                                                    </select>

                                                                </div>
                                                            </div>

                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-danger" data-dismiss="modal"><i
                                                                class="fa fa-ban"></i>
                                                            CANCEL</button>
                                                        <button type="submit" class="btn btn-success" name="selesai"><i
                                                                class="fa fa-circle-check"></i>
                                                            SELESAI</button>
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

</body>

</html>