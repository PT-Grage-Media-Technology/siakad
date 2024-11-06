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
        <!-- <p><input type="text"> = <input type="text"> - <input type="text"></p> -->
        <?php 
                  $tampil = mysql_query("SELECT * FROM rb_kriteria_nilai");

                  while ($kriteriaNilai = mysql_fetch_array($tampil)) {
                      // Akses data dengan nama kolom
                      $id = $kriteriaNilai['id']; // Misalnya kolom id
                      $kode_nilai = $kriteriaNilai['kode_nilai']; // Kolom kode_nilai
                      $nilai_min = $kriteriaNilai['nilai_min']; // Kolom nilai_min
                      
                      echo "ID: $id, Kode Nilai: $kode_nilai, Nilai Min: $nilai_min<br>";
                  }
        ?>
      </div><!-- /.table-responsive -->
    </div><!-- /.box-body -->
  </div><!-- /.box -->
</div>