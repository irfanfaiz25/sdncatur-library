<?php
require 'fungsi/fungsi.php';

session_start();

if (!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit;
}

$nisn = $_GET["id"];

if (hapus($nisn) > 0) {
    header('Location: buku.php');
} else {
    echo "
            <script>
                alert('data tidak berhasil dihapus');
                document.location.href = 'buku.php';
            </script>
            ";
}


?>