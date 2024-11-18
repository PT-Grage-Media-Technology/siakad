<div class="col-xs-12">
  <div class="box">
    <div class="box-header">
      <h3 class="box-title">
     tes dlu 
</h3>
      <form style='margin-right:5px; margin-top:0px' class='pull-right' action='' method='GET'>
  <!-- Tambahkan hidden input untuk menyimpan parameter view -->
  <input type="hidden" name="view" value="jadwalguru">
  <select name='tahun' style='padding:4px' onchange='this.form.submit()'>
    <option value=''>- Pilih Tahun Akademik -</option>
    <?php
    // Ambil tahun akademik yang terbaru
    $tahun = mysql_query("SELECT * FROM rb_tahun_akademik ORDER BY id_tahun_akademik DESC");
    $tahun_terbaru = mysql_fetch_array($tahun); // Ambil tahun terbaru
    mysql_data_seek($tahun, 0); // Kembali ke awal data query untuk loop
    
    // Jika pengguna belum memilih tahun, gunakan tahun terbaru
    $tahun_dipilih = isset($_GET['tahun']) ? $_GET['tahun'] : $tahun_terbaru['id_tahun_akademik'];
    
    while ($k = mysql_fetch_array($tahun)) {
      $selected = ($tahun_dipilih == $k['id_tahun_akademik']) ? 'selected' : '';
      echo "<option value='{$k['id_tahun_akademik']}' $selected>{$k['nama_tahun']}</option>";
    }
    ?>
  </select>
</form>


    </div>
    <!-- /.box-header -->
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
              <th>Ruangan</th>
              <th>Semester</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $tampil = mysql_query("SELECT a.*, e.nama_kelas, b.namamatapelajaran, b.kode_pelajaran, c.nama_guru, d.nama_ruangan 
                                   FROM rb_jadwal_pelajaran a 
                                   JOIN rb_mata_pelajaran b ON a.kode_pelajaran=b.kode_pelajaran
                                   JOIN rb_guru c ON a.nip=c.nip 
                                   JOIN rb_ruangan d ON a.kode_ruangan=d.kode_ruangan
                                   JOIN rb_kelas e ON a.kode_kelas=e.kode_kelas 
                                   WHERE a.nip='$_SESSION[id]' AND a.id_tahun_akademik=20162 
                                   ORDER BY a.hari DESC");
              if (!$tampil) {
                    die("Query Error: " . mysql_error());
                      }
            $no = 1;
            while ($r = mysql_fetch_array($tampil)) {
              echo "<tr>
                      <td>$no</td>
                      <td>$r[kode_pelajaran]</td>
                      <td>$r[namamatapelajaran]</td>
                      <td>$r[nama_kelas]</td>
                      <td>$r[nama_guru]</td>
                      <td>$r[hari]</td>
                      <td>$r[jam_mulai]</td>
                      <td>$r[jam_selesai]</td>
                      <td>$r[nama_ruangan]</td>
                      <td>$r[id_tahun_akademik]</td>
                      <td><a class='btn btn-success btn-xs' href='index.php?view=journalguru&act=lihat&id=$r[kodejdwl]&tahun=$r[id_tahun_akademik]'>Agenda Mengajar</a></td>
                    </tr>";
              $no++;
            }
            ?>
          </tbody>
        </table>
      </div><!-- /.table-responsive -->
    </div><!-- /.box-body -->
  </div>
</div>