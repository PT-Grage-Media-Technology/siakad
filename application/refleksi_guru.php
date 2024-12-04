<?php
if ($_GET['act'] == '') {
    $d = mysql_fetch_array(mysql_query("SELECT * FROM rb_kelas WHERE kode_kelas='$_GET[id]'"));
    $m = mysql_fetch_array(mysql_query("SELECT * FROM rb_mata_pelajaran WHERE kode_pelajaran='$_GET[kd]'"));
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
                        <th>NIP</th>
                        <th>Nama Guru</th>
                        <th>Rating</th>
                      </tr>
                    </thead>
                    <tbody>";

    $no = 1;
    // Query untuk mendapatkan data guru yang memiliki rating di rb_pertanyaan_penilaian_jawab
    $tampil = mysql_query("SELECT g.nip, g.nama_guru, AVG(p.jawaban) as rata_rata
                           FROM rb_guru g
                           JOIN rb_pertanyaan_penilaian_jawab p ON g.nip = p.nip_guru
                           WHERE g.id_jenis_ptk NOT IN (6, 7)
                           GROUP BY g.nip
                           ORDER BY g.nama_guru ASC");
    while ($r = mysql_fetch_array($tampil)) {
        $rating = number_format($r['rata_rata'], 2); // Format angka menjadi 2 desimal
        echo "<tr>
                <td>$no</td>
                <td>$r[nip]</td>
                <td>$r[nama_guru]</td>
                <td>$rating</td>
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
