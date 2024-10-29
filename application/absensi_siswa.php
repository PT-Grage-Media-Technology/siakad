<?php if ($_GET[act] == '') { ?>
  <div class="col-xs-12">
    <div class="box">
      <div class="box-header">
        <h3 class="box-title">
          <?php if (isset($_GET[kelas]) and isset($_GET[tahun])) {
            echo "Absensi siswa";
          } else {
            echo "Absensi Siswa Pada Tahun " . date('Y');
          } ?>
        </h3>
        <form style='margin-right:5px; margin-top:0px' class='pull-right' action='' method='GET'>
          <input type="hidden" name='view' value='absensiswa'>
          <select name='tahun' style='padding:4px'>
            <?php
            echo "<option value=''>- Pilih Tahun Akademik -</option>";
            $tahun = mysql_query("SELECT * FROM rb_tahun_akademik");
            while ($k = mysql_fetch_array($tahun)) {
              if ($_GET[tahun] == $k[id_tahun_akademik]) {
                echo "<option value='$k[id_tahun_akademik]' selected>$k[nama_tahun]</option>";
              } else {
                echo "<option value='$k[id_tahun_akademik]'>$k[nama_tahun]</option>";
              }
            }
            ?>
          </select>
          <select name='kelas' style='padding:4px'>
            <?php
            echo "<option value=''>- Pilih Kelas -</option>";
            $kelas = mysql_query("SELECT * FROM rb_kelas");
            while ($k = mysql_fetch_array($kelas)) {
              if ($_GET[kelas] == $k[kode_kelas]) {
                echo "<option value='$k[kode_kelas]' selected>$k[kode_kelas] - $k[nama_kelas]</option>";
              } else {
                echo "<option value='$k[kode_kelas]'>$k[kode_kelas] - $k[nama_kelas]</option>";
              }
            }
            ?>
          </select>
          <input type="submit" style='margin-top:-4px' class='btn btn-success btn-sm' value='Lihat'>
        </form>

      </div><!-- /.box-header -->
      <div class="box-body">
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
              <?php if ($_SESSION[level] != 'kepala') { ?>
                <th>Action</th>
              <?php } ?>
            </tr>
          </thead>
          <tbody>
            <?php
            if (isset($_GET[kelas]) and isset($_GET[tahun])) {
              $tampil = mysql_query("SELECT a.*, e.nama_kelas, b.namamatapelajaran, b.kode_pelajaran, b.kode_kurikulum, c.nama_guru, d.nama_ruangan 
              FROM rb_jadwal_pelajaran a 
              JOIN rb_mata_pelajaran b ON a.kode_pelajaran=b.kode_pelajaran
              JOIN rb_guru c ON a.nip=c.nip 
              JOIN rb_ruangan d ON a.kode_ruangan=d.kode_ruangan
              JOIN rb_kelas e ON a.kode_kelas=e.kode_kelas 
              WHERE a.kode_kelas='$_GET[kelas]' 
              AND a.id_tahun_akademik='$_GET[tahun]' 
              AND b.kode_kurikulum='$kurikulum[kode_kurikulum]' 
              ORDER BY a.hari DESC");

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
              if ($_SESSION[level] != 'kepala') {
                echo "<td style='width:70px !important'><center>
                                <a class='btn btn-success btn-xs' title='Tampil List Absensi' href='index.php?view=absensiswa&act=tampilabsen&id=$r[kode_kelas]&kd=$r[kode_pelajaran]&jdwl=$r[kodejdwl]'><span class='glyphicon glyphicon-th'></span> Tampilkan</a>
                              </center></td>";
              }
              echo "</tr>";
              $no++;
            }
            ?>
          </tbody>
        </table>
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
  if (isset($_GET['gettgl'])) {
      $filtertgl = $_GET['gettgl'];
      $exp = explode('-', $_GET['gettgl']);
      $tglc = $exp[2];
      $blnc = $exp[1];
      $thn = $exp[0];
  } else {
      $tgl = isset($_POST['tgl']) ? $_POST['tgl'] : date("d");
      $bln = isset($_POST['bln']) ? $_POST['bln'] : date("m");
      $thn = isset($_POST['thn']) ? $_POST['thn'] : date("Y");

      $tglc = str_pad($tgl, 2, '0', STR_PAD_LEFT);
      $blnc = str_pad($bln, 2, '0', STR_PAD_LEFT);

      $filtertgl = "$thn-$blnc-$tglc";
  }

  // Ambil data kelas
  $kelasQuery = "SELECT * FROM rb_kelas WHERE kode_kelas = '{$_GET['id']}'";
  $kelasResult = mysqli_query($conn, $kelasQuery);
  $d = mysqli_fetch_array($kelasResult);

  // Ambil data mata pelajaran
  $pelajaranQuery = "SELECT * FROM rb_mata_pelajaran WHERE kode_pelajaran = '{$_GET['kd']}'";
  $pelajaranResult = mysqli_query($conn, $pelajaranQuery);
  $m = mysqli_fetch_array($pelajaranResult);

  // Ambil data jadwal
  $journalQuery = "SELECT * FROM rb_journal_list WHERE kodejdwl = '{$_GET['idjr']}' AND tanggal = '{$_GET['tgl']}' AND jam_ke = '{$_GET['jam']}'";
  $journalResult = mysqli_query($conn, $journalQuery);
  $j = mysqli_fetch_array($journalResult);

  $ex = explode('-', $filtertgl);
  $tahun = $ex[0];
  $bulane = $ex[1];
  $tanggal = $ex[2];

  $tgle = ltrim($tanggal, '0');
  $blnee = ltrim($bulane, '0');

  echo "
  <div class='col-md-12'>
      <div class='box box-info'>
          <div class='box-header with-border'>
              <h3 class='box-title'>Data Absensi Siswa Pada : <b style='color:red'>" . tgl_indo($_GET['tgl']) . "</b></h3>
          </div>
          <div class='box-body mb-3'>
              <div class='col-md-12'>
                  <div class='table-responsive'>
                      <table class='table table-condensed table-hover'>
                          <tbody>
                              <input type='hidden' name='id' value='{$s['kode_kelas']}'>
                              <tr>
                                  <th width='120px' scope='row'>Kode Kelas</th>
                                  <td>{$d['kode_kelas']}</td>
                              </tr>
                              <tr>
                                  <th scope='row'>Nama Kelas</th>
                                  <td>{$d['nama_kelas']}</td>
                              </tr>
                              <tr>
                                  <th scope='row'>Mata Pelajaran</th>
                                  <td>{$m['namamatapelajaran']}</td>
                              </tr>
                              <tr>
                                  <th scope='row'>Tujuan Pembelajaran</th>
                                  <td>{$j['materi']}</td>
                              </tr>
                          </tbody>
                      </table>
                  </div>
                  <a class='btn btn-success btn-sm mb-2' title='Bahan dan Tugas' href='https://siakad.demogmt.online/index.php?view=bahantugas&act=listbahantugas&jdwl={$_GET['idjr']}&id={$_GET['id']}&kd={$_GET['kd']}'>
                      <div class='d-flex flex-column align-items-center'>
                          <div class='glyphicon glyphicon-tasks' style='font-size:28px; margin-right:5px;'></div>
                          <div class='' style='font-size:14px;'>Tugas</div>
                      </div>
                  </a>
              </div>
              <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
                  <input type='hidden' name='tgla' value='$tglc'>
                  <input type='hidden' name='blna' value='$blnc'>
                  <input type='hidden' name='thna' value='$thn'>
                  <input type='hidden' name='kelas' value='{$_GET['id']}'>
                  <input type='hidden' name='pelajaran' value='{$_GET['kd']}'>
                  <input type='hidden' name='jdwl' value='{$_GET['idjr']}'>
                  <div class='col-md-12'>
                      <div class='table-responsive'>
                          <table class='table table-condensed table-bordered table-striped'>
                              <thead>
                                  <tr>
                                      <th>No</th>
                                      <th>NIPD</th>
                                      <th>NISN</th>
                                      <th>Nama Siswa</th>
                                      <th>Jenis Kelamin</th>
                                      <th width='120px'>Kehadiran</th>
                                  </tr>
                              </thead>
                              <tbody>";

  $no = 1;
  $siswaQuery = "SELECT * FROM rb_siswa a JOIN rb_jenis_kelamin b ON a.id_jenis_kelamin = b.id_jenis_kelamin WHERE a.kode_kelas = '{$_GET['id']}' ORDER BY a.id_siswa";
  $siswaResult = mysqli_query($conn, $siswaQuery);

  while ($r = mysqli_fetch_array($siswaResult)) {
      $sekarangabsen = isset($_GET['gettgl']) ? $_GET['gettgl'] : (isset($_POST['lihat']) ? "$thn-$blnc-$tglc" : date("Y-m-d"));

      $absensiQuery = "SELECT * FROM rb_absensi_siswa WHERE kodejdwl = '{$_GET['jdwl']}' AND tanggal = '$sekarangabsen' AND nisn = '{$r['nisn']}'";
      $absensiResult = mysqli_query($conn, $absensiQuery);
      $a = mysqli_fetch_array($absensiResult);
      
      echo "<tr>
              <td>$no</td>
              <td>{$r['nipd']}</td>
              <td>{$r['nisn']}</td>
              <td>{$r['nama']}</td>
              <td>{$r['jenis_kelamin']}</td>
              <input type='hidden' value='{$r['nisn']}' name='nisn[$no]'>";
      
      if (strtotime(date('Y-m-d')) > strtotime($_GET['tgl'])) {
          echo "<td><select disabled style='width:100px;' name='a[$no]' class='form-control'>";
      } else {
          echo "<td><select style='width:100px;' name='a[$no]' class='form-control'>";
      }

      $kehadiranQuery = "SELECT * FROM rb_kehadiran";
      $kehadiranResult = mysqli_query($conn, $kehadiranQuery);
      while ($k = mysqli_fetch_array($kehadiranResult)) {
          $selected = ($a['kode_kehadiran'] == $k['kode_kehadiran']) ? 'selected' : '';
          echo "<option value='{$k['kode_kehadiran']}' $selected>* {$k['nama_kehadiran']}</option>";
      }
      echo "</select></td>";
      echo "</tr>";
      $no++;
  }

  echo "</tbody>
              </table>
          </div>
      </div>
  </div>";

  if ($_SESSION['level'] != 'kepala') {
      $tglAbsen = $_GET['tgl'];
      $isDisabled = (strtotime(date('Y-m-d')) > strtotime($tglAbsen)) ? 'disabled' : '';

      echo "<div class='box-footer'>
          <button type='submit' name='simpann' class='btn btn-info pull-right' $isDisabled>Simpan Absensi</button>
        </div>";
  }

  echo "</form>
  </div>";

  if (isset($_POST['simpann'])) {
      $jml_data = count($_POST['nisn']);
      $nisn = $_POST['nisn'];
      $a = $_POST['a'];

      $e = $_POST['thna'];
      $f = $_POST['blna'];
      $g = $_POST['tgla'];
      $h = $_POST['jdwl'];
      $nip = $_SESSION['id'];

      for ($i = 1; $i <= $jml_data; $i++) {
          $cekQuery = "SELECT * FROM rb_absensi_siswa WHERE kodejdwl = '$h' AND nisn = '{$nisn[$i]}' AND tanggal = '$e-$f-$g'";
          $cekResult = mysqli_query($conn, $cekQuery);

          if (mysqli_num_rows($cekResult) > 0) {
              $updateQuery = "UPDATE rb_absensi_siswa SET kode_kehadiran = '$a[$i]', nip = '$nip' WHERE kodejdwl = '$h' AND nisn = '{$nisn[$i]}' AND tanggal = '$e-$f-$g'";
              mysqli_query($conn, $updateQuery);
          } else {
              $insertQuery = "INSERT INTO rb_absensi_siswa (kodejdwl, tanggal, nisn, kode_kehadiran, nip) VALUES ('$h', '$e-$f-$g', '{$nisn[$i]}', '$a[$i]', '$nip')";
              mysqli_query($conn, $insertQuery);
          }

          if ($a[$i] != 'H') {
              $parentQuery = "SELECT * FROM rb_siswa WHERE nisn = '{$nisn[$i]}'";
              $parentResult = mysqli_query($conn, $parentQuery);
              $parentData = mysqli_fetch_assoc($parentResult);

              $no_hp = $parentData['no_hp'];
              $msg = "Yth. Orang Tua/Wali, anak Anda {$parentData['nama']} Absen pada " . tgl_indo($tglAbsen) . " dengan status {$a[$i]}.";

              if ($no_hp != '') {
                  kirim_sms($no_hp, $msg);
              }
          }
      }
      echo "<script>window.location='?view=absensi&act=tampilabsen&jdwl={$_GET['idjr']}&id={$_GET['id']}&kd={$_GET['kd']}&tgl=$tglAbsen';</script>";
  }

} elseif ($_GET[act] == 'detailabsenguru') { ?>
  <div class="col-xs-12">
    <div class="box">
      <div class="box-header">
        <h3 class="box-title">
          <?php if (isset($_GET[tahun])) {
            echo "Absensi Siswa";
          } else {
            echo "Absensi Siswa Pada " . date('Y');
          } ?>
        </h3>
        <form style='margin-right:5px; margin-top:0px' class='pull-right' action='' method='GET'>
          <input type="hidden" name='view' value='absensiswa'>
          <input type="hidden" name='act' value='detailabsenguru'>
          <select name='tahun' style='padding:4px'>
            <?php
            echo "<option value=''>- Pilih Tahun Akademik -</option>";
            $tahun = mysql_query("SELECT * FROM rb_tahun_akademik");
            while ($k = mysql_fetch_array($tahun)) {
              if ($_GET[tahun] == $k[id_tahun_akademik]) {
                echo "<option value='$k[id_tahun_akademik]' selected>$k[nama_tahun]</option>";
              } else {
                echo "<option value='$k[id_tahun_akademik]'>$k[nama_tahun]</option>";
              }
            }
            ?>
          </select>
          <input type="submit" style='margin-top:-4px' class='btn btn-success btn-sm' value='Lihat'>
        </form>
      </div><!-- /.box-header -->

      <div class="box-body">
        <!-- Tambahkan wrapper table-responsive -->
        <div class="table-responsive">
          <table id="example1" class="table table-bordered table-striped">
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
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php
              if (isset($_GET[tahun])) {
                $tampil = mysql_query("SELECT a.*, e.nama_kelas, b.namamatapelajaran, b.kode_pelajaran, c.nama_guru, d.nama_ruangan FROM rb_jadwal_pelajaran a 
                  JOIN rb_mata_pelajaran b ON a.kode_pelajaran=b.kode_pelajaran
                  JOIN rb_guru c ON a.nip=c.nip 
                  JOIN rb_ruangan d ON a.kode_ruangan=d.kode_ruangan
                  JOIN rb_kelas e ON a.kode_kelas=e.kode_kelas 
                  where a.nip='$_SESSION[id]' AND a.id_tahun_akademik='$_GET[tahun]' ORDER BY a.hari DESC");
              } else {
                $tampil = mysql_query("SELECT a.*, e.nama_kelas, b.namamatapelajaran, b.kode_pelajaran, c.nama_guru, d.nama_ruangan FROM rb_jadwal_pelajaran a 
                  JOIN rb_mata_pelajaran b ON a.kode_pelajaran=b.kode_pelajaran
                  JOIN rb_guru c ON a.nip=c.nip 
                  JOIN rb_ruangan d ON a.kode_ruangan=d.kode_ruangan
                  JOIN rb_kelas e ON a.kode_kelas=e.kode_kelas 
                  where a.nip='$_SESSION[id]' AND a.id_tahun_akademik LIKE '" . date('Y') . "%' ORDER BY a.hari DESC");
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
                  <td>$r[id_tahun_akademik]</td>
                  <td><a class='btn btn-success btn-xs' title='Tampil List Absensi' href='index.php?view=absensiswa&act=tampilabsen&id=$r[kode_kelas]&kd=$r[kode_pelajaran]'>
                  <span class='glyphicon glyphicon-th'></span> Tampilk Absensi</a></td>
                </tr>";
                $no++;
              }
              ?>
            </tbody>
          </table>
        </div><!-- /.table-responsive -->
      </div><!-- /.box-body -->
    </div>
  </div>

  <?php
} elseif ($_GET[act] == 'detailabsensiswa') {
  echo "<div class='col-xs-12'>  
              <div class='box'>
                <div class='box-header'>
                  <h3 class='box-title'>Data Absensi Siswa untuk Mata Pelajaran yang di Ampu</h3>
                </div>
                <div class='box-body'>
                <b class='semester'>SEMESTER 1</b>
                <table class='table table-bordered table-striped'>
                      <tr>
                        <th style='width:40px'>No</th>
                        <th>Kode Pelajaran</th>
                        <th>Nama Pelajaran</th>
                        <th>Kelas</th>
                        <th>Hari</th>
                        <th>Jam Mulai</th>
                        <th>Jam Selesai</th>
                        <th>Action</th>
                      </tr>";
  $tampil = mysql_query("SELECT * FROM rb_jadwal_pelajaran a 
                                            JOIN rb_mata_pelajaran b ON a.kodepelajaran=b.kodepelajaran
                                              JOIN rb_guru c ON a.nip=c.nip 
                                                JOIN rb_kelas d ON a.kodekelas=d.kodekelas where a.kodekelas='$iden[kodekelas]' AND a.semester='1' ORDER BY a.hari DESC");
  $no = 1;
  while ($r = mysql_fetch_array($tampil)) {
    echo "<tr><td>$no</td>
                              <td>$r[kodepelajaran]</td>
                              <td>$r[namamatapelajaran]</td>
                              <td>$r[kelas]</td>
                              <td>$r[hari]</td>
                              <td>$r[jam_mulai] WIB</td>
                              <td>$r[jam_selesai] WIB</td>
                              <td style='width:70px !important'><center>
                                <a class='btn btn-success btn-xs' title='Tampil List Absensi' href='index.php?view=absensiswa&act=tampilabsen&id=$r[kodekelas]&kd=$r[kodepelajaran]'><span class='glyphicon glyphicon-th'></span> Tampilkan Absensi</a>
                              </center></td>";
    echo "</tr>";
    $no++;
  }

  echo "</table><br>
                  
                  <b class='semester'>SEMESTER 2</b>
                  <table class='table table-bordered table-striped'>
                      <tr>
                        <th style='width:40px'>No</th>
                        <th>Kode Pelajaran</th>
                        <th>Nama Pelajaran</th>
                        <th>Kelas</th>
                        <th>Hari</th>
                        <th>Jam Mulai</th>
                        <th>Jam Selesai</th>
                        <th>Action</th>
                      </tr>";
  $tampil = mysql_query("SELECT * FROM rb_jadwal_pelajaran a 
                                            JOIN rb_mata_pelajaran b ON a.kodepelajaran=b.kodepelajaran
                                              JOIN rb_guru c ON a.nip=c.nip 
                                                JOIN rb_kelas d ON a.kodekelas=d.kodekelas where a.kodekelas='$iden[kodekelas]' AND a.semester='2' ORDER BY a.hari DESC");
  $no = 1;
  while ($r = mysql_fetch_array($tampil)) {

    echo "<tr><td>$no</td>
                              <td>$r[kodepelajaran]</td>
                              <td>$r[namamatapelajaran]</td>
                              <td>$r[kelas]</td>
                              <td>$r[hari]</td>
                              <td>$r[jam_mulai] WIB</td>
                              <td>$r[jam_selesai] WIB</td>
                              <td style='width:70px !important'><center>
                                <a class='btn btn-success btn-xs' title='Tampil List Absensi' href='index.php?view=absensiswa&act=tampilabsen&id=$r[kodekelas]&kd=$r[kodepelajaran]'><span class='glyphicon glyphicon-th'></span> Tampilkan Absensi</a>
                              </center></td>";
    echo "</tr>";
    $no++;
  }

  echo "</table>
                    </div>
                  </div>";
}
?>