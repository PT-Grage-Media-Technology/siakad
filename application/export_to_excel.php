<?php
// Sertakan pustaka SimpleXLSXGen
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