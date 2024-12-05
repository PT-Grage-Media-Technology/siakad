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

  // Ambil semua kesan dari tabel rb_rating
  $rating_query = mysql_query("SELECT kesan FROM rb_rating ORDER BY id");
  $kesan_list = [];
  while ($rating = mysql_fetch_array($rating_query)) {
    echo "<th>" . $rating["kesan"] . "</th>";
    $kesan_list[] = $rating["kesan"]; // Simpan kesan ke dalam array
  }

  echo "</tr>
                    </thead>
                    <tbody>";

  // Query untuk mendapatkan data guru
  $no = 1;
  $tampil = mysql_query("
        SELECT g.nip, g.nama_guru 
        FROM rb_guru g 
        WHERE g.id_jenis_ptk NOT IN (6, 7) 
        ORDER BY g.nama_guru ASC
    ");

  while ($r = mysql_fetch_array($tampil)) {
    echo "<tr>
                <td>$no</td>
                <td>$r[nip]</td>
                <td>$r[nama_guru]</td>";

    // Hitung jumlah jawaban sesuai dengan setiap kesan untuk guru ini
    foreach ($kesan_list as $kesan) {
      $count_query = mysql_query("
          SELECT COUNT(*) as jumlah 
          FROM rb_pertanyaan_penilaian_jawab 
          WHERE nip='$r[nip]' AND jawaban='$kesan'
      ");
      $count_result = mysql_fetch_array($count_query);
      $jumlah = $count_result['jumlah'];

      echo "<td>$jumlah</td>";
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
