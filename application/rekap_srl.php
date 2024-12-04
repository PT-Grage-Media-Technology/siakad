<style>
  .table-responsive {
    overflow-x: auto;
    /* Hanya aktifkan scroll horizontal jika diperlukan */
  }

  @media (min-width: 768px) {
    .table-responsive {
      overflow-x: visible;
      /* Nonaktifkan scroll horizontal di desktop */
    }
  }

  /* Gaya tabel baru */
  table {
    border-collapse: collapse;
    width: 100%;
    text-align: center;
  }
  th, td {
    border: 1px solid black;
    padding: 5px;
  }
  th {
    background-color: #f2f2f2;
  }
</style>

<?php if ($_GET[act] == '') { ?>
  <div class="col-xs-12">
    <div class="box">
      <div class="box-header">
        <h3 class="box-title"><?php if (isset($_GET[kelas]) and isset($_GET[tahun])) {
                                echo "Rekap Absensi siswa";
                              } else {
                                echo "Rekap Sumatif Ruang Lingkup " . date('Y');
                              } ?></h3>
        <form style='margin-right:5px; margin-top:0px' class='pull-right' action='' method='GET'>
          <input type="hidden" name='view' value='rekapabsensiswa'>

          <!-- Dropdown Tahun Akademik -->
          <select name='tahun' style='padding:4px' onchange="this.form.submit()">
              <?php 
                  echo "<option value=''>- Pilih Tahun Akademik -</option>";
                  
                  // Query untuk mendapatkan semua tahun akademik
                  $query_tahun_akademik = mysql_query("SELECT id_tahun_akademik, nama_tahun FROM rb_tahun_akademik ORDER BY id_tahun_akademik DESC");
                  $tahun_akademik_terbaru = mysql_fetch_array(mysql_query("SELECT id_tahun_akademik FROM rb_tahun_akademik ORDER BY id_tahun_akademik DESC LIMIT 1"));
                  
                  while ($row = mysql_fetch_array($query_tahun_akademik)) {
                      // Pilih tahun sesuai dengan $_GET['tahun'], atau default ke tahun terbaru
                      if ($_GET['tahun'] == $row['id_tahun_akademik']) {
                          echo "<option value='".$row['id_tahun_akademik']."' selected>".$row['nama_tahun']."</option>";
                      } elseif (!isset($_GET['tahun']) && $row['id_tahun_akademik'] == $tahun_akademik_terbaru['id_tahun_akademik']) {
                          echo "<option value='".$row['id_tahun_akademik']."' selected>".$row['nama_tahun']."</option>";
                      } else {
                          echo "<option value='".$row['id_tahun_akademik']."'>".$row['nama_tahun']."</option>";
                      }
                  }
              ?>
          </select>

    <!-- Dropdown Kelas -->
    <select name='kelas' style='padding:4px' onchange="this.form.submit()">
        <?php 
            echo "<option value=''>- Pilih Kelas -</option>";
            
            // Query untuk mendapatkan semua kelas
            $kelas = mysql_query("SELECT * FROM rb_kelas");
            while ($k = mysql_fetch_array($kelas)) {
                // Pilih kelas sesuai dengan $_GET['kelas']
                if ($_GET['kelas'] == $k['kode_kelas']) {
                    echo "<option value='$k[kode_kelas]' selected>$k[kode_kelas] - $k[nama_kelas]</option>";
                } else {
                    echo "<option value='$k[kode_kelas]'>$k[kode_kelas] - $k[nama_kelas]</option>";
                }
            }
        ?>
    </select>
</form>

      </div><!-- /.box-header -->
      <div class="box-body">
      <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th rowspan="2">No</th>
                    <th rowspan="2">Nama Siswa</th>
                    <th colspan="3">SUMATIF LINGKUP MATERI</th>
                    <th rowspan="2">NA SUMATIF (S)</th>
                    <th rowspan="2">STS</th>
                    <th rowspan="2">NON TES</th>
                    <th rowspan="2">NA SUMATIF AKHIR SEMESTER (AS)</th>
                    <th rowspan="2">Nilai Rapor<br>(Rerata S + AS)</th>
                </tr>
                <tr>
                    <th>Proses perumusan pancasila</th>
                    <th>Proses perumusan pancasila</th>
                    <th>Proses perumusan pancasila</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>ABDUL RISKI</td>
                    <td>90</td>
                    <td>90</td>
                    <td>90</td>
                    <td>80</td>
                    <td>88</td>
                    <td>95</td>
                    <td>90</td>
                    <td>90</td>
                </tr>
                <!-- ... existing code for dynamic rows ... -->
            </tbody>
        </table>
      </div>
      </div><!-- /.box-body -->
      <?php
      if ($_GET[kelas] == '' and $_GET[tahun] == '') {
        echo "<center style='padding:60px; color:red'>Silahkan Memilih Tahun akademik dan Kelas Terlebih dahulu...</center>";
      }
      ?>
    </div>
  </div>
<?php
} elseif ($_GET[act] == 'tampilabsen') {
$d = mysql_fetch_array(mysql_query("SELECT * FROM rb_kelas where kode_kelas='$_GET[id]'"));
$m = mysql_fetch_array(mysql_query("SELECT * FROM rb_mata_pelajaran where kode_pelajaran='$_GET[kd]'"));
echo "<div class='col-md-12 table-responsive'>
        <div class='box box-info table-responsive'>
            <div class='box-header with-border'>
                <h3 class='box-title'>Rekap Data Absensi Siswa Pada $_GET[tahun]</b></h3>
            </div>
            <div class='box-body'>
                <div class='col-md-12'>
                    <table class='table table-condensed table-hover'>
                        <tbody>
                            <input type='hidden' name='id' value='$s[kode_kelas]'>
                            <tr><th width='120px' scope='row'>Kode Kelas</th> <td>$d[kode_kelas]</td></tr>
                            <tr><th scope='row'>Nama Kelas</th> <td>$d[nama_kelas]</td></tr>
                            <tr><th scope='row'>Mata Pelajaran</th> <td>$m[namamatapelajaran]</td></tr>
                        </tbody>
                    </table>
                </div>
                <div class='col-md-12'>
                    <table class='table table-condensed table-bordered table-striped table-responsive'>
                        <thead>
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
                                <th>Sikap</th>
                                <th>Keterampilan</th>
                                <th>Pengetahuan</th>
                                <th>Total</th>
                                <th>Rata-Rata</th>
                                <th><center>% Kehadiran</center></th>
                            </tr>
                        </thead>
                        <tbody>";

            $no = 1;
            $tampil = mysql_query("SELECT * FROM rb_siswa a JOIN rb_jenis_kelamin b ON a.id_jenis_kelamin=b.id_jenis_kelamin where a.kode_kelas='$_GET[id]' ORDER BY a.id_siswa");
            while ($r = mysql_fetch_array($tampil)) {
                $total = mysql_num_rows(mysql_query("SELECT * FROM `rb_absensi_siswa` where kodejdwl='$_GET[jdwl]' GROUP BY tanggal"));
                $hadir = mysql_num_rows(mysql_query("SELECT * FROM `rb_absensi_siswa` where kodejdwl='$_GET[jdwl]' AND nisn='$r[nisn]' AND kode_kehadiran='H'"));
                $sakit = mysql_num_rows(mysql_query("SELECT * FROM `rb_absensi_siswa` where kodejdwl='$_GET[jdwl]' AND nisn='$r[nisn]' AND kode_kehadiran='S'"));
                $izin = mysql_num_rows(mysql_query("SELECT * FROM `rb_absensi_siswa` where kodejdwl='$_GET[jdwl]' AND nisn='$r[nisn]' AND kode_kehadiran='I'"));
                $alpa = mysql_num_rows(mysql_query("SELECT * FROM `rb_absensi_siswa` where kodejdwl='$_GET[jdwl]' AND nisn='$r[nisn]' AND kode_kehadiran='A'"));
                
                $akademik_query = mysql_query("SELECT * FROM `rb_absensi_siswa` WHERE kodejdwl='$_GET[jdwl]' AND nisn='$r[nisn]'");
                $total_nilai_sikap = 0;
                $total_nilai_keterampilan = 0;
                $total_nilai_pengetahuan = 0;
                $jumlah_pertemuan = 0;

                while ($akademik = mysql_fetch_array($akademik_query)) {
                    $total_nilai_sikap += $akademik['nilai_sikap'];
                    $total_nilai_keterampilan += $akademik['nilai_keterampilan'];
                    $total_nilai_pengetahuan += $akademik['nilai_pengetahuan'];
                    $jumlah_pertemuan++;
                }

                // Hitung rata-rata
                $divider = $jumlah_pertemuan * 3; // Total nilai sikap, keterampilan, pengetahuan per pertemuan
                $rata_rata = ($divider > 0) ? (($total_nilai_sikap + $total_nilai_keterampilan + $total_nilai_pengetahuan) / $divider) : 0;

                $persen = $hadir / ($total) * 100;
                echo "<tr>
                        <td>$no</td>
                        <td>$r[nisn]</td>
                        <td>$r[nama]</td>
                        <td>$r[jenis_kelamin]</td>
                        <td align=center>$jumlah_pertemuan</td>
                        <td align=center>$hadir</td>
                        <td align=center>$sakit</td>
                        <td align=center>$izin</td>
                        <td align=center>$alpa</td>
                        <td align=center>$total_nilai_sikap</td>
                        <td align=center>$total_nilai_keterampilan</td>
                        <td align=center>$total_nilai_pengetahuan</td>
                        <td align=center>".($total_nilai_sikap + $total_nilai_keterampilan + $total_nilai_pengetahuan)."</td>
                        <td align=center>".number_format($rata_rata, 2)."</td>
                        <td align=right>".number_format($persen, 2)." %</td>
                      </tr>";
                $no++;
            }

            echo "</tbody>
                  </table>
                </div>
              </div>
            </div>";

}
?>

