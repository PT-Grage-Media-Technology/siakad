<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/css/bootstrap-select.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/js/bootstrap-select.min.js"></script>
<?php
// Ketika halaman Agenda Mengajar diakses, aktifkan flag di session
session_start();
$_SESSION['akses_agenda'] = true;

// $tampil = mysql_query("SELECT jl.*, g.nama_guru 
//                       FROM rb_journal_list jl 
//                       LEFT JOIN rb_guru g ON jl.users = g.nip 
//                       WHERE jl.kodejdwl='$_GET[id]' 
//                       ORDER BY jl.id_journal DESC");

// Koneksi ke database dan query pencarian jika ada
$search_term = isset($_GET['search_term']) ? $_GET['search_term'] : '';
$id_jdwl = isset($_GET['id']) ? $_GET['id'] : '';  // Mendapatkan kodejdwl dari query string

// Query untuk mencari tujuan pembelajaran berdasarkan search term
$tampil = mysql_query("SELECT jl.*, g.nama_guru 
                       FROM rb_journal_list jl 
                       LEFT JOIN rb_guru g ON jl.users = g.nip 
                       WHERE jl.kodejdwl='$id_jdwl' 
                       AND jl.tujuan_pembelajaran LIKE '%$search_term%' 
                       ORDER BY jl.id_journal DESC");



?>

<?php if ($_GET[act] == '') { ?>
  <div class="col-xs-12">
    <div class="box">
      <div class="box-header">
        <h3 class="box-title">
          <?php if (isset($_GET[tahun])) {
            echo "Tujuan Belajar Mengajar anda ";
          } else {
            echo "Tujuan Belajar Mengajar anda pada " . date('Y');
          } ?>
        </h3>
        <form style='margin-right:5px; margin-top:0px' class='pull-right' action='' method='GET'>
          <input type="hidden" name='view' value='journalguru'>
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

      <!-- Tabel dibungkus dengan table-responsive untuk scroll-x -->
      <div class="box-body">
        <div class="table-responsive"> <!-- Tambahkan div ini -->
          <table class="table table-bordered table-striped">
            <thead>
              <tr>
                <th style='width:20px'>No</th>
                <th>Kode Pelajaran</th>
                <th>Jadwal Pelajaran</th>
                <th>Kelas</th>
                <th>Guru</th>
                <th>Hari</th>
                <th>Mulai</th>
                <th>Selesai</th>
                <th>Ruangan</th>
                <th>Semester</th>
                <th></th>
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
                                <td>$r[kode_pelajaran]</td>
                                <td>$r[namamatapelajaran]</td>
                                <td>$r[nama_kelas]</td>
                                <td>$r[nama_guru]</td>
                                <td>$r[hari]</td>
                                <td>$r[jam_mulai]</td>
                                <td>$r[jam_selesai]</td>
                                <td>$r[nama_ruangan]</td>
                                <td>$r[id_tahun_akademik]</td>
                                <td style='width:80px !important'><center>
                                          <a class='btn btn-success btn-xs' title='Lihat Journal' href='index.php?view=journalguru&act=lihat&id=$r[kodejdwl]'><span class='glyphicon glyphicon-search'></span> Lihat Journal</a>
                                        </center></td>
                            </tr>";
                $no++;
              }
              ?>
            </tbody>
          </table>
        </div> <!-- Akhir div table-responsive -->
      </div><!-- /.box-body -->
    </div>
  </div>


<?php
} elseif ($_GET['act'] == 'lihat') {
  $d = mysql_fetch_array(mysql_query("SELECT a.kode_kelas, b.nama_kelas, c.namamatapelajaran, c.kode_pelajaran, d.nama_guru 
  FROM `rb_jadwal_pelajaran` a 
  JOIN rb_kelas b ON a.kode_kelas=b.kode_kelas 
  JOIN rb_mata_pelajaran c ON a.kode_pelajaran=c.kode_pelajaran 
  JOIN rb_guru d ON a.nip=d.nip 
  WHERE a.kodejdwl='$_GET[id]'"));

  echo "<div class='col-xs-12 col-md-12'>  
              <div class='box'>
                <div class='box-header'>
                  <h3 class='box-title'>Tujuan Belajar Mengajar</h3>
                      <a style='margin-left:5px;display:none;' class='pull-right btn btn-success btn-sm' href='index.php?view=kompetensidasar&act=lihat&id=$_GET[id]'>Lihat Kompetensi Dasar</a>";
  if ($_SESSION['level'] != 'kepala') {
    // echo "<a class='pull-right btn btn-primary btn-sm' href='index.php?view=journalguru&act=tambah&jdwl=$_GET[id]'>Tambahkan Tujuan Pembelajaran</a>";
  }
  echo "</div>
                <div class='box-body'>
  <div class='table-responsive'>
    <table class='table table-condensed table-hover'>
      <tbody>
        <tr><th width='120px' scope='row'>Nama Kelas</th> <td>$d[nama_kelas]</td></tr>
        <tr><th scope='row'>Nama Guru</th> <td>$d[nama_guru]</td></tr>
        <tr><th scope='row'>Mata Pelajaran</th> <td>$d[namamatapelajaran]</td></tr>
      </tbody>
    </table>";
  if (isset($_POST[tambah])) {
    // var_dump($_POST['tambah']);
    // exit;


    $d = tgl_simpan($_POST[d]);

    // Periksa dan proses file yang diunggah
    $target_dir = "files/"; // Direktori tujuan
    $file_name = basename($_FILES['file']['name']);
    $target_file = $target_dir . $file_name;

    // Pastikan direktori ada
    if (!file_exists($target_dir)) {
      mkdir($target_dir, 0777, true);
    }

    // Validasi dan pindahkan file
    if ($_FILES['file']['size'] > 0 && move_uploaded_file($_FILES['file']['tmp_name'], $target_file)) {
      // Simpan data ke database
      $query = "INSERT INTO rb_journal_list VALUES(
          '',
          '$_GET[id]',
          '$_POST[c]', 
          '$d',
          '$_POST[e]', 
          '$_POST[ee]', 
          '$_POST[f]', 
          '$_POST[g]', 
          '$target_file', 
          '" . date('Y-m-d H:i:s') . "', 
          '$_POST[nip_users]',
          NULL
      )";
      mysql_query("INSERT INTO rb_forum_topic VALUES ('','$_GET[id]','$_POST[f]','$_POST[f]','" . date('Y-m-d H:i:s') . "')");


      if (mysql_query($query)) {
        echo "Data berhasil disimpan ke database.<br>";
      } else {
        echo "Gagal menyimpan ke database: " . mysql_error() . "<br>";
      }
    }

    // mysql_query("INSERT INTO rb_journal_list VALUES('','$_GET[id]','$_POST[c]','$d','$_POST[e]','$_POST[f]','$_POST[g]','" . date('Y-m-d H:i:s') . "','$_POST[nip_users]')");
    $tahun = $_GET['tahun'];
    echo "<script>document.location='index.php?view=journalguru&act=lihat&id=$_GET[id]&tahun=$tahun';</script>";
  }

  $e = mysql_fetch_array(mysql_query("SELECT * FROM rb_jadwal_pelajaran where kodejdwl='$_GET[jdwl]'"));
  $jam = mysql_num_rows(mysql_query("SELECT * FROM rb_journal_list where kodejdwl='$_GET[jdwl]'")) + 1;
  echo "<div class='col-md-12'>
                <div class='box box-info'>
                  <div class='box-header with-border'>
                    <h3 class='box-title'>Tambah Tujuan Belajar Mengajar</h3>
                  </div>
                <div class='box-body'>
                <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
                  <div class='col-md-12'>
                    <table class='table table-condensed table-bordered'>
                    <tbody>
                    <input type='hidden' name='jdwl' value='$_GET[jdwl]'>
                      <tr hidden><th width='140px' scope='row' hidden>Kelas </th>   <td hidden><select class='form-control' name='a' hidden>";
  $kelas = mysql_query("SELECT * FROM rb_kelas");
  while ($a = mysql_fetch_array($kelas)) {
    if ($e[kode_kelas] == $a[kode_kelas]) {
      echo "<option value='$a[kode_kelas]' selected hidden>$a[nama_kelas]</option>";
    }
  }
  echo "</select>
                                          </td></tr>
                                          <tr hidden><th scope='row' hidden>Mata Pelajaran</th>  <td hidden><select class='form-control' name='b' hidden>";
  $mapel = mysql_query("SELECT * FROM rb_mata_pelajaran");
  while ($a = mysql_fetch_array($mapel)) {
    if ($e[kode_pelajaran] == $a[kode_pelajaran]) {
      echo "<option value='$a[kode_pelajaran]' selected hidden>$a[namamatapelajaran]</option>";
    }
  }
  echo "</select>
                      </td></tr>
                     
                      <tr>
                        <th scope='row'>Hari</th>
                        <td>
                            <select class='form-control' name='c'>
                                <option value='Senin'" . ($hari_ini == 'Senin' ? ' selected' : '') . ">Senin</option>
                                <option value='Selasa'" . ($hari_ini == 'Selasa' ? ' selected' : '') . ">Selasa</option>
                                <option value='Rabu'" . ($hari_ini == 'Rabu' ? ' selected' : '') . ">Rabu</option>
                                <option value='Kamis'" . ($hari_ini == 'Kamis' ? ' selected' : '') . ">Kamis</option>
                                <option value='Jumat'" . ($hari_ini == 'Jumat' ? ' selected' : '') . ">Jumat</option>
                                <option value='Sabtu'" . ($hari_ini == 'Sabtu' ? ' selected' : '') . ">Sabtu</option>
                            </select>
                        </td>
                      </tr>";

  if ($_SESSION['is_kurikulum']) {
    echo " <tr>
                          <th scope='row'>Pilih Guru</th>   
                          <td>
                          <small style='display: block; text-align: center; color: red;'>Pilih Nama Guru</small>
                              <select style='color: #ffff' class='selectpicker form-control' name='nip_users' data-live-search='true' data-show-subtext='true'>";
    $guru = mysql_query("SELECT * FROM rb_guru WHERE id_jenis_ptk NOT IN (6, 7) ORDER BY nama_guru ASC");
    while ($g = mysql_fetch_array($guru)) {
      echo "<option value='$g[nip]'>$g[nama_guru]</option>";
    }
    echo "</select>
                          </td>
                      </tr>";
  } else {
    echo "<input type='hidden' class='form-control' value='$_SESSION[id]' name='nip_users'>";
  }

  echo " <tr><th scope='row'>Tanggal</th>  <td><input type='text' style='border-radius:0px; padding-left:12px' class='datepicker form-control' value='" . date('d-m-Y') . "' name='d' data-date-format='dd-mm-yyyy'></td></tr>
                      <tr><th scope='row'>Dari Jam Ke-</th>  <td><input type='number' class='form-control' value='$jam' name='e'></td></tr>
                      <tr><th scope='row'>Sampai Jam Ke-</th>  <td><input type='number' class='form-control' value='$sampai_jam_ke' name='ee'></td></tr>
                      <tr>
                        <th scope='row'>Tujuan Pembelajaran222</th>  
                        <td>
                            <input type='hidden' name='id_parent_journal' id='id_parent_journal'>
                            <input type='text' id='search_tujuan' class='form-control' placeholder='Cari tujuan pembelajaran...'>
                            <select id='result_tujuan' class='form-control' >";
                                foreach ($options as $option) {
                                    echo "<option value='$option[id]'>$option[tujuan_pembelajaran]</option>";
                                }
                                echo"
                            </select>
                        </td>
                      </tr>
                          
                          <th scope='row'>Materi</th>
                          <td><textarea style='height:80px' class='form-control' name='f'></textarea></td></tr>
                          <tr><th width=120px scope='row'> File</th>             
                          <td><div style='position:relative;''>
                              <a class='btn btn-primary' href='javascript:;'>
                                <span class='glyphicon glyphicon-search'></span> Cari File Materi atau Tugas yang akan dikirim..."; ?>
  <input type='file' class='files' name='file' onchange='$("#upload-file-info").html($(this).val());'>
<?php
  include('library.php');

  // Mendapatkan waktu saat ini dalam format yang sesuai
  $currentDateTime = date('Y-m-d\TH:i');

  // Tampilkan form dalam satu pernyataan echo
  echo "</a> 
                                      <span style='width:155px' class='label label-info' id='upload-file-info'></span>
                                        </div>
                                      </td>
                                      </tr>
                                      </td></tr>
                                    </tbody>
                                    </table>
                                  </div>
                                </div>
                                <div class='box-footer'>
                                      <button type='submit' name='tambah' class='btn btn-info'>Tambahkan</button>
                                      
                                    </div>
                                </form>
                              </div>";

  // <!-- Container grid dengan margin dan padding yang seragam -->
  echo "<div class='container' style='max-width: 200px; padding: 10px;'>
  <style>
    @media (min-width: 1024px) { .container { margin: 10px; } }
    @media (max-width: 1024px) { .container { margin: 10px auto; } }
  </style>
</div>
  </div>
</div>
                  <div class='table-responsive'>
                  <table id='example' class='table table-bordered table-striped text-center'>
                    <thead>
                      <tr>
                        <th style='width:20px'>No</th>
                        <th>Hari</th>
                        <th style='width:90px'>Tanggal</th>
                        <th style='width:90px'>Dari Jam Ke</th>
                        <th style='width:70px'>Sampai Jam Ke</th>
                        <th style='width:200px' align=center>Guru</th>
                        <th style='width:220px'>Tujuan Pembelajaran</th>
                        <th style='width:220px'>Materi</th>";
  if ($_SESSION['level'] != 'kepala') {
    echo "<th>Action</th>";
  }
  echo "</tr>
                    </thead>
                    <tbody>";
  // $tampil = mysql_query("SELECT * FROM rb_journal_list where kodejdwl='$_GET[id]' ORDER BY id_journal DESC");
  // $no = 1;
  // $today = date('Y-m-d');

  // $tampil = mysql_query("SELECT jl.*, g.nama_guru 
  //                     FROM rb_journal_list jl 
  //                     LEFT JOIN rb_guru g ON jl.users = g.nip 
  //                     WHERE jl.kodejdwl='$_GET[id]' 
  //                     ORDER BY jl.id_journal DESC");
  $no = 1;
  $today = date('Y-m-d');

  // if (mysql_num_rows($tampil) == 0) { // Cek jika tidak ada data
  //   echo "<tr><td colspan='7' style='text-align:center;'>Tidak ada data</td></tr>"; // Tampilkan pesan jika tidak ada data
  // } else {
  //   while ($r = mysql_fetch_array($tampil)) {
  //     $buttonDisabled = ($r['tanggal'] > $today) ? 'disabled' : '';
  //     $absenLink = ($r['tanggal'] > $today) ? '#' : "index.php?view=absensiswa&act=tampilabsen&id=$d[kode_kelas]&kd=$d[kode_pelajaran]&idjr=$_GET[id]&tgl=$r[tanggal]&jam=$r[jam_ke]";

  if (mysql_num_rows($tampil) == 0) {
    // Cek jika tidak ada data
    echo "<tr><td colspan='9' style='text-align:center;'>Tidak ada data</td></tr>";
  } else {
    while ($r = mysql_fetch_array($tampil)) {
      // Logika untuk mengatur status button absen
      $buttonDisabled = ($r['tanggal'] > $today) ? 'disabled' : '';
      $absenLink = ($r['tanggal'] > $today) ? '#' : "index.php?view=absensiswa&act=tampilabsen&id=$d[kode_kelas]&kd=$d[kode_pelajaran]&idjr=$_GET[id]&tgl=$r[tanggal]&jam=$r[jam_ke]&id_journal=$r[id_journal]";

      echo "<tr>
      <td>$no</td>
      <td>$r[hari]</td>
      <td>" . tgl_indo($r['tanggal']) . "</td>
      <td align=center>$r[jam_ke]</td>
      <td align=center>$r[sampai_jam_ke]</td>
      <td align=center>" . ($r['nama_guru'] ? $r['nama_guru'] : 'Tidak ada') . "</td>
      <td>$r[tujuan_pembelajaran]</td>
      <td>$r[materi]</td>";

      if ($_SESSION['level'] != 'kepala') {
        echo "<td style='width: 200px; !important'><center>
                  <a class='btn btn-success btn-xs' title='Absen' href='$absenLink' $buttonDisabled onclick='this.onclick=null; this.classList.add(\"disabled\");'><span class='glyphicon glyphicon-edit'>Absen</span></a>
                   <a class='btn btn-success btn-xs' title='Edit Data' href='index.php?view=journalguru&act=edit&id=$r[id_journal]&jdwl=$_GET[id]'><span class='glyphicon glyphicon-edit'>Edit</span></a>
                 <a class='btn btn-danger btn-xs' title='Delete Data' href='index.php?view=journalguru&act=lihat&hapus=" . $r['id_journal'] . "&jdwl=" . $_GET['id'] . "' onclick='return confirm(\"Apakah Anda yakin ingin menghapus data ini?\");'>
              <span class='glyphicon glyphicon-remove'>Hapus</span>
          </a>
                </center></td>";
      }
      echo "</tr>";
      $no++;
    }
  }

  if (isset($_GET['hapus'])) {
    // Ambil nama file berdasarkan ID
    $query = mysql_query("SELECT file,materi FROM rb_journal_list WHERE id_journal='$_GET[hapus]'");
    $data = mysql_fetch_assoc($query);
    // var_dump($data);
    // exit;

    // Tentukan lokasi file
    $file_path = 'files/' . $data['file'];

    // Hapus file jika ada
    if (!empty($data['file']) && file_exists($file_path)) {
      unlink($file_path); // Menghapus file berdasarkan nama
    }

    // Hapus data dari database
    mysql_query("DELETE FROM rb_journal_list WHERE id_journal='$_GET[hapus]'");
    mysql_query("DELETE FROM rb_forum_topic WHERE judul_topic='$data[materi]'");
    // echo"DELETE FROM rb_forum_topic WHERE judul_topic='$data[materi]";
    // Redirect ke halaman sebelumnya
    echo "<script>document.location='index.php?view=journalguru&act=lihat&id=$_GET[jdwl]';</script>";
  }


  echo "<tbody>
                  </table>
                  </div>
                </div>
            </div>";
} elseif ($_GET[act] == 'tambah') {
  if (isset($_POST[tambah])) {
    // var_dump($_POST);
    // exit;

    $d = tgl_simpan($_POST[d]);
    mysql_query("INSERT INTO rb_journal_list VALUES('','$_POST[jdwl]','$_POST[c]','$d','$_POST[e]','$_POST[f]','$_POST[g]','" . date('Y-m-d H:i:s') . "','$_POST[nip_users]')");
    mysql_query("INSERT INTO rb_forum_topic VALUES ('','$_GET[id]','$_POST[f]','$_POST[f]','" . date('Y-m-d H:i:s') . "')");
    echo "<script>document.location='index.php?view=journalguru&act=lihat&id=$_POST[jdwl]';</script>";
  }

  $e = mysql_fetch_array(mysql_query("SELECT * FROM rb_jadwal_pelajaran where kodejdwl='$_GET[jdwl]'"));
  $jam = mysql_num_rows(mysql_query("SELECT * FROM rb_journal_list where kodejdwl='$_GET[jdwl]'")) + 1;
  echo "<div class='col-md-12'>
              <div class='box box-info'>
                <div class='box-header with-border'>
                  <h3 class='box-title'>Tambah Tujuan Belajar Mengajar</h3>
                </div>
              <div class='box-body'>
              <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
                <div class='col-md-12'>
                  <table class='table table-condensed table-bordered'>
                  <tbody>
                  <input type='hidden' name='jdwl' value='$_GET[jdwl]'>
                    <tr hidden><th width='140px' scope='row' hidden>Kelas</th>   <td hidden><select class='form-control' name='a' hidden>";
  $kelas = mysql_query("SELECT * FROM rb_kelas");
  while ($a = mysql_fetch_array($kelas)) {
    if ($e[kode_kelas] == $a[kode_kelas]) {
      echo "<option value='$a[kode_kelas]' selected hidden>$a[nama_kelas]</option>";
    }
  }
  echo "</select>
                    </td></tr>
                    <tr hidden><th scope='row' hidden>Mata Pelajaran</th>  <td hidden><select class='form-control' name='b' hidden>";
  $mapel = mysql_query("SELECT * FROM rb_mata_pelajaran");
  while ($a = mysql_fetch_array($mapel)) {
    if ($e[kode_pelajaran] == $a[kode_pelajaran]) {
      echo "<option value='$a[kode_pelajaran]' selected hidden>$a[namamatapelajaran]</option>";
    }
  }
  echo "</select>
                    </td></tr>
                   
                    <tr>
                      <th scope='row'>Hari</th>
                      <td>
                          <select class='form-control' name='c'>
                              <option value='Senin'" . ($hari_ini == 'Senin' ? ' selected' : '') . ">Senin</option>
                              <option value='Selasa'" . ($hari_ini == 'Selasa' ? ' selected' : '') . ">Selasa</option>
                              <option value='Rabu'" . ($hari_ini == 'Rabu' ? ' selected' : '') . ">Rabu</option>
                              <option value='Kamis'" . ($hari_ini == 'Kamis' ? ' selected' : '') . ">Kamis</option>
                              <option value='Jumat'" . ($hari_ini == 'Jumat' ? ' selected' : '') . ">Jumat</option>
                              <option value='Sabtu'" . ($hari_ini == 'Sabtu' ? ' selected' : '') . ">Sabtu</option>
                          </select>
                      </td>
                    </tr>";

  if ($_SESSION['is_kurikulum']) {
    echo " <tr>
                        <th scope='row'>Pilih Guru</th>   
                        <td>
                        <small style='display: block; text-align: center; color: red;'>Pilih Nama Guru</small>
                            <select style='color: #ffff' class='selectpicker form-control' name='nip_users' data-live-search='true' data-show-subtext='true'>";
    $guru = mysql_query("SELECT * FROM rb_guru WHERE id_jenis_ptk NOT IN (6, 7) ORDER BY nama_guru ASC");
    while ($g = mysql_fetch_array($guru)) {
      echo "<option value='$g[nip]'>$g[nama_guru]</option>";
    }
    echo "</select>
                                    </td>
                                </tr>";
  } else {
    echo "<input type='hidden' class='form-control' value='$_SESSION[id]' name='nip_users'>";
  }

  echo " <tr><th scope='row'>Tanggal</th>  <td><input type='text' style='border-radius:0px; padding-left:12px' class='datepicker form-control' value='" . date('d-m-Y') . "' name='d' data-date-format='dd-mm-yyyy'></td></tr>
                    <tr><th scope='row'>Dari Jam Ke-</th>  <td><input type='number' class='form-control' value='$jam' name='e'></td></tr>
                    <tr><th scope='row'>Sampai Jam Ke-</th>  <td><input type='number' class='form-control' value='$sampai_jam_ke' name='ee'></td></tr>
                    <tr><th scope='row'>Materi</th>  <td><textarea style='height:80px' class='form-control' name='f'></textarea></td></tr>
                    <tr>
                        <th scope='row'>Tujuan Pembelajaran111</th>
                        <td>
                            <input type='hidden' name='id_parent_journal' id='id_parent_journal'>
                            <input type='text' id='search_tujuan' class='form-control' placeholder='Cari tujuan pembelajaran...'>
                            <select id='result_tujuan' class='form-control' >";
                                foreach ($options as $option) {
                                    echo "<option value='$option[id]'>$option[tujuan]</option>";
                                }
                                echo"
                            </select>
                        </td>
                    </tr>
                  </tbody>
                  </table>
                </div>
              </div>
              <div class='box-footer'>
                    <button type='submit' name='tambah' class='btn btn-info'>Tambahkan</button>
                    <a href='index.php?view=journalguru&act=lihat&id=$e[kodejdwl]'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>
                  </div>
              </form>
            </div>";
            
} elseif ($_GET[act] == 'edit') {
  // if (isset($_POST[update])) {
  //   $d = tgl_simpan($_POST[d]);
  //   mysql_query("UPDATE rb_journal_list SET hari = '$_POST[c]',
  //                                               tanggal = '$d',
  //                                               jam_ke = '$_POST[e]',
  //                                               materi = '$_POST[f]',
  //                                               keterangan = '$_POST[g]',
  //                                               users = '$_POST[nip_users]' where id_journal='$_POST[id]'");
  //   echo "<script>document.location='index.php?view=journalguru&act=lihat&id=$_POST[jdwl]';</script>";
  // }
  if (isset($_POST['update'])) {
    // Konversi tanggal
    $d = tgl_simpan($_POST['d']);

    // Tentukan direktori tujuan untuk menyimpan file
    $target_dir = "files/";

    // Ambil data file lama dari database
    $query_file = mysql_query("SELECT file FROM rb_journal_list WHERE id_journal = '$_POST[id]'");
    $data_file = mysql_fetch_assoc($query_file);
    $old_file = $data_file['file'];

    // Cek apakah file baru diunggah
    if ($_FILES['file']['size'] > 0) {
      $new_file_name = basename($_FILES['file']['name']);
      $target_file = $target_dir . $new_file_name;

      // Pastikan direktori ada
      if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
      }

      // Hapus file lama jika ada
      if (!empty($old_file) && file_exists($old_file)) {
        unlink($old_file);
      }

      // Pindahkan file baru ke direktori tujuan
      if (move_uploaded_file($_FILES['file']['tmp_name'], $target_file)) {
        echo "File berhasil diunggah ke: $target_file<br>";
      } else {
        echo "Gagal mengunggah file baru.<br>";
      }
    } else {
      // Jika tidak ada file baru, gunakan file lama
      $target_file = $old_file;
    }

    // Query update
    $query = "UPDATE rb_journal_list SET 
                  hari = '$_POST[c]',
                  tanggal = '$d',
                  jam_ke = '$_POST[e]',
                  sampai_jam_ke = '$_POST[ee]',
                  materi = '$_POST[f]',
                  tujuan_pembelajaran = '$_POST[g]',
                  users = '$_POST[nip_users]',
                  file = '$target_file'
                WHERE id_journal = '$_POST[id]'";

    // Eksekusi query
    if (mysql_query($query)) {
      echo "<script>alert('Data berhasil diperbarui!');</script>";
      echo "<script>document.location='index.php?view=journalguru&act=lihat&id=$_POST[jdwl]';</script>";
    } else {
      echo "<script>alert('Gagal memperbarui data: " . mysql_error() . "');</script>";
    }
  }

  $e = mysql_fetch_array(mysql_query("SELECT a.*, b.kode_pelajaran, b.kode_kelas FROM rb_journal_list a JOIN rb_jadwal_pelajaran b ON a.kodejdwl=b.kodejdwl where a.id_journal='$_GET[id]'"));
  echo "<div class='col-md-12'>
              <div class='box box-info'>
                <div class='box-header with-border'>
                  <h3 class='box-title'>Edit Tujuan Belajar Mengajar</h3>
                </div>
              <div class='box-body'>
              <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
                <div class='col-md-12'>
                  <table class='table table-condensed table-bordered'>
                  <tbody>
                  <input type='hidden' name='jdwl' value='$_GET[jdwl]'>
                  <input type='hidden' name='id' value='$_GET[id]'>
                    <tr hidden><th width='140px' scope='row' hidden>Kelas</th>   <td><select class='form-control' name='a' hidden>";
  $kelas = mysql_query("SELECT * FROM rb_kelas");
  while ($a = mysql_fetch_array($kelas)) {
    if ($e['kode_kelas'] == $a['kode_kelas']) {
      echo "<option value='$a[kode_kelas]' selected hidden>$a[nama_kelas]</option>";
    }
  }
  echo "</select>
                    </td></tr>
                    <tr hidden><th scope='row' hidden>Mata Pelajaran</th>   <td hidden><select class='form-control' name='b' hidden>";
  $mapel = mysql_query("SELECT * FROM rb_mata_pelajaran");
  while ($a = mysql_fetch_array($mapel)) {
    if ($e['kode_pelajaran'] == $a['kode_pelajaran']) {
      echo "<option value='$a[kode_pelajaran]' selected hidden>$a[namamatapelajaran]</option>";
    }
  }
  echo "</select>
                    </td></tr>
                   
                    <tr>
                      <th scope='row'>Hari</th>
                      <td>
                          <select class='form-control' name='c'>
                              <option value='Senin'" . ($e['hari'] == 'Senin' ? ' selected' : '') . ">Senin</option>
                              <option value='Selasa'" . ($e['hari'] == 'Selasa' ? ' selected' : '') . ">Selasa</option>
                              <option value='Rabu'" . ($e['hari'] == 'Rabu' ? ' selected' : '') . ">Rabu</option>
                              <option value='Kamis'" . ($e['hari'] == 'Kamis' ? ' selected' : '') . ">Kamis</option>
                              <option value='Jumat'" . ($e['hari'] == 'Jumat' ? ' selected' : '') . ">Jumat</option>
                              <option value='Sabtu'" . ($e['hari'] == 'Sabtu' ? ' selected' : '') . ">Sabtu</option>
                          </select>
                      </td>
                    </tr>
                   
                    <tr>
                      <td>";

  if ($_SESSION['is_kurikulum']) {
    echo " <tr>
                            <th scope='row'>Pilih Guru</th>   
                            <td>
                            <small style='display: block; text-align: center; color: red;'>Pilih Nama Guru</small>
                                <select style='color: #ffff' class='selectpicker form-control' name='nip_users' data-live-search='true' data-show-subtext='true'>";
    $guru = mysql_query("SELECT * FROM rb_guru WHERE id_jenis_ptk NOT IN (6, 7) ORDER BY nama_guru ASC");
    while ($g = mysql_fetch_array($guru)) {
      echo "<option value='$g[nip]'" . ($e['users'] == $g['nip'] ? ' selected' : '') . ">$g[nama_guru]</option>";
    }
    echo "</select>
                            </td>
                        </tr>";
  } else {
    echo "<input type='hidden' class='form-control' value='$_SESSION[id]' name='nip_users'>";
  }

  echo "</td>
                    </tr>
                    <tr><th scope='row'>Tanggal</th>  <td><input type='text' style='border-radius:0px; padding-left:12px' class='datepicker form-control' value='" . tgl_view($e['tanggal']) . "' name='d' data-date-format='dd-mm-yyyy'></td></tr>
                    <tr><th scope='row'>Dari Jam Ke-</th>  <td><input type='number' class='form-control' value='$e[jam_ke]' name='e'></td></tr>
                    <tr><th scope='row'>Sampai Jam Ke-</th>  <td><input type='number' class='form-control' value='$e[sampai_jam_ke]' name='ee'></td></tr>
                    <tr><th scope='row'>Tujuan Pembelajaran</th>  <td><textarea style='height:160px'  class='form-control' name='g'>$e[tujuan_pembelajaran]</textarea></td></tr>
                    <tr><th scope='row'>Materi</th>  <td><textarea style='height:80px' class='form-control' name='f'>$e[materi]</textarea></td></tr>
                    <tr><th width=120px scope='row'> File</th>             
                    <td>
                      <div class='d-flex flex-column align-items-start'>
                      <!-- Gambar -->
                        <img src='$e[file]' alt='foto materi' class='img-fluid mb-2' style='max-width: 100%; height: auto;'>
                        <!-- File Upload -->
                        <div style='position: relative;' class='w-100'>
                          <a class='btn btn-primary w-100 mb-2' href='javascript:;'>
                            <span class='glyphicon glyphicon-search'></span> Cari File Materi atau Tugas
                            <input type='file' class='files d-none' name='file' onchange='$('#upload-file-info').html($(this).val());'>
                          </a>
                          <span class='label label-info' id='upload-file-info'></span>
                        </div>
                      </div>
                    </td>

                      </td>
                      </tr>
                    </td></tr>
                  </tbody>
                  </table>
                </div>
              </div>
              <div class='box-footer'>
                    <button type='submit' name='update' class='btn btn-info'>Update</button>
                    <a href='index.php?view=journalguru&act=lihat&id=$e[kodejdwl]'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>
                    
                  </div>
              </form>

              <script>
    // Fungsi untuk memeriksa apakah teks adalah URL
    function isURL(str) {
        const pattern = new RegExp(
            '^(https?:\\/\\/)?' + // skema opsional
            '((([a-z\\d]([a-z\\d-]*[a-z\\d])*))\\.)+' + // domain
            '[a-z]{2,}' + // ekstensi (misal .com)
            '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*' + // port dan path opsional
            '(\\?[;&a-z\\d%_.~+=-]*)?' + // query string opsional
            '(\\#[-a-z\\d_]*)?$', 'i' // fragment locator opsional
        );
        return pattern.test(str);
    }

    // Ambil elemen textarea
    const keteranganField = document.getElementById('keterangan');

    // Tambahkan event listener untuk mendeteksi perubahan teks
    keteranganField.addEventListener('input', function () {
        if (isURL(keteranganField.value.trim())) {
            keteranganField.style.color = 'blue'; // Ubah warna teks menjadi biru
        } else {
            keteranganField.style.color = 'black'; // Kembali ke warna hitam
        }
    });
</script>

              <!-- Inisialisasi Bootstrap-select -->
<script>
$(document).ready(function(){
    $('.selectpicker').selectpicker({
        liveSearch: true,
        showSubtext: true,
        size: 10,
        noneResultsText: 'Tidak ada hasil yang cocok {0}',
        liveSearchPlaceholder: 'Cari guru...'
    });
});
</script>


            </div>";
}
?>

<script>
  document.getElementById('search_tujuan').addEventListener('input', function() {
    var searchValue = this.value.toLowerCase();
    var selectElement = document.getElementById('result_tujuan');
    var options = selectElement.getElementsByTagName('option');
    
    // Menampilkan select jika input tidak kosong
    if (searchValue !== '') {
        selectElement.style.display = 'block';
    } else {
        selectElement.style.display = 'none';
    }
    
    // Menyembunyikan opsi yang tidak sesuai dengan pencarian
    for (var i = 0; i < options.length; i++) {
        var optionText = options[i].textContent || options[i].innerText;
        if (optionText.toLowerCase().indexOf(searchValue) > -1) {
            options[i].style.display = 'block';
        } else {
            options[i].style.display = 'none';
        }
    }
});

</script>
