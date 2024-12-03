<?php if ($_GET[act] == '') { ?>
  <div class="col-12">
    <div class="box">

      <div class="box-header">
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
        ?>

        <h3 class="box-title">
          <?php if (isset($_GET[tahun])) {
            echo "Jadwal Pelajaran";
          } else {
            echo "Jadwal Pelajaran hari ini " . date('Y');
          } ?>
        </h3>
       <form style='margin-right:5px; margin-top:0px' class='pull-right' action='' method='GET'>
    <!-- Tambahkan hidden input untuk menyimpan parameter view -->
    <select name='tahun' style='padding:4px' onchange='this.form.submit()'>
        <option value=''>- Pilih Tahun Akademik -</option>
        <?php
        while ($k = mysql_fetch_array($tahun)) {
            $selected = ($tahun_dipilih == $k['id_tahun_akademik']) ? 'selected' : '';
            echo "<option value='{$k['id_tahun_akademik']}' $selected>{$k['nama_tahun']}</option>";
        }
        ?>
    </select>
    </form>
    </div><!-- /.box-header -->

      <div class="box-body">
        <div class="table-responsive">
          <table id="example1" class="table table-bordered table-striped">
            <thead>
              <tr>
                <th style='width:20px'>No</th>
                <th>Kode Pelajaran</th>
                <th>Jadwal Pelajaran</th>
                <th>Kelas</th>
                <th>Guru</th>
                <th>Hari</th>
                <th>Mulai</th>
                <th>Selesai</th>
                <th>Ruang</th>
                <th>Semester</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php
              if (isset($_GET[tahun])) {
                $tampil = mysql_query("SELECT a.*, e.nama_kelas, b.namamatapelajaran, b.kode_pelajaran, c.nama_guru, d.nama_ruangan FROM rb_jadwal_pelajaran a 
                                      JOIN rb_mata_pelajaran b ON a.kode_pelajaran=b.kode_pelajaran
                                        JOIN rb_guru c ON a.nip=c.nip 
                                          JOIN rb_ruangan d ON a.kode_ruangan=d.kode_ruangan
                                            JOIN rb_kelas e ON a.kode_kelas=e.kode_kelas 
                                            where a.kode_kelas='$_SESSION[kode_kelas]' AND a.id_tahun_akademik='$tahun_dipilih' ORDER BY a.hari DESC");
              } else {
                $tampil = mysql_query("SELECT a.*, e.nama_kelas, b.namamatapelajaran, b.kode_pelajaran, c.nama_guru, d.nama_ruangan FROM rb_jadwal_pelajaran a 
                                      JOIN rb_mata_pelajaran b ON a.kode_pelajaran=b.kode_pelajaran
                                        JOIN rb_guru c ON a.nip=c.nip 
                                          JOIN rb_ruangan d ON a.kode_ruangan=d.kode_ruangan
                                          JOIN rb_kelas e ON a.kode_kelas=e.kode_kelas 
                                            where a.kode_kelas='$_SESSION[kode_kelas]' AND a.id_tahun_akademik='$tahun_dipilih' ORDER BY a.hari DESC");
              }
              $no = 1;
              while ($r = mysql_fetch_array($tampil)) {
                // var_dump($r);
                echo "<tr><td>$no</td>
                        <td>$r[kode_pelajaran]</td>
                        <td>$r[namamatapelajaran]</td>
                        <td>$r[nama_kelas]</td>
                        <td>$r[nama_guru]</td>
                        <td>$r[hari]</td>
                        <td>$r[jam_mulai]</td>
                        <td>$r[jam_selesai]</td>
                        <td>$r[nama_ruangan]</td>
                        <td>$r[id_tahun_akademik]</td>
                        <td><a class='btn btn-success btn-xs' title='Lihat Data' href='index.php?view=home&act=detailtujuan&kodejdwl=$r[kodejdwl]'><span class='glyphicon glyphicon-list'></span> Tujuan Pembelajaran</a></td>
                    </tr>";
                $no++;
              }
              ?>
            </tbody>
          </table>
        </div>
      </div><!-- /.box-body -->

    </div>
  </div>


<?php
} elseif ($_GET[act] == 'detailtujuan') {
  $d = mysql_fetch_array(mysql_query("SELECT * FROM rb_jadwal_pelajaran a JOIN rb_mata_pelajaran b ON a.kode_pelajaran=b.kode_pelajaran JOIN rb_kelas c ON a.kode_kelas=c.kode_kelas where a.kodejdwl='$_GET[kodejdwl]'"));
  echo "<div class='col-12'>  
            <div class='box'>
              <div class='box-header'>
                <h3 class='box-title'>Detail Tujuan Pembelajaran</h3>
              </div>
              <div class='box-body'>
                <div class='col-12'>
                <table class='table table-condensed table-hover'>
                    <tbody>
                      <input type='hidden' name='id' value='$d[kodekelas]'>
                      <tr><th width='120px' scope='row'>Kode Kelas</th> <td>$d[kode_kelas]</td></tr>
                      <tr><th scope='row'>Nama Kelas</th>               <td>$d[nama_kelas]</td></tr>
                      <tr><th scope='row'>Mata Pelajaran</th>           <td>$d[namamatapelajaran]</td></tr>
                    </tbody>
                </table>
                </div>

              <div class='box-body'>
              <div class='table-responsive'>
                <table class='table table-bordered table-striped'>
                  <thead>
                    <tr>
                       <th style='width:20px'>No</th>
                        <th>Hari</th>
                        <th style='width:90px'>Tanggal</th>
                        <th style='width:70px'>Jam Ke</th>
                        <th style='width:220px' align=center>Guru</th>
                        <th style='width:220px'>Materi</th>
                        <th>Keterangan</th>
                        <th>Aksi</th>
                    </tr>
                  </thead>
                  <tbody>";
  // $tampil = mysql_query("SELECT * FROM rb_kompetensi_dasar z JOIN rb_jadwal_pelajaran a ON z.kodejdwl=a.kodejdwl JOIN rb_kelas b ON a.kode_kelas=b.kode_kelas JOIN rb_mata_pelajaran c ON a.kode_pelajaran=c.kode_pelajaran where a.kodejdwl='$_GET[kodejdwl]' ORDER BY z.id_kompetensi_dasar DESC");
  $tampil = mysql_query("SELECT * FROM rb_journal_list z JOIN rb_guru t ON z.users=t.nip JOIN rb_forum_topic ft ON z.materi=ft.judul_topic WHERE z.kodejdwl='$_GET[kodejdwl]'");
  $no = 1;
  if(mysql_num_rows($tampil) == 0){
    echo "<tr><td colspan='8' style='text-align:center;'>Tidak ada data</td></tr>";
  }else{

    while ($r = mysql_fetch_array($tampil)) {
      // var_dump($r);
      echo "<tr><td>$no</td>
                              <td>$r[hari]</td>
                              <td>$r[tanggal]</td>
                              <td>$r[jam_ke]</td>
                              <td>$r[nama_guru]</td>
                              <td>$r[materi]</td>
                              <td>$r[keterangan]</td>
                              <td><a class='btn btn-success btn-xs' title='Lihat Data' href='index.php?view=home&act=detailpembelajaran&kodejdwl=$r[kodejdwl]&tanggal=$r[tanggal]&jam_ke=$r[jam_ke]&idtopic=$r[id_forum_topic]&id_journal=$r[id_journal]'><span class='glyphicon glyphicon-list'></span> Detail</a></td>
                          </tr>";
      $no++;
    }
  }
  echo "<tbody>
                </table>
              </div>
              </div>
          </div>";
}
elseif ($_GET[act] == 'detailpembelajaran') {
  $hari_ini = date('d');
  $d = mysql_fetch_array(mysql_query("SELECT * FROM rb_jadwal_pelajaran a JOIN rb_mata_pelajaran b ON a.kode_pelajaran=b.kode_pelajaran JOIN rb_kelas c ON a.kode_kelas=c.kode_kelas JOIN rb_journal_list d ON a.kodejdwl=d.kodejdwl  where a.kodejdwl='$_GET[kodejdwl]' AND DAY(d.tanggal)=DAY('$_GET[tanggal]') AND jam_ke='$_GET[jam_ke]'"));
  // var_dump($d);
  echo "
<div class='col-12'>  
    <div class='box'>
        <div class='box-header'>
            <h3 class='box-title'>Detail Tujuan Pembelajaran</h3>
        </div>
        <div class='box-body'>
            <div class='col-12'>
                <table class='table table-condensed table-hover'>
                    <tbody>
                        <input type='hidden' name='id' value='{$d['kodekelas']}'>
                        <tr>
                            <th width='120px' scope='row'>Nama Kelas</th>
                            <td>{$d['nama_kelas']}</td>
                        </tr>
                        <tr>
                            <th scope='row'>Mata Pelajaran</th>
                            <td>{$d['namamatapelajaran']}</td>
                        </tr>
                        <tr>
                            <th scope='row'>Materi</th>
                            <td>{$d['materi']}</td>
                        </tr>
                        <tr>
                            <th scope='row'>Keterangan</th>
                            <td>";
                            
if (filter_var($d['keterangan'], FILTER_VALIDATE_URL)) {
    echo "<a href='{$d['keterangan']}' target='_blank'>{$d['keterangan']}</a>";
} else {
    echo $d['keterangan'];
}

echo "
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class='col-12'>
                <img src='{$d['file']}' alt='Gambar' class='img-responsive' style='max-width:100%; height:auto;'>
            </div>
        </div>
    </div>
</div>";


    if (isset($_POST['submit'])){
       $jml = mysql_fetch_array(mysql_query("SELECT count(*) as jmlp FROM `rb_pertanyaan_penilaian` where status='refleksi'"));
       $n = $jml['jmlp'];
       for ($i=0; $i<=$n; $i++){
         if (isset($_POST['jawab'.$i])){
           $jawab = $_POST['jawab'.$i];
           $pertanyaan = $_POST['id'.$i];
           $kelas = $_POST['kelas'.$i];

           // Cek apakah sudah ada jawaban untuk nisn ini
           $cek_jawaban = mysql_fetch_array(mysql_query("SELECT count(*) as total FROM rb_pertanyaan_penilaian_jawab WHERE nisn='$_SESSION[id]' AND id_pertanyaan_penilaian='$pertanyaan' AND status='refleksi' AND kode_kelas='$kelas'"));
           if ($cek_jawaban['total'] > 0) {
               echo "
               <div class='alert alert-warning alert-dismissible fade show' role='alert'>
                   <strong>Peringatan!</strong> Anda sudah memberikan jawaban untuk pertanyaan ini.
                   <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                       <span aria-hidden='true'>&times;</span>
                   </button>
               </div>
               ";
               continue; // Lewati input jika sudah ada
           }

           mysql_query("INSERT INTO rb_pertanyaan_penilaian_jawab VALUES('','$pertanyaan','$_SESSION[id]','','$jawab','$_GET[kodejdwl]','refleksi','$kelas','".date('Y-m-d H:i:s')."','$_GET[id_journal]')");
         }
       }
       echo "<script>window.alert('Sukses Simpan Jawaban Penilaian refleksi...');
                history.back();</script>";
    }
           echo" <div class='col-12'>  
              <div class='box'>
              <form action='' method='POST'>
                <div class='box-header'>
                  <h3 class='box-title'>Pertanyaan Refleksi </h3>
                </div><!-- /.box-header -->
                <div class='box-body'>
                  <table id='example3' class='table table-bordered table-striped'>
                    <thead>
                      <tr>
                        <th style='width:20px'>No</th>
                        <th>Pertanyaan</th>
                      </tr>
                    </thead>
                    <tbody>";
           
                    $t = mysql_fetch_array(mysql_query("SELECT * FROM rb_siswa where nisn='$_SESSION[id]'"));
                    $tampil = mysql_query("SELECT * FROM rb_pertanyaan_penilaian where status='refleksi' ORDER BY id_pertanyaan_penilaian DESC");
                    $no = 1;
                    while($r=mysql_fetch_array($tampil)){
                    $jwb = mysql_fetch_array(mysql_query("SELECT * FROM rb_pertanyaan_penilaian_jawab where nisn='$_SESSION[id]' AND id_pertanyaan_penilaian='$r[id_pertanyaan_penilaian]' AND status='refleksi' AND kode_kelas='$t[kode_kelas]' AND id_journal='$_GET[id_journal]'"));
                    echo "<tr><td>$no</td>
                              <td>$r[pertanyaan]</td>
                          </tr>

                          <tr><td></td>
                                  <input type='hidden' value='$t[kode_kelas]' name='kelas".$no."'>
                                  <input type='hidden' value='$r[id_pertanyaan_penilaian]' name='id".$no."'>
                                  <td>
                                <select class='form-control' name='jawab".$no."' style='background: none; width: 300px;'> <!-- Ganti width sesuai kebutuhan -->
                                  <option value='' disabled>- Pilih Rating -</option>";
                                  
                                  // Ambil nilai bintang dari rb_rating
                                  $rating_query = mysql_query("SELECT * FROM rb_rating");
                                  while ($rating = mysql_fetch_array($rating_query)) {
                                      $stars = str_repeat('⭐', $rating['bintang']); // Mengulangi bintang sesuai nilai
                                      echo "<option value='{$rating['kesan']}' style='background: url(star-icon.png) no-repeat left center; padding-left: 20px;'>$stars - {$rating['kesan']}</option>";
                                  }
                    echo "    </select>
                              </td>
                          </tr>";
                      $no++;
                      }
            
                    echo"</tbody>
                  </table>
                  <input type='submit' name='submit' value='Simpan Semua Jawaban' class='pull-left btn btn-primary btn-sm'>
                </div><!-- /.box-body -->
              </form>
              </div><!-- /.box -->
            </div>";

            echo"<div class='col-12'>  
            <div class='box'>";
                      $topic = mysql_fetch_array(mysql_query("SELECT * FROM rb_forum_topic a 
                                  JOIN rb_jadwal_pelajaran b ON a.kodejdwl=b.kodejdwl
                                    JOIN rb_guru c ON b.nip=c.nip where a.id_forum_topic='$_GET[idtopic]'"));
                    
                      if (isset($_GET[deletetopic])) {
                        mysql_query("DELETE FROM rb_forum_topic where id_forum_topic='$_GET[idtopic]'");

                        echo "<script>document.location='index.php?view=forum&act=detailtopic&jdwl=" . $_GET[jdwl] . "&idtopic=" . $_GET[idtopic] . "&id=" . $_GET[id] . "&kd=" . $_GET[kd] . "';</script>";
                      }
                    
                      if (isset($_GET[deletekomentar])) {
                        mysql_query("DELETE FROM rb_forum_komentar where id_forum_komentar='$_GET[deletekomentar]' AND id_forum_topic='$_GET[idtopic]'");
                        $kodejdwl = $_GET['kodejdwl'];
                        $tanggal = $_GET['tanggal'];
                        $jam_ke = $_GET['jam_ke'];
                        $idtopic = $_GET['idtopic'];
                        echo "<script>document.location='index.php?view=home&act=detailpembelajaran&kodejdwl=$kodejdwl&tanggal=$tanggal&jam_ke=$jam_ke&idtopic=$idtopic';</script>";
                      }
                    
                      echo "<div class='col-12'>
                                  <div class='box box-success'>
                                    <div class='box-header'>
                                      <i class='fa fa-comments-o'></i>
                                      <h3 class='box-title'>Topic Forum - $topic[judul_topic] </h3> 
                                    </div>
                                    <div class='box-body chat' id='chat-box'>
                                      <div class='item'>";
                      if (trim($topic[foto]) == '') {
                        echo "<img src='foto_siswa/no-image.jpg' alt='user image' class='online'>";
                      } else {
                        echo "<img src='foto_pegawai/$topic[foto]' alt='user image' class='online'>";
                      }
                      echo "<p class='message'>
                                          <a href='index.php?view=guru&act=detailguru&id=$topic[nip]' class='name'>
                                            <small class='text-muted pull-right'><i class='fa fa-clock-o'></i> $topic[waktu] WIB</small>
                                            $topic[nama_guru] (Guru)
                                          </a>
                                          $topic[isi_topic]</p>
                                      </div>
                                  </div>
                              </div>
                    
                              <div class='col-12'>
                                  <div class='box box-info'>
                                    <div class='box-body chat' id='chat-box'>";
                      $komentar = mysql_query("SELECT * FROM rb_forum_komentar a 
                                                  LEFT JOIN rb_siswa b ON a.nisn_nip=b.nisn
                                                    where a.id_forum_topic='$_GET[idtopic]' 
                                                      ORDER BY a.id_forum_komentar ASC");
                      while ($k = mysql_fetch_array($komentar)) {
                        if ($k[nama] == '') {
                          echo "<div class='item'>";
                          if (trim($topic[foto]) == '') {
                            echo "<img src='foto_siswa/no-image.jpg' alt='user image' class='online'>";
                          } else {
                            echo "<img src='foto_pegawai/$topic[foto]' alt='user image' class='online'>";
                          }
                          echo "<p class='message'><small class='text-muted'>
                                                    <a href='index.php?view=home&act=detailpembelajaran&kodejdwl=$_GET[kodejdwl]&tanggal=$_GET[tanggal]&jam_ke=$_GET[jam_ke]&idtopic=$_GET[idtopic]&deletekomentar=$k[id_forum_komentar]' onclick=\"return confirm('Apakah anda Yakin Data ini Dihapus?')\"><i class='fa fa-remove pull-right'></i></a> <i class='fa fa-clock-o'></i> $k[waktu_komentar] WIB </small>
                                                    <a href='#' class='name'>$topic[nama_guru] (Guru)</a> $k[isi_komentar]</p>
                                            </div>";
                        } else {
                          echo "<div class='item'>";
                          if (trim($k[foto]) == '') {
                            echo "<img src='foto_siswa/no-image.jpg' alt='user image' class='offline'>";
                          } else {
                            echo "<img src='foto_siswa/$k[foto]' alt='user image' class='offline'>";
                          }
                          echo "<p class='message'><small class='text-muted'>
                                                    <a href='index.php?view=home&act=detailpembelajaran&kodejdwl=$_GET[kodejdwl]&tanggal=$_GET[tanggal]&jam_ke=$_GET[jam_ke]&idtopic=$_GET[idtopic]&deletekomentar=$k[id_forum_komentar]''><i class='fa fa-remove pull-right'></i></a> <i class='fa fa-clock-o'></i> $k[waktu_komentar] WIB</small> 
                                                    <a href='#' class='name'>$k[nama] (Siswa)</a>$k[isi_komentar]</p>
                                            </div>";
                        }
                      }
                    
                      echo "</div>
                                    <form action='' method='POST'>
                                    <div class='box-footer'>
                                      <div class='input-group'>
                                        <input class='form-control' name='a' placeholder='Tuliskan Komentar...'>
                                        <div class='input-group-btn'>
                                          <button type='submit' name='komentar' class='btn btn-success'><i class='fa fa-send'></i></button>
                                        </div>
                                      </div>
                                    </div>
                                    </form>
                                  </div>
                              </div>
                              </div>
                              </div>";
                    
                      if (isset($_POST[komentar])) {
                        $waktu = date("Y-m-d H:i:s");
                        mysql_query("INSERT INTO rb_forum_komentar VALUES('','$_GET[idtopic]','$_SESSION[id]','$_POST[a]','$waktu')");
                        $kodejdwl = $_GET['kodejdwl'];
                        $tanggal = $_GET['tanggal'];
                        $jam_ke = $_GET['jam_ke'];
                        $idtopic = $_GET['idtopic'];
                        echo "<script>document.location='index.php?view=home&act=detailpembelajaran&kodejdwl=$kodejdwl&tanggal=$tanggal&jam_ke=$jam_ke&idtopic=$idtopic';</script>";
                      }
                    }
?>

<style>
  .table-responsive {
    overflow-x: auto;
    /* Hanya aktifkan scroll horizontal jika diperlukan */
  }

  @media (min-width: 768px) {
    .table-responsive {
      overflow-x: visible;
      /* Nonaktifkan scroll horizontal di desktop */
    }
  }
</style>
