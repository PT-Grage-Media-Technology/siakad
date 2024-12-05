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
                        <th>Nama Guru</th>";
  $rating_query = mysql_query("SELECT kesan FROM rb_rating ORDER BY id");
  while ($rating = mysql_fetch_array($rating_query)) {
    echo "<th>" . $rating["kesan"] . " , " . $rating["id"] . "</th>";
  }
  echo "
                      </tr>
                    </thead>
                    <tbody>";

  // $no = 1;
  // $tampil = mysql_query("SELECT * FROM rb_guru WHERE id_jenis_ptk NOT IN (6, 7) ORDER BY nama_guru ASC");
  // while ($r = mysql_fetch_array($tampil)) {
  // var_dump($r);
  // $total = mysql_num_rows(mysql_query("SELECT * FROM `rb_absensi_siswa` where kodejdwl='$_GET[jdwl]' GROUP BY tanggal"));
  // $hadir = mysql_num_rows(mysql_query("SELECT * FROM `rb_absensi_guru` where nip='$r[nip]' AND kode_kehadiran='Hadir'"));
  // $sakit = mysql_num_rows(mysql_query("SELECT * FROM `rb_rekap_absen_guru` where nip='$r[nip]' AND kode_kehadiran='sakit'"));
  // $izin = mysql_num_rows(mysql_query("SELECT * FROM `rb_rekap_absen_guru` where nip='$r[nip]' AND kode_kehadiran='izin'"));
  // $alpa = mysql_num_rows(mysql_query("SELECT * FROM `rb_rekap_absen_guru` where nip='$r[nip]' AND kode_kehadiran='alpa'"));
  // $persen = $hadir / ($total) * 100;
  // <th><center>% Kehadiran</center></th>

  // <td align=right>" . number_format($persen, 2) . " %</td>";

  // var_dump($hadir);

  //     echo "<tr bgcolor=$warna>
  //                         <td>$no</td>
  //                         <td>$r[nip]</td>
  //                         <td>$r[nama_guru]</td>
  //                         <td></td>
  //                         ";
  //     echo "</tr>";
  //     $no++;
  // }


  $no = 1;
  $tampil = mysql_query("
        SELECT g.nip, g.nama_guru, p.jawaban 
        FROM rb_guru g 
        INNER JOIN rb_pertanyaan_penilaian_jawab p ON g.nip = p.nip 
        WHERE g.id_jenis_ptk NOT IN (6, 7) 
        GROUP BY g.nip 
        ORDER BY g.nama_guru ASC
    ");

  while ($r = mysql_fetch_array($tampil)) {
    echo "<tr>
                <td>$no</td>
                <td>$r[nip]</td>
                <td>$r[nama_guru]</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
              </tr>";
    $no++;
  }



  echo "</tbody>
                  </table>
                </div>
              </div>
            </div>";
}
