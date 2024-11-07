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
                  while($kriteriaNilai = mysql_fetch_array($tampil)){

                    //   var_dump($kriteriaNilai) ; // Mengambil data dari array ke-0
                    //   echo $kriteriaNilai['kode_nilai'][0];
                  

       
        echo"<p>{$kriteriaNilai['id']}
            <input type='text' placeholder='Nilai Huruf' style='width: 40px;' value='{$kriteriaNilai['kode_nilai']}'> = <input type='text' style='width: 50px;' value='" . ($kriteriaNilai['nilai_bawah']) . "'> - <input type='text' style='width: 50px;' value='" . ($kriteriaNilai['nilai_atas']) . "'>";

          }
          echo"<a class='pull-right btn btn-primary btn-sm' href='index.php?view=nilai&act=simpan'>Simpan</a>";
        ?>
            
      </div><!-- /.table-responsive -->
    </div><!-- /.box-body -->
  </div><!-- /.box -->
</div>