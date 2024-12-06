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
                        <td><a class='btn btn-success btn-xs' title='Lihat Data' href='index.php?view=rekapsiswa&id=$r[kode_kelas]&idjr=$r[kodejdwl]'><span class='glyphicon glyphicon-list'></span> Lihat Nilai</a></td>
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