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
    $tampil = mysql_query("SELECT * FROM rb_guru WHERE id_jenis_ptk NOT IN (6, 7) ORDER BY nama_guru ASC");
    while ($r = mysql_fetch_array($tampil)) {
        // Mengambil rating dari rb_pertanyaan_penilaian_jawab berdasarkan NIP guru
        $rating_query = mysql_query("SELECT AVG(jawaban) as rata_rata 
                                     FROM rb_pertanyaan_penilaian_jawab 
                                     WHERE nip_guru = '$r[nip]'");
        $rating_data = mysql_fetch_array($rating_query);
        $rating = number_format($rating_data['rata_rata'], 2); // Format angka menjadi 2 desimal

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
