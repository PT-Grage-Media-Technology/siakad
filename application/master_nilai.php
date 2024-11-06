
  <div class="col-xs-12">
    <div class="box">
      <div class="box-header">
        <h3 class="box-title">Data Nilai </h3>
        <?php if ($_SESSION['level'] != 'kepala') { ?>
          <a class='pull-right btn btn-primary btn-sm' href='index.php?view=ruangan&act=tambah'>Tambahkan Data</a>
        <?php } ?>
      </div><!-- /.box-header -->
      <div class="box-body">
        <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
          <table id="example1" class="table table-bordered table-striped">
            <thead>
              <tr>
                <th style='width:40px'>No</th>
                <th>Kode Ruangan</th>
                <th>Nama Gedung</th>
                <th>Nama Ruangan</th>
                <th>Kapasitas Belajar</th>
                <th>Kapasitas Ujian</th>
                <th>Keterangan</th>
                <th>Aktif</th>
                <?php if ($_SESSION['level'] != 'kepala') { ?>
                  <th style='width:70px'>Action</th>
                <?php } ?>
              </tr>
            </thead>
            <tbody>
              <?php
              $tampil = mysql_query("SELECT * FROM rb_ruangan a 
                                                JOIN rb_gedung b ON a.kode_gedung = b.kode_gedung 
                                                ORDER BY a.kode_ruangan DESC");
              $no = 1;
              while ($r = mysql_fetch_array($tampil)) {
                echo "<tr><td>$no</td>
                                  <td>$r[kode_ruangan]</td>
                                  <td>$r[nama_gedung]</td>
                                  <td>$r[nama_ruangan]</td>
                                  <td>$r[kapasitas_belajar] Orang</td>
                                  <td>$r[kapasitas_ujian] Orang</td>
                                  <td>$r[keterangan]</td>
                                  <td>$r[aktif]</td>";
                if ($_SESSION['level'] != 'kepala') {
                  echo "<td><center>
                                    <a class='btn btn-success btn-xs' title='Edit Data' href='?view=ruangan&act=edit&id=$r[kode_ruangan]'><span class='glyphicon glyphicon-edit'></span></a>
                                    <a class='btn btn-danger btn-xs' title='Delete Data' href='?view=ruangan&hapus=$r[kode_ruangan]'><span class='glyphicon glyphicon-remove'></span></a>
                                  </center></td>";
                }
                echo "</tr>";
                $no++;
              }
              if (isset($_GET['hapus'])) {
                mysql_query("DELETE FROM rb_ruangan WHERE kode_ruangan='$_GET[hapus]'");
                echo "<script>document.location='index.php?view=ruangan';</script>";
              }
              ?>
            </tbody>
          </table>
        </div><!-- /.table-responsive -->
      </div><!-- /.box-body -->
    </div><!-- /.box -->
  </div>