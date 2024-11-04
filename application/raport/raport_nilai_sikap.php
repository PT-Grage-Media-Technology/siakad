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

if (isset($_GET['delete-keterampilan'])){
  mysql_query("DELETE FROM rb_nilai_keterampilan where id_nilai_keterampilan='$_GET[delete_keterampilan]'");
  echo "<script>document.location='index.php?view=raport&act=listsiswasikap&jdwl=$_GET[jdwl]&kd=$_GET[kd]&id=$_GET[id]&tahun=$_GET[tahun]#$_GET[nisn]';</script>";
}

    $d = mysql_fetch_array(mysql_query("SELECT * FROM rb_kelas where kode_kelas='$_GET[id]'"));
    $m = mysql_fetch_array(mysql_query("SELECT * FROM rb_mata_pelajaran where kode_pelajaran='$_GET[kd]'"));
    echo "<div class='col-12 col-md-12'>
    <div class='box box-info'>
      <div class='box-header with-border'>
        <h3 class='box-title'>Input Nilai Sikap Siswa</h3>
      </div>
  
<div class='box-body'>
    <div class='col-12'>
    <table class='table table-condensed table-hover'>
        <tbody>
          <input type='hidden' name='id' value='$s[kodekelas]'>
          <tr><th width='120px' scope='row'>Kode Kelas</th> <td>$d[kode_kelas]</td></tr>
          <tr><th scope='row'>Nama Kelas</th>               <td>$d[nama_kelas]</td></tr>
          <tr><th scope='row'>Mata Pelajaran</th>           <td>$m[namamatapelajaran]</td></tr>
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
            echo "<div class='col-12'>
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
                    while ($r = mysql_fetch_array($tampil)) {
                      $des = mysql_fetch_array(mysql_query("SELECT * FROM rb_nilai_sikap where kodejdwl='$_GET[jdwl]' AND nisn='$r[nisn]' AND status='spiritual'"));
                      echo "<tr>
                              <td>$no</td>
                              <td>$r[nisn]</td>
                              <td>$r[nama]</td>
                              <input type='hidden' name='nisn".$no."' value='$r[nisn]'>
                              <td align='center'>
                                  <textarea name='a".$no."' class='form-control' style='width:100%; color:blue' placeholder='Tuliskan Positif...'>$des[positif]</textarea>
                              </td>
                              <td align='center'>
                                  <textarea name='b".$no."' class='form-control' style='width:100%; color:blue' placeholder='Tuliskan Negatif...'>$des[negatif]</textarea>
                              </td>
                              <td align='center'>
                                  <textarea name='c".$no."' class='form-control' style='width:100%; color:blue' placeholder='Tuliskan Deskripsi...'>$des[deskripsi]</textarea>
                              </td>
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
      <div class='col-12'>
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
                          while ($r = mysql_fetch_array($tampil)) {
                            $des = mysql_fetch_array(mysql_query("SELECT * FROM rb_nilai_sikap where kodejdwl='$_GET[jdwl]' AND nisn='$r[nisn]' AND status='sosial'"));
                            echo "<tr>
                                    <td>$no</td>
                                    <td>$r[nisn]</td>
                                    <td>$r[nama]</td>
                                    <input type='hidden' name='nisn".$no."' value='$r[nisn]'>
                                    <td align='center'><textarea name='a".$no."' class='form-control' style='width:100%; color:blue' placeholder='Tuliskan Positif...'>$des[positif]</textarea></td>
                                    <td align='center'><textarea name='b".$no."' class='form-control' style='width:100%; color:blue' placeholder='Tuliskan Negatif...'>$des[negatif]</textarea></td>
                                    <td align='center'><textarea name='c".$no."' class='form-control' style='width:100%; color:blue' placeholder='Tuliskan Deskripsi...'>$des[deskripsi]</textarea></td>
                                  </tr>";
                            $no++;
                        }
                        echo "</tbody>
                              </table>
                              </div> <!-- End of table-responsive -->
                              <div style='clear:both'></div>
                              <div class='box-footer'>
                                <button type='submit' name='simpan-sikap' class='btn btn-info'>Simpan</button>
                              </div>
                              </form>
                              </div>";
                        

            // Halaman Nilai pengetahuan (baru)
            echo "<div role='tabpanel' class='tab-pane fade' id='pengetahuan' aria-labelledby='pengetahuan-tab'>
            <div class='panel-body'>
              <div class='table-responsive'> <!-- Tambahkan table-responsive untuk membuat tabel menjadi scrollable di perangkat kecil -->
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
while ($r = mysql_fetch_array($tampil)) {
    $e = mysql_fetch_array(mysql_query("SELECT * FROM rb_nilai_pengetahuan WHERE kodejdwl='$_GET[jdwl]' AND nisn='$r[nisn]'"));
    
    if ($_GET['nisn'] == $r['nisn']) {
        echo "<form action='index.php?view=raport&act=listsiswasikap&jdwl=$_GET[jdwl]&kd=$_GET[kd]&id=$_GET[id]&tahun=$_GET[tahun]' method='POST'>
              <tr>
                <td>$no</td>
                <td style='font-size:12px' id='$r[nisn]'>$r[nama]</td>
                <input type='hidden' name='nisn' value='$r[nisn]'>
                <input type='hidden' name='id' value='{$e['id_nilai_pengetahuan']}'>
                <input type='hidden' name='status' value='$name'>
                <td align=center><input type='text' name='a' value='{$e['kd']}' style='width:35px; text-align:center; padding:0px'></td>
                <td align=center><input type='text' name='b' value='{$e['nilai1']}' style='width:35px; text-align:center; padding:0px'></td>
                <td align=center><input type='text' name='c' value='{$e['nilai2']}' style='width:35px; text-align:center; padding:0px'></td>
                <td align=center><input type='text' name='d' value='{$e['nilai3']}' style='width:35px; text-align:center; padding:0px'></td>
                <td align=center><input type='text' name='e' value='{$e['nilai4']}' style='width:35px; text-align:center; padding:0px'></td>
                <td align=center><input type='text' name='f' value='{$e['nilai5']}' style='width:35px; text-align:center; padding:0px'></td>
                <td align=center><input type='text' style='width:35px; background:#e3e3e3; border:1px solid #e3e3e3;' disabled></td>
                <td align=center><input type='text' style='width:35px; background:#e3e3e3; border:1px solid #e3e3e3;' disabled></td>
                <td align=center><input type='text' name='g' value='{$e['deskripsi']}' style='width:100%; padding:0px'></td>
                <td align=center><input type='submit' name='simpan-pengetahuan' class='btn btn-xs btn-primary' style='width:65px' value='$name'></td>
              </tr>
              </form>";
    } else {
        echo "<form action='index.php?view=raport&act=listsiswasikap&jdwl=$_GET[jdwl]&kd=$_GET[kd]&id=$_GET[id]&tahun=$_GET[tahun]' method='POST'>
              <tr>
                <td>$no</td>
                <td style='font-size:12px' id='$r[nisn]'>$r[nama]</td>
                <input type='hidden' name='nisn' value='$r[nisn]'>
                <input type='hidden' name='id' value='{$e['id_nilai_pengetahuan']}'>
                <input type='hidden' name='status' value='$name'>
                <td align=center><input type='text' name='a' style='width:35px; text-align:center; padding:0px'></td>
                <td align=center><input type='text' name='b' style='width:35px; text-align:center; padding:0px'></td>
                <td align=center><input type='text' name='c' style='width:35px; text-align:center; padding:0px'></td>
                <td align=center><input type='text' name='d' style='width:35px; text-align:center; padding:0px'></td>
                <td align=center><input type='text' name='e' style='width:35px; text-align:center; padding:0px'></td>
                <td align=center><input type='text' name='f' style='width:35px; text-align:center; padding:0px'></td>
                <td align=center><input type='text' style='width:35px; background:#e3e3e3; border:1px solid #e3e3e3;' disabled></td>
                <td align=center><input type='text' style='width:35px; background:#e3e3e3; border:1px solid #e3e3e3;' disabled></td>
                <td align=center><input type='text' name='g' style='width:100%; padding:0px'></td>
                <td align=center><input type='submit' name='simpan-pengetahuan' class='btn btn-xs btn-primary' style='width:65px' value='$name'></td>
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
                                    <td align=center>{$n['kd']}</td>
                                    <td align=center>{$n['nilai1']}</td>
                                    <td align=center>{$n['nilai2']}</td>
                                    <td align=center>{$n['nilai3']}</td>
                                    <td align=center>{$n['nilai4']}</td>
                                    <td align=center>{$n['nilai5']}</td>
                                    <td align=center>" . number_format($ratarata) . "</td>
                                    <td align=center>{$grade1['grade']}</td>
                                    <td>{$n['deskripsi']}</td>
                                    <td align=center>
                                        <a href='index.php?view=raport&act=listsiswasikap&jdwl=" . htmlspecialchars($_GET['jdwl']) . 
                                        "&kd=" . htmlspecialchars($_GET['kd']) . 
                                        "&id=" . htmlspecialchars($_GET['id']) . 
                                        "&tahun=" . htmlspecialchars($_GET['tahun']) . 
                                        "&edit_pengetahuan=" . $n['id_nilai_pengetahuan'] . 
                                        "&nisn=" . $r['nisn'] . "#{$r['nisn']}' class='btn btn-xs btn-success'>
                                            <span class='glyphicon glyphicon-edit'></span>
                                        </a>
                                        <a href='index.php?view=raport&act=listsiswasikap&jdwl=" . htmlspecialchars($_GET['jdwl']) . 
                                        "&kd=" . htmlspecialchars($_GET['kd']) . 
                                        "&id=" . htmlspecialchars($_GET['id']) . 
                                        "&tahun=" . htmlspecialchars($_GET['tahun']) . 
                                        "&delete_pengetahuan=" . $n['id_nilai_pengetahuan'] . 
                                        "&nisn=" . $r['nisn'] . "' class='btn btn-xs btn-danger' 
                                        onclick=\"return confirm('Apa anda yakin untuk hapus Data ini?')\">
                                            <span class='glyphicon glyphicon-remove'></span>
                                        </a>
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
        <td></td>
        <td></td>
        <td align=center colspan='6'>Nilai Max/Min</td>
        <td align=center>" . number_format($maxn['rata_rata']) . "</td>
        <td align=center>{$grade2['grade']}</td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td align=center colspan='6'>Raport</td>
        <td align=center>" . number_format($rapn['raport']) . "</td>
        <td align=center>{$grade3['grade']}</td>
        <td>{$maxn['deskripsi']}</td>
    </tr>";

$no++;

echo "</tbody>
    </table>
</div>
</div>";

            // Halaman Nilai Keterampilan (baru)
            echo "<div role='tabpanel' class='tab-pane fade' id='keterampilan' aria-labelledby='keterampilan-tab'>
            <div class='panel-body'>
                <table class='table table-bordered table-striped'>
                    <tr>
                        <th style='border:1px solid #e3e3e3' width='30px' rowspan='2'>No</th>
                        <th style='border:1px solid #e3e3e3' width='170px' rowspan='2'>Nama Lengkap</th>
                        <th style='border:1px solid #e3e3e3; width:55px' rowspan='2'><center>KD</center></th>
                        <th style='border:1px solid #e3e3e3' colspan='6'><center>Penilaian</center></th>
                        <th style='border:1px solid #e3e3e3; width:55px' rowspan='2'><center>Nilai</center></th>
                        <th style='border:1px solid #e3e3e3; width:55px' rowspan='2'><center>Grade</center></th>
                        <th style='border:1px solid #e3e3e3' rowspan='2'><center>Deskripsi</center></th>
                        <th style='border:1px solid #e3e3e3; width:65px' rowspan='2'><center>Action</center></th>
                    </tr>
                    <tr>
                        <th style='border:1px solid #e3e3e3; width:110px' colspan='2'><center>Praktek</center></th>
                        <th style='border:1px solid #e3e3e3; width:110px' colspan='2'><center>Proyek</center></th>
                        <th style='border:1px solid #e3e3e3; width:110px' colspan='2'><center>Portofolio</center></th>
                    </tr>
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
                                    echo "<form action='index.php?view=raport&act=listsiswasikap&jdwl={$_GET['jdwl']}&kd={$_GET['kd']}&id={$_GET['id']}&tahun={$_GET['tahun']}' method='POST'>
                                            <tr>
                                                <td>$no</td>
                                                <td style='font-size:12px' id='{$r['nisn']}'>{$r['nama']}</td>
                                                <input type='hidden' name='nisn' value='{$r['nisn']}'>
                                                <input type='hidden' name='id' value='{$e['id_nilai_keterampilan']}'>
                                                <input type='hidden' name='status' value='$name'>
                                                <td align='center'><input type='text' name='a' value='{$e['kd']}' style='width:35px; text-align:center; padding:0px'></td>
                                                <td align='center'><input type='text' name='b' value='{$e['nilai1']}' style='width:35px; text-align:center; padding:0px'></td>
                                                <td align='center'><input type='text' name='c' value='{$e['nilai2']}' style='width:35px; text-align:center; padding:0px'></td>
                                                <td align='center'><input type='text' name='d' value='{$e['nilai3']}' style='width:35px; text-align:center; padding:0px'></td>
                                                <td align='center'><input type='text' name='e' value='{$e['nilai4']}' style='width:35px; text-align:center; padding:0px'></td>
                                                <td align='center'><input type='text' name='f' value='{$e['nilai5']}' style='width:35px; text-align:center; padding:0px'></td>
                                                <td align='center'><input type='text' name='g' value='{$e['nilai6']}' style='width:35px; text-align:center; padding:0px'></td>
                                                <td align='center'><input type='text' style='width:35px; background:#e3e3e3; border:1px solid #e3e3e3;' disabled></td>
                                                <td align='center'><input type='text' style='width:35px; background:#e3e3e3; border:1px solid #e3e3e3;' disabled></td>
                                                <td align='center'><input type='text' name='h' value='{$e['deskripsi']}' style='width:100%; padding:0px'></td>
                                                <td align='center'><input type='submit' name='simpan-keterampilan' class='btn btn-xs btn-primary' style='width:65px' value='$name'></td>
                                            </tr>
                                        </form>";
                                } else {
                                    echo "<form action='index.php?view=raport&act=listsiswasikap&jdwl={$_GET['jdwl']}&kd={$_GET['kd']}&id={$_GET['id']}&tahun={$_GET['tahun']}' method='POST'>
                                            <tr>
                                                <td>$no</td>
                                                <td style='font-size:12px' id='{$r['nisn']}'>{$r['nama']}</td>
                                                <input type='hidden' name='nisn' value='{$r['nisn']}'>
                                                <input type='hidden' name='id' value='{$e['id_nilai_pengetahuan']}'>
                                                <input type='hidden' name='status' value='$name'>
                                                <td align='center'><input type='text' name='a' style='width:35px; text-align:center; padding:0px'></td>
                                                <td align='center'><input type='text' name='b' style='width:35px; text-align:center; padding:0px'></td>
                                                <td align='center'><input type='text' name='c' style='width:35px; text-align:center; padding:0px'></td>
                                                <td align='center'><input type='text' name='d' style='width:35px; text-align:center; padding:0px'></td>
                                                <td align='center'><input type='text' name='e' style='width:35px; text-align:center; padding:0px'></td>
                                                <td align='center'><input type='text' name='f' style='width:35px; text-align:center; padding:0px'></td>
                                                <td align='center'><input type='text' name='g' style='width:35px; text-align:center; padding:0px'></td>
                                                <td align='center'><input type='text' style='width:35px; background:#e3e3e3; border:1px solid #e3e3e3;' disabled></td>
                                                <td align='center'><input type='text' style='width:35px; background:#e3e3e3; border:1px solid #e3e3e3;' disabled></td>
                                                <td align='center'><input type='text' name='h' style='width:100%; padding:0px'></td>
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
        <td align='center'>{$n['kd']}</td>
        <td align='center'>{$n['nilai1']}</td>
        <td align='center'>{$n['nilai2']}</td>
        <td align='center'>{$n['nilai3']}</td>
        <td align='center'>{$n['nilai4']}</td>
        <td align='center'>{$n['nilai5']}</td>
        <td align='center'>{$n['nilai6']}</td>
        <td align='center'>" . number_format($ratarata, 2) . "</td>
        <td align='center'>{$grade1['grade']}</td>
        <td>{$n['deskripsi']}</td>
        <td align='center'>
            <a href='index.php?view=raport&act=listsiswasikap&jdwl={$_GET['jdwl']}&kd={$_GET['kd']}&id={$_GET['id']}&tahun={$_GET['tahun']}&edit_keterampilan={$n['id_nilai_keterampilan']}&nisn={$r['nisn']}#{$r['nisn']}' class='btn btn-xs btn-success'>
                <span class='glyphicon glyphicon-edit'></span>
            </a>
            <a href='index.php?view=raport&act=listsiswasikap&jdwl={$_GET['jdwl']}&kd={$_GET['kd']}&id={$_GET['id']}&tahun={$_GET['tahun']}&delete_keterampilan={$n['id_nilai_keterampilan']}&nisn={$r['nisn']}' class='btn btn-xs btn-danger' onclick=\"return confirm('Apa anda yakin untuk hapus Data ini?')\">
                <span class='glyphicon glyphicon-remove'></span>
            </a>
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
        <td></td>
        <td></td>
        <td align='center' colspan='7'>Nilai Max/Min</td>
        <td align='center'>" . number_format($maxn['tertinggi']) . "</td>
        <td align='center'>{$grade2['grade']}</td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td align='center' colspan='7'>Raport</td>
        <td align='center'>" . number_format($rapn['raport']) . "</td>
        <td align='center'>{$grade3['grade']}</td>
        <td>{$maxn['deskripsi']}</td>
    </tr>";

$no++;

echo "</tbody>
        </table>

    </div>
</div>";

echo "</div>
    </div>
</div>
</div>";

?>
