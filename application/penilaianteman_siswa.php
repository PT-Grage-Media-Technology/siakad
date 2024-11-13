<?php 
if ($_GET[act]==''){ 
?>
            <div class="col-xs-12">
  <div class="box">
    <form action='' method='POST'>
      <div class="box-header">
        <h3 class="box-title">Semua Data Teman Kelas anda </h3>
      </div><!-- /.box-header -->
      <div class="box-body">
        <div class="table-responsive">
          <table class="table table-bordered table-striped">
            <thead>
              <tr>
                <th style='width:40px'>No</th>
                <th>NIPD</th>
                <th>NISN</th>
                <th>Nama Siswa</th>
                <th>Angkatan</th>
                <th>Jurusan</th>
                <th>Kelas</th>
                <th width='135px'></th>
              </tr>
            </thead>
            <tbody>
              <?php 
                $cs = mysql_fetch_array(mysql_query("SELECT * FROM rb_siswa where nisn='$_SESSION[id]'"));
                $tampil = mysql_query("SELECT * FROM rb_siswa a LEFT JOIN rb_kelas b ON a.kode_kelas=b.kode_kelas 
                                      LEFT JOIN rb_jenis_kelamin c ON a.id_jenis_kelamin=c.id_jenis_kelamin 
                                        LEFT JOIN rb_jurusan d ON b.kode_jurusan=d.kode_jurusan 
                                          where a.kode_kelas='$cs[kode_kelas]' AND a.angkatan='$cs[angkatan]' AND nisn!='$_SESSION[id]' ORDER BY a.id_siswa");
                $no = 1;
                while($r=mysql_fetch_array($tampil)){
                echo "<tr><td>$no</td>
                          <td>$r[nipd]</td>
                          <td>$r[nisn]</td>
                          <td>$r[nama]</td>
                          <td>$r[angkatan]</td>
                          <td>$r[nama_jurusan]</td>
                          <td>$r[nama_kelas]</td>
                          <td align=center><a class='btn btn-success btn-xs' title='Lihat Pertanyaan' href='index.php?view=penilaiantemansiswa&act=pertanyaan&nisn=$r[nisn]'><span class='glyphicon glyphicon-search'></span> Lihat Pertanyaan</a></td>
                      </tr>";
                  $no++;
                  }
              ?>
            </tbody>
          </table>
        </div><!-- /.table-responsive -->
      </div><!-- /.box-body -->
    </form>
  </div><!-- /.box -->
</div>


<?php 
}elseif ($_GET[act]=='pertanyaan'){
    if (isset($_POST['submit'])){
       $jml = mysql_fetch_array(mysql_query("SELECT count(*) as jmlp FROM `rb_pertanyaan_penilaian` where status='teman'"));
       $n = $jml[jmlp];
       for ($i=1; $i<=$n; $i++){
         if (isset($_POST['jawab'.$i])){
           $jawab = $_POST['jawab'.$i];
           $pertanyaan = $_POST['id'.$i];
           $nisn = $_POST['nisn'.$i];
           $kelas = $_POST['kelas'.$i];
            $cek = mysql_fetch_array(mysql_query("SELECT count(*) as tot FROM rb_pertanyaan_penilaian_jawab where nisn='$_SESSION[id]' AND id_pertanyaan_penilaian='$pertanyaan' AND nisn_teman='$nisn' AND status='teman' AND kode_kelas='$kelas'"));
            if ($cek[tot] >= 1){
              mysql_query("UPDATE rb_pertanyaan_penilaian_jawab SET jawaban='$jawab' where id_pertanyaan_penilaian='$pertanyaan' AND nisn='$_SESSION[id]' AND kode_kelas='$kelas'");
            }else{
              mysql_query("INSERT INTO rb_pertanyaan_penilaian_jawab VALUES('','$pertanyaan','$_SESSION[id]','$nisn','$jawab','teman','$kelas','".date('Y-m-d H:i:s')."')");
          }
         }
       }
       echo "<script>window.alert('Sukses Simpan Jawaban Penilaian Teman...');
                window.location='index.php?view=penilaiantemansiswa&act=pertanyaan&nisn=".$_POST[nisnteman]."'</script>";
    }
?> 
            <div class="col-xs-12">  
  <div class="box">
    <form action='' method='POST'>
      <div class="box-header">
        <h3 class="box-title">Data Pertanyaan Penilaian Teman</h3>
      </div><!-- /.box-header -->
      <div class="box-body">
        <?php
            echo "<input type='hidden' value='$_GET[nisn]' name='nisnteman'>";
            $t = mysql_fetch_array(mysql_query("SELECT * FROM rb_siswa where nisn='$_GET[nisn]'"));
            $tt = mysql_fetch_array(mysql_query("SELECT * FROM rb_siswa where nisn='$_SESSION[id]'"));
            echo "<div class='col-md-12'>
                  <table class='table table-condensed table-hover'>
                      <tbody>
                        <tr><th width='120px' scope='row'>NISN Teman</th> <td>$t[nisn]</td></tr>
                        <tr><th scope='row'>Nama Teman</th>           <td>$t[nama]</td></tr>
                      </tbody>
                  </table>
                  </div>";
        ?>
        <div class="table-responsive"> <!-- Membuat tabel dapat di-scroll pada perangkat kecil -->
          <table id="example3" class="table table-bordered table-striped">
            <thead>
              <tr>
                <th style='width:20px'>No</th>
                <th>Pertanyaan</th>
              </tr>
            </thead>
            <tbody>
          <?php 
            $tampil = mysql_query("SELECT * FROM rb_pertanyaan_penilaian where status='teman' ORDER BY id_pertanyaan_penilaian DESC");
            $no = 1;
            while($r=mysql_fetch_array($tampil)){
            $jwb = mysql_fetch_array(mysql_query("SELECT * FROM rb_pertanyaan_penilaian_jawab where nisn='$_SESSION[id]' AND nisn_teman='$_GET[nisn]' AND id_pertanyaan_penilaian='$r[id_pertanyaan_penilaian]' AND status='teman' AND kode_kelas='$tt[kode_kelas]'"));
            echo "<tr><td>$no</td>
                      <td>$r[pertanyaan]</td>
                  </tr>
                  <tr><td></td>
                          <input type='hidden' value='$tt[kode_kelas]' name='kelas".$no."'>
                          <input type='hidden' value='$r[id_pertanyaan_penilaian]' name='id".$no."'>
                          <input type='hidden' value='$_GET[nisn]' name='nisn".$no."'>
                      <td><textarea style='height:60px; width:100%' class='form-control' name='jawab".$no."' placeholder='Tulis Jawaban disini...'>$jwb[jawaban]</textarea></td>
                  </tr>";
              $no++;
            }
          ?>
            </tbody>
          </table>
        </div> <!-- Akhir dari table-responsive -->
        <input type="submit" name='submit' value='Simpan Semua Jawaban' class='pull-left btn btn-primary btn-sm'>
      </div><!-- /.box-body -->
    </form>
  </div><!-- /.box -->
</div>

<?php } ?>