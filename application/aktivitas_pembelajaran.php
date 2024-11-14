<div class="col-xs-12">
  <div class="box">
    <div class="box-header">
      <h3 class="box-title">
        <?php
        // Ambil tahun akademik terbaru (id_tahun_akademik paling besar)
        $latest_year = mysql_fetch_array(mysql_query("SELECT id_tahun_akademik, nama_tahun FROM rb_tahun_akademik ORDER BY id_tahun_akademik DESC LIMIT 1"));


        // Jika tidak ada tahun akademik dipilih, set default ke tahun terbaru
        $tahun_dipilih = isset($_GET['tahun']) ? $_GET['tahun'] : $latest_year['id_tahun_akademik'];
        $nama_tahun = isset($_GET['tahun']) ?
          mysql_fetch_array(mysql_query("SELECT nama_tahun FROM rb_tahun_akademik WHERE id_tahun_akademik = '$tahun_dipilih'"))['nama_tahun'] :
          $latest_year['nama_tahun'];

        echo "Aktivitas Pembelajaran Guru - $nama_tahun";
        ?>
      </h3>
      <form style='margin-right:5px; margin-top:0px' class='pull-right' action='' method='GET'>

        <input type="hidden" name="view" value="aktivitaspembelajaran">

        <!-- Filter Tanggal -->
        <select name='tanggal' style='padding:4px' onchange='this.form.submit()'>

          <?php
          $today = date('j'); // Mengambil tanggal hari ini
          $selectedTanggal = isset($_GET['tanggal']) ? $_GET['tanggal'] : $today; // Default ke tanggal hari ini jika kosong
          
          for ($i = 1; $i <= 31; $i++) {
            $selected = ($selectedTanggal == $i) ? 'selected' : '';
            echo "<option value='$i' $selected>$i</option>";
          }
          ?>
        </select>


        <!-- Filter Bulan -->
        <select name='bulan' style='padding:4px' onchange='this.form.submit()'>
          <?php
          $currentMonth = date('n'); // Mengambil bulan saat ini
          $selectedBulan = isset($_GET['bulan']) ? $_GET['bulan'] : $currentMonth; // Default ke bulan saat ini jika kosong
          $bulanNama = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

          for ($i = 1; $i <= 12; $i++) {
            $selected = ($selectedBulan == $i) ? 'selected' : '';
            echo "<option value='$i' $selected>{$bulanNama[$i - 1]}</option>";
          }
          ?>
        </select>

        <select name='tahun' style='padding:4px' onchange='this.form.submit()'>
          <option value=''>- Pilih Tahun Akademik -</option>
          <?php
          $tahun = mysql_query("SELECT * FROM rb_tahun_akademik ORDER BY id_tahun_akademik DESC");
          while ($k = mysql_fetch_array($tahun)) {
            $selected = ($tahun_dipilih == $k['id_tahun_akademik']) ? 'selected' : '';
            echo "<option value='$k[id_tahun_akademik]' $selected>$k[nama_tahun]</option>";
          }
          ?>
        </select>
      </form>
      </div><!-- /.box-header -->



    <div class="box-body">
      <div class="table-responsive">
        <table id="example1" class="table table-bordered table-striped">
          <tbody>
            <?php
            // Mengambil tanggal yang dipilih dari GET
            // Ambil tanggal dan bulan yang dipilih dari GET
            $tanggal_dipilih = isset($_GET['tanggal']) ? $_GET['tanggal'] : date('d');
            $bulan_dipilih = isset($_GET['bulan']) ? $_GET['bulan'] : date('n');
            $tahun_dipilih = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y'); // Tambahkan tahun yang dipilih

            // Ubah query untuk memfilter berdasarkan tanggal, bulan, dan tahun yang dipilih
            $tampil = mysql_query("SELECT jl.*, a.kode_kelas, b.nama_kelas, c.namamatapelajaran, c.kode_pelajaran, d.nama_guru 
            FROM rb_journal_list jl 
            JOIN rb_jadwal_pelajaran a ON jl.kodejdwl = a.kodejdwl
            JOIN rb_kelas b ON a.kode_kelas = b.kode_kelas 
            JOIN rb_mata_pelajaran c ON a.kode_pelajaran = c.kode_pelajaran 
            JOIN rb_guru d ON jl.users = d.nip
            WHERE DAY(jl.tanggal) = '$tanggal_dipilih' 
            AND MONTH(jl.tanggal) = '$bulan_dipilih'
            AND YEAR(jl.tanggal) = '$tahun_dipilih'  // Tambahkan filter tahun
            AND jl.users = '{$_SESSION['id']}'
            ORDER BY jl.waktu_input DESC;
            ");
            ?>
          </tbody>
        </table>
      </div><!-- /.table-responsive -->
    </div><!-- /.box-body -->
  </div>
</div>

<script>
function submitFormWithAlert() {
    // Pesan konfirmasi
    const confirmSubmit = confirm("Apakah Anda yakin ingin mengirimkan peringatan?");
    if (confirmSubmit) {
        // Jika konfirmasi "OK", form akan disubmit
        document.getElementById('pemberitahuan').submit();
        return true;
    }
    // Jika konfirmasi "Cancel", form tidak akan disubmit
    return false;
}
</script>
