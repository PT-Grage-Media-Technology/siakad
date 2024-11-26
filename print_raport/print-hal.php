<?php 
session_start();
error_reporting(0);
include "../config/koneksi.php"; 
include "../config/fungsi_indotgl.php"; 
$s = mysql_fetch_array(mysql_query("SELECT * FROM rb_siswa where nisn='$_GET[id]'"));
?>
<html>
<head>
<title>Cover Raport Siswa</title>
<script>
        function handlePrint() {
            // Membuka dialog cetak
            window.print();
            
            // Setelah dialog cetak ditutup, kembali ke halaman sebelumnya
            setTimeout(() => {
                window.close();
            }, 500); // Tambahkan sedikit jeda untuk memastikan dialog selesai ditutup
        }
    </script>
<link rel="stylesheet" href="../bootstrap/css/printer.css">
</head>
<body onload="handlePrint()">
    <h1 align=center>RAPORT SISWA <br>SEKOLAH MENENGAH ATAS <br> (SMA)</h1>
    <center>
        <img width='170px' src='logo.png'><br><br><br><br><br><br><br><br>
        Nama Siswa :<br>
        <h3 style='border:1px solid #000; width:82%; padding:6px'><?php echo $s[nama]; ?></h3><br><br>

        NIS / NISN<br>
        <h3 style='border:1px solid #000; width:82%; padding:3px'><?php echo "$s[nipd] / $s[nisn]"; ?></h3><br><br><br><br><br><br>

        <p style='font-size:22px'>KEMENTERIAN PENDIDIKAN DAN KEBUDAYAAN <br>REPUBLIK INDONESIA</p>
    </center>
</body>
</html>