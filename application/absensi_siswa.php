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
  $idtopic = mysql_fetch_array(mysql_query("SELECT * FROM rb_forum_topic WHERE judul_topic='$j[materi]'"));
  $jawaban_refleksi = mysql_fetch_array(mysql_query("SELECT * FROM rb_pertanyaan_penilaian_jawab WHERE status='refleksi' AND kodejdwl='$_GET[idjr]'"));
  // echo"SELECT * FROM rb_pertanyaan_penilaian_jawab WHERE status=refleksi AND kodejdwl='$_GET[idjr]'";
  // var_dump($jawaban_refleksi);
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
  echo "
<div class='col-md-12'>
    <div class='box box-info'>
        <div class='box-header with-border'>
            <h3 class='box-title'>Data Absensi Siswa Pada : <b style='color:red'>" . tgl_indo("$_GET[tgl]") . "</b></h3>
        </div>
        <div class='box-body mb-3'>
            <div class='col-md-12'>
                <div class='table-responsive'>
                    <table class='table table-condensed table-hover'>
                        <tbody>
                            <input type='hidden' name='id' value='$s[kode_kelas]'>
                            <tr>
                                <th width='120px' scope='row'>Kode Kelas</th>
                                <td>$d[kode_kelas]</td>
                            </tr>
                            <tr>
                                <th scope='row'>Nama Kelas</th>
                                <td>$d[nama_kelas]</td>
                            </tr>
                            <tr>
                                <th scope='row'>Mata Pelajaran</th>
                                <td>$m[namamatapelajaran]</td>
                            </tr>
                            <tr>
                                <th scope='row'>Tujuan Pembelajaran</th>
                                <td>$j[tujuan_pembelajaran]</td>
                            </tr>
                            <tr>
                                <th scope='row'>Materi</th>
                                <td>$j[materi]</td>
                            </tr>
                            <tr>
                                <th scope='row'>test</th>
                                <td>$j[id_parent_journal]</td>
                            </tr>
                        </tbody>
                    </table>
                     <a class='btn btn-success btn-sm mb-2' title='Bahan dan Tugas' href='index.php?view=forum&act=detailtopic&jdwl=$j[kodejdwl]&idtopic=$idtopic[id_forum_topic]&id_jawaban=$jawaban_refleksi[id_pertanyaan_penilaian]&id_journal=$_GET[id_journal]'>
                    <div class='d-flex flex-column align-items-center'>
                      <div class='glyphicon glyphicon-tasks' style='font-size:28px; margin-right:5px;'></div>
                      <div class='' style='font-size:14px;'>Refleksi</div>
                    </div>
                 </a>
                      <a class='btn btn-success btn-sm mb-2' title='Rekap SRL' href='index.php?view=rekapsrl&id=$_GET[id]&idjr=$_GET[idjr]'>
                    <div class='d-flex flex-column align-items-center'>
                      <div class='glyphicon glyphicon-tasks' style='font-size:28px; margin-right:5px;'></div>
                      <div class='' style='font-size:14px;'>Rekap SRL</div>
                    </div>
                 </a>
                 </a>
                      <a class='btn btn-success btn-sm mb-2' title='Remadial' href='#'>
                    <div class='d-flex flex-column align-items-center'>
                      <div class='glyphicon glyphicon-tasks' style='font-size:28px; margin-right:5px;'></div>
                      <div class='' style='font-size:14px;'>Remadial</div>
                    </div>
                 </a>
                </div>
            </div>
            <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
                <input type='hidden' name='tgla' value='$tglc'>
                <input type='hidden' name='blna' value='$blnc'>
                <input type='hidden' name='thna' value='$thn'>
                <input type='hidden' name='kelas' value='$_GET[id]'>
                <input type='hidden' name='pelajaran' value='$_GET[kd]'>
                <input type='hidden' name='jdwl' value='$_GET[idjr]'>
                <input type='hidden' name='kodejdwl' value='$_GET[idjr]'>
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
                                    <th>Nilai Pengetahuan</th>
                                    <th>Nilai Keterampilan</th>
                                    <th>Nilai Sikap</th>
                                    <th width='120px'>Kehadiran</th>
                                </tr>
                            </thead>
                            <tbody>";

  $no = 1;
  $tugas = mysql_query("SELECT * FROM rb_elearning WHERE 
                        kodejdwl='$_GET[idjr]' AND 
                        DATE(tanggal_tugas)='$_GET[tgl]' AND
                        id_kategori_elearning = 2");

  $data_tugas = mysql_fetch_array($tugas);
  $jumlah_data = mysql_num_rows($tugas);


  $tampil = mysql_query("SELECT * FROM rb_siswa a JOIN rb_jenis_kelamin b ON 
                          a.id_jenis_kelamin=b.id_jenis_kelamin 
                          where a.kode_kelas='$_GET[id]' ORDER BY a.id_siswa");

  // while ($r = mysql_fetch_array($tampil)) {

  //   $nilai = mysql_fetch_array(mysql_query("SELECT nilai FROM rb_elearning_jawab WHERE id_elearning='$data_tugas[id_elearning]' AND nisn='$r[nisn]'"));

  //   $a = mysql_fetch_array(mysql_query("SELECT * FROM rb_absensi_siswa 
  //                                       where kodejdwl='$_GET[idjr]' AND 
  //                                       tanggal='$_GET[tgl]' AND nisn='$r[nisn]'"));

  // echo "<tr bgcolor=$warna>
  //                             <td>$no</td>
  //                             <td>$r[nipd]</td>
  //                             <td>$r[nisn]</td>
  //                             <td>$r[nama]</td>
  //                             <td>$r[jenis_kelamin]</td>
  //                             <td>";



  // else {
  //   if (strtotime(date('Y-m-d')) > strtotime($_GET['tgl'])) {
  //     echo "nilai sikap<input type='number' value='{$a['nilai']}' name='nilai[$no]' style='width:50px;' disabled>";
  //   } else {
  //     echo "nilai sikap<input type='number' value='{$a['nilai']}' name='nilai[$no]' style='width:50px;'>";
  //   }
  // }

  // // Query untuk mendapatkan semua data predikat
  // $predikatQuery = mysql_query("SELECT * FROM rb_kriteria_nilai");

  // // Ambil nilai sesuai nomor siswa
  // $nilaiSiswa = isset($a['nilai']) ? $a['nilai'] : 0; // Pastikan nilai ada
  // // var_dump($nilaiSiswa); // Memeriksa nilai siswa

  // // Variabel untuk menyimpan kode nilai yang cocok
  // $kode_nilai = '';

  // // Loop melalui semua hasil data predikat
  // while ($predikatData = mysql_fetch_array($predikatQuery)) {
  //   // var_dump($predikatData); // Memeriksa data predikat

  //   // Cek apakah nilai siswa berada dalam rentang predikat
  //   if ($nilaiSiswa >= $predikatData['nilai_bawah'] && $nilaiSiswa <= $predikatData['nilai_atas']) {
  //     $kode_nilai = $predikatData['kode_nilai'];
  //     break; // Hentikan loop setelah menemukan predikat yang sesuai
  //   }
  // }

  // Output kode predikat yang cocok, jika ada
  // if ($kode_nilai && $nilaiSiswa) {
  //   echo "<td>$kode_nilai</td>";
  // } else {
  //   echo "<td>Tidak ada predikat yang sesuai</td>";
  // }

  while ($r = mysql_fetch_array($tampil)) {
    

    // Ambil nilai dari tabel terkait
    $nilai_pengetahuan = mysql_fetch_array(mysql_query("SELECT nilai_pengetahuan FROM rb_elearning_jawab WHERE id_elearning='$data_tugas[id_elearning]' AND nisn='$r[nisn]' AND jenis_nilai='pengetahuan'"));
    $nilai_keterampilan = mysql_fetch_array(mysql_query("SELECT nilai_keterampilan FROM rb_elearning_jawab WHERE id_elearning='$data_tugas[id_elearning]' AND nisn='$r[nisn]' AND jenis_nilai='keterampilan'"));
    $nilai_sikap = mysql_fetch_array(mysql_query("SELECT nilai_sikap FROM rb_elearning_jawab WHERE id_elearning='$data_tugas[id_elearning]' AND nisn='$r[nisn]' AND jenis_nilai='sikap'"));

    // $a = mysql_fetch_array(mysql_query("SELECT * FROM rb_absensi_siswa 
    //                                       WHERE kodejdwl='$_GET[idjr]' 
    //                                       AND waktu_input='$_GET[tgl]' 
    //                                       AND nisn='$r[nisn]'"));
    

    $query = "SELECT 
                  rb_journal_list.*, 
                  rb_absensi_siswa.* 
              FROM 
                  rb_journal_list 
              JOIN 
                  rb_absensi_siswa 
              ON 
                  rb_journal_list.kodejdwl = rb_absensi_siswa.kodejdwl 
                  AND rb_journal_list.tanggal = rb_absensi_siswa.tanggal
              WHERE 
                  rb_journal_list.tujuan_pembelajaran = '$tujuan_pembelajaran'";

              $jadwal = mysql_query($query);

              if (!$jadwal) {
              die("Query gagal: " . mysql_error());
              }

              var_dump($jadwal);

              // Ambil data
              while ($row = mysql_fetch_assoc($jadwal)) {
              echo "<pre>";
              print_r($row);
              echo "</pre>";
              }
    
    $a = mysql_fetch_array(mysql_query("SELECT * FROM rb_absensi_siswa 
                                    WHERE kodejdwl='" . mysql_real_escape_string($_GET['idjr']) . "' 
                                    AND DATE(waktu_input)='" . mysql_real_escape_string($_GET['tgl']) . "' 
                                    AND nisn='" . mysql_real_escape_string($r['nisn']) . "'"));

    echo "<tr>
              <td>$no</td>
              <td>$r[nipd]</td>
              <td>
              $r[nisn]
              <input type='number' value='$r[nisn]' name='nisn[$no]' style='width:50px;' hidden>
              </td>
              <td>$r[nama]</td>
              <td>$r[jenis_kelamin]</td>";

    // Nilai Pengetahuan
    if (strtotime(date('Y-m-d')) > strtotime($_GET['tgl'])) {
      echo "<td>1<input type='number' value='$a[nilai_pengetahuan]' name='nilai_pengetahuan[$no]' style='width:50px;' disabled></td>";
    } else {
      echo "<td>2<input type='number' value='$a[nilai_pengetahuan]' name='nilai_pengetahuan[$no]' style='width:50px;'></td>";
    }

    // Nilai Keterampilan
    if (strtotime(date('Y-m-d')) > strtotime($_GET['tgl'])) {
      echo "<td>3<input type='number' value='$a[nilai_keterampilan]' name='nilai_keterampilan[$no]' style='width:50px;' disabled></td>";
    } else {
      echo "<td>4<input type='number' value='$a[nilai_keterampilan]' name='nilai_keterampilan[$no]' style='width:50px;'></td>";
    }

    // Nilai Sikap
    if (strtotime(date('Y-m-d')) > strtotime($_GET['tgl'])) {
      echo "<td>5<input type='number' value='$a[nilai_sikap]' name='nilai_sikap[$no]' style='width:50px;' disabled></td>";
    } else {
      echo "<td>6<input type='number' value='$a[nilai_sikap]' name='nilai_sikap[$no]' style='width:50px;'></td>";
    }

    // Kehadiran
    echo "<td><select style='width:100px;' name='kehadiran[$no]' class='form-control' " . (strtotime(date('Y-m-d')) > strtotime($_GET['tgl']) ? "disabled" : "") . ">";

    $kehadiran = mysql_query("SELECT * FROM rb_kehadiran");
    while ($k = mysql_fetch_array($kehadiran)) {
      echo "<option value='$k[kode_kehadiran]' " . ($a['kode_kehadiran'] == $k['kode_kehadiran'] ? "selected" : "") . ">$k[nama_kehadiran]</option>";
    }

    echo "</select></td>";
    echo "</tr>";
    $no++;
  }


  if ($_SESSION['level'] != 'kepala') {
    $tglAbsen = $_GET['tgl'];
    $isDisabled = (strtotime(date('Y-m-d')) > strtotime($tglAbsen)) ? 'hidden' : '';
    if (strtotime(date('Y-m-d')) > strtotime($tglAbsen)) {
    } else {
      echo "
       </tbody>
    </table>
    </div>
    </div>
      <div class='box-footer'>
              <button type='submit' name='simpann' class='btn btn-info pull-right' >Simpan Absensi</button>
            </div>
        ";
    }
  }

  echo "
    </form>
  </div>";

  if (isset($_POST['simpann'])) {
    $jml_data = count($_POST['nisn']);
    // $jml_data = count($_POST['nilai_sikap']);
    $nisn = $_POST['nisn'];
    $a = $_POST['kehadiran'];
    $nilai_sikap = $_POST['nilai_sikap'];
    $nilai_keterampilan = $_POST['nilai_keterampilan'];
    $nilai_pengetahuan = $_POST['nilai_pengetahuan'];
    // $tgl = $_POST['tgla'] . '-' . $_POST['blna'] . '-' . $_POST['thna'];
    $tgl = $_POST['thna'] . '-' . $_POST['blna'] . '-' . $_POST['tgla'];
    $nip = $_SESSION['id'];
    $kodejdwl = $_POST['jdwl'];
    $kdhadir = 'Hadir';
    $jam_ke = $_GET['jam'];
    $guruInserted = false;


    for ($i = 1; $i <= $jml_data; $i++) {
      $cek = mysql_query("SELECT * FROM rb_absensi_siswa WHERE kodejdwl='$kodejdwl' AND nisn='" . $nisn[$i] . "' AND tanggal='$tgl'");
      $total = mysql_num_rows($cek);
      
      // ini adalah rata rata
      $total_nilai[$i] = ($nilai_keterampilan[$i] + $nilai_pengetahuan[$i] + $nilai_sikap[$i]) / 3;


      if ($total >= 1) {
        // Update data jika sudah ada di tabel
        $updateAbsensiSiswa = mysql_query(
          "UPDATE rb_absensi_siswa 
                  SET kode_kehadiran='" . $a[$i] . "', 
                      nilai_sikap='" . $nilai_sikap[$i] . "',
                      nilai_pengetahuan='" . $nilai_pengetahuan[$i] . "',
                      nilai_keterampilan='" . $nilai_keterampilan[$i] . "', 
                      total='" . number_format($total_nilai[$i], 1) . "' 
                  WHERE nisn='" . $nisn[$i] . "' 
                    AND kodejdwl='$kodejdwl'
                    AND tanggal='$tgl'"
        );

        if ($updateAbsensiSiswa && !$guruInserted) {
          $insertAbsensiGuru = mysql_query("INSERT INTO rb_absensi_guru VALUES('', '$kodejdwl', '$nip', '$kdhadir','$jam_ke', '$tgl', NOW())");
          $guruInserted = true;
        }
      } else {
        // Insert data jika belum ada di tabel


        $insertAbsensiSiswa = mysql_query("
        INSERT INTO rb_absensi_siswa 
              VALUES (
                '', 
                '$kodejdwl', 
                '" . $nisn[$i] . "', 
                '" . $a[$i] . "', 
                '" . $nilai_sikap[$i] . "', 
                '" . $nilai_pengetahuan[$i] . "', 
                '" . $nilai_keterampilan[$i] . "', 
                '" . number_format($total_nilai[$i], 1) . "', 
                '$tgl', 
                NOW()
            )
        ");

        if ($insertAbsensiSiswa && !$guruInserted) {
          $insertAbsensiGuru = mysql_query("INSERT INTO rb_absensi_guru VALUES('', '$kodejdwl', '$nip', '$kdhadir','$jam_ke', '$tgl', NOW())");
          $guruInserted = true;
        }
      }

      // Proses pengiriman SMS jika ada ketidakhadiran
      if ($a[$i] != 'H') {
        $cs = mysql_fetch_array(mysql_query("SELECT * FROM rb_siswa a JOIN rb_kelas b ON a.kode_kelas=b.kode_kelas WHERE a.nisn='" . $nisn[$i] . "'"));
        $statush = ($a[$i] == 'A') ? 'Alpa' : (($a[$i] == 'S') ? 'Sakit' : 'Izin');
        $isi_pesan = "Diberitahukan kepada Yth Bpk/Ibk, Bahwa anak anda $cs[nama], $cs[nama_kelas] absensi Hari ini Tanggal $tgl : $statush";

        if ($cs['no_telpon_ayah'] != '') {
          mysql_query("INSERT INTO rb_sms VALUES('', '$cs[no_telpon_ayah]', '$isi_pesan')");
        } elseif ($cs['no_telpon_ibu'] != '') {
          mysql_query("INSERT INTO rb_sms VALUES('', '$cs[no_telpon_ibu]', '$isi_pesan')");
        }
      }
    }

    // Redirect setelah semua proses selesai
    echo "<script>document.location='index.php?view=absensiswa&act=tampilabsen&id=" . $_POST['kelas'] . "&kd=" . $_POST['pelajaran'] . "&idjr=" . $_POST['jdwl'] . "&tgl=" . $_GET['tgl'] . "&jam=" . $_GET['jam'] . "';</script>";
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
                  JOIN rb_kelas e ON a.kode_kelas=e.kode_kelas $a
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
<?php
if (isset($_GET['id_pemberitahuan'])) {
  $id_pemberitahuan = $_GET['id_pemberitahuan'];
  // Menjalankan query update
  $update = mysql_query("UPDATE rb_pemberitahuan_guru SET is_read = 1 WHERE id_pemberitahuan_guru = '$id_pemberitahuan'");
} else {
  echo "";
}
?>

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