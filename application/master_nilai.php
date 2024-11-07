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
       $query = mysql_query("SELECT * FROM rb_kriteria_nilai");
       $kriteriaNilai = mysql_fetch_array($query);
       
       // Jika data ditemukan, tampilkan dalam form
       if ($kriteriaNilai) {
           echo "<form method='POST' action=''>
               <p>ID: {$kriteriaNilai['id']}</p>
               <input type='hidden' name='id' value='{$kriteriaNilai['id']}'>
               <input type='text' name='kode_nilai' placeholder='Nilai Huruf' style='width: 40px;' value='{$kriteriaNilai['kode_nilai']}'>
               = <input type='text' name='nilai_bawah' style='width: 50px;' value='{$kriteriaNilai['nilai_bawah']}'> 
               - <input type='text' name='nilai_atas' style='width: 50px;' value='{$kriteriaNilai['nilai_atas']}'>
               <button type='submit' name='update' class='btn btn-primary btn-sm'>Update</button>
           </form>";
        }
        echo "<a class='pull-left btn btn-primary btn-sm' name='update' href='index.php?view=nilai'>Simpan</a>";
        ?>

      </div><!-- /.table-responsive -->
    </div><!-- /.box-body -->
  </div><!-- /.box -->
</div>