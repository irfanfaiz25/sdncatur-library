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
$pdf->Cell(30, 7, 'NISN', 1, 0, 'C');
$pdf->Cell(55, 7, 'NAMA', 1, 0, 'C');
$pdf->Cell(20, 7, 'KELAS', 1, 0, 'C');
$pdf->Cell(55, 7, 'JENIS KELAMIN', 1, 0, 'C');


$pdf->Cell(10, 7, '', 0, 1);
$pdf->SetFont('Times', '', 10);
$no = 1;
$data = mysqli_query($konek, "SELECT  * FROM daftar_anggota");
while ($d = mysqli_fetch_array($data)) {
    $pdf->Cell(8, 6, $no++, 1, 0, 'C');
    $pdf->Cell(30, 6, $d['id'], 1, 0);
    $pdf->Cell(55, 6, $d['nama'], 1, 0);
    $pdf->Cell(20, 6, $d['kelas'], 1, 0);
    $pdf->Cell(55, 6, $d['jk'], 1, 1);
}

$pdf->Output();


?>