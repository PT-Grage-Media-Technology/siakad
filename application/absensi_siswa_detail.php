<?php
if ($_GET['act'] == '') {
    $d = mysql_fetch_array(mysql_query("SELECT * FROM rb_kelas where kode_kelas='$_GET[id]'"));
    $m = mysql_fetch_array(mysql_query("SELECT * FROM rb_mata_pelajaran where kode_pelajaran='$_GET[kd]'"));

    // Ambil filter bulan dan tahun jika ada
    $filterBulan = isset($_GET['bulan']) ? $_GET['bulan'] : date('m');
    $filterTahun = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');
    $jumlahHari = cal_days_in_month(CAL_GREGORIAN, $filterBulan, $filterTahun);

    echo "<div class='col-md-12 print-page'>
              <div class='box box-info'>
                <div class='box-header with-border'>
                  <h3 class='box-title'>Rekap Data Absensi Guru Tahun $filterTahun Bulan $filterBulan</h3>
                </div>
              <div class='box-body'>

              <div class='col-md-12'>
                <form method='GET' action=''>
                  <input type='hidden' name='view' value='rekapguru'>
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
                    <button type='button' class='btn btn-success' style='margin-top: 25px;' onclick='window.print()'>Print</button>
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
                        <th rowspan='2'>No</th>
                        <th rowspan='2'>Nip</th>
                        <th rowspan='2'>Nama Guru</th>
                        <th colspan='$jumlahHari'>Tanggal</th>
                      </tr>
                      <tr>";

    // Generate header tanggal sesuai bulan
    for ($i = 1; $i <= $jumlahHari; $i++) {
        echo "<th>$i</th>";
    }

    echo "        </tr>
                    </thead>
                    <tbody>";

    $no = 1;
    $tampil = mysql_query("SELECT * FROM rb_guru WHERE id_jenis_ptk NOT IN (6, 7) ORDER BY nama_guru ASC");
    while ($r = mysql_fetch_array($tampil)) {
        echo "<tr>
                <td>$no</td>
                <td>$r[nip]</td>
                <td>$r[nama_guru]</td>";

        // Isi data absensi per tanggal
        for ($i = 1; $i <= $jumlahHari; $i++) {
            $tanggal = $filterTahun . '-' . $filterBulan . '-' . str_pad($i, 2, '0', STR_PAD_LEFT);
            $absen = mysql_fetch_array(mysql_query("SELECT kode_kehadiran FROM rb_absensi_guru WHERE nip='$r[nip]' AND tanggal='$tanggal'"));
            $status = $absen ? $absen['kode_kehadiran'] : '-';
            if($status == 'Hadir'){
            echo "<td align='center'>H</td>";
            }else{
            echo "<td align='center'> - </td>";
            }
        }

        echo "</tr>";
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
  @media print {
    .btn, form {
      display: none;
    }

    @page {
      size: A4 landscape;  /* Ensure A4 size in landscape mode */
      margin: 0;            /* Remove default margin */
    }

    /* Scale the content to fit on A4 landscape */
    .print-page {
      transform: scale(0.7);
      transform-origin: top center;  /* Scale from the top-center */
      width: 100%;
      margin: 0;
    }

    .table-responsive {
      width: 100%;
      overflow-x: visible;
    }

    table {
      width: 100%;  /* Ensure table takes up the full page width */
    }

    /* Optional: Remove margin and padding of the page for cleaner printing */
    html, body {
      margin: 0;
      padding: 0;
    }
  }

  .table-responsive {
    overflow-x: auto;
  }
</style>


