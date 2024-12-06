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
        <?php
        $siswa = mysql_fetch_array(mysql_query("SELECT * FROM rb_siswa WHERE nisn='$_GET[id]'"));
        ?>
      </div>
      <div class="box-body">
        <div class="table-responsive">
          <table>
            <thead>
              <tr>
                <th rowspan="2">No</th>
                <th rowspan="2">Nama Siswa</th>
                <?php
                // Ambil data header dari tabel rb_journal_list
                $headers = mysql_query("SELECT * FROM rb_journal_list where kodejdwl='$_GET[idjr]' AND id_parent_journal IS NULL ORDER BY tanggal ASC");
                $tanggalArray = []; // Array untuk menyimpan tanggal
                while ($header = mysql_fetch_array($headers)) {
                  $tanggalArray[] = $header['tanggal']; // Simpan tanggal
                  echo "<th>{$header['tujuan_pembelajaran']}</th>";
                }
                $header_count = count($tanggalArray); // Hitung jumlah header
                ?>
                <th rowspan="2">NA SUMATIF (S)</th>
                <th rowspan="2">STS</th>
                <th rowspan="2">NON TES</th>
                <th rowspan="2">NA SUMATIF AKHIR SEMESTER (AS)</th>
                <th rowspan="2">Nilai Rapor<br>(Rerata S + AS)</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $no = 1;
              $tampil = mysql_query("SELECT * FROM rb_siswa a JOIN rb_jenis_kelamin b ON a.id_jenis_kelamin=b.id_jenis_kelamin WHERE a.kode_kelas='$_GET[id]' ORDER BY a.id_siswa");
              while ($r = mysql_fetch_array($tampil)) {
                $total_nilai = 0; // Variabel untuk menjumlahkan nilai
                echo "
                <tr>
                <td>$no</td>
                <td>$r[nama]
                <input type='number' value='$r[nisn]' name='nisn[$no]' style='width:50px;' hidden>
                </td>";

                // Loop untuk setiap header/tanggal
                for ($i = 0; $i < $header_count; $i++) {
                  $abs = mysql_fetch_array(mysql_query("SELECT * FROM rb_absensi_siswa 
                                       WHERE kodejdwl='" . mysql_real_escape_string($_GET['idjr']) . "' 
                                       AND nisn='" . mysql_real_escape_string($r['nisn']) . "' 
                                       AND tanggal='" . mysql_real_escape_string($tanggalArray[$i]) . "' ORDER BY tanggal ASC"));

                  $nilai = $abs ? $abs['total'] : 0; // Gunakan 0 jika tidak ada nilai
                  $total_nilai += $nilai; // Tambahkan nilai ke total
                  echo "<td>$nilai</td>";
                }

                // Hitung NA SUMATIF (S)
                $na_sumatif = $total_nilai / $header_count;

                // Contoh nilai STS, NON TES, dan NA SUMATIF AKHIR SEMESTER
                $sts = 88;
                $non_tes = 95;
                $na_sumatif_akhir = 90;

                // Hitung Nilai Rapor
                $nilai_rapor = ($na_sumatif + $na_sumatif_akhir) / 2;

                echo "
                    <td>" . round($na_sumatif, 2) . "</td>
                    <td>$sts</td>
                    <td>$non_tes</td>
                    <td>$na_sumatif_akhir</td>
                    <td>" . round($nilai_rapor, 2) . "</td>
                  </tr>";
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
