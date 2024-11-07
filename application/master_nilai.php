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
        if (isset($_POST['update'])) {
          var_dump($_POST);
          exit;
          $id = $_POST['id'];
          $kode_nilai = $_POST['kode_nilai'];
          $nilai_bawah = $_POST['nilai_bawah'];
          $nilai_atas = $_POST['nilai_atas'];

          // Query untuk update data berdasarkan ID
          $query = "UPDATE rb_kriteria_nilai SET kode_nilai = '$kode_nilai', nilai_bawah = '$nilai_bawah', nilai_atas = '$nilai_atas' WHERE id = '$id'";

          mysql_query($query);
          echo "Data berhasil diupdate!";
        }
        ?>

        <?php
        $tampil = mysql_query("SELECT * FROM rb_kriteria_nilai");
        while ($kriteriaNilai = mysql_fetch_array($tampil)) {

          //   var_dump($kriteriaNilai) ; // Mengambil data dari array ke-0
          //   echo $kriteriaNilai['kode_nilai'][0];
        


          echo "<p>
            <input type='text' style='width: 50px;'  name='id' value='" . ($kriteriaNilai['id']) . "' hidden>
            <input type='text' placeholder='Nilai Huruf'  name='kode_nilai'  style='width: 40px;' value='{$kriteriaNilai['kode_nilai']}'> = 
            <input type='text' style='width: 50px;' name='nilai_bawah'  value='" . ($kriteriaNilai['nilai_bawah']) . "'> - 
            <input type='text' style='width: 50px;'  name='nilai_atas' value='" . ($kriteriaNilai['nilai_atas']) . "'>
            </p>";

        }
        echo "<a class='pull-left btn btn-primary btn-sm' name='update' href='index.php?view=nilai'>Simpan</a>";
        ?>

      </div><!-- /.table-responsive -->
    </div><!-- /.box-body -->
  </div><!-- /.box -->
</div>