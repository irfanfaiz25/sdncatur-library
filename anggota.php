<?php
require 'fungsi/fungsi.php';

session_start();

if (!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit;
}

if (isset($_POST["addAnggota"])) {
    if (tambahAnggota($_POST)) {
        header('Location: anggota.php');
    } else {
        echo "
					<script>
						alert('data tidak berhasil ditambahkan');
						document.location.href = 'anggota.php';
					</script>
					";
    }
}


if (isset($_POST["editAnggota"])) {
    if (ubahAnggota($_POST)) {
        header('Location: anggota.php');
    } else {
        echo "
						<script>
							alert('data tidak berhasil di ubah');
							document.location.href = 'anggota.php';
						</script>
						";
    }
}

$jmlData = 8;

$totalData = count(query("SELECT * FROM daftar_anggota"));
$jumlahHalaman = ceil($totalData / $jmlData);
$halamanAktif = (isset($_GET["hal"])) ? $_GET["hal"] : 1;
$awalData = ($jmlData * $halamanAktif) - $jmlData;

$anggota = query("SELECT * FROM daftar_anggota ORDER BY kelas ASC LIMIT $awalData, $jmlData");

if (isset($_POST["btnCari"])) {
    $anggota = cariDataAnggota($_POST["pencarian"]);
} elseif (isset($_POST["btnReset"])) {
    $anggota;
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
            height: 420px;
            overflow: auto;
            margin-bottom: 20px;
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
                        <a class="nav-link text-white active" href="anggota.php">
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
                <h1><b>DATA ANGGOTA</b></h1>
                <p style="margin-bottom: -7px; margin-top: -5px; color: #7B8FA1 !important;">SDN Catur </p>
            </div>
            <div class="col-md-2">
                <a href="logout.php" class="btn btn-outline-secondary mc float-end" id="button-toggle">
                    <i class="fa fa-right-from-bracket" style="color: grey;"></i>
                </a>
            </div>
        </div>

        <div class=" card mt-5">
            <div class="card-body">

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
                        <div class="col-md-5" style="margin-left: 0px;">
                            <button type="submit" class="btn btn-primary float-start" href=""
                                name="btnCari">CARI</button>
                            <button type="submit" class="btn btn-danger" href="" name="btnReset"
                                style="margin-left: 4px;">RESET</button>
                        </div>
                        <div class="col-md-4">
                            <button type="button" class="btn btn-primary float-end" data-toggle="modal"
                                data-target="#inputModal"><i class="fa fa-plus"></i> TAMBAH DATA</button>
                        </div>
                    </div>
                </form>



                <!-- modal input data -->
                <div id="inputModal" class="modal fade" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="text-secondary">TAMBAH DATA</h4>
                                <button type="button" class="btn-close" data-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="" method="post" enctype="multipart/form-data">
                                    <label for="nisn">NISN</label>
                                    <input type="text" class="form-control" name="nisnn" id="nisn">

                                    <label for="nama">Nama</label>
                                    <input type="text" class="form-control" name="nama" id="nama">

                                    <label for="kelas">Kelas</label>
                                    <select class="form-select" aria-label="Default select example" name="kelas"
                                        id="kelas">
                                        <option selected>Pilih kelas</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                        <option value="6">6</option>
                                    </select>

                                    <label for="jk">Jenis Kelamin</label>
                                    <select class="form-select" aria-label="Default select example" name="jk" id="jk">
                                        <option selected>Pilih Jenis Kelamin</option>
                                        <option value="Laki-laki">Laki-laki</option>
                                        <option value="Perempuan">Perempuan</option>
                                    </select>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal"><i
                                        class="fa fa-ban"></i>
                                    CANCEL</button>
                                <button type="submit" class="btn btn-primary" name="addAnggota"><i
                                        class="fa fa-plus"></i>
                                    TAMBAH</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>






                <!-- tabel data anggota -->
                <div class="row dataku">

                    <div class="col-md-12 table-responsive-md">
                        <div class="table-wrapper-scroll-y my-custom-scrollbar">
                            <table class="table table-hover">
                                <thead class="thead-dark" style="background-color: #023047; color: #ffffff;">
                                    <tr>
                                        <th class="ctr">NO</th>
                                        <th class="ctr">ID ANGGOTA</th>
                                        <th class="ctr">NISN</th>
                                        <th class="ctr">NAMA</th>
                                        <th class="ctr">KELAS</th>
                                        <th class="ctr">JENIS KELAMIN</th>
                                        <th class="ctr">AKSI</th>
                                    </tr>
                                </thead>

                                <?php $i = 1; ?>
                                <?php foreach ($anggota as $row): ?>
                                    <tbody>
                                        <tr>
                                            <td class="align-middle text-center">
                                                <?= $i + $awalData; ?>
                                            </td>
                                            <td class="align-middle text-center">
                                                <?= $row["id"]; ?>
                                            </td>
                                            <td class="align-middle text-center">
                                                <?= $row["nisn"]; ?>
                                            </td>
                                            <td class="align-middle text-center">
                                                <?= $row["nama"]; ?>
                                            </td>
                                            <td class="align-middle text-center">
                                                <?= $row["kelas"]; ?>
                                            </td>
                                            <td class="align-middle text-center">
                                                <?= $row["jk"]; ?>
                                            </td>

                                            <td class="text-center align-middle">

                                                <button style="margin-right: 5px;" type="submit"
                                                    class="btn btn-primary btn-sm" name="ubah" data-toggle="modal"
                                                    data-target="#editModal<?= $row["id"]; ?>" href=""><i
                                                        class="fa fa-edit"></i> EDIT</button>
                                                <a class="btn btn-danger btn-sm" name="hapus"
                                                    href="hapus_anggota.php?id_anggota=<?= $row["id"]; ?>"><i
                                                        class="fa fa-trash"></i>
                                                    HAPUS</a>

                                            </td>
                                        </tr>
                                        <!-- modal edit data -->
                                        <div id="editModal<?= $row["id"]; ?>" class="modal fade" tabindex="	-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="text-secondary">UBAH DATA</h4>
                                                        <button type="button" class="btn-close" data-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="" method="post" enctype="multipart/form-data">
                                                            <input type="hidden" name="id" value="<?= $row["id"]; ?>">

                                                            <label for="nisn">NISN</label>
                                                            <input type="text" class="form-control" name="nisn" id="nisn"
                                                                value="<?= $row["nisn"]; ?>">

                                                            <label for="nama">Nama</label>
                                                            <input type="text" class="form-control" name="nama" id="nama"
                                                                value="<?= $row["nama"]; ?>">

                                                            <label for="kelas">Kelas</label>
                                                            <select class="form-select" aria-label="Default select example"
                                                                name="kelas" id="kelas">
                                                                <option selected>
                                                                    <?= $row["kelas"]; ?>
                                                                </option>
                                                                <option value="1">1</option>
                                                                <option value="2">2</option>
                                                                <option value="3">3</option>
                                                                <option value="4">4</option>
                                                                <option value="5">5</option>
                                                                <option value="6">6</option>
                                                            </select>

                                                            <label for="jk">Jenis Kelamin</label>
                                                            <select class="form-select" aria-label="Default select example"
                                                                name="jk" id="jk">
                                                                <option selected>
                                                                    <?= $row["jk"]; ?>
                                                                </option>
                                                                <option value="Laki-laki">Laki-laki</option>
                                                                <option value="Perempuan">Perempuan</option>
                                                            </select>

                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-danger" data-dismiss="modal"><i
                                                                class="fa fa-ban"></i>
                                                            CANCEL</button>
                                                        <button type="submit" class="btn btn-primary" name="editAnggota"><i
                                                                class="fa fa-edit"></i>
                                                            UBAH</button>
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


                        <nav aria-label="...">
                            <ul class="pagination float-end">
                                <?php if ($halamanAktif > 1): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="?hal=<?= $halamanAktif - 1; ?>">
                                            &laquo
                                        </a>
                                    </li>
                                <?php endif; ?>
                                <?php for ($i = 1; $i <= $jumlahHalaman; $i++): ?>
                                    <?php if ($i == $halamanAktif): ?>
                                        <li class="page-item active"><a class="page-link" href="?hal=<?= $i; ?>"><?= $i; ?></a>
                                        </li>
                                    <?php else: ?>
                                        <li class="page-item"><a class="page-link" href="?hal=<?= $i; ?>"><?= $i; ?></a></li>
                                    <?php endif; ?>
                                <?php endfor; ?>
                                <?php if ($halamanAktif < $jumlahHalaman): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="?hal=<?= $halamanAktif + 1; ?>">
                                            &raquo
                                        </a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </nav>



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