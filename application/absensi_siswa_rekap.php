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
</style>

<?php if ($_GET[act] == '') { ?>
  <div class="col-xs-12">
    <div class="box">
      <div class="box-header">
        <h3 class="box-title"><?php if (isset($_GET[kelas]) and isset($_GET[tahun])) {
                                echo "Rekap Absensi siswa";
                              } else {
                                echo "Rekap Absensi Siswa Pada Tahun " . date('Y');
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
        <table id="example" class="table table-bordered table-striped">
          <thead>
            <tr>
              <th style='width:20px'>No</th>
              <th>Jadwal Pelajaran</th>
              <th>Kelas</th>
              <th>Guru</th>
              <th>Hari</th>
              <th>Mulai</th>
              <th>Selesai</th>
              <th>Ruangan</th>
              <th>Semester</th>
              <?php if ($_SESSION['level'] != 'kepala') { ?>
                <th>Action</th>
              <?php } ?>
            </tr>
          </thead>
          <tbody>
            <?php
            if (isset($_GET['kelas']) && isset($_GET['tahun'])) {
              $tampil = mysql_query("SELECT a.*, e.nama_kelas, b.namamatapelajaran, b.kode_pelajaran, b.kode_kurikulum, c.nama_guru, d.nama_ruangan FROM rb_jadwal_pelajaran a 
                                              JOIN rb_mata_pelajaran b ON a.kode_pelajaran=b.kode_pelajaran
                                                JOIN rb_guru c ON a.nip=c.nip 
                                                  JOIN rb_ruangan d ON a.kode_ruangan=d.kode_ruangan
                                                    JOIN rb_kelas e ON a.kode_kelas=e.kode_kelas 
                                                    WHERE a.kode_kelas='$_GET[kelas]' 
                                                      AND a.id_tahun_akademik='$_GET[tahun]' 
                                                        AND b.kode_kurikulum='$kurikulum[kode_kurikulum]' ORDER BY a.hari DESC");
            }
            $no = 1;
            while ($r = mysql_fetch_array($tampil)) {
              echo "<tr><td>$no</td>
                            <td>$r[namamatapelajaran]</td>
                            <td>$r[nama_kelas]</td>
                            <td>$r[nama_guru]</td>
                            <td>$r[hari]</td>
                            <td>$r[jam_mulai]</td>
                            <td>$r[jam_selesai]</td>
                            <td>$r[nama_ruangan]</td>
                            <td>$r[id_tahun_akademik]</td>";
                echo "<td style='width:70px !important'><center>
                              <a class='btn btn-success btn-xs' title='Tampil List Absensi' href='index.php?view=rekapabsensiswa&act=tampilabsen&id=$r[kode_kelas]&kd=$r[kode_pelajaran]&jdwl=$r[kodejdwl]&tahun=$_GET[tahun]'><span class='glyphicon glyphicon-th'></span> Tampilkan</a>
                            </center></td>";
              echo "</tr>";
              $no++;
            }
            ?>
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
} elseif ($_GET['act'] == 'tampilabsen') {  
  $d = mysql_fetch_array(mysql_query("SELECT * FROM rb_kelas WHERE kode_kelas='$_GET[id]'"));  
  $m = mysql_fetch_array(mysql_query("SELECT * FROM rb_mata_pelajaran WHERE kode_pelajaran='$_GET[kd]'"));  

  echo "<div class='col-md-12 table-responsive'>  
          <div class='box box-info'>  
              <div class='box-header with-border'>  
                  <h3 class='box-title'>Rekap Data Absensi Siswa Pada $_GET[tahun]</h3>  
              </div>  
              <div class='box-body'>  
                  <table class='table table-bordered'>  
                      <thead>  
                          <tr>  
                              <th rowspan='2'>No</th>  
                              <th rowspan='2'>Nama Siswa</th>  
                              <th rowspan='2'>Jenis Kelamin</th>  
                              <th colspan='31'>Hari</th>  
                              <th rowspan='2'>Hadir</th>  
                              <th rowspan='2'>Tidak Hadir</th>  
                              <th rowspan='2'>Total Hari</th>  
                          </tr>  
                          <tr>";  

  // Generate the day headers (1 to 31)  
  for ($day = 1; $day <= 31; $day++) {  
      echo "<th>$day</th>";  
  }  

  echo "      </tr>  
              </thead>  
              <tbody>";  

  $no = 1;  
  $tampil = mysql_query("SELECT * FROM rb_siswa a JOIN rb_jenis_kelamin b ON a.id_jenis_kelamin=b.id_jenis_kelamin WHERE a.kode_kelas='$_GET[id]' ORDER BY a.id_siswa");  

  while ($r = mysql_fetch_array($tampil)) {  
      echo "<tr>  
              <td>$no</td>  
              <td>$r[nama]</td>  
              <td>$r[jenis_kelamin]</td>";  

      // Loop through each day of the month to check attendance  
      $hadir = 0;  
      $tidak_hadir = 0;  

      for ($day = 1; $day <= 31; $day++) {  
          $attendanceQuery = mysql_query("SELECT kode_kehadiran FROM rb_absensi_siswa WHERE kodejdwl='$_GET[jdwl]' AND nisn='$r[nisn]' AND DAY(tanggal) = '$day'");  

          if ($attendanceRow = mysql_fetch_array($attendanceQuery)) {  
              $status = ($attendanceRow['kode_kehadiran'] == 'H') ? 'ok' : 'X'; // 'ok' for hadir, 'X' for tidak hadir  
              if ($status == 'ok') {  
                  $hadir++;  
              } else {  
                  $tidak_hadir++;  
              }  
          } else {  
              $status = '-'; // No record for this day  
          }  

          echo "<td>$status</td>";  
      }  

      $total_hari = $hadir + $tidak_hadir;  

      echo "      <td>$hadir</td>  
                  <td>$tidak_hadir</td>  
                  <td>$total_hari</td>  
              </tr>";  
      
      $no++;  
  }  

  echo "      </tbody>  
              </table>  
              <div>  
                  <p>Keterangan:</p>  
                  <p>X = Tidak Hadir</p>  
                  <p>ok = Hadir</p>  
              </div>  
          </div>  
      </div>  
  </div>";  
}  
?>

