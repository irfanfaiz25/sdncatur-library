<?php
require 'fungsi/fungsi.php';

session_start();

if (!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit;
}

$id_anggota = $_GET["id_anggota"];

if (hapusAnggota($id_anggota) > 0) {
    header('Location: anggota.php');
} else {
    echo "
            <script>
                alert('data tidak berhasil dihapus');
                document.location.href = 'anggota.php';
            </script>
            ";
}

?>