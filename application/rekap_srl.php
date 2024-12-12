<style>
  .table-responsive {
    overflow-x: auto;
  }

  @media (min-width: 768px) {
    .table-responsive {
      overflow-x: visible;
    }
  }

  table {
    border-collapse: collapse;
    width: 100%;
    text-align: center;
  }

  th,
  td {
    border: 1px solid black;
    padding: 5px;
    text-align: center;
  }

  th {
    background-color: #f2f2f2;
  }
</style>


<?php if ($_GET['act'] == '') { ?>
  <?php cek_session_guru(); ?>
  <div class="col-xs-12">
    <div class="box">
      <div class="box-header">
        <h3 class="box-title">
          <?php
          if (isset($_GET['kelas']) and isset($_GET['tahun'])) {
            echo "Rekap Absensi Siswa";
          } else {
            echo "Rekap Sumatif Ruang Lingkup " . date('Y');
          }
          ?>
        </h3>
      </div>
      <div class="box-body">
        <div class="table-responsive">
          <table>
            <thead>
              <tr>
                <th rowspan="2">No</th>
                <th rowspan="2">Nama Siswa</th>
                <th rowspan="2">KKTP</th>
                <?php
                $headers = mysql_query("SELECT * FROM rb_journal_list WHERE kodejdwl='$_GET[idjr]' AND id_parent_journal IS NULL ORDER BY tanggal ASC");
                $header_count = mysql_num_rows($headers);

                if ($header_count > 0) {
                    echo "<th colspan='$header_count'>SUMATIF LINGKUP MATERI</th>";
                } else {
                    echo "<th colspan='1'>Tidak ada data</th>"; // Menampilkan pesan jika tidak ada data
                }
                ?>
                <th rowspan="2">Nilai Tertinggi</th>
                <th rowspan="2">Nilai Terendah</th>
                <th rowspan="2">NA SUMATIF (S)</th>
                <th rowspan="2">Status</th>
              </tr>
              <tr>
                <?php
                // $headerCells = ""; // Menyimpan sel header
                while ($header = mysql_fetch_array($headers)) {
                  $tanggalArray[] = $header['tanggal'];
                  echo "<th>{$header['tujuan_pembelajaran']}</th>";
                  $headerCells[] = $header['tujuan_pembelajaran']; 
                }
                ?>
              </tr>
            </thead>
            <tbody>
              <?php
              $no = 1;
              $tampil = mysql_query("SELECT * FROM rb_siswa a JOIN rb_jenis_kelamin b ON a.id_jenis_kelamin=b.id_jenis_kelamin WHERE a.kode_kelas='$_GET[id]' ORDER BY a.id_siswa");
              $kktp = mysql_query("SELECT * FROM rb_jadwal_pelajaran WHERE kodejdwl='$_GET[idjr]'");
              $kk = mysql_fetch_array($kktp);

              while ($r = mysql_fetch_array($tampil)) {
                $totalAbsensi = 0; // Reset total absensi untuk setiap siswa
                echo "
                <tr>
                  <td>$no</td>
                  <td>$r[nama]
                    <input type='hidden' value='$r[nisn]' name='nisn[$no]'>
                  </td>
                  <td>$kk[kktp]</td>";

                // Loop untuk nilai absensi
                for ($i = 0; $i < $header_count; $i++) {
                  $abs = mysql_fetch_array(mysql_query("SELECT * FROM rb_absensi_siswa 
                                       WHERE kodejdwl='" . mysql_real_escape_string($_GET['idjr']) . "' 
                                       AND nisn='" . mysql_real_escape_string($r['nisn']) . "' 
                                       AND tanggal='" . mysql_real_escape_string($tanggalArray[$i]) . "' ORDER BY tanggal ASC"));
                  $totalAbsensi += (isset($abs['total']) ? $abs['total'] : 0); // Tambahkan absensi
                  $nilaiArray[] = isset($abs['total']) ? $abs['total'] : 0; // Simpan nilai absensi ke dalam array
                  echo "<td>" . (isset($abs['total']) ? $abs['total'] : 0) . "</td>";
                }
                $maxIndex = array_search(max($nilaiArray), $nilaiArray); 
                echo "<td class='nilai-max'>";
                echo "<input type='hidden' name='header-nilai-tertinggi' value='{$headerCells[$maxIndex]}'/>";
                $nilaiTertinggi = max($nilaiArray);
                // echo $nilaiTertinggi; // Memastikan nilai tertinggi ditampilkan
                echo "</td>"; 

                $minIndex = array_search(min($nilaiArray), $nilaiArray); 
                echo "<td class='nilai-min'>";
                echo "<input type='hidden' name='header-nilai-tertinggi' value='{$headerCells[$minIndex]}'/>";
                $nilaiTerendah = min($nilaiArray);
                echo $nilaiTerendah; // Memastikan nilai tertinggi ditampilkan
                echo "</td>";

                // $minIndex = array_search(min($nilaiArray), $nilaiArray); 
                // echo"<td class='nilai-min'><input type='hidden' name='header-nilai-terendah' value='{$headerCells[$minIndex]}'/>"
                
                // .min($nilaiArray).
                // "</td>";
                // Hitung rata-rata
                echo "<td>";
                if ($header_count > 0) {
                  $rataRata = $totalAbsensi / $header_count;

                  // Validasi sebelum insert atau update
                  $cekData = mysql_query("SELECT * FROM rb_nilai_srl WHERE kodejdwl='" . mysql_real_escape_string($_GET['idjr']) . "' AND nisn='" . mysql_real_escape_string($r['nisn']) . "'");
                  if (mysql_num_rows($cekData) > 0) {
                    echo"dskdsjmd";
                    // Jika data sudah ada, lakukan update
                    $queryUpdate = "UPDATE rb_nilai_srl 
                                    SET nilai='" . mysql_real_escape_string($rataRata) . "',nilai_tertinggi='" . mysql_real_escape_string($nilaiTertinggi) . "',nilai_terendah='" . mysql_real_escape_string($nilaiTerendah) . "', waktu_input=NOW() 
                                    WHERE kodejdwl='" . mysql_real_escape_string($_GET['idjr']) . "' 
                                    AND nisn='" . mysql_real_escape_string($r['nisn']) . "'"
                                    ;
                    mysql_query($queryUpdate);
                  } else {
                    echo "else ini";

                    // Jika data belum ada, lakukan insert
                    $queryInsert = "INSERT INTO rb_nilai_srl (id_nilai_srl,kodejdwl, nisn, nilai, nilai_tertinggi, nilai_terendah, waktu_input)

                                    VALUES ('',
                                            '" . mysql_real_escape_string($_GET['idjr']) . "', 
                                            '" . mysql_real_escape_string($r['nisn']) . "', 
                                            '" . mysql_real_escape_string($rataRata) . "', 
                                            '" . mysql_real_escape_string($nilaiTertinggi) . "', 
                                            '" . mysql_real_escape_string($nilaiTerendah) . "', 
                                            NOW())";
                                            // var_dump($queryInsert);
                    mysql_query($queryInsert);
                  }

                  echo round($rataRata, 2); // Tampilkan nilai rata-rata
                } else {
                  echo "0";
                }
                echo "</td>";

                echo "<td>";
                $cekNilai = mysql_fetch_array(mysql_query("SELECT * FROM rb_nilai_srl WHERE kodejdwl='" . mysql_real_escape_string($_GET['idjr']) . "' AND nisn='" . mysql_real_escape_string($r['nisn']) . "'"));
                if ($cekNilai && $cekNilai['nilai'] < $kk['kktp']) {
                    echo "<a href='#' style='color: red;'>Remedial</a>";
                } else {
                    echo "<span style='color: green;'>Lulus</span>";
                }
                echo "</td>";
                
                echo "</tr>";

                $no++;
              }
              ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
<?php 
}elseif($_GET['act'] == 'rekapsiswa') {
cek_session_siswa();
?>

<div class="col-xs-12">
    <div class="box">
      <div class="box-header">
        <h3 class="box-title">
          <?php
          if (isset($_GET['kelas']) and isset($_GET['tahun'])) {
            echo "Rekap Absensi Siswa";
          } else {
            echo "Rekap Sumatif Ruang Lingkup " . date('Y');
          }
          ?>
        </h3>
      </div>
      <div class="box-body">
        <div class="table-responsive">
          <table>
            <thead>
              <tr>
                <th rowspan="2">No</th>
                <th rowspan="2">Nama Siswa</th>
                <th rowspan="2">KKTP</th>
                <?php
                $headers = mysql_query("SELECT * FROM rb_journal_list WHERE kodejdwl='$_GET[idjr]' AND id_parent_journal IS NULL ORDER BY tanggal ASC");
                $header_count = mysql_num_rows($headers);

                // if ($header_count > 0) {
                    echo "<th colspan='$header_count'>SUMATIF LINGKUP MATERI</th>";
                // } else {
                    // echo "<th colspan='1'>Tidak ada data</th>"; // Menampilkan pesan jika tidak ada data
                // }
                ?>
                <th rowspan="2">Nilai Tertinggi</th>
                <th rowspan="2">Nilai Terendah</th>
                <th rowspan="2">NA SUMATIF (S)</th>
                <th rowspan="2">Status</th>
              </tr>
              <tr>
                <?php
                // $headerCells = ""; // Menyimpan sel header
                while ($header = mysql_fetch_array($headers)) {
                  $tanggalArray[] = $header['tanggal'];
                  echo "<th>{$header['tujuan_pembelajaran']}</th>";
                  $headerCells[] = $header['tujuan_pembelajaran']; 
                }

                if($header_count == 0){
                  echo "<th>Tidak ada tujuan pemb.</th>";
                }
                
                ?>
              </tr>
            </thead>
            <tbody>
              <?php
              $no = 1;
              $tampil = mysql_query("SELECT * FROM rb_siswa a JOIN rb_jenis_kelamin b ON a.id_jenis_kelamin=b.id_jenis_kelamin WHERE a.nisn='$_SESSION[id]' ORDER BY a.id_siswa");
              $kktp = mysql_query("SELECT * FROM rb_jadwal_pelajaran WHERE kodejdwl='$_GET[idjr]'");
              $kk = mysql_fetch_array($kktp);

              while ($r = mysql_fetch_array($tampil)) {
                $totalAbsensi = 0; // Reset total absensi untuk setiap siswa
                echo "
                <tr>
                  <td>$no</td>
                  <td>$r[nama]
                    <input type='hidden' value='$r[nisn]' name='nisn[$no]'>
                  </td>
                  <td>$kk[kktp]</td>";
                  if($header_count == 0){
                    echo "<td>Tidak ada </td>"; 
                  }

                // Loop untuk nilai absensi
                for ($i = 0; $i < $header_count; $i++) {
                  $abs = mysql_fetch_array(mysql_query("SELECT * FROM rb_absensi_siswa 
                                       WHERE kodejdwl='" . mysql_real_escape_string($_GET['idjr']) . "' 
                                       AND nisn='" . mysql_real_escape_string($r['nisn']) . "' 
                                       AND tanggal='" . mysql_real_escape_string($tanggalArray[$i]) . "' ORDER BY tanggal ASC"));
                  $totalAbsensi += (isset($abs['total']) ? $abs['total'] : 0); // Tambahkan absensi
                  $nilaiArray[] = isset($abs['total']) ? $abs['total'] : 0; // Simpan nilai absensi ke dalam array
                  echo "<td>" . (isset($abs['total']) ? $abs['total'] : 0) . "</td>";
                }
                $maxIndex = array_search(max($nilaiArray), $nilaiArray); 
                echo "<td class='nilai-max'>";
                echo "<input type='hidden' name='header-nilai-tertinggi' value='{$headerCells[$maxIndex]}'/>";
                $nilaiTertinggi = max($nilaiArray);
                echo isset($nilaiTertinggi) ? $nilaiTertinggi : 0 ; // Memastikan nilai tertinggi ditampilkan
                echo "</td>";

                $minIndex = array_search(min($nilaiArray), $nilaiArray); 
                echo "<td class='nilai-min'>";
                echo "<input type='hidden' name='header-nilai-tertinggi' value='{$headerCells[$minIndex]}'/>";
                $nilaiTerendah = min($nilaiArray);
                echo isset($nilaiTerendah) ? $nilaiTerendah : 0 ; // Memastikan nilai tertinggi ditampilkan                // Memastikan nilai tertinggi ditampilkan
                echo "</td>";

                // $minIndex = array_search(min($nilaiArray), $nilaiArray); 
                // echo"<td class='nilai-min'><input type='hidden' name='header-nilai-terendah' value='{$headerCells[$minIndex]}'/>"
                
                // .min($nilaiArray).
                // "</td>";
                // Hitung rata-rata
                echo "<td>";
                if ($header_count > 0) {
                  $rataRata = $totalAbsensi / $header_count;


                  echo round($rataRata, 2); // Tampilkan nilai rata-rata
                } else {
                  echo "0";
                }
                echo "</td>";

                echo "<td>";
                $cekNilai = mysql_fetch_array(mysql_query("SELECT * FROM rb_nilai_srl WHERE kodejdwl='" . mysql_real_escape_string($_GET['idjr']) . "' AND nisn='" . mysql_real_escape_string($r['nisn']) . "'"));
                if ($cekNilai && $cekNilai['nilai'] < $kk['kktp']) {
                    echo "<a href='#' style='color: red;'>Remedial</a>";
                } else {
                    echo "<span style='color: green;'>Lulus</span>";
                }
                echo "</td>";
                
                echo "</tr>";

                $no++;
              }
              ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
<?php } ?>

