<?php
if ($_GET[act] == '') {
  cek_session_admin();
  ?>
  <div class="col-xs-12">
    <div class="box">
      <div class="box-header">
        <h3 class="box-title"><?php if (isset($_GET[kelas]) and isset($_GET[tahun])) {
          echo "Jadwal Pelajaran";
        } else {
          echo "Jadwal Pelajaran Pada Tahun " . date('Y');
        } ?></h3>
        <form style='margin-right:5px; margin-top:0px' class='pull-right' action='' method='GET'>
          <input type="hidden" name='view' value='bahantugas'>
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
              <th>Total</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php
            if (isset($_GET[kelas]) and isset($_GET[tahun])) {
              $tampil = mysql_query("SELECT a.*, e.nama_kelas, b.namamatapelajaran, b.kode_pelajaran, b.kode_kurikulum, c.nama_guru, d.nama_ruangan FROM rb_jadwal_pelajaran a 
                                            JOIN rb_mata_pelajaran b ON a.kode_pelajaran=b.kode_pelajaran
                                              JOIN rb_guru c ON a.nip=c.nip 
                                                JOIN rb_ruangan d ON a.kode_ruangan=d.kode_ruangan
                                                  JOIN rb_kelas e ON a.kode_kelas=e.kode_kelas 
                                                  where a.kode_kelas='$_GET[kelas]' 
                                                    AND a.id_tahun_akademik='$_GET[tahun]' 
                                                      AND b.kode_kurikulum='$kurikulum[kode_kurikulum]' ORDER BY a.hari DESC");
            }
            $no = 1;
            while ($r = mysql_fetch_array($tampil)) {
              $total = mysql_num_rows(mysql_query("SELECT * FROM rb_elearning where kodejdwl='$r[kodejdwl]'"));
              echo "<tr><td>$no</td>
                              <td>$r[namamatapelajaran]</td>
                              <td>$r[nama_kelas]</td>
                              <td>$r[nama_guru]</td>
                              <td>$r[hari]</td>
                              <td>$r[jam_mulai]</td>
                              <td>$r[jam_selesai]</td>
                              <td>$r[nama_ruangan]</td>
                              <td style='color:red'>$total Record</td>";
              echo "<td style='width:70px !important'><center>
                                      <a class='btn btn-success btn-xs' title='List Bahan dan Tugas' href='index.php?view=bahantugas&act=listbahantugas&jdwl=$r[kodejdwl]&kd=$r[kode_pelajaran]&id=$r[kode_kelas]'><span class='glyphicon glyphicon-th'></span> List Bahan dan Tugas</a>
                                    </center></td>";
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
} elseif ($_GET[act] == 'listbahantugas') {
  cek_session_guru();
  $d = mysql_fetch_array(mysql_query("SELECT * FROM rb_kelas where kode_kelas='$_GET[id]'"));
  $m = mysql_fetch_array(mysql_query("SELECT * FROM rb_mata_pelajaran where kode_pelajaran='$_GET[kd]'"));
  echo "<div class='col-md-12'>
              <div class='box box-info'>
                <div class='box-header with-border'>
                  <h3 class='box-title'>List Upload Bahan dan Tugas</b></h3>";
  if ($_SESSION['level'] == 'guru') {
    echo "<a style='margin-left:4px' class='btn btn-danger btn-sm pull-right' href='javascript:history.back()'>Kembali</a>";
  } elseif ($_SESSION['level'] == 'siswa') {
    echo "<a style='margin-left:4px' class='btn btn-danger btn-sm pull-right' href='index.php?view=bahantugas&act=listbahantugassiswa'>Kembali</a>";
  } else {
    echo "<a style='margin-left:4px' class='btn btn-danger btn-sm pull-right' href='index.php?view=bahantugas'>Kembali</a>";
  }

  if ($_SESSION['level'] == 'guru' or $_SESSION['level'] == 'superuser') {
    echo "<a class='pull-right btn btn-primary btn-sm' href='index.php?view=bahantugas&act=tambah&jdwl=$_GET[jdwl]&id=$_GET[id]&kd=$_GET[kd]'>Tambahkan Data</a>";
  }
  echo "</div>
              <div class='box-body'>

              <div class='col-md-12'>
              <table class='table table-condensed table-hover'>
                  <tbody>
                    <input type='hidden' name='id' value='$s[kodekelas]'>
                    <tr><th width='120px' scope='row'>Kode Kelas</th> <td>$d[kode_kelas]</td></tr>
                    <tr><th scope='row'>Nama Kelas</th>               <td>$d[nama_kelas]</td></tr>
                    <tr><th scope='row'>Mata Pelajaran</th>           <td>$m[namamatapelajaran]</td></tr>
                  </tbody>
              </table>
              </div>

              <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
              <input type='hidden' name='kelas' value='$_GET[id]'>
              <input type='hidden' name='pelajaran' value='$_GET[kd]'>
                <div class='col-md-12'>
                  <div class='table-responsive'>
                    <table id='example1' class='table table-condensed table-bordered table-striped'>
                      <thead>
                      <tr>
                        <th style='width:40px'>No</th>
                        <th>Nama Tugas</th>
                        <th>Kategori</th>
                        <th>Waktu Mulai</th>
                        <th>Batas Waktu</th>
                        <th>Status</th>";
  if ($_SESSION['level'] != 'kepala') {
    echo "<th>Action</th>";
  }
  echo "</tr>
                    </thead>
                    <tbody>";


  $no = 1;

  // Periksa level user
  if ($_SESSION['level'] == 'siswa') {
    // Hanya tampilkan tugas dengan status 'active' untuk siswa
    $tampil = mysql_query("SELECT * FROM rb_elearning a 
                           JOIN rb_kategori_elearning b ON a.id_kategori_elearning=b.id_kategori_elearning 
                           WHERE kodejdwl='$_GET[jdwl]' AND a.status='active' 
                           ORDER BY a.id_elearning");
  } else {
    // Tampilkan semua tugas untuk user selain siswa
    $tampil = mysql_query("SELECT * FROM rb_elearning a 
                           JOIN rb_kategori_elearning b ON a.id_kategori_elearning=b.id_kategori_elearning 
                           WHERE kodejdwl='$_GET[jdwl]' 
                           ORDER BY a.id_elearning");
  }

  while ($r = mysql_fetch_array($tampil)) {
    echo "<tr>
            <td>$no</td>
            <td style='color:red'>$r[nama_file]</td>
            <td>$r[nama_kategori_elearning]</td>
            <td>$r[tanggal_tugas] WIB</td>
            <td>$r[tanggal_selesai] WIB</td>
            <td>$r[status]</td>";

    // Cek level superuser
    if (true) {
      echo "<td>";

      if ($r['id_kategori_elearning'] == '1') {
        echo "<a style='margin-right:5px; width:106px' class='btn btn-info btn-xs' title='Download Bahan dan Tugas' href='download.php?file=$r[file_upload]'><span class='glyphicon glyphicon-download'></span> Download </a>";
      } else {
        echo "<a style='margin-right:5px; width:106px' class='btn btn-success btn-xs' title='Jawaban Bahan dan Tugas' href='index.php?view=bahantugas&act=kirimjawaban&jdwl=$_GET[jdwl]&id=$_GET[id]&kd=$_GET[kd]&ide=$r[id_elearning]'><span class='glyphicon glyphicon-upload'></span> Jawaban </a>";
      }

      // UNTUK MENAMPILKAN ACTION DAPUS DAN EDIT KECUALI ROLE SISWA
      if ($_SESSION['level'] != 'siswa') {
        echo "<a class='btn btn-success btn-xs' title='Edit Bahan dan Tugas' href='index.php?view=bahantugas&act=edit&jdwl=$_GET[jdwl]&id=$_GET[id]&kd=$_GET[kd]&edit=$r[id_elearning]'><span class='glyphicon glyphicon-edit'></span></a>
              <a class='btn btn-danger btn-xs' title='Delete Bahan dan Tugas' href='index.php?view=bahantugas&act=listbahantugas&jdwl=$_GET[jdwl]&id=$_GET[id]&kd=$_GET[kd]&hapus=$r[id_elearning]' onclick=\"return confirm('Apa anda yakin untuk hapus Data ini?')\"><span class='glyphicon glyphicon-trash'></span></a>
              </td></tr>";
    }
    } elseif ($_SESSION['level'] == 'guru') {
      if ($r['id_kategori_elearning'] == '1') {
        echo "<td><a style='width:185px' class='btn btn-info btn-xs' title='Download Bahan dan Tugas' href='download.php?file=$r[file_upload]'><span class='glyphicon glyphicon-download'></span> Download File</a>";
      } else {
        echo "<td><a class='btn btn-info btn-xs' title='Download Bahan dan Tugas' href='download.php?file=$r[file_upload]'><span class='glyphicon glyphicon-download'></span> Download</a>
        <a class='btn btn-success btn-xs' title='Kirim Bahan dan Tugas' href='index.php?view=bahantugas&act=jawaban&jdwl=$_GET[jdwl]&id=$_GET[id]&kd=$_GET[kd]&ide=$r[id_elearning]'><span class='glyphicon glyphicon-upload'></span> Jawaban Tugas</a>";
      }
      echo "<a style='margin-left:3px' class='btn btn-warning btn-xs' title='Edit $r[nama_kategori_elearning]' href='index.php?view=bahantugas&act=edit&jdwl=" . $_GET['jdwl'] . "&id=" . $_GET['id'] . "&kd=" . $_GET['kd'] . "&edit=$r[id_elearning]'><span class='glyphicon glyphicon-edit'></span></a>
                                        <a class='btn btn-danger btn-xs' title='Delete $r[nama_kategori_elearning]' href='index.php?view=bahantugas&act=listbahantugas&jdwl=" . $_GET['jdwl'] . "&id=" . $_GET['id'] . "&kd=" . $_GET['kd'] . "&hapus=$r[id_elearning]' onclick=\"return confirm('Apa anda yakin untuk hapus Data ini?')\"><span class='glyphicon glyphicon-remove'></span></a></td></tr>";
    }elseif($_SESSION['level'] == ''){
      echo "<td><a class='btn btn-info btn-xs' title='Download Bahan dan Tugas' href='download.php?file=$r[file_upload]'><span class='glyphicon glyphicon-download'></span> Download</a>
      <a class='btn btn-success btn-xs' title='Kirim Bahan dan Tugas' href='index.php?view=bahantugas&act=kirim&jdwl=$_GET[jdwl]&id=$_GET[id]&kd=$_GET[kd]'><span class='glyphicon glyphicon-upload'></span> Kirim</a>";
    }
    $no++;
  }

  if (isset($_GET['hapus'])) {
    mysql_query("DELETE FROM rb_elearning WHERE id_elearning='$_GET[hapus]'");
    echo "<script>document.location='index.php?view=bahantugas&act=listbahantugas&jdwl=" . $_GET['jdwl'] . "&id=" . $_GET['id'] . "&kd=" . $_GET['kd'] . "';</script>";
  }

  echo "</tbody>
                  </table>
                </div>
              </div>
              </form>
            </div>";

}elseif($_GET[act] == 'bahantugassiswa'){
  cek_session_siswa();
  $d = mysql_fetch_array(mysql_query("SELECT * FROM rb_kelas where kode_kelas='$_GET[id]'"));
  $m = mysql_fetch_array(mysql_query("SELECT * FROM rb_mata_pelajaran where kode_pelajaran='$_GET[kd]'"));
  echo "<div class='col-md-12'>
              <div class='box box-info'>
                <div class='box-header with-border'>
                  <h3 class='box-title'>List Tugas</b></h3>";
  echo "</div>
              <div class='box-body'>

              <div class='col-md-12'>
              <table class='table table-condensed table-hover'>
                  <tbody>
                    <input type='hidden' name='id' value='$s[kodekelas]'>
                    <tr><th width='120px' scope='row'>Kode Kelas</th> <td>$d[kode_kelas]</td></tr>
                    <tr><th scope='row'>Nama Kelas</th>               <td>$d[nama_kelas]</td></tr>
                    <tr><th scope='row'>Mata Pelajaran</th>           <td>$m[namamatapelajaran]</td></tr>
                  </tbody>
              </table>
              </div>

              <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
              <input type='hidden' name='kelas' value='$_GET[id]'>
              <input type='hidden' name='pelajaran' value='$_GET[kd]'>
                <div class='col-md-12'>
                  <div class='table-responsive'>
                    <table id='example1' class='table table-condensed table-bordered table-striped'>
                      <thead>
                      <tr>
                        <th style='width:40px'>No</th>
                        <th>Nama Tugas</th>
                        <th>Kategori</th>
                        <th>Waktu Mulai</th>
                        <th>Batas Waktu</th>
                        <th>Status</th>
                        <center><th>Action</th></center>";
  echo "</tr>
                    </thead>
                    <tbody>";


  $no = 1;

 
    $tampil = mysql_query("SELECT * FROM rb_elearning a 
                           JOIN rb_kategori_elearning b ON a.id_kategori_elearning=b.id_kategori_elearning 
                           WHERE kodejdwl='$_GET[jdwl]' AND a.status='active' 
                           ORDER BY a.id_elearning");

  while ($r = mysql_fetch_array($tampil)) {
    echo "<tr>
            <td>$no</td>
            <td style='color:red'>$r[nama_file]</td>
            <td>$r[nama_kategori_elearning]</td>
            <td>$r[tanggal_tugas] WIB</td>
            <td>$r[tanggal_selesai] WIB</td>
            <td>$r[status]</td>
            <td>";
            if ($r['id_kategori_elearning'] == '1') {
              echo "<td><a style='width:185px' class='btn btn-info btn-xs' title='Download Bahan dan Tugas' href='download.php?file=$r[file_upload]'><span class='glyphicon glyphicon-download'></span> Download File</a>";
            } else {
              echo "<td><a class='btn btn-info btn-xs' title='Download Bahan dan Tugas' href='download.php?file=$r[file_upload]'><span class='glyphicon glyphicon-download'></span> Download</a>
              <a class='btn btn-success btn-xs' title='Kirim Bahan dan Tugas' href='index.php?view=bahantugas&act=kirim&jdwl=$_GET[jdwl]&id=$_GET[id]&kd=$_GET[kd]&ide=$r[id_elearning]'><span class='glyphicon glyphicon-upload'></span> Kirim Tugas</a><td>";
            }
    echo "</tr>";
    $no++;
  }
  echo "</tbody>
  </table>
</div>
</div>
</form>
</div>";
} 


elseif ($_GET[act] == 'tambah') {
  cek_session_guru();
  if (isset($_POST[tambah])) {
    // var_dump($_POST);
    // exit;
    $dir_gambar = 'files/';
    $filename = basename($_FILES['c']['name']);
    $filenamee = date("YmdHis") . '-' . basename($_FILES['c']['name']);
    $uploadfile = $dir_gambar . $filenamee;
    if ($filename != '') {
      if (move_uploaded_file($_FILES['c']['tmp_name'], $uploadfile)) {
        mysql_query("INSERT INTO rb_elearning VALUES ('','$_POST[a]','$_GET[jdwl]','$_POST[b]','$filenamee','$_POST[d]','$_POST[e]','$_POST[f]', '$_POST[g]')");
        echo "<script>document.location='index.php?view=bahantugas&act=listbahantugas&jdwl=" . $_GET[jdwl] . "&id=" . $_GET[id] . "&kd=" . $_GET[kd] . "';</script>";
      } else {
        echo "<script>window.alert('Gagal Tambahkan Data Bahan dan Tugas.');
                      window.location='index.php?view=bahantugas&act=listbahantugas&jdwl=" . $_GET[jdwl] . "&id=" . $_GET[id] . "&kd=" . $_GET[kd] . "'</script>";
      }
    } else {
      mysql_query("INSERT INTO rb_elearning VALUES ('','$_POST[a]','$_GET[jdwl]','$_POST[b]','','$_POST[d]','$_POST[e]','$_POST[f]', '$_POST[g]')");
      echo "<script>document.location='index.php?view=bahantugas&act=listbahantugas&jdwl=" . $_GET[jdwl] . "&id=" . $_GET[id] . "&kd=" . $_GET[kd] . "';</script>";
    }
  }

  echo "<div class='col-md-12'>
          <div class='row'>
            <div class='col-12'>
              <div class='box box-info'>
                <div class='box-header with-border'>
                  <h3 class='box-title'>Tambah Bahan dan Tugas</h3>
                </div>
                <div class='box-body'>
                  <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
                    <div class='row'>
                      <div class='col-12'>
                        <table class='table table-condensed table-bordered'>
                          <tbody>
                            <tr><th width='120px' scope='row'>Kategori</th> <td><select class='form-control' name='a'> 
                             <option value='0' selected>- Pilih Kategori Tugas -</option>";
                            $kategori = mysql_query("SELECT * FROM rb_kategori_elearning");
                            while ($a = mysql_fetch_array($kategori)) {
                              if ($s[id_kategori_elearning] == $a[id_kategori_elearning]) {
                                echo "<option value='$a[id_kategori_elearning]' selected>$a[nama_kategori_elearning]</option>";
                              } else {
                                echo "<option value='$a[id_kategori_elearning]'>$a[nama_kategori_elearning]</option>";
                              }
                            }
                            echo "</select>
                    </td></tr>
                            <tr>
                              <th width='120px' scope='row'>Status</th>
                              <td>
                                <select class='form-control' name='g'>
                                  <option value='0' selected>- Pilih Status Tugas -</option>
                                  <option value='active'>Active</option>
                                  <option value='inactive'>Inactive</option>
                                </select>
                              </td>
                            </tr>
                            <tr>
                              <th scope='row'>Nama File</th>
                              <td><input type='text' class='form-control' name='b'></td>
                            </tr>
                            <tr>
                              <th scope='row'>File</th>
                              <td>
                                <div class='d-flex align-items-center'>
                                    <a class='btn btn-primary' href='javascript:;' onclick='$('.files').click();'>
                                        <i class='fa fa-search'></i> Cari File Bahan atau Tugas...
                                    </a>
                                    <input type='file' class='files' name='c' style='display:none;' onchange='$('#upload-file-info').html($(this).val());'>
                                    <span class='label label-info' id='upload-file-info' style='margin-left:10px;'></span>
                                </div>


                              </td>
                            </tr>
                            <tr>
                              <th scope='row'>Waktu Mulai</th>
                              <td><input type='datetime-local' class='form-control' name='d' value='<?php echo date('Y-m-d\TH:i'); ?></td>
                            </tr>
                            <tr>
                              <th scope='row'>Waktu Selesai</th>
                              <td><input type='datetime-local' class='form-control' name='e' value='<?php echo date('Y-m-d\TH:i'); ?></td>
                            </tr>
                            <tr>
                              <th scope='row'>Keterangan</th>
                              <td><input type='text' class='form-control' name='f'></td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                    <div class='box-footer'>
                      <button type='submit' name='tambah' class='btn btn-info'>Tambahkan</button>
                      <a href='index.php?view=bahantugas'>
                        <button type='button' class='btn btn-default pull-right'>Cancel</button>
                      </a>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>";
} elseif ($_GET[act] == 'edit') {
  cek_session_guru();
  if (isset($_POST[update])) {
    // var_dump($_POST);
    // exit;
    $dir_gambar = 'files/';
    $filename = basename($_FILES['c']['name']);
    $filenamee = date("YmdHis") . '-' . basename($_FILES['c']['name']);
    $uploadfile = $dir_gambar . $filenamee;
    if ($filename != '') {
      if (move_uploaded_file($_FILES['c']['tmp_name'], $uploadfile)) {
        mysql_query("UPDATE rb_elearning SET id_kategori_elearning = '$_POST[a]',
                                               kodejdwl              = '$_GET[jdwl]',
                                               nama_file             = '$_POST[b]',
                                               file_upload           = '$filenamee',
                                               tanggal_tugas         = '$_POST[d]',
                                               tanggal_selesai       = '$_POST[e]',
                                               status                = '$_POST[g]',
                                               keterangan            = '$_POST[f]' where id_elearning='$_GET[edit]'");
        echo "<script>document.location='index.php?view=bahantugas&act=listbahantugas&jdwl=" . $_GET[jdwl] . "&id=" . $_GET[id] . "&kd=" . $_GET[kd] . "';</script>";
      } else {
        echo "<script>window.alert('Gagal Update Data Bahan dan Tugas.');
                      window.location='index.php?view=bahantugas&act=listbahantugas&jdwl=" . $_GET[jdwl] . "&id=" . $_GET[id] . "&kd=" . $_GET[kd] . "'</script>";
      }
    } else {
      mysql_query("UPDATE rb_elearning SET id_kategori_elearning = '$_POST[a]',
                                               kodejdwl              = '$_GET[jdwl]',
                                               nama_file             = '$_POST[b]',
                                               tanggal_tugas         = '$_POST[d]',
                                               tanggal_selesai       = '$_POST[e]',
                                               status                = '$_POST[g]',
                                               keterangan            = '$_POST[f]' where id_elearning='$_GET[edit]'");
      echo "<script>document.location='index.php?view=bahantugas&act=listbahantugas&jdwl=" . $_GET[jdwl] . "&id=" . $_GET[id] . "&kd=" . $_GET[kd] . "';</script>";
    }
  }

  $edit = mysql_query("SELECT * FROM rb_elearning a JOIN rb_kategori_elearning b ON a.id_kategori_elearning=b.id_kategori_elearning where a.id_elearning='$_GET[edit]'");
  $s = mysql_fetch_array($edit);
  echo "<div class='col-md-12'>
              <div class='box box-info'>
                <div class='box-header with-border'>
                  <h3 class='box-title'>Edit Bahan dan Tugas</h3>
                </div>
              <div class='box-body'>
              <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
                <div class='col-md-12'>
                  <table class='table table-condensed table-bordered'>
                  <tbody>
                    <tr><th width='120px' scope='row'>Kategori</th> <td><select class='form-control' name='a'> 
                             <option value='0' selected>- Pilih Kategori Tugas -</option>";
  $kategori = mysql_query("SELECT * FROM rb_kategori_elearning");
  while ($a = mysql_fetch_array($kategori)) {
    if ($s[id_kategori_elearning] == $a[id_kategori_elearning]) {
      echo "<option value='$a[id_kategori_elearning]' selected>$a[nama_kategori_elearning]</option>";
    } else {
      echo "<option value='$a[id_kategori_elearning]'>$a[nama_kategori_elearning]</option>";
    }
  }
  echo "</select>
                    </td></tr>
                     <tr>
                        <th width='120px' scope='row'>Status</th>
                        <td>
                          <select class='form-control' name='g'>
                              <option value='active' " . ($s['status'] == 'active' ? 'selected' : '') . ">Active</option>
                              <option value='inactive' " . ($s['status'] == 'inactive' ? 'selected' : '') . ">Inactive</option>
                          </select>
                        </td>
                      </tr>
                    <tr><th scope='row'>Nama File</th>        <td><input type='text' class='form-control' name='b' value='$s[nama_file]'></td></tr>
                    <tr><th scope='row'>Ganti File</th>             <td><div style='position:relative;''>
                                                                          <a class='btn btn-primary' href='javascript:;'>
                                                                            <i class='fa fa-search'></i> <b>Ganti File :</b> $s[file_upload]"; ?>
  <input type='file' class='files' name='c' onchange='$("#upload-file-info").html($(this).val());'>
  <?php echo "</a> <span style='width:155px' class='label label-info' id='upload-file-info'></span>
                                                                        </div>
                    </td></tr>
                    <tr><th scope='row'>Waktu Mulai</th>      <td><input type='datetime-local' class='form-control' value='$s[tanggal_tugas]' name='d'></td></tr>
                    <tr><th scope='row'>Waktu Selesai</th>    <td><input type='datetime-local' class='form-control' value='$s[tanggal_selesai]' name='e'></td></tr>
                    <tr><th scope='row'>Keterangan</th>       <td><input type='text' class='form-control' name='f' value='$s[keterangan]'></td></tr>
                    
                  </tbody>
                  </table>
                </div>
                
              </div>
              <div class='box-footer'>
                    <button type='submit' name='update' class='btn btn-info'>Update</button>
                    <a href='index.php?view=bahantugas'><button class='btn btn-default pull-right'>Cancel</button></a>
                    
                  </div>
              </form>
            </div>";
} elseif ($_GET[act] == 'listbahantugasguru') {
  cek_session_guru();
  ?>
  <div class="col-xs-12">
    <div class="box">
      <div class="box-header">
        <h3 class="box-title"><?php if (isset($_GET[tahun])) {
          echo "Bahan dan Tugas";
        } else {
          echo "Bahan dan Tugas Pada " . date('Y');
        } ?></h3>
        <form style='margin-right:5px; margin-top:0px' class='pull-right' action='' method='GET'>
          <input type="hidden" name='view' value='bahantugas'>
          <input type="hidden" name='act' value='listbahantugasguru'>
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
              <th>Total</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php
            if (isset($_GET[tahun])) {
              $tampil = mysql_query("SELECT a.*, e.nama_kelas, b.namamatapelajaran, b.kode_pelajaran, b.kode_kurikulum, c.nama_guru, d.nama_ruangan FROM rb_jadwal_pelajaran a 
                                            JOIN rb_mata_pelajaran b ON a.kode_pelajaran=b.kode_pelajaran
                                              JOIN rb_guru c ON a.nip=c.nip 
                                                JOIN rb_ruangan d ON a.kode_ruangan=d.kode_ruangan
                                                  JOIN rb_kelas e ON a.kode_kelas=e.kode_kelas 
                                                  where a.nip='$_SESSION[id]' 
                                                    AND a.id_tahun_akademik='$_GET[tahun]' 
                                                      AND b.kode_kurikulum='$kurikulum[kode_kurikulum]'
                                                        ORDER BY a.hari DESC");
            } else {
              $tampil = mysql_query("SELECT a.*, e.nama_kelas, b.namamatapelajaran, b.kode_pelajaran, b.kode_kurikulum, c.nama_guru, d.nama_ruangan FROM rb_jadwal_pelajaran a 
                                            JOIN rb_mata_pelajaran b ON a.kode_pelajaran=b.kode_pelajaran
                                              JOIN rb_guru c ON a.nip=c.nip 
                                                JOIN rb_ruangan d ON a.kode_ruangan=d.kode_ruangan
                                                JOIN rb_kelas e ON a.kode_kelas=e.kode_kelas 
                                                  where b.kode_kurikulum='$kurikulum[kode_kurikulum]' AND a.nip='$_SESSION[id]' AND a.id_tahun_akademik LIKE '" . date('Y') . "%' ORDER BY a.hari DESC");
            }
            $no = 1;
            while ($r = mysql_fetch_array($tampil)) {
              $total = mysql_num_rows(mysql_query("SELECT * FROM rb_elearning where kodejdwl='$r[kodejdwl]'"));
              echo "<tr><td>$no</td>
                              <td>$r[namamatapelajaran]</td>
                              <td>$r[nama_kelas]</td>
                              <td>$r[nama_guru]</td>
                              <td>$r[hari]</td>
                              <td>$r[jam_mulai]</td>
                              <td>$r[jam_selesai]</td>
                              <td>$r[nama_ruangan]</td>
                              <td>$r[id_tahun_akademik]</td>
                              <td style='color:red'>$total Record</td>
                              <td><a class='btn btn-success btn-xs' title='List Bahan dan Tugas' href='index.php?view=bahantugas&act=listbahantugas&jdwl=$r[kodejdwl]&id=$r[kode_kelas]&kd=$r[kode_pelajaran]'><span class='glyphicon glyphicon-th'></span> Tampilkan</a></td>
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
} elseif ($_GET[act] == 'listbahantugassiswa') {
  cek_session_siswa();
  ?>
  <div class="col-xs-12">
    <div class="box">
      <div class="box-header">
        <h3 class="box-title"><?php if (isset($_GET[kelas]) and isset($_GET[tahun])) {
          echo "Bahan dan Tugas";
        } else {
          echo "Bahan dan Tugas " . date('Y');
        } ?></h3>
        <form style='margin-right:5px; margin-top:0px' class='pull-right' action='' method='GET'>
          <input type="hidden" name='view' value='bahantugas'>
          <input type="hidden" name='act' value='listbahantugassiswa'>
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
            if (isset($_GET[tahun])) {
              $tampil = mysql_query("SELECT a.*, e.nama_kelas, b.namamatapelajaran, b.kode_pelajaran, b.kode_kurikulum, c.nama_guru, d.nama_ruangan FROM rb_jadwal_pelajaran a 
                                            JOIN rb_mata_pelajaran b ON a.kode_pelajaran=b.kode_pelajaran
                                              JOIN rb_guru c ON a.nip=c.nip 
                                                JOIN rb_ruangan d ON a.kode_ruangan=d.kode_ruangan
                                                  JOIN rb_kelas e ON a.kode_kelas=e.kode_kelas 
                                                  where a.kode_kelas='$_SESSION[kode_kelas]' 
                                                    AND a.id_tahun_akademik='$_GET[tahun]' 
                                                      AND b.kode_kurikulum='$kurikulum[kode_kurikulum]' 
                                                        ORDER BY a.hari DESC");
            } else {
              $tampil = mysql_query("SELECT a.*, e.nama_kelas, b.namamatapelajaran, b.kode_pelajaran, b.kode_kurikulum, c.nama_guru, d.nama_ruangan FROM rb_jadwal_pelajaran a 
                                            JOIN rb_mata_pelajaran b ON a.kode_pelajaran=b.kode_pelajaran
                                              JOIN rb_guru c ON a.nip=c.nip 
                                                JOIN rb_ruangan d ON a.kode_ruangan=d.kode_ruangan
                                                JOIN rb_kelas e ON a.kode_kelas=e.kode_kelas 
                                                  where b.kode_kurikulum='$kurikulum[kode_kurikulum]' 
                                                    AND a.kode_kelas='$_SESSION[kode_kelas]' 
                                                      AND a.id_tahun_akademik LIKE '" . date('Y') . "%' ORDER BY a.hari DESC");
            }
            $no = 1;
            while ($r = mysql_fetch_array($tampil)) {
              $total = mysql_num_rows(mysql_query("SELECT * FROM rb_elearning where kodejdwl='$r[kodejdwl]'"));
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
                              <td><a class='btn btn-success btn-xs' title='List Bahan dan Tugas' href='index.php?view=bahantugas&act=bahantugassiswa&jdwl=$r[kodejdwl]&id=$r[kode_kelas]&kd=$r[kode_pelajaran]'><span class='glyphicon glyphicon-th'></span> Tampilkan</a></td>
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
} elseif ($_GET[act] == 'kirim') {
  cek_session_siswa();
  $cek = mysql_fetch_array(mysql_query("SELECT count(*) as total FROM rb_elearning_jawab where id_elearning='$_GET[ide]' AND nisn='$iden[nisn]'"));
  // var_dump($iden['nisn']);
  if ($cek[total] >= 1) {
    echo "<script>window.alert('Maaf, Anda Sudah Mengirimkan Tugas ini Sebelumnya.');
               window.location='index.php?view=bahantugas&act=bahantugassiswa&jdwl=" . $_GET['jdwl'] . "&id=" . $_GET['id'] . "&kd=" . $_GET['kd'] . "'</script>";
  } else {
    if (isset($_POST['kirimkan'])) {
      $dir_gambar = 'files/';
      $filename = basename($_FILES['c']['name']);
      $filenamee = date("YmdHis") . '-' . basename($_FILES['c']['name']);
      $uploadfile = $dir_gambar . $filenamee;
  
      if ($filename != '') {
          $waktuu = date("Y-m-d H:i:s");
  
          // Cek hasil move_uploaded_file
          if (move_uploaded_file($_FILES['c']['tmp_name'], $uploadfile)) {
              // Ganti mysql_query dengan mysqli_query atau PDO
              mysql_query("INSERT INTO rb_elearning_jawab VALUES (NULL,'$_GET[ide]','$iden[nisn]','$_POST[a]','$filenamee','$waktuu', NULL)");
              echo "<script>window.alert('Berhasil Kirim Tugas');
              window.location='index.php?view=bahantugas&act=bahantugassiswa&jdwl=" . $_GET['jdwl'] . "&id=" . $_GET['id'] . "&kd=" . $_GET['kd'] . "'</script>";
          } else {
              echo "<script>window.alert('Gagal Kirimkan Data Tugas.');
                      window.location='index.php?view=bahantugas&act=listbahantugas&jdwl=" . $_GET['jdwl'] . "&id=" . $_GET['id'] . "&kd=" . $_GET['kd'] . "'</script>";
          }
      } else {
          echo "<script>window.alert('Gagal Kirimkan Data Tugas.');
                  window.location='index.php?view=bahantugas&act=listbahantugas&jdwl=" . $_GET['jdwl'] . "&id=" . $_GET['id'] . "&kd=" . $_GET['kd'] . "'</script>";
      }
  }
  
  
    

    echo "<div class='col-md-12'>
              <div class='box box-info'>
                <div class='box-header with-border'>
                  <h3 class='box-title'>Kirimkan Tugas</h3>
                </div>
              <div class='box-body'>
              <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
                <div class='col-md-12'>
                  <table class='table table-condensed table-bordered'>
                  <tbody>
                    <tr><th width=120px scope='row'>Nama File</th>             <td><div style='position:relative;''>
                                                                          <a class='btn btn-primary' href='javascript:;'>
                                                                            <span class='glyphicon glyphicon-search'></span> Cari File Tugas yang akan dikirim..."; ?>
    <input type='file' class='files' name='c' onchange='$("#upload-file-info").html($(this).val());'>
    <?php echo "</a> <span style='width:155px' class='label label-info' id='upload-file-info'></span>
                                                                        </div>
                    </td></tr>
                    <tr><th scope='row'>Keterangan</th>       <td><textarea rows='5' class='form-control' name='a'></textarea></td></tr>
                    
                  </tbody>
                  </table>
                  <i><b style='color:red'>Catatan</b> : Tugas Hanya Bisa dikirimkan 1 (satu) kali saja.</i>
                </div>
                
              </div>
              <div class='box-footer'>
                    <button type='submit' name='kirimkan' class='btn btn-info'>Kirimkan Tugas</button>
                    <a href='index.php?view=bahantugas'><button class='btn btn-default pull-right'>Cancel</button></a>
                    
                  </div>
              </form>
            </div>";
  }
} elseif ($_GET[act] == 'kirimjawaban') {
  cek_session_guru();
  $d = mysql_fetch_array(mysql_query("SELECT * FROM rb_kelas where kode_kelas='$_GET[id]'"));
  $m = mysql_fetch_array(mysql_query("SELECT * FROM rb_mata_pelajaran where kode_pelajaran='$_GET[kd]'"));
  $elearning = mysql_fetch_array(mysql_query("SELECT * FROM rb_elearning where id_elearning='$_GET[ide]'"));
  echo "<div class='col-xs-12'>  
              <div class='box'>
                <div class='box-header'>
                  <h3 class='box-title'>Daftar Siswa yang Mengirimkan Jawaban Tugas </h3>
                  <a class='btn btn-danger btn-sm pull-right' href='index.php?view=bahantugas&act=listbahantugas&jdwl=$_GET[jdwl]&kd=$_GET[kd]&id=$_GET[id]'>Kembali</a>
                </div>

                <div class='col-md-12'>
                <table class='table table-condensed table-hover' style='overflow-x: auto; display: block;'>
                    <tbody>
                      <input type='hidden' name='id' value='$s[kode_kelas]'>
                      <tr><th width='120px' scope='row'>Kode Kelas</th> <td>$d[kode_kelas]</td></tr>
                      <tr><th scope='row'>Nama Kelas</th>               <td>$d[nama_kelas]</td></tr>
                      <tr><th scope='row'>Mata Pelajaran</th>           <td>$m[namamatapelajaran]</td></tr>
                      <tr><th scope='row'>Preview Gambar</th>           <td><button type='button' onclick='openModal()'>Lihat Gambar</button></td></tr>
                      <!-- Modal -->
                      <div id='imageModal' style='display:none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.7); justify-content: center; align-items: center;'>
                          <div style='position: relative; background-color: white; padding: 20px; max-width: 90%; max-height: 90%;'>
                              <!-- Tombol untuk Menutup Modal -->
                              <span onclick='closeModal()' style='position: absolute; top: 10px; right: 15px; font-size: 24px; cursor: pointer;'>&times;</span>
                              <!-- Gambar di dalam Modal -->
                              <img src='files/<?php echo $elearning[file_uploadd]; ?>' alt='Gambar Elearning' width='100%'>
                          </div>
                      </div>
                    </tbody>
                </table>
                </div>

                <div class='box-body' style='overflow-x: auto; display: block;'>
                <table class='table table-bordered table-striped' >
                      <tr>
                        <th style='width:40px'>No</th>
                        <th style='width:90px'>NISN</th>
                        <th>Nama Lengkap</th>
                        <th>Keterangan</th>
                        <th style='width:100px'>Waktu Kirim</th>
                        <th>Nilai</th>
                        <th>Action</th>
                      </tr>";

 // Ambil id tugas dari URL
$id_tugas = (int)$_GET['ide']; // Pastikan menggunakan parameter yang benar (ide)

// Modifikasi query dengan menambahkan kondisi yang lebih spesifik
$tampil = mysql_query("SELECT a.*, b.* 
                                FROM rb_elearning_jawab a 
                                JOIN rb_siswa b ON a.nisn = b.nisn 
                                JOIN rb_elearning c ON a.id_elearning = c.id_elearning 
                                WHERE a.id_elearning = '$id_tugas' 
                                AND c.kodejdwl = '$_GET[jdwl]' 
                                AND DATE(a.waktu) = CURDATE() 
                                ORDER BY a.id_elearning_jawab DESC");


// Periksa apakah query berhasil
if(!$tampil) {
    echo "Error: " . mysql_error();
    exit;
}

$no = 1;
while ($r = mysql_fetch_array($tampil)) {
    // Tambahkan pengecekan apakah jawaban ini memang untuk tugas yang dipilih
    echo $tampil;
    if($r['id_elearning'] == $id_tugas) {
        echo "<tr>
                <td>$no</td>
                <td>$r[nisn]</td>
                <td>$r[nama]</td>
                <td>$r[keterangan]</td>
                <td>$r[waktu] WIB</td>
                <td>";
        
        if($r['nilai']){
            echo "$r[nilai]";
        } else {
            echo "<form method='POST' class='form-horizontal' action='' id='nilaiForm'>
                    <input type='hidden' name='id_elearning_jawab' value='$r[id_elearning_jawab]'>
                    <select name='nilai' style='padding:4px' onchange='submitFormWithAlert(this)'>
                        <option value=''>Pilih Nilai</option>
                        <option value='A'>A</option>
                        <option value='B'>B</option>
                        <option value='C'>C</option>
                        <option value='D'>D</option>
                        <option value='F'>F</option>
                    </select>
                  </form>";
        }
        
        echo "</td>
              <td style='width:70px !important'><center>
                <a class='btn btn-success btn-xs' title='Download Tugas' 
                   href='download.php?file=$r[file_tugas]'>
                  <span class='glyphicon glyphicon-download'></span> Download
                </a>
              </center></td>
            </tr>";
        $no++;
    }
}

// Jika tidak ada data yang ditampilkan
if($no == 1) {
    echo "<tr><td colspan='7' class='text-center'>Belum ada jawaban untuk tugas ini</td></tr>";
}

  echo "</tbody>
                  </table>
                </div>
              </div>
              </form>
            </div>";
}
?>

<script>
function submitFormWithAlert(selectElement) {
    const selectedValue = selectElement.value;
    if (selectedValue) {
        const confirmSubmit = confirm(`Apakah Anda yakin ingin memberikan nilai ${selectedValue}?`);
        if (confirmSubmit) {
            document.getElementById('nilaiForm').submit();
        }
    }
}

function openModal() {
    document.getElementById('imageModal').style.display = 'flex';
}

function closeModal() {
    document.getElementById('imageModal').style.display = 'none';
}
</script>