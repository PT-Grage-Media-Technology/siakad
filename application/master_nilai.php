<div class="col-xs-12">
  <div class="box">
    <div class="box-header">
      <h3 class="box-title">Data Nilai </h3>
      <?php if ($_SESSION['level'] != 'kepala') { ?>
        <!-- <a class='pull-right btn btn-primary btn-sm' href='index.php?view=nilai'>Tambahkan Data</a> -->
      <?php } ?>
    </div><!-- /.box-header -->
    <div class="box-body">
      <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
        <!-- <p><input type="text"> = <input type="text"> - <input type="text"></p> -->
        <?php
        if (isset($_POST['update'])) {
          foreach ($_POST['id'] as $key => $id) {
            $kode_nilai = $_POST['kode_nilai'][$key];
            $nilai_bawah = $_POST['nilai_bawah'][$key];
            $nilai_atas = $_POST['nilai_atas'][$key];

            // Query untuk update data berdasarkan ID
            $update_query = "UPDATE rb_kriteria_nilai SET 
                                kode_nilai = '$kode_nilai', 
                                nilai_bawah = '$nilai_bawah', 
                                nilai_atas = '$nilai_atas' 
                             WHERE id = '$id'";
            mysql_query($update_query);
          }
        }
        ?>

        

      </div><!-- /.table-responsive -->
    </div><!-- /.box-body -->
  </div><!-- /.box -->
</div>