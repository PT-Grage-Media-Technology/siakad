<?php
session_start();
error_reporting(0);
include "../config/koneksi.php";
include "../config/fungsi_indotgl.php";
$frt = mysql_fetch_array(mysql_query("SELECT * FROM rb_header_print ORDER BY id_header_print DESC LIMIT 1"));
?>

<head>
  <title>Hal 4 - Raport Siswa</title>
  <link rel="stylesheet" href="../bootstrap/css/printer.css">
</head>

<body onload="handlePrint()">
  <?php
  $t = mysql_fetch_array(mysql_query("SELECT * FROM rb_tahun_akademik where id_tahun_akademik='$_GET[tahun]'"));
  $s = mysql_fetch_array(mysql_query("SELECT a.*, b.*, c.nama_guru as walikelas, c.nip FROM rb_siswa a 
                                      JOIN rb_kelas b ON a.kode_kelas=b.kode_kelas 
                                        LEFT JOIN rb_guru c ON b.nip=c.nip where a.nisn='$_GET[id]'"));
  if (substr($_GET[tahun], 4, 5) == '1') {
    $semester = 'Ganjil';
  } else {
    $semester = 'Genap';
  }
  $iden = mysql_fetch_array(mysql_query("SELECT * FROM rb_identitas_sekolah ORDER BY id_identitas_sekolah DESC LIMIT 1"));
  echo "<table width=100%>
        <tr><td>Nama Peserta Didik</td>       <td> : <b>$s[nama]</b> </td>       <td width=140px>Kelas </td>   <td>: $s[kode_kelas]</td></tr>
        <tr><td>NIS/NISN</td>                 <td> : $s[nisn]</td>        <td>Fase </td> <td>: $s[fase]</td></tr>
        <tr><td width=140px>Sekolah</td> <td> : $iden[nama_sekolah] </td>     <td>Semester </td> <td>: $semester</td></tr>
        <tr>   <td>Alamat</td>                <td> : $iden[alamat_sekolah] </td>         <td>Tahun Ajaran </td> <td>: $t[keterangan]</td></tr>
      </table><br>";

  echo "<table id='tablemodul1' width='100%' border='1'>
          <thead>
          <tr>
            <th width='40px' rowspan='2' style='text-align:center;'>No</th>
            <th colspan='2' rowspan='2' style='text-align:center;'>Mata Pelajaran</th>

            <th colspan='1' rowspan='2' style='text-align:center;'>Nilai Akhir</th>
            <th colspan='1' rowspan='2' style='text-align:center;'>Capaian Kompetensi</th>
          </tr>
          </thead>
          <tbody>";

  $kelompok = mysql_query("SELECT * FROM rb_kelompok_mata_pelajaran");



  while ($k = mysql_fetch_array($kelompok)) {
    echo "<tr>
  <td colspan='6'><b>$k[nama_kelompok_mata_pelajaran]</b></td>
  </tr>";
    $mapel = mysql_query("SELECT * FROM rb_jadwal_pelajaran a 
                          JOIN rb_mata_pelajaran b ON a.kode_pelajaran=b.kode_pelajaran 
                          WHERE a.kode_kelas='$_GET[kelas]' 
                          AND a.id_tahun_akademik='$_GET[tahun]' 
                          AND b.id_kelompok_mata_pelajaran='$k[id_kelompok_mata_pelajaran]'");
    $no = 1;
    while ($m = mysql_fetch_array($mapel)) {
      $rapot = mysql_fetch_array(mysql_query("SELECT * FROM rb_nilai_pengetahuan WHERE kodejdwl='$m[kodejdwl]' AND nisn='$s[nisn]'"));
      $rapn = mysql_fetch_array(mysql_query("SELECT SUM((nilai1+nilai2+nilai3+nilai4+nilai5)/5)/COUNT(nisn) AS raport FROM rb_nilai_pengetahuan WHERE kodejdwl='$m[kodejdwl]' AND nisn='$s[nisn]'"));
      $rapnk = mysql_fetch_array(mysql_query("SELECT SUM(GREATEST(nilai1,nilai2,nilai3,nilai4,nilai5,nilai6))/COUNT(nisn) AS raport FROM rb_nilai_keterampilan WHERE kodejdwl='$m[kodejdwl]' AND nisn='$s[nisn]'"));

      echo "<tr>
              <td rowspan='2' align='center'>$no</td>
              <td colspan='2' rowspan='2'>$m[namamatapelajaran]</td>";

      // Periksa apakah nilai raport kurang dari KKTp
      if ($rapot['nilai_akhir'] <= $m['kktp']) {
          echo "<script>
              alert('Nilai raport mapel $m[namamatapelajaran] kurang dari KKTp. Harap periksa kembali.');
              window.close();
          </script>";
          exit;
      }

      // Jika nilai raport mencukupi, lanjutkan menampilkan data
      echo "<td rowspan='2' align='center'>
              " . number_format($rapot['nilai_akhir']) . "
            </td>
            <td align='center'>$rapot[deskripsi_tertinggi]</td>
          </tr>
          <tr>
            <td align='center'>$rapot[deskripsi_terendah]</td>
          </tr>";

      $no++;
    }
  }
  echo "</tbody></table><br/>";
  ?>


  <!-- tanda tangan ada di raport halaman teralkhir -->
  <!-- <table border=0 width=100%>
  <tr>
    <td width="260" align="left">Orang Tua / Wali</td>
    <td width="520"align="center">Mengetahui <br> Kepala SMA Negeri 1 Padang</td>
    <td width="260" align="left">Padang, <?php echo tgl_raport(date("Y-m-d")); ?> <br> Wali Kelas</td>
  </tr>
  <tr>
    <td align="left"><br /><br /><br /><br /><br />
      ................................... <br /><br /></td>

    <td align="center" valign="top"><br /><br /><br /><br /><br />
      <b>DRS. AMRI JUNA, M.Pd<br>
      NIP : 196209051987031007</b>
    </td>

    <td align="left" valign="top"><br /><br /><br /><br /><br />
      <b><?php echo $s[walikelas]; ?><br />
      NIP : <?php echo $s[nip]; ?></b>
    </td>
  </tr>
</table>  -->
</body>

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