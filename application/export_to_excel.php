<?php
// Sertakan file SimpleCSV yang telah diunduh
include 'config/SimpleCSV.php'; // Ganti dengan path yang sesuai

// Data yang akan diekspor ke CSV
$data = [
    ['Nama', 'Email', 'Telepon'],
    ['John Doe', 'john@example.com', '08123456789'],
    ['Jane Doe', 'jane@example.com', '08198765432']
];

// Membuat objek SimpleCSV
$csv = new SimpleCSV();

// Menambahkan data ke file CSV
$csv->addRows($data);

// Memberikan nama file CSV
$fileName = "data_export_" . date("Y-m-d") . ".csv";

// Set header untuk mengunduh file CSV
header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename="' . $fileName . '"');
header('Cache-Control: max-age=0');

// Menulis dan mengunduh file CSV
$csv->save('php://output');
exit;
?>
