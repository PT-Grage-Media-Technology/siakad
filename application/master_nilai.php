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
                  $kriteriaNilai = mysql_fetch_array($tampil);
                  echo $kriteriaNilai; // Mengambil data dari array ke-0

       
        echo"<p>
          <input type='text' placeholder='Nilai Huruf' style='width: 40px;' value='$kriteriaNilai[kode_nilai]'> = <input type='text' style='width: 50px;'> - <input type='text' style='width: 50px;'>
        </p>
        <p>
          <input type='text' placeholder='Nilai Huruf' style='width: 40px;' value='B'> = <input type='text' style='width: 50px;'> - <input type='text' style='width: 50px;'>
        </p>
        <p>
          <input type='text' placeholder='Nilai Huruf' style='width: 40px;' value='C'> = <input type='text' style='width: 50px;'> - <input type='text' style='width: 50px;'>
        </p>
        <p>
          <input type='text' placeholder='Nilai Huruf' style='width: 40px;' value='D'> = <input type='text' style='width: 50px;'> - <input type='text' style='width: 50px;'>
        </p>
        <p>
          <input type='text' placeholder='Nilai Huruf' style='width: 40px;' value='E'> = <input type='text' style='width: 50px;'> - <input type='text' style='width: 50px;'>
        </p>
        <p>
          <input type='text' placeholder='Nilai Huruf' style='width: 40px;' value='F'> = <input type='text' style='width: 50px;'> - <input type='text' style='width: 50px;'>
        </p>";
        ?>
      </div><!-- /.table-responsive -->
    </div><!-- /.box-body -->
  </div><!-- /.box -->
</div>