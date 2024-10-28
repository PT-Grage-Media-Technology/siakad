<?php
echo "<div class='col-xs-12'>  
          <div class='box'>
            <div class='box-header'>
              <h3 class='box-title'>Laporan Nilai UTS : <b>$nama</b></h3>
              <form style='margin-right:5px; margin-top:0px' class='pull-right' action='' method='GET'>
                <input type='hidden' name='view' value='raportuts'>
                <input type='hidden' name='act' value='detailsiswa'>
                <select name='tahun' style='padding:4px'>
                  <option value=''>- Pilih Tahun Akademik -</option>";

// Fetch academic years
$tahunQuery = mysql_query("SELECT * FROM rb_tahun_akademik");
while ($tahunRow = mysql_fetch_array($tahunQuery)) {
  $selected = ($_GET['tahun'] == $tahunRow['id_tahun_akademik']) ? "selected" : "";
  echo "<option value='{$tahunRow['id_tahun_akademik']}' $selected>{$tahunRow['nama_tahun']}</option>";
}

echo "          </select>
                <input type='submit' style='margin-top:-4px' class='btn btn-success btn-sm' value='Lihat'>
              </form>
            </div>
            <div class='box-body'>
              <b class='semester'>CAPAIAN KOMPETENSI</b>
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

// Check if the academic year is selected
if (empty($_GET['tahun'])) {
  echo "<tr><td colspan='7'><center style='padding:60px; color:red'>Silahkan Memilih Tahun akademik Terlebih dahulu...</center></td></tr>";
} else {
  // Fetch subject groups
  $kelompokQuery = mysql_query("SELECT * FROM rb_kelompok_mata_pelajaran");
  while ($kelompokRow = mysql_fetch_array($kelompokQuery)) {
    echo "<tr><td style='border:1px solid #e3e3e3' colspan='8'><b>{$kelompokRow['nama_kelompok_mata_pelajaran']}</b></td></tr>";

    // Fetch subjects based on selected year and class
    $mapelQuery = mysql_query("SELECT * FROM rb_jadwal_pelajaran a 
                                                JOIN rb_mata_pelajaran b ON a.kode_pelajaran = b.kode_pelajaran 
                                                WHERE a.kode_kelas = '{$_SESSION['kode_kelas']}' 
                                                  AND a.id_tahun_akademik = '{$_GET['tahun']}' 
                                                  AND b.id_kelompok_mata_pelajaran = '{$kelompokRow['id_kelompok_mata_pelajaran']}'
                                                  AND b.kode_kurikulum = '{$kurikulum['kode_kurikulum']}'");

    $no = 1;
    while ($mapelRow = mysql_fetch_array($mapelQuery)) {
      $nilaiRow = mysql_fetch_array(mysql_query("SELECT * FROM rb_nilai_uts WHERE kodejdwl = '{$mapelRow['kodejdwl']}' AND nisn = '{$iden['nisn']}'"));

      // Fetch predicates
      $grade1Query = mysql_query("SELECT * FROM rb_predikat WHERE kode_kelas = '{$_SESSION['kode_kelas']}' AND {$nilaiRow['angka_pengetahuan']} BETWEEN nilai_a AND nilai_b");
      $grade2Query = mysql_query("SELECT * FROM rb_predikat WHERE kode_kelas = '{$_SESSION['kode_kelas']}' AND {$nilaiRow['angka_keterampilan']} BETWEEN nilai_a AND nilai_b");

      $grade1 = mysql_fetch_array($grade1Query);
      $grade2 = mysql_fetch_array($grade2Query);

      echo "<tr>
                              <td align='center'>{$no}</td>
                              <td>{$mapelRow['namamatapelajaran']}</td>
                              <td align='center'>77</td>
                              <td align='center'>" . number_format($nilaiRow['angka_pengetahuan']) . "</td>
                              <td align='center'>{$grade1['grade']}</td>
                              <td align='center'>" . number_format($nilaiRow['angka_keterampilan']) . "</td>
                              <td align='center'>{$grade2['grade']}</td>
                          </tr>";
      $no++;
    }
  }
}

echo "        </table>
            </div>
          </div>
        </div>";
?>