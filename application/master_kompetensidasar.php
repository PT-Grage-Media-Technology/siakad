<?php if ($_GET[act]==''){ ?>
            <div class="col-xs-12">  
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Kompetensi Dasar</h3>
                  <form style='margin-right:5px; margin-top:0px' class='pull-right' action='' method='GET'>
                    <input type="hidden" name='view' value='kompetensidasar'>
                    <select name='kelas' style='padding:4px'>
                        <?php 
                            echo "<option value=''>- Pilih Kelas -</option>";
                            $kelas = mysql_query("SELECT * FROM rb_kelas");
                            while ($k = mysql_fetch_array($kelas)){
                              if ($_GET[kelas]==$k[kode_kelas]){
                                echo "<option value='$k[kode_kelas]' selected>$k[kode_kelas] - $k[nama_kelas]</option>";
                              }else{
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
                        <?php
                        if($_SESSION[level]!='kepala'){ ?>
                        <th>Action</th>
                        <?php } ?>
                      </tr>
                    </thead>
                    <tbody>
                  <?php
                    if (isset($_GET[kelas])){
                      $tampil = mysql_query("SELECT a.*, e.nama_kelas, b.namamatapelajaran, b.kode_kurikulum, b.kode_pelajaran, c.nama_guru, d.nama_ruangan FROM rb_jadwal_pelajaran a 
                                            JOIN rb_mata_pelajaran b ON a.kode_pelajaran=b.kode_pelajaran
                                              JOIN rb_guru c ON a.nip=c.nip 
                                                JOIN rb_ruangan d ON a.kode_ruangan=d.kode_ruangan
                                                  JOIN rb_kelas e ON a.kode_kelas=e.kode_kelas 
                                                  where a.kode_kelas='$_GET[kelas]' AND
                                                      b.kode_kurikulum='$kurikulum[kode_kurikulum]' ORDER BY a.hari DESC");
                    
                    }
                    $no = 1;
                    while($r=mysql_fetch_array($tampil)){
                    echo "<tr><td>$no</td>
                              <td>$r[namamatapelajaran]</td>
                              <td>$r[nama_kelas]</td>
                              <td>$r[nama_guru]</td>
                              <td>$r[hari]</td>
                              <td>$r[jam_mulai]</td>
                              <td>$r[jam_selesai]</td>
                              <td>$r[nama_ruangan]</td>";
                              if($_SESSION[level]!='kepala'){
                                echo "<td style='width:80px !important'><center>
                                        <a class='btn btn-success btn-xs' title='Lihat Kompetensi Dasar' href='index.php?view=kompetensidasar&act=lihat&id=$r[kodejdwl]'><span class='glyphicon glyphicon-search'></span> Lihat Indikator</a>
                                      </center></td>";
                              }
                            echo "</tr>";
                      $no++;
                      }

                      if (isset($_GET[hapus])){
                        mysql_query("DELETE FROM rb_jadwal_pelajaran where kodejdwl='$_GET[hapus]'");
                        echo "<script>document.location='index.php?view=jadwalpelajaran';</script>";
                      }
                  ?>
                    <tbody>
                  </table>
                  </div>
                </div><!-- /.box-body -->
                <?php 
                    if ($_GET[kelas] == ''){
                        echo "<center style='padding:60px; color:red'>Silahkan Memilih Kelas Terlebih dahulu...</center>";
                    }
                ?>
                </div>
            </div>

<?php 
}elseif($_GET[act]=='lihat'){
    $d = mysql_fetch_array(mysql_query("SELECT a.kode_kelas, b.nama_kelas, c.namamatapelajaran, d.nama_guru FROM `rb_jadwal_pelajaran` a JOIN rb_kelas b ON a.kode_kelas=b.kode_kelas JOIN rb_mata_pelajaran c ON a.kode_pelajaran=c.kode_pelajaran JOIN rb_guru d ON a.nip=d.nip where a.kodejdwl='$_GET[id]'"));
            echo "<div class='col-xs-12'>  
              <div class='box'>
                <div class='box-header'>
                  <h3 class='box-title'>Kompetensi Dasar</h3>";
                  if($_SESSION[level]!='kepala'){
                      echo "<a class='pull-right btn btn-primary btn-sm' href='index.php?view=kompetensidasar&act=tambah&jdwl=$_GET[id]'>Tambahkan Kompetensi Dasar</a>";
                  }
                echo "</div>
                <div class='box-body'>
                  <div class='col-md-12'>
                  <table class='table table-condensed table-hover'>
                      <tbody>
                        <tr><th width='120px' scope='row'>Nama Kelas</th> <td>$d[nama_kelas]</td></tr>
                        <tr><th scope='row'>Nama Guru</th>           <td>$d[nama_guru]</td></tr>
                        <tr><th scope='row'>Mata Pelajaran</th>           <td>$d[namamatapelajaran]</td></tr>
                      </tbody>
                  </table>
                  </div>

                  <table id='example' class='table table-bordered table-striped'>
                    <thead>
                      <tr>
                        <th style='width:20px'>No</th>
                        <th>Ranah</th>
                        <th>Indikator</th>";
                        if($_SESSION[level]!='kepala'){
                            echo "<th>Action</th>";
                        }
                      echo "</tr>
                    </thead>
                    <tbody>";
                      $tampil = mysql_query("SELECT * FROM rb_kompetensi_dasar z JOIN rb_jadwal_pelajaran a ON z.kodejdwl=a.kodejdwl JOIN rb_kelas b ON a.kode_kelas=b.kode_kelas JOIN rb_mata_pelajaran c ON a.kode_pelajaran=c.kode_pelajaran where a.kodejdwl='$_GET[id]' ORDER BY z.id_kompetensi_dasar DESC");
                    $no = 1;
                    while($r=mysql_fetch_array($tampil)){
                    echo "<tr><td>$no</td>
                              <td>$r[ranah]</td>
                              <td>$r[kompetensi_dasar]</td>";
                              if($_SESSION[level]!='kepala'){
                                echo "<td style='width:80px !important'><center>
                                        <a class='btn btn-success btn-xs' title='Edit Data' href='index.php?view=kompetensidasar&act=edit&id=$r[id_kompetensi_dasar]&jdwl=$_GET[id]'><span class='glyphicon glyphicon-edit'></span></a>
                                        <a class='btn btn-danger btn-xs' title='Delete Data' href='index.php?view=kompetensidasar&hapus=$r[id_kompetensi_dasar]&jdwl=$_GET[id]'><span class='glyphicon glyphicon-remove'></span></a>
                                      </center></td>";
                              }
                            echo "</tr>";
                      $no++;
                      }

                      if (isset($_GET[hapus])){
                        mysql_query("DELETE FROM rb_kompetensi_dasar where id_kompetensi_dasar='$_GET[hapus]'");
                        echo "<script>document.location='index.php?view=kompetensidasar&act=lihat&id=$_GET[jdwl]';</script>";
                      }

                    echo "<tbody>
                  </table>
                </div>
                </div>
            </div>";

}elseif($_GET[act]=='tambah'){
    if (isset($_POST[tambah])){
        mysql_query("INSERT INTO rb_kompetensi_dasar VALUES('','$_POST[jdwl]','$_POST[e]','$_POST[f]','".date('Y-m-d H:i:s')."')");
        echo "<script>document.location='index.php?view=kompetensidasar&act=lihat&id=$_POST[jdwl]';</script>";
    }

    $e = mysql_fetch_array(mysql_query("SELECT * FROM rb_jadwal_pelajaran where kodejdwl='$_GET[jdwl]'"));

    echo "<div class='col-md-12'>
              <div class='box box-info'>
                <div class='box-header with-border'>
                  <h3 class='box-title'>Tambah Kompetensi Dasar</h3>
                </div>
              <div class='box-body'>
              <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
                <div class='col-md-12'>
                  <table class='table table-condensed table-bordered'>
                  <tbody>
                  <input type='hidden' name='jdwl' value='$_GET[jdwl]'>
                    <tr><th width='140px' scope='row'>Kelas</th>   <td><select class='form-control' name='b'>"; 
                                                $kelas = mysql_query("SELECT * FROM rb_kelas");
                                                while($a = mysql_fetch_array($kelas)){
                                                  if ($e[kode_kelas]==$a[kode_kelas]){
                                                    echo "<option value='$a[kode_kelas]' selected>$a[nama_kelas]</option>";
                                                  }
                                                }
                                                echo "</select>
                    </td></tr>
                    <tr><th scope='row'>Mata Pelajaran</th>   <td><select class='form-control' name='c'>"; 
                                                $mapel = mysql_query("SELECT * FROM rb_mata_pelajaran");
                                                while($a = mysql_fetch_array($mapel)){
                                                  if ($e[kode_pelajaran]==$a[kode_pelajaran]){
                                                    echo "<option value='$a[kode_pelajaran]' selected>$a[namamatapelajaran]</option>";
                                                  }
                                                }
                                                echo "</select>
                    </td></tr>
                   
                    <tr><th scope='row'>Ranah</th>   <td><select class='form-control' name='e'> 
                                                              <option value='0' selected>- Pilih -</option>
                                                              <option value='pengetahuan'>Pengetahuan</option>
                                                              <option value='keterampilan'>Keterampilan</option>
                                                              <option value='sikap'>Sikap</option>
                                                            </select>
                    </td></tr>
                    <tr><th scope='row'>Kompetensi Dasar</th>  <td><input type='text' class='form-control' name='f'></td></tr>
                    
                    </td></tr>
                  </tbody>
                  </table>
                </div>
              </div>
              <div class='box-footer'>
                    <button type='submit' name='tambah' class='btn btn-info'>Tambahkan</button>
                    <a href='index.php?view=kompetensidasar'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>
                    
                  </div>
              </form>
            </div>";

}elseif($_GET[act]=='edit'){
    if (isset($_POST[update])){
        mysql_query("UPDATE rb_kompetensi_dasar SET ranah = '$_POST[e]',
                                                    kompetensi_dasar = '$_POST[f]' where id_kompetensi_dasar='$_POST[id]'");
        echo "<script>document.location='index.php?view=kompetensidasar&act=lihat&id=$_POST[jdwl]';</script>";
    }
    $e = mysql_fetch_array(mysql_query("SELECT a.*, b.kode_pelajaran, b.kode_kelas FROM rb_kompetensi_dasar a JOIN rb_jadwal_pelajaran b ON a.kodejdwl=b.kodejdwl where a.id_kompetensi_dasar='$_GET[id]'"));
    if ($e[semester]=='1'){ $status = 'Ganjil'; }else{ $status = 'Genap'; }
    echo "<div class='col-md-12'>
              <div class='box box-info'>
                <div class='box-header with-border'>
                  <h3 class='box-title'>Edit Data Kompetensi Dasar</h3>
                </div>
              <div class='box-body'>
              <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
                <div class='col-md-12'>
                  <table class='table table-condensed table-bordered'>
                  <tbody>
                  <input type='hidden' name='jdwl' value='$_GET[jdwl]'>
                  <input type='hidden' name='id' value='$_GET[id]'>
                    <tr><th width='140px' scope='row'>Kelas</th>   <td><select class='form-control' name='b'>"; 
                                                $kelas = mysql_query("SELECT * FROM rb_kelas");
                                                while($a = mysql_fetch_array($kelas)){
                                                  if ($e[kode_kelas]==$a[kode_kelas]){
                                                    echo "<option value='$a[kode_kelas]' selected>$a[nama_kelas]</option>";
                                                  }
                                                }
                                                echo "</select>
                    </td></tr>
                    <tr><th scope='row'>Mata Pelajaran</th>   <td><select class='form-control' name='c'>"; 
                                                $mapel = mysql_query("SELECT * FROM rb_mata_pelajaran");
                                                while($a = mysql_fetch_array($mapel)){
                                                  if ($e[kode_pelajaran]==$a[kode_pelajaran]){
                                                    echo "<option value='$a[kode_pelajaran]' selected>$a[namamatapelajaran]</option>";
                                                  }
                                                }
                                                echo "</select>
                    </td></tr>
                   
                    <tr><th scope='row'>Ranah</th>   <td><select style='text-transform:capitalize' class='form-control' name='e'> 
                                                              <option value='$e[ranah]' selected>$e[ranah]</option>
                                                              <option value='pengetahuan'>Pengetahuan</option>
                                                              <option value='keterampilan'>Keterampilan</option>
                                                              <option value='sikap'>Sikap</option>
                                                            </select>
                    </td></tr>
                    <tr><th scope='row'>Kompetensi Dasar</th>  <td><input type='text' class='form-control' value='$e[kompetensi_dasar]' name='f'></td></tr>
                    
                    </td></tr>
                  </tbody>
                  </table>
                </div>
              </div>
              <div class='box-footer'>
                    <button type='submit' name='update' class='btn btn-info'>Update</button>
                    <a href='index.php?view=kompetensidasar'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>
                    
                  </div>
              </form>
            </div>";
}
?>

<style>
  .table-responsive {
    overflow-x: auto; /* Hanya aktifkan scroll horizontal jika diperlukan */
}

@media (min-width: 768px) {
    .table-responsive {
        overflow-x: visible; /* Nonaktifkan scroll horizontal di desktop */
    }
}
</style>