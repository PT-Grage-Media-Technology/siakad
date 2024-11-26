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
                            <td><a class='btn btn-success btn-xs' title='Lihat Data' href='index.php?view=home&act=detailpembelajaran&kodejdwl=$r[kodejdwl]'><span class='glyphicon glyphicon-list'></span> Detail</a></td>
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
  $m = mysql_query("SELECT * FROM rb_rekap_absen_guru a JOIN rb_guru b ON a.nip=b.nip WHERE a.nip=$_GET[nip] AND DAY(a.tanggal) = '$_GET[tanggal]' AND MONTH(a.tanggal) = '$_GET[bulan]'");
  $cek_absen = mysql_query("SELECT * FROM rb_rekap_absen_guru WHERE id_absensi = '$_GET[id_absen]' AND status=1 AND DAY(tanggal) = '$_GET[tanggal]' AND MONTH(tanggal) = '$_GET[bulan]'");
  // $sudah_disetujui = mysql_num_rows($cek_absen) > 0;
  // Tampilkan data yang diambil
  if ($data = mysql_fetch_array($m)) {
      // var_dump($data);
      echo "<div class='col-md-12'>
            <div class='box box-info'>
              <div class='box-header with-border'>
                <h3 class='box-title'>detail</b></h3>";
  echo "  </div>
            <div class='box-body'>";
         
            echo "
            <div style='display: flex; align-items: center;'>
                <div style='text-align: left; flex: 1;'>
                    <img src='bukti_tidak_hadir/" . $data['foto_bukti'] . "' style='max-width: 40%; height: auto;'>
                    <h2>Identitas Guru</h2>
                    <div style='margin-top: 20px;'>
                        <table class='table table-bordered' width='30%'>
          <tr>
              <td>NIP: {$data['nip']}</td>
          </tr>
          <tr>
              <td>Nama Guru: {$data['nama_guru']}</td>
          </tr>
          <tr>
              <td>Kehadiran: {$data['kode_kehadiran']}</td>
          </tr>
          <tr>
              <td>Tanggal: " . tgl_indo($data['tanggal']) . "</td>
          </tr>
        </table>
          ";
              if(!$cek_absen){

                  echo"<a href='index.php?view=absensiguru&act=setujui&id_absen=$_GET[id_absen]&nip=$_GET[nip]&bulan=$_GET[bulan]&tanggal=$_GET[tanggal]' class='btn btn-success' title='Setujui'><i class='fa fa-check'></i></a>
                  <a href='' class='btn btn-danger' title='Hapus' onclick='return confirm(\"Apakah Anda yakin ingin menghapus?\")'><i class='fa fa-times'></i></a>
                  ";
              }
              echo"
                    </div>
                </div>
            </div>";
            
  echo"</div>";

  echo" <div class='box-body'>
          <div class='table-responsive'>

              <table id='example' class='table table-bordered table-striped'>
                  <thead>
                      <tr>
                          <th style='width:20px'>No</th>
                          <th>Nip</th>
                          <th>Guru</th>
                          <th>Guru Pengganti</th>
                          <th>Mapel</th>
                          <th>Tanggal</th>
                          <th>Action</th>

                      </tr>
                  </thead>
                  <tbody>";
                  

                      $tampil = mysql_query("SELECT * FROM rb_journal_list a JOIN rb_guru b ON a.users=b.nip JOIN rb_jadwal_pelajaran c ON c.kodejdwl = a.kodejdwl JOIN rb_mata_pelajaran d ON c.kode_pelajaran=d.kode_pelajaran JOIN rb_tahun_akademik e ON e.id_tahun_akademik = c.id_tahun_akademik JOIN rb_jadwal_pelajaran f ON a.kodejdwl=f.kodejdwl WHERE a.users=$_GET[nip] AND MONTH(a.tanggal)=$_GET[bulan] AND DAY(a.tanggal)=$_GET[tanggal]");
                      $guru_pengganti = mysql_query("SELECT * FROM rb_journal_list a JOIN rb_guru b ON a.pengganti=b.nip ");

                      $no = 1;
                      if (mysql_num_rows($tampil) > 0 && $cek_absen) { // Memeriksa apakah ada data dan sudah disetujui
                          while ($r = mysql_fetch_array($tampil)) {
                              // var_dump($r);
                              echo "<tr><td>$no</td>";
                              if (!empty($r['pengganti'])) { // Memeriksa apakah kolom pengganti tidak kosong
                                  echo "<td>{$r['pengganti']}</td>";
                              } else {
                                  echo "<td>{$r['users']}</td>";
                              }
                              
                              echo"<td>$r[nama_guru]</td>";
                              if ($row = mysql_fetch_array($guru_pengganti)) {
                                  echo"<td>$row[nama_guru]</td>";
                              }
                   

                              echo"<td>$r[namamatapelajaran]</td>
                              <td>" . tgl_indo($r['tanggal']) . "</td>
                              <td>$r[kode_kehadiran] </td>
                              <td>
                                <a href='index.php?view=absensiswa&act=tampilabsen&id=$r[kode_kelas]&kd=$r[kode_pelajaran]&idjr=$r[kodejdwl]&tgl=$r[tanggal]&jam=$r[jam_ke]' class='btn btn-success' title='detail'><i class='fa fa-eye'></i>Buka Absensi</a>";
                              
                                if(!$r['pengganti']){
                                  echo"<a href='index.php?view=absensiguru&act=gantikan&id=$r[id_journal]&jdwl=$r[kodejdwl]&nip=$r[users]&bulan=$_GET[bulan]&tanggal=$_GET[tanggal]' class='btn btn-success' title='detail'><i class='fa fa-eye'></i>Gantikan Mengajar</a>";
                                }
                                echo"<a href='' class='btn btn-danger' title='Hapus' onclick='return confirm(\"Apakah Anda yakin ingin menghapus?\")'><i class='fa fa-times'></i></a>
                              </td>";

                              echo "</tr>";
                              $no++;
                          }
                      } else {
                          echo "<tr><td colspan='6' style='text-align:center;'>Tidak ada data</td></tr>"; // Menampilkan pesan jika tidak ada data
                      }
                    
                 echo"</tbody>
              </table>
          </div>
      </div><!-- /.box-body -->

  </div>
</div>";
  } else {
      echo "Data tidak ditemukan.";
  }
}
?>