<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/css/bootstrap-select.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/js/bootstrap-select.min.js"></script>

<?php
// Koneksi ke database
$conn = mysqli_connect("host", "username", "password", "database");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (!isset($_GET['act'])) {
    $_GET['act'] = '';
}

if ($_GET['act'] == '') { ?>
  <div class="col-xs-12">
    <div class="box">
      <div class="box-header">
        <h3 class="box-title">
          <?php if (isset($_GET['tahun'])) {
            echo "Tujuan Belajar Mengajar anda";
          } else {
            echo "Tujuan Belajar Mengajar anda pada " . date('Y');
          } ?>
        </h3>
        <form style='margin-right:5px; margin-top:0px' class='pull-right' action='' method='GET'>
          <input type="hidden" name='view' value='journalguru'>
          <select name='tahun' style='padding:4px'>
            <?php
            echo "<option value=''>- Pilih Tahun Akademik -</option>";
            $tahun = mysqli_query($conn, "SELECT * FROM rb_tahun_akademik");
            while ($k = mysqli_fetch_array($tahun)) {
              echo "<option value='$k[id_tahun_akademik]'" . (($_GET['tahun'] == $k['id_tahun_akademik']) ? " selected" : "") . ">$k[nama_tahun]</option>";
            }
            ?>
          </select>
          <input type="submit" style='margin-top:-4px' class='btn btn-success btn-sm' value='Lihat'>
        </form>
      </div><!-- /.box-header -->

      <div class="box-body">
        <div class="table-responsive">
          <table class="table table-bordered table-striped">
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
                <th></th>
              </tr>
            </thead>
            <tbody>
              <?php
              // Cek apakah tahun di-set
              if (isset($_GET['tahun'])) {
                $tampil = mysqli_query($conn, "SELECT a.*, e.nama_kelas, b.namamatapelajaran, b.kode_pelajaran, c.nama_guru, d.nama_ruangan FROM rb_jadwal_pelajaran a 
                                              JOIN rb_mata_pelajaran b ON a.kode_pelajaran=b.kode_pelajaran
                                                JOIN rb_guru c ON a.nip=c.nip 
                                                  JOIN rb_ruangan d ON a.kode_ruangan=d.kode_ruangan
                                                    JOIN rb_kelas e ON a.kode_kelas=e.kode_kelas 
                                                    WHERE a.nip='$_SESSION[id]' AND a.id_tahun_akademik='$_GET[tahun]' ORDER BY a.hari DESC");
              } else {
                $tampil = mysqli_query($conn, "SELECT a.*, e.nama_kelas, b.namamatapelajaran, b.kode_pelajaran, c.nama_guru, d.nama_ruangan FROM rb_jadwal_pelajaran a 
                                              JOIN rb_mata_pelajaran b ON a.kode_pelajaran=b.kode_pelajaran
                                                JOIN rb_guru c ON a.nip=c.nip 
                                                  JOIN rb_ruangan d ON a.kode_ruangan=d.kode_ruangan
                                                  JOIN rb_kelas e ON a.kode_kelas=e.kode_kelas 
                                                    WHERE a.nip='$_SESSION[id]' AND a.id_tahun_akademik LIKE '" . date('Y') . "%' ORDER BY a.hari DESC");
              }
              $no = 1;
              while ($r = mysqli_fetch_array($tampil)) {
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
                                <td style='width:80px !important'><center>
                                          <a class='btn btn-success btn-xs' title='Lihat Journal' href='index.php?view=journalguru&act=lihat&id=$r[kodejdwl]'><span class='glyphicon glyphicon-search'></span> Lihat Journal</a>
                                        </center></td>
                            </tr>";
                $no++;
              }
              ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
<?php
} elseif ($_GET['act'] == 'lihat') {
  // Kode untuk 'lihat' di sini
} elseif ($_GET['act'] == 'edit') {
  // Kode untuk 'edit' di sini
}

// Tutup koneksi database
mysqli_close($conn);
?>