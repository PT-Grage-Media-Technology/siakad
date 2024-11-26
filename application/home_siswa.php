<?php if ($_GET[act] == '') { ?>
  <div class="col-xs-12">
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
                        <td><a class='btn btn-success btn-xs' title='Lihat Data' href='index.php?view=home&act=detailtujuan&kodejdwl=$r[kodejdwl]'><span class='glyphicon glyphicon-list'></span> Detail</a></td>
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
  $tampil = mysql_query("SELECT * FROM rb_journal_list z JOIN rb_guru t ON z.users=t.nip WHERE z.kodejdwl='$_GET[kodejdwl]'");
  $no = 1;
  while ($r = mysql_fetch_array($tampil)) {
    // var_dump($r);
    echo "<tr><td>$no</td>
                            <td>$r[hari]</td>
                            <td>$r[tanggal]</td>
                            <td>$r[jam_ke]</td>
                            <td>$r[nama_guru]</td>
                            <td>$r[materi]</td>
                            <td>$r[keterangan]</td>
                            <td><a class='btn btn-success btn-xs' title='Lihat Data' href='index.php?view=home&act=detailpembelajaran&kodejdwl=$r[kodejdwl]&tanggal=$r[tanggal]&jam_ke=$r[jam_ke]'><span class='glyphicon glyphicon-list'></span> Detail</a></td>
                        </tr>";
    $no++;
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
                      <tr><th width='120px' scope='row'>Nama Kelas</th>               <td>$d[nama_kelas]</td></tr>
                      <tr><th scope='row'>Mata Pelajaran</th>           <td>$d[namamatapelajaran]</td></tr>
                      <tr><th scope='row'>Materi</th>           <td>$d[materi]</td></tr>
                      <tr><th scope='row'>Keterangan</th>    
                             <td>";
      
                         // Validasi jika keterangan adalah link
                         if (filter_var($d['keterangan'], FILTER_VALIDATE_URL)) {
                             echo "<a href='{$d['keterangan']}' target='_blank'>{$d['keterangan']}</a>";
                         } else {
                             echo $d['keterangan'];
                         }
                       echo"</td>
                       </tr>
                        

                        
                    </tbody>
                </table>
                </div>";

                echo"<img src='$d[file]' alt='Gambar' style='width:500px; height:500px; text-align:center;'>";



                

              
  echo "<tbody>
                </table>
              </div>
              </div>
          </div>";

          echo"<div class='col-12'>  
            <div class='box'>
              <div class='box-header'>
                <h3 class='box-title'>Refleksi</h3>
              </div>
              <table id='example3' class='table table-bordered table-striped'>
                    <thead>
                      <tr>
                        <th style='width:20px'>No</th>
                        <th>Pertanyaan</th>
                      </tr>
                    </thead>
                    <tbody>
                    <tr><td>1</td>
                              <td>Apa Kesan dan Pesan</td>
                          </tr>

                          <tr><td></td>
                                  <input type='hidden' value='$t[kode_kelas]' name='kelas".$no."'>
                                  <input type='hidden' value='$r[id_pertanyaan_penilaian]' name='id".$no."'>
                              <td><textarea style='height:60px; width:100%' class='form-control' name='jawab".$no."' placeholder='Tulis Jawaban disini..'>$jwb[jawaban]</textarea></td>
                          </tr>
                    </tbody>
              </table>
              </div>
              </div>
              ";
}
?>