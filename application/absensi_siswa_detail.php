<?php
if ($_GET['act'] == '') {
    $d = mysql_fetch_array(mysql_query("SELECT * FROM rb_kelas where kode_kelas='$_GET[id]'"));
    $m = mysql_fetch_array(mysql_query("SELECT * FROM rb_mata_pelajaran where kode_pelajaran='$_GET[kd]'"));
    echo "<div class='col-md-12'>
              <div class='box box-info'>
                <div class='box-header with-border'>
                  <h3 class='box-title'>Rekap Data Absensi Guru $_GET[tahun]</h3>
                  <button onclick='window.print()' class='btn btn-primary btn-sm' style='float: right;'>Print</button>
                </div>
              <div class='box-body'>

              <div class='col-md-12'>
              
              </div>

              <div class='col-md-12'>
                <div class='table-responsive'>
                  <table class='table table-condensed table-bordered table-striped table-hover'>
                    <thead>
                      <tr>
                        <th>No</th>
                        <th>NIP</th>
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
        $hadir = mysql_num_rows(mysql_query("SELECT * FROM `rb_absensi_guru` where nip='$r[nip]' AND kode_kehadiran='Hadir'"));
        $sakit = mysql_num_rows(mysql_query("SELECT * FROM `rb_rekap_absen_guru` where nip='$r[nip]' AND kode_kehadiran='sakit'"));
        $izin = mysql_num_rows(mysql_query("SELECT * FROM `rb_rekap_absen_guru` where nip='$r[nip]' AND kode_kehadiran='izin'"));
        $alpa = mysql_num_rows(mysql_query("SELECT * FROM `rb_rekap_absen_guru` where nip='$r[nip]' AND kode_kehadiran='alpa'"));

        echo "<tr>
                            <td>$no</td>
                            <td>$r[nip]</td>
                            <td>$r[nama_guru]</td>
                            <td align=center>$hadir</td>
                            <td align=center>$sakit</td>
                            <td align=center>$izin</td>
                            <td align=center>$alpa</td>";
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
  .table-responsive {
    overflow-x: auto;
    /* Hanya aktifkan scroll horizontal jika diperlukan */
  }

  @media (min-width: 768px) {
    .table-responsive {
      overflow-x: visible;
      /* Nonaktifkan scroll horizontal di desktop */
    }
  }

  @media print {
    .btn {
      display: none;
      /* Sembunyikan tombol saat dicetak */
    }
  }
</style>
