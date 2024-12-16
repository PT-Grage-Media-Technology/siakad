<?php
if ($_GET['act'] == '') {
    $tahun = $_GET['tahun'] ?? date('Y');
    $bulan = $_GET['bulan'] ?? date('m');

    // Nama bulan
    $nama_bulan = date("F", mktime(0, 0, 0, $bulan, 10));
    $jumlah_hari = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);

    echo "<div class='col-md-12'>
              <div class='box box-info'>
                <div class='box-header with-border'>
                  <h3 class='box-title'>Rekap Data Absensi Guru - $nama_bulan $tahun</h3>
                  <button onclick='window.print()' class='btn btn-primary btn-sm' style='float: right;'>Print</button>
                </div>
              <div class='box-body'>
                <div class='table-responsive'>
                  <table class='table table-condensed table-bordered table-striped table-hover'>
                    <thead>
                      <tr>
                        <th rowspan='2' style='vertical-align: middle;'>No</th>
                        <th rowspan='2' style='vertical-align: middle;'>NIP</th>
                        <th rowspan='2' style='vertical-align: middle;'>Nama Guru</th>";
                        
    // Header tanggal dinamis
    for ($i = 1; $i <= $jumlah_hari; $i++) {
        echo "<th>$i</th>";
    }

    echo "          <th rowspan='2' style='vertical-align: middle;'>Hadir</th>
                        <th rowspan='2' style='vertical-align: middle;'>Sakit</th>
                        <th rowspan='2' style='vertical-align: middle;'>Izin</th>
                        <th rowspan='2' style='vertical-align: middle;'>Alpa</th>
                      </tr>
                      <tr>";

    // Subheader untuk tanggal
    for ($i = 1; $i <= $jumlah_hari; $i++) {
        echo "<th style='font-size: 10px;'>".date('D', strtotime("$tahun-$bulan-$i"))."</th>";
    }

    echo "          </tr>
                    </thead>
                    <tbody>";

    $no = 1;
    $tampil = mysql_query("SELECT * FROM rb_guru WHERE id_jenis_ptk NOT IN (6, 7) ORDER BY nama_guru ASC");
    while ($r = mysql_fetch_array($tampil)) {
        echo "<tr>
                <td>$no</td>
                <td>$r[nip]</td>
                <td>$r[nama_guru]</td>";

        // Kolom absensi untuk setiap tanggal dalam bulan
        for ($i = 1; $i <= $jumlah_hari; $i++) {
            $tanggal = "$tahun-$bulan-$i";
            $cek_absen = mysql_num_rows(mysql_query("SELECT * FROM rb_absensi_guru WHERE nip='$r[nip]' AND tanggal='$tanggal'"));
            echo "<td align='center'>".($cek_absen > 0 ? '✔' : '')."</td>";
        }

        // Hitung total kehadiran
        $hadir = mysql_num_rows(mysql_query("SELECT * FROM rb_absensi_guru WHERE nip='$r[nip]' AND kode_kehadiran='Hadir' AND MONTH(tanggal)='$bulan' AND YEAR(tanggal)='$tahun'"));
        $sakit = mysql_num_rows(mysql_query("SELECT * FROM rb_absensi_guru WHERE nip='$r[nip]' AND kode_kehadiran='Sakit' AND MONTH(tanggal)='$bulan' AND YEAR(tanggal)='$tahun'"));
        $izin = mysql_num_rows(mysql_query("SELECT * FROM rb_absensi_guru WHERE nip='$r[nip]' AND kode_kehadiran='Izin' AND MONTH(tanggal)='$bulan' AND YEAR(tanggal)='$tahun'"));
        $alpa = mysql_num_rows(mysql_query("SELECT * FROM rb_absensi_guru WHERE nip='$r[nip]' AND kode_kehadiran='Alpa' AND MONTH(tanggal)='$bulan' AND YEAR(tanggal)='$tahun'"));

        echo "  <td align='center'>$hadir</td>
                <td align='center'>$sakit</td>
                <td align='center'>$izin</td>
                <td align='center'>$alpa</td>
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

  @media print {
    .btn {
      display: none;
    }
  }

  th, td {
    text-align: center;
    vertical-align: middle;
  }
</style>
