<?php
// Koneksi database
$connection = new mysqli('localhost', 'username', 'password', 'database');
if ($connection->connect_error) {
    die("Koneksi gagal: " . $connection->connect_error);
}

// Validasi input
$tahun = $connection->real_escape_string($_GET['tahun']);
$kd = $connection->real_escape_string($_GET['kd']);
$id = $connection->real_escape_string($_GET['id']);

// Fetch data kelas dan mata pelajaran
$d = $connection->query("SELECT * FROM rb_kelas WHERE kode_kelas='$id'")->fetch_assoc();
$m = $connection->query("SELECT * FROM rb_mata_pelajaran WHERE kode_pelajaran='$kd'")->fetch_assoc();

// Header file Excel
header('Content-Type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=\"Rekap_Absensi_{$d['nama_kelas']}_{$m['namamatapelajaran']}_$tahun.xls\"");
header('Pragma: no-cache');
header('Expires: 0');

// Output header tabel
echo "<table border='1'>
        <tr>
            <th>No</th>
            <th>NISN</th>
            <th>Nama Siswa</th>
            <th>Jenis Kelamin</th>
            <th>Pertemuan</th>
            <th>Hadir</th>
            <th>Sakit</th>
            <th>Izin</th>
            <th>Alpa</th>
            <th>% Kehadiran</th>
            <th>Tanggal Absen</th>
        </tr>";

// Fetch data siswa
$tampil = $connection->query("SELECT a.nisn, a.nama, b.jenis_kelamin 
                              FROM rb_siswa a
                              JOIN rb_jenis_kelamin b ON a.id_jenis_kelamin=b.id_jenis_kelamin
                              WHERE a.kode_kelas='$id'
                              ORDER BY a.id_siswa");

$no = 1;
while ($r = $tampil->fetch_assoc()) {
    $total = $connection->query("SELECT DISTINCT tanggal FROM rb_absensi_siswa WHERE kodejdwl='$kd'")->num_rows;
    $hadir = $connection->query("SELECT * FROM rb_absensi_siswa WHERE kodejdwl='$kd' AND nisn='{$r['nisn']}' AND kode_kehadiran='H'")->num_rows;
    $sakit = $connection->query("SELECT * FROM rb_absensi_siswa WHERE kodejdwl='$kd' AND nisn='{$r['nisn']}' AND kode_kehadiran='S'")->num_rows;
    $izin = $connection->query("SELECT * FROM rb_absensi_siswa WHERE kodejdwl='$kd' AND nisn='{$r['nisn']}' AND kode_kehadiran='I'")->num_rows;
    $alpa = $connection->query("SELECT * FROM rb_absensi_siswa WHERE kodejdwl='$kd' AND nisn='{$r['nisn']}' AND kode_kehadiran='A'")->num_rows;

    // Ambil semua tanggal absen
    $absence_dates = $connection->query("SELECT DISTINCT tanggal FROM rb_absensi_siswa WHERE kodejdwl='$kd' AND nisn='{$r['nisn']}'");
    $dates_array = [];
    while ($absence_record = $absence_dates->fetch_assoc()) {
        $dates_array[] = $absence_record['tanggal'];
    }
    $tanggal_absen = implode(", ", $dates_array);

    $persen = ($total > 0) ? number_format(($hadir / $total) * 100, 2) : 0;

    echo "<tr>
            <td>$no</td>
            <td>{$r['nisn']}</td>
            <td>{$r['nama']}</td>
            <td>{$r['jenis_kelamin']}</td>
            <td align='center'>$total</td>
            <td align='center'>$hadir</td>
            <td align='center'>$sakit</td>
            <td align='center'>$izin</td>
            <td align='center'>$alpa</td>
            <td align='right'>{$persen} %</td>
            <td align='center'>$tanggal_absen</td>
          </tr>";
    $no++;
}

echo "</table>";

// Tutup koneksi
$connection->close();
exit();
?>
