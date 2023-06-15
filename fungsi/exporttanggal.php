<?php
require '../fpdf185/fpdf.php';
include '../fungsi/fungsi.php';

// intance object dan memberikan pengaturan halaman PDF
$pdf = new FPDF('L', 'mm', 'A4');
$pdf->AddPage();

$pdf->SetFont('Times', 'B', 13);
$pdf->Cell(280, 10, 'RIWAYAT PEMINJAMAN', 0, 0, 'C');

$pdf->Cell(10, 7, '', 0, 1);
$pdf->Cell(280, 10, 'PERPUSTAKAAN SD NEGERI CATUR', 0, 0, 'C');

$pdf->Cell(10, 15, '', 0, 1);
$pdf->SetFont('Times', 'B', 9);
$pdf->Cell(8, 7, 'NO', 1, 0, 'C');
$pdf->Cell(20, 7, 'ID PINJAM', 1, 0, 'C');
$pdf->Cell(20, 7, 'KODE BUKU', 1, 0, 'C');
$pdf->Cell(22, 7, 'NISN', 1, 0, 'C');
$pdf->Cell(40, 7, 'NAMA', 1, 0, 'C');
$pdf->Cell(12, 7, 'KELAS', 1, 0, 'C');
$pdf->Cell(55, 7, 'JUDUL', 1, 0, 'C');
$pdf->Cell(25, 7, 'TGL PINJAM', 1, 0, 'C');
$pdf->Cell(25, 7, 'TGL KEMBALI', 1, 0, 'C');
$pdf->Cell(28, 7, 'DIKEMBALIKAN', 1, 0, 'C');
$pdf->Cell(25, 7, 'KONDISI', 1, 0, 'C');

$tgawal = $_GET["tanggalawal"];
$tgakhir = $_GET["tanggalakhir"];

$pdf->Cell(10, 7, '', 0, 1);
$pdf->SetFont('Times', '', 10);
$no = 1;
$data = mysqli_query($konek, "SELECT  * FROM riwayat_pinjam WHERE dikembalikan BETWEEN '$tgawal' AND '$tgakhir'");
while ($d = mysqli_fetch_array($data)) {
    $pdf->Cell(8, 6, $no++, 1, 0, 'C');
    $pdf->Cell(20, 6, $d['id_pinjam'], 1, 0);
    $pdf->Cell(20, 6, $d['id_buku'], 1, 0);
    $pdf->Cell(22, 6, $d['id_anggota'], 1, 0);
    $pdf->Cell(40, 6, $d['nama'], 1, 0);
    $pdf->Cell(12, 6, $d['kelas'], 1, 0);
    $pdf->Cell(55, 6, $d['judul'], 1, 0);
    $pdf->Cell(25, 6, $d['tgl_pinjam'], 1, 0);
    $pdf->Cell(25, 6, $d['tgl_kembali'], 1, 0);
    $pdf->Cell(28, 6, $d['dikembalikan'], 1, 0);
    $pdf->Cell(25, 6, $d['kondisi'], 1, 1);
}

$pdf->Output();


?>