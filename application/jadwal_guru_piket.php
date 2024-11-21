<?php if ($_GET[act]==''){ ?>
            <div class="col-xs-12">  
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Jadwal Guru Piket</h3>
                  <a class='btn btn-primary pull-right' href='index.php?view=jadwalgurupiket&act=tambah' title='Tambah Jadwal'>Tambah Jadwal</a>
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
                   
                      $tampil = mysql_query("SELECT * FROM rb_jadwal_guru_piket a JOIN rb_guru b ON a.nip=b.nip");
                    
                   
                    $no = 1;
                    while($r=mysql_fetch_array($tampil)){
                    echo "<tr><td>$no</td>
                              <td>$r[nip]</td>
                              <td>$r[hari]</td>
                              <td>$r[nama_guru]</td>
                              <td>$r[tanggal]</td>";
                                echo "<td style='width:80px !important'><center>
                                        <a class='btn btn-success btn-xs' title='Lihat Journal' href='index.php?view=jadwalgurupiket&act=edit&nip=$r[nip]'><span class='glyphicon glyphicon-search'></span> Edit</a>
                                        <a class='btn btn-danger btn-xs' title='Hapus Jadwal' href='index.php?view=jadwalgurupiket&act=delete&nip=$r[nip]' onclick=\"return confirm('Apakah Anda yakin ingin menghapus jadwal ini?')\"><span class='glyphicon glyphicon-trash'></span> Delete</a>
                                      </center></td>";
                            echo "</tr>";
                      $no++;
                      }
                  ?>
                    <tbody>
                  </table>
                  </div>
                </div><!-- /.box-body -->

                </div>
            </div>
            <?php 
}elseif($_GET[act]=='tambah'){
    if (isset($_POST[tambah])) {
        $tanggalInput = date('Y-m-d H:i:s'); // Format sesuai dengan format yang diinginkan di database
        mysql_query("INSERT INTO rb_jadwal_guru_piket VALUES('','$_POST[nip]','$_POST[hari]','$_POST[tanggal]','$tanggalInput')");
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
                    <tr><th scope='row'>Tanggal</th>  <td><input type='text' style='border-radius:0px; padding-left:12px' class='datepicker form-control' value='".date('Y-m-d')."' name='tanggal' data-date-format='yyyy-mm-dd'></td></tr>
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
}
elseif($_GET[act]=='edit'){
    if (isset($_POST[edit])) { // Mengubah 'tambah' menjadi 'edit'
        $tanggalInput = date('Y-m-d H:i:s'); // Format sesuai dengan format yang diinginkan di database
        mysql_query("UPDATE rb_jadwal_guru_piket SET nip='$_POST[nip]', hari='$_POST[hari]', tanggal='$_POST[tanggal]', updated_at='$tanggalInput' WHERE nip='$_POST[nip]'"); // Mengubah query untuk update
        echo "<script>document.location='index.php?view=jadwalgurupiket';</script>";
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
                      <tr><th scope='row'>Tanggal</th>  <td><input type='text' style='border-radius:0px; padding-left:12px' class='datepicker form-control' value='".date('d-m-Y', strtotime($data['tanggal']))."' name='tanggal' data-date-format='dd-mm-yyyy'></td></tr> <!-- Menampilkan tanggal yang sudah ada -->
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
}
elseif($_GET[act]=='delete'){ // Menambahkan logika untuk menghapus
    $nip = $_GET['nip']; // Mengambil nip dari GET
    mysql_query("DELETE FROM rb_jadwal_guru_piket WHERE nip='$nip'"); // Menghapus data berdasarkan nip
    echo "<script>document.location='index.php?view=jadwalgurupiket';</script>"; // Redirect setelah penghapusan
}