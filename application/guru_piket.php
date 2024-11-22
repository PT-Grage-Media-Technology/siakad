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
                            <th>Hari</th>
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
                                  <a href='index.php?view=absensiguru&act=lihat&nip=$r[nip]&bulan=$bulan_dipilih&tanggal=$tanggal_dipilih' class='btn btn-info' title='Lihat'><i class='fa fa-eye'></i></a>
                                  <a href='' class='btn btn-success' title='Setujui'><i class='fa fa-check'></i></a>
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
} elseif ($_GET[act] == 'lihat') {
    cek_session_guru();
    // Ambil data sesuai NIP
    $m = mysql_query("SELECT * FROM rb_rekap_absen_guru a JOIN rb_guru b ON a.nip=b.nip   WHERE DAY(a.tanggal) = '$_GET[tanggal]' AND MONTH(a.tanggal) = '$_GET[bulan]'");
    
    // Tampilkan data yang diambil
    if ($data = mysql_fetch_array($m)) {
        echo "<div class='col-md-12'>
              <div class='box box-info'>
                <div class='box-header with-border'>
                  <h3 class='box-title'>Lihat</b></h3>";
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
                <a href='index.php?view=absensiguru&act=lihat&nip=$r[nip]&bulan=$bulan_dipilih&tanggal=$tanggal_dipilih' class='btn btn-info' title='Lihat'><i class='fa fa-eye'></i></a>

                      </div>
                  </div>
              </div>";
              
    echo"</div>";
    } else {
        echo "Data tidak ditemukan.";
    }

    
    }
              