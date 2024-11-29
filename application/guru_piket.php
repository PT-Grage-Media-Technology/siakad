<?php if ($_GET[act] == '') { ?>
<div class="col-xs-12">
    <div class="box">
        <div class="box-header">
            <!-- <a class='btn btn-primary pull-right' href='index.php?view=absensiguru&act=tambah'
      title='Tambah Jadwal'>Tambah Jadwal</a> -->

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
            
            // Mendapatkan bulan dan tanggal yang dipilih atau default ke bulan dan tanggal hari ini
            $bulan_dipilih = isset($_GET['bulan']) ? $_GET['bulan'] : date('n');  // Default ke bulan sekarang
            $tanggal_dipilih = isset($_GET['tanggal']) ? $_GET['tanggal'] : date('j');  // Default ke tanggal hari ini
            
            ?>

            <!-- Menampilkan form dan h3 -->
            <h3 class="box-title">
                Guru Yang berhalangan hadir
            </h3>

            <form style='margin-right:5px; margin-top:0px' class='pull-right' action='' method='GET'>
                <!-- Tambahkan hidden input untuk menyimpan parameter view -->
                <input type="hidden" name="view" value="absensiguru">

                <!-- Dropdown untuk memilih Tahun Akademik -->
                <select name='tahun' style='padding:4px' onchange='this.form.submit()'>
                    <option value=''>- Pilih Tahun Akademik -</option>
                    <?php
                    while ($k = mysql_fetch_array($tahun)) {
                        $selected = ($tahun_dipilih == $k['id_tahun_akademik']) ? 'selected' : '';
                        echo "<option value='{$k['id_tahun_akademik']}' $selected>{$k['nama_tahun']}</option>";
                    }
                    ?>
                </select>

                <!-- Dropdown untuk memilih Bulan -->
                <select name='bulan' style='padding:4px' onchange='this.form.submit()'>
                    <option value=''>- Pilih Bulan -</option>
                    <option value='1' <?php echo ($bulan_dipilih == 1) ? 'selected' : ''; ?>>Januari</option>
                    <option value='2' <?php echo ($bulan_dipilih == 2) ? 'selected' : ''; ?>>Februari</option>
                    <option value='3' <?php echo ($bulan_dipilih == 3) ? 'selected' : ''; ?>>Maret</option>
                    <option value='4' <?php echo ($bulan_dipilih == 4) ? 'selected' : ''; ?>>April</option>
                    <option value='5' <?php echo ($bulan_dipilih == 5) ? 'selected' : ''; ?>>Mei</option>
                    <option value='6' <?php echo ($bulan_dipilih == 6) ? 'selected' : ''; ?>>Juni</option>
                    <option value='7' <?php echo ($bulan_dipilih == 7) ? 'selected' : ''; ?>>Juli</option>
                    <option value='8' <?php echo ($bulan_dipilih == 8) ? 'selected' : ''; ?>>Agustus</option>
                    <option value='9' <?php echo ($bulan_dipilih == 9) ? 'selected' : ''; ?>>September</option>
                    <option value='10' <?php echo ($bulan_dipilih == 10) ? 'selected' : ''; ?>>Oktober</option>
                    <option value='11' <?php echo ($bulan_dipilih == 11) ? 'selected' : ''; ?>>November</option>
                    <option value='12' <?php echo ($bulan_dipilih == 12) ? 'selected' : ''; ?>>Desember</option>
                </select>

                <!-- Dropdown untuk memilih Tanggal -->
                <select name='tanggal' style='padding:4px' onchange='this.form.submit()'>
                    <option value=''>- Pilih Tanggal -</option>
                    <?php
                    for ($i = 1; $i <= 31; $i++) {
                        $selected = ($tanggal_dipilih == $i) ? 'selected' : '';
                        echo "<option value='$i' $selected>$i</option>";
                    }
                    ?>
                </select>
            </form>

        </div><!-- /.box-header -->
        <div class="box-body">
            <div class="table-responsive">

                <table id="example" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th style='width:20px'>No</th>
                            <th>Nip</th>
                            <th>Guru</th>
                            <th>Kehadiran</th>
                            <th>Tanggal</th>
                            <th>Action</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php

                        $tampil = mysql_query("SELECT * FROM rb_rekap_absen_guru a JOIN rb_guru b ON a.nip=b.nip   WHERE DAY(a.tanggal) = '$tanggal_dipilih' AND MONTH(a.tanggal) = '$bulan_dipilih'");


                        $no = 1;
                        if (mysql_num_rows($tampil) > 0) { // Memeriksa apakah ada data
                            while ($r = mysql_fetch_array($tampil)) {
                                echo "<tr><td>$no</td>
                                <td>$r[nip]</td>
                                <td>$r[nama_guru]</td>
                                <td>$r[kode_kehadiran]</td>
                                <td>" . tgl_indo($r['tanggal']) . "</td>
                                <td>
                                  <a href='index.php?view=absensiguru&act=detail&id_absen=$r[id_absensi]&nip=$r[nip]&bulan=$bulan_dipilih&tanggal=$tanggal_dipilih' class='btn btn-info' title='detail'><i class='fa fa-eye'></i></a>
                                  <a href='' class='btn btn-danger' title='Hapus' onclick='return confirm(\"Apakah Anda yakin ingin menghapus?\")'><i class='fa fa-times'></i></a>
                                </td>";

                                echo "</tr>";
                                $no++;
                            }
                        } else {
                            echo "<tr><td colspan='6' style='text-align:center;'>Tidak ada data</td></tr>"; // Menampilkan pesan jika tidak ada data
                        }
                        ?>
                    <tbody>
                </table>
            </div>
        </div><!-- /.box-body -->

    </div>
</div>
<?php
} elseif ($_GET[act] == 'detail') {
    cek_session_guru();
    // Ambil data sesuai NIP
    $m = mysql_query("SELECT * FROM rb_rekap_absen_guru a JOIN rb_guru b ON a.nip=b.nip WHERE a.nip=$_GET[nip] AND DAY(a.tanggal) = '$_GET[tanggal]' AND MONTH(a.tanggal) = '$_GET[bulan]'");
    $cek_absen = mysql_query("SELECT * FROM rb_rekap_absen_guru WHERE id_absensi = '$_GET[id_absen]' AND status=1 AND DAY(tanggal) = '$_GET[tanggal]' AND MONTH(tanggal) = '$_GET[bulan]'");
    $sudah_disetujui = mysql_num_rows($cek_absen) > 0;
    // Tampilkan data yang diambil
    var_dump($cek_absen);
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
                if(!$sudah_disetujui){

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

}elseif ($_GET[act] == 'setujui') {
    // ambil data tujuan belajar mengajar
    $tujuan_belajar = mysql_query("SELECT * FROM rb_journal_list a JOIN rb_rekap_absen_guru b ON a.users=b.nip WHERE a.users='$_GET[nip]' AND DAY(a.tanggal) = DAY(b.tanggal) AND MONTH(a.tanggal) = MONTH(b.tanggal) AND DAY(a.tanggal) = '$_GET[tanggal]' AND MONTH(a.tanggal) = '$_GET[bulan]'");
    
    if ($tujuan_belajar) {
        $result = mysql_fetch_array($tujuan_belajar);
        if ($result) {
            // echo "NIP: " . $result['users'] . "<br>";
            // echo "Nama: " . $result['kodejdwl'] . "<br>";
            // echo "Tanggal: " . tgl_indo($result['tanggal']) . "<br>";

            $kodejdwl = $result['kodejdwl'];
            $nip = $result['users'];
            $kode_kehadiran = $result['kode_kehadiran'];
            $jam_ke = $result['jam_ke'];
            $tanggal = $result['tanggal'];
            $status = 1;
            $updateStatus = mysql_query("UPDATE rb_rekap_absen_guru SET status='$status' WHERE id_absensi='$_GET[id_absen]'");

          if($updateStatus){
            echo "<script>document.location='index.php?view=absensiguru&act=detail&id_absen=$_GET[id_absen]&nip=$_GET[nip]&bulan=$_GET[bulan]&tanggal=$_GET[tanggal]';</script>";
          }

        } else {
            echo "no data"; // Menampilkan pesan jika tidak ada data
        }
    } else {
        echo "Query error: " . mysql_error(); // Menampilkan pesan kesalahan jika query gagal
    }

}elseif ($_GET[act] == 'gantikan') {
    // ambil data tujuan belajar mengajar
    $tujuan_belajar = mysql_query("UPDATE rb_journal_list SET pengganti=$_SESSION[id] WHERE id_journal=$_GET[id]");
    
    $nip = $_GET['nip'];
    $bulan=$_GET['bulan'];
    $tanggal=$_GET['tanggal'];

    if ($tujuan_belajar) {
        // echo"document.location='index.php?view=absensiguru&act=detail&nip=$nip&bulan=$bulan&tanggal=$tanggal'";
            echo "<script>window.history.back();</script>";
          }else{
            echo "no p"; // Menampilkan pesan jika tidak ada data
          } 
}

              