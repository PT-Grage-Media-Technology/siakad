<?php 
    if (isset($_POST['simpan-pengetahuan'])){
      // Debugging: Cek data yang diterima
      
      if ($_POST['status']=='Update'){
        mysql_query("UPDATE rb_nilai_pengetahuan SET kd='$_POST[a]', nilai_ulangan_harian='$_POST[nilai_uh]', nilai2='$_POST[c]', nilai3='$_POST[d]', nilai4='$_POST[e]', nilai5='$_POST[f]', deskripsi='$_POST[g]' where id_nilai_pengetahuan='$_POST[id]'");
      }else{
        mysql_query("INSERT INTO rb_nilai_pengetahuan VALUES('','$_GET[jdwl]','$_POST[nisn]','$_POST[nilai_uh]','$_POST[sts]','$_POST[sas]','$_POST[nilai_akhir]','$_POST[nilai_tertinggi]','$_POST[nilai_terendah]','$_POST[deskripsi_tertinggi]','$_POST[deskripsi_terendah]','$_SESSION[id]','".date('Y-m-d H:i:s')."')");
      }
      echo "<script>document.location='index.php?view=raport&act=listsiswa&jdwl=$_GET[jdwl]&kd=$_GET[kd]&id=$_GET[id]&tahun=$_GET[tahun];</script>";
  }

  if (isset($_GET['delete_pengetahuan'])){
      // Debugging: Cek ID yang akan dihapus
      
      mysql_query("DELETE FROM rb_nilai_pengetahuan where id_nilai_pengetahuan='$_GET[delete_pengetahuan]'");
      echo "<script>document.location='index.php?view=raport&act=listsiswa&jdwl=$_GET[jdwl]&kd=$_GET[kd]&id=$_GET[id]&tahun=$_GET[tahun]#$_GET[nisn]';</script>";
  }

    $d = mysql_fetch_array(mysql_query("SELECT * FROM rb_kelas where kode_kelas='$_GET[id]'"));
    $m = mysql_fetch_array(mysql_query("SELECT * FROM rb_mata_pelajaran where kode_pelajaran='$_GET[kd]'"));
    echo "<div class='col-md-12'>
              <div class='box box-info'>
                <div class='box-header with-border'>
                  <h3 class='box-title'>Input Nilai Pengetahuan Siswa</b></h3>
                </div>
            
        <div class='panel-body' style='overflow-x: auto; display: block;'>
              <table class='table table-bordered table-striped'>
                                <tr>
                                  <th style='border:1px solid #e3e3e3' width='30px' rowspan='2'>No</th>
                                  <th style='border:1px solid #e3e3e3' width='170px' rowspan='2'>Nama Lengkap</th>
                                  <th style='border:1px solid #e3e3e3' colspan='3'><center>Penilaian</center></th>
                                  <th style='border:1px solid #e3e3e3; width:55px' rowspan='2'><center>Nilai Rapor</center></th>
                                  <th style='border:1px solid #e3e3e3; width:55px' rowspan='2'><center>Nilai Tertinggi</center></th>
                                  <th style='border:1px solid #e3e3e3' rowspan='2'><center>Deskripsi</center></th>
                                  <th style='border:1px solid #e3e3e3; width:55px' rowspan='2'><center>Nilai Terendah</center></th>
                                  <th style='border:1px solid #e3e3e3' rowspan='2'><center>Deskripsi</center></th>
                                  <th style='border:1px solid #e3e3e3; width:65px' rowspan='2'><center>Action</center></th>
                                </tr>
                                <tr>
                                  <th style='border:1px solid #e3e3e3; width:55px'><center>UH</center></th>
                                  <th style='border:1px solid #e3e3e3; width:55px'><center>STS</center></th>
                                  <th style='border:1px solid #e3e3e3; width:55px'><center>SAS</center></th>
                                </tr>
                              <tbody>";
                              $no = 1;
                              $tampil = mysql_query("SELECT * FROM rb_siswa where kode_kelas='$_GET[id]' ORDER BY id_siswa");
                              while($r=mysql_fetch_array($tampil)){
                                $nilaiUH = mysql_fetch_array(mysql_query("SELECT * FROM rb_nilai_srl WHERE kodejdwl='$_GET[jdwl]' AND nisn='$r[nisn]'"));
                                $nilaiSTS = mysql_fetch_array(mysql_query("SELECT * FROM rb_nilai_uts WHERE kodejdwl='$_GET[jdwl]' AND nisn='$r[nisn]'"));
                                $nilaiSAS = mysql_fetch_array(mysql_query("SELECT * FROM rb_nilai_sas WHERE kodejdwl='$_GET[jdwl]' AND nisn='$r[nisn]'"));
                                $nilaiAkhir = 0.5*$nilaiUH['nilai']+0.2*$nilaiSTS['angka_pengetahuan']+0.3*$nilaiSAS['nilai'];

                                echo"SELECT * FROM rb_rekap_srl WHERE kodejdwl='$_GET[jdwl]' AND nisn='$r[nisn]'";
                                var_dump($nilaiUH);
                                  if (isset($_GET['edit_pengetahuan'])){
                                      $e = mysql_fetch_array(mysql_query("SELECT * FROM rb_nilai_pengetahuan where id_nilai_pengetahuan='$_GET[edit_pengetahuan]'"));
                                      $name = 'Update';
                                  }else{
                                      $name = 'Simpan';
                                  }
                                  if ($_GET[nisn]==$r[nisn]){
                                    echo "<form action='index.php?view=raport&act=listsiswa&jdwl=$_GET[jdwl]&kd=$_GET[kd]&id=$_GET[id]&tahun=$_GET[tahun]' method='POST'>
                                      <tr>
                                        <td>$no</td>
                                        <td style='font-size:12px' id='$r[nisn]'>$r[nama]</td>
                                        <input type='hidden' name='nisn' value='$r[nisn]'>
                                        <input type='hidden' name='id' value='$e[id_nilai_pengetahuan]'>
                                        <input type='hidden' name='status' value='$name'>
                                        <td align=center><input type='number' name='nilai_uh' value='$nilaiUH[nilai]' style='width:35px; text-align:center; padding:0px'></td>
                                        <td align=center><input type='number' name='sts' value='$nilaiSTS[angka_pengetahuan]' style='width:35px; text-align:center; padding:0px'></td>
                                        <td align=center><input type='number' name='sas' value='$nilaiSAS[nilai]' style='width:35px; text-align:center; padding:0px'></td>
                                        <td align=center><input type='number' name='nilai_akhir' value='$nilaiAkhir' style='width:35px; border:1px solid #e3e3e3;'></td>
                                        <td align=center><input type='text' name='nilai_tertinggi' value='$nilaiUH[nilai_tertinggi]' style='width:35px; background:#e3e3e3; border:1px solid #e3e3e3;' disabled></td>
                                        <td align=center><input type='text' name='g' value='$e[deskripsi]' style='width:100%; padding:0px'></td>
                                        <td align=center><input type='text' name='nilai_terendah' value='$nilaiUH[nilai_terendah]' style='width:35px; background:#e3e3e3; border:1px solid #e3e3e3;' disabled></td>
                                        <td align=center><input type='text' name='g' value='$e[deskripsi]' style='width:100%; padding:0px'></td>
                                        <td align=center><input type='submit' name='simpan-pengetahuan' class='btn btn-xs btn-primary' style='width:65px' value='$name'></td>
                                      </tr>
                                      </form>";
                                  }else{
                                    echo "<form action='index.php?view=raport&act=listsiswa&jdwl=$_GET[jdwl]&kd=$_GET[kd]&id=$_GET[id]&tahun=$_GET[tahun]' method='POST'>
                                      <tr>
                                        <td>$no</td>
                                        <td style='font-size:12px' id='$r[nisn]'>$r[nama]</td>
                                        <input type='hidden' name='nisn' value='$r[nisn]'>
                                        <input type='hidden' name='id' value='$e[id_nilai_pengetahuan]'>
                                        <input type='hidden' name='status' value='$name'>
                                        <td align=center><input type='number' name='nilai_uh' value='$nilaiUH[nilai]' style='width:35px; text-align:center; padding:0px'></td>
                                        <td align=center><input type='number' name='sts' value='$nilaiSTS[angka_pengetahuan]' style='width:35px; text-align:center; padding:0px'></td>
                                        <td align=center><input type='number' name='sas' value='$nilaiSAS[nilai]' style='width:35px; text-align:center; padding:0px'></td>
                                        <td align=center><input type='number' name='nilai_akhir' value='$nilaiAkhir' style='width:35px; border:1px solid #e3e3e3;'></td>
                                        <td align=center><input type='number' name='nilai_tertinggi' value='$nilaiUH[nilai_tertinggi]' style='width:35px; background:#e3e3e3; border:1px solid #e3e3e3;'></td>
                                        <td align=center><input type='text' name='deskripsi_tertinggi' value='$e[deskripsi_tertinggi]' style='width:100%; padding:0px'></td>
                                        <td align=center><input type='number' name='nilai_terendah' value='$nilaiUH[nilai_terendah]' style='width:35px; background:#e3e3e3; border:1px solid #e3e3e3;'></td>
                                        <td align=center><input type='text' name='deskripsi_terendah' value='$e[deskripsi_terendah]' style='width:100%; padding:0px'></td>
                                        <td align=center><input type='submit' name='simpan-pengetahuan' class='btn btn-xs btn-primary' style='width:65px' value='$name'></td>
                                      </tr>
                                      </form>";
                                  }

                                    $pe = mysql_query("SELECT * FROM rb_nilai_pengetahuan where kodejdwl='$_GET[jdwl]' AND nisn='$r[nisn]'");
                                    while ($n = mysql_fetch_array($pe)){
                                    $ratarata = average(array($n[nilai1],$n[nilai2],$n[nilai3],$n[nilai4],$n[nilai5]));
                                    // $cekpredikat = mysql_num_rows(mysql_query("SELECT * FROM rb_predikat where kode_kelas='$_GET[id]'"));
                                    // if ($cekpredikat >= 1){
                                    //   $grade1 = mysql_fetch_array(mysql_query("SELECT * FROM `rb_predikat` where (".number_format($ratarata)." >=nilai_a) AND (".number_format($ratarata)." <= nilai_b) AND kode_kelas='$_GET[id]'"));
                                    // }else{
                                    //   $grade1 = mysql_fetch_array(mysql_query("SELECT * FROM `rb_predikat` where (".number_format($ratarata)." >=nilai_a) AND (".number_format($ratarata)." <= nilai_b) AND kode_kelas='0'"));
                                    // }
                                    
                                      echo "<tr>
                                              <td></td>
                                              <td></td>
                                              <td align=center>$n[kd]</td>
                                              <td align=center>$n[nilai1]</td>
                                              <td align=center>$n[nilai2]</td>
                                              <td align=center>$n[nilai3]</td>
                                              <td align=center>".number_format($ratarata)."</td>
                                              <td>$n[deskripsi]</td>
                                              <td align=center><a href='index.php?view=raport&act=listsiswasikap&jdwl=".$_GET[jdwl]."&kd=".$_GET[kd]."&id=".$_GET[id]."&tahun=".$_GET[tahun]."&edit_pengetahuan=".$n[id_nilai_pengetahuan]."&nisn=".$r[nisn]."#$r[nisn]' class='btn btn-xs btn-success'><span class='glyphicon glyphicon-edit'></span></a>
                                                              <a href='index.php?view=raport&act=listsiswasikap&jdwl=".$_GET[jdwl]."&kd=".$_GET[kd]."&id=".$_GET[id]."&tahun=".$_GET[tahun]."&delete_pengetahuan=".$n[id_nilai_pengetahuan]."&nisn=".$r[nisn]."' class='btn btn-xs btn-danger' onclick=\"return confirm('Apa anda yakin untuk hapus Data ini?')\"><span class='glyphicon glyphicon-remove'></span></a></td>
                                            </tr>";
                                    }
                                      $maxn = mysql_fetch_array(mysql_query("SELECT ((nilai1+nilai2+nilai3+nilai4+nilai5)/5) as rata_rata, deskripsi FROM rb_nilai_pengetahuan where kodejdwl='$_GET[jdwl]' AND nisn='$r[nisn]' ORDER BY rata_rata DESC LIMIT 1"));
                                      $cekpredikat1 = mysql_num_rows(mysql_query("SELECT * FROM rb_predikat where kode_kelas='$_GET[id]'"));
                                      // if ($cekpredikat1 >= 1){
                                      //   $grade2 = mysql_fetch_array(mysql_query("SELECT * FROM `rb_predikat` where (".number_format($maxn[rata_rata])." >=nilai_a) AND (".number_format($maxn[rata_rata])." <= nilai_b) AND kode_kelas='$_GET[id]'"));
                                      // }else{
                                      //   $grade2 = mysql_fetch_array(mysql_query("SELECT * FROM `rb_predikat` where (".number_format($maxn[rata_rata])." >=nilai_a) AND (".number_format($maxn[rata_rata])." <= nilai_b) AND kode_kelas='0'"));
                                      // }
                                      
                                      $rapn = mysql_fetch_array(mysql_query("SELECT sum((nilai1+nilai2+nilai3+nilai4+nilai5)/5)/count(nisn) as raport FROM rb_nilai_pengetahuan where kodejdwl='$_GET[jdwl]' AND nisn='$r[nisn]'"));
                                      $cekpredikat2 = mysql_num_rows(mysql_query("SELECT * FROM rb_predikat where kode_kelas='$_GET[id]'"));
                                      // if ($cekpredikat2 >= 1){
                                      //   $grade3 = mysql_fetch_array(mysql_query("SELECT * FROM `rb_predikat` where (".number_format($rapn[raport])." >=nilai_a) AND (".number_format($rapn[raport])." <= nilai_b) AND kode_kelas='$_GET[id]'"));
                                      // }else{
                                      //   $grade3 = mysql_fetch_array(mysql_query("SELECT * FROM `rb_predikat` where (".number_format($rapn[raport])." >=nilai_a) AND (".number_format($rapn[raport])." <= nilai_b) AND kode_kelas='0'"));
                                      // }

                                      echo "<tr>
                                              <td></td><td></td>
                                              <td align=center colspan='6'>Nilai Max/Min</td>
                                              <td align=center>".number_format($maxn[rata_rata])."</td>
                                            </tr>
                                            <tr>
                                              <td></td><td></td>
                                              <td align=center colspan='6'>Raport</td>
                                              <td align=center>".number_format($rapn[raport])."</td>
                                            </tr>";
                                  $no++;
                                }

                                echo "</tbody>
                            </table>
                       
                        </div>
        </div>
      </div>";
?>