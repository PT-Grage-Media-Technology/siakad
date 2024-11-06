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
                   $query = "SELECT * FROM rb_kriteria_nilai";
                   $tampil = mysql_query($query);
                   
                   if ($tampil) {
                       while ($row = mysql_fetch_assoc($tampil)) {
                           // Ambil nilai dari setiap kolom, dan gunakan htmlspecialchars untuk keamanan
                           $nilaiHuruf = htmlspecialchars($row['nilai_huruf']);  
                           $nilaiMin = htmlspecialchars($row['nilai_min']);      
                           $nilaiMax = htmlspecialchars($row['nilai_max']);      
                   
                           // Tampilkan data dalam struktur HTML
                           echo "<p>";
                           echo "<input type='text' placeholder='Nilai Huruf' style='width: 40px;' value='$nilaiHuruf'> = ";
                           echo "<input type='text' style='width: 50px;' value='$nilaiMin'> - ";
                           echo "<input type='text' style='width: 50px;' value='fsdfdd'>";
                           echo "</p>";
                       }
                   } else {
                       echo "Tidak ada data yang ditemukan.";
                   }
                    

        ?>
        <p>
          <input type="text" placeholder="Nilai Huruf" style="width: 40px;" value="A"> = <input type="text" style="width: 50px;"> - <input type="text" style="width: 50px;">
        </p>
        <!-- <p>
          <input type="text" placeholder="Nilai Huruf" style="width: 40px;" value="B"> = <input type="text" style="width: 50px;"> - <input type="text" style="width: 50px;">
        </p>
        <p>
          <input type="text" placeholder="Nilai Huruf" style="width: 40px;" value="C"> = <input type="text" style="width: 50px;"> - <input type="text" style="width: 50px;">
        </p>
        <p>
          <input type="text" placeholder="Nilai Huruf" style="width: 40px;" value="D"> = <input type="text" style="width: 50px;"> - <input type="text" style="width: 50px;">
        </p>
        <p>
          <input type="text" placeholder="Nilai Huruf" style="width: 40px;" value="E"> = <input type="text" style="width: 50px;"> - <input type="text" style="width: 50px;">
        </p>
        <p>
          <input type="text" placeholder="Nilai Huruf" style="width: 40px;" value="F"> = <input type="text" style="width: 50px;"> - <input type="text" style="width: 50px;">
        </p> -->

      </div><!-- /.table-responsive -->
    </div><!-- /.box-body -->
  </div><!-- /.box -->
</div>