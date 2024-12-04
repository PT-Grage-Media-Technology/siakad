<?php
// Fetch the latest academic year
$tahunQuery = mysql_query("SELECT * FROM rb_tahun_akademik ORDER BY id_tahun_akademik DESC LIMIT 1");
$latestTahunRow = mysql_fetch_array($tahunQuery);
$latestTahunId = $latestTahunRow['id_tahun_akademik'];

// Set the selected year based on the GET parameter or default to the latest year
$selectedTahunId = !empty($_GET['tahun']) ? $_GET['tahun'] : $latestTahunId;

echo "<div class='col-xs-12'>  
          <div class='box'>
            <div class='box-header'>
              <h3 class='box-title'>Laporan Nilai STS : <b>$nama</b></h3>
              <form style='margin-right:5px; margin-top:0px' class='pull-right' action='' method='GET'>
                <input type='hidden' name='view' value='raportuts'>
                <input type='hidden' name='act' value='detailsiswa'>
                <select name='tahun' style='padding:4px' onchange='this.form.submit()' class='form-control form-control-sm'>
                  <option value=''>- Pilih Tahun Akademik -</option>";

// Fetch academic years
$tahunQuery = mysql_query("SELECT * FROM rb_tahun_akademik");
while ($tahunRow = mysql_fetch_array($tahunQuery)) {
  $selected = ($selectedTahunId == $tahunRow['id_tahun_akademik']) ? "selected" : "";
  echo "<option value='{$tahunRow['id_tahun_akademik']}' $selected>{$tahunRow['nama_tahun']}</option>";
}

echo "          </select>
              </form>
            </div>
            <div class='box-body'>
              <b class='semester'>CAPAIAN KOMPETENSI</b>
              <div class='table-responsive'>
                <table class='table table-bordered table-striped'>
                  <tr>
                    <th style='border:1px solid #ffffff; background-color:lightblue' width='40px' rowspan='2'>No</th>
                    <th style='border:1px solid #ffffff; background-color:lightblue' width='300px' rowspan='2'><center>Mata Pelajaran</center></th>
                    <th style='border:1px solid #ffffff; background-color:lightblue' width='300px' rowspan='2'><center>Nilai</center></th>
                  </tr>";

  // Check if the academic year is selected
  if (empty($selectedTahunId)) {
    echo "<tr><td colspan='8'><center style='padding:60px; color:red'>Silahkan Memilih Tahun akademik Terlebih dahulu...</center></td></tr>";
  } else {
    // Fetch subject groups
    $kelompokQuery = mysql_query("SELECT * FROM rb_kelompok_mata_pelajaran");
    while ($kelompokRow = mysql_fetch_array($kelompokQuery)) {
      echo "<tr><td style='border:1px solid #e3e3e3' colspan='8'><b>{$kelompokRow['nama_kelompok_mata_pelajaran']}123</b></td></tr>";

      // Fetch subjects based on selected year and class
      $mapelQuery = mysql_query("SELECT * FROM rb_jadwal_pelajaran a 
                                                    JOIN rb_mata_pelajaran b ON a.kode_pelajaran = b.kode_pelajaran 
                                                    WHERE a.kode_kelas = '{$_SESSION['kode_kelas']}' 
                                                      AND a.id_tahun_akademik = '$selectedTahunId' 
                                                      AND b.id_kelompok_mata_pelajaran = '{$kelompokRow['id_kelompok_mata_pelajaran']}'
                                                      AND b.kode_kurikulum = '{$kurikulum['kode_kurikulum']}'");

                              $no = 1;
                              while ($mapelRow = mysql_fetch_array($mapelQuery)) {
                                $nilaiRow = mysql_fetch_array(mysql_query("SELECT * FROM rb_nilai_uts WHERE kodejdwl = '{$mapelRow['kodejdwl']}' AND nisn = '{$iden['nisn']}'"));

                                // Fetch predicates
                                $grade1Query = mysql_query("SELECT * FROM rb_predikat WHERE kode_kelas = '{$_SESSION['kode_kelas']}' AND {$nilaiRow['angka_pengetahuan']} BETWEEN nilai_a AND nilai_b");

                                $grade1 = mysql_fetch_array($grade1Query);
                                $grade2 = mysql_fetch_array($grade2Query);

                                echo "<tr>
                                <td align='center'>{$no}</td>
                                <td>{$mapelRow['namamatapelajaran']}</td>
                                <td>" . number_format($nilaiRow['angka_pengetahuan']) . "</td>
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
 <!-- //      <script>
        
    //     window.onload = function () {
    //         window.print();
    //     };
    // </script>"; -->