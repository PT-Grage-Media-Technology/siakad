<?php
echo "<div class='col-xs-12 col-md-12'>  
<div class='box'>
  <div class='box-header'>
    <h3 class='box-title'>Laporan Nilai Akhir : <b>$nama</b></h3>
    <form style='margin-right:5px; margin-top:0px' class='form-inline float-right' action='' method='GET'>
      <input type='hidden' name='view' value='raport'>
      <input type='hidden' name='act' value='detailsiswa'>
      <select name='tahun' class='form-control mb-2 mr-sm-2'>
        <option value=''>- Pilih Tahun Akademik -</option>";
$tahun = mysql_query("SELECT * FROM rb_tahun_akademik ORDER BY id_tahun_akademik DESC LIMIT 1"); // Mengambil tahun terakhir
$lastYear = mysql_fetch_array($tahun); // Menyimpan tahun terakhir
while ($k = mysql_fetch_array($tahun)) {
  if ($_GET[tahun] == $k[id_tahun_akademik] || $k[id_tahun_akademik] == $lastYear[id_tahun_akademik]) { // Memilih tahun terakhir secara default
    echo "<option value='$k[id_tahun_akademik]' selected>$k[nama_tahun]</option>";
  } else {
    echo "<option value='$k[id_tahun_akademik]'>$k[nama_tahun]</option>";
  }
}

echo "</select>
    <input type='submit' class='btn btn-success mb-2' value='Lihat'>
    </form>
  </div>
  <div class='box-body'>
    <b class='semester'>CAPAIAN KOMPETENSI</b>
    <div class='table-responsive'>
      <table class='table table-bordered table-striped'>
        <tr>
          <th style='border:1px solid #ffffff; background-color:lightblue' width='40px' rowspan='2'>No</th>
          <th style='border:1px solid #ffffff; background-color:lightblue' width='300px' rowspan='2'><center>Mata Pelajaran</center></th>
          <th style='border:1px solid #ffffff; background-color:lightblue' rowspan='2'><center>KKM</center></th>
          <th style='border:1px solid #ffffff; background-color:lightblue' colspan='2' style='text-align:center'><center>Pengetahuan</center></th>
          <th style='border:1px solid #ffffff; background-color:lightblue' colspan='2' style='text-align:center'><center>Keterampilan</center></th>
        </tr>
        <tr>
          <th style='border:1px solid #ffffff; background-color:lightblue'><center>Nilai</center></th>
          <th style='border:1px solid #ffffff; background-color:lightblue'><center>Predikat</center></th>
          <th style='border:1px solid #ffffff; background-color:lightblue'><center>Nilai</center></th>
          <th style='border:1px solid #ffffff; background-color:lightblue'><center>Predikat</center></th>
        </tr>";
if ($_GET[tahun] == '') {
  echo "<tr><td colspan=7><center style='padding:60px; color:red'>Silahkan Memilih Tahun akademik Terlebih dahulu...</center></td></tr>";
}
$kelompok = mysql_query("SELECT * FROM rb_kelompok_mata_pelajaran");
while ($k = mysql_fetch_array($kelompok)) {
  echo "<tr>
                <td style='border:1px solid #e3e3e3' colspan='2'><b>$k[nama_kelompok_mata_pelajaran]</b></td>
                <td style='border:1px solid #e3e3e3' colspan='5'></td>
              </tr>";
  $mapel = mysql_query("SELECT * FROM  rb_jadwal_pelajaran a 
                                    JOIN rb_mata_pelajaran b ON a.kode_pelajaran=b.kode_pelajaran 
                                      where a.kode_kelas='$_SESSION[kode_kelas]' 
                                        AND a.id_tahun_akademik='$_GET[tahun]' 
                                          AND b.id_kelompok_mata_pelajaran='$k[id_kelompok_mata_pelajaran]'
                                            AND b.kode_kurikulum='$kurikulum[kode_kurikulum]'");

  $no = 1;
  while ($m = mysql_fetch_array($mapel)) {
    $rapn = mysql_fetch_array(mysql_query("SELECT sum((nilai1+nilai2+nilai3+nilai4+nilai5)/5)/count(nisn) as raport FROM rb_nilai_pengetahuan where kodejdwl='$m[kodejdwl]' AND nisn='$iden[nisn]'"));
    $cekpredikat = mysql_num_rows(mysql_query("SELECT * FROM rb_predikat where kode_kelas='$_SESSION[kelas]'"));
    if ($cekpredikat >= 1) {
      $grade3 = mysql_fetch_array(mysql_query("SELECT * FROM `rb_predikat` where (" . number_format($rapn[raport]) . " >=nilai_a) AND (" . number_format($rapn[raport]) . " <= nilai_b) AND kode_kelas='$_SESSION[kelas]'"));
    } else {
      $grade3 = mysql_fetch_array(mysql_query("SELECT * FROM `rb_predikat` where (" . number_format($rapn[raport]) . " >=nilai_a) AND (" . number_format($rapn[raport]) . " <= nilai_b) AND kode_kelas='0'"));
    }

    $rapnk = mysql_fetch_array(mysql_query("SELECT sum(GREATEST(nilai1,nilai2,nilai3,nilai4,nilai5,nilai6))/count(nisn) as raport FROM rb_nilai_keterampilan where kodejdwl='$m[kodejdwl]' AND nisn='$iden[nisn]'"));
    $cekpredikat2 = mysql_num_rows(mysql_query("SELECT * FROM rb_predikat where kode_kelas='$_SESSION[kelas]'"));
    if ($cekpredikat2 >= 1) {
      $grade = mysql_fetch_array(mysql_query("SELECT * FROM `rb_predikat` where (" . number_format($rapnk[raport]) . " >=nilai_a) AND (" . number_format($rapnk[raport]) . " <= nilai_b) AND kode_kelas='$_SESSION[kelas]'"));
    } else {
      $grade = mysql_fetch_array(mysql_query("SELECT * FROM `rb_predikat` where (" . number_format($rapnk[raport]) . " >=nilai_a) AND (" . number_format($rapnk[raport]) . " <= nilai_b) AND kode_kelas='0'"));
    }

    echo "<tr>
                  <td align=center>$no</td>
                  <td>$m[namamatapelajaran]</td>
                  <td align=center>77</td>
                  <td align=center>" . number_format($rapn[raport]) . "</td>
                  <td align=center>$grade3[grade]</td>
                  <td align=center>" . number_format($rapnk[raport]) . "</td>
                  <td align=center>$grade[grade]</td>
              </tr>";
    $no++;
  }
}

echo "</table></div></div></div></div>";
