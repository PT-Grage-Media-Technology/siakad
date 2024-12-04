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
}
?>

