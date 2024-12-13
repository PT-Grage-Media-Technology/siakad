<?php
// Sertakan file SimpleXLSXGen yang telah diunduh
require_once 'connfig/SimpleXLSX.php';

// Data yang akan diekspor ke Excel
$data = [
    ['Nama', 'Email', 'Telepon'],
    ['John Doe', 'john@example.com', '08123456789'],
    ['Jane Doe', 'jane@example.com', '08198765432']
];

// Menggunakan SimpleXLSXGen untuk mengekspor data
SimpleXLSX::fromArray($data)->download('data_export.xlsx');
exit;
?>
