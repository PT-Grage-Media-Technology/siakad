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

