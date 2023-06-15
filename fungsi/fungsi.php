<?php

$konek = mysqli_connect("localhost", "root", "", "db_perpus");

function query($query)
{
    global $konek;
    $result = mysqli_query($konek, $query);
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    return $rows;
}

function tambah($data)
{
    global $konek;

    $judul = htmlspecialchars($data["judul"]);
    $jumlah = htmlspecialchars($data["jumlah"]);
    $pengarang = htmlspecialchars($data["pengarang"]);
    $terbit = htmlspecialchars($data["terbit"]);
    $kategori = htmlspecialchars($data["kategori"]);

    if ($judul == "" || $jumlah == "" || $pengarang == "" || $terbit == "" || $kategori == "") {
        echo "
            <script>
                alert('Data yang anda masukkan tidak lengkap');
            </script>
        ";
        return false;
    }

    $cek = mysqli_query($konek, "SELECT judul_buku FROM daftar_buku WHERE judul_buku = '$judul'");

    if (mysqli_fetch_assoc($cek)) {
        echo "
            <script>
                alert('Buku ini sudah ada !');
            </script>
        ";
        return false;
    }

    $input = "INSERT IGNORE INTO daftar_buku VALUES ('','$judul','$pengarang','$terbit','$kategori','$jumlah')";
    mysqli_query($konek, $input);

    return mysqli_affected_rows($konek);
}

function tambahAnggota($data)
{
    global $konek;

    $nisn = htmlspecialchars($data["nisnn"]);
    $nama = htmlspecialchars($data["nama"]);
    $kelas = htmlspecialchars($data["kelas"]);
    $jk = htmlspecialchars($data["jk"]);

    if ($nisn == "" || $nama == "" || $kelas == "" || $jk == "" || $kelas == "Pilih kelas" || $jk == "Pilih Jenis Kelamin") {
        echo "
            <script>
                alert('Data yang anda masukkan tidak lengkap');
            </script>
        ";
        return false;
    }

    $input = "INSERT IGNORE INTO daftar_anggota VALUES ('','$nisn','$nama','$kelas','$jk')";
    mysqli_query($konek, $input);

    return mysqli_affected_rows($konek);
}



function ubah($data)
{
    global $konek;

    $id = $data["id"];
    $judul = htmlspecialchars($data["judul"]);
    $pengarang = htmlspecialchars($data["pengarang"]);
    $terbit = htmlspecialchars($data["terbit"]);
    $kategori = htmlspecialchars($data["kategori"]);
    $jumlah = htmlspecialchars($data["jumlah"]);

    $edit = "UPDATE daftar_buku SET judul_buku='$judul', pengarang='$pengarang', terbit='$terbit', kategori='$kategori', jumlah_buku='$jumlah' WHERE id='$id'";
    mysqli_query($konek, $edit);

    return mysqli_affected_rows($konek);
}

function ubahAnggota($data)
{
    global $konek;

    $id = $data["id"];
    $nisn = htmlspecialchars($data["nisn"]);
    $nama = htmlspecialchars($data["nama"]);
    $kelas = htmlspecialchars($data["kelas"]);
    $jk = htmlspecialchars($data["jk"]);

    $edit = "UPDATE daftar_anggota SET nisn='$nisn', nama='$nama', kelas='$kelas', jk='$jk' WHERE id='$id'";
    mysqli_query($konek, $edit);

    return mysqli_affected_rows($konek);
}
function hapus($id)
{
    global $konek;

    mysqli_query($konek, "DELETE FROM daftar_buku WHERE id = $id");

    return mysqli_affected_rows($konek);
}

function hapusAnggota($nisn)
{
    global $konek;

    mysqli_query($konek, "DELETE FROM daftar_anggota WHERE id = $nisn");

    return mysqli_affected_rows($konek);
}

function cariData($pencarian)
{
    global $konek;

    $query1 = "SELECT * FROM daftar_buku WHERE id LIKE '%$pencarian%' OR judul_buku LIKE '%$pencarian%' OR terbit LIKE '%$pencarian%' OR pengarang LIKE '%$pencarian%' OR kategori LIKE '%$pencarian%'";

    return query($query1);
}

function cariDataAnggota($pencarian)
{
    global $konek;

    $query1 = "SELECT * FROM daftar_anggota WHERE id LIKE '%$pencarian%' OR nama LIKE '%$pencarian%' OR kelas LIKE '%$pencarian%' OR jk LIKE '%$pencarian%'";

    return query($query1);
}

function cariDataPinjam($pencarian)
{
    global $konek;

    $query1 = "SELECT * FROM peminjaman WHERE id_pinjam LIKE '%$pencarian%' OR id_buku LIKE '%$pencarian%' OR id_anggota LIKE '%$pencarian%' OR nama LIKE '%$pencarian%' OR kelas LIKE '%$pencarian%' OR judul LIKE '%$pencarian%'";

    return query($query1);
}

function cariRiwayat($pencarian)
{
    global $konek;

    $query1 = "SELECT * FROM riwayat_pinjam WHERE id_pinjam LIKE '%$pencarian%' OR id_buku LIKE '%$pencarian%' OR id_anggota LIKE '%$pencarian%' OR nama LIKE '%$pencarian%' OR kelas LIKE '%$pencarian%' OR judul LIKE '%$pencarian%'";

    return query($query1);
}

function cariTglPinjam($pencarian)
{
    global $konek;
    $tgawal = $pencarian["tgawal"];
    $tgakhir = $pencarian["tgakhir"];

    $query1 = "SELECT * FROM peminjaman WHERE tanggal_pinjam BETWEEN '$tgawal' AND '$tgakhir'";

    return query($query1);
}

function cariTglRiwayat($pencarian)
{
    global $konek;
    $tgawal = $pencarian["tgawal"];
    $tgakhir = $pencarian["tgakhir"];

    $query1 = "SELECT * FROM riwayat_pinjam WHERE dikembalikan BETWEEN '$tgawal' AND '$tgakhir'";

    return query($query1);
}

function formatRupiah($angka)
{
    $hasilRupiah = "Rp. " . number_format($angka, 0, ',', '.');

    return $hasilRupiah;
}

function pinjam($data)
{
    global $konek;

    $id_anggota = $data["id"];
    $id_buku = $data["idBuku"];
    $nama = $data["nama"];
    $kelas = $data["kelas"];
    $judul = $data["judul"];
    $tgpinjam = $data["tanggalambil"];
    $tgkembali = $data["tanggalkembali"];

    $rs = mysqli_query($konek, "SELECT count(id_anggota) AS jumlah FROM peminjaman WHERE id_anggota='$id_anggota'");
    $jml = mysqli_fetch_assoc($rs);

    if ($kelas != "guru" && $jml["jumlah"] >= 2) {
        echo "
            <script>
                alert('Anggota ini sudah meminjam 2 buku pada minggu ini !');
            </script>
        ";
        return false;
    }

    $proses = "INSERT IGNORE INTO peminjaman VALUES ('','$id_anggota','$id_buku','$nama','$kelas','$judul','$tgpinjam','$tgkembali')";
    mysqli_query($konek, $proses);

    return mysqli_affected_rows($konek);

}

function kembali($data)
{
    global $konek;

    $id_pinjam = $data["id"];
    $id_anggota = $data["idAnggota"];
    $id_buku = $data["idBuku"];
    $nama = $data["nama"];
    $kelas = $data["kelas"];
    $judul = $data["judul"];
    $tgpinjam = $data["tanggalpinjam"];
    $tgkembali = $data["tanggalkembali"];
    $dikembalikan = $data["dikembalikan"];
    $kondisi = $data["kondisi"];

    if ($kondisi == "pilih kondisi") {
        echo "
            <script>
                alert('Silahkan masukkan keterangan kondisi buku !');
            </script>
        ";
        return false;
    }

    mysqli_query($konek, "DELETE FROM peminjaman WHERE id_pinjam='$id_pinjam'");

    $selesai = "INSERT IGNORE INTO riwayat_pinjam VALUES ('$id_pinjam','$id_anggota','$id_buku','$nama','$kelas','$judul','$tgpinjam','$tgkembali','$dikembalikan','$kondisi')";
    mysqli_query($konek, $selesai);

    return mysqli_affected_rows($konek);

}

function updateStok($data)
{
    global $konek;

    $id_buku = $data["idBuku"];

    $update = "UPDATE daftar_buku SET jumlah_buku=(jumlah_buku - 1) WHERE id='$id_buku'";
    mysqli_query($konek, $update);

    return mysqli_affected_rows($konek);
}

function updateSelesai($data)
{
    global $konek;

    $id_buku = $data["idBuku"];

    $update = "UPDATE daftar_buku SET jumlah_buku=(jumlah_buku + 1) WHERE id='$id_buku'";
    mysqli_query($konek, $update);

    return mysqli_affected_rows($konek);
}

function validasiTanggal($data)
{
    if (date('Y-m-d', strtotime($data)) == $data) {
        return true;
    } else {
        return false;
    }
}

function register($data)
{
    global $konek;

    $username = strtolower(stripslashes($data["username"]));
    $password = mysqli_real_escape_string($konek, $data["password"]);
    $password2 = mysqli_real_escape_string($konek, $data["passwordConfirm"]);
    $token = $data["token"];



    $rs = mysqli_query($konek, "SELECT * FROM user WHERE username = '$username'");

    if (mysqli_fetch_assoc($rs)) {
        echo "
            <script>
                alert('Username sudah ada !');
            </script>
        ";
        return false;
    }

    if ($password != $password2) {
        echo "
            <script>
                alert('Masukkan konfirmasi password dengan benar !');
            </script>
        ";
        return false;
    }

    if ($token != 15098016) {
        echo "
            <script>
                alert('Token salah !');
            </script>
        ";
        return false;
    }

    $password = password_hash($password, PASSWORD_DEFAULT);

    $inp = "INSERT IGNORE INTO user VALUES ('','$username','$password')";
    mysqli_query($konek, $inp);

    return mysqli_affected_rows($konek);
}

?>