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
} elseif ($_GET[act] == 'tampilabsen') {
  if ($_GET[gettgl]) {
    $filtertgl = $_GET[gettgl];
    $exp = explode('-', $_GET[gettgl]);
    $tglc = $exp[2];
    $blnc = $exp[1];
    $thn = $exp[0];
  } else {
    if (isset($_POST[tgl])) {
      $tgl = $_POST[tgl];
    } else {
      $tgl = date("d");
    }
    if (isset($_POST[bln])) {
      $bln = $_POST[bln];
    } else {
      $bln = date("m");
    }
    if (isset($_POST[thn])) {
      $thn = $_POST[thn];
    } else {
      $thn = date("Y");
    }
    $lebartgl = strlen($tgl);
    $lebarbln = strlen($bln);

    switch ($lebartgl) {
      case 1: {
        $tglc = "0" . $tgl;
        break;
      }
      case 2: {
        $tglc = $tgl;
        break;
      }
    }

    switch ($lebarbln) {
      case 1: {
        $blnc = "0" . $bln;
        break;
      }
      case 2: {
        $blnc = $bln;
        break;
      }
    }

    $filtertgl = $thn . "-" . $blnc . "-" . $tglc;
  }
  $d = mysql_fetch_array(mysql_query("SELECT * FROM rb_kelas where kode_kelas='$_GET[id]'"));
  $m = mysql_fetch_array(mysql_query("SELECT * FROM rb_mata_pelajaran where kode_pelajaran='$_GET[kd]'"));
  // $j = mysql_fetch_array(mysql_query("SELECT * FROM rb_journal_list where kodejdwl='$_GET[kd]'"));
  $j = mysql_fetch_array(mysql_query("SELECT * FROM rb_journal_list where kodejdwl='$_GET[idjr]' AND tanggal='$_GET[tgl]' AND jam_ke='$_GET[jam]'"));

  $ex = explode('-', $filtertgl);
  $tahun = $ex[0];
  $bulane = $ex[1];
  $tanggal = $ex[2];
  if (substr($tanggal, 0, 1) == '0') {
    $tgle = substr($tanggal, 1, 1);
  } else {
    $tgle = substr($tanggal, 0, 2);
  }
  if (substr($bulane, 0, 1) == '0') {
    $blnee = substr($bulane, 1, 1);
  } else {
    $blnee = substr($bulane, 0, 2);
  }
  echo "<div class='col-md-12'>
              <div class='box box-info'>
                <div class='box-header with-border'>
                  <h3 class='box-title'>Data Absensi Siswa Pada : <b style='color:red'>" . tgl_indo("$_GET[tgl]") . "</b></h3>
                </div>
              <div class='box-body'>

              <div class='col-md-12'>
              <table class='table table-condensed table-hover'>
                  <tbody>
                    <input type='hidden' name='id' value='$s[kode_kelas]'>
                    <tr><th width='120px' scope='row'>Kode Kelas</th> <td>$d[kode_kelas]</td></tr>
                    <tr><th scope='row'>Nama Kelas</th>               <td>$d[nama_kelas]</td></tr>
                    <tr><th scope='row'>Mata Pelajaran</th>           <td>$m[namamatapelajaran]</td></tr>
                    <tr><th scope='row'>Tujuan Pembelajaran</th>           <td>$j[materi]</td></tr>
                    <tr><th scope='row'><a class='btn btn-success btn-sm' title='Bahan dan Tugas' href='https://siakad.demogmt.online/index.php?view=bahantugas&act=listbahantugas&jdwl=$_GET[idjr]&id=$_GET[id]&kd=$_GET[kd]'><span class='glyphicon glyphicon-tasks'>Tugas</span></a></th></tr>
                  </tbody>
              </table>
              </div>

             
              <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
              <input type='hidden' name='tgla' value='$tglc'>
              <input type='hidden' name='blna' value='$blnc'>
              <input type='hidden' name='thna' value='$thn'>
              <input type='hidden' name='kelas' value='$_GET[id]'>
              <input type='hidden' name='pelajaran' value='$_GET[kd]'>
              <input type='hidden' name='jdwl' value='$_GET[jdwl]'>
                <div class='col-md-12'>
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
  $tampil = mysql_query("SELECT * FROM rb_siswa a JOIN rb_jenis_kelamin b ON a.id_jenis_kelamin=b.id_jenis_kelamin where a.kode_kelas='$_GET[id]' ORDER BY a.id_siswa");
  while ($r = mysql_fetch_array($tampil)) {
    if ($_GET[gettgl]) {
      $sekarangabsen = $_GET[gettgl];
    } else {
      if (isset($_POST[lihat])) {
        $sekarangabsen = $thn . "-" . $blnc . "-" . $tglc;
      } else {
        $sekarangabsen = date("Y-m-d");
      }
    }

    $a = mysql_fetch_array(mysql_query("SELECT * FROM rb_absensi_siswa where kodejdwl='$_GET[jdwl]' AND tanggal='$sekarangabsen' AND nisn='$r[nisn]'"));
    echo "<tr bgcolor=$warna>
                            <td>$no</td>
                            <td>$r[nipd]</td>
                            <td>$r[nisn]</td>
                            <td>$r[nama]</td>
                            <td>$r[jenis_kelamin]</td>
                              <input type='hidden' value='$r[nisn]' name='nisn[$no]'>
                            <td><select style='width:100px;' name='a[$no]' class='form-control'>";
    $kehadiran = mysql_query("SELECT * FROM rb_kehadiran");
    while ($k = mysql_fetch_array($kehadiran)) {
      if ($a[kode_kehadiran] == $k[kode_kehadiran]) {
        echo "<option value='$k[kode_kehadiran]' selected>* $k[nama_kehadiran]</option>";
      } else {
        echo "<option value='$k[kode_kehadiran]'>$k[nama_kehadiran]</option>";
      }
    }
    echo "</select></td>";
    echo "</tr>";
    $no++;
  }

  echo "</tbody>
                  </table>
                </div>
              </div>";
  if ($_SESSION[level] != 'kepala') {
    echo "<div class='box-footer'>
                      <button type='submit' name='simpann' class='btn btn-info pull-right'>Simpan Absensi</button>
                </div>";
  }
  echo "</form>
            </div>";

  if (isset($_POST[simpann])) {
    $jml_data = count($_POST[nisn]);
    $nisn = $_POST[nisn];
    $a = $_POST[a];

    $e = $_POST[thna];
    $f = $_POST[blna];
    $g = $_POST[tgla];

    for ($i = 1; $i <= $jml_data; $i++) {
      $cek = mysql_query("SELECT * FROM rb_absensi_siswa where kodejdwl='$_POST[jdwl]' AND nisn='" . $nisn[$i] . "' AND tanggal='" . $e . "-" . $f . "-" . $g . "'");
      $total = mysql_num_rows($cek);
      if ($total >= 1) {
        mysql_query("UPDATE rb_absensi_siswa SET kode_kehadiran = '" . $a[$i] . "' where nisn='" . $nisn[$i] . "' AND kodejdwl='$_POST[jdwl]'");
        $cs = mysql_fetch_array(mysql_query("SELECT * FROM rb_siswa a JOIN rb_kelas b ON a.kode_kelas=b.kode_kelas where a.nisn='" . $nisn[$i] . "'"));
        if ($a[$i] != 'H') {
          if ($a[$i] == 'A') {
            $statush = 'Alpa';
          } elseif ($a[$i] == 'S') {
            $statush = 'Sakit';
          } elseif ($a[$i] == 'I') {
            $statush = 'Izin';
          }
          $isi_pesan = "Diberitahukan kepada Yth Bpk/Ibk, Bahwa anak anda $cs[nama], $cs[nama_kelas] absensi Hari ini Tanggal $g-$f-$e : $statush";
          if ($cs[no_telpon_ayah] != '') {
            mysql_query("INSERT INTO rb_sms VALUES('','$cs[no_telpon_ayah]','$isi_pesan')");
          } elseif ($cs[no_telpon_ibu] != '') {
            mysql_query("INSERT INTO rb_sms VALUES('','$cs[no_telpon_ibu]','$isi_pesan')");
          }
        }
      } else {
        mysql_query("INSERT INTO rb_absensi_siswa VALUES('','$_POST[jdwl]','" . $nisn[$i] . "','" . $a[$i] . "','" . $e . "-" . $f . "-" . $g . "','" . date('Y-m-d H:i:s') . "')");
        $cs = mysql_fetch_array(mysql_query("SELECT * FROM rb_siswa a JOIN rb_kelas b ON a.kode_kelas=b.kode_kelas where a.nisn='" . $nisn[$i] . "'"));
        if ($a[$i] != 'H') {
          if ($a[$i] == 'A') {
            $statush = 'Alpa';
          } elseif ($a[$i] == 'S') {
            $statush = 'Sakit';
          } elseif ($a[$i] == 'I') {
            $statush = 'Izin';
          }
          $isi_pesan = "Diberitahukan kepada Yth Bpk/Ibk, Bahwa anak anda $cs[nama], $cs[nama_kelas] absensi Hari ini Tanggal $g-$f-$e : $statush";
          if ($cs[no_telpon_ayah] != '') {
            mysql_query("INSERT INTO rb_sms VALUES('','$cs[no_telpon_ayah]','$isi_pesan')");
          } elseif ($cs[no_telpon_ibu] != '') {
            mysql_query("INSERT INTO rb_sms VALUES('','$cs[no_telpon_ibu]','$isi_pesan')");
          }
        }
      }
    }
    echo "<script>document.location='index.php?view=absensiswa&act=tampilabsen&id=" . $_POST[kelas] . "&kd=" . $_POST[pelajaran] . "&jdwl=" . $_POST[jdwl] . "&gettgl=" . $e . "-" . $f . "-" . $g . "';</script>";
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