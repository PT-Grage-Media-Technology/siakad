<div class="col-xs-12">
  <div class="box">
    <div class="box-header">
      <h3 class="box-title">Data Bobot Nilai Raport </h3>
      <?php if ($_SESSION['level'] != 'kepala') { ?>
        <!-- <a class='pull-right btn btn-primary btn-sm' href='index.php?view=nilai'>Tambahkan Data</a> -->
      <?php } ?>
    </div><!-- /.box-header -->
    <div class="box-body">
      <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
        <!-- <p><input type="text"> = <input type="text"> - <input type="text"></p> -->
        <?php
        if (isset($_POST['update'])) {
          $bobot = $_POST['bobot'];
          $id = $_POST['id'];
          mysql_query("UPDATE rb_bobot_raport SET bobot='$bobot' WHERE id='$id'");
          echo "UPDATE rb_bobot_raport SET bobot='$bobot' WHERE id='$id'";
        
        }
        ?>

        <?php

        $tampil = mysql_query("SELECT * FROM rb_bobot_raport");

        //   var_dump($kriteriaNilai) ; // Mengambil data dari array ke-0
        //   echo $kriteriaNilai['kode_nilai'][0];
        // Form untuk mengupdate semua data
        echo "<form method='POST' action=''>";

        // Loop untuk menampilkan semua data dalam satu form
        while ($kriteriaNilai = mysql_fetch_array($tampil)) {
      
          echo "<table style='border-collapse: collapse; width: auto;'>
          <tr>
            <td style='text-align: left; vertical-align: middle; padding: 3px;'>
              {$kriteriaNilai['jenis_nilai']} =
              <input type='hidden' name='id' value='{$kriteriaNilai['id']}'>
            </td>
            <td style='text-align: center; vertical-align: middle; padding: 3px;'>
              <input name='bobot' type='number' value='{$kriteriaNilai['bobot']}'
                     style='width: 50px; text-align: center; margin: 0;'>
            </td>
            <td style='text-align: left; vertical-align: middle; padding: 3px;'>%</td>
          </tr>
        </table>";
  
        
        }

        // Tombol Update untuk mengupdate semua data
        echo "<button type='submit' name='update' class='pull-right btn btn-primary btn-sm mt-2'>Update Semua</button>";
        echo "</form>";

        ?>

      </div><!-- /.table-responsive -->
    </div><!-- /.box-body -->
  </div><!-- /.box -->
</div>