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

        echo "Aktivitas Pembelajaran Guru - $nama_tahun";
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
        <form style='margin-right:5px; margin-top:0px' class='pull-right' action="?" method="GET">
          <input type="hidden" name="view" value="aktivitaspembelajaran">
          <select name='tanggal' style='padding:4px' onchange='this.form.submit()'>
            
            <?php
            $today = date('j'); // Mengambil tanggal hari ini
            $selectedTanggal = isset($_GET['tanggal']) ? $_GET['tanggal'] : $today; // Mengatur default ke tanggal hari ini jika belum ada pilihan
            
            for ($i = 1; $i <= 31; $i++) {
              $selected = ($selectedTanggal == $i) ? 'selected' : '';
              echo "<option value='$i' $selected>$i</option>";
            }

            ?>
            <option value='<?php echo $today; ?>' selected>- Pilih Tanggal -</option>
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
              <th>jam</th>rbj_journal_list
              <th>Kode Kelas</th>rb_jadwal_pelajaran
              <th>Kode Mapel</th>rb_jadwal_pelajaran
              <th>Tujuan Pembelajaran</th>
            </tr>
          </thead>
          <tbody>
        
          </tbody>
        </table> -->

        <table id="example1" class="table table-bordered table-striped">
          <thead>
            <tr>
              <th style='width:20px'>No</th>
              <th>Nip</th>
              <th>Nama Guru</th>
              <th>Hari</th>
              <th>Tanggal</th>
              <th style='width:60px'>Jam ke</th>
              <th>Nama Mapel</th>
              <th>Tujuan Pembelajaran</th>
            </tr>
          </thead>
          <tbody>
            <?php
            // Mengambil tanggal yang dipilih dari GET
            $tanggal_dipilih = isset($_GET['tanggal']) ? $_GET['tanggal'] : date('d');

            // Ubah query untuk memfilter berdasarkan tanggal yang dipilih dan ambil data kelas
            $tampil = mysql_query("SELECT jl.*, a.kode_kelas, b.nama_kelas, c.namamatapelajaran, c.kode_pelajaran, d.nama_guru 
FROM rb_journal_list jl 
JOIN rb_jadwal_pelajaran a ON jl.kodejdwl = a.kodejdwl
JOIN rb_kelas b ON a.kode_kelas = b.kode_kelas 
JOIN rb_mata_pelajaran c ON a.kode_pelajaran = c.kode_pelajaran 
JOIN rb_guru d ON jl.users = d.nip
WHERE DAY(jl.tanggal) = '$tanggal_dipilih' 
ORDER BY jl.waktu_input DESC;
");

            // var_dump($tampil);
            $no = 1;
            while ($r = mysql_fetch_array($tampil)) {
              echo "<tr>
                      <td>$no</td>
                      <td>$r[users]</td>
                      <td>$r[nama_guru]</td>
                      <td>$r[hari]</td>
                      <td>" . tgl_indo($r['tanggal']) . "</td>
                      <td><center>$r[jam_ke]</td>
                      <td>$r[namamatapelajaran]</td>
                      <td><center><a class='btn btn-success btn-xs' href='index.php?view=journalguru&act=lihat&id=$r[kodejdwl]'>Tujuan Pembelajaran</a></center></td>
                    </tr>";
              $no++;
            }

            // // Gunakan kodejdwl_terakhir di sini
            // $d = mysql_fetch_array(mysql_query("SELECT a.kode_kelas, b.nama_kelas, c.namamatapelajaran, c.kode_pelajaran, d.nama_guru 
            // FROM `rb_jadwal_pelajaran` a 
            // JOIN rb_kelas b ON a.kode_kelas=b.kode_kelas 
            // JOIN rb_mata_pelajaran c ON a.kode_pelajaran=c.kode_pelajaran 
            // JOIN rb_guru d ON a.nip=d.nip 
            // WHERE a.kodejdwl='$kodejdwl_terakhir'")); // Ganti $r[kodejdwl] dengan $kodejdwl_terakhir
            // var_dump($d); // Hapus var_dump jika tidak diperlukan
            // if ($d === false) {
            //     echo "Error: " . mysql_error(); // Menampilkan pesan kesalahan
            // }
            ?>
          </tbody>


        </table>

      </div><!-- /.table-responsive -->
    </div><!-- /.box-body -->
  </div>
</div>
