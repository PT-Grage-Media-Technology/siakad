<?php
if ($_GET[act] == '') {
  cek_session_admin();
  ?>
  <div class="container-fluid"> <!-- Added container for responsiveness -->
    <div class="row"> <!-- Added row for Bootstrap grid system -->
      <div class="col-xs-12">
        <div class="box">
          <div class="box-header">
            <h3 class="box-title">
              <?php
              if (isset($_GET['kelas']) and isset($_GET['tahun'])) {
                echo "Jadwal Pelajaran";
              } else {
                echo "Jadwal Pelajaran Pada Tahun " . date('Y');
              }
              ?>
            </h3>
            <form class='form-inline pull-right' action='' method='GET'>
              <!-- Changed to form-inline for responsiveness -->
              <input type="hidden" name='view' value='soal'>
              <div class="form-group"> <!-- Added form-group for consistent spacing -->
                <select name='tahun' class="form-control" style='padding:4px'>
                  <?php
                  echo "<option value=''>- Pilih Tahun Akademik -</option>";
                  $tahun = mysql_query("SELECT * FROM rb_tahun_akademik");
                  while ($k = mysql_fetch_array($tahun)) {
                    if ($_GET['tahun'] == $k['id_tahun_akademik']) {
                      echo "<option value='$k[id_tahun_akademik]' selected>$k[nama_tahun]</option>";
                    } else {
                      echo "<option value='$k[id_tahun_akademik]'>$k[nama_tahun]</option>";
                    }
                  }
                  ?>
                </select>
              </div>
              <div class="form-group"> <!-- Added form-group for consistent spacing -->
                <select name='kelas' class="form-control" style='padding:4px'>
                  <?php
                  echo "<option value=''>- Pilih Kelas -</option>";
                  $kelas = mysql_query("SELECT * FROM rb_kelas");
                  while ($k = mysql_fetch_array($kelas)) {
                    if ($_GET['kelas'] == $k['kode_kelas']) {
                      echo "<option value='$k[kode_kelas]' selected>$k[kode_kelas] - $k[nama_kelas]</option>";
                    } else {
                      echo "<option value='$k[kode_kelas]'>$k[kode_kelas] - $k[nama_kelas]</option>";
                    }
                  }
                  ?>
                </select>
              </div>
              <button type="submit" class='btn btn-success btn-sm' style='margin-top:-4px'>Lihat</button>
              <!-- Used button element for better semantics -->
            </form>
          </div><!-- /.box-header -->

          <div class="box-body">
            <table id="example" class="table table-bordered table-striped table-responsive">
              <!-- Added table-responsive class -->
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
                  <th>Total</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php
                if (isset($_GET['kelas']) and isset($_GET['tahun'])) {
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
                  $total = mysql_num_rows(mysql_query("SELECT * FROM rb_quiz_ujian WHERE kodejdwl='$r[kodejdwl]'"));
                  echo "<tr>
                                            <td>$no</td>
                                            <td>$r[namamatapelajaran]</td>
                                            <td>$r[nama_kelas]</td>
                                            <td>$r[nama_guru]</td>
                                            <td>$r[hari]</td>
                                            <td>$r[jam_mulai]</td>
                                            <td>$r[jam_selesai]</td>
                                            <td>$r[nama_ruangan]</td>
                                            <td style='color:red'>$total Record</td>
                                            <td style='width:70px !important'>
                                                <center>
                                                    <a class='btn btn-success btn-xs' title='List Soal Quiz' href='index.php?view=soal&act=listsoal&jdwl=$r[kodejdwl]&kd=$r[kode_pelajaran]&id=$r[kode_kelas]'>
                                                        <span class='glyphicon glyphicon-th'></span> List Soal dan Jawaban
                                                    </a>
                                                </center>
                                            </td>
                                          </tr>";
                  $no++;
                }
                ?>
              </tbody>
            </table>
          </div><!-- /.box-body -->

          <?php
          if ($_GET['kelas'] == '' and $_GET['tahun'] == '') {
            echo "<center style='padding:60px; color:red'>Silahkan Memilih Tahun akademik dan Kelas Terlebih dahulu...</center>";
          }
          ?>
        </div>
      </div>
    </div>
  </div>

  <?php
} elseif ($_GET[act] == 'listsoal') {
  cek_session_guru();
  $d = mysql_fetch_array(mysql_query("SELECT * FROM rb_kelas where kode_kelas='$_GET[id]'"));
  $m = mysql_fetch_array(mysql_query("SELECT * FROM rb_mata_pelajaran where kode_pelajaran='$_GET[kd]'"));
  echo "<div class='container'>
            <div class='box box-info'>
              <div class='box-header with-border'>
                <h3 class='box-title'>Daftar Ujian dan Quiz Online</h3>";

  if ($_SESSION['level'] != 'kepala') {
    echo "<a class='pull-right btn btn-primary btn-sm' href='index.php?view=soal&act=tambah&jdwl=$_GET[jdwl]&id=$_GET[id]&kd=$_GET[kd]'>Tambahkan Data</a>";
  }
  echo "</div>
            <div class='box-body'>

              <div class='table-responsive'>
                <table class='table table-condensed table-hover'>
                    <tbody>
                      <input type='hidden' name='id' value='$s[kode_kelas]'>
                      <tr><th width='120px' scope='row'>Kode Kelas</th> <td>$d[kode_kelas]</td></tr>
                      <tr><th scope='row'>Nama Kelas</th> <td>$d[nama_kelas]</td></tr>
                      <tr><th scope='row'>Mata Pelajaran</th> <td>$m[namamatapelajaran]</td></tr>
                    </tbody>
                </table>
              </div>

                <div class='table-responsive'>
                  <table id='example1' class='table table-condensed table-bordered table-striped'>
                      <thead>
                      <tr>
                        <th style='width:40px'>No</th>
                        <th>Kategori</th>
                        <th>Keterangan</th>
                        <th>Batas Waktu</th>";

  if ($_SESSION['level'] != 'kepala') {
    echo "<th style='width:210px'>Action</th>";
  }

  echo "</tr>
                    </thead>
                    <tbody>";

  $no = 1;
  $tampil = mysql_query("SELECT * FROM rb_quiz_ujian a JOIN rb_kategori_quiz_ujian b ON a.id_kategori_quiz_ujian=b.id_kategori_quiz_ujian where a.kodejdwl='$_GET[jdwl]' ORDER BY a.id_quiz_ujian");
  while ($r = mysql_fetch_array($tampil)) {
    echo "<tr>
                            <td>$no</td>
                            <td style='color:red'>$r[kategori_quiz_ujian]</td>
                            <td>$r[keterangan]</td>
                            <td>$r[batas_waktu] WIB</td>";
    if ($_SESSION['level'] != 'kepala') {
      echo "<td><a class='btn btn-primary btn-xs' title='Lihat Soal' href='index.php?view=soal&act=semuasoal&jdwl=$_GET[jdwl]&idsoal=$r[id_quiz_ujian]&id=$_GET[id]&kd=$_GET[kd]'><span class='glyphicon glyphicon-search'></span> Lihat Soal</a>
                                          <a class='btn btn-success btn-xs' title='Lihat Jawaban' href='index.php?view=soal&act=semuajawaban&jdwl=$_GET[jdwl]&idsoal=$r[id_quiz_ujian]&id=$_GET[id]&kd=$_GET[kd]'><span class='glyphicon glyphicon-th-list'></span> Jawaban Siswa</a>
                                          <a class='btn btn-danger btn-xs' title='Delete Bahan dan Tugas' href='index.php?view=soal&act=listsoal&jdwl=$_GET[jdwl]&id=$_GET[id]&kd=$_GET[kd]&hapus=$r[id_quiz_ujian]'><span class='glyphicon glyphicon-remove'></span></a></td>";
    }
    echo "</tr>";
    $no++;
  }

  if (isset($_GET['hapus'])) {
    mysql_query("DELETE FROM rb_quiz_ujian where id_quiz_ujian='$_GET[hapus]'");
    echo "<script>document.location='index.php?view=soal&act=listsoal&jdwl=" . $_GET['jdwl'] . "&id=" . $_GET['id'] . "&kd=" . $_GET['kd'] . "';</script>";
  }

  echo "</tbody>
                  </table>
                </div>
              </div>
              </form>
            </div>
          </div>";
} elseif ($_GET[act] == 'tambah') {
  cek_session_guru();
  if (isset($_POST['tambah'])) {
    if (function_exists('date_default_timezone_set')) {
      date_default_timezone_set('Asia/Jakarta');
    }
    $waktu = date("Y-m-d H:i:s");
    $date = date_create($waktu);
    $tjam = date_add($date, date_interval_create_from_date_string("$_POST[c] minutes"));
    $bataswaktu = date_format($tjam, 'Y-m-d H:i:s');
    mysql_query("INSERT INTO rb_quiz_ujian VALUES ('','$_POST[a]','$_GET[jdwl]','$_POST[b]','$bataswaktu')");
    echo "<script>document.location='index.php?view=soal&act=listsoal&jdwl=" . $_GET['jdwl'] . "&id=" . $_GET['id'] . "&kd=" . $_GET['kd'] . "';</script>";
  }

  echo "<div class='container'>
                  <div class='box box-info'>
                      <div class='box-header with-border'>
                          <h3 class='box-title'>Tambah List Ujian dan Quiz Baru</h3>
                      </div>
                      <div class='box-body'>
                          <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
                              <div class='form-group'>
                                  <label class='col-md-3 control-label'>Kategori</label>
                                  <div class='col-md-9'>
                                      <select class='form-control' name='a'> 
                                          <option value='0' selected>- Pilih Kategori -</option>";
  $kategori = mysql_query("SELECT * FROM rb_kategori_quiz_ujian");
  while ($a = mysql_fetch_array($kategori)) {
    echo "<option value='$a[id_kategori_quiz_ujian]'>$a[kategori_quiz_ujian]</option>";
  }
  echo "</select>
                                  </div>
                              </div>
                              <div class='form-group'>
                                  <label class='col-md-3 control-label'>Keterangan</label>
                                  <div class='col-md-9'>
                                      <input type='text' class='form-control' name='b'>
                                  </div>
                              </div>
                              <div class='form-group'>
                                  <label class='col-md-3 control-label'>Batas Waktu</label>
                                  <div class='col-md-9'>
                                      <input style='width:20%' type='text' class='form-control' name='c'> Menit
                                  </div>
                              </div>
                          </div>
                          <div class='box-footer'>
                              <button type='submit' name='tambah' class='btn btn-info'>Tambahkan</button>
                              <a href='index.php?view=bahantugas' class='btn btn-default pull-right'>Cancel</a>
                          </div>
                      </form>
                  </div>
              </div>";
} elseif ($_GET[act] == 'semuasoal') {
  cek_session_guru();
  $so = mysql_fetch_array(mysql_query("SELECT * FROM rb_quiz_ujian a 
                                    JOIN rb_kategori_quiz_ujian b ON a.id_kategori_quiz_ujian=b.id_kategori_quiz_ujian 
                                      JOIN rb_jadwal_pelajaran c ON a.kodejdwl=c.kodejdwl 
                                        JOIN rb_kelas d ON c.kode_kelas=d.kode_kelas where a.id_quiz_ujian='$_GET[idsoal]'"));

  if (isset($_POST[essai])) {
    mysql_query("INSERT INTO rb_pertanyaan_essai VALUES('','$_GET[idsoal]','$_POST[a]')");
    echo "<script>document.location='index.php?view=soal&act=semuasoal&jdwl=$_GET[jdwl]&idsoal=$_GET[idsoal]&id=$_GET[id]&kd=$_GET[kd]';</script>";
  }

  if (isset($_POST[objektif])) {
    mysql_query("INSERT INTO rb_pertanyaan_objektif VALUES('','$_GET[idsoal]','$_POST[a]','$_POST[b]','$_POST[c]','$_POST[d]','$_POST[e]','$_POST[f]','$_POST[g]')");
    echo "<script>document.location='index.php?view=soal&act=semuasoal&jdwl=$_GET[jdwl]&idsoal=$_GET[idsoal]&id=$_GET[id]&kd=$_GET[kd]';</script>";
  }

  if (isset($_GET[deleteessai])) {
    mysql_query("DELETE FROM rb_pertanyaan_essai where id_pertanyaan_essai='$_GET[deleteessai]'");
    echo "<script>document.location='index.php?view=soal&act=semuasoal&jdwl=$_GET[jdwl]&idsoal=$_GET[idsoal]&id=$_GET[id]&kd=$_GET[kd]';</script>";
  }

  if (isset($_GET[deleteobjektif])) {
    mysql_query("DELETE FROM rb_pertanyaan_objektif where id_pertanyaan_objektif='$_GET[deleteobjektif]'");
    echo "<script>document.location='index.php?view=soal&act=semuasoal&jdwl=$_GET[jdwl]&idsoal=$_GET[idsoal]&id=$_GET[id]&kd=$_GET[kd]';</script>";
  }

  echo "<div class='container-fluid'>  
                          <div class='box'>
                            <div class='box-header'>
                              <h3 class='box-title'>Soal Essai '<span class='text-info'>$so[kategori_quiz_ujian]</span>' 
                                <br><small>$so[nama_kelas] - $so[keterangan]</small></h3>
                              <a href='#' class='btn btn-primary btn-sm pull-right' data-toggle='modal' data-target='#essai'>Tambah Soal Essai</a>
                            </div>
                            <div class='box-body'>
                              <table class='table table-condensed table-bordered table-striped'>
                                    <thead>
                                      <tr bgcolor=#cecece>
                                        <th style='width:40px'>No</th>
                                        <th>Pertanyaan Essai</th>
                                        <th colspan=2></th>
                                      </tr>
                                    </thead>
                                    <tbody>";
  $essai = mysql_query("SELECT * FROM `rb_pertanyaan_essai` where id_quiz_ujian='$_GET[idsoal]' ORDER BY id_pertanyaan_essai DESC");
  $no = 1;
  while ($k = mysql_fetch_array($essai)) {
    echo "<tr>
                                        <td>$no</td>
                                        <td>$k[pertanyaan_essai]</td>
                                        <td style='width:60px'><a  class='btn btn-danger btn-xs' href='index.php?view=soal&act=semuasoal&jdwl=$_GET[jdwl]&idsoal=$_GET[idsoal]&id=$_GET[id]&kd=$_GET[kd]&deleteessai=$k[id_pertanyaan_essai]'><span class='glyphicon glyphicon-remove'></span></a></td>
                                        </tr>";
    $no++;
  }
  echo "</tbody>
                              </table>
                            </div>
                          </div>
            
                          <div class='box'>
                            <div class='box-header'>
                              <h3 class='box-title'>Soal Objektif '<span class='text-info'>$so[kategori_quiz_ujian]</span>' 
                              <br><small>$so[nama_kelas] - $so[keterangan]</small></h3>
                              <a href='' class='btn btn-primary btn-sm pull-right' data-toggle='modal' data-target='#objektif'>Tambah Soal Objektif </a>
                            </div>
                            <div class='box-body'>
                              <table class='table table-condensed table-bordered'>
                                <thead>
                                  <tr>
                                    <th style='width:40px'>No</th>
                                    <th>Pertanyaan Objektif</th>
                                    <th colspan=2></th>
                                  </tr>
                                </thead>
                                <tbody>";
  $objektif = mysql_query("SELECT * FROM `rb_pertanyaan_objektif` where id_quiz_ujian='$_GET[idsoal]' ORDER BY id_pertanyaan_objektif DESC");
  $noo = 1;
  while ($ko = mysql_fetch_array($objektif)) {
    echo "<tr>
                                        <td>$noo</td>
                                        <td>$ko[pertanyaan_objektif] <br>";
    if (trim($ko[jawab_a]) != '') {
      echo "<input type='radio' name='$noo'> a. $ko[jawab_a] <br>";
    }
    if (trim($ko[jawab_b]) != '') {
      echo "<input type='radio' name='$noo'> b. $ko[jawab_b] <br>";
    }
    if (trim($ko[jawab_c]) != '') {
      echo "<input type='radio' name='$noo'> c. $ko[jawab_c] <br>";
    }
    if (trim($ko[jawab_d]) != '') {
      echo "<input type='radio' name='$noo'> d. $ko[jawab_d] <br>";
    }
    if (trim($ko[jawab_e]) != '') {
      echo "<input type='radio' name='$noo'> e. $ko[jawab_e]";
    }
    echo "<div class='btn btn-default btn-xs btn-block'>Kunci Jawaban : $ko[kunci_jawaban]</div>
                                        </td>
                                        <td style='width:60px'><a class='btn btn-danger btn-xs' href='index.php?view=soal&act=semuasoal&jdwl=$_GET[jdwl]&idsoal=$_GET[idsoal]&id=$_GET[id]&kd=$_GET[kd]&deleteobjektif=$ko[id_pertanyaan_objektif]'><span class='glyphicon glyphicon-remove'></span></a></td>
                                        </tr>";
    $noo++;
  }
  echo "</tbody>
                              </table>
                            </div>
                          </div>
                        </div>";
} elseif ($_GET[act] == 'semuajawaban') {
  cek_session_guru();
  $d = mysql_fetch_array(mysql_query("SELECT a.*, b.*, c.*, d.*, e.*, f.nama_guru as nama_guru, f.nip FROM rb_quiz_ujian a 
                                    JOIN rb_jadwal_pelajaran b ON a.kodejdwl=b.kodejdwl 
                                      JOIN rb_kelas c ON b.kode_kelas=c.kode_kelas
                                        JOIN rb_mata_pelajaran d ON b.kode_pelajaran=d.kode_pelajaran 
                                          JOIN rb_kategori_quiz_ujian e ON a.id_kategori_quiz_ujian=e.id_kategori_quiz_ujian 
                                            JOIN rb_guru f ON b.nip=f.nip where a.kodejdwl='$_GET[jdwl]'"));

  echo "<div class='container'>
                                    <div class='box box-info'>
                                      <div class='box-header with-border'>
                                        <h3 class='box-title'>Data Jawaban Quiz / Ujian Online</h3>
                                      </div>
                                    <div class='box-body'>
                      
                                    <div class='table-responsive'>
                                    <table class='table table-condensed table-hover'>
                                        <tbody>
                                          <input type='hidden' name='id' value='$s[kodekelas]'>
                                          <tr><th width='120px' scope='row'>Kode Kelas</th>  <td>$d[kode_kelas]</td></tr>
                                          <tr><th scope='row'>Nama Kelas</th>                <td>$d[nama_kelas]</td></tr>
                                          <tr><th scope='row'>Mata Pelajaran</th>            <td>$d[namamatapelajaran] - (Pengajar : $d[nama_guru])</td></tr>
                                          <tr><th scope='row'>$d[kategori_quiz_ujian]</th><td>$d[keterangan]</td></tr>
                                        </tbody>
                                    </table>
                                    </div>
                      
                                      <div class='table-responsive'>
                                        <table class='table table-condensed table-bordered table-striped'>
                                            <thead>
                                            <tr>
                                              <th>No</th>
                                              <th>NISN</th>
                                              <th>Nama Siswa</th>
                                              <th>Jenis Kelamin</th>
                                              <th>Status Jawaban</th>
                                              <th>Action</th>
                                            </tr>
                                          </thead>
                                          <tbody>";

  $no = 1;
  $tampil = mysql_query("SELECT * FROM rb_siswa a JOIN rb_jenis_kelamin b ON a.id_jenis_kelamin=b.id_jenis_kelamin 
                                                                    where a.kode_kelas='$_GET[id]' ORDER BY a.nisn ASC");
  while ($r = mysql_fetch_array($tampil)) {
    $to = mysql_fetch_array(mysql_query("SELECT count(*) as total FROM `rb_jawaban_objektif` a 
                                                            JOIN rb_pertanyaan_objektif b ON a.id_pertanyaan_objektif=b.id_pertanyaan_objektif 
                                                              where a.nisn='$r[nisn]' AND b.id_quiz_ujian='$_GET[idsoal]'"));
    $es = mysql_fetch_array(mysql_query("SELECT count(*) as total FROM rb_jawaban_essai a JOIN rb_pertanyaan_essai b 
                                                                    ON a.id_pertanyaan_essai=b.id_pertanyaan_essai where a.nisn='$r[nisn]' 
                                                                      AND b.id_quiz_ujian='$_GET[idsoal]'"));

    if ($to[total] <= 0 or $es[total] <= 0) {
      $statusnilai = "<i style='color:red'>Belum Dijawab</i>";
    } else {
      $statusnilai = "<i style='color:green'>Sudah Dijawab</i>";
    }
    echo "<tr bgcolor=$warna>
                                                  <td>$no</td>
                                                  <td style='color:red'>$r[nisn]</td>
                                                  <td>$r[nama]</td>
                                                  <td>$r[jenis_kelamin]</td>
                                                  <td>$statusnilai</td>
                                                  <td style='width:130px'><a class='btn btn-primary btn-xs' href='index.php?view=soal&act=semuajawabansiswa&jdwl=$_GET[jdwl]&idsoal=$_GET[idsoal]&id=$_GET[id]&kd=$_GET[kd]&noinduk=$r[nisn]'><span class='glyphicon glyphicon-search'></span> Lihat Jawaban</a></td>
                                                </tr>";
    $no++;
  }

  echo "</tbody>
                                        </table>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>";
} elseif ($_GET[act] == 'semuajawabansiswa') {
  cek_session_guru();
  $so = mysql_fetch_array(mysql_query("SELECT * FROM rb_quiz_ujian a 
                                                    JOIN rb_kategori_quiz_ujian b ON a.id_kategori_quiz_ujian=b.id_kategori_quiz_ujian 
                                                      JOIN rb_jadwal_pelajaran c ON a.kodejdwl=c.kodejdwl 
                                                        JOIN rb_kelas d ON c.kode_kelas=d.kode_kelas WHERE a.id_quiz_ujian='$_GET[idsoal]'"));
  $si = mysql_fetch_array(mysql_query("SELECT * FROM rb_siswa WHERE nisn='$_GET[noinduk]'"));

  if (isset($_POST['nilaiessai'])) {
    $ce = mysql_fetch_array(mysql_query("SELECT count(*) as cek FROM rb_nilai_pertanyaan_essai WHERE id_quiz_ujian='$_GET[idsoal]' AND nisn='$_GET[noinduk]'"));
    if ($ce['cek'] >= 1) {
      mysql_query("UPDATE rb_nilai_pertanyaan_essai SET nilai_essai='$_POST[a]' WHERE id_quiz_ujian='$_GET[idsoal]' AND nisn='$_GET[noinduk]'");
      echo "<script>document.location='index.php?view=soal&act=semuajawabansiswa&jdwl=$_GET[jdwl]&idsoal=$_GET[idsoal]&id=$_GET[id]&kd=$_GET[kd]&noinduk=$_GET[noinduk]';</script>";
    } else {
      mysql_query("INSERT INTO rb_nilai_pertanyaan_essai VALUES('', '$_GET[idsoal]', '$_GET[noinduk]', '$_POST[a]')");
      echo "<script>document.location='index.php?view=soal&act=semuajawabansiswa&jdwl=$_GET[jdwl]&idsoal=$_GET[idsoal]&id=$_GET[id]&kd=$_GET[kd]&noinduk=$_GET[noinduk]';</script>";
    }
  }

  // Calculate Total Score
  $objek = mysql_query("SELECT * FROM `rb_pertanyaan_objektif` WHERE id_quiz_ujian='$_GET[idsoal]' ORDER BY id_pertanyaan_objektif DESC");
  $total = mysql_num_rows($objek);
  $nilai = 100 / $total;
  $to = mysql_fetch_array(mysql_query("SELECT count(*) as total FROM `rb_jawaban_objektif` a 
                                                    JOIN rb_pertanyaan_objektif b ON a.id_pertanyaan_objektif=b.id_pertanyaan_objektif 
                                                      WHERE a.jawaban=b.kunci_jawaban AND a.nisn='$_GET[noinduk]' 
                                                        AND b.id_quiz_ujian='$_GET[idsoal]'"));
  $hasil = $nilai * $to['total'];

  $nli = mysql_fetch_array(mysql_query("SELECT * FROM rb_nilai_pertanyaan_essai WHERE nisn='$_GET[noinduk]' AND id_quiz_ujian='$_GET[idsoal]'"));
  $nilaiessai = trim($nli['nilai_essai']) == '' ? '0' : $nli['nilai_essai'];
  $akhir = ($nilaiessai + $hasil) / 2;

  echo "<div class='container-fluid'>  
                                      <div class='row'>
                                          <div class='col-12'>
                                              <div class='box'>
                                                  <div class='box-header'>
                                                    <table class='table table-condensed'>
                                                        <tbody>
                                                          <tr><th scope='row'>No Induk</th>  <td> : {$si['nisn']}</td></tr>
                                                          <tr><th scope='row'>Nama Siswa</th>              <td> : {$si['nama']}</td></tr>
                                                          <tr><th scope='row'>Nilai Akhir</th>              <td> : (Nilai Essai + Nilai Objektif) : 2 = $akhir</td></tr>
                                                        </tbody>
                                                    </table>
                                                  </div>
                            
                                                <div class='box-header'>
                                                  <h3 class='box-title'>Soal Essai '<span class='text-info'>{$so['kategori_quiz_ujian']}</span>' 
                                                    <br><small>{$so['nama_kelas']} - {$so['keterangan']}</small>
                                                  </h3>";
  echo $nilaiessai == '0' ?
    "<a class='btn btn-danger float-right' href='' data-toggle='modal' data-target='#nilaiessai'>Input Nilai Essai</a>" :
    "<a class='btn btn-success float-right' href='' data-toggle='modal' data-target='#nilaiessai'>Nilai Essai : $nilaiessai</a>";
  echo "</div>
                                                <div class='box-body'>
                            
                                                  <table class='table table-condensed table-bordered'>
                                                        <tr>
                                                          <th style='width:40px'>No</th>
                                                          <th>Pertanyaan Essai</th>
                                                        </tr>";
  $essai = mysql_query("SELECT * FROM `rb_pertanyaan_essai` WHERE id_quiz_ujian='$_GET[idsoal]' ORDER BY id_pertanyaan_essai DESC");
  $no = 1;
  while ($k = mysql_fetch_array($essai)) {
    $je = mysql_fetch_array(mysql_query("SELECT * FROM rb_jawaban_essai WHERE id_pertanyaan_essai='{$k['id_pertanyaan_essai']}' AND nisn='$_GET[noinduk]'"));
    echo "<tr>
                                                        <td>$no</td>
                                                        <td>{$k['pertanyaan_essai']} <br>
                                                            <div style='height:100px; overflow:hidden'>
                                                              <pre style='height:100px'><b>Jawaban</b> : <br>{$je['jawaban_essai']}</pre>
                                                            </div>
                                                        </td>
                                                        </tr>";
    $no++;
  }
  echo "</table>
                                                </div>
                                              </div>";

  echo "<div class='box'>
                                                <div class='box-header'>
                                                  <h3 class='box-title'>Soal Objektif '<span class='text-info'>{$so['kategori_quiz_ujian']}</span>' 
                                                  <br><small>{$so['nama_kelas']} - {$so['keterangan']}</small>
                                                  </h3>
                                                  <a class='btn btn-success float-right' href=''>Nilai Objektif : $hasil</a>
                                                </div>
                                                <div class='box-body'>
                            
                                                  <table class='table table-condensed table-bordered'>
                                                    <tr>
                                                      <th style='width:40px'>No</th>
                                                      <th>Pertanyaan Objektif</th>
                                                    </tr>";
  $objektif = mysql_query("SELECT * FROM `rb_pertanyaan_objektif` WHERE id_quiz_ujian='$_GET[idsoal]' ORDER BY id_pertanyaan_objektif DESC");
  $noo = 1;
  while ($ko = mysql_fetch_array($objektif)) {
    $jo = mysql_fetch_array(mysql_query("SELECT * FROM rb_jawaban_objektif WHERE id_pertanyaan_objektif='{$ko['id_pertanyaan_objektif']}' AND nisn='$_GET[noinduk]'"));
    $jawab = ($ko['kunci_jawaban'] == $jo['jawaban']) ?
      "<span class='glyphicon glyphicon-ok pull-right'></span>" :
      "<span class='glyphicon glyphicon-remove pull-right'></span>";
    $color = ($ko['kunci_jawaban'] == $jo['jawaban']) ? 'success' : 'danger';
    $status = ($ko['kunci_jawaban'] == $jo['jawaban']) ? 'Benar' : 'Salah';
    echo "<tr>
                                                        <td>$noo</td>
                                                        <td>{$ko['pertanyaan_objektif']} <br>";
    foreach (['a', 'b', 'c', 'd', 'e'] as $option) {
      if (trim($ko["jawab_$option"]) != '') {
        echo "<input type='radio' name='$noo'> {$ko["jawab_$option"]} <br>";
      }
    }
    if ($jo['jawaban'] != '') {
      echo "<div class='btn btn-$color btn-xs btn-block'>Jawaban Anda '{$jo['jawaban']}' $status $jawab</div>";
    }
    echo "</td>
                                                      </tr>";
    $noo++;
  }

  echo "</table>
                                                </div>
                                              </div>
                                            </div>
                                          </div>";
} elseif ($_GET[act] == 'detailguru') {
  cek_session_guru();
  ?>
  <div class="w-full p-4">
    <div class="bg-white shadow rounded-lg">
      <div class="flex justify-between items-center p-4 border-b">
        <h3 class="text-xl font-semibold">
          <?php if (isset($_GET['tahun'])) {
            echo "Quiz dan Ujian Online";
          } else {
            echo "Quiz dan Ujian Online Pada " . date('Y');
          } ?>
        </h3>
        <form class='flex items-center' action='' method='GET'>
          <input type="hidden" name='view' value='soal'>
          <input type="hidden" name='act' value='detailguru'>
          <select name='tahun' class='p-2 border rounded'>
            <option value=''>- Pilih Tahun Akademik -</option>
            <?php
            $tahun = mysql_query("SELECT * FROM rb_tahun_akademik");
            while ($k = mysql_fetch_array($tahun)) {
              $selected = ($_GET['tahun'] == $k['id_tahun_akademik']) ? 'selected' : '';
              echo "<option value='$k[id_tahun_akademik]' $selected>$k[nama_tahun]</option>";
            }
            ?>
          </select>
          <input type="submit" class='ml-2 bg-green-500 text-white p-2 rounded' value='Lihat'>
        </form>
      </div><!-- /.box-header -->
      <div class="overflow-x-auto p-4">
        <table id="example1" class="min-w-full table-auto border-collapse border border-gray-300">
          <thead>
            <tr class="bg-gray-200">
              <th class='border border-gray-300 p-2'>No</th>
              <th class='border border-gray-300 p-2'>Jadwal Pelajaran</th>
              <th class='border border-gray-300 p-2'>Kelas</th>
              <th class='border border-gray-300 p-2'>Guru</th>
              <th class='border border-gray-300 p-2'>Hari</th>
              <th class='border border-gray-300 p-2'>Mulai</th>
              <th class='border border-gray-300 p-2'>Selesai</th>
              <th class='border border-gray-300 p-2'>Ruangan</th>
              <th class='border border-gray-300 p-2'>Semester</th>
              <th class='border border-gray-300 p-2'>Total</th>
              <th class='border border-gray-300 p-2'>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php
            if (isset($_GET['tahun'])) {
              $tampil = mysql_query("SELECT a.*, e.nama_kelas, b.namamatapelajaran, b.kode_pelajaran, b.kode_kurikulum, c.nama_guru, d.nama_ruangan FROM rb_jadwal_pelajaran a 
                                                                                    JOIN rb_mata_pelajaran b ON a.kode_pelajaran=b.kode_pelajaran
                                                                                      JOIN rb_guru c ON a.nip=c.nip 
                                                                                        JOIN rb_ruangan d ON a.kode_ruangan=d.kode_ruangan
                                                                                          JOIN rb_kelas e ON a.kode_kelas=e.kode_kelas 
                                                                                          WHERE a.nip='$_SESSION[id]' 
                                                                                            AND a.id_tahun_akademik='$_GET[tahun]' 
                                                                                              AND b.kode_kurikulum='$kurikulum[kode_kurikulum]' ORDER BY a.hari DESC");
            } else {
              $tampil = mysql_query("SELECT a.*, e.nama_kelas, b.namamatapelajaran, b.kode_pelajaran, b.kode_kurikulum, c.nama_guru, d.nama_ruangan FROM rb_jadwal_pelajaran a 
                                                                                    JOIN rb_mata_pelajaran b ON a.kode_pelajaran=b.kode_pelajaran
                                                                                      JOIN rb_guru c ON a.nip=c.nip 
                                                                                        JOIN rb_ruangan d ON a.kode_ruangan=d.kode_ruangan
                                                                                        JOIN rb_kelas e ON a.kode_kelas=e.kode_kelas 
                                                                                          WHERE a.nip='$_SESSION[id]' 
                                                                                            AND b.kode_kurikulum='$kurikulum[kode_kurikulum]'
                                                                                              AND a.id_tahun_akademik LIKE '" . date('Y') . "%' ORDER BY a.hari DESC");
            }
            $no = 1;
            while ($r = mysql_fetch_array($tampil)) {
              $total = mysql_num_rows(mysql_query("SELECT * FROM rb_quiz_ujian where kodejdwl='$r[kodejdwl]'"));
              echo "<tr>
                                                              <td class='border border-gray-300 p-2'>$no</td>
                                                              <td class='border border-gray-300 p-2'>$r[namamatapelajaran]</td>
                                                              <td class='border border-gray-300 p-2'>$r[nama_kelas]</td>
                                                              <td class='border border-gray-300 p-2'>$r[nama_guru]</td>
                                                              <td class='border border-gray-300 p-2'>$r[hari]</td>
                                                              <td class='border border-gray-300 p-2'>$r[jam_mulai]</td>
                                                              <td class='border border-gray-300 p-2'>$r[jam_selesai]</td>
                                                              <td class='border border-gray-300 p-2'>$r[nama_ruangan]</td>
                                                              <td class='border border-gray-300 p-2'>$r[id_tahun_akademik]</td>
                                                              <td class='border border-gray-300 p-2 text-red-500'>$total Record</td>
                                                              <td class='border border-gray-300 p-2'>
                                                                <a class='bg-green-500 text-white btn btn-xs' title='List Soal Quiz' href='index.php?view=soal&act=listsoal&jdwl=$r[kodejdwl]&id=$r[kode_kelas]&kd=$r[kode_pelajaran]'>
                                                                  <span class='glyphicon glyphicon-th'></span> Tampilkan
                                                                </a>
                                                              </td>
                                                            </tr>";
              $no++;
            }
            ?>
          </tbody>
        </table>
      </div><!-- /.box-body -->
    </div>
  </div>

  <?php
} elseif ($_GET[act] == 'detailsiswa') {
  cek_session_siswa();
  ?>
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="box">
          <div class="box-header">
            <h3 class="box-title">
              <?php if (isset($_GET['kelas']) && isset($_GET['tahun'])) {
                echo "Quiz dan Ujian Online";
              } else {
                echo "Quiz dan Ujian Online " . date('Y');
              } ?>
            </h3>
            <form class='d-flex justify-content-end' action='' method='GET'>
              <input type="hidden" name='view' value='soal'>
              <input type="hidden" name='act' value='detailsiswa'>
              <select name='tahun' class='form-select me-2' style='width:auto;'>
                <?php
                echo "<option value=''>- Pilih Tahun Akademik -</option>";
                $tahun = mysql_query("SELECT * FROM rb_tahun_akademik");
                while ($k = mysql_fetch_array($tahun)) {
                  if ($_GET['tahun'] == $k['id_tahun_akademik']) {
                    echo "<option value='$k[id_tahun_akademik]' selected>$k[nama_tahun]</option>";
                  } else {
                    echo "<option value='$k[id_tahun_akademik]'>$k[nama_tahun]</option>";
                  }
                }
                ?>
              </select>
              <input type="submit" class='btn btn-success btn-sm' value='Lihat'>
            </form>
          </div><!-- /.box-header -->
          <div class="box-body">
            <div class="table-responsive">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th style='width:20px'>No</th>
                    <th>Kode</th>
                    <th>Jadwal Pelajaran</th>
                    <th>Kelas</th>
                    <th>Guru</th>
                    <th>Hari</th>
                    <th>Mulai</th>
                    <th>Selesai</th>
                    <th>Ruangan</th>
                    <th>Semester</th>
                    <th>Total</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  if (isset($_GET['tahun'])) {
                    $tampil = mysql_query("SELECT a.*, e.nama_kelas, b.namamatapelajaran, b.kode_pelajaran, b.kode_kurikulum, c.nama_guru, d.nama_ruangan FROM rb_jadwal_pelajaran a 
                                              JOIN rb_mata_pelajaran b ON a.kode_pelajaran=b.kode_pelajaran
                                              JOIN rb_guru c ON a.nip=c.nip 
                                              JOIN rb_ruangan d ON a.kode_ruangan=d.kode_ruangan
                                              JOIN rb_kelas e ON a.kode_kelas=e.kode_kelas 
                                              WHERE a.kode_kelas='$_SESSION[kode_kelas]' 
                                              AND a.id_tahun_akademik='$_GET[tahun]'
                                              AND b.kode_kurikulum='$kurikulum[kode_kurikulum]' 
                                              ORDER BY a.hari DESC");
                  } else {
                    $tampil = mysql_query("SELECT a.*, e.nama_kelas, b.namamatapelajaran, b.kode_pelajaran, b.kode_kurikulum, c.nama_guru, d.nama_ruangan FROM rb_jadwal_pelajaran a 
                                              JOIN rb_mata_pelajaran b ON a.kode_pelajaran=b.kode_pelajaran
                                              JOIN rb_guru c ON a.nip=c.nip 
                                              JOIN rb_ruangan d ON a.kode_ruangan=d.kode_ruangan
                                              JOIN rb_kelas e ON a.kode_kelas=e.kode_kelas 
                                              WHERE a.kode_kelas='$_SESSION[kode_kelas]' 
                                              AND b.kode_kurikulum='$kurikulum[kode_kurikulum]'
                                              AND a.id_tahun_akademik LIKE '" . date('Y') . "%' 
                                              ORDER BY a.hari DESC");
                  }
                  $no = 1;
                  while ($r = mysql_fetch_array($tampil)) {
                    $total = mysql_num_rows(mysql_query("SELECT * FROM rb_quiz_ujian WHERE kodejdwl='$r[kodejdwl]'"));
                    echo "<tr><td>$no</td>
                                  <td>$r[kode_pelajaran]</td>
                                  <td>$r[namamatapelajaran]</td>
                                  <td>$r[nama_kelas]</td>
                                  <td>$r[nama_guru]</td>
                                  <td>$r[hari]</td>
                                  <td>$r[jam_mulai]</td>
                                  <td>$r[jam_selesai]</td>
                                  <td>$r[nama_ruangan]</td>
                                  <td>$r[id_tahun_akademik]</td>
                                  <td style='color:red'>$total Record</td>
                                  <td><a class='btn btn-success btn-xs' title='List Quiz dan Ujian' href='index.php?view=soal&act=listsoalsiswa&jdwl=$r[kodejdwl]&id=$r[kode_kelas]&kd=$r[kode_pelajaran]'><span class='glyphicon glyphicon-th'></span> Tampilkan</a></td>
                              </tr>";
                    $no++;
                  }
                  ?>
                </tbody>
              </table>
            </div><!-- /.table-responsive -->
          </div><!-- /.box-body -->
        </div><!-- /.box -->
      </div><!-- /.col-12 -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
  <?php
} elseif ($_GET[act] == 'listsoalsiswa') {
  cek_session_siswa();
  $d = mysql_fetch_array(mysql_query("SELECT * FROM rb_kelas where kode_kelas='$_GET[id]'"));
  $m = mysql_fetch_array(mysql_query("SELECT * FROM rb_mata_pelajaran where kode_pelajaran='$_GET[kd]'"));
  echo "<div class='container-fluid'>
            <div class='row'>
              <div class='col-12'>
                <div class='box box-info'>
                  <div class='box-header with-border'>
                    <h3 class='box-title'>Daftar Ujian dan Quiz Online</h3>
                  </div>
                  <div class='box-body'>

                    <div class='table-responsive'>
                      <table class='table table-condensed table-hover'>
                        <tbody>
                          <input type='hidden' name='id' value='$s[kodekelas]'>
                          <tr><th width='120px' scope='row'>Kode Kelas</th> <td>$d[kode_kelas]</td></tr>
                          <tr><th scope='row'>Nama Kelas</th> <td>$d[nama_kelas]</td></tr>
                          <tr><th scope='row'>Mata Pelajaran</th> <td>$m[namamatapelajaran]</td></tr>
                        </tbody>
                      </table>
                    </div>

                    <div class='table-responsive'>
                      <table id='example1' class='table table-condensed table-bordered table-striped'>
                        <thead>
                          <tr>
                            <th style='width:40px'>No</th>
                            <th>Kategori</th>
                            <th>Keterangan</th>
                            <th>Batas Waktu</th>
                            <th style='width:80px'>Action</th>
                          </tr>
                        </thead>
                        <tbody>";

  $no = 1;
  $tampil = mysql_query("SELECT * FROM rb_quiz_ujian a JOIN rb_kategori_quiz_ujian b ON a.id_kategori_quiz_ujian=b.id_kategori_quiz_ujian where a.kodejdwl='$_GET[jdwl]' ORDER BY a.id_quiz_ujian");
  while ($r = mysql_fetch_array($tampil)) {
    echo "<tr>
                          <td>$no</td>
                          <td style='color:red'>$r[kategori_quiz_ujian]</td>
                          <td>$r[keterangan]</td>
                          <td>$r[batas_waktu] WIB</td>";
    $sekarangwaktu = date("YmdHis");
    $bataswaktu1 = str_replace('-', '', $r['batas_waktu']);
    $bataswaktu2 = str_replace(':', '', $bataswaktu1);
    $bataswaktu3 = str_replace(' ', '', $bataswaktu2);
    if ($sekarangwaktu > $bataswaktu3) {
      echo "<td><a style='width:100px' class='btn btn-danger btn-xs' title='Lihat Soal $sekarangwaktu - $bataswaktu3' href=''><span class='glyphicon glyphicon-search'></span> Waktu Habis</a></td>";
    } else {
      echo "<td><a style='width:100px' class='btn btn-primary btn-xs' title='Lihat Soal' href='index.php?view=soal&act=jawabsemuasoal&jdwl=$_GET[jdwl]&idsoal=$r[id_quiz_ujian]&id=$_GET[id]&kd=$_GET[kd]'><span class='glyphicon glyphicon-search'></span> Jawab Soal</a></td>";
    }
    echo "</tr>";
    $no++;
  }

  echo "</tbody>
                </table>
              </div>
            </div>
          </div>
        </div>";
} elseif ($_GET[act] == 'jawabsemuasoal') {
  cek_session_siswa();
  $jml = mysql_fetch_array(mysql_query("SELECT count(*) as jmlp FROM `rb_pertanyaan_objektif` where id_quiz_ujian='$_GET[idsoal]'"));

  if (isset($_POST['simpanobjektif'])) {
    $n = $jml['jmlp'];
    for ($i = 0; $i <= $n; $i++) {
      if (isset($_POST['a' . $i])) {
        $jawab = $_POST['a' . $i];
        $pertanyaan = $_POST['b' . $i];
        $cek = mysql_fetch_array(mysql_query("SELECT count(*) as tot FROM rb_jawaban_objektif where nisn='$iden[nisn]' AND id_pertanyaan_objektif='$pertanyaan'"));
        if ($cek['tot'] >= 1) {
          mysql_query("UPDATE rb_jawaban_objektif SET jawaban='$jawab' where id_pertanyaan_objektif='$pertanyaan' AND nisn='$iden[nisn]'");
        } else {
          $waktuobjektif = date("Y-m-d H:i:s");
          mysql_query("INSERT INTO rb_jawaban_objektif (nisn, id_pertanyaan_objektif, jawaban, waktu_objektif) VALUES('$iden[nisn]','$pertanyaan','$jawab','$waktuobjektif')");
        }
      }
    }
    echo "<script>document.location='index.php?view=soal&act=jawabsemuasoal&jdwl=$_GET[jdwl]&idsoal=$_GET[idsoal]&id=$_GET[id]&kd=$_GET[kd]';</script>";
  }

  $so = mysql_fetch_array(mysql_query("SELECT * FROM rb_quiz_ujian a 
                            JOIN rb_kategori_quiz_ujian b ON a.id_kategori_quiz_ujian=b.id_kategori_quiz_ujian 
                              JOIN rb_jadwal_pelajaran c ON a.kodejdwl=c.kodejdwl 
                                JOIN rb_kelas d ON c.kode_kelas=d.kode_kelas where a.id_quiz_ujian='$_GET[idsoal]'"));

  echo "<div class='container'>
                  <div class='col-xs-12'>
                      <div class='box'>
                          <div class='box-header'>
                              <h3 class='box-title'>Jawab Soal Essai '<span class='text-info'>{$so['kategori_quiz_ujian']}</span>' 
                              <br><small>{$so['nama_kelas']} - {$so['keterangan']}</small></h3>
                          </div>
                          <div class='box-body'>
                              <table class='table table-condensed table-bordered table-striped'>
                                  <tr bgcolor='#cecece'>
                                      <th style='width:40px'>No</th>
                                      <th>Pertanyaan Essai</th>
                                      <th>Point</th>
                                  </tr>";
  $essai = mysql_query("SELECT * FROM `rb_pertanyaan_essai` where id_quiz_ujian='$_GET[idsoal]' ORDER BY id_pertanyaan_essai DESC");
  $no = 1;
  while ($k = mysql_fetch_array($essai)) {
    $je = mysql_fetch_array(mysql_query("SELECT * FROM rb_jawaban_essai where id_pertanyaan_essai='{$k['id_pertanyaan_essai']}' AND nisn='$iden[nisn]'"));
    echo "<tr>
                                <td>$no</td>
                                <td>{$k['pertanyaan_essai']} <br>
                                    <b>Jawaban</b> : <pre>{$je['jawaban_essai']}</pre></td>
                                <td style='width:70px'><a class='btn btn-success btn-xs' href='index.php?view=soal&act=jawabsoalessai&jdwl=$_GET[jdwl]&idsoal=$_GET[idsoal]&id=$_GET[id]&kd=$_GET[kd]&idp={$k['id_pertanyaan_essai']}'><span class='glyphicon glyphicon-pencil'></span> Jawab</a></td>
                                </tr>";
    $no++;
  }
  echo "</table>
                    </div>
                </div>
    
                <div class='box'>
                    <div class='box-header'>
                        <h3 class='box-title'>Soal Objektif '<span class='text-info'>{$so['kategori_quiz_ujian']}</span>' 
                        <br><small>{$so['nama_kelas']} - {$so['keterangan']}</small></h3>
                    </div>
                    <div class='box-body'>
                    <form action='' method='POST'>
                        <table class='table table-condensed table-bordered'>
                            <tr>
                                <th style='width:40px'>No</th>
                                <th>Pertanyaan Objektif</th>
                            </tr>";
  $objektif = mysql_query("SELECT * FROM `rb_pertanyaan_objektif` where id_quiz_ujian='$_GET[idsoal]' ORDER BY id_pertanyaan_objektif DESC");
  $noo = 1;
  while ($ko = mysql_fetch_array($objektif)) {
    $ce = mysql_fetch_array(mysql_query("SELECT * FROM rb_jawaban_objektif where id_pertanyaan_objektif='{$ko['id_pertanyaan_objektif']}' AND nisn='$iden[nisn]'"));
    echo "<tr>
                                <td>$noo</td>
                                <td>{$ko['pertanyaan_objektif']} <br>
                                    <input type='hidden' value='{$ko['id_pertanyaan_objektif']}' name='b" . $noo . "'>";

    // Options for radio buttons
    foreach (['a', 'b', 'c', 'd', 'e'] as $option) {
      if (trim($ko["jawab_$option"]) != '') {
        $checked = ($ce['jawaban'] == $option) ? 'checked' : '';
        echo "<input type='radio' name='a" . $noo . "' value='$option' $checked> $option. {$ko["jawab_$option"]} <br>";
      }
    }

    echo "</td>
                            </tr>";
    $noo++;
  }
  echo "</table>
                    <div class='box-footer'>
                        <button type='submit' name='simpanobjektif' class='btn btn-info btn-sm pull-right'>Simpan Jawaban</button>
                    </div>
                    </form>
                    </div>
                </div>
              </div>
            </div>";
} elseif ($_GET[act] == 'jawabsoalessai') {
  cek_session_siswa();

  if (isset($_POST['simpan'])) {
    $cek = mysql_fetch_array(mysql_query("SELECT count(*) as total FROM rb_jawaban_essai where nisn='$iden[nisn]' AND id_pertanyaan_essai='$_GET[idp]'"));

    if ($cek['total'] >= 1) {
      mysql_query("UPDATE rb_jawaban_essai SET jawaban_essai = '$_POST[a]' where nisn='$iden[nisn]' AND id_pertanyaan_essai='$_GET[idp]'");
    } else {
      $waktujawab = date("Y-m-d H:i:s");
      mysql_query("INSERT INTO rb_jawaban_essai VALUES('', '$iden[nisn]', '$_GET[idp]', '$_POST[a]', '$waktujawab')");
    }

    echo "<script>document.location='index.php?view=soal&act=jawabsemuasoal&jdwl=$_GET[jdwl]&idsoal=$_GET[idsoal]&id=$_GET[id]&kd=$_GET[kd]';</script>";
  }

  $n = mysql_fetch_array(mysql_query("SELECT * FROM rb_jawaban_essai where nisn='$iden[nisn]' AND id_pertanyaan_essai='$_GET[idp]'"));

  echo "<form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
                    <div class='container'>
                      <div class='row'>
                        <div class='col-md-12'>
                          <div class='box box-info'>
                            <div class='box-header with-border'>
                              <h3 class='box-title'>Jawab Soal Essai</h3>
                            </div>
                            <div class='box-body'>
                              <table class='table table-condensed table-bordered'>
                                <tbody>
                                  <tr>
                                    <th width='120px' scope='row'>Jawaban</th>
                                    <td>
                                      <textarea rows='4' class='form-control' name='a'>" . htmlspecialchars($n['jawaban_essai']) . "</textarea>
                                    </td>
                                  </tr>
                                </tbody>
                              </table>
                            </div>
          
                            <div class='box-footer'>
                              <button type='submit' name='simpan' class='btn btn-info'>Submit</button>
                              <a href='index.php?view=guru' class='btn btn-default pull-right'>Cancel</a>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </form>";
}
?>