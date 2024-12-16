<?php
if ($_GET['act'] == '') {
    $d = mysql_fetch_array(mysql_query("SELECT * FROM rb_kelas where kode_kelas='$_GET[id]'"));
    $m = mysql_fetch_array(mysql_query("SELECT * FROM rb_mata_pelajaran where kode_pelajaran='$_GET[kd]'"));

    // Ambil filter bulan dan tahun jika ada
    $filterBulan = isset($_GET['bulan']) ? $_GET['bulan'] : date('m');
    $filterTahun = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');

    echo "<div class='col-md-12'>
              <div class='box box-info'>
                <div class='box-header with-border'>
                  <h3 class='box-title'>Rekap Data Absensi Guru Tahun $filterTahun Bulan $filterBulan</h3>
                </div>
              <div class='box-body'>

              <div class='col-md-12'>
                <form method='GET' action=''>
                  <input type='hidden' name='act' value=''>
                  <div class='form-group col-md-4'>
                    <label for='bulan'>Pilih Bulan:</label>
                    <select name='bulan' class='form-control'>
                      <option value='01' " . ($filterBulan == '01' ? 'selected' : '') . ">Januari</option>
                      <option value='02' " . ($filterBulan == '02' ? 'selected' : '') . ">Februari</option>
                      <option value='03' " . ($filterBulan == '03' ? 'selected' : '') . ">Maret</option>
                      <option value='04' " . ($filterBulan == '04' ? 'selected' : '') . ">April</option>
                      <option value='05' " . ($filterBulan == '05' ? 'selected' : '') . ">Mei</option>
                      <option value='06' " . ($filterBulan == '06' ? 'selected' : '') . ">Juni</option>
                      <option value='07' " . ($filterBulan == '07' ? 'selected' : '') . ">Juli</option>
                      <option value='08' " . ($filterBulan == '08' ? 'selected' : '') . ">Agustus</option>
                      <option value='09' " . ($filterBulan == '09' ? 'selected' : '') . ">September</option>
                      <option value='10' " . ($filterBulan == '10' ? 'selected' : '') . ">Oktober</option>
                      <option value='11' " . ($filterBulan == '11' ? 'selected' : '') . ">November</option>
                      <option value='12' " . ($filterBulan == '12' ? 'selected' : '') . ">Desember</option>
                    </select>
                  </div>
                  <div class='form-group col-md-4'>
                    <label for='tahun'>Pilih Tahun:</label>
                    <input type='number' name='tahun' value='$filterTahun' class='form-control'>
                  </div>
                  <div class='form-group col-md-4'>
                    <button type='submit' class='btn btn-primary' style='margin-top: 25px;'>Filter</button>
                  </div>
                </form>
              </div>

              <div class='col-md-12'>
                <h4>Data Absensi Guru Periode: $filterBulan - $filterTahun</h4>
              </div>

              <div class='col-md-12'>
                <div class='table-responsive'>
                  <table class='table table-condensed table-bordered table-striped table-hover'>
                    <thead>
                      <tr>
                        <th>No</th>
                        <th>Nip</th>
                        <th>Nama Guru</th>
                        <th>Hadir</th>
                        <th>Sakit</th>
                        <th>Izin</th>
                        <th>Alpa</th>
                      </tr>
                    </thead>
                    <tbody>";

    $no = 1;
    $tampil = mysql_query("SELECT * FROM rb_guru WHERE id_jenis_ptk NOT IN (6, 7) ORDER BY nama_guru ASC");
    while ($r = mysql_fetch_array($tampil)) {
        $hadir = mysql_num_rows(mysql_query("SELECT * FROM `rb_absensi_guru` WHERE nip='$r[nip]' AND kode_kehadiran='Hadir' AND MONTH(tanggal)='$filterBulan' AND YEAR(tanggal)='$filterTahun'"));
        $sakit = mysql_num_rows(mysql_query("SELECT * FROM `rb_rekap_absen_guru` WHERE nip='$r[nip]' AND kode_kehadiran='sakit' AND MONTH(tanggal)='$filterBulan' AND YEAR(tanggal)='$filterTahun'"));
        $izin = mysql_num_rows(mysql_query("SELECT * FROM `rb_rekap_absen_guru` WHERE nip='$r[nip]' AND kode_kehadiran='izin' AND MONTH(tanggal)='$filterBulan' AND YEAR(tanggal)='$filterTahun'"));
        $alpa = mysql_num_rows(mysql_query("SELECT * FROM `rb_rekap_absen_guru` WHERE nip='$r[nip]' AND kode_kehadiran='alpa' AND MONTH(tanggal)='$filterBulan' AND YEAR(tanggal)='$filterTahun'"));

        echo "<tr>
                <td>$no</td>
                <td>$r[nip]</td>
                <td>$r[nama_guru]</td>
                <td align=center>$hadir</td>
                <td align=center>$sakit</td>
                <td align=center>$izin</td>
                <td align=center>$alpa</td>
              </tr>";
        $no++;
    }

    echo "</tbody>
                  </table>
                </div>
              </div>
            </div>";
}
?>

<style>
  .table-responsive {
    overflow-x: auto;
  }

  @media (min-width: 768px) {
    .table-responsive {
      overflow-x: visible;
    }
  }
</style>
