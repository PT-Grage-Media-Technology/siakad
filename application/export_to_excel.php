<?php
// Aktifkan tampilan error dan laporan kesalahan
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Sertakan file PHPExcel
require_once 'config/Classes/PHPExcel.php';

// Cek apakah PHPExcel ter-load dengan benar
if (!class_exists('PHPExcel')) {
    die('PHPExcel tidak ditemukan.');
}

// Buat objek PHPExcel
$objPHPExcel = new PHPExcel();

// Data yang akan diekspor
$data = [
    ['Nama', 'Email', 'Telepon'],
    ['John Doe', 'john@example.com', '08123456789'],
    ['Jane Doe', 'jane@example.com', '08198765432']
];

// Debugging: Cek apakah data sudah ter-set dengan benar
echo '<pre>';
var_dump($data);
echo '</pre>';

// Menulis data ke dalam sheet
$row = 1; // Mulai dari baris pertama
foreach ($data as $rowData) {
    $col = 0; // Mulai dari kolom pertama
    foreach ($rowData as $cellData) {
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $cellData);
        $col++;
    }
    $row++;
}

// Memberikan nama file
$fileName = "data_export_" . date("Y-m-d") . ".xls";

// Debugging: Cek nama file yang akan diunduh
echo 'Nama file yang akan diunduh: ' . $fileName;

// Set header untuk unduhan file Excel
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="' . $fileName . '"');
header('Cache-Control: max-age=0');

// Debugging: Cek apakah header sudah terkirim dengan benar
echo 'Header sudah dikirim, memulai proses export...';

// Membuat writer dan menulis output ke browser
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
?>
