<div class="col-xs-12">
  <div class="box">
    <div class="box-header">
      <h3 class="box-title">Data Nilai </h3>
      <?php if ($_SESSION[level] != 'kepala') { ?>
        <a class='pull-right btn btn-primary btn-sm' href='index.php?view=nilai'>Tambahkan Data</a>
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

        <?php
        $tampil = mysql_query("SELECT * FROM rb_kriteria_nilai");

        //   var_dump($kriteriaNilai) ; // Mengambil data dari array ke-0
        //   echo $kriteriaNilai['kode_nilai'][0];
        // Form untuk mengupdate semua data
        echo "<form method='POST' action=''>";

        // Loop untuk menampilkan semua data dalam satu form
        while ($kriteriaNilai = mysql_fetch_array($tampil)) {
          echo "<p>
        <input type='hidden' name='id[]' value='{$kriteriaNilai['id']}'>
        <input type='text' name='kode_nilai[]' placeholder='Nilai Huruf' style='width: 40px;' value='{$kriteriaNilai['kode_nilai']}'>
        = <input type='text' name='nilai_bawah[]' style='width: 50px;' value='{$kriteriaNilai['nilai_bawah']}'> 
        - <input type='text' name='nilai_atas[]' style='width: 50px;' value='{$kriteriaNilai['nilai_atas']}'>
        <br>";
        }

        // Tombol Update untuk mengupdate semua data
        echo "<button type='submit' name='update' class='pull-right btn btn-primary btn-sm mt-2'>Update Semua</button>";
        echo "</form>";

        ?>

      </div><!-- /.table-responsive -->
    </div><!-- /.box-body -->
  </div><!-- /.box -->
</div>