<?php if ($_GET[act] == '') { ?>
  <div class="col-xs-12">
    <div class="box">
      <div class="box-header">
      <a class='btn btn-primary pull-right' href='index.php?view=jadwalgurupiket&act=tambah'
      title='Tambah Jadwal'>Tambah Jadwal</a>
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
      
        // Mendapatkan bulan dan tanggal yang dipilih atau default ke bulan dan tanggal hari ini
        $bulan_dipilih = isset($_GET['bulan']) ? $_GET['bulan'] : date('n');  // Default ke bulan sekarang
        $tanggal_dipilih = isset($_GET['tanggal']) ? $_GET['tanggal'] : date('j');  // Default ke tanggal hari ini
      
        ?>

        <!-- Menampilkan form dan h3 -->
        <h3 class="box-title">
          Jadwal Guru Piket
        </h3>

        <form style='margin-right:5px; margin-top:0px' class='pull-right' action='' method='GET'>
          <!-- Tambahkan hidden input untuk menyimpan parameter view -->
          <input type="hidden" name="view" value="jadwalgurupiket">

          <!-- Dropdown untuk memilih Tahun Akademik -->
          <select name='tahun' style='padding:4px' onchange='this.form.submit()'>
            <option value=''>- Pilih Tahun Akademik -</option>
            <?php
            while ($k = mysql_fetch_array($tahun)) {
              $selected = ($tahun_dipilih == $k['id_tahun_akademik']) ? 'selected' : '';
              echo "<option value='{$k['id_tahun_akademik']}' $selected>{$k['nama_tahun']}</option>";
            }
            ?>
          </select>

          <!-- Dropdown untuk memilih Bulan -->
          <select name='bulan' style='padding:4px' onchange='this.form.submit()'>
            <option value=''>- Pilih Bulan -</option>
            <option value='1' <?php echo ($bulan_dipilih == 1) ? 'selected' : ''; ?>>Januari</option>
            <option value='2' <?php echo ($bulan_dipilih == 2) ? 'selected' : ''; ?>>Februari</option>
            <option value='3' <?php echo ($bulan_dipilih == 3) ? 'selected' : ''; ?>>Maret</option>
            <option value='4' <?php echo ($bulan_dipilih == 4) ? 'selected' : ''; ?>>April</option>
            <option value='5' <?php echo ($bulan_dipilih == 5) ? 'selected' : ''; ?>>Mei</option>
            <option value='6' <?php echo ($bulan_dipilih == 6) ? 'selected' : ''; ?>>Juni</option>
            <option value='7' <?php echo ($bulan_dipilih == 7) ? 'selected' : ''; ?>>Juli</option>
            <option value='8' <?php echo ($bulan_dipilih == 8) ? 'selected' : ''; ?>>Agustus</option>
            <option value='9' <?php echo ($bulan_dipilih == 9) ? 'selected' : ''; ?>>September</option>
            <option value='10' <?php echo ($bulan_dipilih == 10) ? 'selected' : ''; ?>>Oktober</option>
            <option value='11' <?php echo ($bulan_dipilih == 11) ? 'selected' : ''; ?>>November</option>
            <option value='12' <?php echo ($bulan_dipilih == 12) ? 'selected' : ''; ?>>Desember</option>
          </select>

          <!-- Dropdown untuk memilih Tanggal -->
          <select name='tanggal' style='padding:4px' onchange='this.form.submit()'>
            <option value=''>- Pilih Tanggal -</option>
            <?php
            for ($i = 1; $i <= 31; $i++) {
              $selected = ($tanggal_dipilih == $i) ? 'selected' : '';
              echo "<option value='$i' $selected>$i</option>";
            }
            ?>
          </select>
        </form>
        <h3 class="box-title">Jadwal Guru Piket</h3>
        
      </div><!-- /.box-header -->
      <div class="box-body">
        <div class="table-responsive">

          <table id="example" class="table table-bordered table-striped">
            <thead>
              <tr>
                <th style='width:20px'>No</th>
                <th>Nip</th>
                <th>Hari</th>
                <th>Guru</th>
                <th>Tanggal</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php

              $tampil = mysql_query("SELECT * FROM rb_jadwal_guru_piket a JOIN rb_guru b ON a.nip=b.nip   WHERE DAY(a.tanggal) = '$tanggal_dipilih' 
              AND MONTH(a.tanggal) = '$bulan_dipilih'");


              $no = 1;
              if (mysql_num_rows($tampil) > 0) { // Memeriksa apakah ada data
                while ($r = mysql_fetch_array($tampil)) {
                  echo "<tr><td>$no</td>
                                <td>$r[nip]</td>
                                <td>$r[hari]</td>
                                <td>$r[nama_guru]</td>
                                <td>" . tgl_indo($r['tanggal']) . "</td>";
                  echo "<td style='width:80px !important'><center>
                                          <a class='btn btn-success btn-xs' title='Lihat Journal' href='index.php?view=jadwalgurupiket&act=edit&nip=$r[nip]'><span class='glyphicon glyphicon-pencil'></span> Edit</a>
                                          <a class='btn btn-danger btn-xs' title='Hapus Jadwal' href='index.php?view=jadwalgurupiket&act=delete&nip=$r[nip]' onclick=\"return confirm('Apakah Anda yakin ingin menghapus jadwal ini?')\"><span class='glyphicon glyphicon-trash'></span> Delete</a>
                                        </center></td>";
                  echo "</tr>";
                  $no++;
                }
              } else {
                echo "<tr><td colspan='6' style='text-align:center;'>Tidak ada data</td></tr>"; // Menampilkan pesan jika tidak ada data
              }
              ?>
            <tbody>
          </table>
        </div>
      </div><!-- /.box-body -->

    </div>
  </div>
  <?php
} elseif ($_GET[act] == 'tambah') {
  if (isset($_POST[tambah])) {
    $tanggal = tgl_simpan($_POST[tanggal]);
    $tanggalInput = date('Y-m-d H:i:s'); // Format sesuai dengan format yang diinginkan di database
    mysql_query("INSERT INTO rb_jadwal_guru_piket VALUES('','$_POST[nip]','$_POST[hari]','$tanggal','$tanggalInput')");
    echo "<script>document.location='index.php?view=jadwalgurupiket';</script>";
  }

  echo "<div class='col-md-12'>
              <div class='box box-info'>
                <div class='box-header with-border'>
                  <h3 class='box-title'>Tambah Jadwal</h3>
                </div>
              <div class='box-body'>
              <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
                <div class='col-md-12'>
                  <table class='table table-condensed table-bordered'>
                  <tbody>
                  <input type='hidden' name='jdwl' value='$_GET[jdwl]'>
                    <tr><th width='140px' scope='row'>Guru</th>   
                   <td><select class='form-control' name='nip'> 
                                    <option value='0' selected>- Pilih Guru -</option>";
  $guru = mysql_query("SELECT * FROM rb_guru WHERE id_jenis_ptk != 6 ORDER BY nama_guru ASC");
  while ($a = mysql_fetch_array($guru)) {
    echo "<option value='$a[nip]'>$a[nama_guru]</option>";
  }
  echo "</select>
                    </td></tr>
                   
                 </select>
                      </td></tr>
                     
                      <tr>
                        <th scope='row'>Hari</th>
                        <td>
                            <select class='form-control' name='hari'>
                                <option value='Senin'" . ($hari_ini == 'Senin' ? ' selected' : '') . ">Senin</option>
                                <option value='Selasa'" . ($hari_ini == 'Selasa' ? ' selected' : '') . ">Selasa</option>
                                <option value='Rabu'" . ($hari_ini == 'Rabu' ? ' selected' : '') . ">Rabu</option>
                                <option value='Kamis'" . ($hari_ini == 'Kamis' ? ' selected' : '') . ">Kamis</option>
                                <option value='Jumat'" . ($hari_ini == 'Jumat' ? ' selected' : '') . ">Jumat</option>
                                <option value='Sabtu'" . ($hari_ini == 'Sabtu' ? ' selected' : '') . ">Sabtu</option>
                            </select>
                        </td>
                      </tr>
                    <tr><th scope='row'>Tanggal</th>  <td><input type='text' style='border-radius:0px; padding-left:12px' class='datepicker form-control' value='" . date('d-m-Y') . "' name='tanggal' data-date-format='dd-mm-yyyy'></td></tr>
                  </tbody>
                  </table>
                </div>
              </div>
              <div class='box-footer'>
                    <button type='submit' name='tambah' class='btn btn-info'>Tambahkan</button>
                    <a href='index.php?view=journalguru'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>
                    
                  </div>
              </form>
            </div>";
} elseif ($_GET[act] == 'edit') {
  if (isset($_POST[edit])) { // Mengubah 'tambah' menjadi 'edit'
    $tanggal = tgl_simpan($_POST[tanggal]);
    $tanggalInput = date('Y-m-d H:i:s'); // Format sesuai dengan format yang diinginkan di database
    mysql_query("UPDATE rb_jadwal_guru_piket SET nip='$_POST[nip]', hari='$_POST[hari]', tanggal='$tanggal', updated_at='$tanggalInput' WHERE nip='$_GET[nip]'"); // Mengubah query untuk update
    echo "<script>document.location='index.php?view=jadwalgurupiket';</script>";
    // echo"UPDATE rb_jadwal_guru_piket SET nip='$_POST[nip]', hari='$_POST[hari]', tanggal='$tanggal', waktu_input='$tanggalInput' WHERE nip='$_POST[nip]'";
  }

  $nip = $_GET['nip']; // Ambil nip dari GET
  $query = mysql_query("SELECT * FROM rb_jadwal_guru_piket WHERE nip='$nip'"); // Ambil data berdasarkan nip
  $data = mysql_fetch_array($query); // Ambil hasil query

  echo "<div class='col-md-12'>
                <div class='box box-info'>
                  <div class='box-header with-border'>
                    <h3 class='box-title'>Edit Jadwal</h3>
                  </div>
                <div class='box-body'>
                <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
                  <div class='col-md-12'>
                    <table class='table table-condensed table-bordered'>
                    <tbody>
                    <input type='hidden' name='nip' value='$data[nip]'> <!-- Menampilkan nip yang sedang diedit -->
                      <tr><th width='140px' scope='row'>Guru</th>   
                     <td><select class='form-control' name='nip'> 
                                      <option value='0' selected>- Pilih Guru -</option>";
  $guru = mysql_query("SELECT * FROM rb_guru WHERE id_jenis_ptk != 6 ORDER BY nama_guru ASC");
  while ($a = mysql_fetch_array($guru)) {
    $selected = ($a['nip'] == $data['nip']) ? 'selected' : ''; // Menandai guru yang dipilih
    echo "<option value='$a[nip]' $selected>$a[nama_guru]</option>";
  }
  echo "</select>
                      </td></tr>
                       </select>
                        </td></tr>
                        <tr>
                          <th scope='row'>Hari</th>
                          <td>
                              <select class='form-control' name='hari'>
                                  <option value='Senin'" . ($data['hari'] == 'Senin' ? ' selected' : '') . ">Senin</option>
                                  <option value='Selasa'" . ($data['hari'] == 'Selasa' ? ' selected' : '') . ">Selasa</option>
                                  <option value='Rabu'" . ($data['hari'] == 'Rabu' ? ' selected' : '') . ">Rabu</option>
                                  <option value='Kamis'" . ($data['hari'] == 'Kamis' ? ' selected' : '') . ">Kamis</option>
                                  <option value='Jumat'" . ($data['hari'] == 'Jumat' ? ' selected' : '') . ">Jumat</option>
                                  <option value='Sabtu'" . ($data['hari'] == 'Sabtu' ? ' selected' : '') . ">Sabtu</option>
                              </select> 
                      <tr><th scope='row'>Tanggal</th>  <td><input type='text' style='border-radius:0px; padding-left:12px' class='datepicker form-control' value='" . date('d-m-Y', strtotime($data['tanggal'])) . "' name='tanggal' data-date-format='dd-mm-yyyy'></td></tr> <!-- Menampilkan tanggal yang sudah ada -->
                    </tbody>
                    </table>
                  </div>
                </div>
                <div class='box-footer'>
                      <button type='submit' name='edit' class='btn btn-info'>Simpan Perubahan</button>
                      <a href='index.php?view=journalguru'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>
                      
                    </div>
                </form>
              </div>";
} elseif ($_GET[act] == 'delete') { // Menambahkan logika untuk menghapus
  $nip = $_GET['nip']; // Mengambil nip dari GET
  mysql_query("DELETE FROM rb_jadwal_guru_piket WHERE nip='$nip'"); // Menghapus data berdasarkan nip
  echo "<script>document.location='index.php?view=jadwalgurupiket';</script>"; // Redirect setelah penghapusan
}