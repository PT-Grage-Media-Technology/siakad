<div class="col-xs-12">
  <div class="box">
    <div class="box-header">
      <h3 class="box-title">
        <?php
        if (isset($_GET[tahun])) {
          echo "Input Nilai Raport STS";
        } else {
          echo "Input Nilai Raport STS Anda " . date('Y');
        }
        ?>
      </h3>
      <form style='margin-right:5px; margin-top:0px' class='pull-right' action='' method='GET'>
        <input type="hidden" name='view' value='raportuts'>
        <input type="hidden" name='act' value='detailguru'>
        <select name='tahun' style='padding:4px'>
          <?php
          echo "<option value=''>- Pilih Tahun Akademik -</option>";
          $tahun = mysql_query("SELECT * FROM rb_tahun_akademik");
          while ($k = mysql_fetch_array($tahun)) {
            if ($_GET[tahun] == $k[id_tahun_akademik]) {
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
      <!-- Tambahkan div ini untuk memberikan scroll horizontal -->
      <div class="table-responsive">
        <table id="example1" class="table table-bordered table-striped">
          <thead>
            <tr>
              <th style='width:20px'>No</th>
              <th>Jadwal Pelajaran</th>
              <th>Kelas</th>
              <th>Guru</th>
              <th>Hari</th>
              <th>Mulai</th>
              <th>Selesai</th>
              <th>Ruangan</th>
              <th>Semester</th>
              <?php
              if (isset($_GET[tahun])) {
                echo "<th>Action</th>";
              }
              ?>
            </tr>
          </thead>
          <tbody>
            <?php
            if (isset($_GET[tahun])) {
              $tampil = mysql_query("SELECT a.*, e.nama_kelas, b.namamatapelajaran, b.kode_pelajaran, b.kode_kurikulum, c.nama_guru, d.nama_ruangan FROM rb_jadwal_pelajaran a 
                                        JOIN rb_mata_pelajaran b ON a.kode_pelajaran=b.kode_pelajaran
                                        JOIN rb_guru c ON a.nip=c.nip 
                                        JOIN rb_ruangan d ON a.kode_ruangan=d.kode_ruangan
                                        JOIN rb_kelas e ON a.kode_kelas=e.kode_kelas 
                                        where a.nip='$_SESSION[id]' AND a.id_tahun_akademik='$_GET[tahun]' AND b.kode_kurikulum='$kurikulum[kode_kurikulum]' ORDER BY a.hari DESC");
            } else {
              $tampil = mysql_query("SELECT a.*, e.nama_kelas, b.namamatapelajaran, b.kode_pelajaran, b.kode_kurikulum, c.nama_guru, d.nama_ruangan FROM rb_jadwal_pelajaran a 
                                        JOIN rb_mata_pelajaran b ON a.kode_pelajaran=b.kode_pelajaran
                                        JOIN rb_guru c ON a.nip=c.nip 
                                        JOIN rb_ruangan d ON a.kode_ruangan=d.kode_ruangan
                                        JOIN rb_kelas e ON a.kode_kelas=e.kode_kelas 
                                        where a.nip='$_SESSION[id]' AND b.kode_kurikulum='$kurikulum[kode_kurikulum]' AND a.id_tahun_akademik LIKE '" . date('Y') . "%' ORDER BY a.hari DESC");
            }
            $no = 1;
            while ($r = mysql_fetch_array($tampil)) {
              echo "<tr><td>$no</td>
                          <td>$r[namamatapelajaran]</td>
                          <td>$r[nama_kelas]</td>
                          <td>$r[nama_guru]</td>
                          <td>$r[hari]</td>
                          <td>$r[jam_mulai]</td>
                          <td>$r[jam_selesai]</td>
                          <td>$r[nama_ruangan]</td>
                          <td>$r[id_tahun_akademik]</td>";
              if (isset($_GET[tahun])) {
                echo "<td style='width:70px !important'><center>
                                    <a class='btn btn-success btn-xs' title='Lihat Siswa' href='index.php?view=raportuts&act=listsiswa&jdwl=$r[kodejdwl]&kd=$r[kode_pelajaran]&id=$r[kode_kelas]&tahun=$_GET[tahun]'>
                                      <span class='glyphicon glyphicon-th-list'></span> Input Nilai
                                    </a>
                                  </center></td>";
              }
              echo "</tr>";
              $no++;
            }
            ?>
          </tbody>
        </table>
      </div><!-- /.table-responsive -->
    </div><!-- /.box-body -->
  </div>
</div>