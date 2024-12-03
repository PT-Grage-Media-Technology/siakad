<?php 
if ($_GET[act]==''){
// cek_session_admin();
cek_session_guru();

?>
            <div class="col-xs-12">  
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title"><?php if (isset($_GET[kelas]) AND isset($_GET[tahun])){ echo "Jadwal Pelajaran"; }else{ echo "Jadwal Pelajaran Pada Tahun ".date('Y'); } ?></h3>
                  <form style='margin-right:5px; margin-top:0px' class='pull-right' action='' method='GET'>
                    <input type="hidden" name='view' value='raportuts'>
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
                        <th>Aktif</th>
                        <?php
                        if (isset($_GET[tahun]) AND isset($_GET[tahun])){ 
                          if($_SESSION[level]!='kepala'){ 
                            echo "<th>Action</th>";
                          }
                        }  
                        ?>
                      </tr>
                    </thead>
                    <tbody>
                  <?php
                    if (isset($_GET[kelas]) AND isset($_GET[tahun])){
                      $tampil = mysql_query("SELECT a.*, e.nama_kelas, b.namamatapelajaran, b.kode_pelajaran, b.kode_kurikulum, c.nama_guru, d.nama_ruangan FROM rb_jadwal_pelajaran a 
                                            JOIN rb_mata_pelajaran b ON a.kode_pelajaran=b.kode_pelajaran
                                              JOIN rb_guru c ON a.nip=c.nip 
                                                JOIN rb_ruangan d ON a.kode_ruangan=d.kode_ruangan
                                                  JOIN rb_kelas e ON a.kode_kelas=e.kode_kelas 
                                                  where a.kode_kelas='$_GET[kelas]' 
                                                    AND a.id_tahun_akademik='$_GET[tahun]' 
                                                      AND b.kode_kurikulum='$kurikulum[kode_kurikulum]' ORDER BY a.hari DESC");
                    
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
                              <td>$r[nama_ruangan]</td>
                              <td>$r[aktif]</td>";
                              if (isset($_GET[tahun]) AND isset($_GET[kelas])){
                                if($_SESSION[level]!='kepala'){
                                  echo "<td style='width:70px !important'><center>
                                          <a class='btn btn-success btn-xs' title='Lihat Siswa' href='index.php?view=raportuts&act=listsiswa&jdwl=$r[kodejdwl]&kd=$r[kode_pelajaran]&id=$r[kode_kelas]&tahun=$_GET[tahun]'><span class='glyphicon glyphicon-th-list'></span> Input Nilai</a>
                                        </center></td>";
                                }
                              }
                            echo "</tr>";
                      $no++;
                      }
                  ?>
                    </tbody>
                  </table>
                  </div>
                </div><!-- /.box-body -->
                <?php 
                    if ($_GET[kelas] == '' AND $_GET[tahun] == ''){
                        echo "<center style='padding:60px; color:red'>Silahkan Memilih Tahun akademik dan Kelas Terlebih dahulu...</center>";
                    }
                ?>
                </div>
            </div>                               
<?php
}elseif($_GET[act]=='detailguru'){
cek_session_guru();
    include "raport/raport_uts_halaman_guru.php";

}elseif($_GET[act]=='listsiswa'){
cek_session_guru();
    if (isset($_POST[simpan])){
        $jumls = mysql_num_rows(mysql_query("SELECT * FROM rb_siswa where kode_kelas='$_GET[id]'"));
        for ($ia=1; $ia<=$jumls; $ia++){
          $a  = $_POST['a'.$ia];
          $b  = $_POST['b'.$ia];
          $nisn = $_POST['nisn'.$ia];
          if ($a != '' OR $b != ''){
            $cek = mysql_num_rows(mysql_query("SELECT * FROM rb_nilai_uts where kodejdwl='$_POST[jdwl]' AND nisn='$nisn'"));
            if ($cek >= '1'){
              mysql_query("UPDATE rb_nilai_uts SET angka_pengetahuan='$a', angka_keterampilan='$b' where kodejdwl='$_POST[jdwl]' AND nisn='$nisn'");
            }else{
              mysql_query("INSERT INTO rb_nilai_uts VALUES('','$_POST[jdwl]','$nisn','$a','','$b','','".date('Y-m-d H:i:s')."')");
            }
          }
        }
        echo "<script>document.location='index.php?view=raportuts&act=listsiswa&jdwl=$_GET[jdwl]&kd=$_GET[kd]&id=$_GET[id]&tahun=$_GET[tahun]';</script>";
    }

    $d = mysql_fetch_array(mysql_query("SELECT * FROM rb_kelas where kode_kelas='$_GET[id]'"));
    $m = mysql_fetch_array(mysql_query("SELECT * FROM rb_mata_pelajaran where kode_pelajaran='$_GET[kd]'"));
    echo "<div class='col-md-12'>
      <div class='box box-info'>
        <div class='box-header with-border'>
          <h3 class='box-title'>Input Nilai STS Siswa</b></h3>
          <form style='margin-right:5px; margin-top:0px' class='pull-right' action='' method='POST'>
            <input type='hidden' name='id' value='$_GET[id]'>
            <input type='hidden' name='jdwl' value='$_GET[jdwl]'>
        </div>
        <div class='box-body'>

          <div class='col-md-12'>
            <table class='table table-condensed table-hover'>
              <tbody>
                <input type='hidden' name='id' value='$s[kodekelas]'>
                <tr><th width='120px' scope='row'>Kode Kelas</th> <td>$d[kode_kelas]</td></tr>
                <tr><th scope='row'>Nama Kelas</th>               <td>$d[nama_kelas]</td></tr>
                <tr><th scope='row'>Mata Pelajaran</th>           <td>$m[namamatapelajaran]</td></tr>
              </tbody>
            </table>
          </div>

          <div class='col-md-12'>
            <div class='table-responsive'>
              <table class='table table-bordered table-striped'>
                <tr>
                  <th style='border:1px solid #e3e3e3' width='30px' rowspan='2'>No</th>
                  <th style='border:1px solid #e3e3e3' width='90px' rowspan='2'>NISN</th>
                  <th style='border:1px solid #e3e3e3' width='190px' rowspan='2'>Nama Lengkap</th>
                  <th style='border:1px solid #e3e3e3' colspan='2'><center>Nilai Akhir</center></th>
                  <th style='border:1px solid #e3e3e3' colspan='2'><center>Deskripsi</center></th>
                </tr>
            
                <tbody>";
                $no = 1;
                $tampil = mysql_query("SELECT * FROM rb_siswa where kode_kelas='$_GET[id]' ORDER BY id_siswa");
                while($r=mysql_fetch_array($tampil)){
                  $n = mysql_fetch_array(mysql_query("SELECT * FROM rb_nilai_uts where nisn='$r[nisn]' AND kodejdwl='$_GET[jdwl]'"));
                  $cekpredikat = mysql_num_rows(mysql_query("SELECT * FROM rb_predikat where kode_kelas='$_GET[id]'"));
                  if ($cekpredikat >= 1){
                    $grade1 = mysql_fetch_array(mysql_query("SELECT * FROM `rb_predikat` where ($n[angka_pengetahuan] >=nilai_a) AND ($n[angka_pengetahuan] <= nilai_b) AND kode_kelas='$_GET[id]'"));
                    $grade2 = mysql_fetch_array(mysql_query("SELECT * FROM `rb_predikat` where ($n[angka_keterampilan] >=nilai_a) AND ($n[angka_keterampilan] <= nilai_b) AND kode_kelas='$_GET[id]'"));
                  }else{
                    $grade1 = mysql_fetch_array(mysql_query("SELECT * FROM `rb_predikat` where ($n[angka_pengetahuan] >=nilai_a) AND ($n[angka_pengetahuan] <= nilai_b) AND kode_kelas='0'"));
                    $grade2 = mysql_fetch_array(mysql_query("SELECT * FROM `rb_predikat` where ($n[angka_keterampilan] >=nilai_a) AND ($n[angka_keterampilan] <= nilai_b) AND kode_kelas='0'"));
                  }
                    echo "<tr>
                          <td>$no</td>
                          <td>$r[nisn]</td>
                          <td>$r[nama]</td>
                          <input type='hidden' name='nisn".$no."' value='$r[nisn]'>
                          <td align=center colspan='2'><input type='number' name='a".$no."' value='$n[angka_pengetahuan]' style='width:90px; text-align:center; padding:0px' placeholder='-' colspan='2'></td>
                          <td align=center colspan='3'><textarea type='text' name='b' value='' style='width:350px; text-align:center; padding:20px' placeholder='-' colspan='2'></textarea></td>
                        </tr>";
                  $no++;
                  }

                echo "</tbody>
              </table>
            </div>
          </div>

          <div style='clear:both'></div>
          <div class='box-footer'>
            <button type='submit' name='simpan' class='btn btn-info'>Simpan</button>
          </div>
          </form>

      </div>";

}elseif($_GET[act]=='detailsiswa'){
cek_session_siswa();
    include "raport/raport_uts_halaman_siswa.php";
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

<!-- <th style='border:1px solid #e3e3e3'><center>Angka</center></th>
                  <th style='border:1px solid #e3e3e3'><center>Predikat</center></th>
                  <th style='border:1px solid #e3e3e3'><center>Angka</center></th>
                  <th style='border:1px solid #e3e3e3'><center>Predikat</center></th> -->


                  <!-- <input type='hidden' name='nisn".$no."' value='$r[nisn]'>
                          <td align=center><input type='number' name='a".$no."' value='$n[angka_pengetahuan]' style='width:90px; text-align:center; padding:0px' placeholder='-'></td>
                          <td align=center><input type='text' value='$grade1[grade]' style='width:90px; text-align:center; padding:0px' placeholder='-' disabled></td>
                          <td align=center><input type='number' name='b".$no."' value='$n[angka_keterampilan]' style='width:90px; text-align:center; padding:0px' placeholder='-'></td>
                          <td align=center><input type='text' value='$grade2[grade]' style='width:90px; text-align:center; padding:0px' placeholder='-' disabled></td> -->