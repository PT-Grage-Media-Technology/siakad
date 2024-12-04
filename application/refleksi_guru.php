<?php
if ($_GET['act'] == '') {
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
                        <th>Nama Guru</th>
                        <th>Rating</th>
                      </tr>
                    </thead>
                    <tbody>";

    $no = 1;
    $tampil = mysql_query("SELECT * FROM rb_guru WHERE id_jenis_ptk NOT IN (6, 7) ORDER BY nama_guru ASC");
    while ($r = mysql_fetch_array($tampil)) {
        // Mengambil data rating dari rb_pertanyaan_penilaian_jawab berdasarkan nip
        $rating_query = mysql_query("SELECT jawaban FROM rb_pertanyaan_penilaian_jawab WHERE nip = '$r[nip]'");
        $rating_data = [];
        while ($rating_row = mysql_fetch_array($rating_query)) {
            $rating_data[] = $rating_row['jawaban'];
        }

        // Menghitung rata-rata rating
        $rating_avg = !empty($rating_data) ? round(array_sum($rating_data) / count($rating_data), 2) : 'N/A';

        echo "<tr>
                <td>$no</td>
                <td>$r[nip]</td>
                <td>$r[nama_guru]</td>
                <td align='center'>$rating_avg</td>
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
