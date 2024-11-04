<?php 
    if (isset($_POST['simpan-sikap'])){
        // Debugging: Cek data yang diterima
        $juml = mysql_num_rows(mysql_query("SELECT * FROM rb_siswa where kode_kelas='$_GET[id]'"));
        for ($ia=1; $ia<=$juml; $ia++){
          $a   = $_POST['a'.$ia];
          $b   = $_POST['b'.$ia];
          $c   = $_POST['c'.$ia];
          $nisn   = $_POST['nisn'.$ia];
          
        
          
          if ($a != '' OR $b != '' OR $c != ''){
            $cek = mysql_num_rows(mysql_query("SELECT * FROM rb_nilai_sikap where kodejdwl='$_POST[jdwl]' AND nisn='$nisn' AND status='$_POST[status]'"));
            // Debugging: Cek hasil pengecekan
          
            
            if ($cek >= '1'){
              mysql_query("UPDATE rb_nilai_sikap SET positif='$a', negatif='$b', deskripsi='$c' where kodejdwl='$_GET[jdwl]' AND nisn='$nisn' AND status='$_POST[status]'");
            }else{
              mysql_query("INSERT INTO rb_nilai_sikap VALUES('','$_GET[jdwl]','$nisn','$a','$b','$c','$_POST[status]','$_SESSION[id]','".date('Y-m-d H:i:s')."')");
            }
          }
        }
        echo "<script>document.location='index.php?view=raport&act=listsiswasikap&jdwl=$_GET[jdwl]&kd=$_GET[kd]&id=$_GET[id]&tahun=$_GET[tahun]';</script>";
    }
    
    if (isset($_POST['simpan-pengetahuan'])){
        // Debugging: Cek data yang diterima
        
        if ($_POST['status']=='Update'){
          mysql_query("UPDATE rb_nilai_pengetahuan SET kd='$_POST[a]', nilai1='$_POST[b]', nilai2='$_POST[c]', nilai3='$_POST[d]', nilai4='$_POST[e]', nilai5='$_POST[f]', deskripsi='$_POST[g]' where id_nilai_pengetahuan='$_POST[id]'");
        }else{
          mysql_query("INSERT INTO rb_nilai_pengetahuan VALUES('','$_GET[jdwl]','$_POST[nisn]','$_POST[a]','$_POST[b]','$_POST[c]','$_POST[d]','$_POST[e]','$_POST[f]','$_POST[g]','$_SESSION[id]','".date('Y-m-d H:i:s')."')");
        }
        echo "<script>document.location='index.php?view=raport&act=listsiswasikap&jdwl=$_GET[jdwl]&kd=$_GET[kd]&id=$_GET[id]&tahun=$_GET[tahun]#$_POST[nisn]';</script>";
    }

    if (isset($_GET['delete_pengetahuan'])){
        // Debugging: Cek ID yang akan dihapus
        
        mysql_query("DELETE FROM rb_nilai_pengetahuan where id_nilai_pengetahuan='$_GET[delete_pengetahuan]'");
        echo "<script>document.location='index.php?view=raport&act=listsiswasikap&jdwl=$_GET[jdwl]&kd=$_GET[kd]&id=$_GET[id]&tahun=$_GET[tahun]#$_GET[nisn]';</script>";
    }

    if (isset($_POST['simpan-keterampilan'])){
      if ($_POST['status']=='Update'){
        mysql_query("UPDATE rb_nilai_keterampilan SET kd='$_POST[a]', nilai1='$_POST[b]', nilai2='$_POST[c]', nilai3='$_POST[d]', nilai4='$_POST[e]', nilai5='$_POST[f]', nilai6='$_POST[g]', deskripsi='$_POST[h]' where id_nilai_keterampilan='$_POST[id]'");
      }else{
        mysql_query("INSERT INTO rb_nilai_keterampilan VALUES('','$_GET[jdwl]','$_POST[nisn]','$_POST[a]','$_POST[b]','$_POST[c]','$_POST[d]','$_POST[e]','$_POST[f]','$_POST[g]','$_POST[h]','$_SESSION[id]','".date('Y-m-d H:i:s')."')");
      }
  echo "<script>document.location='index.php?view=raport&act=listsiswasikap&jdwl=$_GET[jdwl]&kd=$_GET[kd]&id=$_GET[id]&tahun=$_GET[tahun]#$_POST[nisn]';</script>";
}

if (isset($_GET['delete_keterampilan'])){
  
  mysql_query("DELETE FROM rb_nilai_keterampilan where id_nilai_keterampilan='$_GET[delete_keterampilan]'");
  echo "<script>document.location='index.php?view=raport&act=listsiswasikap&jdwl=$_GET[jdwl]&kd=$_GET[kd]&id=$_GET[id]&tahun=$_GET[tahun]#$_GET[nisn]';</script>";
}

    $d = mysql_fetch_array(mysql_query("SELECT * FROM rb_kelas where kode_kelas='$_GET[id]'"));
    $m = mysql_fetch_array(mysql_query("SELECT * FROM rb_mata_pelajaran where kode_pelajaran='$_GET[kd]'"));
    echo "<div class='col-md-12'>
    <div class='box box-info'>
      <div class='box-header with-border'>
        <h3 class='box-title'>Input Nilai Sikap Siswa</h3>
      </div>
  
      <div class='box-body'>
          <div class='table-responsive'>
            <table class='table table-condensed table-hover'>
                <tbody>
                  <input type='hidden' name='id' value='$s[kodekelas]'>
                  <tr><th width='120px' scope='row'>Kode Kelas</th> <td>$d[kode_kelas]</td></tr>
                  <tr><th scope='row'>Nama Kelas</th> <td>$d[nama_kelas]</td></tr>
                  <tr><th scope='row'>Mata Pelajaran</th> <td>$m[namamatapelajaran]</td></tr>
                </tbody>
            </table>
          </div>
  
          <div class='panel-body'>
              <ul id='myTabs' class='nav nav-tabs' role='tablist'>
                <li role='presentation' class='active'><a href='#spiritual' id='spiritual-tab' role='tab' data-toggle='tab' aria-controls='spiritual' aria-expanded='true'>Penilaian Spiritual</a></li>
                <li role='presentation' class=''><a href='#sosial' role='tab' id='sosial-tab' data-toggle='tab' aria-controls='sosial' aria-expanded='false'>Penilaian Sosial</a></li>
                <li role='presentation' class=''><a href='#pengetahuan' role='tab' id='pengetahuan-tab' data-toggle='tab' aria-controls='pengetahuan' aria-expanded='false'>Penilaian Pengetahuan</a></li>
                <li role='presentation' class=''><a href='#keterampilan' role='tab' id='keterampilan-tab' data-toggle='tab' aria-controls='keterampilan' aria-expanded='false'>Penilaian Keterampilan</a></li>
              </ul><br>
  
              <div id='myTabContent' class='tab-content'>";


            // Halaman Nilai Spiritual
            echo "<div role='tabpanel' class='tab-pane fade active in' id='spiritual' aria-labelledby='spiritual-tab'>"; 
            echo "<div class='col-md-12'>
                  <form action='index.php?view=raport&act=listsiswasikap&jdwl=$_GET[jdwl]&kd=$_GET[kd]&id=$_GET[id]&tahun=$_GET[tahun]' method='POST'>
                  <input type='hidden' value='spiritual' name='status'>
                  <div class='table-responsive'>
                      <table class='table table-bordered table-striped'>
                          <tr>
                            <th style='border:1px solid #e3e3e3' width='30px' rowspan='2'>No</th>
                            <th style='border:1px solid #e3e3e3' width='80px' rowspan='2'>NISN</th>
                            <th style='border:1px solid #e3e3e3' width='190px' rowspan='2'>Nama Lengkap</th>
                            <th style='border:1px solid #e3e3e3' colspan='3'><center>Penilaian Spiritual</center></th>
                          </tr>
                          <tr>
                            <th style='border:1px solid #e3e3e3;'><center>Positif</center></th>
                            <th style='border:1px solid #e3e3e3;'><center>Negatif</center></th>
                            <th style='border:1px solid #e3e3e3;'><center>Deskripsi</center></th>
                          </tr>
                        <tbody>";
            
                    $no = 1;
                    $tampil = mysql_query("SELECT * FROM rb_siswa where kode_kelas='$_GET[id]' ORDER BY id_siswa");
                    while($r = mysql_fetch_array($tampil)){
                      $des = mysql_fetch_array(mysql_query("SELECT * FROM rb_nilai_sikap where kodejdwl='$_GET[jdwl]' AND nisn='$r[nisn]' AND status='spiritual'"));
                      echo "<tr>
                            <td>$no</td>
                            <td>$r[nisn]</td>
                            <td>$r[nama]</td>
                            <input type='hidden' name='nisn".$no."' value='$r[nisn]'>
                            <td align='center'><textarea name='a".$no."' class='form-control' placeholder='Tuliskan Positif...' rows='1'>$des[positif]</textarea></td>
                            <td align='center'><textarea name='b".$no."' class='form-control' placeholder='Tuliskan Negatif...' rows='1'>$des[negatif]</textarea></td>
                            <td align='center'><textarea name='c".$no."' class='form-control' placeholder='Tuliskan Deskripsi...' rows='1'>$des[deskripsi]</textarea></td>
                          </tr>";
                      $no++;
                  }
                  echo "</tbody>
                        </table>
                        </div>
                        <div style='clear:both'></div>
                        <div class='box-footer'>
                          <button type='submit' name='simpan-sikap' class='btn btn-info'>Simpan</button>
                        </div>
                        </form>
                        </div>";
                  

            // Halaman Nilai Sosial
            echo "<div role='tabpanel' class='tab-pane fade' id='sosial' aria-labelledby='sosial-tab'>
          <div class='col-md-12'>
                <form action='index.php?view=raport&act=listsiswasikap&jdwl=$_GET[jdwl]&kd=$_GET[kd]&id=$_GET[id]&tahun=$_GET[tahun]' method='POST'>
                <input type='hidden' value='sosial' name='status'>
                <div class='table-responsive'>
                    <table class='table table-bordered table-striped'>
                        <tr>
                          <th style='border:1px solid #e3e3e3' width='30px' rowspan='2'>No</th>
                          <th style='border:1px solid #e3e3e3' width='80px' rowspan='2'>NISN</th>
                          <th style='border:1px solid #e3e3e3' width='190px' rowspan='2'>Nama Lengkap</th>
                          <th style='border:1px solid #e3e3e3' colspan='3'><center>Penilaian Sosial</center></th>
                        </tr>
                        <tr>
                          <th style='border:1px solid #e3e3e3;'><center>Positif</center></th>
                          <th style='border:1px solid #e3e3e3;'><center>Negatif</center></th>
                          <th style='border:1px solid #e3e3e3;'><center>Deskripsi</center></th>
                        </tr>
                      <tbody>";

                          $no = 1;
                          $tampil = mysql_query("SELECT * FROM rb_siswa where kode_kelas='$_GET[id]' ORDER BY id_siswa");
                          while($r = mysql_fetch_array($tampil)){
                            $des = mysql_fetch_array(mysql_query("SELECT * FROM rb_nilai_sikap where kodejdwl='$_GET[jdwl]' AND nisn='$r[nisn]' AND status='sosial'"));
                            echo "<tr>
                                  <td>$no</td>
                                  <td>$r[nisn]</td>
                                  <td>$r[nama]</td>
                                  <input type='hidden' name='nisn".$no."' value='$r[nisn]'>
                                  <td align='center'><textarea name='a".$no."' class='form-control' placeholder='Tuliskan Positif...' rows='1'>$des[positif]</textarea></td>
                                  <td align='center'><textarea name='b".$no."' class='form-control' placeholder='Tuliskan Negatif...' rows='1'>$des[negatif]</textarea></td>
                                  <td align='center'><textarea name='c".$no."' class='form-control' placeholder='Tuliskan Deskripsi...' rows='1'>$des[deskripsi]</textarea></td>
                                </tr>";
                            $no++;
                        }
                        echo "</tbody>
                              </table>
                              </div>
                              <div style='clear:both'></div>
                              <div class='box-footer'>
                                <button type='submit' name='simpan-sikap' class='btn btn-info'>Simpan</button>
                              </div>
                              </form>
                              </div>";
                        

            // Halaman Nilai pengetahuan (baru)
            echo "<div role='tabpanel' class='tab-pane fade' id='pengetahuan' aria-labelledby='pengetahuan-tab'>
          <div class='panel-body'>
              <div class='table-responsive'>
                  <table class='table table-bordered table-striped'>
                                <tr>
                                  <th style='border:1px solid #e3e3e3' width='30px' rowspan='2'>No</th>
                                  <th style='border:1px solid #e3e3e3' width='170px' rowspan='2'>Nama Lengkap</th>
                                  <th style='border:1px solid #e3e3e3; width:55px' rowspan='2'><center>KD</center></th>
                                  <th style='border:1px solid #e3e3e3' colspan='5'><center>Penilaian</center></th>
                                  <th style='border:1px solid #e3e3e3; width:55px' rowspan='2'><center>Rata2</center></th>
                                  <th style='border:1px solid #e3e3e3; width:55px' rowspan='2'><center>Grade</center></th>
                                  <th style='border:1px solid #e3e3e3' rowspan='2'><center>Deskripsi</center></th>
                                  <th style='border:1px solid #e3e3e3; width:65px' rowspan='2'><center>Action</center></th>
                                </tr>
                                <tr>
                                  <th style='border:1px solid #e3e3e3; width:55px'><center>UH</center></th>
                                  <th style='border:1px solid #e3e3e3; width:55px'><center>UTS</center></th>
                                  <th style='border:1px solid #e3e3e3; width:55px'><center>TU</center></th>
                                  <th style='border:1px solid #e3e3e3; width:55px'><center>SM</center></th>
                                  <th style='border:1px solid #e3e3e3; width:55px'><center></center></th>
                                </tr>
                              <tbody>";

                              $no = 1;
                              $tampil = mysql_query("SELECT * FROM rb_siswa where kode_kelas='$_GET[id]' ORDER BY id_siswa");
                              while($r=mysql_fetch_array($tampil)){
                                  if (isset($_GET['edit_pengetahuan'])){
                                      $e = mysql_fetch_array(mysql_query("SELECT * FROM rb_nilai_pengetahuan where id_nilai_pengetahuan='$_GET[edit_pengetahuan]'"));
                                      $name = 'Update';
                                  }else{
                                      $name = 'Simpan';
                                  }
                                  if ($_GET['nisn'] == $r['nisn']) {
                                    echo "<form action='index.php?view=raport&act=listsiswasikap&jdwl=$_GET[jdwl]&kd=$_GET[kd]&id=$_GET[id]&tahun=$_GET[tahun]' method='POST'>
                                              <tr>
                                                <td>$no</td>
                                                <td style='font-size:12px' id='$r[nisn]'>$r[nama]</td>
                                                <input type='hidden' name='nisn' value='$r[nisn]'>
                                                <input type='hidden' name='id' value='$e[id_nilai_pengetahuan]'>
                                                <input type='hidden' name='status' value='$name'>
                                                <td align='center'><input type='text' name='a' value='$e[kd]' class='form-control' style='width:60px; text-align:center; padding:0;'></td>
                                                <td align='center'><input type='text' name='b' value='$e[nilai1]' class='form-control' style='width:60px; text-align:center; padding:0;'></td>
                                                <td align='center'><input type='text' name='c' value='$e[nilai2]' class='form-control' style='width:60px; text-align:center; padding:0;'></td>
                                                <td align='center'><input type='text' name='d' value='$e[nilai3]' class='form-control' style='width:60px; text-align:center; padding:0;'></td>
                                                <td align='center'><input type='text' name='e' value='$e[nilai4]' class='form-control' style='width:60px; text-align:center; padding:0;'></td>
                                                <td align='center'><input type='text' name='f' value='$e[nilai5]' class='form-control' style='width:60px; text-align:center; padding:0;'></td>
                                                <td align='center'><input type='text' style='width:60px; background:#e3e3e3; border:1px solid #e3e3e3;' disabled></td>
                                                <td align='center'><input type='text' style='width:60px; background:#e3e3e3; border:1px solid #e3e3e3;' disabled></td>
                                                <td align='center'><input type='text' name='g' value='$e[deskripsi]' class='form-control' style='width:100%; padding:0;'></td>
                                                <td align='center'><input type='submit' name='simpan-pengetahuan' class='btn btn-xs btn-primary' style='width:65px;' value='$name'></td>
                                              </tr>
                                              </form>";
                                } else {
                                    echo "<form action='index.php?view=raport&act=listsiswasikap&jdwl=$_GET[jdwl]&kd=$_GET[kd]&id=$_GET[id]&tahun=$_GET[tahun]' method='POST'>
                                              <tr>
                                                <td>$no</td>
                                                <td style='font-size:12px' id='$r[nisn]'>$r[nama]</td>
                                                <input type='hidden' name='nisn' value='$r[nisn]'>
                                                <input type='hidden' name='id' value='$e[id_nilai_pengetahuan]'>
                                                <input type='hidden' name='status' value='$name'>
                                                <td align='center'><input type='text' name='a' class='form-control' style='width:60px; text-align:center; padding:0;'></td>
                                                <td align='center'><input type='text' name='b' class='form-control' style='width:60px; text-align:center; padding:0;'></td>
                                                <td align='center'><input type='text' name='c' class='form-control' style='width:60px; text-align:center; padding:0;'></td>
                                                <td align='center'><input type='text' name='d' class='form-control' style='width:60px; text-align:center; padding:0;'></td>
                                                <td align='center'><input type='text' name='e' class='form-control' style='width:60px; text-align:center; padding:0;'></td>
                                                <td align='center'><input type='text' name='f' class='form-control' style='width:60px; text-align:center; padding:0;'></td>
                                                <td align='center'><input type='text' style='width:60px; background:#e3e3e3; border:1px solid #e3e3e3;' disabled></td>
                                                <td align='center'><input type='text' style='width:60px; background:#e3e3e3; border:1px solid #e3e3e3;' disabled></td>
                                                <td align='center'><input type='text' name='g' class='form-control' style='width:100%; padding:0;'></td>
                                                <td align='center'><input type='submit' name='simpan-pengetahuan' class='btn btn-xs btn-primary' style='width:65px;' value='$name'></td>
                                              </tr>
                                              </form>";
                                }
                              

                                    $pe = mysql_query("SELECT * FROM rb_nilai_pengetahuan where kodejdwl='$_GET[jdwl]' AND nisn='$r[nisn]'");
                                    while ($n = mysql_fetch_array($pe)){
                                    $ratarata = average(array($n[nilai1],$n[nilai2],$n[nilai3],$n[nilai4],$n[nilai5]));
                                    $cekpredikat = mysql_num_rows(mysql_query("SELECT * FROM rb_predikat where kode_kelas='$_GET[id]'"));
                                    if ($cekpredikat >= 1){
                                      $grade1 = mysql_fetch_array(mysql_query("SELECT * FROM `rb_predikat` where (".number_format($ratarata)." >=nilai_a) AND (".number_format($ratarata)." <= nilai_b) AND kode_kelas='$_GET[id]'"));
                                    }else{
                                      $grade1 = mysql_fetch_array(mysql_query("SELECT * FROM `rb_predikat` where (".number_format($ratarata)." >=nilai_a) AND (".number_format($ratarata)." <= nilai_b) AND kode_kelas='0'"));
                                    }
                                    
                                    echo "<tr>
                                    <td></td>
                                    <td></td>
                                    <td align='center' class='text-nowrap'>$n[kd]</td>
                                    <td align='center' class='text-nowrap'>$n[nilai1]</td>
                                    <td align='center' class='text-nowrap'>$n[nilai2]</td>
                                    <td align='center' class='text-nowrap'>$n[nilai3]</td>
                                    <td align='center' class='text-nowrap'>$n[nilai4]</td>
                                    <td align='center' class='text-nowrap'>$n[nilai5]</td>
                                    <td align='center' class='text-nowrap'>".number_format($ratarata)."</td>
                                    <td align='center' class='text-nowrap'>$grade1[grade]</td>
                                    <td>$n[deskripsi]</td>
                                    <td align='center'>
                                        <a href='index.php?view=raport&act=listsiswasikap&jdwl=".$_GET['jdwl']."&kd=".$_GET['kd']."&id=".$_GET['id']."&tahun=".$_GET['tahun']."&edit_pengetahuan=".$n['id_nilai_pengetahuan']."&nisn=".$r['nisn']."' class='btn btn-xs btn-success' title='Edit Data'><span class='glyphicon glyphicon-edit'></span></a>
                                        <a href='index.php?view=raport&act=listsiswasikap&jdwl=".$_GET['jdwl']."&kd=".$_GET['kd']."&id=".$_GET['id']."&tahun=".$_GET['tahun']."&delete_pengetahuan=".$n['id_nilai_pengetahuan']."&nisn=".$r['nisn']."' class='btn btn-xs btn-danger' onclick=\"return confirm('Apa anda yakin untuk hapus Data ini?')\" title='Hapus Data'><span class='glyphicon glyphicon-remove'></span></a>
                                    </td>
                                </tr>";
                            
                                    }
                                      $maxn = mysql_fetch_array(mysql_query("SELECT ((nilai1+nilai2+nilai3+nilai4+nilai5)/5) as rata_rata, deskripsi FROM rb_nilai_pengetahuan where kodejdwl='$_GET[jdwl]' AND nisn='$r[nisn]' ORDER BY rata_rata DESC LIMIT 1"));
                                      $cekpredikat1 = mysql_num_rows(mysql_query("SELECT * FROM rb_predikat where kode_kelas='$_GET[id]'"));
                                      if ($cekpredikat1 >= 1){
                                        $grade2 = mysql_fetch_array(mysql_query("SELECT * FROM `rb_predikat` where (".number_format($maxn[rata_rata])." >=nilai_a) AND (".number_format($maxn[rata_rata])." <= nilai_b) AND kode_kelas='$_GET[id]'"));
                                      }else{
                                        $grade2 = mysql_fetch_array(mysql_query("SELECT * FROM `rb_predikat` where (".number_format($maxn[rata_rata])." >=nilai_a) AND (".number_format($maxn[rata_rata])." <= nilai_b) AND kode_kelas='0'"));
                                      }
                                      
                                      $rapn = mysql_fetch_array(mysql_query("SELECT sum((nilai1+nilai2+nilai3+nilai4+nilai5)/5)/count(nisn) as raport FROM rb_nilai_pengetahuan where kodejdwl='$_GET[jdwl]' AND nisn='$r[nisn]'"));
                                      $cekpredikat2 = mysql_num_rows(mysql_query("SELECT * FROM rb_predikat where kode_kelas='$_GET[id]'"));
                                      if ($cekpredikat2 >= 1){
                                        $grade3 = mysql_fetch_array(mysql_query("SELECT * FROM `rb_predikat` where (".number_format($rapn[raport])." >=nilai_a) AND (".number_format($rapn[raport])." <= nilai_b) AND kode_kelas='$_GET[id]'"));
                                      }else{
                                        $grade3 = mysql_fetch_array(mysql_query("SELECT * FROM `rb_predikat` where (".number_format($rapn[raport])." >=nilai_a) AND (".number_format($rapn[raport])." <= nilai_b) AND kode_kelas='0'"));
                                      }

                                      echo "<tr>
                                              <td colspan='2'></td>
                                              <td align='center' colspan='6' class='text-nowrap'>Nilai Max/Min</td>
                                              <td align='center' class='text-nowrap'>".number_format($maxn['rata_rata'])."</td>
                                              <td align='center' class='text-nowrap'>$grade2[grade]</td>
                                              <td></td>
                                            </tr>
                                            <tr>
                                              <td colspan='2'></td>
                                              <td align='center' colspan='6' class='text-nowrap'>Raport</td>
                                              <td align='center' class='text-nowrap'>".number_format($rapn['raport'])."</td>
                                              <td align='center' class='text-nowrap'>$grade3[grade]</td>
                                              <td class='text-nowrap'>$maxn[deskripsi]</td>
                                            </tr>";
                                      $no++;
                                    }
                                      echo "</tbody>
                                          </table>
                                      </div>
                                      </div>";

            // Halaman Nilai Keterampilan (baru)
            echo "<div role='tabpanel' class='tab-pane fade' id='keterampilan' aria-labelledby='keterampilan-tab'>
        <div class='panel-body'>
            <div class='table-responsive'>
                <table class='table table-bordered table-striped'>
                    <thead>
                        <tr>
                            <th style='border:1px solid #e3e3e3' width='30' rowspan='2'>No</th>
                            <th style='border:1px solid #e3e3e3' width='170' rowspan='2'>Nama Lengkap</th>
                            <th style='border:1px solid #e3e3e3' width='55' rowspan='2' class='text-center'>KD</th>
                            <th style='border:1px solid #e3e3e3' colspan='6' class='text-center'>Penilaian</th>
                            <th style='border:1px solid #e3e3e3' width='55' rowspan='2' class='text-center'>Nilai</th>
                            <th style='border:1px solid #e3e3e3' width='55' rowspan='2' class='text-center'>Grade</th>
                            <th style='border:1px solid #e3e3e3' rowspan='2' class='text-center'>Deskripsi</th>
                            <th style='border:1px solid #e3e3e3' width='65' rowspan='2' class='text-center'>Action</th>
                        </tr>
                        <tr>
                            <th style='border:1px solid #e3e3e3' colspan='2' class='text-center'>Praktek</th>
                            <th style='border:1px solid #e3e3e3' colspan='2' class='text-center'>Proyek</th>
                            <th style='border:1px solid #e3e3e3' colspan='2' class='text-center'>Portofolio</th>
                        </tr>
                    </thead>
                    <tbody>";

                              $no = 1;
                              $tampil = mysql_query("SELECT * FROM rb_siswa where kode_kelas='$_GET[id]' ORDER BY id_siswa");
                              while($r=mysql_fetch_array($tampil)){
                                  if (isset($_GET['edit_keterampilan'])){
                                      $e = mysql_fetch_array(mysql_query("SELECT * FROM rb_nilai_keterampilan where id_nilai_keterampilan='$_GET[edit_keterampilan]'"));
                                      $name = 'Update';
                                  }else{
                                      $name = 'Simpan';
                                  }
                                  if ($_GET['nisn'] == $r['nisn']) {
                                    echo "<form action='index.php?view=raport&act=listsiswasikap&jdwl=$_GET[jdwl]&kd=$_GET[kd]&id=$_GET[id]&tahun=$_GET[tahun]' method='POST'>
                                            <tr>
                                                <td>$no</td>
                                                <td style='font-size:12px' id='$r[nisn]'>$r[nama]</td>
                                                <input type='hidden' name='nisn' value='$r[nisn]'>
                                                <input type='hidden' name='id' value='$e[id_nilai_keterampilan]'>
                                                <input type='hidden' name='status' value='$name'>
                                                <td align='center'><input type='text' name='a1' value='$e[kd]' class='form-control form-control-sm' style='width:50px; text-align:center; padding:0px'></td>
                                                <td align='center'><input type='text' name='b1' value='$e[nilai1]' class='form-control form-control-sm' style='width:50px; text-align:center; padding:0px'></td>
                                                <td align='center'><input type='text' name='c1' value='$e[nilai2]' class='form-control form-control-sm' style='width:50px; text-align:center; padding:0px'></td>
                                                <td align='center'><input type='text' name='d1' value='$e[nilai3]' class='form-control form-control-sm' style='width:50px; text-align:center; padding:0px'></td>
                                                <td align='center'><input type='text' name='e1' value='$e[nilai4]' class='form-control form-control-sm' style='width:50px; text-align:center; padding:0px'></td>
                                                <td align='center'><input type='text' name='f1' value='$e[nilai5]' class='form-control form-control-sm' style='width:50px; text-align:center; padding:0px'></td>
                                                <td align='center'><input type='text' name='g1' value='$e[nilai6]' class='form-control form-control-sm' style='width:50px; text-align:center; padding:0px'></td>
                                                <td align='center'><input type='text' style='width:50px; background:#e3e3e3; border:1px solid #e3e3e3;' disabled></td>
                                                <td align='center'><input type='text' style='width:50px; background:#e3e3e3; border:1px solid #e3e3e3;' disabled></td>
                                                <td align='center'><input type='text' name='h' value='$e[deskripsi]' class='form-control' style='width:100%; padding:0px'></td>
                                                <td align='center'><input type='submit' name='simpan-keterampilan' class='btn btn-xs btn-primary' style='width:65px' value='$name'></td>
                                            </tr>
                                          </form>";
                                } else {
                                    echo "<form action='index.php?view=raport&act=listsiswasikap&jdwl=$_GET[jdwl]&kd=$_GET[kd]&id=$_GET[id]&tahun=$_GET[tahun]' method='POST'>
                                            <tr>
                                                <td>$no</td>
                                                <td style='font-size:12px' id='$r[nisn]'>$r[nama]</td>
                                                <input type='hidden' name='nisn' value='$r[nisn]'>
                                                <input type='hidden' name='id' value='$e[id_nilai_keterampilan]'>
                                                <input type='hidden' name='status' value='$name'>
                                                <td align='center'><input type='text' name='a1' class='form-control form-control-sm' style='width:50px; text-align:center; padding:0px'></td>
                                                <td align='center'><input type='text' name='b1' class='form-control form-control-sm' style='width:50px; text-align:center; padding:0px'></td>
                                                <td align='center'><input type='text' name='c1' class='form-control form-control-sm' style='width:50px; text-align:center; padding:0px'></td>
                                                <td align='center'><input type='text' name='d1' class='form-control form-control-sm' style='width:50px; text-align:center; padding:0px'></td>
                                                <td align='center'><input type='text' name='e1' class='form-control form-control-sm' style='width:50px; text-align:center; padding:0px'></td>
                                                <td align='center'><input type='text' name='f1' class='form-control form-control-sm' style='width:50px; text-align:center; padding:0px'></td>
                                                <td align='center'><input type='text' name='g1' class='form-control form-control-sm' style='width:50px; text-align:center; padding:0px'></td>
                                                <td align='center'><input type='text' style='width:50px; background:#e3e3e3; border:1px solid #e3e3e3;' disabled></td>
                                                <td align='center'><input type='text' style='width:50px; background:#e3e3e3; border:1px solid #e3e3e3;' disabled></td>
                                                <td align='center'><input type='text' name='h' class='form-control' style='width:100%; padding:0px'></td>
                                                <td align='center'><input type='submit' name='simpan-keterampilan' class='btn btn-xs btn-primary' style='width:65px' value='$name'></td>
                                            </tr>
                                          </form>";
                                }
                                

                                    $pe = mysql_query("SELECT * FROM rb_nilai_keterampilan where kodejdwl='$_GET[jdwl]' AND nisn='$r[nisn]'");
                                    while ($n = mysql_fetch_array($pe)){
                                    $ratarata = max($n[nilai1],$n[nilai2],$n[nilai3],$n[nilai4],$n[nilai5],$n[nilai6]);
                                    $cekpredikat = mysql_num_rows(mysql_query("SELECT * FROM rb_predikat where kode_kelas='$_GET[id]'"));
                                      if ($cekpredikat >= 1){
                                        $grade1 = mysql_fetch_array(mysql_query("SELECT * FROM `rb_predikat` where (".number_format($ratarata)." >=nilai_a) AND (".number_format($ratarata)." <= nilai_b) AND kode_kelas='$_GET[id]'"));
                                      }else{
                                        $grade1 = mysql_fetch_array(mysql_query("SELECT * FROM `rb_predikat` where (".number_format($ratarata)." >=nilai_a) AND (".number_format($ratarata)." <= nilai_b) AND kode_kelas='0'"));
                                      }
                                    
                                      echo "<tr>
                                              <td></td>
                                              <td></td>
                                              <td align='center' class='text-nowrap'>$n[kd]</td>
                                              <td align='center' class='text-nowrap'>$n[nilai1]</td>
                                              <td align='center' class='text-nowrap'>$n[nilai2]</td>
                                              <td align='center' class='text-nowrap'>$n[nilai3]</td>
                                              <td align='center' class='text-nowrap'>$n[nilai4]</td>
                                              <td align='center' class='text-nowrap'>$n[nilai5]</td>
                                              <td align='center' class='text-nowrap'>$n[nilai6]</td>
                                              <td align='center' class='text-nowrap'>".number_format($ratarata)."</td>
                                              <td align='center' class='text-nowrap'>$grade1[grade]</td>
                                              <td class='text-wrap'>$n[deskripsi]</td>
                                              <td align='center'>
                                                  <a href='index.php?view=raport&act=listsiswasikap&jdwl=".$_GET['jdwl']."&kd=".$_GET['kd']."&id=".$_GET['id']."&tahun=".$_GET['tahun']."&edit_keterampilan=".$n['id_nilai_keterampilan']."&nisn=".$r['nisn']."' class='btn btn-xs btn-success' role='button'><span class='glyphicon glyphicon-edit'></span></a>
                                                  <a href='index.php?view=raport&act=listsiswasikap&jdwl=".$_GET['jdwl']."&kd=".$_GET['kd']."&id=".$_GET['id']."&tahun=".$_GET['tahun']."&delete_keterampilan=".$n['id_nilai_keterampilan']."&nisn=".$r['nisn']."' class='btn btn-xs btn-danger' role='button' onclick=\"return confirm('Apa anda yakin untuk hapus Data ini?')\"><span class='glyphicon glyphicon-remove'></span></a>
                                              </td>
                                            </tr>";

                                    }
                                      $maxn = mysql_fetch_array(mysql_query("SELECT deskripsi, GREATEST(nilai1,nilai2,nilai3,nilai4,nilai5,nilai6) as tertinggi FROM rb_nilai_keterampilan where kodejdwl='$_GET[jdwl]' AND nisn='$r[nisn]' ORDER BY tertinggi DESC LIMIT 1"));
                                      $cekpredikat1 = mysql_num_rows(mysql_query("SELECT * FROM rb_predikat where kode_kelas='$_GET[id]'"));
                                      if ($cekpredikat1 >= 1){
                                        $grade2 = mysql_fetch_array(mysql_query("SELECT * FROM `rb_predikat` where (".number_format($maxn[tertinggi])." >=nilai_a) AND (".number_format($maxn[tertinggi])." <= nilai_b) AND kode_kelas='$_GET[id]'"));
                                      }else{
                                        $grade2 = mysql_fetch_array(mysql_query("SELECT * FROM `rb_predikat` where (".number_format($maxn[tertinggi])." >=nilai_a) AND (".number_format($maxn[tertinggi])." <= nilai_b) AND kode_kelas='0'"));
                                      }

                                      
                                      $rapn = mysql_fetch_array(mysql_query("SELECT sum(GREATEST(nilai1,nilai2,nilai3,nilai4,nilai5,nilai6))/count(nisn) as raport FROM rb_nilai_keterampilan where kodejdwl='$_GET[jdwl]' AND nisn='$r[nisn]'"));
                                      $cekpredikat2 = mysql_num_rows(mysql_query("SELECT * FROM rb_predikat where kode_kelas='$_GET[id]'"));
                                      if ($cekpredikat2 >= 1){
                                        $grade3 = mysql_fetch_array(mysql_query("SELECT * FROM `rb_predikat` where (".number_format($rapn[raport])." >=nilai_a) AND (".number_format($rapn[raport])." <= nilai_b) AND kode_kelas='$_GET[id]'"));
                                      }else{
                                        $grade3 = mysql_fetch_array(mysql_query("SELECT * FROM `rb_predikat` where (".number_format($rapn[raport])." >=nilai_a) AND (".number_format($rapn[raport])." <= nilai_b) AND kode_kelas='0'"));
                                      }

                                      echo "<tr>
                                      <td></td><td></td>
                                      <td align='center' colspan='7' class='text-nowrap'>Nilai Max/Min</td>
                                      <td align='center' class='text-nowrap'>".number_format($maxn['tertinggi'])."</td>
                                      <td align='center' class='text-nowrap'>$grade2[grade]</td><td></td>
                                    </tr>
                                    <tr>
                                      <td></td><td></td>
                                      <td align='center' colspan='7' class='text-nowrap'>Raport</td>
                                      <td align='center' class='text-nowrap'>".number_format($rapn['raport'])."</td>
                                      <td align='center' class='text-nowrap'>$grade3[grade]</td>
                                      <td class='text-wrap'>$maxn[deskripsi]</td>
                                    </tr>";
                              $no++;
                                    }
                              
                              echo "</tbody>
                                    </table>
                                    </div>
                                    </div>";
                              
                              echo "</div>
                                    </div>
                                    </div>
                                    </div>";
                              
?>