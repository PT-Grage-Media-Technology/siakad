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
      <th>Jam</th>
      <th>Kode Kelas</th>
      <th>Nama Mapel</th>
      <th>Tujuan Pembelajaran</th>
    </tr>
  </thead>
  <tbody>
  <?php
            $tampil = mysql_query("SELECT * FROM rb_journal_list");

            var_dump(mysql_fetch_array($tampil));
            $no = 1;
            while ($r = mysql_fetch_array($tampil)) {
              echo "<tr>
                      <td>$no</td>
                      <td>$r[users]</td>
                      <td>$r[nama_guru]</td>
                      <td>$r[hari]</td>
                      <td><?php echo date('d F Y', strtotime($r[tanggal])); ?></td>
                      <td>$r[jam]</td>
                      <td>$r[kode_kelas]</td>
                      <td>$r[kode_pelajaran]</td>
                      <td><a class='btn btn-success btn-xs' href='index.php?view=journalguru&act=lihat&id=$r[kodejdwl]'>Tujuan Pembelajaran</a></td>
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
