<?php if ($_GET[act] == '') { ?>
  
  <div class="container-fluid">

    <div class="row">
      <div class="col-12">
        <div class="box">
          <div class="box-header d-flex justify-content-between align-items-center">
            <h3 class="box-title">Semua Data Guru</h3>
            <?php if ($_SESSION['level'] != 'kepala') { ?>
              <a class='btn btn-primary btn-sm' href='index.php?view=guru&act=tambahguru'>Tambahkan Data Guru</a>
            <?php } ?>
          </div><!-- /.box-header -->

          <div class="box-body">
            <div class="table-responsive">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>NIP</th>
                    <th>Nama Lengkap</th>
                    <th>Jenis Kelamin</th>
                    <th>No Telpon</th>
                    <th>Status Pegawai</th>
                    <th>Jenis PTK</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $tampil = mysql_query("SELECT * FROM rb_guru a 
                                      LEFT JOIN rb_jenis_kelamin b ON a.id_jenis_kelamin=b.id_jenis_kelamin 
                                      LEFT JOIN rb_status_kepegawaian c ON a.id_status_kepegawaian=c.id_status_kepegawaian 
                                      LEFT JOIN rb_jenis_ptk d ON a.id_jenis_ptk=d.id_jenis_ptk
                                      ORDER BY a.nip DESC");
                  $no = 1;
                  while ($r = mysql_fetch_array($tampil)) {
                    echo "<tr>
                          <td>$no</td>
                          <td>$r[nip]</td>
                          <td>$r[nama_guru]</td>
                          <td>$r[jenis_kelamin]</td>
                          <td>$r[hp]</td>
                          <td>$r[status_kepegawaian]</td>
                          <td>$r[jenis_ptk]</td>";
                    if ($_SESSION['level'] != 'kepala') {
                      echo "<td>
                            <center>
                              <a class='btn btn-info btn-xs' title='Lihat Detail' href='?view=guru&act=detailguru&id=$r[nip]'><span class='glyphicon glyphicon-search'></span></a>
                              <a class='btn btn-success btn-xs' title='Edit Data' href='?view=guru&act=editguru&id=$r[nip]'><span class='glyphicon glyphicon-edit'></span></a>
                              <a class='btn btn-danger btn-xs' title='Delete Data' href='?view=guru&hapus=$r[nip]'><span class='glyphicon glyphicon-remove'></span></a>
                            </center>
                          </td>";
                    } else {
                      echo "<td>
                            <center>
                              <a class='btn btn-info btn-xs' title='Lihat Detail' href='?view=guru&act=detailguru&id=$r[nip]'><span class='glyphicon glyphicon-search'></span></a>
                            </center>
                          </td>";
                    }
                    echo "</tr>";
                    $no++;
                  }
                  if (isset($_GET['hapus'])) {
                    mysql_query("DELETE FROM rb_guru WHERE nip='$_GET[hapus]'");
                    echo "<script>document.location='index.php?view=guru';</script>";
                  }
                  ?>
                </tbody>
              </table>
            </div><!-- /.table-responsive -->
          </div><!-- /.box-body -->
        </div><!-- /.box -->
      </div><!-- /.col-12 -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->

  <?php } ?>