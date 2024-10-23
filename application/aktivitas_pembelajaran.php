<div class="col-xs-12">
  <div class="box">
    <div class="box-header">
      <h3 class="box-title">
        <?php
        // Ambil tahun akademik terbaru (id_tahun_akademik paling besar)
        $latest_year = mysql_fetch_array(mysql_query("SELECT id_tahun_akademik, nama_tahun FROM rb_tahun_akademik ORDER BY id_tahun_akademik DESC LIMIT 1"));

        // Jika tidak ada tahun akademik dipilih, set default ke tahun terbaru
        $tahun_dipilih = isset($_GET['tahun']) ? $_GET['tahun'] : $latest_year['id_tahun_akademik'];
        $nama_tahun = isset($_GET['tahun']) ?
          mysql_fetch_array(mysql_query("SELECT nama_tahun FROM rb_tahun_akademik WHERE id_tahun_akademik = '$tahun_dipilih'"))['nama_tahun'] :
          $latest_year['nama_tahun'];

        echo "Aktivitas Pembelajaran Anda - $nama_tahun";
        ?>
      </h3>
      <form style='margin-right:5px; margin-top:0px' class='pull-right' action='' method='GET'>
        <select name='tahun' style='padding:4px' onchange='this.form.submit()'>
          <option value=''>- Pilih Tahun Akademik -</option>
          <?php
          $tahun = mysql_query("SELECT * FROM rb_tahun_akademik ORDER BY id_tahun_akademik DESC");
          while ($k = mysql_fetch_array($tahun)) {
            $selected = ($tahun_dipilih == $k['id_tahun_akademik']) ? 'selected' : '';
            echo "<option value='$k[id_tahun_akademik]' $selected>$k[nama_tahun]</option>";
          }
          ?>
        </select>
      </form>
    </div><!-- /.box-header -->
    <div class="box-body">
      <div class="table-responsive">
        <!-- <table id="example1" class="table table-bordered table-striped">
          <thead>
            <tr>
              <th style='width:20px'>No</th>
              <th>Nip</th>rb_guru
              <th>Nama Guru</th>rb_guru
              <th>hari</th>rbj_ournal_list
              <th>tanggal</th>rbj_ournal_list
              <th>jam</th>rbj_ournal_list
              <th>Kode Kelas</th>rb_jadwal_pelajaran
              <th>Kode Mapel</th>rb_jadwal_pelajaran
              <th>Tujuan Pembelajaran</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $tampil = mysql_query("SELECT a.*, e.nip, b.nama_guru, b.kode_pelajaran, c.nama_guru, d.nama_ruangan 
                                   FROM rb_jadwal_pelajaran a 
                                   JOIN rb_mata_pelajaran b ON a.kode_pelajaran=b.kode_pelajaran
                                   JOIN rb_guru c ON a.nip=c.nip 
                                   JOIN rb_ruangan d ON a.kode_ruangan=d.kode_ruangan
                                   JOIN rb_kelas e ON a.kode_kelas=e.kode_kelas 
                                   WHERE a.nip='$_SESSION[id]' AND a.id_tahun_akademik='$tahun_dipilih' 
                                   ORDER BY a.hari DESC");
            $no = 1;
            while ($r = mysql_fetch_array($tampil)) {
              echo "<tr>
                      <td>$no</td>
                      <td>$r[nip]</td>
                      <td>$r[nama_guru]</td>
                      <td>$r[hari]</td>
                      <td>$r[tanggal]</td>
                      <td>$r[jam]</td>
                      <td>$r[kode_kelas]</td>
                      <td>$r[kode_pelajaran]</td>
                      <td><a class='btn btn-success btn-xs' href='index.php?view=journalguru&act=lihat&id=$r[kodejdwl]'>Tujuan Pembelajaran</a></td>
                    </tr>";
              $no++;
            }
            ?>
          </tbody>
        </table> -->

        <table id="example1" class="table table-bordered table-striped">
  <thead>
    <tr>
      <th style='width:20px'>No</th>
      <th>Nip</th><!-- rb_guru -->
      <th>Nama Guru</th><!-- rb_guru -->
      <th>Hari</th><!-- rb_journal_list -->
      <th>Tanggal</th><!-- rb_journal_list -->
      <th>Jam</th><!-- rb_journal_list -->
      <th>Kode Kelas</th><!-- rb_jadwal_pelajaran -->
      <th>Kode Mapel</th><!-- rb_jadwal_pelajaran -->
      <th>Tujuan Pembelajaran</th>
    </tr>
  </thead>
  <tbody>
    <?php
    // Query untuk mengambil data dari beberapa tabel
    $tampil = mysql_query("
      SELECT a.*, e.nip, b.nama_guru, a.hari, a.tanggal, a.jam, a.kode_kelas, a.kode_pelajaran 
      FROM rb_jadwal_pelajaran a
      JOIN rb_mata_pelajaran b ON a.kode_pelajaran=b.kode_pelajaran
      JOIN rb_guru c ON a.nip=c.nip 
      JOIN rb_ruangan d ON a.kode_ruangan=d.kode_ruangan
      JOIN rb_kelas e ON a.kode_kelas=e.kode_kelas 
      WHERE a.nip='$_SESSION[id]' AND a.id_tahun_akademik='$tahun_dipilih' 
      ORDER BY a.hari DESC");

    // Inisialisasi nomor
    $no = 1;
    
    // Loop untuk menampilkan hasil query
    while ($r = mysql_fetch_array($tampil)) {
      echo "<tr>
              <td>$no</td>
              <td>$r[nip]</td> <!-- Kolom nip dari tabel rb_guru -->
              <td>$r[nama_guru]</td> <!-- Kolom nama_guru dari tabel rb_guru -->
              <td>$r[hari]</td> <!-- Kolom hari dari tabel rb_journal_list -->
              <td>$r[tanggal]</td> <!-- Kolom tanggal dari tabel rb_journal_list -->
              <td>$r[jam]</td> <!-- Kolom jam dari tabel rb_journal_list -->
              <td>$r[kode_kelas]</td> <!-- Kolom kode_kelas dari tabel rb_jadwal_pelajaran -->
              <td>$r[kode_pelajaran]</td> <!-- Kolom kode_pelajaran dari tabel rb_jadwal_pelajaran -->
              <td><a class='btn btn-success btn-xs' href='index.php?view=journalguru&act=lihat&id=$r[kodejdwl]'>Tujuan Pembelajaran</a></td>
            </tr>";
      $no++; // Increment nomor urut
    }
    ?>
  </tbody>
</table>

      </div><!-- /.table-responsive -->
    </div><!-- /.box-body -->
  </div>
</div>