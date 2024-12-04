<?php
if ($_GET[act] == '') {
    $d = mysql_fetch_array(mysql_query("SELECT * FROM rb_kelas where kode_kelas='$_GET[id]'"));
    $m = mysql_fetch_array(mysql_query("SELECT * FROM rb_mata_pelajaran where kode_pelajaran='$_GET[kd]'"));
    echo "<div class='col-md-12'>
              <div class='box box-info'>
                <div class='box-header with-border'>
                  <h3 class='box-title'>Hasil Refleksi Guru $_GET[tahun]</b></h3>
                </div>
              <div class='box-body'>

              <div class='col-md-12'>
              
              </div>

              <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
                <div class='col-md-12'>
                  <table class='table table-condensed table-bordered table-striped'>
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
      // var_dump($r);
        // $total = mysql_num_rows(mysql_query("SELECT * FROM `rb_absensi_siswa` where kodejdwl='$_GET[jdwl]' GROUP BY tanggal"));
        $hadir = mysql_num_rows(mysql_query("SELECT * FROM `rb_absensi_guru` where nip='$r[nip]' AND kode_kehadiran='Hadir'"));
        $sakit = mysql_num_rows(mysql_query("SELECT * FROM `rb_rekap_absen_guru` where nip='$r[nip]' AND kode_kehadiran='sakit'"));
        $izin = mysql_num_rows(mysql_query("SELECT * FROM `rb_rekap_absen_guru` where nip='$r[nip]' AND kode_kehadiran='izin'"));
        $alpa = mysql_num_rows(mysql_query("SELECT * FROM `rb_rekap_absen_guru` where nip='$r[nip]' AND kode_kehadiran='alpa'"));
        // $persen = $hadir / ($total) * 100;
        // <th><center>% Kehadiran</center></th>

        // <td align=right>" . number_format($persen, 2) . " %</td>";

        // var_dump($hadir);
        echo "<tr bgcolor=$warna>
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
