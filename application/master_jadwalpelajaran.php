<?php if ($_GET[act] == '') { ?>
  <div class="col-xs-12">
    <div class="box">
      <div class="box-header">
        <h3 class="box-title">
          <?php
          // Ambil tahun akademik yang terbaru
          $tahun = mysql_query("SELECT * FROM rb_tahun_akademik ORDER BY id_tahun_akademik DESC");
          $tahun_terbaru = mysql_fetch_array($tahun); // Ambil tahun terbaru
          mysql_data_seek($tahun, 0); // Kembali ke awal data query untuk loop

          // Jika pengguna belum memilih tahun, gunakan tahun terbaru
          $tahun_dipilih = isset($_GET['tahun']) ? $_GET['tahun'] : $tahun_terbaru['id_tahun_akademik'];

          // Ambil nama tahun akademik yang dipilih
          $nama_tahun_dipilih = '';
          while ($k = mysql_fetch_array($tahun)) {
            if ($tahun_dipilih == $k['id_tahun_akademik']) {
              $nama_tahun_dipilih = $k['nama_tahun']; 
            }
          }
          mysql_data_seek($tahun, 0); // Kembali ke awal untuk loop dropdown

          // Mendapatkan kelas yang dipilih
          $kelas_dipilih = isset($_GET['kelas']) ? $_GET['kelas'] : '';
          ?>
          Jadwal Pelajaran Pada Tahun <?php echo $nama_tahun_dipilih; ?>
        </h3>
        <a class='pull-right btn btn-primary btn-sm' href='index.php?view=jadwalpelajaran&act=tambah'>Tambahkan Jadwal Pelajaran</a>
        <form style='margin-right:5px; margin-top:0px' class='pull-right' action='' method='GET'>
          <input type="hidden" name='view' value='jadwalpelajaran'>

          <!-- Dropdown untuk memilih Tahun Akademik -->
          <select name='tahun' style='padding:4px' onchange='this.form.submit()'>
            <option value=''>- Pilih Tahun Akademik -</option>
            <?php
            mysql_data_seek($tahun, 0); // Kembali ke awal untuk loop dropdown
            while ($k = mysql_fetch_array($tahun)) {
              $selected = ($tahun_dipilih == $k['id_tahun_akademik']) ? 'selected' : '';
              echo "<option value='{$k['id_tahun_akademik']}' $selected>{$k['nama_tahun']}</option>";
            }
            ?>
          </select>

          <!-- Dropdown untuk memilih Kelas -->
          <select name='kelas' style='padding:4px' onchange="this.form.submit()">
            <option value=''>- Pilih Kelas -</option>
            <?php
            $kelas = mysql_query("SELECT * FROM rb_kelas");
            while ($k = mysql_fetch_array($kelas)) {
              $selected = ($kelas_dipilih == $k['kode_kelas']) ? 'selected' : '';
              echo "<option value='$k[kode_kelas]' $selected>$k[nama_kelas]</option>";
            }
            ?>
          </select>
        </form>
      </div><!-- /.box-header -->
      <div class="box-body">
        <div class="table-responsive"> <!-- Added this div for responsiveness -->
          <table id="example" class="table table-bordered table-striped">
            <thead>
              <tr>
                <th style='width:20px'>No</th>
                <th>Jadwal Pelajaran</th>
                <th>Kelas</th>
                <th>Guru</th>
                <th>Hari</th>
                <th>Kktp</th>
                <th>Mulai</th>
                <th>Selesai</th>
                <th>Ruangan</th>
                <?php
                if ($_SESSION['level'] != 'kepala') { ?>
                  <th>Action</th>
                <?php } ?>
              </tr>
            </thead>
            <tbody>
              <?php
              if (isset($_GET['kelas']) and isset($_GET['tahun'])) {
                $tampil = mysql_query("SELECT a.*, e.nama_kelas, b.namamatapelajaran, b.kode_kurikulum, b.kode_pelajaran, c.nama_guru, d.nama_ruangan FROM rb_jadwal_pelajaran a 
                                                    JOIN rb_mata_pelajaran b ON a.kode_pelajaran=b.kode_pelajaran
                                                    JOIN rb_guru c ON a.nip=c.nip 
                                                    JOIN rb_ruangan d ON a.kode_ruangan=d.kode_ruangan
                                                    JOIN rb_kelas e ON a.kode_kelas=e.kode_kelas 
                                                    where a.kode_kelas='$_GET[kelas]' AND 
                                                    a.id_tahun_akademik='$_GET[tahun]' AND 
                                                    b.kode_kurikulum='$kurikulum[kode_kurikulum]' ORDER BY a.hari DESC");
              }
              $no = 1;
              while ($r = mysql_fetch_array($tampil)) {
                echo "<tr><td>$no</td>
                                        <td>$r[namamatapelajaran]</td>
                                        <td>$r[nama_kelas]</td>
                                        <td>$r[nama_guru]</td>
                                        <td>$r[hari]</td>
                                        <td>$r[kktp]</td>
                                        <td>$r[jam_mulai]</td>
                                        <td>$r[jam_selesai]</td>
                                        <td>$r[nama_ruangan]</td>";
                if ($_SESSION['level'] != 'kepala') {
                  echo "<td style='width:70px !important'><center>
                                        <a class='btn btn-success btn-xs' title='Edit Jadwal' href='index.php?view=jadwalpelajaran&act=edit&id=$r[kodejdwl]'><span class='glyphicon glyphicon-edit'></span></a>
                                        <a class='btn btn-danger btn-xs' title='Hapus Jadwal' href='index.php?view=jadwalpelajaran&hapus=$r[kodejdwl]' onclick=\"return confirm('Apakah anda Yakin Data ini Dihapus?')\"><span class='glyphicon glyphicon-remove'></span></a>
                                      </center></td>";
                }
                echo "</tr>";
                $no++;
              }

              if (isset($_GET['hapus'])) {
                mysql_query("DELETE FROM rb_jadwal_pelajaran where kodejdwl='$_GET[hapus]'");
                echo "<script>document.location='index.php?view=jadwalpelajaran';</script>";
              }
              ?>
            <tbody>
          </table>
        </div><!-- /.table-responsive -->
      </div><!-- /.box-body -->
      <?php
      if ($_GET['kelas'] == '' and $_GET['tahun'] == '') {
        echo "<center style='padding:60px; color:red'>Silahkan Memilih Tahun akademik dan Kelas Terlebih dahulu...</center>";
      }
      ?>
    </div>
  </div>


<?php
} elseif ($_GET[act] == 'tambah') {
  if (isset($_POST[tambah])) {
    mysql_query("INSERT INTO rb_jadwal_pelajaran VALUES('','$_POST[a]','$_POST[b]','$_POST[c]','$_POST[d]','$_POST[e]','$_POST[f]','$_POST[g]','$_POST[h]','$_POST[i]','$_POST[j]','$_POST[kktp]','$_POST[k]')");
    echo "<script>document.location='index.php?view=jadwalpelajaran';</script>";
  }

  echo "<div class='col-md-12'>
              <div class='box box-info'>
                <div class='box-header with-border'>
                  <h3 class='box-title'>Tambah Data Jadwal Pelajaran</h3>
                </div>
              <div class='box-body'>
              <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
                <div class='col-md-12'>
                  <table class='table table-condensed table-bordered'>
                  <tbody>
                    <tr><th style='width:120px' scope='row'>Tahun Akademik</th>   <td><select class='form-control' name='a'> 
                                                <option value='0' selected>- Pilih Tahun Akademik -</option>";
  $tahun = mysql_query("SELECT * FROM rb_tahun_akademik");
  while ($a = mysql_fetch_array($tahun)) {
    echo "<option value='$a[id_tahun_akademik]'>$a[nama_tahun]</option>";
  }
  echo "</select>
                    </td></tr>
                    <tr><th scope='row'>Kelas</th>   <td><select class='form-control' name='b'> 
                                                <option value='0' selected>- Pilih Kelas -</option>";
  $kelas = mysql_query("SELECT * FROM rb_kelas");
  while ($a = mysql_fetch_array($kelas)) {
    echo "<option value='$a[kode_kelas]'>$a[nama_kelas]</option>";
  }
  echo "</select>
                    </td></tr>
                    <tr><th scope='row'>Mata Pelajaran</th>   <td><select class='form-control' name='c'> 
                                                <option value='0' selected>- Pilih Mata Pelajaran -</option>";
  $mapel = mysql_query("SELECT * FROM rb_mata_pelajaran");
  while ($a = mysql_fetch_array($mapel)) {
    echo "<option value='$a[kode_pelajaran]'>$a[namamatapelajaran]</option>";
  }
  echo "</select>
                    </td></tr>
                    <tr><th scope='row'>Ruangan</th>   <td><select class='form-control' name='d'> 
                                                <option value='0' selected>- Pilih Ruangan -</option>";
  $ruangan = mysql_query("SELECT * FROM rb_ruangan a JOIN rb_gedung b ON a.kode_gedung=b.kode_gedung");
  while ($a = mysql_fetch_array($ruangan)) {
    echo "<option value='$a[kode_ruangan]'>$a[nama_gedung] - $a[nama_ruangan]</option>";
  }
  echo "</select>
                    </td></tr>
                    <tr><th scope='row'>Guru</th>   <td><select class='form-control' name='e'> 
                                                <option value='0' selected>- Pilih Guru -</option>";
  $guru = mysql_query("SELECT * FROM rb_guru WHERE id_jenis_ptk NOT IN (6, 7) ORDER BY nama_guru ASC");
  while ($a = mysql_fetch_array($guru)) {
    echo "<option value='$a[nip]'>$a[nama_guru]</option>";
  }
  echo "</select>
                    </td></tr>
                    <tr><th scope='row'>Jam Mulai</th>  <td><input type='text' class='form-control' name='h' placeholder='hh:ii:ss' value='" . date('H:i:s') . "'></td></tr>
                    <tr><th scope='row'>Jam Selesai</th><td><input type='text' class='form-control' name='i' placeholder='hh:ii:ss' value='" . date('H:i:s') . "'></td></tr>
                    <tr><th scope='row'>Hari</th>  <td><select class='form-control' name='j'>
                                                <option value='0' selected>- Pilih Hari -</option>
                                                <option value='Senin'>Senin</option>
                                                <option value='Selasa'>Selasa</option>
                                                <option value='Rabu'>Rabu</option>
                                                <option value='Kamis'>Kamis</option>
                                                <option value='Jumat'>Jumat</option>
                                                <option value='Sabtu'>Sabtu</option>
                                                <option value='Minggu'>Minggu</option>
                    </td></tr>
                    <tr style='display:none;'><th scope='row'>KKTP</th><td><input type='text' class='form-control' name='kktp' placeholder='KKTP' value='75'></td></tr>
                    <tr><th scope='row'>Aktif</th>                <td><input type='radio' name='k' value='Ya' checked> Ya
                                                                             <input type='radio' name='k' value='Tidak'> Tidak
                    </td></tr>
                  </tbody>
                  </table>
                </div>
              </div>
              <div class='box-footer'>
                    <button type='submit' name='tambah' class='btn btn-info'>Tambahkan</button>
                    <a href='index.php?view=kelas'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>
                    
                  </div>
              </form>
            </div>";
} elseif ($_GET[act] == 'edit') {
  if (isset($_POST[update])) {
    mysql_query("UPDATE rb_jadwal_pelajaran SET id_tahun_akademik = '$_POST[a]',
                                                    kode_kelas = '$_POST[b]',
                                                    kode_pelajaran = '$_POST[c]',
                                                    kode_ruangan = '$_POST[d]',
                                                    nip = '$_POST[e]',
                                                    paralel = '$_POST[f]',
                                                    jadwal_serial = '$_POST[g]',
                                                    jam_mulai = '$_POST[h]',
                                                    jam_selesai = '$_POST[i]',
                                                    hari = '$_POST[j]',
                                                    aktif = '$_POST[k]' where kodejdwl='$_POST[id]'");
    echo "<script>document.location='index.php?view=jadwalpelajaran';</script>";
  }
  $e = mysql_fetch_array(mysql_query("SELECT * FROM rb_jadwal_pelajaran where kodejdwl='$_GET[id]'"));
  echo "<div class='col-md-12'>
              <div class='box box-info'>
                <div class='box-header with-border'>
                  <h3 class='box-title'>Edit Data Jadwal Pelajaran</h3>
                </div>
              <div class='box-body'>
              <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
                <div class='col-md-12'>
                  <table class='table table-condensed table-bordered'>
                  <tbody>
                  <input type='hidden' name='id' value='$_GET[id]'>
                    <tr><th style='width:120px' scope='row'>Tahun Akademik</th>   <td><select class='form-control' name='a'> 
                                                <option value='0' selected>- Pilih Tahun Akademik -</option>";
  $tahun = mysql_query("SELECT * FROM rb_tahun_akademik");
  while ($a = mysql_fetch_array($tahun)) {
    if ($e[id_tahun_akademik] == $a[id_tahun_akademik]) {
      echo "<option value='$a[id_tahun_akademik]' selected>$a[nama_tahun]</option>";
    } else {
      echo "<option value='$a[id_tahun_akademik]'>$a[nama_tahun]</option>";
    }
  }
  echo "</select>
                    </td></tr>
                    <tr><th scope='row'>Kelas</th>   <td><select class='form-control' name='b'> 
                                                <option value='0' selected>- Pilih Kelas -</option>";
  $kelas = mysql_query("SELECT * FROM rb_kelas");
  while ($a = mysql_fetch_array($kelas)) {
    if ($e[kode_kelas] == $a[kode_kelas]) {
      echo "<option value='$a[kode_kelas]' selected>$a[nama_kelas]</option>";
    } else {
      echo "<option value='$a[kode_kelas]'>$a[nama_kelas]</option>";
    }
  }
  echo "</select>
                    </td></tr>
                    <tr><th scope='row'>Mata Pelajaran</th>   <td><select class='form-control' name='c'> 
                                                <option value='0' selected>- Pilih Mata Pelajaran -</option>";
  $mapel = mysql_query("SELECT * FROM rb_mata_pelajaran");
  while ($a = mysql_fetch_array($mapel)) {
    if ($e[kode_pelajaran] == $a[kode_pelajaran]) {
      echo "<option value='$a[kode_pelajaran]' selected>$a[namamatapelajaran]</option>";
    } else {
      echo "<option value='$a[kode_pelajaran]'>$a[namamatapelajaran]</option>";
    }
  }
  echo "</select>
                    </td></tr>
                    <tr><th scope='row'>Ruangan</th>   <td><select class='form-control' name='d'> 
                                                <option value='0' selected>- Pilih Ruangan -</option>";
  $ruangan = mysql_query("SELECT * FROM rb_ruangan a JOIN rb_gedung b ON a.kode_gedung=b.kode_gedung");
  while ($a = mysql_fetch_array($ruangan)) {
    if ($e[kode_ruangan] == $a[kode_ruangan]) {
      echo "<option value='$a[kode_ruangan]' selected>$a[nama_gedung] - $a[nama_ruangan]</option>";
    } else {
      echo "<option value='$a[kode_ruangan]'>$a[nama_gedung] - $a[nama_ruangan]</option>";
    }
  }
  echo "</select>
                    </td></tr>
                    <tr><th scope='row'>Guru</th>   <td><select class='form-control' name='e'> 
                                                <option value='0' selected>- Pilih Guru -</option>";
  $guru = mysql_query("SELECT * FROM rb_guru WHERE id_jenis_ptk NOT IN (6, 7) ORDER BY nama_guru ASC");
  while ($a = mysql_fetch_array($guru)) {
    if ($e[nip] == $a[nip]) {
      echo "<option value='$a[nip]' selected>$a[nama_guru]</option>";
    } else {
      echo "<option value='$a[nip]'>$a[nama_guru]</option>";
    }
  }
  echo "</select>
                    </td></tr>
                    <tr><th scope='row'>Jam Mulai</th>  <td><input type='text' class='form-control' name='h' placeholder='hh:ii:ss' value='$e[jam_mulai]'></td></tr>
                    <tr><th scope='row'>Jam Selesai</th><td><input type='text' class='form-control' name='i' placeholder='hh:ii:ss' value='$e[jam_selesai]'></td></tr>
                    <tr><th scope='row'>Hari</th>  <td><select class='form-control' name='j'>
                                                <option value='$e[hari]' selected>$e[hari]</option>
                                                <option value='Senin'>Senin</option>
                                                <option value='Selasa'>Selasa</option>
                                                <option value='Rabu'>Rabu</option>
                                                <option value='Kamis'>Kamis</option>
                                                <option value='Jumat'>Jumat</option>
                                                <option value='Sabtu'>Sabtu</option>
                                                <option value='Minggu'>Minggu</option>
                    </td></tr>
                    <tr><th scope='row'>Aktif</th>                <td>";
  if ($e[aktif] == 'Ya') {
    echo "<input type='radio' name='k' value='Ya' checked> Ya
                                                                             <input type='radio' name='k' value='Tidak'> Tidak";
  } else {
    echo "<input type='radio' name='k' value='Ya'> Ya
                                                                             <input type='radio' name='k' value='Tidak' checked> Tidak";
  }
  echo "</td></tr>
                  </tbody>
                  </table>
                </div>
              </div>
              <div class='box-footer'>
                    <button type='submit' name='update' class='btn btn-info'>Update</button>
                    <a href='index.php?view=kelas'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>
                    
                  </div>
              </form>
            </div>";
}
?>