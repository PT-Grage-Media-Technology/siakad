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

        <input type="hidden" name="view" value="aktivitaspembelajaran">

        <!-- Filter Tanggal -->
        <select name='tanggal' style='padding:4px' onchange='this.form.submit()'>

          <?php
          $today = date('j'); // Mengambil tanggal hari ini
          $selectedTanggal = isset($_GET['tanggal']) ? $_GET['tanggal'] : $today; // Default ke tanggal hari ini jika kosong
          
          for ($i = 1; $i <= 31; $i++) {
            $selected = ($selectedTanggal == $i) ? 'selected' : '';
            echo "<option value='$i' $selected>$i</option>";
          }
          ?>
        </select>


        <!-- Filter Bulan -->
        <select name='bulan' style='padding:4px' onchange='this.form.submit()'>
          <?php
          $currentMonth = date('n'); // Mengambil bulan saat ini
          $selectedBulan = isset($_GET['bulan']) ? $_GET['bulan'] : $currentMonth; // Default ke bulan saat ini jika kosong
          $bulanNama = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

          for ($i = 1; $i <= 12; $i++) {
            $selected = ($selectedBulan == $i) ? 'selected' : '';
            echo "<option value='$i' $selected>{$bulanNama[$i - 1]}</option>";
          }
          ?>
        </select>

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
              <th>Kelas</th>
              <th>Nama Mapel</th>
              <th>Kehadiran</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>

            <?php
            if ($_SESSION['is_kurikulum']) {
              // Mengambil tanggal yang dipilih dari GET
              // Ambil tanggal dan bulan yang dipilih dari GET
              $tanggal_dipilih = isset($_GET['tanggal']) ? $_GET['tanggal'] : date('d');
              $bulan_dipilih = isset($_GET['bulan']) ? $_GET['bulan'] : date('n');


              $tampil = mysql_query("SELECT jl.*, a.kode_kelas, b.nama_kelas, c.namamatapelajaran, c.kode_pelajaran, d.nama_guru,
              (SELECT kode_kehadiran 
               FROM rb_absensi_guru ag 
               WHERE ag.nip = jl.users 
               AND ag.tanggal = jl.tanggal 
               AND ag.jam_ke = jl.jam_ke  
               LIMIT 1) AS kode_kehadiran
                FROM rb_journal_list jl 
                JOIN rb_jadwal_pelajaran a ON jl.kodejdwl = a.kodejdwl
                JOIN rb_kelas b ON a.kode_kelas = b.kode_kelas 
                JOIN rb_mata_pelajaran c ON a.kode_pelajaran = c.kode_pelajaran 
                JOIN rb_guru d ON jl.users = d.nip
                WHERE DAY(jl.tanggal) = '$tanggal_dipilih' 
                AND MONTH(jl.tanggal) = '$bulan_dipilih'
                ORDER BY jl.waktu_input DESC;
                ");


              // $kehadiran = mysqli_query("SELECT * FROM rb_absensi_guru")
            
              // var_dump(mysql_fetch_array($tampil));
              $no = 1;
              while ($r = mysql_fetch_array($tampil)) {
                echo "<tr>
                        <td>$no</td>
                        <td>$r[users]</td>
                        <td>$r[nama_guru]</td>
                        <td>$r[hari]</td>
                        <td>" . tgl_indo($r['tanggal']) . "</td>
                        <td>$r[jam_ke]</td>
                        <td>$r[kode_kelas]</td>
                        <td>$r[namamatapelajaran]</td>";

                        $pemberitahuan = mysql_query("SELECT * FROM rb_pemberitahuan_guru 
                                                      WHERE nip_guru='$r[users]' 
                                                      AND is_read=1 
                                                      AND kode_kelas='$r[kode_kelas]' 
                                                      AND kode_mapel='$r[kode_pelajaran]' 
                                                      AND id_tujuan_pembelajaran='$r[kodejdwl]' 
                                                      AND tanggal_absen='$r[tanggal]' 
                                                      AND jam_ke='$r[jam_ke]'");

                        $pe = mysql_fetch_array($pemberitahuan);
                        echo"
                        <td>"; 
                            if (isset($r['kode_kehadiran'])) {
                                echo "$r[kode_kehadiran]";
                            } else {
                              if(mysql_num_rows($pemberitahuan) > 0){
                                echo "Sudah Kirim Pemberitahuan";
                              } else {
                                echo "
                                <form method='POST' id='pemberitahuan' action='' onsubmit='return submitFormWithAlert()'>
                                    <input type='hidden' name='users' value='$r[users]'>
                                    <input type='hidden' name='kodejdwl' value='$r[kodejdwl]'>
                                    <input type='hidden' name='jam_ke' value='$r[jam_ke]'>
                                    <button class='btn btn-primary btn-xs' type='submit' onsubmit='return submitFormWithAlert()' name='peringatkan'>Peringatkan</button>
                                </form>";
                              }
                            }
                            echo"
                        </td>

                        <td>
                            <center>
                              <a class='btn btn-warning btn-xs' href='index.php?view=journalguru&act=lihat&id=$r[kodejdwl]'>Detail Tujuan Pembelajaran Guru</a>
                              <a class='btn btn-primary btn-xs' href='index.php?view=absensiswa&act=tampilabsen&id=$r[kode_kelas]&kd=$r[kode_pelajaran]&idjr=$r[kodejdwl]&tgl=$r[tanggal]&jam=$r[jam_ke]'>Absensi</a>
                            </center>
                        </td>
                      </tr>";
                $no++;
                
                if (isset($_POST['peringatkan']) && $_POST['users'] == $r['users'] &&
                    $_POST['kodejdwl'] == $r['kodejdwl'] && $_POST['jam_ke'] == $r['jam_ke']) {
                   // Mendapatkan NIP pengguna
                   $nip = mysql_real_escape_string($_POST['users']); // Menyantisisasi input
                   
                   $pesan = 'Segera Absen Muridnya';
                   $tanggal = date('Y-m-d H:i:s');
               
                   $insertResult = mysql_query("INSERT INTO rb_pemberitahuan_guru VALUES (null, '$nip', '$pesan', 0, '$r[kode_kelas]', '$r[kode_pelajaran]', '$r[kodejdwl]', '$r[tanggal]', '$r[jam_ke]', '$tanggal')");

                   echo "<script>document.location='index.php?view=aktivitaspembelajaran';</script>";
                }
                // if (isset($_POST['peringatkan']) && $_POST['users'] == $r['users']) {
                //    // Mendapatkan NIP pengguna
                //    $nip = mysql_real_escape_string($_POST['users']); // Menyantisisasi input
                   
                //    $pesan = 'tes aja';
                //    $tanggal = date('Y-m-d H:i:s');
               
                //    $insertResult = mysql_query("INSERT INTO rb_pemberitahuan_guru VALUES (null, '$nip', '$pesan', 0, '$r[kode_kelas]', '$r[kode_pelajaran]', '$r[kodejdwl]', '$r[tanggal]', '$r[jam_ke]', '$tanggal')");
                //    if ($insertResult) {
                //      echo "<script>alert('Pemberitahuan berhasil dikirim.');</script>";
                //    } else {
                //      echo "<script>alert('Gagal mengirim pemberitahuan: " . mysql_error() . "');</script>";
                //    }
                // }

              }

            } else {
              // Mengambil tanggal yang dipilih dari GET
              // Ambil tanggal dan bulan yang dipilih dari GET
              $tanggal_dipilih = isset($_GET['tanggal']) ? $_GET['tanggal'] : date('d');
              $bulan_dipilih = isset($_GET['bulan']) ? $_GET['bulan'] : date('n');


              // Ubah query untuk memfilter berdasarkan tanggal yang dipilih dan ambil data kelas
              $tampil = mysql_query("SELECT jl.*, a.kode_kelas, b.nama_kelas, c.namamatapelajaran, c.kode_pelajaran, d.nama_guru 
              FROM rb_journal_list jl 
              JOIN rb_jadwal_pelajaran a ON jl.kodejdwl = a.kodejdwl
              JOIN rb_kelas b ON a.kode_kelas = b.kode_kelas 
              JOIN rb_mata_pelajaran c ON a.kode_pelajaran = c.kode_pelajaran 
              JOIN rb_guru d ON jl.users = d.nip
              WHERE DAY(jl.tanggal) = '$tanggal_dipilih' 
              AND MONTH(jl.tanggal) = '$bulan_dipilih'
              AND jl.users = '{$_SESSION['id']}'
              ORDER BY jl.waktu_input DESC;
              ");

              // var_dump(mysql_fetch_array($tampil));
              $no = 1;
              while ($r = mysql_fetch_array($tampil)) {
                echo "<tr>
                        <td>$no</td>
                        <td>$r[users]</td>
                        <td>$r[nama_guru]</td>
                        <td>$r[hari]</td>
                        <td>" . tgl_indo($r['tanggal']) . "</td>
                        <td>$r[jam_ke]</td>
                        <td>$r[kode_kelas]</td>
                        <td>$r[namamatapelajaran]</td>";

                        // $pemberitahuan = mysql_query("SELECT * FROM rb_pemberitahuan_guru 
                        //                               WHERE nip_guru='$r[users]' 
                        //                               AND is_read=1 
                        //                               AND kode_kelas='$r[kode_kelas]' 
                        //                               AND kode_mapel='$r[kode_pelajaran]' 
                        //                               AND id_tujuan_pembelajaran='$r[kodejdwl]' 
                        //                               AND tanggal_absen='$r[tanggal]' 
                        //                               AND jam_ke='$r[jam_ke]'");

                        $pemberitahuan = mysql_query("SELECT * FROM rb_absensi_guru 
                                                      WHERE nip='$r[users]' 
                                                      AND kodejdwl='$r[kodejdwl]' 
                                                      AND tanggal='date('Y-m-d')'");

                        $coba = "SELECT * FROM rb_absensi_guru 
                                                      WHERE nip='$r[users]' 
                                                      AND kodejdwl='$r[kodejdwl]' 
                                                      AND tanggal='date('Y-m-d')'";
                        
                        var_dump($coba);


                        $pe = mysql_fetch_array($pemberitahuan);
                        var_dump(mysql_num_rows($pemberitahuan));
                        var_dump(isset($r['kode_kehadiran']));
                        echo"
                        <td>"; 
                            if (isset($r['kode_kehadiran'])) {
                                echo "$r[kode_kehadiran]";
                            } else {
                              if(mysql_num_rows($pemberitahuan) > 0){
                                echo "Sudah Kirim Pemberitahuan";
                              } else {
                                echo "Anda Belom Mengisi kehadiran";
                              }
                            }
                            echo"
                        </td>

                        <td>
                            <center>
                              <a class='btn btn-warning btn-xs' href='index.php?view=journalguru&act=lihat&id=$r[kodejdwl]'>Detail Tujuan Pembelajaran Guru</a>
                              <a class='btn btn-primary btn-xs' href='index.php?view=absensiswa&act=tampilabsen&id=$r[kode_kelas]&kd=$r[kode_pelajaran]&idjr=$r[kodejdwl]&tgl=$r[tanggal]&jam=$r[jam_ke]'>Absensi</a>
                            </center>
                        </td>
                      </tr>";
                $no++;
              }
              // Check if form is submitted
            
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

<script>
function submitFormWithAlert() {
    // Pesan konfirmasi
    const confirmSubmit = confirm("Apakah Anda yakin ingin mengirimkan peringatan?");
    if (confirmSubmit) {
        // Jika konfirmasi "OK", form akan disubmit
        document.getElementById('pemberitahuan').submit();
        return true;
    }
    // Jika konfirmasi "Cancel", form tidak akan disubmit
    return false;
}
</script>
