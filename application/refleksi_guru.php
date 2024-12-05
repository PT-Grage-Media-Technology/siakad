<?php
if ($_GET['act'] == '') {
  $d = mysql_fetch_array(mysql_query("SELECT * FROM rb_kelas where kode_kelas='$_GET[id]'"));
  $m = mysql_fetch_array(mysql_query("SELECT * FROM rb_mata_pelajaran where kode_pelajaran='$_GET[kd]'"));
  echo "<div class='col-md-12'>
              <div class='box box-info'>
                <div class='box-header with-border'>
                  <h3 class='box-title'>Hasil Refleksi Guru $_GET[tahun]</h3>
                </div>
              <div class='box-body'>

              <div class='col-md-12'></div>

              <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
                <div class='col-md-12'>
                  <table class='table table-condensed table-bordered table-striped'>
                      <thead>
                      <tr>
                        <th>No</th>
                        <th>Nip</th>
                        <th>Nama Guru</th>";

  // Simpan data rating ke dalam array untuk digunakan kembali
  $rating_query = mysql_query("SELECT * FROM rb_rating ORDER BY id");
  while ($rating = mysql_fetch_array($rating_query)) {
    echo "<th>" . $rating["kesan"] . "</th>";
    $ratingArray[] = $rating['id']; // Simpan ID rating
  }

  echo "
                      </tr>
                    </thead>
                    <tbody>";

  // Loop data guru dan tampilkan data berdasarkan ratingArray
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

    // Loop untuk menampilkan jawaban berdasarkan ratingArray
    foreach ($ratingArray as $ratingID) {
      // Ambil jawaban berdasarkan nip dan id rating
      $jawaban_query = mysql_query("SELECT * FROM rb_pertanyaan_penilaian_jawab WHERE nip='$r[nip]' AND id_rating='$ratingID'");
      $jawaban = mysql_fetch_array($jawaban_query);
      echo "<td>" . ($jawaban ? count($jawaban['jawaban']) : '-') . "</td>"; // Tampilkan jawaban atau '-' jika tidak ada  echo
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
