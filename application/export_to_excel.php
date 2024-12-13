<?php
// Sertakan file PHPExcel
require_once 'config/Classes/PHPExcel.php';

// Buat objek PHPExcel
$objPHPExcel = new PHPExcel();

// Data yang akan diekspor
$data = [
    ['Nama', 'Email', 'Telepon'],
    ['John Doe', 'john@example.com', '08123456789'],
    ['Jane Doe', 'jane@example.com', '08198765432']
];

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

// Set header untuk unduhan file Excel
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="' . $fileName . '"');
header('Cache-Control: max-age=0');

// Membuat writer dan menulis output ke browser
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
?>
