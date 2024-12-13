<?php  
// include necessary files for Excel creation (optional PHPExcel library)  
// require PHPExcel.php if using PHPExcel Library  

// Database connection  
// include 'db_connection.php'; // your DB connection file  

// Fetch the data based on the parameters passed in the URL  
$tahun = $_GET['tahun'];  
$kd = $_GET['kd'];  
$id = $_GET['id'];  

// Fetch class and subject info  
$d = mysql_fetch_array(mysql_query("SELECT * FROM rb_kelas WHERE kode_kelas='$id'"));  
$m = mysql_fetch_array(mysql_query("SELECT * FROM rb_mata_pelajaran WHERE kode_pelajaran='$kd'"));  

// Prepare the Excel file  
header('Content-Type: application/vnd.ms-excel');  
header('Content-Disposition: attachment; filename="Rekap_Absensi_'.$d['nama_kelas'].'_'.$m['namamatapelajaran'].'_'.$tahun.'.xls"');  
header('Pragma: no-cache');  
header('Expires: 0');  

// Output headers  
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

// Query to get the attendance data  
$tampil = mysql_query("SELECT * FROM rb_siswa a   
                       JOIN rb_jenis_kelamin b ON a.id_jenis_kelamin=b.id_jenis_kelamin   
                       WHERE a.kode_kelas='$id'   
                       ORDER BY a.id_siswa");  

$no = 1;  
while ($r = mysql_fetch_array($tampil)) {  
    $total = mysql_num_rows(mysql_query("SELECT * FROM rb_absensi_siswa WHERE kodejdwl='$kd' GROUP BY tanggal"));  
    $hadir = mysql_num_rows(mysql_query("SELECT * FROM rb_absensi_siswa WHERE kodejdwl='$kd' AND nisn='$r[nisn]' AND kode_kehadiran='H'"));  
    $sakit = mysql_num_rows(mysql_query("SELECT * FROM rb_absensi_siswa WHERE kodejdwl='$kd' AND nisn='$r[nisn]' AND kode_kehadiran='S'"));  
    $izin = mysql_num_rows(mysql_query("SELECT * FROM rb_absensi_siswa WHERE kodejdwl='$kd' AND nisn='$r[nisn]' AND kode_kehadiran='I'"));  
    $alpa = mysql_num_rows(mysql_query("SELECT * FROM rb_absensi_siswa WHERE kodejdwl='$kd' AND nisn='$r[nisn]' AND kode_kehadiran='A'"));  

    $tanggal_absen = mysql_fetch_array(mysql_query("SELECT DISTINCT tanggal FROM rb_absensi_siswa WHERE kodejdwl='$kd' AND nisn='$r[nisn]'"));  

    $persen = ($total > 0) ? number_format(($hadir / $total) * 100, 2) : 0;  

    echo "<tr>  
            <td>$no</td>