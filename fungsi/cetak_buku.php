<?php
require '../fpdf185/fpdf.php';
include '../fungsi/fungsi.php';

// intance object dan memberikan pengaturan halaman PDF
$pdf = new FPDF('L', 'mm', 'A4');
$pdf->AddPage();

$pdf->SetFont('Times', 'B', 13);
$pdf->Cell(280, 10, 'DATA BUKU', 0, 0, 'C');

$pdf->Cell(10, 7, '', 0, 1);
$pdf->Cell(280, 10, 'PERPUSTAKAAN SD NEGERI CATUR', 0, 0, 'C');

$pdf->Cell(10, 15, '', 0, 1);
$pdf->SetFont('Times', 'B', 9);
$pdf->Cell(8, 7, 'NO', 1, 0, 'C');
$pdf->Cell(20, 7, 'KODE BUKU', 1, 0, 'C');
$pdf->Cell(160, 7, 'JUDUL', 1, 0, 'C');
$pdf->Cell(40, 7, 'PENGARANG', 1, 0, 'C');
$pdf->Cell(25, 7, 'TAHUN TERBIT', 1, 0, 'C');
$pdf->Cell(15, 7, 'JUMLAH', 1, 0, 'C');


$pdf->Cell(10, 7, '', 0, 1);
$pdf->SetFont('Times', '', 10);
$no = 1;
$data = mysqli_query($konek, "SELECT  * FROM daftar_buku");
while ($d = mysqli_fetch_array($data)) {
    $pdf->Cell(8, 6, $no++, 1, 0, 'C');
    $pdf->Cell(20, 6, $d['id'], 1, 0);
    $pdf->Cell(160, 6, $d['judul_buku'], 1, 0);
    $pdf->Cell(40, 6, $d['pengarang'], 1, 0);
    $pdf->Cell(25, 6, $d['terbit'], 1, 0);
    $pdf->Cell(15, 6, $d['jumlah_buku'], 1, 1);
}

$pdf->Output();


?>