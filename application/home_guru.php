<div class="col-xs-12">
  <div class="box">
    <div class="box-header">
      <h3 class="box-title"><?php if (isset($_GET[tahun])) {
        echo "Jadwal Mengajar Anda";
      } else {
        echo "Jadwal Mengajar Anda " . date('Y');
      } ?></h3>
      <?php
      // Ambil tahun akademik terbaru (id_tahun_akademik paling besar)
      $latest_year = mysql_fetch_array(mysql_query("SELECT id_tahun_akademik FROM rb_tahun_akademik ORDER BY id_tahun_akademik DESC LIMIT 1"));

                  // Jika tidak ada tahun akademik dipilih, set default ke tahun terbaru dan redirect
                  $tahun_dipilih = isset($_GET['tahun']) ? $_GET['tahun'] : $latest_year['id_tahun_akademik'];

                  // Jika tahun tidak ada di URL, redirect dengan tahun terbaru
                  // if (!isset($_GET['tahun'])) {
                  //   header("Location: ?tahun=" . $latest_year['id_tahun_akademik']);
                  //   exit();
                  // }
                  ?>

      <form style='margin-right:5px; margin-top:0px' class='pull-right' action='' method='GET'>
        <select name='tahun' style='padding:4px'>
          <?php
          echo "<option value=''>- Pilih Tahun Akademik -</option>";
          $tahun = mysql_query("SELECT * FROM rb_tahun_akademik");
          while ($k = mysql_fetch_array($tahun)) {
            // Jika tahun yang dipilih sama dengan tahun yang ada di query, set selected
            if ($tahun_dipilih == $k['id_tahun_akademik']) {
              echo "<option value='$k[id_tahun_akademik]' selected>$k[nama_tahun]</option>";
            } else {
              echo "<option value='$k[id_tahun_akademik]'>$k[nama_tahun]</option>";
            }
          }
          ?>
        </select>
        <input type="submit" style='margin-top:-4px' class='btn btn-success btn-sm' value='Lihat'>
      </form>


    </div><!-- /.box-header -->
    <div class="box-body">
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
            <th>Ruangan</th>
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
                                                  where a.nip='$_SESSION[id]' AND a.id_tahun_akademik='$_GET[tahun]' ORDER BY a.hari DESC");
          } else {
            $tampil = mysql_query("SELECT a.*, e.nama_kelas, b.namamatapelajaran, b.kode_pelajaran, c.nama_guru, d.nama_ruangan FROM rb_jadwal_pelajaran a 
                                            JOIN rb_mata_pelajaran b ON a.kode_pelajaran=b.kode_pelajaran
                                              JOIN rb_guru c ON a.nip=c.nip 
                                                JOIN rb_ruangan d ON a.kode_ruangan=d.kode_ruangan
                                                JOIN rb_kelas e ON a.kode_kelas=e.kode_kelas 
                                                  where a.nip='$_SESSION[id]' AND a.id_tahun_akademik LIKE '" . date('Y') . "%' ORDER BY a.hari DESC");
          }
          $no = 1;
          while ($r = mysql_fetch_array($tampil)) {
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
                  <a class='btn btn-success btn-xs' href='view=journalguru&tahun=$r[id_tahun_akademik]'>Tujuan Pembelajaran</a>
                              <td></td>
                          </tr>";
            $no++;
          }
          ?>
        </tbody>
      </table>
    </div><!-- /.box-body -->
  </div>
</div>