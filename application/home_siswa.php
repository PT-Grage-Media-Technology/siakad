<?php if ($_GET['act'] == '') { ?>
  <div class="col-xs-12">
    <div class="box">
      <div class="box-header">
        <?php
        // Ambil semua tahun akademik dan simpan dalam array
        $tahun_query = mysql_query("SELECT * FROM rb_tahun_akademik ORDER BY id_tahun_akademik DESC");
        $tahun_list = [];
        while ($row = mysql_fetch_array($tahun_query)) {
          $tahun_list[] = $row;
        }

        // Ambil tahun terbaru dari hasil array
        $tahun_terbaru = $tahun_list[0]['id_tahun_akademik'];

        // Gunakan tahun terbaru jika tidak ada tahun yang dipilih
        $tahun_dipilih = isset($_GET['tahun']) ? $_GET['tahun'] : $tahun_terbaru;

        // Ambil nama tahun akademik yang dipilih
        $nama_tahun_dipilih = '';
        foreach ($tahun_list as $tahun) {
          if ($tahun_dipilih == $tahun['id_tahun_akademik']) {
            $nama_tahun_dipilih = $tahun['nama_tahun'];
            break;
          }
        }
        ?>

        <h3 class="box-title">
          <?php
          if (isset($_GET['tahun'])) {
            echo "Jadwal Pelajaran untuk Tahun Akademik: $nama_tahun_dipilih";
          } else {
            echo "Jadwal Pelajaran untuk Tahun Akademik Terbaru: $nama_tahun_dipilih";
          }
          ?>
        </h3>

        <form style='margin-right:5px; margin-top:0px' class='pull-right' action='' method='GET'>
          <select name='tahun' style='padding:4px' onchange='this.form.submit()'>
            <option value=''>- Pilih Tahun Akademik -</option>
            <?php
            foreach ($tahun_list as $tahun) {
              $selected = ($tahun_dipilih == $tahun['id_tahun_akademik']) ? 'selected' : '';
              echo "<option value='{$tahun['id_tahun_akademik']}' $selected>{$tahun['nama_tahun']}</option>";
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
              $tampil = mysql_query("SELECT a.*, e.nama_kelas, b.namamatapelajaran, b.kode_pelajaran, c.nama_guru, d.nama_ruangan 
                                     FROM rb_jadwal_pelajaran a
                                     JOIN rb_mata_pelajaran b ON a.kode_pelajaran = b.kode_pelajaran
                                     JOIN rb_guru c ON a.nip = c.nip 
                                     JOIN rb_ruangan d ON a.kode_ruangan = d.kode_ruangan
                                     JOIN rb_kelas e ON a.kode_kelas = e.kode_kelas 
                                     WHERE a.kode_kelas = '$_SESSION[kode_kelas]' 
                                     AND a.id_tahun_akademik = '$tahun_dipilih' 
                                     ORDER BY a.hari DESC");

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
                        <td><a class='btn btn-success btn-xs' title='Lihat Data' href='index.php?view=home&act=kompetensidasar&kodejdwl=$r[kodejdwl]'><span class='glyphicon glyphicon-list'></span> Detail</a></td>
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


<?php
} elseif ($_GET[act] == 'kompetensidasar') {
  $d = mysql_fetch_array(mysql_query("SELECT * FROM rb_jadwal_pelajaran a JOIN rb_mata_pelajaran b ON a.kode_pelajaran=b.kode_pelajaran JOIN rb_kelas c ON a.kode_kelas=c.kode_kelas where a.kodejdwl='$_GET[kodejdwl]'"));
  echo "<div class='col-12'>  
            <div class='box'>
              <div class='box-header'>
                <h3 class='box-title'>Kompetensi Dasar</h3>
              </div>
              <div class='box-body'>
                <div class='col-12'>
                <table class='table table-condensed table-hover'>
                    <tbody>
                      <input type='hidden' name='id' value='$d[kodekelas]'>
                      <tr><th width='120px' scope='row'>Kode Kelas</th> <td>$d[kode_kelas]</td></tr>
                      <tr><th scope='row'>Nama Kelas</th>               <td>$d[nama_kelas]</td></tr>
                      <tr><th scope='row'>Mata Pelajaran</th>           <td>$d[namamatapelajaran]</td></tr>
                    </tbody>
                </table>
                </div>

                <table class='table table-bordered table-striped'>
                  <thead>
                    <tr>
                      <th style='width:20px'>No</th>
                      <th>Kelas</th>
                      <th>Mata Pelajaran</th>
                      <th>Ranah</th>
                      <th>Indikator</th>
                    </tr>
                  </thead>
                  <tbody>";
  $tampil = mysql_query("SELECT * FROM rb_kompetensi_dasar z JOIN rb_jadwal_pelajaran a ON z.kodejdwl=a.kodejdwl JOIN rb_kelas b ON a.kode_kelas=b.kode_kelas JOIN rb_mata_pelajaran c ON a.kode_pelajaran=c.kode_pelajaran where a.kodejdwl='$_GET[kodejdwl]' ORDER BY z.id_kompetensi_dasar DESC");
  $no = 1;
  while ($r = mysql_fetch_array($tampil)) {
    echo "<tr><td>$no</td>
                            <td>$r[nama_kelas]</td>
                            <td>$r[namamatapelajaran]</td>
                            <td>$r[ranah]</td>
                            <td>$r[kompetensi_dasar]</td>
                        </tr>";
    $no++;
  }
  echo "<tbody>
                </table>
              </div>
              </div>
          </div>";
}
?>