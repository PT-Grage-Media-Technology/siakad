<?php
// Sertakan pustaka SimpleXLSXGen
require_once 'config/SimpleXLSXGen.php'; // Sesuaikan dengan path di proyek Anda

// Data untuk diekspor
$data = [
    ['ID', 'Nama', 'Email'], // Header
    [1, 'John Doe', 'john@example.com'],
    [2, 'Jane Doe', 'jane@example.com'],
    [3, 'Mark Smith', 'mark@example.com']
];

// Buat file Excel
$xlsx = SimpleXLSXGen::fromArray($data);

// Unduh file
$xlsx->downloadAs('data-export.xlsx'); // Nama file yang akan diunduh
?>
