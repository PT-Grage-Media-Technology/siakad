<?php 
if ($_GET[act]==''){ 
    if (isset($_POST['submit'])){
       $jml = mysql_fetch_array(mysql_query("SELECT count(*) as jmlp FROM `rb_pertanyaan_penilaian` where status='diri'"));
       $n = $jml[jmlp];
       for ($i=0; $i<=$n; $i++){
         if (isset($_POST['jawab'.$i])){
           $jawab = $_POST['jawab'.$i];
           $pertanyaan = $_POST['id'.$i];
           $kelas = $_POST['kelas'.$i];
            $cek = mysql_fetch_array(mysql_query("SELECT count(*) as tot FROM rb_pertanyaan_penilaian_jawab where nisn='$_SESSION[id]' AND id_pertanyaan_penilaian='$pertanyaan' AND status='diri' AND kode_kelas='$kelas'"));
            if ($cek[tot] >= 1){
              mysql_query("UPDATE rb_pertanyaan_penilaian_jawab SET jawaban='$jawab' where id_pertanyaan_penilaian='$pertanyaan' AND nisn='$_SESSION[id]' AND kode_kelas='$kelas'");
            }else{
              mysql_query("INSERT INTO rb_pertanyaan_penilaian_jawab VALUES('','$pertanyaan','$_SESSION[id]','','$jawab','diri','$kelas','".date('Y-m-d H:i:s')."')");
          }
         }
       }
       echo "<script>window.alert('Sukses Simpan Jawaban Penilaian Diri...');
                window.location='index.php?view=penilaiandirisiswa'</script>";
    }
?> 
            <div class="col-xs-12">  
              <div class="box">
              <form action='' method='POST'>
                <div class="box-header">
                  <h3 class="box-title">Data Pertanyan Penilaian Diri </h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <table id="example3" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th style='width:20px'>No</th>
                        <th>Pertanyaan</th>
                      </tr>
                    </thead>
                    <tbody>
                  <?php 
                    $t = mysql_fetch_array(mysql_query("SELECT * FROM rb_siswa where nisn='$_SESSION[id]'"));
                    $tampil = mysql_query("SELECT * FROM rb_pertanyaan_penilaian where status='diri' ORDER BY id_pertanyaan_penilaian DESC");
                    $no = 1;
                    while($r=mysql_fetch_array($tampil)){
                    $jwb = mysql_fetch_array(mysql_query("SELECT * FROM rb_pertanyaan_penilaian_jawab where nisn='$_SESSION[id]' AND id_pertanyaan_penilaian='$r[id_pertanyaan_penilaian]' AND status='diri' AND kode_kelas='$t[kode_kelas]'"));
                    echo "<tr><td>$no</td>
                              <td>$r[pertanyaan]</td>
                          </tr>

                          <tr><td></td>
                                  <input type='hidden' value='$t[kode_kelas]' name='kelas".$no."'>
                                  <input type='hidden' value='$r[id_pertanyaan_penilaian]' name='id".$no."'>
                              <td><textarea style='height:60px; width:100%' class='form-control' name='jawab".$no."' placeholder='Tulis Jawaban disini..'>$jwb[jawaban]</textarea></td>
                          </tr>";
                      $no++;
                      }
                  ?>
                    </tbody>
                  </table>
                  <input type="submit" name='submit' value='Simpan Semua Jawaban' class='pull-left btn btn-primary btn-sm'>
                </div><!-- /.box-body -->
              </form>
              </div><!-- /.box -->
            </div>
<?php } ?>