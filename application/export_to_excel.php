<?php
// Include PhpSpreadsheet library
require 'vendor/autoload.php'; // Pastikan path ini sesuai dengan lokasi PhpSpreadsheet di proyek Anda

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Cek apakah parameter GET sudah diterima
if (isset($_GET['tahun'], $_GET['kd'], $_GET['id'])) {
    // Ambil data berdasarkan parameter GET
    $tahun = $_GET['tahun'];
    $kd = $_GET['kd'];
    $id = $_GET['id'];

    // Query untuk mendapatkan data absensi
    $tampil = mysql_query("SELECT * FROM rb_siswa a 
                            JOIN rb_jenis_kelamin b ON a.id_jenis_kelamin = b.id_jenis_kelamin 
                            WHERE a.kode_kelas = '$id' 
                            ORDER BY a.id_siswa");

    // Membuat objek spreadsheet baru
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle('Absensi Siswa');

    // Menambahkan header kolom
    $sheet->setCellValue('A1', 'No');
    $sheet->setCellValue('B1', 'NISN');
    $sheet->setCellValue('C1', 'Nama Siswa');
    $sheet->setCellValue('D1', 'Jenis Kelamin');
    $sheet->setCellValue('E1', 'Pertemuan');
    $sheet->setCellValue('F1', 'Hadir');
    $sheet->setCellValue('G1', 'Sakit');
    $sheet->setCellValue('H1', 'Izin');
    $sheet->setCellValue('I1', 'Alpa');
    $sheet->setCellValue('J1', '% Kehadiran');

    // Menambahkan data absensi ke dalam sheet
    $rowNum = 2; // Dimulai dari baris kedua (baris pertama adalah header)
    $no = 1;
    while ($r = mysql_fetch_array($tampil)) {
        // Query untuk menghitung absensi
        $total = mysql_num_rows(mysql_query("SELECT * FROM `rb_absensi_siswa` WHERE kodejdwl='$kd' GROUP BY tanggal"));
        $hadir = mysql_num_rows(mysql_query("SELECT * FROM `rb_absensi_siswa` WHERE kodejdwl='$kd' AND nisn='$r[nisn]' AND kode_kehadiran='H'"));
        $sakit = mysql_num_rows(mysql_query("SELECT * FROM `rb_absensi_siswa` WHERE kodejdwl='$kd' AND nisn='$r[nisn]' AND kode_kehadiran='S'"));
        $izin = mysql_num_rows(mysql_query("SELECT * FROM `rb_absensi_siswa` WHERE kodejdwl='$kd' AND nisn='$r[nisn]' AND kode_kehadiran='I'"));
        $alpa = mysql_num_rows(mysql_query("SELECT * FROM `rb_absensi_siswa` WHERE kodejdwl='$kd' AND nisn='$r[nisn]' AND kode_kehadiran='A'"));

        // Hitung persentase kehadiran
        $persen = ($total > 0) ? ($hadir / $total) * 100 : 0;

        // Menambahkan data ke dalam baris
        $sheet->setCellValue("A$rowNum", $no);
        $sheet->setCellValue("B$rowNum", $r['nisn']);
        $sheet->setCellValue("C$rowNum", $r['nama']);
        $sheet->setCellValue("D$rowNum", $r['jenis_kelamin']);
        $sheet->setCellValue("E$rowNum", $total);
        $sheet->setCellValue("F$rowNum", $hadir);
        $sheet->setCellValue("G$rowNum", $sakit);
        $sheet->setCellValue("H$rowNum", $izin);
        $sheet->setCellValue("I$rowNum", $alpa);
        $sheet->setCellValue("J$rowNum", number_format($persen, 2) . " %");

        $rowNum++;
        $no++;
    }

    // Menulis file Excel
    $writer = new Xlsx($spreadsheet);

    // Mengatur header untuk download file
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="Rekap_Absensi_Siswa_' . $tahun . '.xlsx"');
    header('Cache-Control: max-age=0');

    // Menyimpan dan mengunduh file Excel
    $writer->save('php://output');
    exit;
}
?>
