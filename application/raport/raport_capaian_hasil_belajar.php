<?php 
if ($_GET[act]==''){ 
    if (isset($_POST[simpan])){
        $juml = mysql_num_rows(mysql_query("SELECT * FROM rb_siswa where kode_kelas='$_GET[kelas]'"));
        for ($ia=1; $ia<=$juml; $ia++){
          $a   = $_POST['a'.$ia];
          $b   = $_POST['b'.$ia];
          $c   = $_POST['c'.$ia];
          $d   = $_POST['d'.$ia];
          $nisn   = $_POST['nisn'.$ia];
          if ($a != '' OR $b != '' OR $c != '' OR $d != ''){
            $cek = mysql_num_rows(mysql_query("SELECT * FROM rb_nilai_sikap_semester where id_tahun_akademik='$_POST[tahun]' AND nisn='$nisn' AND kode_kelas='$_POST[kelas]'"));
            if ($cek >= '1'){
              mysql_query("UPDATE rb_nilai_sikap_semester SET spiritual_predikat='$a', spiritual_deskripsi='$b', sosial_predikat='$c', sosial_deskripsi='$d' where id_tahun_akademik='$_POST[tahun]' AND nisn='$nisn' AND kode_kelas='$_POST[kelas]'");
            }else{
              mysql_query("INSERT INTO rb_nilai_sikap_semester VALUES('','$_POST[tahun]','$nisn','$_POST[kelas]','$a','$b','$c','$d','$_SESSION[id]','".date('Y-m-d H:i:s')."')");
            }
          } 
        }
        echo "<script>document.location='index.php?view=capaianhasilbelajar&tahun=".$_POST[tahun]."&kelas=".$_POST[kelas]."';</script>";
    }
?> 
            <div class="col-xs-12">  
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Input Capaian Hasil Belajar </h3>
                  <form style='margin-right:5px; margin-top:0px' class='pull-right' action='' method='GET'>
                    <input type="hidden" name='view' value='capaianhasilbelajar'>
                    <select name='tahun' style='padding:4px'>
                        <?php 
                            echo "<option value=''>- Pilih Tahun Akademik -</option>";
                            $tahun = mysql_query("SELECT * FROM rb_tahun_akademik");
                            while ($k = mysql_fetch_array($tahun)){
                              if ($_GET[tahun]==$k[id_tahun_akademik]){
                                echo "<option value='$k[id_tahun_akademik]' selected>$k[nama_tahun]</option>";
                              }else{
                                echo "<option value='$k[id_tahun_akademik]'>$k[nama_tahun]</option>";
                              }
                            }
                        ?>
                    </select>
                    <select name='kelas' style='padding:4px'>
                        <?php 
                            echo "<option value=''>- Filter Kelas -</option>";
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
                    <input type="submit" style='margin-top:-4px' class='btn btn-info btn-sm' value='Lihat'>
                  </form>
                </div><!-- /.box-header -->
                <div class="box-body">
                <form action='' method='POST'>
                <input type="hidden" name='tahun' value='<?php echo $_GET[tahun]; ?>'>
                <input type="hidden" name='kelas' value='<?php echo $_GET[kelas]; ?>'>
                <div class="table-responsive"> <!-- Tambahkan div ini untuk responsif -->
                  <?php 
                    echo "<table id='example' class='table table-bordered table-striped'>
                      <thead>
                        <tr><th rowspan='2'>No</th>
                          <th rowspan='2'>NISN</th>
                          <th rowspan='2'>Nama Siswa</th>
                          <th colspan='1'><center>Sikap Spiritual</center></th>
                          <th colspan='1'><center>Sikap Sosial</center></th>
                        </tr>
                        <tr>
                            <th hidden><center>Predikat</center></th>
                            <th><center>Deskripsi</center></th>
                            <th hidden><center>Predikat</center></th>
                            <th><center>Deskripsi</center></th>
                        </tr>
                      </thead>
                      <tbody>";

                  if ($_GET[kelas] != '' AND $_GET[tahun] != ''){
                    $tampil = mysql_query("SELECT * FROM rb_siswa a LEFT JOIN rb_kelas b ON a.kode_kelas=b.kode_kelas 
                                              LEFT JOIN rb_jenis_kelamin c ON a.id_jenis_kelamin=c.id_jenis_kelamin 
                                                LEFT JOIN rb_jurusan d ON b.kode_jurusan=d.kode_jurusan 
                                                  where a.kode_kelas='$_GET[kelas]' ORDER BY a.id_siswa");
                  }
                    $no = 1;
                    while($r=mysql_fetch_array($tampil)){
                      $n = mysql_fetch_array(mysql_query("SELECT * FROM rb_nilai_sikap_semester where id_tahun_akademik='$_GET[tahun]' AND nisn='$r[nisn]' AND kode_kelas='$_GET[kelas]'"));
                    echo "<tr><td>$no</td>
                              <td>$r[nisn]</td>
                              <td>$r[nama]</td>
                              <input type='hidden' name='nisn".$no."' value='$r[nisn]'>
                              <td hidden><center><input type='text' name='a".$no."' value='$n[spiritual_predikat]' style='width:70px; text-align:center; padding:0px; color:blue'></center></td>
                              <td><textarea name='b".$no."' class='form-control' style='width:100%; color:blue' placeholder='Tuliskan Deskripsi...'>$n[spiritual_deskripsi]</textarea></td>
                              <td hidden><center><input type='text' name='c".$no."' value='$n[sosial_predikat]' style='width:100%; text-align:center; padding:0px; color:blue'></center></td>
                              <td><textarea name='d".$no."' class='form-control' style='width:100%; color:blue' placeholder='Tuliskan Deskripsi...'>$n[sosial_deskripsi]</textarea></td>
                            </tr>";
                      $no++;
                      }
                  ?>
                    </tbody>
                  </table>
                </div><!-- /.box-body -->
                <?php 
                    if ($_GET[kelas] == '' AND $_GET[tahun] == ''){
                        echo "<center style='padding:60px; color:red'>Silahkan Memilih Tahun akademik dan Kelas Terlebih dahulu...</center>";
                    }
                ?>
                <div style='clear:both'></div>
              <div class='box-footer'>
                  <button type='submit' name='simpan' class='btn btn-info'>Simpan</button>
                  <button type='reset' class='btn btn-default pull-right'>Cancel</button>
              </div>
              </div><!-- /.box -->
              
              </form>
            </div>
<?php }  ?>

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