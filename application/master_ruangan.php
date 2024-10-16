<?php if ($_GET['act'] == '') { ?>
  <div class="container">
    <div class="row">
      <div class="col-12">
        <div class="card mt-4">
          <div class="card-header">
            <h3 class="card-title">Data Ruangan</h3>
            <?php if ($_SESSION['level'] != 'kepala') { ?>
              <a class="btn btn-primary btn-sm float-right" href="index.php?view=ruangan&act=tambah">Tambahkan Data</a>
            <?php } ?>
          </div><!-- /.card-header -->
          <div class="card-body">
            <div class="table-responsive">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th style="width:40px">No</th>
                    <th>Kode Ruangan</th>
                    <th>Nama Gedung</th>
                    <th>Nama Ruangan</th>
                    <th>Kapasitas Belajar</th>
                    <th>Kapasitas Ujian</th>
                    <th>Keterangan</th>
                    <th>Aktif</th>
                    <?php if ($_SESSION['level'] != 'kepala') { ?>
                      <th style="width:70px">Action</th>
                    <?php } ?>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $tampil = mysql_query("SELECT * FROM rb_ruangan a 
                                        JOIN rb_gedung b ON a.kode_gedung=b.kode_gedung 
                                        ORDER BY a.kode_ruangan DESC");
                  $no = 1;
                  while ($r = mysql_fetch_array($tampil)) {
                    echo "<tr>
                                            <td>$no</td>
                                            <td>$r[kode_ruangan]</td>
                                            <td>$r[nama_gedung]</td>
                                            <td>$r[nama_ruangan]</td>
                                            <td>$r[kapasitas_belajar] Orang</td>
                                            <td>$r[kapasitas_ujian] Orang</td>
                                            <td>$r[keterangan]</td>
                                            <td>$r[aktif]</td>";
                    if ($_SESSION['level'] != 'kepala') {
                      echo "<td>
                                                <a class='btn btn-success btn-xs' title='Edit Data' href='?view=ruangan&act=edit&id=$r[kode_ruangan]'><i class='fas fa-edit'></i></a>
                                                <a class='btn btn-danger btn-xs' title='Delete Data' href='?view=ruangan&hapus=$r[kode_ruangan]'><i class='fas fa-trash'></i></a>
                                            </td>";
                    }
                    echo "</tr>";
                    $no++;
                  }
                  if (isset($_GET['hapus'])) {
                    mysql_query("DELETE FROM rb_ruangan WHERE kode_ruangan='$_GET[hapus]'");
                    echo "<script>document.location='index.php?view=ruangan';</script>";
                  }
                  ?>
                </tbody>
              </table>
            </div><!-- /.table-responsive -->
          </div><!-- /.card-body -->
        </div><!-- /.card -->
      </div><!-- /.col-12 -->
    </div><!-- /.row -->
  </div><!-- /.container -->
<?php } elseif ($_GET['act'] == 'edit') {
  if (isset($_POST['update'])) {
    mysql_query("UPDATE rb_ruangan SET kode_ruangan = '$_POST[a]',
            kode_gedung = '$_POST[b]',
            nama_ruangan = '$_POST[c]',
            kapasitas_belajar = '$_POST[d]',
            kapasitas_ujian = '$_POST[e]',
            keterangan = '$_POST[f]',
            aktif = '$_POST[g]' WHERE kode_ruangan='$_POST[id]'");
    echo "<script>document.location='index.php?view=ruangan';</script>";
  }
  $edit = mysql_query("SELECT * FROM rb_ruangan WHERE kode_ruangan='$_GET[id]'");
  $s = mysql_fetch_array($edit);
  echo "<div class='container'>
            <div class='row'>
                <div class='col-md-12'>
                    <div class='card mt-4'>
                        <div class='card-header'>
                            <h3 class='card-title'>Edit Data Ruangan</h3>
                        </div>
                        <div class='card-body'>
                            <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
                                <input type='hidden' name='id' value='$s[kode_ruangan]'>
                                <div class='form-group'>
                                    <label for='kode_ruangan'>Kode Ruangan</label>
                                    <input type='text' class='form-control' name='a' value='$s[kode_ruangan]'>
                                </div>
                                <div class='form-group'>
                                    <label for='nama_gedung'>Nama Gedung</label>
                                    <select class='form-control' name='b'>
                                        <option value='0' selected>- Pilih Gedung -</option>";
  $wali = mysql_query("SELECT * FROM rb_gedung");
  while ($a = mysql_fetch_array($wali)) {
    if ($a['kode_gedung'] == $s['kode_gedung']) {
      echo "<option value='$a[kode_gedung]' selected>$a[nama_gedung]</option>";
    } else {
      echo "<option value='$a[kode_gedung]'>$a[nama_gedung]</option>";
    }
  }
  echo "</select>
                                </div>
                                <div class='form-group'>
                                    <label for='nama_ruangan'>Nama Ruangan</label>
                                    <input type='text' class='form-control' name='c' value='$s[nama_ruangan]'>
                                </div>
                                <div class='form-group'>
                                    <label for='kapasitas_belajar'>Kapasitas Belajar</label>
                                    <input type='text' class='form-control' name='d' value='$s[kapasitas_belajar]'>
                                </div>
                                <div class='form-group'>
                                    <label for='kapasitas_ujian'>Kapasitas Ujian</label>
                                    <input type='text' class='form-control' name='e' value='$s[kapasitas_ujian]'>
                                </div>
                                <div class='form-group'>
                                    <label for='keterangan'>Keterangan</label>
                                    <input type='text' class='form-control' name='f' value='$s[keterangan]'>
                                </div>
                                <div class='form-group'>
                                    <label>Aktif</label><br>";
  if ($s['aktif'] == 'Ya') {
    echo "<input type='radio' name='g' value='Ya' checked> Ya
                                              <input type='radio' name='g' value='Tidak'> Tidak";
  } else {
    echo "<input type='radio' name='g' value='Ya'> Ya
                                              <input type='radio' name='g' value='Tidak' checked> Tidak";
  }
  echo "</div>
                        </div>
                        <div class='card-footer'>
                            <button type='submit' name='update' class='btn btn-info'>Update</button>
                            <a href='index.php?view=ruangan' class='btn btn-default float-right'>Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>";
} elseif ($_GET['act'] == 'tambah') {
  if (isset($_POST['tambah'])) {
    mysql_query("INSERT INTO rb_ruangan VALUES('$_POST[a]', '$_POST[b]', '$_POST[c]', '$_POST[d]', '$_POST[e]', '$_POST[f]', '$_POST[g]')");
    echo "<script>document.location='index.php?view=ruangan';</script>";
  }

  echo "<div class='container'>
            <div class='row'>
                <div class='col-md-12'>
                    <div class='card mt-4'>
                        <div class='card-header'>
                            <h3 class='card-title'>Tambah Data Ruangan</h3>
                        </div>
                        <div class='card-body'>
                            <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
                                <div class='form-group'>
                                    <label for='kode_ruangan'>Kode Ruangan</label>
                                    <input type='text' class='form-control' name='a'>
                                </div>
                                <div class='form-group'>
                                    <label for='nama_gedung'>Nama Gedung</label>
                                    <select class='form-control' name='b'>
                                        <option value='0' selected>- Pilih Gedung -</option>";
  $wali = mysql_query("SELECT * FROM rb_gedung");
  while ($a = mysql_fetch_array($wali)) {
    echo "<option value='$a[kode_gedung]'>$a[nama_gedung]</option>";
  }
  echo "</select>
                                </div>
                                <div class='form-group'>
                                    <label for='nama_ruangan'>Nama Ruangan</label>
                                    <input type='text' class='form-control' name='c'>
                                </div>
                                <div class='form-group'>
                                    <label for='kapasitas_belajar'>Kapasitas Belajar</label>
                                    <input type='text' class='form-control' name='d'>
                                </div>
                                <div class='form-group'>
                                    <label for='kapasitas_ujian'>Kapasitas Ujian</label>
                                    <input type='text' class='form-control' name='e'>
                                </div>
                                <div class='form-group'>
                                    <label for='keterangan'>Keterangan</label>
                                    <input type='text' class='form-control' name='f'>
                                </div>
                                <div class='form-group'>
                                    <label>Aktif</label><br>
                                    <input type='radio' name='g' value='Ya' checked> Ya
                                    <input type='radio' name='g' value='Tidak'> Tidak
                                </div>
                        </div>
                        <div class='card-footer'>
                            <button type='submit' name='tambah' class='btn btn-info'>Tambah</button>
                            <a href='index.php?view=ruangan' class='btn btn-default float-right'>Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>";
}
?>